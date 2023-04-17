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
                  <a class="nav-link" href="{{ url('/en/register')}}">
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
            
            <div class="wosul-form-bg">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">
                                
                                Get Wosul
                                <img src="{{ asset('images/about-right.png') }}" style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            <div class="wosul-form-content">

                <div class="container">

                    <div class="row">

                        <div class="offset-md-2 col-md-7">

                            <div class="form-header">

                                <img src="{{ asset('images/form-header.png') }}" class="img-fluid">

                            </div>

                            <div class="form-info">

                                <p><strong>نموذج تسجيل المنشأة في نظام وصول</strong></p>

                                <p>أخبرنا قليلا عنك ، وسنتصل بك عبر الهاتف أو البريد الإلكتروني لتوفير معلومات حول منتجات وخدمات وصول. أو الاتصال على </p>
                                
                                <p>  الرقم التالي :  0549249523 </p>

                            </div>

                            <div class="form-content">

                                <form>
                                    
                                    <div class="form-group">
                                        
                                        <label for="username">Full Name | الاسم بالكامل </label>
                                        
                                        <input type="text" class="form-control" id="username" aria-describedby="username" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="usernumber">Phone Number | رقم الجوال </label>
                                        
                                        <input type="text" class="form-control" id="usernumber" aria-describedby="usernumber" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="useremail">E-mail | البريد الالكتروني *</label>
                                        
                                        <input type="email" class="form-control" id="useremail" aria-describedby="useremail" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="usercompany">Company | اسم المنشاة</label>
                                        
                                        <input type="text" class="form-control" id="usercompany" aria-describedby="usercompany" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="usercompany">Company URL | اسم المنشاة</label>
                                        
                                        <div class="row">

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanurl" aria-describedby="usercompanyurl" placeholder="Enter Your URL" hidefocus="true" style="outline: currentcolor none medium;">

                                                <small id="usercompanurl" class="form-text text-muted">Example : demo</small>

                                            </div>

                                            <div class="col-md-6">

                                                <input type="text" class="form-control" id="usercompanurl" aria-describedby="usercompanyurl" placeholder=".wosul.com" disabled="" hidefocus="true" style="outline: currentcolor none medium;">

                                                

                                            </div>

                                        </div>
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="useraddress">Address | العنوان</label>
                                        
                                        <input type="text" class="form-control" id="useraddress" aria-describedby="useraddress" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <!-- <div class="form-group custom-check ">
                                        
                                        <label for="useraddress">Order Hardware | اطلب جهازك</label>
                                        
                                        <div class="clearfix"></div>

                                        <div class="row">

                                            <div class="col-md-6">
                                               
                                                <label class="form-check-label" for="pos-plan-check">
                                                    
                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="pos-plan-check" hidefocus="true" style="outline: currentcolor none medium;"><span class="checkmark"></span>

                                                        <label class="form-check-label" for="pos-plan-check"><img src="images/ipad-stand.jpg" class="img-fluid"></label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="pos-plan-check">Ipad Stand | قاعدة تثبيت آيباد</label>

                                                    </div>

                                                </label>

                                            </div>

                                            <div class="col-md-6">
                                                
                                                <label class="form-check-label" for="erp-plan-check">

                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="erp-plan-check" hidefocus="true" style="outline: currentcolor none medium;">

                                                        <label class="form-check-label" for="erp-plan-check">
                                                        <img src="images/cash-drawer.jpg" class="img-fluid">
                                                        </label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="erp-plan-check">Cash drawer | صندوق النقد
                                                        </label>

                                                    </div>

                                                </label>

                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="col-md-6">

                                                <label class="form-check-label" for="manufacturing-plan-check">

                                                    <div class="form-group form-check">

                                                        <input type="checkbox" class="form-check-input" id="manufacturing-plan-check" hidefocus="true" style="outline: currentcolor none medium;">

                                                        <label class="form-check-label" for="manufacturing-plan-check">
                                                        <img src="images/invoice-printer.jpg" class="img-fluid">
                                                        </label>

                                                        <span class="checkmark"></span>
                                                        <label class="form-check-label" for="manufacturing-plan-check">Invoice printer | طابعة فواتير
                                                        </label>

                                                    </div>

                                                </label>

                                            </div>

                                        </div> -->
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="userpromotioncode">Promotion Code |كود الخصم  </label>
                                        
                                        <input type="text" class="form-control" id="userpromotioncode" aria-describedby="userpromotioncode" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="useraddress">Recommendation | أقتراحتكم </label>
                                        
                                        <input type="text" class="form-control" id="useraddress" aria-describedby="useraddress" placeholder="Your Answer" hidefocus="true" style="outline: currentcolor none medium;">
                                        
                                    </div>

                                    <div class="form-group">

                                        <button class="btn custom-btn" type="submit">أرسل</button>

                                

                            </div></form>

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
                    <p class="footer-head">Contact Us</p>
                    <p class="footer-cont"><a href="#">support@wosul.sa</a></p>
                    <p class="footer-cont"><a href="#">info@wosul.sa</a></p>
                    <p class="footer-cont"><a href="http://www.wasap.my/966549249523">Whatsapp</a></p>
                    

                     
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">Follow Us</p>
                    <p  class="footer-cont"><a href="https://twitter.com/wosulerp?lang=en">Twitter</a></p>
                    <p class="footer-cont"><a href="https://www.instagram.com/wosulerp/?hl=af">Instagram</a></p>
                    <p class="footer-cont"><a href="https://www.linkedin.com/company/wosul/?viewAsMember=true">LinkedIn</a></p>
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">Make A Visit</p>
                    <p class="footer-cont"><a>PO Box 93597,</a></p>
                    <p  class="footer-cont"><a>Riyadh 13414</a></p>
                    <p  class="footer-cont"><a>Airport Branch Road</a></p>
                    <p class="footer-cont"><a>Saudi Arabia</a></p>
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">Join Our Newsletter</p>
                    <p class="footer-cont">
                        <input type="email" name="" class="input-footer" placeholder="Enter Your Email">
                        <button class="subscribe-btn">Subscribe</button>
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