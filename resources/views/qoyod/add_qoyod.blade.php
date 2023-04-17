@extends('layouts.layout')

@section("content")
<addqoyodcomponent :qoyod_data="{{ json_encode($qoyod_data) }}"></addqoyodcomponent>
@endsection