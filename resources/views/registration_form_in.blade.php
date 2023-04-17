<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
    <style>
        @media (max-width:767px){
            .title h1 {
                line-height: 26px !important;
                margin-bottom: 0px !important;
                font-size: 20px;
            }
            .title p {
                font-size: 15px !important;
                line-height: 22px !important;
                text-align: center;
                padding-top: 8px !important;
            }
            .p-2-sm{
                padding: 20px !important;
            }
            .header .logo img {
                width: 150px !important;
            }
            .header {
                align-items: center;
            }
            .m-5-sm {
                margin: 10px !important;
            }
            .mb-sm-0 {
                margin-bottom: 0 !important;
            }
            .saudi-zakat-text {
                font-size: 15px !important;
                line-height: 22px;
            }
            .pt-2-sm {
                padding-top: 20px !important;
            }
            .pt-3-sm {
                padding-top: 20px !important;
                padding-bottom: 20px !important;
            }
            .pt-0-sm{
                padding-top: 0 !important;
            }
            .form-group {
                margin-bottom: 15px;
            }
            html .btn {
                padding: 0 35px !important;
                line-height: 44px;
                font-size: 14px;
                min-width: 90px;
                width: auto !important;
            }
            .at_head {
                margin-bottom: -20px;
                font-size: 22px !important;
            }
            section{
                padding-bottom: 30px;
            }
        }
    </style>
</head>

<body class="p-0 m-0">

<div id=" pt-0 mt-0 p-0">


    <section class="pt-md-5 pt-0-sm">
        <div class="container">

            <div class="row p-5 p-2-sm pt-0 header">
                <div class="col-6 col-md-10">
                    <a href="/{{ app()->getLocale() }}" class="logo">
                        <img loading="lazy"
                             style="width: 250px;"
                             src="{{ env('WEBSITE_MEDIA_URL') . '/storage/website_setting/' . $header_logo }}" />
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <div class="multi-language">
                        <select class="customSelect switch-language">
                            <option @if (App::getLocale() == 'en') selected @endif value="en">English
                            </option>
                            <option @if (App::getLocale() == 'ar') selected @endif value="ar"> عربي</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="error-string"></div>

            <div>

                @csrf

                <div class="row p-5 p-2-sm">
                    <div class="col-12 col-md-7 title">
                        <h1 style="line-height:54px;font-weight:400;margin-bottom: 24px;">  {{ __('Wosul provide technical selling solutions that moving your business to a wider range.') }} </h1>
                        <p style="font-size:25px;line-height:36px;" class="fw-light pt-3">{{ __('Cloud vending systems with fully management of your business activities from just one screen.') }} </p>
                    </div>
                    <div class="col-12 col-md-5">

                        <div class="form-group">
                            <input type="text" id="name" class="form-control  border  border-dark " required placeholder=" {{ __('Name') }} " >
                        </div>

                        <div class="form-group">
                            <input type="number" id="phone_number" class="form-control  border  border-dark" required placeholder=" {{ __('Phone Number') }} " >
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" class="form-control  border  border-dark" required placeholder=" {{ __('Email') }} " >
                        </div>

                        <div class="form-group">
                            <input type="text" id="business_type" class="form-control  border  border-dark" required placeholder=" {{ __('Business Type') }} " >
                        </div>

                        {{-- <div class="form-group">
                            <select name="" id="number_of_branch" class="form-control border  border-dark" required>
                                <option selected value="">{{ __('Number of Branches') }}</option>
                                <option value="1">1</option>
                                <option value="from 2-5">{{ __('from 2 to 5') }}</option>
                                <option value="from 6-10">{{ __('from 6 to 10') }}</option>
                                <option value="more than 11">{{ __('more than 11') }}</option>
                            </select>
                        </div> --}}

                        <div class="form-group text-center mb-sm-0">
                            <button type="button" onclick="submit()" class="btn btn-danger w-75 m-5 p-2 m-5-sm  btn-submit">{{ __('Contact us!') }} </button>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row text-center">

                <div class="col-1"></div>
                <div class="col-10 rounded-3 pt-5 text-center pt-2-sm" style="background-color:rgba(245,245,245,1);border:1px solid rgba(0,0,0,0.1)">
                    <p style="font-size:28px;color:rgb(42, 69, 80);" class="at_head">{{ __('Authorized From') }}</p>
                    <img style="width: 597px;height: 144px;" src="{{ asset('website/images/zatca-logo-darker.svg') }} " />

                    <div class="row ">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <p class="pb-4 saudi-zakat-text"   style="font-size:28px;color:rgb(156, 156, 156);">{{ __('The Saudi Zakat, Tax and Customs Authority has approved wosul as a sales system and a compatible e-invoiceing provider') }}</p>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>



</div>



@include('includes/footerscript')

<script>

    function submit() {

        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

        let formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append('name', $('#name').val());
        formData.append('phone_number', $('#phone_number').val());
        formData.append('email', $('#email').val());
        formData.append('business_type', $('#business_type').val());
        formData.append('type', 2);
        // formData.append('number_of_branch', $('#number_of_branch').val());

        $.ajax({
            url: "{{ route('save_registration_form') }}",
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function(resp) {

                if (resp.status_code == 200) {
                    $('.btn-submit').addClass('disabled');
                    $('.error-string').removeClass('mb-5 alert alert-danger');
                    $('.error-string').addClass('mb-5 alert alert-success');
                    $('.error-string').html(resp.msg);
                    // $('.btn-reload').css('display','block');
                }

                if (resp.status_code != 200) {

                    let error_json = JSON.parse(resp.msg);
                    var error_string = '';
                    $.each(error_json, (key, value) => {
                        error_string += value[0] + '<br>';
                    });

                    $('.error-string').addClass('mb-5 alert alert-success');
                    $('.error-string').addClass('mb-5 alert alert-danger');
                    $('.error-string').html(error_string);
                }

            }
        });



    }

</script>

</body>


</html>
