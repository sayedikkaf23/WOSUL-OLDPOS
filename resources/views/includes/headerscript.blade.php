  <!-- Required meta tags -->
  <meta charset="utf-8">
  <title>Wosul</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <meta name="_token" content="{{ csrf_token() }}">
  <link href="{{ asset('website/images/favicon.png') }} rel=" type="image/x-icon" />

  <!-- favicon -->
  <link rel="icon" type="image/jpg" href="{{ asset('website/images/favicon.png') }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('website/css/bootstrap.min.css') }}">
  <!-- Main Css -->
  <link rel="stylesheet" href="{{ asset('website/css/style.css') }}">
  <!-- Responsive Css-->
  <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}">

  <!-- Animation CSS -->
  <!-- <link href="{{ asset('website/css/aos.css') }}" rel="stylesheet"> -->

  <link rel="stylesheet" href="{{ asset('website/css/fancybox.css') }}" />

  <link rel="stylesheet" href="{{ asset('website/fonts/font.css') }}">
  <link rel="stylesheet" href="{{ asset('website/fonts/arabic-font.css') }}">
  <link rel="stylesheet" href="{{ asset('website/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('website/css/jquery-ui.css') }}">
  <meta name="facebook-domain-verification" content="q17iilhzm0u0sxfk1mgw47jqo07arq" />
  @php
    $snap_chat_id = env('SNAPCHAT_ID');
    $snap_email = env('SNAPCHAT_EMAIL');
    $GOOGLE_TAG_ID = env('GOOGLE_TAG_ID')??'';
    $GOOGLE_ANALYTIC_MEASUREMENT_ID = env('GOOGLE_ANALYTIC_MEASUREMENT_ID')??'';
  @endphp
  @if($snap_chat_id!=null)
    <!-- Snap Pixel Code -->
    <script type='text/javascript'>
      (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
      {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
        a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
        r.src=n;var u=t.getElementsByTagName(s)[0];
        u.parentNode.insertBefore(r,u);})(window,document,
              'https://sc-static.net/scevent.min.js');

      snaptr('init', '{{ $snap_chat_id }}', {
        'user_email': '{{ $snap_email }}'
      });

      snaptr('track', 'PAGE_VIEW');

    </script>
    <!-- End Snap Pixel Code -->
  @endif

  @php
    $fb_pixel_id = env('FACEBOOK_PIXEL_ID');
    $fb_pixel_url = "https://www.facebook.com/tr?id=".$fb_pixel_id."&ev=PageView&noscript=1";
  @endphp
  @if($fb_pixel_id!=null)
    <!-- Meta Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
              n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '{{ $fb_pixel_id }}');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="{{ $fb_pixel_url }}"
      /></noscript>
    <!-- End Meta Pixel Code -->
  @endif

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{$GOOGLE_ANALYTIC_MEASUREMENT_ID}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{$GOOGLE_ANALYTIC_MEASUREMENT_ID}}');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','{{$GOOGLE_TAG_ID}}');</script>
  <!-- End Google Tag Manager -->