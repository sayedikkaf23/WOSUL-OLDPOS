@extends('layouts.layout')

@section("content")
<bonatuserpointssettingcomponent :bonat_setting="{{ json_encode($bonat_setting) }}"></bonatuserpointssettingcomponent>
@endsection