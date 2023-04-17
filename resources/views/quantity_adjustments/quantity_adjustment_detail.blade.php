@extends('layouts.layout')
@php
$quantityadjustmentdata = isset($quantity_adjustment_details)?$quantity_adjustment_details:[];
$quantityadjustmentproducts = isset($quantity_adjustment_products)?$quantity_adjustment_products:[];
@endphp
@section("content")
<quantityadjustmentdetailcomponent :quantity_adjustment="{{json_encode($quantityadjustmentdata)}}"
    :quantity_adjustment_products="{{json_encode($quantityadjustmentproducts)}}">
</quantityadjustmentdetailcomponent>
@endsection