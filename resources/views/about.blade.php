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
    <section class="bg-gradient inner-banner">
        <div class="inner-banner-logo-light">
            <img src="{{ asset('website/images/bg-logo-light.png') }}" />
        </div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-7">
                    <div class="content">
                        <h2 data-aos="fade-up">
                            @if(App::getLocale() == 'ar')
                                في وصول نخدم قطاعات متنوعة من قطاعات التجزئة والمطاعم
                            @else
                                {{ __('Sectors') }}
                            @endif
                        </h2>
                        <ul class="breadcrumb-list" data-aos="fade-up">
                            <li>
                                <a href="index.html">{{__('Home')}}</a>
                            </li>
                            <li>{{__('Sectors')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="bg-light">
        <div class="container">
            <div class="title text-center mb-4" data-aos="fade-up">
                <h2>
                    @if (App::getLocale() == 'en')
                        A Solution For Every Need
                    @elseif(App::getLocale() == 'ar')
                        نقدم الحلول التقنية لكل احتياجات العميل
                    @endif
                </h2>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-md-8" data-aos="fade-up">
                    @if (App::getLocale() == 'en')
                        <p>We transform point of sale systems into simple and not complicated
                            systems and provide integrated solutions to suit any need, whatever
                            the business activity, in addition to a specialized support team,
                            Wosul working to change the method of selling in stores and make
                            the selling process easy and professional.</p>
                    @elseif(App::getLocale() == 'ar')
                        <p>
                            حولنا أنظمة البيع إلى أنظمة بسيطة مُجهزة بكل سهولة لخدمة تجربة العميل بجميع الحلول التقنية لأن تكون مناسبة لكل أحتياج مهما كان النشاط التجاري، بالأضافة إلى فريق دعم متخصص، وجدت وصول لتغيير أسلوب البيع في المتاجر وجعل عملية البيع أسهل وأكثر أحترافية
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="about-section pt-0">
        <div class="about-item">
            <div class="container">
                <div class="text" data-aos="fade-right">

                    <h2>
                        @if (App::getLocale() == 'en')
                            Wosul Point of sale System
                        @elseif(App::getLocale() == 'ar')
                            نظام وصول لنقاط البيع
                        @endif
                    </h2>
                    <p>
                        @if (App::getLocale() == 'en')
                            A cloud-based POS solution with minimal IT personnel dependencies.
                            Minimal to zero software maintenance required and avoid interruptions in
                            your operations.
                        @elseif(App::getLocale() == 'ar')
                            هذا النظام يتناسب مع أغلب قطاعات التجزئة كالمطاعم والمقاهي والبيع بالتجزئة والامتياز التجاري والتموينات والمحلات التجارية
                        @endif
                    </p>
                    {{-- <div class="mt-md-4"><a href="#" class="btn btn-primary">See more</a></div> --}}
                </div>
                <div class="img">
                    <img src="{{ asset('website/images/about-us/about-1.png') }}" alt="" class="w-100" />
                </div>
            </div>
        </div>
        <div class="about-item">
            <div class="container">
                <div class="text" data-aos="fade-left">
                    <h2>
                        @if (App::getLocale() == 'en')
                            wosul modern, advanced, fast, and easy-to-use system.
                        @elseif(App::getLocale() == 'ar')
                            وصول ايزي
                        @endif
                    </h2>
                    <p>
                        @if (App::getLocale() == 'en')
                            An integrated sales and management system.
                            You can login from anywhere.
                            Works on iPad and android devices
                            Keep selling even when the internet cuts.
                        @elseif(App::getLocale() == 'ar')
                            يتناسب هذا المنتج مع القطاعات التالية: الأسر المنتجة وعربات الأكل و الحلاقة والخدمات السريعة ومحلات الهدايا وخدمات السيارات والمحلات التجارية
                        @endif
                    </p>
                    {{-- <div class="mt-md-4"><a href="#" class="btn btn-primary">See more</a></div> --}}
                </div>
                <div class="img">
                    <img src="{{ asset('website/images/about-us/about-2.png') }}" alt="" class="w-100" />
                </div>
            </div>
        </div>
        <div class="about-item">
            <div class="container">
                <div class="text" data-aos="fade-right">
                    <h2>
                        @if (App::getLocale() == 'en')
                            PAYMENT gateway
                        @elseif(App::getLocale() == 'ar')
                            البوابة الإلكترونية للدفع
                        @endif
                    </h2>
                    <p>
                        @if (App::getLocale() == 'en')
                            Enhance your operations with solutions that will bring a satisfactory
                            experience, both for you and your customers
                            through a special trip for your restaurant that includes kitchen, menu and
                            cashier
                        @elseif(App::getLocale() == 'ar')
                            صمم هذا النظام للمتاجر الالكرتونية والتطبيقات و المنشأت الحكومية و تجار التجزئة عبر الانترنت
                        @endif
                    </p>
                    {{-- <div class="mt-md-4"><a href="#" class="btn btn-primary">See more</a></div> --}}
                </div>
                <div class="img">
                    <img src="{{ asset('website/images/about-us/about-3.png') }}" alt="" class="w-100" />
                </div>
            </div>
        </div>
        <div class="about-item">
            <div class="container">
                <div class="text" data-aos="fade-left">
                    <h2>
                        @if (App::getLocale() == 'en')
                            E-invoice
                        @elseif(App::getLocale() == 'ar')
                            الفاتورة الإلكترونية E-invoice
                        @endif
                    </h2>
                    <p>
                        @if (App::getLocale() == 'en')
                            Wosul is a platform to manage sales, inventory, customers, suppliers,
                            .and issue E-invoices
                        @elseif(App::getLocale() == 'ar')
                            يتوافق منتج الفاتورة الإكترونية مع الشركات والمقاولات وبعض المحلات التجارية
                        @endif
                    </p>
                    {{-- <div class="mt-md-4"><a href="#" class="btn btn-primary">See more</a></div> --}}
                </div>
                <div class="img">
                    <img src="{{ asset('website/images/about-us/about-4.png') }}" alt="" class="w-100" />
                </div>
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


</body>

</html>
