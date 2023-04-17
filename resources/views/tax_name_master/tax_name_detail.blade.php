@extends('layouts.layout')

@section("content")
<taxnamedetailcomponent :tax_name_data = "{{ json_encode($tax_name_data) }}"></taxnamedetailcomponent>
@endsection