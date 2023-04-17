<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Storage;
use App\Exports\UserExport;

use App\Models\Role as RoleModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\User as UserModel;
use App\Models\Store as StoreModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Category as CategoryModel;
use App\Models\Product as ProductModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Http\Controllers\API\User as UserAPI;
use App\Http\Controllers\API\Store as StoreAPI;
use App\Http\Controllers\API\Supplier as SupplierAPI;
use App\Http\Controllers\API\Category as CategoryAPI;
use App\Http\Controllers\API\Product as ProductAPI;

use Carbon\Carbon;
use Mpdf\Mpdf;

class Import extends Controller
{
    public function index(Request $request){
        try {
            
            $import_type = $request->upload_type;
            $import_file = $request->upload_file;

            if(empty($import_type)){
                throw new Exception("Invalid request", 400);
            }
            if(empty($import_file)){
                throw new Exception("File is required", 400);
            }

            $filename = '';
            switch($import_type){
                case "USER":
                if(!check_access(['A_UPLOAD_USER'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
                case "STORE":
                if(!check_access(['A_UPLOAD_STORE'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
                case "SUPPLIER":
                if(!check_access(['A_UPLOAD_SUPPLIER'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
                case "CATEGORY":
                if(!check_access(['A_UPLOAD_CATEGORY'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
                case "PRODUCT":
                if(!check_access(['A_UPLOAD_PRODUCT'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
                case "INGREDIENT":
                if(!check_access(['A_UPLOAD_INGREDIENT'], true)){
                    throw new Exception("Invalid request", 400);
                }
                break;
            }

            $custom_filename = strtolower($import_type).'_'.date('Y_m_d_H_i_s').'_'.uniqid();
            $extension = $import_file->getClientOriginalExtension();
            $custom_file = $custom_filename.".".$extension;

            Storage::disk('imports')->delete(
                [
                    $custom_file
                ]
            );

            $path = Storage::disk('imports')->putFileAs('/', $import_file, $custom_file);

            $import_response = $this->forward_import_request($import_type, $custom_file);

            if($import_response['import_status'] == false){
                Storage::disk('imports')->delete(
                    [
                        $custom_file
                    ]
                );
            }
            
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Import file read successfully"),
                    "data" => $import_response,
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function forward_import_request($import_type, $import_file){

        switch($import_type){
            case "USER":
                $response = $this->import_file($import_type, $import_file);
                if($response['import_status'] == true){
                    $user_api = new UserAPI();
                    $request = request();
                    foreach ($response['data'] as $user_array_item) {
                        $request->merge($user_array_item);
                        $user_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
            case "STORE":
                $response = $this->import_file($import_type, $import_file);
                if($response['import_status'] == true){
                    $store_api = new StoreAPI();
                    $request = request();
                    foreach ($response['data'] as $store_array_item) {
                        $request->merge($store_array_item);
                        $store_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
            case "SUPPLIER":
                $response = $this->import_file($import_type, $import_file);
                if($response['import_status'] == true){
                    $supplier_api = new SupplierAPI();
                    $request = request();
                    foreach ($response['data'] as $supplier_array_item) {
                        $request->merge($supplier_array_item);
                        $supplier_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
            case "CATEGORY":
                $response = $this->import_file($import_type, $import_file);
                if($response['import_status'] == true){
                    $category_api = new CategoryAPI();
                    $request = request();
                    foreach ($response['data'] as $category_array_item) {
                        $request->merge($category_array_item);
                        $category_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
            case "PRODUCT":
                $response = $this->import_file($import_type, $import_file);
                
                if($response['import_status'] == true){
                    $product_api = new ProductAPI();
                    $request = request();
                
                    $request->request->add(['is_taxable' => true]);
                    
                    foreach ($response['data'] as $product_array_item) {
                        $request->merge($product_array_item);
                        $product_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
            case "INGREDIENT":
                $response = $this->import_file($import_type, $import_file);
                if($response['import_status'] == true){
                    $product_api = new ProductAPI();
                    $request = request();
                    $request->request->add(['is_taxable' => true]);
                    foreach ($response['data'] as $product_array_item) {
                        $request->merge($product_array_item);
                        $product_api->store($request);
                    }
                    unset($response['data']);
                }
            break;
        }

        return $response;
    }

    public function import_file($import_type, $import_file){

        $data_array   = [];
        $error_array  = [];

        switch($import_type){
            case "USER":
                $valid_template = $this->validate_template("USER", $import_file);
            break;
            case "STORE":
                $valid_template = $this->validate_template("STORE", $import_file);
            break;
            case "SUPPLIER":
                $valid_template = $this->validate_template("SUPPLIER", $import_file);
            break;
            case "CATEGORY":
                $valid_template = $this->validate_template("CATEGORY", $import_file);
            break;
            case "PRODUCT":
                $valid_template = $this->validate_template("PRODUCT", $import_file);
            break;
            case "INGREDIENT":
                $valid_template = $this->validate_template("INGREDIENT", $import_file);
            break;
        }

        if($valid_template){
            
            $upload_folder = Config::get('constants.upload.imports.upload_path');
            $excel_data = (new DataImport)->toArray( $upload_folder.$import_file);

            $excel_data = $excel_data[0];
            if(count($excel_data) == 0){
                throw new Exception(trans("Please provide some data in the excel sheet."), 400);
            }

            foreach($excel_data as $key => $excel_data_item){
                if(count(array_filter(array_values($excel_data_item)))>0) {
                switch($import_type){
                    case "USER":
                        $validate_response = $this->validate_user_data($excel_data_item);
                    break;
                    case "STORE":
                        $validate_response = $this->validate_store_data($excel_data_item);
                    break;
                    case "SUPPLIER":
                        $validate_response = $this->validate_supplier_data($excel_data_item);
                    break;
                    case "CATEGORY":
                        $validate_response = $this->validate_category_data($excel_data_item);
                    break;
                    case "PRODUCT":
                        $validate_response = $this->validate_product_data($excel_data_item);
                    break;
                    case "INGREDIENT":
                        $validate_response = $this->validate_ingredient_data($excel_data_item);
                    break;
                }
                if(count($validate_response['error_list'])>0){
                    $error_array[$key+2] = $validate_response['error_list'];
                }
                $data_array[] = $validate_response['data'];
            }
            }

            $response = [
                'import_status' => (count($error_array)>0)?false:true,
                'errors' => $error_array
            ];
            if(count($error_array) == 0){
                $response['data'] = $data_array;
            }
            
            return $response;

        }else{
            throw new Exception(trans("Invalid file uploaded"), 400);
        }
    }

    public function validate_template($template_type, $import_file){
        $valid_template = false;
        
        //check template if valid
        switch($template_type){
            case "USER":
                $template_format = Config::get('constants.upload.imports.user_format');
            break;
            case "STORE":
                $template_format = Config::get('constants.upload.imports.store_format');
            break;
            case "SUPPLIER":
                $template_format = Config::get('constants.upload.imports.supplier_format');
            break;
            case "CATEGORY":
                $template_format = Config::get('constants.upload.imports.category_format');
            break;
            case "PRODUCT":
                $template_format = Config::get('constants.upload.imports.product_format');
            break;
            case "INGREDIENT":
                $template_format = Config::get('constants.upload.imports.ingredient_format');
            break;
        }
        
        $default_foramt_file_path = public_path($template_format);
        $format_headings = (new HeadingRowImport)->toArray($default_foramt_file_path);
        $format_headings = array_filter(array_map('trim', $format_headings[0][0]));
        
        $upload_folder = Config::get('constants.upload.imports.upload_path');
        $uploaded_file_headings = (new HeadingRowImport)->toArray($upload_folder.$import_file);
        $uploaded_file_headings = array_filter(array_map('trim', $uploaded_file_headings[0][0])); 

        $valid_template = ($format_headings == $uploaded_file_headings)?true:false;

        return $valid_template;
    }

    public function validate_user_data($excel_data_item)
    {

        
        $response = [];
        $data = [];
        $error_array  = [];
        $stores = '';

        $fullname       = $excel_data_item['fullname'];
        $email          = $excel_data_item['email'];
        $contact_number = $excel_data_item['contact_number'];
        $role_code      = $excel_data_item['role_code'];
        $status         = $excel_data_item['status'];
        $store_codes    = $excel_data_item['store_codes_csv'];

        $validator = Validator::make($excel_data_item, [
            'fullname' => $this->get_validation_rules("fullname", true),
            'email' => $this->get_validation_rules("email", true),
            'contact_number' => $this->get_validation_rules("phone", true),
            'role_code' => $this->get_validation_rules("codes", true),
            'status' => $this->get_validation_rules("filled",true),
            //'store_codes_csv' => $this->get_validation_rules("filled",true)
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }

        $role_data = RoleModel::select('slack')->where('role_code', '=', $role_code)->resolveSuperAdminRole()->active()->first();
        if (!$role_data) {
            $error_array[] = trans('Invalid role code provided');
        }

        if ($email != "") {
            $email_data = UserModel::where('email', '=', $email)->first();
            if ($email_data) {
                $error_array[] = trans('Email already assigned to another user');
            }
        }

        if ($contact_number != "") {
            $contact_number_data = UserModel::where('phone', '=', $contact_number)->first();
            if ($contact_number_data) {
                $error_array[] = trans('Contact number already assigned to another user');
            }
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'USER_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }

        if($store_codes != ''){
            $store_codes_array = explode(",",$store_codes);
            $store_codes_array = array_map('trim',$store_codes_array);
            if (count($store_codes_array) > 0) {
                
                $store_data = StoreModel::whereIn('store_code', $store_codes_array)->active()->get();
                $valid_store_slack_array = $store_data->pluck('slack')->toArray();
                $valid_store_code_array = $store_data->pluck('store_code')->toArray();
                
                $invalid_store_codes = array_diff($store_codes_array, $valid_store_code_array);

                if(count($invalid_store_codes) > 0){
                    $error_array[] = implode(',', $invalid_store_codes).' :'.trans('Invalid stores provided');
                }
                if ($store_data->isEmpty()) {
                    $error_array[] = trans('Invalid stores provided');
                }else{
                    $stores = implode(',', $valid_store_slack_array);
                }
            }
        }

        if(count($error_array) == 0){
            $data = [
                "fullname"  => $fullname,
                "email"     => $email,
                "phone"     => $contact_number,
                "role"      => $role_data->slack,
                "status"    => $status_data->value,
                "user_stores" => $stores
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_store_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $store_code = $excel_data_item['store_code'];
        $name = $excel_data_item['name'];
        $address = $excel_data_item['address'];
        $pincode = $excel_data_item['pincode'];
        $tax_number = $excel_data_item['tax_number'];
        $primary_contact_no = $excel_data_item['primary_contact_no'];
        $secondary_contact_no = $excel_data_item['secondary_contact_no'];
        $primary_email = $excel_data_item['primary_email'];
        $secondary_email = $excel_data_item['secondary_email'];
        $status = $excel_data_item['status'];
        $invoice_type = $excel_data_item['invoice_type'];
        $country_code = $excel_data_item['country_code'];
        $currency_code = $excel_data_item['currency_code'];

        $validator = Validator::make($excel_data_item, [
            'name' => $this->get_validation_rules("name_label", true),
            'address' => $this->get_validation_rules("text", true),
            'pincode' => $this->get_validation_rules("pincode", false),
            'store_code' => $this->get_validation_rules("codes", true),
            'tax_number' => $this->get_validation_rules("name_label", false),
            'primary_contact' => $this->get_validation_rules("phone", false),
            'secondary_contact' => $this->get_validation_rules("phone", false),
            'primary_email' => $this->get_validation_rules("email", false),
            'secondary_email' => $this->get_validation_rules("email", false),
            'status' => $this->get_validation_rules("filled", true),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }

        $store_name_exists = StoreModel::select('id')
            ->where('name', '=', trim($name))
            ->first();
        if (!empty($store_name_exists)) {
            throw new Exception("Store name already assigned to a store", 400);
        }

        $store_data_exists = StoreModel::select('id')
        ->where('store_code', '=', trim($store_code))
        ->first();
        if (!empty($store_data_exists)) {
            throw new Exception("Store code already assigned to a store", 400);
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'STORE_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }

        if(count($error_array) == 0){
            $data = [
                "store_code" => $store_code,
                "name" => $name,
                "tax_number" => $tax_number,
                "address" => $address,
                "pincode" => $pincode,
                "primary_contact" => $primary_contact_no,
                "secondary_contact" => $secondary_contact_no,
                "primary_email" => $primary_email,
                "secondary_email" => $secondary_email,
                "country_code" => $country_code,
                "currency_code"=>$currency_code,
                'invoice_type'=>$invoice_type,
                "status" => $status_data->value,
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_supplier_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $supplier_name = $excel_data_item['supplier_name'];
        $contact_email = $excel_data_item['contact_email'];
        $contact_number = $excel_data_item['contact_number'];
        $address = $excel_data_item['address'];
        $pincode = $excel_data_item['pincode'];
        $status = $excel_data_item['status'];

        $validator = Validator::make($excel_data_item, [
            'supplier_name' => $this->get_validation_rules("name_label", true),
            'contact_email' => $this->get_validation_rules("email", false),
            'contact_number' => $this->get_validation_rules("phone", false),
            'address' => $this->get_validation_rules("text", false),
            'pincode' => $this->get_validation_rules("pincode", false),
            'status' => $this->get_validation_rules("filled",true)
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }

        $supplier_data_exists = SupplierModel::select('id')
        ->where('name', '=', trim($supplier_name))
        ->first();
        if (!empty($supplier_data_exists)) {
            throw new Exception(trans("Supplier already available in the system"), 400);
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'SUPPLIER_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }

        if(count($error_array) == 0){
            $data = [
                "supplier_name" => $supplier_name,
                "address" => $address,
                "phone" => $contact_number,
                "email" => $contact_email,
                "pincode" => $pincode,
                "status" => $status_data->value,
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_category_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $category_name = $excel_data_item['category_name'];
        $description = $excel_data_item['description'];
        $status = $excel_data_item['status'];
        
        $validator = Validator::make($excel_data_item, [
            'category_name' => $this->get_validation_rules("name_label", true),
            'description' => $this->get_validation_rules("text"),
            'status' => $this->get_validation_rules("filled",true)
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }
        
        $category_data_exists = CategoryModel::select('id')
        ->where('label', '=', trim($category_name))
        ->first();
        if (!empty($category_data_exists)) {
            throw new Exception(trans("Category already available in the system"), 400);
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'CATEGORY_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }

        if(count($error_array) == 0){
            $data = [
                "category_name" => $category_name,
                "description" => $description,
                "status" => $status_data->value,
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_product_data($excel_data_item)
    {

        $response = [];
        $data = [];
        $error_array  = [];

        $product_code = trim($excel_data_item['product_code']);
        $product_name = trim($excel_data_item['product_name']);
        $supplier_code = trim($excel_data_item['supplier_code']);
        $category_code = trim($excel_data_item['category_code']);
        //$tax_code = trim($excel_data_item['tax_code']);
        $tax_code="";
        $purchase_price_excluding_tax = trim($excel_data_item['purchase_price_excluding_tax']);
        $sale_price_excluding_tax = trim($excel_data_item['sale_price_excluding_tax']);
        $sale_price_including_tax = trim($excel_data_item['sale_price_including_tax']);
        $quantity = trim($excel_data_item['quantity']);
        $stock_alert_quantity = trim($excel_data_item['stock_alert_quantity']);
        $description = trim($excel_data_item['description']);
        $discount_code = trim($excel_data_item['discount_code']);
        $shows_in = trim($excel_data_item['shows_in']);
        $barcode = trim($excel_data_item['barcode']);
        $excel_data_item['product_code'] = trim($excel_data_item['product_code']);
        // $is_unlimited_quantity = trim($excel_data_item['is_unlimited_quantity']);
        // $product_manufacturer_date = trim($excel_data_item['product_manufacturer_date']);
        // $product_expiry_date = trim($excel_data_item['product_expiry_date']);
        // print_r($product_manufacturer_date);
        // print_r($product_expiry_date); exit;
        $status = $excel_data_item['status'];

        $validator = Validator::make($excel_data_item, [
            'product_name' => $this->get_validation_rules("name_label", true),
            'product_code' => $this->get_validation_rules("codes", false),
            'supplier_code' => $this->get_validation_rules("codes", false),
            'category_code' => $this->get_validation_rules("codes", true),
            'tax_code' => $this->get_validation_rules("codes", false),
            'discount_code' => $this->get_validation_rules("codes", false),
            'purchase_price_excluding_tax' => $this->get_validation_rules("numeric", true),
            'sale_price_excluding_tax' => $this->get_validation_rules("numeric", true),
            'sale_price_including_tax' => $this->get_validation_rules("numeric", true),
            'quantity' => $this->get_validation_rules("numeric", true),
            'stock_alert_quantity' => $this->get_validation_rules("numeric", false),
            'description' => $this->get_validation_rules("text", false),
            'barcode' => $this->get_validation_rules("numeric", false),
            // 'product_manufacturer_date'=> $this->get_validation_rules("date", false),
            // 'product_expiry_date'=> $this->get_validation_rules("date", false),
            'shows_in' => $this->get_validation_rules("shows_in", false),
            'status' => $this->get_validation_rules("filled",true)
        ]);

        // if($product_manufacturer_date != ""){
        //     $validator_config['product_manufacturer_date'] = $this->get_validation_rules("date", false);
        // }
        // if($product_expiry_date != ""){
        //     $validator_config['product_expiry_date'] = $this->get_validation_rules("date", false);
        // }
        // if($barcode != ""){
        //     $validator_config['barcode'] = $this->get_validation_rules("numeric", false);
        // }

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }

        // $product_data_exists = ProductModel::select('id')
        // ->where('product_code', '=', trim($product_code))
        // ->first();
        // if (!empty($product_data_exists)) {
        //     throw new Exception(trans("Product code already assigned to a product"), 400);
        // }

       if($supplier_code != ""){

        $supplier_data = SupplierModel::select('slack','id')
        ->where('supplier_code', '=', trim($supplier_code))
        ->active()
        ->first();
        if (empty($supplier_data)) {
            throw new Exception(trans("Supplier not found or inactive in the system"), 400);
        }
       }

       //shows in validation
       $shows_in_arr=array("don't show anywhere","pos","invoice","both (pos & invoice)");

        if($shows_in == '' || is_null($shows_in)){
            $shows_in = 'pos';
        }
        if(!in_array(strtolower($shows_in),$shows_in_arr)){
          throw new Exception(trans("Shows In not found in the system"), 400);
        }

       
        $shows_in = array_search(strtolower($shows_in), $shows_in_arr);
        // end shows in
       
        $category_data = CategoryModel::select('slack','id')
        ->where('category_code', '=', trim($category_code))
        ->active()
        ->first();
        if (empty($category_data)) {

            throw new Exception(trans("Category not found or inactive in the system"), 400);
        }

        /*if($tax_code != ""){
            $taxcode_data = TaxcodeModel::select('slack','id')
            ->where('tax_code', '=', trim($tax_code))
            ->active()
            ->first();
            if (empty($taxcode_data)) {
                throw new Exception("Taxcode not found or inactive in the system", 400);
            }
       }*/
     
        if($discount_code != ""){
            $currentdate = date('Y-m-d H:i:sa');
            $discount_code_data = DiscountcodeModel::select('slack','id')
            ->where('discount_code', '=', trim($discount_code))
            ->whereRaw("discounttype='code'")
            ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
            ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
            ->active()
            ->first();

            if (empty($discount_code_data)) {
                $discount_code_data = DiscountcodeModel::where('discount_code', '=', trim($discount_code))->first();
                throw new Exception(trans("Discount Name '{$discount_code_data->label}' not found or inactive in the system"), 400);
            }
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'PRODUCT_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }
        // if($product_manufacturer_date != ""){
        //     $validator_config['product_manufacturer_date'] = $this->get_validation_rules("date", false);
        // }
        // if($product_expiry_date != ""){
        //     $validator_config['product_expiry_date'] = $this->get_validation_rules("date", false);
        // }
        // if($barcode != ""){
        //     $validator_config['barcode'] = $this->get_validation_rules("numeric", false);
        // }

        if(count($error_array) == 0){
            $data = [
                "product_name" => $product_name,
                "product_code" => $product_code,
                "description" => $description,
                "category" => $category_data->id,
                "supplier" => (isset($supplier_data))?$supplier_data->slack:'',
               /* "tax_code" => (isset($taxcode_data))?$taxcode_data->slack:'',
                "update_tax_code" => (isset($taxcode_data))?$taxcode_data->id:'',*/
                "discount_code" => (isset($discount_code_data))?$discount_code_data->slack:NULL,
                "quantity" => $quantity,
                "alert_quantity" => $stock_alert_quantity,
                "purchase_price" => $purchase_price_excluding_tax,
                "sale_price" => $sale_price_excluding_tax,
                "sale_price_including_tax" => $sale_price_including_tax,
                "barcode" => trim($barcode),
                // "product_manufacturer_date" => isset($product_manufacturer_date) ? Carbon::createFromFormat('d-m-Y', $product_manufacturer_date)->format('Y-m-d') : '',
                // "product_expiry_date" => isset($product_expiry_date) ? Carbon::createFromFormat('d-m-Y', $product_expiry_date)->format('Y-m-d') : '',           
                "status" => $status_data->value,
                "shows_in"=>$shows_in,
            ];

        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        
        return $response;

    }

    public function validate_ingredient_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $product_code = $excel_data_item['product_code'];
        $product_name = $excel_data_item['product_name'];
        $supplier_code = $excel_data_item['supplier_code'];
        $category_code = $excel_data_item['category_code'];
        //$tax_code = $excel_data_item['tax_code'];
        $tax_code="";
        $purchase_price_excluding_tax = $excel_data_item['purchase_price_excluding_tax'];
        $sale_price_excluding_tax = $excel_data_item['sale_price_excluding_tax'];
        $sale_price_including_tax = $excel_data_item['sale_price_including_tax'];
        $quantity = $excel_data_item['quantity'];
        $stock_alert_quantity = $excel_data_item['stock_alert_quantity'];
        $description = $excel_data_item['description'];
        $discount_code = $excel_data_item['discount_code'];
        
        $status = $excel_data_item['status'];
        $shows_in = $excel_data_item['shows_in'];

        $validator = Validator::make($excel_data_item, [
            'product_name' => $this->get_validation_rules("name_label", true),
            'product_code' => $this->get_validation_rules("codes", true),
            'supplier_code' => $this->get_validation_rules("codes", false),
            'category_code' => $this->get_validation_rules("codes", true),
            'tax_code' => $this->get_validation_rules("codes", false),
            'discount_code' => $this->get_validation_rules("codes", false),
            'purchase_price_excluding_tax' => $this->get_validation_rules("numeric", true),
            'sale_price_excluding_tax' => $this->get_validation_rules("numeric", true),
            'sale_price_including_tax' => $this->get_validation_rules("numeric", true),
            'quantity' => $this->get_validation_rules("numeric", true),
            'stock_alert_quantity' => $this->get_validation_rules("numeric", false),
            'description' => $this->get_validation_rules("text", false),
            'shows_in' => $this->get_validation_rules("text",true),
            'status' => $this->get_validation_rules("filled",true)
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error_array[] = $message;
            }
        }

        // $product_data_exists = ProductModel::select('id')
        // ->where('product_code', '=', trim($product_code))
        // ->first();
        // if (!empty($product_data_exists)) {
        //     throw new Exception(trans("Product code already assigned to a product"), 400);
        // }

        if($supplier_code != ""){
            $supplier_data = SupplierModel::select('slack')
            ->where('supplier_code', '=', trim($supplier_code))
            ->active()
            ->first();
            if (empty($supplier_data)) {
                throw new Exception(trans("Supplier not found or inactive in the system"), 400);
            }
        }

        $category_data = CategoryModel::select('id')
        ->where('category_code', '=', trim($category_code))
        ->active()
        ->first();
        if (empty($category_data)) {
            throw new Exception(trans("Category not found or inactive in the system"), 400);
        }

        //shows in validation
        $shows_in_arr=array("don't show anywhere","pos","invoice","both (pos & invoice)");
        if($shows_in == '' || is_null($shows_in)){
            $shows_in = 'pos';
        }
        if(!in_array(strtolower($shows_in),$shows_in_arr)){
          throw new Exception(trans("Shows In not found in the system"), 400);
         }
       
        $shows_in = array_search(strtolower($shows_in), $shows_in_arr);
        // end shows in
       
       /* if($tax_code != ""){
                $taxcode_data = TaxcodeModel::select('slack')
                ->where('tax_code', '=', trim($tax_code))
                ->active()
                ->first();
                if (empty($taxcode_data)) {
                    throw new Exception("Taxcode not found or inactive in the system", 400);
                }
        }*/
        if($discount_code != ""){
            $currentdate = date('Y-m-d H:i:sa');
            $discount_code_data = DiscountcodeModel::select('id')
            ->where('discount_code', '=', trim($discount_code))
            ->whereRaw("discounttype='code'")
            ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
            ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
            ->active()
            ->first();
            if (empty($discount_code_data)) {
                $discount_code_data = DiscountcodeModel::where('discount_code', '=', trim($discount_code))->first();
                throw new Exception(trans("Discount Name '{$discount_code_data->label}' not found or inactive in the system"), 400);
            }
        }

        if ($status != "") {
            $status_data = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status)],
                ['key', '=', 'PRODUCT_STATUS']
            ])->active()->first();
            if (!$status_data) {
                $error_array[] = trans('Invalid status provided');
            }
        }

        if(count($error_array) == 0){
            $data = [
                "product_name" => $product_name,
                "product_code" => $product_code,
                "description" => $description,
                "category" => $category_data->id,
                "supplier" => (isset($supplier_data))?$supplier_data->slack:'',
               /* "tax_code" => (isset($taxcode_data))?$taxcode_data->slack:'',*/
                "quantity" => $quantity,
                "alert_quantity" => $stock_alert_quantity,
                "purchase_price" => $purchase_price_excluding_tax,
                "sale_price" => $sale_price_excluding_tax,
                "sale_price_including_tax" => $sale_price_including_tax,
                "discount_code" => (isset($discount_code_data))?$discount_code_data->slack:NULL,
                "is_ingredient" => 1,
                "shows_in"=>$shows_in,
                "status" => $status_data->value,
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    /* 
        upload and update section begins
    */

    public function update_data(Request $request){
        try {
            
            $update_type = $request->upload_type;
            $update_file = $request->upload_file;

            if(empty($update_type)){
                throw new Exception(trans("Invalid request"), 400);
            }
            if(empty($update_file)){
                throw new Exception(trans("File is required"), 400);
            }

            $filename = '';
            switch($update_type){
                case "USER":
                if(!check_access(['A_UPDATE_USER'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
                case "STORE":
                if(!check_access(['A_UPDATE_STORE'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
                case "SUPPLIER":
                if(!check_access(['A_UPDATE_SUPPLIER'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
                case "CATEGORY":
                if(!check_access(['A_UPDATE_CATEGORY'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
                case "PRODUCT":
                if(!check_access(['A_UPDATE_PRODUCT'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
                case "INGREDIENT":
                if(!check_access(['A_UPDATE_INGREDIENT'], true)){
                    throw new Exception(trans("Invalid request"), 400);
                }
                break;
            }

            $custom_filename = strtolower($update_type).'_'.date('Y_m_d_H_i_s').'_'.uniqid();

            $extension = $update_file->getClientOriginalExtension();
            $custom_file = $custom_filename.".".$extension;

            Storage::disk('updates')->delete(
                [
                    $custom_file
                ]
            );

            $path = Storage::disk('updates')->putFileAs('/', $update_file, $custom_file);

            $update_response = $this->forward_update_request($update_type, $custom_file);

            if($update_response['update_status'] == false){
                Storage::disk('updates')->delete(
                    [
                        $custom_file
                    ]
                );
            }
            
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Update file read successfully"),
                    "data" => $update_response,
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function forward_update_request($update_type, $update_file){

        switch($update_type){
            case "USER":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $user_array_item) {
                            
                        $update_data = $user_array_item['update_data'];
                        $update_key = $user_array_item['update_key'];
                        $user_stores = (isset($update_data['user_stores']))?$update_data['user_stores']:'';
                        
                        unset($update_data['user_stores']);

                        if(!empty($update_data) && !empty($update_key)){
                            $data = UserModel::where('slack', $update_key)
                            ->update($update_data);
                        }

                        if($user_stores != '' && !empty($update_key)){
                            $user_api = new UserAPI();
                            $request = request();
                            $request->merge(['user_stores' => $user_stores]);
                            $user_api->update_user_stores($request, $update_key);
                        }
                            
                    }

                    unset($response['data']);
                }
            break;
            case "STORE":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $store_array_item) {
                        
                        $update_data = $store_array_item['update_data'];
                        $update_key = $store_array_item['update_key'];

                        if(!empty($update_data) && !empty($update_key)){
                            $data = StoreModel::where('slack', $update_key)
                            ->update($update_data);
                        }
                    }
                    unset($response['data']);
                }
            break;
            case "SUPPLIER":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $supplier_array_item) {
                        $update_data = $supplier_array_item['update_data'];
                        $update_key = $supplier_array_item['update_key'];

                        if(!empty($update_data) && !empty($update_key)){
                            $data = SupplierModel::where('slack', $update_key)
                            ->update($update_data);
                        }
                    }
                    unset($response['data']);
                }
            break;
            case "CATEGORY":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $category_array_item) {
                        $update_data = $category_array_item['update_data'];
                        $update_key = $category_array_item['update_key'];

                        if(!empty($update_data) && !empty($update_key)){
                            $data = CategoryModel::where('slack', $update_key)
                            ->update($update_data);
                        }
                    }
                    unset($response['data']);
                }
            break;
            case "PRODUCT":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $product_array_item) {
                        $update_data = $product_array_item['update_data'];
                        $update_key = $product_array_item['update_key'];

                        if(!empty($update_data) && !empty($update_key)){
                            $data = ProductModel::where('slack', $update_key)
                            ->update($update_data);
                        }
                    }
                    unset($response['data']);
                }
            break;
            case "INGREDIENT":
                $response = $this->update_file($update_type, $update_file);
                if($response['update_status'] == true){
                    foreach ($response['data'] as $product_array_item) {
                        $update_data = $product_array_item['update_data'];
                        $update_key = $product_array_item['update_key'];

                        if(!empty($update_data) && !empty($update_key)){
                            $data = ProductModel::where('slack', $update_key)
                            ->update($update_data);
                        }
                    }
                    unset($response['data']);
                }
            break;
        }

        return $response;
    }

    public function update_file($update_type, $update_file){
        
        $data_array   = [];
        $error_array  = [];

        switch($update_type){
            case "USER":
                $valid_template = $this->validate_upload_update_template("USER", $update_file);
            break;
            case "STORE":
                $valid_template = $this->validate_upload_update_template("STORE", $update_file);
            break;
            case "SUPPLIER":
                $valid_template = $this->validate_upload_update_template("SUPPLIER", $update_file);
            break;
            case "CATEGORY":
                $valid_template = $this->validate_upload_update_template("CATEGORY", $update_file);
            break;
            case "PRODUCT":
                $valid_template = $this->validate_upload_update_template("PRODUCT", $update_file);
            break;
            case "INGREDIENT":
                $valid_template = $this->validate_upload_update_template("INGREDIENT", $update_file);
            break;
        }
        
        if($valid_template){
            
            $upload_folder = Config::get('constants.upload.updates.upload_path');
            $excel_data = (new DataImport)->toArray( $upload_folder.$update_file);
            
            $excel_data = $excel_data[0];
            if(count($excel_data) == 0){
                throw new Exception(trans("Please provide some data in the excel sheet."), 400);
            }

            foreach($excel_data as $key => $excel_data_item){
                switch($update_type){
                    case "USER":
                        $validate_response = $this->validate_update_user_data($excel_data_item);
                    break;
                    case "STORE":
                        $validate_response = $this->validate_update_store_data($excel_data_item);
                    break;
                    case "SUPPLIER":
                        $validate_response = $this->validate_update_supplier_data($excel_data_item);
                    break;
                    case "CATEGORY":
                        $validate_response = $this->validate_update_category_data($excel_data_item);
                    break;
                    case "PRODUCT":
                        $validate_response = $this->validate_update_product_data($excel_data_item);
                    break;
                    case "INGREDIENT":
                        $validate_response = $this->validate_update_ingredient_data($excel_data_item);
                    break;
                }
                if(count($validate_response['error_list'])>0){
                    $error_array[$key+2] = $validate_response['error_list'];
                }
                $data_array[] = $validate_response['data'];
            }

            $response = [
                'update_status' => (count($error_array)>0)?false:true,
                'errors' => $error_array
            ];
            if(count($error_array) == 0){
                $response['data'] = $data_array;
            }
            
            return $response;

        }else{
            throw new Exception(trans("Invalid file uploaded"), 400);
        }
    }

    public function validate_upload_update_template($template_type, $update_file){
        $valid_template = false;
    
        //check template if valid
        switch($template_type){
            case "USER":
                $template_format = Config::get('constants.upload.updates.user_format');
            break;
            case "STORE":
                $template_format = Config::get('constants.upload.updates.store_format');
            break;
            case "SUPPLIER":
                $template_format = Config::get('constants.upload.updates.supplier_format');
            break;
            case "CATEGORY":
                $template_format = Config::get('constants.upload.updates.category_format');
            break;
            case "PRODUCT":
                $template_format = Config::get('constants.upload.updates.product_format');
            break;
            case "INGREDIENT":
                $template_format = Config::get('constants.upload.updates.ingredient_format');
            break;
        }
        $default_format_file_path = public_path($template_format);
        $format_headings = (new HeadingRowImport)->toArray($default_format_file_path);
        $format_headings = array_filter(array_map('trim', $format_headings[0][0]));
       
        $upload_folder = Config::get('constants.upload.updates.upload_path');
        $uploaded_file_headings = (new HeadingRowImport)->toArray($upload_folder.$update_file);
        $uploaded_file_headings = array_filter(array_map('trim', $uploaded_file_headings[0][0])); 

        $valid_template = ($format_headings == $uploaded_file_headings)?true:false;

        return $valid_template;
    }
    
    public function validate_update_user_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];
        $stores = '';

        $user_code       = $excel_data_item['user_code'];
        $fullname       = $excel_data_item['fullname'];
        $email          = $excel_data_item['email'];
        $contact_number = $excel_data_item['contact_number'];
        $role_code      = $excel_data_item['role_code'];
        $status         = $excel_data_item['status'];
        $store_codes    = $excel_data_item['store_codes_csv'];

        $validator_config = [];
        $validator_config['user_code'] = $this->get_validation_rules("codes", true);

        if($user_code != ""){
            $user_data = UserModel::where('user_code', '=', $user_code)->first();
            if (!$user_data) {
                $error_array[] = trans('Invalid user code provided');
            }else{
                $slack = $user_data->slack;
            }
        }

        if(isset($slack)){
            if ($fullname != "") {
                $validator_config['fullname'] = $this->get_validation_rules("fullname", false);
            }

            if ($email != "") {
                $validator_config['email']  = $this->get_validation_rules("email", false);
                $email_data = UserModel::where('email', '=', $email)->where('slack', '!=', $slack)->first();
                if ($email_data) {
                    $error_array[] = trans('Email already assigned to another user');
                }
            }

            if ($contact_number != "") {
                $validator_config['contact_number'] = $this->get_validation_rules("phone", false);
                $contact_number_data = UserModel::where('phone', '=', $contact_number)->where('slack', '!=', $slack)->first();
                if ($contact_number_data) {
                    $error_array[] = trans('Contact number already assigned to another user');
                }
            }

            if ($role_code != "") {
                $validator_config['role_code'] = $this->get_validation_rules("codes", false);
                $role_data = RoleModel::select('slack')->where('role_code', '=', $role_code)->resolveSuperAdminRole()->active()->first();
                if (!$role_data) {
                    $error_array[] = trans('Invalid role code provided');
                }
            }

            if($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'USER_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

            if($store_codes != '') {
                $store_codes_array = explode(",",$store_codes);
                $store_codes_array = array_map('trim',$store_codes_array);
                if (count($store_codes_array) > 0) {
                    
                    $store_data = StoreModel::whereIn('store_code', $store_codes_array)->active()->get();
                    $valid_store_slack_array = $store_data->pluck('slack')->toArray();
                    $valid_store_code_array = $store_data->pluck('store_code')->toArray();
                    
                    $invalid_store_codes = array_diff($store_codes_array, $valid_store_code_array);

                    if(count($invalid_store_codes) > 0){
                        $error_array[] = implode(',', $invalid_store_codes).' :'.trans('Invalid stores provided');
                    }
                    if ($store_data->isEmpty()) {
                        $error_array[] = trans('Invalid stores provided');
                    }else{
                        $stores = implode(',', $valid_store_slack_array);
                    }
                }
            }

            if(!empty($validator_config)) {
                $validator = Validator::make($excel_data_item, $validator_config);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    foreach ($errors->all() as $message) {
                        $error_array[] = $message;
                    }
                }
            }
        }
        
        if(count($error_array) == 0) {
            $update_data = [
                "fullname"  => $fullname,
                "email"     => $email,
                "phone"     => $contact_number,
                "role"      => (!empty($role_data))?$role_data->id:'',
                "status"    => (!empty($status_data))?$status_data->value:'',
                "user_stores" => $stores
            ];
            $update_data = array_filter($update_data, 'skip_zero_array_filter');

            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_update_store_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $store_code = $excel_data_item['store_code'];
        $name = $excel_data_item['name'];
        $address = $excel_data_item['address'];
        $pincode = $excel_data_item['pincode'];
        $tax_number = $excel_data_item['tax_number'];
        $primary_contact_no = $excel_data_item['primary_contact_no'];
        $secondary_contact_no = $excel_data_item['secondary_contact_no'];
        $primary_email = $excel_data_item['primary_email'];
        $secondary_email = $excel_data_item['secondary_email'];
        $tax_code = $excel_data_item['tax_code'];
        $discount_code = $excel_data_item['discount_code'];
        $status = $excel_data_item['status'];

        $validator_config = [];
        $validator_config['store_code'] = $this->get_validation_rules("codes", true);

        if($store_code != ""){
            $store_data = StoreModel::where('store_code', '=', $store_code)->first();
            if (!$store_data) {
                $error_array[] = trans('Invalid store code provided');
            }else{
                $slack = $store_data->slack;
            }
        }

        if(isset($slack)){

            if($name != ""){
                $validator_config['name'] = $this->get_validation_rules("name_label", false);
            }

            if($address != ""){
                $validator_config['address'] = $this->get_validation_rules("text", false);
            }

            if($pincode != ""){
                $validator_config['pincode'] = $this->get_validation_rules("pincode", false);
            }

            if($tax_number != ""){
                $validator_config['tax_number'] = $this->get_validation_rules("name_label", false);
            }

            if($primary_contact_no != ""){
                $validator_config['primary_contact'] = $this->get_validation_rules("phone", false);
            }

            if($secondary_contact_no != ""){
                $validator_config['secondary_contact'] = $this->get_validation_rules("phone", false);
            }

            if($primary_email != ""){
                $validator_config['primary_email'] = $this->get_validation_rules("email", false);
            }

            if($secondary_email != ""){
                $validator_config['secondary_email'] = $this->get_validation_rules("email", false);
            }

            if($tax_code != ""){
                $validator_config['tax_code'] = $this->get_validation_rules("codes", false);
                $taxcode_data = TaxcodeModel::select('id')
                ->where('tax_code', '=', trim($tax_code))
                ->active()
                ->first();
                if (empty($taxcode_data)) {
                    throw new Exception(trans("Taxcode not found or inactive in the system"), 400);
                }
            }

            if($discount_code != ""){
                $validator_config['discount_code'] = $this->get_validation_rules("codes", false);
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                ->where('discount_code', '=', trim($discount_code))
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->active()
                ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::where('discount_code', '=', trim($discount_code))->first();
                    throw new Exception(trans("Discount Name '{$discount_code_data->label}' not found or inactive in the system"), 400);
                }
            }

            if($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'STORE_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

            if(!empty($validator_config)) {
                $validator = Validator::make($excel_data_item, $validator_config);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    foreach ($errors->all() as $message) {
                        $error_array[] = $message;
                    }
                }
            }

        }

        if(count($error_array) == 0) {
            $update_data = [
                "name" => $name,
                "tax_number" => $tax_number,
                "address" => $address,
                "pincode" => $pincode,
                "primary_contact" => $primary_contact_no,
                "secondary_contact" => $secondary_contact_no,
                "primary_email" => $primary_email,
                "secondary_email" => $secondary_email,
                "tax_code_id" => (isset($taxcode_data))?$taxcode_data->id:NULL,
                "discount_code_id" => (isset($discount_code_data))?$discount_code_data->id:NULL,
                "status" => (isset($status_data))?$status_data->value:'',
            ];
            $update_data = array_filter($update_data, 'skip_zero_array_filter');

            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function validate_update_supplier_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $supplier_code = $excel_data_item['supplier_code'];
        $supplier_name = $excel_data_item['supplier_name'];
        $contact_email = $excel_data_item['contact_email'];
        $contact_number = $excel_data_item['contact_number'];
        $address = $excel_data_item['address'];
        $pincode = $excel_data_item['pincode'];
        $status = $excel_data_item['status'];

        $validator_config = [];
        $validator_config['supplier_code'] = $this->get_validation_rules("codes", true);

        $supplier_data = SupplierModel::where('supplier_code', '=', trim($supplier_code))->first();
        if (!$supplier_data) {
            $error_array[] = trans('Invalid supplier code provided');
        }else{
            $slack = $supplier_data->slack;
        }

        if(isset($slack)){

            if($supplier_name != ""){
                $validator_config['supplier_name'] = $this->get_validation_rules("name_label", false);
            }

            if($contact_email != ""){
                $validator_config['contact_email'] = $this->get_validation_rules("email", false);
            }

            if($contact_number != ""){
                $validator_config['contact_number'] = $this->get_validation_rules("phone", false);
            }

            if($address != ""){
                $validator_config['address'] = $this->get_validation_rules("text", false);
            }

            if($pincode != ""){
                $validator_config['pincode'] = $this->get_validation_rules("pincode", false);
            }

            if($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'SUPPLIER_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

            if(!empty($validator_config)) {
                $validator = Validator::make($excel_data_item, $validator_config);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    foreach ($errors->all() as $message) {
                        $error_array[] = $message;
                    }
                }
            }
        }

        if(count($error_array) == 0) {
            $update_data = [
                "supplier_name" => $supplier_name,
                "address" => $address,
                "phone" => $contact_number,
                "email" => $contact_email,
                "pincode" => $pincode,
                "status" => (isset($status_data))?$status_data->value:'',
            ];
            $update_data = array_filter($update_data, 'skip_zero_array_filter');

            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];

        return $response;
    }

    public function validate_update_category_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $excel_data_item = array_map('trim', $excel_data_item);
        $category_code = $excel_data_item['category_code'];
        $category_name = $excel_data_item['category_name'];
        $description = $excel_data_item['description'];
        $status = $excel_data_item['status'];
        
        $validator_config = [];
        $validator_config['category_code'] = $this->get_validation_rules("codes", true);

        $category_data = CategoryModel::where('category_code', '=', trim($category_code))->first();
        if (!$category_data) {
            $error_array[] = trans('Invalid category code provided');
        }else{
            $slack = $category_data->slack;
        }

        if(isset($slack)){

            if($category_name != ""){
                $validator_config['category_name'] = $this->get_validation_rules("name_label", false);
            }

            if($description != ""){
                $validator_config['description'] = $this->get_validation_rules("text", false);
            }

            if($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'CATEGORY_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

        }
        
        if(count($error_array) == 0) {
            $update_data = [
                "label" => $category_name,
                "description" => $description,
                "status" => (isset($status_data))?$status_data->value:''
            ];
            $update_data = array_filter($update_data, 'skip_zero_array_filter');

            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }

        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];

        return $response;
    }

    public function validate_update_product_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $product_code = trim($excel_data_item['product_code']);
        $product_name = trim($excel_data_item['product_name']);
        $supplier_code = trim($excel_data_item['supplier_code']);
        $category_code = trim($excel_data_item['category_code']);
        //$tax_code = trim($excel_data_item['tax_code']);
        $tax_code="";
        $purchase_price_excluding_tax = trim($excel_data_item['purchase_price_excluding_tax']);
        $sale_price_excluding_tax = trim($excel_data_item['sale_price_excluding_tax']);
        $sale_price_including_tax = trim($excel_data_item['sale_price_including_tax']);
        $quantity = trim($excel_data_item['quantity']);
        $stock_alert_quantity = trim($excel_data_item['stock_alert_quantity']);
        $description = trim($excel_data_item['description']);
        $discount_code = trim($excel_data_item['discount_code']);
        $status = trim($excel_data_item['status']);
        $shows_in = trim($excel_data_item['shows_in']);
      
        $barcode = trim($excel_data_item['barcode']);
        // $is_unlimited_quantity = trim($excel_data_item['is_unlimited_quantity']);
        // $product_manufacturer_date = trim($excel_data_item['product_manufacturer_date']);
        // $product_expiry_date = trim($excel_data_item['product_expiry_date']);

       

        $validator_config = [];
        $validator_config['product_code'] = $this->get_validation_rules("codes", true);

        $product_data = ProductModel::where('product_code', '=', trim($product_code))->first();
        if (!$product_data) {
            $error_array[] = trans('Invalid product code provided');
        }else{
            $slack = $product_data->slack;
        }

        if(isset($slack)){

            if($product_name != ""){
                $validator_config['product_name'] = $this->get_validation_rules("name_label", false);
            }

            if($supplier_code != ""){
                $validator_config['supplier_code'] = $this->get_validation_rules("codes", false);
                
                $supplier_data = SupplierModel::select('id')
                ->where('supplier_code', '=', trim($supplier_code))
                ->active()
                ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Supplier not found or inactive in the system"), 400);
                }
            }

            if($category_code != ""){
                $validator_config['category_code'] = $this->get_validation_rules("codes", false);

                $category_data = CategoryModel::select('id')
                ->where('category_code', '=', trim($category_code))
                ->active()
                ->first();
                if (empty($category_data)) {
                    throw new Exception(trans("Category not found or inactive in the system"), 400);
                }
            }

            /*if($tax_code != ""){
                $validator_config['tax_code'] = $this->get_validation_rules("codes", false);

                $taxcode_data = TaxcodeModel::select('id')
                ->where('tax_code', '=', trim($tax_code))
                ->active()
                ->first();
                if (empty($taxcode_data)) {
                    throw new Exception("Taxcode not found or inactive in the system", 400);
                }
            }*/

            if($discount_code != ""){
                $validator_config['discount_code'] = $this->get_validation_rules("codes", false);

                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                ->where('discount_code', '=', trim($discount_code))
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->active()
                ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::where('discount_code', '=', trim($discount_code))->first();
                    throw new Exception(trans("Discount Name '{$discount_code_data->label}' not found or inactive in the system"), 400);
                }
            }
        
        
        //shows in validation
        if($shows_in == '' || is_null($shows_in)){
            $shows_in = 'pos';
        }
        $shows_in_arr=array("don't show anywhere","pos","invoice","both (pos & invoice)");

        if(!in_array(strtolower($shows_in),$shows_in_arr)){
          throw new Exception(trans("Shows In not found in the system"), 400);
         }
       
        $shows_in = array_search(strtolower($shows_in), $shows_in_arr);
        // end shows in

            if($purchase_price_excluding_tax != ""){
                $validator_config['purchase_price_excluding_tax'] = $this->get_validation_rules("numeric", false);
            }

            if($sale_price_excluding_tax != ""){
                $validator_config['sale_price_excluding_tax'] = $this->get_validation_rules("numeric", false);
            }
            if($sale_price_including_tax != ""){
                $validator_config['sale_price_including_tax'] = $this->get_validation_rules("numeric", false);
            }

            if($quantity != ""){
                $validator_config['quantity'] = $this->get_validation_rules("numeric", false);
            }

            if($stock_alert_quantity != ""){
                $validator_config['stock_alert_quantity'] = $this->get_validation_rules("numeric", false);
            }

            if($description != ""){
                $validator_config['description'] = $this->get_validation_rules("text", false);
            }
            if($quantity != ""){
                $validator_config['quantity'] = $this->get_validation_rules("numeric", false);
            }
     
            // if($product_manufacturer_date != ""){
            //     $validator_config['product_manufacturer_date'] = $this->get_validation_rules("date", false);
            // }
            // if($product_expiry_date != ""){
            //     $validator_config['product_expiry_date'] = $this->get_validation_rules("date", false);
            // }
            if($barcode != ""){
                $validator_config['barcode'] = $this->get_validation_rules("numeric", false);
            }
            // if($is_unlimited_quantity != "")
            // {
            //     $validator_config['is_unlimited_quantity'] = $this->get_validation_rules("boolean", false);
            // }
            

            if ($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'PRODUCT_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

        }

        if(count($error_array) == 0) {
            $update_data = [
                "name" => trim($product_name),
                "description" => $description,
                "category_id" => (isset($category_data))?$category_data->id:'',
                "supplier_id" => (isset($supplier_data))?$supplier_data->id:'',
              /*  "tax_code_id" => (isset($taxcode_data))?$taxcode_data->id:'',*/
                "discount_code_id" => (isset($discount_code_data))?$discount_code_data->id:NULL,
                "quantity" =>   trim($quantity),
                "alert_quantity" => $stock_alert_quantity,
                "purchase_amount_excluding_tax" => trim($purchase_price_excluding_tax),
                "sale_amount_excluding_tax" => trim($sale_price_excluding_tax),
                "sale_amount_including_tax" => trim($sale_price_including_tax),
                "barcode" => trim($barcode),
                // "product_manufacturer_date" => isset($product_manufacturer_date) ? Carbon::createFromFormat('d-m-Y', $product_manufacturer_date)->format('Y-m-d') : '',
                // "product_expiry_date" => isset($product_expiry_date) ? Carbon::createFromFormat('d-m-Y', $product_expiry_date)->format('Y-m-d') : '', 
                "shows_in"=>$shows_in,          
                "status" => (isset($status_data))?$status_data->value:''
            ];
         
            $update_data = array_filter($update_data, 'skip_zero_array_filter');
   
            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }
    
    public function validate_update_ingredient_data($excel_data_item)
    {
        $response = [];
        $data = [];
        $error_array  = [];

        $product_code = $excel_data_item['product_code'];
        $product_name = $excel_data_item['product_name'];
        $supplier_code = $excel_data_item['supplier_code'];
        $category_code = $excel_data_item['category_code'];
        //$tax_code = $excel_data_item['tax_code'];
        $tax_code="";
        $purchase_price_excluding_tax = $excel_data_item['purchase_price_excluding_tax'];
        $sale_price_excluding_tax = $excel_data_item['sale_price_excluding_tax'];
        $sale_price_including_tax = $excel_data_item['sale_price_including_tax'];
        $quantity = $excel_data_item['quantity'];
        $stock_alert_quantity = $excel_data_item['stock_alert_quantity'];
        $description = $excel_data_item['description'];
        $discount_code = $excel_data_item['discount_code'];
        $status = $excel_data_item['status'];
        $shows_in = $excel_data_item['shows_in'];

        $validator_config = [];
        $validator_config['product_code'] = $this->get_validation_rules("codes", true);

        $product_data = ProductModel::where('product_code', '=', trim($product_code))->isIngredient()->first();
        if (!$product_data) {
            $error_array[] = trans('Invalid product code provided (The product might not be an ingredient or the product might not exist in the system)');
        }else{
            $slack = $product_data->slack;
        }

        if(isset($slack)){

            if($product_name != ""){
                $validator_config['product_name'] = $this->get_validation_rules("name_label", false);
            }

            if($supplier_code != ""){
                $validator_config['supplier_code'] = $this->get_validation_rules("codes", false);
                
                $supplier_data = SupplierModel::select('id')
                ->where('supplier_code', '=', trim($supplier_code))
                ->active()
                ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Supplier not found or inactive in the system"), 400);
                }
            }

        //shows in validation
        $shows_in_arr=array("don't show anywhere","pos","invoice","both (pos & invoice)");
        if($shows_in == '' || is_null($shows_in)){
            $shows_in = 'pos';
        }
        if(!in_array(strtolower($shows_in),$shows_in_arr)){
          throw new Exception(trans("Shows In not found in the system"), 400);
         }
       
        $shows_in = array_search(strtolower($shows_in), $shows_in_arr);
        // end shows in

            if($category_code != ""){
                $validator_config['category_code'] = $this->get_validation_rules("codes", false);

                $category_data = CategoryModel::select('id')
                ->where('category_code', '=', trim($category_code))
                ->active()
                ->first();
                if (empty($category_data)) {
                    throw new Exception(trans("Category not found or inactive in the system"), 400);
                }
            }

            /*if($tax_code != ""){
                $validator_config['tax_code'] = $this->get_validation_rules("codes", false);

                $taxcode_data = TaxcodeModel::select('id')
                ->where('tax_code', '=', trim($tax_code))
                ->active()
                ->first();
                if (empty($taxcode_data)) {
                    throw new Exception("Taxcode not found or inactive in the system", 400);
                }
            }*/

            if($discount_code != ""){
                $validator_config['discount_code'] = $this->get_validation_rules("codes", false);

                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                ->where('discount_code', '=', trim($discount_code))
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->active()
                ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::where('discount_code', '=', trim($discount_code))->first();
                    throw new Exception(trans("Discount Name '{$discount_code_data->label}' not found or inactive in the system"), 400);
                }
            }

            if($purchase_price_excluding_tax != ""){
                $validator_config['purchase_price_excluding_tax'] = $this->get_validation_rules("numeric", false);
            }

            if($sale_price_excluding_tax != ""){
                $validator_config['sale_price_excluding_tax'] = $this->get_validation_rules("numeric", false);
            }
            if($sale_price_including_tax != ""){
                $validator_config['sale_price_including_tax'] = $this->get_validation_rules("numeric", false);
            }

            if($quantity != ""){
                $validator_config['quantity'] = $this->get_validation_rules("numeric", false);
            }

            if($stock_alert_quantity != ""){
                $validator_config['stock_alert_quantity'] = $this->get_validation_rules("numeric", false);
            }

            if($description != ""){
                $validator_config['description'] = $this->get_validation_rules("text", false);
            }

            if ($status != "") {
                $status_data = MasterStatusModel::select('value')->where([
                    ['value_constant', '=', strtoupper($status)],
                    ['key', '=', 'PRODUCT_STATUS']
                ])->active()->first();
                if (!$status_data) {
                    $error_array[] = trans('Invalid status provided');
                }
            }

        }

        if(count($error_array) == 0) {
            $update_data = [
                "name" => $product_name,
                "description" => $description,
                "category_id" => (isset($category_data))?$category_data->id:'',
                "supplier_id" => (isset($supplier_data))?$supplier_data->id:'',
              /*  "tax_code_id" => (isset($taxcode_data))?$taxcode_data->id:'',*/
                "discount_code_id" => (isset($discount_code_data))?$discount_code_data->id:NULL,
                "quantity" => $quantity,
                "alert_quantity" => $stock_alert_quantity,
                "purchase_amount_excluding_tax" => $purchase_price_excluding_tax,
                "sale_amount_excluding_tax" => $sale_price_excluding_tax,
                "sale_amount_including_tax" => $sale_price_including_tax,
                "is_ingredient" => 1,
                "shows_in"=>$shows_in,
                "status" => (isset($status_data))?$status_data->value:''
            ];
            $update_data = array_filter($update_data, 'skip_zero_array_filter');

            $data = [
                "update_data" => $update_data,
                "update_key" => (isset($slack))?$slack:''
            ];
        }
        
        $response = [
            "error_list" => $error_array,
            "data" => $data
        ];
        return $response;
    }

    public function generate_reference_sheet(Request $request){
        try {
            $view_path = Config::get('constants.upload.imports.view_path');

            $download_link = '';
                
            $date = Carbon::now();
            $current_date = $date->format('d-m-Y H:i');
            $store = $request->logged_user_store_code.'-'.$request->logged_user_store_name;

            $data = [];

            $data['role_codes'] = RoleModel::select('role_code', 'label')->resolveSuperAdminRole()->active()->get()->toArray();

            $data['store_codes'] = StoreModel::select('store_code', 'name')->active()->get()->toArray();

            $data['supplier_codes'] = SupplierModel::select('supplier_code', 'name')->active()->get()->toArray();

            $data['category_codes'] = CategoryModel::select('category_code', 'label')->active()->get()->toArray();

            $data['tax_codes'] = TaxcodeModel::select('tax_code', 'label', 'total_tax_percentage')->active()->get()->toArray();

            $currentdate = date('Y-m-d H:i:sa');
            $data['discount_codes'] = DiscountcodeModel::select('discount_code', 'label', 'discount_percentage')
            ->whereRaw("discounttype='code'")
            ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
            ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")->active()->get()->toArray();

            $data['product_codes'] = ProductModel::select('product_code', 'products.name')
            ->categoryJoin()
            ->supplierJoin()
            ->taxcodeJoin()
            ->categoryActive()
            ->supplierActive()
            ->taxcodeActive()
            ->active()->get()->toArray();

            $data['user_codes'] = UserModel::select('user_code', 'fullname')
            ->hideSuperAdminRole()
            ->active()->get()->toArray();

            $data['statuses'] = MasterStatusModel::select('key', DB::raw('GROUP_CONCAT(value_constant) AS status_values'))
            ->whereIn('key', ['USER_STATUS', 'STORE_STATUS', 'SUPPLIER_STATUS', 'CATEGORY_STATUS', 'PRODUCT_STATUS'])
            ->active()->groupBy('key')->get()->toArray();

            $print_ref_page = view('import.reference_sheet', ['data' => $data, 'store' => $store, 'date' => $current_date])->render();

            $pdf_filename = "reference_sheet_".date('Y_m_d_h_i_s')."_".uniqid().".pdf";
            
            ini_set("pcre.backtrack_limit", "5000000");
            set_time_limit(180);

            $mpdf_config = [
                'mode'          => 'utf-8',
                'format'        => 'A4',
                'orientation'   => 'P',
                'margin_left'   => 1,
                'margin_right'  => 1,
                'margin_top'    => 1,
                'margin_bottom' => 1,
                'margin_footer' => 1,
                'tempDir' => storage_path()."/pdf_temp" 
            ];
            
            $path = public_path('storage/imports');

            Storage::makeDirectory('imports');

            $mpdf = new Mpdf($mpdf_config);
            $mpdf->curlAllowUnsafeSslRequests = true;
            $mpdf->SetDisplayMode('real');
            $mpdf->SetHTMLFooter('<div class="footer">store: '.$store.' | generated on: '.$current_date.' | page: {PAGENO}/{nb}</div>');
            $mpdf->WriteHTML($print_ref_page);
            $mpdf->Output($path .'/'. $pdf_filename, \Mpdf\Output\Destination::FILE);

            $download_link = asset($view_path.$pdf_filename);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Import and update by upload reference sheet generated successfully"),
                    'link' => ($download_link != '')?$download_link:''
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
}