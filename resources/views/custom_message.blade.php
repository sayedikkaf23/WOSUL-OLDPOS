<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else dir="ltr" lang="en" @endif>
<head>
    @include('includes/headerscript')
</head>

<body>

@include('includes/header')

<div id="wrapper p-5">

    <div class="p-5 m-5">
        <p class="alert alert-{{ ($message_params['type'] == "error") ? 'danger' : $message_params['type'] }}"> {{ __($message_params['message'])  }} </p>
    </div>

</div>

@include('includes/footer', ['showInquiryForm' => false])


@include('includes/footerscript')
<script>
   
</script>

</body>

</html>