<!doctype html>
<html lang="en">
    @include('layouts.header')
    <div id="app">
        <body>
            @includeWhen((isset($demo['demo_notification']) && $demo['demo_notification'] != ''), 'layouts.demo_notification')
            <div class="wrapper">
                <div class="content ml-0 p-0">
                    @yield('content')
                </div>
            </div>     
        </body>
        @include('layouts.footer')
    </div>
    @include('layouts.scripts')
    @stack('scripts')
</html>