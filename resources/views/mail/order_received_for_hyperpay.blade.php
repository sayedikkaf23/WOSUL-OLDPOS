<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Almarai&display=swap" rel="stylesheet" />

    <style type="text/css">
    body {
        margin: 30px;
        font-size: 13.5px;
        font-family: "Almarai", sans-serif;
    }

    /*** Common Code ***/

    @media (max-width: 480px) {
        #u_content_image_7 .v-src-width {
            width: auto !important;
        }

        #u_content_image_7 .v-src-max-width {
            max-width: 21% !important;
        }

        #u_content_image_6 .v-src-width {
            width: auto !important;
        }

        #u_content_image_6 .v-src-max-width {
            max-width: 27% !important;
        }

        #u_content_image_5 .v-src-width {
            width: auto !important;
        }

        #u_content_image_5 .v-src-max-width {
            max-width: 20% !important;
        }
    }


    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 0px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        /*text-align: left;*/
        padding: 8px;
    }

    th {
        text-align: left;
        background: #58595b;
        color: #fff;
    }

    .invoice-info-qrcode table tr td,
    .invoice-seller-buyer-details table tr td {
        width: 130px;
        white-space: nowrap;
    }

    .direction-rtl th {
        text-align: right;
    }

    .direction-rtl {
        direction: rtl !important;
        text-align: right !important;
    }

    .d-flex {
        display: flex;
    }

    .mt-1 {
        margin-top: 1rem !important;
    }

    .mt-2 {
        margin-top: 2rem !important;
    }

    .mt-3 {
        margin-top: 3rem !important;
    }

    .mt-5 {
        margin-top: 5rem !important;
    }

    .mb-2 {
        margin-bottom: 2rem !important;
    }

    .text-center {
        text-align: center !important;
        border-right: 1px solid transparent !important;
    }

    .title {
        padding: 10px;
    }

    .border-bottom {
        border-bottom: 1px solid #f0efef;
    }

    .justify-space-between {
        justify-content: space-between;
    }

    /*** Common Code Ends ***/

    #invoice-wrapper {
        width: 780px;
        background: #fff;
        box-shadow: 0px 0px 30px #eee;
        margin: 0 auto;
    }

    .invoice-inner {
        padding: 20px;
    }

    .header {
        width: 100%;
        padding: 10px 0px;
    }

    .header-02 {
        width: 100%;
        text-align: center;
    }

    .header-02 h4 {
        margin: 5px 0px;
    }

    .invoice-info-qrcode {
        width: 100%;
        display: flex;
    }

    .in-info {
        width: 100%;
        height: auto;
    }

    .in-info-en,
    .in-info-en table {
        width: 100%;
    }

    .in-qr {
        width: 25%;
        min-height: 150px;
        text-align: center;
    }

    .in-qr img {
        min-height: 150px;
        max-height: 150px;
    }

    .in-info table tr td {
        width: 130px;
        white-space: nowrap;
    }

    .in-info table tr td:first-child {
        border-right: 1px solid transparent;
    }

    .invoice-seller-buyer-details {
        width: 100%;
        display: flex;
    }

    .invoice-seller-buyer-details table tr td:first-child,
    .invoice-seller-buyer-details table tr th:first-child {
        border-right: 1px solid transparent;
    }

    .in-seller-details {
        width: 100%;
    }

    .list-items-details table tr th,
    .list-items-details table tr td {
        text-align: center;
    }

    .list-items-details table tr:nth-child(2) th {
        background: #186ca5;
        color: #fff;
        font-size: 13px;
    }

    .nowrap,
    .list-items-details table tr td {
        white-space: nowrap;
    }

    .title-en {
        text-align: left !important;
    }

    .title-ar {
        text-align: right !important;
        direction: rtl !important;
    }

    .total-amount-details table {
        width: 100%;
    }

    .total-amount-details table {
        width: 70%;
    }

    .total-amount-details table tr td {
        width: 25%;
    }

    table tr td:nth-child(2) {
        border-left: none;
        border-right: none;
    }

    .total-amount-details table tr td:first-child,
    .total-amount-details table tr th:first-child,
    .list-items-details table tr:first-child th:first-child {
        border-right: 1px solid transparent;
    }

    .total-amount-details table tr:last-child {
        font-weight: bold;
    }

    .total-amount-details.mt-1 {
        display: flex;
        justify-content: space-between;
    }

    .bank-account-details {
        width: 28%;
        background: #f9f9f9;
        border: 1px solid #f0efef;
    }

    .policy-link a {
        color: #d7d7d7;
        margin-right: 10px;
        text-decoration: none;
        border-right: 1px solid #d7d7d7;
        padding-right: 10px;
        margin-bottom: 10px;
    }

    .policy-link a:last-child {
        border-right: 1px solid #fff !important;
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            -moz-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
    </style>

    <title>Wosul:Invoice</title>
</head>

<body>
    <div id="invoice-wrapper">
        <div class="invoice-inner">
            <div class="header text-center">
                <img src="{{URL::asset('images/logo.png')}}" alt="Image" title="Image" height="100px"/>
            </div>

            <div class="header-02">
                <h4>فاتورة ضريبية</h4>
                <h4>Subscription Invoice</h4>
            </div>

            <div class="invoice-info-qrcode">
                <div class="in-info">
                    <div class="d-flex mt-3">
                        <div class="in-info-en">
                            <table>
                                <tr>
                                    <td>Invoice Issue Date:</td>

                                    <td class="text-center">{{date('d-m-Y')}}</td>

                                    <td class="direction-rtl">تاريخ اصدار الفاتورة :</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="in-qr">

                <img src="{{URL::asset('images/qr-code.png')}}">

            </div>
            <!-- Invoice Info and QR Code Ends -->

            <div class="invoice-seller-buyer-details mt-1">
                <div class="in-seller-details">
                    <div class="in-seller-en">
                        <table>
                            <tr>
                                <th colspan="2">Customer Details:</th>

                                <th class="direction-rtl">تفاصيل العميل:</th>
                            </tr>
                            <tr>
                                <td>Full Name:</td>

                                <td class="text-center">{{$data['name']}}</td>

                                <td class="direction-rtl">:الاسم الكامل</td>
                            </tr>
                            <tr>
                                <td>Mobile Number:</td>

                                <td class="text-center">{{$data['phone_number']}}</td>

                                <td class="direction-rtl">:رقم الهاتف المحمول</td>
                            </tr>
                            <!--<tr>

                  <td>Brand Name:</td>

                  <td class="text-center">demo</td>

                  <td class="direction-rtl">customer's brand name-ar:</td>

                </tr>-->
                            <tr>
                                <td>Email Address:</td>

                                <td class="text-center">{{$data['email']}}</td>

                                <td class="direction-rtl">:عنوان بريد الكتروني</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Invoice Seller and Buyer Details Ends -->
            @php
            $products = (array)$data['trackable_data'];
            $devicescount =count(array_filter($products,function($element){
            return $element->product_type=='device';
            }));

            $subscriptionCount =count(array_filter($products,function($element){
            return $element->product_type=='subscription';
            }));

            @endphp
            <div class="list-items-details mt-1">
                <table>
                    <tr>
                        <th class="title-en" colspan="4">
                            Subscription/Purchase Details:
                        </th>
                        <th class="title-ar" colspan="4">تفاصيل الاشتراك / الشراء:</th>
                    </tr>
                    @if($subscriptionCount>0)
                    <tr>
                        <th>Service<br /><span>اسم الصنف </span></th>
                        <th class="nowrap">Start Date<br /><span>سعر الوحدة</span></th>
                        <th class="nowrap">Duration<br /><span>الكمية</span></th>
                        <th class="nowrap">
                            End Date<br /><span>تاريخ الإنتهاء </span>
                        </th>
                        <th class="nowrap">Price<br /><span>الخصم</span></th>
                        <th class="nowrap">
                            Price Including Tax<br /><span>إجمالي المبلغ مع الضريبة </span>
                        </th>
                    </tr>
                    @foreach($products as $product)
                    @if($product->product_type=="subscription")
                    <tr>
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->subscription_start_date}}</td>
                        <td>{{$product->subscription_days}} Days</td>
                        <td>
                            {{(new \DateTime($product->subscription_start_date))->add(new \DateInterval("P{$product->subscription_days}D"))->format('Y-m-d')}}
                        </td>
                        <td>{{$product->amount}}</td>
                        <td>{{$product->total_amount}}</td>
                    </tr>
                    @endif @endforeach @endif @if($devicescount>0)
                    <tr>
                        <th>Device Name<br /><span>اسم الجهاز</span></th>
                        <th class="nowrap">Model<br /><span>نموذج</span></th>
                        <th class="nowrap">Quantity<br /><span>كمية</span></th>
                        <th class="nowrap">Price<br /><span>السعر</span></th>
                        <th class="nowrap">
                            Price Including Tax<br /><span>السعر شامل الضريبة</span>
                        </th>
                    </tr>
                    @foreach($products as $product) @if($product->product_type=="device")
                    <tr>
                        <td>{{$product->product_name}}</td>
                        <td>-</td>
                        <td>{{$product->device_count}}</td>
                        <td>{{$product->amount}}</td>
                        <td>{{$product->total_amount}}</td>
                    </tr>
                    @endif @endforeach @endif
                    <!--<tr>
              <td>Device Name</td>
              <td>05/02/2022</td>
              <td>60 Days</td>
              <td>05/04/2022</td>
              <td>320 SAR</td>
              <td>325 SAR</td>

            </tr>
            
            </tr>-->
                </table>
            </div>
            <!-- List Items Details Ends -->

            <div class="total-amount-details mt-1">
                <table>
                    <tr>
                        <th class="title-en" colspan="2">Payment Details:</th>
                        <th class="title-ar" colspan="2">إجمالي المبالغ:</th>
                    </tr>

                    <tr>
                        <td>Total</td>
                        @php
                        $finaltotal = 0;
                        $total_taxable_amount = 0;
                        foreach($products as $product)
                        {
                        $finaltotal += (float)$product->amount;
                        $total_taxable_amount +=(float)$product->total_amount;
                        }
                        @endphp
                        <td class="text-center">
                            {{$finaltotal}}
                            {{__('SAR')}}
                        </td>
                        <td class="direction-rtl">الإجمالي</td>
                    </tr>

                    <tr>
                        <td>Discount</td>
                        @php
                        $discount_amount = 0;
                        foreach($products as $product)
                        {
                        $discount_amount += (float)$product->discount_amount;
                        }
                        @endphp
                        <td class="text-center">
                            {{$discount_amount}}
                            {{__("SAR")}}</td>
                        <td class="direction-rtl">مجموع الخصم</td>
                    </tr>

                    <tr>
                        <td>Total Taxable Amount (Excluding VAT)</td>
                        <td class="text-center">{{$total_taxable_amount}}
                            {{__('SAR')}}</td>
                        <td class="direction-rtl">الإجمالي غير شامل الضريبة</td>
                    </tr>

                    <tr>

                        <td>Total Amount Due</td>
                        <td class="text-center">
                            {{$total_taxable_amount}}
                            {{__('SAR')}}
                        </td>
                        <td class="direction-rtl">إجمالي المبلغ المستحق</td>
                    </tr>
                </table>
            </div>

             <div class="delivery-address-details mt-1">
                <table>
                    <tr>
                        <th class="title-en" colspan="2">Delivery/Billing Address:</th>
                        <th class="title-ar" colspan="2">عنوان التسليم / الفواتير:</th>
                    </tr>

                    <tr>
                        <td>Country Code</td>
                        <td class="text-center">
                           {{ $data["country"] }}
                        </td>
                        <td class="direction-rtl">بلد إرسال الفواتير</td>
                    </tr>

                    <tr>
                        <td>State</td>
                        <td class="text-center">
                           {{ $data["state"] }}
                        </td>
                        <td class="direction-rtl">المنطقة</td>
                    </tr>

                    <tr>
                        <td>City</td>
                        <td class="text-center">
                           {{ $data["city"] }}
                        </td>
                        <td class="direction-rtl">المدينة</td>
                    </tr>

                    <tr>
                        <td>Zip Code</td>
                        <td class="text-center">
                           {{ $data["zipcode"] }}
                        </td>
                        <td class="direction-rtl">الرمز البريدي</td>
                    </tr>

                    <tr>

                        <td>Billing Street</td>
                        <td class="text-center">
                           {{ $data["address_line1"] }}
                        </td>
                        <td class="direction-rtl">عنوان الشارع</td>
                    </tr>
                </table>
            </div>

            <div class="thanks-msg-wrapper text-center mt-5">
                <div class="text-center mb-2 policy-link">
                    <a href="https://wosul.sa/ar/privacy_policy">Privacy Policy</a>

                    <a href="https://wosul.sa/ar/term_and_condition">Terms Of Service</a>
                </div>

                <h3>Thanks for choosing Wosul !!</h3>
            </div>
        </div>
    </div>
</body>

</html>