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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9DJS8P5GMV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-9DJS8P5GMV');
    </script>

</head>

<body>


    <footer>
        <div class="footer-form-bar">
            <div class="container">
                <div class="form-box">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text bg-gradient me-lg-5" data-aos="fade-up">
                                <h2>لأفضل الحلول جرب نظام وصــــــــول</h2>
                                <br>
                                <h3>نظام وصول السحابي لحلول المبيعات للتجزئة وإدارة المنشأة التجارية</h3>
                                <h3>حاب تعرف أكثر عن وصول؟</h3>
                                <h3>عندك استفسارات تخص منشأتك التجارية؟</h3>
                                <h4>فريق وصول جاهز لخدمتك، كل اللي عليك تسجيل بياناتك وراح نتواصل معك</h4>
                                {{-- <ul>
                                    <li>{{ __('Demo will include a complete tour from core to premium features so based on that you can choose your packages wisely.') }}
                                    </li>
                                    <li>{{ __('Our team is trained to focus on your prime requirements so we would like to give you customized demo of only required features.') }}
                                    </li>
                                    <li>{{ __('We are all set to serve you, what you need to do is just submit the form and get ready for an amazing experience.') }}
                                    </li>
                                </ul> --}}
                                <div class="row pt-3">
                                    <div class="col-5 pt-2">
                                        <img src="{{ asset('images/wosul-white-logo.png') }}" alt="">

                                    </div>
                                    <div class="col-7 p-1">
                                        <img src="{{ asset('images/zakat-white-logo.png') }}" alt=""
                                            style="width: 400px;">

                                    </div>
                                </div>
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
                                            <input type="text" placeholder="5xxxxxxxx" class="form-control" required
                                                id="demo_request_contact_number" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">{{ __('Email Address') }} </label>
                                    <input type="email" placeholder="example@example.com" class="form-control"
                                        id="demo_request_email" required />
                                </div>
                                {{-- <div class="form-group">
                                    <label class="label">{{ __('Promoter Name') }} <em>*</em> </label>
                                    <input type="text" placeholder="{{ __('Promoter Name') }}"
                                        class="form-control" id="demo_request_promoter_name" required />
                                </div> --}}
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
                                        onclick="submit_promoter()">{{ __('Submit') }}</button>
                                </div>
                            </form>

                            <div class="row text-center">
                                <a onClick="trackUserIp()" href="#">
                                    <img loading="lazy" style="width:200px;margin-top:50px;" src="{{ asset('website/images/wosul-post-wh.png') }}" alt="" />
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>


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

        function submit_promoter() {

            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('full_name', $('#demo_request_full_name').val());
            formData.append('phone_number', $('#demo_request_contact_number').val());
            formData.append('email', $('#demo_request_email').val());
            // formData.append('promoter_name', $('#demo_request_promoter_name').val());

            $.ajax({
                url: "{{ route('save_promoter') }}",
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

        function trackUserIp(){

        $.ajax({
            type : 'post',
            dataType : 'json',
            url : '/api/track_whatsapp_page_visits',
            success: function(resp){
                console.log(resp);
                if(resp.status){
                    window.location.href= 'https://api.whatsapp.com/send/?phone=966920033225&text&type=phone_number&app_absent=0';
                }
            }
        })

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
