@extends('layouts.layout')

@section("content")

<addcombogroupcomponent 
    
    :combo_group_data="{{ json_encode($combo_group_data) }}"
    :parent_group_data="{{ json_encode($parent_group_data) }}"

></addcombogroupcomponent>

@endsection