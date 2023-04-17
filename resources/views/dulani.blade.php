<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>Wosul</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <link href="images/favicon.png" rel="icon" type="image/x-icon" />

    <!-- favicon -->
    <link rel="icon" type="image/jpg" href="images/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('website/css/bootstrap.min.css') }}">
    <!-- Main Css -->
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('website/fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('website/fonts/arabic-font.css') }}">
  
    <!-- Responsive Css-->
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}">

    <!-- Animation CSS -->
    <!-- <link href="{{ asset('website/css/aos.css') }}" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('website/css/fancybox.css') }}" />

    <link rel="stylesheet" href="{{ asset('website/fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/jquery-ui.css') }}">


    <!-- Snap Pixel Code -->
    <script type='text/javascript'>
        (function(e, t, n) {
            if (e.snaptr) return;
            var a = e.snaptr = function() {
                a.handleRequest ? a.handleRequest.apply(a, arguments) : a.queue.push(arguments)
            };
            a.queue = [];
            var s = 'script';
            r = t.createElement(s);
            r.async = !0;
            r.src = n;
            var u = t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r, u);
        })(window, document,
            'https://sc-static.net/scevent.min.js');

        snaptr('init', '792b0b26-1b84-4594-8d18-86c2ddec3fe1', {
            'user_email': '__INSERT_USER_EMAIL__'
        });

        snaptr('track', 'PAGE_VIEW');
    </script>
    <!-- End Snap Pixel Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DGPPCLM5HB"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-DGPPCLM5HB');
    </script>

</head>

<body>

    
    @include('includes/header')

    <section class="bg-gradient inner-banner p-5">
        <div class="inner-banner-logo-light">
            <img src="images/bg-logo-light.png" />
        </div>
        <div class="container ">
            <div class="row justify-content-center align-items-center ">
                <div class="col-lg-7">
                    <div class="content ">
                        <h2 class="aos-init aos-animate p-0">{{__('Subscriptions')}}</h2>
                        <h4>خصم خاص 20% لعملاء بنك التنمية الاجتماعية ومركز دلني للأعمال استخدم كود ”دلني“</h4>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="plan-bg-shape"></div>
        <div class="container postition-static">
            <div class="plans-tabs">
                

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="Subscriptions" role="tabpanel"
                        aria-labelledby="Subscriptions-tab">
                        <div class="row">
                            <div class="col-1"></div>
                            @forelse($data['subscriptions'] as $subscription)
                                <div class="ms-4 col-md-6 col-lg-3 mb-3 mb-md-4">
                                    <div class="plan-box">
                                        <h3>{{ App::getLocale() == 'en' ? $subscription->title : $subscription->title_ar }}
                                        </h3>
                                        <div class="plan-price">
                                            {{ $subscription->amount }}<small> {{__('SAR')}}</small></div>
                                        <p>{{ App::getLocale() == 'en' ? $subscription->description : $subscription->description_ar }}
                                        </p>
                                        <ul>
                                            @isset($subscription->features)
                                                @foreach ($subscription->features as $feature)
                                                    <li>{{ App::getLocale() == 'en' ? $feature->title : $feature->title_ar }}
                                                    </li>
                                                @endforeach
                                            @endisset
                                        </ul>
                                        {{-- <a href="#" class="btn btn-outline-primary w-100 mb-2"
                                            onclick="open_modal('{{ $subscription->id }}','subscription');">{{__('ADD TO CART')}}</a> --}}
                                        {{-- <a href="#" class="btn btn-primary w-100"
                                            onclick="buy_product_now('{{ $subscription->id }}','subscription');">{{__('BUY NOW')}}</a> --}}
                                        <a href="#dulani_form"
                                            class="btn btn-primary w-100">{{__('BUY NOW')}}</a>
                                    </div>
                                </div>
                            @empty
                            @endforelse

                        </div>


                    </div>

                </div>
                {{-- <div class="row mb-5 mt-5">
                    <div class="col-12 text-center">
                        <h2 class="aos-init aos-animate">{{__('Devices')}}</h2>
                    </div>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="Devices" role="tabpanel"
                        aria-labelledby="Devices-tab">

                        <div class="row">
                            @forelse($data['devices'] as $device)
                                <div class="col-md-6 col-lg-3 mb-3 mb-md-4">
                                    <div class="plan-box">
                                        <div class="img">
                                            <img src="{{ $device->image_path }}" alt="" class="w-100" />
                                        </div>
                                        <h3>{{ App::getLocale() == 'en' ? $device->title : $device->title_ar }}
                                        </h3>
                                        <div class="plan-price">
                                            {{ $device->amount }}<small> {{__('SAR')}}</small></div>
                                        <p>{{ App::getLocale() == 'en' ? $device->description : $device->description_ar }}
                                        </p>
                                        <a href="#" class="btn btn-outline-primary w-100 mb-2"
                                        onclick="open_modal('{{$device->id}}','device');">{{__('ADD TO CART')}}</a>
                                        <a href="#" class="btn btn-primary w-100"
                                            onclick="buy_product_now('{{$device->id}}','device');">{{__('BUY NOW')}}</a>
                                        
                                    </div>
                                </div>
                            @empty
                            @endforelse

                        </div>

                    </div>
                </div> --}}
                <div id="dulani_form"></div>
            </div>

        </div>
    </section>


    <div >
        <div class="container">
            <div class="form-box" >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text bg-gradient me-lg-5 aos-init aos-animate" data-aos="fade-up">
                            <div class="row">

                                <div class="col-6 pt-4">
                                    <img src="https://wosul.sa/images/wosul-white-logo.png" alt="">
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-8">
                                            <img src="{{ asset('website/images/dulani/dulani-logo.png') }}"
                                                alt="">
                                        </div>
                                        <div class="col-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <img src="{{ asset('website/images/dulani/social-development-logo.png') }}"
                                                alt="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br>
                            <h3>إدارة متجرك ومتابعة فواتيرك ومبيعاتك صارت أسهل مع وصــــــــول</h3>
                            <h3>نظام سحابي لنقاط البيع وإصدار الفواتير الإلكترونية</h3>
                            {{-- <h2>بنك التنمية الاجتماعية</h2> --}}
                            {{-- <h2>ومركز دلني للأعمال</h2> --}}
                            {{-- <h1>بنسبة 20%<
                                /h1> --}}
                            <br>
                            <h4>فريق وصول جاهز لخدمتك، كل اللي عليك تسجيل بياناتك وراح نتواصل معك</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="error-string"></div>

                        <form>
                            @csrf

                            <label class="label">{{ __('Your Name') }} <em>*</em></label>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" id="demo_request_full_name"
                                            placeholder="{{ __('Full Name') }}" class="form-control" required />
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
                                        <input type="text" placeholder="05xxxxxxxx" class="form-control" required
                                            id="demo_request_contact_number" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">{{ __('Email Address') }} </label>
                                <input type="email" placeholder="example@example.com" class="form-control"
                                    id="demo_request_email" />
                            </div>
                            <div class="form-group">
                                <label class="label">{{ __('Discount') }} </label>
                                <input type="text" placeholder="enter discount" class="form-control"
                                    id="demo_request_discount" />
                            </div>

                            <div class="form-group">
                                <div class="custom-checkbox">
                                    <label>
                                        <input type="checkbox" id="check1" />
                                        <span>
                                            {{ __('By checking this box, I confirm that I have read, understood and agree to the') }}
                                            <a href="{{ route('term_and_condition', app()->getLocale()) }}">{{ __('Terms and Conditions') }}
                                            </a>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <div class="custom-checkbox">
                                    <label>
                                        <input type="checkbox" id="check2" />
                                        <span>
                                            {{ __('By using this form you agree with the storage and handling of your data by this website in accordance with the') }}
                                            <a href="{{ route('privacy_policy', app()->getLocale()) }}">{{ __('Privacy Policy') }}
                                            </a>.</span>
                                    </label>
                                </div>
                            </div> --}}
                            <div class="form-submit-wrap">
                                <button type="button" class="btn btn-primary btn-m-width disabled btn-submit"
                                    onclick="submit_dulani()">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('includes/footer', ['showInquiryForm' => false])

    <a href="javascript:void(0)" class="scrollToTop"><svg xmlns="http://www.w3.org/2000/svg" width="49.198"
            height="78.727" viewBox="0 0 49.198 78.727">
            <path id="arrow-up-long-solid"
                d="M47.761,28.055a4.918,4.918,0,0,1-6.958,0L29.526,16.784v57a4.92,4.92,0,0,1-9.84,0v-57L8.4,28.055A4.92,4.92,0,1,1,1.446,21.1L21.127,1.416a4.918,4.918,0,0,1,6.958,0L47.766,21.1A4.923,4.923,0,0,1,47.761,28.055Z"
                transform="translate(-0.005 0.025)" fill="currentColor" />
        </svg>
    </a>


    <!-- javascript -->
    <script src="{{ asset('website/js/jquery-2.2.4.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('website/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Main Js -->
    <script src="{{ asset('website/js/custome.js') }}"></script>


    <script src="{{ asset('website/js/fancybox.umd.js') }}"></script>

    <!--
  <script>
      $(window).on('load', function() {
          var scrollLink = $('.page-scroll');
          $(window).scroll(function() {
              var scrollbarLocation = $(this).scrollTop();
              scrollLink.each(function() {
                  var sectionOffset = $(this.hash).offset().top - 5;
                  if (sectionOffset <= scrollbarLocation) {
                      $(this).parent().addClass('active');
                      $(this).parent().siblings().removeClass('active');
                  }
              });
          });
      });
  </script>
-->

    <script src="{{ asset('website/js/jarallax.js') }}"></script>
    <script src="{{ asset('website/js/jarallax-video.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.jarallax').jarallax({});
        });

        function submit_dulani() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('full_name', $('#demo_request_full_name').val());
            formData.append('phone_number', $('#demo_request_contact_number').val());
            formData.append('email', $('#demo_request_email').val());
            formData.append('discount', $('#demo_request_discount').val());

            $.ajax({
                url: "{{ route('save_dulani') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(resp) {

                    // console.log(resp);

                    if (resp.status_code == 200) {
                        $('.btn-submit').addClass('disabled');
                        $('.error-string').removeClass('mb-5 alert alert-danger');
                        $('.error-string').addClass('mb-5 alert alert-success');
                        $('.error-string').html(resp.msg);
                    }

                    if (resp.status_code != 200) {

                        let error_json = JSON.parse(resp.msg);
                        var error_string = '';
                        $.each(error_json, (key, value) => {
                            error_string += value[0] + '<br>';
                        });

                        $('.error-string').addClass('mb-5 alert alert-success');
                        $('.error-string').addClass('mb-5 alert alert-danger');
                        $('.error-string').html(error_string);
                    }

                }
            });



        }

        $('#demo_request_domain').on('selectmenuchange', function() {
            if ($(this).val() == 'Other' || $(this).val() == 'أخرى') {
                $('.other-domain-block').css('display', 'block');
            } else {
                $('.other-domain-block').css('display', 'none');
            }
        });

        $('#check1').on('change', function() {
            enable_disable_submit();
        });


        $('#check2').on('change', function() {
            enable_disable_submit();
        });

        function enable_disable_submit() {
            if ($('#check1').prop('checked') == true) {
                $('.btn-submit').removeClass('disabled');
            } else {
                $('.btn-submit').addClass('disabled');
            }
        }
    </script>






    <script src="{{ asset('website/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-ui.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.logos-carousel').owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                dots: false,
                items: 1,
                autoplay: true,
                autoplayTimeout: 5000,
                smartSpeed: 1800,
                responsive: {
                    0: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 3
                    }
                }
            })
            $('.gallery-carousel').owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                dots: false,
                items: 1,
                autoplay: true,
                autoplayTimeout: 5000,
                smartSpeed: 1800,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 3
                    },
                    1199: {
                        items: 5
                    }
                }
            })
            $('.review-carousel').owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                dots: false,
                items: 1,
                autoplay: true,
                autoplayTimeout: 5000,
                smartSpeed: 1800,
                stagePadding: 120,
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 40,
                    },
                    768: {
                        items: 2,
                        stagePadding: 80,
                    },
                    992: {
                        items: 3
                    },
                    1199: {
                        items: 4
                    }
                }
            })



            $(".customSelect").selectmenu();





        });
    </script>



    <script src="{{ asset('website/js/aos.js') }}"></script>



    <script>
        AOS.init();


        // ----- COUNTER ----- //

        var a = 0;
        $(window).scroll(function() {
            var oTop = $('#counter').offset().top - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) {
                $('.counter-value').each(function() {
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: $this.text()
                    }).animate({
                        countNum: countTo
                    }, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function() {
                            $this.text(this.countNum);
                            //alert('finished');
                        }
                    });
                });
                a = 1;
            }
        });
    </script>


</body>

</html>