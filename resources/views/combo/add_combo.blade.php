@extends('layouts.layout')

@section("content")

<addcombocomponent 
    :combo_data="{{ json_encode($combo_data) }}" 
    :statuses_data="{{ json_encode($statuses_data) }}" 
    :categories_data="{{ json_encode($categories_data) }}"
    :products_data="{{ json_encode($products_data) }}"
    :groups_data="{{ json_encode($groups_data) }}">
</addcombocomponent>
@endsection