<!DOCTYPE html>
<html>

<head>
    @include('includes/headerscript')
</head>

<body>
    @include('includes/header')

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">

            <div class="wosul-form-bg" style="min-height: 150px;">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">

                                {{ __('My Profile') }}
                                <img src=" {{ asset('public/images/about-right.png') }}"
                                    style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            <div class="wosul-form-content">

                <div class="container">

                    <br>
                    <br>
                    <div class="row mt-5 pt-5">

                        <div class="col-12">
                            <h2> Welcome, <span
                                    class="text-primary display-5">{{ session('logged_merchant_name') }}</span> </h2>

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
