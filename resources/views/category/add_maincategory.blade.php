@extends('layouts.layout')

@section("content")
<addmaincategorycomponent 

	:statuses="{{ json_encode($statuses) }}" 
	:category_data="{{ json_encode($category_data) }}"
	:stores="{{ json_encode($all_stores)}}" 
	:sync_zid_category="{{ json_encode($sync_zid_category) }}"
	:zid_status="{{ json_encode($zid_status) }}"

></addmaincategorycomponent>
@endsection

