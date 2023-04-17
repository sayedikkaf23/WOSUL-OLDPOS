<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer as CustomerModel;
use App\Models\MasterStatus;
use App\Models\Country as CountryModel;

use App\Http\Resources\CustomerResource;
use App\Models\Order;
use App\Models\ReturnOrders;
use App\Models\Invoice;
use App\Models\InvoiceReturn;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Customer extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_USER';
        $data['sub_menu_key'] = 'SM_CUSTOMERS';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('customer.customers', $data);
    }

    //This is the function that loads the add/edit page
    public function add_customer($slack = null){
        //check access
        $data['menu_key'] = 'MM_USER';
        $data['sub_menu_key'] = 'SM_CUSTOMERS';
        $data['action_key'] = ($slack == null)?'A_ADD_CUSTOMER':'A_EDIT_CUSTOMER';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('CUSTOMER_STATUS')->active()->sortValueAsc()->get();

        $data['country_list'] = CountryModel::select('id as country_id', 'name', 'code')
        ->active()
        ->groupBy('code')
        ->get();


        $data['customer_data'] = null;
        if(isset($slack)){
            $customer = CustomerModel::where('customers.slack', $slack)
            ->first();

            if (empty($customer)) {
                abort(404);
            }
            $customer_data = new CustomerResource($customer);
            $data['customer_data'] = $customer_data;
        }

        return view('customer.add_customer', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_USER';
        $data['sub_menu_key'] = 'SM_CUSTOMERS';
        $data['action_key'] = 'A_DETAIL_CUSTOMER';
        check_access([$data['action_key']]);

        $customer = CustomerModel::where('slack', $slack)
        ->first();
        
        if (empty($customer)) {
            abort(404);
        }

        $customer_data = new CustomerResource($customer);

        $data['customer_data'] = $customer_data;
        $total_order = Order::withoutGlobalScopes()->where('customer_id',$customer_data->id)->count();
        $total_order_amount = Order::withoutGlobalScopes()->where('customer_id',$customer_data->id)->sum('total_order_amount');
        $total_return_order = ReturnOrders::withoutGlobalScopes()->where('customer_id',$customer_data->id)->count();
        $total_return_order_amount = ReturnOrders::withoutGlobalScopes()->where('customer_id',$customer_data->id)->sum('total_order_amount');
        $total_invoice = Invoice::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->count();
        $total_invoice_amount = Invoice::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->sum('total_order_amount');
        $total_return_invoice = InvoiceReturn::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->count();
        $total_return_invoice_amount = InvoiceReturn::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->sum('total_order_amount');

        $last_order_date = Order::withoutGlobalScopes()->select('created_at')->where('customer_id',$customer_data->id)->orderBy('created_at','DESC')->first();
        $fav_product = OrderProduct::withoutGlobalScopes()->select('order_products.name', DB::raw('count(order_products.product_id) as total'))
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.customer_id',$customer_data->id)
            ->groupBy('order_products.product_id')
            ->orderBy('total','DESC')
            ->first();
        
        $fav_store = Order::withoutGlobalScopes()->select('stores.name', DB::raw('count(orders.store_id) as total'))
            ->join('stores', 'stores.id', '=', 'orders.store_id')
            ->where('orders.customer_id',$customer_data->id)
            ->groupBy('orders.store_id')
            ->orderBy('total','DESC')
            ->first();

        $pending_invoice = Invoice::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->where('status',1)->count();
        $closed_invoice = Invoice::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->where('status',3)->count();
        $partial_pay_invoice = Invoice::withoutGlobalScopes()->where('bill_to_id',$customer_data->id)->where('status',7)->count();

        $data['count_data']['total_order']=$total_order;
        $data['count_data']['total_order_amount']=$total_order_amount;
        $data['count_data']['total_return_order']=$total_return_order;
        $data['count_data']['total_return_order_amount']=$total_return_order_amount;
        $data['count_data']['total_invoice']=$total_invoice;
        $data['count_data']['total_invoice_amount']=$total_invoice_amount;
        $data['count_data']['total_return_invoice']=$total_return_invoice;
        $data['count_data']['total_return_invoice_amount']=$total_return_invoice_amount;
        $data['count_data']['pending_invoice']=$pending_invoice;
        $data['count_data']['closed_invoice']=$closed_invoice;
        $data['count_data']['partial_pay_invoice']=$partial_pay_invoice;

        $data['count_data']['last_order_date']=(isset($last_order_date) && $last_order_date->created_at!=null)?$last_order_date->created_at->format("F d, h:i a"):'-';
        $data['count_data']['fav_product']=(isset($fav_product) && $fav_product->total!=null)?$fav_product->name:'-';
        $data['count_data']['fav_store']=(isset($fav_store) && $fav_store->total!=null)?$fav_store->name:'-';
        return view('customer.customer_detail', $data);
    }

    public function delete_customer($slack){
        
        CustomerModel::where('slack',$slack)->delete();

        return redirect()->back();

    }
}
