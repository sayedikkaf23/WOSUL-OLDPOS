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
    <title>Damage Order :: PDF</title>
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
        <tr >
            <td class='bold' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('ID') }}</td>
            <td class='bold' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Product') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Branch') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Branch Reference') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Order Type') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Added By') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Order Reference') }}</td>
            
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Time') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Quantity') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Amount') }}</td>
            <td class='bold ' style="white-space:nowrap;margin:10px;padding:10px;">{{ __('Reason') }}</td>
        </tr>
        @php  
            $total = 0;
            $total_quantity = 0.00;
        @endphp
        @foreach ($orders as $order)
           @php
           $spacing = '';
              $total+=round((float)$order->amount,2);
              $total_quantity += $order->quantity;
      
           @endphp
        
     
           <tr>
            <td class='' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">{{ $order->id }}</td>
            <td class='{{ $spacing }} ' style="white-space:nowrap;margin:10px;padding:10px;">
                {{ $order->product }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
                {{ $order->branch }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
                {{ $order->branch_reference }}
               
            </td>
             <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
                {{ $order->return_type }}
             </td>  
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
                {{ $order->added_by }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
                 {{$order->order_reference }}
               
            </td>
                        
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
               {{ $order->time }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
               {{ $order->quantity }}
               
            </td>
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
               {{ $order->amount+$order->tax_amount }}
               
            </td> 
            <td class='{{ $spacing }} ' valign="top" style="white-space:nowrap;margin:10px;padding:10px;">
               {{ $order->reason }}
               
            </td> 
               
        </tr>
      
        @endforeach
        <tr><td colspan="11"><hr /></td></tr>
        <tr><td><strong>
            {{ __('Total') }}
        </strong></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;"></td><td style="white-space:nowrap;margin:10px;padding:10px;">{{$total_quantity}}</td><td style="white-space:nowrap;margin:10px;padding:10px;"> {{$total}}</td></tr>
    </table>
   
    
    <div><br></div>
    <div class='center'>
        <div class='display-block text-uppercase '>POWERED BY WOSUL</div>
    </div>

</body>

</html>
