@php 
    $transactions = json_decode($transactions); 

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
    <title>Transaction :: PDF</title>
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
            <td class='bold'>{{ __('Transaction Code') }}</td>
            <td class='bold '>{{ __('Transaction Date') }}</td>
            <td class='bold '>{{ __('Transaction Type') }}</td>
            <td class='bold '>{{ __('Account') }}</td>
            <td class='bold '>{{ __('Payment Method') }}</td>
            <td class='bold '>{{ __('Bll to Type') }}</td>
            <td class='bold '>{{ __('Bill To Name') }}</td>
            <td class='bold '>{{ __('Amount') }}</td>
            <td class='bold '>{{ __('Notes') }}</td>
            
            <td class='bold '>{{ __('Created On') }}</td>
            <td class='bold '>{{ __('Updated On') }}</td>
            <td class='bold '>{{ __('Created By') }}</td>
        </tr>
        @php  
            $total = 0;
        @endphp
        @foreach ($transactions as $transaction)

           @php
           $spacing = '';
           @endphp
     
           <tr>
            <td class='' valign="top">{{ $transaction->transaction_code }}</td>
            <td class='{{ $spacing }} '>
                {{ $transaction->transaction_date }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->transaction_type_data->label }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->account->label }}
            </td>
             <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->payment_method_data->label }}
             </td>  
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->bill_to }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->bill_to_name }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->amount }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $transaction->notes }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
                 {{$transaction->created_at_label }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
               {{ $transaction->updated_at_label }}
            </td>
            <td class='{{ $spacing }} ' valign="top">
               {{ (isset($transaction->created_by) && isset($transaction->created_by->fullname)) ? $transaction->created_by->fullname : '-' }}
            </td> 
               
        </tr>
      
        @endforeach
        
    </table>
   
    
    <div><br></div>
    <div class='center'>
        <div class='display-block text-uppercase '>POWERED BY WOSUL</div>
    </div>

</body>

</html>
