@php $data = json_decode($data); @endphp
<!DOCTYPE html>
<html>

<head>
    <title>Order #{{ $data->order_number }}</title>
</head>

<body>
    <div class='pb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='w-50'>
                    @if($logo_path != '')
                    <img src="{{ $logo_path }}" class='h-50px' />
                    @endif
                </td>
                <td class='right bold'>INVOICE</td>
            </tr>
        </table>
    </div>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='bold display-block'>{{ $data->store->name }}</div>
        <div>
            {{ $data->store->address }}
            @if ($data->store->pincode != '')
            {{ $data->store->pincode }}
            @endif
        </div>
        {{--   @if ($data->store->tax_number != '')
                <div>GST: {{ $data->store->tax_number }}
    </div>
    @endif --}}
    @if ($data->store->primary_contact != '')
    <div>Contact No: {{ $data->store->primary_contact }}</div>
    @endif
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='bold w-50'>Order #{{ $data->order_number }}</td>
                <td class='right'>Date: {{ $data->created_at_label  }}</td>
            </tr>
            <tr>
                <td class='w-50'>Billed By: {{ $data->created_by->user_code }}</td>
                <td class='right'>Payment Method: {{ $data->payment_method }}</td>
            </tr>
            <tr>
                @php
                $quantity = 0;
                if(count($data->products)>0)
                {
                    foreach($data->products as $product)
                    {
                        $quantity += $product->quantity;
                    }
                }
                @endphp
                <td class='w-50'>Customer : {{ $data->customer_phone }}, {{ $data->customer_email }}</td>
                <td class='right'>{{ count($data->products)>0?count($data->products):0 }}
                    Items ({{ $quantity }} Qty)</td>
            </tr>
            @if ($data->restaurant_mode == 1)
            <tr>
                <td class='w-50'>Type: {{ $data->order_type }}</td>
                <td class='right'>Table: {{ ($data->table != '')?$data->table:'-' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='bold w-45'>Description</td>
            <td class='bold right'>Qty</td>
            <td class='bold right'>Rate</td>
            <td class='bold right'>Discount</td>
            <td class='bold right'>Tax</td>
            <td class='bold right'>Amount</td>
        </tr>
        @if(count($data->products)>0)
            @foreach ($data->products as $order_products)
                @php
                $spacing = '';
                if($order_products->tax_percentage>0 || $order_products->discount_percentage >0){
                    $spacing = 'pb-0';
                }
                @endphp
                <tr>
                    <td class='{{ $spacing }}'>{{ $order_products->product_code.'-'.$order_products->name }}

                        @if(isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if(is_string($order_products->order_product_modifier_options))
                                {
                                    $order_products->order_product_modifier_options =
                                    json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach($order_products->order_product_modifier_options as $modifier_option)
                                <br>
                                <small class="text-muted">{{ $modifier_option->modifier_label }} :
                                {{ $modifier_option->modifier_option_label }}</small>
                            @endforeach
                        @endif

                        @if($order_products->discount_percentage >0)
                        <br>
                        <small class="text-muted">Discount ({{ $order_products->discount_percentage }}%): {{
                        $order_products->discount_amount }}</small>
                        @elseif((int)$order_products->discount_amount >0)
                        <br>
                        <small class="text-muted">Discount: {{
                        $order_products->discount_amount }}</small>
                        @endif
                    </td>
                    <td class='{{ $spacing }} right'>{{ $order_products->quantity }}</td>
                    <td class='{{ $spacing }} right'>{{ $order_products->price }}
                        @php
                        $total_modifier_option_amount = 0;
                        @endphp

                        @if(isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if(is_string($order_products->order_product_modifier_options))
                                {
                                    $order_products->order_product_modifier_options =
                                    json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach($order_products->order_product_modifier_options as $modifier_option)
                                @php 
                                    $total_modifier_option_amount += $modifier_option->modifier_option_price; // * $order_products->quantity 
                                @endphp
                                <br>
                                <small>{{ number_format($modifier_option->modifier_option_price,2) }}</small>
                            @endforeach
                        @endif
                    </td>
                    <td class='{{ $spacing }} right'>{{ $order_products->discount_amount }}</td>
                    <td class='{{ $spacing }} right'>{{ $order_products->tax_amount }}</td>
                    <td class='{{ $spacing }} right'>{{ $order_products->total_after_discount }}</td>
                </tr>

                @if($order_products->tax_percentage>0 || $order_products->discount_percentage >0)

                    @php
                        $spacing = '';
                        if($order_products->tax_percentage > 0 || $order_products->discount_percentage > 0){
                            $spacing = 'pb-0';
                        }
                    @endphp

                    {{-- @if($order_products->discount_percentage >0)
                    <tr>
                        <td class='{{ $spacing }} small' colspan='4'>Discount ({{ $order_products->discount_percentage }}%):
                            {{ $order_products->discount_amount }}</td>
                    </tr>
                    @endif --}}
                    @if ($order_products->discount_percentage > 0)
                        <tr>
                            <td class='{{ $spacing }} small' colspan='4'>Discount
                                ({{ $order_products->discount_percentage }}%):
                                @php
                                    $item_discount_amount = 
                                        (($item_price + $total_modifier_option_amount) * ($order_products->discount_percentage/100)) * $order_products->quantity;
                                @endphp
                                {{ number_format($item_discount_amount,2) }} {{ __('SAR') }}</td>
                        </tr>
                    @elseif($order_products->discount_amount > 0)
                        <tr>
                            <td class='{{ $spacing }} small' colspan='4'>Discount :
                                {{ $order_products->discount_amount }} {{ __('SAR') }}</td>
                        </tr>
                    @endif

                    @if($order_products->tax_percentage>0)
                        <tr>
                            <td class='small' colspan='4'>
                                @if(count($order_products->tax_components)>0)
                                @foreach ($order_products->tax_components as $tax_component)
                                {{ strtoupper($tax_component->tax_type) }}({{ round($tax_component->tax_percentage, 2) }}%):
                                {{ number_format($tax_component->tax_amount,2) }}|
                                @endforeach
                                @endif
                                Tax Amount: {{ number_format($order_products->tax_amount,2) }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @endif
    </table>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='w-50'>Sub Total</td>
            <td class='right'>{{ number_format($data->sale_amount_subtotal_excluding_tax,2) }}</td>
        </tr>

        @php
        $spacing = '';
        if($data->order_level_discount_percentage > 0 || $data->product_level_total_discount > 0){
        $spacing = 'pb-0';
        }
        @endphp

        <tr>
            <td class='{{ $spacing }} w-50'>Discount</td>
            <td class='{{ $spacing }} right'>{{ $data->total_discount_before_additional_discount }}</td>
        </tr>
        @if($data->order_level_discount_percentage > 0)
        <tr>
            <td class='{{ $spacing }} small' colspan='2'>
                [Overall Discount
                {{ ($data->order_level_discount_percentage >0 )?'('.$data->order_level_discount_percentage.'%)':'' }}:
                {{ $data->order_level_discount_amount }}]
            </td>
        </tr>
        @endif
        @if($data->product_level_total_discount > 0)
        <tr>
            <td class='small' colspan='2'>
                [Product Discount: {{ $data->product_level_total_discount }}]
            </td>
        </tr>
        @endif

        @if($data->additional_discount_percentage > 0 && $data->sale_amount_subtotal_excluding_tax > 0)
        <tr>
            <td class='w-50'>Additional Discount</td>
            <td class='right'>({{ $data->additional_discount_percentage }}%) {{ $data->additional_discount_amount }}
            </td>
        </tr>
        @endif

        <tr>
            <td class='w-50'>Total Amount After Discount</td>
            <td class='right'>{{ number_format($data->total_after_discount,2) }}</td>
        </tr>

        {{-- @php 
                $spacing = '';            
                if($data->order_level_tax_percentage > 0 || $data->order_level_tax_amount > 0){
                    $spacing = 'pb-0';
                }
            @endphp --}}
        @foreach($data->order_level_tax_components as $tax_component)
        @if(!empty($tax_component->tax_percentage))
        <tr>
            <td class='w-50'>{{ $tax_component->tax_type }}({{ $tax_component->tax_percentage }}%)</td>
            <td class='right'>{{ number_format($tax_component->tax_amount, 2) }}</td>
        </tr>
        @endif
        @endforeach
        {{-- @if($data->order_level_tax_percentage >0)
            <tr>
                <td class='{{ $spacing }} small' colspan='2'>
        @if(count($data->order_level_tax_components)>0)
        [Overall Tax:
        @foreach ($data->order_level_tax_components as $tax_component)
        {{ strtoupper($tax_component->tax_type) }}({{ $tax_component->tax_percentage }}%) :
        {{ round($tax_component->tax_amount, 2) }}|
        @endforeach
        Tax Amount: {{ number_format($data->order_level_tax_amount,2) }}]
        @endif
        </td>
        </tr>
        @endif --}}

        {{-- @if($data->product_level_total_tax > 0)
            <tr>
                <td class='small' colspan='2'>
                    [Product Tax: {{ $data->product_level_total_tax }}]
        </td>
        </tr>
        @endif --}}

        <tr>
            <td class='bold w-50'>Bill Total</td>
            <td class='bold right'>{{ $data->store->currency_code }} {{ $data->total_order_amount }}</td>
        </tr>
        <tr>
            <td>
                <small>All prices are in {{ $data->store->currency_name }} ({{ $data->store->currency_code }})</small>
            </td>
        </tr>
    </table>

    <div class='center'>
        <div class='display-block'>Thank You!</div>
    </div>

</body>

</html>