@extends('layouts.layout')

@section("content")
<merchantdetailcomponent :merchant_data="{{ json_encode($merchant_data) }}"></merchantdetailcomponent>
@endsection