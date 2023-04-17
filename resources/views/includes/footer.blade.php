<footer id="inquiry-now">
    @if (isset($showInquiryForm) && $showInquiryForm == true)
        <div class="footer-form-bar">
            <div class="container">

                <div class="form-box">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text  me-lg-5" data-aos="fade-up">
                                <h5> {{ __('Inquiry Now') }}</h5>
                                <h2>{{ __('Request a free') }} <br />{{ __('demo of Wosul') }}</h2>
                                <h4>{{ __('Let us help you to get started with Wosul with a free demo from our expert team.') }}
                                </h4>
                                <ul>
                                    <li>{{ __('Demo will include a complete tour from core to premium features so based on that you can choose your packages wisely.') }}
                                    </li>
                                    <li>{{ __('Our team is trained to focus on your prime requirements so we would like to give you customized demo of only required features.') }}
                                    </li>
                                    <li>{{ __('We are all set to serve you, what you need to do is just submit the form and get ready for an amazing experience.') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">

                            <div class="error-string"></div>

                            <form>

                                @csrf

                                <label class="label">{{ __('Your Name') }} <em>*</em></label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" id="demo_request_first_name"
                                                placeholder="{{ __('First Name') }}" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" id="demo_request_last_name"
                                                placeholder="{{ __('Last Name') }}" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Phone Number') }} <em>*</em></label>
                                    <div class="phonumber-field-group">
                                        <div class="phonumber-field-code">
                                            <select class="customSelect">
                                                <option>+966</option>
                                            </select>
                                        </div>
                                        <div class="phonumber-field-input">
                                            <input type="text" placeholder="{{ __('5XXXXXXXX') }}"
                                                class="form-control" required id="demo_request_contact_number" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Email Address') }} <em>*</em></label>
                                    <input type="email" placeholder="{{ __('example@example.com') }}"
                                        class="form-control" required id="demo_request_email" />
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('City') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('example: Riyadh') }}"
                                        class="form-control" required id="demo_request_city" />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label">{{ __('Business Domain') }}
                                                <em>*</em></label>
                                            <select class="customSelect" id="demo_request_domain">
                                                <option> {{ App::getLocale() == 'en' ? 'Cafe' : 'كافيه' }} </option>
                                                <option> {{ App::getLocale() == 'en' ? 'Restaurants' : 'مطاعم' }}
                                                </option>
                                                <option> {{ App::getLocale() == 'en' ? 'Retail' : 'قطاعي' }}
                                                </option>
                                                <option> {{ App::getLocale() == 'en' ? 'Store' : 'متجر' }} </option>
                                                <option> {{ App::getLocale() == 'en' ? 'Other' : 'آخر' }} </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span>{{ __('By submitting this box, I confirm that I have read, understood and agree to the') }}
                                        <a
                                            href="{{ route('term_and_condition', app()->getLocale()) }}">{{ __('terms and conditions') }}</a>.</span>
                                </div>

                                <div class="form-submit-wrap">
                                    <button type="button" class="btn btn-primary btn-m-width"
                                        onclick="submit_demo_request()">{{ __('Submit') }}</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="footer-logo">
                            <img src="{{ env('WEBSITE_MEDIA_URL') . '/storage/website_setting/' . $footer_logo }}"
                                alt="" />
                        </div>
                        <ul class="footer-info-list row">
                            <li style="direction: ltr;" class="col-lg-6">
                                <a href="tel:{{ $support_contact_number }}">
                                    <span class="icon"><svg width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.9343 14.2188L13.6348 11.2189C13.4789 11.0771 13.2739 11.0015 13.0633 11.008C12.8526 11.0145 12.6527 11.1027 12.5058 11.2538L10.5634 13.2513C10.0959 13.162 9.15599 12.869 8.18846 11.9039C7.22094 10.9356 6.92792 9.99323 6.84107 9.52895L8.837 7.58579C8.9883 7.43898 9.07655 7.23906 9.08307 7.02833C9.08958 6.81761 9.01385 6.61262 8.8719 6.45674L5.87274 3.15807C5.73073 3.00171 5.53336 2.90686 5.32254 2.89367C5.11173 2.88049 4.90408 2.95 4.74369 3.08746L2.98234 4.59799C2.84201 4.73883 2.75825 4.92628 2.74696 5.12477C2.73478 5.32769 2.50264 10.1345 6.22988 13.8633C9.48147 17.1141 13.5545 17.3519 14.6762 17.3519C14.8402 17.3519 14.9408 17.3471 14.9676 17.3454C15.1661 17.3343 15.3534 17.2502 15.4936 17.1092L17.0033 15.3471C17.1413 15.1872 17.2113 14.9797 17.1984 14.7689C17.1856 14.5581 17.0908 14.3607 16.9343 14.2188Z"
                                                fill="#3C53C7" />
                                        </svg>
                                    </span>
                                    {{ $support_contact_number }}</a>
                            </li>
                            <li class="col-lg-6">
                                <span class="icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.5388 6.79994L10.0454 10.8583L3.55195 6.79994V5.17658L10.0454 9.23499L16.5388 5.17658V6.79994ZM16.5388 3.55322H3.55195C2.65098 3.55322 1.92859 4.27562 1.92859 5.17658V14.9167C1.92859 15.3473 2.09962 15.7602 2.40406 16.0646C2.7085 16.3691 3.12141 16.5401 3.55195 16.5401H16.5388C16.9694 16.5401 17.3823 16.3691 17.6867 16.0646C17.9912 15.7602 18.1622 15.3473 18.1622 14.9167V5.17658C18.1622 4.74604 17.9912 4.33313 17.6867 4.02869C17.3823 3.72425 16.9694 3.55322 16.5388 3.55322Z"
                                            fill="#3C53C7" />
                                    </svg>
                                </span>
                                <a href="mailto:support@wosul.sa">{{ $support_email }}</a>
                                <a href="mailto:info@wosul.sa">{{ $info_email }}</a>
                            </li>
                            <li class="col-lg-12">
                                <a target="_blank" href="https://www.google.com/maps/dir//wosul/data=!4m6!4m5!1m1!4e2!1m2!1m1!1s0x3e2f032e1ccbf5dd:0xec94c51ec184a456?sa=X&ved=2ahUKEwjoro2Pl8L9AhWy4DgGHSr3BikQ9Rd6BAhIEAU">

                                    <span class="icon"><svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                        d="M10.0459 1.99463C8.27052 1.99672 6.56849 2.7029 5.31313 3.95826C4.05777 5.21362 3.35159 6.91565 3.34949 8.69099C3.34737 10.1418 3.82127 11.5533 4.69851 12.7088C4.69851 12.7088 4.88114 12.9493 4.91097 12.984L10.0459 19.0399L15.1832 12.9809C15.21 12.9487 15.3932 12.7088 15.3932 12.7088L15.3938 12.707C16.2706 11.5519 16.7443 10.1411 16.7422 8.69099C16.7401 6.91565 16.0339 5.21362 14.7786 3.95826C13.5232 2.7029 11.8212 1.99672 10.0459 1.99463ZM10.0459 11.126C9.56425 11.126 9.09346 10.9832 8.69302 10.7157C8.29258 10.4481 7.98048 10.0678 7.79617 9.62284C7.61187 9.1779 7.56365 8.68829 7.65761 8.21594C7.75156 7.74359 7.98348 7.30971 8.32402 6.96916C8.66457 6.62861 9.09845 6.3967 9.57081 6.30274C10.0432 6.20878 10.5328 6.25701 10.9777 6.44131C11.4227 6.62561 11.803 6.93772 12.0705 7.33816C12.3381 7.7386 12.4809 8.20939 12.4809 8.69099C12.4801 9.33656 12.2233 9.95545 11.7668 10.4119C11.3103 10.8684 10.6914 11.1252 10.0459 11.126Z"
                                        fill="#3C53C7" />
                                    </svg>
                                </span>
                                {{ $visiting_address }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-8 ps-lg-5 mt-5 mt-lg-0">
                        <div class="row justify-content-md-around">
                            <div class="col-6 col-md-auto mb-4 mb-md-0">
                                <h3>{{ __('Quick Links') }}</h3>
                                <ul class="foot-links">
                                    <li><a href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('sectors', app()->getLocale()) }}">{{ __('Sectors') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('pricing', app()->getLocale()) }}">{{ __('Plans & Pricing') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('marketplace', app()->getLocale()) }}">{{ __('Marketplace') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('contact', app()->getLocale()) }}">{{ __('Contact Us') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6 col-md-auto mb-4 mb-md-0">
                                <h3>{{ __('Make A Visit') }}</h3>
                                <ul class="foot-links">
                                    <li>{{ __('Al Olaya') }}</li>
                                    <li>{{ __('Riyadh 11564') }}</li>
                                    <li>{{ __('Airport Branch Road') }}</li>
                                    <li>{{ __('Saudi Arabia') }}</li>
                                </ul>
                            </div>
                            <div class="col-6 col-md-auto">
                                <h3>{{ __('Legal') }} </h3>
                                <ul class="foot-links">
                                    <li><a
                                            href="{{ route('privacy_policy', app()->getLocale()) }}">{{ __('Privacy Policy') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ route('term_and_condition', app()->getLocale()) }}">{{ __('Terms Of Service') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-auto order-2 order-md-1">
                        <p>© 2022 Wosul. {{ __('All Rights Reserved.') }}</p>
                    </div>
                    <div class="col-md-auto order-1 order-md-2">
                        <ul class="list-social">
                            {{-- @if (isset($facebook_url) && $facebook_url != '') --}}
                                <li><a href="https://www.facebook.com/WOSULPOS/"><svg width="13" height="21" viewBox="0 0 13 21"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.3401 11.8125L11.9177 8.01199H8.30578V5.54572C8.30578 4.50598 8.81034 3.49248 10.428 3.49248H12.0701V0.256758C12.0701 0.256758 10.58 0 9.15525 0C6.18068 0 4.23637 1.82027 4.23637 5.11547V8.01199H0.929901V11.8125H4.23637V21H8.30578V11.8125H11.3401Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                            {{-- @endif --}}
                            {{-- @if (isset($instagram_url) && $instagram_url != '') --}}
                                <li><a href="https://www.instagram.com/wosulpos/"><svg width="25" height="24" viewBox="0 0 25 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.37695 1.066C9.54209 1.012 9.91358 1 12.8816 1C15.8496 1 16.2211 1.013 17.3852 1.066C18.5494 1.119 19.344 1.306 20.0393 1.577C20.7674 1.854 21.4279 2.287 21.9743 2.847C22.5305 3.396 22.9596 4.06 23.2338 4.794C23.504 5.494 23.6887 6.294 23.7423 7.464C23.796 8.639 23.8079 9.013 23.8079 12C23.8079 14.988 23.795 15.362 23.7423 16.535C23.6897 17.705 23.504 18.505 23.2338 19.205C22.9596 19.9391 22.5298 20.6042 21.9743 21.154C21.4279 21.714 20.7674 22.146 20.0393 22.422C19.344 22.694 18.5494 22.88 17.3872 22.934C16.2211 22.988 15.8496 23 12.8816 23C9.91358 23 9.54209 22.987 8.37695 22.934C7.21478 22.881 6.42014 22.694 5.72483 22.422C4.99567 22.146 4.33502 21.7133 3.78888 21.154C3.23301 20.6047 2.80285 19.9399 2.52838 19.206C2.2592 18.506 2.07444 17.706 2.0208 16.536C1.96717 15.361 1.95525 14.987 1.95525 12C1.95525 9.012 1.96816 8.638 2.0208 7.466C2.07345 6.294 2.2592 5.494 2.52838 4.794C2.80325 4.06008 3.23374 3.39531 3.78988 2.846C4.33524 2.2865 4.99522 1.85344 5.72384 1.577C6.41915 1.306 7.21379 1.12 8.37595 1.066H8.37695ZM17.2968 3.046C16.1446 2.993 15.7989 2.982 12.8816 2.982C9.96424 2.982 9.61857 2.993 8.46634 3.046C7.40053 3.095 6.82243 3.274 6.43703 3.425C5.92746 3.625 5.56292 3.862 5.1805 4.247C4.81799 4.60205 4.539 5.03428 4.364 5.512C4.21402 5.9 4.03621 6.482 3.98754 7.555C3.9349 8.715 3.92397 9.063 3.92397 12C3.92397 14.937 3.9349 15.285 3.98754 16.445C4.03621 17.518 4.21402 18.1 4.364 18.488C4.53883 18.965 4.81794 19.398 5.1805 19.753C5.53312 20.118 5.96322 20.399 6.43703 20.575C6.82243 20.726 7.40053 20.905 8.46634 20.954C9.61857 21.007 9.96325 21.018 12.8816 21.018C15.7999 21.018 16.1446 21.007 17.2968 20.954C18.3626 20.905 18.9407 20.726 19.3261 20.575C19.8357 20.375 20.2002 20.138 20.5826 19.753C20.9452 19.398 21.2243 18.965 21.3991 18.488C21.5491 18.1 21.7269 17.518 21.7756 16.445C21.8282 15.285 21.8392 14.937 21.8392 12C21.8392 9.063 21.8282 8.715 21.7756 7.555C21.7269 6.482 21.5491 5.9 21.3991 5.512C21.2005 4.999 20.9651 4.632 20.5826 4.247C20.23 3.88207 19.8006 3.60121 19.3261 3.425C18.9407 3.274 18.3626 3.095 17.2968 3.046ZM11.486 15.391C12.2654 15.7176 13.1333 15.7617 13.9414 15.5157C14.7495 15.2697 15.4477 14.7489 15.9167 14.0422C16.3858 13.3356 16.5966 12.4869 16.5131 11.6411C16.4297 10.7953 16.0572 10.005 15.4592 9.405C15.078 9.02148 14.6171 8.72781 14.1096 8.54515C13.6022 8.36248 13.0608 8.29536 12.5245 8.34862C11.9882 8.40187 11.4702 8.57418 11.008 8.85313C10.5458 9.13208 10.1507 9.51074 9.85131 9.96185C9.55189 10.413 9.35554 10.9253 9.2764 11.462C9.19726 11.9986 9.2373 12.5463 9.39363 13.0655C9.54996 13.5847 9.81871 14.0626 10.1805 14.4647C10.5423 14.8668 10.9882 15.1832 11.486 15.391ZM8.91035 8.002C9.43186 7.47697 10.051 7.0605 10.7324 6.77636C11.4137 6.49222 12.144 6.34597 12.8816 6.34597C13.6191 6.34597 14.3494 6.49222 15.0308 6.77636C15.7122 7.0605 16.3313 7.47697 16.8528 8.002C17.3743 8.52702 17.788 9.15032 18.0702 9.8363C18.3525 10.5223 18.4977 11.2575 18.4977 12C18.4977 12.7425 18.3525 13.4777 18.0702 14.1637C17.788 14.8497 17.3743 15.473 16.8528 15.998C15.7996 17.0583 14.3711 17.654 12.8816 17.654C11.3921 17.654 9.96358 17.0583 8.91035 15.998C7.85712 14.9377 7.26542 13.4995 7.26542 12C7.26542 10.5005 7.85712 9.06234 8.91035 8.002ZM19.7433 7.188C19.8725 7.06527 19.976 6.91768 20.0476 6.75397C20.1191 6.59027 20.1573 6.41377 20.1599 6.23493C20.1625 6.05609 20.1294 5.87855 20.0626 5.71281C19.9958 5.54707 19.8967 5.39651 19.7711 5.27004C19.6455 5.14357 19.4959 5.04376 19.3313 4.97652C19.1666 4.90928 18.9903 4.87598 18.8127 4.87858C18.635 4.88119 18.4597 4.91965 18.2971 4.9917C18.1345 5.06374 17.9879 5.1679 17.866 5.298C17.6289 5.55103 17.4991 5.88712 17.5041 6.23493C17.5091 6.58274 17.6486 6.91488 17.8929 7.16084C18.1373 7.40681 18.4672 7.54723 18.8127 7.5523C19.1581 7.55737 19.492 7.42669 19.7433 7.188Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                            {{-- @endif --}}
                            {{-- @if (isset($linkedin_url) && $linkedin_url != '') --}}
                                <li><a href="https://www.linkedin.com/company/wosul/"><svg width="20" height="24" viewBox="0 0 20 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.47679 21H0.330357V6.97971H4.47679V21ZM2.40134 5.06721C1.07545 5.06721 0 3.91408 0 2.52189C9.49017e-09 1.85318 0.252998 1.21185 0.703336 0.738991C1.15367 0.266136 1.76446 0.000488281 2.40134 0.000488281C3.03821 0.000488281 3.649 0.266136 4.09934 0.738991C4.54968 1.21185 4.80268 1.85318 4.80268 2.52189C4.80268 3.91408 3.72679 5.06721 2.40134 5.06721ZM19.9955 21H15.858V14.175C15.858 12.5485 15.8268 10.4625 13.7022 10.4625C11.5464 10.4625 11.2161 12.2297 11.2161 14.0578V21H7.07411V6.97971H11.0509V8.89221H11.1089C11.6625 7.79065 13.0147 6.62815 15.0321 6.62815C19.2286 6.62815 20 9.52971 20 13.2985V21H19.9955Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                            {{-- @endif --}}
                            {{-- @if (isset($twitter_url) && $twitter_url != '') --}}
                                <li><a href="https://twitter.com/wosulPOS"><svg width="25" height="24" viewBox="0 0 25 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M23.7235 4.93659C22.8941 5.30659 22.0031 5.55659 21.0664 5.66959C22.0329 5.08738 22.756 4.17105 23.1007 3.09159C22.1927 3.63458 21.1989 4.01678 20.1625 4.22159C19.4656 3.47245 18.5425 2.97591 17.5365 2.80906C16.5306 2.64221 15.4981 2.81438 14.5993 3.29884C13.7005 3.7833 12.9857 4.55295 12.5659 5.48829C12.1461 6.42363 12.0448 7.47234 12.2777 8.47159C10.4378 8.37858 8.63785 7.89714 6.99473 7.05849C5.3516 6.21985 3.902 5.04275 2.73999 3.60359C2.34267 4.29359 2.11421 5.09359 2.11421 5.94559C2.11377 6.71258 2.30138 7.46783 2.6604 8.14432C3.01943 8.82081 3.53877 9.39763 4.17233 9.82359C3.43756 9.80005 2.719 9.60017 2.07646 9.24059V9.30059C2.07639 10.3763 2.446 11.419 3.12259 12.2516C3.79918 13.0842 4.74107 13.6555 5.78843 13.8686C5.10681 14.0543 4.39219 14.0817 3.69853 13.9486C3.99403 14.8742 4.56965 15.6836 5.34479 16.2635C6.11994 16.8434 7.05581 17.1648 8.02138 17.1826C6.38226 18.478 4.35797 19.1807 2.27413 19.1776C1.905 19.1777 1.53618 19.156 1.16958 19.1126C3.28479 20.4818 5.74705 21.2084 8.26176 21.2056C16.7744 21.2056 21.428 14.1076 21.428 7.95159C21.428 7.75159 21.423 7.54959 21.4141 7.34959C22.3193 6.69056 23.1006 5.87448 23.7215 4.93959L23.7235 4.93659Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                            {{-- @endif --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
