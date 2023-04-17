@extends('layouts.layout')

@section("content")


    <invoicedetailcomponent :invoice_statuses="{{ json_encode($invoice_statuses) }}" :invoice_data="{{ json_encode($invoice_data) }}" :transaction_type_data="{{ json_encode($transaction_type) }}" :accounts="{{ json_encode($accounts) }}" :payment_methods="{{ json_encode($payment_methods) }}" :currency_codes="{{ json_encode($currency_codes) }}" :delete_invoice_access="{{ json_encode($delete_invoice_access) }}" :make_payment_access="{{ json_encode($make_payment_access) }}" :pdf_path="{{ json_encode($pdf_path) }}"></invoicedetailcomponent>
    
 
    <div class="row">
        <div class="col-lg-7 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-center mb-5">
                            {{-- <img src="assets/images/logo.jpg" class="img-fluid" alt="Logo" width="130" /> --}}
                            <img src="{{ $relative_logo_path }}" class="img-fluid" alt="Logo" width="130">
                        </div>
                        <div class="col-lg-12">
                            <h5 class="border-bottom pb-2">Purchase Order To : {{ $invoice->bill_to }} {{ ($invoice->bill_to_code != '') ? '('.$invoice->bill_to_code.")" : '' }}
                                
                            </h5>
                        </div>
                        <div class="col-lg-4">
                            <div class="invoice-to-logo">
                                <br>
                                <p><b>Company: {{ $supplier->name }}</b></p>
                                <p>Address: {{ ($supplier->address != '') ? $supplier->address : '-' }}</p>
                                <p>Telephone : {{ $supplier->phone }}</p>
                                <p>VAT Reg. #: {{ $store_detail->vat_number }} </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="invoice-to-logo">
                                <br>
                                <p>{{ $store_detail->name }}</p>
                                <p>{{ $store_detail->address }}</p>
                                <p>{{ $store_detail->primary_contact }}</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <table class="table table-borderless table-responsive"style="border-collapse: collapse; border-spacing: 0; width: 100%;border: none !important;box-shadow: none !important;
            -webkit-box-shadow : none !important;">
                                <tr>
                                    <td style="color: #85959d !important;">Invoice number:</td>
                                    <td style="color: #85959d !important;">#{{ $invoice->invoice_number  }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #85959d !important;">Invoice Date:</td>
                                    <td style="color: #85959d !important;">{{ date('d/m/Y', strtotime($invoice->invoice_date)) }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #85959d !important;">Invoice Due Date:</td>
                                    <td style="color: #85959d !important;">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-12 text-right">
                            <invoicecolorcodecomponent :invoice_color_code="{{ json_encode($invoice->invoice_color_code)  }}" :invoice_id="{{ json_encode($invoice->id)  }}" ></invoicecolorcodecomponent>
                        </div>
                        <div class="col-lg-12">

                            <table class="table table-custom dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead id="pr2" style="background:{{ $invoice->invoice_color_code  }};color:#fff;">
                                    <tr>
                                        <th style="width:50%">Products</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @if(isset($invoice_products))
                                     @foreach($invoice_products as $rs)
                                    <tr>
                                        <td style="color: #85959d !important;">{{ $rs->name }}</td>
                                        <td style="color: #85959d !important;">{{ $rs->quantity }}</td>
                                        <td style="color: #85959d !important;">{{ $rs->amount_excluding_tax }}</td>
                                        <td style="color: #85959d !important;">{{ $rs->subtotal_amount_excluding_tax }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>                           
                            </table>                                    
                        </div>
                        <div class="col-lg-6">
                            <br>
                            <p><strong>Payment Instructions</strong></p>
                            <p>{{ $store_detail->bank_name }}</p>
                            <p>IBAN : {{ $store_detail->iban_number }}</p>
                            <p>Name: {{ $store_detail->account_holder_name }}</p>
                            <br>
                            <p>50% Payment upfront</p>
                            <p>50% Payment before shipping</p>
                            <br>
                            <p><strong>Comments</strong></p>
                            <p>{{ $invoice->terms }}</p>
                        </div>
                        <div class="col-lg-6">
                            
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14" style="margin-left: 5px;">Sub Total</span>
                            </div>
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14">{{ $invoice->subtotal_excluding_tax }}</span>
                            </div>
                            
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14" style="margin-left: 5px;">Discount:</span>
                            </div>
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14">- {{ $invoice->total_discount_amount }}</span>
                            </div>

                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14" style="margin-left: 5px;">Subtotal(Excluding Tax):</span>
                            </div>
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14">{{ $invoice->total_after_discount }}</span>
                            </div>

                            

                            @php $total_including_charges = $invoice->total_after_discount @endphp
                            
                            @forelse($invoice->invoiceCharges as $rs)
                            
                                @php 
                                    $total_including_charges += $rs->amount
                                @endphp
                                
                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                    <span class="font-size-14" style="margin-left: 5px;">{{ $rs->name }}</span>
                                </div>
                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                    <span class="font-size-14">{{ $rs->amount }}</span>
                                </div>

                                @empty

                                {{-- just an empty space --}}

                            @endforelse

                          {{--   @if(isset($invoice->invoiceCharges))
                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                    <span class="font-size-14" style="margin-left: 5px;">Subtotal(Including Charges):</span>
                                </div>
                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                    <span class="font-size-14">{{ $total_including_charges }}</span>
                                </div>
                            @endif --}}

                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14" style="margin-left: 5px;">Tax:</span>
                            </div>
                            <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                <span class="font-size-14">{{ $invoice->total_tax_amount }}</span>
                            </div>

                            <div id="pr1" class="blue-box" style="background:{{ $invoice->invoice_color_code  }} !important;color:#fff;">
                                <div style="float: left; width: 50%;">
                                    <span class="font-size-14">Grand Total</span>
                                </div>
                                <div style="float: left; width: 50%;">
                                    <span class="font-size-14">{{ $invoice->total_order_amount }}</span> {{ $invoice->currency_code }}
                                </div>
                            </div>
                        </div>
                        <script>
                            function update(picker, selector) {
                                document.querySelector(selector).style.background = picker.toBackground()
                            }

                            // triggers 'onInput' and 'onChange' on all color pickers when they are ready
                            jscolor.trigger('input change');
                            </script>
                        <div class="col-lg-12 mt-5 text-center">
                            <h5>We thank you for doing business with us</h5>
                        </div>
                    </div>                                        
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
