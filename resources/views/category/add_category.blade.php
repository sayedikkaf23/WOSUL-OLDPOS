@extends('layouts.layout')

@section("content")

<addcategorycomponent 

	:main_categories="{{  json_encode($main_categories) }}" 
	:statuses="{{ json_encode($statuses) }}" 
	:category_data="{{ json_encode($category_data) }}"
	:stores="{{ json_encode($category_stores)}}" 
	:sync_zid_category="{{ json_encode($sync_zid_category) }}"
	:zid_status="{{ json_encode($zid_status) }}"
	:store_data="{{ json_encode($all_stores) }}"

></addcategorycomponent>
@endsection