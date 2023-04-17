<!DOCTYPE html>
<html>

<head>
  <title>Email Template</title>

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    .header {
        width: 100%;
        padding: 10px 0px;
      }

    th,
    td {
      padding: 15px;
    }

    .email-header {
      background: #186CA5;
    }

    .email-footer {
      background: #186CA5;
      color: #fff;
      font-size: 12px;
      text-align: center;
    }
  </style>

</head>

<body>
  @php
    $productid = $data['product_id'];
    $redirecturl = url("/en/pricing?subscription_id=".$productid."&renew_subscription=true");
  @endphp
  <div class="d-flex flex-column align-items-center justify-content-center">
    <div class="header text-center">
      <img src="{{asset('images/subscription-invoice-logo')}}"  alt="Image" title="Image"/>
    </div>
      <p>Hi,</p>
      <p>This Email is to inform you that your subscription(Title : {{$data['title']}}, Start Date : {{(new \DateTime($data['subscription_start_date']))->format('d-m-Y')}}) is about to expire in {{$data['days']}} days.</p>
      <p>Please click on the below link to renew it</p>
      <a href="{{url('/en/pricing?renew=true&days='.$data['days'].'&renewid='.$productid)}}" target="_blank">Renew Subscription</a>
</div>
<div class="d-flex flex-column align-items-center justify-content-center">
    <p>Thanks & Regards</p>
    <p>wosul Team</p>
</div>
</body>

</html>