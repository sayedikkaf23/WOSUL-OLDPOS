@extends('layouts.layout')

@section("content")
<bonatstorecounterpointssettingcomponent 
 :bonat_setting="{{ json_encode($bonat_setting) }}"
 :view_counter_id="{{ json_encode($view_counter_id) }}"  
></bonatstorecounterpointssettingcomponent>
@endsection