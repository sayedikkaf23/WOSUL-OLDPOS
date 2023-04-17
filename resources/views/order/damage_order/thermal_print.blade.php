@php $data = (array)json_decode($data); @endphp
<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Order') }} #{{ $data[0]->id }}</title>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='center'>
            @if (file_exists(asset('storage/store/'.$logo_path)))
            <img class="nav-profile" src="{{ asset('storage/store/'.$logo_path) }}"  style="width: 60px;height: 15px;border-radius:0 !important;">
            @endif
        </div>
        <div class='bold display-block'>{{ $data[0]->store->name }}</div>
        <div>{{ $data[0]->store->primary_email }}</div>
        <div>{{ __('Tel') }}:{{ $data[0]->store->primary_contact }}</div>
        <div>{{ __('VAT Reg') }} #: {{ $data[0]->store->vat_number }}</div>
        <div>{{ __('Address') }}: {{ $data[0]->store->address }}</div>
        <br>
        <div>{{ __('Welcome') }}</div>
        <div>{{ __('Damage Order Receipt') }}</div>
        <div><small>{{ __('Printed at') }} : {{ date('d-m-y h:i:s') }}</small></div>
        {{-- <div>
                {{ $data[0]->store->address }}
        @if ($data[0]->store->pincode != '')
        {{ $data[0]->store->pincode }}
        @endif
    </div>
    @if ($data[0]->store->tax_number != '')
    <div>GST: {{ $data[0]->store->tax_number }}</div>
    @endif
    @if ($data[0]->store->primary_contact != '')
    <div>Contact No: {{ $data[0]->store->primary_contact }}</div>
    @endif --}}
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='bold w-50'>{{ __('Order') }} #{{ $data[0]->id }}</td>
                <td class='right'>{{ __('Date') }}: {{ (new \DateTime($data[0]->created_at))->format('Y-m-d H:i:s')  }}</td>
            </tr>
            <tr>
                <td class='w-50'>{{ __('Billed By') }}: {{ $data[0]->user->fullname }}</td>
                <td class='right'>{{ count($data) }} {{ __('Items') }} ({{ $data[0]->quantity }} {{ __('Qty') }})</td>
            </tr>
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
        <td class='bold w-45'>{{ __('Description') }}</td>
            <td class='bold right'>{{ __('Qty') }}</td>
            <td class='bold right'>{{ __('Price') }}</td>
            <td class='bold right'>{{ __('Tax') }}</td>
            <td class='bold right'>{{ __('Discount') }}</td>
        </tr>
        @php
        $sub_total = 0;
        $sub_total_including_tax = 0;
        $tax_amount = 0;
        $discount_amount = 0;
        $sub_total_excluding_tax = 0;
        @endphp
       @foreach($data as $product)
       @php
        $sub_total+=(float)$product->amount;
        $sub_total_excluding_tax+=(float)$product->amount - (float)$product->discount_amount;
        $sub_total_including_tax +=$sub_total_excluding_tax+(float)$product->tax_amount;
        $tax_amount += (float)$product->tax_amount;
        $discount_amount +=(float)$product->discount_amount;
       @endphp
        <tr>
            <td>{{$product->product}}</td>
            <td class='right'>{{$product->quantity}}</td>
            <td class='right'>{{$product->amount}}</td>
            <td class='right'>{{$product->tax_amount}}</td>
            <td class='right'>{{$product->amount+$product->tax_amount}}</td>
        </tr>
       @endforeach
        </table>
      <table class='border-bottom-dashed mb-1rem w-100'>
       <tr>
           <td>{{ __('Sub Total') }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td class=' right'>{{(float)$sub_total+$product->tax_amount}}</td>
        </tr>

        <tr>
           <td>{{ __('Discount') }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td class=' right'>{{(float)$discount_amount}}</td>
        </tr>

        <tr>
           <td>{{ __('Sub Total Exclude Tax') }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td class=' right'>{{(float)$sub_total_excluding_tax}}</td>
        </tr>

        <tr>
           <td>{{ __('Tax') }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td class=' right'>{{(float)$tax_amount}}</td>
        </tr>

        <tr>
           <td>{{ __('Bill Total') }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td class=' right'>{{(float)$sub_total_including_tax}}</td>
        </tr>
        </table>
    <div class='center'>
        {{ __('Terms & Conditions') }}
        <div class='display-block'>{{ $data[0]->store->pos_invoice_policy_information }} </div>
    </div>
    <div><br></div>
    <div class='center'>
        <div class='display-block text-uppercase '>{{ __('POWERED BY WOSUL') }}</div>
    </div>

</body>

</html>
