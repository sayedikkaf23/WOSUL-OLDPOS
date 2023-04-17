@extends('layouts.layout')

@section("content")
<smssettingcomponent :sms_setting="{{ json_encode($sms_setting) }}"></smssettingcomponent>
@endsection