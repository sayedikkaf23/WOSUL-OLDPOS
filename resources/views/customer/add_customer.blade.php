@extends('layouts.layout')

@section("content")
<addcustomercomponent :statuses="{{ json_encode($statuses) }}" :country_list="{{ json_encode($country_list) }}" :customer_data="{{ json_encode($customer_data) }}"></addcustomercomponent>
@endsection