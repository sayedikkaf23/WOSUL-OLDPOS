@extends('layouts.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kitchen_view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/labels.css') }}">
@endpush

@section("content")
<waiterviewcomponent></waiterviewcomponent>
@endsection