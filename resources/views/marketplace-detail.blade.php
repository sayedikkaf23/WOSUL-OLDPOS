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
        @if (isset($data['marketplace']->banner_image) && $data['marketplace']->banner_image != '')
            <section class="stcpay-banner" style="background-image:url('images/stcpay-banner.jpg')">
                <div class="container">
                    <div class="content">
                        {{-- <h2 data-aos="fade-up">“A digital wallet for your daily payments wherever you are.”</h2>
                    <h5 data-aos="fade-up">Ahmed AlEnezi</h5> --}}
                        <h6 data-aos="fade-up">
                            {{ App::getLocale() == 'en' ? $data['marketplace']->title : $data['marketplace']->title_ar }}
                        </h6>
                    </div>
                </div>
            </section>
        @endif


        <section class="pt-5">
            <div class="container pt-3">
                <div class="row">
                    <div class="col-md-8">
                        <p>
                            {{ App::getLocale() == 'en' ? $data['marketplace']->long_description : $data['marketplace']->long_description_ar }}
                        </p>
                    </div>
                    @if (isset($data['marketplace']->specifications) && $data['marketplace']->specifications != null)
                        <div class="col-md-4">
                            <div class="sticky-details-sidebar">
                                @foreach ($data['marketplace']->specifications as $specification)
                                    <div class="item">
                                        <h4>{{ App::getLocale() == 'en' ? $specification->name : $specification->name_ar }}
                                        </h4>
                                        <p>{{ App::getLocale() == 'en' ? $specification->value : $specification->value_ar }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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


</body>

</html>
