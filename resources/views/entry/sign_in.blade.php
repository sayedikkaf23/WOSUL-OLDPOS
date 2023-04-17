<html lang="en">
    @php
	    $favicon = config("app.favicon");
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
	            logged_in: "{{ (session('slack') != '')?1:0 }}",
	            language: "{{ (isset($logged_user_data['selected_language_code']))?$logged_user_data['selected_language_code']:'en' }}",
	            restaurant_mode: "{{ (isset($logged_user_data['selected_store']['restaurant_mode']))?$logged_user_data['selected_store']['restaurant_mode']:0 }}",
	            currency_code: "{{ (isset($logged_user_data['selected_store']['currency_code']))?$logged_user_data['selected_store']['currency_code']:'' }}"
	        }
	    </script>
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    
	    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('website/images/favicon.png') }}">
	    <link rel="apple-touch-icon" href="{{ asset('images/logo_apple_touch_icon.png') }}"/>
	    
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
	    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
	
		
	    {{-- Custom Styles --}}
        <link href="{{ asset('theme/css/sb-admin-2.min.css') }}" rel="stylesheet">
		
		{{-- new theme css --}}
        <link rel="stylesheet" href="{{ asset('css/splide-default.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/login/style.css') }}">
		<link rel="stylesheet" href="{{ asset('css/login/responsive.css') }}"> 

		@stack('styles')
	    <title> {{ config('app.name') }}</title>

	</head>

    <div id="app">
        <body>
            <div class="wrapper">
                <div class="content ml-0 p-0">
					{{-- <h1>Hello</h1> --}}
                        <signincomponent :is_demo="{{ json_encode($is_demo) }}" :preview_mode = "{{ json_encode($preview_mode) }}" :company_logo = "{{ json_encode($company_logo) }}" :stores="{{ json_encode($stores)  }}" :store_logo_path="{{ json_encode($store_logo_path)  }}"  ></signincomponent>
                </div>
            </div>     
        </body>
        @include('layouts.footer')
    </div>
    @include('layouts.scripts')
    @stack('scripts')
	<script>
		const splide = new Splide( '.gallery-1', {
			type   : 'loop',
			drag   : 'free',
			pauseOnHover: false,
			pagination: false,
			arrows : false,
			focus  : 'center',
			direction : 'ttb' ,
			height   : '100vh',
			perPage: 4,
			autoScroll: {
				speed: 1,
			},
		} ).mount( window.splide.Extensions );

		const splide1 = new Splide( '.gallery-2', {
			type   : 'loop',
			drag   : 'free',
			pauseOnHover: false,
			pagination: false,
			arrows : false,
			focus  : 'center',
			direction : 'ttb' ,
			height   : '100vh',
			perPage: 4,
			autoScroll: {
				speed: 0.5,
			},
		} ).mount( window.splide.Extensions );

		const splide2 = new Splide( '.gallery-3', {
			type   : 'loop',
			drag   : 'free',
			pauseOnHover: false,
			pagination: false,
			arrows : false,
			focus  : 'center',
			direction : 'ttb' ,
			height   : '100vh',
			perPage: 4,
			autoScroll: {
				speed: 1.5,
			},
		} ).mount( window.splide.Extensions );
		
	</script>
</html>

