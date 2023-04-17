<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use App\Http\Resources\ProductResource;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\ReturnOrdersProducts;
use Carbon\Carbon;
use App\Models\TaxcodeType as TaxcodeTypeModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Category as CategoryModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountCodeModel;
use App\Models\Product as ProductModel;
use App\Models\Store as StoreModel;
use DB;
use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProducts;

class ProductWiseSalesExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithStyles
{
    use Exportable;

    public  $total_sold_quantity = 0, 
            $total_sales = 0, 
            $total_returned_quantity = 0, 
            $total_sales_returned = 0,
            $total_damaged_quantity = 0, 
            $total_sales_damaged = 0,
            $total_net_sales_quantity = 0,
            $total_net_sales = 0,
            $total_discount = 0,
            $total_tax = 0,
            $total_net_profit = 0;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->rows_count = 0;
    }

    public function collection()
    {
        $from_created_date = Carbon::parse($this->data['from_created_date'])->format('Y-m-d') ;
        $to_created_date = Carbon::parse($this->data['to_created_date'])->format('Y-m-d') ;
        
        // $to_created_date = $this->data['to_created_date'];

        $supplier_id = (isset($this->data['supplier']) && $this->data['supplier'] != '') ? SupplierModel::where('slack',$this->data['supplier'])->first()->id : '' ;

        $category_id = (isset($this->data['category']) && $this->data['category'] != '') ? CategoryModel::where('slack',$this->data['category'])->first()->id : '' ;

        $tax_code_id = (isset($this->data['tax_code']) && $this->data['tax_code'] != '') ? TaxcodeModel::where('slack',$this->data['tax_code'])->first()->id : '' ;

        $currentdate = date('Y-m-d H:i:sa');
        $discount_code_id = '';
        if(isset($this->data['discount_code']) && $this->data['discount_code'] != ''){
            $discount_code_id = DiscountCodeModel::withoutGlobalScopes()->where('slack',$this->data['discount_code'])
            ->whereRaw("'{$currentdate}' between discount_start_date and discount_end_date")
            ->whereRaw("limit_on_discount=-1 OR limit_on_discount>0")
            ->whereRaw("discounttype!='cashier'")->first();
            if(isset($discount_code_id->id)){
                $discount_code_id = $discount_code_id->id;
            }
        }
        $product_type = $this->data['product_type'];
        $status = $this->data['status'];

        
        $query = OrderProductModel::query()
        ->select('order_products.product_id')
        ->join('orders','orders.id','order_products.order_id')
        ->join('products','products.id','order_products.product_id')
        ->join('category','category.id','products.category_id')
        ->where('orders.status',1) // closed orders only
        ->where('products.store_id',request()->logged_user_store_id); // logged store only

        $query2 = InvoiceProduct::query()
        ->select('invoice_products.product_id')
        ->join('invoices','invoices.id','invoice_products.invoice_id')
        ->join('products','products.id','invoice_products.product_id')
        ->join('category','category.id','products.category_id')
        /*->where('invoices.status',1)*/
        ->where('invoices.store_id',request()->logged_user_store_id);


        // Filter 1 - By Product Type
        if($product_type == 'billing_products') {
            $query = $query->where('products.is_ingredient', 0);
            $query2 = $query2->where('products.is_ingredient', 0);
        };
        if($product_type == 'ingredients') {
            $query = $query->where('products.is_ingredient', 1);
            $query2 = $query2->where('products.is_ingredient', 1);
        };

        // Filter 2 & 3 - Between Created Dates
        // $from_created_date = strtotime($from_created_date);
        // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
        $from_created_date = $from_created_date;

        // $to_created_date = strtotime($to_created_date);
        // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
        $to_created_date = $to_created_date;

        $query = $query->whereBetween('orders.value_date',[$from_created_date,$to_created_date]);
        $query2 = $query2->whereBetween('invoices.invoice_date',[$from_created_date,$to_created_date]);

        // Filter 4 - By Product Supplier
        if($supplier_id != ''){
            $query = $query->where('products.supplier_id', $supplier_id);
            $query2 = $query2->where('products.supplier_id', $supplier_id);
        }

        // Filter 5 - By Product Category
        if($category_id != ''){
            $query = $query->where('products.category_id', $category_id);
            $query2 = $query2->where('products.category_id', $category_id);
        }

        // Filter 6 - By Product Tax Code
        if($tax_code_id != ''){
            $query = $query->where('products.tax_code_id', $tax_code_id);
            $query2 = $query2->where('products.tax_code_id', $tax_code_id);
        }

        // Filter 7 - By Product Discount Code
        if($discount_code_id != ''){
            $query = $query->where('products.discount_code_id', $discount_code_id);
            $query2 = $query2->where('products.discount_code_id', $discount_code_id);
        }

        // Filter 8 - By Product Status
        if(isset($status)){
            $query = $query->where('products.status', $status);
            $query2 = $query2->where('products.status', $status);
        }

        $product_ids = $query->pluck('order_products.product_id')->toArray();
        $product_ids2 = $query2->pluck('invoice_products.product_id')->toArray();

        $product_ids = array_merge($product_ids,$product_ids2);
        $product_ids = array_unique($product_ids);

        $result = [];

        if(isset($product_ids)){
            foreach($product_ids as $product_id){

                $product = ProductModel::withoutGlobalScopes()->where('id',$product_id)->first();
                $category = CategoryModel::find($product['category_id']);

                $dataset['product_code'] = $product->product_code;
                if(app()->getLocale() == 'ar'){
                    $dataset['product_name'] = $product->name_ar!=null?$product->name_ar:$product->name;
                }else{
                    $dataset['product_name'] = $product->name;
                }
                $dataset['category'] = $category->label;
                $dataset['purchase_price'] = $product->purchase_amount_excluding_tax;
                $dataset['sale_price'] = $product->sale_amount_excluding_tax;
                
                $dataset['order_sale_quantity'] = OrderProductModel::active()
                ->join('orders','orders.id','order_products.order_id')
                ->where('order_products.product_id',$product_id)
                ->whereBetween('orders.value_date',[$from_created_date,$to_created_date])
                ->sum('order_products.quantity');

                $dataset['invoice_sale_quantity'] = InvoiceProduct::active()
                    ->join('invoices','invoices.id','invoice_products.invoice_id')
                    ->where('invoice_products.product_id',$product_id)
                    ->whereBetween('invoices.invoice_date',[$from_created_date,$to_created_date])
                    ->sum('invoice_products.quantity');

                $dataset['sales_quantity'] = $dataset['order_sale_quantity'] + $dataset['invoice_sale_quantity'];

                $dataset['sales'] = $dataset['sales_quantity'] * $dataset['sale_price'];
                
                $dataset['discount'] = 0;
                
                if($product->discount_code_id != null){
                    $discount = DiscountCodeModel::withoutGlobalScopes()->where('id',$product->discount_code_id)->first();
                    if(isset($discount)){
                        if($discount->discount_type=="percentage")
                        {
                          $dataset['discount'] = $dataset['sales'] * $discount->discount_percentage / 100;
                        }
                        else{
                            $dataset['discount'] = $discount->discount_value;
                        }
                    }else{
                        $dataset['discount'] = 0;
                    }
                }


                $dataset['sales_after_discount'] = $dataset['sales'] - $dataset['discount'];
                
                $whole_tax = '0';
                $store = StoreModel::withoutGlobalScopes()->where('id',request()->logged_user_store_id)->first();
                if($product->is_taxable == 1){
                    $tax = TaxcodeModel::withoutGlobalScopes()->where('id',$store->tax_code_id)->first();
                    if($tax->total_tax_percentage > 0){
                        $whole_tax = $dataset['sales_after_discount'] * $tax->total_tax_percentage / 100;
                    }
                }


                
                // $returned_quantity = ReturnOrdersProducts::active()
                //                     ->join('order_return1','order_return.id','order_return_product.return_order_id')
                //                     ->where('order_return_product.product_id',$product_id)
                //                     ->whereBetween('order_return_product.created_at',[$from_created_date,$to_created_date])
                //                     ->select(DB::Raw('coalesce(SUM(order_return_product.quantity),0) as quantity'))->first();

                $returned_quantity = DB::select("select coalesce(SUM(orp1.quantity),0) as return_quantity, coalesce(SUM(orp2.quantity),0) as damage_quantity
                                    from `order_return`
                                    left join `order_return_product` as orp1 on `order_return`.`id` = orp1.`return_order_id` and orp1.return_type = 'Return'
                                    left join `order_return_product` as orp2 on `order_return`.`id` = orp2.`return_order_id` and orp2.return_type = 'Damage'
                                    where (orp1.status = 1 or orp2.status = 1 ) 
                                        and (orp1.`product_id` = $product_id or orp2.`product_id` = $product_id )
                                        and order_return.`created_at` between '$from_created_date' and '$to_created_date' limit 1");
                if(isset($returned_quantity[0])){
                    $dataset['returned_quantity'] = $returned_quantity[0]->return_quantity;
                    $dataset['damaged_quantity'] = $returned_quantity[0]->damage_quantity;
                }else{
                    $dataset['returned_quantity'] = 0;
                    $dataset['damaged_quantity'] = 0;
                }

                //invoice return quantity
                 $invoice_returned_quantity = InvoiceReturnProducts::active()
                 ->join('invoices_return','invoices_return.id','invoice_return_products.return_invoice_id')
                 ->where('invoice_return_products.product_id',$product_id)
                 ->whereBetween(DB::Raw('DATE(invoice_return_products.created_at)'),[$from_created_date,$to_created_date])
                 ->select(DB::Raw('coalesce(SUM(invoice_return_products.quantity),0) as quantity'))->first();

                $dataset['returned_quantity'] = $dataset['returned_quantity'] + $invoice_returned_quantity->quantity;

                $dataset['returned_sales'] = '0';
                if($dataset['returned_quantity'] > 0){
                    $dataset['returned_sales'] = $dataset['returned_quantity'] * $dataset['sale_price'];
                }
                $dataset['damaged_sales'] = '0';
                if($dataset['damaged_quantity'] > 0){
                    $dataset['damaged_sales'] = $dataset['damaged_quantity'] * $dataset['sale_price'];
                }
                $dataset['net_sales_quantity'] = $dataset['sales_quantity'] - ($dataset['returned_quantity'] + $dataset['damaged_quantity']);
                $dataset['net_sales'] = $dataset['sales'] - ($dataset['returned_sales'] + $dataset['damaged_sales']) - $dataset['discount'];
                $whole_net_profit = $dataset['sales_after_discount'] - ( $product['purchase_amount_excluding_tax']  * $dataset['sales_quantity']);
                $one_product_profit = $whole_net_profit/$dataset['sales_quantity'];
                $dataset['net_profit'] = $one_product_profit * ($dataset['sales_quantity'] - $dataset['returned_quantity']);

                $single_product_tax = $whole_tax/$dataset['sales_quantity'];
                $dataset['tax'] = $single_product_tax * ($dataset['sales_quantity'] - $dataset['returned_quantity']);
                $dataset['status'] = $product['status'];

                $dataset['barcode'] = $product['barcode']; 
                
                $result[] = $dataset;

                // grand total
                $this->total_sold_quantity += $dataset['sales_quantity'];
                $this->total_sales += $dataset['sales'];
                $this->total_returned_quantity += $dataset['returned_quantity'];
                $this->total_sales_returned += $dataset['returned_sales'];
                $this->total_damaged_quantity += $dataset['damaged_quantity'];
                $this->total_sales_damaged += $dataset['damaged_sales'];
                $this->total_net_sales_quantity += $dataset['net_sales_quantity'];
                $this->total_net_sales += $dataset['net_sales'];
                $this->total_discount += $dataset['discount'];
                $this->total_tax += $dataset['tax'];
                $this->total_net_profit += $dataset['net_profit'];
                
            }
        }
        $this->rows_count = count($result) + 3;
        return collect($result);
    }

    public function headings(): array
    {
        return [
            trans('PRODUCT CODE'),
            trans('NAME'),
            trans('CATEGORY'),
            trans('PURCHASE PRICE'), // Purchase Price Without Tax
            trans('SALE PRICE'), // Sale Price Without Tax
            trans('SALES QUANTITY'), 
            trans('TOTAL SALES'),
            trans('RETURNED QUANTITY'),
            trans('RETURNED SALES'),
            trans('DAMAGED QUANTITY'),
            trans('DAMAGED SALES'),
            trans('NET SALES QUANTITY'),
            trans('NET SALES'),
            trans('TOTAL DISCOUNT'),
            trans('TOTAL TAX'),
            trans('NET PROFIT'),
            trans('STATUS'),
            trans('BARCODE'),
        ];
    }

    public function map($product): array
    {
    
        
        return [
            $product['product_code'],
            $product['product_name'],
            $product['category'],
            $product['purchase_price'],
            $product['sale_price'],
            $product['sales_quantity'],
            $product['sales'], // total sales price
            $product['returned_quantity'],
            $product['returned_sales'],
            $product['damaged_quantity'],
            $product['damaged_sales'],
            $product['net_sales_quantity'],
            $product['net_sales'],
            $product['discount'],
            $product['tax'],
            $product['net_profit'],
            ($product['status'] == 1) ? 'Active' : 'Inactive',
            $product['barcode'],
        ];
    }

    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                    $event->sheet->getDelegate()->setCellValue("A$this->rows_count",trans('Total'));
                    $event->sheet->getDelegate()->setCellValue("F$this->rows_count", $this->total_sold_quantity);
                    $event->sheet->getDelegate()->setCellValue("G$this->rows_count", $this->total_sales);    
                    $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->total_returned_quantity);    
                    $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->total_sales_returned);    
                    $event->sheet->getDelegate()->setCellValue("J$this->rows_count", $this->total_damaged_quantity);    
                    $event->sheet->getDelegate()->setCellValue("K$this->rows_count", $this->total_sales_damaged);    
                    $event->sheet->getDelegate()->setCellValue("L$this->rows_count", $this->total_net_sales_quantity);    
                    $event->sheet->getDelegate()->setCellValue("M$this->rows_count", $this->total_net_sales);    
                    $event->sheet->getDelegate()->setCellValue("N$this->rows_count", $this->total_discount);    
                    $event->sheet->getDelegate()->setCellValue("O$this->rows_count", $this->total_tax);    
                    $event->sheet->getDelegate()->setCellValue("P$this->rows_count", $this->total_net_profit);    
                },
        ];
    }

    public function styles(Worksheet $sheet)
    {

        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
        ];
    }

}
