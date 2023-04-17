@extends('layouts.layout')

@section("content")
<addsuppliercomponent :statuses="{{ json_encode($statuses) }}" :stores="{{ json_encode($all_stores) }}" :selection_stores = "{{ json_encode($selection_stores)}}" :country_list="{{ json_encode($country_list) }}" :supplier_data="{{ json_encode($supplier_data) }}"></addsuppliercomponent>
@endsection