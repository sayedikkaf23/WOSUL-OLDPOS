<!DOCTYPE html>
<html>

<head>
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

                                {{ __('Get Wosul') }}
                                <img src=" {{ asset('website/images/about-right.png') }}"
                                    style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            <div class="wosul-form-content">



                <div class="container">
                    <div class="col-md-12">
                        <?php if (isset($success) && $success != "") { ?>
                        <div class="alert alert-success text-center"><strong><?php echo $success; ?></strong></div>
                        <?php } else if (isset($error) && $error != "") { ?>
                        <div class="alert alert-danger text-center"><strong><?php echo $error; ?></strong></div>
                        <?php }  ?>
                    </div>


                    <div class="row">

                        <div class="offset-md-2 col-md-7">

                            <div class="form-header">

                                <img src="{{ asset('website/images/form-header.png') }}" class="img-fluid">

                            </div>

                            @include('components.message')

                            <div class="form-info">
                                <p><strong>{{ __('Facility registration form in WOSUL system') }}</strong></p>
                                <p>{{ __('Tell us a little about you, and we will contact you by phone or email to provide information about products and access services. Or call') }}
                                </p>
                                <p>{{ __('Next number') }} : 0549249523</p>

                            </div>

                            <div class="form-content">

                                <form method="POST" action="{{ route('save_register_merchant') }}">

                                    @csrf

                                    <div class="form-group">

                                        <label for="username">{{ __('Full Name') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="username" name="name"
                                            aria-describedby="username" placeholder="{{ __('Full Name') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;">

                                    </div>

                                    <div class="form-group">

                                        <label for="usernumber">{{ __('Phone Number') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="usernumber"
                                            aria-describedby="usernumber" placeholder="{{ __('Phone Number') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;"
                                            name="phone_number">

                                    </div>

                                    <div class="form-group">

                                        <label for="useremail">{{ __('E-mail') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="email" class="form-control" id="useremail"
                                            aria-describedby="useremail" placeholder="{{ __('E-mail') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;" name="email">
                                        <label for="" class="text-danger email-exists"
                                            style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>

                                    </div>

                                    {{-- <div class="form-group">

                                        <label for="password">{{__('Password')}} <span class="text-danger">*</span></label>

                                        <input type="password" class="form-control" id="password" aria-describedby="password" placeholder="{{__('Password')}}" hidefocus="true" style="outline: currentcolor none medium;" name="password">

                                    </div> --}}

                                    <div class="form-group">

                                        <label for="usercompany">{{ __('Company') }}<span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="usercompany"
                                            aria-describedby="usercompany" placeholder="{{ __('Company') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;"
                                            name="company_name">

                                    </div>

                                    <div class="form-group">

                                        <label for="usercompany">{{ __('Company URL') }} <span
                                                class="text-danger">*</span></label>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanyurl"
                                                    aria-describedby="usercompanyurl"
                                                    placeholder="{{ __('Company URL') }}" hidefocus="true"
                                                    style="outline: currentcolor none medium;" name="company_url">

                                                <small id="usercompanyurl"
                                                    class="form-text text-muted">{{ __('Example : demo') }}</small>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanyurl"
                                                    aria-describedby="usercompanyurl" placeholder=".wosul.com"
                                                    disabled="" hidefocus="true"
                                                    style="outline: currentcolor none medium;">
                                                <label for="" class="text-danger company-url-exists"
                                                    style="display:none;"><strong>{{ __('Company URL Already Exists') }}</strong></label>


                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label for="useraddress">{{ __('Address') }}<span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="useraddress"
                                            aria-describedby="useraddress" placeholder="{{ __('Address') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;" name="address">

                                    </div>

                                    <!-- <div class="form-group custom-check ">

                                        <label for="useraddress">Order Hardware | اطلب جهازك</label>

                                        <div class="clearfix"></div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <label class="form-check-label" for="pos-plan-check">

                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="pos-plan-check" hidefocus="true" style="outline: currentcolor none medium;"><span class="checkmark"></span>

                                                        <label class="form-check-label" for="pos-plan-check"><img src="images/ipad-stand.jpg" class="img-fluid"></label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="pos-plan-check">Ipad Stand | قاعدة تثبيت آيباد</label>

                                                    </div>

                                                </label>

                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-check-label" for="erp-plan-check">

                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="erp-plan-check" hidefocus="true" style="outline: currentcolor none medium;">

                                                        <label class="form-check-label" for="erp-plan-check">
                                                        <img src="images/cash-drawer.jpg" class="img-fluid">
                                                        </label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="erp-plan-check">Cash drawer | صندوق النقد
                                                        </label>

                                                    </div>

                                                </label>

                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="col-md-6">

                                                <label class="form-check-label" for="manufacturing-plan-check">

                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="manufacturing-plan-check" hidefocus="true" style="outline: currentcolor none medium;">

                                                        <label class="form-check-label" for="manufacturing-plan-check">
                                                        <img src="images/invoice-printer.jpg" class="img-fluid">
                                                        </label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="manufacturing-plan-check">Invoice printer | طابعة فواتير
                                                        </label>

                                                    </div>

                                                </label>

                                            </div>

                                        </div>
 -->

                                    {{-- </div> --}}

                                    <div class="form-group">

                                        <label for="userpromotioncode">{{ __('Promotion Code') }} </label>

                                        <input type="text" class="form-control" id="userpromotioncode"
                                            aria-describedby="userpromotioncode"
                                            placeholder="{{ __('Promotion Code') }}" hidefocus="true"
                                            style="outline: currentcolor none medium;" name="promo_code">

                                    </div>

                                    <div class="form-group">

                                        <label for="useraddress">{{ __('Recommendation') }} </label>

                                        <input type="text" class="form-control" id="useraddress"
                                            aria-describedby="useraddress" placeholder="{{ __('Recommendation') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;"
                                            name="recommendation">

                                    </div>

                                    <div class="form-group">

                                        <div id="merchant_submit_preloader" style="display: none;">
                                            <img src="{{ asset('website/images/loader2.gif') }}" alt=""> <br> <strong
                                                class="text-primary">
                                                {{ __('Your application is submitting, please wait') }} </strong>
                                        </div>

                                        <button class="btn custom-btn" id="merchant_submit_btn" type="submit"
                                            name="submit">{{ __('Submit') }} </button>

                                    </div>

                                    <input type="hidden" name="subscription_id"
                                        value="{{ $data['subscription_id'] }}">
                                    <input type="hidden" name="lang" value="{{ request('lang') }}">

                                </form>

                            </div>

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
