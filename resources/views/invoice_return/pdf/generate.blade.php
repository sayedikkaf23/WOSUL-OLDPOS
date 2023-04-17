@php 
    $invoices = json_decode($invoices); 

    if(isset($print_logo_path) && $print_logo_path != ""){
        $logo_path = asset('/storage/store/'.$print_logo_path);
    }else{
        $logo_path = asset('images/logo.jpg');
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Return Invoice :: PDF</title>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='center'>
      
            @if (file_exists(public_path('storage/store/'.$print_logo_path)) )  
            <img class="nav-profile" src="{{ $logo_path }}"  style="width: 60px;height: 15px;border-radius:0 !important;"> 
             @endif
        </div>
    
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100' align="center">
            <tr>
                <td class='bold w-50'>{{__('Date')}} : {{ $from_date }} - {{ $to_date }}</td>
            </tr>
                       
           
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='bold '>{{ __('Invoice Return Number') }}</td>
            <td class='bold '>{{ __('Bill To') }}</td>
            <td class='bold '>{{ __('Bill Name') }}</td>
            <td class='bold '>{{ __('Bill Email Id') }}</td>
            <td class='bold '>{{ __('Bill Contact') }}</td>
            <td class='bold '>{{ __('Store') }}</td>
            <td class='bold '>{{ __('Total Discount Amount') }}</td>
            <td class='bold '>{{ __('Total Tax Amount') }}</td>
            <td class='bold '>{{ __('Amount') }}</td>
            <td class='bold '>{{ __('Status') }}</td>
            <td class='bold '>{{ __('Created On') }}</td>
            <td class='bold '>{{ __('Updated On') }}</td>
            <td class='bold '>{{ __('Created By') }}</td>
        </tr>
        @php  
            $total = 0;
        @endphp
        
        @foreach ($invoices as $invoice)
           @php
           $spacing = '';
           $total+=$invoice->total_order_amount;
           @endphp
        


           <tr>
            <td class='' valign="top">{{ $invoice->return_invoice_number }}</td>
            <td class='{{ $spacing }} '>
                {{ $invoice->return_invoice_number }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->bill_to }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->bill_to_name }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->bill_to_email }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->bill_to_contact }}
               
            </td>
             <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->store->name }}
             </td>  
             <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->total_discount_amount }}
             </td>  
             <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->total_tax_amount }}
             </td>  
             <td class='{{ $spacing }} ' valign="top">
                {{ $invoice->total_order_amount }}
             </td>  
             <td class='{{ $spacing }} ' valign="top">
             {{ $invoice->status->label }}
             </td>  
    
            <td class='{{ $spacing }} ' valign="top">
                 {{$invoice->created_at_label }}
               
            </td>       
            <td class='{{ $spacing }} ' valign="top">
               {{ $invoice->updated_at_label }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
               {{ (isset($invoice->created_by) && isset($invoice->created_by->fullname)) ? $invoice->created_by->fullname : '-' }}
               
            </td> 
               
        </tr>
      
        @endforeach
        <tr><td colspan="13"><hr /></td></tr>
        <tr><td><strong>
            {{ __('Total') }}
        </strong></td><td></td><td></td><td></td><td> {{$total}}</td></tr>
    </table>
   
    
    <div><br></div>
    <div class='center'>
        <div class='display-block text-uppercase '>POWERED BY WOSUL</div>
    </div>

</body>

</html>
