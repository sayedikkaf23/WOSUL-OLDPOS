@extends('layouts.layout')

@section("content")
<edituserpointssettingcomponent :statuses="{{ json_encode($statuses) }}" :setting_data="{{ json_encode($setting_data) }}"></edituserpointssettingcomponent>
@endsection