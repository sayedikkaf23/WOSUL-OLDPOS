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

        <div class="container">
            @include('components.message')

            <div class="row mt-5 pt-5">

                <div class="col-3">
                    <div class="card">
                        <img class="card-img-top" src="/images/marketplace/zid-logo.png" alt="Card image cap"
                            style="height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Zid E-Commerce') }}</h5>
                            <p class="card-text">
                                {{ __('Our objective is to grow your e-commerce and support you in your expansion and success journey') }}
                            </p>
                            <p class="card-text">
                                @if ($data['status']['zid'])
                                    <a href="{{ route('marketplce_zid', ['lang' => request()->lang]) }}"
                                        class="badge bg-success text-white p-2">Configure</a>
                                @else
                                    <a href="{{ route('marketplce_zid', ['lang' => request()->lang]) }}"
                                        class="badge bg-danger text-white p-2">Click to Enable</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <img class="card-img-top" src="/images/marketplace/bonat-logo.png" alt="Card image cap"
                            style="height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Bonat Loyalty Programme') }}</h5>
                            <p class="card-text">
                                {{ __('A Comprehensive loyalty system that will help you drive sales') }}
                            </p>
                            <p class="card-text">
                                @if ($data['status']['bonat'])
                                    <a href="#" class="badge bg-success text-white p-2">Configure</a>
                                @else
                                    <a href="#" class="badge bg-danger text-white p-2">Click to Activate</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


    @include('includes/footer')
    @include('includes/footerscript')
    <script>
        $('#contact_submit_btn').on('click', function() {

            $(this).hide();
            $('#merchant_submit_preloader').show();

        });
    </script>

</body>

</html>
