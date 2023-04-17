<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <!-- Font Awesome -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="{{ asset('js/all.js') }}"></script> 

    <!-- Google Font for Number -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300&display=swap" rel="stylesheet">   
     

    <!-- Bootstrap Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

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
                    <span><i class="fas fa-headset"> </i> </span> Support</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"  href="tel: +966549249523">
                    <span><i class="fas fa-mobile-alt"> </i> </span> +966 549 249 523</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="faq.html">
                    <span><i class="far fa-question-circle"> </i> </span> FAQ</a>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/en/faq')}}">
                    <span><i class="fas fa-language"> </i> </span> AR</a>
                </li>
                
              </ul>

            </div>            

            <nav id="sticky-top" class="navbar navbar-expand-lg navbar-light">
            
              <a class="navbar-brand" href="#"><img class="img-fluid site-logo" src="{{asset('images/logo.png')}}"alt="Logo" /></a>
              
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                  <span class="navbar-toggler-icon"></span>

              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav ml-auto">
                  
                  <li class="nav-item active">
                    
                    <a class="nav-link" href="{{ url('/')}}">Home</a>

                  </li>
                  
                  <li class="nav-item">
                    
                    <a class="nav-link" href="{{ route('about',app()->getLocale())}}">About</a>

                  </li>

                  <li class="nav-item">
                    
                    <a class="nav-link" href="{{ route('pricing',app()->getLocale())}}">Plans & Pricing</a>

                  </li>
                  
                  <li class="nav-item">

                    <a class="nav-link" href="{{ route('partners',app()->getLocale())}}">Partners</a>

                  </li>   

                  <li class="nav-item">

                    <a class="nav-link" href="{{ route('contact',app()->getLocale())}}">Contact</a>

                  </li>  

                  <li class="nav-item">

                    <a class="nav-link login-btn blue-tab" href="{{ route('pricing',app()->getLocale())}}">Get Wosul</a>

                  </li>

                  <li class="nav-item">
                    
                    <a  class="nav-link login-btn pl-3 pr-3 green-tab" href="{{ route('login')}}">Login</a>

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
            <div class="pricing-bg faq-bg">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">
                                
                                Frequently Asked Questions
                                <img src="{{ asset('images/about-right.png') }}" style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>
            <div class="faq-content">

                <div class="container">

                  <div class="row">

                    <div class="col-12">

                      <div id="accordion">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                              <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What does WOSUL system offer?
                              </button>
                            </h5>
                          </div>

                          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                              It offers a Point of Sale package and an integrated package
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What does a Point of Sale package consist of?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                              Offers (sales - purchases - inventory - control panel - statistics)
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                What does WOSUL plus offer ?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                              Provides (Sales - Purchases - Inventory - Control Panel - Statistics - Finance - Human Resources)
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What does the subscription fee include?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                              IPad, stand, Epson printer, cash box and subscription to Wosul system for one year
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Can I subscribe monthly?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                              Yes is available
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingSix">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Do I need to pay an annual fee?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                            <div class="card-body">
                              You need to pay the annual subscription amount
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingSeven">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Do you have offers for trolleys?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                            <div class="card-body">
                              Yes, we have offers for them (as per requirements)
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingEight">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                How long does it take to activate the subscription?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
                            <div class="card-body">
                              Not more than 5 days
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingNine">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                Do I need to pay subscription fees to the other branch?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
                            <div class="card-body">
                              If the stock is linked, you do not need to pay other fees
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingTen">
                            <h5 class="mb-0">
                              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                Will I pay training fees?
                              </button>
                            </h5>
                          </div>
                          <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
                            <div class="card-body">
                              Free delivery, installation and training
                            </div>
                          </div>
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