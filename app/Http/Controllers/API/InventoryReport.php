<?php

namespace App\Http\Controllers\API;

use App\Exports\InventoryExport;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\OrderProduct as OrderProductModel;
use App\Http\Controllers\Controller;

use App\Models\ReturnOrdersProducts;
use App\Models\Store as StoreModel;

use App\Models\InventoryCountItem;
use App\Models\QuantityAdjustmentItems;
use App\Models\QuantityHistory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class InventoryReport extends Controller
{



    public function index(Request $request)
    {

        try {

            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $store_id = $request->store_id;
            $product_type = $request->product_type;
            
            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            $products = DB::table('products')
                ->select('products.*', 'products.id as product_id', 'products.name as product_name')
                ->when($filter_string, function ($query, $filter_string) {
                        $query->where('products.name', 'like', '%' . $filter_string . '%');
                })
                ->when($store_id != '', function ($query) use($store_id) {
                        $query->where('products.store_id', $store_id);
                })
                ->when($product_type != '', function ($query) use($product_type) {
                        $query->where('products.is_ingredient', $product_type);
                })
                ->orderBy('products.created_at','DESC')
                ->take($limit)
                ->skip($offset)
                ->get();
            $product_count = DB::table('products')
                ->when($filter_string, function ($query, $filter_string) {
                    $query->where('products.name', 'like', '%' . $filter_string . '%');
                })
                ->when($store_id != '', function ($query) use($store_id) {
                    $query->where('products.store_id', $store_id);
                })
                ->when($product_type != '', function ($query) use($product_type) {
                    $query->where('products.is_ingredient', $product_type);
                })
                ->count();
            $item_array = $this->generate_inventory_report($products,$from_date,$to_date);
            $response = [
                'draw' => $draw,
                'recordsTotal' => $product_count,
                'recordsFiltered' => $product_count,
                'data' => $item_array,
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode(),
                )
            ));
        }
    }

    public function generate_inventory_report($products,$from_date,$to_date){
        
        $item_array = [];
        
        foreach ($products as $key => $product) {
            if(app()->getLocale() == 'ar'){
                $item_array[$key][] = $product->name_ar!=''?$product->name_ar:$product->name;
            }else{
                $item_array[$key][] = $product->name;
            }

            $item_array[$key][] = StoreModel::find($product->store_id)->name;
            $item_array[$key][] = $this->get_opening_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_purchase_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_transfer_from_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_transfer_to_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_sold_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_returned_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_damaged_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_adjustment_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = $this->get_stock_return_quantity($product->id,$from_date,$to_date);
            // $item_array[$key][] = $this->get_variance_quantity($product->id,$from_date,$to_date);
            $item_array[$key][] = (int) $product->quantity;
                
        }

        return $item_array;

    }

    
    public function download_excel(Request $request){
        
        $from_created_date = $request->from_created_date;
        $to_created_date = $request->to_created_date;
        $store_id = $request->store_id;

        try {

            $products = DB::table('products')
                ->select('products.*', 'products.id as product_id', 'products.name as product_name')
                ->when( $store_id != '', function($query) use($store_id) {
                    $query->where('products.store_id',$store_id);
                 })
                ->where('products.is_ingredient',0)
                ->orderBy('products.created_at','DESC')
                ->get();
            
            $params = [
                'products' => $products,
                'from_created_date' => $from_created_date,
                'to_created_date' => $to_created_date,
            ];

            $filename = 'inventory_export_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new InventoryExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $view_path = '/storage/reports/';
            $download_link = asset($view_path . $filename);
            
            return response()->json($this->generate_response(
                array(
                    "message" => "Inventory report generated successfully",
                    "data" => '',
                    "link" => $download_link
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



    private function get_opening_quantity($product_id,$from_date,$to_date){
        
        $quantity_history = QuantityHistory::where('product_id',$product_id)->whereDate('date','<',$from_date)->get();
        
        $quantity = 0;

        if($quantity_history->count() == 0){
            
            $quantity = QuantityHistory::where('product_id',$product_id)->oldest()->first();
            if(isset($quantity)){
                $quantity = $quantity->quantity;
            }
        
        }else{
            
            if(isset($quantity_history) && count($quantity_history) > 0){
                foreach($quantity_history as $history){
                    if($history->action == 'INCREMENT'){
                        $quantity += (int) $history->quantity;
                    }else if($history->action == 'DECREMENT'){
                        $quantity -= (int) $history->quantity;
                    }
        
                }

          }
        }

        return (int) $quantity;

    }
    
    private function get_purchase_quantity($product_id,$from_date,$to_date){

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->whereIn('type',['PURCHASE_ORDER','QUANTITY_PURCHASE'])
        ->where('action','INCREMENT')
        ->get();
        
        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_transfer_from_quantity($product_id,$from_date,$to_date){

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->whereIn('type',['STOCK_TRANSFER'])
        ->where('action','INCREMENT')
        ->get();
        
        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_transfer_to_quantity($product_id,$from_date,$to_date){

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->whereIn('type',['STOCK_TRANSFER'])
        ->where('action','DECREMENT')
        ->get();
        
        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_sold_quantity($product_id,$from_date,$to_date){

        // $quantity = OrderProductModel::query()
        // ->join('orders','orders.id','order_products.order_id')
        // ->where('order_products.product_id',$product_id)
        // ->where('orders.status',1)
        // ->whereBetween('orders.value_date',[$from_date,$to_date])
        // ->select(DB::raw('coalesce(SUM(order_products.quantity),0) as quantity'))
        // ->first();
        
        // return (int) $quantity['quantity'];

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->where(function ($query) {
                $query->where('type', '=', 'ORDER')
                    ->orWhere('type', '=', 'INVOICE');
        })
        ->where('action','DECREMENT')
        ->get();

        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_returned_quantity($product_id,$from_date,$to_date){
     
        // $quantity = ReturnOrdersProducts::active()
        // ->join('order_return','order_return.id','order_return_product.return_order_id')
        // ->where('order_return_product.product_id',$product_id)
        // ->whereBetween('order_return.value_date',[$from_date,$to_date])
        // ->where('order_return_product.return_type','Return')
        // ->select(DB::Raw('coalesce(SUM(order_return_product.quantity),0) as quantity'))->first();
        
        // return (int) $quantity['quantity'];

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->where(function ($query) {
            $query->where('type', '=', 'ORDER_RETURN')
                ->orWhere('type', '=', 'INVOICE_RETURN');
        })
        ->where('action','INCREMENT')
        ->get();

        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }

    private function get_damaged_quantity($product_id,$from_date,$to_date){
        
        // $quantity = ReturnOrdersProducts::active()
        // ->join('order_return','order_return.id','order_return_product.return_order_id')
        // ->where('order_return_product.product_id',$product_id)
        // ->whereBetween('order_return.value_date',[$from_date,$to_date])
        // ->where('order_return_product.return_type','Damage')
        // ->select(DB::Raw('coalesce(SUM(order_return_product.quantity),0) as quantity'))->first();
        
        // return (int) $quantity['quantity'];

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->where('type','ORDER_DAMAGE')
        ->where('action','DECREMENT')
        ->get();

        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_adjustment_quantity($product_id,$from_date,$to_date){

        // $quantity = QuantityAdjustmentItems::query()
        // ->join('quantity_adjustments','quantity_adjustments.id','quantity_adjustment_items.quantity_adjustment_id')
        // ->where('quantity_adjustment_items.product_id',$product_id)
        // ->whereBetween('quantity_adjustments.submitted_at',[$from_date,$to_date])
        // ->select(DB::Raw('coalesce(SUM(quantity_adjustment_items.quantity),0) as quantity'))->first();
        
        // return (int) $quantity['quantity'];

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[$from_date,$to_date])
        ->where('type','QUANTITY_ADJUSTMENT')
        ->where('action','DECREMENT')
        ->get();

        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }
    
    private function get_variance_quantity($product_id,$from_date,$to_date){
        
        $quantity = InventoryCountItem::query()
        ->join('inventory_counts','inventory_counts.id','inventory_count_items.inventory_count_id')
        ->where('inventory_count_items.product_id',$product_id)
        ->whereBetween('inventory_count_items.created_at',[$from_date,$to_date])
        ->select(DB::Raw('coalesce( SUM(inventory_count_items.entered_quantity - inventory_count_items.original_quantity),0) as quantity'))->first();
        
        return (int) $quantity['quantity'];

    }

    private function get_stock_return_quantity($product_id,$from_date,$to_date){

        $quantity_history = QuantityHistory::where('product_id',$product_id)
        ->whereBetween('date',[ $from_date, $to_date])
        ->where('type','STOCK_RETURN')
        ->where('action','DECREMENT')
        ->get();

        $quantity = 0;
        if(isset($quantity_history) && count($quantity_history) > 0)
        foreach($quantity_history as $history){
            $quantity += $history->quantity;
        }

        return (int) $quantity;

    }

  

}
