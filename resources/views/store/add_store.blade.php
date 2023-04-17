@extends('layouts.layout')

@section("content")


<addstorecomponent :logged_user_store_id="{{ json_encode($logged_user_store_id) }}" :statuses="{{ json_encode($statuses) }}"  :discount_codes="{{ json_encode($discount_codes) }}" :store_data="{{ json_encode($store_data) }}" :invoice_print_types="{{ json_encode($invoice_print_types) }}" :currency_list="{{ json_encode($currency_list) }}" :country_list="{{ json_encode($country_list) }}" :accounts="{{ json_encode($accounts) }}" :billing_type_list="{{ json_encode($billing_type_list) }}" :waiter_role="{{ json_encode($waiter_role) }}" :session_currency_code=" {{ json_encode($session_currency_code)  }}" :tax_codes=" {{ json_encode($tax_codes)  }}" :restaurant_url=" {{ json_encode($restaurant_url?? '')  }}" :restaurant_id=" {{ json_encode($restaurant_id?? '')  }}" :user_id=" {{ json_encode($user_id?? '')  }}" :store_id="{{ json_encode($store_id?? '')  }}" :base_url="{{ json_encode(url('/')) }}" ></addstorecomponent>
@endsection
