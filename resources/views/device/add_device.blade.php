@extends('layouts.layout')

@section('content')
    <adddevicecomponent :statuses="{{ json_encode($statuses) }}" :device_data="{{ json_encode($device_data) }}"
        :currency_data="{{ json_encode($currency_data) }}"></adddevicecomponent>
@endsection
