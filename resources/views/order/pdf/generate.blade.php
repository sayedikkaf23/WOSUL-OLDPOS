@php 
    $orders = json_decode($orders); 

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
    <title>Order :: PDF</title>
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
            <td class='bold'>{{ __('Transaction ID') }}</td>
            <td class='bold '>{{ __('Order Number') }}</td>
            <td class='bold '>{{ __('Customer Phone') }}</td>
            <td class='bold '>{{ __('Customer Email') }}</td>
            <td class='bold '>{{ __('Amount') }}</td>
            <td class='bold '>{{ __('Status') }}</td>
            
            <td class='bold '>{{ __('Created On') }}</td>
            <td class='bold '>{{ __('Updated On') }}</td>
            <td class='bold '>{{ __('Created By') }}</td>
        </tr>
        @php  
            $total = 0;
        @endphp
        @foreach ($orders as $order)
           @php
           $spacing = '';
           if($order->status->label!="Return")
           {
              $total+=$order->total_order_amount;
           }
           @endphp
        
     
           <tr>
            <td class='' valign="top">{{ $order->id }}</td>
            <td class='{{ $spacing }} '>
                {{ $order->order_number }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $order->customer_phone }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                {{ $order->customer_email }}
               
            </td>
             <td class='{{ $spacing }} ' valign="top">
                {{ $order->status->label=='Return'?'0.00':$order->total_order_amount }}
             </td>  
            <td class='{{ $spacing }} ' valign="top">
                {{ $order->status->label }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
                 {{$order->created_at_label }}
               
            </td>
                        
            <td class='{{ $spacing }} ' valign="top">
               {{ $order->updated_at_label }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top">
               {{ (isset($order->created_by) && isset($order->created_by->fullname)) ? $order->created_by->fullname : '-' }}
               
            </td> 
               
        </tr>
      
        @endforeach
        <tr><td colspan="9"><hr /></td></tr>
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
