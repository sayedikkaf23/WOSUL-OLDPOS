@extends('layouts.layout')

@section("content")
<subscriptiondetailcomponent 
	:subscription_data="{{ json_encode($subscription_data) }}"
	:subscription_feature_data="{{ json_encode($subscription_feature_data) }}"
></subscriptiondetailcomponent>
@endsection
