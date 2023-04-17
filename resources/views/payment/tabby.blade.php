<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body>

    <!-- Page Loader Start -->
    {{-- <div id="loader-wrapper">
        <div id="loader"><img loading="lazy" src="{{ env('WEBSITE_MEDIA_URL') }}/public.website/images/logo-light.png"
    alt=""
    width="" height=""></div>
    <div class="loader-section-wrap">
        <div class="loader-section"></div>
        <div class="loader-section"></div>
    </div>
    </div>
    <div class="header-menu-overlay"></div> --}}

    @include('includes/header')



    <div id="wrapper">

        <section class="login-section">
            <div class="container">
                <div class="login-box login-box-mini d-flex flex-column align-items-center justify-content-center">
                <div class="alert alert-warning p-3" id="warningmsg"><h4 class="text-danger"><b>{{__('Warning')}} : {{__('Do not Refresh or Close the Browser')}}</b></h4></div>
                    <img src="images/login-logo.png" style="width:100px;height:100px;margin-bottom:20px;display:none;"
                        id="finalize-logo" alt="" />
                    <h2 id="transaction-title">{{ __('Please wait..') }}</h2>
                    <div id="transactionSuccessDetails" class="d-none" style="width:100%;text-align:center;word-wrap:break-word;">
                        <button type="button" class="btn btn-primary btn-m-width"
                            onclick="window.location='{{route('pricing', ['lang' => request()->lang])}}';">{{__('Back to Pricing')}}</button>
                    </div>
                    <div id="transactionErrorDetails" class="d-none" style="width:100%;text-align:center;word-wrap:break-word;">
                        <button type="button" class="btn btn-primary btn-m-width"
                            onclick="window.location='{{route('checkout', ['lang' => request()->lang])}}';">{{__('Back to Checkout')}}</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('includes/footer', ['showInquiryForm' => true])
    @include('includes/footerscript')
    <script>
    let status = "";

    function printContent(status) {
        if (status == "success") {
            localStorage.clear();
            $("#transactionSuccessDetails").removeClass("d-none");
            $("#transaction-title").html("{{__('Transaction Success')}}");
            $("#warningmsg").hide();
            $("#finalize-logo").css("display", "block");
            $("#finalize-logo").attr("src", "{{asset('/images/transaction-success.png')}}");

            setTimeout(function(){
               window.location = "{{route('my_orders', ['lang' => request()->lang])}}";
            },5000);
        } else if (status == "cancel") {
            $("#transactionErrorDetails").removeClass("d-none");
            $("#finalize-logo").css("display", "block");
            $("#finalize-logo").attr("src", "{{asset('/images/transaction-failed.png')}}");
            $("#transaction-title").html("{{__('Transaction Cancelled')}}");
            $("#warningmsg").hide();
            setTimeout(function(){
               window.location = "{{route('checkout', ['lang' => request()->lang])}}";
            },5000);

        } else {
            $("#transactionErrorDetails").removeClass("d-none");
            $("#finalize-logo").css("display", "block");
            $("#finalize-logo").attr("src", "{{asset('/images/transaction-failed.png')}}");
            $("#transaction-title").html("{{__('Transaction Failed')}}");
            $("#warningmsg").hide();
            setTimeout(function(){
               window.location = "{{route('checkout', ['lang' => request()->lang])}}";
            },5000);
        }
    }

    function loadFromLocal(key) {
        if (typeof(window.localStorage) !== "undefined") {
            return localStorage.getItem(key);
        }
    }

    window.onload = function() {
        printContent("{{$paymentdetails['status']}}");
    };
    window.onbeforeunload = function() {
        location.replace(localStorage.getItem("redirect_url"));
    }
    </script>
</body>

</html>