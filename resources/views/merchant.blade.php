<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-197961721-1"></script>
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

            <div class="">

                <div class="container pt-5">

                    <div class="col-md-12">
                        <?php if (isset($success) && $success != "") { ?>
                        <div class="alert alert-success text-center"><strong><?php echo $success; ?></strong></div>
                        <?php } else if (isset($error) && $error != "") { ?>
                        <div class="alert alert-danger text-center"><strong><?php echo $error; ?></strong></div>
                        <?php }  ?>
                    </div>

                    <div class="row justify-content-center pt-5">
                        <div class="card col-lg-10" style="background: white;
                        box-shadow: 1px 1px 5px;">
                            @include('components.message')
                            <form method="POST" action="{{ route('save_register_merchant') }}">

                                @csrf

                                <div class="form-content-wrapper">
                                    <div class="col-lg-12 text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <h2 class="text-uppercase mt-4 mb-0" style="color:#006eb7">
                                                {{ __('Get Wosul') }}</h2>
                                        </div>
                                    </div>
                                    <form class="mt-4 mb-3 px-3">
                                        <div class="form-row px-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="username">{{ __('Full Name') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="name"
                                                        aria-describedby="username"
                                                        placeholder="{{ __('Full Name') }}" hidefocus="true"
                                                        style="outline: currentcolor none medium;">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">

                                                    <label for="usernumber">{{ __('Phone Number') }} <span
                                                            class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" id="usernumber"
                                                        aria-describedby="usernumber"
                                                        placeholder="{{ __('Phone Number') }}" hidefocus="true"
                                                        style="outline: currentcolor none medium;" name="phone_number">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row px-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">

                                                    <label for="useremail">{{ __('E-mail') }} <span
                                                            class="text-danger">*</span></label>

                                                    <input type="email" class="form-control" id="useremail"
                                                        aria-describedby="useremail" placeholder="{{ __('E-mail') }}"
                                                        hidefocus="true" style="outline: currentcolor none medium;"
                                                        name="email">
                                                    <label for="" class="text-danger email-exists"
                                                        style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>

                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">

                                                    <label for="usercompany">{{ __('Company') }}<span
                                                            class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" id="usercompany"
                                                        aria-describedby="usercompany"
                                                        placeholder="{{ __('Company') }}" hidefocus="true"
                                                        style="outline: currentcolor none medium;" name="company_name">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row px-4">
                                            <div class="col-lg-12">
                                                <div class="form-group">

                                                    <label for="usercompany">{{ __('Company URL') }} <span
                                                            class="text-danger">*</span></label>

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="usercompanyurl"
                                                                    aria-describedby="usercompanyurl"
                                                                    placeholder="{{ __('Company URL') }}"
                                                                    hidefocus="true"
                                                                    style="outline: currentcolor none medium;"
                                                                    name="company_url">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"
                                                                        id="basic-addon2">.wosul.sa</span>
                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">

                                                            <label for="" class="text-danger company-url-exists"
                                                                style="display:none;"><strong>{{ __('Company URL Already Exists') }}</strong></label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row px-4">

                                            <div class="col-lg-6">
                                                <div class="form-group">

                                                    <label for="userpromotioncode">{{ __('Promotion Code') }}
                                                    </label>

                                                    <input type="text" class="form-control" id="userpromotioncode"
                                                        aria-describedby="userpromotioncode"
                                                        placeholder="{{ __('Promotion Code') }}" hidefocus="true"
                                                        style="outline: currentcolor none medium;" name="promo_code">

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">

                                                    <label for="recommendation">{{ __('Recommendation') }} </label>

                                                    <input type="text" class="form-control" id="recommendation"
                                                        aria-describedby="recommendation"
                                                        placeholder="{{ __('Recommendation') }}" hidefocus="true"
                                                        style="outline: currentcolor none medium;"
                                                        name="recommendation">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mb-4">

                                            <div id="merchant_submit_preloader" style="display: none;">
                                                <img src="{{ asset('website/images/loader2.gif') }}" alt=""> <br>
                                                <strong class="text-primary">
                                                    {{ __('Your application is submitting, please wait') }} </strong>
                                            </div>

                                            <button id="merchant_submit_btn"
                                                class="btn btn-primary btn-sm text-uppercase mt-2 px-4 py-1">{{ __('Submit') }}
                                            </button>
                                        </div>

                                        <input type="hidden" name="subscription_id"
                                            value="{{ $data['subscription_id'] }}">
                                        <input type="hidden" name="lang" value="{{ request('lang') }}">

                                    </form>
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

    <script>
        $('#useremail').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var useremail = $('#useremail').val();

            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('email', useremail);

            $.ajax({
                url: "{{ route('is_email_exists') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data) {
                        $('.email-exists').show();
                        $('#useremail').css('color', 'red');
                    } else {
                        $('.email-exists').hide();
                        $('#useremail').css('color', 'inherit');
                    }
                }
            });

        });

        $('#usercompanyurl').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var company_url = $('#usercompanyurl').val();

            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('company_url', company_url);

            $.ajax({
                url: "{{ route('is_company_url_exists') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data) {
                        $('.company-url-exists').show();
                        $('#usercompanyurl').css('color', 'red');
                    } else {
                        $('.company-url-exists').hide();
                        $('#usercompanyurl').css('color', 'inherit');
                    }
                }
            });

        });

        // hide and show preloader in during form submission

        $('#merchant_submit_btn').on('click', function() {

            $(this).hide();
            $('#merchant_submit_preloader').show();

        });
    </script>

</body>

</html>
