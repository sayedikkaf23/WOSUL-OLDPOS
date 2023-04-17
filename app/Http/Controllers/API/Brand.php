<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\BrandResource;

use App\Models\Brand as BrandModel;

use App\Http\Resources\Collections\BrandCollection;

class Brand extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_BRAND';
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
            
            $query = BrandModel::select('brands.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()
            ->createdUser()

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

            $brands = BrandResource::collection($query);
           
            $total_count = BrandModel::select("id")->get()->count();

            $item_array = [];
            foreach($brands as $key => $brand){
                
                $brand = $brand->toArray($request);
                $item_array[$key][] = $brand['label'];
                $item_array[$key][] = (isset($brand['status']['label']))?view('common.status', ['status_data' => ['label' => $brand['status']['label'], "color" => $brand['status']['color']]])->render():'-';
                $item_array[$key][] = $brand['created_at_label'];
                $item_array[$key][] = $brand['updated_at_label'];
                $item_array[$key][] = (isset($brand['created_by']['fullname']))?$brand['created_by']['fullname']:'-';
                $item_array[$key][] = view('brand.layouts.brand_actions', ['brand' => $brand])->render();
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

            if(!check_access(['A_ADD_BRAND'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $brand_data_exists = BrandModel::select('id')
            ->where('label', '=', trim($request->label))
            ->first();
            if (!empty($brand_data_exists)) {
                throw new Exception(trans("Brand already exists"), 400);
            }

            DB::beginTransaction();
            
            $brand = [
                "slack" => $this->generate_slack("brands"),
                "label" => $request->label,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];
            
            $brand_id = BrandModel::create($brand)->id;

            DB::commit();

            $data['brands'] = BrandModel::select('slack','label')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Brand created successfully"), 
                    "data"    => $data['brands']
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

            if(!check_access(['A_DETAIL_BRAND'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = BrandModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new BrandResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Brand loaded successfully"), 
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

            if(!check_access(['A_VIEW_BRAND_LISTING'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new BrandCollection(BrandModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Brands loaded successfully"), 
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

            if(!check_access(['A_EDIT_BRAND'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $brand_exists = BrandModel::where('label',$request->label)->where('slack','!=',$slack)->first();
            if (!empty($brand_exists)) {
                throw new Exception(trans("Brand already exists"), 400);
            }

            DB::beginTransaction();

            $brand = [
                "label" => $request->label,
                "status" => $request->status,
                'updated_by' => $request->logged_user_id
            ];

            $action_response = BrandModel::where('slack', $slack)
            ->update($brand);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Brand updated successfully"), 
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
            'label' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
