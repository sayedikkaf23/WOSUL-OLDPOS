@extends('layouts.layout')

@section("content")
    <reportcomponent 
    :roles = "{{ json_encode($roles) }}" 
    :users = "{{ json_encode($users) }}" 
    :user_statuses = "{{ json_encode($user_statuses) }}"
    :product_list = "{{ json_encode($products)}}"
    :suppliers = "{{ json_encode($suppliers) }}" 
    :categories = "{{ json_encode($categories) }}" 
    :taxcodes = "{{ json_encode($taxcodes) }}"
    :discountcodes = "{{ json_encode($discountcodes) }}"
    :product_statuses = "{{ json_encode($product_statuses) }}"
    
    :order_statuses = "{{ json_encode($order_statuses) }}"
    
    :purchase_order_statuses = "{{ json_encode($purchase_order_statuses) }}"
    
    :store_statuses = "{{ json_encode($store_statuses) }}"
    
    :customer_statuses = "{{ json_encode($customer_statuses) }}"
    
    :category_statuses = "{{ json_encode($category_statuses) }}"
    
    :supplier_statuses = "{{ json_encode($supplier_statuses) }}"
    
    :taxcode_statuses = "{{ json_encode($taxcode_statuses) }}"
    
    :discountcode_statuses = "{{ json_encode($discountcode_statuses) }}"
    
    :invoice_statuses = "{{ json_encode($invoice_statuses) }}"
    
    :quotation_statuses = "{{ json_encode($quotation_statuses) }}"
    
    :transaction_types = "{{ json_encode($transaction_types) }}"
    :accounts = "{{ json_encode($accounts) }}"
    :payment_methods = "{{ json_encode($payment_methods) }}"
    :stores = "{{ json_encode($stores) }}"
    :logged_in_store_id = "{{ json_encode($logged_in_store_id) }}"

    :inventory_statuses = "{{ json_encode($inventory_statuses) }}"
    
    > </reportcomponent>
@endsection