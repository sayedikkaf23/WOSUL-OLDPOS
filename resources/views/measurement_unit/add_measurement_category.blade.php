@extends('layouts.layout')

@section("content")
<addmeasurementcategorycomponent :statuses="{{ json_encode($statuses) }}" :measurement_category_data="{{ json_encode($measurement_category_data) }}"></addmeasurementcategorycomponent>
@endsection