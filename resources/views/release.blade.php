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

        <section class=" main-banner">
            <div class="row justify-content-between align-items-center g-0">
                <div class="col-md-5">
                    <div class="content">
                        
                        <h3 class="text-muted">Wosul POS - Releases</h3>
                        @forelse($data['releases'] as $release)

                        <div class="row">
                            {!! $release->release_notes !!}
                        </div>

                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>


    @include('includes/footer', ['showInquiryForm' => false])


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
