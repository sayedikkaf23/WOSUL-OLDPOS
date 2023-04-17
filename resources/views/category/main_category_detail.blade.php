@extends('layouts.layout')

@section("content")
<maincategorydetailcomponent 
    :category_data="{{ json_encode($category_data) }}" 
    :subcategory_data="{{ json_encode($subcategory_data) }}"
    :store_data="{{ json_encode($store_data) }}" 
>
</maincategorydetailcomponent>
@endsection