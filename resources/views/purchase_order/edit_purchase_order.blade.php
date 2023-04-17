@extends('layouts.layout')

@section("content")
<editpurchaseordercomponent :currency_list="{{ json_encode($currency_list) }}" :purchase_order_data="{{ json_encode($purchase_order_data) }}" :tax_options="{{ json_encode($tax_options) }}" :store_tax_percentage="{{ json_encode($store_tax_percentage) }}"></editpurchaseordercomponent>
@endsection