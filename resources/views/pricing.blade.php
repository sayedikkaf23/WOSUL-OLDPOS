<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body>

    <!-- Page Loader Start -->
    <div id="loader-wrapper">
        <div id="loader"><img loading="lazy" src="images/logo-light.png" alt="" width="" height=""></div>
        <div class="loader-section-wrap">
            <div class="loader-section"></div>
            <div class="loader-section"></div>
        </div>
    </div>
    <div class="header-menu-overlay"></div>

    @include('includes/header')
    <div id="wrapper">

        <section class="bg-gradient inner-banner">
            <div class="inner-banner-logo-light">
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7">
                        <div class="content">
                            <h2 data-aos="fade-up">{{ __('Pricing Plans') }}</h2>
                            <p data-aos="fade-up">{{ __('See all of our plans and devices including POS and POS Devices') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="plan-bg-shape"></div>
            <div class="container postition-static">
                <div class="plans-tabs">
                    {{-- <div class="plans-tab-nav">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="Subscriptions-tab" data-bs-toggle="tab"
                                    data-bs-target="#Subscriptions" type="button" role="tab"
                                    aria-controls="Subscriptions" aria-selected="true">Subscriptions</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="Devices-tab" data-bs-toggle="tab"
                                    data-bs-target="#Devices" type="button" role="tab" aria-controls="Devices"
                                    aria-selected="false">Devices</button>
                            </li>
                        </ul>
                    </div> --}}

                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <h2 class="aos-init aos-animate">{{ __('Subscriptions') }}</h2>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Subscriptions" role="tabpanel"
                            aria-labelledby="Subscriptions-tab">
                          
                            <div class="achive-box-wrap">
                                <div class="row justify-content-center gx-lg-4">
                                    
                                    @forelse($data['subscriptions'] as $subscription)
                                    <div class="col-md-6 col-lg-3 py-2" data-aos="fade-up">
                                        <div class="achive-box h-100">
                                            <div class="text">
                                                <h3>{{ App::getLocale() == 'en' ? $subscription->title : $subscription->title_ar }}</h3>
                                                <ul>
                                                    @isset($subscription->features)
                                                        @foreach ($subscription->features as $feature)
                                                        <li>{{ App::getLocale() == 'en' ? $feature->title : $feature->title_ar }}</li>
                                                        @endforeach
                                                    @endisset
                                                </ul>
                                                <h5>{{ App::getLocale() == 'en' ? $subscription->short_description : $subscription->short_description_ar }}
                                                </h5>
                                                <h6>{{ round($subscription->amount, 0) }} SAR <small>/ Annually</small></h6>
                                            </div>
                                            <div class="action">
                                                @if($data['enable_payment_gateway'])
                                            {{-- with payment gateway --}}
                                            <a href="javascript:;" class="btn btn-outline-primary w-100 mb-2" style="color:#C90880;"
                                                onclick="open_modal('{{$subscription->id}}','subscription',{{$subscription->subscription_type}});"
                                                id="btn_add_to_cart_subscription_{{$subscription->id}}">{{ __('ADD TO CART') }}</a>

                                            {{-- <a href="javascript:;" class="btn btn-primary w-100"
                                                onclick="buy_product_now('{{$subscription->id}}','subscription');">{{ __('BUY NOW') }}</a> --}}
                                        @else
                                        
                                            {{-- without payment gateway --}}
                                            @if(Request::get('trial')==1)
                                                <a href="{{ url(app()->getLocale() . '/merchant/register/' . $subscription->id) }}?trial=1" class="btn btn-primary w-100">{{__('BUY NOW')}}</a>
                                            @else
                                                <a href="{{ url(app()->getLocale() . '/merchant/register/' . $subscription->id) }}" class="btn btn-primary w-100">{{__('BUY NOW')}}</a>
                                            @endif

                                        @endif
                                                </div>
                                        </div>
                                    </div>
                                    @empty
                                        <div class="alert alert-warning">
                                            {{ __('There are not featured packages available') }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>

                    </div>

                    @if($data['enable_payment_gateway'])
                    {{-- devices: display only if payment gateway is enabled --}}
                    <div class="row mb-5 mt-5">
                        <div class="col-12 text-center">
                            <h2 class="aos-init aos-animate">{{ __('Devices') }}</h2>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Devices" role="tabpanel"
                            aria-labelledby="Devices-tab">
                        
                            <div class="achive-box-wrap">
                                <div class="row justify-content-center gx-lg-4">
                            @forelse($data['devices'] as $device)
                            <div class="col-md-6 col-lg-3 py-2" data-aos="fade-up">
                                <div class="achive-box h-75">
                                    <div class="img">
                                        <img src="{{ $device->image_path }}" alt="" style="width:150px;" />
                                    </div>
                                    <h3>{{ App::getLocale() == 'en' ? $device->title : $device->title_ar }}
                                    </h3>
                                    <div class="plan-price">
                                        {{ $device->amount }}<small> {{ __('SAR') }}</small></div>
                                    <p>{{ App::getLocale() == 'en' ? $device->description : $device->description_ar }}
                                    </p>
                                    <a href="javascript:;" class="btn btn-outline-primary w-100 mb-2"
                                        id="btn_add_to_cart_device_{{$device->id}}"
                                        onclick="open_modal('{{$device->id}}','device');">{{ __("ADD TO CART") }}</a>
                                </div>
                            </div>
                            @empty
                                <div class="alert alert-warning">
                                    {{ __('There are not featured packages available') }}
                                </div>
                            @endforelse
                            </div></div>
                          

                        </div>
                    </div>
                    {{-- devices: end --}}
                    @endif 
                    
                </div>

            </div>
        </section>



    </div>

    @include('includes/footer', ['showInquiryForm' => true])


    <a href="javascript:void(0)" class="scrollToTop"><svg xmlns="http://www.w3.org/2000/svg" width="49.198"
            height="78.727" viewBox="0 0 49.198 78.727">
            <path id="arrow-up-long-solid"
                d="M47.761,28.055a4.918,4.918,0,0,1-6.958,0L29.526,16.784v57a4.92,4.92,0,0,1-9.84,0v-57L8.4,28.055A4.92,4.92,0,1,1,1.446,21.1L21.127,1.416a4.918,4.918,0,0,1,6.958,0L47.766,21.1A4.923,4.923,0,0,1,47.761,28.055Z"
                transform="translate(-0.005 0.025)" fill="currentColor" />
        </svg>
    </a>

    @include('includes/footerscript')



    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalTitle">{{ __('Add Cart Item') }}</h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="$('#itemModal').modal('hide');">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deviceForm">
                        <input type="hidden" id="txtDeviceId" />
                        <label for="txtDeviceCount">{{ __('Enter Number of Devices:') }}</label>
                        <input type="number" class="form-control mt-1" id="txtDeviceCount" min="1" value="1"
                            placeholder="{{ __('Enter Number of Devices') }}" />
                        <span id="txtDeviceError" class="text-danger"></span>
                    </form>

                    <form id="subscriptionForm">
                        <input type="hidden" id="txtSubscriptionId" />
                        <label for="txtSubscriptionDate">{{ __('Enter Subscription Date:') }}</label>
                        <input type="date" class="form-control mt-1" id="txtSubscriptionDate"
                            placeholder="{{ __('Enter Subscription Date') }}" value="{{$data['subscription_start_date']}}"
                            min="{{$data['subscription_start_date']}}" />
                        <p class="mt-2">{{ __('Subscription End Date') }}:<span id="subscription_end_date_txt"></span></p>
                        <span id="txtSubscriptionError" class="text-danger"></span>
                        <i class="text-danger date-error"></i>
                    </form>
                </div>
                <div class="modal-footer">
                    <div id="deviceBtn">
                        <button type="button" class="btn btn-primary"
                            onclick="add_device_to_cart(document.getElementById('txtDeviceId').value,document.getElementById('txtDeviceCount').value);">{{ __('Add Device') }}</button>
                    </div>
                    <div id="subscriptionBtn">
                        <button type="button" class="btn btn-primary"
                            onclick="add_subscription_to_cart(document.getElementById('txtSubscriptionId').value,document.getElementById('txtSubscriptionDate').value);">{{ __('Add Subscription') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="existModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalTitle">Already Exist</h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="$('#existModal').modal('hide');">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alreadyExists">
                    </div>
                    <div id="alreadyAdded">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                        onclick="$('#existModal').modal('hide');">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <script>
    window.onload = function(event) {
        localStorage.setItem("from_payment", 1);
        clear_from_buy_now();
        let products = get_from_storage();
        for (let product in products) {
            if (products[product].product_type == "subscription") {
                $("#btn_add_to_cart_subscription_" + products[product].product_id).html("{{ __('ADDED TO CART') }}");
                $("#btn_add_to_cart_subscription_" + products[product].product_id).addClass("disabled");
            } else {
                $("#btn_add_to_cart_device_" + products[product].product_id).html("{{ __('ADDED TO CART') }}");
                $("#btn_add_to_cart_device_" + products[product].product_id).addClass("disabled");
            }
        }

    }

    function getCurrentDate() {
        let date = new Date();
        let datestring = String(date.getMonth() + 1).padStart(2, '0') + "/" + String(date.getDate()).padStart(2, '0') +
            "/" + date
            .getFullYear();
        return datestring;
    }

    function buy_product_now(product_id, product_type) {
        let cart_item = {};
        let response = confirm("{{ __("Clicking on buy now clears all items from cart. Proceed??") }}","Yes","No");
        if(response==true)
        {
           localStorage.removeItem("cart_items");
        }
        else
        {
           return false;
        }
        if (product_type.toLowerCase() == "subscription") {
            @foreach($data['subscriptions'] as $subscription)
            if (parseInt("{{$subscription->id}}") == product_id) {
                cart_item['product_id'] = product_id;
                cart_item['product_type'] = 'subscription';
                @if((int)$subscription->subscription_type==2)
                 cart_item['subscription_days'] = 365;
                @else
                 cart_item['subscription_days'] = 30;
                @endif
                cart_item['discount_amount'] = 0;
                cart_item['product_name'] =
                    "{{ App::getLocale() == 'en' ? $subscription->title : $subscription->title_ar }}";
                var price = price_calculation(parseFloat("{{ $subscription->amount }}"));
                cart_item['amount'] = price[0];
                cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                cart_item['product_currency'] = "SAR";
                cart_item['product_description'] =
                    "{{ App::getLocale() == 'en' ? $subscription->description : $subscription->description_ar }}";
                cart_item['subscription_start_date'] = $('#txtSubscriptionDate').attr('min');
                cart_item['tax_amount'] = price[1];
                cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
            }
            @endforeach
            localStorage.setItem("buy_now", JSON.stringify(cart_item));
        } else {
            @foreach($data['devices'] as $device)
            if (parseInt("{{$device->id}}") == product_id) {
                cart_item['product_id'] = product_id;
                cart_item['product_type'] = 'device';
                cart_item['discount_amount'] = 0;
                cart_item['product_name'] = "{{ App::getLocale() == 'en' ? $device->title : $device->title_ar }}";
                var price = price_calculation(parseFloat("{{ $device->amount }}"));
                cart_item['unit_price'] = price[0];
                cart_item['unit_price_round'] = Number(price[0]).toFixed(2);
                cart_item['product_currency'] = "SAR";
                cart_item['product_description'] =
                    "{{ App::getLocale() == 'en' ? $device->description : $device->description_ar }}";
                cart_item['device_count'] = 1;
                cart_item['amount'] = cart_item['unit_price'] * cart_item['device_count'];
                cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                cart_item['tax_amount'] = (price[1] * cart_item['device_count']);
                cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
            }
            @endforeach
            localStorage.setItem("buy_now", JSON.stringify(cart_item));
        }
        "{{request()->session()->put('uid',uniqid())}}";
        window.location = "{{route('checkout', ['lang' => request()->lang])}}";
    }

    function add_subscription_to_cart(product_id = parseInt(product_id), subscription_start_date) {
        if (is_already_exist_in_cart('subscription', product_id) == false) {
            if (subscription_start_date == "") {
                document.querySelector("#txtSubscriptionError").innerHTML = "{{ __('Enter subscription Date') }}";
                return false;
            } else {

                let m_date = $('#txtSubscriptionDate').attr('min');
                let e_date = $('#txtSubscriptionDate').val();
                let min_date = new Date(m_date);
                let entered_date = new Date(e_date);

                if(min_date <= entered_date){
                    $('.date-error').hide();
                    document.querySelector("#txtSubscriptionError").innerHTML = "";
                    let cart_item = {};
                    @foreach($data['subscriptions'] as $subscription)
                    if (parseInt("{{$subscription->id}}") == product_id) {
                        cart_item['product_id'] = product_id;
                        cart_item['subscription_count'] = 1;
                        @if((int)$subscription->subscription_type==2)
                            cart_item['subscription_days'] = 365;
                        @else
                            cart_item['subscription_days'] = 30;
                        @endif
                            cart_item['discount_amount'] = 0;
                        cart_item['product_type'] = 'subscription';
                        cart_item['product_name'] =
                            "{{ App::getLocale() == 'en' ? $subscription->title : $subscription->title_ar }}";
                        var price = price_calculation(parseFloat("{{ $subscription->amount }}"));
                        cart_item['amount'] = price[0];
                        cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                        cart_item['product_currency'] = "SAR";
                        cart_item['product_description'] =
                            "{{ App::getLocale() == 'en' ? $subscription->description : $subscription->description_ar }}";
                        cart_item['subscription_start_date'] = subscription_start_date;
                        cart_item['tax_amount'] = price[1];
                        cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                        cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                        cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
                    }
                    @endforeach
                    add_to_storage(cart_item);
                    $('#itemModal').modal("hide");
                    set_cart_view();
                    $("#btn_add_to_cart_subscription_" + product_id).html("{{ __('ADDED TO CART') }}");
                    $("#btn_add_to_cart_subscription_" + product_id).addClass("disabled");
                }else{
                    $('.date-error').text('Enter valid date! Either '+min_date.getDate()+'/'+min_date.getMonth()+'/'+min_date.getFullYear()+' or later date').show();
                    $('#itemModal').modal("show");
                    return false;
                }
            }
        } else {
            $('#existModal').modal("show");
        }
    }

    function open_modal(product_id, product_type,subscription_type=0,subscription_date="{{$data['subscription_start_date']}}") {
        if (is_already_exist_in_cart(product_type, product_id) == false) {
            if (product_type.toLowerCase() == "device") {
                document.querySelector("#subscriptionForm").style.display = 'none';
                document.querySelector("#subscriptionBtn").style.display = 'none';

                document.querySelector("#deviceForm").style.display = 'block';
                document.querySelector("#deviceBtn").style.display = 'block';


                document.querySelector("#itemModalTitle").innerHTML = "{{ __('Add Device') }}";
                document.querySelector("#txtDeviceId").value = parseInt(product_id);
            } else {
                document.querySelector("#deviceForm").style.display = 'none';
                document.querySelector("#deviceBtn").style.display = 'none';

                document.querySelector("#subscriptionForm").style.display = 'block';
                document.querySelector("#subscriptionBtn").style.display = 'block';

                document.querySelector("#itemModalTitle").innerHTML = "{{ __('Add Subscription') }}";
                document.querySelector("#txtSubscriptionId").value = parseInt(product_id);
                if(parseInt(subscription_type)==2)
                {
                   var date = new Date(subscription_date);
                   date.setDate(date.getDate() + 365);
                   const yyyy = date.getFullYear();
                   let mm = date.getMonth() + 1;
                   let dd = date.getDate();
                   if (dd < 10) dd = '0' + dd;
                   if (mm < 10) mm = '0' + mm;
                   const formattedToday = mm + '/' + dd + '/' + yyyy;
                   document.querySelector("#subscription_end_date_txt").innerHTML = formattedToday;
                }
                else
                {
                   var date = new Date(subscription_date);
                   date.setDate(date.getDate() + 30);
                   const yyyy = date.getFullYear();
                   let mm = date.getMonth() + 1;
                   let dd = date.getDate();
                   if (dd < 10) dd = '0' + dd;
                   if (mm < 10) mm = '0' + mm;
                   const formattedToday = mm + '/' + dd + '/' + yyyy;
                   document.querySelector("#subscription_end_date_txt").innerHTML = formattedToday;
                } 
            }
            $('#itemModal').modal("show");
        } else {
            $('#existModal').modal("show");
        }
    }

    function add_device_to_cart(product_id = parseInt(product_id), product_count) {
        if (product_count == '' || parseInt(product_count) == 0) {
            document.querySelector("#txtDeviceError").innerHTML = "Enter no.of device";
            return false;
        } else {
            document.querySelector("#txtDeviceError").innerHTML = "";
            let cart_item = {};
            @foreach($data['devices'] as $device)
            if (parseInt("{{$device->id}}") == product_id) {
                cart_item['product_id'] = product_id;
                cart_item['product_type'] = 'device';
                cart_item['discount_amount'] = 0;
                cart_item['product_name'] = "{{ App::getLocale() == 'en' ? $device->title : $device->title_ar }}";
                var price = price_calculation(parseFloat("{{ $device->amount }}"));
                cart_item['unit_price'] = price[0];
                cart_item['unit_price_round'] = Number(price[0]).toFixed(2);
                cart_item['product_currency'] = "SAR";
                cart_item['product_description'] =
                    "{{ App::getLocale() == 'en' ? $device->description : $device->description_ar }}";
                cart_item['device_count'] = parseInt(product_count);
                cart_item['amount'] = cart_item['unit_price'] * cart_item['device_count'];
                cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                cart_item['tax_amount'] = (price[1] * cart_item['device_count']);
                cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
            }
            @endforeach
            add_to_storage(cart_item);
            $('#itemModal').modal("hide");
            set_cart_view();
            $("#btn_add_to_cart_device_" + product_id).html("{{ __('ADDED TO CART') }}");
            $("#btn_add_to_cart_device_" + product_id).addClass("disabled");
        }
    }

    function add_to_storage(item) {
        let product_list;
        if (typeof(window.localStorage) !== "undefined") {
            if (localStorage.getItem("cart_items") != null) {
                product_list = JSON.parse(localStorage.getItem("cart_items"));
            } else {
                product_list = [];
            }
            product_list.push(item);
            localStorage.setItem("cart_items", JSON.stringify(product_list));
        }
    }

    function clear_from_buy_now() {
        if (typeof(localStorage) !== "undefined" && localStorage.getItem("buy_now") != null) {
            localStorage.removeItem("buy_now");
        }
    }

    function is_already_exist_in_cart(product_type, product_id) {
        /*$('#itemModal').modal("hide");*/
        let products = get_from_storage();
        let product = products.find((el) => {
            return el['product_type'] == product_type && parseInt(el['product_id']) == parseInt(product_id);
        });
        if (product_type == "subscription") {
            let more_than_one_plan = products.filter((el) => {
                return el['product_type'] == 'subscription';
            });
            if (more_than_one_plan.length == 1) {
                $('#existModalTitle').html("{{ __('Subscription Added') }}");
                $('#alreadyAdded').html("{{ __("You have already added a subscription plan in the cart") }}");
                $('#alreadyExists').html("");
                return true;
            }
        } else {
            if (typeof(product) !== "undefined") {
                $('#existModalTitle').html("{{ __('Item already added') }}");
                $('#alreadyAdded').html("");
                $('#alreadyExists').html("{{ __('This item already Exist in cart') }}");
                return true;
            }
        }
        return false;
    }

    window.addEventListener( "pageshow", function ( event ) {
       var historyTraversal = event.persisted || 
                         ( typeof window.performance != "undefined" && 
                              window.performance.navigation.type === 2 );
       if (historyTraversal) {
          // Handle page restore.
          window.location.reload();
        }
    });

    function price_calculation(amount){
        var per = 15;
        var price_wo_tax = amount / ((per / 100) + 1);
        var tax = amount - parseFloat(price_wo_tax);
        console.log(price_wo_tax.toFixed(4),tax.toFixed(4));
        return [price_wo_tax.toFixed(4),tax.toFixed(4)];
    }

    function custom_round(number) {
        const spitedValues = String(number.toLocaleString()).split('.');
        let decimalValue = spitedValues.length > 1 ? spitedValues[1] : '';
        decimalValue = decimalValue.concat('00').substr(0,2);
        return spitedValues[0] + '.' + decimalValue;
    }
    </script>
</body>

</html>