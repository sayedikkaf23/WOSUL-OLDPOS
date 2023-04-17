@extends('layouts.layout')

@section("content")
<userpointssettingcomponent :abkhas_setting="{{ json_encode($abkhas_setting) }}"></userpointssettingcomponent>
@endsection