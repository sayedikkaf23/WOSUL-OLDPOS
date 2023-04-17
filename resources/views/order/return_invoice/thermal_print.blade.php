@php $data = json_decode($data); @endphp
<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Order') }} #{{ $data->order_number }}</title>
</head>

<body>
    <div class='center border-bottom-dashed pb-1rem'>
        <div class='center'>
            
                <img class="nav-profile" src="{{ $logo_path }}"
                    style="width: 120px;height: 35px;border-radius:0 !important;padding-bottom:2px;">
        </div>
        <div class='bold display-block'>{{ $data->store->name }}</div>
        <div>{{ $data->store->primary_email }}</div>
        <div>{{ __('Tel') }}:{{ $data->store->primary_contact }}</div>
        <div>{{ __('VAT Reg') }}. #: {{ $data->store->vat_number }}</div>
        <div>{{ __('Address') }}: {{ $data->store->address }}</div>
        <br>
        <div>{{ __('Welcome') }}</div>
        <div>{{ __('Return Receipt') }}</div>
        <div><small>{{ __('Printed at') }} : {{ date('d-m-y h:i:s') }}</small></div>
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
                <td class='bold w-50'>{{ __('Order') }} #{{ $data->return_order_number }}</td>
                <td class='right'>{{ __('Date') }}: {{ $data->created_at_label }}</td>
            </tr>
            <tr>
                <td class='w-50'>{{ __('Billed By') }}: {{ $data->created_by->user_code }}</td>
                @php
                    $quantity = 0;
                    if (count($data->products) > 0) {
                        foreach ($data->products as $product) {
                            $quantity += $product->quantity;
                        }
                    }
                    
                @endphp
                <td class='right'>{{ count($data->products) > 0 ? count($data->products) : 0 }}
                    {{ __('Items') }} ({{ $quantity }} {{ __('Qty') }})</td>
            </tr>
            @if ($data->restaurant_mode == 1)
                {{-- <tr>
                <td class='w-50'>Type: {{ $data->order_type }}</td>
                <td class='right'>Table: {{ ($data->table != '')?$data->table:'-' }}</td>
                </tr> --}}
            @endif
            <?php /*
                    <tr>
                        <td colspan='2'>Payment Method: {{ $data->payment_method }}</td>
                    </tr>
                    */
            ?>
        </table>
    </div>

    <table class='border-bottom-dashed mb-1rem w-100'>
        <tr>
            <td class='bold w-45'>{{ __('Description') }}</td>
            <td class='bold right'>{{ __('Qty') }}</td>
            <td class='bold right'>{{ __('Price') }}</td>
            {{-- <td class='bold right'>Tax</td> --}}
            <td class='bold right'>{{ __('Amount') }}</td>
        </tr>
        @php
            $total_modifier_amount = 0;
            $sub_total = 0;
            $tax_amount = 0;
        @endphp
        
        @if ( isset($data->combos) && count($data->combos) > 0)
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
                    @if ($order_products->product_code != '' && $order_products->combo_id == '' )
                        {{ $order_products->product_code . '-' }}
                    @endif
                    - {{ $order_products->name }}
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
            <td class='w-50'>{{ __('Sub Total Exclude Tax') }}</td>
            <td class='right'>{{ $data->sale_amount_subtotal_excluding_tax }}</td>
        </tr>

        @php
            $spacing = '';
            if ($data->order_level_discount_percentage > 0 || $data->product_level_total_discount > 0) {
                $spacing = 'pb-0';
            }
        @endphp
        <tr>
            <td class='{{ $spacing }} w-50'>{{ __('Discount') }} @if ($data->additional_discount_percentage > 0 && $data->sale_amount_subtotal_excluding_tax > 0)
                    ({{ number_format($data->additional_discount_percentage, 0) }}%)
                @endif
            </td>
            <td class='{{ $spacing }} right'>{{ $data->total_discount_amount }}</td>
        </tr>
        @if ($data->order_level_discount_percentage > 0)
            <tr>
                <td class='{{ $spacing }} small' colspan='2'>
                    [{{ __('Overall Discount') }}
                    {{ $data->order_level_discount_percentage > 0 ? '(' . $data->order_level_discount_percentage . '%)' : '' }}:
                    {{ $data->order_level_discount_amount }}]
                </td>
            </tr>
        @endif
        @if ($data->product_level_total_discount > 0)
            <?php /*
                    <tr>
                        <td class='small' colspan='2'>
                            [Product Discount: {{ $data->product_level_total_discount }}]
                        </td>
                    </tr>
                    */
            ?>
            <tr>
                <td></td>
            </tr>
        @endif
        @if ($data->additional_discount_percentage > 0)
            <?php /*  <tr>
                        <td class='w-50'>Additional Discount</td>
                        <td class='right'>({{ $data->additional_discount_percentage }}%) {{ $data->additional_discount_amount }}</td>
                    </tr> */
            ?>
        @endif
        <tr>
            <td class='w-50'>{{ __('Sub Total After Disc.') }}</td>
            <td class='right'>{{ $data->total_after_discount }}</td>
        </tr>
        @foreach ($data->order_level_tax_components as $tax_component)
            @if (!empty($tax_component->tax_percentage))
                @php $tax_name=""; @endphp
                @if($tax_component->tax_type=='Vat Tax' || $tax_component->tax_type=='VAT TAX' || $tax_component->tax_type=='Vat' || $tax_component->tax_type=='VAT'){
                    @php $tax_name = __("VAT"); @endphp
                @elseif($tax_component->tax_type=='Tobacco Tax' || $tax_component->tax_type=='TOBACCO TAX' || $tax_component->tax_type=='TOBACCO' || $tax_component->tax_type=='Tobacco')
                    @php $tax_name = __("Tobacco Tax"); @endphp
                @else
                    @php $tax_name=""; @endphp
                @endif
                <tr>
                    <td class='w-50'>{{ $tax_name }}
                        <span style="text-align:left!important;" dir="ltr">({{ $tax_component->tax_percentage . '%' }})</span>
                    </td>
                    <td class='right'>{{ number_format($tax_component->tax_amount, 2) }}</td>
                </tr>
            @endif
        @endforeach

        <?php /*
               <tr>
                    <td class='{{ $spacing }} w-50'>Sub Total (Including Tax)</td>
                     @php $sub_total_including_tax = $data->sale_amount_subtotal_excluding_tax + $data->total_tax_amount @endphp
                    <td class='{{ $spacing }} right'>{{ number_format($sub_total_including_tax,2) }}</td> 
                 </tr> 
            */
        ?>

        @php
            $spacing = '';
            if ($data->order_level_tax_percentage > 0 || $data->order_level_tax_amount > 0) {
                $spacing = 'pb-0';
            }
        @endphp

        @if ($data->order_level_tax_percentage > 0)
            <?php /*
                    <tr>
                        <td class='{{ $spacing }} small' colspan='2'>
                            @if(count($data->order_level_tax_components)>0)
                            [Overall Tax:
                            @foreach ($data->order_level_tax_components as $tax_component)
                            {{ strtoupper($tax_component->tax_type) }}({{ $tax_component->tax_percentage }}%) : {{ round($tax_component->tax_amount, 2) }}|
                            @endforeach
                            Tax Amount: {{ round($data->order_level_tax_amount, 2) }}]
                            @endif
                        </td>
                    </tr>
                */
            ?>
        @endif

        @if ($data->product_level_total_tax > 0)
            <?php /*
                    <tr>
                        <td class='small' colspan='2'>
                            [Product Tax: {{ $data->product_level_total_tax }}]
                        </td>
                    </tr>
                */
            ?>
            <tr>
                <td> </td>
            </tr>
        @endif
        <!-- @php $sub_total_including_tax = $data->total_after_discount + $data->total_tax_amount @endphp -->
        @php $sub_total_including_tax = $data->total_after_discount + $data->order_level_tax_amount @endphp

        <tr>
            <td class='bold w-50'>{{ __('Bill Total') }}</td>
            <td class='bold right'>{{ $data->store->currency_code }} {{ $sub_total_including_tax }}</td>
        </tr>
    </table>
    <div class='center'>
        <div style="margin-top: 20px;">
            <?php
              if ($data->store->vat_number != '') {
                $qrcode = Salla\ZATCA\GenerateQrCode::fromArray([new Salla\ZATCA\Tags\Seller(($data->store->tax_registration_name != '') ? $data->store->tax_registration_name : $data->store->name), new Salla\ZATCA\Tags\TaxNumber($data->store->vat_number), new Salla\ZATCA\Tags\InvoiceDate($data->created_at_iso), new Salla\ZATCA\Tags\InvoiceTotalAmount($data->total_order_amount), new Salla\ZATCA\Tags\InvoiceTaxAmount($data->total_tax_amount)])->toBase64();
                $qrcode = QrCode::encoding('UTF-8')
                    ->size(100)
                    ->generate($qrcode);
                $qrcode = str_replace( '<?xml version="1.0" encoding="UTF-8"?>','',$qrcode);
            } else {
                $qrcode = '';
            }
    
            ?>
            <span>{!! $qrcode !!}</span>
        </div>
        <br>
        {{ __('Terms & Conditions') }}
        <div class='display-block'>{{ $data->store->pos_invoice_policy_information }} </div>
    </div>
    
    <div class='center' style="margin-top:10px;">
        <div class='display-block text-uppercase '>{{ __('POWERED BY WOSUL') }}</div>
    </div>

</body>

</html>