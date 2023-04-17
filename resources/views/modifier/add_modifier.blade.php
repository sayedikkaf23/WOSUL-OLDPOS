@extends('layouts.layout')

@section("content")
<addmodifiercomponent :statuses="{{ json_encode($statuses) }}" :modifier_data="{{ json_encode($modifier_data) }}"></addmodifiercomponent>
@endsection