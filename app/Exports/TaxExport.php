<?php

namespace App\Exports;

use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProducts;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Taxcode;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Discountcode;
use App\Models\OrderProduct;
use App\Models\Store;


use App\Models\ReturnOrdersProducts;
use App\Http\Resources\ProductResource;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class TaxExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;

    public $total_quantity=0,$total_tobacco_tax=0,$total_vat_tax=0,$tot_other_tax=0,$total_tax=0,$total_price=0;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->rows_count = 0;
        $this->sale_amount_excluding_tax = 0;
        $this->purchase_amount_excluding_tax = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $supplier = $this->data['supplier'];
        $product = $this->data['product'];
        $category = $this->data['category'];
        $tax_code = $this->data['tax_code'];
        $discount_code = $this->data['discount_code'];
        $product_type = $this->data['product_type'];
        $status = $this->data['status'];
        $created_by = $this->data['created_by'];

        $query =OrderProduct::query()
            ->select('orders.id as order_id', 'orders.slack as order_slack', 'orders.value_date', 'orders.order_number', 'orders.reference_number', 'order_products.product_id', 'order_products.product_code', 'order_products.name', 'products.name_ar', 'order_products.quantity', 'order_products.sale_amount_excluding_tax', 'order_products.tax_components', 'order_products.tobacco_tax_components', 'order_products.total_after_discount','orders.payment_method','users.fullname')
            ->leftJoin('orders','orders.id','order_products.order_id')
            ->leftJoin('products','products.id','order_products.product_id')
            ->leftJoin('category','category.id','products.category_id')
            ->leftJoin('suppliers','suppliers.id','products.supplier_id')
            ->leftJoin('tax_codes','tax_codes.id','products.tax_code_id')
            ->leftJoin('discount_codes','discount_codes.id','products.discount_code_id')
            ->leftJoin('users','users.id','orders.created_by')
            ->where('order_products.tax_amount','>',0)
            ->where('orders.store_id','=',session('store_id'));

        $query2 = InvoiceProduct::query()
            ->select(
                'invoices.id as invoice_id', 'invoices.slack as invoice_slack', 'invoices.invoice_date', 'invoices.invoice_number', 'invoice_products.product_id', 'invoice_products.product_code', 'invoice_products.name','products.name_ar', 'invoice_products.quantity', 'invoice_products.amount_excluding_tax', 'invoice_products.tax_amount', 'tax_codes.tax_code', 'invoice_products.total_amount', 'tax_codes.label','users.fullname')
            ->leftJoin('invoices','invoices.id','invoice_products.invoice_id')
            ->leftJoin('products','products.id','invoice_products.product_id')
            ->leftJoin('category','category.id','products.category_id')
            ->leftJoin('suppliers','suppliers.id','products.supplier_id')
            ->leftJoin('tax_codes','tax_codes.id','invoice_products.tax_code_id')
            ->leftJoin('users','users.id','invoices.created_by')
            ->where('invoice_products.tax_amount','>',0)
            ->where('invoices.store_id','=',session('store_id'));

        if ($from_created_date != '') {
            $query = $query->where( DB::raw('DATE(orders.value_date)'), '>=', $from_created_date);
            $query2 = $query2->where('invoices.invoice_date', '>=', $from_created_date);
        }
        if ($to_created_date != '') {
            $query = $query->where(DB::raw('DATE(orders.value_date)'), '<=', $to_created_date);
            $query2 = $query2->where('invoices.invoice_date', '<=', $to_created_date);
        }
        if ($supplier != '') {
            $query = $query->where('suppliers.slack', $supplier);
            $query2 = $query2->where('suppliers.slack', $supplier);
        }
        if ($product != '') {
            $query = $query->where('products.slack', $product);
            $query2 = $query2->where('products.slack', $product);
        }
        if ($category != '') {
            $query = $query->where('category.slack', $category);
            $query2 = $query2->where('category.slack', $category);
        }
        if ($tax_code != '') {
            $query = $query->where('tax_codes.slack', $tax_code);
            $query2 = $query2->where('tax_codes.slack', $tax_code);
        }
        if ($discount_code != '') {
            $query = $query->where('discount_codes.slack', $discount_code);
        }
        if (isset($status)) {
            $query = $query->where('products.status', $status);
            $query2 = $query2->where('products.status', $status);
        }
        if($created_by!=''){
            $user_id= User::select('id')->where('slack',$created_by)->active()->first()->id;
            $query = $query->where('orders.created_by', $user_id);
            $query2 = $query2->where('invoices.created_by', $user_id);
        }
        if($product_type == 'billing_products'){
            $query = $query->where('products.is_ingredient', 0);
            $query2 = $query2->where('products.is_ingredient', 0);
        }
        if($product_type == 'ingredients'){
            $query = $query->where('products.is_ingredient', 1);
            $query2 = $query2->where('products.is_ingredient', 1);
        }
        $order_products = $query->orderBy('orders.id','DESC')->get();
        $invoice_products = $query2->orderBy('invoices.id','DESC')->get();

        $is_tobacco = Store::select('tobacco_tax_val')->where('id',session('store_id'))->first();
        $taxcodes = TaxcodeModel::select('id','slack', 'tax_code', 'label')->sortLabelAsc()->get();
        
        $result = [];
        $total_order_tax_array = [];
        $total_order_tax_array['TOBACCO'] = 0;
        foreach ($taxcodes as $taxcode) {
            $total_order_tax_array[$taxcode->tax_code] = 0;
        }
        $total_order_tax_array['TOT_TAX'] = 0;
        $total_order_tax_array['TOT_PRICE'] = 0;

        //POS ORDER
        foreach ($order_products as $product){
            $total_tax_sum = 0;

            $data_array = [];
            $data_array['date']= $product->value_date!=''?Carbon::parse($product->value_date)->format('d-m-Y'):'-';
            $data_array['reference_no']=$product->reference_number;
            $data_array['order_invoice_no']=$product->order_number;
            $data_array['type']='ORDER';
            
            if(isset($product->name_ar) && $product->name_ar != ""){
                $data_array['product_name']=$product->name." (".$product->name_ar.") ";
            }else{
                $data_array['product_name']=$product->name;
            }

            //Return Product Tax
            $terurn_products = ReturnOrdersProducts::select('order_return_product.tax_components','order_return_product.tobacco_tax_components','order_return_product.total_after_discount','order_return_product.tax_amount','order_return_product.quantity')
                ->leftJoin('order_return','order_return.id','order_return_product.return_order_id')
                ->where('order_return.order_slack',$product->order_slack)
                ->where('order_return_product.product_id',$product->product_id)
                ->get();

            $return_order_tax_array = [];
            $order_tax_array = [];


            $return_order_tax_array['TOBACCO'] = 0;
            $order_tax_array['TOBACCO'] = 0;

            foreach ($taxcodes as $taxcode){
                $return_order_tax_array[$taxcode->tax_code] = 0;
                $order_tax_array[$taxcode->tax_code] = 0;
            }
            if(!empty($terurn_products)){
                $return_total_qty = 0;
                $total_od_ret_amount = 0;
                foreach ($terurn_products as $return_product){
                    $r_tobacoo_tax_com = json_decode($return_product->tobacco_tax_components,true);
                    $r_other_tax_com = json_decode($return_product->tax_components,true);
                    if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0 && !empty($r_tobacoo_tax_com)){
                        $return_order_tax_array['TOBACCO'] += $r_tobacoo_tax_com[0]['tax_amount'];
                    }
                    if(!empty($r_other_tax_com)){
                        $other_tot_ret_tax = 0;
                        foreach($r_other_tax_com as $tax){
                            foreach ($taxcodes as $taxcode){
                                if($taxcode->label == $tax['tax_type']){
                                    $return_order_tax_array[$taxcode->tax_code] += $tax['tax_amount'];
                                    $other_tot_ret_tax +=$tax['tax_amount'];
                                }
                            }
                        }
                    }
                    $total_od_ret_amount +=$return_product->total_after_discount + $return_order_tax_array['TOBACCO'] + $other_tot_ret_tax;
                    $return_total_qty +=$return_product->quantity;
                }
            }

            $data_array['quantity']=$product->quantity - $return_total_qty;
            $this->total_quantity += $data_array['quantity'];
            if($data_array['quantity']<0.0001){
                continue;
            }
            $data_array['sale_price']=$product->sale_amount_excluding_tax;
            $data_array['payment_type']=$product->payment_method;

            //Tax
            $tobacco_tax_com = json_decode($product->tobacco_tax_components,true);
            $other_tax_com = json_decode($product->tax_components,true);
            if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0 && !empty($tobacco_tax_com)){
                $order_tax_array['TOBACCO'] = $tobacco_tax_com[0]['tax_amount'];
                $data_array['TOBACCO'] = $order_tax_array['TOBACCO'] - $return_order_tax_array['TOBACCO'];
                $total_tax_sum += $data_array['TOBACCO'];
                $total_order_tax_array['TOBACCO'] +=$data_array['TOBACCO'];
            }
            if(!empty($other_tax_com)){
                $total_order_tax = 0;
                foreach($other_tax_com as $tax){
                    foreach ($taxcodes as $taxcode){
                        if($taxcode->label == $tax['tax_type']){
                            $order_tax_array[$taxcode->tax_code] += $tax['tax_amount'];
                            $total_order_tax +=$tax['tax_amount'];
                        }
                    }
                }
            }

            foreach ($taxcodes as $taxcode){
                $data_array[$taxcode->tax_code] = $order_tax_array[$taxcode->tax_code] - $return_order_tax_array[$taxcode->tax_code];
                $total_tax_sum +=$data_array[$taxcode->tax_code];
                $total_order_tax_array[$taxcode->tax_code] +=$data_array[$taxcode->tax_code];
            }

            $data_array['total']=$total_tax_sum;
            $total_order_tax_array['TOT_TAX'] += $total_tax_sum;

            $total_od_amount = $product->total_after_discount + $order_tax_array['TOBACCO'] + $total_order_tax;
            $data_array['total_amount'] = $total_od_amount - $total_od_ret_amount;
            $total_order_tax_array['TOT_PRICE'] +=$data_array['total_amount'];
            $data_array['created_by'] = $product->fullname;
            $result[] = $data_array;
        }

        //INVOICES
        foreach ($invoice_products as $product){
            $total_tax_sum = 0;
            $data_array = [];
            $data_array['date']= $product->invoice_date!=''?Carbon::parse($product->invoice_date)->format('d-m-Y'):'-';
            $data_array['reference_no']='';
            $data_array['order_invoice_no']=$product->invoice_number;
            $data_array['type']='INVOICE';
            $data_array['product_name']=$product->name;

            //Return Product Tax
            $terurn_products = InvoiceReturnProducts::select('invoice_return_products.total_after_discount','invoice_return_products.tax_amount','invoice_return_products.quantity','tax_codes.label','tax_codes.tax_code')
                ->leftJoin('invoices_return','invoices_return.id','invoice_return_products.return_invoice_id')
                ->leftJoin('tax_codes','tax_codes.id','invoice_return_products.tax_code_id')
                ->where('invoices_return.invoice_slack',$product->invoice_slack)
                ->where('invoice_return_products.product_id',$product->product_id)
                ->get();

            $return_invoice_tax_array = [];
            $invoice_tax_array = [];
            foreach ($taxcodes as $taxcode){
                $return_invoice_tax_array[$taxcode->tax_code] = 0;
                $invoice_tax_array[$taxcode->tax_code] = 0;
            }

            $r_total_amount = 0;
            $r_total_qty = 0;
            foreach ($terurn_products as $row){
                foreach ($taxcodes as $taxcode){
                    if($taxcode->label == $row->label){
                        $return_invoice_tax_array[$taxcode->tax_code] += $row->tax_amount;
                        $r_total_amount +=$row->total_after_discount + $row->tax_amount;
                    }
                }
                $r_total_qty += $row->quantity;
            }

            $data_array['quantity']=$product->quantity - $r_total_qty;
            $this->total_quantity += $data_array['quantity'];
            if($data_array['quantity']<0.0001){
                continue;
            }
            $data_array['sale_price']=$product->amount_excluding_tax;
            $data_array['payment_type']=' ';
            if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0) {
                $data_array['TOBACCO'] = 0;
            }

            $total_invoice_tax = 0;
            foreach ($taxcodes as $taxcode){
                if($taxcode->label == $product->label){
                    $invoice_tax_array[$taxcode->tax_code] += $product->tax_amount;
                    $total_invoice_tax +=$invoice_tax_array[$taxcode->tax_code];
                }
            }

            foreach ($taxcodes as $taxcode){
                $data_array[$taxcode->tax_code] = $invoice_tax_array[$taxcode->tax_code] - $return_invoice_tax_array[$taxcode->tax_code];
                $total_tax_sum +=$data_array[$taxcode->tax_code];
                $total_order_tax_array[$taxcode->tax_code] +=$data_array[$taxcode->tax_code];
            }
            $data_array['total']=$total_tax_sum;
            $total_order_tax_array['TOT_TAX'] += $total_tax_sum;

            $data_array['total_amount'] = $product->total_amount - $r_total_amount;
            $total_order_tax_array['TOT_PRICE'] +=$data_array['total_amount'];
            $data_array['created_by'] = $product->fullname;
            $result[] = $data_array;
        }

        //SORTING
        foreach ($result as $key => $part) {
            $sort[$key] = strtotime($part['date']);
        }
        if(!empty($result)){
            array_multisort($sort, SORT_ASC, $result);
        }

        //$this->rows_count = $query->count() + $query2->count() + 3;
        //FOR LAST TOTAL ROW
        $data_array = [];
        $data_array['date'] = 'Total';
        $data_array['reference_no']='';
        $data_array['order_invoice_no']='';
        $data_array['type']='';
        $data_array['product_name']='';
        $data_array['quantity']=$this->total_quantity;
        $data_array['sale_price']='';
        $data_array['payment_type']='';
        if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0) {
            $data_array['TOBACCO'] = $total_order_tax_array['TOBACCO'];
        }
        foreach ($taxcodes as $taxcode) {
            $data_array[$taxcode->tax_code] =$total_order_tax_array[$taxcode->tax_code];
        }
        $data_array['total']=$total_order_tax_array['TOT_TAX'];
        $data_array['total_amount'] =$total_order_tax_array['TOT_PRICE'];
        $data_array['created_by'] ='';
        $result[] = $data_array;
        return collect($result);
    }

    public function headings(): array
    {
        $columns = [
            trans('DATE'),
            trans('REFERENCE NO'),
            trans('ORDER NO'),
            trans('TYPE'),
            trans('PRODUCT NAME'),
            trans('QUANTITY'),
            trans('SALE PRICE'),
            trans('PAYMENT TYPE'),
        ];

        $is_tobacco = Store::select('tobacco_tax_val')->where('id',session('store_id'))->first();
        if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0){
            $columns = array_merge($columns,[trans('TOBACCO TAX')]);
        }

        $taxcodes = TaxcodeModel::select('id','slack', 'tax_code', 'label')->sortLabelAsc()->get();
        foreach ($taxcodes as $tax){
            $columns = array_merge($columns,[$tax->tax_code]);
        }
        $columns = array_merge($columns,[trans('TOTAL TAX'),trans('TOTAL PRICE'),trans('CREATED BY')]);
        return [
            $columns
        ];
    }

    public function map($result): array
    {
        return $result;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle("A1:Z1")->getFont()->setBold(true);
                /*$event->sheet->getDelegate()->setCellValue("A$this->rows_count", trans('Total'));*/
            },
        ];
    }
}