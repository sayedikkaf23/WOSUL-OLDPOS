@extends('layouts.layout')

@section("content")
<addmeasurementcomponent 
	:statuses="{{ json_encode($statuses) }}" 
	:measurement_data="{{ json_encode($measurement_data) }}"
	:measurement_categories="{{ json_encode($measurement_categories) }}"
></addmeasurementcomponent>
@endsection