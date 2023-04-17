<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body class="p-0 m-0">
<script>
    fbq('track', 'Contact');
</script>
    <div id="wrapper pt-0 mt-0 p-0">
       

        <section class="">
            <div class="container p-0">

                <div class="post-card-img-wrap">
                    <div class="post-card-img" style="max-width: 1200px;">
                        <div class="img">
                            <img loading="lazy" src="{{ asset('website/images/wosul-post-img.jpg') }}" alt="" />
                            {{-- <img loading="lazy" src="{{ asset('website/images/wosul-post-black-friday.jpg') }}" alt="" /> --}}
                        </div>
                        <div class="wh">
                            <a onClick="trackUserIp()" class="pl-5" href="#">
                                <img loading="lazy"  src="{{ asset('website/images/wosul-post-wh.png') }}" alt="" />
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>



    </div>



    @include('includes/footerscript')

    <script>

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

</body>


</html>
