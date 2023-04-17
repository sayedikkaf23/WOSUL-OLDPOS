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

use App\Http\Resources\OrderResource;

use App\Models\Order as OrderModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Product as ProductModel;
use App\Models\Customer as CustomerModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\Store as StoreModel;
use App\Models\TaxcodeType as TaxcodeTypeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Account as AccountModel;
use App\Models\MasterOrderType as MasterOrderTypeModel;
use App\Models\Table as TableModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\User as UserModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;

use App\Http\Resources\Collections\OrderCollection;
use App\Http\Resources\Collections\OrderDateCollection;


use App\Http\Controllers\API\Notification as NotificationAPI;
use App\Http\Resources\OrderDateResource;
use App\Http\Resources\ReturnOrderResource;
use App\Models\ReturnOrders as ReturnOrdersModel;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;

use Mpdf\Mpdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ReturnOrder extends Controller
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

            // $from_date = str_replace('T', ' ', $from_date);
            // $to_date = str_replace('T', ' ', $to_date);

            $from_date = strtotime($from_date);
            $from_date = date(config('app.sql_date_format'), $from_date);
            $from_date = $from_date . ' 00:00:00';
            // $from_created_date = $request->from_created_date;

            $to_date = strtotime($to_date);
            $to_date = date(config('app.sql_date_format'), $to_date);
            $to_date = $to_date . ' 23:59:59';

            // $data['action_key'] = 'A_VIEW_RETURN_ORDER_LISTING';
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
            $query = ReturnOrdersModel::select('order_return.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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
                })->whereBetween('order_return.created_at', [$from_date, $to_date]);

            if ($request->status != "") {
                $query->where('order_return.status', '=', $request->status);
            }
            $query = $query->get();

            $orders = ReturnOrderResource::collection($query);
            $total_q = ReturnOrdersModel::select("id")->whereBetween('order_return.created_at', [$from_date, $to_date]);

            if ($request->status != "") {
                $total_q->where('order_return.status', '=', $request->status);
            }

            $total_count = $total_q->get()->count();

            $item_array = [];
            foreach ($orders as $key => $order) {

                $order = $order->toArray($request);

                $item_array[$key][] = $order['order_number'];
                $item_array[$key][] = (!empty($order['customer_phone'])) ? $order['customer_phone'] : '-';
                $item_array[$key][] = (!empty($order['customer_email'])) ? $order['customer_email'] : '-';
                $item_array[$key][] = $order['total_order_amount'];
                $item_array[$key][] = (isset($order['status']['label'])) ? view('common.status', ['status_data' => ['label' => $order['status']['label'], "color" => $order['status']['color']]])->render() : '-';
                $item_array[$key][] = $order['return_type'];
                $item_array[$key][] = $order['created_at_label'];
                $item_array[$key][] = $order['updated_at_label'];
                $item_array[$key][] = (isset($order['created_by']) && isset($order['created_by']['fullname'])) ? $order['created_by']['fullname'] : '-';
                $item_array[$key][] = view('return_order.actions', ['return_order' => $order])->render();
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
     * method : generate_return_order_pdf
     * param  : start_date,end_date

     **/
    public function generate_return_order_pdf(Request $request)
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

            $query = ReturnOrdersModel::select('order_return.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->statusJoin()
                ->createdUser()
                ->orderBy('order_return.id', 'desc')
                ->whereBetween('order_return.created_at', [$from_date, $to_date]);
            if ($request->order_status != "") {
                $query->where('order_return.status', '=', $request->order_status);
            }

            $query = $query->get();

            $orders = ReturnOrderResource::collection($query);

            if (isset($orders)) {

                $view_file = 'return_order.pdf.generate';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [100, 150];
                $print_logo_path = session('store_logo');

                $print_data = view($view_file, ['orders' => json_encode($orders), 'print_logo_path' => $print_logo_path, 'from_date' => $from_date, 'to_date' => $to_date])->render();

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

                $pdf_filename = "return_orders_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.order.view_path');
                $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Return Order pdf created successfully",
                        "data" => $orders,
                        'orders' => $orders,
                        "link" => '/storage/' . session('merchant_id') . '/order/' . $pdf_filename,
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
