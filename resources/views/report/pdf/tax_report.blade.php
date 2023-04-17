<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Tax Report :: PDF</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        .table, .table th,.table tr,.table td{
            border: 1px solid #262323;
            border-collapse: collapse;
            padding: 1px 3px 1px 3px;
        }
    </style>
</head>
<body>

@php
    /*echo "<pre>";
    print_r($taxes);
    die;*/
@endphp
<table class="table table-striped">
    <thead>
    <tr>
        @foreach($columns as $column)
            <th scope="col" style="background: #A2A9B1">{{ $column }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php $i=1; @endphp
    @foreach($taxes as $main_row)
    <tr>
        <td scope="row" style="background: #A2A9B1">{{$i++}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['date']}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['reference_no']}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['order_invoice_no']}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['type']}}</td>
        <td class="bg-gray" style="background: #A2A9B1"></td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['quantity']}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{number_format($main_row['sale_price'],4)}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{$main_row['payment_type']}}</td>
        @if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0)
        <td class="bg-gray" style="background: #A2A9B1">{{ (isset($main_row['TOBACCO']) && $main_row['TOBACCO']>0) ? number_format($main_row['TOBACCO'],4) : number_format(0,4) }}</td>
        @endif
        @foreach($taxcodes as $tax)
            <td class="bg-gray" style="background: #A2A9B1">{{ number_format($main_row[$tax->tax_code],4) }}</td>
        @endforeach
        <td class="bg-gray" style="background: #A2A9B1">{{number_format($main_row['total'],4)}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{number_format($main_row['total_amount'],4)}}</td>
        <td class="bg-gray" style="background: #A2A9B1">{{ $main_row['created_by'] }}</td>
    </tr>
        @foreach($main_row['products'] as $product)
            <tr>
                <td scope="row"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$product['product_name']}}</td>
                <td>{{$product['quantity']}}</td>
                <td>{{$product['sale_price']}}</td>
                <td></td>
                @if(isset($is_tobacco) && $is_tobacco->tobacco_tax_val>0)
                    <td>{{ (isset($product['TOBACCO']) && $product['TOBACCO']>0) ? number_format($product['TOBACCO'],4) : number_format(0,4) }}</td>
                @endif
                @foreach($taxcodes as $tax)
                    <td>{{ number_format($product[$tax->tax_code],4) }}</td>
                @endforeach
                <td>{{number_format($product['total'],4)}}</td>
                <td>{{number_format($product['total_amount'],4)}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        @foreach($footers as $footer)
            <th scope="col" style="background: #A2A9B1">{{ $footer }}</th>
        @endforeach
    </tr>
    </tbody>
</table>


<div>
    <br>
</div>
<div class='center'>
    <div class='display-block text-uppercase '>POWERED BY WOSUL</div>
</div>

</body>
</html>
