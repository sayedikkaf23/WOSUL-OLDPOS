<!doctype html>
<html lang="en">
    @include('layouts.header')
    <div id="app">
        <body class="container-fluid p-0">
            @includeWhen((isset($logged_user_data['demo_notification']) && $logged_user_data['demo_notification'] != ''), 'layouts.demo_notification')
            @include('layouts.top_nav')
            <div class="wrapper" style="margin-top:70px;">
                <!-- @include('layouts.side_nav') -->
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </body>
        @include('layouts.footer')
    </div>
    @include('layouts.scripts')
    @stack('scripts')
</html>
