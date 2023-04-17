<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body class="pt-0">
    <div id="wrapper">

        @include('includes/header')

        <section class="login-section">
            <div class="login-logo"><img src="images/login-logo.png" alt="" /></div>
            <div class="container mt-5">
                <div class="login-box">
                    <h2>{{ __('Sign up to Account') }}</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-5 mb-5">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success mt-5 mb-5">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="col-md-12">
                        <?php if (isset($success) && $success != "") { ?>
                        <div class="alert alert-success text-center"><strong><?php echo $success; ?></strong></div>
                        <?php } else if (isset($error) && $error != "") { ?>
                        <div class="alert alert-danger text-center"><strong><?php echo $error; ?></strong></div>
                        <?php }  ?>
                    </div>



                    <form action="{{ route('save_register_merchant', app()->getLocale()) }}" method="POST">
                        @csrf

                        <h4>{{ __('Owner’s information') }}</h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="label">{{ __('Your Name') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Your Name') }}" class="form-control"
                                        required name="name" />
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="label">{{ __('Phone Number') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Phone Number') }}" class="form-control"
                                        required name="phone_number" />
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="label">{{ __('E-mail') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('E-mail') }}" class="form-control"
                                        required name="email" id="email" />
                                    <label for="" class="text-danger email-exists small pt-2"
                                        style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>
                                </div>
                            </div>

                        </div>
                        <h4>{{ __('Business’s information') }}</h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Company URL') }}</label>
                                    <div class="form-company-group">
                                        <input type="text" placeholder="{{ __('Company URL') }}"
                                            class="form-control" required name="company_url" id="company_url" />
                                        <div class="company-url-label">.wosul.sa</div>
                                        <label for="" class="text-danger company-url-available small pt-2"
                                            style="display:none;"><strong>{{ __('Not Available') }}</strong></label>
                                        <label for="" class="text-success company-url-not-available small pt-2"
                                            style="display:none;"><strong>{{ __('Available') }}</strong></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Company Name') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Company Name') }}" class="form-control"
                                        required name="company_name" />
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Referral Code') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Referral Code') }}" class="form-control"
                                        required name="referral_code" />
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Recommendation') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Recommendation') }}"
                                        class="form-control" required name="recommendation" />
                                </div>
                            </div>

                        </div>
                        <div class="custom-checkbox">
                            <label>
                                <input type="checkbox" />
                                <span>{{ __('Accept') }} <a href="#">{{ __('Terms and Conditions') }}</a></span>
                            </label>
                        </div>
                        <div class="form-submit-wrap text-center">
                            <button type="submit" class="btn btn-primary btn-m-width">{{ __('Sign Up') }}</button>
                            <p>{{ __('Have an account?') }} <a href=""><b><u>{{ __('Login') }}</u></b></a></p>
                        </div>
                        <input type="hidden" name="lang" value="{{ request('lang') }}">

                    </form>
                </div>
            </div>
        </section>



    </div>

    @include('includes/footerscript')

    <script>
        $('#email').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var email = $('#email').val();

            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('email', email);

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
                        $('#email').css('color', 'red');
                    } else {
                        $('.email-exists').hide();
                        $('#email').css('color', 'inherit');
                    }
                }
            });

        });

        $('#company_url').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var company_url = $('#company_url').val();

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
                        $('#company_url').css('color', 'red');
                        $('.company-url-available').show();
                        $('.company-url-not-available').hide();
                    } else {
                        $('.company-url-available').hide();
                        $('.company-url-not-available').show();
                        $('#company_url').css('color', 'green');
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
