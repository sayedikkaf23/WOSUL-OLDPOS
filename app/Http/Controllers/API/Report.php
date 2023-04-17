<?php

namespace App\Http\Controllers\API;

use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProducts;
use App\Models\OrderProduct;
use App\Models\Store;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\User;
use Exception;
use Validator;

use Illuminate\Support\Str;

use App\Imports\DataImport;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReturnOrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

use App\Exports\UserExport;
use App\Exports\UserWiseSalesExport;
use App\Exports\ProductExport;
use App\Exports\ProductWiseSalesExport;
use App\Exports\OrderExport;
use App\Exports\CustomerExport;
use App\Exports\CategoryExport;
use App\Exports\DiscountcodeExport;
use App\Exports\StoreExport;
use App\Exports\SupplierExport;
use App\Exports\TaxcodeExport;
use App\Exports\PurchaseOrderExport;
use App\Exports\InvoiceExport;
use App\Exports\QuotationExport;
use App\Exports\TransactionExport;
use App\Exports\ReturnOrderExport;
use App\Exports\ReturnExport;
use App\Exports\POSOrderExport;
use App\Exports\NewsSubscriptionExport;
use App\Exports\StockStatusExport;
use App\Exports\QuantityPurchaseExport;
use App\Exports\SupplierInvoiceReport;
use App\Exports\InvoiceReturnExport;
use App\Exports\SalesExport;
use App\Exports\DamageOrderExport;
use App\Exports\InventoryExport;
use App\Exports\TaxReturnReportExport;
use App\Exports\TaxExport;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Category as CategoryModel;
use App\Models\Product as ProductModel;
use App\Models\Order as OrderModel;
use App\Models\ReturnOrders as ReturnOrdersModel;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Transaction as TransactionModel;
use App\Models\ReturnOrdersProducts;
use Illuminate\Support\Carbon;
use App\Models\Store as StoreModel;

use App\Http\Resources\ProductResource;
use App\Http\Resources\DamageResource;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;
class Report extends Controller
{

    public function __construct(Request $request)
    {
        $this->view_path = Config::get('constants.upload.reports.view_path');
        $this->date = ($request->date != '') ? $request->date : date("Y-m");
    }

    public function user_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'role' => $request->role,
                'status' => $request->status,
            ];

            $filename = 'user_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new UserExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "User report generated successfully",
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

    public function user_wise_sales_report(Request $request)
    {
        try {

            $params = [
                'store' => $request->store,
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'user_wise_sales_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new UserWiseSalesExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "User Wise Sale report generated successfully",
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

    public function taxReturnReport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date_period_type' => 'required',
                'year' => 'required',
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }
            $download_link = '';
            $first_day_year_month = 'current_date';
            $last_day_year_month = 'current_date';
            $data['logged_in_store_id'] = $logged_in_store_id = session('store_id');
            $data['store_details'] = StoreModel::select(['id', 'name', 'vat_number'])->where('id', session('store_id'))->first()->toArray();
            $year = $request->year;
            $data['selected_date_period_type'] = $request->date_period_type;
            $data['selected_month_name'] = $request->selected_month_name ?? '';
            $data['selected_period_name'] = $request->selected_period_name ?? '';
            $data['selected_year'] = $year;
            if (isset($request->date_period_type) && !empty($request->date_period_type)) {
                $date_period_type = $request->date_period_type;
                if ($date_period_type == 1) { // Monthly type
                    if (isset($request->period_monthly) && !empty($request->period_monthly)) {
                        $period_monthly = $year . '-' . $request->period_monthly;
                        $year_month = date('Y-m', strtotime($period_monthly));
                        $first_day_year_month = $year_month . '-01';
                        $last_day_year_month = \Carbon\Carbon::parse($year_month)->endOfMonth()->toDateString();
                    }
                }

                if ($date_period_type == 2) { // Quarterly type
                    if (isset($request->period_quarterly) && !empty($request->period_quarterly)) {
                        $period_quarterly = $request->period_quarterly;
                        $start_month = $period_quarterly;
                        $start_month_year = $year . '-' . $start_month . '-01';
                        $first_day_year_month = date('Y-m-d', strtotime($start_month_year));

                        $end_month = $start_month + 2;
                        $end_month_year = $year . '-' . $end_month;
                        $end_month_year = date('Y-m', strtotime($end_month_year));
                        $last_day_year_month = \Carbon\Carbon::parse($end_month_year)->endOfMonth()->toDateString();
                        // dd($first_day_year_month, $last_day_year_month);
                    }
                }
            }
            $data['from_date'] = $first_day_year_month;
            $data['to_date'] = $last_day_year_month;
            $data['tax_details'] = DB::table('tax_codes')->select('id', 'slack', 'tax_code', 'label', 'total_tax_percentage')
                ->where('tax_code','!=','NO_TAX')
                ->where('store_id', $logged_in_store_id) // ->where('is_tax_return', 1)
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
            foreach ($data['tax_details'] as $tax_detail) {

                /*=========================================== Purchase and return Details ============================================*/

                $purchase_details[$tax_detail->id]['purchase_orders'] = DB::select("select 
                                                    COALESCE(sum(pop.total_amount),0) total_purchase_amount, COALESCE(sum(tax_amount),0) total_tax_amount 
                                                    from purchase_order_products pop
                                                    join purchase_orders po on po.store_id = $logged_in_store_id and po.id = pop.purchase_order_id and po.status > 0
                                                    where date(po.order_date) >= '$first_day_year_month' and date(po.order_date) <= '$last_day_year_month' 
                                                    and pop.tax_code_id = $tax_detail->id and po.status = 4");
                $purchase_details[$tax_detail->id]['purchase_returns'] = DB::select("select 
                                                    COALESCE(sum(srp.total_amount),0) total_purc_ret_amount,COALESCE(sum(srp.tax_amount),0) total_purc_ret_tax
                                                    from stock_return_products srp
                                                    join stock_returns sr on sr.store_id = $logged_in_store_id and sr.id = srp.stock_return_id
                                                    where date(sr.return_date) >= '$first_day_year_month' and date(sr.return_date) <= '$last_day_year_month' 
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
                // "select sum(a.total_sale_amount) total_sale_amount, sum(a.total_tax_amount) total_tax_amount
                // from 
                // (select o.id, COALESCE(o.total_order_amount,0) total_sale_amount, COALESCE(o.total_tax_amount,0) total_tax_amount 
                // from order_products op 
                // join orders o on o.store_id = 1 and o.id = op.order_id and o.status in (1,5,6) 
                // where date(op.created_at) >= '2022-09-01' and date(op.created_at) <= '2022-09-30'  and op.tax_code_id = 2
                // group by o.id) as a;";
                // $sale_orders = DB::select("select o.id1, COALESCE(o.total_order_amount,0) total_sale_amount, store_level_total_tax_components 
                //                                 from order_products op 
                //                                 join orders o on o.store_id = $logged_in_store_id and o.id = op.order_id and o.status in (1,6) 
                //                                 where date(o.value_date) >= '$first_day_year_month' and date(o.value_date) <= '$last_day_year_month' 
                //                                 and op.tax_code_id = $tax_detail->id
                //                                 group by o.id");
                $sale_orders = DB::select("select o.id, COALESCE(o.total_order_amount,0) total_sale_amount, op.tax_code_id, op.tax_components, 
                                                op.tobacco_tax_components, op.total_after_discount 
                                            from order_products op                       
                                            join orders o on o.store_id = $logged_in_store_id and o.id = op.order_id and o.status in (1,6)
                                            where date(o.value_date) >= '$first_day_year_month' and date(o.value_date) <= '$last_day_year_month'
                                            and op.tax_code_id = $tax_detail->id order by o.id");

                $total_sale_amount = $total_tax_amount = $all_tax_amount = 0;
                // foreach($sale_orders as $sale_order){
                //     $store_level_total_tax_components = json_decode($sale_order->store_level_total_tax_components);
                //     if(isset($store_level_total_tax_components[0]->tax_type) && isset($store_level_total_tax_components[0]->tax_amount)){
                //         if($store_level_total_tax_components[0]->tax_type == $tax_detail->label){
                //             $total_tax_amount += $store_level_total_tax_components[0]->tax_amount;
                //         }
                //     }
                //     $total_sale_amount += $sale_order->total_sale_amount;
                // }
                if(!empty($sale_orders)){
                    foreach($sale_orders as $sale_order){
                        $tax_components = json_decode($sale_order->tax_components);
                        $tobacco_tax_components = json_decode($sale_order->tobacco_tax_components);
                        if(isset($tax_components[0]->tax_name_id) && isset($tax_components[0]->tax_amount)){
                            $total_tax_amount += $tax_components[0]->tax_amount;
                            $all_tax_amount = $tax_components[0]->tax_amount;
                            if(isset($tobacco_tax_components[0])  && !empty($tobacco_tax_components[0]) && isset($tobacco_tax_components[0]->tax_amount)){
                                $all_tax_amount += $tobacco_tax_components[0]->tax_amount;
                            }
                        }
                        $total_sale_amount += $all_tax_amount + $sale_order->total_after_discount;
                    }
                }
                // dump($tax_detail->id.'---$$$ total_tax_amount ='.$total_tax_amount.' total_sale_amount = '.$total_sale_amount);
                $invoice_orders = DB::select("select COALESCE(sum(ip.total_amount),0) total_sale_amount_invoice, COALESCE(sum(ip.tax_amount),0) total_tax_amount_invoice
                                    from invoice_products ip 
                                    join invoices i on i.store_id = $logged_in_store_id and i.id = ip.invoice_id and i.status in (3,7,8) 
                                    where date(i.invoice_date) >= '$first_day_year_month' and date(i.invoice_date) <= '$last_day_year_month' and ip.tax_code_id = $tax_detail->id");

                if(!empty($invoice_orders) && isset($invoice_orders[0])){
                    $total_sale_amount += $invoice_orders[0]->total_sale_amount_invoice;
                    $total_tax_amount += $invoice_orders[0]->total_tax_amount_invoice;
                }
                // dump('$total_sale_amount = '.$total_sale_amount. ' $total_tax_amount = '.$total_tax_amount);
                // dump('total_sale_amount_invoice ='.$invoice_orders[0]->total_sale_amount_invoice. ' total_tax_amount_invoice ='.$invoice_orders[0]->total_tax_amount_invoice);
                $sale_details[$tax_detail->id]['sale_orders'][0]['total_sale_amount'] = $total_sale_amount;
                $sale_details[$tax_detail->id]['sale_orders'][0]['total_tax_amount'] = $total_tax_amount;
                $sale_details[$tax_detail->id]['sale_returns'] = DB::select("select 
                                                    COALESCE(sum(orp.total_amount),0) total_sale_ret_amount,COALESCE(sum(orp.tax_amount),0) total_sale_ret_tax
                                                    from order_return_product orp
                                                    join order_return r on r.store_id = $logged_in_store_id and r.id = orp.return_order_id
                                                    where date(r.value_date) >= '$first_day_year_month' and date(r.value_date) <= '$last_day_year_month' 
                                                        and orp.tax_code_id= $tax_detail->id ");

                $invoices_returns = DB::select("select 
                                                COALESCE(sum(irp.total_amount),0) total_invoice_ret_amount,COALESCE(sum(irp.tax_amount),0) total_invoice_ret_tax
                                                from invoice_return_products irp
                                                join invoices_return r on r.store_id = $logged_in_store_id and r.id = irp.return_invoice_id
                                                where date(r.invoice_date) >= '$first_day_year_month' and date(r.invoice_date) <= '$last_day_year_month' 
                                                    and irp.tax_code_id= $tax_detail->id ");
                                                        
                $sale_amount = $sale_details[$tax_detail->id]['sale_orders'][0]['total_sale_amount'];
                $sale_tax = $sale_details[$tax_detail->id]['sale_orders'][0]['total_tax_amount'];
                $sale_ret_tax = $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_tax;
                $sale_ret_amount = $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_amount;
                // dump('$sale_ret_tax = '.$sale_ret_tax);
                if(isset($invoices_returns[0]) && !empty($invoices_returns[0])){
                    $sale_ret_tax += $invoices_returns[0]->total_invoice_ret_tax;
                    $sale_ret_amount += $invoices_returns[0]->total_invoice_ret_amount;
                    $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_tax += $invoices_returns[0]->total_invoice_ret_tax;
                    $sale_details[$tax_detail->id]['sale_returns'][0]->total_sale_ret_amount += $invoices_returns[0]->total_invoice_ret_amount;
                }
                // dump('$sale_ret_tax2 = '.$sale_ret_tax);
                $sale_tax_due = round($sale_tax - $sale_ret_tax, 2);
                // dump('$sale_tax = '.$sale_tax.' $sale_ret_tax = '. $sale_ret_tax);
                $sale_details[$tax_detail->id]['tax_due'] = $sale_tax_due;
                $sale_details[$tax_detail->id]['tax_details'] = $tax_detail;
                $sale_details['sale_order_total'] += $sale_amount;
                $sale_details['sale_return_total'] += $sale_ret_amount;
                $sale_details['sale_order_total_tax_due'] += $sale_details[$tax_detail->id]['tax_due'];
                $total_vat_due += ($sale_tax_due - $purchase_tax_paid);
            }
            // dd('-----------------------------', $sale_details );
            $data['total_vat_due'] = $total_vat_due;
            $data['purchase_details'] = $purchase_details;
            $data['sale_details'] = $sale_details;

            if (isset($request->report_type) && !empty($request->report_type)) {
                $data['prev_vat_credit'] = $request->prev_vat_credit ?? 0;
                $data['prev_vat_correction'] = $request->prev_vat_correction ?? 0;
                $data['net_vat_due'] = $request->net_vat_due ?? 0;
                //dd($total_vat_due, $request->prev_vat_credit,$request->prev_vat_correction, $request->net_vat_due);
                $report_type = $request->report_type;
                
                if ($report_type == 'pdf') {


                    $view_file = 'report.pdf.tax_return_report';
                    $css_file = 'css/order_thermal_print_invoice.css';
                    $format = [100, 150];
                    $print_logo_path = session('store_logo');
                    if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path))) {
                        $print_logo_path = public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
                        //$print_logo_path = asset('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
                    } else {
                        $print_logo_path = '';
                    }
                    // dd($data);
                    $print_data = view($view_file, ['data' => $data, 'print_logo_path' => $print_logo_path])->render();
                    // echo $print_data; exit;
                    $mpdf_config = [
                        'mode'          => 'utf-8',
                        'format'        => $format,
                        'orientation'   => 'P',
                        'margin_left'   => 7,
                        'margin_right'  => 7,
                        'margin_top'    => 7,
                        'margin_bottom' => 7,
                        'tempDir' => storage_path() . "/pdf_temp"
                    ];

                    $stylesheet = File::get(public_path($css_file));
                    $mpdf = new Mpdf($mpdf_config);
                    $mpdf->curlAllowUnsafeSslRequests = true;
                    $mpdf->SetDisplayMode('real');
                    $mpdf->showImageErrors = true;
                    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                    $mpdf->WriteHTML($print_data);

                    $pdf_filename = "tax_return_report_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                    $view_path = Config::get('constants.upload.order.view_path');
                    $upload_dir = Storage::disk('reports')->getAdapter()->getPathPrefix();
                    $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                    return response()->json($this->generate_response(
                        array(
                            "message" => "Order pdf created successfully",
                            "link" => '/storage/' . session('merchant_id') . '/reports/' . $pdf_filename,
                        ),
                        'SUCCESS'
                    ));
                } elseif ($report_type == 'excel') {


                    $filename = 'tax_return_report123_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

                    Excel::store(
                        new TaxReturnReportExport(
                            [1, 2, 3],
                            [4, 5, 6]
                        ),
                        'public/reports/' . $filename
                    );

                    $download_link = asset($view_path . $filename);
                }
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Data loaded successfully",
                    "data"    => $data,
                    "link" => $download_link,
                ),
                'SUCCESS'
            ));
        }
        catch (\GuzzleHttp\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getResponse()->getReasonPhrase(),
            ];
        }
        
        //  catch (Exception $e) {
        //     return response()->json($this->generate_response(
        //         array(
        //             "message" => $e->getMessage(),
        //             "status_code" => $e->getCode()
        //         )
        //     ));
        // }
    }

    public function taxReturnReportExcel(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date_period_type' => 'required',
                'year' => 'required',
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }
            $first_day_year_month = 'current_date';
            $last_day_year_month = 'current_date';
            $data['logged_in_store_id'] = $logged_in_store_id = session('store_id');
            $data['store_details'] = StoreModel::select(['id', 'name', 'vat_number'])->where('id', session('store_id'))->first()->toArray();
            $year = $request->year;
            $data['selected_date_period_type'] = $request->date_period_type;
            $data['selected_month_name'] = $request->selected_month_name ?? '';
            $data['selected_period_name'] = $request->selected_period_name ?? '';
            $data['selected_year'] = $year;
            if (isset($request->date_period_type) && !empty($request->date_period_type)) {
                $date_period_type = $request->date_period_type;
                if ($date_period_type == 1) { // Monthly type
                    if (isset($request->period_monthly) && !empty($request->period_monthly)) {
                        $period_monthly = $year . '-' . $request->period_monthly;
                        $year_month = date('Y-m', strtotime($period_monthly));
                        $first_day_year_month = $year_month . '-01';
                        $last_day_year_month = \Carbon\Carbon::parse($year_month)->endOfMonth()->toDateString();
                    }
                }
                if ($date_period_type == 2) { // Quarterly type
                    if (isset($request->period_quarterly) && !empty($request->period_quarterly)) {
                        $period_quarterly = $request->period_quarterly;
                        $start_month = $period_quarterly;
                        $start_month_year = $year . '-' . $start_month . '-01';
                        $first_day_year_month = date('Y-m-d', strtotime($start_month_year));

                        $end_month = $start_month + 2;
                        $end_month_year = $year . '-' . $end_month;
                        $end_month_year = date('Y-m', strtotime($end_month_year));
                        $last_day_year_month = \Carbon\Carbon::parse($end_month_year)->endOfMonth()->toDateString();
                        // dd($first_day_year_month, $last_day_year_month);
                    }
                }
            }
            $data['from_date'] = $first_day_year_month;
            $data['to_date'] = $last_day_year_month;



            if (isset($request->report_type) && !empty($request->report_type)) {
                $data['prev_vat_credit'] = $request->prev_vat_credit ?? 0;
                $data['prev_vat_correction'] = $request->prev_vat_correction ?? 0;
                $data['net_vat_due'] = $request->net_vat_due ?? 0;
                //dd($total_vat_due, $request->prev_vat_credit,$request->prev_vat_correction, $request->net_vat_due);
                $report_type = $request->report_type;

                $filename = 'tax_return_report123_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

                Excel::store(
                    new TaxReturnReportExport(
                        $data
                    ),
                    'public/reports/' . $filename
                );

                $download_link = asset($this->view_path . $filename);
                return response()->json($this->generate_response(
                    array(
                        "message" => "Order excel created successfully",
                        "link" => '/storage/' . session('merchant_id') . '/reports/' . $filename,
                    ),
                    'SUCCESS'
                ));
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Data loaded successfully",
                    "data"    => $data
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

    public function damage_orders(Request $request)
    {
        try {
            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $from_date = $request->from_date;
            $to_date = $request->to_date;



            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];

            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            // DB::enableQueryLog();
            $query = ReturnOrdersProducts::select('*','order_return_product.id')
                ->take($limit)
                ->skip($offset)
                ->createdUser()
                ->ProductJoin()
                ->where("return_type","Damage")
                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })->whereRaw("order_return_product.created_at between '" . $from_date . "' and '" . $to_date . "'");

            $query = $query->get();
            $orders = DamageResource::collection($query);

            $total_q = ReturnOrdersProducts::where("return_type","Damage")
                            ->createdUser()
                            ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                                $query->orderBy($order_by_column, $order_direction);
                            }, function ($query) {
                                $query->orderBy('created_at', 'desc');
                            })

                            ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                                $query->where(function ($query) use ($filter_string, $filter_columns) {
                                    foreach ($filter_columns as $filter_column) {
                                        $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                                    }
                                });
                            })->whereRaw("order_return_product.created_at between '" . $from_date . "' and '" . $to_date . "'");

            $total_count = $total_q->count('order_return_product.id');
            $item_array = [];
            foreach ($orders as $key => $order_data) {
                $order = $order_data->toArray($request);

                $item_array[$key][] = $order['id'];
                if(app()->getLocale() == 'ar'){
                    $item_array[$key][] = $order_data->name_ar!=null?$order_data->name_ar:$order['name'];
                }else{
                    $item_array[$key][] = $order['name'];
                }
                $item_array[$key][] = $order['branch'];
                $item_array[$key][] = $order['branch_reference'];
                $item_array[$key][] = $order['return_type'];
                $item_array[$key][] = $order['added_by'];
                $item_array[$key][] = $order['order_reference'];
                $item_array[$key][] = $order['time'];
                $item_array[$key][] = $order['quantity'];
                $item_array[$key][] = $order['amount'];
                $item_array[$key][] = $order['reason'];
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


    public function product_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'supplier' => $request->supplier,
                'product' => $request->product,
                'category' => $request->category,
                'tax_code' => $request->tax_code,
                'discount_code' => $request->discount_code,
                'product_type' => $request->product_type,
                'status' => $request->status,
            ];

            $filename = 'product_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new ProductExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product report generated successfully",
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

    public function product_wise_sales_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'supplier' => $request->supplier,
                'category' => $request->category,
                'tax_code' => $request->tax_code,
                'discount_code' => $request->discount_code,
                'product_type' => $request->product_type,
                'status' => $request->status,
            ];

            $filename = 'product_wise_sales_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new ProductWiseSalesExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product wise sales report generated successfully",
                    "data" => '',
                    "link" => $download_link
                ),
                'SUCCESS'
            ));
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase(),
        //     ];
        // }
        catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function order_report(Request $request)
    {
        try {
            // $params = [
            //     'from_created_date' => $request->from_created_date,
            //     'to_created_date' => $request->to_created_date,
            //     'status' => $request->status,
            // ];

            $filename = 'order_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            $from_created_date = Carbon::parse($request->from_created_date)->format('Y-m-d');
            $to_created_date = Carbon::parse($request->to_created_date)->format('Y-m-d');
            
            $status = $request->status;
            
            $query = OrderModel::select(['orders.id','orders.slack','order_number','customer_phone','customer_email','sale_amount_subtotal_excluding_tax',
                                    'total_discount_amount','total_after_discount','total_tax_amount', 'total_order_amount','payment_method','orders.status',
                                    'orders.created_at as created_at_label','orders.created_by', 'orders.updated_at as updated_at_label', 'orders.updated_by',
                                    'master_status.label as status_label','user_created.fullname as created_user','user_updated.fullname as updated_user'])
                                ->createdUser()
                                ->updatedUser()
                                ->statusJoin();

            if($from_created_date != ''){
                $query = $query->where('orders.value_date', '>=', $from_created_date);
            }
            if($to_created_date != ''){
                $query = $query->where('orders.value_date', '<=', $to_created_date);
            }
            if(!empty($status)){
                $query = $query->where('orders.status', $status);
            }
            $query = $query->orderBy('orders.created_at','ASC');
            $orders = $query->get();
            $dataArr = [];
            $download_link = asset($this->view_path . $filename);
            $file_path = public_path('/storage/reports/'.$filename);
            $sale_amount_subtotal_excluding_tax = 0;
            $total_discount_amount = 0;
            $total_after_discount = 0;
            $total_tax_amount = 0;
            $total_order_amount = 0;
           
            // foreach($orders as $order){
            //     $order = collect(new OrderResource($order));
            //     $dataArr[] = [
            //         'ORDER NUMBER' => (int)$order['order_number'],
            //         'CUSTOMER PHONE' => $order['customer_phone'],
            //         'CUSTOMER EMAIL' => $order['customer_email'],
            //         'SALE AMOUNT SUBTOTAL EXCLUDING TAX' => (int)$order['sale_amount_subtotal_excluding_tax'],
            //         'TOTAL DISCOUNT AMOUNT' => (int)$order['total_discount_amount'],
            //         'TOTAL AFTER DISCOUNT' => (int)$order['total_after_discount'],
            //         'TOTAL TAX AMOUNT' => (int)$order['total_tax_amount'],
            //         'TOTAL ORDER AMOUNT' => (int)$order['total_order_amount'],
            //         'PAYMENT METHOD' => $order['payment_method'],
            //         'STATUS' => $order['status']['label'],
            //         'CREATED AT' => $order['created_at_label'],
            //         'CREATED BY' => $order['created_by']['fullname'],
            //         'UPDATED AT' => $order['updated_at_label'],
            //         'UPDATED BY' => isset($order['updated_by']['fullname'])?$order['updated_by']['fullname']:''
            //     ];
            // }
            foreach($orders as $order){
                //dd(Carbon::parse($order->created_at_label)->format(config("app.date_time_format")) );
                $dataArr[] = [
                    trans('ORDER NUMBER') => (int)$order->order_number,
                    trans('CUSTOMER PHONE') => $order->customer_phone,
                    trans('CUSTOMER EMAIL') => $order->customer_email,
                    trans('SALE AMOUNT SUBTOTAL EXCLUDING TAX') => (float)$order->sale_amount_subtotal_excluding_tax,
                    trans('TOTAL DISCOUNT AMOUNT') => (float)$order->total_discount_amount,
                    trans('TOTAL AFTER DISCOUNT') => (float)$order->total_after_discount,
                    trans('TOTAL TAX AMOUNT') => (float)$order->total_tax_amount,
                    trans('TOTAL ORDER AMOUNT') => (float)$order->total_order_amount,
                    trans('PAYMENT METHOD') => $order->payment_method,
                    trans('STATUS') => $order->status_label,
                    trans('CREATED AT') => ($order->created_at_label != null) ? Carbon::parse($order->created_at_label)->format(config("app.date_time_format")) : null,
                    trans('CREATED BY') => $order->created_user,
                    trans('UPDATED AT') => ($order->updated_at_label != null) ? Carbon::parse($order->updated_at_label)->format(config("app.date_time_format")) : null,
                    trans('UPDATED BY') => $order->updated_user
                ];
                $sale_amount_subtotal_excluding_tax += (float)$order->sale_amount_subtotal_excluding_tax;
                $total_discount_amount += (float)$order->total_discount_amount;
                $total_after_discount += (float)$order->total_after_discount;
                if($order->status!=6){
                    $total_tax_amount += (float)$order->total_tax_amount;
                    $total_order_amount += (float)$order->total_order_amount;
                }
            }
            $dataArr[] = [
                'ORDER NUMBER' => trans('TOTAL'),
                'CUSTOMER PHONE' => null,
                'CUSTOMER EMAIL' => null,
                'SALE AMOUNT SUBTOTAL EXCLUDING TAX' => $sale_amount_subtotal_excluding_tax,
                'TOTAL DISCOUNT AMOUNT' => $total_discount_amount,
                'TOTAL AFTER DISCOUNT' => $total_after_discount,
                'TOTAL TAX AMOUNT' => $total_tax_amount, 
                'TOTAL ORDER AMOUNT' => $total_order_amount,
                'PAYMENT METHOD' => null,
                'STATUS' => null,
                'CREATED AT' => null,
                'CREATED BY' => null, 
                'UPDATED AT' => null, 
                'UPDATED BY' => null
            ];
            SimpleExcelWriter::create($file_path)->addRows($dataArr);
            // Excel::store( $dataArr,'public/reports/' . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Order report generated successfully",
                    "data" => '',
                    "link" => $download_link
                ),
                'SUCCESS'
            ));
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase(),
        //     ];
        // }
        catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    public function damage_order_report(Request $request)
    {
        try {
            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'store_code' => $request->logged_user_store_code
            ];
            $filename = 'damage_order_report' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';
            Excel::store(
                new DamageOrderExport(
                    $params
                ),
                'public/reports/' . $filename
            );
            $download_link = asset($this->view_path . $filename);
            return response()->json($this->generate_response(
                array(
                    "message" => "Damage Order report generated successfully",
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

    public function generate_damage_order_pdf(Request $request)
    {
        try {
            //print_r(json_encode($request->all()));die;
            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }

            // $from_date = $from_date . ' 00:00:00';
            // $to_date = $to_date . ' 23:59:59';

            $query = ReturnOrdersProducts::select('*','order_return_product.id')->where("return_type","Damage")->createdUser()->orderBy('order_return_product.id', 'asc')
                ->whereRaw("order_return_product.created_at between '" . $from_date . "' and '" . $to_date . "'");
            $query = $query->get();

            $orders = DamageResource::collection($query);

            if (isset($orders)) {

                $view_file = 'order.pdf.damage_report';
                $css_file = 'css/damage_report.css';
                $format = [100, 150];
                $print_logo_path = session('store_logo');

                $print_data = view($view_file, ['orders' => json_encode($orders), 'print_logo_path' => $print_logo_path, 'from_date' => $from_date, 'to_date' => $to_date])
                            ->render();
                $mpdf_config = [
                    'mode'          => 'utf-8',
                    'format'        => $format,
                    'orientation'   => 'P',
                    'margin_left'   => 7,
                    'margin_right'  => 7,
                    'margin_top'    => 7,
                    'margin_bottom' => 7,
                    'tempDir' => storage_path() . "/pdf_temp"
                ];

                $stylesheet = File::get(public_path($css_file));
                $mpdf = new Mpdf($mpdf_config);
                $mpdf->curlAllowUnsafeSslRequests = true;
                $mpdf->SetDisplayMode('real');
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($print_data);

                $pdf_filename = "damage_orders_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.order.view_path');
                $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Damage Order pdf created successfully",
                        "data" => $orders,
                        'orders' => $orders,
                        "link" => '/storage/' . session('merchant_id') . '/order/' . $pdf_filename,
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No order found.'),
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    public function pos_order_report(Request $request)
    {
        try {
            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'order_status' => $request->order_status,
                'store_id' => $request->logged_user_store_id,
            ];

            $filename = 'pos_order_report' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';
            // $bb = new POSOrderExport($params );
            // dd($bb);

            $aa = Excel::store(
                new POSOrderExport(
                    $params
                ),
                'public/reports/' . $filename
            );
            
            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "POS Order report generated successfully",
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



    public function purchase_order_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'purchase_order_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new PurchaseOrderExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Purchase Order report generated successfully",
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

    public function customer_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'customer_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new CustomerExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Customer report generated successfully",
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

    public function store_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'store_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new StoreExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Store report generated successfully",
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

    public function taxcode_report(Request $request)
    {
        $store = Str::slug(StoreModel::where('id', session('store_id'))->pluck('name')->first(), "_");

        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
                'all_stores' => $request->all_stores
            ];

            if($request->all_stores == "true")
            {
                $filename = 'taxcode_report_' . date('Y_m_d_h_i_s') . '_all_stores_' . uniqid() . '.xlsx';
            }
            else
            {
                $filename = 'taxcode_report_' . date('Y_m_d_h_i_s') . '_' . $store . '_' . uniqid() . '.xlsx';
            }

            Excel::store(
                new TaxcodeExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Taxcode report generated successfully",
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

    public function discountcode_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'discountcode_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new DiscountcodeExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Discount code report generated successfully",
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

    public function supplier_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'supplier_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new SupplierExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Supplier code report generated successfully",
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

    public function category_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'category_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new CategoryExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Category code report generated successfully",
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

    public function invoice_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'invoice_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new InvoiceExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoice report generated successfully",
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

    public function quotation_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'quotation_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new QuotationExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Quotation report generated successfully",
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

    public function transaction_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'account' => $request->account,
                'transaction_type' => $request->transaction_type,
                'payment_method' => $request->payment_method,
            ];

            $filename = 'transaction_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new TransactionExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Transaction report generated successfully",
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

    public function get_trending_products(Request $request)
    {
        try {

            $this->current_store = $request->logged_user_store_id;

            $trending_products = OrderProductModel::select(DB::raw("CONCAT(COALESCE(order_products.product_code, ''),' - ',COALESCE(order_products.name, '')) as product"), DB::raw("SUM(order_products.quantity) as quantity"))
                ->orderJoin()
                ->where('orders.store_id', $this->current_store)
                ->where('orders.created_at', 'like', $this->date . '%')
                ->where('orders.status', 1)
                ->orderBy('quantity', 'DESC')
                ->groupBy('product_code')
                ->limit(10)
                ->get()->toArray();

            $x_axis_data = array_column($trending_products, 'product');
            $y_axis_data = array_column($trending_products, 'quantity');

            return response()->json($this->generate_response(
                array(
                    "message" => "Trending products loaded successfully",
                    "data" => [
                        "x_axis" => $x_axis_data,
                        "y_axis" => $y_axis_data
                    ],
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

    public function get_category_performance(Request $request)
    {
        try {

            $this->current_store = $request->logged_user_store_id;
            $month = ($request->month) ? $request->month : date('Y-m');

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $main_query = CategoryModel::select("category.label","category.label_ar", "category.category_code", DB::raw("IFNULL(SUM(order_products.quantity), 0) AS sold_quantity"), DB::raw("IFNULL(SUM(order_products.sub_total_purchase_price_excluding_tax),0) AS purchased_amount"), DB::raw("IFNULL(SUM(order_products.sub_total_sale_price_excluding_tax), 0) AS sold_amount"), DB::raw("(IFNULL(SUM(order_products.sub_total_sale_price_excluding_tax), 0) - IFNULL(SUM(order_products.sub_total_purchase_price_excluding_tax),0)) AS profit_loss"))
                ->active()
                ->productJoin()
                ->leftJoin('order_products', function ($join) use ($month) {
                    $join->on('order_products.product_id', '=', 'products.id');
                    $join->where('order_products.created_at', 'like', $month . '%');
                })
                ->leftJoin('orders', function ($join) {
                    $join->on('orders.id', '=', 'order_products.order_id');
                    $join->where('orders.status', '=', 1);
                })
                ->groupBy('category.id');

            $categories = $main_query
                ->take($limit)
                ->skip($offset)

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->get();

            $total_count = $main_query->get()->count();

            $item_array = [];
            foreach ($categories as $key => $category) {
                if(app()->getLocale() == 'ar'){
                    $item_array[$key][] = $category->label_ar!=null?$category->label_ar:$category->label;
                }else{
                    $item_array[$key][] = $category->label;
                }

                $item_array[$key][] = $category->category_code;
                $item_array[$key][] = $category->sold_quantity;
                $item_array[$key][] = $category->purchased_amount;
                $item_array[$key][] = $category->sold_amount;
                $item_array[$key][] = $category->profit_loss;
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


    public function product_alert_report(Request $request)
    {
        try {

            $item_array = array();

            $product_filter = (isset($request->product_type)) ? $request->product_type : 'billing_products';

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = ProductModel::select('products.*')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->categoryJoin()
                ->supplierJoin()
                ->taxcodeJoin()
                ->discountcodeJoin()
                ->createdUser()
                ->lowStock()
                ->active()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->when($product_filter == 'billing_products', function ($query) {
                    $query->mainProduct();
                })

                ->when($product_filter == 'ingredients', function ($query) {
                    $query->isIngredient();
                });

            $count_query = $query;

            $query = $query->get();

            $products = ProductResource::collection($query);
            $count_query->getQuery()->limit = null;
            $count_query->getQuery()->offset = null;
            $total_count = $count_query->get()->count();

            $item_array = [];
            foreach ($products as $key => $product) {

                $product = $product->toArray($request);

                $item_array[$key][] = $product['product_code'];
                if(app()->getLocale() == 'ar'){
                    $item_array[$key][] = $product['name_ar']!=''?Str::limit($product['name_ar'], 100):Str::limit($product['name'], 100);
                }else{
                    $item_array[$key][] = Str::limit($product['name'], 100);
                }

                $item_array[$key][] = $product['alert_quantity'];
                $item_array[$key][] = $product['quantity'];
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

    public function store_stock_chart(Request $request)
    {
        try {
            $stock_data = ProductModel::select(DB::raw("IFNULL(SUM(products.quantity), 0) AS total_quantity"), DB::raw("IFNULL(SUM(products.purchase_amount_excluding_tax*products.quantity), 0.00) AS total_purchase_cost"), DB::raw("IFNULL(SUM(products.sale_amount_excluding_tax*products.quantity), 0.00) AS total_sale_price"), DB::raw("IFNULL(SUM(products.sale_amount_excluding_tax*products.quantity), 0.00) -  IFNULL(SUM(products.purchase_amount_excluding_tax*products.quantity), 0.00) AS profit_estimate"))
                ->active()
                ->first();

            $response = [
                "total_quantity" => round($stock_data->total_quantity, 2),
                "total_purchase_cost" => round($stock_data->total_purchase_cost, 2),
                "total_sale_price" => round($stock_data->total_sale_price, 2),
                "profit_estimate" => round($stock_data->profit_estimate, 2)
            ];

            return response()->json($this->generate_response(
                array(
                    "message" => "stock chart data loaded successfully",
                    "data"    => $response
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

    public function news_subscriptions_report(Request $request)
    {
        try {
            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'order_status' => $request->order_status,
            ];

            $filename = 'news_subscriptions_report' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new NewsSubscriptionExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "News Subscription report generated successfully",
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

    public function returnOrderListReport(Request $request)
    {
        try {

            $filename = 'order_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            $from_created_date = $request->from_created_date;
            $to_created_date = $request->to_created_date;
            $status = $request->status;
            
            $query = ReturnOrdersModel::select('order_return.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->statusJoin()
                ->createdUser()
                ->whereBetween('order_return.created_at', [$from_created_date, $to_created_date])
                ->orderBy('created_at', 'desc');

            if ($request->status != "") {
                $query->where('order_return.status', '=', $request->status);
            }
            $orders = $query->get();
            $dataArr = [];
            $download_link = asset($this->view_path . $filename);
            $file_path = public_path('/storage/reports/'.$filename);
            $sale_amount_subtotal_excluding_tax = 0;
            // $total_discount_amount = 0;
            // $total_after_discount = 0;
            // $total_tax_amount = 0;
            $total_order_amount = 0;

            foreach($orders as $order){
                // dd(Carbon::parse($order->created_at_label)->format(config("app.date_time_format")) );
                $dataArr[] = [
                    'ORDER NUMBER' => (int)$order->order_number,
                    'CUSTOMER PHONE' => $order->customer_phone,
                    'CUSTOMER EMAIL' => $order->customer_email,
                    'AMOUNT' => (float)$order->total_order_amount,
                    'STATUS' => $order->status_label,
                    'ORDER TYPE' => $order->return_type,
                    'CREATED AT' => ($order->created_at != null) ? Carbon::parse($order->created_at)->format(config("app.date_time_format")) : null,
                    'UPDATED AT' => ($order->updated_at != null) ? Carbon::parse($order->updated_at)->format(config("app.date_time_format")) : null, 
                    'CREATED BY' => $order->fullname, 
                ];
                $total_order_amount += (float)$order->total_order_amount;
            }
            $dataArr[] = [
                'ORDER NUMBER' => trans('TOTAL'),
                'CUSTOMER PHONE' => null,
                'CUSTOMER EMAIL' => null,
                'AMOUNT' => $total_order_amount,
                'STATUS' => null,
                'ORDER TYPE' => null,
                'CREATED AT' => null,
                'UPDATED AT' => null, 
                'CREATED BY' => null, 
            ];
            SimpleExcelWriter::create($file_path)->addRows($dataArr);
            // Excel::store( $dataArr,'public/reports/' . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Order Return report generated successfully",
                    "data" => '',
                    "link" => $download_link
                ),
                'SUCCESS'
            ));
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase(),
        //     ];
        // }
        catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function return_order_report(Request $request)
    {
        try {
            $from_date = date(config('app.sql_date_format'));
            $to_date = date(config('app.sql_date_format'));
            if ($request->from_created_date != "") {
                $from_date = strtotime($request->from_created_date);
                $from_date = date(config('app.sql_date_format'), $from_date);
            }
            if ($request->to_created_date != "") {
                $to_date = strtotime($request->to_created_date);
                $to_date = date(config('app.sql_date_format'), $to_date);
            }
            $from_date_str = Carbon::parse($from_date)->format('d/M/Y');
            $to_date_str = Carbon::parse($to_date)->format('d/M/Y');
            $params = [
                'from_created_date' => $from_date,
                'to_created_date' => $to_date,
                'status' => $request->status,
                'from_date_show' => $from_date_str,
                'to_date_show' => $to_date_str,
            ];

            $filename = 'return_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new ReturnExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product report generated successfully",
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

    public function stock_status_report(Request $request)
    {

        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
                'store' => $request->store,
            ];

            $filename = 'stock_status_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new StockStatusExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product report generated successfully",
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

    public function quantity_purchase_report(Request $request)
    {

        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
                'product' => $request->product, // product slack
                'store' => $request->store, // store id
            ];

            $filename = 'quantity_purchase_status_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new QuantityPurchaseExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Quantity Purchase report generated successfully",
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

    public function supplier_invoice_report(Request $request)
    {

        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'supplier' => $request->supplier,
            ];

            $filename = 'supplier_invoice_report' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new SupplierInvoiceReport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Supplier Invoice report generated successfully",
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

    public function sales_report(Request $request)
    {
                                               
        $from_created_date = strtotime($request->from_created_date);
        $from_created_date = date(config('app.sql_date_format'), $from_created_date);
        // $from_created_date = $from_created_date . ' 00:00:00';
        // $from_created_date = $request->from_created_date;

        $to_created_date = strtotime($request->to_created_date);
        $to_created_date = date(config('app.sql_date_format'), $to_created_date);
        // $to_created_date = $to_created_date . ' 23:59:59';
        // $to_created_date = $request->to_created_date;
        $general_data = [];

        // Gross Sales
        $orders = OrderModel::withoutGlobalScopes()
            // ->where('orders.status', 1) // closed orders only
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('store_id', $request->store);
            })
            ->select(
                DB::Raw('IFNULL (SUM(orders.total_order_amount), 0) as total_order_amount'),
                DB::Raw('IFNULL (SUM(orders.total_tax_amount), 0) as total_tax_amount'),
                DB::Raw('IFNULL (SUM(orders.total_discount_amount), 0) as total_discount_amount')
            )
            ->first()->toArray();
        
        $fully_return_quantity = OrderModel::withoutGlobalScopes()
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('store_id', $request->store);
            })
            ->where('return_order_amount','>',0)
            ->where('status',6)
            ->count();

        $orders_quantity = OrderModel::withoutGlobalScopes()
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('store_id', $request->store);
            })
            ->count();
        $total_discount_order_quantity = OrderModel::withoutGlobalScopes()
            ->where('orders.status',1) // closed orders only
            ->whereRaw('(orders.total_discount_amount + orders.product_level_total_discount_amount > 0)') 
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('store_id', $request->store);
            })
            ->count();
        $dataset = [];
        $dataset['title'] = 'Gross Sales';
        $dataset['quantity'] = (string) $orders_quantity;
        $dataset['amount'] = (string) $orders['total_order_amount'];
        array_push($general_data, $dataset);

        
        $returned_amount = ReturnOrdersModel::withoutGlobalScopes()
        ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
        ->when($request->store != null, function ($query) use ($request) {
            $query->where('order_return.store_id', $request->store);
        })
        ->select(
            DB::Raw('coalesce(SUM(order_return.total_tax_amount),0) as total_tax_amount'),
            DB::Raw('coalesce(SUM(order_return.total_discount_amount),0) as total_discount_amount'),
            DB::Raw('coalesce(SUM(order_return.total_order_amount),0) as total_return_amount'),
        )
        ->first();
        $returned_products = ReturnOrdersModel::withoutGlobalScopes()
        ->join('order_return_product as orp','orp.return_order_id','=','order_return.id')
        ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
        ->when($request->store != null, function ($query) use ($request) {
            $query->where('order_return.store_id', $request->store);
        })
        ->select(
            DB::Raw('coalesce(SUM(orp.discount_amount),0) as total_product_discount_amount'),
        )
        ->first();
        $returned_quantity = ReturnOrdersModel::withoutGlobalScopes()
            ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('order_return.store_id', $request->store);
            })
            ->select(
                DB::Raw('COUNT(*) as quantity'),
            )->first();

        $dataset = [];
        $dataset['title'] = "Sales Return";
        $dataset['quantity'] = (string) $returned_quantity['quantity'];
        $dataset['amount'] = (string) $returned_amount['total_return_amount'];
        array_push($general_data, $dataset);

        $order_products = OrderModel::withoutGlobalScopes()
            ->where('orders.status',1) // closed and fully returned orders only
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(DB::Raw('COUNT(*) as total_order_quantity'))
            ->first()->toArray();
        $dataset['quantity'] = (string) $order_products['total_order_quantity'];
        // array_push($general_data, $dataset);

        $dataset = [];

        // Product Discount Data
        $order_product_discount = OrderProductModel::withoutGlobalScopes()
        ->join('orders', 'orders.id', 'order_products.order_id')
        ->whereIn('orders.status',[1,6]) // closed and fully returned orders only
        ->when($request->store != null, function ($query) use ($request) {
            $query->where('orders.store_id', $request->store);
        })
        ->where('order_products.discount_amount', '>', 0)
        ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
        ->select(
            DB::Raw('IFNULL (SUM(order_products.discount_amount), 0) as total_product_discount_amount'),
            )
            ->first()->toArray();
            
        // $order_product_discount_quantity = OrderModel::withoutGlobalScopes()
        // ->join('order_products','order_products.order_id','orders.id')
        // ->where('orders.status', 1) // closed orders only
        // ->when($request->store != null, function ($query) use ($request) {
        //     $query->where('orders.store_id', $request->store);
        // })
        // ->where('order_products.discount_amount', '>', 0)
        // ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
        // ->select(
        //     DB::Raw('COUNT(*) as quantity')
        //     )
        // ->first()->toArray();

        $tax_and_discount = OrderModel::withoutGlobalScopes()
            ->whereIn('orders.status',[1,6]) // closed orders only
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('store_id', $request->store);
            })
            ->select(
                DB::Raw('IFNULL (SUM(orders.total_tax_amount), 0) as total_tax_amount'),
                DB::Raw('IFNULL (SUM(orders.total_discount_amount), 0) as total_discount_amount')
            )
            ->first()->toArray();
    
        $dataset['title'] = 'Total Discounts';
        $dataset['quantity'] = (string) $total_discount_order_quantity;
        $dataset['amount'] = ($order_product_discount['total_product_discount_amount'] + $tax_and_discount['total_discount_amount']) - 
            ($returned_products['total_product_discount_amount'] + $returned_amount['total_discount_amount']); 
        $dataset['amount'] = (string) $dataset['amount'];
        array_push($general_data, $dataset);

        $dataset = [];
        $dataset['title'] = 'Total Taxes';
        $dataset['quantity'] = (string) ($order_products['total_order_quantity']);
        $net_tax = $tax_and_discount['total_tax_amount'] - $returned_amount['total_tax_amount'];
        $dataset['amount'] = (string) $net_tax;
        array_push($general_data, $dataset);

        // $dataset = [];
        // $dataset['title'] = 'Net Sales';
        // $dataset['quantity'] = (string) $order_products['total_order_quantity'];
        // $dataset['amount'] = $orders['total_order_amount'] - $returned_amount['total_return_amount'] - $tax_and_discount['total_tax_amount'];
        // $net_sales = $dataset['amount'];
        // $dataset['amount'] = (string) $dataset['amount'];
        // array_push($general_data, $dataset);

        $dataset = [];
        $dataset['title'] = "Net Sales[Exclude Tax]";
        $dataset['quantity'] = (string) ($orders_quantity - $fully_return_quantity);  // only fully returned order will be deducted.
        $net_order_qty_after_return = $orders_quantity - $fully_return_quantity;
        $dataset['amount'] = $orders['total_order_amount'] - $returned_amount['total_return_amount'] - $net_tax;
        $net_sales_after_return = $dataset['amount'];
        $dataset['amount'] = (string) $dataset['amount'];
        array_push($general_data, $dataset);

        $dataset = [];
        $dataset['title'] = "Average Per Order";
        $dataset['quantity'] = (string) $net_order_qty_after_return;
        if ($net_order_qty_after_return > 0) {
            $dataset['amount'] = ($net_sales_after_return) / $net_order_qty_after_return;
        } else {
            $dataset['amount'] = 0;
        }
        $dataset['amount'] = number_format($dataset['amount'], 2);
        $dataset['amount'] = (string) $dataset['amount'];
        array_push($general_data, $dataset);

        $order_types = [];

        $dine_in_quantity = OrderModel::withoutGlobalScopes()
            ->where('orders.status', 1) // closed orders only
            ->where('orders.order_type', 'Dine In')
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw(' COUNT(*) as quantity')
            )
            ->first()->toArray();
        
        $dine_in_amount = OrderModel::withoutGlobalScopes()
        ->where('orders.status', 1) // closed orders only
        ->where('orders.order_type', 'Dine In')
        ->when($request->store != null, function ($query) use ($request) {
            $query->where('orders.store_id', $request->store);
        })
        ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
        ->select(
            DB::Raw('IFNULL (SUM(orders.total_order_amount), 0) as amount')
        )
        ->first()->toArray();

        $dataset = [];
        $dataset['title'] = "Dine In";
        $dataset['quantity'] = (string) $dine_in_quantity['quantity'];
        $dataset['amount'] = (string) $dine_in_amount['amount'];
        array_push($order_types, $dataset);

        $take_away_order_amount = OrderModel::withoutGlobalScopes()
            ->where('orders.status', 1) // closed orders only
            ->where('orders.order_type', 'Take Away')
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw('IFNULL (SUM(orders.total_order_amount), 0) as amount')
            )
            ->first()->toArray();

        $take_away_order_quantity = OrderModel::withoutGlobalScopes()
            ->where('orders.status', 1) // closed orders only
            ->where('orders.order_type', 'Take Away')
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw(' COUNT(*) as quantity')
            )
            ->first()->toArray();

        $dataset = [];
        $dataset['title'] = "Take Away";
        $dataset['quantity'] =  (string) $take_away_order_quantity['quantity'];
        $dataset['amount'] = (string) $take_away_order_amount['amount'];
        array_push($order_types, $dataset);

        $payments = [];
        $total_payments = 0;
        $payment_methods = PaymentMethodModel::active()->get();
        $cash_payment_method_id = 4;    
        $total_payment_quantity = 0;
        $cash_payment_in_credit_section = 0;
        if (isset($payment_methods)) {
            foreach ($payment_methods as $payment_method) {

                if($payment_method->payment_constant != 'CASH'){
                    
                    $payment_order_amount = OrderModel::withoutGlobalScopes()
                        ->whereIn('orders.status',[1,6]) // closed and returned orders only
                        ->where('orders.payment_method_id', $payment_method->id)
                        ->when($request->store != null, function ($query) use ($request) {
                            $query->where('orders.store_id', $request->store);
                        })
                        ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
                        ->select(
                            DB::Raw('IFNULL (SUM(orders.credit_amount), 0) as amount'),
                            DB::Raw('IFNULL (SUM(orders.cash_amount), 0) as cash_amount'),
                        )
                        ->first()->toArray();
                    $cash_payment_in_credit_section += $payment_order_amount['cash_amount'];
                    $payment_order_return = ReturnOrdersModel::withoutGlobalScopes()
                        ->where('order_return.status', 1) // closed orders only
                        ->where('order_return.payment_method_id', $payment_method->id)
                        ->when($request->store != null, function ($query) use ($request) {
                            $query->where('order_return.store_id', $request->store);
                        })
                        ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
                        ->select(
                            DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount'),
                        )
                        ->first()->toArray();
                    $payment_order_quantity = OrderModel::withoutGlobalScopes()
                        ->where('orders.status', 1) // closed orders only
                        ->where('orders.payment_method_id', $payment_method->id)
                        ->when($request->store != null, function ($query) use ($request) {
                            $query->where('orders.store_id', $request->store);
                        })
                        ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
                        ->select(
                            DB::Raw(' COUNT(*) as quantity')  
                        )
                        ->first()->toArray();
                    $net_order_payment = $payment_order_amount['amount'] - $payment_order_return['amount'];
                    $dataset = [];
                    $dataset['type'] = 'CREDIT';
                    $dataset['title'] = $payment_method->label;
                    $dataset['quantity'] = (string) $payment_order_quantity['quantity'];
                    $dataset['amount'] = (string) $net_order_payment;
                    array_push($payments, $dataset);
                    $total_payment_quantity += $payment_order_quantity['quantity'];
                    $total_payments += $net_order_payment;
                }elseif($payment_method->payment_constant == 'CASH'){
                    $cash_payment_method_id = $payment_method->id;
                }
            }
        }
        // cash payment report
        $cash_order_amount = OrderModel::withoutGlobalScopes()
            ->whereIn('orders.status', [1,6]) // closed and fully return orders only
            ->where('orders.payment_method_id', $cash_payment_method_id)
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw('IFNULL (SUM(orders.cash_amount), 0) as amount'),
                DB::Raw('IFNULL (SUM(orders.change_amount), 0) as change_amount')
            )
            ->first()->toArray();
        $payment_order_return = ReturnOrdersModel::withoutGlobalScopes()
            ->where('order_return.status', 1) // closed  orders only
            ->where('order_return.payment_method_id', $cash_payment_method_id)
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('order_return.store_id', $request->store);
            })
            ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount'),
            )
            ->first()->toArray();
        
        $cash_order_quantity = OrderModel::withoutGlobalScopes()
            ->where('orders.status', 1) // closed orders only
            ->where('orders.payment_method', 'CASH')
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('orders.store_id', $request->store);
            })
            ->whereBetween('orders.value_date', [$from_created_date, $to_created_date])
            ->select(
                DB::Raw('COUNT(*) as quantity')
            )
            ->first()->toArray();
        
        $dataset = [];
        $dataset['type'] = 'CASH';
        $dataset['title'] = 'Cash';
        $dataset['quantity'] = (string) $cash_order_quantity['quantity'];
        $cash_amount_without_change = round(($cash_order_amount['amount'] + $cash_payment_in_credit_section) - 
                                        $cash_order_amount['change_amount'] - $payment_order_return['amount'], 2);
        $dataset['amount'] = (string) $cash_amount_without_change;
        array_push($payments, $dataset);
        $total_payments += $cash_amount_without_change;
        $total_payment_quantity += $cash_order_quantity['quantity'];
        
        /* Cash Out by Return  for General section start  */
        $returned_quantity = ReturnOrdersModel::withoutGlobalScopes()
            ->whereBetween('order_return.value_date', [$from_created_date, $to_created_date])
            ->when($request->store != null, function ($query) use ($request) {
                $query->where('order_return.store_id', $request->store);
            })
            ->where('order_return.payment_method_id', $cash_payment_method_id)
            ->select(
                DB::Raw('COUNT(*) as quantity'),
            )->first();

        $dataset = [];
        $dataset['title'] = "Cash Out by Return";
        $dataset['quantity'] = (string) $returned_quantity['quantity'];
        $dataset['amount'] = (string) $payment_order_return['amount'];
        array_push($general_data, $dataset);
        /* Cash Out by Return  for General section end  */

        $data['general'] = $general_data;
        $data['order_types'] = $order_types;
        $data['payments'] = $payments;

        // total payments
        $dataset = [];
        $dataset['title'] = 'Total Payments';
        $dataset['quantity'] = (string) $total_payment_quantity;
        $dataset['amount'] = (string) $total_payments;
        array_push($data['payments'], $dataset);

        $response = [
            'status' => true,
            'msg' => 'Sales Report Generated Successfully',
            'data' => $data
        ];

        return response()->json($response);
    }

    public function category_wise_product_sales_report(Request $request)
    {

        $from_created_date = strtotime($request->from_created_date);
        $from_created_date = date(config('app.sql_date_format'), $from_created_date);
        // $from_created_date = $request->from_created_date . ' 00:00:00';
        $from_created_date = $request->from_created_date;

        $to_created_date = strtotime($request->to_created_date);
        $to_created_date = date(config('app.sql_date_format'), $to_created_date);
        // $to_created_date = $request->to_created_date . ' 23:59:59';
        $to_created_date = $request->to_created_date;

        $query = "SELECT 
                c.label as category_name,
                p.id as product_id,
                p.name as product_name,
                SUM(op.quantity) as quantity,
                SUM(op.total_amount) as amount 
            FROM `order_products` op 
            INNER JOIN products p ON p.id = op.product_id 
            INNER JOIN category c ON c.id = p.category_id  
            INNER JOIN orders o ON o.id = op.order_id  
            WHERE o.status in ('1','6')";

        if (isset($request->store) && $request->store != null) {
            $query .= ' AND o.store_id = "' . $request->store . '" ';
        }

        $query .= " AND o.value_date BETWEEN '" . $from_created_date . "' AND '" . $to_created_date . "' 
                    GROUP BY category_name,product_name,product_id ";

        $orders = DB::Select(DB::Raw($query));

        $data = [];

        if (isset($orders)) {
            foreach ($orders as $order) {

                $returned_quantity = DB::Select(
                    DB::Raw("SELECT IFNULL(SUM(ordrt.quantity),0) as quantity, IFNULL(SUM(ordrt.total_amount),0) as amount
                    FROM order_return_product ordrt 
                    INNER JOIN order_return ord ON ord.id = ordrt.return_order_id 
                    WHERE ordrt.product_id = :product_id AND ord.value_date BETWEEN :from_created_date AND :to_created_date "),
                    [
                        'product_id' => $order->product_id,
                        'from_created_date' => $from_created_date,
                        'to_created_date' => $to_created_date,
                    ]
                );

                $order_quantity = $order->quantity - $returned_quantity[0]->quantity;

                $subset = [];
                $subset['title'] = $order->category_name;

                $dataset = [];
                $dataset['product_name'] = $order->product_name;
                $dataset['sold_quantity'] = (string) $order_quantity;
                $dataset['amount'] = (string) ($order->amount - $returned_quantity[0]->amount);

                $subset['items'][0] = $dataset;

                $values = array_column($data, 'title');

                $exists = 0;
                foreach ($data as $key => $value) {
                    if ($value['title'] == $order->category_name) {

                        $dataset = [];
                        $dataset['product_name'] = $order->product_name;
                        $dataset['sold_quantity'] = (string) $order_quantity;
                        $dataset['amount'] = (string) ($order->amount - $returned_quantity[0]->amount);
                        $data[$key]['items'][] = $dataset;

                        $exists = 1;
                    }
                }

                if ($exists == 0) {
                    $data[] = $subset;
                }
            }
        }

        $json = [
            'status' => true,
            'msg' => 'Category Wise Product Sales Report Generated Successfully',
            'data' => $data
        ];

        return response()->json($json);
    }

    public function invoice_return_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'invoice_return_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new InvoiceReturnExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product report generated successfully",
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

    public function get_payment_data(Request $request)
    {
        try {
            $data = [];
            $inventories = [];

            $draw = $request->draw;

            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $this->current_store = $request->logged_user_store_id;
            $from_created_date = $request->from_date;

            $to_created_date = $request->to_date;
            $status = 1;
            // $aquery = DB::table('payment_methods')
            // // ->join('orders', 'orders.payment_method_id', '=', 'payment_methods.id')
            // ->join('order_return', 'order_return.payment_method_id', '=', 'payment_methods.id')
            // // ->select('payment_methods.id','payment_methods.label', DB::raw('SUM(orders.total_order_amount) AS order_amount'), DB::raw('COUNT(orders.id) AS order_count'), DB::raw('SUM(order_return.total_order_amount) AS return_order_amount'), DB::raw('COUNT(order_return.id) AS return_order_count'), DB::raw('(SUM(orders.total_order_amount) - SUM(order_return.total_order_amount)) AS net_amount'))
            // // ->groupBy('payment_methods.id')
            // ->get();
            // dd($aquery);
            $order_query = DB::table('payment_methods')
                ->join('orders', 'orders.payment_method_id', '=', 'payment_methods.id')
                ->select('payment_methods.id','payment_methods.label',
                    DB::raw('SUM(orders.cash_amount) AS cash_amount'),
                    DB::raw('SUM(orders.credit_amount) AS credit_amount'),
                    DB::raw('SUM(orders.total_order_amount) AS order_amount'),
                    DB::raw('COUNT(orders.id) AS order_count'),'orders.payment_option')
                ->where('orders.status', 1)
                ->groupBy('payment_methods.id','orders.payment_option')
                ->take($limit)
                ->skip($offset)

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('value_date', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });

            if ($from_created_date != '')
                $order_query = $order_query->where('orders.value_date', '>=', $from_created_date);
            
            if ($to_created_date != '') 
                $order_query = $order_query->where('orders.value_date', '<=', $to_created_date);

            $order_return_query = DB::table('payment_methods')
                ->join('order_return', 'order_return.payment_method_id', '=', 'payment_methods.id')
                ->select('payment_methods.id','payment_methods.label', DB::raw('SUM(order_return.total_order_amount) AS return_order_amount'), DB::raw('COUNT(order_return.id) AS return_order_count'))
                ->where('order_return.status', 1)
                ->groupBy('payment_methods.id')
                ->take($limit)
                ->skip($offset)

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('value_date', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });

            if ($from_created_date != '') 
                $order_return_query = $order_return_query->where('order_return.value_date', '>=', $from_created_date);

            if ($to_created_date != '') 
                $order_return_query = $order_return_query->where('order_return.value_date', '<=', $to_created_date);
            $order_data = $order_query->get();
            $order_return_data = $order_return_query->get();
            $payments = $this->array_merge_callback($order_data, $order_return_data);

            $item_array = [];
            $item_count = 0;
            $cash_paid_amount = 0;

            foreach ($payments as $payment) {
                $cash_amount = isset($payment->cash_amount) ? $payment->cash_amount : 0.00;
                $cash_paid_amount += $cash_amount;
            }

            foreach ($payments as $payment) {
                $payment_option = isset($payment->payment_option) ? $payment->payment_option : 0;
                $credit_amount = isset($payment->credit_amount) ? $payment->credit_amount : 0.00;
                $order_amount = isset($payment->order_amount) ? $payment->order_amount : 0.00;
                $order_count = isset($payment->order_count) ? $payment->order_count : 0;
                $return_order_amount = isset($payment->return_order_amount) ? $payment->return_order_amount: 0.00;
                $return_order_count = isset($payment->return_order_count) ? $payment->return_order_count : 0;

                if($payment_option > 1){
                    $item_array[$item_count][] = $payment->label;
                    $item_array[$item_count][] = format_decimal($credit_amount) + format_decimal($return_order_amount);
                    $item_array[$item_count][] = $order_count + $return_order_count;
                    $item_array[$item_count][] = format_decimal($return_order_amount);
                    $item_array[$item_count][] = $return_order_count;
                    $item_array[$item_count][] = format_decimal($credit_amount);
                
                    $item_count++;
                }else if($payment_option == 1){
                    $item_array[$item_count][] = $payment->label;
                    $item_array[$item_count][] = format_decimal($cash_paid_amount) + format_decimal($return_order_amount);
                    $item_array[$item_count][] = $order_count + $return_order_count;
                    $item_array[$item_count][] = format_decimal($return_order_amount);
                    $item_array[$item_count][] = $return_order_count;
                    $item_array[$item_count][] = format_decimal($cash_paid_amount);
                
                    $item_count++;
                }
            }

            $total_count = count($item_array);

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

    public function array_merge_callback($data1, $data2) {
        $result = array();
        $item1_array = [];
        
        foreach ($data1 as $item1) {
            $result[$item1->label] = $item1;
            foreach ($data2 as $item2) {
                if(isset($result[$item2->label])){
                    $item1_array = $result[$item2->label];
                    $result[$item2->label] = (object)array_merge((array)$item1_array,(array)$item2);
                }
            }
        }
    
        return $result;
    }

    public function tax_report(Request $request)
    {
        try {

            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'supplier' => $request->supplier,
                'product' => $request->product,
                'category' => $request->category,
                'tax_code' => $request->tax_code,
                'discount_code' => $request->discount_code,
                'product_type' => $request->product_type,
                'status' => $request->status,
                'created_by' => $request->created_by,
            ];

            $filename = 'tax_report_' . date('Y_m_d_h_i_s') . '_' . uniqid() . '.xlsx';

            Excel::store(
                new TaxExport(
                    $params
                ),
                'public/reports/' . $filename
            );

            $download_link = asset($this->view_path . $filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Product Tax report generated successfully",
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
    public function tax_report_pdf(Request $request)
    {
        ini_set('memory_limit', '5120M');
        ini_set('max_execution_time', '300000000');
        ini_set("pcre.backtrack_limit", "5000000000000");

        try {
            $query =OrderProduct::query()
                ->select('orders.id as master_id', 'orders.slack as order_slack', 'orders.value_date', 'orders.order_number', 'orders.reference_number', 'order_products.product_id', 'order_products.product_code', 'order_products.name', 'products.name_ar', 'order_products.quantity', 'order_products.sale_amount_excluding_tax', 'order_products.tax_components', 'order_products.tobacco_tax_components', 'order_products.total_after_discount','orders.payment_method','users.fullname')
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
                    'invoices.id as master_id', 'invoices.slack as invoice_slack', 'invoices.invoice_date', 'invoices.invoice_number', 'invoice_products.product_id', 'invoice_products.product_code', 'invoice_products.name','products.name_ar', 'invoice_products.quantity', 'invoice_products.amount_excluding_tax', 'invoice_products.tax_amount', 'tax_codes.tax_code', 'invoice_products.total_amount', 'tax_codes.label','users.fullname')
                ->leftJoin('invoices','invoices.id','invoice_products.invoice_id')
                ->leftJoin('products','products.id','invoice_products.product_id')
                ->leftJoin('category','category.id','products.category_id')
                ->leftJoin('suppliers','suppliers.id','products.supplier_id')
                ->leftJoin('tax_codes','tax_codes.id','invoice_products.tax_code_id')
                ->leftJoin('users','users.id','invoices.created_by')
                ->where('invoice_products.tax_amount','>',0)
                ->where('invoices.store_id','=',session('store_id'));

            if ($request->from_created_date != '') {
                $query = $query->where( DB::raw('DATE(orders.value_date)'), '>=', $request->from_created_date);
                $query2 = $query2->where('invoices.invoice_date', '>=', $request->from_created_date);
            }
            if ($request->to_created_date != '') {
                $query = $query->where(DB::raw('DATE(orders.value_date)'), '<=', $request->to_created_date);
                $query2 = $query2->where('invoices.invoice_date', '<=', $request->to_created_date);
            }
            if ($request->supplier != '') {
                $query = $query->where('suppliers.slack', $request->supplier);
                $query2 = $query2->where('suppliers.slack', $request->supplier);
            }
            if ($request->product != '') {
                $query = $query->where('products.slack', $request->product);
                $query2 = $query2->where('products.slack', $request->product);
            }
            if ($request->category != '') {
                $query = $query->where('category.slack', $request->category);
                $query2 = $query2->where('category.slack', $request->category);
            }
            if ($request->tax_code != '') {
                $query = $query->where('tax_codes.slack', $request->tax_code);
                $query2 = $query2->where('tax_codes.slack', $request->tax_code);
            }
            if ($request->discount_code != '') {
                $query = $query->where('discount_codes.slack', $request->discount_code);
            }
            if (isset($request->status)) {
                $query = $query->where('products.status', $request->status);
                $query2 = $query2->where('products.status', $request->status);
            }
            if($request->created_by!=''){
                $user_id= User::select('id')->where('slack',$request->created_by)->active()->first()->id;
                $query = $query->where('orders.created_by', $user_id);
                $query2 = $query2->where('invoices.created_by', $user_id);
            }
            if($request->product_type == 'billing_products'){
                $query = $query->where('products.is_ingredient', 0);
                $query2 = $query2->where('products.is_ingredient', 0);
            }
            if($request->product_type == 'ingredients'){
                $query = $query->where('products.is_ingredient', 1);
                $query2 = $query2->where('products.is_ingredient', 1);
            }
            $order_products = $query->orderBy('orders.id','DESC')->get();
            $invoice_products = $query2->orderBy('invoices.id','DESC')->get();

            $is_tobacco = Store::select('tobacco_tax_val')->where('id',session('store_id'))->first();
            $taxcodes = TaxcodeModel::select('id','slack', 'tax_code', 'label')->sortLabelAsc()->get();

            $result = [];
            $result2 = [];
            $total_quantity=0;$total_tobacco_tax=0;$total_vat_tax=0;$tot_other_tax=0;$total_tax=0;$total_price=0;
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
                $data_array['id']= $product->master_id;
                $data_array['date']= $product->value_date!=''? \Carbon\Carbon::parse($product->value_date)->format('d-m-Y'):'-';
                $data_array['reference_no']=$product->reference_number;
                $data_array['order_invoice_no']=$product->order_number;
                $data_array['type']=trans('ORDER');

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
                $total_quantity += $data_array['quantity'];
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

            $final_array = $this->group_by("id", $result);

            //INVOICES
            foreach ($invoice_products as $product){
                $total_tax_sum = 0;
                $data_array = [];
                $data_array['id']= $product->master_id;
                $data_array['date']= $product->invoice_date!=''? \Carbon\Carbon::parse($product->invoice_date)->format('d-m-Y'):'-';
                $data_array['reference_no']='';
                $data_array['order_invoice_no']=$product->invoice_number;
                $data_array['type']=trans('INVOICE');
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
                $total_quantity += $data_array['quantity'];
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
                $result2[] = $data_array;
            }
            $final_array2 = $this->group_by("order_invoice_no", $result2);
            $taxes = array_merge($final_array,$final_array2);

            $tax_data=[];
            $fin_tot_sale_price =0;
            foreach ($taxes as $tax){
                $count_total_quantity = 0;
                $total_sale_price = 0;
                $total_tobacco = 0;
                $total = 0;
                $total_amount = 0;
                foreach ($taxcodes as $taxcode){
                    $new_total_tax_array[$taxcode->tax_code] = 0;
                }
                foreach ($tax as $row){
                    $count_total_quantity += $row['quantity'];
                    $total_sale_price += $row['sale_price'];
                    if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0 && isset($row['TOBACCO']) && $row['TOBACCO']>0) {
                        $total_tobacco += $row['TOBACCO'];
                    }
                    foreach ($taxcodes as $taxcode){
                        $new_total_tax_array[$taxcode->tax_code] += $row[$taxcode->tax_code];
                    }
                    $total +=$row['total'];
                    $total_amount +=$row['total_amount'];
                }
                $main_row = array(
                    'date'=>$tax[0]['date'],
                    'reference_no'=>$tax[0]['reference_no'],
                    'order_invoice_no'=>$tax[0]['order_invoice_no'],
                    'type'=>$tax[0]['type'],
                    'products'=>$tax,
                    'quantity'=>$count_total_quantity,
                    'sale_price'=>$total_sale_price,
                    'payment_type'=>$tax[0]['payment_type'],
                );
                if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0) {
                    $main_row['TOBACCO'] = $total_tobacco;
                }
                foreach ($taxcodes as $taxcode){
                    $main_row[$taxcode->tax_code] = $new_total_tax_array[$taxcode->tax_code];
                }
                $main_row['total'] = $total;
                $main_row['total_amount'] = $total_amount;
                $fin_tot_sale_price +=$total_sale_price;
                $main_row['created_by'] = $tax[0]['created_by'];
                $tax_data[] = $main_row;
            }

            //SORTING
            foreach ($tax_data as $key => $part) {
                $sort[$key] = strtotime($part['date']);
            }

            if(!empty($tax_data)){
                array_multisort($sort, SORT_ASC, $tax_data);
            }

            $columns = [
                trans('SR. NO.'),
                trans('DATE'),
                trans('REFERENCE NO'),
                trans('ORDER NO'),
                trans('TYPE'),
                trans('PRODUCT NAME'),
                trans('QUANTITY'),
                trans('SALE PRICE'),
                trans('PAYMENT TYPE'),
            ];
            if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0){
                $columns = array_merge($columns,[trans('TOBACCO TAX')]);
            }
            foreach ($taxcodes as $tax){
                $columns = array_merge($columns,[$tax->tax_code]);
            }
            $columns = array_merge($columns,[trans('TOTAL TAX'),trans('TOTAL PRICE'),trans('CREATED BY')]);
            $footers = [
                trans('Total'),
                '',
                '',
                '',
                '',
                '',
                $total_quantity,
                number_format($fin_tot_sale_price,4),
                '',
            ];
            if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0){
                $footers = array_merge($footers,[number_format($total_order_tax_array['TOBACCO'],4)]);
            }
            foreach ($taxcodes as $tax){
                $footers = array_merge($footers,[number_format($total_order_tax_array[$tax->tax_code],4)]);
            }
            $footers = array_merge($footers,[number_format($total_order_tax_array['TOT_TAX'],4),number_format($total_order_tax_array['TOT_PRICE'],4),'']);

            /*PDF CODE*/
            $view_file = 'report.pdf.tax_report';
            //$css_file = 'css/order_thermal_print_invoice.css';
            $print_data = view($view_file, ['columns'=>$columns,'taxcodes'=>$taxcodes,'taxes' => $tax_data,'footers'=>$footers,'is_tobacco'=>$is_tobacco])->render();//json_encode($orders)
            $download_link = $this->make_pdf($print_data);
            /*PDF CODE END*/

            return response()->json($this->generate_response(
                array(
                    "message" => "Product Tax report generated successfully",
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

    function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }

    function make_pdf($html){
        /*ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);*/
        ini_set('memory_limit', '5120M');
        ini_set('max_execution_time', '300000000');
        ini_set("pcre.backtrack_limit", "5000000000000");
        $pdf_filename = "tax_report_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
        $download_link = '/storage/tax_report/' . $pdf_filename;
        $upload_dir = Storage::disk('tax_report')->getAdapter()->getPathPrefix();
        $mpdf_config = [
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'L',
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 5,
            'margin_bottom' => 5,
            'tempDir' => storage_path() . "/pdf_temp"
        ];

        //$stylesheet = File::get(public_path($css_file));
        $mpdf = new Mpdf($mpdf_config);
        $mpdf->WriteHTML($html);
        $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);
        return $download_link;
    }
}
