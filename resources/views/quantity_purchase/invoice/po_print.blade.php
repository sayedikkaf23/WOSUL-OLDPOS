@php 
    $data = json_decode($data); 
    $lang = App::getLocale();
    $storelogo = Storage::disk('store')->url($data->store->store_logo);
    $colspan = 5+((isset($data->tax_option_data) && count($data->tax_option_data->component_array) > 0)?count($data->tax_option_data->component_array):1);
@endphp
<!DOCTYPE html>
<html>
    <head>
        <title>Quantity Purchase #{{ $data->po_number }}</title>
    </head>
    <body>
        
        <table>
            <tr>
                <td><h2>{{ __("QUANTITY PURCHASE") }}</h2></td>
            </tr>
        </table>

        <div class='mb-1rem'>
            <table class='w-100'>
                <tr>
                    <td class='w-50'>
                        <table style="border-collapse:collapse;" cellspacing="0">
                         @if($lang=='ar')
                          <tr><td style="text-align:right;">{{ __("PO Number") }}: {{ $data->po_number }}</td></tr>
                          <tr><td style="text-align:right;">{{ __("Reference Number") }}: {{ $data->po_reference }}</td></tr>
                          <tr><td style="text-align:right;">{{ __("Order Date") }}: {{ $data->order_date }}</td></tr>
                          <tr><td style="text-align:right;">{{ __("Order Due Date") }}: {{ $data->order_due_date }}</td></tr> 
                         @else
                         <tr><td style="text-align:left;">{{ __("PO Number") }}: {{ $data->po_number }}</td></tr>
                          <tr><td style="text-align:left;">{{ __("Reference Number") }}: {{ $data->po_reference }}</td></tr>
                          <tr><td style="text-align:left;">{{ __("Order Date") }}: {{ $data->order_date }}</td></tr>
                          <tr><td style="text-align:left;">{{ __("Order Due Date") }}: {{ $data->order_due_date }}</td></tr> 
                        @endif
                        </table>
                    </td>
                    <td class='right'>
                            <img src="{{ $logo_path }}" class='h-50px'  style="height:50px"/>
                    </td>
                </tr>
            </table>
        </div>

        <table class='w-100 mb-1rem'>
            <tr>
                <td class='v-top w-50 pr-20px'>
                    <table style="border-collapse:collapse;" cellspacing = "0">
                    <tr><td style="text-align:center;" class="bold">{{ __("SUPPLIER") }}</td></tr>
                      @if($lang=='ar')
                        <tr><td style="text-align:right;">{{ $data->supplier_address }}</td></tr>
                      @else
                        <tr><td style="text-align:left;">{{ $data->supplier_address }}</td></tr>
                      @endif
                      <tr>
                        @if($lang=='ar')
                          <td style="text-align:right;">
                           @if ($data->store->pincode != '')
                             {{ __("Pincode") }}: {{ $data->store->pincode }}
                           @endif
                           @if ($data->supplier->email != '')
                            {{ __("Email") }}: {{ $data->supplier->email }}
                           @endif
                           @if ($data->supplier->phone != '')
                            {{ __("Contact No") }}: {{ $data->supplier->phone }}
                           @endif
                          </td>
                        @else
                        <td style="text-align:left;">
                           @if ($data->store->pincode != '')
                             {{ __("Pincode") }}: {{ $data->store->pincode }}
                           @endif
                           @if ($data->supplier->email != '')
                            {{ __("Email") }}: {{ $data->supplier->email }}
                           @endif
                           @if ($data->supplier->phone != '')
                            {{ __("Contact No") }}: {{ $data->supplier->phone }}
                           @endif
                          </td>
                        @endif
                       </tr>
                    </table>
                    <!--<div class='bold display-block'>{{ __("SUPPLIER") }} </div>
                    <div class='display-block'>{{ $data->supplier_name }} ({{ $data->supplier_code }})</div>
                    <div class='pr-100px'>
                        {{ $data->supplier_address }}
                        @if ($data->store->pincode != '')
                        {{ __("Pincode") }}: {{ $data->store->pincode }}
                        @endif
                        @if ($data->supplier->email != '')
                            {{ __("Email") }}: {{ $data->supplier->email }}
                        @endif
                        @if ($data->supplier->phone != '')
                            {{ __("Contact No") }}: {{ $data->supplier->phone }}
                        @endif
                    </div>-->
                </td>
                <td class='v-top w-50 pr-20px'>
                    <table>
                    <tr ><td style="text-align:center;" class='bold'>{{ __("SHIP TO") }}</td></tr>
                    @if($lang=='ar')
                     <tr><td style="text-align:right;">{{ $data->store->name }}</td></tr>
                    @else
                    <tr><td style="text-align:left;">{{ $data->store->name }}</td></tr>
                    @endif
                    @if($lang=='ar')
                    <tr>
                        <td style="text-align:right;">
                            {{ $data->store->address }}
                            @if ($data->store->pincode != '')
                            {{ __("Pincode") }}: {{ $data->store->pincode }}
                            @endif
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td  style="text-align:left;">
                            {{ $data->store->address }}
                            @if ($data->store->pincode != '')
                            {{ __("Pincode") }}: {{ $data->store->pincode }}
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($lang=='ar')
                        @isset ($data->store->tax_number)
                            <tr><td style="text-align:right;">{{ __("GST") }}: {{ $data->store->tax_number }}</td></tr>
                        @endisset
                        @isset ($data->store->primary_email)
                            <tr><td style="text-align:right;">{{ __("Email 1") }}: {{ $data->store->primary_email }}</td></tr>
                        @endisset
                        @isset ($data->store->secondary_email)
                            <tr><td style="text-align:right;">{{ __("Email 2") }} : {{ $data->store->secondary_email }}</td></tr>
                        @endisset
                        @isset ($data->store->primary_contact)
                            <tr><td style="text-align:right;">{{ __("Contact No 1") }}: {{ $data->store->primary_contact }}</td></tr>
                        @endisset
                        @isset ($data->store->secondary_contact)
                            <tr><td style="text-align:right;">{{ __("Contact No 2") }}: {{ $data->store->secondary_contact }}</td></tr>
                        @endisset
                    @else
                    @isset ($data->store->tax_number)
                            <tr><td style="text-align:left;">{{ __("GST") }}: {{ $data->store->tax_number }}</td></tr>
                        @endisset
                        @isset ($data->store->primary_email)
                            <tr><td style="text-align:left;">{{ __("Email 1") }}: {{ $data->store->primary_email }}</td></tr>
                        @endisset
                        @isset ($data->store->secondary_email)
                            <tr><td style="text-align:left;">{{ __("Email 2") }} : {{ $data->store->secondary_email }}</td></tr>
                        @endisset
                        @isset ($data->store->primary_contact)
                            <tr><td style="text-align:left;">{{ __("Contact No 1") }}: {{ $data->store->primary_contact }}</td></tr>
                        @endisset
                        @isset ($data->store->secondary_contact)
                            <tr><td style="text-align:left;">{{ __("Contact No 2") }}: {{ $data->store->secondary_contact }}</td></tr>
                        @endisset
                    @endif
                    </table>
                </td>
            </tr>
        </table>


        <div class="mb-1rem">
            <table class="w-100 product-table mb-1rem">
                <thead>
                    <tr>
                    <th class="left">#</th>
                    <th class="left">{{ __("Product Description") }}</th>
                    <th class="right">{{ __("Qty") }}</th>
                    <th class="right">{{ __("Price (EXCL Tax)") }}</th>
                    <th class="right">{{ __("Discount") }}</th>
                    @if (isset($data->tax_option_data) && count($data->tax_option_data->component_array) > 0)
                        @foreach ($data->tax_option_data->component_array as $component_array_key => $component_array_item)
                            <th class="right">{{ $component_array_item }}</th>
                        @endforeach
                    @else
                        <th class="right">{{ __("Tax") }}</th>
                    @endif
                    <th class="right">{{ __("Total") }}</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($data->products as $item_key => $po_products)
                    <tr v-for="(po_product, key, index) in products" v-bind:value="$po_products->product_slack" v-bind:key="index">
                        <td>{{ $item_key+1 }}</td>
                        <td>{{ ($po_products->product_code != ''? $po_products->product_code." - ": '') }}{{ $po_products->name }}</td>
                        <td class="right">{{ $po_products->quantity }}</td>
                        <td class="right">{{ $po_products->amount_excluding_tax }}</td>
                        <td class="right">{{ $po_products->discount_amount }}<br>({{ $po_products->discount_percentage }}%)</td>
                        
                        @if (isset($data->tax_option_data) && count($data->tax_option_data->component_array) > 0)
                            @foreach ($data->tax_option_data->component_array as $component_array_key => $component_array_item)
                                <td class="right">-</td>
                            @endforeach
                        @else
                            <td class="right">{{ $po_products->tax_amount }}<br>({{ $po_products->tax_percentage }}%)</td>
                        @endif

                        <td class="right">{{ $po_products->subtotal_amount_excluding_tax }}</td>
                    </tr>
                    @endforeach

                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Sub Total (EXCL Tax)") }}</td>
                        <td class="right">{{ $data->subtotal_excluding_tax }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Total Discount") }}</td>
                        <td class="right">{{ $data->total_discount_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Total After Discount") }}</td>
                        <td class="right">{{ $data->total_after_discount }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Total Tax") }}</td>
                        <td class="right">{{ $data->total_tax_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Shipping Charge") }}</td>
                        <td class="right">{{ $data->shipping_charge }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Packaging Charge") }}</td>
                        <td class="right">{{ $data->packing_charge }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right bold">{{ __("Total (INCL Tax)") }}</td>
                        <td class="right bold">{{ $data->total_order_amount }}</td>
                    </tr>
                </tbody>
            </table>
            @if($data->currency_code != '')
            <div>
                <small>{{ __("All prices are in") }} {{ $data->currency_name }} ({{ $data->currency_code }})</small>
            </div>
            @endif
        </div>

        @if($data->terms != '')
        <div class="mb-1rem">
            <div class='bold display-block'>{{ __("Terms") }}</div>
            <pre>{{ $data->terms }}</pre>
        </div>
        @endif

        <div class='center'>
            <div class='display-block'>{{ __("Thank You!") }}</div>
        </div>

    </body>
</html>
