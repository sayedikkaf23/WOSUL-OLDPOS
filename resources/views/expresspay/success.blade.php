<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>
<head>
    @include('includes/headerscript')
</head>

<body>

@include('includes/header')


<div id="wrapper">

    <section class="checkout-section">
        <div class="container">
            <div class="row" dir="ltr">

                <div class="col-12 text-center">

                    <h4 class="alert alert-success p-5">Your payment is successfull, thank you!</h4>

                </div>
                
            </div>
        </div>
    </section>

</div>

@include('includes/footer', ['showInquiryForm' => false])


@include('includes/footerscript')
<script>
   
</script>

</body>

</html>