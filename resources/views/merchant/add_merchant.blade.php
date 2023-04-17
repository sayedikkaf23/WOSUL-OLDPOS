@extends('layouts.layout')

@section("content")
<addmerchantcomponent :statuses="{{ json_encode($statuses) }}" :merchant_data="{{ json_encode($merchant_data) }}"></addmerchantcomponent>
@endsection