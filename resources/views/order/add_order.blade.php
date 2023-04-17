@extends('layouts.custom_layout')

@push('styles')
    <style>
        /* Let's get this party started */
        ::-webkit-scrollbar {
            width: 9px;
            height: 9px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.3);
            -webkit-border-radius: 5px;
            border-radius: 5px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            -webkit-border-radius: 5px;
            border-radius: 5px;
            background: #66af94;
            -webkit-box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.5);
        }

        ::-webkit-scrollbar-thumb:window-inactive {
            background: #66af94;
        }

        .content {
            padding: 5px !important;
        }

        .category-tag {
            background: white;
            padding: 5px 10px;
            border-radius: 5px;
            /*border: 2px solid #e9e9e9;*/
        }

        .category-tag-wrapper li a {
            transition: 0.5s ease all;
        }

        .pos-search-bar {
            border: none !important;
            border-radius: 5px;
        }

        .category-tag-wrapper li a.active {
            /*background: white;*/
            padding: 5px 10px;
            border-radius: 5px;
            /*border: 2px solid #186ca5;*/
            /*color: #186ca5;*/
            transition: 0.5s ease all;
            background: #186ca5;
            color: white;
            border: 2px solid white;
        }

        .product-card {
            cursor: pointer;
        }

        .product-card:hover {
            border: 2px solid #186ca5;
        }

        .product-card .card-body img {
            height: 200px;
        }

        .select-product-img {
            padding-top: 20px;
        }

        .select-product-img img {
            height: 80px;
            border: 1px solid #e9e9e9;
        }

        .select-product-piece-label,
        .select-product-piece-count {
            border: 1px solid #e9e9e9;
            padding-left: 6px;
            height: 28px;
        }

        .select-product-piece-label label {
            vertical-align: middle;
            display: inline;
        }

        .select-product-piece-label label,
        .select-product-piece-count label {
            margin-bottom: 0px !important;
            font-weight: normal !important;
        }

        .select-product-piece-count .count-add-minus {
            margin: 0px;
            font-size: 20px;
        }

        .remove-btn {
            background: #186ca5;
            height: 27px;
            padding-top: 3px;

        }

        .pay-icon {
            /*width: 100%;*/
            float: none !important;
        }

        .pay-icon .active {
            background: #186ca5;
        }

        .product-grid:hover {
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px #bdbdbd;
        }

        .payment-option-selected {
            background: #d5eeff;
        }

        .card-box {
            border-radius: 0;
            padding: 10px 10px 15px 10px;
            border-radius: 5px;
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .form-control {
            border-radius: 5px !important;
        }

        .cart-item-title {
            color: #186ca5;
        }

        .btn-primary-updated {
            background: #186ca5 !important;
            color: white;
        }

        .custom-width-card {
            /*width: 410px;*/
            margin-left: 15px;
            margin-right: 30px;
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #a9a9a9;
            margin-top: 5px;

        }

        .pos-product-block {
            margin-right: -8px;
            margin-left: 0px;
        }

        .small {
            font-size: 7px;
        }

        table {
            font-size: 8px;
        }

        td {
            padding-bottom: 1rem;
        }

        .pt-1rem {
            padding-top: 1rem;
        }

        .pb-1rem {
            padding-bottom: 1rem;
        }

        .mb-1rem {
            margin-bottom: 1rem;
        }

        .pb-0 {
            padding-bottom: 0;
        }

        .display-block {
            display: inline-block;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .border-bottom-dashed {
            border-bottom: 0.3px dashed #000;
        }

        .w-45 {
            width: 45% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .w-100 {
            width: 100% !important;
        }

        .point-btn-grey {
            background: #186ca5;
            text-transform: uppercase;
            font-size: 13px;
            color: white;
            border-radius: 5px;
        }

        .product-code,
        .product-code span {
            font-size: 13px;
        }

        form.purchases-form {
            padding: 20px 15px;
        }

        .custom-font {
            font-size: 10px !important;
            vertical-align: middle;
        }

        .select2-container {
            width: 100% !important;
            z-index: 99999 !important;
        }

        /**** Responsive Code Starts ****/

        @media screen and (max-width: 768px) {
            .custom-width-card {
                width: auto;
            }

        }

        @media screen and (max-width: 480px) {}

        @media screen and (min-width: 769px) and (max-width: 1300px) {
            .custom-width-card {
                width: auto;
            }
        }

        .chip {
            display: inline-block;
            padding: 0 10px 0px 10px;
            height: 32px;
            font-size: 10px;
            line-height: 32px;
            border-radius: 19px;
            background-color: #f1f1f1;
            margin-left: 5px;
        }

        .closebtn {
            font-size: 25px;
            position: absolute;
            padding-left: 2px;
            color: #b0b8cc;
            cursor: pointer;
        }

        .closebtn:hover {
            color: #474e5d;
        }

        /* Bootstrap Switch */

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 23px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(14px);
            -ms-transform: translateX(14px);
            transform: translateX(14px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quickpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/labels.css') }}">
@endpush

@section('content')
    <addordercomponent :store_tax_code_id="{{ json_encode($store_tax_code_id) }}"
        :store_currency="{{ json_encode($store_currency) }}" :store_tax_label="{{ json_encode($store_tax_label) }}"
        :store_tax_components="{{ json_encode($store_tax_components) }}"
        :store_tax_percentage="{{ json_encode($store_tax_percentage) }}"
        :store_discount_percentage="{{ json_encode($store_discount_percentage) }}"
        :payment_methods="{{ json_encode($payment_methods) }}" :categories="{{ json_encode($categories) }}"
        :default_business_account="{{ json_encode($default_business_account) }}"
        :business_accounts="{{ json_encode($business_accounts) }}" :order_data="{{ json_encode($order_data) }}"
        :store_restaurant_mode="{{ json_encode($store_restaurant_mode) }}"
        :restaurant_order_types="{{ json_encode($restaurant_order_types) }}"
        :vacant_tables="{{ json_encode($vacant_tables) }}" :new_order_link="{{ json_encode($new_order_link) }}"
        :billing_types="{{ json_encode($billing_types) }}"
        :store_billing_type="{{ json_encode($store_billing_type) }}"
        :store_waiter_role_slack="{{ json_encode($store_waiter_role_slack) }}"
        :keyboard_shortcuts="{{ json_encode($keyboard_shortcuts) }}"
        :keyboard_shortcuts_formatted="{{ json_encode($keyboard_shortcuts_formatted) }}"
        :enable_customer_detail_popup="{{ json_encode($enable_customer_detail_popup) }}"
        :main_categories="{{ json_encode($main_categories) }}" :store_name=" {{ json_encode($store_name) }}"
        :counter_name=" {{ json_encode($counter_name) }}" :counter_slack=" {{ json_encode($counter_slack) }}"
        :customer_data=" {{ json_encode($customer_data) }}"
        :send_to_kitchen_access=" {{ json_encode($send_to_kitchen_access) }}"
        :can_gift_access=" {{ json_encode($can_gift_access) }}"
        :can_hold_access=" {{ json_encode($can_hold_access) }}"
        :session_currency_code=" {{ json_encode($session_currency_code) }}"
        :store_discount_codes="{{ json_encode($store_discount_codes) }}"
        :restaurant_url=" {{ json_encode($restaurant_url) }}" :merchant_id=" {{ json_encode($merchant_id) }}"
        :products_data=" {{ json_encode($products_data) }}"
        :products_counter=" {{ json_encode($products_counter) }}"
        :store_discount_codes_cashier="{{ json_encode($store_discount_codes_cashier) }}"
        :next_order_number=" {{ json_encode($next_order_number) }}"
        :order_value_date=" {{ json_encode($order_value_date) }}"
        :prices_data=" {{ json_encode($prices_data) }}"
        :selected_price_id=" {{ json_encode($selected_price_id) }}"
        :is_price_enabled=" {{ json_encode($is_price_enabled) }}"
        :locale="{{ json_encode(app()->getLocale()) }}"
        >
    </addordercomponent>
@endsection
