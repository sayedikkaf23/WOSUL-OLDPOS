<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>

    @if (App::getLocale() == 'ar')
        <style>
            .multi-language .ui-selectmenu-button.ui-button:before {
                background: url("{{ asset('website/images/language/saudi-flag.svg') }}") no-repeat center center;
            }
        </style>
    @else
        <style>
            .multi-language .ui-selectmenu-button.ui-button:before {
                background: url("{{ asset('website/images/language/uk-flag.svg') }}") no-repeat center center;
            }
        </style>
    @endif
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

        <section class="bg-gradient1 main-banner">
            <div class="bg-logo-light">
                {{-- <img src="{{ asset('website/images/bg-logo-light.png') }} " /> --}}
            </div>
            <div class="container pt-5">
                <div class="row justify-content-between align-items-center g-0">
                    <div class="col-md-5">
                        <div class="content">
                            @if (App::getLocale() == 'en')
                                <h2>{{ __('With WOSUL..') }}</h2>
                                <p>{{ __('Make Your Interactions With Clients,') }}
                                    <br />{{ __('Products And Your Facility Numbers') }} <br />{{ __('Much Easier.') }}
                                </p>
                            @elseif(App::getLocale() == 'ar')
                                <div class="row">
                                    <div class="col-5">
                                        <p style="font-size:30px;">حلول تقنية تنقل </p>
                                        <p style="font-size:30px;">تجارتك إلى أفق </p>
                                    </div>
                                    <div class="col-6 pt-3"><p style="font-size:55px;font-weight:bold;"> أوسع </p></div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <span style="font-size:40px;">نظام وصول</span>
                                    </div>
                                </div>
                            @endif
                            <!--<div class="row mt-5">
                                <div class="col-12">
                                    تمكين الكاشير بواسطة نظام سحابي احترافي ومتكامل لنقاط البيع، ليصبح عملية نموذجية تهيئ الحلول المناسبة لاحتياجات المشروع، وتمكن التاجر من التوسع والوصول إلى العملاء بطريقة سريعة نحو الهدف المحدد.
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="" data-aos-delay="400">
                            <img src=" {{ asset('website/images/home-banner.png') }}" class="w-100 d-block mt-4 mt-4" />
                            {{-- <a class="banner-video-toggle" href="website/video/wosul-intro.mp4" data-fancybox="banner">
                                <span>
                                    <svg width="30" height="34" viewBox="0 0 30 34" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M27.1864 13.5361C29.8531 15.0757 29.8531 18.9247 27.1864 20.4643L6.13559 32.618C3.46892 34.1576 0.135584 32.2331 0.135584 29.1539L0.135585 4.8465C0.135585 1.76729 3.46892 -0.157209 6.13559 1.38239L27.1864 13.5361Z"
                                            fill="#3767CD" />
                                    </svg>
                                </span>
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="service-step-section">
            <div class="container">
                <div class="service-step-item-wrap">
                    @if (isset($data['website_services']))
                        @foreach ($data['website_services'] as $website_service)
                            <div class="row align-items-center service-step-item">
                                @if ($loop->iteration % 2 != 0)
                                    <div class="col-md-5">
                                        <div class="img">
                                            <img src="{{ $website_service->image_path }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="text">
                                            <!-- <div class="number-icon">
                                                <img
                                                    src="{{ asset('website/images/number-' . $loop->iteration . '.svg') }}" />
                                            </div> -->
                                            <h2 data-aos="fade-left">
                                                {{ App::getLocale() == 'en' ? $website_service->title : $website_service->title_ar }}
                                            </h2>
                                            <p data-aos="fade-left">
                                                {{ App::getLocale() == 'en' ? $website_service->description : $website_service->description_ar }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-7 order-2 order-md-1">
                                        <div class="text">
                                            <!-- <div class="number-icon">
                                                <img
                                                    src="{{ asset('website/images/number-' . $loop->iteration . '.svg') }}" />
                                            </div> -->
                                            <h2 data-aos="fade-left">
                                                {{ App::getLocale() == 'en' ? $website_service->title : $website_service->title_ar }}
                                            </h2>
                                            <p data-aos="fade-left">
                                                {{ App::getLocale() == 'en' ? $website_service->description : $website_service->description_ar }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-5 order-1 order-md-2">
                                        <div class="img">
                                            <img src="{{ $website_service->image_path }}" />
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        {{-- <section class="bg-gradient">
            <div class="about-bg jarallax" style="background: url('website/images/Rectangle 142.jpg')"></div>
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="title">
                            <h5>{{ __('About Us') }}</h5>
                            <h2> {{ __('Welcome to Wosul') }} </h2>
                        </div>
                        <p class="text-big text-center text-md-start">
                            {{ __('Offers an easy-to-use system associated with inventory management support And points of sale with an unlimited number of branches and with high efficiency to provide you with comprehensive and accurate statistics to form performance measures') }}
                        </p>
                        <ul class="icon-list icon-list-light mt-4">
                            <li>
                                <div class="icon">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_596_1873)">
                                            <path
                                                d="M10.8669 21.7173C13.6934 21.7173 16.4041 20.5884 18.4027 18.5791C20.4014 16.5698 21.5242 13.8446 21.5242 11.003C21.5242 8.16138 20.4014 5.43616 18.4027 3.42684C16.4041 1.41752 13.6934 0.288696 10.8669 0.288696C8.0404 0.288696 5.32967 1.41752 3.33104 3.42684C1.33241 5.43616 0.209595 8.16138 0.209595 11.003C0.209595 13.8446 1.33241 16.5698 3.33104 18.5791C5.32967 20.5884 8.0404 21.7173 10.8669 21.7173ZM12.1058 9.11191L10.7736 15.4132C10.6804 15.8686 10.8123 16.1271 11.1786 16.1271C11.4371 16.1271 11.8274 16.0333 12.0925 15.7976L11.9752 16.3548C11.5929 16.8182 10.7497 17.1557 10.0236 17.1557C9.08712 17.1557 8.6888 16.5905 8.94724 15.3891L9.93038 10.7445C10.0156 10.3521 9.93837 10.2101 9.54805 10.115L8.94724 10.0066L9.05648 9.49629L12.1071 9.11191H12.1058ZM10.8669 7.65477C10.5136 7.65477 10.1747 7.51366 9.92491 7.2625C9.67508 7.01133 9.53473 6.67068 9.53473 6.31548C9.53473 5.96028 9.67508 5.61963 9.92491 5.36846C10.1747 5.1173 10.5136 4.9762 10.8669 4.9762C11.2202 4.9762 11.559 5.1173 11.8089 5.36846C12.0587 5.61963 12.1991 5.96028 12.1991 6.31548C12.1991 6.67068 12.0587 7.01133 11.8089 7.2625C11.559 7.51366 11.2202 7.65477 10.8669 7.65477Z"
                                                fill="currentColor" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_596_1873">
                                                <rect width="21.3146" height="21.4286" fill="white"
                                                    transform="translate(0.209595 0.288696)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <h4> {{ __('Our Domains') }} </h4>
                                <p>{{ __('Restaurants, Cafes, Food Courts') }}</p>
                            </li>
                        </ul>
                        <div class="text-center text-md-start mt-4 mt-md-3">
                            <a href="#inquiry-now" class="btn btn-light"
                               >{{ __('Know More') }}</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
        </section> --}}



        <section>
            <div class="container">
                <div class="row">
                    @if (isset($data['website_features']))
                        @foreach ($data['website_features'] as $website_feature)
                            <div class="col-md-6 col-lg-3">
                                <div class="service-box">
                                    <div class="icon">
                                        <img src="{{ $website_feature->image_path }}" />
                                    </div>
                                    <h3>{{ App::getLocale() == 'en' ? $website_feature->title : $website_feature->title_ar }}
                                    </h3>
                                    <p>{{ App::getLocale() == 'en' ? $website_feature->description : $website_feature->description_ar }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </section>
        
      
        <section class="pt-0 pb-0">
            <div class="container">
                <div class="title-box-wrap">
                    <div class="row justify-content-between align-items-end">
                        <div class="col-md-auto">
                            <div class="title mb-0">
                                <h2>{{ __('Our Pricing Plans') }}</h2>
                            </div>
                        </div>
                        <div class="col-md-auto text-center text-md-start mt-0 mt-md-0" data-aos="fade-left">
                            <a href="{{ route('pricing', app()->getLocale()) }}"
                                class="btn btn-primary btn-m-width">{{ __('View Plans') }}</a>
                        </div>
                    </div>
                </div>

              {{--   <div class="row">
                    <div class="achive-box-wrap">
                        <div class="row justify-content-center gx-lg-4">
                            @forelse($data['featured_subscriptions'] as $featured_subscription)
                            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                                <div class="achive-box h-100">
                                    <div class="text">
                                        <h3>{{ App::getLocale() == 'en' ? $featured_subscription->title : $featured_subscription->title_ar }}</h3>
                                        <ul>
                                            @isset($featured_subscription->features)
                                                @foreach ($featured_subscription->features as $feature)
                                                <li>{{ App::getLocale() == 'en' ? $feature->title : $feature->title_ar }}</li>
                                                @endforeach
                                            @endisset
                                        </ul>
                                        <h5>{{ App::getLocale() == 'en' ? $featured_subscription->short_description : $featured_subscription->short_description_ar }}
                                        </h5>
                                        <h6>{{ round($featured_subscription->amount, 0) }} SAR <small>/ Annually</small></h6>
                                    </div>
                                    <div class="action">
                                        <a href="{{ route('pricing',app()->getLocale()) }}" class="btn btn-primary">{{ __('ADD TO CART') }}</a>
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

                    {{-- @forelse($data['featured_subscriptions'] as $featured_subscription)
                        <div class="col-lg-6">

                            <div class="feature-box feature-box aos-init aos-animate"
                                style="background-image: url('website/images/Mask-group.png');height:600px;"
                               >
                                <h3> {{ App::getLocale() == 'en' ? $featured_subscription->title : $featured_subscription->title_ar }}
                                </h3>
                                <p>{{ App::getLocale() == 'en' ? $featured_subscription->short_description : $featured_subscription->short_description_ar }}
                                </p>
                                <hr />
                                <div class="feature-price">
                                    {{ round($featured_subscription->amount, 0) }} SAR <small>/ Annually</small>
                                </div>
                                <ul>

                                    @isset($featured_subscription->features)
                                        @foreach ($featured_subscription->features as $feature)
                                            <li>{{ App::getLocale() == 'en' ? $feature->title : $feature->title_ar }}
                                            </li>
                                        @endforeach
                                    @endisset
                                </ul>
                                <a href="#" class="btn btn-outline-light w-100 mb-2">{{ __('ADD TO CART') }}</a>
                                <a href="#" class="btn btn-light w-100">{{ __('BUY NOW') }}</a>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            {{ __('There are not featured packages available') }}
                        </div>
                    @endforelse --}}

                </div>
 
            </div>
        </section>

<!--


        <section class="light-bg zakat-section">
            <div class="zakat-shape"><img src="{{ asset('website/images/zakat-shape.svg') }}" alt="" /></div>
            <div class="container">
                <div class="title text-center">
                    <h2> {{ __('Certified by') }} <br /> {{ __('Zakat, Tax and Customs Authority') }}</h2>
                </div>
                <div class="row  align-items-center">
                    <div class="col-md-6 mx-auto order-2 order-md-1">
                        <div class="img">
                            <img src="{{ asset('website/images/wosul-bill.png') }}" />
                        </div>
                    </div>
                    <div class="col-md-5 mx-auto order-1 order-md-2">
                        <div class="zakat-img" data-aos="fade-left"><img
                                src="{{ asset('website/images/zakat.png') }}" alt="" /></div>
                        <div class="text" data-aos="fade-left">
                            <h2>{{ __('Electronic Invoice') }}</h2>
                            <ul>
                                <li>{{ __('Print electronic invoices compatible with the Zakat, Tax and Coustoms Authority') }}
                                </li>
                                <li>{{ __('Unlimited digital invoicing') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="pt-0">
            <div class="video-bg-box jarallax" data-video-src="mp4:website/video/wosul-food-event-2022.mp4"
                data-aos="zoom-out-down">
            </div>
            <div class="video-box-text">
                <div class="container">
                    <div class="inner bg-gradient">
                        <h2>{{ __('We Deliver What We Promise, Let The Journey Begin..') }} </h2>
                        <a href="{{ route('pricing', app()->getLocale()) }}"
                            class="btn btn-light mt-3 mt-md-4">{{ __('View Plans & Pricing') }}</a>
                    </div>
                </div>
            </div>
        </section>

-->


        {{-- <section class="pt-0">
            <div class="container">
                <div class="title">
                    <h2>{{ __('What Our Customers') }} {{ __('Say About Us') }}</h2>
                </div>
            </div>
            <div class="review-carousel owl-carousel owl-theme nav-style-arrow nav-style-arrow-top-right">

                @forelse($data['website_reviews'] as $website_review)
                    <div class="item">
                        <div class="review-box">
                            <div class="img">
                                <img src="{{ $website_review->image_path }}" />
                            </div>
                            <h4> {{ App::getLocale() == 'en' ? $website_review->name : $website_review->name_ar }}
                            </h4>
                            <div class="rating-star">
                                <span style="width:{{ $website_review->rating_percentage }}%;"></span>
                            </div>
                            <p>{{ App::getLocale() == 'en' ? $website_review->review : $website_review->review_ar }}
                            </p>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>
        </section> --}}


<!--
        <section class="light-bg counter-section-wrap">
            <div class="container">
                <div class="cout-wrapper" id="counter">
                    <div class="row g-0">
                        <div class="col-6 col-md-3">
                            <div class="item">
                                <h4><span class="counter-value" data-count="900">900</span>+</h4>
                                <p>{{ __('Merchants') }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="item">
                                <h4><span class="counter-value" data-count="25990">25990</span>+</h4>
                                <p>{{ __('Orders') }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="item">
                                <h4><span class="counter-value" data-count="195000">195000</span>+</h4>
                                <p>{{ __('Sales') }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="item">
                                <h4><span class="counter-value" data-count="2500">2500</span>+</h4>
                                <p>{{ __('Users') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>{{ __('Client Gallery') }}</h2>
                            <p>{{ __('Let us show you some workplace photos from our clients, you can see their satisfaction about our system. You can also become one of them, you know what to do!') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="owl-carousel owl-theme gallery-carousel nav-style-arrow nav-style-arrow-top-right">

                @forelse ($data['website_gallery'] as $website_gallery)
                    <div class="item">
                        @foreach ($website_gallery as $value)
                            <div class="gallery-box">
                                <a href="{{ env('WEBSITE_MEDIA_URL') . '/storage/website_gallery/' . $value['image'] }}"
                                    class="d-block" data-fancybox="gallery">
                                    <div class="img">
                                        <img src="{{ env('WEBSITE_MEDIA_URL') . '/storage/website_gallery/' . $value['image'] }}"
                                            alt="" />
                                        <span><img src="images/zoom-in.svg" alt="" /></span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- <div class="item">
                    <div class="gallery-box gallery-box-big">
                        <a href="{{ asset('website/images/gallery/details-4.png') }}" class="d-block"
                        data-fancybox="gallery">
                        <div class="img">
                            <img src="{{ asset('website/images/gallery/details-4.png') }}" alt="" />
                            <span><img src="images/zoom-in.svg" alt="" /></span>
                        </div>
                        </a>
                    </div>
                </div> --}}
                @empty
                @endforelse

            </div>


        </section>
-->

        <section class="logos-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 me-auto">
                        <div class="text">
                            <div class="title" data-aos="fade-left">
                                <h2>{{ __('Our Clients') }}</h2>
                                <p>{{ __('We are expanding quickly, Our clients bases is becoming more and more wide. Here\'s few of the renowned clients from almost all domains.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="owl-carousel owl-theme logos-carousel nav-style-arrow nav-style-arrow-left">

                            @foreach ($data['website_clients'] as $website_clients)
                                <div class="item">
                                    @foreach ($website_clients as $website_client)
                                        <div class="logos-box">
                                            <div class="img">
                                                <img src="{{ $website_client->image_path }}" alt="" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="row align-items-center ourclient-wrap">
                    <div class="col-md-4 ms-auto order-1 order-md-2">
                        <div class="text" data-aos="fade-left">
                            <div class="title">
                                <h2>{{ __('Our Partners') }}</h2>
                                <p>{{ __('We are having range of partners from each and every domain such as finanace, digital services etc.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 order-2 order-md-1">
                        <div class="owl-carousel owl-theme logos-carousel nav-style-arrow nav-style-arrow-right">
                            @foreach ($data['website_partners'] as $website_partners)
                                <div class="item">
                                    @foreach ($website_partners as $website_partner)
                                        <div class="logos-box">
                                            <div class="img">
                                                <img src="{{ $website_partner->image_path }}" alt="" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
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
