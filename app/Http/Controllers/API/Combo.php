<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ComboCollection;
use App\Http\Resources\ComboResource;
use App\Models\Combo as ComboModel;
use App\Models\ComboSize as ComboSizeModel;
use App\Models\ComboProduct as ComboProductModel;
use Illuminate\Support\Str;

class Combo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_COMBO_LISTING';
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
            
            $query =  ComboModel::select('combos.*', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
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

            $total_count = $query->count();
            $combos = ComboResource::collection($query);
            
            $item_array = [];
            foreach($combos as $key => $combo){

                $combo = $combo->toArray($request);
                $item_array[$key][] = $combo['name'];
                $item_array[$key][] = $combo['category']['label'];
                
                // available sizes
                $sizes = '';
                foreach($combo['sizes'] as $size){
                    $sizes .= $size->size_name." | ";
                }
                $item_array[$key][] = substr($sizes,0,-2);
                $item_array[$key][] = count($combo['products']);

                // discount
                if($combo['is_discount_enabled']){
                    if($combo['discount_type'] == 'AMOUNT'){
                        $item_array[$key][] = 'SAR '.$combo['discount_value'];
                    }else if($combo['discount_type'] == 'PERCENTAGE'){
                        $item_array[$key][] = $combo['discount_value'].'%';
                    }
                }else{
                    $item_array[$key][] = '-';
                }
                $item_array[$key][] = (isset($combo['status']['label'])) ? view('common.status', ['status_data' => ['label' => $combo['status']['label'], "color" => $combo['status']['color']]])->render() : '-';
                
                $item_array[$key][] = $combo['created_at_label'];
                $item_array[$key][] = $combo['updated_at_label'];
                $item_array[$key][] = (isset($combo['created_by']) && isset($combo['created_by']['fullname']))?$combo['created_by']['fullname']:'-';
                $item_array[$key][] = view('combo.layouts.combo_actions', array('combo' => $combo))->render();
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

            if(!check_access(['A_ADD_COMBO'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $combo = ComboModel::select('id')
            ->where('name', '=', trim($request->combo_name))
            ->where('category_id', '=', trim($request->category_id))
            ->first();

            if (!empty($combo)) {
                throw new Exception(trans("Combo already exists"), 400);
            }

            DB::beginTransaction();

            $combo = ComboModel::create([
                "slack" => $this->generate_slack("combos"),
                "store_id" => $request->logged_user_store_id,
                "category_id" => $request->category_id,
                "name" => $request->combo_name,
                "is_discount_enabled" => ($request->is_discount_enabled == "true") ? 1 : 0,
                "discount_type" => $request->discount_type,
                "discount_value" => $request->discount_value,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ]);

            $combo_sizes = json_decode($request->combo_sizes,true);

            if(isset($combo_sizes)){
                foreach($combo_sizes as $combo_size){
                    
                    if(isset($combo_size['items'])){

                        $combo_size_data = ComboSizeModel::create([
                            "slack" => $this->generate_slack("combo_sizes"),
                            'combo_id' => $combo->id,
                            'size_name' => Str::upper($combo_size['size_name']),
                        ]);
                        
                        foreach($combo_size['items'] as $combo_item){
                            
                            if($request->is_discount_enabled == "true"){
                                if($request->discount_type == 'AMOUNT'){
                                    $price_after_discount = $combo_item['product_price'] - $request->discount_value;
                                }else{
                                    $price_after_discount = $combo_item['product_price'] - ($combo_item['product_price'] * $request->discount_value / 100);
                                }
                            }else{
                                $price_after_discount = $combo_item['product_price'];
                            }

                            $combo_product = [
                                "slack" => $this->generate_slack("combo_products"),
                                'combo_id' => $combo->id,
                                'combo_size_id' => $combo_size_data->id,
                                'combo_group_id' => ( isset($combo_item['subgroup']) && $combo_item['subgroup'] != null) ? $combo_item['subgroup'] : $combo_item['group'],
                                'product_id' => $combo_item['product']['id'],
                                'measurement_id' => $combo_item['product_measurement'],
                                'quantity' => $combo_item['product_quantity'],
                                'price' => $combo_item['product_price'],
                                'price_after_discount' => $price_after_discount
                            ];
                            ComboProductModel::create($combo_product);

                        } 

                    } 

                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Combo created successfully"), 
                    "data"    => $combo,
                    "link"    => route('combos')
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

            if(!check_access(['A_EDIT_COMBO'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $combo_data_exists = ComboModel::select('id')
            ->where('slack', '!=', $slack)
            ->where('name', '=', trim($request->name))
            ->where('category_id', '=', trim($request->category_id))
            ->first();

            if (!empty($combo_data_exists)) {
                throw new Exception(trans("Combo already exists"), 400);
            }

            $combo_data = ComboModel::where('slack',$slack)->first();

            DB::beginTransaction();
            
            $combo = ComboModel::where('slack',$slack)->update([
                "category_id" => $request->category_id,
                "name" => $request->combo_name,
                "is_discount_enabled" => ( $request->is_discount_enabled || $request->is_discount_enabled == "true"  ) ? 1 : 0,
                "discount_type" => $request->discount_type,
                "discount_value" => $request->discount_value,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ]);

            // deleting existing entries and add new entries
            ComboSizeModel::where('combo_id',$combo_data->id)->delete();
            ComboProductModel::where('combo_id',$combo_data->id)->delete();

            $combo_sizes = json_decode($request->combo_sizes,true);
            
            if(isset($combo_sizes)){
                foreach($combo_sizes as $combo_size){
                    
                    if(isset($combo_size['items'])){

                        $combo_size_data = ComboSizeModel::create([
                            "slack" => $this->generate_slack("combo_sizes"),
                            'combo_id' => $combo_data->id,
                            'size_name' => Str::upper($combo_size['size_name']),
                        ]);
                        
                        foreach($combo_size['items'] as $combo_item){
                            
                            if( $request->is_discount_enabled || $request->is_discount_enabled == "true"){
                                if($request->discount_type == 'AMOUNT'){
                                    $price_after_discount = $combo_item['product_price'] - $request->discount_value;
                                }else{
                                    $price_after_discount = $combo_item['product_price'] - ($combo_item['product_price'] * $request->discount_value / 100);
                                }
                            }else{
                                $price_after_discount = $combo_item['product_price'];
                            }
                            
                            ComboProductModel::create([
                                "slack" => $this->generate_slack("combo_products"),
                                'combo_id' => $combo_data->id,
                                'combo_size_id' => $combo_size_data->id,
                                'combo_group_id' => ( isset($combo_item['subgroup']) && $combo_item['subgroup'] != null) ? $combo_item['subgroup'] : $combo_item['group'],
                                'product_id' => $combo_item['product']['id'],
                                'measurement_id' => $combo_item['product_measurement'],
                                'quantity' => $combo_item['product_quantity'],
                                'price' => $combo_item['product_price'],
                                'price_after_discount' => $price_after_discount,
                            ]);
    
                        } 

                    }

                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Combo updated successfully"), 
                    "data"    => $combo,
                    "link"    => route('combos')
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
    public function list()
    {
        try {

            if (!check_access(['A_VIEW_COMBO_LISTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new ComboCollection(ComboModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            $list = $list->toArray($list);
            
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Combos loaded successfully"),
                    "data"    => $list
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
            'combo_name' => $this->get_validation_rules("name_label", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
