@extends('layouts.layout')

@section("content")
<categorydetailcomponent :category_data="{{ json_encode($category_data) }}" :store_data="{{ json_encode($store_data) }}"></categorydetailcomponent>
@endsection