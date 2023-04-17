@php
$total_after_discount = 0;
$data = json_decode($data);
// $arabic= get_arabic_text();
// if(isset($arabic))
// {
// $data_ar=json_decode($arabic);
// }
// else
// {
// $data_ar = '';
// }
//dd($data->products[0]->discount_amount);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    @if (App::getLocale() == 'ar')
        <link href="{{ public_path('css/order_thermal_print_invoice_ar.css') }}" rel="stylesheet" type="text/css" />
    @endif
    <title>{{ __('Order') }} #{{ $data->order_number }}</title>

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
        <div class='center'>
            {{-- @if (file_exists(public_path('storage/store/' . $logo_path))) --}}

            <img class="nav-profile" src="{{ $logo_path }}"
                style="width: 120px;height: 35px;border-radius:0 !important;padding-bottom:2px;">
            {{-- @endif --}}
        </div>
        <div class='bold display-block font-smooth'>{{ $data->store->name }}</div>
        {{-- <div><a href="http://wosul.com">http://wosul.com</a></div> --}}
        <div class="bold font-smooth">{{ $data->store->primary_email }}</div>
        <div class="bold font-smooth">{{ __('Tel') }}:{{ $data->store->primary_contact }}</div>
        @if ($data->store->vat_number != '')
            <div class="bold font-smooth">{{ __('VAT Reg') }}. #: {{ $data->store->vat_number }}</div>
        @endif
        <div class="bold font-smooth">{{ __('Address') }}: {{ $data->store->address }}</div>
        <br>
        <div class="font-smooth">{{ __('Welcome') }}</div>
        {{-- <div class="font-smooth">{{__('Official Receipt')}}
    </div> --}}
        @if ($data->store->vat_number != '')
        <div class="font-smooth">{{ __('Simplified Tax Invoice') }}</div>
        @else
        <div class="font-smooth">{{ __('Invoice') }}</div>
        @endif
        <div class="font-smooth"><small>{{ __('Printed at') }} : {{ date('d-m-y h:i:s') }}</small></div>
        {{-- <div>
                {{ $data->store->address }}
    @if ($data->store->pincode != '')
    {{ $data->store->pincode }}
    @endif
    </div>
    @if ($data->store->tax_number != '')
    <div>GST: {{ $data->store->tax_number }}</div>
    @endif
    @if ($data->store->primary_contact != '')
    <div>Contact No: {{ $data->store->primary_contact }}</div>
    @endif --}}
    </div>
    <div class='border-bottom-dashed pt-1rem mb-1rem'>
        <table class='w-100'>
            <tr>
                <td class='font-smooth bold w-50'>{{ __('Order') }} #{{ $data->order_number }}</td>
                <td class='font-smooth right'><small>{{ __('Date') }}:{{ $data->created_at_label }}</small></td>
            </tr>
            <tr>
                <td class='font-smooth w-50'>{{ __('Billed By') }}: {{ $data->created_by->fullname }}</td>
                @php
                    $quantity = 0;
                    if (count($data->products) > 0) {
                        foreach ($data->products as $product) {
                            $quantity += $product->quantity;
                        }
                    }
                @endphp
                <td class='font-smooth right w-50'>
                    {{ count($data->products) > 0 ? count($data->products) : 0 }}
                    {{ __('Items') }} -
                    {{ $quantity }} {{ __('Qty') }} </td>
            </tr>
            @if (isset($data->status->value) && $data->status->value == 5)
                <tr>
                    <td class='w-50 font-smooth'>Type: {{ $data->order_type }}</td>
                    <td class='right font-smooth'> {{ ($data->table != '')? 'Table: '.$data->table:''  }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="2" class='font-smooth bold'>{{ __('Reference Number') }} # {{ $data->reference_number }}</td>
            </tr>
            <tr>
                @if(isset($data->credit_amount) && $data->credit_amount > 0)
                    <td class='font-smooth w-100'>
                        {{ __('Payment Type') }}: {{ $data->payment_method }}: {{ $data->credit_amount}} @if ($data->cash_amount > 0)
                            , {{ __('Cash')}}: {{ $data->cash_amount}}
                        @endif
                    </td>
                @else
                    <td class='font-smooth w-100'>{{ __('Payment Type') }}: {{ $data->payment_method }} </td>
                @endif
            </tr>
            @if (isset($data->status->value) && $data->status->value == 7 && isset($data->payment_details->total_paid_amount))
                <tr>
                    <td colspan="2" class='font-smooth bold'>{{ __('Payment Status') }}: {{ __('PostPaid')}}</td>
                </tr>
            @endif
            @if( isset($data->customer) &&  !is_null($data->customer) )
            
                @if($data->customer->name != '')
                <tr>
                    <td class='font-smooth bold w-50' style="padding:0px;">{{ __('Customer Name') }}: </td>
                    <td class='font-smooth w-50' style="padding:0px;" align="right"> {{ $data->customer->name }}</td>
                </tr>
                @endif

                @if($data->customer->email != '')
                <tr>
                        <td class='font-smooth bold w-50' style="padding:0px;">{{ __('Customer Email') }}: </td>
                        <td class='font-smooth w-50' style="padding:0px;" align="right"> {{ $data->customer->email }}</td>
                </tr>
                @endif
                
                @if($data->customer->phone != '')
                <tr>
                        <td class='font-smooth bold w-50' style="padding-top:0px;">{{ __('Customer Phone') }}: </td>
                        <td class='font-smooth w-50' style="padding-top:0px;" align="right"> {{ $data->customer->phone }}</td>
                </tr>
                @endif

            @endif
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='font-smooth bold w-45'>{{ __('Description') }}</td>
            <td class='font-smooth bold right'>{{ __('Qty') }}</td>
            <td class='font-smooth bold right'>{{ __('Price') }}</td>
            <!-- <td class='font-smooth bold right'>{{ __('Tax') }}</td> -->
            @if (isset($data->order_bonat_discount) && $data->order_bonat_discount == true)
                <td class='font-smooth bold right'>{{ __('Discount') }}</td>
            @endif

            <td class='font-smooth bold right'>{{ __('Amount') }}</td>
        </tr>
        @php
            $total_modifier_amount = 0;
            $sub_total = 0;
        @endphp

        @if (count($data->combos) > 0)
            @foreach ($data->combos as $order_combo)
                <tr>
                    <td class="font-smooth" style="font-size:11px;">  {{ $order_combo->combo_name }}  </td>
                    <td class="font-smooth right">    </td>
                    <td class="font-smooth right">  </td>
                    <td class="font-smooth right"> {{ $order_combo->combo_total_price  }} </td>
                </tr>
                @foreach ($order_combo->combo_products as $order_products)
                @php
                    $spacing = '';
                    $total_modifier_option_amount = 0;
                    if ($order_products->tax_percentage > 0 || $order_products->discount_percentage > 0) {
                        $spacing = 'pb-0';
                    }
                @endphp
                <tr>
                    <td class='{{ $spacing }}'>
                        @if ($order_products->product_code != '')
                            {{ $order_products->product_code . '-' }}
                        @endif
                        {{ $order_products->name }}
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
                        @if($order_products->description != null && ($order_products->show_description_in == 1 || $order_products->show_description_in == 3 || $order_products->show_description_in == 5 || $order_products->show_description_in == 6))
                        <br>
                        <small style="color:lightgrey">-----</small>
                        <br />
                        <small>{{ $order_products->description }}</small>
                        @endif
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1)
                        <img class="nav-profile" src="{{ asset('images/gift.png') }}"
                        style="width: 10px;height: 10px;">
                        @endif
                    </td>
                    <td class='{{ $spacing }} right ' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                        {{-- {{ $order_products->quantity }} --}}
                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            
                        @endif
                    </td>
                    <td class='{{ $spacing }} right ' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                        @php
                            $item_price = isset($order_products->price) ? $order_products->price : $order_products->amount;
                        @endphp
                        {{-- {{ $item_price }} --}}

                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach ($order_products->order_product_modifier_options as $modifier_option)
                                @php $total_modifier_option_amount += $modifier_option->modifier_option_price; @endphp
                                {{-- <br> --}}
                                {{-- <small>{{ number_format($modifier_option->modifier_option_price, 2) }}</small> --}}
                            @endforeach
                        @endif

                    </td>
                    @if (isset($data->order_bonat_discount) && $data->order_bonat_discount == true)
                        <td class='{{ $spacing }} right ' 
                            @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                            {{ $order_products->bonat_discount == true ? 'BONAT' : '-' }}
                        </td>
                    @endif

                    <!-- <td class='{{ $spacing }} right'>{{ $order_products->tax_amount }}</td> -->
                    <td class='{{ $spacing }} ' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                        @php 
                            $total_after_discount = $order_products->total_after_discount;
                        @endphp
                        {{-- {{ number_format($total_after_discount, 2) }}	 --}}
                    </td>
                </tr>
                
                @php

                    if(isset($order_products->is_gifted) && $order_products->is_gifted == 0){
                        $sub_total += $total_after_discount + $total_modifier_option_amount;
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
                                {{ __('Discount') }} ({{ $order_products->discount_percentage }}%) :
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
                            <td class='{{ $spacing }} small font-smooth' colspan='4'>{{ __('Discount') }} :
                                {{ $total_item_wise_disc_with_modif }} {{ __('SAR') }}<br>{{ isset($data_ar->discount) ? $data_ar->discount : '' }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="small" colspan="4"></td>
                    </tr>
                @endif
            @endforeach
            
            @endforeach 
        @endif
        
        @if (count($data->products) > 0)
            @foreach ($data->products as $order_products)
                @php
                    $spacing = '';
                    $total_modifier_option_amount = 0;
                    if ($order_products->tax_percentage > 0 || $order_products->discount_percentage > 0) {
                        $spacing = 'pb-0';
                    }
                @endphp
                <tr>
                    <td class='{{ $spacing }} font-smooth'>
                        @if ($order_products->product_code != '')
                            {{ $order_products->product_code . '-' }}
                        @endif
                        {{ $order_products->name }}
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
                        @if($order_products->description != null && ($order_products->show_description_in == 1 || $order_products->show_description_in == 3 || $order_products->show_description_in == 5 || $order_products->show_description_in == 6))
                        <br>
                        <small style="color:lightgrey">-----</small>
                        <br />
                        <small>{{ $order_products->description }}</small>
                        @endif
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1)
                        <img class="nav-profile" src="{{ asset('images/gift.png') }}"
                        style="width: 10px;height: 10px;">
                        @endif
                    </td>
                    <td class='{{ $spacing }} right font-smooth' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                        {{ $order_products->quantity }}
                        @if (isset($order_products->order_product_modifier_options) && !empty($order_products->order_product_modifier_options))
                            @php
                                if (is_string($order_products->order_product_modifier_options)) {
                                    $order_products->order_product_modifier_options = json_decode($order_products->order_product_modifier_options);
                                }
                            @endphp
                            @foreach ($order_products->order_product_modifier_options as $modifier_option)
                                <br>
                                <small>-</small>
                            @endforeach
                        @endif
                    </td>
                    <td class='{{ $spacing }} right font-smooth' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
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
                                @php $total_modifier_option_amount += $modifier_option->modifier_option_price; @endphp
                                <br>
                                <small>{{ number_format($modifier_option->modifier_option_price, 2) }}</small>
                            @endforeach
                        @endif

                    </td>
                    @if (isset($data->order_bonat_discount) && $data->order_bonat_discount == true)
                        <td class='{{ $spacing }} right font-smooth' 
                            @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
                            {{ $order_products->bonat_discount == true ? 'BONAT' : '-' }}
                        </td>
                    @endif

                    <!-- <td class='{{ $spacing }} right'>{{ $order_products->tax_amount }}</td> -->
                    <td class='{{ $spacing }} right font-smooth' 
                        @if(isset($order_products->is_gifted) && $order_products->is_gifted == 1) style="text-decoration: line-through;"  @endif>
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
                                {{ __('Discount') }} ({{ $order_products->discount_percentage }}%) :
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
                            <td class='{{ $spacing }} small font-smooth' colspan='4'>{{ __('Discount') }} :
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
            <td class='w-50 font-smooth'>{{ __('Sub Total Exclude Tax') }}</td>
            <td class='right font-smooth'>{{ number_format($data->sale_amount_subtotal_excluding_tax, 2) }}</td>
        </tr>
        @php
            $spacing = '';
            if ($data->order_level_discount_percentage > 0 || $data->product_level_total_discount > 0) {
                $spacing = 'pb-0';
            }
        @endphp
        <tr>
            <td class='{{ $spacing }} w-50 font-smooth'>
                {{ __('Discount') }}<br>{{ isset($data_ar->discount) ? $data_ar->discount : '' }}
                @if ($data->additional_discount_percentage > 0 && $sub_total > 0)
                    ({{ number_format($data->additional_discount_percentage, 0) }}%)
                @endif
            </td>
            <td class='{{ $spacing }} right font-smooth'>
                @if ($sub_total > 0)
                    {{ number_format($data->total_discount_amount, 2) }}
                @else 0.00 @endif
            </td>
        </tr>
        @if ($data->order_level_discount_percentage > 0)
            <tr>
                <td class='{{ $spacing }} small font-smooth' colspan='2'>
                    [{{ __('Overall Discount') }}<br>{{ isset($data_ar->overall_discount) ? $data_ar->overall_discount : '' }}
                    {{ $data->order_level_discount_percentage > 0 ? '(' . $data->order_level_discount_percentage . '%)' : '' }}:
                    {{ number_format($data->order_level_discount_amount, 2) }}]

                </td>
            </tr>
        @endif
        @if ($data->product_level_total_discount > 0)
            <tr>
                <td></td>
            </tr>
        @endif
        @if ($data->additional_discount_percentage > 0)
        @endif

        <tr>
            <td class='w-50 font-smooth'>{{ __('Sub Total After Disc.') }}</span>
                <br>
               
                {{ isset($data_ar->subtotal_excluding_tax) ? $data_ar->subtotal_excluding_tax : '' }}   
               
            </td>
            @php
                // $data->total_after_discount = $data->total_after_discount + $data->tax_amount;
                $total_after_discount = $sub_total - $data->total_discount_amount;
            @endphp
            <td class='right'>
               
                @if ($sub_total > 0 )
                
                    {{-- {{ number_format($total_after_discount, 2) }} --}}
                    {{number_format($data->total_after_discount,2)}}
                    {{-- {{ number_format($order_products->total_after_discount, 2) }}ss --}}
                @else
                    0.00
                @endif
            </td>
        </tr>

        @foreach ($data->order_level_tax_components as $tax_component)
            @if ((int) $tax_component->tax_percentage != 0)
                @php $tax_name=""; @endphp
                @if($tax_component->tax_type=='Vat Tax' || $tax_component->tax_type=='VAT TAX' || $tax_component->tax_type=='Vat' || $tax_component->tax_type=='VAT'){
                    @php $tax_name = __("VAT"); @endphp
                @elseif($tax_component->tax_type=='Tobacco Tax' || $tax_component->tax_type=='TOBACCO TAX' || $tax_component->tax_type=='TOBACCO' || $tax_component->tax_type=='Tobacco')
                    @php $tax_name = __("Tobacco Tax"); @endphp
                @else
                    @php $tax_name=""; @endphp
                @endif
                <tr>
                    <td class='{{ $spacing }} w-50 font-smooth'>{{ $tax_name }}
                        <span style="text-align:left!important;" dir="ltr">({{ $tax_component->tax_percentage . '%' }})</span>
                    </td>
                    <td class='{{ $spacing }} right font-smooth'> {{ number_format($tax_component->tax_amount, 2) }}</td>
                </tr>
            @endif
        @endforeach

        @php
            $spacing = '';
            if ($data->order_level_tax_percentage > 0 || $data->order_level_tax_amount > 0) {
                $spacing = 'pb-0';
            }
        @endphp

        @if ($data->product_level_total_tax > 0)
            <tr>
                <td> </td>
            </tr>
        @endif
        @php
            $total_order_amount = number_format($data->total_order_amount, 2, '.', '');
        @endphp

        <tr>
            <td class='bold w-50 font-smooth'>{{ __('Bill Total') }}</td>
            <td class='bold right font-smooth'>{{ $data->store->currency_code }} {{ $total_order_amount }}</td>
        </tr>
        {{-- <tr> --}}
        {{-- <td colspan="2"> --}}
        {{-- <small>All prices are in {{ $data->store->currency_name }} ({{ $data->store->currency_code }})</small> --}}
        {{-- </td> --}}
        {{-- </tr> --}}
    </table>
    @if (isset($data->status->value) && $data->status->value == 7 && isset($data->payment_details->total_paid_amount))
        <table class='border-bottom-dashed mb-1rem w-100'>
            <tr>
                <td colspan="2" class='font-smooth' align="center">{{ __('Last Paid Amount') }}: {{ $data->payment_details->last_paid_amount}}</td>
            </tr>
            <tr>
                <td class='font-smooth'>{{ __('Total Paid Amount') }}: {{ $data->payment_details->total_paid_amount}}</td>
                <td class='font-smooth' align="right">{{ __('Total Remaining Amount') }}: {{ $data->payment_details->total_balance_amount}}</td>
            </tr>
        </table>
    @endif

    <div class='center' style="margin-top: 20px;">
        <?php
            /*
                    $invoice_link = route('order_receipt', $data->slack);
                    $qrcode=QrCode::encoding('UTF-8')->size(70)->generate($invoice_link);
                $qrcode=str_replace('
            <?xml version="1.0" encoding="UTF-8"@endphp',"",$qrcode); //replace to empty */

        if ($data->store->vat_number != '') {
            $qrcode = Salla\ZATCA\GenerateQrCode::fromArray([
                new Salla\ZATCA\Tags\Seller(($data->store->tax_registration_name != '') ? $data->store->tax_registration_name : $data->store->name), 
                new Salla\ZATCA\Tags\TaxNumber($data->store->vat_number), 
                new Salla\ZATCA\Tags\InvoiceDate($data->created_at_iso), 
                new Salla\ZATCA\Tags\InvoiceTotalAmount($data->total_order_amount), 
                new Salla\ZATCA\Tags\InvoiceTaxAmount($data->total_tax_amount)])
            ->toBase64();
            $qrcode = QrCode::encoding('UTF-8')
                ->size(100)
                ->generate($qrcode);
            $qrcode = str_replace( '<?xml version="1.0" encoding="UTF-8"?>','',$qrcode);
        } else {
            $qrcode = '';
        }

        ?>
        <span>{!! $qrcode !!}</span>
        <div class='display-block font-smooth' style="margin-top: 10px;">
            {{ $data->store->pos_invoice_policy_information }} </div>
    </div>
    <div></div>
    <div class='center'>
        <div class='display-block text-uppercase font-smooth'><strong>{{ __('POWERED BY WOSUL') }}</strong></div>
    </div>

</body>

</html>
