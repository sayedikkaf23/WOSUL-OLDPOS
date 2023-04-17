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
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div class="header text-center">
            <img src="{{asset('images/subscription-invoice-logo')}}" alt="Image" title="Image" />
        </div>
        <p>Hi,</p>
        <p>This Email is to inform that a new purchase order has been received</p>
    </div>
    <div class="d-flex flex-column align-items-center justify-content-center">
        <p>Thanks & Regards</p>
        <p>wosul Team</p>
    </div>
</body>

</html>