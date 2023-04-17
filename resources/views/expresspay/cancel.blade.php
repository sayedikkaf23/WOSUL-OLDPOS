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

                    <h4 class="alert alert-danger p-5">Oops! your payment has been cancelled.</h4>

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