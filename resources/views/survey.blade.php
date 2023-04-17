<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-177630217-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-177630217-1');
    </script>

    <title>WOSUL</title>

    @include('includes/headerscript')

</head>

<body>

    @include('includes/header')

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">
            <div class="pricing-bg faq-bg">

                <div class="container pt-5">

                    <div class="col-lg-12 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <h2 class="text-uppercase mt-4 mb-0 text-white">
                                {{ __('Survey Form') }}</h2>
                        </div>
                    </div>


                    <div class="col-md-12 col-lg-12">
                        <?php if (isset($success) && $success != "") { ?>
                        <div class="alert alert-success text-center"><strong><?php echo $success; ?></strong></div>
                        <?php } else if (isset($error) && $error != "") { ?>
                        <div class="alert alert-danger text-center"><strong><?php echo $error; ?></strong></div>
                        <?php }  ?>
                    </div>



                </div>
            </div>
            <div class="row justify-content-center pt-5">
                <div class="card col-lg-6" style="background: white;
                box-shadow: 1px 1px 5px;">


                    @include('components.message')

                    <div class="form-content-wrapper">

                        <form method="POST" class="mt-4 mb-3 px-3" style="padding: 50px;">

                            <div class="form-row px-4" style="padding-top: 50px;">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">{{ __('Full Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input required type="text" class="form-control" id="name" name="name"
                                            aria-describedby="username" placeholder="{{ __('Full Name') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <label for="usernumber">{{ __('Phone Number') }} <span
                                                class="text-danger">*</span></label>

                                        <input required type="text" class="form-control" id="phone_number"
                                            aria-describedby="usernumber" placeholder="{{ __('Phone Number') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;"
                                            name="phone_number">

                                    </div>
                                </div>
                            </div>
                            <div class="form-row px-4">
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <label for="useremail">{{ __('E-mail') }} <span
                                                class="text-danger">*</span></label>

                                        <input required type="email" class="form-control" id="email"
                                            aria-describedby="useremail" placeholder="{{ __('E-mail') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;" name="email">
                                        <label for="" class="text-danger email-exists"
                                            style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>

                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <label for="usercompany">{{ __('City') }}<span
                                                class="text-danger">*</span></label>

                                        <input required type="text" class="form-control" id="city"
                                            aria-describedby="usercompany" placeholder="{{ __('City') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;" name="city">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <label for="business_domain">{{ __('Business Domain') }}<span
                                                class="text-danger">*</span></label>

                                        <input required type="text" class="form-control" id="business_domain"
                                            aria-describedby="business_domain"
                                            placeholder="{{ __('Business Domain') }}" hidefocus="true"
                                            style="outline: currentcolor none medium;" name="business_domain">

                                    </div>
                                </div>
                            </div>

                            <div class="form-row justify-content-center mb-4">

                                <div id="merchant_submit_preloader" style="display: none;">
                                    <img src="{{ asset('website/images/loader2.gif') }}" alt=""> <br>
                                    <strong class="text-primary">
                                        {{ __('Your application is submitting, please wait') }} </strong>
                                </div>

                                <button id="btn_submit"
                                    class="btn btn-primary btn-sm text-uppercase mt-2 px-4 py-1">{{ __('Submit') }}
                                </button>
                            </div>

                            <input type="hidden" name="lang" value="{{ request('lang') }}">

                        </form>
                    </div>

                </div>
            </div>

        </section>
    </div>

    @include('includes/footer')
    @include('includes/footerscript')

    <script>
        $('#btn_submit').on('click', function() {

            let name = $('#name').val();
            let phone_number = $('#phone_number').val();
            let email = $('#email').val();
            let city = $('#city').val();
            let business_domain = $('#business_domain').val();


            if (name != '' && phone_number != '' && email != '' && city != '' && business_domain != '') {

                var formData = new FormData();
                formData.append('name', name);
                formData.append('phone_number', phone_number);
                formData.append('email', email);
                formData.append('city', city);
                formData.append('business_domain', business_domain);

                $.ajax({
                    url: "{{ route('save_survey') }}",
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: formData,
                    success: function(data) {

                        alert(data.message);

                        if (data.status) {
                            $('#name').val('');
                            $('#phone_number').val('');
                            $('#email').val('');
                            $('#city').val('');
                            $('#business_domain').val('');
                        }
                    }
                });
            }

        });
    </script>

</body>

</html>
