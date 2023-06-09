<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\ProductIngredient;
use Exception;
use phpseclib3\Crypt\EC\Formats\Keys\Common;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\StockTransferResource;

use App\Models\StockTransfer as StockTransferModel;
use App\Models\StockTransferProduct as StockTransferProductModel;
use App\Models\Product as ProductModel;
use App\Models\Store as StoreModel;
use App\Models\MasterStatus as MasterStatusModel;

use App\Http\Resources\Collections\StockTransferCollection;
use App\Models\Taxcode;
use Illuminate\Support\Carbon;
use App\Http\Traits\CommonApiTrait;
use App\Http\Traits\QoyodApiTrait;

class StockTransfer extends Controller
{
    use CommonApiTrait,QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        try {

            $data['action_key'] = 'A_VIEW_STOCK_TRANSFER_LISTING';
            if(check_access(array($data['action_key']), true) == false){
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $current_selected_store = $request->logged_user_store_id;
            $current_selected_store_slack = $request->logged_user_store_slack;

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;
            
            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            
            $query = StockTransferModel::select('stock_transfer.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()
            ->createdUser()
            ->resolveStore($current_selected_store)

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

            $stock_transfers = StockTransferResource::collection($query);
           
            $total_count = StockTransferModel::select("id")->resolveStore($current_selected_store)->get()->count();

            $item_array = [];
            foreach($stock_transfers as $key => $stock_transfer){
                
                $stock_transfer = $stock_transfer->toArray($request);

                $item_array[$key][] = $stock_transfer['stock_transfer_reference'];
                $item_array[$key][] = $stock_transfer['from_store_code'];
                $item_array[$key][] = $stock_transfer['from_store_name'];
                $item_array[$key][] = $stock_transfer['to_store_code'];
                $item_array[$key][] = $stock_transfer['to_store_name'];
                $item_array[$key][] = (isset($stock_transfer['status']['label']))?view('common.status', ['status_data' => ['label' => $stock_transfer['status']['label'], "color" => $stock_transfer['status']['color']]])->render():'-';
                $item_array[$key][] = $stock_transfer['created_at_label'];
                $item_array[$key][] = $stock_transfer['updated_at_label'];
                $item_array[$key][] = (isset($stock_transfer['created_by']['fullname']))?$stock_transfer['created_by']['fullname']:'-';
                $item_array[$key][] = view('stock_transfer.layouts.stock_transfer_actions', ['stock_transfer' => $stock_transfer, 'current_selected_store_slack' => $current_selected_store_slack])->render();
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

            if(!check_access(['A_ADD_STOCK_TRANSFER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();
            
            $stock_transfer_data = $this->form_stock_transfer_array($request);

            if(!empty($stock_transfer_data['stock_transfer_data'])){
                
                $stock_transfer = $stock_transfer_data['stock_transfer_data'];
                
                $stock_transfer['slack'] = $this->generate_slack("stock_transfer");
                $stock_transfer['stock_transfer_reference'] = Str::random(6);
                $stock_transfer['created_at'] = now();
                $stock_transfer['created_by'] = $request->logged_user_id;

                $stock_transfer_id = StockTransferModel::create($stock_transfer)->id;

                $code_start_config = Config::get('constants.unique_code_start.stock_transfer');
                $code_start = (isset($code_start_config))?$code_start_config:100;
                
                $stock_transfer_reference_data = [
                    "stock_transfer_reference" => ($code_start+$stock_transfer_id)
                ];
                StockTransferModel::where('id', $stock_transfer_id)
                ->update($stock_transfer_reference_data);
            }
            
            if(!empty($stock_transfer_data['stock_transfer_products'])){
                
                $stock_transfer_products = $stock_transfer_data['stock_transfer_products'];

                array_walk($stock_transfer_products, function (&$item, $key) use ($stock_transfer_id, $request){
                    
                    $item['slack'] = $this->generate_slack("stock_transfer_products");
                    $item['stock_transfer_id'] = $stock_transfer_id; 
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;

                    StockTransferProductModel::insert($item);

                });
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock transfer created successfully"), 
                    "data"    => $stock_transfer['slack']
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

            if(!check_access(['A_DETAIL_STOCK_TRANSFER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = StockTransferModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new StockTransferResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock Transfer loaded successfully"), 
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

            if(!check_access(['A_VIEW_STOCK_TRANSFER_LISTING'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new StockTransferCollection(StockTransferModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock Transfer loaded successfully"), 
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

            if(!check_access(['A_EDIT_STOCK_TRANSFER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $current_selected_store = $request->logged_user_store_id;

            $stock_transfer_details = StockTransferModel::where('slack', $slack)->resolveStore($current_selected_store)->first();

            if (empty($stock_transfer_details)) {
                throw new Exception(trans("Invalid stock transfer selected"), 400);
            }

            if($current_selected_store != $stock_transfer_details->from_store_id){
                throw new Exception(trans("Invalid, can be edited only from source store"), 400);
            }

            $stock_transfer_status = MasterStatusModel::select('value_constant')->where([
                ['value', '=', $stock_transfer_details->status],
                ['key', '=', 'STOCK_TRANSFER_STATUS']
            ])->active()->first();
            if(empty($stock_transfer_status)){
                throw new Exception(trans("Invalid status provided"));
            }
            if($stock_transfer_status->value_constant != 'PENDING'){
                throw new Exception(trans("Unable to edit, Destination store has already started verifying the stock transfer"));
            }

            DB::beginTransaction();
            
            $stock_transfer_data = $this->form_stock_transfer_array($request);
            
            if(!empty($stock_transfer_data['stock_transfer_data'])){
                
                $stock_transfer = $stock_transfer_data['stock_transfer_data'];
            
                $stock_transfer['updated_at'] = now();
                $stock_transfer['updated_by'] = $request->logged_user_id;

                $action_response =  StockTransferModel::where('slack', $slack)
                ->update($stock_transfer);
            }

            $stock_transfer_id = $stock_transfer_details->id;

            if(!empty($stock_transfer_data['stock_transfer_products'])){

                if(count($stock_transfer_data['stock_transfer_products']) > 0){
                    StockTransferProductModel::where('stock_transfer_id', $stock_transfer_id)->delete();
                }
                
                $stock_transfer_products = $stock_transfer_data['stock_transfer_products'];

                array_walk($stock_transfer_products, function (&$item, $key) use ($stock_transfer_id, $request){
                    
                    $item['slack'] = $this->generate_slack("stock_transfer_products");
                    $item['stock_transfer_id'] = $stock_transfer_id; 
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;

                    StockTransferProductModel::insert($item);

                });
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock transfer updated successfully"), 
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
    public function destroy($slack)
    {
        try{

            if(!check_access(['A_DELETE_STOCK_TRANSFER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $current_selected_store = request()->logged_user_store_id;

            $stock_transfer_details = StockTransferModel::select('id')->where('slack', $slack)->resolveStore($current_selected_store)->isDeletable()->first();
            if (empty($stock_transfer_details)) {
                throw new Exception(trans("Invalid stock transfer selected"), 400);
            }

            $stock_transfer_id = $stock_transfer_details->id;

            DB::beginTransaction();

            StockTransferProductModel::where('stock_transfer_id', $stock_transfer_id)->delete();
            StockTransferModel::where('id', $stock_transfer_id)->delete();

            DB::commit();

            $forward_link = route('stock_transfers');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock transfer deleted successfully"), 
                    "data" => $slack,
                    "link" => $forward_link
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

    public function reject_stock_transfer_product(Request $request, $slack)
    {
        try {

            if(!check_access(['A_VERIFY_STOCK_TRANSFER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }
            
            $current_selected_store = $request->logged_user_store_id;

            $stock_transfer_product_details = StockTransferProductModel::where('slack', $slack)->verifiable()->first();

            if (empty($stock_transfer_product_details)) {
                throw new Exception(trans("Product might already been verified or rejected"), 400);
            }

            $stock_transfer_details = StockTransferModel::where('id', $stock_transfer_product_details->stock_transfer_id)->resolveStore($current_selected_store)->first();

            if (empty($stock_transfer_details)) {
                throw new Exception(trans("Invalid stock transfer selected"), 400);
            }

            if($current_selected_store != $stock_transfer_details->to_store_id){
                throw new Exception(trans("Invalid, request updation can be only done from destination store"), 400);
            }

            $stock_transfer_status = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper('INITIATED')],
                ['key', '=', 'STOCK_TRANSFER_STATUS']
            ])->active()->first();
            if(empty($stock_transfer_status)){
                throw new Exception("Invalid status provided");
            }

            $stock_transfer_product_status = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper('REJECTED')],
                ['key', '=', 'STOCK_TRANSFER_PRODUCT_STATUS']
            ])->active()->first();
            if(empty($stock_transfer_product_status)){
                throw new Exception(trans("Invalid status provided"));
            }

            DB::beginTransaction();
            
            $stock_transfer = [];
            $stock_transfer['status'] = $stock_transfer_status->value;
            $stock_transfer['updated_at'] = now();
            $stock_transfer['updated_by'] = $request->logged_user_id;

            $action_response = StockTransferModel::where('id', $stock_transfer_product_details->stock_transfer_id)
            ->update($stock_transfer);

            $stock_transfer_product = [];
            $stock_transfer_product['status'] = $stock_transfer_product_status->value;
            $stock_transfer_product['updated_at'] = now();
            $stock_transfer_product['updated_by'] = $request->logged_user_id;

            $action_response = StockTransferProductModel::where('slack', $slack)
            ->update($stock_transfer_product);

            $this->check_and_update_stock_transfer_status($request, $stock_transfer_details->slack);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock transfer product request updated successfully"), 
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

    public function merge_product_stock(Request $request)
    {
        // dd($request->all());
        try {
            if(!check_access(['A_VERIFY_STOCK_TRANSFER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $stock_transfer_product_slack = $request->stock_transfer_slack;

            $stock_transfer_product = StockTransferProductModel::where('slack',$stock_transfer_product_slack)->first();
            
            $stock_transfer_product_id = $stock_transfer_product->product_id;
            
            $stock_transfer_store_details = StockTransferModel::find($stock_transfer_product->stock_transfer_id);
            if(!empty(session('store_tax_code'))){
                $stock_transfer_to_store_id = $stock_transfer_store_details->to_store_id;
                $stock_transfer_from_store_id = $stock_transfer_store_details->from_store_id;
            }else{
                $stock_transfer_from_store_id = $stock_transfer_to_store_id = session('store_tax_code');
            }            
            // $store_product_slack = $request->store_product_slack;
            $accepted_quantity = $request->accepted_quantity;
            $item_tax_selection = $request->item_tax_selection;

            $validate_response = $this->validate_verify_stock_transfer($request, $stock_transfer_product_slack, $accepted_quantity);
   
            $check_product_exist_destination_store = DB::table('products')->where('name', $validate_response['stock_transfer_product_details']['product_name'])->where('store_id', $validate_response['stock_transfer_details']['to_store_id'])->first();

            $add_in_qoyod = 0;
            DB::beginTransaction();
            
            if(empty($check_product_exist_destination_store))
            {
                $existing_product = ProductModel::withoutGlobalScopes()->where('id',$stock_transfer_product_id)->first();

                $new_product = $existing_product->replicate();
                $store_product_slack = $new_product->slack = $this->generate_slack("products");
                $new_product->store_id = $stock_transfer_to_store_id;
                $new_product->quantity = 0;
                if($item_tax_selection == 'to_store_tax'){
                    $store_tax_code_id = StoreModel::where('id',$stock_transfer_to_store_id)->first(['tax_code_id'])->tax_code_id;
                }elseif($item_tax_selection == 'from_store_tax'){

                    $store_tax_code_id = StoreModel::where('id',$stock_transfer_from_store_id)->first(['tax_code_id',])->tax_code_id;

                    $from_tax_percentage = DB::table('tax_codes')->select('total_tax_percentage')->where('id',$store_tax_code_id)->get();
                    if(isset($from_tax_percentage[0]->total_tax_percentage) && !empty($from_tax_percentage[0]->total_tax_percentage)){
                        $from_tax_percentage = $from_tax_percentage[0]->total_tax_percentage;
                        $store_tax_code_id = Taxcode::where('store_id',$stock_transfer_to_store_id)->where('total_tax_percentage',$from_tax_percentage)
                                            ->first(['id'])->id;
                    }else{
                        $store_tax_code_id = session('store_tax_code');
                    }
                    
                }else{
                    $store_tax_code_id = StoreModel::where('id',$stock_transfer_to_store_id)->first(['tax_code_id'])->tax_code_id;
                }
                $new_product->tax_code_id = $store_tax_code_id;
                $new_product->save();
                $add_in_qoyod = 1;

                // $new_product = $new_product->;

                // throw new Exception("There is no product ".$validate_response['stock_transfer_product_details']['product_name']." in ".$validate_response['stock_transfer_details']['to_store_name'].". Please add as a new Product in store.", 400);

            }else{

                $store_product_slack = $check_product_exist_destination_store->slack;

            }
            $stock_transfer_details = $validate_response['stock_transfer_details'];
            $stock_transfer_product_details = $validate_response['stock_transfer_product_details'];
            $stock_transfer_status = $validate_response['stock_transfer_status'];
            $stock_transfer_product_status = $validate_response['stock_transfer_product_status'];
        
            $destination_store_product = DB::table('products')->where('slack', $store_product_slack)->increment('quantity', $accepted_quantity,[
            'updated_at' => now()]);

            $source_store_product_quantity = ProductModel::withoutGlobalScopes()->where('id',$stock_transfer_product_details->product_id)->first()->quantity;
            if($source_store_product_quantity != "-1"){
                $source_store_product = DB::table('products')->where('id', $stock_transfer_product_details->product_id)->decrement('quantity', $accepted_quantity);
            }
            DB::table('products')->where('id', $stock_transfer_product_details->product_id)->update(array('updated_at'=>now()));


            $stock_transfer = [];
            $stock_transfer['status'] = $stock_transfer_status->value;
            $stock_transfer['updated_at'] = now();
            $stock_transfer['updated_by'] = $request->logged_user_id;

            $action_response = StockTransferModel::withoutGlobalScopes()->where('id', $stock_transfer_product_details->stock_transfer_id)
            ->update($stock_transfer);

            $destination_store_product_details = DB::table('products')->where('slack', $store_product_slack)->first();

            $stock_transfer_product = [];

            $stock_transfer_product['inward_type'] = 'MERGE';
            $stock_transfer_product['accepted_quantity'] = $accepted_quantity;
            $stock_transfer_product['destination_product_id'] = $destination_store_product_details->id;
            $stock_transfer_product['destination_product_slack'] = $destination_store_product_details->slack;
            $stock_transfer_product['destination_product_code'] = $destination_store_product_details->product_code;
            $stock_transfer_product['destination_product_name'] = $destination_store_product_details->name;

            $stock_transfer_product['status'] = $stock_transfer_product_status->value;
            $stock_transfer_product['updated_at'] = now();
            $stock_transfer_product['updated_by'] = $request->logged_user_id;

            $action_response = StockTransferProductModel::where('slack', $stock_transfer_product_slack)
            ->update($stock_transfer_product);
            

            
            // Add quantity history

            // Sending store


            $sending_store_product = ProductModel::withoutGlobalScopes()->where('id',$stock_transfer_product_id)->first();

            $this->addQuantityHistory($this->generate_slack('quantity_history'),$sending_store_product->id,$stock_transfer_details->store_id,'STOCK_TRANSFER','DECREMENT',$request->accepted_quantity,$stock_transfer_product_details->id);

            // Receiving store
            $receiving_store_product = ProductModel::withoutGlobalScopes()->where('slack',$store_product_slack)->first();

            $this->addQuantityHistory($this->generate_slack('quantity_history'),$destination_store_product_details->id,$destination_store_product_details->store_id,'STOCK_TRANSFER','INCREMENT',$request->accepted_quantity,$stock_transfer_product_details->id);

            $this->check_and_update_stock_transfer_status($request, $stock_transfer_details->slack);

            //qoyod
            if($add_in_qoyod){
                if(Session('qoyod_status')){
                    $transfer_products = StockTransferProductModel::select('product_id','product_name','accepted_quantity')->where('slack', $stock_transfer_product_slack)->get();
                    if($transfer_products->count()>0){
                        $transaction_data = array(
                            'to_location'=>$stock_transfer_store_details->to_store_id,
                            'from_location'=>$stock_transfer_store_details->from_store_id,
                            'products'=>$transfer_products,
                        );
                        $this->qoyod_inventory_transfer($transaction_data);
                    }
                }


                /*$user_qoyod = $this->qoyod_is_sync($request->logged_user_store_id);
                if($user_qoyod['status']) {
                    $qoyod_products = Product::where('slack',$new_product->slack)->first();
                    $new_request = (object) array(
                        'barcode'=>$qoyod_products->barcode,
                        'arabic_product_name'=>$qoyod_products->name_ar,
                        'product_name'=>$qoyod_products->name,
                        'description'=>$qoyod_products->description,
                        'purchase_price'=>$qoyod_products->purchase_amount_excluding_tax,
                        'sale_price'=>$qoyod_products->sale_amount_excluding_tax,
                    );

                    $ingredients = ProductIngredient::select('product_ingredients.*','products.slack as product_slack')->join('products', 'products.id', '=', 'product_ingredients.ingredient_product_id')->where('product_ingredients.product_id',$qoyod_products->id)->get();

                    if(!empty($ingredients)){
                        $ing_array = [];
                        foreach ($ingredients as $ing){
                            $temp_ing_array = array(
                                'ingredient_slack' =>$ing->product_slack,
                                'quantity' =>$ing->quantity,
                                'measurement_id' =>$ing->measurement_id,
                            );
                            $ing_array[] = $temp_ing_array;
                        }
                    }
                    $new_request->ingredients = $ing_array;
                    $this->add_product_for_qoyod($user_qoyod['data']->api_key,$new_product,$new_request,'Add');
                }*/
            }
            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock transfer product request updated successfully"), 
                    "data"    => $stock_transfer_product_slack
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

    public function validate_verify_stock_transfer(Request $request, $stock_transfer_product_slack, $quantity){

        $current_selected_store = $request->logged_user_store_id;

        $stock_transfer_product_details = StockTransferProductModel::where('slack', $stock_transfer_product_slack)->verifiable()->first();
        if (empty($stock_transfer_product_details)) {
            throw new Exception(trans("Product might already been verified or rejected"), 400);
        }

        $stock_transfer_details = StockTransferModel::where('id', $stock_transfer_product_details->stock_transfer_id)->resolveStore($current_selected_store)->first();

        if (empty($stock_transfer_details)) {
            throw new Exception(trans("Invalid stock transfer selected"), 400);
        }

        if($current_selected_store != $stock_transfer_details->to_store_id){
            throw new Exception(trans("Invalid, request updation can be only done from destination store"), 400);
        }

        $stock_transfer_product_quantity_check = StockTransferProductModel::where('slack', $stock_transfer_product_slack)->quantityCheck($quantity)->first();
        if (empty($stock_transfer_product_quantity_check)) {
            throw new Exception(trans("Accepted quantity might have exceeded the transferred quantity"), 400);
        }

        $stock_transfer_status = MasterStatusModel::select('value')->where([
            ['value_constant', '=', strtoupper('INITIATED')],
            ['key', '=', 'STOCK_TRANSFER_STATUS']
        ])->active()->first();
        if(empty($stock_transfer_status)){
            throw new Exception(trans("Invalid status provided"));
        }

        $stock_transfer_product_status = MasterStatusModel::select('value')->where([
            ['value_constant', '=', strtoupper('ACCEPTED')],
            ['key', '=', 'STOCK_TRANSFER_PRODUCT_STATUS']
        ])->active()->first();
        if(empty($stock_transfer_product_status)){
            throw new Exception(trans("Invalid status provided"));
        }

        $product_data = ProductModel::withoutGlobalScopes()->select('products.id', 'products.slack', 'products.product_code', 'products.name')
        ->where([
            ['products.id', '=', $stock_transfer_product_details->product_id],
            ['products.store_id', '=', $stock_transfer_details->from_store_id],
        ])
        ->categoryJoin()
        ->supplierJoin()
        ->taxcodeJoin()
        ->discountcodeJoin()
        ->categoryActive()
        // ->supplierActive()
        ->taxcodeActive()
        ->quantityCheck($quantity)
        ->first();

        if (empty($product_data)) {
            throw new Exception(trans("Product is currently out of stock from source store"), 400);
        }

        return [
            'stock_transfer_product_details' => $stock_transfer_product_details,
            'stock_transfer_details' => $stock_transfer_details,
            'stock_transfer_status' => $stock_transfer_status,
            'stock_transfer_product_status' => $stock_transfer_product_status,
        ];
    }

    public function form_stock_transfer_array($request){
        
        $stock_transfer_slack = $request->stock_transfer_slack;

        $products = $request->products;

        if( empty((array) $products) ){
            throw new Exception(trans("Product list cannot be empty"));
        }

        $from_store_data = StoreModel::select('id', 'store_code', 'name')
        ->where('slack', '=', trim($request->logged_user_store_slack))
        ->first();
        if (empty($from_store_data)) {
            throw new Exception(trans("Invalid source store selected"), 400);
        }

        $to_store_data = StoreModel::select('id', 'store_code', 'name')
        ->where('slack', '=', trim($request->to_store))
        ->first();
        if (empty($to_store_data)) {
            throw new Exception(trans("Invalid destination store selected"), 400);
        }

        foreach($products as $product_key => $product_value){

            $product_slack = $product_value['slack'];
            $product_name = $product_value['name'];
            $quantity = (isset($product_value['quantity']) && $product_value['quantity'] != '')?$product_value['quantity']:0.00;

            if($product_slack != ''){
                
                $product_data = ProductModel::select('products.id', 'products.slack', 'products.product_code', 'products.name')
                ->where('products.slack', '=', $product_slack)
                ->categoryJoin()
                // ->supplierJoin()
                ->taxcodeJoin()
                ->discountcodeJoin()
                ->categoryActive()
                // ->supplierActive()
                ->taxcodeActive()
                ->quantityCheck($quantity)
                ->first();

                if (empty($product_data)) {
                    throw new Exception(trans("Product :") .$product_name. trans(" is out of stock currently"), 400);
                }
                
                $stock_transfer_products[] = [
                    'stock_transfer_id' => 0,
                    'product_id' => $product_data->id,
                    'product_slack' => $product_data->slack,
                    'product_code' => $product_data->product_code,
                    'product_name' => $product_data->name,
                    'quantity' => $quantity
                ];
            }   
        }

        $stock_transfer_data = [
            "store_id" => $request->logged_user_store_id,
            "from_store_id" => $from_store_data->id,
            "from_store_code" => $from_store_data->store_code,
            "from_store_name" => $from_store_data->name,
            "to_store_id" => $to_store_data->id,
            "to_store_code" => $to_store_data->store_code,
            "to_store_name" => $to_store_data->name,
            "notes" => $request->notes,
        ];

        return [
            'stock_transfer_data' => $stock_transfer_data,
            'stock_transfer_products' => $stock_transfer_products
        ];
    }

    public function check_and_update_stock_transfer_status($request, $stock_transfer_slack){

        $stock_transfer_status = MasterStatusModel::select('value')->where([
            ['value_constant', '=', strtoupper('VERIFIED')],
            ['key', '=', 'STOCK_TRANSFER_STATUS']
        ])->active()->first();

        $stock_transfer_product_status = MasterStatusModel::select('value')->where([
            ['value_constant', '=', strtoupper('PENDING')],
            ['key', '=', 'STOCK_TRANSFER_PRODUCT_STATUS']
        ])->active()->first();
        
        $stock_transfer_details = StockTransferModel::withoutGlobalScopes()->where('slack', $stock_transfer_slack)->first();

        $pending_stock_transfer_product_count = StockTransferProductModel::where([
            ['stock_transfer_id', '=', $stock_transfer_details->id],
            ['status', '=', $stock_transfer_product_status->value],
        ])->get()->count();
        
        if($pending_stock_transfer_product_count == 0){
            DB::beginTransaction();
            
            $stock_transfer = [];
            $stock_transfer['status'] = $stock_transfer_status->value;
            $stock_transfer['updated_at'] = now();
            $stock_transfer['updated_by'] = $request->logged_user_id;

            $action_response = StockTransferModel::withoutGlobalScopes()->where('id', $stock_transfer_details->id)
            ->update($stock_transfer);

            DB::commit();
        }
    }

    public function validate_request($request)
    {
        $request->merge(['products' => json_decode($request->products, true)]);

        $validator = Validator::make($request->all(), [
            'to_store' => $this->get_validation_rules("slack", true),
            'notes' => $this->get_validation_rules("text", false),
            'products.*.name' => $this->get_validation_rules("name_label", true),
            'products.*.quantity' => 'min:0.01|'.$this->get_validation_rules("numeric", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
