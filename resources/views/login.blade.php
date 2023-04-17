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
            <div class="container">
                <div class="login-box login-box-mini">
                    <h2>{{ __('Sign in to Account') }}</h2>
                    @include('components.message')
                    <form action="{{ route('authenticate', app()->getLocale()) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="label">{{ __('Your Name') }} <em>*</em></label>
                            <div class="form-group-icon">
                                <input type="text" placeholder="{{ __('Your Name') }}*" name="email"
                                    class="form-control" required />
                                <div class="icon"><img src="images/field-user.svg" alt="" /></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">{{ __('Password') }}<em>*</em></label>
                            <div class="form-group-icon">
                                <input type="password" placeholder="{{ __('Password') }}*" name="password"
                                    class="form-control" required />
                                <div class="icon"><img src="images/field-password.svg" alt="" /></div>
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <div class="custom-checkbox">
                                    <label>
                                        <input type="checkbox" />
                                        <span>{{ __('Remember Me') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a
                                    href="{{ route('forgot_password', app()->getLocale()) }}"><u>{{ __('Forgot Password?') }}</u></a>
                            </div>
                        </div>
                        <div class="form-submit-wrap text-center">
                            <input type="hidden" id="txtFromPaymentForm" name="from_payment" />
                            <button type="submit" class="btn btn-primary btn-m-width">{{ __('Submit') }}</button>
                            <p>{{ __('Don’t have an account yet?') }} 

                                @if($data['enable_payment_gateway'])
                                {{-- with payment gateway --}}
                                <a href="{{ route('merchant_register', app()->getLocale()) }}"><b><u>{{ __('Sign up') }}</u></b></a>
                                @else
                                {{-- without payment gateway --}}
                                <a href="{{ route('pricing', app()->getLocale()) }}"><b><u>{{ __('Sign up') }}</u></b></a>
                                @endif

                            </p>
                            <p>
                                @if(app()->getLocale()=='ar')
                                    {{ 'للتسجيل لتجربة مجانية للنظام لمدة  '.$data['free_trial_days'].'  أيام' }}
                                @else
                                    {{ 'Signup for a free trial '.$data['free_trial_days'].' days!' }}
                                @endif
                                {{--{{ __('Do you need a Free trial for '.$data['free_trial_days'].' days “No credit card required”') }}--}}
                                @if($data['enable_payment_gateway'])
                                    <a href="{{ route('merchant_register', app()->getLocale()) }}?trial=1"><b><u>{{ __('Try Now') }}</u></b></a>
                                @else
                                    <a href="{{ route('pricing', app()->getLocale()) }}?trial=1"><b><u>{{ __('Try Now') }}</u></b></a>
                                @endif
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>



    </div>

    @include('includes/footerscript')
    <script>
        if (typeof(localStorage) !== "undefined") {
            console.log(localStorage.getItem("from_payment"))
            $("#txtFromPaymentForm").val(localStorage.getItem("from_payment"));
        }
    </script>
</body>

</html>
