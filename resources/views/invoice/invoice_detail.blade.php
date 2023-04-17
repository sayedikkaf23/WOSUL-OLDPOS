@extends('layouts.layout')
@php
    use Illuminate\Support\Str as Str;
    use App\Http\Resources\ExpresspayResource;

@endphp
@push('styles')

    <style>


        body {
            margin: 15px;
            color: #111;
            font-family: 'Roboto', sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
            height: auto;
            width: auto;
        }

        a {
            color: #111;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        ul,
        ol,
        p {
            margin-top: 0;
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
            margin-bottom: 20px;
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
            width: 350px;
            font-size: 14px;
        }

        .invoice-header h2 {
            margin: 0 0 3px;
            text-align: left;
            font-size: 18px;
            text-transform: uppercase;
        }

        .invoice-header h5 {
            font-size: 14px;
            font-weight: normal;
            margin: 0;
        }

        .invoice-header-right p {
            margin: 0 0 8px;
        }

        .invoice-header-left p:last-child {
            margin-bottom: 0;
        }

        .invoice-header h3 {
            margin: 0 0 5px;
            font-size: 22px;
        }

        .invoice-header h3 small {
            font-weight: normal;
            font-size: 60%;
        }

        .border-line {
            height: 4px;
            background: linear-gradient(240.13deg, #BE0683 -3.35%, #3E63CA 103.16%);
            margin-top: 25px;
        }

        .invoice-text-table {
            width: 100%;
            border: 0;
            line-height: 130%;
        }

        .invoice-text-table th,
        .invoice-text-table td {
            padding: 4px 0;
            border: 0 !important;
            font-size: 12px;
        }

        .invoice-text-table th {
            text-align: left;
            padding-right: 20px;
        }

        .invoice-text-table .total-tr {
            font-size: 12px;
        }

        .seller-address {
            max-width: 280px;
            line-height: 160%;
        }


        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-end {
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
            width: 40%;
            max-width: 40%;
        }

        .invoice-col-6 {
            width: 50%;
            max-width: 50%;
        }

        .invoice-col-7 {
            width: 60%;
            max-width: 60%;
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
            font-weight: 500;
        }

        table.invoice-table td {
            padding: 8px 5px;
            font-size: 12px;
        }

        table.invoice-table .col-width-15 {
            width: 15%;
        }

        table.invoice-table .col-width-20 {
            width: 20%;
        }

        table.invoice-table .col-width-30 {
            width: 30%;
        }

        .table-responsive {
            overflow: auto;
            margin-bottom: 20px;
        }

        table.invoice-table tbody tr:nth-child(2n+2) {
            background: #f8f8f8;
        }

        .head-sub {
            text-align: center;
            font-size: 16px;
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
            /* border-top: 1px solid #ddd; */
            font-size: 10px;
            padding-top: 10px;
            bottom: 0px;
        }

        table th {
            border-top: 1px solid #ddd !important;
        }

        table tr td:nth-child(2) {
            border-left: 0px solid #fff !important;
        }
    </style>
@endpush

@section("content")

    <invoicedetailcomponent :invoice_statuses="{{ json_encode($invoice_statuses) }}"
                            :invoice_data="{{ json_encode($invoice_data) }}"
                            :transaction_type_data="{{ json_encode($transaction_type) }}"
                            :accounts="{{ json_encode($accounts) }}"
                            :payment_methods="{{ json_encode($payment_methods) }}"
                            :currency_codes="{{ json_encode($currency_codes) }}"
                            :delete_invoice_access="{{ json_encode($delete_invoice_access) }}"
                            :make_payment_access="{{ json_encode($make_payment_access) }}"
                            :express_payment_slack="{{ json_encode($express_payment_slack) }}"
                            :pdf_path="{{ json_encode($pdf_path) }}"
                            :mada_visa_master_img="{{ json_encode($mada_visa_master_img) }}"
                            ></invoicedetailcomponent>

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
            new Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),      
            new Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
            new Salla\ZATCA\Tags\InvoiceDate($invoice->invoice_date_iso),
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

    <div class="row">
        <div class="col-12 text-right">
            <button onClick="printReceipt()"
                    class="btn btn-outline-danger mr-1 text-danger">{{ __("Print Now") }}</button>
            {{-- <button onClick="download_pdf()" class="btn btn-outline-danger mr-1 text-danger download_pdf"></button> --}}
            <a target="_blank" class="btn btn-outline-danger mr-1 text-danger download_pdf"
               href="{{$pdf_path}}">{{ __("Download Now") }}</a>

        </div>
    


        <div id="invoiceWrapper" style="width: 850px !important;" class="invoice-wrap card p-5">
            <div @if(App::getLocale() == 'ar') style="direction: rtl;" @else style="direction: ltr;" @endif >
                <div class="invoice-header">
                    <div class="invoice-header-left">
                        <div class="invoice-header-logo">
                            <img style="max-width:150%;max-height:100px;" src="{{ $relative_logo_path }}"
                                 alt="no media found"/>
                        </div>
                        <div class="qr-wrap">
                            @if($store_detail->vat_number != '')
                                <div class="qr_code">
                                    <span>{!! $qrcode !!}</span>
                                </div>
                            @endif
                            <div class="inner" @if(App::getLocale() == 'ar') style="padding-right:10px;"
                                 @else style="padding-left:10px;" @endif >
                                @if($is_tax_applicable == false)
                                    <h4>Invoice <br> فاتورة </h4>
                                @elseif($invoice->bill_to == "COMPANY" && $is_tax_applicable )
                                    <h4>Tax Invoice <br> فاتورة ضريبية </h4>
                                @else
                                    <h4> Simplified Tax Invoice <br> فاتورة ضريبية مبسطة </h4>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(App::getLocale() == 'ar')

                        <div class="invoice-header-right">
                            <h3 class="text-right" align="right">{{ $store_detail->name }}</h3>
                            <table width="100%">
                                <tr>
                                    <td style="padding-bottom:3px;font-size:12px;" width="100%" colspan="3"
                                        align="right">
                                        {{ $store_detail->building_number }}, {{ $store_detail->district }}
                                        , {{ $store_detail->street_name }}, {{ $store_detail->city }}
                                        , {{ $store_detail->pincode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="30%"
                                        align="right" class="text-right">البريد الإلكتروني
                                    </td>
                                    <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                        class="text-center">{{ $store_detail->primary_email }}</td>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="20%"
                                        class="text-left" align="left">Email
                                    </th>
                                </tr>
                                <tr>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="30%"
                                        align="right" class="text-right"> رقم التواصل
                                    </td>
                                    <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                        class="text-center">{{ $store_detail->primary_contact }}</td>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="20%"
                                        class="text-left" align="left">Contact Number
                                    </th>
                                </tr>
                                @if($store_detail->vat_number != '')
                                    <tr>
                                        <th style="font-size:12px;border:0px !important;" width="30%" align="right"
                                            class="text-right">الرقم الضريبي
                                        </th>
                                        <td style="font-size:12px;" width="50%" align="center"
                                            class="text-center">{{ $store_detail->vat_number }}</td>
                                        <th style="font-size:12px;border:0px !important;" width="20%" class="text-left"
                                            align="left"> VAT Number1
                                        </th>
                                    </tr>
                                    @if(isset($store_detail->other_seller_id) && $store_detail->other_seller_id != '')

                                        <tr>
                                            <th style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                                width="30%" align="right" class="text-right"> معرف البائع
                                            </th>
                                            <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                                class="text-center">{{ $store_detail->other_seller_id }}</td>
                                            <th style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                                width="20%" class="text-left" align="left">Seller Id
                                            </th>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                            <div class="border-line"><span></span></div>
                        </div>

                    @else
                        <div class="invoice-header-right">
                            <h3>{{ $store_detail->name }}</h3>
                            <table width="100%">
                                <tr>
                                    <td style="padding-bottom:3px;font-size:12px;" width="100%" colspan="3">
                                        {{ $store_detail->building_number }}, {{ $store_detail->district }}
                                        , {{ $store_detail->street_name }}, {{ $store_detail->city }}
                                        , {{ $store_detail->pincode }}
                                    </td>
                                </tr>

                                <tr>
                                    <th align="left" style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                        width="20%">Email
                                    </th>
                                    <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                        class="text-center">{{ $store_detail->primary_email }}</td>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="30%"
                                        align="right" class="text-right">البريد الإلكتروني
                                    </th>
                                </tr>
                                <tr>
                                    <th align="left" style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                        width="20%">Contact Number
                                    </th>
                                    <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                        class="text-center">{{ $store_detail->primary_contact }}</td>
                                    <th style="padding-bottom:3px;font-size:12px;border:0px !important;" width="30%"
                                        align="right" class="text-right"> رقم التواصل
                                    </th>
                                </tr>
                                @if($store_detail->vat_number != '')
                                    <tr>
                                        <th align="left" style="font-size:12px;border:0px !important;" width="20%"> VAT
                                            Number
                                        </th>
                                        <td style="font-size:12px;" width="50%" align="center"
                                            class="text-center">{{ $store_detail->vat_number }}</td>
                                        <th style="font-size:12px;border:0px !important;" width="30%" align="right"
                                            class="text-right">الرقم الضريبي
                                        </th>
                                    </tr>
                                    @if(isset($store_detail->other_seller_id) && $store_detail->other_seller_id != '')
                                        <tr>
                                            <th align="left"
                                                style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                                width="20%">Seller Id
                                            </th>
                                            <td style="padding-bottom:3px;font-size:12px;" width="50%" align="center"
                                                class="text-center">{{ $store_detail->other_seller_id }}</td>
                                            <th style="padding-bottom:3px;font-size:12px;border:0px !important;"
                                                width="30%" align="right" class="text-right"> معرف البائع
                                            </th>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                            <div class="border-line"><span></span></div>
                        </div>
                    @endif

                </div>
                <div class="invoice-body">
                    <div class="invoice-row justify-content-between" style="margin: 0;">

                        @if(!is_null($supplier))
                            <div class="invoice-col invoice-col-6" style="padding:5px; border:1px solid black;border-radius:5px;margin-bottom: 10px;@if(App::getLocale() == 'en') margin-right: 5px; @endif">
                                <div class="table-responsive rounded mb-0">
                                    <table class="invoice-text-table">
                                        <tbody>

                                        @if(App::getLocale() == 'ar')
                                            <tr>
                                                <td width="40%"
                                                    style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                    align="center"
                                                    class=""> @if($invoice->bill_to == 'CUSTOMER') عميل
                                                    Customer @else شركة Company @endif </td>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                    align="center" class="">{{ $supplier->name ?? '' }}</td>
                                            </tr>
                                            @if($supplier->email != '')
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center" class="">البريد الإلكتروني Email
                                                    </td>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center"
                                                        class="">{{ isset($supplier->email) ? $supplier->email : '-' }}</td>
                                                </tr>
                                            @endif
                                            @if($supplier->phone != '')
                                            <tr>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                    align="center" class=""> رقم التواصل Contact Number
                                                </td>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                    align="center"
                                                    class="">{{ isset($supplier->phone) ? $supplier->phone : '-' }}</td>
                                            </tr>
                                            @endif
                                            @if($invoice->bill_to != 'CUSTOMER' && $supplier->tax_number != '')
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center" class=""> الرقم الضريبي Vat Number
                                                    </td>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center"
                                                        class="">{{ isset($supplier->tax_number) ? $supplier->tax_number : '-' }}</td>
                                                </tr>
                                            @endif

                                            @if($supplier->building_number != null ||
                                            $supplier->street_name != null ||
                                            $supplier->district != null ||
                                            $supplier->city != null)
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center"> العنوان Address
                                                    </td>

                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"
                                                        align="center" >
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
                                                <td width="40%"
                                                    style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;"> @if($invoice->bill_to == 'CUSTOMER')
                                                        Customer عميل  @else Company شركة @endif </td>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->name ?? '' }}</td>
                                            </tr>
                                            @if($supplier->email != '')
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">
                                                        Email البريد الإلكتروني
                                                    </td>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->email ?? '' }}</td>
                                                </tr>
                                            @endif
                                            @if($supplier->phone != '')
                                            <tr>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">
                                                    Contact Number رقم التواصل
                                                </td>
                                                <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->phone ?? '' }}</td>
                                            </tr>
                                            @endif
                                            @if($invoice->bill_to != 'CUSTOMER' && $supplier->tax_number != '')
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">
                                                        VAT Number الرقم الضريبي
                                                    </td>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $supplier->tax_number ?? '' }}</td>
                                                </tr>
                                            @endif
                                            @if($supplier->building_number != null ||
                                            $supplier->street_name != null ||
                                            $supplier->district != null ||
                                            $supplier->city != null)
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">
                                                        Address العنوان
                                                    </td>

                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">
                                                        {{ ($supplier->building_number != null) ? $supplier->building_number." ," : '' }}
                                                        {{ ($supplier->street_name != null) ? $supplier->street_name." ," : ''  }}
                                                        {{ ($supplier->district != null) ? $supplier->district." ," : '' }}
                                                        {{ ($supplier->city != null) ? $supplier->city." ," : null }}
                                                        {{ $supplier->pincode ?? ''  }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if(!empty($customer_add_infos))
                                            @foreach($customer_add_infos as $info)
                                                <tr>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $info->title }}</td>
                                                    <td style="font-size:10px;padding-bottom:0px !important;padding-top:0px !important;">{{ $info->description }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif


                        <div class="invoice-col invoice-col-6" style="padding:5px; border:1px solid black;border-radius:5px;margin-bottom: 10px;@if(App::getLocale() == 'ar') margin-right: 5px; @endif">
                            <div class="table-responsive rounded mb-0">
                                <table class="invoice-text-table">
                                    <tbody>
                                    @if(App::getLocale() == 'ar')
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right">فاتورة رقم</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ $invoice->invoice_number ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;padding-left:10px;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="left" class="text-left">Invoice No
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right">تاريخ إصدار الفاتورة
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_date)) ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;padding-left:10px;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="left" class="text-left">Invoice Issue Date
                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right">تاريخ استحقاق الفاتورة
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;padding-left:10px;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="left" class="text-left">Invoice Due Date
                                            </td>
                                        </tr>
                                        @if($invoice->invoice_reference != null)
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right"> الرقم المرجعي للفاتورة </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ $invoice->invoice_reference ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;padding-left:10px;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="left" class="text-left">Invoice Reference
                                            </td>
                                        </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif padding-left:5px;"
                                                width="30%">Invoice No
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ $invoice->invoice_number ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right"><small><strong>رقم
                                                        الفاتورة</strong> </small></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif padding-left:5px;"
                                                width="30%">Invoice Issue Date
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_date)) ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right"> تاريخ إصدار الفاتورة
                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif padding-left:5px;"
                                                width="30%">Invoice Due Date
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ date('d/m/Y', strtotime($invoice->invoice_due_date)) ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right">تاريخ استحقاق الفاتورة
                                            </td>
                                        </tr>
                                        @if($invoice->invoice_reference != null)
                                        <tr>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif padding-left:5px;"
                                                width="30%">Invoice Reference
                                            </td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="40%" align="center"
                                                class="text-center">{{ $invoice->invoice_reference ?? '' }}</td>
                                            <td style="padding-bottom:0px !important;padding-top:0px !important;@if(!is_null($supplier)) font-size:10px; @else font-size:12px; @endif "
                                                width="30%" align="right" class="text-right"><small><strong>الرقم المرجعي للفاتورة</strong> </small></td>
                                        </tr>
                                        @endif
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <div class="table-responsive">
                        <table class="invoice-table">
                            <thead>
                            @php
                                $bg_color = $company_detail->store_invoice_color!=''?$company_detail->store_invoice_color:'#002235';
                            @endphp
                            @php
                                $total_product_discount = 0
                            @endphp
                            @foreach($invoice_products as $rs)
                                @php $total_product_discount += $rs->discount_amount @endphp
                            @endforeach
                            <tr>
                                <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                    align="center" class="text-center">Item Name <br> اسم الصنف
                                </th>
                                <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                    align="center" class="text-center">Unit Price <br> سعر الوحدة
                                </th>
                                <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                    align="center" class="text-center">Quantity <br> الكمية
                                </th>
                                @if($store_detail->vat_number != '')
                                    <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                        align="center" class="text-center">Taxable Amount <br> المبلغ الخاضع
                                        للضريبة
                                    </th> @endif
                                @if($total_product_discount > 0)
                                    <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                        align="center" class="text-center">Discount <br> الخصم
                                    </th>
                                @endif
                                @if($store_detail->vat_number != '')
                                    <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                        align="center" class="text-center">Tax Rate <br> نسبة الضريبة
                                    </th>
                                    <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                        align="center" class="text-center">Tax Amount <br> مبلغ الضريبة
                                    </th>
                                @endif
                                <th style="font-size:12px;font-weight:light;background: {{ $bg_color }};"
                                    align="center" class="text-center">Items Subtotal <br>المجموع الفرعي
                                </th>
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
                                    $product_description = "<br><small>";
                                    $parts = str_split($rs->description, 50);
                                    $product_description .= implode("<br>", $parts);
                                    /*if(Str::length($rs->description) >= 50){
                                        $product_description .= Str::substr($rs->description,0,50)."<br>".Str::substr($rs->description,50);
                                      }else{
                                          $product_description .= $rs->description;
                                      }*/
                                      $product_description .= "</small>";
                                @endphp
                                <tr>
                                    <td class="text-center" align="center">
                                        {{ Str::limit($rs->name,50,'...')  }}
                                        @if($rs->product_type == 1 && $rs->description != null && in_array($rs->show_description_in,[4,5,6]) )
                                            {!! $product_description !!}
                                        @elseif($rs->product_type == 2 && $rs->description != null)
                                            {!! $product_description !!}
                                        @endif
                                    </td>
                                    <td class="text-center" align="center">{{  (round($rs->amount_excluding_tax) == $rs->amount_excluding_tax) ? number_format($rs->amount_excluding_tax,0) : number_format($rs->amount_excluding_tax,2)  }}</td>
                                    <td class="text-center" align="center">{{ $rs->quantity }}</td>
                                    @if($store_detail->vat_number != '')
                                        <td class="text-center" align="center">{{ (round($product_amount) == $product_amount) ? number_format($product_amount,0) : number_format($product_amount,2)  }}</td>
                                    @endif
                                    @if($total_product_discount > 0)
                                        <td class="text-center" align="center">{{ (round($rs->discount_amount) == $rs->discount_amount) ? number_format($rs->discount_amount,0) : number_format($rs->discount_amount,2)   }}</td>
                                    @endif

                                    @if($store_detail->vat_number != '')
                                        <td class="text-center" align="center">{{ $rs->tax_percentage  }}</td>
                                        <td class="text-center" align="center">{{ (round($rs->tax_amount) == $rs->tax_amount) ? number_format($rs->tax_amount,0) : number_format($rs->tax_amount,2)  }}</td>
                                    @endif
                                    <td class="text-center" align="center">
                                        {{ (round($rs->total_amount) == $rs->total_amount) ? number_format($rs->total_amount,0) : number_format($rs->total_amount,2) }}
                                    </td>
                                </tr>
                            @empty

                            @endforelse

                            </tbody>
                        </table>
                    </div>
                    <div class="invoice-row justify-content-between">
                        <div class="invoice-col invoice-col-5">
                            <div class="table-responsive">
                                <table class="invoice-text-table">
                                    <tbody>
                                    @if(App::getLocale() == 'ar')
                                        <tr>
                                            <td style="font-weight: bold;"> اسم البنك Bank Name</td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->bank_name) ? $store_detail->bank_name : ''}}</td>

                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top"> رقم الآيبان
                                                IBAN Number
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->iban_number) ?  $store_detail->iban_number : '' }}</td>

                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top"> الاسم
                                                Name
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->account_holder_name) ? $store_detail->account_holder_name : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top"> الملاحظات
                                                Notes
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                @if(isset($invoice->terms) && $invoice->terms!='' && $invoice->terms!='null')
                                                    {{ $invoice->terms }}
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="font-weight: bold;"> Bank Name اسم البنك</td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->bank_name) ? $store_detail->bank_name : ''}}</td>

                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top"> IBAN Number
                                                رقم الآيبان
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->iban_number) ?  $store_detail->iban_number : '' }}</td>

                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top">Name الاسم
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ isset($store_detail->account_holder_name) ? $store_detail->account_holder_name : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;" class="white-space" valign="top">Notes
                                                الملاحظات
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if(isset($invoice->terms) && $invoice->terms!='' && $invoice->terms!='null')
                                                    {{ $invoice->terms }}
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-col invoice-col-6">
                            <div class="table-responsive">
                                <table class="invoice-text-table">
                                    <tbody>
                                    <tr>
                                        @if(App::getLocale() == 'ar')
                                            <td align="center"> المجموع الفرعي <br> Total (Excluding VAT)</td>
                                        @else
                                            <td align="center">Total (Excluding VAT) <br> المجموع الفرعي </td>
                                        @endif
                                        <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif>
                                            @php
                                                echo (round($subtotal) == $subtotal) ? number_format($subtotal,0) : number_format($subtotal,2);
                                            @endphp
                                        </td>
                                    </tr>
                                    @if($invoice->total_discount_amount > 0)
                                        <tr>
                                            @if(App::getLocale() == 'ar')
                                                <td  align="center" > مجموع الخصم <br> Discount </td>
                                            @else
                                                <td  align="center" > Discount <br> مجموع الخصم  </td>
                                            @endif
                                            <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif>
                                                @php
                                                    echo (round($invoice->total_discount_amount) == $invoice->total_discount_amount) ? number_format($invoice->total_discount_amount,0) : number_format($invoice->total_discount_amount,2);
                                                @endphp
                                            </td>
                                        </tr>
                                    @endif
                                    @if($store_detail->vat_number != '')
                                        <tr>
                                            @if(App::getLocale() == 'ar')
                                                <td align="center"> الإجمالي غير شامل الضريبة <br>
                                                    Total Taxable Amounts (Excl. VAT)
                                                </td>
                                            @else
                                                <td align="center" class="">Total Taxable Amount (Excl. VAT) <br>
                                                    الإجمالي غير شامل الضريبة
                                                </td>
                                            @endif
                                            <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif>
                                                @php
                                                    echo (round($subtotal - $invoice->total_discount_amount) == $subtotal - $invoice->total_discount_amount) ? number_format($subtotal - $invoice->total_discount_amount,0) : number_format($subtotal - $invoice->total_discount_amount,2);
                                                @endphp
                                            </td>
                                        </tr>
                                    @endif
                                    @php $total_including_charges = $invoice->total_after_discount @endphp

                                    @forelse($invoice->invoiceCharges as $rs)

                                        @php
                                            $total_including_charges += $rs->amount
                                        @endphp

                                        <tr>
                                            <td @if(App::getLocale() == 'ar') align="center"
                                                @else align="center" @endif>{{  $rs->name }}</td>
                                            <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif>
                                                @php
                                                    echo (round($rs->amount) == $rs->amount) ? number_format($rs->amount,0) : number_format($rs->amount,2);
                                                @endphp
                                            </td>
                                        </tr>

                                    @empty

                                    @endforelse
                                    @if($store_detail->vat_number != '')
                                        <tr>
                                            @if(App::getLocale() == 'ar')
                                                <td  align="center"> مجموع ضريبة القيمة المضافة <br> Total VAT </td>
                                            @else
                                                <td  align="center"> Total VAT <br> مجموع ضريبة القيمة المضافة  </td>
                                            @endif
                                            <td @if(App::getLocale() == 'en') align="right" @else align="left" @endif >
                                                @php
                                                    echo (round($total_product_tax) == $total_product_tax) ? number_format($total_product_tax,0) : number_format($total_product_tax,2);
                                                @endphp
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="total-tr">
                                        @if(App::getLocale() == 'ar')
                                            <td style="font-weight: bold;font-size:15px;" align="center" >
                                                إجمالي المبلغ المستحق <br> Total Amount Due
                                            </td>
                                        @else
                                            <td style="font-weight: bold;font-size:15px;" align="center">
                                                Total Amount Due <br>  إجمالي المبلغ المستحق
                                            </td>
                                        @endif

                                        <td style="font-weight: bold;font-size:15px;"
                                            @if(App::getLocale() == 'en') align="right" @else align="left" @endif >
                                            @php
                                                echo (round($total_including_charges + $total_product_tax) == $total_including_charges + $total_product_tax) ? number_format($total_including_charges + $total_product_tax,0) : number_format($total_including_charges + $total_product_tax,2);
                                            @endphp
                                            </span> {{ $invoice->currency_code }}</td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-footer" style="position: absolute;bottom:5px;width:100%;">
                <div>
                    @if(App::getLocale() == 'en')
                        <p> POWERED BY WOSUL | مطوَّر من قِبَل وصول </p>
                    @else
                        <p> مطوَّر من قِبَل وصول | POWERED BY WOSUL </p>
                    @endif

                </div>
            </div>


        </div>

        
    </div>
    @if($expresspay_transaction_permission)
    <div class="row mt-5">
        <div class="col-3"></div>
        <div class="col-6 border border-1">

            <h5 class="py-3">Express Pay Transactions</h5>

            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Created At</th>
                        <th>Paid At</th>
                        <th>Amount</th>
                    <th class="text-right">Payment Link</th>
                </tr>
            </thead>
            @if(isset($invoice->expresspay_transactions))
            @foreach($invoice->expresspay_transactions as $transaction)
            @php 
                $transaction = new ExpresspayResource($transaction);
                $transaction = $transaction->toArray($transaction);

            @endphp 
            <tbody>
                <tr>
                    <td>{{ $transaction['created_at_label'] }}</td>
                    <td>{{ $transaction['paid_at_label'] }}</td>
                    <td>{{ $transaction['amount'] }} SAR</td>
                    <td class="text-right"> 
                        @if($transaction['status']['value'] == 0)
                            <button class="btn btn-primary btn-sm sms-btn" id="@php echo $transaction['id']; @endphp">Send Transaction Link SMS</button>
                            <a target="_blank" href="{{ $transaction['payment_link'] }}" class="btn btn-sm btn-warning">  Pay Now</a>
                            {{-- <input type="hidden" value="{{ $transaction['payment_link'] }}" id="payment_link">  --}}
                            {{-- <button class="btn btn-primary btn-sm " onClick="copyToClipboard()"  >Copy text</button> --}}
                        @else 
                            {{ $transaction['status']['label'] }} 
                        @endif  
                    </td>
                </tr>
            </tbody>
            @endforeach
            @else 
            <tr>
                <td colspan="5">No expresspay transactions..</td>
            </tr>
            @endif
        </table>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
    {{-- <script src="{{ asset('js/pages/invoice_return.js') }}"></script> --}}

    <script>
        
        $(".download_pdf").text("{{ __("Download Now") }}");

        function printReceipt() {

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><title>' + document.title + '</title>');
            mywindow.document.write('<style>');
            mywindow.document.write('a,body{color:#111}.invoice-text-table .total-tr,.invoice-wrap{font-size:12px}body{margin:15px;font-family:Roboto,sans-serif}*{box-sizing:border-box}img{max-width:100%;height:auto;width:auto}h1,h2,h3,h4,h5,h6,ol,p,ul{margin-top:0}.invoice-wrap{position:relative;margin:0 auto;max-width:940px;padding:0}.invoice-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}.invoice-header .invoice-header-logo{max-width:180px;margin-bottom:20px;min-height:70px}.invoice-header .invoice-header-logo img{max-height:70px}.invoice-header-right{text-align:left}.invoice-header h2{margin:0 0 3px;text-align:left;font-size:18px;text-transform:uppercase}.invoice-header h5{font-size:14px;font-weight:400;margin:0}.invoice-header-right p{margin:0 0 8px}.invoice-header-left p:last-child{margin-bottom:0}.invoice-header h3{margin:0 0 5px;font-size:22px}.invoice-header h3 small{font-weight:400;font-size:60%}.border-line{height:4px;background:linear-gradient(240.13deg,#be0683 -3.35%,#3e63ca 103.16%);margin-top:25px}.invoice-text-table{width:100%;border:0;line-height:130%}.invoice-text-table td,.invoice-text-table th{padding:4px 0;border:0!important;font-size:12px}table.invoice-table,table.invoice-table td,table.invoice-table th,table.invoice-table tr{border:1px solid #ddd}.invoice-text-table th{text-align:left;padding-right:20px}.seller-address{max-width:280px;line-height:160%}.justify-content-between{justify-content:space-between}.justify-content-end{justify-content:flex-end}.invoice-row{display:flex;margin:0 -15px}.invoice-row .invoice-col{padding:0 15px;flex-grow:1}.invoice-col-5{width:40%;max-width:40%}.invoice-col-6{width:50%;max-width:50%}.invoice-col-7{width:60%;max-width:60%}table.invoice-table{width:100%;border-collapse:collapse;border-spacing:0}table.invoice-table th{padding:6px 5px;background:#002235;color:#fff;font-weight:500}table.invoice-table td{padding:8px 5px;font-size: 12px}table.invoice-table .col-width-15{width:15%}table.invoice-table .col-width-20{width:20%}table.invoice-table .col-width-30{width:30%}.table-responsive{overflow:auto;margin-bottom:20px}table.invoice-table tbody tr:nth-child(2n+2){background:#f8f8f8}.head-sub{text-align:center;font-size:16px;margin:20px 0 8px}.invoice-footer-row{display:flex;justify-content:space-between}.footer-signature p{font-size:16px;margin:0;font-weight:500;padding:12px 20px 0;text-transform:uppercase}.invoice-footer{margin-top:12px;text-align:center;font-size:10px;padding-top:10px}.footer-signature-img{min-height:110px}.qr-wrap{display:flex;align-items:center}.qr-wrap .qr_code{margin-right:15px;width:120px;background:#fff;padding:7px;border-radius:8px;border:4px solid #efefef}.qr-wrap .qr_code svg{display:block;width:100%;height:auto} table th{ border-top : 1px solid #ddd!important;} table tr td:nth-child(2) {border-left: 0px solid #fff!important;}');
            mywindow.document.write('</style>');
            mywindow.document.write('</head><body>');
            mywindow.document.write(document.getElementById('invoiceWrapper').innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }

        function download_pdf() {
            
            $(".download_pdf").text("Processing for Download");
            $(".download_pdf").attr("disabled", true);
            // var from_date = document.getElementById('from_date').value;
            // var to_date = document.getElementById('to_date').value;
            // var access_token= window.settings.access_token;
            // let invoice_status = document.getElementById('invoice_status').value;
            let formData = {
                // "from_date" :from_date,
                // "to_date" :to_date,
                // "access_token" :access_token,
                // "invoice_status":invoice_status
            }
            // document.getElementById('processing_pdf').innerHTML="Processing..";

            $.ajax({
                url: '/invoices/generate_pdf/urfxwjEQeC',
                type: "get",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log("response");
                    console.log(response);
                    if (response.status_code == 200) {
                        console.log(response.status_code);
                        // document.getElementById('processing_pdf').innerHTML="";

                        if (typeof response.link != 'undefined' && response.link != "") {
                            console.log(response.link);
                            const link = document.createElement('a');
                            link.href = response.link;
                            document.body.appendChild(link);
                            console.log(link);
                            //link.click();
                            window.open(link, '_blank');
                            console.log("done");

                        } else {
                            // location.reload();
                        }
                    } else {
                        document.getElementById('processing_pdf').innerHTML = response.msg;
                        console.log('Error')
                    }
                    $(".download_pdf").text("{{ __("Download Now") }}");
                    $(".download_pdf").attr("disabled", false);
                }
            });
        }

        function copyToClipboard() {
            // navigator.clipboard.readText().then(
            //   (clipText) => document.querySelector("#payment_link").innerText = clipText);

            // let text = document.getElementById('payment_link').innerHTML;
            // navigator.clipboard.writeText(text);

            // document.getElementById("payment_link").value.select();
            // document.getElementById("payment_link").value.select();
            // document.execCommand('copy');
            // alert(0);
            // new Clipboard('#payment_link');
        }
        $(document).on('click','.sms-btn',function (){
            var that = $(this);
            that.text("Sending...");
            that.attr("disabled", true);
            var transaction_id = that.attr('id');
            let formData = {
                "access_token" : window.settings.access_token,
                "transaction_id" :transaction_id
            }
            $.ajax({
                url: '/api/send_transaction_sms',
                type: "post",
                data: formData,
                success: function (response) {
                    console.log("response");
                    console.log(response);
                    if (response.status_code == 200) {
                        console.log(response.status_code);
                        // document.getElementById('processing_pdf').innerHTML="";
                    } else {
                        document.getElementById('processing_pdf').innerHTML = response.msg;
                        console.log('Error')
                    }
                    that.text("{{ __("Send Transaction Link SMS") }}");
                    that.attr("disabled", false);
                }
            });
        });

    </script>
@endpush