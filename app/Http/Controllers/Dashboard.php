<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Models\Store as StoreModel;
use App\Models\Order as OrderModel;
use App\Models\Invoice as InvoiceModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Product as ProductModel;
use App\Models\UserStore as UserStoreModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\Taxcode as TaxcodeModel;

class Dashboard extends Controller
{
    //This is the function that loads the dashboard page
    public function index(Request $request)
    {
        // $temp = new \Illuminate\Http\Request($request);
        // $order = new \App\Http\Controllers\API\Order($request);
        // $order = $order->show('2CunJhvZl4');
        // dd($order);
        // exit;

        $data['store_names'] = [];
        $data['date_formatted'] = Config::get('constants.date_format_frontend');

        if (session('is_master') == 1) {

            $data['store_names']  = UserStoreModel::join('stores', 'stores.id', 'user_stores.store_id')->where('user_stores.user_id', session('user_id'))->select('stores.id as id', 'stores.name as name')->get()->toArray();

            if (isset($stores) && count($stores) > 0) {
                foreach ($stores as $store) {
                    $dataset['id'] = $store['id'];
                    $dataset['name'] = $store['name'];
                    $data['store_names'][] = $dataset;
                }
            }
        } else {

            $data['store_names']  = StoreModel::where('id', session('store_id'))->select('id', 'name')->get()->toArray();

            if (isset($data['store_names'])) {
                $data['store_names'] = $data['store_names'];
            }
        }

        $store_names = [];

        if (isset($data['store_names']) && count($data['store_names']) > 0) {

            foreach ($data['store_names'] as $store_name) {

                $dataset = [];
                $dataset['id'] = $store_name['id'];
                $dataset['name'] = $store_name['name'];
                $dataset['active_status'] = false;
                $store_names[] = $dataset;
            }
        }

        $data['store_names'] = $store_names;

        $data['menu_key'] = "MM_DASHBOARD";
        $data['sub_menu_key'] = "SM_MASTER_DASHBOARD";
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['store'] = StoreModel::select('currency_name', 'currency_code', 'store_opening_time', 'store_closing_time')
            ->where('id', $request->logged_user_store_id)
            ->first();
        $data['revenue']['in_invoice'] = InvoiceModel::active()->sum('total_order_amount');
        $data['revenue']['in_pos_credit'] = OrderModel::closed()->sum('credit_amount');
        $data['revenue']['in_pos_cash'] = OrderModel::closed()->sum('cash_amount');

        $data['total_sales_quantity'] = OrderProductModel::orderJoin()->active()->where('orders.status', 1)->sum('order_products.quantity');

        $data['total_sales_amount'] = OrderProductModel::active()->select(DB::Raw('sum(quantity*sale_amount_excluding_tax) as amount'))->first();
        $data['total_sales_amount'] =  (isset($data['total_sales_amount'])) ? $data['total_sales_amount']->amount : 0;

        $data['total_sales_margin_amount'] = OrderProductModel::active()->select(DB::Raw('sum(quantity*sale_amount_excluding_tax) - sum(quantity*purchase_amount_excluding_tax) as amount'))->first();
        $data['total_sales_margin_amount'] =  (isset($data['total_sales_margin_amount'])) ? $data['total_sales_margin_amount']->amount : 0;

        $top_selling_products = OrderProductModel::active()
            ->groupBy('product_id')
            ->select('order_products.product_id', 'order_products.name', DB::Raw('sum(order_products.quantity) as sum'))
            ->orderBy('sum', 'DESC')
            ->limit(4)
            ->orderJoin()
            ->where('orders.status', 1)
            ->get();

        $data['top_selling_products'] = [];
        if (isset($top_selling_products)) {
            foreach ($top_selling_products as $rs) {
                $dataset = $rs;
                $dataset['sum'] = round($rs['sum'], 2);
                $data['total_sales_quantity'] = ($data['total_sales_quantity'] == 0) ? 1 : $data['total_sales_quantity'];
                if ((int) $data['total_sales_quantity'] > 0) {
                    $dataset['percent'] = round(((float) $rs->sum * 100) / (int) $data['total_sales_quantity']);
                } else {
                    $dataset['percent'] = 0;
                }

                $data['top_selling_products'][] = $dataset;
            }
        }

        $top_earning_products = OrderProductModel::active()
            ->groupBy('product_id')
            ->select('order_products.product_id', 'order_products.name', DB::Raw('sum(order_products.quantity*order_products.sale_amount_excluding_tax) - sum(order_products.quantity*order_products.purchase_amount_excluding_tax) as amount'))
            ->orderBy('amount', 'DESC')
            ->limit(4)
            ->active()
            ->get();

        $data['top_earning_products'] = [];
        if (isset($top_earning_products)) {
            foreach ($top_earning_products as $rs) {
                $dataset = $rs;
                $dataset['amount'] = round($rs->amount, 2);
                $data['total_sales_margin_amount'] = ($data['total_sales_margin_amount'] == 0) ? 1 : $data['total_sales_margin_amount'];
                if ($data['total_sales_margin_amount'] == 0) {
                    $dataset['percent'] = 0;
                } else {
                    if ((int) $data['total_sales_margin_amount'] != 0) {
                        $dataset['percent'] = round(((float) $rs->amount * 100) / (int) $data['total_sales_margin_amount']);
                    } else {
                        $dataset['percent'] = 0;
                    }
                }
                $data['top_earning_products'][] = $dataset;
            }
        }

        $data['chart']['revenue_value'] = app('App\Http\Controllers\API\Dashboard')->get_total_revenue();
        $data['chart']['expense'] = app('App\Http\Controllers\API\Dashboard')->get_total_expense();
        $data['chart']['net_profit_value'] = app('App\Http\Controllers\API\Dashboard')->get_net_profit();
        $data['dashboard_start_date'] = (new \DateTime(date('Y-m-d')))->format('Y-m-d');
        $data['dashboard_end_date'] = (new \DateTime(date('Y-m-d')))->add(new \DateInterval("P1D"))->format('Y-m-d');
        $data['show_combined_stats'] = Session::get('show_combined_stats');

        
        return view('dashboard.dashboard', $data);
    }

    public function billing_counter_dashboard(Request $request)
    {
        $data['menu_key'] = "MM_DASHBOARD";
        $data['sub_menu_key'] = "SM_BILLING_COUNTER_DASHBOARD";
        check_access(array($data['menu_key'], $data['sub_menu_key']));
        $logged_user_store_id = session('store_id');

        $data['store'] = StoreModel::select('currency_name', 'currency_code')
            ->where('id', $logged_user_store_id)
            ->first();

        return view('dashboard.billing_counter_dashboard', $data);
    }
}
