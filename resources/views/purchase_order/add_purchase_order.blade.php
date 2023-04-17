@extends('layouts.layout')

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
    .select-product-img{
        padding-top: 20px;
    }
    .select-product-img img
    {
        /*height: 10vh;*/
        /*border: 1px solid #e9e9e9;*/
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
    .purchases-form
    {
        width: 100%;
    }
    .select-total-label p,.select-total-price p
    {
        color: #186ca5 !important;
    }
    .purchases-form .payment-btn
    {
        margin-top: 20px!important;
    }
    .purchases-form .form-control
    {
        border-top: none;
        border-left: none;
        border-right: none;
        border-radius: 0px;
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
    .product-row{
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    .card{
        border: none;
    }
    .fs-22{
        font-size:22px; 
    }
    .fs-18{
        font-size:18px; 
    }
    .fs-15{
        font-size:15px; 
    }
    .text-blue{
        color: #186ca5;
    }

    @media screen and (min-width: 992px)
    {
        .select-product-img-blk
        {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .select-product-price-blk
        {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
    }
    .product-image{
        height: 80%; */
    width: 100px;
    height: 100px;
    border: 5px solid #fff;
    border-radius: 8px;
    }

    </style>
@endpush

@section("content")
<addpurchaseordercomponent 
    :main_categories="{{ json_encode($main_categories)  }}" 
    :suppliers="{{ json_encode($suppliers)  }}" 
    :supplierdata = "{{json_encode($supplierdata)}}"
    :stores="{{ json_encode($stores)  }}" 
    :po_number="{{ json_encode($po_number) }}" 
    :session_currency_code=" {{ json_encode($session_currency_code)  }}" 
    :purchase_policy_information=" {{ json_encode($purchase_policy_information)  }}"  
    :store_tax_percentage="{{ json_encode($store_tax_percentage) }}" 
    :purchase_order_data="{{ json_encode($purchase_order_data) }}" 
    :currency_list="{{ json_encode($currency_list) }}"
    :tax_codes=" {{ json_encode($tax_codes)  }}"
></addpurchaseordercomponent>
@endsection
