<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Price as PriceModel;

use App\Http\Resources\PriceResource;
use App\Models\Store;
use Illuminate\Support\Str;

class Price extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_PRICE_LISTING';
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
            
            $query =  PriceModel::select('prices.*', 'master_status.label as status_label', 'master_status.color as status_color')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()

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

            $prices = PriceResource::collection($query);
            

            $total_count = $query->count();

            $item_array = [];
            foreach($prices as $key => $price){
                
                $price = $price->toArray($request);

                $item_array[$key][] = $price['name'];
                $item_array[$key][] = $price['name_ar'];
                $item_array[$key][] = (isset($price['status']['label']))?view('common.status', ['status_data' => ['label' => $price['status']['label'], "color" => $price['status']['color']]])->render():'-';
                $item_array[$key][] = $price['created_at_label'];
                $item_array[$key][] = $price['updated_at_label'];
                $item_array[$key][] = view('price.layouts.price_actions', ['price' => $price])->render();
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

     public function store(Request $request)
    {   
        try {

            if(!check_access(['A_ADD_PRICE'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $price_data_exists = PriceModel::select('id')
            ->where('name', '=', trim($request->name))
            ->orWhere('name_ar', '=', trim($request->name_ar))
            ->active()
            ->first();
            
            if (!empty($price_data_exists)) {
                throw new Exception(trans("Price already exists"), 400);
            }

            DB::beginTransaction();

            $selected_stores = json_decode($request->selected_stores,true);
            
            $data = [];

            if(isset($selected_stores) && count($selected_stores) > 0){
                
                foreach($selected_stores as $selected_store){
                    
                    $store = Store::where('slack',$selected_store['slack'])->first();
                
                    $dataset = [
                        "slack" => $this->generate_slack("prices"),
                        "store_id" => $store->id,
                        "name" => $request->name,
                        "name_ar" => $request->name_ar,
                        "price_code" => Str::upper(str_replace(' ','_',$request->name)),
                    ];
                    PriceModel::create($dataset);
                    $data[] = $dataset;
                
                }
    
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Price created successfully"), 
                    "data"    => $data
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

            if(!check_access(['A_EDIT_PRICE'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $price = [
                "name" => $request->name,
                "name_ar" => $request->name_ar,
                "status" => $request->status
            ];

            $action_response = PriceModel::where('slack', $slack)->where('store_id',$request->logged_user_store_id)
            ->update($price);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Price updated successfully"), 
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
    
    
    public function update_price_id(Request $request)
    {
        try {

            DB::beginTransaction();
            
            $price_id = ($request->price_id == 0) ? null : $request->price_id;
            Store::where('id',$request->logged_user_store_id)->update(['price_id'=>$price_id]);
            
            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Price Id updated successfully"), 
                    "data"    => []
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
    
    public function get_status()
    {
        $store = Store::where('id',request()->logged_user_store_id)->select('is_price_enabled')->first();
        return response()->json(['is_price_enabled'=>$store->is_price_enabled]);
    }
    
    public function update_status(Request $request)
    {
        
        if($request->status){
            $update = [
                'is_price_enabled'=>$request->status
            ];
        }else{
            $update = [
                'is_price_enabled'=>$request->status,
                'price_id'=>''
            ];
        }

        Store::where('id',request()->logged_user_store_id)->update($update);
        return response()->json(['is_enabled_status'=>$request->status]);
    }

    public function list(Request $request){
        try {

            $updated_at = (isset($request->updated_at) && $request->updated_at != '') ? $request->updated_at : '';
            
            $price_list = PriceModel::active()->when( ($updated_at != '' ), function($query) use($updated_at) {
                $query->where('updated_at','>=',$updated_at);
            })->get();
            $prices = PriceResource::collection($price_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Prices loaded successfully",
                    "data"    => $prices
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => $this->get_validation_rules("name_label", true),
            'name_ar' => $this->get_validation_rules("name_label", true),
            // 'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

   
   
}
