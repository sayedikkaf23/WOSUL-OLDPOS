@extends('layouts.custom_layout')

@section("content")
<div class="alert alert-primary">
         @if(App::getLocale()!="ar")
          <p class="text-left">Hello Dear Merchant,<br />
We would like to inform you that Simplified  tax invoices are released if an invoice to customer option is selected. Tax invoices are released if an invoice to company  option is selected.</p>
         @else
          <p class="mt-2 text-right">أهلًا تاجرنا العزيز<br />نود إعلامك بأن الفواتير الضريبية المبسطة تحرر اذا تم تحديد خيار فاتورة الى عميل تحرر الفواتير الضريبة اذا تم تحديد خيار فاتورة الى منشأة</p>  
         @endif        
</div>
<addinvoicecomponent :currency_list="{{ json_encode($currency_list) }}" :country_list="{{ json_encode($country_list) }}" :invoice_data="{{ json_encode($invoice_data) }}" :store_data="{{ json_encode($store_data) }}" :tax_options="{{ json_encode($tax_options) }}" :product_data="{{ json_encode($product_data) }}" :session_currency_code=" {{ json_encode($session_currency_code)  }}" :session_currency_name=" {{ json_encode($session_currency_name)  }}" :invoice_data_charges=" {{ json_encode($invoice_data_charges)  }} " :tax_codes=" {{ json_encode($tax_codes)  }} " :category_data=" {{ json_encode($category_data)  }} "></addinvoicecomponent>
@endsection
