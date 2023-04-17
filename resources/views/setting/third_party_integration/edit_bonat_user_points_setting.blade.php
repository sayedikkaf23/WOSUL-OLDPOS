@extends('layouts.layout')

@section("content")
<editbonatuserpointssettingcomponent :statuses="{{ json_encode($statuses) }}" :setting_data="{{ json_encode($setting_data) }}"></editbonatuserpointssettingcomponent>
@endsection