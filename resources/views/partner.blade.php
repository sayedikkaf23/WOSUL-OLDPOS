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
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T436WWS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('includes/header')

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">
            <!-- <div class="fly-divider-space space-md"></div> -->
            <div class="container">
                <div class="row">
                    <!--Content Area-->
                    <div class="fly-content-area col-md-12 col-sm-12">
                        <div>
                            <h1 class="about-head">
                                {{__('Partners')}}
                                <img src="{{ asset('website/images/about-right.png') }}" style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                            <div class="clearfix"></div>

                        </div>


                        <div class="fly-col-inner">
                            <div class="fly-col-inner">

                                <div class="row">

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-01.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-02.jpg')  }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-03.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-04.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-05.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-06.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-07.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-08.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-09.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-10.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-11.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-12.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6 col-md-3">

                                        <div class="partners-logo">

                                            <img src="{{ asset('website/images/partners/Partners-13.jpg') }}" class="img-fluid">

                                        </div>

                                    </div>

                                </div>

                            </div>
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