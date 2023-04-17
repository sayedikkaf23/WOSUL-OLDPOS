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
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7">
                        <div class="content">
                            <h2 data-aos="fade-up"> {{ __('Marketplace') }} </h2>
                            <p>{{ __('We Partner and work with Top Companies and startups to deliver the most meaningful and rewarding value to our merchants.') }}
                            </p>
                            <div class="mt-4 mt-md-4">
                                <a href="{{ route('pricing', app()->getLocale()) }}"
                                    class="btn btn-light">{{ __('Enroll to Develop with Wosul') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="pt-5">
            <div class="container pt-3">
                <div class="row">
                    @forelse($data['marketplaces'] as $marketplace)
                        <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                            <div class="market-place-box">
                                <div class="img" style="background-color:#4F018D;">
                                    <img src="{{ $marketplace->thumb_image_path }}" alt="" />
                                </div>
                                <div class="market-head">
                                    <h3><a
                                            href="#">{{ App::getLocale() == 'en' ? $marketplace->title : $marketplace->title_ar }}</a>
                                    </h3>
                                    <a href="{{ route('marketplace_detail', ['slack' => $marketplace->slack, 'lang' => app()->getLocale()]) }}"
                                        class="btn btn-primary btn-sm">{{ __('Learn More') }}</a>
                                </div>
                                <p>{{ App::getLocale() == 'en' ? $marketplace->short_description : $marketplace->short_description_ar }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">No Marketplaces Found!</div>
                    @endforelse

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
