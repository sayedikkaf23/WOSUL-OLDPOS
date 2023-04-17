@extends('layouts.layout')

@section("content")

<addingredientcomponent 
	
	:statuses="{{ json_encode($statuses) }}" 
	:taxcodes="{{ json_encode($taxcodes) }}"  
	:suppliers="{{ json_encode($suppliers) }}" 
	:main_categories="{{ json_encode($main_categories) }}" 
	:categories="{{ json_encode($categories) }}" 
	:discount_codes="{{ json_encode($discount_codes) }}" 
	:product_data="{{ json_encode($product_data) }}" 
	:stock_transfer_data="{{ json_encode($stock_transfer_data) }}" 
	:stock_transfer_product_data="{{ json_encode($stock_transfer_product_data) }}"
	:brands="{{ json_encode($brands) }}"
	:product_list = "{{ json_encode($product_list) }}"
	:category_list = "{{ json_encode($category_list) }}" 
	:store_tax_slack="{{ json_encode($store_tax_slack) }}"
	:measurement_categories_data="{{ json_encode($measurement_categories_data) }}"
	:main_category_id="{{ json_encode($main_category_id) }}"
	:category_id="{{ json_encode($category_id) }}"
	:subcategories="{{ json_encode($subcategories) }}"
	:stock_transfer_product_data="{{ json_encode($stock_transfer_product_data) }}"
	:measurement_category_id="{{ json_encode($measurement_category_id) }}"
	:measurements_data="{{ json_encode($measurements_data) }}"
	:store_tax_id="{{ json_encode($store_tax_id) }}"

	></addingredientcomponent>

@endsection
