@php
$favicon = config('app.favicon');
@endphp

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        'use strict';
        window.settings = {
            csrfToken: "{{ csrf_token() }}",
            logged_in_user: "{{ session('slack') }}",
            access_token: "{{ session('access_token') }}",
            logged_in: "{{ session('slack') != '' ? 1 : 0 }}",
            language: "{{ isset($logged_user_data['selected_language_code']) ? $logged_user_data['selected_language_code'] : 'en' }}",
            restaurant_mode: "{{ isset($logged_user_data['selected_store']['restaurant_mode']) ? $logged_user_data['selected_store']['restaurant_mode'] : 0 }}",
            currency_code: "{{ isset($logged_user_data['selected_store']['currency_code']) ? $logged_user_data['selected_store']['currency_code'] : '' }}"
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('website/images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_apple_touch_icon.png') }}" />

    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/web.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/labels.css') }}">
    <link id="modal_css" rel="stylesheet" href="{{ asset('css/modal.css') }}">

    <!-- Main Css -->
    <link rel="stylesheet" href="{{ asset('website/css/style-new.css') }}">
    <!-- Responsive Css -->
    <link rel="stylesheet" href="{{ asset('website/css/responsive-new.css') }}">

    <!-- DataTables -->


    <!-- Icons Css -->
    <link href="{{ asset('theme/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/css/radial-bar-styles.css') }}" rel="stylesheet" />

    @stack('styles')
    <title> {{ config('app.name', 'Wosul') }}</title>
    @if (App::getLocale() == 'ar')
        <link href="{{ asset('css/arabic.css') }}" rel="stylesheet" type="text/css" />
    @endif
</head>
