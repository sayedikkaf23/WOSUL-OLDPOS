<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Http\Resources\CustomerResource;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaxReturnReportExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $first_day_year_month = $this->data['from_date'];
        $last_day_year_month = $this->data['to_date'];
        
        $logged_in_store_id = $this->data['logged_in_store_id'];
        
        $report_data['tax_details'] = DB::table('tax_codes')->select('id','slack', 'tax_code', 'label', 'total_tax_percentage')
                                ->where('store_id',$logged_in_store_id)->where('is_tax_return',1)
                                ->orderBy('label')->get()->toArray();
            // $purchase_details = DB::select("select tc.id tax_code_id, tc.label tax_label,tc.total_tax_percentage tax_percentage,
            //                         sum(pop.total_amount) total_purchase_amount, sum(tax_amount) total_tax_amount
            //                         from purchase_order_products pop
            //                         join purchase_orders po on po.store_id = 1 and po.id = pop.purchase_order_id
            //                         join tax_codes tc on pop.tax_code_id = tc.id and tc.store_id = 1
            //                         where po.status > 0 and date(pop.created_at) >= '2022-05-13' and date(pop.created_at) <= '2022-05-15'
            //                         group by tax_code_id, pop.total_amount");
            $purchase_details = [];
            $purchase_details['purchase_order_total'] = 0;
            $purchase_details['purchase_return_total'] = 0;
            $purchase_details['purchase_order_total_tax_paid'] = 0;
            
            $sale_details = [];
            $sale_details['sale_order_total'] = 0;
            $sale_details['sale_return_total'] = 0;
            $sale_details['sale_order_total_tax_due'] = 0;
            $total_vat_due = 0;
            foreach($report_data['tax_details'] as $tax_detail){

                /*=========================================== Purchase and return Details ============================================*/
            
                $purchase_details[$tax_detail->id]['purchase_orders'] = DB::select("select 
                                                    COALESCE(sum(pop.total_amount),0) total_purchase_amount, COALESCE(sum(tax_amount),0) total_tax_amount 
                                                    from purchase_order_products pop
                                                    join purchase_orders po on po.store_id = $logged_in_store_id and po.id = pop.purchase_order_id and po.status > 0
                                                    where date(pop.created_at) >= '$first_day_year_month' and date(pop.created_at) <= '$last_day_year_month' 
                                                    and pop.tax_code_id = $tax_detail->id");
                $purchase_details[$tax_detail->id]['purchase_returns'] = DB::select("select 
                                                    COALESCE(sum(srp.total_amount),0) total_purc_ret_amount,COALESCE(sum(srp.tax_amount),0) total_purc_ret_tax
                                                    from stock_return_products srp
                                                    join stock_returns sr on sr.store_id = $logged_in_store_id and sr.id = srp.stock_return_id
                                                    where date(srp.created_at) >= '$first_day_year_month' and date(srp.created_at) <= '$last_day_year_month' 
                                                    and srp.tax_code_id = $tax_detail->id ");
                $purchase_amount = $purchase_details[$tax_detail->id]['purchase_orders'][0]->total_purchase_amount;
                $purchase_tax = $purchase_details[$tax_detail->id]['purchase_orders'][0]->total_tax_amount;
                $purchase_ret_amount = $purchase_details[$tax_detail->id]['purchase_returns'][0]->total_purc_ret_amount;
                $purchase_ret_tax = $purchase_details[$tax_detail->id]['purchase_returns'][0]->total_purc_ret_tax;
                $purchase_tax_paid = round($purchase_tax - $purchase_ret_tax, 2);
                $purchase_details[$tax_detail->id]['tax_paid'] = $purchase_tax_paid;
                $purchase_details[$tax_detail->id]['tax_details'] = $tax_detail;
                $purchase_details['purchase_order_total'] += $purchase_amount;
                $purchase_details['purchase_return_total'] += $purchase_ret_amount;
                $purchase_details['purchase_order_total_tax_paid'] += $purchase_details[$tax_detail->id]['tax_paid'];

                /*=========================================== Sales and return Details ============================================*/
                $sale_details[$tax_detail->id]['sale_orders'] = DB::select("select 
                                                    COALESCE(sum(op.total_amount),0) total_sale_amount, COALESCE(sum(op.tax_amount),0) total_tax_amount 
                                                    from order_products op
                                                    join orders o on o.store_id = $logged_in_store_id and o.id = op.order_id and o.status = 1
                                                    where date(op.created_at) >= '$first_day_year_month' and date(op.created_at) <= '$last_day_year_month' 
                                                        and op.tax_code_id = $tax_detail->id");
                $sale_details[$tax_detail->id]['sale_returns'] = DB::select("select 
                                                    COALESCE(sum(orp.total_amount),0) total_sale_ret_amount,COALESCE(sum(orp.tax_amount),0) total_sale_ret_tax
                                                    from order_return_product orp
                                                    join order_return r on r.store_id = $logged_in_store_id and r.id = orp.return_order_id
                                                    where date(orp.created_at) >= '$first_day_year_month' and date(orp.created_at) <= '$last_day_year_month' 
                                                        and orp.tax_code= '$tax_detail->tax_code' ");
                $sale_amount = $sale_details[$tax_detail->id]['sale_orders'][0]->total_sale_amount;
                $sale_tax = $sale_details[$tax_detail->id]['sale_orders'][0]->total_tax_amount;
                $sale_ret_tax = $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_tax;
                $sale_ret_amount = $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_amount;
                $sale_tax_due = round($sale_tax - $sale_ret_tax, 2);
                $sale_details[$tax_detail->id]['tax_due'] = $sale_tax_due;
                $sale_details[$tax_detail->id]['tax_details'] = $tax_detail;
                $sale_details['sale_order_total'] += $sale_amount;
                $sale_details['sale_return_total'] += $sale_ret_amount;
                $sale_details['sale_order_total_tax_due'] += $sale_details[$tax_detail->id]['tax_due'];
                $total_vat_due += ( $purchase_tax_paid - $sale_tax_due);

            }
            
            $report_data['total_vat_due'] = $total_vat_due;
            $report_data['purchase_details'] = $purchase_details;
            $report_data['sale_details'] = $sale_details;
        // dd($report_data);
        return $report_data;
    }

    public function headings(): array
    {
        return [
            trans('NAME'),
            trans('EMAIL'),
            trans('PHONE'),
            trans('ADDRESS'),
        ];
    }

    public function map($report_data): array
    {
        //dd($report_data['tax_details']);
       // $customer = collect(new CustomerResource($report_data));
        return [
            (isset($report_data['net_vat_due']))?$report_data['net_vat_due']:'',
            (isset($report_data['net_vat_due']))?$report_data['net_vat_due']:'',
            (isset($report_data['net_vat_due']))?$report_data['net_vat_due']:'',
            (isset($report_data['net_vat_due']))?$report_data['net_vat_due']:''
            // (isset($customer['email']))?$customer['email']:'',

        ];
    }
}
