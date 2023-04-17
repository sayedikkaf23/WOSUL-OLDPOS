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
            <div class="container mt-5 pt-5">
                <div class="login-box">
                    <h2 class="pb-0">{{ __('Sign up to Account') }}</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-5 mb-5">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                      <ul>
                        <li>{!! \Session::get('success') !!}</li>
                      </ul>
                    </div>
                    @endif


                    <form action="{{ route('save_register_merchant', app()->getLocale()) }}" method="POST">
                        @csrf

                        <h4>{{ __('Owner’s information') }}</h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Your Name') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('Your Name') }}" class="form-control"
                                        required name="name" id="name" pattern="^[a-zA-Z\u0600-\u06FF ]+$"/>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Phone Number') }} <em>*</em></label>
                                    <input type="number" placeholder="{{ __('Phone Number') }}" class="form-control" required name="phone_number" min="9" id="number" pattern="[0-9]+"/>
                                </div>
                            </div>
                           
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('E-mail') }} <em>*</em></label>
                                    <input type="email" placeholder="{{ __('E-mail') }}" class="form-control"
                                        required name="email" id="email" />
                                    <label for="" class="text-danger email-exists small pt-2"
                                        style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('City') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('City') }}" class="form-control"
                                        required name="city" id="city" pattern="^[a-zA-Z\u0600-\u06FF ]+$"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Additional Phone Number') }} </label>
                                    <input type="nuber" placeholder="{{ __('Additional Phone Number') }}"      class="form-control" name="additional_phone_number" min="9" id="adi_number" pattern="[0-9]+"/>
                                </div>
                            </div>

                        </div>
                        <h4>{{ __('Business’s information') }}</h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Company URL') }}<em>*</em></label>
                                    <div class="form-company-group">
                                        <input type="text" placeholder="{{ __('Company URL') }}"
                                            class="form-control" required name="company_url" id="company_url" pattern="^[a-zA-Z\u0600-\u06FF]+$" />
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
                                        required name="company_name" id="company_name" pattern="^[a-zA-Z\u0600-\u06FF ]+$"/>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Referral Code') }} </label>
                                    <input type="text" placeholder="{{ __('Referral Code') }}" class="form-control"
                                        name="referral_code" id="referral_code" pattern="^[a-zA-Z\u0600-\u06FF]+$"/>
                                </div>
                            </div>
<!--                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('User Type') }} <em>*</em></label>
                                    <select class="form-select select2" name="user_type" required>
                                        <option value="">{{ __('Select User Type') }}</option>
                                        <option value="1">{{ __('Real Client') }}</option>
                                        <option value="2">{{ __('Test') }}</option>
                                    </select>
                                </div>
                            </div>-->
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Merchant Business') }} <em>*</em></label>
                                    <select class="form-select select2" name="merchant_business" id="merchant_business" required>
                                        <option value="">{{ __('Select Merchant Business')}}</option>
                                        <option value="1">{{ __('Restaurants')}}</option>
                                        <option value="2">{{ __('Caffe')}}</option>
                                        <option value="3">{{ __('Clothes')}}</option>
                                        <option value="4">{{ __('Makeup Accessories')}}</option>
                                        <option value="5">{{ __('Other')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6" id="other_merchant_business">
                                <div class="form-group">
                                    <label class="label">{{ __('Other Merchant Business') }} </label>
                                    <input type="text" placeholder="{{ __('Enter Other Merchant Business') }}"
                                        class="form-control" name="other_merchant_business" />
                                </div>
                            </div>
                            @if($data['enable_payment_gateway'])
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="label">{{ __('Subscriptions') }} <em>*</em></label>
                                    <select class="form-select select2" name="subscriptions" id="subscriptions" required>
                                        <option value="">{{ __('Select Subscription')}}</option>
                                        @foreach($data['suscription_plans'] as $plan)
                                            <option value="{{ $plan['id'] }}">{{ app()->getLocale()=='en'?$plan['title']:$plan['title_ar'] }} ({{ $plan['amount'] }} {{ __('SAR') }}) </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="custom-checkbox">
                            <label>
                                <input type="checkbox" onChange="showHideSubmit()" id="term_and_condition_check" />
                                <span>{{ __('Accept') }} <a href="#">{{ __('Terms and Conditions') }}</a></span>
                            </label>
                        </div>
                        <div class="form-submit-wrap text-center">
                            <button type="submit" class="btn btn-primary btn-m-width disabled"
                                id='submit_btn'>{{ __('Sign Up') }}</button>
                            <p>{{ __('Have an account?') }} <a href="{{ route('login',app()->getLocale()) }}"><b><u>{{ __('Sign In') }}</u></b></a></p>
                        </div>
                        <input type="hidden" name="lang" id="lang" value="{{ request('lang') }}">
                        <input type="hidden" name="subscription_id" id="subscription_id" value="{{ request('id') }}">
                        <input type="hidden" id="enable_payment_gateway" value="{{ $data['enable_payment_gateway'] }}">
                        <input type="hidden" name="in_cart" id="in_cart">
                        <input type="hidden" name="is_trial" value="{{ Request::get('trial') }}">

                    </form>
                </div>
            </div>
        </section>



    </div>

    @include('includes/footerscript')

    <script>

        if($('#enable_payment_gateway').val() == 1){
            let products = JSON.parse(localStorage.getItem("cart_items"));
            var subscription_id = 0;
            var error = 0;
            var in_cart = 0;
            for (let product in products) {
                in_cart = 1;
                if (products[product].product_type == "subscription") {
                    subscription_id = products[product].product_id
                    break;
                }
            }
            $('#subscription_id').val(subscription_id);
            /*if(subscription_id<1 || subscription_id==''){
                var url = "{{ route('login',app()->getLocale()) }}";
                setTimeout(function() {window.location.href = url}, 300);
            }*/
            if(subscription_id>0){
                $('#subscriptions option[value='+subscription_id+']').attr('selected','selected');
            }
        }
        
        $('#in_cart').val(in_cart);
        $('#other_merchant_business').hide();
        function showHideSubmit() {
            let value = $('#term_and_condition_check').prop('checked');
            if (value && error==0) {
                $('#submit_btn').removeClass('disabled');
            } else {
                $('#submit_btn').addClass('disabled');
            }
        }

        $("#merchant_business").on('change',function(){
            // alert($('#merchant_business').val());
            if($('#merchant_business').val() == 5){
                $('#other_merchant_business').show();
            }else{
                $('#other_merchant_business').hide();
            }
        })

        $('#email').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var email = $('#email').val();
            if(email!=''){
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
                            $('#submit_btn').addClass('disabled');
                            error = 1;
                        } else {
                            $('.email-exists').hide();
                            $('#email').css('color', 'inherit');
                            let value = $('#term_and_condition_check').prop('checked');
                            if (value){
                                $('#submit_btn').removeClass('disabled');
                            }
                            error = 0;
                        }
                    }
                });
            }
        });

        $('#company_url').on('focusout', function() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var company_url = $('#company_url').val();
            if(company_url!=''){
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
                            $('#submit_btn').addClass('disabled');
                            error = 1;
                        } else {
                            $('.company-url-available').hide();
                            $('.company-url-not-available').show();
                            $('#company_url').css('color', 'green');
                            let value = $('#term_and_condition_check').prop('checked');
                            if (value){
                                $('#submit_btn').removeClass('disabled');
                            }
                            error = 0;
                        }
                    }
                });
            }
        });

        // hide and show preloader in during form submission

        $('#merchant_submit_btn').on('click', function() {
            $(this).hide();
            $('#merchant_submit_preloader').show();

        });

        $(document).on('change','#subscriptions',function (){
            //set the subscription id
            $('#subscription_id').val($(this).val());
            var subscription = [];
            //get the selected subscription and add in to the local storage array
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('subscription_id', $(this).val());

            $.ajax({
                url: "{{ route('get_subscription_detail') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(data) {
                    //change the local storage cart array
                    let products = JSON.parse(localStorage.getItem("cart_items"));
                    var new_product_list = [];
                    if(products.length>0){
                        for (let product in products) {
                            if (products[product].product_type == "subscription") {

                                //subscription add in to array which selected from dropdown
                                let cart_item = {};
                                cart_item['product_id'] = data.data.id;
                                cart_item['subscription_count'] = 1;
                                if(data.data.subscription_type==2)
                                {
                                    cart_item['subscription_days'] = 365;
                                }else{
                                    cart_item['subscription_days'] = 30;
                                }
                                cart_item['discount_amount'] = 0;
                                cart_item['product_type'] = 'subscription';
                                if($('#lang').val() == 'en'){
                                    cart_item['product_name'] =data.data.title;
                                }else{
                                    cart_item['product_name'] =data.data.title_ar;
                                }
                                var price = price_calculation(parseFloat(data.data.amount));
                                cart_item['amount'] = price[0];
                                cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                                cart_item['product_currency'] = "SAR";
                                if($('#lang').val() == 'en'){
                                    cart_item['product_description'] =data.data.description;
                                }else{
                                    cart_item['product_description'] =data.data.description_ar;
                                }
                                cart_item['subscription_start_date'] = '2023-02-23';
                                cart_item['tax_amount'] = price[1];
                                cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                                cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                                cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
                                new_product_list.push(cart_item);
                            }else{
                                new_product_list.push(products[product]);
                            }
                        }
                    }else{
                        //add only subscription array;
                        let cart_item = {};
                        cart_item['product_id'] = data.data.id;
                        cart_item['subscription_count'] = 1;
                        if(data.data.subscription_type==2)
                        {
                            cart_item['subscription_days'] = 365;
                        }else{
                            cart_item['subscription_days'] = 30;
                        }
                        cart_item['discount_amount'] = 0;
                        cart_item['product_type'] = 'subscription';
                        if($('#lang').val() == 'en'){
                            cart_item['product_name'] =data.data.title;
                        }else{
                            cart_item['product_name'] =data.data.title_ar;
                        }
                        var price = price_calculation(parseFloat(data.data.amount));
                        cart_item['amount'] = price[0];
                        cart_item['amount_round'] = Number(cart_item['amount']).toFixed(2);
                        cart_item['product_currency'] = "SAR";
                        if($('#lang').val() == 'en'){
                            cart_item['product_description'] =data.data.description;
                        }else{
                            cart_item['product_description'] =data.data.description_ar;
                        }
                        cart_item['subscription_start_date'] = '2023-02-23';
                        cart_item['tax_amount'] = price[1];
                        cart_item['tax_amount_round'] = Number(cart_item['tax_amount']).toFixed(2);
                        cart_item['total_amount'] = parseFloat(cart_item['amount']) + parseFloat(cart_item['tax_amount']);
                        cart_item['total_amount'] = cart_item['total_amount'].toFixed(2);
                        new_product_list.push(cart_item);
                    }
                    localStorage.setItem("cart_items", JSON.stringify(new_product_list));
                }
            });
        });

        function price_calculation(amount){
            var per = 15;
            var price_wo_tax = amount / ((per / 100) + 1);
            var tax = amount - parseFloat(price_wo_tax);
            return [price_wo_tax.toFixed(4),tax.toFixed(4)];
        }

        /*$('#name, #number, #city, #adi_number, #company_url, #company_name, #referral_code').keydown(function(e) {
            if (e.keyCode === 190 || e.keyCode === 110) {
                e.preventDefault();
            }
        });*/

        $('#name, #city, #company_name').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z\u0600-\u06FF ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
        $('#company_url, #referral_code').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z\u0600-\u06FF]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
        $('#number, #adi_number').on('keypress', function (event) {
            var regex = new RegExp("^[0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
    </script>


</body>

</html>
