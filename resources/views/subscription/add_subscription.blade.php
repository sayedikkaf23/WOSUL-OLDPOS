@extends('layouts.layout')

@section("content")
<addsubscriptioncomponent 
	:statuses="{{ json_encode($statuses) }}" 
	:subscription_data="{{ json_encode($subscription_data) }}"
	:subscription_features_data="{{ json_encode($subscription_features_data) }}"
	:currency_data="{{ json_encode($currency_data) }}"
></addsubscriptioncomponent>
@endsection