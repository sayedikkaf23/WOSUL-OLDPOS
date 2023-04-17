<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Arr;

use App\Models\Customer as CustomerModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Models\Store as StoreModel;
use App\Models\Product as ProductModel;
use App\Models\Supplier as SupplierModel;
use App\Models\PurchaseOrder as PurchaseOrderModel;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Invoice as InvoiceModel;
use App\Models\Quotation as QuotationModel;
use App\Models\Target as TargetModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\BillingCounter as BillingCounterModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\UserStore as UserStoreModel;

use App\Http\Resources\TransactionResource;
use App\Http\Resources\BillingCounterResource;
use App\Http\Resources\TopSellingProductResource;
use App\Http\Resources\TopEarningProductResource;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\CommonApiTrait;

class Dashboard extends Controller
{
    public $from_date, $to_date, $store_ids;

    use CommonApiTrait;

    public function __construct(Request $request)
    {

        $this->from_date = (isset($request->from_date) && $request->from_date != '') ? $request->from_date : request()->logged_store_opening_time;
        $this->to_date = (isset($request->to_date) && $request->to_date != '') ? $request->to_date : request()->logged_store_closing_time;
        
        $this->from_date = (new \DateTime($this->from_date))->format("Y-m-d") . " 00:00:00";
        $this->to_date = (new \DateTime($this->to_date))->format("Y-m-d") . " 23:59:00";
        
        $this->from_date_only = (new \DateTime($this->from_date))->format("Y-m-d");
        $this->to_date_only = (new \DateTime($this->to_date))->format("Y-m-d");

    }

    public function get_dashboard_stats(Request $request)
    {

        try {

            $data = [];
            //echo $this->from_date;die;
            $data['todays_order_count'] = $this->get_today_order_count();
            $data['todays_order_value'] = $this->get_today_order_value();
            //echo $this->from_date;die;
            $data['order_count'] = $this->get_order_count();
            $data['order_value'] = $this->get_order_value();
            $data['customer_count'] = $this->get_customer_count();
            // $data['revenue_value'] = $this->get_total_revenue();

            $data['expense'] = $this->get_total_expense();
            // $data['net_profit_value'] = $this->get_net_profit();

            $data['purchase_order_count'] = $this->get_po_count();
            $data['invoices_count'] = $this->get_invoice_count();

            $data['get_revenue'] = $this->get_revenue();

            $data['targets'] = $this->get_target_values();

            // to display paid and pending sales for invoice and pos in donut chart 
            $data['paid_pending_invoice'] = $this->get_paid_pending_invoice();
            $data['paid_pending_pos'] = $this->get_paid_pending_pos();

            //chart data
            // $income = round($data['revenue_value']['count_raw']);

            $income = $data['get_revenue']['invoice_cash'] + $data['get_revenue']['invoice_credit'] + $data['get_revenue']['pos_cash'] + $data['get_revenue']['pos_credit'] - $data['get_revenue']['pos_returned'] - $data['get_revenue']['pos_change'];

            $data['revenue_value']['count_raw'] = $income;
            $data['revenue_value']['count_formatted'] = number_format_short($income);

            $expense = $data['expense']['count_raw'];

            // $net_profit = round($data['net_profit_value']['count_raw']);
            $net_profit = $income - $expense;
            $data['net_profit_value']['count_raw'] = $net_profit;
            $data['net_profit_value']['count_formatted'] = number_format_short($net_profit);

            // $total_revenue = ($income + $expense + $net_profit);
            $total_revenue = $income;


            if ($total_revenue > 0) {
                $data['chart_income'] =  ($income * 100) / $total_revenue;
                $data['chart_expense'] =  ($expense * 100) / $total_revenue;
                $data['chart_net_profit'] = ($net_profit * 100) / $total_revenue;

                // if($data['chart_expense']==0){
                //    $data['chart_net_profit']=($data['chart_net_profit']-1);
                //     $data['chart_expense'] = 1;
                // }
                $data['chart_total'] = $total_revenue;
            } else {
                $data['chart_income'] =  0;
                $data['chart_expense'] =  0;
                $data['chart_net_profit'] =  0;
                $data['chart_total'] = 0;
            }

            $data['chart_income'] = round($data['chart_income'], 2);
            $data['chart_expense'] = round($data['chart_expense'], 2);
            $data['chart_net_profit'] = round($data['chart_net_profit'], 2);
            $data['chart_total'] = round($data['chart_total'], 2);
            $data['top_selling_earning_products'] = $this->get_top_selling_earning_products();

            return response()->json($this->generate_response(
                array(
                    "message" => "Dashboard stats calculated successfully",
                    "data" => $data,
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


    public function get_order_chart_stats(Request $request)
    {
        try {

            $dates = $this->get_chart_dates();
            $order_count_array = $this->get_monthly_order_count();
            $order_value_array = $this->get_monthly_order_value();

            $result = [];
            foreach ($dates['key'] as $key => $date) {
                $order_count = 0;
                $order_value = 0;
                if (array_key_exists($date, $order_count_array)) {
                    $order_count = $order_count_array[$date];
                }
                if (array_key_exists($date, $order_value_array)) {
                    $order_value = $order_value_array[$date];
                }
                $result[] = [
                    "order_count" => $order_count,
                    "order_value" => $order_value,
                    "date" => $date
                ];
            }

            $x_axis_data = array_column($result, 'date');
            $y_axis_order_count_data = array_column($result, 'order_count');
            $y_axis_order_value_data = array_column($result, 'order_value');

            return response()->json($this->generate_response(
                array(
                    "message" => "Monthly order matrix calculated successfully",
                    "data" => [
                        "x_axis" => $x_axis_data,
                        "y_axis" => [
                            'order_count' => $y_axis_order_count_data,
                            'order_value' => $y_axis_order_value_data
                        ]
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

    public function get_order_chart_timely_stats(Request $request)
    {

        try {

            $dates = $this->get_chart_times();
            $order_count_array = $this->get_timely_order_count();
            $order_value_array = $this->get_timely_order_value();


            $result = [];
            foreach ($dates['key'] as $key => $date) {


                $order_count = 0;
                $order_value = 0;
                if (array_key_exists($date, $order_count_array)) {
                    $order_count = $order_count_array[$date];
                }
                if (array_key_exists($date, $order_value_array)) {
                    $order_value = $order_value_array[$date];
                }

                $date_key = explode("-", $date);
                $date_key = $date_key[0];


                $result[] = [
                    "order_count" => $order_count,
                    "order_value" => $order_value,
                    "date" => $date_key
                ];
            }

            $x_axis_data = array_column($result, 'date');
            $y_axis_order_count_data = array_column($result, 'order_count');
            $y_axis_order_value_data = array_column($result, 'order_value');

            return response()->json($this->generate_response(
                array(
                    "message" => "Timely order matrix calculated successfully",
                    "data" => [
                        "x_axis" => $x_axis_data,
                        "y_axis" => [
                            'order_count' => $y_axis_order_count_data,
                            'order_value' => $y_axis_order_value_data
                        ]
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

    private function get_chart_dates()
    {
        $j = 0;
        $to_year = date("Y", strtotime($this->to_date));
        $to_month = date("n", strtotime($this->to_date));
        $to_start_date = date("j", strtotime($this->to_date));
        $from_year = date("Y", strtotime($this->from_date));
        $from_month = date("n", strtotime($this->from_date));
        $form_start_date = date("j", strtotime($this->from_date));
        $current_month = date("n");
        $current_day = date("j");
        $number_of_days = cal_days_in_month(CAL_GREGORIAN, $from_month, $from_year);
        $dates = [];
        $key = [];
        for ($i = $form_start_date; $i <= $number_of_days; $i++) {
            if ($current_month == $from_month && $i > $current_day) {
                continue;
            }
            $loop_date = ($i . '/' . ($from_month + $j));
            $key[] = $loop_date;
            $dates[] = $i;
        }
        $diffrence_month =  $to_month - $from_month;
        // dd($diffrence_month);
        $diffm = $diffrence_month;
        if ($from_month != $to_month) {
            for ($j = 1; $j <= $diffrence_month; $j++) {
                if ($diffm == "1") {
                    for ($i = 1; $i <= $to_start_date; $i++) {
                        $loop_date = $i . '/' . ($from_month + $j);
                        $key[] = $loop_date;
                        $dates[] = $i;
                    }
                }
                if ($diffm != "1") {
                    $number_of_days = cal_days_in_month(CAL_GREGORIAN, $from_month + $j, $from_year);
                    for ($i = 1; $i <= $number_of_days; $i++) {
                        $loop_date = $i . '/' . ($from_month + $j);
                        $key[] = $loop_date;
                        $dates[] = $i;
                    }
                }
                $diffm--;
            }
        }

        $result = [];
        $result['dates'] = $dates;
        $result['key'] = $key;

        return $result;
    }

    private function get_chart_times()
    {

        $result = [];
        $result['times'] = [
            '0:00-1:00',
            '1:00-2:00',
            '2:00-3:00',
            '3:00-4:00',
            '4:00-5:00',
            '5:00-6:00',
            '6:00-7:00',
            '7:00-8:00',
            '8:00-9:00',
            '9:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '12:00-13:00',
            '13:00-14:00',
            '14:00-15:00',
            '15:00-16:00',
            '16:00-17:00',
            '17:00-18:00',
            '18:00-19:00',
            '19:00-20:00',
            '20:00-21:00',
            '21:00-22:00',
            '22:00-23:00',
            '23:00-24:00',
        ];
        $result['key'] = $result['times'];

        return $result;
    }

    public function get_monthly_order_count()
    {

        $combined_store = $this->get_combined_stores();

        $order_count_data = OrderModel::selectRaw("COUNT(id) as order_count, DATE_FORMAT(created_at, '%e/%c') as order_date")
            ->whereBetween('created_at', [$this->from_date, $this->to_date])
            ->groupBy('order_date')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->get()
            ->toArray();

        $order_count_data_array = [];
        if (!empty($order_count_data)) {
            foreach ($order_count_data as $order_count_data_item) {
                $order_count_data_array[$order_count_data_item['order_date']] = $order_count_data_item['order_count'];
            }
        }
        return $order_count_data_array;
    }

    public function get_monthly_order_value()
    {

        $combined_store = $this->get_combined_stores();

        $order_count_data = OrderModel::selectRaw("SUM(total_order_amount) as order_value, DATE_FORMAT(created_at, '%e/%c') as order_date")
            ->whereBetween('created_at', [$this->from_date, $this->to_date])
            ->groupBy('order_date')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->get()
            ->toArray();

        $order_count_data_array = [];
        if (!empty($order_count_data)) {
            foreach ($order_count_data as $order_count_data_item) {
                $order_count_data_array[$order_count_data_item['order_date']] = $order_count_data_item['order_value'];
            }
        }
        return $order_count_data_array;
    }

    public function get_timely_order_count()
    {

        $combined_store = $this->get_combined_stores();

        $from_date = Carbon::parse($this->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($this->to_date)->format('Y-m-d');
        
        $where_condition = "WHERE orders.status = '1' ";
        if (is_null($this->to_date)) {
            $where_condition .= " AND orders.value_date = '" . $from_date . "' ";
        } else {
            $where_condition .= " AND orders.value_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' ";
        }

        if ($combined_store['show_combined_stats'] == 1) {
            $where_condition .= " AND orders.store_id IN(" . implode(',', $combined_store['store_id']) . ") ";
        } else if ($combined_store['show_combined_stats'] == 0) {
            $where_condition .= " AND orders.store_id = '" . $combined_store['store_id'] . "' ";
        }

        $query = "SELECT CONCAT(Hour, ':00-', Hour+1, ':00') AS order_time , 
        COUNT(created_at) AS `order_count` FROM orders 
        RIGHT JOIN ( SELECT 0 AS Hour UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 ) AS AllHours ON HOUR(created_at) = Hour 
        " . $where_condition . " 
        OR created_at IS NULL 
        GROUP BY Hour 
        ORDER BY Hour";
        
        $order_count_data = DB::SELECT($query);
        
        $dataset = [
            '0:00-1:00' => 0,
            '1:00-2:00' => 0,
            '2:00-3:00' => 0,
            '3:00-4:00' => 0,
            '4:00-5:00' => 0,
            '5:00-6:00' => 0,
            '6:00-7:00' => 0,
            '7:00-8:00' => 0,
            '8:00-9:00' => 0,
            '9:00-10:00' => 0,
            '10:00-11:00' => 0,
            '11:00-12:00' => 0,
            '12:00-13:00' => 0,
            '13:00-14:00' => 0,
            '14:00-15:00' => 0,
            '15:00-16:00' => 0,
            '16:00-17:00' => 0,
            '17:00-18:00' => 0,
            '18:00-19:00' => 0,
            '19:00-20:00' => 0,
            '20:00-21:00' => 0,
            '21:00-22:00' => 0,
            '22:00-23:00' => 0,
            '23:00-24:00' => 0,
        ];

        if (isset($order_count_data) && count($order_count_data) > 0) {
            foreach ($order_count_data as $row) {
                $dataset[$row->order_time] = $row->order_count;
            }
        }

        $order_count_data_array = [];
        if (!empty($dataset)) {
            foreach ($dataset as $key => $value) {
                $order_count_data_array[$key] = $value;
            }
        }
        

        return $order_count_data_array;
    }

    public function get_timely_order_value()
    {

        $combined_store = $this->get_combined_stores();

        $where_condition = "WHERE orders.status = '1' ";
        if (is_null($this->to_date)) {
            $where_condition .= " AND DATE(created_at) = '" . $this->from_date . "' ";
        } else {
            $where_condition .= " AND DATE(created_at) BETWEEN '" . $this->from_date . "' AND '" . $this->to_date . "' ";
        }

        if ($combined_store['show_combined_stats'] == 1) {
            $where_condition .= " AND orders.store_id IN(" . implode(",", $combined_store['store_id']) . ") ";
        } else if ($combined_store['show_combined_stats'] == 0) {
            $where_condition .= " AND orders.store_id = '" . $combined_store['store_id'] . "' ";
        }

        $query = "SELECT CONCAT(Hour, ':00-', Hour+1, ':00') AS order_time , 
        SUM(total_order_amount) AS `order_count` FROM orders 
        RIGHT JOIN ( SELECT 0 AS Hour UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 ) AS AllHours ON HOUR(created_at) = Hour 
        " . $where_condition . " 
        OR created_at IS NULL 
        GROUP BY Hour 
        ORDER BY Hour";

        $order_count_data = DB::SELECT($query);

        $dataset = [
            '0:00-1:00' => 0,
            '1:00-2:00' => 0,
            '2:00-3:00' => 0,
            '3:00-4:00' => 0,
            '4:00-5:00' => 0,
            '5:00-6:00' => 0,
            '6:00-7:00' => 0,
            '7:00-8:00' => 0,
            '8:00-9:00' => 0,
            '9:00-10:00' => 0,
            '10:00-11:00' => 0,
            '11:00-12:00' => 0,
            '12:00-13:00' => 0,
            '13:00-14:00' => 0,
            '14:00-15:00' => 0,
            '15:00-16:00' => 0,
            '16:00-17:00' => 0,
            '17:00-18:00' => 0,
            '18:00-19:00' => 0,
            '19:00-20:00' => 0,
            '20:00-21:00' => 0,
            '21:00-22:00' => 0,
            '22:00-23:00' => 0,
            '23:00-24:00' => 0,
        ];

        if (isset($order_count_data) && count($order_count_data) > 0) {
            foreach ($order_count_data as $row) {
                $dataset[$row->order_time] = $row->order_count;
            }
        }

        $order_count_data_array = [];
        if (!empty($dataset)) {
            foreach ($dataset as $key => $value) {
                $order_count_data_array[$key] = ($value == "") ? 0 : $value;
            }
        }

        return $order_count_data_array;
    }

    public function get_order_count()
    {

        $combined_store = $this->get_combined_stores();

        $count = OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            // ->where('store_id',session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $count_formatted = number_format_short($count);

        $response = [
            "count_raw" => $count,
            "count_formatted" => $count_formatted
        ];

        return $response;
    }

    public function get_today_order_count()
    {

        $combined_store = $this->get_combined_stores();

        $yesterday = date('Y-m-d', strtotime(Carbon::now()->subDays(1)));
        
        $day_before_yesterday = date('Y-m-d', strtotime(Carbon::now()->subDays(2)));
        
        $count = OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $count_formatted = number_format_short($count);

        
        $yesterday_count = OrderModel::where('orders.value_date', $yesterday)
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $day_before_yesterday_count = OrderModel::where('orders.value_date',$day_before_yesterday)
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $sale_count_chart = [
            'x_axis' => [trans('Day Before Yest') . '.', trans('Yesterday'), trans('Today')],
            'y_axis' => [$day_before_yesterday_count, $yesterday_count, $count]
        ];

        $response = [
            "count_raw" => $count,
            "count_formatted" => $count_formatted,
            "chart" => $sale_count_chart
        ];

        return $response;
    }

    public function get_order_value()
    {

        $combined_store = $this->get_combined_stores();

        $sum = OrderModel::whereBetween('value_date', [$this->from_date_only, $this->to_date_only])
            // ->where('store_id',session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('total_order_amount');

        $sum_formatted = number_format_short($sum);

        $response = [
            "count_raw" => $sum,
            "count_formatted" => $sum_formatted
        ];

        return $response;
    }

    public function get_today_order_value()
    {

        $combined_store = $this->get_combined_stores();

        $yesterday = date('Y-m-d', strtotime(Carbon::now()->subDays(1)));
        $day_before_yesterday = date('Y-m-d', strtotime(Carbon::now()->subDays(2)));

        $order_value = OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('orders.total_order_amount');

        $return_order_value = OrderModel::query()
            ->whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('orders.return_order_amount');

        $order_value = $order_value - $return_order_value;
        $order_value_formatted = number_format_short($order_value);

        $yesterday_value = OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('total_order_amount');

        $day_before_yesterday_value = OrderModel::where('orders.value_date', $day_before_yesterday)
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('total_order_amount');

        $sale_value_chart = [
            'x_axis' => [trans('Day Before Yest') . '.', trans('Yesterday'), trans('Today')],
            'y_axis' => [$day_before_yesterday_value, $yesterday_value, $order_value]
        ];

        $response = [
            "count_raw" => $order_value,
            "count_formatted" => $order_value_formatted,
            "chart" => $sale_value_chart
        ];

        return $response;
    }

    public function get_customer_count()
    {

        $count = CustomerModel::active()
            ->whereBetween('created_at', [$this->from_date, $this->to_date])
            ->count();

        $count_formatted = number_format_short($count);

        $response = [
            "count_raw" => $count,
            "count_formatted" => $count_formatted
        ];

        return $response;
    }

    public function get_total_revenue()
    {

        $combined_store = $this->get_combined_stores();

        // $sum = OrderModel::closed()
        // ->whereBetween(DB::raw('created_at)'),[$this->from_date,$this->to_date])
        // // ->where('store_id',session('store_id'))
        // ->withoutGlobalScopes()
        // ->when( $combined_store['show_combined_stats'] == 1, function($query) use($combined_store) {
        //     $query->whereIn('orders.store_id', $combined_store['store_id']);
        // })
        // ->when( $combined_store['show_combined_stats'] == 0, function($query) use($combined_store) {
        //     $query->where('orders.store_id', $combined_store['store_id']);
        // })
        // ->where('orders.status',1)
        // ->sum('total_order_amount');

        $transaction_type_data = MasterTransactionTypeModel::select('id')
            ->where('transaction_type_constant', '=', trim('INCOME'))
            ->first();

        $transaction_income = TransactionModel::select('id')->where([
            ['transaction_type', '=', $transaction_type_data->id],
        ])
            ->whereIn('bill_to', ['POS_ORDER', 'INVOICE', 'CUSTOMER', 'SUPPLIER'])
            ->whereBetween('created_at', [$this->from_date, $this->to_date])
            // ->where('store_id',session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('transactions.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('transactions.store_id', $combined_store['store_id']);
            })
            ->sum('amount');

        $sum = $transaction_income;
        // $sum_formatted = number_format_short($sum);
        $sum_formatted = $sum;

        $response = [
            "count_raw" => $sum,
            "count_formatted" => $sum_formatted
        ];
        return $response;
    }

    public function get_total_expense()
    {

        $combined_store = $this->get_combined_stores();

        //$this->from_date = date_format(date_create($this->from_date),"Y-m-d");
        //$this->to_date = date_format(date_create($this->to_date),"Y-m-d");

        $transaction_type_data = MasterTransactionTypeModel::select('id')
            ->where('transaction_type_constant', '=', trim('EXPENSE'))
            ->first();

        $transaction_expense = TransactionModel::select('id')->where([
            ['transaction_type', '=', $transaction_type_data->id],
        ])
            ->whereIn('bill_to', ['INVOICE', 'CUSTOMER', 'SUPPLIER'])
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$this->from_date, $this->to_date])
            // ->where('store_id',session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('transactions.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('transactions.store_id', $combined_store['store_id']);
            })
            ->sum('amount');

        $sum_formatted = number_format_short($transaction_expense);

        $response = [
            "count_raw" => round($transaction_expense, 2),
            "count_formatted" => $sum_formatted
        ];

        return $response;
    }


    public function get_top_selling_earning_products()
    {

        /***************** total_sales_quantity  *************/

        $combined_store = $this->get_combined_stores();
        $data['total_sales_quantity'] = round(OrderProductModel::active()
            ->whereBetween('orders.created_at', [$this->from_date, $this->to_date])
            ->where('orders.status', 1)
            ->orderJoin()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('order_products.quantity'), 2);

        /***************** total_sales_margin_amount  *************/

        $total_sales_margin_amount = OrderProductModel::active()
            ->select(DB::Raw('sum(quantity*sale_amount_excluding_tax) - sum(quantity*purchase_amount_excluding_tax) as amount'))
            ->whereBetween('orders.created_at', [$this->from_date, $this->to_date])
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->orderJoin()
            ->where('orders.status', 1)
            ->first();

        $data['total_sales_margin_amount'] =  (isset($total_sales_margin_amount)) ? round($total_sales_margin_amount->amount, 2) : 0;

        /***************** top_selling_products  *************/

        $top_selling_products = OrderProductModel::active()
            ->select('order_products.product_id', 'order_products.name', DB::Raw('sum(order_products.quantity) as sum'))
            ->whereBetween("orders.created_at", [$this->from_date, $this->to_date])
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->groupBy('order_products.product_id')
            ->orderBy('sum', 'DESC')
            ->limit(5)
            ->orderJoin()
            ->where('orders.status', 1)
            ->get();

        $data['top_selling_products'] = [];
        if (isset($top_selling_products)) {
            foreach ($top_selling_products as $rs) {
                $dataset = $rs;
                $dataset['sum'] = round($rs['sum'], 2);
                $data['total_sales_quantity'] = ($data['total_sales_quantity'] == 0) ? 1 : $data['total_sales_quantity'];
                $dataset['percent'] = round(((float) $rs->sum * 100) / (int) $data['total_sales_quantity']);

                $data['top_selling_products'][] = $dataset;
            }
        }

        $data['top_selling_products'] = TopSellingProductResource::collection($data['top_selling_products']);

        /***************** top_earning_products  *************/
        $top_earning_products = OrderProductModel::active()
            ->groupBy('product_id')
            ->select('order_products.product_id', 'order_products.name', DB::Raw('sum(order_products.quantity*order_products.sale_amount_excluding_tax) - sum(order_products.quantity*order_products.purchase_amount_excluding_tax) as amount'))
            ->whereBetween('orders.created_at', [$this->from_date, $this->to_date])
            // ->where('orders.store_id', session('store_id'))
            ->orderBy('amount', 'DESC')
            ->limit(5)
            ->active()
            ->orderJoin()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->where('orders.status', 1)
            ->get();

        $data['top_earning_products'] = [];
        if (isset($top_earning_products)) {
            foreach ($top_earning_products as $rs) {
                $dataset = $rs;
                $dataset['amount'] = round($rs->amount, 2);
                $data['total_sales_margin_amount'] = ($data['total_sales_margin_amount'] == 0) ? 1 : $data['total_sales_margin_amount'];
                if ($data['total_sales_margin_amount'] == 0 || $data['total_sales_margin_amount'] < 1) {
                    $dataset['percent'] = 0;
                } else {
                    $dataset['percent'] = round(((float) $rs->amount * 100) / (int) $data['total_sales_margin_amount']);
                }
                $data['top_earning_products'][] = $dataset;
            }
        }

        $data['top_earning_products'] = TopEarningProductResource::collection($data['top_earning_products']);

        return $data;
    }

    public function get_net_profit()
    {

        $combined_store = $this->get_combined_stores();

        $revenue = $this->get_total_revenue();

        $revenue_value = $revenue['count_raw'];

        $transaction_type_data = MasterTransactionTypeModel::select('id')
            ->where('transaction_type_constant', '=', trim('EXPENSE'))
            ->first();

        $transaction_expense = TransactionModel::select('id')->where([
            ['transaction_type', '=', $transaction_type_data->id],
        ])
            ->whereIn('bill_to', ['INVOICE', 'CUSTOMER', 'SUPPLIER'])
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            // ->where('store_id',session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('transactions.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('transactions.store_id', $combined_store['store_id']);
            })
            ->sum('amount');

        $net_profit = $revenue_value - $transaction_expense;

        // $net_profit_formatted = number_format_short($net_profit);
        $net_profit_formatted = $net_profit;

        $response = [
            "count_raw" => $net_profit,
            "count_formatted" => $net_profit_formatted
        ];

        return $response;
    }

    public function get_po_count()
    {

        /*
            Purchase order counts 
            1. Total Pending Purchase Orders
            2. Total Closed Purchase Orders
        */

        $combined_store = $this->get_combined_stores();

        $data['pending_po_count_raw'] = PurchaseOrderModel::select('id')
            ->statusJoin()
            ->whereBetween('purchase_orders.created_at', [$this->from_date, $this->to_date])
            ->whereIn('value_constant', ['CREATED', 'APPROVED', 'RELEASED_TO_SUPPLIER'])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('purchase_orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('purchase_orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $data['closed_po_count_raw'] = PurchaseOrderModel::select('id')
            ->statusJoin()
            ->whereBetween('purchase_orders.created_at', [$this->from_date, $this->to_date])
            ->whereIn('value_constant', ['CLOSED'])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('purchase_orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('purchase_orders.store_id', $combined_store['store_id']);
            })
            ->count();

        $data['pending_po_count_formatted'] = number_format_short($data['pending_po_count_raw']);
        $data['closed_po_count_formatted'] = number_format_short($data['closed_po_count_raw']);

        return $data;
    }

    public function get_invoice_count()
    {

        $combined_store = $this->get_combined_stores();
        $paid_invoice_count = InvoiceModel::select('id')
            ->statusJoin()
            ->whereBetween('invoices.invoice_date', [$this->from_date, $this->to_date])
            // ->where('store_id', session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->where('value_constant', 'PAID')->count();
        $paid_invoice_count_formatted = number_format_short($paid_invoice_count);

        $pending_invoice_count = InvoiceModel::select('id')
            ->statusJoin()
            ->whereBetween('invoices.invoice_date', [$this->from_date, $this->to_date])
            // ->where('store_id', session('store_id'))
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereIn('value_constant', ['NEW', 'SENT', 'OVERDUE', 'WRITEOFF', 'PARTIAL_PAY'])->count();
        $pending_invoice_count_formatted = number_format_short($pending_invoice_count);

        $response = [
            "paid_count_raw" => $paid_invoice_count,
            "paid_count_formatted" => $paid_invoice_count_formatted,
            "pending_count_raw" => $pending_invoice_count,
            "pending_count_formatted" => $pending_invoice_count_formatted
        ];



        return $response;
    }

    public function get_target_values()
    {

        $combined_store = $this->get_combined_stores();

        $target = TargetModel::select('income', 'expense', 'sales', 'net_profit')
            ->whereBetween('month', [$this->from_date, $this->to_date])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('targets.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('targets.store_id', $combined_store['store_id']);
            })
            ->first();

        $income = ($target) ? $target->income : 0;
        $expense = ($target) ? $target->expense : 0;
        $sales = ($target) ? $target->sales : 0;
        $net_profit = ($target) ? $target->net_profit : 0;

        $response = [
            "income" => $income,
            "expense" => $expense,
            "sales" => $sales,
            "net_profit" => $net_profit
        ];

        return $response;
    }

    public function get_recent_trasactions(Request $request)
    {

        $combined_store = $this->get_combined_stores();

        try {

            $transactions_list = TransactionModel::select('*')
                ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
                ->orderBy('created_at', 'DESC')
                ->withoutGlobalScopes()
                ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                    $query->whereIn('transactions.store_id', $combined_store['store_id']);
                })
                ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                    $query->where('transactions.store_id', $combined_store['store_id']);
                })
                ->limit(10)
                ->get();

            $transactions = TransactionResource::collection($transactions_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Recent transactions loaded successfully",
                    "data" => $transactions,
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

    public function get_billing_counter_stats(Request $request)
    {

        $combined_store = $this->get_combined_stores();

        try {
            $date = date("Y-m-d");

            $payment_methods = PaymentMethodModel::select('id', 'label')
                ->active()
                ->get();

            $billing_counters = BillingCounterModel::select("*")
                ->active()
                ->withoutGlobalScopes()
                ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                    $query->whereIn('billing_counters.store_id', $combined_store['store_id']);
                })
                ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                    $query->where('billing_counters.store_id', $combined_store['store_id']);
                })
                ->get();

            $billing_counters_data = BillingCounterResource::collection($billing_counters);

            $billing_counters_data = $billing_counters_data->map(function ($billing_counter, $key) use ($date, $payment_methods) {

                $business_registers = BusinessRegisterModel::select('id')
                    ->where('billing_counter_id', '=', $billing_counter->id)
                    ->where(function ($query) {
                        $query->whereBetween('opening_date', [$this->from_date, $this->to_date])
                            ->orWhereBetween('closing_date', [$this->from_date, $this->to_date]);
                    })
                    ->pluck('id')->toArray();

                $business_register_ids = array_values($business_registers);

                $order_data = [];
                $payment_method_array = [];

                if (count($business_register_ids) > 0) {

                    $order_data = OrderModel::select(DB::raw('COUNT(id) as order_count, SUM(total_order_amount) as order_value'))
                        ->where('created_at', 'like', $date . '%')
                        ->whereIn('register_id', $business_register_ids)

                        ->first();
                }

                $billing_counter =  collect(['order_data' => $order_data])->merge($billing_counter);

                foreach ($payment_methods as $payment_method) {

                    $payment_method_order_amount = OrderModel::select(DB::raw('COUNT(id) as order_count, SUM(total_order_amount) as order_value'))
                        ->where([
                            ['created_at', 'like', $date . '%'],
                            ['payment_method_id', '=', $payment_method->id]
                        ])
                        ->whereIn('register_id', $business_register_ids)

                        ->first();

                    $payment_method_array[] = [
                        'payment_method' => $payment_method['label'],
                        'value' => $payment_method_order_amount->order_value,
                        'order_count' => $payment_method_order_amount->order_count
                    ];
                }

                $billing_counter =  collect(['payment_method_data' => $payment_method_array])->merge($billing_counter);

                return $billing_counter;
            });

            return response()->json($this->generate_response(
                array(
                    "message" => "Billing counter stats loaded successfully",
                    "data" => $billing_counters_data,
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

    public function get_invoice_data()
    {

        $combined_store = $this->get_combined_stores();

        $data['in_invoice'] =
            InvoiceModel::query()
            ->join('transactions', 'transactions.bill_to_id', 'invoices.id')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            ->where('transactions.bill_to', 'INVOICE')
            ->whereIn('invoices.status', ['3', '7'])
            ->sum('transactions.amount');

        return $data;
    }

    public function get_paid_pending_invoice()
    {

        /*
            Pending Sales amount as 
            1. Pending Invoice Amount Total
            2. Paid Invoice Amount Total
        */

        $combined_store = $this->get_combined_stores();

        $pending_amount =
            InvoiceModel::query()
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('invoices.invoice_date', [$this->from_date, $this->to_date])
            ->whereIn('invoices.status', ['1', '2', '4', '6'])
            ->sum('invoices.total_order_amount');

        $partial_paid_amount =
            InvoiceModel::query()
            ->join('transactions', 'transactions.bill_to_id', 'invoices.id')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            ->where('transactions.bill_to', 'INVOICE')
            ->where('invoices.status', '7')
            ->sum('transactions.amount');

        $total_order_amount =
            InvoiceModel::query()
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('invoices.invoice_date', [$this->from_date, $this->to_date])
            ->where('invoices.status', '7')
            ->sum('invoices.total_order_amount');

        $data['pending_amount'] = $pending_amount + ($total_order_amount - $partial_paid_amount);
        $data['paid_amount'] =
            InvoiceModel::query()
            ->join('transactions', 'transactions.bill_to_id', 'invoices.id')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            ->where('transactions.bill_to', 'INVOICE')
            ->sum('transactions.amount');

        return [
            'paid_amount_raw' => $data['paid_amount'],
            'paid_amount_formatted' => number_format_short($data['paid_amount']),
            'pending_amount_raw' => $data['pending_amount'],
            'pending_amount_formatted' => number_format_short($data['pending_amount'])
        ];
    }

    public function get_paid_pending_pos()
    {

        /*
            Pending Sales amount as 
            1. Pending POS Amount Total
            2. Paid POS Amount Total
        */

        $combined_store = $this->get_combined_stores();

        $total_paid_amount = OrderModel::whereBetween('value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->sum('total_order_amount');

        $total_pending_amount = OrderModel::query()
            ->whereBetween('value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->whereNotIn('orders.status', [1, 6])
            ->sum('total_order_amount');

        return [
            'paid_amount_raw' => $total_paid_amount,
            'paid_amount_formatted' => number_format_short($total_paid_amount),
            'pending_amount_raw' => $total_pending_amount,
            'pending_amount_formatted' => number_format_short($total_pending_amount)
        ];
    }

    public function get_revenue()
    {

        /*
            Revenue is considered as following
            1. Sum of General Invoice with Cash & Credit
            2. Sum of POS Invoice with Cash & Credit
        */
        //echo $this->from_date;die;
        $combined_store = $this->get_combined_stores();

        $data = [];

        $data['invoice_credit'] =
            InvoiceModel::query()
            ->join('transactions', 'transactions.bill_to_id', 'invoices.id')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            ->where('transactions.bill_to', 'INVOICE')
            ->where('transactions.payment_method', '!=', 'Cash')
            ->whereIn('invoices.status', ['3', '7'])
            ->sum('transactions.amount');

        $data['invoice_cash'] =
            InvoiceModel::query()
            ->join('transactions', 'transactions.bill_to_id', 'invoices.id')
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('invoices.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('invoices.store_id', $combined_store['store_id']);
            })
            ->whereBetween('transactions.created_at', [$this->from_date, $this->to_date])
            ->where('transactions.bill_to', 'INVOICE')
            ->where('transactions.payment_method', 'Cash')
            ->whereIn('invoices.status', ['3', '7'])
            ->sum('transactions.amount');


        $pos_credit =
            OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->select(
                DB::Raw('IFNULL(SUM(credit_amount),0) as credit_amount'),
            )->first();


        $pos_cash =
            OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->select(
                DB::Raw('IFNULL(SUM(cash_amount),0) as cash_amount'),
            )->first();


        $pos_change =
            OrderModel::whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->select(
                DB::Raw('IFNULL(SUM(change_amount),0) as change_amount'),
            )->first();

        $pos_returned =
            OrderModel::query()
            ->whereBetween('orders.value_date', [$this->from_date_only, $this->to_date_only])
            ->withoutGlobalScopes()
            ->when($combined_store['show_combined_stats'] == 1, function ($query) use ($combined_store) {
                $query->whereIn('orders.store_id', $combined_store['store_id']);
            })
            ->when($combined_store['show_combined_stats'] == 0, function ($query) use ($combined_store) {
                $query->where('orders.store_id', $combined_store['store_id']);
            })
            ->select(
                DB::Raw('IFNULL(SUM(return_order_amount),0) as returned_amount')
            )->first();

        $data['pos_returned'] = 0;
        if (isset($pos_returned)) {
            $data['pos_returned'] = $pos_returned->returned_amount;
        }

        $data['pos_credit'] = 0;
        if (isset($pos_credit)) {
            $data['pos_credit'] = $pos_credit->credit_amount;
        }

        $data['pos_cash'] = 0;
        if (isset($pos_cash)) {
            $data['pos_cash'] = $pos_cash->cash_amount;
        }

        $data['pos_change'] = 0;
        if (isset($pos_change)) {
            $data['pos_change'] = $pos_change->change_amount;
        }

        return $data;
    }

    public function get_combined_stores($show_combined_stats = false)
    {

        $dataset = [];

        if (Session::has('show_combined_stats') && Session::get('show_combined_stats') == true) {

            $dataset['show_combined_stats'] = 1;
            $dataset['store_id']  = UserStoreModel::where('user_id', session('user_id'))->pluck('store_id')->toArray();
        } else {

            $dataset['show_combined_stats'] = 0;
            $dataset['store_id']  = session('store_id');
        }

        return $dataset;
    }

    public function set_combined_stores(Request $request)
    {

        if ($request->combined == 'true') {
            Session::put('show_combined_stats', true);
        } else {
            Session::put('show_combined_stats', false);
        }
    }
}
