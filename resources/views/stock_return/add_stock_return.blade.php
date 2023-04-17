@extends('layouts.layout')

@section("content")
<addstockreturncomponent 
    :currency_list="{{ json_encode($currency_list) }}" 
    :stock_return_data="{{ json_encode($stock_return_data) }}" 
    :tax_options="{{ json_encode($tax_options) }}" 
    :all_products="{{ json_encode($all_products) }}" 
    :session_currency_code=" {{ json_encode($session_currency_code)  }}"
    :tax_codes=" {{ json_encode($tax_codes)  }}"
></addstockreturncomponent>
@endsection
