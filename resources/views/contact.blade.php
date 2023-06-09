<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body>

    <!-- Page Loader Start -->
    {{-- <div id="loader-wrapper">
        <div id="loader"><img loading="lazy" src="{{ env('WEBSITE_MEDIA_URL') }}/public.website/images/logo-light.png" alt=""
                width="" height=""></div>
        <div class="loader-section-wrap">
            <div class="loader-section"></div>
            <div class="loader-section"></div>
        </div>
    </div>
    <div class="header-menu-overlay"></div> --}}

    @include('includes/header')

    <div id="wrapper">
        <section class="bg-gradient inner-banner inner-banner-left">
            <div class="inner-banner-logo-light">
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="content">
                    <h2 data-aos="fade-up">{{ __('Contact Us') }}</h2>
                    <ul class="breadcrumb-list" data-aos="fade-up">
                        <li>
                            <a href="index.html">{{ __('Home') }}</a>
                        </li>
                        <li>{{ __('Contact Us') }}</li>
                    </ul>
                </div>
            </div>
        </section>


        <section class="bg-light">
            <div class="container">

                <div class="row">
                    <div class="col-md-6">
                        <ul class="contact-details-info">
                            <li>
                                <span class="icon">
                                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.3575 5.625C7.425 6.62625 7.59375 7.605 7.86375 8.53875L6.51375 9.88875C6.0525 8.53875 5.76 7.11 5.65875 5.625H7.3575ZM18.45 19.1475C19.4062 19.4175 20.385 19.5862 21.375 19.6537V21.33C19.89 21.2287 18.4612 20.9362 17.1 20.4862L18.45 19.1475ZM8.4375 3.375H4.5C3.88125 3.375 3.375 3.88125 3.375 4.5C3.375 15.0638 11.9362 23.625 22.5 23.625C23.1187 23.625 23.625 23.1187 23.625 22.5V18.5737C23.625 17.955 23.1187 17.4487 22.5 17.4487C21.105 17.4487 19.7437 17.2238 18.4838 16.8075C18.3713 16.7625 18.2475 16.7513 18.135 16.7513C17.8425 16.7513 17.5612 16.8638 17.3363 17.0775L14.8612 19.5525C11.6775 17.9212 9.0675 15.3225 7.4475 12.1388L9.9225 9.66375C10.2375 9.34875 10.3275 8.91 10.2037 8.51625C9.7875 7.25625 9.5625 5.90625 9.5625 4.5C9.5625 3.88125 9.05625 3.375 8.4375 3.375Z"
                                            fill="white" />
                                    </svg>

                                </span>
                                <h5>{{ __('Phone Number') }}</h5>
                                <p>
                                    <a href="tel:+966 55 032 9065">+966 55 032 9065</a><br />
                                </p>
                            </li>
                            <li>
                                <span class="icon">
                                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.75 6.75C24.75 5.5125 23.7375 4.5 22.5 4.5H4.5C3.2625 4.5 2.25 5.5125 2.25 6.75V20.25C2.25 21.4875 3.2625 22.5 4.5 22.5H22.5C23.7375 22.5 24.75 21.4875 24.75 20.25V6.75ZM22.5 6.75L13.5 12.375L4.5 6.75H22.5ZM22.5 20.25H4.5V9L13.5 14.625L22.5 9V20.25Z"
                                            fill="white" />
                                    </svg>

                                </span>
                                <h5>{{ __('Our Email') }}</h5>
                                <p>
                                    <a href="mailto:support@wosul.sa">support@wosul.sa</a><br />
                                    <a href="mailto:info@wosul.sa">info@wosul.sa</a>
                                </p>
                            </li>
                            <li>
                                <span class="icon">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.044 1.99487C8.26868 1.99697 6.56665 2.70315 5.3113 3.95851C4.05594 5.21386 3.34976 6.91589 3.34766 8.69124C3.34554 10.142 3.81944 11.5535 4.69668 12.7091C4.69668 12.7091 4.8793 12.9495 4.90913 12.9842L10.044 19.0402L15.1814 12.9812C15.2081 12.9489 15.3914 12.7091 15.3914 12.7091L15.392 12.7072C16.2688 11.5522 16.7425 10.1414 16.7404 8.69124C16.7383 6.91589 16.0321 5.21386 14.7768 3.95851C13.5214 2.70315 11.8194 1.99697 10.044 1.99487ZM10.044 11.1263C9.56242 11.1263 9.09163 10.9835 8.69119 10.7159C8.29075 10.4483 7.97865 10.068 7.79434 9.62309C7.61004 9.17814 7.56182 8.68854 7.65578 8.21618C7.74973 7.74383 7.98165 7.30995 8.32219 6.9694C8.66274 6.62886 9.09662 6.39694 9.56898 6.30298C10.0413 6.20903 10.5309 6.25725 10.9759 6.44155C11.4208 6.62585 11.8011 6.93796 12.0687 7.3384C12.3363 7.73884 12.4791 8.20963 12.4791 8.69124C12.4783 9.3368 12.2215 9.9557 11.765 10.4122C11.3085 10.8687 10.6896 11.1255 10.044 11.1263Z"
                                            fill="#fff" />
                                    </svg>

                                </span>
                                <h5>{{ __('Our Location') }}</h5>
                                <p>{{ __('Anas Ibn Malik Road,') }} <br />
                                    {{ __('Al Malqa,') }}<br />
                                    {{ __('Riyadh 13524,') }}<br />
                                    {{ __('Saudi Arabia') }}</p>
                            </li>
                        </ul>
                        <div class="contact-follow">
                            <h4>{{ __('Follow Us') }} :</h4>
                            <ul class="contact-list-social">
                                <li><a href="https://www.facebook.com/WOSULPOS/" target="_blank"><svg width="13" height="21" viewBox="0 0 13 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.3401 11.8125L11.9177 8.01199H8.30578V5.54572C8.30578 4.50598 8.81034 3.49248 10.428 3.49248H12.0701V0.256758C12.0701 0.256758 10.58 0 9.15525 0C6.18068 0 4.23637 1.82027 4.23637 5.11547V8.01199H0.929901V11.8125H4.23637V21H8.30578V11.8125H11.3401Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                                <li><a href="https://www.instagram.com/wosulpos/" target="_blank"><svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.37695 1.066C9.54209 1.012 9.91358 1 12.8816 1C15.8496 1 16.2211 1.013 17.3852 1.066C18.5494 1.119 19.344 1.306 20.0393 1.577C20.7674 1.854 21.4279 2.287 21.9743 2.847C22.5305 3.396 22.9596 4.06 23.2338 4.794C23.504 5.494 23.6887 6.294 23.7423 7.464C23.796 8.639 23.8079 9.013 23.8079 12C23.8079 14.988 23.795 15.362 23.7423 16.535C23.6897 17.705 23.504 18.505 23.2338 19.205C22.9596 19.9391 22.5298 20.6042 21.9743 21.154C21.4279 21.714 20.7674 22.146 20.0393 22.422C19.344 22.694 18.5494 22.88 17.3872 22.934C16.2211 22.988 15.8496 23 12.8816 23C9.91358 23 9.54209 22.987 8.37695 22.934C7.21478 22.881 6.42014 22.694 5.72483 22.422C4.99567 22.146 4.33502 21.7133 3.78888 21.154C3.23301 20.6047 2.80285 19.9399 2.52838 19.206C2.2592 18.506 2.07444 17.706 2.0208 16.536C1.96717 15.361 1.95525 14.987 1.95525 12C1.95525 9.012 1.96816 8.638 2.0208 7.466C2.07345 6.294 2.2592 5.494 2.52838 4.794C2.80325 4.06008 3.23374 3.39531 3.78988 2.846C4.33524 2.2865 4.99522 1.85344 5.72384 1.577C6.41915 1.306 7.21379 1.12 8.37595 1.066H8.37695ZM17.2968 3.046C16.1446 2.993 15.7989 2.982 12.8816 2.982C9.96424 2.982 9.61857 2.993 8.46634 3.046C7.40053 3.095 6.82243 3.274 6.43703 3.425C5.92746 3.625 5.56292 3.862 5.1805 4.247C4.81799 4.60205 4.539 5.03428 4.364 5.512C4.21402 5.9 4.03621 6.482 3.98754 7.555C3.9349 8.715 3.92397 9.063 3.92397 12C3.92397 14.937 3.9349 15.285 3.98754 16.445C4.03621 17.518 4.21402 18.1 4.364 18.488C4.53883 18.965 4.81794 19.398 5.1805 19.753C5.53312 20.118 5.96322 20.399 6.43703 20.575C6.82243 20.726 7.40053 20.905 8.46634 20.954C9.61857 21.007 9.96325 21.018 12.8816 21.018C15.7999 21.018 16.1446 21.007 17.2968 20.954C18.3626 20.905 18.9407 20.726 19.3261 20.575C19.8357 20.375 20.2002 20.138 20.5826 19.753C20.9452 19.398 21.2243 18.965 21.3991 18.488C21.5491 18.1 21.7269 17.518 21.7756 16.445C21.8282 15.285 21.8392 14.937 21.8392 12C21.8392 9.063 21.8282 8.715 21.7756 7.555C21.7269 6.482 21.5491 5.9 21.3991 5.512C21.2005 4.999 20.9651 4.632 20.5826 4.247C20.23 3.88207 19.8006 3.60121 19.3261 3.425C18.9407 3.274 18.3626 3.095 17.2968 3.046ZM11.486 15.391C12.2654 15.7176 13.1333 15.7617 13.9414 15.5157C14.7495 15.2697 15.4477 14.7489 15.9167 14.0422C16.3858 13.3356 16.5966 12.4869 16.5131 11.6411C16.4297 10.7953 16.0572 10.005 15.4592 9.405C15.078 9.02148 14.6171 8.72781 14.1096 8.54515C13.6022 8.36248 13.0608 8.29536 12.5245 8.34862C11.9882 8.40187 11.4702 8.57418 11.008 8.85313C10.5458 9.13208 10.1507 9.51074 9.85131 9.96185C9.55189 10.413 9.35554 10.9253 9.2764 11.462C9.19726 11.9986 9.2373 12.5463 9.39363 13.0655C9.54996 13.5847 9.81871 14.0626 10.1805 14.4647C10.5423 14.8668 10.9882 15.1832 11.486 15.391ZM8.91035 8.002C9.43186 7.47697 10.051 7.0605 10.7324 6.77636C11.4137 6.49222 12.144 6.34597 12.8816 6.34597C13.6191 6.34597 14.3494 6.49222 15.0308 6.77636C15.7122 7.0605 16.3313 7.47697 16.8528 8.002C17.3743 8.52702 17.788 9.15032 18.0702 9.8363C18.3525 10.5223 18.4977 11.2575 18.4977 12C18.4977 12.7425 18.3525 13.4777 18.0702 14.1637C17.788 14.8497 17.3743 15.473 16.8528 15.998C15.7996 17.0583 14.3711 17.654 12.8816 17.654C11.3921 17.654 9.96358 17.0583 8.91035 15.998C7.85712 14.9377 7.26542 13.4995 7.26542 12C7.26542 10.5005 7.85712 9.06234 8.91035 8.002ZM19.7433 7.188C19.8725 7.06527 19.976 6.91768 20.0476 6.75397C20.1191 6.59027 20.1573 6.41377 20.1599 6.23493C20.1625 6.05609 20.1294 5.87855 20.0626 5.71281C19.9958 5.54707 19.8967 5.39651 19.7711 5.27004C19.6455 5.14357 19.4959 5.04376 19.3313 4.97652C19.1666 4.90928 18.9903 4.87598 18.8127 4.87858C18.635 4.88119 18.4597 4.91965 18.2971 4.9917C18.1345 5.06374 17.9879 5.1679 17.866 5.298C17.6289 5.55103 17.4991 5.88712 17.5041 6.23493C17.5091 6.58274 17.6486 6.91488 17.8929 7.16084C18.1373 7.40681 18.4672 7.54723 18.8127 7.5523C19.1581 7.55737 19.492 7.42669 19.7433 7.188Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                                <li><a href="https://www.linkedin.com/company/wosul/" target="_blank"><svg width="20" height="24" viewBox="0 0 20 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.47679 21H0.330357V6.97971H4.47679V21ZM2.40134 5.06721C1.07545 5.06721 0 3.91408 0 2.52189C9.49017e-09 1.85318 0.252998 1.21185 0.703336 0.738991C1.15367 0.266136 1.76446 0.000488281 2.40134 0.000488281C3.03821 0.000488281 3.649 0.266136 4.09934 0.738991C4.54968 1.21185 4.80268 1.85318 4.80268 2.52189C4.80268 3.91408 3.72679 5.06721 2.40134 5.06721ZM19.9955 21H15.858V14.175C15.858 12.5485 15.8268 10.4625 13.7022 10.4625C11.5464 10.4625 11.2161 12.2297 11.2161 14.0578V21H7.07411V6.97971H11.0509V8.89221H11.1089C11.6625 7.79065 13.0147 6.62815 15.0321 6.62815C19.2286 6.62815 20 9.52971 20 13.2985V21H19.9955Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                                <li><a href="https://twitter.com/wosulPOS" target="_blank"><svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M23.7235 4.93659C22.8941 5.30659 22.0031 5.55659 21.0664 5.66959C22.0329 5.08738 22.756 4.17105 23.1007 3.09159C22.1927 3.63458 21.1989 4.01678 20.1625 4.22159C19.4656 3.47245 18.5425 2.97591 17.5365 2.80906C16.5306 2.64221 15.4981 2.81438 14.5993 3.29884C13.7005 3.7833 12.9857 4.55295 12.5659 5.48829C12.1461 6.42363 12.0448 7.47234 12.2777 8.47159C10.4378 8.37858 8.63785 7.89714 6.99473 7.05849C5.3516 6.21985 3.902 5.04275 2.73999 3.60359C2.34267 4.29359 2.11421 5.09359 2.11421 5.94559C2.11377 6.71258 2.30138 7.46783 2.6604 8.14432C3.01943 8.82081 3.53877 9.39763 4.17233 9.82359C3.43756 9.80005 2.719 9.60017 2.07646 9.24059V9.30059C2.07639 10.3763 2.446 11.419 3.12259 12.2516C3.79918 13.0842 4.74107 13.6555 5.78843 13.8686C5.10681 14.0543 4.39219 14.0817 3.69853 13.9486C3.99403 14.8742 4.56965 15.6836 5.34479 16.2635C6.11994 16.8434 7.05581 17.1648 8.02138 17.1826C6.38226 18.478 4.35797 19.1807 2.27413 19.1776C1.905 19.1777 1.53618 19.156 1.16958 19.1126C3.28479 20.4818 5.74705 21.2084 8.26176 21.2056C16.7744 21.2056 21.428 14.1076 21.428 7.95159C21.428 7.75159 21.423 7.54959 21.4141 7.34959C22.3193 6.69056 23.1006 5.87448 23.7215 4.93959L23.7235 4.93659Z"
                                                fill="white" />
                                        </svg>
                                    </a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="contact-form-box">
                            <h2>{{ __('Send Us Message') }}</h2>
                            <form>
                                <div class="form-group">
                                    <label class="label">{{ __('Your Name') }} <em>*</em></label>
                                    <input type="text" placeholder="{{ __('First Name and Last Name') }}"
                                        class="form-control" required />
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
                                                class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Email Address') }} <em>*</em></label>
                                    <input type="email" placeholder="{{ __('example@example.com') }}"
                                        class="form-control" required />
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Subject') }}</label>
                                    <input type="text" placeholder="{{ __('Enter Subject') }}" class="form-control"
                                        required />
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Message') }}</label>
                                    <textarea class="form-control" placeholder="{{ __('Type Your Message') }}"></textarea>
                                </div>
                                <div class="form-submit-wrap text-center">
                                    <button type="submit"
                                        class="btn btn-primary btn-m-width">{{ __('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>



    </div>


    @include('includes/footer')


    <a href="javascript:void(0)" class="scrollToTop"><svg xmlns="http://www.w3.org/2000/svg" width="49.198"
            height="78.727" viewBox="0 0 49.198 78.727">
            <path id="arrow-up-long-solid"
                d="M47.761,28.055a4.918,4.918,0,0,1-6.958,0L29.526,16.784v57a4.92,4.92,0,0,1-9.84,0v-57L8.4,28.055A4.92,4.92,0,1,1,1.446,21.1L21.127,1.416a4.918,4.918,0,0,1,6.958,0L47.766,21.1A4.923,4.923,0,0,1,47.761,28.055Z"
                transform="translate(-0.005 0.025)" fill="currentColor" />
        </svg>
    </a>

    @include('includes/footerscript')


</body>

</html>
