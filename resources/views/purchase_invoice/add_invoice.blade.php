@extends('layouts.custom_layout')

@section("content")

<addinvoicecomponent :currency_list="{{ json_encode($currency_list) }}" :invoice_data="{{ json_encode($invoice_data) }}" :store_data="{{ json_encode($store_data) }}" :tax_options="{{ json_encode($tax_options) }}" :product_data="{{ json_encode($product_data) }}" :session_currency_code=" {{ json_encode($session_currency_code)  }}" :session_currency_name=" {{ json_encode($session_currency_name)  }}" :invoice_data_charges=" {{ json_encode($invoice_data_charges)  }} " :tax_codes=" {{ json_encode($tax_codes)  }} " :category_data=" {{ json_encode($category_data)  }} "></addinvoicecomponent>
@endsection
