@extends('layouts.layout')
@php
$lang = App::getLocale();
@endphp
@section("content")
<orderdetailcomponent :lang="{{json_encode($lang)}}" 
    :order_data="{{ json_encode($order_data) }}"
    :payment_methods="{{ json_encode($payment_methods) }}"
    :delete_order_access="{{ json_encode($delete_order_access) }}"
    :share_invoice_sms_access="{{ json_encode($share_invoice_sms_access) }}"
    :print_order_link="{{ json_encode($print_order_link) }}"
    :print_pos_receipt_link="{{ json_encode($print_pos_receipt_link) }}"
    :return_order_exist="{{ json_encode($return_order_exist) }}"
    :damage_order_exist="{{ json_encode($damage_order_exist) }}"
    :store_tax_percentage="{{ json_encode($store_tax_percentage) }}"
    :restaurant_mode="{{ json_encode($restaurant_mode) }}" :return_reason_data="{{ json_encode($return_reason_data) }}"
    :damage_reason_data="{{ json_encode($damage_reason_data) }}"
    :damage_order_exist="{{ json_encode($damage_order_exist) }}"
    :damage_order_list="{{json_encode($damage_order_list)}}" :return_order_list="{{json_encode($return_order_list)}}">
</orderdetailcomponents>
@endsection