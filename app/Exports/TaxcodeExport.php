<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Taxcode;
use App\Models\Scopes\StoreScope;
use Illuminate\Support\Facades\DB;
use App\Models\Order as OrderModel;

use App\Http\Resources\TaxcodeResource;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ReturnOrders as ReturnOrderModel;


class TaxcodeExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    
    public $from_created_date, $to_created_date, $tax_amount, $net_amount;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        $all_stores =  $this->data['all_stores'] == "false" ? false : true;
           
        // $query = DB::select("WITH RECURSIVE cte AS ( SELECT id, store_level_total_tax_components, 1 element, JSON_UNQUOTE(JSON_EXTRACT(store_level_total_tax_components, '$[0].tax_type')) tax_type, JSON_UNQUOTE(JSON_EXTRACT(store_level_total_tax_components, '$[0].tax_percentage')) tax_percentage FROM orders UNION ALL SELECT id, store_level_total_tax_components, 1 + element, JSON_UNQUOTE(JSON_EXTRACT(store_level_total_tax_components, CONCAT('$[', element, '].tax_type'))), JSON_UNQUOTE(JSON_EXTRACT(store_level_total_tax_components, CONCAT('$[', element, '].tax_percentage'))) FROM cte WHERE element < JSON_LENGTH(store_level_total_tax_components) ) SELECT DISTINCT (tax_type), tax_percentage FROM cte");  
        
        $taxcodes = collect();

        if($all_stores == true)
        {
            $query = OrderModel::withoutGlobalScopes()->closed();
        }
        else
        {
            $query = OrderModel::closed();
        }     
        
        if($this->from_created_date != ''){
            $from_created_date = strtotime($this->from_created_date);
            $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('created_at', '>=', $from_created_date);
        }

        if($this->to_created_date != ''){
            $to_created_date = strtotime($this->to_created_date);
            $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('created_at', '<=', $to_created_date);
        }

        $order_data = $query->get();    
        
        foreach($order_data as $order)
        {
            $tax_components = json_decode($order->store_level_total_tax_components) ;
            if($tax_components != null)
            {
                foreach($tax_components as $tax_component)
                {
                    $taxcodes->add($tax_component);
                }     
            }            
        }

        $taxcodes = $taxcodes->unique('tax_percentage');

        foreach($taxcodes as $key => $taxcode)
        {
            if($taxcode->tax_percentage == 0)
            {
                $taxcodes->pull($key);
            }
        }

        $tax_codes = $taxcodes;

        $taxes = [];
        $total_tax_amount = [];

        foreach($order_data as $index => $order)
        {
            $tax_components = json_decode($order->store_level_total_tax_components);
            if($tax_components != null)
            {
                foreach($tax_components as $tax_component)
                {
                    $taxes["$tax_component->tax_percentage"]['tax_name'][$index] = $tax_component->tax_type;
                    $taxes["$tax_component->tax_percentage"]['tax_percentage'][$index] = $tax_component->tax_percentage;
                    $taxes["$tax_component->tax_percentage"]['tax_amount'][$index] = $tax_component->tax_amount;
                }
            }
        }

        // $query = Taxcode::query();

        // if($all_stores == 'true')
        // {
        //     $query = Taxcode::withoutGlobalScopes()->query();          
        // }
        // else
        // {
        //     $query = Taxcode::query();          
        // }

        // if(isset($status)){
        //     $query = $query->where('tax_codes.status', $status);
        // }

        // if($all_stores == true)
        // {
        //     $tax_codes = $query->withoutGlobalScopes()->get();
        // }
        // else
        // {
        //     $tax_codes = $query->get();
        // }        
 
        // $this->tax_amount = [];
        // $this->net_amount = [];

        if(isset($tax_codes)){
            foreach($tax_codes as $tax_code){

                // if($all_stores == true)
                // {
                //     $query = OrderModel::withoutGlobalScopes()->closed();

                //     if($this->to_created_date != '')
                //     {
                //         $query = $query->where('orders.created_at', '<=', $this->to_created_date);
                //     }
                //     if($this->from_created_date != '')
                //     {
                //         $query = $query->where('orders.created_at', '>=', $this->from_created_date);
                //     }                    
                // }
                // else
                // {
                //     $query = OrderModel::closed();

                //     if($this->to_created_date != '')
                //     {
                //         $query = $query->where('orders.created_at', '<=', $this->to_created_date);
                //     }
                //     if($this->from_created_date != '')
                //     {
                //         $query = $query->where('orders.created_at', '>=', $this->from_created_date);
                //     }       
                // }
                
                // $total_order_tax_amount = $query->sum(DB::raw("CAST((JSON_EXTRACT (orders.store_level_total_tax_components, JSON_UNQUOTE( REPLACE( JSON_SEARCH(orders.store_level_total_tax_components, 'one', '$tax_code->tax_type'), 'tax_type', 'tax_amount' ) ) ) ) AS DECIMAL(10, 2) )"));
                
                $total_tax_amount[$tax_code->tax_percentage] = round(array_sum($taxes["$tax_code->tax_percentage"]['tax_amount']), 2);

                $this->tax_amount[$tax_code->tax_percentage] = $total_tax_amount[$tax_code->tax_percentage];
                
                //  $total_order_tax_amount = OrderModel::withoutGlobalScopes()->closed()
                // ->where('orders.store_level_tax_code_id',$tax_code['id'])
                // ->where('orders.created_at', '<=', $this->to_created_date)
                // ->where('orders.created_at', '>=', $this->from_created_date)
                // ->sum('orders.total_tax_amount');
                
                // $total_return_order_tax_amount =  ReturnOrderModel::withoutGlobalScopes()->closed()
                // ->where('order_return.store_level_tax_code_id',$tax_code['id'])
                // ->where('order_return.created_at', '<=', $this->to_created_date)
                // ->where('order_return.created_at', '>=', $this->from_created_date)
                // ->sum('order_return.total_tax_amount');                

                // $this->tax_amount[$tax_code['slack']] = $total_order_tax_amount - $total_return_order_tax_amount; 

                // $total_order_net_amount = OrderModel::withoutGlobalScopes()->closed()
                // ->where('orders.store_level_tax_code_id',$tax_code['id'])
                // ->where('orders.created_at', '<=', $this->to_created_date)
                // ->where('orders.created_at', '>=', $this->from_created_date)
                // ->sum('orders.total_order_amount');

                // $total_return_order_net_amount = ReturnOrderModel::withoutGlobalScopes()->closed()
                // ->where('order_return.store_level_tax_code_id',$tax_code['id'])
                // ->where('order_return.created_at', '<=', $this->to_created_date)
                // ->where('order_return.created_at', '>=', $this->from_created_date)
                // ->sum('order_return.total_order_amount');

                // $this->net_amount[$tax_code['slack']] = $total_order_net_amount - $total_return_order_net_amount;
            }
        }

        return $tax_codes;
    }

    public function headings(): array
    {
        return [
     //       trans('TAX CODE'),
            trans('TAX'),
            trans('TAX PERCENTAGE'),
            // trans('DESCRIPTION'),
            // trans('CREATED AT'),
            // trans('CREATED BY'),
            // trans('UPDATED AT'),
            // trans('UPDATED BY'),
            trans('TAX AMOUNT'),
            // trans('NET AMOUNT'),
            // trans('STATUS'),
            trans('FROM'),
            trans('TO'),
        ];
    }

    public function map($tax_code): array
    {
        // $tax_code = collect(new TaxcodeResource($tax_code));
        return [
 //           (isset($tax_code['tax_code']))?$tax_code['tax_code']:'',
            (isset($tax_code->tax_type)) ? $tax_code->tax_type : '',
            (isset($tax_code->tax_percentage))?$tax_code->tax_percentage:'',
            // (isset($tax_code['total_tax_percentage']))?$tax_code['total_tax_percentage']:'',
            // (isset($tax_code['description']))?$tax_code['description']:'',            
            // (isset($tax_code['created_at_label']))?$tax_code['created_at_label']:'',
            // (isset($tax_code['created_by']['fullname']))?$tax_code['created_by']['fullname']:'',
            // (isset($tax_code['updated_at_label']))?$tax_code['updated_at_label']:'',
            // (isset($tax_code['updated_by']['fullname']))?$tax_code['updated_by']['fullname']:''
            (isset($tax_code->tax_type) && $this->tax_amount["$tax_code->tax_percentage"] != null)?$this->tax_amount["$tax_code->tax_percentage"]:'0',
            // (isset($tax_code['slack']) && $this->net_amount[$tax_code['slack']] != null)?$this->net_amount[$tax_code['slack']]:'0',
            // (isset($tax_code['status']['label']))?$tax_code['status']['label']:'',
            (isset($this->from_created_date) && $this->from_created_date != null) ? Carbon::parse($this->from_created_date)->toDateString() : '',
            (isset($this->to_created_date) && $this->to_created_date != null) ? Carbon::parse($this->to_created_date)->toDateString() : ''
        ];
    }
}
