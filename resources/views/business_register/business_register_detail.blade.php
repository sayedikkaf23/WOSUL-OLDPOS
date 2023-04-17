@extends('layouts.layout')

@section("content")
<businessregisterdetailcomponent :business_register_data="{{ json_encode($business_register_data) }}" :delete_register_access="{{ json_encode($delete_register_access) }}"></businessregisterdetailcomponent>
@endsection