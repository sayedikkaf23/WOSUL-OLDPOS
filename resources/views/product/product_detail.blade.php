@extends('layouts.layout')

@section("content")
<productdetailcomponent 
    :product_data="{{ json_encode($product_data) }}"
    :ingredient_products="{{ json_encode($ingredient_products) }}"
    :tax_data="{{ json_encode($tax_data) }}">
</productdetailcomponent>
@endsection
