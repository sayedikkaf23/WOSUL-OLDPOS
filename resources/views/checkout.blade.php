<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>
<head>
    @include('includes/headerscript')
    <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
    <script src="https://checkout.tabby.ai/tabby-payment-method-snippet-cci.js"></script>
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

    <section class="checkout-section">
        <div class="container">
            <div class="row">

                <div class="col-lg-4">
                    <div class="checkout-data">
                        <h2>{{__('Billing Details')}}</h2>
                        <div class="checkout-data-table">
                            <table class="table">

                            </table>
                        </div>
                        <div class="checkout-coupon">
                            <!--<div class="form-group">
                                    <label class="label">{{__('Add Discount Code')}}</label>
                                    <input type="text" placeholder="000000" class="form-control" required />
                                </div>-->
                            <div class="form-group">
                                <label class="label"> {{ __('Street') }} <span class="text-danger">*</span></label>
                                <input type="text" placeholder="{{ __('Street') }}" id="txtBillingStreet"
                                       class="form-control" required />
                                <span class="text-danger bg-white rounded border border-danger p-1 d-none" id="txtBillingStreet-error"></span>
                            </div>
                            <div class="form-group ">
                                <label class="label"> {{ __('City') }} <span class="text-danger">*</span></label>
                                <input type="text" placeholder="{{ __('City') }}" id="txtBillingCity"
                                       class="form-control" required />
                                <span class="text-danger bg-white rounded border border-danger p-1 d-none" id="txtBillingCity-error"></span>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label class="label">{{ __('Postcode') }}<span class="text-danger">*</span></label>
                                    <input type="text" placeholder="{{ __('Postcode') }}" id="txtBillingPostCode"
                                           class="form-control" required />
                                    <span class="text-danger bg-white rounded border border-danger p-1 d-none" id="txtBillingPostCode-error"></span>
                                </div>
                                <div class="col">
                                    <label class="label">{{ __('State') }}<span class="text-danger">*</span></label>
                                    <input type="text" placeholder="{{ __('State') }}" id="txtBillingState"
                                           class="form-control" required />
                                    <span class="text-danger bg-white rounded border border-danger p-1 d-none" id="txtBillingState-error"></span>
                                </div>
                            </div>
                            <input type="hidden" value="SA" name="country" id="txtBillingCountry">
                        </div>
                        <div class="checkout-data-total">
                            {{__('Total Amount')}} <b>{{__('SAR')}} 0</b>
                        </div>

                        <!--<div class="checkout-btn">
                                <button type="button" class="btn"
                                    onclick="setPaymentMethod();">{{__('Proceed To Payment')}}</button>
                            </div>-->
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="checkout-form">
                        <div class="checkout-form-head">
                            <h2>{{__('Payment Method')}}</h2>
                            <h6><img src="images/lock-icon.svg" alt="" /> {{__('Secure Server')}}</h6>
                        </div>
                        <div class="checkout-form-box">

                            <div class="checkout-tabs">
                                <div class="alert alert-info" style="display:none;" id="selectPaymentMethodAlert"></div>
                                <ul class="nav nav-tabs" id="myTab" role="tablist" style="justify-content: center;">
<!--                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab1-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1"
                                                aria-selected="true" value="VISA MASTER"
                                                onclick="setPaymentMethod('VISA MASTER');">
                                            <img src="{{asset('/images/visa-mastercard.png')}}" />
                                        </button>
                                    </li>-->
<!--                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2"
                                                aria-selected="false" value="MADA" onclick="setPaymentMethod('MADA');">
                                            <img src="{{asset('/images/mada.png')}}" />
                                        </button>
                                    </li>-->
                                    <!--                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3"
                                                aria-selected="false" value="STC_PAY"
                                                onclick="setPaymentMethod('STC_PAY');">
                                                <img src="{{asset('images/stc.png')}}" />
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab4-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4"
                                                aria-selected="false" value="APPLEPAY"
                                                onclick="setPaymentMethod('APPLEPAY');">
                                                <img src="{{asset('images/applepay.png')}}" />
                                            </button>
                                        </li>-->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab5-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4"
                                                value="TABBY" aria-selected="false" onclick="setUpTabbyForm();">
                                            <img src="{{asset('images/tabby-logo.png')}}" />
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                         aria-labelledby="tab1-tab">
                                    </div>
                                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                    </div>
                                    <!--                                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                                                                            </div>
                                                                            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                                                                            </div>-->
                                    <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab5-tab">
                                    </div>
                                    <div id="parent_paymentWidgets" class="d-none"></div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>


</div>


@include('includes/footer', ['showInquiryForm' => false])

<a href="javascript:void(0)" class="scrollToTop"><svg xmlns="http://www.w3.org/2000/svg" width="49.198"
                                                      height="78.727" viewBox="0 0 49.198 78.727">
        <path id="arrow-up-long-solid"
              d="M47.761,28.055a4.918,4.918,0,0,1-6.958,0L29.526,16.784v57a4.92,4.92,0,0,1-9.84,0v-57L8.4,28.055A4.92,4.92,0,1,1,1.446,21.1L21.127,1.416a4.918,4.918,0,0,1,6.958,0L47.766,21.1A4.923,4.923,0,0,1,47.761,28.055Z"
              transform="translate(-0.005 0.025)" fill="currentColor" />
    </svg>
</a>

@include('includes/footerscript')
<script>
    function check_for_apple_pay() {
        if (window.ApplePaySession) {
            var merchantIdentifier = 'example.com.store';
            var promise = ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier);
            promise.then(function(canMakePayments) {
                if (canMakePayments) {
                    $("#tab4-tab").show();
                }
            });
        } else {
            $("#tab4-tab").hide();
        }
    }

    function load_checkout_data() {
        let strHTML = `<tbody>`;
        let products = get_from_buy_now();
        if (products.length == 0) {
            products = get_from_storage();
        }
        if (products.length == 0) {
            window.location = "{{route('pricing', ['lang' => request()->lang])}}";
        }
        let devicecount = 0;
        let subTotal = 0;
        let taxTotal = 0;
        let total = 0;
        for (let product in products) {
            subTotal += parseFloat(products[product].amount_round);
            taxTotal += parseFloat(products[product].tax_amount_round);
            total += parseFloat(products[product].total_amount);
            if (products[product].product_type == "subscription") {
                strHTML += `<tr>
                         <td>${products[product].product_name} (${products[product].subscription_start_date})</td>
                         <td class="text-end">{{__('SAR')}} ${ products[product].amount_round}</td>
                       </tr>`;
            } else {
                strHTML += `<tr>
                         <td>${products[product].product_name}(x ${products[product].device_count})</td>
                         <td class="text-end">{{__('SAR')}} ${ products[product].amount_round}</td>
                       </tr>`;
                devicecount++;
            }
        }
        strHTML += `<tr><td><td></tr><tr style="border-top: 1px solid rgba(217, 228, 255, 0.64);">
                         <td>{{__('Sub Total')}}</td>
                         <td class="text-end">{{__('SAR')}} ${subTotal.toFixed(2)}</td>
                       </tr>
                       <tr>
                         <td>{{__('Tax')}}</td>
                         <td class="text-end">{{__('SAR')}} ${taxTotal.toFixed(2)}</td>
                       </tr>`;
        strHTML += `<tbody>`;

        /*if (parseInt(devicecount) > 0) {
            $("#tab5-tab").show();
        } else {
            $("#tab5-tab").hide();
        }*/
        total = total.toFixed(2);
        document.querySelector(".checkout-data-table table").innerHTML = strHTML;
        document.querySelector(".checkout-data-total").innerHTML = `{{__('Total Amount')}} <b>{{__('SAR')}} ${total}</b>`;
    }
    window.onload = function(event) {
        load_checkout_data();
        set_cart_view();
        check_for_apple_pay();
    }
    function removeTicks(){
        let elements = document.querySelectorAll(".nav-link");
        for(let element in elements)
        {
            elements[element].classList.remove("active");
        }
    }
    function setPaymentMethod(brand="") {
        if (brand.toLowerCase() != "tabby")
        {
            if ($("#txtBillingStreet").val() == "") {
                $('#txtBillingStreet').focus();
                $("#txtBillingStreet-error").html("{{__('Street Address cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
                removeTicks();
                return false;
            } else {
                $("#txtBillingStreet-error").html("").removeClass('d-inline-block').addClass('d-none');
            }
            if ($("#txtBillingCity").val() == "") {
                //$("#alertUser").css('display', 'block');
                document.querySelector("#txtBillingCity-error").scrollIntoView({
                    behavior: "smooth"
                });
                $("#txtBillingCity-error").html("{{__('City cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
                removeTicks();
                return false;
            } else {
                $("#txtBillingCity-error").html("").removeClass('d-inline-block').addClass('d-none');
            }
            if ($("#txtBillingPostCode").val() == "") {
                //$("#alertUser").css('display', 'block');
                document.querySelector("#txtBillingPostCode-error").scrollIntoView({
                    behavior: "smooth"
                });
                $("#txtBillingPostCode-error").html("{{__('Postcode cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
                removeTicks();
                return false;
            } else {
                $("#txtBillingPostCode-error").html("").removeClass('d-inline-block').addClass('d-none');
            }
            if ($("#txtBillingState").val() == "") {
                // $("#alertUser").css('display', 'block');
                document.querySelector("#txtBillingState-error").scrollIntoView({
                    behavior: "smooth"
                });
                $("#txtBillingState-error").html("{{__('State cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
                removeTicks();
                return false;
            } else {
                $("#txtBillingState-error").html("").removeClass('d-inline-block').addClass('d-none');
            }
            if(brand=="" && typeof($("[class='nav-link active']").val())=="undefined")
            {
                $("#selectPaymentMethodAlert").css("display","block");
                $("#selectPaymentMethodAlert").html("{{__('Please select your payment Method')}}");
            }
            else
            {
                localStorage.setItem("billing_state", $("#txtBillingState").val());
                localStorage.setItem("billing_country", $("#txtBillingCountry").val());
                localStorage.setItem("billing_postcode", $("#txtBillingPostCode").val());
                localStorage.setItem("billing_address_1", $("#txtBillingStreet").val());
                localStorage.setItem("billing_address_2", $("#txtBillingStreet").val());
                localStorage.setItem("billing_city", $("#txtBillingCity").val());

                $("#parent_paymentWidgets").removeClass('d-none');
                $("#parent_paymentWidgets").html(`<div class="text-center mb-5">
                <h4> <div class="spinner-grow mr-3" role="status">
                </div>{{__('Loading Payment Form')}}</h4>
               </div>`);
                var CSRF_TOKEN = "{{ csrf_token() }}";
                let totalamount = 0;
                let products = get_from_buy_now();
                if (products.length == 0) {
                    products = get_from_storage();
                }
                for (let product in products) {
                    totalamount += Number(Number(products[product].total_amount).toFixed(2));
                }
                totalamount = totalamount.toFixed(2);
                let formData = {
                    "brand": brand,
                    "merchant_id": parseInt("{{request()->session()->get('logged_merchant_id')}}"),
                    "access_token": CSRF_TOKEN,
                    "amount": totalamount,
                    "billingState": $("#txtBillingState").val(),
                    "billingCity": $("#txtBillingCity").val(),
                    "billingPostCode": $("#txtBillingPostCode").val(),
                    "billingCountry": $("#txtBillingCountry").val(),
                    "billingStreet": $("#txtBillingStreet").val(),
                    "products": products
                }
                $.ajax({
                    url: "/api/hyperpay/payment",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        console.log(response.script_url);
                        @if(App::getLocale() == 'ar')
                        document.querySelector("#parent_paymentWidgets").innerHTML = `<form
                                   id="paymentWidgets"
                                   class="paymentWidgets"
                                   action="{{url('/ar/finalize')}}"
                                   data-brands="${brand}"
                                   ></form>`;
                        @else
                        let brand_name = "";
                        document.querySelector(`#parent_paymentWidgets`).innerHTML = `<form
                                 id="paymentWidgets"
                                 class="paymentWidgets"
                                 action="{{url('/en/finalize')}}"
                                 data-brands="${brand}"
                                 ></form>`;
                        @endif
                        attachScriptInTheHtmlHead(response.script_url);
                    },
                    error: function(xhr) {
                        if (xhr.status.toString().startsWith("5")) {
                            if (typeof(xhr.responseJSON.message) !== "undefined" && xhr.responseJSON.message ==
                                "") {
                                alert("some error have occured");
                            } else {
                                alert(xhr.responseJSON.message);
                            }
                        }
                    }
                });
            }
        } else {
            setUpTabbyForm();
        }
    }

    function attachScriptInTheHtmlHead(script_url) {
        /*if(devicescount>0)
        {*/
        let scripttag = document.createElement('script');
        scripttag.innerHTML = `var wpwlOptions = {
                                               style: "plain",
                                               maskCvv:true,
                                               paymentTarget:"_top",
                                               applePay: {
                                                supportedNetworks:["mada","visa","masterCard"],
                                                supportedCountries: ["SA"],
                                                merchantCapabilities: "capability3DS"
                                                },
                                                 onReady: function() {
                                                    $(".wpwl-group-cardNumber").after($(".wpwl-group-brand").detach());
        $(".wpwl-group-cvv").after( $(".wpwl-group-cardHolder").detach());
         $(".wpwl-button-pay").addClass("btn btn-primary btn-block");
          }
                                             }`;
        document.head.appendChild(scripttag);
        // }
        /*else
                                            {
                                                let scripttag = document.createElement('script');
                     scripttag.innerHTML = `var wpwlOptions = {
                                               style: "plain",
                                               maskCvv:true,
                                               paymentTarget:"_top",
                                               onReady: function() {
                                                    $(".wpwl-group-cardNumber").after($(".wpwl-group-brand").detach());
        $(".wpwl-group-cvv").after( $(".wpwl-group-cardHolder").detach());
         $(".wpwl-button-pay").addClass("btn btn-primary btn-block");
            var customerPhoneHtml = '<div class="wpwl-label wpwl-label-custom" style="display:inline-block">Phone Number  </div>' +
              '<div class="wpwl-wrapper wpwl-wrapper-custom" style="display:inline-block">' +
              '<input type="text" name="customer.phone" value="${merchant_phone}" class="wpwl-control wpwl-control-custom" style="margin-bottom:10px;" required="required" disabled />' +
              '</div>';
            $('form.wpwl-form-card').find('.wpwl-button').before(customerPhoneHtml);
          }
                                            }`;
                                            document.head.appendChild(scripttag);
                                        }*/
        let scriptTag = document.createElement("script");
        scriptTag.setAttribute("src", script_url);
        scriptTag.setAttribute("id", "hyperpay_script");
        document.head.appendChild(scriptTag);
    }

    function addTabbyScript() {
        let scripttag = document.createElement("script");
        let totalamount = 0;
        let products = get_from_buy_now();
        if (products.length == 0) {
            products = get_from_storage();
        }
        for (let product in products) {
            totalamount += Number(Number(products[product].total_amount).toFixed(2));
        }
        totalamount = totalamount.toFixed(2);
        scripttag.innerHTML = `
                new TabbyPaymentMethodSnippetCCI({
                  selector: '#TabbyPromo',
                  currency: 'SAR',
                  price:${totalamount},
                  lang: '{{App::getLocale()}}'
                });`;
        document.head.appendChild(scripttag);
    }

    function setUpTabbyForm() {
        if ($("#txtBillingStreet").val() == "") {
            $('#txtBillingStreet').focus();
            $("#txtBillingStreet-error").html("{{__('Street Address cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
            removeTicks();
            return false;
        } else {
            $("#txtBillingStreet-error").html("").removeClass('d-inline-block').addClass('d-none');
        }
        if ($("#txtBillingCity").val() == "") {
            //$("#alertUser").css('display', 'block');
            document.querySelector("#txtBillingCity-error").scrollIntoView({
                behavior: "smooth"
            });
            $("#txtBillingCity-error").html("{{__('City cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
            removeTicks();
            return false;
        } else {
            $("#txtBillingCity-error").html("").removeClass('d-inline-block').addClass('d-none');
        }
        if ($("#txtBillingPostCode").val() == "") {
            //$("#alertUser").css('display', 'block');
            document.querySelector("#txtBillingPostCode-error").scrollIntoView({
                behavior: "smooth"
            });
            $("#txtBillingPostCode-error").html("{{__('Postcode cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
            removeTicks();
            return false;
        } else {
            $("#txtBillingPostCode-error").html("").removeClass('d-inline-block').addClass('d-none');
        }
        if ($("#txtBillingState").val() == "") {
            // $("#alertUser").css('display', 'block');
            document.querySelector("#txtBillingState-error").scrollIntoView({
                behavior: "smooth"
            });
            $("#txtBillingState-error").html("{{__('State cannot be empty')}}").removeClass('d-none').addClass('d-inline-block');
            removeTicks();
            return false;
        } else {
            $("#txtBillingState-error").html("").removeClass('d-inline-block').addClass('d-none');
        }

        localStorage.setItem("billing_state", $("#txtBillingState").val());
        localStorage.setItem("billing_country", $("#txtBillingCountry").val());
        localStorage.setItem("billing_postcode", $("#txtBillingPostCode").val());
        localStorage.setItem("billing_address_1", $("#txtBillingStreet").val());
        localStorage.setItem("billing_address_2", $("#txtBillingStreet").val());
        localStorage.setItem("billing_city", $("#txtBillingCity").val());
        addTabbyScript();
        $("#card_brand_image").attr("src", "{{asset('images/tabby-logo.png')}}");
        $("#parent_paymentWidgets").removeClass('d-none');
        document.querySelector("#parent_paymentWidgets").innerHTML = `
                                                                            <div id="TabbyPromo" class="mt-4"></div>
                                                                            <div id="tabbyErrorMsg" class="alert alert-danger d-none"></div>
                                                                            <form action="" method="POST" class="mt-4">
                                                                                  <div class="row">
                                                                                  <div class="col-12 md-12"><h4>Tabby's Account Details</h3></div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Name" class="form-label text-muted">Name</label>
                                                                                      <input type="text" class="form-control" name="merchant_name" id="tabby_name" placeholder="Name" aria-label="Name">
                                                                                      <span id="merchant-name-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Merchant Email" class="form-label text-muted">Email</label>
                                                                                      <input type="email" class="form-control" name="merchant_email" id="tabby_email" placeholder="Email" aria-label="Email">
                                                                                      <span id="merchant-email-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Number" class="form-label text-muted">Phone Number</label>
                                                                                      <input type="tel" class="form-control" name="merchant_number" id="tabby_number" placeholder="Phone Number" aria-label="Phone Number">
                                                                                      <span id="merchant-phone-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <input type="button" class="btn btn-primary btn-block" onclick="tabbyPayment()" value="{{__('Pay now')}}" />
                                                                                      <!--<input type="button" class="btn btn-primary" onclick="tabbyNewPaymentRegister()" value="New to tabby? Please Register"/>-->
                                                                                  </div>
                                                                              </form>`;
    }

    $("#tabby_name").on("input", function() {
        $("#merchant-name-error").html("");
    });
    $("#tabby_email").on("input", function() {
        $("#merchant-email-error").html("");
    });
    $("#tabby_number").on("input", function() {
        $("#merchant-phone-error").html("");
    });

    function tabbyPayment() {
        $("#tabbyErrorMsg").addClass('d-none');
        let validate = true;
        $("#merchant-name-error").html("");
        $("#merchant-email-error").html("");
        $("#merchant-phone-error").html("");
        if ($("#tabby_name").val() == "") {
            $("#merchant-name-error").html("{{__('Name required')}}");
            validate = false;
        }
        if ($("#tabby_email").val() == "") {
            $("#merchant-email-error").html("{{__('Email required')}}");
            validate = false;
        }
        if ($("#tabby_number").val() == "") {
            $("#merchant-phone-error").html("{{__('Phone number required')}}");
            validate = false;
        }
        if (validate == false) {
            return false;
        }
        let totalamount = 0;
        let products = get_from_buy_now();
        if (products.length == 0) {
            products = get_from_storage();
        }
        for (let product in products) {
            totalamount += Number(Number(products[product].total_amount).toFixed(2));
        }
        totalamount = totalamount.toFixed(2);
        var CSRF_TOKEN = "{{ csrf_token() }}";
        let formData = {
            "merchant_email": $("#tabby_email").val(),
            "merchant_name": $("#tabby_name").val(),
            "merchant_phone": $("#tabby_number").val(),
            "merchant_dob": "1985-01-01",
            "billingState": $("#txtBillingState").val(),
            "billingCity": $("#txtBillingCity").val(),
            "billingPostCode": $("#txtBillingPostCode").val(),
            "billingCountry": $("#txtBillingCountry").val(),
            "billingStreet": $("#txtBillingStreet").val(),
            "language": "{{App::getLocale()}}",
            "totalamount": totalamount,
            "products": products,
            "access_token": CSRF_TOKEN
        }
        $.ajax({
            url: "/api/tabby/payment",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                //console.log(response);
                if (typeof(response.url) !== "undefined") {
                    window.location = response.url;
                } else {
                    alert("some error have occured");
                }
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
                $("#tabbyErrorMsg").html(xhr.responseJSON.message);
                $("#tabbyErrorMsg").removeClass('d-none');
            },
        });
    }
</script>

</body>

</html>