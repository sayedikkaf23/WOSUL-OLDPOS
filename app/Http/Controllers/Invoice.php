<?php

namespace App\Http\Controllers;

use App\Models\CustomerAdditionalInfo;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Invoice as InvoiceModel;
use App\Models\InvoiceProduct;
use App\Models\Country as CountryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Account as AccountModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Store as StoreModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User as UserModel;
use App\Models\Product as ProductModel;
use App\Models\InvoiceCharge as InvoiceChargeModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Category as CategoryModel;
use App\Http\Resources\TaxcodeResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoiceReturnResource;
use App\Models\Expresspay;
use App\Models\Measurement as MeasurementModel;
use App\Models\InvoiceReturn as InvoiceReturnModel;
use App\Models\InvoiceReturnProducts as InvoiceReturnProductsModel;

use Mpdf\Mpdf;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Stripe\TaxCode;
use Illuminate\Support\Carbon;
use App\Models\InvoiceReturn;
use App\Models\InvoiceReturnProducts;

class Invoice extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){

        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('invoice.invoices', $data);
    }

    //This is the function that loads the add/edit page
    public function add_invoice(Request $request, $slack = null){ 
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        $data['action_key'] = ($slack == null)?'A_ADD_INVOICE':'A_EDIT_INVOICE';
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

        $data['invoice_data'] = null;
        $data['invoice_data_charges'] = [];
        $data['category_data'] = [];

        if(isset($slack)){
            
            $invoice = InvoiceModel::where('slack', '=', $slack)->first();

            if($invoice->bill_to == "CUSTOMER"){
                $data['suppliers_or_customers'] = Customer::active()->get();
            }else{
                $data['suppliers_or_customers'] = Supplier::active()->get();
            }

            if (empty($invoice)) {
                abort(404);
            }

            $invoice_data = new InvoiceResource($invoice);
            // foreach($invoice_data['products']->pluck('product_id') as $rs){
            // $invoice_data['products']->put('is_taxable',$is_taxable);
            
            $invoice_products_data = [];
            foreach($invoice_data['products'] as $rs){
               $dataset = $rs;
               if($rs['product_id'] != ""){
                $is_taxable =  ProductModel::where('id',$rs['product_id'])->first()->is_taxable;
               }else{
                $is_taxable = 1;
               }               
               $dataset['is_taxable'] = $is_taxable;
               $invoice_products_data[] = $dataset;
            }
    
            $data['invoice_data'] = $invoice_data; 
            $data['invoice_data']['products'] = $invoice_products_data;

            $data['invoice_data_charges'] = InvoiceChargeModel::where('invoice_id',$invoice->id)->get();
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
        return view('invoice.add_invoice', $data);
    }

    public function detail($slack){
        
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access([$data['sub_menu_key']]);

        $print_logo_path = config("app.invoice_print_logo");

        $invoice = InvoiceModel::where('slack', '=', $slack)->first();
        
        if (empty($invoice)) {
            abort(404);
        }

        $data['transaction_type'] = 'INCOME';

        $data['accounts'] = AccountModel::select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')
        ->masterAccountTypeJoin()
        ->active()
        ->get();

        $data['payment_methods'] = PaymentMethodModel::select('slack', 'label')
        ->active()
        // ->where('PAYMENT_CONSTANT','!=','EXPRESSPAY')
        ->get();


        $store_data = StoreModel::select('currency_name', 'currency_code')
        ->where([
            ['stores.id', '=', request()->logged_user_store_id]
        ])
        ->active()
        ->first();

        $invoice_data = new InvoiceResource($invoice);
        
        $data['invoice_data'] = $invoice_data;
        
        $invoice_statuses = [];
        
        if(check_access(['A_EDIT_STATUS_INVOICE'] ,true)){
            $invoice_statuses = MasterStatusModel::select('label','value_constant')->where([
                ['value_constant', '!=', strtoupper('NEW')],
                ['key', '=', 'INVOICE_STATUS'],
                ['status', '=', '1']
            ])->active()->orderBy('value', 'asc')
            ->where('value_constant','!=','PAID')
            ->get();
        }

        $data['invoice_statuses'] = $invoice_statuses;

        $data['currency_codes'] = [
            'store_currency' => $store_data->currency_code,
            'invoice_currency' => $invoice_data->currency_code
        ];

        $data['delete_invoice_access'] = check_access(['A_DELETE_INVOICE'] ,true);

        $data['make_payment_access'] = check_access(['A_MAKE_PAYMENT_INVOICE'] ,true);

        if(!Storage::disk('invoice')->has('invoice_'.$slack.".pdf")){
            Storage::disk('invoice')->delete('invoice_'.$slack.".pdf");   
        }

        // For Invoice Print
        $data['invoice'] = InvoiceModel::with('invoiceCharges')->where('slack', '=', $slack)->first();
        
        if (empty($data['invoice'])) {
            abort(404);
        }
        
        $data['invoice_products'] = InvoiceProduct::where('invoice_id',$data['invoice']->id)->orderBy('product_id','ASC')->get();
        $data['customer_add_infos'] = CustomerAdditionalInfo::where('customer_id',$data['invoice']->bill_to_id)->orderBy('id','ASC')->get();
        if($data['invoice']->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($data['invoice']->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($data['invoice']->bill_to_id);
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

        
        
        $data['store_detail'] = StoreModel::select('*')->find($invoice->store_id);
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

        $data['is_tax_applicable'] = true;
        if($data['store_detail']->vat_number == ''){
            $data['is_tax_applicable'] = false;
            // $is_tax_applicable = TaxcodeModel::select('tax_code')->where('id',$data['store_detail']->tax_code_id)->first();
            // if(isset($is_tax_applicable) && $is_tax_applicable->tax_code == 'NO_TAX'){
            //     $data['is_tax_applicable'] = false;
            // }
        }

        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($data['store_detail']->store_logo);
            $data['relative_logo_path'] = asset('storage/'.session('merchant_id').'/store/'.$data['store_detail']->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $data['company_detail'] = StoreModel::select('*')->where('id',request()->logged_user_store_id)->first();
        $data['invoice']['invoice_date_iso'] = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at, '+0:00');
    //   return  $print_data = view('invoice.invoice.invoice_print_alt', [
    //         'logo_path' => json_encode($data['logo_path']),
    //         'relative_logo_path' => json_encode($data['relative_logo_path']),
    //         'invoice' => json_encode($data['invoice']), 
    //         'invoice_products' => json_encode($data['invoice_products']), 
    //         'is_tax_applicable' => json_encode($data['is_tax_applicable']), 
    //         'supplier' => json_encode($data['supplier']),
    //         'store_detail' => json_encode($data['store_detail']),
    //         'pdf_path' => asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf'),
    //     ]);
        $print_data = view('invoice.invoice.invoice_print_alt', [
            'logo_path' => json_encode($data['logo_path']),
            'relative_logo_path' => json_encode($data['relative_logo_path']),
            'invoice' => json_encode($data['invoice']), 
            'invoice_products' => json_encode($data['invoice_products']), 
            'is_tax_applicable' => json_encode($data['is_tax_applicable']), 
            'supplier' => json_encode($data['supplier']),
            'store_detail' => json_encode($data['store_detail']),
            'pdf_path' => asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf'),
            'customer_add_infos' => json_encode($data['customer_add_infos']),
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
        $mpdf->Output('storage/'.session('merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf', 'F');
        $data['pdf_path'] =  '../storage/'.session('merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf';
        $data['qrcode_pdf_path'] =  asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf');
        
        $express_payment_slack = PaymentMethodModel::where('payment_constant','EXPRESSPAY')->first();
        $data['express_payment_slack'] = (isset($express_payment_slack)) ? $express_payment_slack->slack : '';

        $data['expresspay_transaction_permission'] = false;
        if(check_access(['A_VIEW_EXPRESSPAY_TRANSACTION'] ,true)){
            $data['expresspay_transaction_permission'] = true;
        }
        $data['mada_visa_master_img'] = asset('images/mada-visa-master.png');
        return view('invoice.invoice_detail', $data);

    }
    

    public function purchase_invoice_detail($slack){
    
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access([$data['sub_menu_key']]);

        $print_logo_path = config("app.invoice_print_logo");

        $invoice = InvoiceModel::where('slack', '=', $slack)->first();
        
        if (empty($invoice)) {
            abort(404);
        }

        $data['transaction_type'] = 'INCOME';

        $data['accounts'] = AccountModel::select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')
        ->masterAccountTypeJoin()
        ->active()
        ->get();

        $data['payment_methods'] = PaymentMethodModel::select('slack', 'label')
        ->active()
        ->skipPaymentGateway()
        // ->where('PAYMENT_CONSTANT','!=','EXPRESSPAY')
        ->get();

        $store_data = StoreModel::select('currency_name', 'currency_code')
        ->where([
            ['stores.id', '=', request()->logged_user_store_id]
        ])
        ->active()
        ->first();

        $invoice_data = new InvoiceResource($invoice);
        
        $data['invoice_data'] = $invoice_data;
        
        $invoice_statuses = [];
        
        if(check_access(['A_EDIT_STATUS_INVOICE'] ,true)){
            $invoice_statuses = MasterStatusModel::select('label','value_constant')->where([
                ['value_constant', '!=', strtoupper('NEW')],
                ['key', '=', 'INVOICE_STATUS'],
                ['status', '=', '1']
            ])->active()->orderBy('value', 'asc')->get();
        }

        $data['invoice_statuses'] = $invoice_statuses;

        $data['currency_codes'] = [
            'store_currency' => $store_data->currency_code,
            'invoice_currency' => $invoice_data->currency_code
        ];

        $data['delete_invoice_access'] = check_access(['A_DELETE_INVOICE'] ,true);

        $data['make_payment_access'] = check_access(['A_MAKE_PAYMENT_INVOICE'] ,true);

        if(!Storage::disk('invoice')->has('invoice_'.$slack.".pdf")){
            Storage::disk('invoice')->delete('invoice_'.$slack.".pdf");   
        }

        // For Invoice Print
        $data['invoice'] = InvoiceModel::with('invoiceCharges')->where('slack', '=', $slack)->first();
        
        if (empty($data['invoice'])) {
            abort(404);
        }
        
        $data['invoice_products'] = InvoiceProduct::where('invoice_id',$data['invoice']->id)->orderBy('product_id','ASC')->get();
        
        if($data['invoice']->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($data['invoice']->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($data['invoice']->bill_to_id);
        }

        $data['store_detail'] = StoreModel::select('name','vat_number','address','primary_contact','bank_name','account_holder_name','iban_number','invoice_policy_information','store_logo')->find($invoice->store_id);

        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($data['store_detail']->store_logo);
            $data['relative_logo_path'] = asset('storage/store/'.$data['store_detail']->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $data['company_detail'] = StoreModel::select('name','vat_number','address','primary_contact')->where('id',request()->logged_user_store_id)->first();

        $print_data = view('purchase_invoice.invoice.invoice_print_alt', [
            'logo_path' => json_encode($data['logo_path']),
            'relative_logo_path' => json_encode($data['relative_logo_path']),
            'invoice' => json_encode($data['invoice']), 
            'invoice_products' => json_encode($data['invoice_products']), 
            'supplier' => json_encode($data['supplier']),
            'store_detail' => json_encode($data['store_detail']),
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
        $mpdf->Output('storage/invoice/invoice_'.$slack.'.pdf', 'F');
        $data['pdf_path'] = '../storage/invoice/invoice_'.$slack.'.pdf';

        return view('purchase_invoice.invoice_detail', $data);

    }

    public function get_invoice_data($slack){
        $data['invoice_data'] = null;

        if(isset($slack)){

            $invoice = InvoiceModel::withoutGlobalScopes()->select('invoices.*')->where('invoices.slack', $slack)->first();

            if (empty($invoice)) {
                abort(404);
            }

            $invoice_data = new InvoiceResource($invoice);

            $invoice_products_array = collect($invoice_data->products)->toArray();

            $total_qty_array = data_get($invoice_products_array, '*.quantity', 0);
            $total_quantity = array_sum($total_qty_array);
            
            $invoice_data = collect($invoice_data);
            $invoice_data->put('total_quantity', $total_quantity);
            $data = $invoice_data->all();
        }

        $products = [];
        if(!empty($data['products'])){
            foreach($data['products'] as $invoice_product){
                 $temp = [];
                 $temp = collect($invoice_product);
                 $return_quantity = InvoiceReturn::join('invoice_return_products','invoice_return_products.return_invoice_slack','invoices_return.slack')->where('invoices_return.invoice_slack',$invoice->slack)->where('invoice_return_products.product_slack',$invoice_product->product_slack)->sum('invoice_return_products.quantity');
                $temp->put('main_quantity', $invoice_product->quantity);
                $temp->put('return_quantity', $return_quantity);
                $current_quantity =  $invoice_product->quantity - $return_quantity;
                $temp->put('quantity', number_format((float)$current_quantity, 2, '.', ''));
                $temp->put('max_quantity', number_format((float)$current_quantity, 2, '.', ''));

                $products[] = $temp;
            }
        }
        $data['products'] = $products;
     
        return $data;
    }
    
    public function invoice_return_details($slack){
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access([$data['sub_menu_key']]);

        $invoice_data = $this->get_invoice_data($slack);

        if (empty($invoice_data)) {
            abort(404);
        }
        $data['invoice_data'] = $invoice_data;
        $return_invoice_exist =FALSE;

        $return_reason = InvoiceReturnModel::select('id','reason')->where('invoice_slack', $slack)->orderBy('id', 'desc')->first();
        $return_invoice_check = InvoiceReturnModel::select('slack','reason')->where('invoice_slack', $slack)->orderBy('id', 'desc')->get()->pluck('slack')->toArray();
        $data['return_reason_data'] = "";

        if(isset($return_reason->id))
        {
            //$return_invoice_exist = TRUE;
            $data['return_reason_data'] = $return_reason->reason;
            $return_total_product = InvoiceReturnProducts::whereIn('return_invoice_slack',$return_invoice_check)->sum('quantity');

            if($return_total_product==$invoice_data['total_quantity']){
                $return_invoice_exist = TRUE;
            }
        }
        $data['return_invoice_exist'] = $return_invoice_exist;
        $data['store_tax_percentage'] = "";
        if(session('store_tax_code') != ""){
            $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id',session('store_tax_code'))->first()->total_tax_percentage;
        }

        $restaurant_mode = StoreModel::find(session('store_id'))->pluck('restaurant_mode')->first();
        $data['restaurant_mode'] = ($restaurant_mode == 1) ? true : false; 


        if($data['invoice_data']['bill_to'] == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($data['invoice_data']['bill_to_id']);
        }else{
            $data['supplier'] = Customer::find($data['invoice_data']['bill_to_id']);
        }

        if(isset($data['supplier']['country_id']))
        {
               $country_details = CountryModel::select('*')->find($data['supplier']['country_id']);
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
        $data['payment_methods'] = PaymentMethodModel::select('id', 'slack', 'label')
            ->active()
            ->get()
            ->makeVisible(['id']);

        $data['store_detail'] = StoreModel::select('name','vat_number','address','primary_contact','bank_name','account_holder_name','iban_number','invoice_policy_information','store_logo')->find($data['invoice_data']['store_id']);

      
        return view('invoice.invoice_return_details', $data);

    }

 

    public function invoice_return_receipt(Request $request, $slack){   
        return $this->print_return_invoice($request, $slack, 'INLINE','SMALL');
    }

    

    public function get_return_invoice_data($slack){
        $data['invoice_data'] = null;

        if(isset($slack)){

            $invoice = InvoiceReturnModel::select('invoices_return.*')->where('invoices_return.slack', $slack)
            ->first();

   
            if (empty($invoice)) {
                abort(404);
            }

            $invoice_data = new InvoiceReturnResource($invoice);

            $order_products_array = collect($invoice_data->products)->toArray();
            $total_qty_array = data_get($order_products_array, '*.quantity', 0);
            $total_quantity = array_sum($total_qty_array);
            
            $invoice_data = collect($invoice_data);
            $invoice_data->put('total_quantity', $total_quantity);
            $data = $invoice_data->all();
        }
        return $data;
    }

   
    public function print_return_invoice(Request $request, $slack, $type = 'INLINE',$invoice_print_type = null){
        
     
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access([$data['sub_menu_key']]);


        
        $invoice = InvoiceReturnModel::where('slack', '=', $slack)->first();
        
        if (empty($invoice)) {
            abort(404);
        }

      
          // For Invoice Print
          $data['invoice'] = InvoiceReturnModel::with('invoiceCharges')->where('slack', '=', $slack)->first();
        
          if (empty($data['invoice'])) {
              abort(404);
          }
            $data['customer_additional_info'] = array();
          if(isset($data['invoice']->bill_to_id) && $data['invoice']->bill_to_id>0){
              $data['customer_additional_info'] = CustomerAdditionalInfo::where('customer_id',$data['invoice']->bill_to_id)->get();
          }
          $data['invoice_products'] = InvoiceReturnProductsModel::where('return_invoice_id',$data['invoice']->id)->orderBy('product_id','ASC')->get();
  
          if($data['invoice']->bill_to == "SUPPLIER"){
              // Supplier
              $data['supplier'] = Supplier::find($data['invoice']->bill_to_id);
          }else{
              $data['supplier'] = Customer::find($data['invoice']->bill_to_id);
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
  
          
          
          $data['store_detail'] = StoreModel::select('*')->find($invoice->store_id);
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
          
        return view('invoice.return_invoice.invoice_return_detail',$data);

        // $print_data = view($view_file, [
        //     'logo_path' => json_encode($data['logo_path']),
        //     'relative_logo_path' => json_encode($data['relative_logo_path']),
        //     'invoice' => json_encode($data['invoice']), 
        //     'invoice_products' => json_encode($data['invoice_products']), 
        //     'supplier' => json_encode($data['supplier']),
        //     'store_detail' => json_encode($data['store_detail']),
        // ])->render();

        // // $print_data = view($view_file, ['data' => json_encode($data['invoice_data']), 'logo_path' => $print_logo_path])->render();


        // $mpdf_config = [
        //     'mode'          => 'utf-8',
        //     'format'        => 'A4',
        //     'orientation'   => 'P',
        //     'margin_left'   => 0,
        //     'margin_right'  => 0,
        //     'margin_top'    => 0,
        //     'margin_bottom' => 0,
        //     'tempDir' => storage_path()."/pdf_temp" 
        // ];


        // $stylesheet = File::get(public_path($css_file));
        // $mpdf = new Mpdf($mpdf_config);
        // $mpdf->curlAllowUnsafeSslRequests = true;
        // $mpdf->autoScriptToLang = true;
        // $mpdf->autoLangToFont = true;
        // $mpdf->SetDisplayMode(100);
        // $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        // $mpdf->WriteHTML($print_data);
        // $filename = 'return_invoice_'.session('merchant_id')."_".$invoice->return_invoice_number.'.pdf';
        
        // if($type == 'INLINE'){

        //     $view_path = Config::get('constants.upload.invoice.view_path');
        //     $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
        //     $mpdf->Output($upload_dir.$filename,'F');
        //     $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);

        // }else{
        
        //     Storage::disk('invoice')->delete(
        //         [
        //             $filename
        //         ]
        //     );

        //     $view_path = Config::get('constants.upload.invoice.view_path');
        //     $upload_dir = Storage::disk('invoice')->getAdapter()->getPathPrefix();

        //     $mpdf->Output($upload_dir.$filename, \Mpdf\Output\Destination::FILE);

            
        //     $download_link = $view_path.$filename;
        //     return $download_link; 
        // }
    }

    public function generate_invoice_return_pdf($slack)
    {
       
        try {
           
            
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_INVOICES';
        check_access([$data['sub_menu_key']]);

        $print_logo_path = config("app.invoice_print_logo");

        $invoice = InvoiceModel::where('slack', '=', $slack)->first();
        
        if (empty($invoice)) {
            return response()->json($this->generate_response(
                array(
                    "message" => __('No invoice order found.'),
                ),
                'ERROR'
            ));
        }

        $data['transaction_type'] = 'INCOME';

        $data['accounts'] = AccountModel::select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')
        ->masterAccountTypeJoin()
        ->active()
        ->get();

        $data['payment_methods'] = PaymentMethodModel::select('slack', 'label')
        ->active()
        // ->where('PAYMENT_CONSTANT','!=','EXPRESSPAY')
        ->get();


        $store_data = StoreModel::select('currency_name', 'currency_code')
        ->where([
            ['stores.id', '=', request()->logged_user_store_id]
        ])
        ->active()
        ->first();

        $invoice_data = new InvoiceResource($invoice);
        
        $data['invoice_data'] = $invoice_data;
        
        $invoice_statuses = [];
        
        if(check_access(['A_EDIT_STATUS_INVOICE'] ,true)){
            $invoice_statuses = MasterStatusModel::select('label','value_constant')->where([
                ['value_constant', '!=', strtoupper('NEW')],
                ['key', '=', 'INVOICE_STATUS'],
                ['status', '=', '1']
            ])->active()->orderBy('value', 'asc')
            ->where('value_constant','!=','PAID')
            ->get();
        }

        $data['invoice_statuses'] = $invoice_statuses;

        $data['currency_codes'] = [
            'store_currency' => $store_data->currency_code,
            'invoice_currency' => $invoice_data->currency_code
        ];

        $data['delete_invoice_access'] = check_access(['A_DELETE_INVOICE'] ,true);

        $data['make_payment_access'] = check_access(['A_MAKE_PAYMENT_INVOICE'] ,true);

        if(!Storage::disk('invoice')->has('invoice_'.$slack.".pdf")){
            Storage::disk('invoice')->delete('invoice_'.$slack.".pdf");   
        }

        // For Invoice Print
        $data['invoice'] = InvoiceModel::with('invoiceCharges')->where('slack', '=', $slack)->first();
        
        if (empty($data['invoice'])) {
            return response()->json($this->generate_response(
                array(
                    "message" => __('No return order found.'),
                ),
                'ERROR'
            ));
        }
        
        $data['invoice_products'] = InvoiceProduct::where('invoice_id',$data['invoice']->id)->orderBy('product_id','ASC')->get();

        if($data['invoice']->bill_to == "SUPPLIER"){
            // Supplier
            $data['supplier'] = Supplier::find($data['invoice']->bill_to_id);
        }else{
            $data['supplier'] = Customer::find($data['invoice']->bill_to_id);
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

        
        
        $data['store_detail'] = StoreModel::select('*')->find($invoice->store_id);
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

        $data['is_tax_applicable'] = true;
        if($data['store_detail']->vat_number == ''){
            $data['is_tax_applicable'] = false;
            // $is_tax_applicable = TaxcodeModel::select('tax_code')->where('id',$data['store_detail']->tax_code_id)->first();
            // if(isset($is_tax_applicable) && $is_tax_applicable->tax_code == 'NO_TAX'){
            //     $data['is_tax_applicable'] = false;
            // }
        }

        if(!empty($data['store_detail']->store_logo)){
            $data['logo_path'] = Storage::disk('store')->path($data['store_detail']->store_logo);
            $data['relative_logo_path'] = asset('storage/'.session('merchant_id').'/store/'.$data['store_detail']->store_logo);
        }else{
            $data['logo_path'] = Storage::disk('company')->path('logo_company.png');
            $data['relative_logo_path'] = asset('storage/company/logo_company.png');
        }

        $data['company_detail'] = StoreModel::select('*')->where('id',request()->logged_user_store_id)->first();
        $data['invoice']['invoice_date_iso'] = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at, '+0:00');

        $print_data = view('invoice.invoice.invoice_print_alt', [
            'logo_path' => json_encode($data['logo_path']),
            'relative_logo_path' => json_encode($data['relative_logo_path']),
            'invoice' => json_encode($data['invoice']), 
            'invoice_products' => json_encode($data['invoice_products']), 
            'is_tax_applicable' => json_encode($data['is_tax_applicable']), 
            'supplier' => json_encode($data['supplier']),
            'store_detail' => json_encode($data['store_detail']),
            'pdf_path' => asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf'),
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
        $mpdf->Output('storage/'.session('merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf', 'F');
        $data['pdf_path'] =  '../storage/'.session('merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf';
        $data['qrcode_pdf_path'] =  asset('storage/'.config('constants.config.merchant_id').'/invoice/temp_invoice_'.$slack.'.pdf');
        
      
            if (isset($data)) {
               
                $view_file = 'invoice.pdf.generate';
                // $css_file = 'css/invoice_alt_print.css';
                $css_file = 'css/order_thermal_print_invoice.css';
                $print_logo_path = session('store_logo');
                $format = [100, 150];

                $print_data = view($view_file, ['invoices' => json_encode($data), 'print_logo_path' => $print_logo_path, 'from_date' => "2020-20-20", 'to_date' => "2020-20-20"])->render();

                $mpdf_config = [
                    'mode'          => 'utf-8',
                    'format'        => $format,
                    'orientation'   => 'P',
                    'margin_left'   => 7,
                    'margin_right'  => 7,
                    'margin_top'    => 7,
                    'margin_bottom' => 7,
                    'tempDir' => storage_path() . "/pdf_temp"
                ];

                $stylesheet = File::get(public_path($css_file));
                $mpdf = new Mpdf($mpdf_config);
                $mpdf->curlAllowUnsafeSslRequests = true;
                $mpdf->SetDisplayMode('real');
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($print_data);

                $pdf_filename = "invoice_return_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.invoice_return.view_path');
                $upload_dir = Storage::disk('invoice_return')->getAdapter()->getPathPrefix();

             
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Invoice Return pdf created successfully",
                        // "data" => $invoices,
                        // 'invoices' => $invoices,
                        "link" => '/storage/'.session('merchant_id').'/invoice_return/' . $pdf_filename,
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No return order found.'),
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

       
}
