@extends('layouts.layout')

@section("content")

<addtaxnamecomponent :statuses="{{ json_encode($statuses) }}" 
    :tax_name_data="{{ json_encode($tax_name_data) }}"
    :tax_names_list_route="{{ json_encode($tax_names_list_route) }}"
>
</addtaxnamecomponent>
@endsection