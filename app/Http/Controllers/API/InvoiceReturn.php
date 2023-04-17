<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Store as StoreModel;
use App\Models\InvoiceReturn as InvoiceReturnModel;
use App\Models\InvoiceReturnProducts as InvoiceReturnProductsModel;
use App\Http\Resources\InvoiceReturnResource;

use Mpdf\Mpdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class InvoiceReturn extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            
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

            $from_date = str_replace('T',' ',$from_date);
            $to_date = str_replace('T',' ',$to_date);
            
            // $data['action_key'] = 'A_VIEW_RETURN_INVOICE_LISTING';
            // if (check_access(array($data['action_key']), true) == false) {
            //     $response = $this->no_access_response_for_listing_table();
            //     return $response;
            // }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            // DB::enableQueryLog();
            $query = InvoiceReturnModel::select('invoices_return.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
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
                })->whereBetween('invoices_return.created_at', [$from_date, $to_date]);

                if ($request->status != "") {
                    $query->where('invoices_return.status', '=', $request->status);
                }
                $query = $query->get();

            $invoices = InvoiceReturnResource::collection($query);
            $total_q = InvoiceReturnModel::select("id")->whereBetween('invoices_return.created_at', [$from_date, $to_date]);
            
            if ($request->status != "") {
                $total_q->where('invoices_return.status', '=', $request->status);
            }

            $total_count = $total_q->get()->count();

            $item_array = [];
            foreach ($invoices as $key => $invoice) {

                $invoice = $invoice->toArray($request);

                $item_array[$key][] = $invoice['return_invoice_number'];
                $item_array[$key][] = (!empty($invoice['bill_to'])) ? $invoice['bill_to'] : '-';
                $item_array[$key][] = (!empty($invoice['bill_to_name'])) ? $invoice['bill_to_name'] : '-';
                $item_array[$key][] = (!empty($invoice['bill_to_email'])) ? $invoice['bill_to_email'] : '-';
                $item_array[$key][] = (!empty($invoice['bill_to_contact'])) ? $invoice['bill_to_contact'] : '-';
                $item_array[$key][] = (!empty($invoice['store']['name'])) ? $invoice['store']['name'] : '-';
                $item_array[$key][] = (!empty($invoice['total_discount_amount'])) ? $invoice['total_discount_amount'] : '-';
                $item_array[$key][] = (!empty($invoice['total_tax_amount'])) ? $invoice['total_tax_amount'] : '-';
                $item_array[$key][] = (!empty($invoice['total_order_amount'])) ? $invoice['total_order_amount'] : '-';
                $item_array[$key][] = (isset($invoice['status']['label'])) ? view('common.status', ['status_data' => ['label' => $invoice['status']['label'], "color" => $invoice['status']['color']]])->render() : '-';
                $item_array[$key][] = $invoice['created_at_label'];
                $item_array[$key][] = $invoice['updated_at_label'];
                $item_array[$key][] = (isset($invoice['created_by']) && isset($invoice['created_by']['fullname'])) ? $invoice['created_by']['fullname'] : '-';
                $item_array[$key][] = view('invoice_return.actions', ['invoice_return' => $invoice])->render();
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

    /**
     * method : generate_invoices_return_pdf
     * param  : start_date,end_date

     **/
    public function generate_invoice_return_pdf(Request $request)
    {
       
        try {
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

            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $query = InvoiceReturnModel::select('invoices_return.*', 'stores.store_logo as store_logo' , 'stores.name as name' ,'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->statusJoin()
                ->createdUser()
                ->StoreDetails()
                ->orderBy('invoices_return.id', 'desc')
                ->whereBetween('invoices_return.created_at', [$from_date, $to_date]);
            if ($request->order_status != "") {
                $query->where('invoices_return.status', '=', $request->order_status);
            }

            $query = $query->get();
            $invoice = $query->first();
            $invoices = InvoiceReturnResource::collection($query);

        
            $store_logo = $invoice['store_logo'];
         
            $data['store_detail'] = StoreModel::select('*')->find($invoice->store_id);

            
        if(session()->has('merchant_id')){
            if(session('merchant_id') == ''){
                // default logo
                $print_logo_path = asset('/images/logo.png');
            }else{
                // for logged in users
                // $print_logo_path = asset('storage/'.session('merchant_id').'/store/'.session('store_logo'));
                $print_logo_path = asset('storage/'.session('merchant_id').'/store/'.$store_logo);
            }
        }else{
            // for logged out users (public url)
            $print_logo_path = asset('storage/'.config('constants.config.merchant_id').'/store/'.$store_logo);
        }
           
            if (isset($invoices)) {

                $view_file = 'invoice_return.pdf.generate';
                // $css_file = 'css/invoice_alt_print.css';
                $css_file = 'css/order_thermal_print_invoice.css';
                $print_logo_path = session('store_logo');
                $format = [100, 150];

                $print_data = view($view_file, ['invoices' => json_encode($invoices), 'print_logo_path' => $print_logo_path, 'from_date' => $from_date, 'to_date' => $to_date])->render();

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

                $pdf_filename = "invoice_return_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.invoice_return.view_path');
                $upload_dir = Storage::disk('invoice_return')->getAdapter()->getPathPrefix();

             
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Invoice Return pdf created successfully",
                        "data" => $invoices,
                        'invoices' => $invoices,
                        "link" => '/storage/'.session('merchant_id').'/invoice_return/' . $pdf_filename,
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No return order found.'),
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
    
}
