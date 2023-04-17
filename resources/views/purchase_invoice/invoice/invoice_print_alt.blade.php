@php 
    $logo_path = json_decode($logo_path);
    $invoice = json_decode($invoice);
    $supplier = json_decode($supplier);
    $invoice_products = json_decode($invoice_products);
    $store_detail = json_decode($store_detail);
@endphp
<!DOCTYPE html>
<html>
    <head>
        <title>Invoice #2</title>
    </head>

    <style>
        .invoice-card{
            color: #505d69 !important;
        }
        td{
            color: #505d69 !important;
        }
        .background-color{
            background: {{ $invoice->invoice_color_code }};
        }
    </style>

    <body>
        <div id="invoice-wrapper">
            <div class="invoice-card">
                <div class="col-print-12 text-center" align="center">
                    <!-- [LOGO] -->
                    <img src="{{ $logo_path }}" width="100">
                </div>
                <div class="col-print-12 border-bottom margin-top-30 margin-bottom-10 padding-bottom-10">
                    <div class="col-6">
                        <h5 style="float:left" class="font-16 no-margin no-padding">{{ __("Purchase Order To") }} : {{ $invoice->bill_to }} {{ ($invoice->bill_to_code != '') ? '('.$invoice->bill_to_code.")" : '' }} </h5>
                    </div>
                </div>
                <div class="col-print-12">
                    <div class="col-print-4">
                        <!-- <p>[LOGO]</p> -->
                        {{-- <img src="{{ $logo_path }}" width="50" style="padding-left: 10px;"> --}}
                        <p class="padding-left-10">{{ __("Company") }}: {{ $supplier->name }}</p>
                        <p class="padding-left-10">{{ __("Address") }}: {{ ($supplier->address != '') ? $supplier->address : '-' }}</p>
                        <p class="padding-left-10"> {{ __("Telephone") }}: {{ $supplier->phone }}</p>
                    </div>
                    <div class="col-print-4">
                        <p class="padding-left-10">{{ $store_detail->name }}</p>
                        <p class="padding-left-10">{{ $store_detail->address }}</p>
                        <p class="padding-left-10">{{ $store_detail->primary_contact }}</p>
                    </div>
                    <div class="col-print-2" align="right">
                        {{-- <p></p> --}}
                        <p>{{ __("Invoice Number") }} : </p>
                        <p>{{ __("Invoice Date") }} : </p>
                        <p>{{ __("Invoice Due Date") }} : </p>
                    </div>
                    <div class="col-print-2" align="right">
                        <p></p>
                        <p align="right">#{{ $invoice->invoice_number  }}</p>
                        <p align="right">{{ date('d/m/Y', strtotime($invoice->invoice_date)) }}</p>
                        <p align="right">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) }}</p>
                    </div>
                </div>
                <div class="col-print-12 margin-top-30">
                    
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td width="40%" class="col-height-45 color-white bold-text font-15 padding-5 background-color">{{ __("Products") }}</td>
                                <td width="8%" class="col-height-45 color-white bold-text font-15 padding-5 background-color">{{ __("Qty") }}</td>
                                <td width="30%" class="col-height-45 color-white bold-text font-15 padding-5 background-color">{{ __("Unit Price") }}</td>
                                <td class="col-height-45 color-white bold-text font-15 padding-5 background-color">{{ __("Amount") }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($invoice_products))
                            @foreach($invoice_products as $rs)
                            <tr>
                                <td class="font-15 padding-5">{{ $rs->name }}</td>
                                <td class="font-15 padding-5">{{ $rs->quantity }}</td>
                                <td class="font-15 padding-5">{{ $rs->amount_excluding_tax }}</td>
                                <td class="font-15 padding-5">{{ $rs->subtotal_amount_excluding_tax }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="col-print-6">
                        <br>
                        <p><strong>{{ __("Payment Instructions") }}</strong></p>
                        <p>{{ $store_detail->bank_name }}</p>
                        <p>{{ __("IBAN Number") }} : {{ $store_detail->iban_number }}</p>
                        <p>{{ __("Name") }}: {{ $store_detail->account_holder_name }}</p>
                        <br>
                        <p>50% {{ __("Payment Upfront Amount") }}</p>
                        <p>50% {{ __("Payment Before Shipping") }}</p>
                        <br>
                        <p><strong>{{ __("Comments") }}</strong></p>
                        <p>{{ $store_detail->invoice_policy_information }}</p>
                    </div>
                    <div style="float:right" class="col-print-6">

                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Sub Total") }}:</span>
                        </div>
                        <div style="float: right; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">{{ $invoice->subtotal_excluding_tax }}</span>
                        </div>


                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Discount") }}:</span>
                        </div>
                        <div style="float: left; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">- {{ $invoice->total_discount_amount }}</span>
                        </div>


                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Sub Total(Excluding Tax)") }}:</span>
                        </div>
                        <div style="float: left; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">{{ $invoice->total_after_discount }}</span>
                        </div>
                        
                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Tax") }}:</span>
                        </div>
                        <div style="float: left; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">{{ $invoice->total_tax_amount }}</span>
                        </div>

                        @if($invoice->shipping_charge > 0)
                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Shipping Charge") }}:</span>
                        </div>
                        <div style="float: left; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">{{ $invoice->shipping_charge }}</span>
                        </div>
                        @endif

                        @if($invoice->packing_charge > 0)
                        <div style="float: left; width: 57%;  margin-bottom: 15px;">
                            <span class="font-size-14" style="margin-left: 5px;">{{ __("Packing Charge") }}:</span>
                        </div>
                        <div style="float: left; width: 43%;  margin-bottom: 15px;">
                            <span class="font-size-14">{{ $invoice->packing_charge }}</span>
                        </div>
                        @endif

                        @if(isset($invoice->invoice_charges))
                        @foreach($invoice->invoice_charges as $rs)
                            <div style="float: left; width: 57%;  margin-bottom: 15px;">
                                <span class="font-size-14" style="margin-left: 5px;">{{ $rs->name }}</span>
                            </div>
                            <div style="float: left; width: 43%;  margin-bottom: 15px;">
                                <span class="font-size-14">{{ $rs->amount }}</span>
                            </div>
                        @endforeach
                        @endif

                        <div style="float:left;color:#fff;margin-top: 10px;border-radius:4px;padding: 12px;" class="background-color">
                            <div style="float: left; width: 57%;">
                                <span class="font-size-14">{{ __("Grand Total") }}</span>
                            </div>
                            <div style="float: left; width: 43%;">
                                <span class="font-size-14">{{ $invoice->total_order_amount }}</span>{{ __("Riyals") }} 
                            </div>
                        </div>

                    </div>
                  
                </div>
                <div class="col-print-12 margin-top-30" align="center">
                    <p class="bold-text font-16" align="center">{{ __("We thank you for doing business with us") }}</p>
                </div>
                <div class="col-print-12" align="center" style="font-size: 20px;">
                    {{ $store_detail->iban_number }} 
                </div>
            </div>
        </div>
    </body>
    
</html>
