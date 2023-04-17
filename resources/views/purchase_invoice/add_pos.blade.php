@extends('layouts.custom_layout')

@push('styles')
    <style>
  .category-tag
    {
        background: white;
        padding: 5px 10px;
        border-radius: 100px;
        border: 2px solid #e9e9e9;
    }
    .category-tag-wrapper li a
    {
        transition: 0.5s ease all;
    }
    .category-tag-wrapper li a.active {
        background: white;
        padding: 5px 10px;
        border-radius: 100px;
        border: 2px solid #186ca5;
        color: #186ca5;
        transition: 0.5s ease all;
    }
    .product-card
    {
        cursor:pointer;
    }
    .product-card:hover
    {
        border:2px solid #186ca5;
    }
    .product-card .card-body img{
        height: 200px;
    }

    .select-product-img{
        padding-top: 20px;
    }
    .select-product-img img
    {
        height: 80px;
        border: 1px solid #e9e9e9;
    }
    .select-product-piece-label,.select-product-piece-count
    {
        border: 1px solid #e9e9e9;
        padding-left: 6px;
        height: 28px;
    }
    .select-product-piece-label label
    {
        vertical-align: middle;
        display: inline;
    }
    .select-product-piece-label label,.select-product-piece-count label
    {
        margin-bottom: 0px!important;
        font-weight: normal!important;
    }
    .select-product-piece-count .count-add-minus
    {
        margin: 0px;
        font-size: 20px;
    }
    .remove-btn
    {
        background: #186ca5;        
        height: 27px;
        padding-top: 3px;

    }
    .pay-icon{
        width: 100%;
    }
  
    </style>
@endpush
@section("content")
<poscomponent :main_categories="{{ json_encode($main_categories) }}" :store_name="{{ json_encode($store_name) }}" :counter_name="{{ json_encode($counter_name) }}" :session_currency_name=" {{ json_encode($session_currency_name)  }}" ></poscomponent>
@endsection
