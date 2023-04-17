@extends('layouts.layout')

@section("content")
<editbonatstorecounterpointssettingcomponent 
:statuses="{{ json_encode($statuses) }}" 
:setting_data="{{ json_encode($setting_data) }}"
:view_counter_id="{{ json_encode($view_counter_id) }}"
></editbonatstorecounterpointssettingcomponent>
@endsection