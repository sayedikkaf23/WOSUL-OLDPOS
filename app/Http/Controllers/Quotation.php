<?php

namespace App\Http\Controllers;

use App\Models\CustomerAdditionalInfo;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Quotation as QuotationModel;
use App\Models\Country as CountryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\QuotationProduct;
use App\Models\Product as ProductModel;
use App\Http\Resources\QuotationResource;
use App\Models\Store;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\Measurement as MeasurementModel;


use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Account as AccountModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Store as StoreModel;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User as UserModel;
use App\Models\QuotationCharge as QuotationChargeModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Category as CategoryModel;
use App\Http\Resources\TaxcodeResource;
use App\Http\Resources\QuotationReturnResource;
use DOMDocument;
use Illuminate\Support\Facades\Response;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class Quotation extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('quotation.quotations', $data);
    }


    //This is the function that loads the add/edit page
    public function add_quotation(Request $request, $slack = null){ 
        
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        $data['action_key'] = ($slack == null)?'A_ADD_QUOTATION':'A_EDIT_QUOTATION';
        check_access(array($data['action_key']));

        $data['currency_list'] = CountryModel::select('currency_code', 'currency_name')
        ->where('currency_code', '!=', '')
        ->whereNotNull('currency_code')
        ->active()
        ->groupBy('currency_code')
        ->get();

        $data['country_list'] = CountryModel::select('id as country_id', 'name', 'code')
        ->active()
        ->groupBy('code')
        ->get();

        $data['tax_options'] = MasterTaxOptionModel::select('tax_option_constant', 'label')
        ->active()
        ->get();

        $data['quotation_data'] = null;
        $data['category_data'] = [];

        if(isset($slack)){
            
            $invoice = QuotationModel::where('slack', '=', $slack)->first();

            if($invoice->bill_to == "CUSTOMER"){
                $data['suppliers_or_customers'] = Customer::active()->get();
            }else{
                $data['suppliers_or_customers'] = Supplier::active()->get();
            }

            if (empty($invoice)) {
                abort(404);
            }

            $quotation_data = new QuotationResource($invoice);
            // foreach($quotation_data['products']->pluck('product_id') as $rs){
            // $quotation_data['products']->put('is_taxable',$is_taxable);
            
            $quotation_products_data = [];
            foreach($quotation_data['products'] as $rs){
               $dataset = $rs;
               if($rs['product_id'] != ""){
                $is_taxable =  ProductModel::where('id',$rs['product_id'])->first()->is_taxable;
               }else{
                $is_taxable = 1;
               }               
               $dataset['is_taxable'] = $is_taxable;
               $quotation_products_data[] = $dataset;
            }
    
            $data['quotation_data'] = $quotation_data; 
            $data['quotation_data']['products'] = $quotation_products_data;

            $data['category_data'] = CategoryModel::parentCategory()->active()->where('store_id', $request->logged_user_store_id)->get();

        }

        $query = TaxcodeModel::select('tax_codes.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
        ->statusJoin()
        ->createdUser()
        ->get();
        
        $data['tax_codes'] = TaxcodeResource::collection($query);
        
        // products not associated with any supplier 
        $data['product_data'] = [];
        $data['subcategory_data'] = [];
        $data['store_data'] = UserModel::with('store')->where('id',session('user_id'))->first()->store;

        $data['main_categories'] = CategoryModel::select('id','slack','category_code', 'label')->parentCategory()->sortLabelAsc()->active()->get();
        return view('quotation.add_quotation', $data);
    }

    public function detail($slack){

        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        $data['action_key'] = 'A_DETAIL_QUOTATION';
        check_access([$data['action_key']]);

        $print_logo_path = config("app.invoice_print_logo");

        $quotation = QuotationModel::where('slack', '=', $slack)->first();
        
        if (empty($quotation)) {
            abort(404);
        }

        $data['transaction_type'] = 'INCOME';

        $data['accounts'] = AccountModel::select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')
        ->masterAccountTypeJoin()
        ->active()
        ->get();

        $data['payment_methods'] = PaymentMethodModel::select('slack', 'label')
        ->active()
        ->get();


        $store_data = StoreModel::select('currency_name', 'currency_code')
        ->where([
            ['stores.id', '=', request()->logged_user_store_id]
        ])
        ->active()
        ->first();

        $quotation_data = new QuotationResource($quotation);
        
        $data['quotation_data'] = $quotation_data;
        
        $quotation_statuses = [];
        
        if(check_access(['A_EDIT_STATUS_QUOTATION'] ,true)){
            $quotation_statuses = MasterStatusModel::select('label','value_constant')->where([
                ['value_constant', '!=', strtoupper('NEW')],
                ['key', '=', 'QUOTATION_STATUS'],
                ['status', '=', '1']
            ])->active()->orderBy('value', 'asc')
            ->where('value_constant','!=','PAID')
            ->get();
        }

        $data['quotation_statuses'] = $quotation_statuses;

        $data['currency_codes'] = [
            'store_currency' => $store_data->currency_code,
            'quotation_currency' => $quotation_data->currency_code
        ];

        $data['delete_quotation_access'] = check_access(['A_DELETE_QUOTATION'] ,true);

        $data['make_payment_access'] = check_access(['A_MAKE_PAYMENT_QUOTATION'] ,true);

        if(!Storage::disk('invoice')->has('quotation_'.$slack.".pdf")){
            Storage::disk('invoice')->delete('quotation_'.$slack.".pdf");   
        }

        // For Quotation Print
        $data['quotation'] = QuotationModel::where('slack', '=', $slack)->first();
        
        if (empty($data['quotation'])) {
            abort(404);
        }
        
        $data['quotation_products'] = QuotationProduct::where('quotation_id',$data['quotation']->id)->orderBy('product_id','ASC')->get();

        if($data['quotation']->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($data['quotation']->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($data['quotation']->bill_to_id);
        }

        if(isset($data['supplier']->country_id))
        {
               $country_details = CountryModel::select('*')->find($data['supplier']->country_id);
               if(isset($country_details->name))
               {
                     $supplier_country_name = $country_details->name;
               }
               else
               {
                $supplier_country_name = '';
               }
               $data['supplier']['country_name'] = $supplier_country_name;
        }

        
        
        $data['store_detail'] = StoreModel::select('*')->find($quotation->store_id);
        if(isset($data['store_detail']->country_id))
        {
               $country_details = CountryModel::select('*')->find($data['store_detail']->country_id);
               if(isset($country_details->name))
               {
                     $country_name = $country_details->name;
               }
               else
               {
                $country_name = '';
               }
               $data['store_detail']['country_name'] =$country_name;
        }

        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($data['store_detail']->store_logo);
            $data['relative_logo_path'] = asset('storage/'.session('merchant_id').'/store/'.$data['store_detail']->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $data['company_detail'] = StoreModel::select('*')->where('id',request()->logged_user_store_id)->first();

        $print_data = view('invoice.quotation.quotation_print_alt', [
            'logo_path' => json_encode($data['logo_path']),
            'relative_logo_path' => json_encode($data['relative_logo_path']),
            'quotation' => json_encode($data['quotation']), 
            'quotation_products' => json_encode($data['quotation_products']), 
            'supplier' => json_encode($data['supplier']),
            'store_detail' => json_encode($data['store_detail']),
            'pdf_path' => asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_quotation_'.$slack.'.pdf'),
        ])->render();

        $mpdf_config = [
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_left'   => 0,
            'margin_right'  => 0,
            'margin_top'    => 0,
            'margin_bottom' => 0,
            'tempDir' => storage_path()."/pdf_temp" 
        ];

        $stylesheet = File::get(public_path('css/invoice_alt_print.css'));
        $mpdf = new Mpdf($mpdf_config);
        $mpdf->curlAllowUnsafeSslRequests = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode(100);
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        // $mpdf->SetHTMLFooter('<div class="footer">Page: {PAGENO}/{nb}</div>');
        $mpdf->WriteHTML($print_data);
        $mpdf->Output('storage/'.session('merchant_id').'/invoice/temp_quotation_'.$slack.'.pdf', 'F');
        $data['pdf_path'] =  '../storage/'.session('merchant_id').'/invoice/temp_quotation_'.$slack.'.pdf';
        $data['qrcode_pdf_path'] =  asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_quotation_'.$slack.'.pdf');
        
      

        return view('quotation.quotation_detail', $data);

    }

    //This is the function that loads the print purchase order page
    public function print_quotation(Request $request, $slack){
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        check_access([$data['sub_menu_key']]);

        $quotation = QuotationModel::where('slack', '=', $slack)->first();
        
        if (empty($quotation)) {
            abort(404);
        }

        $quotation_data = new QuotationResource($quotation);

        if($quotation_data->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($quotation_data->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($quotation_data->bill_to_id);
        }
        $data['additional_infos'] = CustomerAdditionalInfo::select('title','description')->where('customer_id',$quotation_data->bill_to_id)->get();
        $data['store_detail'] = StoreModel::select('*')->find($quotation_data->store_id);
        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($data['store_detail']->store_logo);
            $data['relative_logo_path'] = asset('storage/'.session('merchant_id').'/store/'.$data['store_detail']->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $print_logo_path = config("app.invoice_print_logo");
        
        $print_data = view('quotation.invoice.quotation_print', [
            'data' => json_encode($quotation_data), 
            'logo_path' => json_encode($data['logo_path']),
            'relative_logo_path' => $data['relative_logo_path'],
            'supplier' => $data['supplier'],
            'additional_infos' => $data['additional_infos'],
            ]
            )->render();

        $mpdf_config = [
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_left'   => 7,
            'margin_right'  => 7,
            'margin_top'    => 7,
            'margin_bottom' => 7,
            'tempDir' => storage_path()."/pdf_temp" 
        ];

        $stylesheet = File::get(public_path('css/quotation_print_invoice.css'));
        $mpdf = new Mpdf($mpdf_config);
        $mpdf->curlAllowUnsafeSslRequests = true;
        $mpdf->SetDisplayMode('real');
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->SetHTMLFooter('<div class="footer">Page: {PAGENO}/{nb}</div>');
        $mpdf->WriteHTML($print_data);
        $mpdf->Output('quotation_'.$quotation_data['quotation_number'].'.pdf', \Mpdf\Output\Destination::INLINE);
    }

    //This is the function that loads the print purchase order page
    public function quotation_einvoice(Request $request, $slack){

        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        check_access([$data['sub_menu_key']]);

        $quotation = QuotationModel::where('slack', '=', $slack)->first();
        if (empty($quotation)) {
            abort(404);
        }
        $quotation_data = new QuotationResource($quotation);

        if($quotation_data->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($quotation_data->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($quotation_data->bill_to_id);
        }
        $data['additional_infos'] = CustomerAdditionalInfo::select('title','description')->where('customer_id',$quotation_data->bill_to_id)->get();
        $store_detail = StoreModel::select('*')->find($quotation_data->store_id);
        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($store_detail->store_logo);
            $data['relative_logo_path'] = asset('storage/'.session('merchant_id').'/store/'.$store_detail->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $print_logo_path = config("app.invoice_print_logo");
        
        // $print_data = view('quotation.invoice.quotation_einvoice', [
        //     'data' => json_encode($quotation_data), 
        //     'logo_path' => json_encode($data['logo_path']),
        //     'relative_logo_path' => $data['relative_logo_path'],
        //     'supplier' => $data['supplier'],
        //     'additional_infos' => $data['additional_infos'],
        //     ]
        //     )->render();
        $items_tax_components = [];
        // dd('-------------');
        $currency_code = $quotation_data->currency_code;

        $dom = new DOMDocument('1.0','utf-8');
        $dom->formatOutput = true;

            $invoice = $dom->createElement('Invoice');
            $invoice->setAttribute('xmlns','urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $invoice->setAttribute('xmlns:cac','urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $invoice->setAttribute('xmlns:cbc','urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $invoice->setAttribute('xmlns:ccts','urn:un:unece:uncefact:documentation:2');
            $invoice->setAttribute('xmlns:ext','urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
            // $invoice->setAttribute('xmlns:qdt','urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2');
            // $invoice->setAttribute('xmlns:udt','urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2');
            // $invoice->setAttribute('xmlns:xades','http://uri.etsi.org/01903/v1.3.2#');
            // $invoice->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
            // $invoice->setAttribute('xmlns:ds','http://www.w3.org/2000/09/xmldsig#');
            
            

            // $invoice->appendChild($dom->createElement('cbc:UBLVersionID','2.1'));
            $invoice->appendChild($dom->createElement('cbc:ProfileID','reporting:1.0'));
            $invoice->appendChild($dom->createElement('cbc:ID',$quotation_data->quotation_number));
            $invoice->appendChild($dom->createElement('cbc:UUID','16e78469-64af-406d-9cfd-895e724198f0'));
            $invoice->appendChild($dom->createElement('cbc:IssueDate',date('Y-m-d')));
            $invoice->appendChild($dom->createElement('cbc:IssueTime',date('h:i:s')));
                $InvoiceTypeCode = $dom->createElement('cbc:InvoiceTypeCode','388');
                $InvoiceTypeCode->setAttribute('name',"0100000");
            $invoice->appendChild($InvoiceTypeCode);
            $invoice->appendChild($dom->createElement('cbc:DocumentCurrencyCode',$currency_code));
            $invoice->appendChild($dom->createElement('cbc:TaxCurrencyCode',$currency_code));

            $AdditionalDocumentReference = $dom->createElement('cac:AdditionalDocumentReference');
                $AdditionalDocumentReference->appendChild($dom->createElement('cbc:ID','ICV'));
                $AdditionalDocumentReference->appendChild($dom->createElement('cbc:UUID',$quotation_data->quotation_number));
            $invoice->appendChild($AdditionalDocumentReference);

            $AdditionalDocumentReference = $dom->createElement('cac:AdditionalDocumentReference');
                $AdditionalDocumentReference->appendChild($dom->createElement('cbc:ID','PIH'));
                $Attachment = $dom->createElement('cac:Attachment');
                    $EmbeddedDocumentBinaryObject = $dom->createElement('cbc:EmbeddedDocumentBinaryObject','NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==');
                    $EmbeddedDocumentBinaryObject->setAttribute('mimeCode', 'text/plain');
                    $Attachment->appendChild($EmbeddedDocumentBinaryObject);
                $AdditionalDocumentReference->appendChild($Attachment);
            $invoice->appendChild($AdditionalDocumentReference);

            /*   Supplier/Wosul merchant store address details Start  */
            $AccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
            
                $Party = $dom->createElement('cac:Party');
                    $PartyIdentification = $dom->createElement('cac:PartyIdentification');
                        $ID_PartyIdentification = $dom->createElement('cbc:ID',8899298738923);
                        $ID_PartyIdentification->setAttribute('schemeID', 'CRN');
                        $PartyIdentification->appendChild($ID_PartyIdentification);
                    $Party->appendChild($PartyIdentification);

                    $PartyName = $dom->createElement('cac:PartyName');
                        $PartyName->appendChild($dom->createElement('cbc:Name',$store_detail->name));
                    $Party->appendChild($PartyName);

                    $PostalAddress = $dom->createElement('cac:PostalAddress');
                        $PostalAddress->appendChild($dom->createElement('cbc:StreetName',$store_detail->street_name));
                        $PostalAddress->appendChild($dom->createElement('cbc:BuildingNumber',$store_detail->building_number));
                        $PostalAddress->appendChild($dom->createElement('cbc:PlotIdentification',$store_detail->address));
                        $PostalAddress->appendChild($dom->createElement('cbc:CitySubdivisionName',$store_detail->district));
                        $PostalAddress->appendChild($dom->createElement('cbc:CityName',$store_detail->city));
                        $PostalAddress->appendChild($dom->createElement('cbc:PostalZone',$store_detail->pincode));
                        // $PostalAddress->appendChild($dom->createElement('cbc:CountrySubentity',$store_detail->district));
                        $Country = $dom->createElement('cac:Country');
                            $Country->appendChild($dom->createElement('cbc:IdentificationCode','SA'));
                        $PostalAddress->appendChild($Country);
                    $Party->appendChild($PostalAddress);

                    $PartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                        $PartyTaxScheme->appendChild($dom->createElement('cbc:RegistrationName',$store_detail->tax_registration_name));
                        $PartyTaxScheme->appendChild($dom->createElement('cbc:CompanyID',$store_detail->other_seller_id));
                        $TaxScheme = $dom->createElement('cac:TaxScheme');
                            $TaxScheme->appendChild($dom->createElement('cbc:ID',$store_detail->tax_code->tax_code));
                            // $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode',$store_detail->tax_code->tax_code));
                        $PartyTaxScheme->appendChild( $TaxScheme);
                    $Party->appendChild($PartyTaxScheme);

                    $PartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                        $PartyLegalEntity->appendChild($dom->createElement('cbc:RegistrationName',$store_detail->tax_registration_name));
                    $Party->appendChild($PartyLegalEntity);

                $AccountingSupplierParty->appendChild($Party);
            $invoice->appendChild($AccountingSupplierParty);
            /*   Supplier/Wosul merchant store address details End  */

            /*   Customer address details Start  */
            $AccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
                $Party = $dom->createElement('cac:Party');
                    $PartyIdentification = $dom->createElement('cac:PartyIdentification');
                        $ID_PartyIdentification = $dom->createElement('cbc:ID',$quotation_data->bill_to_id);
                        $ID_PartyIdentification->setAttribute('schemeID', 'CRN');
                        $PartyIdentification->appendChild($ID_PartyIdentification);
                    $Party->appendChild($PartyIdentification);

                    $PartyName = $dom->createElement('cac:PartyName');
                        $PartyName->appendChild($dom->createElement('cbc:Name',$quotation_data->bill_to_name));
                    $Party->appendChild($PartyName);

                    $PostalAddress = $dom->createElement('cac:PostalAddress');
                        $PostalAddress->appendChild($dom->createElement('cbc:StreetName',$store_detail->street_name));
                        $PostalAddress->appendChild($dom->createElement('cbc:BuildingNumber',$store_detail->building_number));
                        $PostalAddress->appendChild($dom->createElement('cbc:PlotIdentification',$store_detail->address));
                        $PostalAddress->appendChild($dom->createElement('cbc:CitySubdivisionName',$store_detail->district));
                        $PostalAddress->appendChild($dom->createElement('cbc:CityName',$store_detail->city));
                        $PostalAddress->appendChild($dom->createElement('cbc:PostalZone',$store_detail->pincode));
                        // $PostalAddress->appendChild($dom->createElement('cbc:CountrySubentity',$store_detail->district));
                        $Country = $dom->createElement('cac:Country');
                            $Country->appendChild($dom->createElement('cbc:IdentificationCode','SA'));
                        $PostalAddress->appendChild($Country);
                    $Party->appendChild($PostalAddress);

                    $PartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                        // $PartyTaxScheme->appendChild($dom->createElement('cbc:RegistrationName',$store_detail->tax_registration_name));
                        // $PartyTaxScheme->appendChild($dom->createElement('cbc:CompanyID',$quotation_data->bill_to_id));
                        $TaxScheme = $dom->createElement('cac:TaxScheme');
                            $TaxScheme->appendChild($dom->createElement('cbc:ID',$store_detail->tax_code->tax_code));
                            // $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode',$store_detail->tax_code->tax_code));
                        $PartyTaxScheme->appendChild( $TaxScheme);
                    $Party->appendChild($PartyTaxScheme);

                    $PartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                        $PartyLegalEntity->appendChild($dom->createElement('cbc:RegistrationName',$store_detail->tax_registration_name));
                    $Party->appendChild($PartyLegalEntity);

                $AccountingCustomerParty->appendChild($Party);
            $invoice->appendChild($AccountingCustomerParty);
            
            /*   Customer address details end  */
            
            $PaymentMeans = $dom->createElement('cac:PaymentMeans');
                $PaymentMeans->appendChild($dom->createElement('cbc:PaymentMeansCode',10)); // by cash
            $invoice->appendChild($PaymentMeans);

            $item_total_after_discount = $item_total_amount = 0;
            foreach ($quotation_data->products as $item_key => $quotation_products){
                $item_total_after_discount += $quotation_products->total_after_discount;
                $item_total_amount += $quotation_products->total_amount;
                $item_tax_code = '';
                if(isset($quotation_products->tax_code_id) && $quotation_products->tax_code_id > 0){
                    $tax_code_details = TaxcodeModel::find($quotation_products->tax_code_id);
                    $item_tax_code = $tax_code_details->tax_code;//$store_detail->tax_code->tax_code??'';
                    $items_tax_components[$item_key]['tax_code'] = $item_tax_code;
                    $items_tax_components[$item_key]['tax_label'] = $tax_code_details->label;
                    $items_tax_components[$item_key]['tax_percentage'] = $quotation_products->tax_percentage;
                    $items_tax_components[$item_key]['taxable_amount'] = $quotation_products->total_after_discount;
                    $items_tax_components[$item_key]['tax_amount'] = $quotation_products->tax_amount;
                }
            }
            $TaxTotal = $dom->createElement('cac:TaxTotal');
                $TaxAmount = $dom->createElement('cbc:TaxAmount',"$quotation_data->total_tax_amount");
                $TaxAmount->setAttribute('currencyID',$currency_code);
                $TaxTotal->appendChild($TaxAmount);

                foreach($items_tax_components as $tax_key => $tax_details){
                    $tax_amount = $tax_details['tax_amount'];
                    $taxable_amount = $tax_details['taxable_amount'];
                    $TaxSubtotal = $dom->createElement('cac:TaxSubtotal');
                        $TaxableAmount = $dom->createElement('cbc:TaxableAmount',"$taxable_amount");
                        $TaxableAmount->setAttribute('currencyID',$currency_code);
                        $TaxSubtotal->appendChild($TaxableAmount);
                        
                        $TaxAmount = $dom->createElement('cbc:TaxAmount',"$tax_amount");
                        $TaxAmount->setAttribute('currencyID',$currency_code);
                        $TaxSubtotal->appendChild($TaxAmount);
    
                        $TaxCategory = $dom->createElement('cac:TaxCategory');
                            if($tax_details['tax_percentage'] == 0 || $tax_details['tax_code'] == 'EXEMPT_TAX'){
                                $TaxCategory->appendChild($dom->createElement('cbc:ID','E'));
                            }elseif($tax_code_details->tax_code == 'ZERO_TAX'){
                                $TaxCategory->appendChild($dom->createElement('cbc:ID','Z'));
                            }else{
                                $TaxCategory->appendChild($dom->createElement('cbc:ID','S'));
                            }
                            $TaxCategory->appendChild($dom->createElement('cbc:Percent',$tax_details['tax_percentage']));
                            if($tax_details['tax_percentage'] == 0 && $tax_details['tax_code'] == 'EXEMPT_TAX'){
                                $TaxCategory->appendChild($dom->createElement('cbc:TaxExemptionReasonCode','VATEX-SA-35'));
                                $TaxCategory->appendChild($dom->createElement('cbc:TaxExemptionReason','Medicines and medical equipment'));
                            }
                            $TaxScheme = $dom->createElement('cac:TaxScheme');
                                $TaxScheme->appendChild($dom->createElement('cbc:ID',$store_detail->tax_code->tax_code));
                                $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode',$tax_details['tax_code']));
                            $TaxCategory->appendChild($TaxScheme);
    
                        $TaxSubtotal->appendChild($TaxCategory);
                    $TaxTotal->appendChild($TaxSubtotal);
                }

            $invoice->appendChild($TaxTotal);

            $TaxTotal = $dom->createElement('cac:TaxTotal');
                $TaxAmount = $dom->createElement('cbc:TaxAmount',"$quotation_data->total_tax_amount");
                $TaxAmount->setAttribute('currencyID',$currency_code);
            $TaxTotal->appendChild($TaxAmount);
            $invoice->appendChild($TaxTotal);
            
            $LegalMonetaryTotal = $dom->createElement('cac:LegalMonetaryTotal');
                $LineExtensionAmount = $dom->createElement('cbc:LineExtensionAmount',"$item_total_after_discount");
                $LineExtensionAmount->setAttribute('currencyID',$currency_code);
                $LegalMonetaryTotal->appendChild($LineExtensionAmount);

                $TaxExclusiveAmount = $dom->createElement('cbc:TaxExclusiveAmount',"$item_total_after_discount");
                $TaxExclusiveAmount->setAttribute('currencyID',$currency_code);
                $LegalMonetaryTotal->appendChild($TaxExclusiveAmount);

                $TaxInclusiveAmount = $dom->createElement('cbc:TaxInclusiveAmount',"$item_total_amount");
                $TaxInclusiveAmount->setAttribute('currencyID',$currency_code);
                $LegalMonetaryTotal->appendChild($TaxInclusiveAmount);

                $PayableAmount = $dom->createElement('cbc:PayableAmount',"0.00");
                $PayableAmount->setAttribute('currencyID',$currency_code);
                $LegalMonetaryTotal->appendChild($PayableAmount);
            $invoice->appendChild($LegalMonetaryTotal);

            foreach ($quotation_data->products as $item_key => $quotation_products){

                $store_tax_code = '';
                if(isset($quotation_products->tax_code_id) && $quotation_products->tax_code_id > 0){
                    $tax_code_details = TaxcodeModel::find($quotation_products->tax_code_id);
                    $store_tax_code = $store_detail->tax_code->tax_code??'';
                }
                
                $InvoiceLine = $dom->createElement('cac:InvoiceLine');
                    $InvoiceLine->appendChild($dom->createElement('cbc:ID',$item_key+1));

                    $InvoicedQuantity = $dom->createElement('cbc:InvoicedQuantity',$quotation_products->quantity);
                    $InvoicedQuantity->setAttribute('unitCode','PCE');
                    $InvoiceLine->appendChild($InvoicedQuantity);

                    $InvoicedQuantity = $dom->createElement('cbc:LineExtensionAmount',$quotation_products->total_after_discount);
                    $InvoicedQuantity->setAttribute('currencyID',$currency_code);
                    $InvoiceLine->appendChild($InvoicedQuantity);

                    $AllowanceCharge = $dom->createElement('cac:AllowanceCharge');
                        $AllowanceCharge->appendChild($dom->createElement('cbc:ChargeIndicator','false'));
                        $AllowanceCharge->appendChild($dom->createElement('cbc:AllowanceChargeReason','Discount'));

                        $Amount = $dom->createElement('cbc:Amount',$quotation_products->discount_amount);
                        $Amount->setAttribute('currencyID',$currency_code);
                        $AllowanceCharge->appendChild($Amount);
                    $InvoiceLine->appendChild($AllowanceCharge);

                    $TaxTotal = $dom->createElement('cac:TaxTotal');
                        $TaxAmount = $dom->createElement('cbc:TaxAmount',$quotation_products->tax_amount);
                        $TaxAmount->setAttribute('currencyID',$currency_code);
                        $TaxTotal->appendChild($TaxAmount);

                        $RoundingAmount = $dom->createElement('cbc:RoundingAmount',$quotation_products->total_amount);
                        $RoundingAmount->setAttribute('currencyID',$currency_code);
                        $TaxTotal->appendChild($RoundingAmount);
                    $InvoiceLine->appendChild($TaxTotal);

                    $Item = $dom->createElement('cac:Item');
                        $Item->appendChild($dom->createElement('cbc:Name',$quotation_products->name));

                        $ClassifiedTaxCategory = $dom->createElement('cac:ClassifiedTaxCategory');
                            if($quotation_products->tax_percentage == 0 && $tax_code_details->tax_code == 'EXEMPT_TAX'){
                                $ClassifiedTaxCategory->appendChild($dom->createElement('cbc:ID','E'));
                            }elseif($tax_code_details->tax_code == 'ZERO_TAX'){
                                $ClassifiedTaxCategory->appendChild($dom->createElement('cbc:ID','Z'));
                            }else{
                                $ClassifiedTaxCategory->appendChild($dom->createElement('cbc:ID','S'));
                            }
                            $ClassifiedTaxCategory->appendChild($dom->createElement('cbc:Percent',$quotation_products->tax_percentage));

                            $TaxScheme = $dom->createElement('cac:TaxScheme');
                            $TaxScheme->appendChild($dom->createElement('cbc:ID',$store_tax_code));
                            $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode',$store_tax_code));
                            $ClassifiedTaxCategory->appendChild($TaxScheme);
                        $Item->appendChild($ClassifiedTaxCategory);
                    $InvoiceLine->appendChild($Item);

                    $Price = $dom->createElement('cac:Price');
                        $PriceAmount = $dom->createElement('cbc:PriceAmount',$quotation_products->amount_excluding_tax);
                        $PriceAmount->setAttribute('currencyID',$currency_code);
                        $Price->appendChild($PriceAmount);
                    $InvoiceLine->appendChild($Price);
                $invoice->appendChild($InvoiceLine);
            }

        $dom->appendChild($invoice);
        $xml_data = $dom->saveXML();
        // $xml_data_encoded = base64_encode($xml_data);
        // echo $xml_data_encoded;
        echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $tempDir = storage_path()."/quotation/";
        //dd($quotation_data->store_id,$quotation_data->quotation_number);
        if(!File::exists($tempDir)) {
            File::makeDirectory($tempDir, $mode = 0777, true, true);
        }
        $file_name = $quotation_data->store_id.'_'.$quotation_data->quotation_number.'_quotation.xml';
        $dom->save($tempDir.$file_name) or die('XML Create Error');
        // if($dom->save($tempDir.$file_name)){
        //     $headers = array(
        //         'Content-Type: application/xml',
        //     );
        //     // return Response::download($tempDir, $file_name, $headers);
        //     return Storage::download($tempDir.$file_name);
        // }else{
        //     die('XML Create Error');
        // } 


            //  ***************  Fatoora Commands for validating XML ********************

            // fatoora validatexml -f 1_106_quotation_signed.xml -p 1_106_quotation_signed.xml
        // openssl dgst -verify PublicKey.pem -signature PublicKey.bin 1_106_quotation_signed.xml
        //  step 1:  fatoora -csr -csrConfig wosul.cnf -privateKey PrivateKey.pem -generatedCsr wosul.csr -pem
        //  step 2: fatoora -sign -invoice 312345678901233_20230201T150400_23555.xml -signedInvoice 312345678901233_20230201T150400_23555.xml
        // 
        // fatoora -qr -invoice 1_106_quotation_signed_api_request.xml
        // step 3:  fatoora -validate -invoice 312345678901233_20230201T150400_23555.xml
        // fatoora -generateHash -validate -invoice 1_106_quotation_signed.xml
        // step 4:  fatoora -invoice 312345678901233_20230201T150400_23555.xml -invoiceRequest -apiRequest 312345678901233_20230201T150400_23555_signed_api_request.xml
            // nezQnaz6xkhu8sl6xwpm2W7BEY3Xp7a0CeVXZRJ4yb8=
            // nezQnaz6xkhu8sl6xwpm2W7BEY3Xp7a0CeVXZRJ4yb8=
            // 3ryDY6KBzmtqIPcfH0nOY26HDXnP4v6vJ08fWY74oBw=
            // J8f2BYvol1eu6akura2DANi5B7Xdb3icdje9dV1jFsk=
            // JEOabYlACQkYvYePuZ72nO8vOYAVL3PcviRIsGp2nnk=    
            // MNRYwSMjoOV8q4R271w4HmbIMPZF+oGlzsABCLniqFk=
            // CLxVdb6vkEetaWeIq7/zipf59dRBdfDVlHpxJdv281g=
            // EoGd8G4J5SYoNxKYMOB0Chhda1KG39KflY8bKYv1g28=
            // t6fydOD2Zn8jadVUJoE6/+yYiB6qG8cQA7aK0B5fKc0=
            // g3sqk9Q7U013HohNJOKgJ/mcafKUFP/zi7g/aAUlGs4=
            // QHTStgIOodRRjIXX+6WWXPCRAKOmZsBdDh2F8pAeLSs=
            // b3Gl3E7uv15sl8kZpWK55uXAdKHDDCQnTGPoVqnMe7Y=
            
    }

    public function print_quotation_alt(Request $request, $slack){
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_QUOTATIONS';
        check_access([$data['sub_menu_key']]);

        $quotation = QuotationModel::where('slack', '=', $slack)->first();
        
        if (empty($quotation)) {
            abort(404);
        }

        $data = new QuotationResource($quotation);

        $quotation_products = QuotationProduct::where('quotation_id',$data->resource->id)->get();
        return view('quotation.invoice.quotation_print_alt',compact('data','quotation_products'));

    }
    
}
