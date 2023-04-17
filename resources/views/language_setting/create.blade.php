@extends('layouts.layout')

@section("content")
<addlangsettingcomponent :statuses="{{ json_encode($statuses) }}" :lang_data="{{ json_encode($lang_data) }}"></addlangsettingcomponent>
@endsection