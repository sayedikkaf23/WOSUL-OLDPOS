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
                  <a class="nav-link" href="{{ url('/ar')}}">
                    <span><i class="fas fa-language"> </i> </span> AR</a>
                </li>
                
              </ul>

            </div>            

            <nav id="sticky-top" class="navbar navbar-expand-lg navbar-light">
            
              <a class="navbar-brand" href="#"><img class="img-fluid site-logo" src="{{ asset('images/logo.png') }}"alt="Logo" /></a>
              
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
                    
                    <a class="nav-link login-btn pl-3 pr-3 green-tab" href="{{ route('login')}}">Login</a>

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
                With WOSUL..<br>
                Make your interactions with<br>
                Clients, Products<br>
                and your facility numbers much easier.
              </h1>

            </div>

          </div>

          <div class="col-lg-5">

            <div class="banner-video">

              <div class="video-bg visible-lg">

              </div>
              <video controls="">
                <source src="{{ asset('videos/wosul-intro.mp4') }}" type="video/mp4">
                Your browser does not support HTML5 video.
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
              <b>WOSUL..</b><br>
              Providing an easy-to-use and linked system that supports, 
              managing inventory and branches points with high efficiency to provide you with real-time, comprehensive and accurate statistics related to your facility.
            </h5>

            <a class="btn btn-primary green-btn mt-3" href="">Request Demo</a>

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

        		<h3 class="title text-center mb-5">Our Feature</h3>

        	</div>

          <div class="col-12">

  			    <ul class="nav nav-pills nav-fill feature-tabs mb-4">
  				    <li ><a data-toggle="tab" class="feature-tab-link active green-tab" href="#inventory">Inventory </a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link gray-tab" href="#dashboard">Dashboard</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link yellow-tab" href="#pos">Point of Sales</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link red-tab" href="#sales">Sales</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link blue-tab" href="#finance">Finance</a></li>
  				    <li><a data-toggle="tab" class="feature-tab-link black-tab" href="#purchases">Purchases</a></li>
  				  </ul>

    			 	<div class="tab-content">
    				    <div id="inventory" class="tab-pane fade in active">
                  <div class="row">
                    <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                      <img src="{{ asset('images/demo-img.png') }}" class="img-fluid border-radius-5">
                    </div>
                    <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 green-tab no-padding">
        				      <div class="inventory-feature">
                        <h4>Inventory</h4>
                        <ul class="custom-ul">
                          <li>Create product groups.</li>
                          <li>Link inventory to POS lists.</li>
                          <li>Dealing with various branches in particular.</li>
                          <li>Reflection of purchases in inventory lists directly.</li>
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
                      <div class="dashboard-feature">
                        <h4>Dashboard</h4>                          
                        <ul class="custom-ul">
                          <li>Sync points of sales, sales, and expenses and display them in various statistics.</li>
                          <li>Statistics and graphical representation of best selling products.</li>
                          <li>Statistics and graphical representation of best selling products.</li>
                          <li>Graphic display of the highest expenses.</li>
                          <li>Tools for monitoring and improvement.</li>
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
                      <div class="pos-feature">
                        <h4>Point of Sales</h4> 
                        <ul class="custom-ul">
                            <li>Maintain customer information.</li>
                            <li>Clearly review product listings.</li>
                            <li>Perform payments for products in a number of ways.</li>
                            <li>Scan QR codes for products and create a receipt.</li>
                            <li>Print or share bills.</li>
                            <li>It allows you to create more than one employee for the same system separately.</li>
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
                      <div class="sales-feature">
                        <h4>Sales</h4>
                        <ul class="custom-ul">
                          <li>Expenses / Revenue by  product.</li>
                          <li>Product Sales of the period of time (i.e. Day, Week, Month, Year).</li>
                          <li>Product Sales by Salesperson.</li>
                          <li>Product Sales, Sales Team / Channel.</li>
                          <li>Product Sales by Warehouse.</li>
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
                      <div class="finance-features">
                        <h4>Finance</h4>
                        <ul class="custom-ul">
                          <li>General ledger.</li>
                          <li>Accounts payable (AP).</li>
                          <li>Accounts receivable (AR).</li>
                          <li>Fixed asset management.</li>
                          <li>P/L statements </li>
                          <li>Tax statement generation.</li>
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
                      <div class="purchases-feature">
                        <h4>Purchases</h4>
                        <ul class="custom-ul">
                          <li>Adding products easley.</li>
                          <li>Identifying suppliers for products easley.</li>
                          <li>Choose any of the branches separately.</li>
                          <li>Display the products on the purchases page in detail.</li>
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

              <a href="#" class="text-primary">Shop All Hardware</a>-->
              <h3 class="title text-center mt-5 mb-3"><a href="#" class="text-primary">Shop All Hardware</a></h3>
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

            <h3 class="title text-center text-white mb-3">The best merchent experiences</h3>

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
                    <p class="footer-head">Contact Us</p>
                    <p class="footer-cont"><a href="mailto:support@wosul.sa">support@wosul.sa</a></p>
                    <p class="footer-cont"><a href="mailto:info@wosul.sa">info@wosul.sa</a></p>
                    <p class="footer-cont"><a href="tel: +966549249523">+966 549 249 523</a></p>
                    <p class="footer-cont"><a href="http://www.wasap.my/966549249523">Whatsapp</a></p>
                    

                     
                </div>
                <div class="col-lg-3">
                    <p class="footer-head">Follow Us</p>
                    <p  class="footer-cont"><a href="https://twitter.com/wosulerp?lang=en" target="_blank">Twitter</a></p>
                    <p class="footer-cont"><a href="https://www.instagram.com/wosulerp/?hl=af" target="_blank">Instagram</a></p>
                    <p class="footer-cont"><a href="https://www.linkedin.com/company/wosul/?viewAsMember=true" target="_blank">LinkedIn</a></p>
                </div>
                <div class="col-lg-3">
                    <p class="footer-head"><a href="https://goo.gl/maps/BymKXtZcbbg1AzS86" target="_blank">Make A Visit</a></p>
                    <p class="footer-cont"><a>Al Olaya</a></p>
                    <p  class="footer-cont"><a>Riyadh 11564</a></p>
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