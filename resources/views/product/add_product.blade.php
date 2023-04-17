@extends('layouts.layout')

@section("content")

<addproductcomponent 

	:statuses="{{ json_encode($statuses) }}" 
	:country_list="{{ json_encode($country_list) }}" 
	:taxcodes="{{ json_encode($taxcodes) }}"  
	:suppliers="{{ json_encode($suppliers) }}" 
	:main_categories="{{ json_encode($main_categories) }}" 
	:categories="{{ json_encode($categories) }}" 
	:discount_codes="{{ json_encode($discount_codes) }}" 
	:product_data="{{ json_encode($product_data) }}" 
	:ingredient_data="{{ json_encode($ingredient_data) }}" 
	:stock_transfer_data="{{ json_encode($stock_transfer_data) }}" 
	:stock_transfer_product_data="{{ json_encode($stock_transfer_product_data) }}" 
	:measurement_categories_data="{{ json_encode($measurement_categories_data) }}" 
	:brands="{{ json_encode($brands) }}" 
	:category_id="{{ json_encode($category_id) }}" 
	:main_category_id="{{ json_encode($main_category_id) }}" 
	:subcategories="{{ json_encode($subcategories) }}" 
	:store_tax_slack="{{ json_encode($store_tax_slack) }}" 
	:measurement_category_id="{{ json_encode($measurement_category_id) }}" 
	:measurements_data="{{ json_encode($measurements_data) }}" 
	:store_tax_percentage="{{ json_encode($store_tax_percentage) }}" 
	:store_tax_id="{{ json_encode($store_tax_id) }}" 
	:modifiers="{{ json_encode($modifiers) }}" 
	:product_list = "{{ json_encode($product_list) }}"
	:category_list = "{{ json_encode($category_list) }}"
	:product_modifiers_data="{{ json_encode($product_modifiers_data) }}" 
	:sync_zid_product="{{ json_encode($sync_zid_product) }}" 
	:zid_status="{{ json_encode($zid_status) }}" 
	:stores="{{ json_encode($all_stores)}}" 
	:store_data="{{ json_encode($category_stores)}}" 
    :selection_stores = "{{ json_encode($selection_stores)}}"
    :price_data = "{{ json_encode($price_data)}}"
    :product_clone = "{{ json_encode($product_clone)}}"
></addproductcomponent>
@endsection
