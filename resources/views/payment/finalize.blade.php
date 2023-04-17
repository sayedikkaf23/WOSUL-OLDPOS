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
                        <p>Amount : <span id="transaction-amount"></span></p>
                        <p>Currency : <span id="transaction-currency"></span></p>
                        <p>Customer Name : <span id="transaction-name"></span></p>
                        <p>Customer Email : <span id="transaction-email"></span></p>
                        <p>Message : <span id="transaction-message"></span></p>
                        <button type="button" class="btn btn-primary btn-m-width"
                            onclick=`window.location="{{route('pricing', ['lang' => request()->lang])}}"
                            ;`>{{__('Back to Pricing')}}</button>
                    </div>
                    <div id="transactionErrorDetails" class="d-none" style="width:100%;text-align:center;word-wrap:break-word;">
                        <div id="transaction_failed_message"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('includes/footer', ['showInquiryForm' => true])
    @include('includes/footerscript')
    <script>
    let status = "";

    function loadProducts() {
        let productlist = [];
        if (typeof(window.localStorage) !== "undefined") {
            if (localStorage.getItem("products")) {
                productlist = JSON.parse(localStorage.getItem("products"));
            }
        }
        return productlist;
    }

    function loadFromLocal(key) {
        if (typeof(window.localStorage) !== "undefined") {
            return localStorage.getItem(key);
        }
    }

    function setUpOrders(responsedata) {
        var CSRF_TOKEN = "{{ csrf_token() }}";
        let formData = {
            "id": '{{ $id }}',
            "resourcePath": '{{ $resourcePath}}',
            "delivery_state": loadFromLocal("billing_state"),
            "delivery_country": loadFromLocal("billing_country"),
            "delivery_zipcode": loadFromLocal("billing_postcode"),
            "delivery_address_1": loadFromLocal("billing_address_1"),
            "delivery_address_2": loadFromLocal("billing_address_2"),
            "delivery_city": loadFromLocal("billing_city"),
            "card_details": (typeof(responsedata["card"]) !== "undefined") ? JSON.stringify(responsedata["card"]) :
                "",
            "access_token": CSRF_TOKEN
        }
        $.ajax({
            url: '/api/hyperpay/add_to_orders',
            type: "POST",
            data: formData,
            success: function(response) {
                localStorage.clear();
                $("#transactionSuccessDetails").removeClass("d-none");
                $("#finalize-logo").css("display", "block");
                $("#finalize-logo").attr("src", "{{asset('/images/transaction-success.png')}}");
                if (typeof(responsedata.amount) !== "undefined") {
                    $("#transaction-amount").html(responsedata.amount);
                } else {
                    $("#transaction-amount").html("unknown");
                }
                if (typeof(responsedata.currency) !== "undefined") {
                    $("#transaction-currency").html(responsedata.currency);
                } else {
                    $("#transaction-currency").html("unknown");
                }
                if (typeof(responsedata.customer) !== "undefined") {
                    $("#transaction-name").html(responsedata.customer.givenName);
                } else {
                    $("#transaction-name").html("unknown");
                }
                if (typeof(responsedata.customer) !== "undefined") {
                    $("#transaction-email").html(responsedata.customer.email);
                } else {
                    $("#transaction-email").html("unknown");
                }
                if (typeof(responsedata.message) !== "undefined") {
                    $("#transaction-message").html(responsedata.message);
                } else {
                    $("#transaction-message").html("unknown");
                }
                $("#transaction-title").html("{{__('Transaction Success')}}");
                $("#warningmsg").hide();
                setTimeout(function() {
                    window.location = "{{route('my_orders', ['lang' => request()->lang])}}";
                }, 5000);

            },
            error: function(response) {
                response = response.responseJSON;
                console.error(response);
                if (typeof(response.status) !== "undefined" && response.status.toString().startsWith("5")) {
                    if (typeof(response.responseJSON.message) !== "undefined" && response.responseJSON
                        .message == "") {
                        alert("some error have occured");
                    } else {
                        alert(response.responseJSON.message);
                    }
                } else {
                    if (typeof(response.message) !== "undefined") {
                        alert(response.message);
                    }
                }
            }
        });
    }

    function checkPaymentStatus() {
        var CSRF_TOKEN = "{{ csrf_token() }}";
        let formData = {
            "id": '{{ $id }}',
            "resourcePath": '{{ $resourcePath}}',
            "access_token": CSRF_TOKEN
        }
        $.ajax({
            url: '/api/hyperpay/payment-status',
            type: "POST",
            data: formData,
            success: function(response) {
                setUpOrders(response);
            },
            error: function(response) {
                if (typeof(response.status) !== "undefined" && response.status.toString().startsWith("5")) {
                    if (typeof(response.responseJSON.message) !== "undefined" && response.responseJSON
                        .message == "") {
                        alert("some error have occured");
                    } else {
                        alert(response.responseJSON.message);
                    }
                } else {
                    if (typeof(response.message) !== "undefined") {
                        alert(response.message);
                    }
                }
                response = response.responseJSON;

                $("#transactionErrorDetails").removeClass("d-none");
                let strHTML = `<p>The Following errors have occured : </p>`;
                if (typeof(response.errors) !== "undefined" && typeof(response.errors.message) !==
                    "undefined") {
                    for (let error in response.errors.message) {
                        strHTML += `<p>${response.errors.message[error]}</p>`;
                    }
                } else {
                    strHTML += `<p>${response.message}</p>`;
                }
                strHTML +=
                    `<button type="button" class="btn btn-primary btn-m-width" onclick="window.location='{{route('checkout', ['lang' => request()->lang])}}';">{{__('Back to Checkout')}}</button>`;
                $("#transaction_failed_message").html(strHTML);
                $("#finalize-logo").css("display", "block");
                $("#finalize-logo").attr("src", "{{asset('/images/transaction-failed.png')}}");
                $("#transaction-title").html("{{__('Transaction Failed')}}");
                $("#warningmsg").hide();
                setTimeout(function() {
                    window.location = "{{route('checkout', ['lang' => request()->lang])}}";
                }, 1000);
            }
        });
    }
    window.onload = function() {
        checkPaymentStatus();
    };
    window.onbeforeunload = function() {
        location.replace(localStorage.getItem("redirect_url"));
    }
    </script>
</body>

</html>