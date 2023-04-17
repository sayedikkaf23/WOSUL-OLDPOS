<!doctype html>
<html @if (App::getLocale()=='ar' ) lang="ar" dir="rtl" @else lang="en" @endif>
<head>
    @include('includes/headerscript')
</head>

<body>

@include('includes/header')

@php
    $count = mb_substr_count($data['transaction']['bill_to_name'],'?');
    if ($count>0 || !preg_match('/[a-zA-Z]/', $data['transaction']['bill_to_name'])) {
        if($data['invoice']['bill_to']=='COMPANY'){
            $name = 'Company';
        }else{
            $name = 'Customer';
        }
    }else{
        $name = $data['transaction']['bill_to_name'];
    }
@endphp
<div id="wrapper">

    <section class="checkout-section">
        <div class="container">
            <div class="row" dir="ltr">

                <div class="col-4"></div>
                <div class="col-4">
                    <table class=" table table-bordered table-striped table-condensed">
                        <tr>
                            <td>Bill Type</td>
                            <td>{{ $data['transaction']['bill_to'] }}</td>
                        </tr>
                        <tr>
                            <td>Bill Id</td>
                            <td>{{ $data['transaction']['bill_to_id'] }}</td>
                        </tr>
                        <tr>
                            <td>Bill To</td>
                            <td>{{ $name }}</td>
                        </tr>
                        <tr>
                            <td>Billing Amount</td>
                            <td>{{ $data['transaction']['amount']." ".$data['transaction']['currency_code'] }}</td>
                        </tr>
                        <form method="post" action="{{ route('expresspay_checkout') }}">
                            @csrf
                            <input type="hidden" name="payment_slack" value="{{ $data['payment_slack'] }}">
                            <input type="hidden" name="company_url" value="{{ $data['company_url'] }}">
                            <tr>
                                <td colspan="2" class="text-center"><button type="submit" href="" class="btn custom-btn" style="padding: 0px; line-height: 0px;">Pay Now</button></td>
                            </tr>
                        </form>
                    </table>

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