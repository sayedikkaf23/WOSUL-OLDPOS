<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Menu as MenuModel;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User as UserModel;
use App\Models\UserMenu as UserMenuModel;
use App\Models\Role as RoleModel;
use App\Models\SettingApp;
use App\Models\Store as StoreModel;
use App\Models\UserStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\Taxcode as TaxcodeModel;
class UserMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $menu_array = array();
        $logged_in_user = $request->logged_user_id;
        $restaurant_mode = $request->logged_user_store_restaurant_mode;

        if ($request->logged_user_role_id == 1) {

            $menus = MenuModel::select('*')
                // ->active()
                ->orderByRaw('FIELD(type , "MAIN_MENU", "SUB_MENU") ASC')
                ->orderBy('sort_order', 'ASC')
                ->when($restaurant_mode == 0, function ($menus) {
                    $menus->where('menus.is_restaurant_menu', 0);
                })
                ->when($logged_in_user != 1, function ($menus) {
                    $menus->active();
                })
                ->get();
        } else {

            $role_created_by = RoleModel::where([
                'id' => $request->logged_user_role_id,
                'created_by' => 1
            ])->first();

            if (!empty($role_created_by)) {
                // Role Created by Admin 
            }

            $menus = DB::table('user_menus')
                ->select('menus.*')
                ->join('menus', 'menus.id', '=', 'user_menus.menu_id')
                ->where('user_menus.user_id', $logged_in_user)
                ->orderByRaw('FIELD(type , "MAIN_MENU", "SUB_MENU") ASC')
                ->orderBy('sort_order', 'ASC')
                ->when($restaurant_mode == 0, function ($menus) {
                    $menus->where('menus.is_restaurant_menu', 0);
                })
                ->get();
        }

        foreach ($menus as $menu) {
            if ($menu->type == "MAIN_MENU") {
                $menu_array[$menu->id] = [
                    "menu_key" => $menu->menu_key,
                    "label" => $menu->label,
                    "route" => $menu->route,
                    "icon"  => $menu->icon,
                    "image"  => $menu->image,
                ];
            } else if ($menu->type == "SUB_MENU") {
                if (isset($menu_array[$menu->parent])) {
                    unset($menu_array[$menu->parent]["route"]);
                    $menu_array[$menu->parent]['sub_menu'][] = [
                        "sub_menu_id" => $menu->id,
                        "menu_key" => $menu->menu_key,
                        "label" => $menu->label,
                        "route" => $menu->route
                    ];
                }
            }
        }

        View::share('menus', $menu_array);

        $quick_links = [];
        if (check_access(array('A_ADD_NOTIFICATION'), true)) {
            $quick_links[] = [
                'label' => 'New Notification',
                'route' => route('add_notification')
            ];
        }
        if (check_access(array('SM_RESTAURANT_KITCHEN'), true) && $request->logged_user_store_restaurant_mode == 1) {
            $quick_links[] = [
                'label' => 'Kitchen View',
                'route' => route('kitchen')
            ];
        }
        if (check_access(array('SM_RESTAURANT_WAITER'), true) && $request->logged_user_store_restaurant_mode == 1) {
            $quick_links[] = [
                'label' => 'Waiter View',
                'route' => route('waiter')
            ];
        }
        if (check_access(array('A_ADD_CUSTOMER'), true)) {
            $quick_links[] = [
                'label' => 'New Customer',
                'route' => route('add_customer')
            ];
        }
        if (check_access(array('A_ADD_ORDER'), true)) {
            $quick_links[] = [
                'label' => 'New Order',
                'route' => route('add_order')
            ];
        }
        if (check_access(array('A_ADD_TRANSACTION'), true)) {
            $quick_links[] = [
                'label' => 'New Transaction',
                'route' => route('add_transaction')
            ];
        }
        if (check_access(array('A_ADD_INVOICE'), true)) {
            $quick_links[] = [
                'label' => 'New Invoice',
                'route' => route('add_invoice')
            ];
        }
        if (check_access(array('A_ADD_PURCHASE_ORDER'), true)) {
            $quick_links[] = [
                'label' => 'New Purchase Order',
                'route' => route('add_purchase_order')
            ];
        }
        if (check_access(array('A_ADD_QUOTATION'), true)) {
            $quick_links[] = [
                'label' => 'New Quotation',
                'route' => route('add_quotation')
            ];
        }

        // Add Invoice Shortcut Status
        if (check_access(array('A_ADD_INVOICE'), true)) {
            $invoice_menu_status = ['active' => true, 'route' => route('add_invoice')];
        } else {
            $invoice_menu_status = ['active' => false];
        }

        // Add POS Shortcut Status
        if (check_access(array('A_ADD_ORDER'), true)) {
            $pos_menu_status = ['active' => true, 'route' => route('add_order')];
        } else {
            $pos_menu_status = ['active' => false];
        }

        View::share('pos_menu_status', $pos_menu_status);
        View::share('invoice_menu_status', $invoice_menu_status);
        View::share('logout_route', route('logout'));
        View::share('send_to_kitchen_access', check_access(array('A_SEND_TO_KITCHEN'), true));

        View::share('session_currency_code', $request->session()->get('currency_code'));
        View::share('session_currency_name', $request->session()->get('currency_name'));
        // Added Later : ends

        $user_store = UserModel::find(session('user_id'));

        $is_master = false;
        $is_price_enabled = false;
        if (isset($user_store) && $user_store->store_id != null) {
            $store_detail = StoreModel::find($user_store->store_id);
            View::share('store_name', $store_detail->name);
            $store_logo = ($store_detail->store_logo == null) ? url('/images/logo.png') : url('/') . '/storage/' . session('merchant_id') . '/store/' . $store_detail->store_logo;
            View::share('store_logo', $store_logo);
            $is_master = $user_store->is_master;
            $is_price_enabled = $store_detail->is_price_enabled;
            $selected_price_id = $store_detail->price_id;
        }

        View::share('quick_links', $quick_links);

        if ($request->logged_user_id == 1) {
            $all_stores = StoreModel::select('id', 'name as text','platform_type')->oldest()->active()->get();
        } else {
            $all_stores = UserStore::select('stores.id', 'stores.name as text','platform_type')->where('user_id', $request->logged_user_id)->storeData()->get();
        }

        View::share('all_stores', $all_stores);
        View::share('is_master', $is_master);
        
        $price_list = null;
        if($is_price_enabled){
            $default_product_count = Product::active()->get()->count();
            $prices = Price::join('product_prices','product_prices.price_id','prices.id')
            ->join('products','products.id','product_prices.product_id')
            ->where('products.store_id',$user_store->store_id)
            ->groupBy('prices.name')
            ->select('prices.id','prices.name',DB::Raw('COUNT(*) as product_count'))
            ->get()->toArray();
            $price_list = [
                'selected_price_id' => $selected_price_id,
                'prices' => $prices
            ];
            array_unshift($price_list['prices'],[
                'id' => 0,
                'name' => 'Default',
                'product_count' => $default_product_count,
            ]);
        }
        View::share('price_list', $price_list);
        
        $is_store_tax_exists = SettingApp::select('tax_setting_updated_at')->first();
        
        if(isset($is_store_tax_exists) && $is_store_tax_exists->tax_setting_updated_at != null){
            $is_store_tax_exists = true;
        }else{
            $is_store_tax_exists = false;
        }
        
        View::share('is_store_tax_exists', $is_store_tax_exists );
        View::share('default_store_taxes', config('constants.default_taxes'));

        return $next($request);
    }
}
