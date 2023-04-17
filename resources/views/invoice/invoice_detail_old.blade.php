@extends('layouts.layout')

@section("content")


    <invoicedetailcomponent :invoice_statuses="{{ json_encode($invoice_statuses) }}" :invoice_data="{{ json_encode($invoice_data) }}" :transaction_type_data="{{ json_encode($transaction_type) }}" :accounts="{{ json_encode($accounts) }}" :payment_methods="{{ json_encode($payment_methods) }}" :currency_codes="{{ json_encode($currency_codes) }}" :delete_invoice_access="{{ json_encode($delete_invoice_access) }}" :make_payment_access="{{ json_encode($make_payment_access) }}" :pdf_path="{{ json_encode($pdf_path) }}"></invoicedetailcomponent>
    
    <div class="row">
        <div class="col-lg-8 mx-auto custom-center">
            <div id="invoice-wrapper">
                <div class="card-body invoice-view-block">
                    <div>
                    <div class="invoice-inner">
                      
            <div class="header text-center">
              <img src="{{ $relative_logo_path }}" style="width: 120px;height: auto!important;border-radius:0 !important;padding-bottom:2px;" alt="Logo">
      
              </div>

        <div class="header-02">
        @if($invoice->bill_to=="CUSTOMER")
        <h4>فاتورة ضريبية مبسطة</h4>
        <h4>Simplified Tax Invoice</h4>
        @else
          <h4>فاتورة ضريبية</h4>
          <h4>Tax Invoice</h4>
        @endif
        </div>

        <div class="row invoice-info-qrcode">

          <div class="col-lg-9 p-0 in-info">

            <div class="d-flex">

              <div class="in-info-en">

                <table>
                  
                  <tr>

                    <td>Invoice No:</td>

                    <td class="text-center">{{ $invoice->invoice_number ?? '' }}</td>

                    <td class="direction-rtl">رقم الفاتورة :</td>

                  </tr>

                </table>

              </div>

              
            </div>

            <div>

              <div class="in-info-en">

                <table>
                  
                  <tr>

                    <td>Invoice Issue Date:</td>

                    <td class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_date)) ?? '' }}</td>

                    <td class="direction-rtl">تاريخ اصدار الفاتورة :</td>

                  </tr>
                  

                </table>

              </div>

              <div class="in-info-en">

                <table>
                  
                  <tr>

                    <td>Date of Supply:</td>

                    <td class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) ?? '' }}</td>

                    <td class="direction-rtl">تاريخ استحقاق الفاتورة :</td>

                  </tr>

                </table>

              </div>

            </div>

          </div>

          <div class="col-lg-3 p-0 in-qr">

            @php 
                $total_product_tax = 0;
                foreach($invoice_products as $rs){
                     $total_product_tax += $rs->tax_amount;
                }
                $total_tax = $invoice->total_tax_amount + $total_product_tax; 
                
                $total_including_charges = $invoice->total_after_discount;
                foreach($invoice->invoiceCharges as $rs){
                      $total_including_charges += $rs->amount;
                }

                // $invoice_link = $qrcode_pdf_path;

                // $invoice_data = 'Store Name:'.$store_detail->name.' Vat RegNo:'.$store_detail->vat_number.' Client:'.$supplier->phone.' Invoice No:'.$invoice->invoice_number.' Invoice Date:'.date('d/m/Y', strtotime($invoice->invoice_date)).' Total:'.$invoice->total_order_amount.' sar Tax:'.$total_tax.' sar';
                if($store_detail->vat_number!="")
                {
                $qrcode = Salla\ZATCA\GenerateQrCode::fromArray([
                    new Salla\ZATCA\Tags\Seller($store_detail->tax_registration_name),      
                    new Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                    new Salla\ZATCA\Tags\InvoiceDate($invoice->invoice_date),
                    new Salla\ZATCA\Tags\InvoiceTotalAmount($total_including_charges + $invoice->total_tax_amount),
                    new Salla\ZATCA\Tags\InvoiceTaxAmount( (float) $invoice->total_tax_amount) 
                ])->toBase64();

                $qrcode=QrCode::encoding('UTF-8')->size(100)->generate($qrcode);
                $qrcode=str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",$qrcode); 
                }
                else
                {
                  $qrcode = "";
                }

            @endphp

            <span>{!! $qrcode !!}</span>

          </div>

        </div><!-- Invoice Info and QR Code Ends -->

        <div class="row invoice-seller-buyer-details mt-1">

        <?php if(isset($supplier->name)){
        ?>
          <div class="col-lg-6 p-0 in-seller-details">
        <?php }
        else{ ?>
          <div class="col-lg-12 p-0 in-seller-details">
        <?php } ?>
            <div class="in-seller-en">

              <table>
                
                <tr>

                  <th colspan="2">Seller/Company:  </th>

                  <th class="direction-rtl">المورد / الشركة :</th>

                </tr>
                <tr>

                  <td>Name:</td>

                  <td class="text-center">{{ $store_detail->name ?? '' }}</td>

                  <td class="direction-rtl">الاسم :</td>

                </tr>

                <?php if(isset($store_detail->primary_email)){
                ?>
                <tr>

                  <td>Email Address:</td>

                  <td class="text-center">{{ isset($store_detail->primary_email) ? $store_detail->primary_email : '' }}</td>

                  <td class="direction-rtl"> البريد الإلكتروني : </td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->street_name)){
                ?>
                <tr>

                  <td>Street</td>

                  <td class="text-center">{{ isset($store_detail->street_name) ? $store_detail->street_name : '' }}</td>

                  <td class="direction-rtl"> اسم الشارع : </td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->district)){
                ?>
                <tr>

                  <td>District</td>

                  <td class="text-center">{{ isset($store_detail->district) ? $store_detail->district : '' }}</td>

                  <td class="direction-rtl"> الحي : </td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->city)){
                ?>
                <tr>

                  <td>City</td>

                  <td class="text-center">{{ isset($store_detail->city) ? $store_detail->city : '' }}</td>

                  <td class="direction-rtl"> المدينة : </td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->country_name)){
                ?>
                <tr>

                  <td>Country</td>

                  <td class="text-center">{{ isset($store_detail->country_name) ? $store_detail->country_name : '' }}</td>

                  <td class="direction-rtl"> البلد :</td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->pincode)){
                ?>
                <tr>

                  <td>Postal Code:</td>

                  <td class="text-center">{{ isset($store_detail->pincode) ? $store_detail->pincode : '' }}</td>

                  <td class="direction-rtl">الرمز الريدي:</td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->primary_contact)){
                ?>
                <tr>

                  <td>Telephone:</td>

                  <td class="text-center">{{ isset($store_detail->primary_contact) ? $store_detail->primary_contact : '' }}</td>

                  <td class="direction-rtl"> الهاتف: </td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->vat_number)){
                ?>
                <tr>

                  <td>VAT Number:</td>

                  <td class="text-center">{{ isset($store_detail->vat_number) ? $store_detail->vat_number : '' }}</td>

                  <td class="direction-rtl">رقم تسجيل ضريبة القيمة المضافة :</td>

                </tr>
                <?php } ?>

                <?php if(isset($store_detail->other_seller_id)){
                ?>
                <tr>

                  <td>Other Seller ID:</td>

                  <td class="text-center">{{ isset($store_detail->other_seller_id) ? $store_detail->other_seller_id : '' }}</td>

                  <td class="direction-rtl">رقم المعرف:</td>

                </tr>
                <?php } ?>

              </table>

            </div>

          </div>

          <div class="col-lg-6 p-0 in-buyer-details">
 
          <?php if(isset($supplier->name)){

          ?>
            <div class="in-buyer-en">

              <table>
                
                <tr>

                  <th colspan="2">Customer:</th>

                  <th class="direction-rtl">العميل :</th>

                </tr>
                <tr>

                  <td>Name:</td>

                  <td class="text-center">{{ $supplier->name ?? '' }}</td>

                  <td class="direction-rtl">الاسم :</td>

                </tr>

                <?php if(isset($supplier->email)){

                ?>

                <tr>

                  <td>Email Address:</td>

                  <td class="text-center">{{ isset($supplier->email) ? $supplier->email : '' }}</td>

                  <td class="direction-rtl"> البريد الإلكتروني: </td>

                </tr>

                <?php } ?>

                <?php if(isset($supplier->street_name)){

                ?>

                <tr>

                  <td>Street</td>

                  <td class="text-center">{{ isset($supplier->street_name) ? $supplier->street_name : '' }}</td>

                  <td class="direction-rtl"> اسم الشارع: </td>

                </tr>

                <?php } ?>                

                <?php if(isset($supplier->district)){

                ?>

                <tr>

                  <td>District</td>

                  <td class="text-center">{{ isset($supplier->district) ? $supplier->district : '' }}</td>

                  <td class="direction-rtl"> الحي: </td>

                </tr>

                <?php } ?> 

                <?php if(isset($supplier->city)){
                ?>
                <tr>

                  <td>City</td>

                  <td class="text-center">{{ isset($supplier->city) ?  $supplier->city : '' }}</td>

                  <td class="direction-rtl"> المدينة: </td>

                </tr>
                <?php } ?>

                <?php if(isset($supplier->country_name)){
                ?>
                <tr>

                  <td>Country</td>

                  <td class="text-center">{{ isset($supplier->country_name) ? $supplier->country_name : '' }}</td>

                  <td class="direction-rtl"> البلد: </td>

                </tr>
                <?php } ?>

                <?php if(isset($supplier->pincode)){
                ?>
                <tr>

                  <td>Postal Code:</td>

                  <td class="text-center">{{ isset($supplier->pincode) ?  $supplier->pincode : '' }}</td>

                  <td class="direction-rtl">الرمز الريدي:</td>

                </tr>
                <?php } ?>

                <?php if(isset($supplier->phone)){
                ?>
                <tr>

                  <td>Telephone:</td>

                  <td class="text-center">{{ isset($supplier->phone) ? $supplier->phone : '' }}</td>

                  <td class="direction-rtl">الهاتف:</td>

                </tr>
                <?php } ?>

                <?php if(isset($supplier->tax_number)){
                ?>
                <tr>

                  <td>VAT Number:</td>

                  <td class="text-center">{{ isset($supplier->tax_number) ? $supplier->tax_number : '' }}</td>

                  <td class="direction-rtl">رقم تسجيل ضريبة القيمة المضافة :</td>

                </tr>
                <?php } ?>

                <?php if(isset($supplier->other_seller_id)){
                ?>
                <tr>

                  <td>Other Customer ID:</td>

                  <td class="text-center">{{ isset($supplier->other_seller_id) ?  $supplier->other_seller_id : '' }}</td>

                  <td class="direction-rtl">رقم المعرف:</td>

                </tr>
                <?php } ?>

              </table>

            </div>
            <?php } ?>
          </div>

        </div><!-- Invoice Seller and Buyer Details Ends -->

        <div class="row list-items-details mt-1">

          <table>

            <thead>

                <tr>

                  <th class="title-en" colspan="4">Line Items:</th>
                  <th class="title-ar" colspan="4">توصيف السلعة أو الخدمة:</th>

                </tr>
                    
                <tr>

                  <th>Item Name<br/><span>اسم الصنف </span></th>
                  <th class="nowrap">Unit Price<br/><span>سعر الوحدة</span></th>
                  <th class="nowrap">Quantity<br/><span>الكمية</span></th>
                  <th class="nowrap">Taxable Amount<br/><span>المبلغ الخاضع للضريبة </span></th>
                  <th class="nowrap">Discount<br/><span>الخصم</span></th>
                  <th class="nowrap">Tax Rate<br/><span>نسبة الضريبة </span></th>
                  <th class="nowrap">Tax Amount<br/><span>مبلغ الضريبة </span></th>
                  <th>Items Subtotal<br/><span>المجموع الفرعي</span></th>

                </tr>

            </thead>

            <tbody>

                @php
                    $subtotal = 0;
                    $product_tax_amount = 0;
                    $product_total_amount = 0;
                @endphp
                @forelse($invoice_products as $rs)
                @php
                    $product_tax_amount += $rs->tax_amount;
                    $product_amount = $rs->quantity * $rs->amount_excluding_tax ;
                    $subtotal += $product_amount;
                    $product_total_amount += $rs->total_amount;
                @endphp

                <tr>
                  
                  <td>
                    <div style="all:unset; padding:0px 0px !important; padding-bottom:0px" >
                      {{ $rs->name }}
                    </div>

                    @if($rs->description != null && ($rs->show_description_in == 4 || $rs->show_description_in == 5 || $rs->show_description_in == 6))
                      <div style="line-height: 0 !important; display: block !important;padding: 0px 0px !important; margin:0px 0px !important; color:lightgray"  class="font-weight-light">----------</div>
                      <div style="padding-top:0px; padding:0px 0px !important;" class="font-weight-light"><small>{{ $rs->description }}</small></div>
                    @endif

                  </td>
                  <td>{{ $rs->amount_excluding_tax }}</td>
                  <td>{{ $rs->quantity }}</td>
                  <td>{{ $product_amount }}</td>
                  <td>{{ $rs->discount_amount  }}</td>
                  <td>{{ $rs->tax_percentage  }}</td>
                  <td>{{ $rs->tax_amount  }}</td>
                  <td>{{ $rs->total_amount }}</td>

                </tr>

                @empty

                @endforelse
                
            </tbody>

          </table>

        </div><!-- List Items Details Ends -->

        <div class="row total-amount-details mt-1">

            <div class="col-lg-6 p-0">

              <table>

                <tr>

                  <th class="title-en" colspan="2">Total Amounts:</th>
                  <th class="title-ar" colspan="2">إجمالي المبالغ:</th>

                </tr>

                <tr>
                  
                  <td>Total (Excluding VAT)</td>
                  <td class="text-center">{{ $subtotal }}</td>
                  <td class="direction-rtl"> الإجمالي </td>
                  

                </tr>

                <tr>
                  
                  <td>Discount</td>
                  <td class="text-center">{{ $invoice->total_discount_amount }}</td>
                  <td class="direction-rtl">مجموع الخصم</td>
                  


                </tr>

                <tr>
                  
                  <td>Total Taxable Amount (Excluding VAT)</td>
                  {{-- <td class="text-center">{{ $subtotal - $invoice->total_discount_amount }}</td> --}}
                  <td class="text-center">{{ $subtotal - $invoice->total_discount_amount }}</td>
                  <td class="direction-rtl">الإجمالي غير شامل الضريبة</td>
                  

                </tr>

                @php $total_including_charges = $invoice->total_after_discount @endphp
                        
                @forelse($invoice->invoiceCharges as $rs)
                
                    @php 
                        $total_including_charges += $rs->amount
                    @endphp

                    <tr>
                        
                        <td>{{ $rs->name }}</td>

                        <td class="text-center">{{ $rs->amount }}</td>

                        <td class="direction-rtl">{{ $rs->name }}</td>

                    </tr>

                @empty

                    {{-- just an empty space --}}

                @endforelse

                {{--   @if(isset($invoice->invoiceCharges))

                    <tr>
                        
                        <td>Subtotal(Including Charges):</td>

                        <td class="text-center">{{ $total_including_charges }}</td>

                        <td class="direction-rtl">Subtotal(Including Charges)-AR:</td>

                    </tr>

                @endif --}}

                <tr>
                  
                  <td>Total VAT</td>
                  {{-- <td class="text-center">{{ $invoice->total_tax_amount }}</td> --}}
                  <td class="text-center">{{ $total_product_tax }}</td>
                  <td class="direction-rtl">مجموع ضريبة القيمة المضافة </td>
                  

                </tr>

                <tr>
                  
                  <td>Total Amount Due</td>
                  {{-- <td class="text-center">{{ $invoice->total_order_amount }}</span> {{ $invoice->currency_code }}</td> --}}
                  <td class="text-center">{{ $total_including_charges + $total_product_tax }}</span> {{ $invoice->currency_code }}</td>
                  <td class="direction-rtl">إجمالي المبلغ المستحق </td>
                  

                </tr>
                

              </table>

            </div>

            <div class="col-lg-6 p-0 bank-account-details">

                <div class="d-flex justify-space-between title border-bottom">

                  <h5>Bank Account</h5>
                  <h5>معلومات الحساب </h5>

                </div>

                <div>
                    
                   <table>
                       
                       <tr>

                            <td>

                                {{ __("Bank Name") }}

                            </td>
                           
                            <td>

                                {{ isset($store_detail->bank_name) ? $store_detail->bank_name : ''}}

                            </td>

                       </tr>

                        <tr>
                           
                           <td>

                                {{ __("IBAN Number") }}

                            </td>
                           
                            <td>

                                {{ isset($store_detail->iban_number) ?  $store_detail->iban_number : '' }}

                            </td>

                        </tr>

                        <tr>
                           
                           <td>

                                {{ __("Name") }}

                            </td>
                           
                            <td>

                                {{ isset($store_detail->account_holder_name) ? $store_detail->account_holder_name : '' }}

                            </td>

                        </tr>

                        <tr>
                            
                            @php   if( (isset($invoice->terms)) && ($invoice->terms != 'null'))
                            { @endphp

                                <td>{{ __("Comments") }}</td>

                                <td>{{ ( (isset($invoice->terms)) && ($invoice->terms != 'null') ) ? $invoice->terms : '' }}</td>

                            @php     } @endphp

                        </tr>

                   </table>

                </div>

            </div>
            <div class="col-lg-12 p-0">
            <table>
                     <tr colspan="2">
                       <div class='center'>
                        <div class='display-block text-uppercase text-center mt-2'>POWERED BY WOSUL</div>
                        </div>
                      </tr>
                   </table>
            </div>

        </div><!-- Total Amount Details Ends -->

      </div> 

      </div>                                      
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
