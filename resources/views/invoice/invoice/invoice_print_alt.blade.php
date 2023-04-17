<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    body{
    margin:15px;
    color:#111;
    font-family: 'Roboto', sans-serif;
}
*{
    box-sizing: border-box;
}
img{
    max-width:100%;
    height:auto;
    width:auto;
}
a{
    color:#111;
}
h1,
h2,
h3,
h4,
h5,
h6,
ul,
ol,
p{
    margin-top:0;
}
.invoice-wrap {
    position: relative;
    margin: 0 auto;
    max-width: 940px;
    padding: 0;
    font-size: 12px;
}
.invoice-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 50px;
}
.invoice-header .invoice-header-logo {
    max-width: 180px;
    margin-bottom: 20px;
    min-height: 70px;
}
.invoice-header .invoice-header-logo img {
    max-height: 70px;
}
.invoice-header-right {
    text-align: left;
}
.invoice-header h2 {
    margin: 0 0 3px;
    text-align: left;
    font-size: 22px;
    text-transform: uppercase;
}
.invoice-header h5 {
    font-size: 16px;
    font-weight: normal;
    margin: 0;
}
.invoice-header-right p {
    margin: 0 0 8px;
}
.invoice-header-left p:last-child{
    margin-bottom: 0;
}
.invoice-header h3 {
    margin: 0 0 5px;
    font-size: 26px;
}
.invoice-header h3 small {
    font-weight: normal;
    font-size: 60%;
}
.border-line {
    height: 4px;
    background:linear-gradient(240.13deg, #BE0683 -3.35%, #3E63CA 103.16%);
    margin-top: 25px;
}
.invoice-text-table {
    width: 100%;
    border:0;
}
.invoice-text-table th, 
.invoice-text-table td {
    padding: 4px 0;
    border:0;
}
.invoice-text-table th{
    text-align:left;
    padding-right:20px;
}
.invoice-text-table .total-tr{
    font-size: 16px;
}
.seller-address {
    max-width: 280px;
    line-height: 160%;
}



.justify-content-between{
    justify-content: space-between;
}
.justify-content-end{
    justify-content: flex-end;
}
.invoice-row {
    display: flex;
    margin: 0 -15px;
}
.invoice-row .invoice-col {
    padding: 0 15px;
    flex-grow: 1;
}
.invoice-col-5 {
    width:40%;
    max-width:40%;
}
.invoice-col-6 {
    width:50%;
    max-width:50%;
}
.invoice-col-7 {
    width:60%;
    max-width:60%;
}
table.invoice-table {
    width: 100%;
    border: 1px solid #ddd;
    border-collapse: collapse;
    border-spacing: 0;
}
table.invoice-table tr, 
table.invoice-table td, 
table.invoice-table th {
    border: 1px solid #ddd;
}
table.invoice-table th {
    padding: 6px 5px;
    background: #002235;
    color: #fff;
    border-color: #245470;
    font-weight: 500;
}
table.invoice-table td {
    padding: 8px 5px;
}
table.invoice-table .col-width-15{
    width:15%;
}
table.invoice-table .col-width-20{
    width:20%;
}
table.invoice-table .col-width-30{
    width:30%;
}
.table-responsive{
    overflow:auto;
    margin-bottom:20px;
}
table.invoice-table tbody tr:nth-child(2n+2) {
    background: #f8f8f8;
}
.head-sub {
    text-align: center;
    font-size: 18px;
    margin: 20px 0 8px;
}
.invoice-footer-row {
    display: flex;
    justify-content: space-between;
}
.footer-signature p {
    font-size: 16px;
    margin: 0;
    font-weight: 500;
    border-top: 2px solid #ededed;
    padding: 12px 20px 0;
    text-transform: uppercase;
}
.invoice-footer {
    margin-top: 12px;
}
.footer-signature-img {
    min-height: 110px;
}
.qr-wrap {
    display: flex;
    align-items: center;
}
.qr-wrap .qr_code {
    margin-right: 15px;
    width: 120px;
    background: #fff;
    padding: 7px;
    border-radius: 8px;
    border: 4px solid #efefef;
}
.qr-wrap .qr_code svg {
    display: block;
    width: 100%;
    height: auto;
}
.invoice-footer {
    text-align: center;
    border-top: 1px solid #ddd;
    font-size: 13px;
    padding-top: 10px;
}
.p-5{
    padding: 3rem!important;
}


</style>
    </head>
    <body>
        @php 
        $logo_path = json_decode($logo_path);
        $invoice = json_decode($invoice);
        $supplier = json_decode($supplier);
        $invoice_products = json_decode($invoice_products);
        $store_detail = json_decode($store_detail);
        $customer_add_infos = json_decode($customer_add_infos);
    @endphp
     
        @php 
            $total_product_tax = 0;
            foreach($invoice_products as $rs){
                  $total_product_tax += $rs->tax_amount;
            }
            $total_tax = $invoice->total_tax_amount + $total_product_tax; 
            
            $total_including_charges = $invoice->total_after_discount;
            if(isset($invoice->invoiceCharges)){
                foreach($invoice->invoiceCharges as $rs){
                    $total_including_charges += $rs->amount;
                }
            }
    
            // $invoice_link = $qrcode_pdf_path;
    
            // $invoice_data = 'Store Name:'.$store_detail->name.' Vat RegNo:'.$store_detail->vat_number.' Client:'.$supplier->phone.' Invoice No:'.$invoice->invoice_number.' Invoice Date:'.date('d/m/Y', strtotime($invoice->invoice_date)).' Total:'.$invoice->total_order_amount.' sar Tax:'.$total_tax.' sar';
            if($store_detail->vat_number!="")
            {
            $qrcode = Salla\ZATCA\GenerateQrCode::fromArray([
                new Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),      
                new Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                new Salla\ZATCA\Tags\InvoiceDate($invoice->invoice_date_iso),
                new Salla\ZATCA\Tags\InvoiceTotalAmount($total_including_charges + $invoice->total_tax_amount),
                new Salla\ZATCA\Tags\InvoiceTaxAmount( (float) $invoice->total_tax_amount) 
            ])->toBase64();
    
            $qrcode=QrCode::encoding('UTF-8')->size(150)->generate($qrcode);
            $qrcode=str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",$qrcode); 
            }
            else
            {
              $qrcode = "";
            }
          
        @endphp
     
        <div class="invoice-wrap p-5">
            <table style="margin-top:25px;">
                <tr>
                    <td valign="bottom" style="padding: 0;" width="60%" >
                        <div style="width:300px;margin-bottom:20px;">
                            <img style="max-height:100px;" width="300" src={{ json_decode($relative_logo_path) }} alt="no media found"/>
                        </div>
                        <table style="width:100%;margin-top:20px;">
                            <tr>
                                <td width="200px">
                                    @if($store_detail->vat_number != '')
                                    <div style="width:200px;border:2px solid #ddd;border-radius: 8px;padding:8px;">
                                        <span style="max-height:100px;">{!! $qrcode !!}</span>
                                    </div>
                                    @endif
                                </td>
                                <td style="padding-left:20px;">
                                    <div class="inner" @if(App::getLocale() == 'ar') style="padding-right:10px;" @else style="padding-left:10px;" @endif >
                                        @if($is_tax_applicable == false)
                                            <h4 style="font-size: 28px;">Invoice <br> فاتورة </h4>
                                        @elseif($invoice->bill_to == "COMPANY" && $is_tax_applicable )
                                            <h4 style="font-size: 28px;">Tax Invoice <br> فاتورة ضريبية </h4>
                                        @else
                                            <h4 style="font-size: 28px;"> Simplified Tax Invoice <br> فاتورة ضريبية مبسطة </h4>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    @if(App::getLocale() == 'ar')
                    <td valign="bottom" style="padding: 0;" width="40%" >
                        <h3 class="text-right" align="right" style="font-size:34px;margin:0 0 5px;">{{ $store_detail->name }}</h3>
                        <p style="font-size:20px;margin:0 0 15px;">{{ $store_detail->building_number }}, {{ $store_detail->district }}, {{ $store_detail->street_name }}, {{ $store_detail->city }}, {{ $store_detail->pincode }}</p>
                     
                        <table style="width:100%">
                            <tr>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">البريد الإلكتروني</td>
                                    <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->primary_email }}</td>
                                    <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%" class="text-left" align="left">Email</th>
                            </tr>
                            <tr>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">  رقم التواصل  </td>
                                    <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->primary_contact }}</td>
                                    <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%" class="text-left" align="left">Contact Number</th>
                            </tr>
                            @if($store_detail->vat_number != '')
                            <tr>
                                <th style="font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">الرقم الضريبي</th>
                                <td style="font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->vat_number }}</td>
                                <th style="font-size:20px;border:0px !important;" width="20%" class="text-left" align="left"> VAT Number </th>
                            </tr>
                            @endif
                            @if(isset($store_detail->other_seller_id) && $store_detail->other_seller_id != '')
                            
                            <tr>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right"> معرف البائع </th>
                                <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center" >{{ $store_detail->other_seller_id }}</td>
                                <th  style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%" class="text-left" align="left">Seller Id</th>
                            </tr>
                            @endif
                        </table>
                        <img src="{{asset('images/bottom-line.png')}}" style="width:100%;margin-top:10px;display:block;"/>
                    </td>
                    @else  
                    <td valign="bottom" style="padding: 0;" width="40%" >
                        <h3 style="font-size:34px;margin:0 0 5px;">{{ $store_detail->name }}</h3>
                        <p style="font-size:20px;margin:0 0 15px;">  {{ $store_detail->building_number }}, {{ $store_detail->district }}, {{ $store_detail->street_name }}, {{ $store_detail->city }}, {{ $store_detail->pincode }}</p>
                        <table style="width:100%">
                            <tr>
                                <th align="left" style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%">Email</th>
                                <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->primary_email }}</td>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">البريد الإلكتروني</th>
                            </tr>
                            <tr>
                                <th align="left" style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%">Contact Number</th>
                                <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->primary_contact }}</td>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">  رقم التواصل  </th>
                            </tr>
                            @if($store_detail->vat_number != '')
                            <tr>
                                <th align="left" style="font-size:20px;border:0px !important;" width="20%"> VAT Number </th>
                                <td style="font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->vat_number }}</td>
                                <th style="font-size:20px;border:0px !important;" width="30%" align="right" class="text-right">الرقم الضريبي </th>
                            </tr>
                            @if(isset($store_detail->other_seller_id) && $store_detail->other_seller_id != '')
                            <tr>
                                <th align="left" style="padding-bottom:3px;font-size:20px;border:0px !important;" width="20%">Seller Id</th>
                                <td style="padding-bottom:3px;font-size:20px;" width="50%" align="center" class="text-center">{{ $store_detail->other_seller_id }}</td>
                                <th style="padding-bottom:3px;font-size:20px;border:0px !important;" width="30%" align="right" class="text-right"> معرف البائع </th>
                            </tr>
                            @endif
                            @endif
                        </table>
                        <img src="{{asset('images/bottom-line.png')}}" style="width:100%;margin-top:10px;display:block;"/>
                    </td>
                    @endif
                </tr>
            </table>

            <table style="width:100%;margin-top:25px;">
                <tr>
                    @if(!is_null($supplier))
                    <td width="47%" valign="top" style="border:1px solid #ddd;border-radius:6px;padding:15px">
                        <div style="border:2px solid #ddd;border-radius:6px;padding:15px;height:200px">
                            <table style="width:100%">
                            @if(App::getLocale() == 'ar')
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" align="center" class=""> @if($invoice->bill_to == 'CUSTOMER') Customer عميل  @else Company شركة  @endif </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">{{ $supplier->name ?? '' }}</td>
                                </tr>
                                    @if(isset($supplier->email))
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">Email البريد الإلكتروني </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">{{ isset($supplier->email) ? $supplier->email : '-' }}</td>
                                </tr>
                                    @endif
                                    @if(isset($supplier->phone))
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" align="center" class=""> Contact Number رقم التواصل  </td>
                                  
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">{{ isset($supplier->phone) ? $supplier->phone : '-' }}</td>
                                </tr>
                                    @endif
                                @if($invoice->bill_to != 'CUSTOMER' && $supplier->tax_number != '')
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">VAT Number الرقم الضريبي  </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">{{ isset($supplier->tax_number) ? $supplier->tax_number : '-' }}</td>
                                </tr>
                                @endif
                                @if($supplier->building_number != null ||
                                $supplier->street_name != null ||
                                $supplier->district != null ||
                                $supplier->city != null)
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" > Address العنوان </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">
                                        {{ ($supplier->building_number != null) ? $supplier->building_number." ," : '' }}     
                                        {{ ($supplier->street_name != null) ? $supplier->street_name." ," : ''  }}
                                        {{ ($supplier->district != null) ? $supplier->district." ," : '' }}
                                        {{ ($supplier->city != null) ? $supplier->city." ," : null }} 
                                        {{ $supplier->pincode  }}    
                                    </td>
                                </tr>
                                @endif
                            
                            @else 
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;"> @if($invoice->bill_to == 'CUSTOMER') Customer عميل  @else Company شركة @endif </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->name ?? '' }}</td>
                                </tr>
                                @if($supplier->email!='')
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;"> Email البريد الإلكتروني </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->email ?? '' }}</td>
                                </tr>
                                @endif
                                @if($supplier->phone!='')
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;">Contact Number رقم التواصل</td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->phone ?? '' }}</td>
                                </tr>
                                @endif
                                @if($invoice->bill_to != 'CUSTOMER' && $supplier->tax_number != '')
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;">VAT Number الرقم الضريبي</td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->tax_number ?? '' }}</td>
                                </tr>
                                @endif
                                @if($supplier->building_number != null ||
                                $supplier->street_name != null ||
                                $supplier->district != null ||
                                $supplier->city != null)
                                <tr>
                                    <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" > Address العنوان </td>
                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="center" class="">
                                        {{ ($supplier->building_number != null) ? $supplier->building_number." ," : '' }}     
                                        {{ ($supplier->street_name != null) ? $supplier->street_name." ," : ''  }}
                                        {{ ($supplier->district != null) ? $supplier->district." ," : '' }}
                                        {{ ($supplier->city != null) ? $supplier->city." ," : null }} 
                                        {{ $supplier->pincode  }}    
                                    </td>
                                </tr>
                                @endif
                            @endif
                            @if(!empty($customer_add_infos))
                                @foreach($customer_add_infos as $info)
                                    <tr>
                                        <td style="font-size:10px;font-weight: 600;padding-bottom:0px !important;padding-top:0px !important;" >{{ $info->title }}</td>
                                        <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;" align="left" class="text-left">{{ $info->description }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </table>
                        </div>
                    </td>
                    @endif
                    <td width="6%">
                    </td>
                    <td width="47%" valign="top" style="border:1px solid #ddd;border-radius:6px;padding:15px">
                        <div >
                            <table style="width:100%">
                                @if(App::getLocale() == 'ar') 
                                <tr>
                                    <th align="left" style="padding-bottom:6px;font-weight: 600;font-size:12px;">Invoice No</th>
                                    <td style="padding-bottom:6px;font-size:12px;" align="center">{{ $invoice->invoice_number ?? '' }}</td>
                                    <td style="padding-bottom:6px;font-size:12px;" align="right">فاتورة رقم</td>
                                </tr>
                                <tr>
                                    <th align="left" style="padding-bottom:6px;font-weight: 600;font-size:12px;">Invoice Issue Date</th>
                                    <td style="padding-bottom:6px;font-size:12px;" align="center">{{ date('d/m/Y', strtotime($invoice->invoice_date)) ?? '' }}</td>
                                    <td style="padding-bottom:6px;font-size:12px;" align="right">الفاتورة إصدار تاريخ</td>
                                </tr>
                                <tr>
                                    <th align="left" style="padding-bottom:6px;font-weight: 600;font-size:12px;">Invoice Due Date</th>
                                    <td style="padding-bottom:6px;font-size:12px;" align="center">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) ?? '' }}</td>
                                    <td style="padding-bottom:6px;font-size:12px;" align="right">تاريخ استحقاق الفاتورة</td>
                                </tr>
                                @if($invoice->invoice_reference != null)
                                <tr>
                                    <th align="left" style="padding-bottom:6px;font-weight: 600;font-size:12px;">Invoice Reference</th>
                                    <td style="padding-bottom:6px;font-size:12px;" align="center">{{ $invoice->invoice_reference ?? '' }}</td>
                                    <td style="padding-bottom:6px;font-size:12px;" align="right">الرقم المرجعي للفاتورة</td>
                                </tr>
                                @endif
                            @else 
                                <tr>
                                    <td  style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600;padding-left:5px;" width="30%">Invoice No</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif " width="40%" align="center" class="text-center">{{ $invoice->invoice_number ?? '' }}</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600;" width="30%" align="right" class="text-right"> <small><strong>فاتورة رقم</strong> </small> </td>
                                </tr>
                                <tr>
                                    <td  style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600;padding-left:5px;" width="30%">Invoice Issue Date</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif " width="40%" align="center" class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_date)) ?? '' }}</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600; " width="30%" align="right" class="text-right"> الفاتورة إصدار تاريخ</td>
                                    
                                </tr>
                                <tr>
                                    <td  style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600; padding-left:5px;" width="30%">Invoice Due Date</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif " width="40%" align="center" class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) ?? '' }}</td>
                                    <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600;" width="30%" align="right" class="text-right">تاريخ استحقاق الفاتورة</td>
                                </tr>
                                @if($invoice->invoice_reference != null)
                                    <tr>
                                        <td  style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600; padding-left:5px;" width="30%">Invoice Reference</td>
                                        <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif " width="40%" align="center" class="text-center">{{ $invoice->invoice_reference ?? '' }}</td>
                                        <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif font-weight: 600;" width="30%" align="right" class="text-right">الرقم المرجعي للفاتورة </td>
                                    </tr>
                                @endif
                            @endif
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="table-responsive" style="margin-top:25px">
                <table style="width:100%" class="invoice-table">
                    <thead>
                        @php 
                        $total_product_discount = 0 
                    @endphp
                    @foreach($invoice_products as $rs)
                        @php $total_product_discount += $rs->discount_amount @endphp
                    @endforeach
                    <tr>
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Item Name <br> اسم الصنف </th>
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Unit Price <br> سعر الوحدة </th>
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Quantity <br> الكمية</th>
                        @if($store_detail->vat_number != '')
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Taxable Amount <br> المبلغ الخاضع
للضريبة </th> @endif
@if($total_product_discount > 0)
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Discount <br> الخصم</th>
                        @endif
                        @if($store_detail->vat_number != '')
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Tax Rate <br> نسبة الضريبة</th>
                        <th style="font-size:12px;font-weight:light;" class="text-center"  >Tax Amount <br> مبلغ الضريبة </th>
                        @endif
                        <th style="font-size:12px;font-weight:light;" class="text-center">Items Subtotal <br> المجموع الفرعي</th>
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
                      <tr >
                          <td class="text-center" align="center" style="font-size: 12px;">
                            {{ $rs->name }}
                            @if($rs->description != null && ($rs->show_description_in == 4 || $rs->show_description_in == 5 || $rs->show_description_in == 6))
                              <br><small>{{ $rs->description }}</small>
                            @endif
                          </td>
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->amount_excluding_tax }}</td>
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->quantity }}</td>
                          @if($store_detail->vat_number != '')
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $product_amount }}</td>
                          @endif
                          @if($total_product_discount > 0)
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->discount_amount  }}</td>
                          @endif
                          
                          @if($store_detail->vat_number != '')
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->tax_percentage  }}</td>
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->tax_amount  }}</td>
                          @endif
                          <td class="text-center" align="center" style="font-size: 12px;">{{ $rs->total_amount }}</td>
                      </tr>
                      @empty

                      @endforelse
                    
                    </tbody>
                </table>
            </div>
            <table style="width:100%">
                <tr>
                    <td width="47%" valign="top">
                        <table style="width:100%">
                            @if(App::getLocale() == 'ar')  
                            <tr>
                                <td style="font-weight: bold;font-size:12px;"> اسم البنك Bank Name</td>
                           
                                <td style="font-size: 12px;">{{ isset($store_detail->bank_name) ? $store_detail->bank_name : ''}}</td>

                            </tr>
                            <tr>
                                <td style="font-weight: bold;font-size:12px;" class="white-space" valign="top"> رقم الآيبان IBAN Number</td>
                           
                                <td style="font-size: 12px;">{{ isset($store_detail->iban_number) ?  $store_detail->iban_number : '' }}</td>

                            </tr>
                            <tr>
                                <td style="font-weight: bold;font-size:12px;" class="white-space" valign="top"> الاسم Name</td>
                           
                                <td style="font-size: 12px;">{{ isset($store_detail->account_holder_name) ? $store_detail->account_holder_name : '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;font-size:12px;" class="white-space" valign="top"> الملاحظات Notes</td>
                           
                                
                                <td style="font-size: 12px;">
                                    @if(isset($invoice->terms) && $invoice->terms!='' && $invoice->terms!='null')
                                        {{ $invoice->terms }}
                                    @else
                                        {{ ' ' }}
                                    @endif
                                </td>
                            </tr>
                        @else 
                            <tr>
                                <td style="font-weight: bold; font-size: 12px;"> Bank Name اسم البنك </td>
                            
                                <td style="font-size: 12px;">{{ isset($store_detail->bank_name) ? $store_detail->bank_name : ''}}</td>

                            </tr>
                            <tr>
                                <td style="font-weight: bold; font-size: 12px;" class="white-space" valign="top"> IBAN Number رقم الآيبان </td>
                           
                                <td style="font-size: 12px;">{{ isset($store_detail->iban_number) ?  $store_detail->iban_number : '' }}</td>

                            </tr>
                            <tr>
                                <td style="font-weight: bold; font-size: 12px;" class="white-space" valign="top">Name الاسم </td>
                           
                                <td style="font-size: 12px;">{{ isset($store_detail->account_holder_name) ? $store_detail->account_holder_name : '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; font-size: 12px;" class="white-space" valign="top">Notes الملاحظات </td>
                           
                                <td style="font-size: 12px;">
                                    @if(isset($invoice->terms) && $invoice->terms!='' && $invoice->terms!='null')
                                        {{ $invoice->terms }}
                                    @else
                                        {{ ' ' }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </table>
                    </td>
                    <td width="6%">
                    </td>
                    <td width="47%" valign="top">
                        <table style="width:100%">
                            <tr>
                                @if(App::getLocale() == 'ar')
                                <td  align="center" style="font-size: 12px;line-height:140%;padding-bottom:10px;">
                                    <div>المجموع الفرعي</div><div>Total (Excluding VAT) </div></td>
                                @else 
                                    <td  align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> Total (Excluding VAT) </div> <div>المجموع الفرعي</div>
                                    </td>
                                @endif
                                <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;padding-bottom:10px;">{{ $subtotal }}</td>
                            </tr>
                            @if($invoice->total_discount_amount > 0)
                            <tr>
                                @if(App::getLocale() == 'ar')
                                    <td align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> مجموع الخصم </div> Discount 
                                    </td>
                                @else
                                    <td align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> Discount </div> مجموع الخصم 
                                    </td>
                                @endif
                                <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;padding-bottom:10px;">{{ $invoice->total_discount_amount }}</td>
                            </tr>
                            @endif
                            @if($store_detail->vat_number != '')
                            <tr>
                                @if(App::getLocale() == 'ar')
                                    <td class="text-right" align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> الإجمالي غير شامل الضريبة	</div> Total Taxable Amounts (Excl. VAT)
                                    </td>
                                @else 
                                    <td align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;" >
                                        <div>Total Taxable Amount (Excl. VAT) </div> الإجمالي غير شامل الضريبة 
                                    </td>
                                @endif
                                <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;padding-bottom:10px;">{{ $subtotal - $invoice->total_discount_amount }}</td>
                            </tr>
                            @endif
                            @php $total_including_charges = $invoice->total_after_discount @endphp
                            
                            @isset($invoice->invoiceCharges)
                            @forelse($invoice->invoiceCharges as $rs)
                            
                                @php 
                                    $total_including_charges += $rs->amount
                                @endphp

                                <tr>
                                  <td   @if(App::getLocale() == 'ar') align="center" @else align="center" @endif style="font-size: 12px;padding-bottom:10px;line-height:140%;">{{ $rs->name }}</td>
                                  <td  @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;padding-bottom:10px;line-height:140%;">{{ $rs->amount }}</td>
                                </tr>

                            @empty

                            @endforelse
                            @endisset
                            @if($store_detail->vat_number != '')
                            <tr>
                                @if(App::getLocale() == 'ar')
                                    <td align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> مجموع ضريبة القيمة المضافة  </div> Total VAT
                                    </td>
                                @else
                                    <td align="center" style="font-size: 12px;padding-bottom:10px;line-height:140%;">
                                        <div> Total VAT </div> مجموع ضريبة القيمة المضافة  
                                    </td>
                                @endif
                                <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;padding-bottom:10px;" >{{ $total_product_tax }}</td>
                            </tr>
                            @endif
                            <tr class="total-tr">
                                @if(App::getLocale() == 'ar')
                                    <td style="font-weight: bold; font-size:15px; padding-bottom:10px; line-height:140%;" align="center" style="">
                                        <div> إجمالي المبلغ المستحق  </div> Total Amount Due 
                                    </td>
                                @else
                                    <td style="font-weight: bold; font-size:15px; padding-bottom:10px; line-height:140%;" align="center" style="">
                                        <div> Total Amount Due </div> إجمالي المبلغ المستحق
                                    </td>
                                @endif
                                <td style="font-size: 12px;padding-bottom:10px;" @if(App::getLocale() == 'en') align="right" @else align="left" @endif style="font-size: 12px;" >{{ $total_including_charges + $total_product_tax }}</span> {{ $invoice->currency_code }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div style="border-top:1px solid #ddd;padding:15px 0;margin-top:25px;">
                @if(App::getLocale() == 'en') 
                <p style="margin:0;text-align: center;"> POWERED BY WOSUL | مطوَّر من قِبَل وصول </p>
                @else 
                    <p style="margin:0;text-align: center;"> مطوَّر من قِبَل وصول | POWERED BY WOSUL </p>
                @endif
              
            </div>

        </div>



    </body>
</html>

