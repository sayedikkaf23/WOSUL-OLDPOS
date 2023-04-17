@php $data = json_decode($data); @endphp
<!DOCTYPE html>
<html>

<head>
    <title>Order #{{ $data->order_number }}</title>
    <style type="text/css">
        .font-smooth {
            font-size: 10px;
            /* text-shadow: #fff 0px 1px 1px; */
            font-smoothing: antialiased;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: antialiased;
        }

    </style>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='bold display-block'>{{ $data->store->name }}</div>
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='bold w-50'>Order #{{ $data->order_number }}</td>
                <td class='right w-50'>Date: {{ $data->created_at_label }}</td>
            </tr>
            <tr>
                @if(isset($data->credit_amount) && $data->credit_amount > 0)
                    <td class='w-100'>
                        {{ __('Payment Type') }}: {{ $data->payment_method }}: {{ $data->credit_amount}} @if ($data->cash_amount > 0)
                            , {{ __('Cash')}}: {{ $data->cash_amount}}
                        @endif
                    </td>
                @else
                    <td class='w-100'>{{ __('Payment Type') }}: {{ $data->payment_method }} </td>
                @endif
            </tr>
            @if (isset($data->status->value) && $data->status->value == 7 && isset($data->payment_details->total_paid_amount))
                <tr>
                    <td colspan="2" class='bold'>{{ __('Payment Status') }}: {{ __('PostPaid')}}</td>
                </tr>
            @endif
            @if (isset($data->status->value) && $data->status->value == 5)
                <tr>
                    <td class='w-50'>Type: {{ $data->order_type }}</td>
                    <td class='right w-50'>Table: {{ ($data->table != '')?$data->table:'-'  }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='bold w-45'>Description</td>
            <td class='bold right'>Qty</td>
            <td class='bold right'>Rate</td>
            <td class='bold right'>Amount</td>
        </tr>
        @php
            $total_modifier_amount = 0;
            $sub_total = 0;
            $tax_amount = 0;
        @endphp
        @if (count($data->products) > 0)
            @foreach ($data->products as $order_products)
                @php
                    $total_modifier_option_amount = 0;
                @endphp
                <tr class="">
                    <td class='pb-1rem'>
                        {{ $order_products->product_code . '-' . $order_products->name }}
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1)
                            <img class="nav-profile" src="{{ asset('images/gift.png') }}" style="width: 10px;height: 10px;">
                        @endif
                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach ($order_products->order_product_modifier_options as $modifier_option)
                                <br>
                                <small class="text-muted">{{ $modifier_option->modifier_label }} :
                                    {{ $modifier_option->modifier_option_label }}</small>
                            @endforeach
                        @endif
                       

                        {{-- @if ((int) $order_products->discount_percentage > 0)
                            <br>
                            <small class="text-muted">Discount ({{ $order_products->discount_percentage }}%):
                                {{ $order_products->discount_amount }} {{ __('SAR') }}</small>
                        @endif
                        @if ((int) $order_products->discount_amount > 0)
                            <br>
                            <small class="text-muted">Discount:
                                {{ $order_products->discount_amount }} {{ __('SAR') }}</small>
                        @endif --}}
                    </td>
                    <td class='pb-1rem right' >{{ $order_products->quantity }}</td>
                    <td class='pb-1rem right' @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
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
                                @php $total_modifier_option_amount += $modifier_option->modifier_option_price;  // * $order_products->quantity;
                                @endphp
                                <br>
                                <small>{{ $modifier_option->modifier_option_price }}</small>
                            @endforeach
                        @endif
                    </td>
                    <td class='pb-1rem right' @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                        @php 
                            $total_after_discount = $order_products->total_after_discount;
                        @endphp
                        {{ number_format($total_after_discount, 2) }}
                    </td>
                </tr>
                @php
                    if(isset($order_products->is_gifted) && $order_products->is_gifted == 0){
                        $sub_total += $total_after_discount;
                    }
                @endphp
                @if ($order_products->tax_percentage > 0 || $order_products->discount_percentage > 0)
                    @php
                        $spacing = '';
                        if ($order_products->tax_percentage > 0 || $order_products->discount_percentage > 0) {
                            $spacing = 'pb-0';
                        }
                    @endphp
                    @php
                        $total_item_wise_disc_with_modif = number_format($order_products->discount_amount,2);
                    @endphp
                    @if ($order_products->discount_percentage > 0)
                        <tr>
                            <td class='{{ $spacing }} small font-smooth' colspan='4'>
                                Discount ({{ $order_products->discount_percentage }}%) :
                                @if($total_item_wise_disc_with_modif == 0)
                                    @php
                                        $item_discount_amount = (($item_price + $total_modifier_option_amount) * ($order_products->discount_percentage/100)) * $order_products->quantity;
                                        $total_item_wise_disc_with_modif = $item_discount_amount;
                                    @endphp
                                @endif
                                {{ number_format($total_item_wise_disc_with_modif,2) }} {{ __('SAR') }}<br>{{ isset($data_ar->discount) ? $data_ar->discount : '' }}
                            </td>
                        </tr>
                    @elseif($order_products->discount_amount > 0)
                        <tr>
                            <td class='{{ $spacing }} small font-smooth' colspan='4'>Discount :
                                {{ $total_item_wise_disc_with_modif }} {{ __('SAR') }}<br>{{ isset($data_ar->discount) ? $data_ar->discount : '' }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="small" colspan="4"></td>
                    </tr>
                @endif
            @endforeach
        @endif
    </table>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='w-50'>Sub Total(Exclude Tax)</td>
            <td class='right'>
                {{ number_format($sub_total, 2) }}
            </td>
        </tr>
        <tr>
            <td class=' w-50'>
                Discount{{ isset($data_ar->discount) ? $data_ar->discount : '' }}
                @if ($data->additional_discount_percentage > 0 && $sub_total > 0)
                    ({{ number_format($data->additional_discount_percentage, 0) }}%)
                @endif
                <br>
            </td>
            <td class=' right'>
                @if ($sub_total > 0)
                    {{ number_format($data->total_discount_amount, 2) }}
                @else 0.00 @endif
            </td>
        </tr>
        <tr>
            <td class='w-50'>Sub Total<span style="text-align:left!important;" dir="ltr"> (After Disc.)</span>
                <br>{{ isset($data_ar->subtotal_excluding_tax) ? $data_ar->subtotal_excluding_tax : '' }}
            </td>
            @php
                // $data->total_after_discount = $data->total_after_discount + $data->tax_amount;
                $total_after_discount = $sub_total - $data->total_discount_amount;
            @endphp
            <td class='right'>
                @if ($sub_total > 0 )
                    {{-- {{ number_format($total_after_discount, 2) }} --}}
                    {{-- {{ number_format($order_products->total_after_discount, 2) }} --}}
                    {{number_format($data->total_after_discount,2)}}
                @else
                    0.00
                @endif
            </td>
        </tr>
        {{-- @if ($data->additional_discount_percentage > 0)
            <tr>
                <td class='w-50'>Additional Discount</td>
                <td class='right'>({{ $data->additional_discount_percentage }}%)
                    {{ $data->additional_discount_amount }}
                </td>
            </tr>
        @endif --}}
        {{-- <tr>
            <td class='w-50'>Tax</td>
            <td class='right'>{{ $data->total_tax_amount }}</td>
        </tr> --}}
        @foreach ($data->order_level_tax_components as $tax_component)
            @if ((int) $tax_component->tax_percentage != 0)
                <tr>
                    <td class='w-50'>{{ $tax_component->tax_type }}
                        <span style="text-align:left!important;" dir="ltr">({{ $tax_component->tax_percentage . '%' }})</span>
                    </td>
                    <td class='right'> {{ number_format($tax_component->tax_amount, 2) }}</td>
                </tr>
            @endif
        @endforeach
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
    @if (isset($data->status->value) && $data->status->value == 7 && isset($data->payment_details->total_paid_amount))
        <table class='border-bottom-dashed mb-1rem w-100'>
            <tr>
                <td colspan="2" class='' align="center">{{ __('Last Paid Amount') }}: {{ $data->payment_details->last_paid_amount}}</td>
            </tr>
            <tr>
                <td class=''>{{ __('Total Paid Amount') }}: {{ $data->payment_details->total_paid_amount}}</td>
                <td class='' align="right">{{ __('Total Remaining Amount') }}: {{ $data->payment_details->total_balance_amount}}</td>
            </tr>
        </table>
    @endif
    <div class='center'>
        <div class='display-block'>Thank You!</div>
    </div>

</body>

</html>