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

                <div class="col-12">
                    @if (isset($data['zid_store']) && count($data['zid_store']) > 0)
                        <form action="">
                            <div class="form-group">
                                <label for="">Zid Store Id</label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $data['zid_store']['zid_store_id'] }}">
                            </div>
                            <div class="form-group">
                                <label for="">Authorization Token</label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $data['zid_store']['authorization'] }}">
                            </div>
                            <div class="form-group">
                                <label for="">Access Token</label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $data['zid_store']['access_token'] }}">
                            </div>
                            <div class="form-group">
                                <label for="">Expires In</label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $data['zid_store']['expires_in'] }}">
                            </div>

                        </form>
                    @else
                        <form action="{{ route('zid_auth_redirect') }}">
                            <button type="submit" class="btn btn-primary">Link You Zid Account</button>
                        </form>
                    @endif
                </div>

            </div>

        </div>

    </div>


    @include('includes/footer')
    @include('includes/footerscript')

</body>

</html>
