@php

$navbar_logo = config('app.navbar_logo');
$user_slack = session('slack');
$user_id = session('user_id');
$fullname = session('full_name') ? session('full_name') : 'Unknown';
$menus = array_chunk($menus, 4);
$pos_menu_status = $pos_menu_status;
$invoice_menu_status = $invoice_menu_status;
$logout_route = $logout_route;
$store_name = @$store_name;
$store_logo = @$store_logo;
$lang = App::getLocale();
$merchant_id = session('merchant_id') ? (int) session('merchant_id') : 0;
$subdomain_name = Config::get('constants.subdomain_name') ? Config::get('constants.subdomain_name') : '';
$all_stores = $all_stores;
$store_id = request()->logged_user_store_id;
$store_slack = request()->logged_user_store_slack;
$price_list_data = $price_list;

$is_master = $is_master;

$languages = isset($logged_user_data['languages']) ? $logged_user_data['languages'] : [];
$selected_language = isset($logged_user_data['selected_language']) ? $logged_user_data['selected_language'] : 'en - English';

@endphp
{{-- <topnavcomponent :user_slack="{{ json_encode($user_slack) }}" :logged_user_data="{{ json_encode($logged_user_data) }}" :navbar_logo="{{ json_encode($navbar_logo) }}" :quick_links="{{ json_encode($quick_links) }}" :order_page="{{ json_encode(isset($order)?true:false)}}"></topnavcomponent> --}}
<header id="page-topbar">
    <headercomponent :menus="{{ json_encode($menus) }}" :user_slack="{{ json_encode($user_slack) }}"
        :navbar_logo="{{ json_encode($navbar_logo) }}" :pos_menu_status="{{ json_encode($pos_menu_status) }}"
        fullname="{{ $fullname }}" :invoice_menu_status="{{ json_encode($invoice_menu_status) }}"
        :logout_route="{{ json_encode($logout_route) }}" :store_name="{{ json_encode($store_name) }}"
        :store_logo="{{ json_encode($store_logo) }}" :user_id="{{ json_encode($user_id) }}"
        :lang="{{ json_encode($lang) }}" :merchant_id="{{ json_encode($merchant_id) }}"
        :subdomain_name="{{ json_encode($subdomain_name) }}" :store_id="{{ json_encode($store_id) }}"
        :store_slack="{{ json_encode($store_slack) }}" :all_stores="{{ json_encode($all_stores) }}"
        :is_master="{{ json_encode($is_master) }}" :languages="{{ json_encode($languages) }}"
        
        :selected_language="{{ json_encode($selected_language) }}"
        :is_store_tax_exists="{{ json_encode($is_store_tax_exists) }}"
        :default_store_taxes="{{ json_encode($default_store_taxes) }}"
        :price_list_data="{{ json_encode($price_list_data) }}"
        >
    </headercomponent>
</header>
