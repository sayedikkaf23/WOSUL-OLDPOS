@extends('layouts.custom_layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quickpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/labels.css') }}">
@endpush

@section('content')
    <div class="container text-center mt-5 pt-5">
        <div class="alert alert-danger">
            <h2>{{ $message }}</h2>
        </div>
        @isset($store_slack)
        <div class="btn btn-primary mt-5">
            <a href="{{ url('/edit_store/' . $store_slack) }}" style="padding-top:50px;">Click here to change store
                timings</a>
        </div>
        @endisset
    </div>
@endsection
