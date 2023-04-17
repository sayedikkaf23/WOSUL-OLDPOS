@extends('layouts.layout')

@section("content")
<measurementdetailcomponent 
	:measurement_data="{{ json_encode($measurement_data) }}"
	:measurement_conversion_data="{{ json_encode($measurement_conversion_data) }}"
></measurementdetailcomponent>
@endsection
