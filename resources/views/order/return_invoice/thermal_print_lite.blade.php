@php $data = json_decode($data); @endphp
<!DOCTYPE html>
<html>

<head>
    <title>Order #{{ $data->order_number }}</title>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='bold display-block'>{{ $data->store->name }}</div>
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='bold w-50'>Order #{{ $data->order_number }}</td>
                <td class='right'>Date: {{ $data->created_at_label }}</td>
            </tr>
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='bold w-45'>Description</td>
            <td class='bold right'>Qty</td>
            <td class='bold right'>Rate</td>
            <td class='bold right'>Amount</td>
        </tr>
        @if (count($data->products) > 0)
            @foreach ($data->products as $order_products)
                @php
                    $total_modifier_option_amount = 0;
                    $total_item_price_with_modif = 0;
                @endphp
                <tr class="">
                    <td class='pb-1rem'>
                        {{ $order_products->product_code . '-' . $order_products->name }}
                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach ($order_products->order_product_modifier_options as $modifier_option)
                                <br>
                                <small class="text-muted">{{ $modifier_option->modifier_label }} : {{ $modifier_option->modifier_option_label }}</small>
                            @endforeach
                        @endif

                        
                    </td>
                    <td class='pb-1rem right'>{{ $order_products->quantity }}</td>
                    <td class='pb-1rem right'>
                        @php
                            $item_price = isset($order_products->price) ? $order_products->price : $order_products->amount;
                        @endphp
                        {{ $item_price }}

                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach ($order_products->order_product_modifier_options as $modifier_option)
                                @php $total_modifier_option_amount += $modifier_option->modifier_option_price; // * $order_products->quantity; 
                                    // $total_item_price_with_modif += ($modifier_option->modifier_option_price + $item_price );
                                @endphp
                                <br>
                                <small>{{ number_format($modifier_option->modifier_option_price,2) }}</small>
                            @endforeach
                        @endif
                    </td>
                    <td class='pb-1rem right'>{{ $order_products->total_after_discount }}</td>
                </tr>
                <tr>
                    @if ((int) $order_products->discount_percentage > 0)
                        <br>
                        <small class="text-muted">Discount ({{ $order_products->discount_percentage }}%):
                            @php
                                $item_discount_amount = (($item_price + $total_modifier_option_amount) * ($order_products->discount_percentage/100)) * $order_products->quantity;
                            @endphp
                            {{ number_format($item_discount_amount,2) }} {{ __('SAR') }}</small>
                    @elseif((int) $order_products->discount_amount > 0)
                        <br>
                        <small class="text-muted">Discount: {{ $order_products->discount_amount }} {{ __('SAR') }}</small>
                    @endif
                </tr>
            @endforeach
        @endif
    </table>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='w-50'>Sub Total(Exclude Tax)</td>
            <td class='right'>{{ $data->sale_amount_subtotal_excluding_tax + $order_products->tax_amount }}</td>
        </tr>
        <tr>
            <td class='w-50'>Discount</td>
            <td class='right'>{{ $data->total_discount_amount }}</td>
        </tr>

        @if ($data->additional_discount_percentage > 0 && $data->sale_amount_subtotal_excluding_tax > 0)
            <tr>
                <td class='w-50'>Additional Discount</td>
                <td class='right'>({{ $data->additional_discount_percentage }}%) {{ $data->additional_discount_amount }} </td>
            </tr>
        @endif
        <tr>
            <td class='w-50'>Tax</td>
            <td class='right'>{{ $data->total_tax_amount }}</td>
        </tr>
        <tr>
            <td class='bold w-50'>Bill Total</td>
            <td class='bold right'>{{ $data->store->currency_code }} {{ $data->total_order_amount }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <small>All prices are in {{ $data->store->currency_name }} ({{ $data->store->currency_code }})</small>
            </td>
        </tr>
    </table>

    <div class='center'>
        <div class='display-block'>Thank You!</div>
    </div>

</body>

</html>