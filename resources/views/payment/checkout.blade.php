<!DOCTYPE html>
<html>

<head>
     <!-- Google Tag Manager -->
     <style>
        .wpwl-wrapper.wpwl-wrapper-brand{
        display: none;
    }
    a
{
	outline: none!important;
	text-decoration: none!important;
}


.cnpBillingCheckoutWrapper {position:relative;}
    .cnpBillingCheckoutHeader {width:100%;border-bottom: 1px solid #c0c0c0;margin-bottom:5px;}
    .cnpBillingCheckoutLeft {width:240px;margin-left: 5px;margin-bottom: 10px;border: 1px solid #c0c0c0;display:inline-block;vertical-align: top;padding:10px;}
    .cnpBillingCheckoutRight {width:50%;margin-left: 5px;border: 1px solid #c0c0c0;display:inline-block;vertical-align: top;padding:10px;}
    .cnpBillingCheckoutOrange {font-size:110%;color: rgb(255, 60, 22);font-weight:bold;}
    div.wpwl-wrapper, div.wpwl-label, div.wpwl-sup-wrapper { width: 100% }
    div.wpwl-group-expiry, div.wpwl-group-brand { width: 30%; float:left }
    div.wpwl-group-cvv { width: 68%; float:left; margin-left:2% }
    div.wpwl-group-cardHolder, div.wpwl-sup-wrapper-street1, div.wpwl-group-expiry { clear:both }
    div.wpwl-sup-wrapper-street1 { padding-top: 1px }
    div.wpwl-wrapper-brand { width: auto }
    div.wpwl-sup-wrapper-state, div.wpwl-sup-wrapper-city { width:32%;float:left;margin-right:2% }
    div.wpwl-sup-wrapper-postcode { width:32%;float:left }
    div.wpwl-sup-wrapper-country { width: 100% }
    div.wpwl-wrapper-brand, div.wpwl-label-brand, div.wpwl-brand { display: none;}
    
    div.wpwl-group-brand { width:35%; float:left; margin-top:0px }
    div.wpwl-brand-card  { width: 65px }
    div.wpwl-brand-custom  { margin: 0px 5px; background-image: url("https://eu-test.oppwa.com/v1/paymentWidgets/img/brand.png") }
    /* style error messages */
    .wpwl-hint {
        font-size: 12px;
        color: #EF4444
    }

.payment-logo img 
{
  	width: 120px;
	background: white;
	border: 2px solid #f0f0f0;
	padding: 10px;
	border-radius: 10px;
}
/*#choose_days 
{
  display: flex;
}*/
#payment-method-logo input[type="radio"] 
{
    display: none;
}
#payment-method-logo input[type="radio"]:checked + span 
{
    width: 120px;
	background: white;
	border: 2px solid #03b080;
	padding: 10px;
	border-radius: 10px;
	display: flex;
    justify-content: space-between;
    cursor: pointer;
    background-image: url("{{asset('images/select-icon.png')}}");
	background-repeat: no-repeat;
	background-origin: border-box;
	background-position: 100px 0px;
}
#payment-method-logo span 
{
    display: flex;
    justify-content: space-between;
    width: 120px;
	background: white;
	border: 2px solid #f0f0f0;
	padding: 10px;
	border-radius: 10px;
	margin-right: 12px;
	cursor: pointer;
}
input[type],label,p{
    font-weight:bold;
}
.card-details-form .form-control
{
	font-size: 14px;
}
    </style>
     <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-T436WWS');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-197961721-1">
    </script>

<script src="https://checkout.tabby.ai/tabby-promo.js"></script>
<script src="https://checkout.tabby.ai/tabby-payment-method-snippet-cci.js"></script>


    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-197961721-1');
    </script>

    <title>WOSUL</title>
    @push('styles')
    <style>
      
    </style>
    @endpush

    @include('includes/headerscript')
    @include('layouts/scripts')
    
</head>

<body>
<div id="main" class="site-main" role="main">

<section class="fly-sidebar-right container-min">


    <div class="pricing-bg">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 text-center">

                    <h1 class="about-head" style="color:#fff;">

                        {{ __('Checkout') }}
                        <img src="{{ asset('website/images/about-right.png') }}"
                            style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                    </h1>

                </div>

            </div>

        </div>

    </div>
</section>
<div class="payment-wrapper">
      <div class="container">
        <div class="row justify-content-between">
          <!-- Checkout Payment Method Code -->
          <div class="col-lg-6 mb-4 mt-3">
            <div class="payment-method-wrapper d-none" id="payment_method">
              <div class="payment-method-logo">
                <div class="text-center mb-5">
                  <h4>{{__('Payment Method')}}</h4>
                </div>
                <div class="d-flex">
                  <p id="payment-method-logo" class="mb-0">
                    <label><input type="radio" name="payment-method" id="visa_master_card" value="" onclick="setPaymentMethod('VISA MASTER');"><span><img src="{{asset('/images/visa-mastercard.png')}}" alt="Visa,Mastercard" title="Visa,Mastercard" class="img-fluid"></span></label>
                    <label><input type="radio" name="payment-method" value="" onclick="setPaymentMethod('MADA');"><span><img src="{{asset('/images/mada.png')}}" alt="Mada" title="Mada" class="img-fluid"></span></label>
                    <label><input type="radio" name="payment-method" value="" onclick="setPaymentMethod('APPLEPAY');"><span><img src="{{asset('images/applepay.png')}}" alt="ApplePay" title="ApplePay" class="img-fluid"></span></label>
                    <label><input type="radio" name="payment-method" value="" onclick="setPaymentMethod('STC_PAY');"><span><img src="{{asset('images/stc.png')}}" alt="StcPay" title="StcPay" class="img-fluid"></span></label>
                    <label id="tabbyLabel"><input type="radio" name="payment-method" value="" onclick="setUpTabbyForm();"><span><img src="{{asset('images/tabby-logo.png')}}" alt="TabbyPay" title="TabbyPay"  class="img-fluid p-2"></span></label>
                  </p>
                </div>
                <div class="d-flex">
                   <div id="TabbyPromo" class="mt-4"></div>
                </div>
              </div>
              <div class="payment-card-details mt-5">
                <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                  <h6 class="text-muted">Card Details</h6>
                  <img src="{{asset('images/visa-mastercard.png')}}" alt="Visa,Mastercard" title="Visa,Mastercard" id="card_brand_image" width="80px">
                </div>
                <div class="card-details-form mt-5">
                    <div id="parent_paymentWidgets" class="mt-3 d-none" style="width:500px; height:500px;"> <h6><div class="spinner-grow mr-3" role="status">
                    <span class="sr-only">Loading...</span>
             </div>Please wait...DO NOT Refresh or close browser</h6></div>
                </div>
              </div>
            </div>
          </div>
          <!-- Border Code -->
          <div class="col-lg-0 border-right"></div>
          <!-- Checkout Amount Summery Code -->
          

          <div  class="col-lg-5 mt-3">
            <div class="d-flex flex-column">
            <div class="text-center mb-5" id="payment-summary">
                <h4> <div class="spinner-grow mr-3" role="status">
                    <span class="sr-only">Loading...</span>
             </div>{{__('Loading Payment Summary')}}</h4>
             </div>
             <div id="loginDiv">
              <div id="registration-success"></div>
               <label>{{__('Enter Merchant Email Id')}}</label>
                  <input type="email" id="txtMerchantEmailId" oninput="if(this.value==''){document.querySelector('#chkAvailability').disabled=true;}else{document.querySelector('#chkAvailability').disabled=false;}" class="form-control mt-2"/>
                  <span class="text-danger" id="txtMerchantEmailId-error"></span>
                  <select name="country" id="billingCountry" class="form-control mt-2 mb-2">
                      <option value="-1">Select billing country</option>
                      <option value="AF">Afghanistan</option>
                      <option value="AX">Aland Islands</option>
                      <option value="AL">Albania</option>
                      <option value="DZ">Algeria</option>
                      <option value="AS">American Samoa</option>
                      <option value="AD">Andorra</option>
                      <option value="AO">Angola</option>
                      <option value="AI">Anguilla</option>
                      <option value="AQ">Antarctica</option>
                       <option value="AG">Antigua and Barbuda</option>
                      <option value="AR">Argentina</option>
    <option value="AM">Armenia</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="AZ">Azerbaijan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus</option>
    <option value="BE">Belgium</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
    <option value="BA">Bosnia and Herzegovina</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei Darussalam</option>
    <option value="BG">Bulgaria</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KH">Cambodia</option>
    <option value="CM">Cameroon</option>
    <option value="CA">Canada</option>
    <option value="CV">Cape Verde</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic</option>
    <option value="TD">Chad</option>
    <option value="CL">Chile</option>
    <option value="CN">China</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos (Keeling) Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoros</option>
    <option value="CG">Congo</option>
    <option value="CD">Congo, Democratic Republic of the Congo</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="CI">Cote D'Ivoire</option>
    <option value="HR">Croatia</option>
    <option value="CU">Cuba</option>
    <option value="CW">Curacao</option>
    <option value="CY">Cyprus</option>
    <option value="CZ">Czech Republic</option>
    <option value="DK">Denmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea</option>
    <option value="ER">Eritrea</option>
    <option value="EE">Estonia</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands (Malvinas)</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland</option>
    <option value="FR">France</option>
    <option value="GF">French Guiana</option>
    <option value="PF">French Polynesia</option>
    <option value="TF">French Southern Territories</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia</option>
    <option value="DE">Germany</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GG">Guernsey</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea-Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HM">Heard Island and Mcdonald Islands</option>
    <option value="VA">Holy See (Vatican City State)</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary</option>
    <option value="IS">Iceland</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran, Islamic Republic of</option>
    <option value="IQ">Iraq</option>
    <option value="IE">Ireland</option>
    <option value="IM">Isle of Man</option>
    <option value="IL">Israel</option>
    <option value="IT">Italy</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="JE">Jersey</option>
    <option value="JO">Jordan</option>
    <option value="KZ">Kazakhstan</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KP">Korea, Democratic People's Republic of</option>
    <option value="KR">Korea, Republic of</option>
    <option value="XK">Kosovo</option>
    <option value="KW">Kuwait</option>
    <option value="KG">Kyrgyzstan</option>
    <option value="LA">Lao People's Democratic Republic</option>
    <option value="LV">Latvia</option>
    <option value="LB">Lebanon</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libyan Arab Jamahiriya</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania</option>
    <option value="LU">Luxembourg</option>
    <option value="MO">Macao</option>
    <option value="MK">Macedonia, the Former Yugoslav Republic of</option>
    <option value="MG">Madagascar</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexico</option>
    <option value="FM">Micronesia, Federated States of</option>
    <option value="MD">Moldova, Republic of</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia</option>
    <option value="ME">Montenegro</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco</option>
    <option value="MZ">Mozambique</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NP">Nepal</option>
    <option value="NL">Netherlands</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="MP">Northern Mariana Islands</option>
    <option value="NO">Norway</option>
    <option value="OM">Oman</option>
    <option value="PK">Pakistan</option>
    <option value="PW">Palau</option>
    <option value="PS">Palestinian Territory, Occupied</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PH">Philippines</option>
    <option value="PN">Pitcairn</option>
    <option value="PL">Poland</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania</option>
    <option value="RU">Russian Federation</option>
    <option value="RW">Rwanda</option>
    <option value="BL">Saint Barthelemy</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts and Nevis</option>
    <option value="LC">Saint Lucia</option>
    <option value="MF">Saint Martin</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent and the Grenadines</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">Sao Tome and Principe</option>
    <option value="SA">Saudi Arabia</option>
    <option value="SN">Senegal</option>
    <option value="RS">Serbia</option>
    <option value="CS">Serbia and Montenegro</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SX">Sint Maarten</option>
    <option value="SK">Slovakia</option>
    <option value="SI">Slovenia</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia</option>
    <option value="ZA">South Africa</option>
    <option value="GS">South Georgia and the South Sandwich Islands</option>
    <option value="SS">South Sudan</option>
    <option value="ES">Spain</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan</option>
    <option value="SR">Suriname</option>
    <option value="SJ">Svalbard and Jan Mayen</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="SY">Syrian Arab Republic</option>
    <option value="TW">Taiwan, Province of China</option>
    <option value="TJ">Tajikistan</option>
    <option value="TZ">Tanzania, United Republic of</option>
    <option value="TH">Thailand</option>
    <option value="TL">Timor-Leste</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia</option>
    <option value="TR">Turkey</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine</option>
    <option value="AE">United Arab Emirates</option>
    <option value="GB">United Kingdom</option>
    <option value="US">United States</option>
    <option value="UM">United States Minor Outlying Islands</option>
    <option value="UY">Uruguay</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Viet Nam</option>
    <option value="VG">Virgin Islands, British</option>
    <option value="VI">Virgin Islands, U.s.</option>
    <option value="WF">Wallis and Futuna</option>
    <option value="EH">Western Sahara</option>
    <option value="YE">Yemen</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
</select> 
<span class="text-danger" id="txtBillingCountry-error"></span>

<input type="text" name="billing_state" placeholder="Enter state" id="billingState" class="form-control"/>
<span class="text-danger" id="txtBillingState-error"></span>

                  <div class="row">
                      <div class="col">
                         <input type="text" name="billing_city" placeholder="Enter city" id="billingCity" class="form-control mt-2 mb-2"/>
                         <span class="text-danger" id="txtBillingCity-error"></span> 
                      </div>
                      <div class="col">
                         <input type="text" name="billing_postcode" placeholder="Enter postcode" id="billingPostCode" class="form-control mt-2 mb-2"/>
                         <span class="text-danger" id="txtBillingPostCode-error"></span>
                      </div>
                  </div>
                  <input type="text" name="billing_street_address" placeholder="Enter Street address" id="billingStreetAddress" class="form-control mt-2 mb-2"/>
                  <span class="text-danger" id="txtBillingStreetAddress-error"></span><br />
               <button class="btn btn-primary mt-3" id="chkAvailability">{{__('Login')}}</button>
               <button class="btn btn-primary mt-3" id="chkRegister">{{__('Register')}}</button>
             </div>

             <div id="merchantRegistration" class="d-none">
        <div id="not-registered"></div>
                                <h5>Register Merchant</h5>
                                <form>
                                                   <div id="validation-errors"></div>
                                                   <div class="form-group">
                                                       <label for="username">{{ __('Full Name') }} </label>

                                                       <input type="text" class="form-control" id="username" name="name"
                                                           aria-describedby="username" placeholder="{{ __('Full Name') }}"
                                                          hidefocus="true" style="outline: currentcolor none medium;">

                                                    </div>
                                                    <div class="form-group">
                                                       <label for="usernumber">{{ __('Phone Number') }} </label>
                                                       <input type="text" class="form-control" id="usernumber"
                                                         aria-describedby="usernumber" placeholder="{{ __('Phone Number') }}"
                                                         hidefocus="true" style="outline: currentcolor none medium;"
                                                        name="phone_number">

                                                    </div>
                                                    <div class="form-group">

                                        <label for="useremail">{{ __('E-mail') }} <span
                                                class="text-danger">*</span></label>

                                        <input type="email" class="form-control" id="useremail"
                                            aria-describedby="useremail" placeholder="{{ __('E-mail') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;" name="email">
                                        <label for="" class="text-danger email-exists"
                                            style="display:none;"><strong>{{ __('Email Already Exists') }}</strong></label>

                                    </div>



                                    <div class="form-group">

                                        <label for="usercompany">{{ __('Company') }}<span
                                                class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="usercompany"
                                            aria-describedby="usercompany" placeholder="{{ __('Company') }}"
                                            hidefocus="true" style="outline: currentcolor none medium;"
                                            name="company_name">

                                    </div>

                                    <div class="form-group">

                                        <label for="usercompany">{{ __('Company URL') }} <span
                                                class="text-danger">*</span></label>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanyurl"
                                                    aria-describedby="usercompanyurl"
                                                    placeholder="{{ __('Company URL') }}" hidefocus="true"
                                                    style="outline: currentcolor none medium;" name="company_url">

                                                <small id="usercompanyurl"
                                                    class="form-text text-muted">{{ __('Example : demo') }}</small>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanyurl"
                                                    aria-describedby="usercompanyurl" placeholder=".wosul.com"
                                                    disabled="" hidefocus="true"
                                                    style="outline: currentcolor none medium;">
                                                <label for="" class="text-danger company-url-exists"
                                                    style="display:none;"><strong>{{ __('Company URL Already Exists') }}</strong></label>


                                            </div>

                                        </div>

                                    </div>

                                    
                                    <div class="form-group">

                                        <div id="merchant_submit_preloader" style="display: none;">
                                            <img src="{{ asset('website/images/loader2.gif') }}" alt=""> <br> <strong
                                                class="text-primary">
                                                {{ __('Your application is submitting, please wait') }} </strong>
                                        </div>

                                        <button class="btn custom-btn" id="merchant_submit_btn" type="submit"
                                            name="submit">{{ __('Submit') }} </button>

                                            <button class="btn custom-btn" id="backBtn"
                                            >{{ __('Back') }} </button>

                                    </div>

                                    <input type="hidden" name="subscription_id"
                                        value="">
                                    <input type="hidden" name="lang" value="{{ request('lang') }}">

                                </form>
                       </div>
            </div>
           </div>
            









          <div class="col-lg-5">
            
          </div>


        </div>
      </div>
    </div>
</div>  
    <script>
        let merchant_id = 0;
        window.onload = function(){
            if(loadProducts().length==0)
            {
              window.location = "{{ App::getLocale() == 'ar'?'/ar/pricing':'/en/pricing'}}";
            }
            else
            {
                loadPurchaseData();
            }
            if(localStorage.getItem("merchant_email")!==null)
            {
              $("#txtMerchantEmailId").val(localStorage.getItem("merchant_email"));
              $("#billingStreetAddress").val(localStorage.getItem("billing_address_1"));
              $("#billingCity").val(localStorage.getItem("billing_city"));
              $("#billingState").val(localStorage.getItem("billing_state"));
              $("#billingPostCode").val(localStorage.getItem("billing_postcode"));
              $("#billingCountry").val(localStorage.getItem("billing_country"));
              $("#chkAvailability").click();
            }
        }
        function getTotalAmount(){
            let productlist = loadProducts();
            let totalamount = 0;
            for(let product in productlist){
               totalamount+=productlist[product].priceincludingtax;
            }
            return totalamount;
        }
        function setPaymentMethod(brand){
                    if(brand=="VISA MASTER")
                    {
                        $("#card_brand_image").attr("src","{{asset('images/visa-mastercard.png')}}");
                    }
                    else if(brand=="MADA")
                    {
                        $("#card_brand_image").attr("src","{{asset('images/mada.png')}}");
                    }
                    else if(brand=="APPLEPAY")
                    {
                        $("#card_brand_image").attr("src","{{asset('images/applepay.png')}}");
                    }
                    else if(brand=="STC_PAY")
                    {
                        $("#card_brand_image").attr("src","{{asset('images/stc.png')}}");
                    }
                    $("#parent_paymentWidgets").removeClass('d-none');
                    $("#parent_paymentWidgets").html(`<div class="text-center mb-5">
                <h4> <div class="spinner-grow mr-3" role="status">
                    <span class="sr-only">Loading...</span>
             </div>{{__('Loading Payment Form')}}</h4>
             </div>`);
                    var CSRF_TOKEN = "{{ csrf_token() }}";
                    let products = [],totalamount = 0,devicescount = 0,taxamount=0;
                    if(typeof(window.localStorage)!=="undefined")
                    {
                        if(localStorage.getItem("products")){
                            products = loadProducts();
                            console.log(products);
                            if(typeof products[products.length-1]["totalamountafterdiscount"]!=="undefined" && products[products.length-1]["totalamountafterdiscount"]>0){
                               totalamount = products[products.length-1]["totalamountafterdiscount"];
                            }
                            else
                            {
                                totalamount = getTotalAmount();
                            }
                            devicescount = products[products.length-1]["devicescount"];

                        }
                    }
                        let formData = {
                          "brand": brand,
                          "merchant_id":merchant_id,
                          "access_token": CSRF_TOKEN,
                          "amount":totalamount,
                          "billingState":$("#billingState").val(),
                          "billingCity":$("#billingCity").val(),
                          "billingPostCode":$("#billingPostCode").val(),
                          "billingCountry":$("#billingCountry").val(),
                          "billingStreet":$("#billingStreetAddress").val(),
                          "products":products
                        }
                    $.ajax({
                          url: "/api/hyperpay/payment",
                          type: "POST",
                          data: formData,
                          dataType:"json",
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
                                document.querySelector("#parent_paymentWidgets").innerHTML = `<form
                                 id="paymentWidgets"
                                 class="paymentWidgets"
                                 action="{{url('/en/finalize')}}"
                                 data-brands="${brand}"
                                 ></form>`;
                            @endif
                            attachScriptInTheHtmlHead(response.script_url,devicescount);
                          },
                          error:function(xhr){
                            if(xhr.status.toString().startsWith("5")){
                                  if(typeof(xhr.responseJSON.message)!=="undefined" && xhr.responseJSON.message=="")
                                  {
                                     alert("some error have occured");
                                  }
                                  else
                                  {
                                    alert(xhr.responseJSON.message);
                                  }
                              }
                          }
                    });
                }
        document.querySelector("#chkRegister").addEventListener("click",function(event){
            $("#merchantRegistration").removeClass("d-none");
            $("#loginDiv").addClass('d-none');
        });
        document.querySelector("#backBtn").addEventListener("click",function(event){
            event.preventDefault();
            $("#merchantRegistration").addClass("d-none");
            $("#loginDiv").removeClass('d-none');
        });
        document.querySelector("#merchant_submit_btn").addEventListener("click",function(event){
            $("#merchant_submit_btn").html(`<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Submitting`);
                    event.preventDefault();
                    var CSRF_TOKEN = "{{ csrf_token() }}";
                        let formData = {
                            "name":$("#username").val(),
                            "phone_number":$("#usernumber").val(),
                            "email":$("#useremail").val(),
                            "company_name":$("#usercompany").val(),
                            "company_url":$("#usercompanyurl").val(),
                            "from_payment":1,
                            'subscription_id':1,
                            'lang':"{{App::getLocale() == 'ar'?'ar':'en'}}"
                        }
                    $.ajax({
                          url: "{{ route('save_register_merchant') }}",
                          type: "POST",
                          data: formData,
                          dataType:"json",
                          headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                          },
                          success: function(response) {
                            $("#not-registered").html(``);
                            $("#registration-success").html("<div class='alert alert-success'><p>Registration Success</p></div>");
                             $("#merchantRegistration").addClass("d-none");
                             $("#loginDiv").removeClass("d-none");
                          },
                          error: function (xhr) {
                            $("#merchant_submit_btn").html(`Submit`);
                            $('#validation-errors').html('');
                              if(xhr.status.toString().startsWith("5")){
                                  if(typeof(xhr.responseJSON.message)!=="undefined" && xhr.responseJSON.message=="")
                                  {
                                     alert("some error have occured");
                                  }
                                  else
                                  {
                                    alert(xhr.responseJSON.message);
                                  }
                                  $.ajax({
                                    url: "{{ route('delete_register_merchant') }}",
                                    type: "POST",
                                    data: formData,
                                    dataType:"json",
                                    headers: {
                                     'X-CSRF-TOKEN': CSRF_TOKEN
                                    },
                                    success: function(response) {
                                         
                                     }
                                  });
                              }
                            let strHTML = ``;
                            if(xhr.responseJSON.errors)
                            {
                              strHTML = `<div class="alert alert-danger"><ul style="list-style-type:none;">`;
                              $.each(xhr.responseJSON.errors, function(key,value) {
                               strHTML+=`<li>${value}</li>`;
                              });

                              strHTML+=`</ul></div>`;
                            $('#validation-errors').html(strHTML);
                            }
                            else
                            {
                                strHTML+=`<div class="alert alert-danger"><p>Some Unknown error have occured.Please Try again</p></div>`;
                            } 
                       }
                    });
        });
        function attachScriptInTheHtmlHead(script_url,devicescount) {
                    /*if(devicescount>0)
                    {*/
                     let scripttag = document.createElement('script');
                     scripttag.innerHTML = `var wpwlOptions = {
                                               style: "plain",
                                               maskCvv:true,
                                               paymentTarget:"_top",
                                               
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


                function setUpProducts(){
                 $.ajax({
                          url: "/api/setProductListInSession",
                          type: "POST",
                          data: {"productlist":JSON.stringify(loadProducts()),"language":"{{App::getLocale()}}"},
                          dataType:"json",
                          success: function(response) {
                          },
                          error:function(xhr){
                            if(xhr.status.toString().startsWith("5")){
                                  if(typeof(xhr.responseJSON.message)!=="undefined" && xhr.responseJSON.message=="")
                                  {
                                     alert("some error have occured");
                                  }
                                  else
                                  {
                                    alert(xhr.responseJSON.message);
                                  }
                              }
                          }
                });
            }

            function addToLocal(key,value){
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.setItem(key,value);
                }
            }


        document.querySelector("#chkAvailability").addEventListener("click",function(event){
                event.preventDefault();
                let isvalid = true;

                if($("#txtMerchantEmailId").val()==""){
                    $("#txtMerchantEmailId-error").html("Please enter merchant email");
                    isvalid = false;
                }
                $("#txtMerchantEmailId").on("change",function(){
                    $("#txtMerchantEmailId-error").html("");
                    isvalid = true;
                });
                
                if($("#billingCountry").val()=="-1"){
                    $("#txtBillingCountry-error").html("Please choose a country");
                    isvalid = false;
                }
                $("#billingCountry").on("change",function(){
                    $("#txtBillingCountry-error").html("");
                    isvalid = true;
                });
                if($("#billingPostCode").val()==""){
                    $("#txtBillingPostCode-error").html("Please enter postcode");
                    isvalid = false;
                }
                $("#billingPostCode").on("change",function(){
                    $("#txtBillingPostCode-error").html("");
                    isvalid = true;
                });
                if($("#billingState").val()==""){
                    $("#txtBillingState-error").html("Please enter state");
                    isvalid = false;
                }
                $("#billingState").on("change",function(){
                    $("#txtBillingState-error").html("");
                    isvalid = true;
                });
                if($("#billingCity").val()=="")
                {
                    $("#txtBillingCity-error").html("Please enter city");
                    isvalid = false;
                }
                $("#billingCity").on("change",function(){
                    $("#txtBillingCity-error").html("");
                    isvalid = true;
                });
                if($("#billingStreetAddress").val()==""){
                    $("#txtBillingStreetAddress-error").html("Please enter street address");
                    isvalid = false;
                }
                $("#billingStreetAddress").on("change",function(){
                    $("#txtBillingStreetAddress-error").html("");
                    isvalid = true;
                });
                if(isvalid==false)
                {
                    return false;
                }
                else
                {
                    addToLocal("billing_address_1",$("#billingStreetAddress").val());
                    addToLocal("billing_address_2",$("#billingStreetAddress").val());
                    addToLocal("billing_city",$("#billingCity").val());
                    addToLocal("billing_state",$("#billingState").val());
                    addToLocal("billing_postcode",$("#billingPostCode").val());
                    addToLocal("billing_country",$("#billingCountry").val());
                }
                $("#chkAvailability").html(`<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>{{__('Login')}}`);
                        var CSRF_TOKEN = "{{ csrf_token() }}";
                        let formData = {
                          "merchant_email_id": document.querySelector("#txtMerchantEmailId").value,
                          "access_token": CSRF_TOKEN
                        }
                        $.ajax({
                          url: '/api/verify-merchant',
                          type: "POST",
                          data: formData,
                          success: function(response) {
                              console.log(response);
                            $("#chkAvailability").html(`Login`);
                              merchant_id = response.data.id;
                              setToStorage("merchant_id",merchant_id);
                              merchant_phone = response.data.phone_number;
                             if(response.data.length==0){
                               $("#merchantRegistration").removeClass("d-none");
                               $("#not-registered").html("<div class='alert alert-primary'><p>{{__('It looks like you have not registered!!.Please Register')}}</p></div>");
                               $("#loginDiv").addClass("d-none");
                               setTimeout(() => {
                                $("#not-registered").html("");
                               }, 2500);
                             }
                             else
                             {
                                let devicecount = 0;
                                let productlist = loadProducts();
                                for(let index = 0;index<productlist.length;index++){
                                     if(productlist[index]['productType']=="subscription"){
                                          productlist[index]['subscriptionStartDate'] = response.subscriptionDate;
                                          $("#productNameWithSubscriptionStart").html(`${productlist[index]['productName']}( Start Date : ${productlist[index]['subscriptionStartDate']})`);
                                     }
                                     else
                                     {
                                         devicecount = parseInt(productlist[index]['devicescount']);
                                     }
                                }
                                saveToStorage(productlist);
                                setUpProducts();
                                if(devicecount>0){
                                    $("#TabbyPromo").show();
                                    $("#tabbyLabel").show();
                                    addTabbyScript();
                                }
                                else
                                {
                                    $("#tabbyLabel").hide();
                                    $("#TabbyPromo").hide();
                                }
                                // $("#loginForm").addClass('d-none');
                                $("#payment_method").removeClass('d-none');
                                $("#payment-summary").removeClass('d-none');
                                $("#loginDiv").addClass("d-none");
                                //alert($("#paymentGatewayDiv").val());
                                //$("#visa_master_card").click();

                             }
                          },
                          error:function(xhr){
                            if(xhr.status.toString().startsWith("5")){
                                  if(typeof(xhr.responseJSON.message)!=="undefined" && xhr.responseJSON.message=="")
                                  {
                                     alert("some error have occured");
                                  }
                                  else
                                  {
                                    alert(xhr.responseJSON.message);
                                  }
                              }
                          }
                    });
                });
            function saveToStorage(productlist)
            {
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.setItem("products",JSON.stringify(productlist));
                }
            }
            function setToStorage(key,value)
            {
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.setItem(key,value);
                }
            }
            function loadFromStorage(key)
            {
                if(typeof(window.localStorage)!=="undefined"){
                    return localStorage.getItem(key);
                }
            }
              function loadProducts(){
                let productlist = [];
                if(typeof(window.localStorage)!=="undefined")
                {
                  if(localStorage.getItem("products"))
                  {
                  productlist = JSON.parse(localStorage.getItem("products"));
                  }
                }
                return productlist;
        }
        function loadDiscount(){
            let discountcodes = [
                {"code":"test11","type":"amount","value":10},
                {"code":"test12","type":"percentage","value":10}
            ];
            let strHTML = `<div class="d-flex flex-column"><p class="mb-2">Discount code</p>
                              <select class="form-control mr-2" onclick="setDiscountValue(this.value);">`;
            for(let i = 0;i<discountcodes.length;i++)
            {
              strHTML+=`<option value="${discountcodes[i].value}_${discountcodes[i].type}">${discountcodes[i].code}</option>`;
            }
            strHTML+=`</select></div><p id="discount_total">10</p>`;
            document.querySelector("#discount_code_section").innerHTML=strHTML;
        }

        function setDiscountValue(value){
            let discounttype = value.split("_")[1];
            let discountvalue = value.split("_")[0];
            let productlist = loadProducts();
            if(discounttype=="percentage")
            {
                    discountvalue = (discountvalue*0.01)*productlist[productlist.length-1]["totalamount"];
                    discountvalue = Math.round(discountvalue * 100) / 100;
            }
            $("#discount_total").html(discountvalue);
            productlist[productlist.length-1]["discountamount"] = discountvalue;
            productlist[productlist.length-1]["totalamountafterdiscount"] = getTotalAmount() - discountvalue;
            saveToStorage(productlist);
            $("#txtTotalAmount").html(productlist[productlist.length-1]["totalamountafterdiscount"].toFixed(2));
        }
        function saveToStorage(productlist)
            {
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.setItem("products",JSON.stringify(productlist));
                }
            }

        function loadPurchaseData(){
            try
            {
                let productlist = loadProducts();
                let strHTML = `<div class="text-center mb-5"><h4>{{__('Payment Summary')}}</h4></div>`;
                let index = 0,total = 0,currency = '';
                for(let product of productlist)
                {
                    if(product['productType']=="device")
                    {
                      strHTML+=`<div class="d-flex align-items-center justify-content-between mb-2">
                                   <p class="text-muted">${product['productName']}( x ${product['devicecount']})</p>
                                   <p>${product['price']} ${product['currency']}</p>
                              </div>`;
                    }
                    else
                    {
                        strHTML+=`<div class="d-flex align-items-center justify-content-between mb-2">
                                   <p class="text-muted" id="productNameWithSubscriptionStart">${product['productName']}( Start Date : ${product['subscriptionStartDate']})</p>
                                   <p>${product['price']} ${product['currency']}</p>
                              </div>`;
                    }
                }
                strHTML+=`
                          <div class="d-flex align-items-center justify-content-between mb-2">
                               <p class="text-muted">{{__('Sub Total')}}</p>
                               <p>${productlist[productlist.length-1]["totalamount"]} ${productlist[productlist.length-1]["currency"]}</p>
                          </div>
                          <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="text-muted">{{__('VAT Tax')}}</p>
                                <p>15% </p>
                          </div>`;

                strHTML+=` <div class="d-flex align-items-center justify-content-between border-top border-bottom mb-4 py-3" id="discount_code_section">
                               <p class="text-muted mb-0"><a href="#" id="add_discount_code">{{__('Add Discount Code')}}</a></p>
                               <p></p>
                            </div>
                           <div class="d-flex align-items-center justify-content-between mb-2">
                              <p class="text-muted">{{__('Total Amount')}}</p>
                              <p id="txtTotalAmount">${getTotalAmount()}</p>
                           </div>`;
                           saveToStorage(productlist);
                document.querySelector("#payment-summary").innerHTML=strHTML;
                document.querySelector("#add_discount_code").addEventListener("click",function(event){
                      event.preventDefault();
                      loadDiscount();
                });
            }
            catch(e)
            {
                alert(e);
            }
            }
            function addTabbyScript(){
                let scripttag = document.createElement("script");
                let productlist = loadProducts();
                if(typeof productlist[productlist.length-1]["totalamountafterdiscount"]!=="undefined" && productlist[productlist.length-1]["totalamountafterdiscount"]>0){
                    totalamount = productlist[productlist.length-1]["totalamountafterdiscount"];
                }
                else
                {
                    totalamount = getTotalAmount();
                }
                scripttag.innerHTML=`
                new TabbyPaymentMethodSnippetCCI({
  selector: '#TabbyPromo', 
  currency: 'SAR', 
  price:${totalamount},
  lang: '{{App::getLocale()}}'
});`;
                document.head.appendChild(scripttag);
            }

            function setUpTabbyForm(){
                $("#card_brand_image").attr("src","{{asset('images/tabby-logo.png')}}");
                $("#parent_paymentWidgets").removeClass('d-none');
                document.querySelector("#parent_paymentWidgets").innerHTML = `
                                                                            <div id="tabbyErrorMsg" class="alert alert-danger d-none"></div>
                                                                            <form action="" method="POST">
                                                                                  <div class="row">
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Merchant Name" class="form-label text-muted">Merchant Name</label>
                                                                                      <input type="text" class="form-control" name="merchant_name" id="tabby_name" placeholder="Merchant Name" aria-label="Merchant Name">
                                                                                      <span id="merchant-name-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Merchant Email" class="form-label text-muted">Registered Email</label>
                                                                                      <input type="email" class="form-control" name="merchant_email" id="tabby_email" placeholder="Registered Merchant Email" aria-label="Merchant Email">
                                                                                      <span id="merchant-email-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <label for="Merchant Number" class="form-label text-muted">Registered Phone Number</label>
                                                                                      <input type="tel" class="form-control" name="merchant_number" id="tabby_number" placeholder="Registered Merchant Number" aria-label="Merchant Phone Number">
                                                                                      <span id="merchant-phone-error" class="text-danger"></span>
                                                                                  </div>
                                                                                  <div class="col-12 mb-3">
                                                                                      <input type="button" class="btn btn-primary btn-block" onclick="tabbyPayment()" value="Pay now" />
                                                                                      <!--<input type="button" class="btn btn-primary" onclick="tabbyNewPaymentRegister()" value="New to tabby? Please Register"/>-->
                                                                                  </div>
                                                                              </form>`;
            }

            $("#tabby_name").on("input",function(){
                $("#merchant-name-error").html("");
            });
            $("#tabby_email").on("input",function(){
                $("#merchant-email-error").html("");
            });
            $("#tabby_number").on("input",function(){
                $("#merchant-phone-error").html("");
            });
            function tabbyPayment(){
                $("#tabbyErrorMsg").addClass('d-none');
                let validate = true;
                $("#merchant-name-error").html("");
                $("#merchant-email-error").html("");
                $("#merchant-phone-error").html("");
                if($("#tabby_name").val()=="")
                {
                    $("#merchant-name-error").html("Merchant name required");
                    validate = false;
                }
                if($("#tabby_email").val()=="")
                {
                    $("#merchant-email-error").html("Merchant Email required");
                    validate = false;
                }
                if($("#tabby_number").val()=="")
                {
                    $("#merchant-phone-error").html("Merchant Phone number required");
                    validate = false;
                }
                if(validate==false)
                {
                    return false;
                }
                if(typeof(window.localStorage)!=="undefined")
                    {
                        if(localStorage.getItem("products")){
                            products = loadProducts();
                            console.log(products);
                            if(typeof products[products.length-1]["totalamountafterdiscount"]!=="undefined" && products[products.length-1]["totalamountafterdiscount"]>0){
                               totalamount = products[products.length-1]["totalamountafterdiscount"];
                            }
                            else
                            {
                                totalamount = getTotalAmount();
                            }
                            devicescount = products[products.length-1]["devicescount"];

                        }
                    }
                var CSRF_TOKEN = "{{ csrf_token() }}";
                        let formData = {
                          "merchant_email": $("#tabby_email").val(),
                          "merchant_name": $("#tabby_name").val(),
                          "merchant_phone": $("#tabby_number").val(),
                          "merchant_dob":"1985-01-01",
                          "billingState":$("#billingState").val(),
                          "billingCity":$("#billingCity").val(),
                          "billingPostCode":$("#billingPostCode").val(),
                          "billingCountry":$("#billingCountry").val(),
                          "billingStreet":$("#billingStreetAddress").val(),
                          "login_merchant_email":document.querySelector("#txtMerchantEmailId").value,
                          "merchant_id":loadFromStorage("merchant_id"),
                          "language": "{{App::getLocale()}}",
                          "totalamount":totalamount,
                          "products":products,
                          "access_token": CSRF_TOKEN
                        }
                $.ajax({
                          url: "/api/tabby/payment",
                          type: "POST",
                          data: formData,
                          dataType:"json",
                          success: function(response) {
                              window.location = response.url;
                          },
                          error: function(xhr){
                             console.log(xhr.responseJSON);
                             $("#tabbyErrorMsg").html(xhr.responseJSON.message);
                             $("#tabbyErrorMsg").removeClass('d-none');
                          },
                        });
            }
    </script>
    </body>
    </html>