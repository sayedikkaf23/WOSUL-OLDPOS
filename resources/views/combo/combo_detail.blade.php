@extends('layouts.layout')

@section("content")
<comboproductdetailcomponent :tax_code_data="{{ json_encode($tax_code_data) }}"></comboproductdetailcomponent>
@endsection