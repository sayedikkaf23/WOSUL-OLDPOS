@extends('layouts.layout')

@section("content")
<addbrandcomponent :statuses="{{ json_encode($statuses) }}" :brand_data="{{ json_encode($brand_data) }}"></addbrandcomponent>
@endsection
