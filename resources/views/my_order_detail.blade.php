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
                            <h2 data-aos="fade-up">{{ __('Order Detail') }}</h2>
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
                                <div class="col-md-12">
                                    <div class="container">
                                        <section class="card p-3">
                                            <div class="card-body">
                                                <!-- Invoice Company Details -->
                                                <div id="invoice-company-details" class="row">
                                                    <div class="col-md-3 col-sm-12 text-md-right">
                                                        <h5>{{ __('Order No.') }}:</h5>
                                                        <p class="pb-3">{{ $data['order']->order_number }}</p>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 text-md-right">
                                                        <h5>{{ __('Order Amount') }}:</h5>
                                                        <p class="pb-3">{{ $data['order']->total_amount }} <small>{{ __('SAR') }} </small></p>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12">
                                                        <h5>{{ __('Status') }}:</h5>
                                                        @if($data['order']->status==0) <span class="badge bg-danger">{{ __('Fail') }}</span> @else <span class="badge bg-success">{{ __('Success') }}</span> @endif
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 text-md-right">
                                                        <h5>{{ __('Order Date') }}:</h5>
                                                        <p class="pb-3">{{ $data['order']->created_at->format('d-m-Y') }}</p>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 text-md-right">
                                                        <h5>{{ __('Address') }}:</h5>
                                                        <p class="pb-3">{{ $data['order']->address_line1 }}, {{ $data['order']->city }} - {{ $data['order']->zipcode }}, Saudi Arabia</p>
                                                    </div>
                                                </div>
                                                <!--/ Invoice Company Details -->
                                                <!-- Invoice Items Details -->
                                                <div id="invoice-items-details" class="pt-2">
                                                    <div class="row">
                                                        <div class="table-responsive col-sm-12">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>{{ __('Item & Description') }}</th>
                                                                    <th class="text-end">{{ __('Amount') }}</th>
                                                                    <th class="text-end">{{ __('Quantity') }}</th>
                                                                    <th class="text-end">{{ __('Amount') }}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php $i = 1; $sub_total=0; @endphp
                                                                @if(isset($data['subscription']) && $data['subscription']->id>0)
                                                                <tr>
                                                                    <th scope="row">{{ $i }}</th>
                                                                    <td>
                                                                        <p><b>{{ __('Subscription') }}</b> - {{ $data['subscription']->subscription_title }}</p>
                                                                        <p class="text-muted">{{ date('d/m/Y',strtotime($data['subscription']->start_date)) }} to {{ date('d/m/Y',strtotime($data['subscription']->end_date)) }}</p>
                                                                    </td>
                                                                    <td class="text-end">{{ number_format($data['subscription']->basic_amount,2) }}</td>
                                                                    <td class="text-end">1</td>
                                                                    <td class="text-end">{{ number_format($data['subscription']->basic_amount,2) }}</td>
                                                                </tr>
                                                                    @php $sub_total= $sub_total + $data['subscription']->basic_amount;  $i++; @endphp
                                                                @endif
                                                                @if(isset($data['devices']))
                                                                @forelse($data['devices'] as $device)
                                                                    @php $base_amount = $device->basic_amount/ $device->quantity @endphp
                                                                    <tr>
                                                                        <th scope="row">{{ $i }}</th>
                                                                        <td>
                                                                            <p><b>{{ __('Device') }}</b> - {{ $device->device_name }}</p>
                                                                            <p class="text-muted"></p>
                                                                        </td>
                                                                        <td class="text-end">{{ number_format($base_amount,2) }}</td>
                                                                        <td class="text-end">{{ $device->quantity }}</td>
                                                                        <td class="text-end">{{ number_format($device->basic_amount,2) }}</td>
                                                                    </tr>
                                                                    @php $sub_total= $sub_total + $device->basic_amount; $i++; @endphp
                                                                @empty
                                                                @endforelse
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-9 col-sm-12 text-md-left">
                                                            <p class="lead">{{ __('Payment Method') }}:</p>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    {{ $data['order']->payment_constant }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>{{ __('Sub Total') }}</td>
                                                                        <td class="text-end">{{ number_format($sub_total,2) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{{ __('TAX') }} (15%) @php $tax_amount = $sub_total * 0.15; @endphp</td>
                                                                        <td class="text-end">{{ number_format($tax_amount,2) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-bold-800"><b>{{ __('Total') }}</b> ({{ __('SAR') }})</td>
                                                                        <td class="text-bold-800 text-end"> <b>{{ number_format($data['order']->total_amount,2) }}</b></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Invoice Footer -->
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                    </div>
                                                </div>
                                                <!--/ Invoice Footer -->
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
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


    <script>

    </script>
</body>

</html>