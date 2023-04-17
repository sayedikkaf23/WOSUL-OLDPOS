<?php

namespace App\Exports;

use App\Models\ReturnOrders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\Product as ProductModel;
use App\Models\Store as StoreModel;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\ReturnOrderResource;
use App\Models\Discountcode;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\Taxcode;
use Carbon\Carbon;
use Mpdf\Tag\B;

class QuantityPurchaseExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;
    
    public $from_created_date, $to_created_date, $store_ids = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        $store_id = $this->data['store']; // store id
        $product_id = ($this->data['product'] == '') ? null : ProductModel::where('slack',$this->data['product'])->first()->id; 
        
        $headings = [
            trans('Date'),
            trans('Product Code'),
            trans('Product Name'),
            trans('Opening Qty'),
            trans('Purchased Qty'),
            trans('Unit Price'),
            trans('Tax'),
            trans('Discount'),
            trans('Purchase Amount'),
            trans('Sold Qty'),
            trans('Sale Price'),
            trans('Tax'),
            trans('Discount'),
            trans('Sales Amount'),
        ];

        return $headings;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        $store_id = $this->data['store'];
        $product_id = ($this->data['product'] == '') ? null : ProductModel::where('slack',$this->data['product'])->first()->id; 

        $stores = StoreModel::select('id','name')->active()->get();

        $query = ProductModel::withoutGlobalScopes()->select('products.*')->distinct();

        if($this->from_created_date != ''){
            // $this->from_created_date = strtotime($this->from_created_date);
            // $this->from_created_date = date(config('app.sql_date_format'), $this->from_created_date);
            $this->from_created_date = $this->from_created_date . ' 00:00:00';
            $query = $query->where('products.created_at', '>=', $this->from_created_date);
        }
        if($this->to_created_date != ''){
            // $this->to_created_date = strtotime($this->to_created_date);
            // $this->to_created_date = date(config('app.sql_date_format'), $this->to_created_date);
            $this->to_created_date = $this->to_created_date . ' 23:59:59';
            $query = $query->where('products.created_at', '<=', $this->to_created_date);
        }
        if($store_id != ''){
            $query = $query->where('products.store_id', '<=', $store_id);
        }
        if($product_id != ''){
            $query = $query->where('products.id', $product_id);
        }
        if(isset($status)){
            $query = $query->where('products.status', $status);
        }

        $products = $query->get();
        
        $rows = [];
        foreach($products as $product){

            // variables for subtotal column
            $subtotal_purchase_quantity = 0; 
            $subtotal_unit_price = 0; 
            $subtotal_purchase_tax = 0; 
            $subtotal_purchase_discount = 0; 
            $subtotal_purchase_amount = 0; 
            $subtotal_sold_quantity = 0; 
            $subtotal_sales_price = 0; 
            $subtotal_tax_amount = 0; 
            $subtotal_discount_amount = 0; 
            $subtotal_sales_amount = 0; 


            $sold_quantity = Order::query()
            ->join('order_products','order_products.order_id','orders.id')
            ->where('orders.status',1)
            ->whereBetween('orders.created_at',[$this->from_created_date,$this->to_created_date])
            ->where('order_products.product_id',$product->id)
            ->sum('order_products.quantity');
            
            $purchased_quantity = PurchaseOrder::query()
            ->join('purchase_order_products','purchase_order_products.purchase_order_id','purchase_orders.id')
            ->where('purchase_orders.status',1)
            ->whereBetween('purchase_orders.created_at',[$this->from_created_date,$this->to_created_date])
            ->where('purchase_order_products.product_id',$product->id)
            ->sum('purchase_order_products.quantity');

            $opening_qty = ($product->quantity + $sold_quantity) - $purchased_quantity;
            $tax_amount = 0;
            if($product->tax_code_id > 0){
                $tax_percent = Taxcode::find($product->tax_code_id)->total_tax_percentage;
                $tax_amount = ($product->sale_amount_excluding_tax * $tax_percent) / 100;
            }
            $total_tax_amount = $tax_amount * $opening_qty;
            $discount_amount = 0;
            if($product->discount_code_id > 0){
                $discount_percent = Discountcode::find($product->discount_code_id)->discount_percentage;
                $discount_amount = ($product->sale_amount_excluding_tax * $discount_percent) / 100;
            }
            $total_discount_amount = $discount_amount * $opening_qty;
            $total_product_cost = ($opening_qty * $product->sale_amount_excluding_tax) + $total_tax_amount;
            
            // quantity sales status for the day when product was created
            $query = Order::query()
            ->join('order_products','order_products.order_id','orders.id')
            ->where('orders.status',1)
            ->whereDate('orders.created_at',$product->created_at)
            ->where('order_products.product_id',$product->id);
            
            $first_sold_quantity = $query->sum('order_products.quantity');
            $first_sale = $query->select('order_products.sale_amount_excluding_tax','order_products.tax_amount','order_products.discount_amount')->first();
            
            if(isset($first_sale)){
                $first_sale_cost = ($first_sold_quantity * $first_sale->sale_amount_excluding_tax) + $first_sale->tax_amount - $first_sale->discount_amount;
            }else{
                $first_sale_cost = 0;
            }

            $product_data = [];
            $product_data['date'] = Carbon::parse($product->created_at)->format('d-m-Y');
            $product_data['Product Code'] = $product->product_code;
            if(app()->getLocale() == 'ar'){
                $product_data['Product Name'] = $product->name_ar!=null?$product->name_ar:$product->name;
            }else{
                $product_data['Product Name'] = $product->name;
            }
            $product_data['Opening Qty'] = $opening_qty;
            $product_data['Purchased Qty'] = 0;
            $product_data['Unit Price'] = $product->sale_amount_excluding_tax;
            $product_data['Product Tax'] = $total_tax_amount;
            $product_data['Product Discount'] = $total_discount_amount;
            $product_data['Product Cost'] = $total_product_cost;
            $product_data['Sales Qty'] = $first_sold_quantity;
            $product_data['Sales Price'] = (isset($first_sale)) ? $first_sale->sale_amount_excluding_tax : 0;
            $product_data['Sales Tax'] = (isset($first_sale)) ? $first_sale->tax_amount : 0;
            $product_data['Sales Discount'] = (isset($first_sale)) ? $first_sale->discount_amount : 0;
            $product_data['Sales Cost'] = $first_sale_cost;
            array_push($rows, $product_data);

            // subtotal for the first row of product which is based on product
            $subtotal_purchase_quantity += 0;
            $subtotal_unit_price += (float) $product->sale_amount_excluding_tax ; 
            $subtotal_purchase_tax += (float) $total_tax_amount ; 
            $subtotal_purchase_discount += (float) $total_discount_amount; 
            $subtotal_purchase_amount += (float) $total_product_cost ; 
            $subtotal_sold_quantity += (float) $first_sold_quantity ; 
            $subtotal_sales_price += (isset($first_sale)) ? (float) $first_sale->sale_amount_excluding_tax : 0 ; 
            $subtotal_tax_amount += (isset($first_sale)) ? (float) $first_sale->tax_amount : 0; 
            $subtotal_discount_amount += (isset($first_sale)) ? (float) $first_sale->discount_amount : 0 ; 
            $subtotal_sales_amount += (float) $first_sale_cost ; 

            // get purchase details of product
            $purchase_detail = PurchaseOrderProduct::where('product_id',$product->id)->whereBetween('created_at',[$this->from_created_date,$this->to_created_date])->get();
            
            if($purchase_detail->count() > 0){
                foreach($purchase_detail as $purchase){

                    // quantity sales on purchase dates
                    $query = Order::query()
                    ->join('order_products','order_products.order_id','orders.id')
                    ->where('orders.status',1)
                    ->whereDate('orders.created_at',$purchase->created_at)
                    ->where('order_products.product_id',$purchase->product_id);
                    
                    $sold_quantity = $query->sum('order_products.quantity');
                    $sales = $query->select('order_products.sale_amount_excluding_tax','order_products.tax_amount','order_products.discount_amount')->first();
                    
                    if(isset($sales)){
                        $sales_cost = ($sold_quantity * $sales->sale_amount_excluding_tax) + $sales->tax_amount - $sales->discount_amount;
                    }else{
                        $sales_cost = 0;
                    }

                    $subset = [];
                    $subset['date'] = Carbon::parse($purchase->created_at)->format('d-m-Y');
                    $subset['Product Code'] = $product->product_code;
                    $subset['Product Name'] = $product->name;
                    $subset['Opening Qty'] = '';
                    $subset['Purchased Qty'] = $purchase->quantity;
                    $subset['Unit Price'] = $purchase->amount_excluding_tax;
                    $subset['Product Tax'] = $purchase->tax_amount;
                    $subset['Product Discount'] = $purchase->discount_amount;
                    $subset['Product Cost'] = $purchase->subtotal_amount_excluding_tax;
                    $subset['Sales Qty'] = $sold_quantity;
                    $subset['Sales Price'] = (isset($sales)) ? $sales->sale_amount_excluding_tax : 0;
                    $subset['Sales Tax'] = (isset($sales)) ? $sales->tax_amount : 0;
                    $subset['Sales Discount'] = (isset($sales)) ? $sales->discount_amount : 0;
                    $subset['Sales Cost'] = $sales_cost;

                    array_push($rows,$subset);

                    // subtotal for rest of the rows product which is based on product purchased
                    $subtotal_purchase_quantity += (float) $purchase->quantity;
                    $subtotal_unit_price += (float) $purchase->amount_excluding_tax; 
                    $subtotal_purchase_tax += (float) $purchase->tax_amount; 
                    $subtotal_purchase_discount += (float) $purchase->discount_amount; 
                    $subtotal_purchase_amount += (float) $purchase->subtotal_amount_excluding_tax; 
                    $subtotal_sold_quantity += (float) $sold_quantity ; 
                    $subtotal_sales_price += (isset($sales)) ? (float) $sales->sale_amount_excluding_tax : 0 ; 
                    $subtotal_tax_amount += (isset($sales)) ? (float) $sales->tax_amount : 0; 
                    $subtotal_discount_amount += (isset($sales)) ? (float) $sales->discount_amount : 0 ; 
                    $subtotal_sales_amount += (float) $sales_cost; 

                }
            }

            $subtotal_set = [];
            $subtotal_set['date'] = 'Subtotal';
            $subtotal_set['Product Code'] = '';
            $subtotal_set['Product Name'] = '';
            $subtotal_set['Opening Qty'] = '';
            $subtotal_set['Purchased Qty'] = $subtotal_purchase_quantity;
            $subtotal_set['Unit Price'] = $subtotal_unit_price;
            $subtotal_set['Product Tax'] = $subtotal_purchase_tax;
            $subtotal_set['Product Discount'] = $subtotal_purchase_discount;
            $subtotal_set['Product Cost'] = $subtotal_purchase_amount;
            $subtotal_set['Sales Qty'] = $subtotal_sold_quantity;
            $subtotal_set['Sales Price'] = $subtotal_sales_price;
            $subtotal_set['Sales Tax'] = $subtotal_tax_amount;
            $subtotal_set['Sales Discount'] = $subtotal_discount_amount;
            $subtotal_set['Sales Cost'] = $subtotal_sales_amount;

            array_push($rows,$subtotal_set);

        }

        return collect($rows);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            
            'A'   => [
                    'font' => [
                        'italic' => true
                    ], 
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ]
            ],
            
            'A1'   => [
                    'font' => [
                        'bold' => true
                    ]
            ],
            
        ];
    }

}
