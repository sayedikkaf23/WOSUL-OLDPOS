<?php

namespace App\Http\Controllers\API;

use Exception;

use App\Http\Controllers\Controller;
use App\Models\InventoryCount as InventoryCountModel;
use App\Models\InventoryCountItem as InventoryCountItemModel;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Scopes\StoreScope;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryCount extends Controller
{
    public function generate_view(Request $request)
    {    
        try {
            $reference_no = $request->reference_no;

            $inventory_count = InventoryCountModel::where('reference_no', $reference_no)->first();

            $inventory_count_id = $inventory_count->id;
            $inventory_count_store_id = $inventory_count->store_id;
            
            session()->put('inventory_count_id', $inventory_count_id);
            session()->put('inventory_count_store_id', $inventory_count_store_id);

            return response()->json($this->generate_response(
                array(
                    "message" => "Inventory count session stored",
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

    public function get_items(Request $request)
    {
        try {
            $store_id = session('inventory_count_store_id');
            $query = Product::where('store_id', $store_id)->removeUnlimitedQuantity()->withoutGlobalScopes();

            $product_ids = clone $query;
            $product_ids = $product_ids->pluck('id')->toArray();

            if(!session()->has('inventory_count_id')){
                $available_items = clone $query;
                $available_items = $available_items->select('id','name')->whereIn('id',$product_ids)->get()->toArray();
                $data['available_items'] = $available_items;
                $data['items'] = [];
            }
            else{
                $inventory_count_id = session('inventory_count_id');
                $inventory_count_product_ids = InventoryCountItemModel::where('inventory_count_id',$inventory_count_id)->pluck('product_id')->toArray();
                $product_ids = array_diff($product_ids, $inventory_count_product_ids);
                $available_items = clone $query;
                $available_items = $available_items->select('id','name')->whereIn('id',$product_ids)->get()->toArray();
                $items = InventoryCountItemModel::join('products', 'products.id', '=', 'inventory_count_items.product_id')
                        ->select('inventory_count_items.id','products.name', 'inventory_count_items.entered_quantity')->where('inventory_count_id',$inventory_count_id)
                        ->groupBy('inventory_count_items.product_id')->get()->toArray();

                $data['available_items'] = $available_items;
                $data['items'] = $items;
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Items loaded successfully", 
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

    public function add_inventory_count(Request $request)
    {       
        try {
            $store_id = $request->store_id;
            
            session()->put('inventory_count_store_id', $store_id);

            return response()->json($this->generate_response(
                array(
                    "message" => "Inventory count session stored",
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

    public function delete_inventory_count(Request $request)
    {       
        try {
            if(session()->has('inventory_count_id')){
                $inventory_count_id = session('inventory_count_id');

                DB::transaction(function() use($inventory_count_id) {
                        InventoryCountItemModel::where('inventory_count_id', $inventory_count_id)->delete();
                        InventoryCountModel::where('id', $inventory_count_id)->delete();

                        session()->forget('inventory_count_store_id');
                        session()->forget('inventory_count_id');
                });
            }
            return response()->json($this->generate_response(
                array(
                    "message" => "Delete inventory count",
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

    public function change_branch(Request $request)
    {       
        try {
            $store_id = $request->store_id;

            if(session()->has('inventory_count_id') && session()->has('inventory_count_store_id')){
                $inventory_count_store_id = session('inventory_count_store_id');
                $inventory_count_id = session('inventory_count_id');

                if($inventory_count_store_id == $store_id){
                    $data['branch'] = 0;
                    return response()->json($this->generate_response(
                        array(
                            "message" => "Already same branch selected",
                            "data" => $data
                        ), 'SUCCESS'
                    ));
                }

                DB::transaction(function() use($inventory_count_id,$store_id) {
                        InventoryCountItemModel::where('inventory_count_id', $inventory_count_id)->delete();
                        InventoryCountModel::where('id', $inventory_count_id)->update(['store_id'=>$store_id]);
                });
                
                $store = Store::find($store_id);

                session()->put('inventory_count_store_id', $store_id);
                
                $data['branch'] = 1;
                $data['store_id'] = $store->id;
                $data['store_name'] = $store->name;
            }
            return response()->json($this->generate_response(
                array(
                    "message" => "Branch changed successfully",
                    "data" => $data
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

    public function submit_inventory_count(Request $request)
    {       
        try {
            $inventory_count_id = session('inventory_count_id');
            $quantities = json_decode($request->quantities);
            $business_date = $request->business_date;
            
            foreach ($quantities as $quantity) {
                $product_id = InventoryCountItemModel::find($quantity->id)->product_id;

                $original_quantity = $this->get_original_quantity($product_id, $business_date); 
                InventoryCountItemModel::where(['id' => $quantity->id])->update(['original_quantity' => $original_quantity]);
            }

            InventoryCountModel::where(['id' => $inventory_count_id])->update(['status' => 1]);
            $data['status'] = 1;

            return response()->json($this->generate_response(
                array(
                    "message" => "Submitted inventory count successfully",
                    "data" => $data
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

    public function add_item(Request $request)
    {
        try {
            $product_ids = json_decode($request->product_ids);

            if(session()->has('inventory_count_id')){
                $inventory_count_id = session('inventory_count_id');
                $inventory_count = InventoryCountModel::find($inventory_count_id);

                $store_id = $inventory_count->store_id;
                $business_date = $inventory_count->business_date;
                $logged_user_id = $inventory_count->user_id;

                foreach ($product_ids as $product_id) {
                    InventoryCountItemModel::create([
                        'inventory_count_id' => $inventory_count_id, 
                        'product_id' => $product_id 
                    ]);
                }
            }
            else{
                $store_id = session('inventory_count_store_id');
                if($store_id<1){
                    if (!check_access(['A_EDIT_PRODUCT'], true)) {
                        throw new Exception(trans("Store not selected!"), 400);
                    }
                }
                $business_date = Carbon::now()->format('Y-m-d');
                $logged_user_id = session('user_id');
                $last_inventory_count_id = 0;

                $last_inventory_count= InventoryCountModel::orderBy('id', 'desc')->first();

                if($last_inventory_count){
                    $last_inventory_count_id = $last_inventory_count->reference_no;
                    $last_inventory_count_id = (int)preg_replace('/[^0-9]/', '', $last_inventory_count_id);
                }

                $reference_no_generated = 'IC-'. str_pad($last_inventory_count_id + 1, 6, "0", STR_PAD_LEFT);

                $inventory_count = InventoryCountModel::create([
                    'reference_no' => $reference_no_generated,
                    'store_id' => $store_id,
                    'user_id' => $logged_user_id,
                    'business_date' => $business_date,
                    'status' => 0
                ]);

                foreach ($product_ids as $product_id) {
                    InventoryCountItemModel::create([
                        'inventory_count_id' => $inventory_count->id, 
                        'product_id' => $product_id 
                    ]);
                }

                session()->put('inventory_count_id', $inventory_count->id);
            }
            
            $reference_no = $inventory_count->reference_no;
            $store_name = Store::find($store_id)->name;
            $user_name = User::find($logged_user_id)->fullname;
            $status = $inventory_count->status;
            $updated = Carbon::parse($inventory_count->updated_at)->format('Y-m-d');

            $data['reference_no'] = $reference_no;
            $data['store_name'] = $store_name;
            $data['user_name'] = $user_name;
            $data['business_date'] = $business_date;
            $data['status'] = $status;
            $data['updated'] = $updated;

            return response()->json($this->generate_response(
                array(
                    "message" => "Item added successfully",
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

    public function add_quantities(Request $request)
    { 
        try {
            $quantities = json_decode($request->quantities);
            
            foreach ($quantities as $quantity) {
                InventoryCountItemModel::where(['id' => $quantity->id])->update(['entered_quantity' => $quantity->entered_quantity]);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Added entered quantities successfully",
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

    public function get_filter_data(Request $request)
    { 
        try {

            $store_id = InventoryCountModel::groupBy('store_id')->get(['store_id'])->toArray();
            $user_id = InventoryCountModel::groupBy('user_id')->get(['user_id'])->toArray();

            $data['reference_nos'] = InventoryCountModel::select('id','reference_no as name')->groupBy('reference_no')->get()->toArray();
            $data['store_names'] = Store::select('id', 'name')->whereIn('id', $store_id)->get()->toArray();
            $data['user_names'] = User::select('id', 'fullname as name')->whereIn('id', $user_id)->get()->makeVisible(['id'])->toArray();

            return response()->json($this->generate_response(
                array(
                    "message" => "Fetched filters successfully",
                    "data" => $data
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

    public function get_inventory_count_data(Request $request)
    {
        try {

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $order_by = $request->order[0]["column"];
            // $order_direction = $request->order[0]["dir"];
            // $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $main_query = InventoryCountModel::query();
            $query = clone $main_query;
            $query = $query
                ->take($limit)
                ->skip($offset)

                ->when(request('reference_no', false), function ($query, $reference_no) {
                    return $query->where('reference_no', $reference_no);
                })

                ->when(request('branch', false), function ($query, $branch) {
                    return $query->where('store_id', $branch);
                })

                ->when(request('user_name', false), function ($query, $user_name) {
                    return $query->where('user_id', $user_name);
                })

                ->when(request('status') !== null && request('status') !== '', function ($query) {
                    return $query->where('status', request('status'));
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });
                
                if(!empty($from_date) && !empty($to_date)) {
                    $query = $query->whereBetween('business_date', [$from_date, $to_date]);
                }

            $count_query = clone $main_query;

            $inventory_counts = $query->get();

            $total_count = $count_query->get()->count();

            $item_array = [];

            foreach($inventory_counts as $key => $inventory_count) {

                $inventory_count_ref = $inventory_count->reference_no;
                $store_name = Store::find($inventory_count->store_id)->name;
                $user_name = User::find($inventory_count->user_id)->fullname;
                $status = $inventory_count->status;
                $business_date = Carbon::parse($inventory_count->business_date)->format('d-m-Y');
                $updated_on = Carbon::parse($inventory_count->updated_at)->format('d-m-Y');

                $status_html = '';

                if($status == 0){
                    $status_html = '<span class="badge badge-dot mr-4">
                                        <i class="bg-danger"></i> draft
                                    </span>';
                }
                elseif($status == 1){
                    $status_html = '<span class="badge badge-dot mr-4">
                                        <i class="bg-success"></i> closed
                                    </span>';
                }

                $item_array[$key][] = $inventory_count_ref;
                $item_array[$key][] = $store_name;
                $item_array[$key][] = $user_name;
                $item_array[$key][] = $business_date;
                $item_array[$key][] = $status_html;
                $item_array[$key][] = $updated_on;
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    
    public function get_inventory_count_item_data(Request $request)
    {
        try {

            $item_array = array();

            $inventory_count_id = session('inventory_count_id');

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            // $order_direction = $request->order[0]["dir"];
            // $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $main_query = InventoryCountItemModel::where('inventory_count_id', $inventory_count_id);
            $query = clone $main_query;
            $query = $query
                ->take($limit)
                ->skip($offset)

                // ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                //     $query->orderBy($order_by_column, $order_direction);
                // }, function ($query) {
                //     $query->orderBy('created_at', 'desc');
                // })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });

            $count_query = clone $main_query;

            $inventory_count_items = $query->get();

            $total_count = $count_query->get()->count();

            $item_array = [];

            $inventory_count = InventoryCountModel::find($inventory_count_id);

            foreach($inventory_count_items as $key => $inventory_count_item) {
                $item_name = Product::withoutGlobalScopes()->find($inventory_count_item->product_id)->name;

                $original_quantity = $inventory_count_item->original_quantity;
                $entered_quantity = $inventory_count_item->entered_quantity;
                $variance_quantity = $inventory_count_item->entered_quantity - $inventory_count_item->original_quantity;
                $variance_quantity_percentage = 0;
                
                if(!empty($original_quantity))
                    $variance_quantity_percentage = number_format(($variance_quantity/$original_quantity)*100, 2, '.', '');
                $variance_percentage_html = '';
                $variance_percentage_html_class = 'bg-danger';

                $not_variance_quantity_percentage = -$variance_quantity_percentage;

                if($not_variance_quantity_percentage >= 0 && $not_variance_quantity_percentage < 25)
                    $variance_percentage_html_class = 'bg-info';
                elseif($not_variance_quantity_percentage >= 25 && $not_variance_quantity_percentage < 50)
                    $variance_percentage_html_class = 'bg-success';
                elseif($not_variance_quantity_percentage >= 50 && $not_variance_quantity_percentage < 75)
                    $variance_percentage_html_class = 'bg-warning'; 
                elseif($not_variance_quantity_percentage >= 75 && $not_variance_quantity_percentage < 100)
                    $variance_percentage_html_class = 'bg-danger'; 
                
                $variance_percentage_html = '
                    <div class="d-flex align-items-center">
                        <span class="mr-2">'.$variance_quantity_percentage.'%</span>
                    <div>
                        <div class="progress">
                            <div
                            class="progress-bar '.$variance_percentage_html_class.'"
                            role="progressbar"
                            aria-valuenow="'.$not_variance_quantity_percentage.'"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            style="width: '.$not_variance_quantity_percentage.'%"
                            ></div>
                        </div>
                        </div>
                    </div>';

                $item_array[$key][] = $item_name;
                $item_array[$key][] = !empty($inventory_count->status) ? $original_quantity : '-';
                $item_array[$key][] = !empty($inventory_count_item->entered_quantity) ? $entered_quantity : '-';
                $item_array[$key][] = !empty($inventory_count->status) ? $variance_quantity : '-';
                $item_array[$key][] = !empty($inventory_count->status) ? $variance_percentage_html : '-';
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            dd($e);
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function get_original_quantity($id, $date)
    {
        $inventories = [];

        $current_store = session('inventory_count_store_id');
        $status = 1;

        $main_query = DB::table('products')
        ->select('products.*','products.id as product_id', 'products.name as product_name');

        $query = clone $main_query;
        $query = $query->addSelect('stores.name as store_name')
        ->where('products.id', $id)
        ->where('stores.id', $current_store);

        $products = clone $query;
        $purchase_quantity = clone $query;
        $transferred_quantity = clone $query;
        $transfer_to_quantity = clone $query;
        $sales_quantity = clone $query;
        $return_quantity = clone $query;
        $damage_quantity = clone $query;
        $adjustment_quantity = clone $query;
        $stock_return_quantity = clone $query;

        $products = $products->addSelect(DB::raw('SUM(products.quantity) AS quantity_sum'))->join('stores', 'products.store_id', '=', 'stores.id')->groupBy('stores.id', 'products.id');

        $purchase_quantity = $purchase_quantity->addSelect(DB::raw('SUM(quantity_purchase_products.quantity) AS quantity_sum'))
        ->rightJoin('quantity_purchase_products', 'quantity_purchase_products.product_id','=','products.id')
        ->join('quantity_purchases', 'quantity_purchase_products.purchase_order_id','=','quantity_purchases.id')
        ->join('stores', 'quantity_purchases.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');
        
        $transferred_quantity = $transferred_quantity->addSelect(DB::raw('SUM(stock_transfer_products.accepted_quantity) AS quantity_sum'))
        ->rightJoin('stock_transfer_products', 'stock_transfer_products.product_id','=','products.id')
        ->join('stock_transfer', 'stock_transfer_products.stock_transfer_id','=','stock_transfer.id')
        ->join('stores', 'stock_transfer.store_id', '=', 'stores.id')
        ->where('stock_transfer.to_store_id', $current_store)
        ->groupBy('stores.id', 'products.id');
        
        $transfer_to_quantity = $transfer_to_quantity->addSelect(DB::raw('SUM(stock_transfer_products.accepted_quantity) AS quantity_sum'))
        ->rightJoin('stock_transfer_products', 'stock_transfer_products.product_id','=','products.id')
        ->join('stock_transfer', 'stock_transfer_products.stock_transfer_id','=','stock_transfer.id')
        ->join('stores', 'stock_transfer.store_id', '=', 'stores.id')
        ->where('stock_transfer.from_store_id', $current_store)
        ->groupBy('stores.id', 'products.id');

        $sales_quantity = $sales_quantity->addSelect(DB::raw('SUM(order_products.quantity) AS quantity_sum'))
        ->rightJoin('order_products', 'order_products.product_id','=','products.id')
        ->join('orders', 'order_products.order_id','=','orders.id')
        ->join('stores', 'orders.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');

        $return_quantity = $return_quantity->addSelect(DB::raw('SUM(order_return_product.quantity) AS quantity_sum'))
        ->rightJoin('order_return_product', 'order_return_product.product_id','=','products.id')
        ->join('order_return', 'order_return_product.return_order_id','=','order_return.id')
        ->join('stores', 'order_return.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');

        $damage_quantity = $damage_quantity->addSelect(DB::raw('SUM(order_damage.quantity) AS quantity_sum'))
        ->rightJoin('order_damage', 'order_damage.product_id','=','products.id')
        ->join('orders', 'order_damage.order_id','=','orders.id')
        ->join('stores', 'orders.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');

        $stock_return_quantity = $stock_return_quantity->addSelect(DB::raw('SUM(stock_return_products.quantity) AS quantity_sum'))
        ->rightJoin('stock_return_products', 'stock_return_products.product_id','=','products.id')
        ->join('stock_returns', 'stock_return_products.stock_return_id','=','stock_returns.id')
        ->join('stores', 'products.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');

        $adjustment_quantity = $adjustment_quantity->addSelect(DB::raw('SUM(quantity_adjustment_items.quantity) AS quantity_sum'))
        ->rightJoin('quantity_adjustment_items', 'quantity_adjustment_items.product_id','=','products.id')
        ->join('quantity_adjustments', 'quantity_adjustment_items.quantity_adjustment_id','=','quantity_adjustments.id')
        ->join('stores', 'products.store_id', '=', 'stores.id')
        ->groupBy('stores.id', 'products.id');
        
        $products = $products->where('products.status', $status);
        $purchase_quantity = $purchase_quantity->where('quantity_purchases.status', 4);
        $transferred_quantity = $transferred_quantity->where('stock_transfer_products.status', $status);
        $transfer_to_quantity = $transfer_to_quantity->where('stock_transfer_products.status', $status);
        $sales_quantity = $sales_quantity->where('orders.status', $status);
        $return_quantity = $return_quantity->where('order_return.status', $status);
        $damage_quantity = $damage_quantity->where('orders.status', $status);
        $adjustment_quantity = $adjustment_quantity->where('quantity_adjustments.status', $status);
        $stock_return_quantity = $stock_return_quantity->where('stock_returns.status', $status);

        if($date != ''){
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            //$from_created_date = $from_created_date . ' 00:00:00';
            // $products_opening = clone $products;
            $purchase_quantity_opening = clone $purchase_quantity;
            $transferred_quantity_opening = clone $transferred_quantity;
            $transfer_to_quantity_opening = clone $transfer_to_quantity;
            $sales_quantity_opening = clone $sales_quantity;
            $return_quantity_opening = clone $return_quantity;
            $damage_quantity_opening = clone $damage_quantity;
            $adjustment_quantity_opening = clone $adjustment_quantity;
            $stock_return_quantity_opening = clone $stock_return_quantity;

            // $products_opening = $products_opening->addSelect(DB::raw('SUM(products.quantity) AS quantity_sum'))->get();
            $purchase_quantity_opening = $purchase_quantity_opening->where('quantity_purchase_products.created_at', '<=', $date)->get();
            $transferred_quantity_opening = $transferred_quantity_opening->where('stock_transfer_products.created_at', '<=', $date)->get();
            $transfer_to_quantity_opening = $transfer_to_quantity_opening->where('stock_transfer_products.created_at', '<=', $date)->get();
            $sales_quantity_opening = $sales_quantity_opening->where('order_products.created_at', '<=', $date)->get();
            $return_quantity_opening = $return_quantity_opening->where('order_return_product.created_at', '<=', $date)->get();
            $damage_quantity_opening = $damage_quantity_opening->where('order_damage.created_at', '<=', $date)->get();
            $adjustment_quantity_opening = $adjustment_quantity_opening->where('quantity_adjustment_items.created_at', '<=', $date)->get();
            $stock_return_quantity_opening = $stock_return_quantity_opening->where('stock_return_products.created_at', '<=', $date)->get();
            
            $purchase_quantity = $purchase_quantity->where('quantity_purchase_products.created_at', $date);
            $transferred_quantity = $transferred_quantity->where('stock_transfer_products.created_at', $date);
            $transfer_to_quantity = $transfer_to_quantity->where('stock_transfer_products.created_at', $date);
            $sales_quantity = $sales_quantity->where('order_products.created_at', $date);
            $return_quantity = $return_quantity->where('order_return_product.created_at', $date);
            $damage_quantity = $damage_quantity->where('order_damage.created_at', $date);
            $adjustment_quantity = $adjustment_quantity->where('quantity_adjustment_items.created_at', $date);
            $stock_return_quantity = $stock_return_quantity->where('stock_return_products.created_at', $date);

        }

        $products = $products->get();

        $purchase_quantities = $purchase_quantity->get();
        
        $transferred_quantities = $transferred_quantity->get();
        
        $transfer_to_quantities = $transfer_to_quantity->get();
                
        $sales_quantities = $sales_quantity->get();

        $return_quantities = $return_quantity->get();
        
        $damage_quantities = $damage_quantity->get();
        
        $adjustment_quantities = $adjustment_quantity->get();
        
        $stock_return_quantities = $stock_return_quantity->get();

        $opening_quantity = 0.00;
        $available_quantity = 0.00;

        if(!empty($purchase_quantities[0]))
            $available_quantity += $purchase_quantities[0]->quantity_sum;

        if(!empty($transferred_quantities[0]))
            $available_quantity += $transferred_quantities[0]->quantity_sum;

        if(!empty($transfer_to_quantities[0]))
            $available_quantity -= $transfer_to_quantities[0]->quantity_sum;

        if(!empty($sales_quantities[0]))
            $available_quantity -= $sales_quantities[0]->quantity_sum;

        if(!empty($return_quantities[0]))
            $available_quantity += $return_quantities[0]->quantity_sum;

        if(!empty($damage_quantities[0]))
            $available_quantity -= $damage_quantities[0]->quantity_sum;

        if(!empty($adjustment_quantities[0]))
            $available_quantity -= $adjustment_quantities[0]->quantity_sum;

        if(!empty($stock_return_quantities[0]))
            $available_quantity -= $stock_return_quantities[0]->quantity_sum;
        
        if(!empty($products[0]))
            $opening_quantity += $products[0]->quantity_sum;

        if(!empty($purchase_quantity_opening[0]))
            $opening_quantity += $purchase_quantity_opening[0]->quantity_sum;

        if(!empty($transferred_quantity_opening[0]))
            $opening_quantity += $transferred_quantity_opening[0]->quantity_sum;
        
        if(!empty($transfer_to_quantity_opening[0]))
            $opening_quantity -= $transfer_to_quantity_opening[0]->quantity_sum;
        
        if(!empty($sales_quantity_opening[0]))
            $opening_quantity -= $sales_quantity_opening[0]->quantity_sum;
        
        if(!empty($return_quantity_opening[0]))
            $opening_quantity += $return_quantity_opening[0]->quantity_sum;
        
        if(!empty($damage_quantity_opening[0]))
            $opening_quantity -= $damage_quantity_opening[0]->quantity_sum;
        
        if(!empty($adjustment_quantity_opening[0]))
            $opening_quantity -= $adjustment_quantity_opening[0]->quantity_sum;

        if(!empty($stock_return_quantity_opening[0]))
            $opening_quantity -= $stock_return_quantity_opening[0]->quantity_sum;

        $available_quantity += $opening_quantity;

        return $available_quantity;
    }
}
