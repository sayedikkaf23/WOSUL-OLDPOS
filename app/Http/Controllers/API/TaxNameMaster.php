<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Taxcode as TaxcodeModel;

use App\Http\Resources\Collections\TaxcodeCollection;
use App\Http\Resources\TaxNameResource;
use App\Models\TaxName;

class TaxNameMaster extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_TAXNAME_LISTING';
            if(check_access(array($data['action_key']), true) == false){
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;
            
            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            
            $query = TaxName::select('tax_names.*',  'user_created.fullname')
                            // 'master_status.label as status_label', 'master_status.color as status_color',
            ->take($limit)
            ->skip($offset)
            // ->statusJoin()
            ->createdUser()
            ->orderBy('created_at', 'desc')
            ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                $query->orderBy($order_by_column, $order_direction);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                $query->where(function ($query) use ($filter_string, $filter_columns){
                    foreach($filter_columns as $filter_column){
                        $query->orWhere($filter_column, 'like', '%'.$filter_string.'%');
                    }
                });
            })

            ->get();

            $tax_codes = TaxNameResource::collection($query);
            
            $total_count = TaxName::select("id")->get()->count();
            //dd(count($query), $total_count);

            $item_array = [];
            foreach($tax_codes as $key => $tax_code){

                $tax_code = $tax_code->toArray($request);

                $item_array[$key][] = $tax_code['tax_name'];
                $item_array[$key][] = $tax_code['percentage'];
                // $item_array[$key][] = (isset($tax_code['status']['label']))?view('common.status', ['status_data' => ['label' => $tax_code['status']['label'], "color" => $tax_code['status']['color']]])->render():'-';
                $item_array[$key][] = ($tax_code['is_default'] == '1')?
                    view('common.status', ['status_data' => ['label' => 'Default Tax', "color" => 'text-warning']])->render():'--';
                $item_array[$key][] = $tax_code['created_at_label'];
                $item_array[$key][] = $tax_code['updated_at_label'];
                $item_array[$key][] = (isset($tax_code['created_by']) && isset($tax_code['created_by']['fullname']))?$tax_code['created_by']['fullname']:'-';
                $item_array[$key][] = view('tax_name_master.layouts.tax_name_actions', array('tax_code' => $tax_code))->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];
            
            return response()->json($response);
        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if(!check_access(['A_ADD_TAXNAME'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $taxname_exists = TaxName::select('id')->where('tax_name', 'LIKE', $request->tax_name)
                                    ->where('percentage', '=', $request->percentage)->first();
            if (!empty($taxname_exists)) {
                throw new Exception(trans("Tax percentage with this name is already exists"), 400);
            }
            
            $taxNameDetails = [
                "tax_name" => ucfirst($request->tax_name),
                "percentage" => $request->percentage,
                "is_visible" => ($request->is_visible == 'true') ? 1 : 0,
                // "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "updated_by" => $request->logged_user_id
            ];
            
            $status = TaxName::create($taxNameDetails)->id;

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Tax Name Created Successfully"), 
                    "data"    => $status,
                    "link"    => route('tax_names'),
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    { 
        try {

            if(!check_access(['A_DETAIL_TAXNAME'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = TaxName::select('*')
            ->where('id', $slack)
            ->first();

            $item_data = new TaxNameResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Tax name loaded successfully", 
                    "data"    => $item_data
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }  
    }

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if(!check_access(['A_VIEW_TAXNAME_LISTING'], true)){
                throw new Exception("Invalid request", 400);
            }

            $list = new TaxcodeCollection(TaxcodeModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Tax name loaded successfully", 
                    "data"    => $list
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        try {

            if(!check_access(['A_EDIT_TAXNAME'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $taxname_exists = TaxName::select('id')->where('tax_name', 'LIKE', $request->tax_name)
                                    ->where('percentage', '=', $request->percentage)
                                    ->where('id','!=',$slack)->first();
            if (!empty($taxname_exists)) {
                throw new Exception(trans("Tax percentage with this name is already exists"), 400);
            }
            
            $taxNameDetails = [
                "tax_name" => ucfirst($request->tax_name),
                "percentage" => $request->percentage,
                "is_visible" => ($request->is_visible == 'true') ? 1 : 0,
                // "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];
            
            $action_response = TaxName::where('id', $slack)->update($taxNameDetails);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Tax name Updated Successfully"), 
                    "data"    => $slack
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'tax_name' => $this->get_validation_rules("string", true),
            'percentage' => $this->get_validation_rules("numeric", true),
            // 'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
