@extends('layouts.layout')

@section("content")
<detaillangsettingcomponent :language_setting_data="{{ json_encode($language_setting_data) }}"></detaillangsettingcomponent>
@endsection