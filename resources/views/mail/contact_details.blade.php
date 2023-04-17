<!DOCTYPE html>
<html>

<head>
  <title>Email Template</title>

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
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
  <table style="margin:0 auto;width:600px;" class="email-wrapper">
    <tr>
      <th class="email-header"><img src="https://wosul.sa/public/images/logo.png"></th>
    </tr>
    <tr>
      <td class="email-user-info">Contact Enquiry</td>
    </tr>

    <tr>
      <td style="padding: 5px 5px 5px 15px;" class="email-url-title custom-width"><b style="color:#186CA5;">Name : </b> {{ $data->name }} </td>
    </tr>
    <tr>
      <td style="padding: 5px 5px 5px 15px;" class="email-url-title custom-width"><b style="color:#186CA5;">Email : </b> {{ $data->email }} </td>
    </tr>
    <tr>
      <td style="padding: 5px 5px 5px 15px;" class="email-url-title custom-width"><b style="color:#186CA5;">Subject : </b> {{ $data->subject }} </td>
    </tr>
    <tr>
      <td style="padding: 5px 5px 5px 15px;" class="email-url-title custom-width"><b style="color:#186CA5;">Message : </b> {{ $data->message }} </td>
    </tr>



    <tr>
      <td>To learn how to use WOSUL system: <a href="https://wosul.sa">Click here</a></td>
    </tr>
    <tr>
      <td class="email-support-link">For support, you can contact us via<br />
        <a href="mailto:support@wosul.sa"> support@wosul.sa </a>
      </td>
    </tr>
    <tr>
      <td class="email-footer">PO Box 93597,Riyadh 13414,Airport Branch Road,Saudi Arabia
        <br /><br />Contact : +966 11 222 2591 / +966 54 924 9523
      </td>
    </tr>

    <tr>
      <td style="text-align: center;font-size: 14px;" class="email-automatice text-center">This email was generated automatically</td>
    </tr>
  </table>
</body>

</html>