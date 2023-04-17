@php 
    $logo_path = json_decode($logo_path);
    $quotation = json_decode($quotation);
    $supplier = json_decode($supplier);
    $quotation_products = json_decode($quotation_products);
    $store_detail = json_decode($store_detail);
@endphp
<!DOCTYPE html>
<html>
    <head>
        @if(App::getLocale() == "ar")
        <link href="{{ public_path('css/quotation_print_alt_ar.css') }}" rel="stylesheet" type="text/css" />
        @endif
        <title>Quotation #2</title>
    </head>

    <style>
        .quotation-card{
            color: #505d69 !important;
        }
        td{
            color: #505d69 !important;
        }
        .background-color{
            background: white;
        }
    </style>

    <body>
        <div id="quotation-wrapper">

      <div class="quotation-inner">

        <div class="header text-center">

        </div>


        <div class="header-02">
          <div style="width: auto;
          height: 110px;">
            <img src="{{ $logo_path }}" alt="Logo" style="
            width: auto; 
          max-width: 100%;
          max-height: 110px;">

          </div>
        @if($quotation->bill_to=="CUSTOMER")
        <h4>فاتورة ضريبة مبسطة</h4>
        <h4>Simplified Tax Quotation</h4>
        @else
        <h4>فاتورة ضريبة</h4>
        <h4>Tax Quotation</h4>
        @endif
        </div>

        <div class="quotation-info-qrcode">

          <div class="in-info">

            <div class="d-flex">

              <div class="in-info-en">

                <table>
                  
                  <tr>

                    <td style="width:30%;border-left:2px solid #000!important;border-right:1px solid #fff!important">Quotation No:</td>

                    <td style="border-left:2px solid #fff;border-right:2px solid #fff;" class="text-center">{{ $quotation->quotation_number ?? '' }}</td>

                    <td style="width:30%;border-right:2px solid #000!important" class="direction-rtl">رقم الفاتورة :</td>

                  </tr>

                </table>

              </div>

            </div>

            <div class="d-flex">

              <div class="in-info-en">

                <table>
                  
                  <tr>

                    <td style="width:30%;border-left:2px solid #000!important;border-right:1px solid #fff!important">Quotation Issue Date:</td>

                    <td style="border-left:2px solid #fff;border-right:2px solid #fff;text-align:center;" class="text-center">{{ date('d/m/Y', strtotime($quotation->quotation_date)) ?? '' }}</td>

                    <td style="width:30%;border-right:2px solid #000!important" class="direction-rtl">تاريخ اصدار الفاتورة :</td>

                  </tr>
      
                  

                </table>

              </div>

              <div class="in-info-en">

                <table>
                  
                  
      
                  <tr>

                    <td style="width:30%;border-left:2px solid #000!important;border-right:1px solid #fff!important">Date of Supply:</td>

                    <td style="border-left:2px solid #fff;border-right:2px solid #fff;text-align:center;" class="text-center">{{ date('d/m/Y', strtotime($quotation->quotation_due_date)) ?? '' }}</td>

                    <td style="width:30%;border-right:2px solid #000!important" class="direction-rtl">تاريخ استحقاق الفاتورة :</td>

                  </tr>

                </table>

              </div>

            </div>

          </div>

          <div class="in-qr">

            @php 
                $total_product_tax = 0;
                foreach($quotation_products as $rs){
                     $total_product_tax += $rs->tax_amount;
                }
                $total_tax = $quotation->total_tax_amount + $total_product_tax; 
                
                // $quotation_link = $qrcode_pdf_path;

                // $quotation_data = 'Store Name:'.$store_detail->name.' Vat RegNo:'.$store_detail->vat_number.' Client:'.$supplier->phone.' Quotation No:'.$quotation->quotation_number.' Quotation Date:'.date('d/m/Y', strtotime($quotation->quotation_date)).' Total:'.$quotation->total_order_amount.' sar Tax:'.$total_tax.' sar';
                if($store_detail->vat_number!="")
                {
                $qrcode = Salla\ZATCA\GenerateQrCode::fromArray([
                    new Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),      
                    new Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                    new Salla\ZATCA\Tags\InvoiceDate($quotation->quotation_date),
                    new Salla\ZATCA\Tags\InvoiceTotalAmount($quotation->total_order_amount),
                    new Salla\ZATCA\Tags\InvoiceTaxAmount($quotation->total_tax_amount) 
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

        </div><!-- Quotation Info and QR Code Ends -->
        <?php if(!isset($supplier->name)){?>
        <div class="quotation-seller-buyer-details" style="width:100%;" >
         
          <div class="d-flex in-seller-details" style="width:100%;">
            <div class="in-seller-en" style="width:100%;">

              <table style="width:100%;">
              <?php } else {?>
                <div class="quotation-seller-buyer-details" >
         
         <div class="d-flex in-seller-details" >
           <div class="in-seller-en">

             <table>
              <?php } ?>
                
                <tr>

                  <th colspan="2">Seller/Company:</th>

                  <th style="border-left:1px solid #e9e9e9!important;" class="direction-rtl">المورد / الشركة :</th>

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




        
          <?php if(isset($supplier->name)){?>
            <div class="d-flex in-buyer-details">
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
           
          </div>
          <?php  } ?>

        
        
        </div>

        <!-- Quotation Seller and Buyer Details Ends -->

        <div class="list-items-details mt-1">

          <table>

            <thead>

                <tr>

                  <th class="title-en" colspan="4">Line Items:</th>
                  <th class="direction-rtl" colspan="4">توصيف السلعة أو الخدمة:</th>

                </tr>
                    
                <tr>

                  <th class="nowrap">Item Name<br/><span>اسم الصنف </span></th>
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
                @forelse($quotation_products as $rs)
                @php
                    $product_tax_amount += $rs->tax_amount;
                    $product_amount = $rs->quantity * $rs->amount_excluding_tax ;
                    $subtotal += $product_amount;
                    $product_total_amount += $rs->total_amount;

                @endphp

                <tr>
                  
                  <td class="nowrap">
                    {{ $rs->name }}
                    <br />
                    {{ $rs->description }}
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

        <div class="total-amount-wrapper mt-1">

            <table>

                <tr>

                  <th class="title-en" colspan="2">Total Amounts:</th>
                  <th class="direction-rtl" colspan="2">إجمالي المبالغ:</th>

                </tr>

                <tr>
                  
                  <td style="color:#000;">Total (Excluding VAT)</td>
                  <td style="color:#000;" class="text-center">{{ $subtotal }}</td>
                  <td style="color:#000;" class="direction-rtl"> الإجمالي </td>
                  

                </tr>

                <tr>
                  
                  <td style="color:#000;">Discount</td>
                  <td style="color:#000;" class="text-center">{{ $quotation->total_discount_amount }}</td>
                  <td style="color:#000;" class="direction-rtl">مجموع الخصم</td>
                  


                </tr>

                <tr>
                  
                  <td style="color:#000;">Total Taxable Amount (Excluding VAT)</td>
                  <td style="color:#000;" class="text-center">{{ $subtotal - $quotation->total_discount_amount }}</td>
                  <td style="color:#000;" class="direction-rtl">الإجمالي غير شامل الضريبة</td>
                  

                </tr>


                <tr>
                  
                  <td style="color:#000;">Total VAT</td>
                  {{-- <td style="color:#000;" class="text-center">{{ $quotation->total_tax_amount  }}</td> --}}
                  <td style="color:#000;" class="text-center">{{ $total_product_tax  }}</td>
                  <td style="color:#000;" class="direction-rtl">مجموع ضريبة القيمة المضافة </td>
                  

                </tr>

                <tr>
                  
                  <td style="color:#000;font-weight: bold;">Total Amount Due</td>
                  {{-- <td style="color:#000;font-weight: bold;" class="text-center">{{ $quotation->total_order_amount }}</span> {{ $quotation->currency_code }}</td> --}}
                  <td style="color:#000;font-weight: bold;" class="text-center">{{ $product_total_amount }}</span> {{ $quotation->currency_code }}</td>
                  <td style="color:#000;font-weight: bold;" class="direction-rtl">إجمالي المبلغ المستحق </td>
                  

                </tr>

                
                

              </table>

        </div>

        <div class="bank-details-wrapper">

            <div class="bank-account-details">

                <div>                   

                    <table width="100%">
                        
                        <tr>
                            
                            <th style="background: none;float: left;border:none">Bank Account</th>

                            <th style="background: none;float: right;text-align: right;border:none">علومات الحساب  </th>

                        </tr>

                    </table>

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
                            
                            @php   if( (isset($quotation->terms)) && ($quotation->terms != 'null'))
                            { @endphp

                                <td style="border-bottom:1px solid #f9f9f9!important">{{ __("Comments") }}</td>

                                <td style="border-bottom:1px solid #f9f9f9!important">{{ $quotation->terms }}</td>

                            @php     } @endphp

                        </tr>

                   </table>
                  

                </div>
                

            </div>

        </div>

        <div class="d-flex align-items-center justify-content-center w-100 mt-1">
            <p class="text-center">POWERED BY WOSUL</p>
            </div>
      </div>

    </div>
    </body>
    
</html>
