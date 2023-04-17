<!doctype html>
<html lang="en">

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
        <section class="bg-gradient inner-banner">
            <div class="inner-banner-logo-light">
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7">
                        <div class="content">
                            <h2 data-aos="fade-up">{{ __('Search Your Question') }}</h2>
                            <div class="banner-search">
                                <form>
                                    <div class="banner-search-field">
                                        <input type="text" placeholder="{{ __('Type your question here..') }}"
                                            class="form-control" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="container">
                <div class="accordion accordion-style-1" id="accordionExample">
                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1"
                            aria-expanded="true" aria-controls="faq1">
                            {{ __('What does WOSUL system offer?') }}
                        </button>
                        <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('It offers a Point of Sale package and an integrated package') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            {{ __('What does a Point of Sale package consist of?') }}
                        </button>
                        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Offers (sales - purchases - inventory - control panel - statistics)') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                            {{ __('What does WOSUL plus offer ?') }}
                        </button>
                        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Provides (Sales - Purchases - Inventory - Control Panel - Statistics - Finance - Human Resources)') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                            {{ __('What does the subscription fee include?') }}
                        </button>
                        <div id="faq5" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('IPad, stand, Epson printer, cash box and subscription to Wosul system for one year') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                            {{ __('Can I subscribe monthly?') }}
                        </button>
                        <div id="faq6" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes is available') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                            {{ __('Do I need to pay an annual fee?') }}
                        </button>
                        <div id="faq7" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('You need to pay the annual subscription amount') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                            {{ __('Do you have offers for trolleys?') }}
                        </button>
                        <div id="faq8" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes, we have offers for them (as per requirements)') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
                            {{ __('How long does it take to activate the subscription?') }}
                        </button>
                        <div id="faq9" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Not more than 5 days') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq10" aria-expanded="false" aria-controls="faq10">
                            {{ __('Do I need to pay subscription fees to the other branch?') }}
                        </button>
                        <div id="faq10" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('If the stock is linked, you do not need to pay other fees') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq11" aria-expanded="false" aria-controls="faq11">
                            {{ __('Will I pay training fees?') }}
                        </button>
                        <div id="faq11" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Free delivery, installation and training') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq12" aria-expanded="false" aria-controls="faq12">
                            {{ __('Can you make technical support remotely?') }}
                        </button>
                        <div id="faq12" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes, we are able to troubleshoot 95% of errors and issues remotely, by providing support via chat and phone call. In extreme cases, We will provide support by visiting your site') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq13" aria-expanded="false" aria-controls="faq13">
                            {{ __('Can I track my sales remotely?') }}
                        </button>
                        <div id="faq13" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes, you can.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq14" aria-expanded="false" aria-controls="faq14">
                            {{ __('What does the access system support which languages?') }}
                        </button>
                        <div id="faq14" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Arabic - English') }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq15" aria-expanded="false" aria-controls="faq15">
                            {{ __('Do you offer barcode reader?') }}
                        </button>
                        <div id="faq15" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes we do offer barcode') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq16" aria-expanded="false" aria-controls="faq16">
                            {{ __('Can I use my own devise ?') }}
                        </button>
                        <div id="faq16" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yes you can your own devise') }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq17" aria-expanded="false" aria-controls="faq17">
                            {{ __('Do I need to pay for site visit support?') }}
                        </button>
                        <div id="faq17" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Our visit is free') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq18" aria-expanded="false" aria-controls="faq18">
                            {{ __('What types of reports can I access through Wosul?') }}
                        </button>
                        <div id="faq18" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Some reports include: Sales, Inventory, Customer, and Employee and can be posted to excel') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq19" aria-expanded="false" aria-controls="faq19">
                            {{ __('Does the access system offer an accounting unit?') }}
                        </button>
                        <div id="faq19" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('It offers an integrated accounting system, in case you subscribe to the integrated package') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq20" aria-expanded="false" aria-controls="faq20">
                            {{ __('What if the internet connection disconnect at the store; Will my operations stop?') }}
                        </button>
                        <div id="faq20" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('No; An access system is designed to function; Whether there is an internet connection or not, across all branches; So that you can continue your activity and collect your payments.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq21" aria-expanded="false" aria-controls="faq21">
                            {{ __('What kind of printers are compatible with WOSUL?') }}
                        </button>
                        <div id="faq21" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Access system compatible with Epson printer') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq22" aria-expanded="false" aria-controls="faq22">
                            {{ __('What kind of businesses does WOSUL support?') }}
                        </button>
                        <div id="faq22" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Mobile vehicles, restaurants, cafes and retailers completely') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq23" aria-expanded="false" aria-controls="faq23">
                            {{ __('Can the secretary of my warehouse; Stock requisitioned from main warehouse with arrival') }}
                        </button>
                        <div id="faq23" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __('Yeah; He can send an order, and get it by transferring the transaction.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq24" aria-expanded="false" aria-controls="faq24">
                            {{ __('Will you send me notifications about renewing subscriptions?') }}
                        </button>
                        <div id="faq24" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p> {{ __('WOSUL customer service team will contact you, when your subscriptions are due for renewal.') }}
                                </p>
                            </div>
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
