
@php 
    // dd($data['store_details']['vat_number']);
    $sale_details = $data['sale_details'];
    $purchase_details = $data['purchase_details'];
    if(isset($print_logo_path) && $print_logo_path != ""){
        $logo_path = asset('/storage/store/'.$print_logo_path);
    }else{
        $logo_path = asset('images/logo.jpg');
    }
    // print_r($sale_details);
    // echo $sale_details[2]['tax_due'];
    // dd();
@endphp

{{-- @dd('----------') --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Tax Return Report :: PDF</title>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='center'>
      
            @if (isset($print_logo_path) && !empty($print_logo_path) )  
                <img class="nav-profile" src="{{ $print_logo_path }}"  style="width: 60px;height: 25px;border-radius:0 !important;"> 
            @endif
        </div>
    
    </div>
    
    <table class="nowrap text-nowrap w-100"  border="2px black" id="report_details_table">
        <thead>
            <tr>
                <th  colspan="4" style="text-align: center;">
                    {{ __("Tax Retrun Report")}} 
                </th>
            </tr>
            <tr >
                @if($data['selected_date_period_type'] == 1)
                    <th  colspan="4"  style="text-align: center;">
                        {{$data['selected_month_name']}} - {{$data['selected_year']}}
                    </th>
                @elseif ($data['selected_date_period_type'] == 2)
                    <th  colspan="4" style="text-align: center;">
                        {{$data['selected_period_name']}} - {{$data['selected_year']}}
                    </th>
                @endif
            </tr>
            <tr>
                <th  colspan="4" style="text-align: center;">
                    {{ __("From: ") }} {{$data['from_date']}} {{ __("To: ") }} {{$data['to_date']}}
                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;" class="border-bottom-solid">
                    {{ __("Tax Number: ") }} {{$data['store_details']['vat_number']}}
                </th>
            </tr>

            <tr style="border: 1px solid black;">
                <th class="right border-bottom-solid"></th>
                <th class="right border-bottom-solid" >{{ __("Bill Amount") }}</th>
                <th class="right border-bottom-solid" >{{ __("Return Bill Amount") }}</th>
                <th class="right border-bottom-solid" >{{ __("Tax Amount") }}</th>
            </tr>
            <tr>
                <th class="border-bottom-solid" style=" border-bottom-solid" >{{ __("Tax") }}</th>
                <th class="border-bottom-solid" style="" >{{ __("Sales (Orders & Invoices)") }}</th>
                <th class="border-bottom-solid" style="" >{{ __("Sales Return (Orders & Invoices)") }}</th>
                <th class="border-bottom-solid" style="text-align: right;" >{{ __("VAT Due (Sale Tax - Sale Return Tax)") }}</th>
            </tr>
        </thead>
        <tbody >
            @foreach ($data['tax_details'] as $detail )
                <tr>
                    <td class="left border-bottom-solid" >{{ $detail->label }}</td>
                    <td class="right border-bottom-solid">{{ round($sale_details[$detail->id]['sale_orders'][0]['total_sale_amount'], 2) }}</td>
                    <td class="right border-bottom-solid">{{ round($sale_details[$detail->id]['sale_returns'][0]->total_sale_ret_amount,2) }}</td>
                    <td class="right border-bottom-solid">{{ round($sale_details[$detail->id]['tax_due'],2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th class=" border-bottom-solid" style="text-align: left;">{{ __("Total") }}</th>
                <th class=" border-bottom-solid" style="text-align: right; ">{{__("SAR")}} {{ round($sale_details['sale_order_total'],2)}}</th>
                <th class=" border-bottom-solid" style="text-align: right; ">{{__("SAR")}} {{ round($sale_details['sale_return_total'],2)}}</th>
                <th class=" border-bottom-solid" style="text-align: right; ">{{__("SAR")}} {{ round($sale_details['sale_order_total_tax_due'],2)}}</th>
            </tr>
            <tr><th  colspan="4">&nbsp;</th></tr>
            <tr>
                <th class=" border-bottom-solid" style="" >{{ __("Tax") }}</th>
                <th class=" border-bottom-solid" style="" >{{ __("Purchase") }}</th>
                <th class=" border-bottom-solid" style="" >{{ __("Purchase Return") }}</th>
                <th class=" border-bottom-solid" style="text-align: right;" >{{ __("VAT Paid (Purchase Tax - Purchase Return Tax)") }}</th>
            </tr>
            @foreach ($data['tax_details'] as $detail )
                <tr>
                    <td class="left border-bottom-solid">{{ $detail->label }}</td>
                    <td class="right border-bottom-solid">{{ round($purchase_details[$detail->id]['purchase_orders'][0]->total_purchase_amount,2) }}</td>
                    <td class="right border-bottom-solid">{{ round($purchase_details[$detail->id]['purchase_returns'][0]->total_purc_ret_amount,2) }}</td>
                    <td class="right border-bottom-solid">{{ round($purchase_details[$detail->id]['tax_paid'],2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th class=" border-bottom-solid" style="">{{ __("Total") }}</th>
                <th class=" border-bottom-solid" style="text-align: right;">{{__("SAR")}} {{round($purchase_details['purchase_order_total'],2)}}</th>
                <th class=" border-bottom-solid" style="text-align: right;">{{__("SAR")}} {{round($purchase_details['purchase_return_total'],2)}}</th>
                <th class=" border-bottom-solid" style="text-align: right;">{{__("SAR")}} {{round($purchase_details['purchase_order_total_tax_paid'],2)}}</th>
            </tr>
            <tr><th  colspan="4"> &nbsp;</th></tr>
            <tr>
                <th colspan="3" style="text-align: center;" class="border-bottom-solid bold">
                    {{__("Total VAT Due of current Period")}}
                </th>
                <th style="" class="border-bottom-solid bold">
                    {{__("SAR")}} {{$data['total_vat_due']}}
                </th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center;" class="border-bottom-solid bold">
                    {{__("VAT Return carried from previous period")}}
                </th>
                <th class="border-bottom-solid bold">{{$data['prev_vat_credit']}}</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center;" class="border-bottom-solid bold">
                    {{__("Corrections for previous period(-5000 to 5000)")}}
                </th>
                <th class="border-bottom-solid bold">{{$data['prev_vat_correction']}}</th>
            </tr>
            <tr>
                <th class=" border-bottom-solid bold    " colspan="3" style="text-align: center;">
                    {{__("Net VAT Due")}}
                </th>
                <th class=" border-bottom-solid bold" style="font-size: 12px;">
                    {{__("SAR")}} {{$data['net_vat_due']}}
                </th>
            </tr>
        </tbody>
    </table>
    
    <div><br></div>
    <div class='center'>
        <div class='display-block text-uppercase '>POWERED BY WOSUL</div>
    </div>

</body>

</html>
