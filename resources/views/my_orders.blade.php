<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>

<head>
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

        <section class="bg-gradient inner-banner">
            <div class="inner-banner-logo-light">
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7">
                        <div class="content">
                            <h2 data-aos="fade-up">{{ __('My Orders') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="plan-bg-shape"></div>
            <div class="container postition-static">
                <div class="plans-tabs">
<!--                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <h2 class="aos-init aos-animate">{{ __('Subscriptions') }}</h2>
                        </div>
                    </div>-->

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Subscriptions" role="tabpanel"
                            aria-labelledby="Subscriptions-tab">
                            <div class="row">
                                @forelse($data['orders'] as $order)
                                    <div class="col-md-6 col-lg-3 mb-3 mb-md-4">
                                        <a href="{{ route('my_order_detail',[App()->getLocale(),$order->id]) }}">
                                            <div class="plan-box">
                                                <h3>{{ __('Order No.:') }} {{ $order->order_number }}</h3>
                                                <div class="d-flex justify-content-between">@if($order->payment_status==0) <span class="badge bg-danger">{{ __('Fail') }}</span> @else <span class="badge bg-success">{{ __('Success') }}</span> @endif <p> {{ $order->created_at->format('d-m-Y') }} </p></div>
                                                <div class="plan-price"> {{ $order->total_amount }}<small> {{ __('SAR') }}</small></div>

                                                <p><b>{{ __('Payment Gateway:') }}</b>{{ $order->payment_constant }}</p>
                                                <p><b>{{ __('Location:') }} </b>{{ $order->address_line1 }}, {{ $order->city }}-{{ $order->zipcode }}, {{ $order->country }} </p>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="d-100 text-center"><h4>{{ __('There are no orders placed yet!') }}</h4></div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    {{ $data['orders']->links() }}
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


    <script>

    </script>
</body>

</html>