@extends('layouts.layout')

@section("content")
<expresspaysettingcomponent :expresspay_data="{{ json_encode($expresspay_data) }}"></expresspaysettingcomponent>
@endsection