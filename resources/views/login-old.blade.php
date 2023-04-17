<!DOCTYPE html>
<html>

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-T436WWS');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-197961721-1">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-197961721-1');
    </script>
    <title>WOSUL</title>

    @include('includes/headerscript')

</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T436WWS" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @include('includes/header')

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">

            <div class="wosul-form-bg">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">

                                {{ __('Login') }}
                                <img src=" {{ asset('public/images/about-right.png') }}"
                                    style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            <div class="wosul-form-content">



                <div class="container">
                    <div class="col-md-12">
                        @if ($errors->any())
                            <div class="alert alert-danger text-center"><strong>{{ $errors->first() }}</strong></div>
                        @endif
                    </div>


                    <div class="row">

                        <div class="offset-md-2 col-md-7">

                            <div class="form-header">

                                <img src="{{ asset('public/images/form-header.png') }}" class="img-fluid">

                            </div>

                            @include('components.message')
                            <form action="{{ route('authenticate', ['lang' => request('lang')]) }}" method="POST">

                                @csrf

                                <div class="form-content mt-5">


                                    <div class="form-group">

                                        <label for="email">{{ __('Email') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="email" name="email"
                                            aria-describedby="email" placeholder="{{ __('enter merchant email') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;">

                                    </div>

                                    <div class="form-group">

                                        <label for="password">{{ __('Password') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="password" class="form-control" id="password" name="password"
                                            aria-describedby="password"
                                            placeholder="{{ __('enter merchant password') }}" hidefocus="true"
                                            style="outline: currentcolor none medium;">

                                    </div>

                                    <div class="form-group">

                                        <button type="submit" class="btn custom-btn">{{ __('Login') }} </button>

                                    </div>

                                    <input type="hidden" name="lang" value="{{ request('lang') }}">

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


    @include('includes/footer')
    @include('includes/footerscript')


</body>

</html>
