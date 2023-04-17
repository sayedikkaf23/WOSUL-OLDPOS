<!DOCTYPE html>
<html>

<head>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-T436WWS');
  </script>
  <!-- End Google Tag Manager -->
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> -->

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-197961721-1">
  </script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-197961721-1');
  </script>

  <title>WOSUL</title>

  @include('includes.headerscript')
  @include('components.message')

</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T436WWS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  @include('includes.header')

  <div class="site-banner-wrapper">

    <div id="boxes" style="display: none;">

        <div style="display: none;" id="dialog" class="window">
          
          <a href="#">

            <img src="{{ asset('website/images/offer_popup.jpeg')  }}" class="img-fluid"/>

          </a>

        </div>

        <div style="width: 100%; font-size: 32pt; color:white; height: 100%; display: none; opacity: 0.8;" id="mask">

        </div>

      </div>

    <div id="particles-js"></div>

    <div class="container">

      <div class="row">

        <div class="col-lg-7 no-padding">

          <div class="banner-text">

            <h1>
              {{__('With WOSUL..')}}<br>
              {{__('Make your interactions with')}}<br>
              {{__('Clients, Products')}}<br>
              {{__('and your facility numbers much easier.')}}
            </h1>

          </div>

        </div>

        <div class="col-lg-5">

          <div class="banner-video">

            <div class="video-bg visible-lg">

            </div>
            <video controls="">
              <source src="{{ asset('website/videos/wosul-intro.mp4') }}" type="video/mp4">
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
            <b>{{__('WOSUL..')}}</b><br>
            {{__('Providing an easy-to-use and linked system that supports, managing inventory and branches points with high efficiency to provide you with real-time, comprehensive and accurate statistics related to your facility.')}}
          </h5>

          <a class="btn btn-primary green-btn mt-3" href="{{ route('pricing',app()->getLocale()) }}">{{__('Request')}} {{__('Demo') }}</a>

        </div>

        <div class="col-12">

          <img class="img-fluid" src="{{ asset('website/images/app-sample-img.png')  }}">

        </div>

      </div>

    </div>

  </div><!-- Site Block-02-Wrapper DIV Closed -->

  <div class="site-block-03-wrapper">

    <div class="container">

      <div class="row">

        <div class="col-12">

          <h3 class="title text-center mb-5">{{__('Our Feature')}}</h3>

        </div>

        <div class="col-12">

          <ul class="nav nav-pills nav-fill feature-tabs mb-4">
            <li><a data-toggle="tab" class="feature-tab-link active green-tab" href="#inventory">{{__('Inventory')}} </a></li>
            <li><a data-toggle="tab" class="feature-tab-link gray-tab" href="#dashboard">{{__('Dashboard')}}</a></li>
            <li><a data-toggle="tab" class="feature-tab-link yellow-tab" href="#pos">{{__('Point of Sales')}}</a></li>
            <li><a data-toggle="tab" class="feature-tab-link red-tab" href="#sales">{{__('Sales')}}</a></li>
            <li><a data-toggle="tab" class="feature-tab-link blue-tab" href="#finance">{{__('Finance')}}</a></li>
            <li><a data-toggle="tab" class="feature-tab-link black-tab" href="#purchases">{{__('Purchases')}}</a></li>
          </ul>

          <div class="tab-content">
            <div id="inventory" class="tab-pane fade in active">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 green-tab no-padding">
                  <div class="inventory-feature">
                    <h4>{{__('Inventory')}}</h4>
                    <ul class="custom-ul">
                      <li>{{__('Create product groups.')}}</li>
                      <li>{{__('Link inventory to POS lists.')}}</li>
                      <li>{{__('Dealing with various branches in particular.')}}</li>
                      <li>{{__('Reflection of purchases in inventory lists directly.')}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div id="dashboard" class="tab-pane fade">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 gray-tab no-padding">
                  <div class="dashboard-feature">
                    <h4>{{__('Dashboard')}}</h4>
                    <ul class="custom-ul">
                      <li>{{__('Sync points of sales, sales, and expenses and display them in various statistics.')}}</li>
                      <li>{{__('Statistics and graphical representation of best selling products.')}}</li>

                      <li>{{__('Graphic display of the highest expenses.')}}</li>
                      <li>{{__('Tools for monitoring and improvement.')}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div id="pos" class="tab-pane fade">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 yellow-tab no-padding">
                  <div class="pos-feature">
                    <h4>{{__('Point of Sales')}}</h4>
                    <ul class="custom-ul">
                      <li>{{__('Maintain customer information.')}}</li>
                      <li>{{__('Clearly review product listings.')}}</li>
                      <li>{{__('Perform payments for products in a number of ways.')}}</li>
                      <li>{{__('Scan QR codes for products and create a receipt.')}}</li>
                      <li>{{__('Print or share bills.')}}</li>
                      <li>{{__('It allows you to create more than one employee for the same system separately.')}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div id="sales" class="tab-pane fade">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 red-tab no-padding">
                  <div class="sales-feature">
                    <h4>{{__('Sales')}}</h4>
                    <ul class="custom-ul">
                      <li>{{__('Expenses / Revenue by  product.')}}</li>
                      <li>{{__('Product Sales of the period of time (i.e. Day, Week, Month, Year).')}}</li>
                      <li>{{__('Product Sales by Salesperson.')}}</li>
                      <li>{{__('Product Sales, Sales Team / Channel.')}}</li>
                      <li>{{__('Product Sales by Warehouse.')}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div id="finance" class="tab-pane fade">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 blue-tab no-padding">
                  <div class="finance-features">
                    <h4>{{__('Finance')}}</h4>
                    <ul class="custom-ul">
                      <li>{{__('General ledger.')}}</li>
                      <li>{{__('Accounts payable (AP).')}}</li>
                      <li>{{__('Accounts receivable (AR).')}}</li>
                      <li>{{__('Fixed asset management.')}}</li>
                      <li>{{__('P/L statements')}} </li>
                      <li>{{__('Tax statement generation.')}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div id="purchases" class="tab-pane fade">
              <div class="row">
                <div class="col-6 col-sm-4 col-lg-4 feature-tabs-img">
                  <img src="{{ asset('website/images/demo-img.png')  }}" class="img-fluid border-radius-5">
                </div>
                <div class="col-6 col-sm-8 col-lg-8 feature-tabs-content border-radius-5 black-tab no-padding">
                  <div class="purchases-feature">
                    <h4>{{__('Purchases') }}</h4>
                    <ul class="custom-ul">
                      <li>{{__('Adding products easley.')}}</li>
                      <li>{{__('Identifying suppliers for products easley.')}}</li>
                      <li>{{__('Choose any of the branches separately.')}}</li>
                      <li>{{__('Display the products on the purchases page in detail.')}}</li>
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
            <h3 class="title text-center mt-5 mb-3"><a href="#" class="text-primary">{{__('Shop All Hardware')}}</a></h3>
          </div>

          <div class="col-12">

            <div class="js-slider isystkSlider" data-shift="1" data-carousel="true">
              <div class="view-layer">
                <ul class="parent">
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/all-in-one-01.png')  }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/invoice-printer.png')  }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/ipad-stand-01.png')  }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/all-in-one-01.png')  }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/invoice-printer.png')  }}" width="280px">
                    </p>
                  </li>
                  <li class="child">
                    <p>
                      <img src="{{ asset('website/images/ipad-stand-01.png')  }}" width="280px">
                    </p>
                  </li>
                </ul>
              </div>
              <div>
                <p class="next-btn"><a href="#"><img src="{{ asset('website/images/btn-next.png')  }}" alt=">>"></a></p>
                <p class="prev-btn"><a href="#"><img src="{{ asset('website/images/btn-prev.png')  }}" alt="<<"></a></p>
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

          <h3 class="title text-center text-white mb-3">{{__('The best merchent experiences')}}</h3>

        </div>

        <div class="col-12">

          <div class="js-slider isystkSlider isystkBrandSlider" data-shift="5" data-carousel="true" data-auto-slide="true">
            <div class="view-layer">
              <ul class="parent">
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-01.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-02.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-03.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-04.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-05.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-06.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-07.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">

                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-09.jpg')  }}" width="120px">
                  </p>
                </li>
                <li class="child">
                  <p>
                    <img src="{{ asset('website/images/clients/Client-10.jpg')  }}" width="120px">
                  </p>
                </li>
              </ul>
            </div>
          </div>

        </div>

      </div>

    </div>

  </div> <!-- Site Block-05-Wrapper DIV Closed -->

  @include('includes.footer')
  @include('includes.footerscript')

</body>

</html>