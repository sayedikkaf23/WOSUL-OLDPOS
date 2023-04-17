@extends('layouts.custom_layout')

@section("content")

<addquotationcomponent :currency_list="{{ json_encode($currency_list) }}" :country_list="{{ json_encode($country_list) }}" :quotation_data="{{ json_encode($quotation_data) }}" :store_data="{{ json_encode($store_data) }}" :tax_options="{{ json_encode($tax_options) }}" :product_data="{{ json_encode($product_data) }}" :session_currency_code=" {{ json_encode($session_currency_code)  }}" :session_currency_name=" {{ json_encode($session_currency_name)  }}"  :tax_codes=" {{ json_encode($tax_codes)  }} " :category_data=" {{ json_encode($category_data)  }} "></addquotationcomponent>
@endsection
