@extends('layouts.layout')

@section("content")
    <taxreturnreportcomponent 
    :min_created_year = "{{ json_encode($min_created_year) }}"
    
    > </taxreturnreportcomponent>
@endsection