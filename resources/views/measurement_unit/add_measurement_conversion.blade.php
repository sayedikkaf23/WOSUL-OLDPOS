@extends('layouts.layout')

@section("content")
<addmeasurementconversioncomponent 
	:statuses="{{ json_encode($statuses) }}" 
	:measurement_conversion_data="{{ json_encode($measurement_conversion_data) }}"
	:measurement_slack_data="{{ json_encode($measurement_slack_data) }}"
	></addmeasurementconversioncomponent>
@endsection