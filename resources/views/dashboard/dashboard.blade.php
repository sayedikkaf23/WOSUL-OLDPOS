@extends('layouts.layout')

@php

$invoice_revenue = round($revenue['in_invoice']);
$pos_credit_revenue = round($revenue['in_pos_credit']);
$pos_cash_revenue = round($revenue['in_pos_cash']);
$total_revenue = $invoice_revenue + $pos_cash_revenue + $pos_credit_revenue;
$lang = App::getLocale();
if ($total_revenue > 0) {
    $chart_revenue_value = round(($chart['revenue_value']['count_raw'] * 100) / $total_revenue);
    $chart_expense = round(($chart['expense']['count_raw'] * 100) / $total_revenue);
    $chart_net_profit_value = round(($chart['net_profit_value']['count_raw'] * 100) / $total_revenue);
} else {
    $chart_revenue_value = 0;
    $chart_expense = 0;
    $chart_net_profit_value = 0;
}

$store_opening_time = request()->logged_store_opening_time;
$store_closing_time = request()->logged_store_closing_time;

@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/fontawesome.min.css">

    <style>
        .custom-block-card-height {
            height: 130px;
        }

    </style>

@endpush

@push('scripts')



@endpush

@section("content")
    <dashboardcomponent
	    :lang = "{{json_encode($lang)}}" 
    	:store="{{ json_encode($store)}}" 
    	:revenue="{{ json_encode($revenue)}}"
        :dashboard_start_date = "{{json_encode($dashboard_start_date)}}"
        :dashboard_end_date = "{{json_encode($dashboard_end_date)}}"
    	:total_sales_quantity="{{ json_encode($total_sales_quantity)}}"
    	:total_sales_amount="{{ json_encode($total_sales_amount)}}"
    	:total_sales_margin_amount="{{ json_encode($total_sales_margin_amount)}}"
    	:top_selling_products="{{ json_encode($top_selling_products)}}"
    	:top_earning_products="{{ json_encode($top_earning_products)}}"
    	{{-- :store_names="{{ json_encode($store_names)}}" --}}
    	:is_master="{{ json_encode($is_master)}}"
    	:show_combined_stats="{{ json_encode($show_combined_stats)}}"
		:date_format="{{  json_encode($date_formatted)  }}"
        :logged_store_opening_time="{{ json_encode($store_opening_time) }}"
        :logged_store_closing_time="{{ json_encode($store_closing_time) }}"
	></dashboardcomponent>
@endsection
