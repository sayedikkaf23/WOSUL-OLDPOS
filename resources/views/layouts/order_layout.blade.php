<!doctype html>
<html lang="en">
    @include('layouts.header')
    <div id="app">
        <body class="container-fluid p-0">
            @include('layouts.top_nav', ['order' => true])
            <div class="wrapper">
                <div class="content-order m-0 p-0">
                    @yield('content')
                </div>
            </div>     
        </body>
        
    </div>
    @include('layouts.footer', ['fixed_footer' => true])
    @include('layouts.scripts')
    @stack('scripts')
</html>