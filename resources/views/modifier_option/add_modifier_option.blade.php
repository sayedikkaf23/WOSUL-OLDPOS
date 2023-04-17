@extends('layouts.layout')
@section("content")
<addmodifieroptioncomponent 
	:statuses="{{ json_encode($statuses) }}" 
	:modifier_data="{{ json_encode($modifier_data) }}"
	:modifier_option_data="{{ json_encode($modifier_option_data) }}"
	:ingredient_data="{{ json_encode($ingredient_data) }}"
	:modifier_option_ingredients_data="{{ json_encode($modifier_option_ingredients_data) }}"
	></addmodifieroptioncomponent>
@endsection