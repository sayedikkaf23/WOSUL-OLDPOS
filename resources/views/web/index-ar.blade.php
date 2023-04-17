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
                  <a class="nav-link" href="{{url('/')}}">
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
                    
                    <a  class="nav-link login-btn pl-3 pr-3 green-tab" href="{{ route('login')}}">تسجيل الدخول</a>

                  </li>     

                </ul>       

              </div>

            </nav>

          </div>

        </div>

      </div>

    </div> <!-- Site Header DIV Code Ends ---->


    <div class="site-banner-wrapper">

      <div id="particles-js"></div>

      <div class="container">

        <div class="row">

          <div class="col-lg-7 no-padding">

            <div class="banner-text">

              <h1>
                
                مع وصول.</br>
                اجعل تعاملك مع</br>
                عملائك، منتجاتك
                وأرقام منشأتك</br>
                بشكل اسهل

              </h1>

            </div>

          </div>

          <div class="col-lg-5">

            <div class="banner-video">

              <div class="video-bg visible-lg">

              </div>
              <video controls="">
                <source src="videos/wosul-intro.mp4" type="video/mp4">
                المتصفح لا يدعم عرض الفديو.
              </video> 

            </div>             

          </div>

        </div>

      </div>

    </div>

    </div> <!-- Site Banner DIV Code Ends ---->


    <div class="site-block-02-wrapper">

      <div class="container">

        <div class="row">

          <div class="col-8 mx-auto text-center">

            <h5>
              <b>مع وصول  </b><br>
                توفير نظام سهل الاستخدام ومرتبط يدعم وإدارة نقاط الجرد والفروع بكفاءة عالية لتزويدك بإحصاءات شاملة ودقيقة في الوقت الفعلي تتعلق بمنشأتك
            </h5>

            <a class="btn btn-primary green-btn mt-3" href="">اطلب النسخة التجريبية</a>

          </div>

          <div class="col-12">

            <img class="img-fluid" src="{{ asset('images/app-sample-img.png') }}">

          </div>

        </div>

      </div>

    </div><!-- Site Block-02-Wrapper DIV Closed -->

    <div class="site-block-03-wrapper">

      <div class="container">

        <div class="row">

        	<div class="col-12">

        		<h3 class="title text-center mb-5">مميزات النظام </h3>

        	</div>

          <div class="col-12">

  			    <ul class="nav nav-pills nav-fill feature-tabs mb-4">
  				    <li ><a data-toggle="tab" class="feature-tab-link active green-tab" href="#inventory">المخزون </a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link gray-tab" href="#dashboard">الإحصائيات</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link yellow-tab" href="#pos">نقطة المبيعات</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link red-tab" href="#sales">المبيعات</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link blue-tab" href="#finance">المالية </a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link black-tab" href="#purchases">المشتريات</a></li>
  				  </ul>

    			 	<div class="tab-content">
    				    <div id="inventory" class="tab-pane fade in active">
                  <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 green-tab no-padding">
        				      <div class="inventory-feature mt-3 mr-5">
                        <h4>المخزون </h4>
                        <ul class="custom-ul">
                          <li>إنشاء مجموعات للمنتجات .</li>
                          <li>ربط المخزون بقوائم نقاط البيع.</li>
                          <li>التعامل مع الفروع بشكل خاص.</li>
                          <li>انعكاس المشتريات في قوائم المخزون بشكل مباشر.</li>
                        </ul>
                    </div>
                    </div>
                  </div>
    				    </div>
    				    <div id="dashboard" class="tab-pane fade">
    				      <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 gray-tab no-padding">
                      <div class="dashboard-feature mt-3 mr-5">
                        <h4>الإحصائيات</h4>                          
                        <ul class="custom-ul">
                          <li>مزامنة نقاط البيع والمبيعات والمصروفات وعرضها في احصائيات منوعة.</li>
                          <li>ملخص نهاية اليوم في الحساب المصرفي والنقدي.</li>
                          <li>إحصائيات وتمثيل بياني للمنتجات الأكثر إحصائيات.</li>
                          <li>عرض بياني لأعلى المصروفات.</li>
                          <li>أدوات للمتابعة والتحسين.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
    				    </div>
    				    <div id="pos" class="tab-pane fade">
    				      <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 yellow-tab no-padding">
                      <div class="pos-feature mt-3 mr-5">
                        <h4>نقطة المبيعات</h4> 
                        <ul class="custom-ul">
                            <li>الاحتفاظ بمعلومات العملاء.</li>
                            <li>استعراض قوائم المنتجات بشكل واضح.</li>
                            <li>تنفيذ عمليات الدفع مقابل المنتجات بعدة طرق.</li>
                            <li>مسح كيو ار باركود للمنتجات وإنشاء إيصال.</li>
                            <li>طباعة الفواتير او مشاركتها.</li>
                            <li>يتيح لك عمل اكثر من موظف لنفس النظام بشكل منفصل.</li>
                            <li>استعراض قائمة المنتجات للعميل عن طريق كيو ار باركود.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
    				    </div>
    				    <div id="sales" class="tab-pane fade">
    				      <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 red-tab no-padding">
                      <div class="sales-feature mt-3 mr-5">
                        <h4>المبيعات</h4>
                        <ul class="custom-ul">
                          <li>المصاريف / الإيرادات حسب المنتج.</li>
                          <li>مبيعات المنتج في الفترة الزمنية (يوم ، أسبوع ، شهر ، سنة).</li>
                          <li>مبيعات المنتج من قبل ممثل المبيعات.</li>
                          <li>مبيعات المنتجات : فريق المبيعات / الفرع.</li>
                          <li>مبيعات المنتجات بالمستودع.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
    				    </div>
                <div id="finance" class="tab-pane fade">
                  <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 blue-tab no-padding">
                      <div class="finance-features mt-3 mr-5">
                        <h4>المالية </h4>
                        <ul class="custom-ul">
                          <li>دفتر الأستاذ العام.</li>
                          <li> (AP) حسابات الدفع .</li>
                          <li>(AR)حسابات القبض.</li>
                          <li>إدارة الأصول الثابتة.</li>
                          <li>P / L قائمة الدخل.</li>
                          <li> إنشاء بيان الضريبة. </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="purchases" class="tab-pane fade">
                  <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 black-tab no-padding">
                      <div class="purchases-feature mt-3 mr-5">
                        <h4>المشتريات</h4>
                        <ul class="custom-ul">
                          <li>سهولة إضافة المنتجات .</li>
                          <li>سهولة تحديد الموردين للمنتجات.</li>
                          <li>اختيار أي من الفروع بشكل منفصل.</li>
                          <li>عرض المنتجات في صفحة المشتريات بشكل مفصل.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

    			  </div> 

          </div>

        </div>

      </div>

    </div><!-- Site Block-03-Wrapper DIV Closed -->
    

    <div class="site-block-04-wrapper">

      <div class="site-block-04-inner">

        <div class="container">

          <div class="row">

            <div class="col-12 text-center">

              <!-- <h3 class="title text-center mt-5 mb-3">Get 20% off hardware that<br>
              works as hard as you do.</h3>

              <p>Start taking Apple Pay and contactless cards at your counter, curbside, or on the go. Use code FEB20 for 20% off contactless hardware,<br> now through February 16.</p> 
              <a href="#" class="text-primary">تسوق من متجر الاجهزة</a>-->
            <h3 class="title text-center mt-5 mb-3"><a href="#" class="text-primary"> تسوق الاجهزة من متجر وصول </a></h3>

            </div>

            <div class="col-12">
         
            <div class="js-slider isystkSlider" data-shift="1" data-carousel="true" >
              <div class="view-layer">
                <ul class="parent">
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/all-in-one-01.png') }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/invoice-printer.png') }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/ipad-stand-01.png') }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/all-in-one-01.png') }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/invoice-printer.png') }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/ipad-stand-01.png') }}" width="280px">
                    </p>
                  </li>
                </ul>
              </div>
              <div>
                <p class="next-btn"><a href="#"><img src="{{ asset('images/btn-next.png') }}" alt=">>" ></a></p>
                <p class="prev-btn"><a href="#"><img src="{{ asset('images/btn-prev.png') }}" alt="<<" ></a></p>
              </div>
            </div>

            </div>

          </div>

        </div>

      </div>

    </div> <!-- Site Block-04-Wrapper DIV Closed -->


    <div class="site-block-05-wrapper">

      <div class="container">

        <div class="row">

          <div class="col-12">

            <h3 class="title text-center text-white mb-3">عملائنا</h3>

          </div>

          <div class="col-12">

            <div class="js-slider isystkSlider isystkBrandSlider" data-shift="5" data-carousel="true" data-auto-slide="true">
              <div class="view-layer">
                <ul class="parent">
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-01.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-02.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-03.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-04.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-05.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-06.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-07.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-08.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-09.jpg') }}" width="120px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('images/clients/Client-10.jpg') }}" width="120px">
                    </p>
                  </li>
                </ul>
              </div>
            </div>

          </div>

        </div>

      </div>

    </div> <!-- Site Block-05-Wrapper DIV Closed -->


    

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