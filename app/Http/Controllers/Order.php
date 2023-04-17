<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Order as APIOrder;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SettingAppResource;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Order as OrderModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Customer as CustomerModel;
use App\Models\Store as StoreModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Category as CategoryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\Account as AccountModel;
use App\Models\MasterOrderType as MasterOrderTypeModel;
use App\Models\Table as TableModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\Role as RoleModel;
use App\Models\SettingApp as SettingAppModel;
use App\Models\KeyboardShortcut as KeyboardShortcutModel;
use App\Models\Product as ProductModel;
use App\Models\User as UserModel;
use App\Models\BillingCounter as BillingCounterModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\OrderProductModifierOption as OrderProductModifierOptionModel;
use App\Models\Language as LanguageModel;
use App\Models\DamageReportModel;
use App\Http\Resources\DamageResource;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\PriceResource;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;
use Mpdf\Mpdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ReturnOrderResource;
use App\Http\Resources\ProductResource;
use App\Models\ReturnOrders as ReturnOrdersModel;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;
use Carbon\Carbon;
use Schema;
use Exception;
// use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// use Mike42\Escpos\Printer;
use App\Http\Traits\CommonApiTrait;
use App\Models\Combo;
use App\Models\Price;

class Order extends Controller
{
    use CommonApiTrait;

    public function __construct()
    {
    }
    //This is the function that loads the listing page
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access(array($data['menu_key'], $data['sub_menu_key']));
        //order
        $data['order_statuses'] = MasterStatus::select('value', 'label')->filterByKey('ORDER_STATUS')->active()->sortValueAsc()->get();

        $data['store'] = StoreModel::select('currency_name', 'currency_code', 'store_opening_time', 'store_closing_time', 'is_store_closing_next_day')
            ->where('id', $request->logged_user_store_id)
            ->first();

        $date_format = config()->get('date_format_backend');
        $data['store_opening_time'] = Carbon::parse(request()->logged_store_opening_time)->format($date_format . '\TH:i:s');
        $data['store_closing_time'] = Carbon::parse(request()->logged_store_closing_time)->format($date_format . '\TH:i:s');

        $order_value_date = new APIOrder();
        $data['order_value_date'] = $order_value_date->get_order_value_date();

        return view('order.orders', $data);
    }

    public function return_orders(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_RETURN_ORDERS';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        return view('return_order.return_order_list', $data);
    }

    public function flatten(array $array)
    {
        $return = array();
        array_walk_recursive($array, function ($value, $key) use (&$return) {
            $return[$key] = $value;
        });
        return $return;
    }


    //This is the function that loads the add/edit page
    public function add_order(Request $request, $slack = null)
    {
        
        $this->send_to_kitchen_access = check_access(array('A_SEND_TO_KITCHEN'), true);
        $this->can_gift_access = check_access(array('A_GIFT_ORDER'), true);
        $this->can_hold_access = check_access(array('A_HOLD_ORDER'), true);
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        $data['action_key'] = ($slack == null) ? 'A_ADD_ORDER' : 'A_EDIT_ORDER';
        check_access(array($data['action_key']));

        $store_detail = StoreModel::find($request->logged_user_store_id);

        if($store_detail->platform_mode == 'OFFLINE'){
            $data['message']  =  trans("Sorry! POS cannot be used on multiple platforms in offline mode, please change platform mode from store settings to online mode to continue");
            return view('message', $data);
        }

        $business_register_data = BusinessRegisterModel::select('slack', 'billing_counter_id')
            ->where('user_id', '=', trim($request->logged_user_id))
            ->whereNull('closing_date')
            ->first();

        if (empty($business_register_data)) {
            return redirect('add_business_register');
        }

        $data['new_order_link'] = route('add_order');

        $data['store_tax_percentage'] = null;
        $data['store_tax_components'] = null;
        $data['store_discount_percentage'] = null;
        $data['enable_customer_detail_popup'] = true;

        $store_data = StoreModel::select('id', 'tax_code_id', 'discount_code_id', 'currency_code', 'restaurant_billing_type_id', 'restaurant_waiter_role_id', 'enable_customer_popup', 'store_opening_time', 'store_closing_time', 'is_store_closing_next_day','price_id','is_price_enabled')
            ->where([
                ['id', '=', $request->logged_user_store_id],
                ['status', '=', 1]
            ])
            ->first();
        if (empty($store_data)) {
            return redirect('select_store');
        }

        $data['store_tax_code_id'] = $store_data->tax_code_id;
        $data['store_currency'] = $store_data->currency_code;
        if (isset($store_data->tax_code_id)) {
            $taxcode_data = TaxcodeModel::withoutGlobalScopes()->select('id', 'label', 'total_tax_percentage')
                ->where('id', '=', $store_data->tax_code_id)
                ->active()
                ->first();
            $data['store_tax_percentage'] = (isset($taxcode_data->total_tax_percentage)) ? $taxcode_data->total_tax_percentage : 0.00;
            $data['store_tax_label'] = (isset($taxcode_data->label)) ? $taxcode_data->label : 0.00;
            $data['store_tax_components'] = isset($taxcode_data->tax_components) ? $taxcode_data->tax_components : [];
        }
        // dd($taxcode_data->tax_components);
        if (isset($store_data->discount_code_id)) {
            $currentdate = date('Y-m-d H:i:s');
            $discountcode_data = DiscountcodeModel::select('*')
                ->where('id', '=', trim($store_data->discount_code_id))
                ->where("discounttype", "code")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->first();
            $data['store_discount_percentage'] = (isset($discountcode_data->discount_percentage)) ? $discountcode_data->discount_percentage : 0.00;
        }
        if (isset($store_data->id)) {
            $currentdate = date('Y-m-d H:i:s');
            $discountcodes = DiscountcodeModel::select('*')
                ->where('store_id', '=', trim($store_data->id))
                ->where("discounttype", "code")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->get();

            $discountcodes_cashier = DiscountcodeModel::select('*')
                ->where('store_id', '=', trim($store_data->id))
                ->where("discounttype", "cashier")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->get();


            $data['store_discount_codes'] = !empty($discountcodes) ? $discountcodes : [];
            $data['store_discount_codes_cashier'] = !empty($discountcodes_cashier) ? $discountcodes_cashier : [];
        }


        $data['enable_customer_detail_popup'] = ($store_data->enable_customer_popup == 0) ? false : true;

        $categories = CategoryModel::select('slack', 'category_code', 'label')->categoryStore()->active()->sortLabelAsc()->get();
        $data['categories'] = (!empty($categories)) ? $categories : [];

        $payment_methods = PaymentMethodModel::select('slack', 'label', 'id')->Where('payment_constant', '!=', 'CASH')
            ->active()
            ->get();
        $data['payment_methods'] = (!empty($payment_methods)) ? $payment_methods : [];

        $default_business_account = AccountModel::select('slack', 'account_code', 'label')
            ->where('pos_default', '=', 1)
            ->active()
            ->first();
        $data['default_business_account'] = $default_business_account;

        $business_accounts = AccountModel::select('accounts.slack', 'accounts.account_code', 'accounts.label', 'master_account_type.label as account_type_label')
            ->masterAccountTypeJoin()
            ->active()
            ->get();
        $data['business_accounts'] = (!empty($business_accounts)) ? $business_accounts : [];

        $data['store_restaurant_mode'] = ($request->logged_user_store_restaurant_mode == 1) ? true : false;

        $data['restaurant_order_types'] = null;
        $data['vacant_tables'] = null;
        $data['billing_types']  = null;
        $data['store_billing_type'] = null;
        $data['store_waiter_role_slack'] = null;
        if ($request->logged_user_store_restaurant_mode == true) {

            $occupied_tables = OrderModel::select('table_id', 'table_number')->whereNotNull('table_id')
                ->when($slack, function ($query, $slack) {
                    return $query->where('slack', '!=', $slack);
                })
                ->inkitchen()->get();
            $occupied_tables_list = ($occupied_tables->count() > 0) ? $occupied_tables->pluck('table_id') : [];

            $data['restaurant_order_types'] = MasterOrderTypeModel::select('order_type_constant', 'label')->where('restaurant', 1)->active()->get();

            $data['vacant_tables'] = TableModel::select('slack', 'table_number', 'no_of_occupants')
                ->whereNotIn('id', $occupied_tables_list)
                ->active()
                ->get();

            $data['billing_types'] = MasterBillingTypeModel::select('billing_type_constant', 'label')
                ->active()
                ->get();

            if ($store_data->restaurant_billing_type_id != '') {
                $store_billing_type = MasterBillingTypeModel::select('billing_type_constant')
                    ->where('id', '=', $store_data->restaurant_billing_type_id)
                    ->active()
                    ->first();

                $data['store_billing_type'] = (!empty($store_billing_type)) ? $store_billing_type->billing_type_constant : 'FINE_DINE';
            }

            if ($store_data->restaurant_waiter_role_id != '') {
                $waiter_role_slack = RoleModel::select('slack')
                    ->where('id', '=', $store_data->restaurant_waiter_role_id)
                    ->active()
                    ->first();

                $data['store_waiter_role_slack'] = (!empty($waiter_role_slack)) ? $waiter_role_slack->slack : '';
            }
        }

        $data['order_data'] = null;
        if (isset($slack)) {
            $order = OrderModel::where('slack', $slack)->first();

            $order_data = new OrderResource($order);
            
            $order_status = MasterStatusModel::select('value_constant')->where([
                    ['key', '=', 'ORDER_STATUS'],
                    ['value', '=', $order->status],
                ])
                ->first();
            //dd($order_data, json_encode($order_data, true), $order_data);

            $data['order_data']['slack'] = $order->slack;
            $additional_discount_type = ''; $additional_discount_value = 0;
            if($order_data->additional_discount_percentage > 0 || $order_data->additional_discount_amount > 0 ){
                if($order_data->additional_discount_percentage > 0){
                    $additional_discount_type = 'percentage';
                }else{
                    $additional_discount_type = 'amount';
                    $additional_discount_value = $order_data->additional_discount_amount;
                }
            }
            $data['order_data']['order'] = [
                'register_id' => $order->register_id,
                'order_number' => $order->order_number,
                'store_level_total_tax_percentage' => $order->store_level_total_tax_percentage,
                'store_level_total_discount_percentage' => $order->store_level_total_discount_percentage,
                'sub_total' => $order->sale_amount_subtotal_excluding_tax,
                'tax_total' => $order->total_tax_amount,
                'total' => $order->total_order_amount,
                'customer_number' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'payment_method' => $order->payment_method_slack,
                'current_status' => $order_status,
                'restaurant_mode' => $order->restaurant_mode,
                'order_type' => ($order_data->order_type_data != null) ? $order_data->order_type_data->order_type_constant : '',
                'table' => ($order_data->restaurant_table_data != null) ? $order_data->restaurant_table_data->slack : '',
                'waiter' => ($order_data->waiter_data != null) ? $order_data->waiter_data->slack : '',
                'waiter_name' => ($order_data->waiter_data != null) ? $order_data->waiter_data->fullname : '',
                'billing_type' => ($order_data->billing_type_data != null) ? $order_data->billing_type_data->billing_type_constant : '',
                'additional_discount_percentage' => ($order_data->additional_discount_percentage != null) ? $order_data->additional_discount_percentage : 0,
                'additional_discount_amount' => ($order_data->additional_discount_amount != null) ? $order_data->additional_discount_amount : 0,
                'discount_type' => ($order_data->discount_type != null) ? $order_data->discount_type : 0,
                'discount_code_id' => ($order_data->store_level_discount_code_id != null) ? $order_data->store_level_discount_code_id : 0,
                'value_date' => $order->value_date,
            ];
           
            $order_products = OrderProductModel::select('order_products.*','products.category_id','products.price_type','products.name_ar',
                            'products.is_taxable','products.is_tobacco_tax','products.tobacco_tax_percentage','products.product_thumb_image as image',
                            'category.parent')
                            ->where('order_id', $order->id)->product()->categoryJoin()->get();

            // new OrderProductResource($order_products);
            // dd($order_data, $order_products[0]);
            $cart = [];
            if (count($order_products) > 0) {
                foreach ($order_products as $order_product) {

                    if($order_product->parent == 0 || $order_product->parent == null){
                        $product_category_id = $order_product->category_id;
                        $product_subcategory_id = null;
                    }else{
                        $product_category_id = $order_product->parent;
                        $product_subcategory_id = $order_product->category_id;
                    }
                    $discount_type = ''; $discount_value = 0;
                    if(!empty($order_product->discount_code_id)){
                        if($order_product->discount_percentage > 0){
                            $discount_type = 'percentage';
                        }else{
                            $discount_type = 'amount';
                            $discount_value = $order_product->discount_amount;
                        }
                    }
                    $tax_amount = 0.0;
                    if(!empty($order_product->tobacco_tax_components) ){
                        $tobacco_tax_components = json_decode($order_product->tobacco_tax_components);
                        $tobacco_tax_percentage = $tobacco_tax_components[0]->tax_percentage;
                        $tobacco_tax_amount =  ($tobacco_tax_percentage / 100) * $order_product->sale_amount_excluding_tax;
                        $price_with_tobacco_tax  = $order_product->sale_amount_excluding_tax + $tobacco_tax_amount;
                
                        $tax_amount = ($order_product->tax_percentage / 100) * $price_with_tobacco_tax;
                        $tax_include_price  = $price_with_tobacco_tax + $tax_amount;
                    }elseif($order_product->tax_percentage > 0){
                        $tax_amount = ($order_product->tax_percentage / 100) * $order_product->sale_amount_excluding_tax;
                        $tax_include_price = $order_product->sale_amount_excluding_tax + $tax_amount;
                    }else{
                        $tax_include_price = $order_product->sale_amount_excluding_tax;
                    }

                    $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options','modifier_options.id',
                        'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers','modifiers.id','modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id',$order_product->id)
                        ->select('modifiers.label as modifier_label','modifier_options.label as modifier_option_label',
                            'modifier_options.price as modifier_tax_include_price','modifier_options.id as modifier_options_id',
                            'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();
                    $product_modifier_options_arr = []; $modifier_amount_include_tax = $modifier_price = 0;
                    $price_with_modifier = 0;
                    $price_with_modifier += $order_product->sale_amount_excluding_tax;
                    if(isset($order_product_modifier_options) && count($order_product_modifier_options) > 0){
                        foreach($order_product_modifier_options as $product_modifier_option){
                            $product_modifier_options_arr[] = [
                                "id" =>  $product_modifier_option->modifier_options_id,
                                "price" => $product_modifier_option->modifier_tax_include_price,
                                "label" => "$product_modifier_option->modifier_label: $product_modifier_option->modifier_option_label | ".
                                            "$product_modifier_option->modifier_tax_include_price".' '."$order_data->currency_code",
                            ];
                            $modifier_amount_include_tax += $product_modifier_option->modifier_tax_include_price;
                            $modifier_price += $product_modifier_option->modifier_option_price;
                        }
                        $price_with_modifier += $modifier_price;
                    }
                    $note_flag = $gift_flag = false;
                    if(!empty($order_product->note)){
                        $note_flag = true;
                    }
                    $price_gift = $order_product->sale_amount_excluding_tax;
                    $price_with_modifier_gift = $price_with_modifier;
                    $modifier_price_gift = $modifier_price;
                    $tax_include_price_gift = $tax_include_price;
                    if($order_product->is_gifted == 1){
                        $gift_flag = true;
                        $price_gift = 0; 
                        $modifier_price_gift = 0;
                        $price_with_modifier_gift = 0;
                        $tax_include_price_gift = 0;
                    }
                    //dd($order_product_modifier_options, $product_modifier_options_arr);
                    //dump($order_product);
                    $cart[$order_product->slack] = [                        
                       // "product_order_id" => $order_product->id,
                        "product_id"  => $order_product->product_id,
                        "product_category"  =>$product_category_id,
                        "product_sub_category"  => $product_subcategory_id,
                        "product_slack"  => $order_product->product_slack,
                        "cart_id"  => $order_product->slack,
                        "product_code"  => $order_product->product_code,
                        "name"  => $order_product->name,
                        "name_ar"  => $order_product->name_ar,
                        "price"  => $price_gift,
                        "price_actual"  => $order_product->sale_amount_excluding_tax,
                        "price_with_modifier"  => $price_with_modifier_gift,
                        "price_with_modifier_actual"  => $price_with_modifier,
                        "price_type"  => $order_product->price_type,
                        "tax_include_price"  => $tax_include_price_gift,
                        "tax_include_price_actual" => $tax_include_price,
                        "modifier_amount"  => $modifier_amount_include_tax,
                        "modifier_price"  => $modifier_price_gift,
                        "modifier_price_actual" => $modifier_price,
                        "is_taxable"  => $order_product->is_taxable,
                        "quantity"  => $order_product->quantity,
                        "is_tobacco_tax"  => $order_product->is_tobacco_tax,
                        "tobacco_tax_percentage"  => $order_product->tobacco_tax_percentage,
                        "tax_code_details"  => json_decode($order_product->tax_components),
                        "tobacco_tax_code_details"  => json_decode($order_product->tobacco_tax_components),
                        "tax_code_id"  => $order_product->tax_code_id,
                        "tax_code_label"  => '',
                        "tax_percentage"  => $order_product->tax_percentage,
                        "discount_percentage"  => $order_product->discount_percentage,
                        "discount_code_id"  => $order_product->discount_code_id,
                        "discount_type"  => $discount_type,
                        "discount_value"  => $discount_value,
                        "additional_discount_percentage" => $order_data->additional_discount_percentage,
                        "additional_discount_type"  => $additional_discount_type,
                        "additional_discount_value" => $additional_discount_value,
                        "additional_discount_code"  => '',
                        "additional_discount_percentage_calc"  => 0,
                        "total_price"  => $order_product->total_after_discount,
                        "total_price_actual"  => $order_product->total_after_discount,
                        "image"  => $order_product->image,
                        "product_border_color"  => null,
                        "is_low_on_ingredient"  => '',
                        "modifiers"  => $product_modifier_options_arr,
                        "note"  => $order_product->note,
                        "note_flag"  => $note_flag,
                        "gift_flag"  => $gift_flag,
                        "already_scanned"  => '',
                        "bonat_discount"  => $order_product->bonat_discount,
                        "bonat_price"  => $order_product->bonat_discount_price,
                        "bonat_coupon"  => $order_product->bonat_coupon,
                        // "item_total_amount"  => 0,
                        // "total_tax"  => 0,
                        // "total_discount"  => 0,
                        "tax_components"  => json_decode($order_product->tax_components),
                        "tobacco_tax_components"  => json_decode($order_product->tobacco_tax_components),
                        
                    ];
                }
            }
            // dd('$cart =', $cart);
            $data['order_data']['cart'] = $cart;
            // if(isset($data['store_discount_codes_cashier']) ){
            // }
            // dd($data['store_discount_codes_cashier']);
        }
        // dd($data['order_data']['cart']);
        $data['keyboard_shortcuts'] = KeyboardShortcutModel::select('keyboard_constant', 'keyboard_shortcut', 'keyboard_shortcut_label', 'description')
            ->active()
            ->sortAsc()
            ->get();

        $keyboard_shortcuts_formatted = $data['keyboard_shortcuts']->mapWithKeys(function ($item) {
            $keyboard_shortcut = null;
            $shortcuts = explode(',', $item['keyboard_shortcut']);
            if (count($shortcuts) > 1) {
                $keyboard_shortcut = $shortcuts;
            } else {
                $keyboard_shortcut =  $shortcuts;
            }
            return [$item['keyboard_constant'] => $keyboard_shortcut];
        });
        $data['keyboard_shortcuts_formatted'] = $keyboard_shortcuts_formatted;

        $store_id = UserModel::find(session('user_id'))->store_id;

        $data['store_name'] = StoreModel::find($store_id)->name;


        $data['restaurant_url'] = "";
        $hasTable = Schema::hasTable('qr_codes');

        if ($store_id && $hasTable && $data['store_name'] != "") {

            $qrcode = DB::table('qr_codes')->select('id', 'restaurant_id')->where('store_id', '=', $store_id)->count();

            $data['restaurant_url'] = env('WOSULIN_URL') . 'restaurant/' . $this->makeSubdomain($data['store_name']);
        }
        $counter = BillingCounterModel::where('id', $business_register_data->billing_counter_id)->first();
        $data['counter_name'] = $counter->counter_name;
        $data['counter_slack'] = $counter->slack;
        

        $main_category_data = CategoryModel::parentCategory()->categoryStore()->active()->get()->toArray();
        $new_category_list = [];
        $sort_orders = [];
        foreach ($main_category_data as $category) {
            array_push($sort_orders, $category['sort_order']);
        }
        $sort_orders = array_unique($sort_orders);
        sort($sort_orders);
        foreach ($sort_orders as $sort_order) {
            $main_categories = CategoryModel::parentCategory()->categoryStore()->where('sort_order', $sort_order)->active()->get();
            foreach($main_categories as $main_category)
            {
                $main_category = $main_category->toArray();
                array_push($new_category_list, $this->flatten($main_category));
            }
            // array_push($new_category_list, $this->flatten(CategoryModel::parentCategory()->categoryStore()->where('sort_order', $sort_order)->active()->get()->toArray()));
        }
        $data['main_categories'] = $new_category_list;


        $data['customer_data'] = CustomerModel::select('slack', 'name', 'phone', 'email')->active()->get();
        $data['send_to_kitchen_access'] = $this->send_to_kitchen_access;
        $data['can_gift_access'] = $this->can_gift_access;
        $data['can_hold_access'] = $this->can_hold_access;
        $products_data = $query = ProductModel::select(['products.*', 'tax_codes.total_tax_percentage', 'tax_codes.label as tax_code_label'])
            ->with('product_modifiers')->categoryJoin()
            ->supplierJoin()
            ->Active()
            ->categoryActive()
            ->productTypePos()
            ->taxcodeJoin()
            ->mainProduct();
        $total = $query->get()->count();

        $products_data = $products_data->take(30)->get();
        $total_count = count($products_data);

        $data['products_counter']['offset'] = 30; // default offset
        $data['products_counter']['total'] = $total;

        $products_data = ProductResource::collection($products_data);

        $data['products_data'] = null;
        if (!empty($products_data)) {

            foreach ($products_data as $product) {

                $dataset = [];
                $dataset = $product;
                $requested_product_quantity = 1;

                if ($this->__check_ingredient_stock($product->id, $requested_product_quantity)) {
                    $dataset['ingredient_low_stock'] = 1;
                } else {
                    $dataset['ingredient_low_stock'] = 0;
                }

                $data['products_data'][] = $dataset;
            }
        }
        // dd($data['products_data'][0]['tax_code']['tax_components'][0]['tax_name_id']);
        $data['merchant_id'] = session('merchant_id');

        $data['next_order_number'] = $this->getNextOrderNumber($store_data->id);

        $order_value_date = new APIOrder();
        $data['order_value_date'] = $order_value_date->get_order_value_date();

        $prices_data = Price::active()->get();
        
        $data['prices_data'] = PriceResource::collection($prices_data);
        $data['selected_price_id'] = ( is_null($store_data->price_id) ) ? 0 : $store_data->price_id;
        $data['is_price_enabled'] = $store_data->is_price_enabled;
        // dd($data['order_data']);
        return view('order.add_order', $data);
    }

    private function __check_ingredient_stock($product_id, $requested_product_quantity)
    {

        $product_ingredients = ProductIngredientModel::with('measurements')->where('product_id', $product_id)->get();

        $low_ingredient_stock = 0;

        if (!empty($product_ingredients)) {
            foreach ($product_ingredients as $product_ingredient) {

                $ingredient = ProductModel::where('id', $product_ingredient->ingredient_product_id)->active()->first();

                if ($product_ingredient->measurements != null) {

                    if ($ingredient->measurement_id == $product_ingredient->measurement_id || $product_ingredient->measurements->measurement_category_id == "") {

                        $quantity = $product_ingredient->quantity * $requested_product_quantity;
                        if (($ingredient->quantity < $quantity) && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                    } else {

                        // if not same then we need to get the conversion, means 1 == ?
                        $measurement_conversion = MeasurementConversionModel::where([
                            'from_measurement_id' => $product_ingredient->measurement_id,
                            'to_measurement_id' => $ingredient->measurement_id
                        ])->active()->first();

                        if(isset($measurement_conversion))
                        {
                           $quantity = ((float) $measurement_conversion->value * $product_ingredient->quantity) * $requested_product_quantity;
                        }
                        else
                        {
                            $quantity = ((float)$product_ingredient->quantity) * $requested_product_quantity;              
                        }
                        if ($ingredient->quantity < $quantity && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                    }
                }
            }
        }

        return $low_ingredient_stock;
    }

    public function makeSubdomain($name)
    {
        $cyr = [
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я',
        ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q',
        ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }


    public function add_pos($slack = null)
    {

        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        $data['action_key'] = ($slack == null) ? 'A_ADD_ORDER' : 'A_EDIT_ORDER';
        check_access(array($data['action_key']));

        $store_id = UserModel::find(session('user_id'))->store_id;
        $data['store_name'] = StoreModel::find($store_id)->name;
        $data['counter_name'] = BillingCounterModel::find($store_id)->counter_name;

        $data['main_categories'] = CategoryModel::parentCategory()->sortLabelAsc()->active()->get();
        dd($data['main_categories']);
        return view('invoice.add_pos', $data);
    }


    //This is the function that loads the detail page
    public function detail($slack)
    {

        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        $data['action_key'] = 'A_DETAIL_ORDER';
        check_access([$data['action_key']]);

        $order_data = $this->get_order_data($slack);
        $data['order_data'] = $order_data;
        $data['order_data']['order_level_tax_percentage'] = explode(".", $data['order_data']['order_level_tax_percentage'])[1] > 0 ? round($data['order_data']['order_level_tax_percentage'], 2) : explode(".", $data['order_data']['order_level_tax_percentage'])[0];
        $data['print_order_link'] = route('print_order', ['slack' => $slack]);
        $data['print_pos_receipt_link'] = route('order_receipt', ['slack' => $slack]);

        $data['payment_methods'] = PaymentMethodModel::select('id', 'slack', 'label')
        ->active()
        ->where('id',$order_data['payment_method_id'])
        ->orWhere('payment_constant','CASH')
        ->get()
        ->makeVisible(['id']);

        $return_order_exist = FALSE;
        $return_order_check = ReturnOrdersModel::select('*')->where('order_slack', $slack)->first();
        $damage_order_exist = FALSE;
        $damage_order_check = DamageReportModel::select('*')->where('order_slack', $slack)->first();
        $data['return_reason_data'] = "";
        $data['return_order_list'] = [];
        if (isset($return_order_check->id)) {
            $return_order_exist = TRUE;
            $data['return_reason_data'] = $return_order_check->reason;
            $return_orders = ReturnOrdersProductsModel::select('*')->where('order_slack', $slack)->get()->toArray();
            $product_codes = [];
            foreach ($return_orders as $order) {
                array_push($product_codes, $order['product_id']);
            }
            $product_codes  = array_unique($product_codes);
            foreach ($product_codes as $code) {
                $return_orders = ReturnOrdersProductsModel::select('*')->where('order_slack', $slack)->where('product_id', $code)->get()->toArray();
                $total_quantity = 0;
                foreach ($return_orders as $return) {
                    array_push($data['return_order_list'], $return);
                }
            }
        }

        $data['damage_reason_data'] = "";
        $data['damage_order_list'] = [];
        //print_r($damage_order_check);die;
        if (isset($damage_order_check->product)) {
            $damage_order_exist = TRUE;
            $data['damage_reason_data'] = $damage_order_check->reason;
        }
        $damage_orders = DamageReportModel::select('*')->where('order_slack', $slack)->get();
        $product_codes = [];
        foreach ($damage_orders as $order) {
            array_push($product_codes, $order->product_id);
        }
        $product_codes  = array_unique($product_codes);
        foreach ($product_codes as $code) {
            $orders = DamageReportModel::select('*')->where('order_slack', $slack)->where('product_id', $code)->get();
            $total_quantity = 0;
            foreach ($orders as $order) {
                array_push($data['damage_order_list'], $order);
            }
        }
        $data['damage_order_exist'] = $damage_order_exist;
        $data['return_order_exist'] = $return_order_exist;
        $data['delete_order_access'] = check_access(['A_DELETE_ORDER'], true);

        $data['store_tax_percentage'] = "";
        if (session('store_tax_code') != "") {
            $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id', session('store_tax_code'))->first()->total_tax_percentage;
        }

        $data['share_invoice_sms_access'] = check_access(['A_SHARE_INVOICE_SMS'], true);

        $restaurant_mode = StoreModel::find(session('store_id'))->pluck('restaurant_mode')->first();
        $data['restaurant_mode'] = ($restaurant_mode == 1) ? true : false;
        
        if(isset($data['order_data']['combos']) && count($data['order_data']['combos']) > 0){

            foreach($data['order_data']['combos'] as $combo){
                if(isset($combo)){
                    
                    $dataset = [];
                    $dataset['combo_id'] = $combo['combo_id'];
                    $dataset['combo_cart_id'] = $combo['combo_products'][0]['combo_cart_id'];
                    $dataset['combo_name'] = $combo['combo_name'];
                    $dataset['combo_total_price'] = format_decimal($combo['combo_total_price']);
                    $dataset['is_returned'] = ($combo['combo_products'][0]['return_quantity'] > 0) ? true : false;

                    $data['order_data']['products'][] = $dataset;
                    foreach($combo['combo_products'] as $combo_product){

                            $temp = $combo_product;
                            $temp['quantity'] = 1;
                            $data['order_data']['products'][] = $temp;
                    }
                }
                
            }
        }

        return view('order.order_detail', $data);
    }

    //This is the function that loads the print order page
    public function print_order(Request $request, $slack, $type = 'INLINE', $invoice_print_type = null)
    {

        // $data['menu_key'] = 'MM_ORDERS';
        // $data['sub_menu_key'] = 'SM_POS_ORDERS';
        // check_access([$data['sub_menu_key']]);

        $order_data = $this->get_order_data($slack);

        if (in_array($order_data['status']['value'], [1,2, 5, 6, 7]) != 1) {
            abort(404);
        }

        if ($invoice_print_type == null) {
            $invoice_print_type = $order_data['store']['invoice_type'];
        }

        if (session()->has('merchant_id')) {
            if (session('merchant_id') == '') {
                // default logo
                $print_logo_path = public_path('/images/logo.png');
            } else {
                // for logged in users
                // $print_logo_path = asset('storage/'.session('merchant_id').'/store/'.session('store_logo'));
                $print_logo_path =  public_path('storage/' . session('merchant_id') . '/store/' . $order_data['store']['store_logo']); //asset('storage/' . session('merchant_id') . '/store/' . $order_data['store']['store_logo']);
            }
        } else {
            // for logged out users (public url)
            $print_logo_path = public_path('storage/' . config('constants.config.merchant_id') . '/store/' . $order_data['store']['store_logo']);
        }

        switch ($invoice_print_type) {
            case 'A4':
                $view_file = 'order.invoice.a4_print';
                $css_file = 'css/order_a4_print_invoice.css';
                $format = 'A4';
                break;
            case 'SMALL':
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                break;
            case 'SMALL_LITE':
                $view_file = 'order.invoice.thermal_print_lite';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                break;
            default:
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                break;
        }

        $print_data = view($view_file, ['data' => json_encode($order_data), 'logo_path' => $print_logo_path])->render();
        // return view($view_file, ['data' => json_encode($order_data), 'logo_path' => $print_logo_path]);
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
        $filename = 'order_' . session('merchant_id') . "_" . $order_data['order_number'] . '.pdf';

        if ($type == 'INLINE') {

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
            $mpdf->Output($upload_dir . $filename, 'F');
            $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
        } else {

            Storage::disk('order')->delete(
                [
                    $filename
                ]
            );

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();

            $mpdf->Output($upload_dir . $filename, \Mpdf\Output\Destination::FILE);

            $download_link = $view_path . $filename;
            return $download_link;
        }
    }

    public function print_pos_receipt(Request $request, $slack)
    {

        $this->print_pos_order($request, $slack, 'INLINE', 'SMALL');
    }

    public function print_pos_order(Request $request, $slack, $type = 'INLINE', $invoice_print_type = null)
    {
        // $data['menu_key'] = 'MM_ORDERS';
        // $data['sub_menu_key'] = 'SM_POS_ORDERS';
        // check_access([$data['sub_menu_key']]);

        $order_data = $this->get_order_data($slack);
        if (in_array($order_data['status']['value'], [1,2,5,7]) != 1) {
            abort(404);
        }

        if ($invoice_print_type == null) {
            $invoice_print_type = $order_data['store']['invoice_type'];
        }


        switch ($invoice_print_type) {
            case 'A4':
                $view_file = 'order.invoice.a4_print';
                $css_file = 'css/order_a4_print_invoice.css';
                $format = 'A4';
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL':
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL_LITE':
                $view_file = 'order.invoice.thermal_print_lite';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
            default:
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
        }

        if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path))) {
            $print_logo_path = public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
            //$print_logo_path = asset('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
        } else {
            $print_logo_path = public_path('images/logo.png');
        }

        $print_data = view($view_file, ['data' => json_encode($order_data), 'logo_path' => $print_logo_path])->render();

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
        $filename = 'order_' . session('merchant_id') . "_" . $order_data['order_number'] . '.pdf';

        if ($type == 'INLINE') {

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
            $mpdf->Output($upload_dir . $filename, 'F');

            $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
            // $command = 'lpr '.$upload_dir.$filename;
            // $command = 'powershell Start-Process "'.$upload_dir.$filename.'" -Verb print';
            //$command = 'powershell Set-PrintConfiguration -PrinterName "LPT1PDF" -PaperSize A5 & powershell Start-Process "2.pdf" -Verb print';
            // $output = shell_exec( $command );
        } else {

            Storage::disk('order')->delete(
                [
                    $filename
                ]
            );

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();

            $mpdf->Output($upload_dir . $filename, \Mpdf\Output\Destination::FILE);
            //$command = 'lpr '.$upload_dir.$filename;
            // $command = 'powershell Start-Process "'.$upload_dir.$filename.'" -Verb print';
            // $command = 'powershell Set-PrintConfiguration -PrinterName "LPT1PDF" -PaperSize A5 & powershell Start-Process "2.pdf" -Verb print';
            // $output = shell_exec( $command );
            $download_link = $view_path . $filename;
            return $download_link;
        }
    }

    public function get_order_data($slack)
    {
        $data['order_data'] = null;
        $data['payment_details'] = null;
        $total_paid_amount = $total_balance_amount = 0;
        if (isset($slack)) {

            $order = OrderModel::withoutGlobalScopes()->select('orders.*')->where('orders.slack', $slack)->first();
            if (empty($order)) {
                abort(404);
            }

            $order_data = new OrderResource($order);
            // dd($order_data);


            $order_products_array = collect($order_data->products)->toArray();
            $total_qty_array = data_get($order_products_array, '*.quantity', 0);
            $total_quantity = array_sum($total_qty_array);

            $order_data = collect($order_data);
            $order_data->put('total_quantity', $total_quantity);
            $data = $order_data->all();
        }
        if ($data['status']['value_constant'] == 'PARTIAL_PAYMENT' || $data['status']['value'] == 7) {
            foreach ($data['transactions'] as $payment_details) {
                $total_paid_amount += $payment_details->amount;
            }
            $total_balance_amount = bcsub($data['total_order_amount'], $total_paid_amount, 2);
            $data['payment_details'] =  ['last_paid_amount' => $data['transactions'][0]->amount,
                                        'total_paid_amount' => $total_paid_amount, 
                                        'total_balance_amount' => $total_balance_amount,
                                        ];
        }

        $products = [];
        $combo_cart = [];
        
        if (!empty($data['products'])) {
            foreach ($data['products'] as $order_product) {
                
                if($order_product->combo_id == null || $order_product->combo_id == 0){
                    
                    $temp = [];
                    $temp = collect($order_product);
                    $temp['is_gifted']=$order_product->is_gifted;
                    $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id', $order_product->id)
                        ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();
                    $temp->put('modifier_options', $order_product_modifier_options);
                    
                    $products[] = $temp;
                
                }else{
                    $combo_cart[$order_product['combo_cart_id']][] = $order_product; 
                }

            }
        }

        $combos = [];

        if(isset($combo_cart) && count($combo_cart) > 0 ){
            
            foreach($combo_cart as $key => $combo_cart_products){
                
                $combo = Combo::find($combo_cart_products[0]['combo_id']);
                
                $dataset = [];
                $dataset['combo_id'] = $combo->id;
                $dataset['combo_name'] = $combo->name;
                $dataset['combo_quantity'] = 1;
                $dataset['combo_total_price'] = 0;
                
                foreach($combo_cart_products as $product){
                    
                    $temp = [];
                    $temp = collect($product);
                    $temp['is_gifted']=$product['is_gifted'];
                    $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id', $product['id'])
                        ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();
                    $temp->put('modifier_options', $order_product_modifier_options);

                    $total_modifier_option_price = 0;
                    if(isset($order_product_modifier_options)){
                        foreach($order_product_modifier_options as $modifier_option){
                            $total_modifier_option_price += $modifier_option->modifier_option_price;
                        }
                    }
                    $dataset['combo_products'][] = $temp;
                    $dataset['combo_total_price'] += (float) $product['sale_amount_excluding_tax'] + $total_modifier_option_price;
                    
                }
                $combos[] = $dataset;
                
            }
            

        }
        
        $data['combos'] = $combos;
        $data['products'] = $products;

        return $data;
    }

    public function payment_gateway($type, $slack)
    {
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access([$data['sub_menu_key']]);

        $order = OrderModel::select('orders.*')->where('orders.slack', $slack)->first();
        $order_status_master = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'CLOSED')->first();

        $data['new_order_link'] = route('add_order');

        if ($order_status_master->value == $order->status) {
            return redirect($data['new_order_link']);
        }

        $zero_decimal_currencies = ["BIF", "CLP", "DJF", "GNF", "JPY", "KMF", "KRW", "MGA", "PYG", "RWF", "UGX", "VND", "VUV", "XAF", "XOF", "XPF"];

        $data['order_slack'] = $slack;
        $data['order_detail_link'] = route('order_detail', ['slack' => $slack]);
        $data['order_print_link'] = route('order_summary', ['slack' => $slack]);
        $data['order_number'] = $order->order_number;
        $data['order_amount'] = $order->total_order_amount;
        $data['order_currency'] = $order->currency_code;
        $data['order_currency_round_note'] = (in_array(strtoupper($order->currency_code), $zero_decimal_currencies)) ? "Amount will be rounded in case of these currencies :" . implode(", ", $zero_decimal_currencies) : '';

        switch (strtolower($type)) {
            case "stripe":
                if (in_array(strtoupper($order->currency_code), $zero_decimal_currencies)) {
                    $data['order_amount'] = $order->total_order_amount_rounded;
                } else {
                    $data['order_amount'] = ($order->total_order_amount);
                }
                $view_file = 'payment.stripe';
                break;
            case "paypal":

                $payment_method = PaymentMethodModel::where('payment_constant', '=', 'PAYPAL')->first();
                $client_secret = $payment_method->key_1;
                $client_id = $payment_method->key_2;
                $data['client_id'] = $client_id;

                $view_file = 'payment.paypal';
                break;
        }

        return view($view_file, $data);
    }

    public function get_order_data_public($slack)
    {
        $data['order_data'] = null;

        if (isset($slack)) {

            $order = OrderModel::withoutGlobalScopes()->select('orders.*')->where('orders.slack', $slack)
                ->closed()->first();

            if (empty($order)) {
                abort(404);
            }

            $order_data = new OrderResource($order);

            $order_products_array = collect($order_data->products)->toArray();
            $total_qty_array = data_get($order_products_array, '*.quantity', 0);
            $total_quantity = array_sum($total_qty_array);

            $order_data = collect($order_data);
            $order_data->put('total_quantity', $total_quantity);
            $data = $order_data->all();
        }
        return $data;
    }

    public function detail_public_view($slack)
    {

        $order_data = $this->get_order_data_public($slack);

        $data['order_data'] = $order_data;

        $data['company_logo'] = config('app.company_logo');

        return view('order.order_detail_public', $data);
    }

    public function order_summary(Request $request, $slack)
    {

        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access([$data['sub_menu_key']]);

        $order_data = $this->get_order_data($slack);

        $data['order_data'] = $order_data;

        $data['pdf_print'] = $this->print_order($request, $slack, 'FILE');

        $data['new_order_link'] = route('add_order');

        $data['order_detail_link'] = route('order_detail', ['slack' => $slack]);

        $data['print_order_link'] = route('print_order', ['slack' => $slack]);

        $data['order_detail_access'] = check_access(['A_DETAIL_ORDER'], true);

        $data['new_order_access'] = check_access(['A_ADD_ORDER'], true);

        $data['edit_order_link'] = route('edit_order', ['slack' => $slack]);

        $data['edit_order_access'] = check_access(['A_EDIT_ORDER'], true);

        return view('order.order_summary', $data);
    }

    public function order_receipt(Request $request, $slack)
    {   

        $order_created_by = OrderModel::withoutGlobalScopes()->where('slack', $slack)->pluck('created_by')->first();

        if (!empty($order_created_by)) {
            $language_array = $this->get_selected_languages($order_created_by);
            if (!empty($language_array) && count($language_array) > 0) {
                \App::setLocale($language_array['language_code']);
            } else {
                \App::setLocale('en');
            }
        } else {
            \App::setLocale('en');
        }

        $this->print_order($request, $slack, 'INLINE', 'SMALL');

        // $data['menu_key'] = 'MM_ORDERS';
        // $data['sub_menu_key'] = 'SM_POS_ORDERS';
        // check_access([$data['sub_menu_key']]);

        // $order_data = $this->get_order_data($slack);

        // $data['order_data'] = $order_data;

        // $data['pdf_print'] = ;

        // $data['new_order_link'] = route('add_order');

        // $data['order_detail_link'] = route('order_detail', ['slack' => $slack]);

        // $data['print_order_link'] = route('print_order', ['slack' => $slack]);

        // $data['order_detail_access'] = check_access(['A_DETAIL_ORDER'] ,true);

        // $data['new_order_access'] = check_access(['A_ADD_ORDER'] ,true);

        // $data['edit_order_link'] = route('edit_order', ['slack' => $slack]);

        // $data['edit_order_access'] = check_access(['A_EDIT_ORDER'] ,true);

        // return view('order.order_receipt', $data);
    }


    public function subcategories(Request $request)
    {

        $subcategories = CategoryModel::where('main_category_id', $request->catetgory_id)->get();
        return response()->json($subcategories);
    }

    public function products(Request $request)
    {

        $products = ProductModel::where('category_id', $request->sub_category_id)->get();
        return response()->json($products);
    }

    public function return_order_receipt(Request $request, $slack)
    {
        $this->print_return_order($request, $slack, 'INLINE', 'SMALL');
    }

    public function damage_order_receipt(Request $request, $slack)
    {

        $this->print_return_order($request, $slack, 'INLINE', 'SMALL', true);
    }

    public function print_return_order(Request $request, $slack, $type = 'INLINE', $invoice_print_type = null, $damage = false)
    {


        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access([$data['sub_menu_key']]);
        $order_data = $this->get_return_order_data($slack, $damage);

        if ($damage == false && in_array($order_data['status']['value'], [1, 5, 6]) != 1) {
            abort(404);
        }

        if ($damage == false && $invoice_print_type == null) {
            $invoice_print_type = $order_data['store']['invoice_type'];
        }


        switch ($invoice_print_type) {
            case 'A4':
                $view_file = 'order.return_invoice.return_a4_print';
                $css_file = 'css/order_a4_print_invoice.css';
                $format = 'A4';
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL':
                $view_file = 'order.return_invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL_LITE':
                $view_file = 'order.return_invoice.thermal_print_lite';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
            default:
                $view_file = 'order.return_invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
        }

        if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path))) {
            $print_logo_path = public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
            //$print_logo_path = asset('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
        } else {
            $print_logo_path = public_path('images/logo.png');
        }

        $print_data = view($view_file, ['data' => json_encode($order_data), 'logo_path' => $print_logo_path, 'damage' => $damage])->render();
        // echo $print_data;
        // exit;
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
        if ($damage == false) {
            $filename = 'order_' . session('merchant_id') . "_" . $order_data['id'] . '.pdf';
        } else {
            $filename = 'damage_order_' . session('merchant_id') . "_" . $order_data['id'] . '.pdf';
        }

        if ($type == 'INLINE') {

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
            $mpdf->Output($upload_dir . $filename, 'F');
            $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
        } else {

            Storage::disk('order')->delete(
                [
                    $filename
                ]
            );

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();

            $mpdf->Output($upload_dir . $filename, \Mpdf\Output\Destination::FILE);


            $download_link = $view_path . $filename;
            return $download_link;
        }
    }

    public function get_return_order_data($slack, $damage = false)
    {
        $data['order_data'] = null;

        if (isset($slack)) {


            if ($damage == false) {
                $order = ReturnOrdersModel::select('order_return.*')->where('order_return.slack', $slack)
                    ->orderBy('order_return.id', 'DESC')->first();
            } else {
                $order = ReturnOrdersModel::select('order_return.*')->where('order_return.order_slack', $slack)
                    ->orderBy('order_return.id', 'DESC')->first();
            }


            if (empty($order)) {
                abort(404);
            }


            $order_data = new ReturnOrderResource($order);

            $order_products_array = collect($order_data->products)->toArray();
            $total_qty_array = data_get($order_products_array, '*.quantity', 0);
            $total_quantity = array_sum($total_qty_array);

            $order_data = collect($order_data);
            $order_data->put('total_quantity', $total_quantity);
            $data = $order_data->all();

            $products = [];
            $combo_cart = [];
            
            if (!empty($data['products'])) {
                foreach ($data['products'] as $order_product) {
                    
                    if($order_product->combo_id == null || $order_product->combo_id == 0){
                        
                        $temp = [];
                        $temp = collect($order_product);
                        $temp['is_gifted']=$order_product->is_gifted;
                        $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                            ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                            ->where('order_product_modifier_options.order_product_id', $order_product->id)
                            ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                            ->get();
                        $temp->put('modifier_options', $order_product_modifier_options);
                        
                        $products[] = $temp;
                    
                    }else{
                        $combo_cart[$order_product['combo_cart_id']][] = $order_product; 
                    }

                }
            }

            $combos = [];

            if(isset($combo_cart) && count($combo_cart) > 0 ){
                
                foreach($combo_cart as $key => $combo_cart_products){
                    
                    $combo = Combo::find($combo_cart_products[0]['combo_id']);
                    
                    $dataset = [];
                    $dataset['combo_id'] = $combo->id;
                    $dataset['combo_name'] = $combo->name;
                    $dataset['combo_quantity'] = 1;
                    $dataset['combo_total_price'] = 0;
                    
                    foreach($combo_cart_products as $product){
                        
                        $temp = [];
                        $temp = collect($product);
                        $temp['is_gifted']=$product['is_gifted'];
                        $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                            ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                            ->where('order_product_modifier_options.order_product_id', $product['id'])
                            ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                            ->get();
                        $temp->put('modifier_options', $order_product_modifier_options);

                        $total_modifier_option_price = 0;
                        if(isset($order_product_modifier_options)){
                            foreach($order_product_modifier_options as $modifier_option){
                                $total_modifier_option_price += $modifier_option->modifier_option_price;
                            }
                        }
                        $dataset['combo_products'][] = $temp;
                        $dataset['combo_total_price'] += (float) $product['sale_amount_excluding_tax'] + $total_modifier_option_price;
                        
                    }
                    $combos[] = $dataset;
                    
                }

            }
            
            $data['combos'] = $combos;
            $data['products'] = $products;

            return $data;
        }
    }

    public function get_selected_languages($user_id)
    {

        $user_language_data = UserModel::WithoutGlobalScopes()->select('language_id')->where('id', $user_id)->first();

        if (empty($user_language_data->language_id)) {

            $language_array = [
                'language_code' => 'en',
                'language_label' => "English - en"
            ];
        } else {
            $language = LanguageModel::WithoutGlobalScopes()->select("language_code", "language")->where('id', $user_language_data->language_id)->active()->first();

            $language_array = [
                'language_code' => $language->language_code,
                'language_label' => $language->language . " - " . $language->language_code
            ];
        }

        return $language_array;
    }

    // public function testprint(){

    //     $connector = new FilePrintConnector("php://stdout");
    //     $printer = new Printer($connector);
    //     $printer -> text("Hello World!\n");
    //     $printer -> cut();
    //     $printer -> close();

    // }

}
