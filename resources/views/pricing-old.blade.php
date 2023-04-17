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

    @include('includes/headerscript')
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T436WWS" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('includes/header')

    <div id="main" class="site-main" role="main">

        <section class="fly-sidebar-right container-min">


            <div class="pricing-bg">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12 text-center">

                            <h1 class="about-head" style="color:#fff;">

                                {{ __('Plans & Pricing') }}
                                <img src="{{ asset('website/images/about-right.png') }}"
                                    style="vertical-align: bottom; float: right; margin-right: 10px; margin-top: -20px;">
                            </h1>

                        </div>

                    </div>

                </div>

            </div>
            <div class="pricing-content">

                <div class="container">

                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <div class="tabs-content-wrapper">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                   <div class="d-flex justify-content-end">  
                                          <button type="button" class="btn btn-primary"><input type="image" class="btn" width='50' height='50' onclick="viewcart();" src="{{asset('images/Shopping-bag.png')}}" style="color:white;" title="view cart"/></button>
                                   </div>
                                </div>
                            </div>
                                <div class="row">
                                    <ul class="nav nav-pills mx-auto" role="tablist">
                                        <li class="nav-item ">
                                            <a class="nav-link active btn btn-primary text-uppercase "
                                                style="border-radius: 0; border-top-left-radius:30px; border-bottom-left-radius:30px;"
                                                data-toggle="tab" href="#tabs-1" role="tab">Subscriptions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-primary text-uppercase"
                                                style="border-radius: 0; border-top-right-radius:30px; border-bottom-right-radius:30px;"
                                                data-toggle="tab" href="#tabs-2" role="tab">Devices</a>
                                        </li>
                                    </ul><!-- Tab panes -->
                                </div>
                                <div class="tab-content mt-5">
                                    <div class="tab-pane active" id="tabs-1" role="tabpanel">

                                        <div class="row">

                                            @forelse($data['subscriptions'] as $subscription)
                                                <div class="col-md-4">
                                                    <div class="card-deck mb-3 text-center">
                                                        <div
                                                            class="card mb-4 box-shadow @if ($subscription->is_featured) border-success @endif ">
                                                            @if ($subscription->is_featured)
                                                                <span
                                                                    class="bg-success text-white text-uppercase">Featured</span>
                                                            @endif
                                                            <div class="card-header">
                                                                <h4 class="my-0 font-weight-normal text-uppercase">
                                                                    {{ App::getLocale() == 'ar' && $subscription->title_ar != '' ? $subscription->title_ar : $subscription->title }}
                                                                </h4>
                                                            </div>
                                                            <div class="card-body">
                                                                <h1 class="card-title pricing-card-title">
                                                                    <span
                                                                        class="currency-prefix">{{ $subscription->currency }}
                                                                    </span>
                                                                    {{ number_format($subscription->amount, 0) }}
                                                                    <span class="plan-type">/
                                                                        {{ __('Monthly') }}</span>
                                                                </h1>
                                                                <ul class="list-unstyled mt-3 mb-4">
                                                                    @forelse($subscription->features as $feature)
                                                                        <li class="text-dark">
                                                                            {{ App::getLocale() == 'ar' && $feature->title_ar != '' ? $feature->title_ar : $feature->title }}
                                                                        </li>
                                                                    @empty
                                                                    @endforelse
                                                                </ul>
                                                                <button type="button"
                                                                    class="btn btn-lg btn-block btn-outline-success" onclick="addToCart({{$subscription->id}},'subscription');">Add
                                                                    To
                                                                    Cart</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                {{ __('No Pricing Plans Found') }}
                                            @endforelse
                                        </div>
                                    </div>
                                    <d class="tab-pane" id="tabs-2" role="tabpanel">
                                        <div class="row">
                                            @forelse($data['devices'] as $device)
                                                <div class="col-md-4">
                                                    <div class="card-deck mb-3 text-center">
                                                        <div class="card mb-4 box-shadow">
                                                            <div class="card-header">
                                                                <h4 class="my-0 font-weight-normal">
                                                                    {{ App::getLocale() == 'ar' && $device->title_ar != '' ? $device->title_ar : $device->title }}
                                                                </h4>
                                                            </div>
                                                            <div class="card-body">
                                                                <h1 class="card-title pricing-card-title"><span
                                                                        class="currency-prefix">{{ $device->currency }}
                                                                    </span>
                                                                    {{ number_format($device->price, 0) }} <span
                                                                        class="plan-type">/
                                                                        {{ __('Nos') }}</span></h1>
                                                                <img src="{{ $device->image_path }}" alt=""
                                                                    style="width:150px;" class="my-5">
                                                                <button type="button"
                                                                    class="btn btn-lg btn-block btn-outline-primary" onclick="addToCart({{$device->id}},'device');">Add
                                                                    To
                                                                    Cart</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                {{ __('No Pricing Plans Found') }}
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            

<div class="modal" id="cartModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartmodalTitle">{{ __('Cart')}}</h5>
        <button type="button" class="close" id="cartcloseBtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-2" style="height:300px;overflow-y:scroll;" id="cartmodalContent">
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-primary" onclick="setUpProducts();" id="btnPayNow">{{__('Proceed to Checkout')}}</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="alreadyAddedModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{__('Already added')}}</h5>
        <button type="button" class="close" id="alreadyAddedcloseBtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p id="messageTxt">{{__('This product has been already added to the cart')}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnViewCart">{{__('View Cart')}}</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
      </div>
    </div>
  </div>
</div>

                <div class="row" style="display: none;">

                    <div class="col-md-1"></div>

                    @forelse($data['subscriptions'] as $subscription)
                        <div
                            class="col-md-3 @if ($loop->index == 0) custom-position @endif text-center no-padding">

                            <div class="pricing-wrapper @if ($loop->index == 1) center-box @endif">

                                <div class="pricing-title"
                                    style="background-color: {{ $subscription['color_code'] }}">
                                    <h5>{{ App::getLocale() == 'ar' && $subscription->title_ar != '' ? $subscription->title_ar : $subscription->title }}
                                    </h5>

                                </div>

                                @if (strtoupper($subscription->title) != 'CORPORATE PLAN')
                                    <div class="pricing-value">
                                        <h2 class="price"> <span
                                                class="currency-prefix">{{ $subscription->currency }} </span>
                                            {{ number_format($subscription->amount, 0) }} <span
                                                class="plan-type">/ {{ __('Monthly') }}</span></h2>
                                        <p><strong><u>{{ App::getLocale() == 'ar' && $subscription->short_description_ar != ''? $subscription->short_description_ar: $subscription->short_description }}
                                                </u></strong></p>

                                        @if ($subscription->discount != '')
                                            <p>{{-- <strong class="discount-text @if ($loop->index == 0) {{ 'bg-red' }}  @elseif($loop->index == 1) {{ 'bg-blue' }} @elseif($loop->index == 2) {{ 'box-03' }} @endif">{{__('Get')}} {{ $subscription->discount }}%</strong> --}}
                                                {{ App::getLocale() == 'ar' && $subscription->discount_description_ar != ''? $subscription->discount_description_ar: $subscription->discount_description }}
                                            </p>
                                        @endif

                                    </div>

                                    <div class="pricing-button">
                                        @php
                                            $params = ['subscription_id' => $subscription->id];
                                            
                                            $params['lang'] = app()->getLocale();
                                            
                                        @endphp
                                        <a href="{{ route('add_merchant', $params) }}"
                                            class="btn btn-md custom-pricing-button btn-block"
                                            style="background-color: {{ $subscription['color_code'] }}">
                                            {{ __('Order Now') }} </a>

                                        <!-- <small class="text-muted">Valid for 3 months</small>  -->

                                    </div>

                                    <div class="pricing-info">

                                        <ul class="list-group">
                                            @forelse($subscription->features as $feature)
                                                <li class="list-group-item">
                                                    {{ App::getLocale() == 'ar' && $feature->title_ar != '' ? $feature->title_ar : $feature->title }}
                                                </li>
                                            @empty
                                            @endforelse
                                        </ul>

                                    </div>
                                @else
                                    <div class="pricing-blank">
                                        <p><strong>{{ __('coming soon') }}</strong></p>
                                    </div>
                                @endif


                            </div>

                        </div>



                        @empty
                            {{ __('No Pricing Plans Found') }}
                        @endforelse


                    </div>

                </div>

        </div>
        </section>
        </div>

        @include('includes/footer')
        @include('includes/footerscript')
        
        <script>
            window.onload = function(){
                let subscription_id = parseInt("{{$data['subscription_id']}}");
                let renew_subscription=parseInt("{{$data['renew_subscription']==true?1:0}}");
                if(renew_subscription==1)
                {
                 addToCart(subscription_id,"subscription","{{$data['renewal_date']}}");
                  if(!$('body').hasClass('modal-open'))
                  {
                   viewcart();
                  }
                }
            }
            function setUpProducts(deleteProduct = 0){
                 $.ajax({
                          url: "/api/setProductListInSession",
                          type: "POST",
                          data: {"productlist":JSON.stringify(loadProducts()),"language":"{{App::getLocale()}}"},
                          dataType:"json",
                          success: function(response) {
                              if(deleteProduct==0)
                              {
                                window.location = "{{ App::getLocale() == 'ar'?url('/ar/checkout'):url('/en/checkout')}}";
                              }
                          },
                          error:function(xhr){
                            if(xhr.status.toString().startsWith("5")){
                                  if(typeof(xhr.responseJSON.message)!=="undefined" && xhr.responseJSON.message=="")
                                  {
                                     alert("some error have occured");
                                  }
                                  else
                                  {
                                    alert(xhr.responseJSON.message);
                                  }
                              }
                          }
                });
            }
            function checkForSubscriptions(){
                let alreadySubscribed = false;
                if(typeof(window.localStorage)!=="undefined"){
                     if(localStorage.getItem("products")){
                          productlist = JSON.parse(localStorage.getItem("products"));
                          for(let product of productlist){
                              if(product["productType"]=="subscription")
                              {
                                alreadySubscribed = true;
                              }
                          }
                     }
                     
                }
                return alreadySubscribed;
            }
            function showAlreadyExistingModal(){
                let modal = new bootstrap.Modal(document.getElementById('alreadyAddedModal'), {});
                    modal.show();
            }
            function closeAlreadyExistingModal(){
                document.querySelector("#alreadyAddedcloseBtn").click();
            }
             function closeCartModal(){
                document.querySelector("#cartcloseBtn").click();
            }
            function clearCart(){
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.clear();
                }
            }
            function deleteProduct(id){
                let newproductlist = [],index = 0;
                let products = loadProducts();
                for(let product of products)
                {
                    if(product['productId']!=id)
                    {
                        newproductlist.push(product);
                    }
                }
                saveToStorage(newproductlist);
                setUpProducts(1);
                viewcart();
            }
            function enablePayNowBtn(){
                let productlist;
                if(localStorage.getItem("products"))
                {
                  productlist = JSON.parse(localStorage.getItem("products"));
                }
                else
                {
                  productlist = [];
                }
                let disablePayNowbtn = false;
                for(let product of productlist){
                    if(product['subscriptionStartDate']==''||parseInt(product['price'])==0)
                    {
                        disablePayNowbtn = true;
                    }
                }
                if(disablePayNowbtn==true || productlist.length==0)
                {
                    $("#btnPayNow").hide();
                }
                else{
                    $("#btnPayNow").show();
                }
            }
            function changeDeviceCount(element){
                 let product_id = element.id.split("_")[1];
                 let subscriptionCount = 0,devicescount = 0,index = 0,currency='',price = 0;
                 let productlist = loadProducts();
                 price = element.value;
                 updatePrices(productlist,price,product_id);
                 console.log(localStorage.getItem("products"));
                 enablePayNowBtn();
                 
            }
            function updatePrices(productlist,price=0,product_id=0){
                let devicescount = 0,subscriptionCount = 0,total = 0,currency='';
                for(let product of productlist)
                {
                    currency = product['currency'];
                    if(product["productType"]=="subscription")
                     {
                       subscriptionCount+=1;
                     }
                     else
                     {
                        devicescount +=1;
                     }
                     currency = product['currency'];
                      if(product['productId']==product_id && product['productType']=="device" && product_id>0 && price>0)
                      {
                          product['devicecount'] = price;
                          product['price'] =parseFloat(product['unitprice'])*parseInt(price);
                          product['taxamount'] = Math.round(((0.15)*parseFloat(product['price']))*100)/100;;
                          product['priceincludingtax'] = product['price']+product['taxamount'];
                          document.querySelector("#txtDeviceTotal_"+product_id).innerHTML = `${price}<span class="ml-2">${currency}</span>`;
                      }
                     product['subscriptionCount'] = subscriptionCount;
                     product['devicescount'] = devicescount;
                }
                
                console.log(productlist);
                saveToStorage(productlist);
                setUpProducts(1);
                viewcart();
            }
            function saveToStorage(productlist)
            {
                if(typeof(window.localStorage)!=="undefined"){
                    localStorage.setItem("products",JSON.stringify(productlist));
                }
            }
            function changeSubscriptionStartDate(element){
                let product_id = element.id.split("_")[1];
                let total = 0,index = 0;
                let subscriptionCount = 0,devicescount = 0;
                let productlist = loadProducts();
                for(let product of productlist)
                {
                    if(product['productId']==product_id)
                    {
                        product['subscriptionStartDate'] = new Date(element.value).toISOString().split('T')[0];
                    }
                }
                saveToStorage(productlist);
                 enablePayNowBtn();
            }
            function loadProducts(){
                let productlist = [];
                if(typeof(window.localStorage)!=="undefined")
                {
                  if(localStorage.getItem("products"))
                  {
                  productlist = JSON.parse(localStorage.getItem("products"));
                  }
                }
                return productlist;
            }
            function viewcart(){
                closeCartModal();
                let productlist = [];
                let index = 0,currency = '',strHTML=``,taxamount = 0,finaltotal=0;
                productlist = loadProducts();
                if(productlist.length>0)
                {
                    strHTML = `<table class="table">`;
                    for(let product of productlist)
                    {
                      index+=1;
                      taxamount +=product.taxamount;
                      finaltotal += product.priceincludingtax; 
                      currency = product['currency'];
                      strHTML += `<tr>
                                    <td class="p-3">${index}</td>
                                    <td class="p-3"><p>${product['productName']}</p></td>
                                    <td class="p-3">${product['productType']=='device'?`<form class="form-inline"><label>No.of Devices : </label><input type="number" onchange="changeDeviceCount(this);" min="1" id="txtDeviceNumber_${product['productId']}" value="${product['devicecount']>0?product['devicecount']:1}" class="form-control" placeholder="Enter no of device" /></form>`:`<form class="form-inline"><label>{{__('Subscription Start Date')}} : </label><input type="date" id="txtSubscriptionStartDate_${product['productId']}" value='${product['subscriptionStartDate']}' class="form-control" onchange="changeSubscriptionStartDate(this);" /></form>`}</td>
                                    <td class="p-3"><p id="txtDeviceTotal_${product['productId']}">${product['price']} <span class="ml-2">${product['currency']}</span></h5></td>
                                    <td class="p-3"><button class="btn btn-danger" onclick="deleteProduct(${product['productId']});">Remove</button></td>
                                  </tr>`;
                     }
                     strHTML+=`<tr><td class="p-3"></td><td class="p-3"></td><td class="p-3"><h5>VAT(15%)</h5></td><td class="p-3"><p id="txtTaxTotal">${taxamount}<span class="ml-2">${currency}</span></p></td><td class="p-3"></td></tr>`;
                     strHTML+=`<tr><td class="p-3"></td><td class="p-3"></td><td class="p-3"><h5>Total</h5></td><td class="p-3"><p id="txtTotal">${finaltotal}<span class="ml-2">${currency}</span></p></td><td class="p-3"></td></tr></table>`;
                }
                else
                {
                    strHTML = `<div class="d-flex flex-column align-items-center justify-content-center">`;
                    strHTML+=`<img src="{{asset('/images/cart-empty.png')}}" width="100" height="100"/><h5>Cart is empty</h5></div>`;
                }
                document.querySelector("#cartmodalContent").innerHTML = strHTML;
                enablePayNowBtn();
                console.log(localStorage.getItem("products"));
                let modal = new bootstrap.Modal(document.getElementById('cartModal'), {});
                modal.show();

            }
            function addToCart(id,product_type,subscriptionstartdate="{{date('Y-m-d')}}"){
               
                let tax = 0;
                let productlist = [],subscriptioncount = 0,alreadyExist = 0,devicecount = 0,total = 0,devicescount = 0,subscriptionCount = 0;
                productlist = loadProducts();
                for(let product of productlist)
                {
                    if(product["productId"]==id && product["productType"]==product_type)
                    {
                        alreadyExist = 1;
                    }
                }
                if(alreadyExist==1 || (checkForSubscriptions()==true && product_type=="subscription"))
                {
                    $("#messageTxt").html("This product has been already added to the cart");
                    if(alreadyExist==0)
                    {
                        $("#messageTxt").html("{{__('You have already chosen one plan')}}");
                    }
                   showAlreadyExistingModal();
                   document.querySelector("#btnViewCart").addEventListener("click",function(){
                    closeAlreadyExistingModal();
                    viewcart();
                   });
                }
                else
                {
                  if(product_type=="device")
                  {
                      let priceincludingtax = 0,taxamount = 0;
                    @foreach($data['devices'] as $device)
                    product_id = parseInt("{{$device->id}}");
                    if(product_id==id)
                    {
                      devicescount +=1; 
                      total = parseFloat("{{ number_format($device->price, 0) }}");
                      taxamount = (0.15*total);
                      taxamount = Math.round(taxamount*100)/100;
                      priceincludingtax = total+(0.15*total);
                      if(productlist.length>0)
                      {
                          total+=parseFloat( productlist[productlist.length-1].price);
                          subscriptioncount = parseFloat( productlist[productlist.length-1].subscriptionCount);
                      }
                      productlist.push({
                        'productId' : product_id,
                        'subscriptionCount':subscriptioncount,
                        'devicescount':devicescount,
                        'totalamount':total,
                        'devicecount':1,
                        'taxamount':Math.round(taxamount*100)/100,
                        'discountamount':0,
                        'priceincludingtax':priceincludingtax,
                        'productImage':"{{ $device->image_path }}",
                        'productType':'device',
                        'productName':"{{ App::getLocale() == 'ar' && $device->title_ar != '' ? $device->title_ar : $device->title }}",'currency':"{{ $device->currency }}",
                        'price':parseFloat("{{ number_format($device->price, 0) }}")*1,
                        'unitprice':parseInt("{{ number_format($device->price, 0) }}"),
                        'planType':"{{ __('Nos') }}"
                    });
                    }
                    @endforeach
                 }
                 else
                 {
                    let priceincludingtax = 0,taxamount = 0;
                   @foreach($data['subscriptions'] as $subscription)
                   subscription_id = parseInt("{{$subscription->id}}");
                   if(subscription_id==id)
                   {
                      subscriptionCount+=1;
                      total = parseFloat("{{ number_format($subscription->amount, 0) }}");
                      taxamount = (0.15*total);
                      taxamount = Math.round(taxamount*100)/100;
                      priceincludingtax = total+taxamount;
                      if(productlist.length>0)
                      {
                          total+=parseFloat( productlist[productlist.length-1].price);
                          devicescount = productlist[productlist.length-1].devicescount;
                          devicecount = productlist[productlist.length-1].devicecount;
                      }
                      productlist.push({
                        'productId':subscription_id,
                        'devicescount':devicescount,
                        'devicecount':devicecount,
                        'subscriptionCount':subscriptionCount,
                        'totalamount':total,
                        'discountamount':0,
                        'taxamount':Math.round(taxamount*100)/100,
                        'priceincludingtax':priceincludingtax,
                        'subscriptionDays':30,
                        'productType':'subscription',
                        'productName':"{{ App::getLocale() == 'ar' && $subscription->title_ar != '' ? $subscription->title_ar : $subscription->title }}",
                        'currency':"{{ $subscription->currency }}",
                        'price':parseFloat("{{ number_format($subscription->amount, 0) }}"),
                        'planType':"{{ __('Monthly') }}",
                        'subscriptionStartDate':subscriptionstartdate
                      });
                   }
                   @endforeach
               }
               localStorage.setItem("products",JSON.stringify(productlist));
               viewcart();
            }   
            }
        </script>
    </body>

    </html>
