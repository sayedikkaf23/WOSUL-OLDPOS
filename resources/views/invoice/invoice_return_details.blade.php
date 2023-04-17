@extends('layouts.layout')

@section("content")
<invoicereturndetailcomponent 
    :invoice_data="{{ json_encode($invoice_data) }}" 
    :store_detail="{{ json_encode($store_detail) }}" 
    :payment_methods="{{ json_encode($payment_methods) }}"
    :supplier="{{ json_encode($supplier) }}" 
    :return_invoice_exist="{{ json_encode($return_invoice_exist) }}" 
    :store_tax_percentage="{{ json_encode($store_tax_percentage) }}"
    :restaurant_mode="{{ json_encode($restaurant_mode) }}"
    :return_reason_data="{{ json_encode($return_reason_data) }}"
></invoicereturndetailcomponent>
@endsection