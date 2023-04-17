@php

    $data = json_decode($data);
    $colspan = 6+((isset($data->tax_option_data) && count($data->tax_option_data->component_array) > 0)?count($data->tax_option_data->component_array):1);
@endphp
<!DOCTYPE html>
<html>
    <head>
        <title>Quotation #{{ $data->quotation_number }}</title>
    </head>
    <body>
        
        <table>
            <tr>
                <td><h2>{{ __("QUOTATION") }}</h2></td>
            </tr>
        </table>

        <div class='mb-1rem'>
            <table class='w-100'>
                <tr>
                    <td class='w-50'>
                        <div class='display-block'>{{ __("Quotation Number") }}: {{ $data->quotation_number }}</div>
                        <div class='display-block'>{{ __("Reference Number") }}: {{ $data->quotation_reference }}</div>
                        <div class='display-block'>{{ __("Quotation Date") }}: {{ $data->quotation_date }}</div> 
                        <div class='display-block'>{{ __("Quotation Due Date") }}: {{ $data->quotation_due_date }}</div>
                        
                    </td>
                    <td class='right'>
                        <img style="max-width:150%;max-height:70px;" src="{{ $relative_logo_path }}" alt="no media found"/>
                    </td>
                </tr>
            </table>
        </div>

        <table class='w-100 mb-1rem'>
            <tr>
                <td class='v-top w-50 pr-20px'>
                    <div class='bold display-block'>{{ __("Quotation From") }} </div>
                        <div class='display-block'>{{ $data->store->name }}</div>
                        <div>
                            {{ $data->store->building_number }}, {{ $data->store->district }}, {{ $data->store->street_name }}, {{ $data->store->city }}, {{ $data->store->pincode }}
                        </div>
                        
                        <div> <strong>Email: </strong> {{ $data->store->primary_email }}</div>
                        <div> <strong>Contact: </strong> {{ $data->store->primary_contact }}</div>
                        @if($data->store->vat_number != '')
                        <div> <strong>Vat Number:</strong> {{ $data->store->vat_number }}</div>
                        @endif
                        @if($data->store->other_seller_id != '')
                        <div> <strong>Other Seller Id:</strong> {{ $data->store->other_seller_id }}</div>
                        @endif
                    </div>
                </td>
                @if(!is_null($supplier))
                <td class='v-top w-50 pr-20px'>
                    <div class='bold display-block'>{{ __("Quotation To") }} </div>
                    <div class='display-block'>@if($data->bill_to == 'CUSTOMER') Customer  @else Company  @endif : {{ $supplier->name ?? '' }} </div>
                    <div class='display-block'>Email: {{ isset($supplier->email) ? $supplier->email : '-' }} </div>
                    <div class='display-block'>Contact Number: {{ isset($supplier->phone) ? $supplier->phone : '-' }}</div>
                    @if($data->bill_to != 'CUSTOMER' && $supplier->tax_number != '')
                    <div class='display-block'>VAT Number: {{ isset($supplier->tax_number) ? $supplier->tax_number : '-' }}</div>
                    @endif
                    @if($supplier->building_number != null ||
                                        $supplier->street_name != null ||
                                        $supplier->district != null ||
                                        $supplier->city != null)
                    <div class='display-block'>
                        {{ ($supplier->building_number != null) ? $supplier->building_number." ," : '' }}     
                                                {{ ($supplier->street_name != null) ? $supplier->street_name." ," : ''  }}
                                                {{ ($supplier->district != null) ? $supplier->district." ," : '' }}
                                                {{ ($supplier->city != null) ? $supplier->city." ," : null }} 
                                                {{ $supplier->pincode  }}    
                    </div>
                    @endif
                    @if(!empty($additional_infos))
                        @foreach($additional_infos as $info)
                            <div class='display-block'>{{ $info->title  }}: {{ $info->description }} </div>
                        @endforeach
                    @endif
                    <div class='pr-100px'>
                        
                    </div>
                </td>
                @endif
            </tr>
        </table>


        <div class="mb-1rem">
            <table class="w-100 product-table mb-1rem">
                <thead>
                    <tr>
                    <th class="left">#</th>
                    <th class="left">{{ __("Product Description") }}</th>
                    <th class="right">{{ __("Qty") }}</th>
                    <th class="center">{{ __("Measurement") }}</th>
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
                    
                    @foreach ($data->products as $item_key => $quotation_products)
                    <tr v-for="(po_product, key, index) in products" v-bind:value="$quotation_products->product_slack" v-bind:key="index">
                        <td>{{ $item_key+1 }}</td>
                        <td>{{ $quotation_products->name }} @if($quotation_products->product_code != '') {{ '('.$quotation_products->product_code.')' }} @endif <br/> @if($quotation_products->description!=''){{ $quotation_products->description }} @endif</td>
                        <td class="right">{{ $quotation_products->quantity }}</td>
                        <td class="center">@if(!empty($quotation_products->measurements)) {{ $quotation_products->measurements[0]->label }} @else {{ '--' }}@endif</td>
                        <td class="right">{{ $quotation_products->amount_excluding_tax }}</td>
                        <td class="right">{{ $quotation_products->discount_amount }}<br>({{ $quotation_products->discount_percentage }}%)</td>
                        
                        @if (isset($data->tax_option_data) && count($data->tax_option_data->component_array) > 0)
                            @foreach ($data->tax_option_data->component_array as $component_array_key => $component_array_item)
                                <td class="right">{{ $quotation_products->tax_component_array->$component_array_item }}</td>
                            @endforeach
                        @else
                            <td class="right">{{ $quotation_products->tax_amount }}<br>({{ $quotation_products->tax_percentage }}%)</td>
                        @endif

                        @php $total_with_tax = $quotation_products->subtotal_amount_excluding_tax+$quotation_products->tax_amount; @endphp
                        <td class="right">{{ number_format($total_with_tax,2) }}</td>
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
                        <td colspan="{{$colspan}}" class="right"> {{ __("Total After Discount") }}</td>
                        <td class="right">{{ $data->total_after_discount }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$colspan}}" class="right">{{ __("Total Tax") }}</td>
                        <td class="right">{{ $data->total_tax_amount }}</td>
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

        @if($data->notes != '')
        <div class="mb-1rem">
            <div class='bold display-block'>{{ __("Notes") }}</div>
            <pre>{{ $data->notes }}</pre>
        </div>
        @endif

        <div class='center'>
            <div class='display-block'>{{ $data->terms }}</div>
        </div>

    </body>
</html>