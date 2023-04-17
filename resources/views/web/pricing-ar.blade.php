<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="{{ asset('js/all.js') }}"></script> 

    <!-- Google Font for Number -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300&display=swap" rel="stylesheet">   
     

    <!-- Bootstrap Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Bootstrap Custom Arabic CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="{{ asset('css/style-ar.css') }}">


    <link rel="stylesheet" href="{{ asset('css/particle-style.css') }}">
    <link href="{{ asset('css/carousel-slider.css') }}" rel="stylesheet"> 


    <title>WOSUL</title>

</head>

<body>

	<div class="site-header-wrapper">

      <div class="container">

        <div class="row">

          <div class="col-12">

            <div class="top-header">

            <ul class="nav justify-content-end">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="{{ route('contact',app()->getLocale())}}">
                    <span><i class="fas fa-headset"> </i> </span> الدعم</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"  href="tel: +966549249523">
                    <span><i class="fas fa-mobile-alt"> </i> </span> +966 549 249 523</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="faq-ar.html">
                    <span><i class="far fa-question-circle"> </i> </span> FAQ</a>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/en/pricing')}}>
                    <span><i class="fas fa-language"> </i> </span> EN</a>
                </li>
                
              </ul>

            </div>            

            <nav id="sticky-top" class="navbar navbar-expand-lg navbar-light">
            
              <a class="navbar-brand" href="#"><img class="img-fluid site-logo" src="{{ asset('images/logo.png') }}"alt="Logo" /></a>
              
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                  <span class="navbar-toggler-icon"></span>

              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav mr-auto">
                  
                  <li class="nav-item active">
                    
                    <a class="nav-link" href="{{ url('/ar')}}">الصفحة الرئيسية </a>

                  </li>
                  
                  <li class="nav-item">
                    
                    <a class="nav-link" href="{{ route('about',app()->getLocale())}}">معلومات عنا </a>

                  </li>

                  <li class="nav-item">
                    
                    <a class="nav-link" href="{{ route('pricing',app()->getLocale())}}">الخطط والأسعار</a>

                  </li>
                  
                  <li class="nav-item">

                    <a class="nav-link" href="{{ route('partners',app()->getLocale())}}">شركاء وصول</a>

                  </li>   

                  <li class="nav-item">

                    <a class="nav-link" href="{{ route('contact',app()->getLocale())}}">اتصل بنا</a>

                  </li>  

                  <li class="nav-item">

                    <a href="{{ route('pricing',app()->getLocale())}}" class="nav-link login-btn blue-tab">احصل على وصول </a>

                    

                  </li>

                  <li class="nav-item">
                    
                    <a class="nav-link login-btn pl-3 pr-3 green-tab" href="{{ route('login')}}">تسجيل الدخول</a>

                  </li>     

                </ul>       

              </div>

            </nav>

          </div>

        </div>

      </div>

    </div> <!-- Site Header DIV Code Ends ---->

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">
            <div class="pricing-bg">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">
                                
                                الخطط والأسعار
                                <img src="{{ asset('images/about-right.png') }}" style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>
            <div class="pricing-content">

                <div class="container">

                    <div class="row">

                        <div class="col-md-3 offset-md-1 custom-position text-center no-padding">

                            <div class="pricing-wrapper">

                                <div class="pricing-title box-01">

                                    <h5>باقة نقاط البيع</h5>
                                    

                                </div>

                                <div class="pricing-value">

                                    <h2 class="price"> <span class="currency-prefix">SAR</span> 249 <span class="plan-type">/ شهرياً</span></h2>

                                    <p><strong><u>مناسب للشركات الناشئة</u></strong></p>

                                    <p><strong class="discount-text bg-red">أحصل على %15</strong> خصم على الباقة السنوية</p>

                                </div>

                                <div class="pricing-button">

                                    <a href="{{ route('register', app()->getLocale())}}" class="btn btn-md custom-pricing-button bg-red btn-block" hidefocus="true" style="outline: currentcolor none medium;"> اطلب الان </a>

                                     <!-- <small class="text-muted">Valid for 3 months </small> -->

                                </div>

                                <div class="pricing-info">

                                    <ul class="list-group">
                                      <li class="list-group-item font-arabic direction-rtl">نظام السحابة</li>
                                      <li class="list-group-item font-arabic direction-rtl">مستخدمون غير محدودين</li>
                                      <li class="list-group-item font-arabic direction-rtl">منتج غير محدود</li>
                                      <li class="list-group-item font-arabic direction-rtl">نقطة المبيعات</li>
                                      <li class="list-group-item font-arabic direction-rtl">ادارة المخزون</li>
                                      <li class="list-group-item font-arabic direction-rtl">إدارة شراء</li>
                                    </ul>

                                </div>

                                

                            </div>

                        </div>

                        <div class="col-md-3 text-center no-padding">

                            <div class="pricing-wrapper center-box">

                                <div class="pricing-title box-02">

                                    <h5>الباقة المتكاملة</h5>

                                </div>

                                <div class="pricing-value">

                                    <h2 class="price"> <span class="currency-prefix">SAR</span> 349 <span class="plan-type">/ شهرياً</span></h2>

                                    <p><strong><u>مناسب للشركات الصغيرة و المتوسطة</u></strong></p>

                                    <p><strong class="discount-text bg-blue">أحصل على %15</strong> خصم على الباقة السنوية</p>

                                </div>

                                <div class="pricing-button">

                                    <a href="{{ route('register', app()->getLocale())}}" class="btn btn-md custom-pricing-button bg-blue btn-block" hidefocus="true" style="outline: currentcolor none medium;"> اطلب الان </a>

                                     <!--  <small class="text-muted">Valid for 3 months</small>  -->

                                </div>

                                <div class="pricing-info">

                                    <ul class="list-group">

                                        <li class="list-group-item font-arabic direction-rtl">الإدارة المالية </li>
                                        <li class="list-group-item font-arabic direction-rtl">إدارة الموارد البشرية </li>
                                        <li class="list-group-item font-arabic direction-rtl">إدارة شراء</li>
                                        <li class="list-group-item font-arabic direction-rtl">ادارة المخزون</li>
                                        <li class="list-group-item font-arabic direction-rtl">نظام السحابة</li>
                                        <li class="list-group-item font-arabic direction-rtl">مستخدمون غير محدودين</li>
                                        <li class="list-group-item font-arabic direction-rtl">منتج غير محدود</li>
                                        <li class="list-group-item font-arabic direction-rtl">نقطة المبيعات</li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3 text-center no-padding">

                            <div class="pricing-wrapper">

                                <div class="pricing-title box-03">

                                    <h5>باقة التصنيع</h5>

                                </div>

                                <div class="pricing-value">

                                    <h2 class="price"> <span class="currency-prefix">SAR</span> 599 <span class="plan-type">/ شهرياً</span></h2>

                                    <p><strong><u>مناسب للشركات المصنعة</u></strong></p>

                                    <p><strong class="discount-text bg-yellow">أحصل على %15</strong> خصم على الباقة السنوية</p>

                                </div>

                                <div class="pricing-button">

                                    <a href="{{ route('register', app()->getLocale())}}" class="btn btn-md custom-pricing-button bg-yellow btn-block" hidefocus="true" style="outline: currentcolor none medium;"> اطلب الان </a>

                                     <!-- <small class="text-muted">Valid for 3 months</small> -->

                                </div>

                                <div class="pricing-info">

                                    <ul class="list-group">

                                        <li class="list-group-item font-arabic direction-rtl">ادارة المخزون</li>
                                        <li class="list-group-item font-arabic direction-rtl">إدارة التصنيع</li>
                                        <li class="list-group-item font-arabic direction-rtl">إدارة الموارد البشرية</li>
                                        <li class="list-group-item font-arabic direction-rtl">الإدارة المالية</li>
                                        <li class="list-group-item font-arabic direction-rtl">إدارة شراء</li>
                                        <li class="list-group-item font-arabic direction-rtl">نظام السحابة وعلى الأعداد الأولية</li>
                                        <li class="list-group-item font-arabic direction-rtl">مستخدمون غير محدودين</li>
                                        <li class="list-group-item font-arabic direction-rtl">منتج غير محدود</li>
                                        <li class="list-group-item font-arabic direction-rtl">نقطة المبيعات</li>
                                        <li class="list-group-item font-arabic direction-rtl">برانش غير محدود من المصنع</li>
                                        <li class="list-group-item font-arabic direction-rtl">تكامل API</li>
                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>
    </div>
    

    <!--Footer-->
    <footer class="fly-site-footer">
        <div class="container">
            <div class="row">
                <div class="fly-divider-space space-sm"></div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <p class="footer-head">تواصل معنا عبر</p>
                    <p class="footer-cont"><a href="mailto:support@wosul.sa">support@wosul.sa</a></p>
                    <p class="footer-cont"><a href="mailto:info@wosul.sa">info@wosul.sa</a></p>
                    <p class="footer-cont"><a href="tel: +966549249523">+966 549 249 523</a></p>
                    <p class="footer-cont"><a href="http://www.wasap.my/966549249523">Whatsapp</a></p>
                    

                     
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">تابعنا عبر</p>
                    <p  class="footer-cont"><a href="https://twitter.com/wosulerp?lang=en" target="_blank">Twitter</a></p>
                    <p class="footer-cont"><a href="https://www.instagram.com/wosulerp/?hl=af" target="_blank">Instagram</a></p>
                    <p class="footer-cont"><a href="https://www.linkedin.com/company/wosul/?viewAsMember=true" target="_blank">LinkedIn</a></p>
                </div>
                <div class="col-lg-3">
                    <p class="footer-head"><a href="https://goo.gl/maps/BymKXtZcbbg1AzS86" target="_blank" style="color:inherit;">يمكنك زيارتنا عبر</a></p>
                    <p class="footer-cont"><a>Al Olaya</a></p>
                    <p  class="footer-cont"><a>Riyadh 11564</a></p>
                    <p  class="footer-cont"><a>Airport Branch Road</a></p>
                    <p class="footer-cont"><a>Saudi Arabia</a></p>
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">انضم الى صحيفتنا الاخبارية</p>
                    <p class="footer-cont text-right">
                        <input type="email" name="" class="input-footer font-arabic" placeholder="ادخل بريدك الالكتروني هنا " hidefocus="true" style="outline: currentcolor none medium;">
                        <button class="subscribe-btn font-arabic">اشترك معنا </button>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="fly-divider-space space-sm"></div>
            </div>
        </div>
    </footer>


    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- partial -->
    <script src='https://cldup.com/S6Ptkwu_qA.js'></script>
    <script  src="{{ asset('js/particle-script.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/carousel-slider.js') }}"></script>

    <script type="text/javascript">
    
      ///////////////// fixed menu on scroll for desktop
      if ($(window).width() > 992) {
        $(window).scroll(function(){  
           if ($(this).scrollTop() > 40) {
              $('#sticky-top').addClass("fixed-top");
              // add padding top to show content behind navbar
              $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            }else{
              $('#sticky-top').removeClass("fixed-top");
               // remove padding top from body
              $('body').css('padding-top', '0');
            }   
        });
      } // end if
      
    </script>

</body>

</html>