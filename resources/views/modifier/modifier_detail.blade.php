@extends('layouts.layout')

@section("content")
<modifierdetailcomponent :modifier_data="{{ json_encode($modifier_data) }}"></modifierdetailcomponent>
@endsection
