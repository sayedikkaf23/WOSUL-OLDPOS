<!doctype html>
<html lang="en">

<head>
    @include('includes/headerscript')
</head>

<body class="pt-0">

    <div id="wrapper">


        <section class="login-section">
            <div class="login-logo"><img src="images/login-logo.png" alt="" /></div>
            <div class="container">
                <div class="login-box login-box-mini">
                    <h2>{{ __('Forgot Password') }}</h2>
                    <form action="{{ route('save_register_merchant', app()->getLocale()) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="label">{{ __('Email Address') }} <em>*</em></label>
                            <div class="form-group-icon">
                                <input type="email" placeholder="{{ __('Enter your email') }}" class="form-control" required />
                                <div class="icon"><img src="images/field-user.svg" alt="" /></div>
                            </div>
                            <p>{{ __('Lost your password? Please enter your email address to receive a link to create a new password via email.') }}</p>
                        </div>
                        <div class="form-submit-wrap text-center">
                            <button type="submit" class="btn btn-primary btn-m-width">{{ __('Reset Password')}}</button>
                            <p>{{ __('Return to Sign In?') }} <a href="{{ route('login',app()->getLocale()) }}"><b><u>{{ __('Sign In')}}</u></b></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </section>



    </div>

    @include('includes/footerscript')

</body>

</html>
