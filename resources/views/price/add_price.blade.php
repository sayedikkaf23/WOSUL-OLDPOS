@extends('layouts.layout')

@section("content")
<addpricecomponent :statuses="{{ json_encode($statuses) }}" :price_data="{{ json_encode($price_data) }}" :store_data="{{ json_encode($store_data) }}"></addpricecomponent>
@endsection