<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Brand as BrandModel;
use App\Models\Category as CategoryModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementUnit as MeasurementUnitModel;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\Product as ProductModel;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\Modifier as ModifierModel;
use App\Models\ProductModifier as ProductModifierModel;
use App\Models\Menu as MenuModel;
use App\Models\Price;
use App\Models\ProductPrice;
use App\Models\RoleMenu as RoleMenuModel;
use App\Models\UserMenu as UserMenuModel;
use App\Models\Store as StoreModel;
use App\Models\WebsiteSetting as WebsiteSettingModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Taxcode as TaxcodeModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Models\SettingEmail as SettingEmailModel;
use App\Models\SettingApp as SettingAppModel;
use App\Models\SettingSms as SettingSmsModel;
use App\Models\SmsSetting as SmsSettingModel;
use App\Models\TaxcodeType;
use App\Models\WebsiteClient;
use App\Providers\MailServiceProvider;

class Setting extends Controller
{

    public function add_setting_email(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_EMAIL_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_email_setting_request($request);

            $request->type = 'SIMPLE';

            Artisan::call('config:clear');

            DB::beginTransaction();

            $email_setting = [
                "slack" => $this->generate_slack("setting_mail"),
                "type" => $request->type,
                "driver" => $request->driver,
                "host" => $request->host,
                "port" => $request->port,
                "username" => $request->username,
                "password" => $request->password,
                "encryption" => $request->encryption,
                "from_email" => $request->from_email,
                "from_email_name" => $request->from_email_name,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

            $setting_id = SettingEmailModel::create($email_setting)->id;

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Email settings added successfully"),
                    "data"    => $email_setting['slack']
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function update_setting_email(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_EMAIL_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_email_setting_request($request);

            $email_setting_data_exists = SettingEmailModel::select('id')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            if (empty($email_setting_data_exists)) {
                throw new Exception(trans("Trying to update invalid email setting"), 400);
            }

            $request->type = 'SIMPLE';

            Artisan::call('config:clear');

            DB::beginTransaction();

            $email_setting = [
                "type" => $request->type,
                "driver" => $request->driver,
                "host" => $request->host,
                "port" => $request->port,
                "username" => $request->username,
                "password" => $request->password,
                "encryption" => $request->encryption,
                "from_email" => $request->from_email,
                "from_email_name" => $request->from_email_name,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];

            $action_response = SettingEmailModel::where('slack', $slack)
                ->update($email_setting);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Email settings updated successfully"),
                    "data"    => $slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function update_setting_app(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_APP_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_app_setting_request($request);

            Artisan::call('config:clear');

            DB::beginTransaction();

            $app_setting = SettingAppModel::select('*')->first();
            $company_logo_file = (isset($app_setting->company_logo)) ? $app_setting->company_logo : '';
            $invoice_print_logo_file = (isset($app_setting->invoice_print_logo)) ? $app_setting->invoice_print_logo : '';
            $navbar_logo_file = (isset($app_setting->navbar_logo)) ? $app_setting->navbar_logo : '';
            $favicon_file = (isset($app_setting->favicon)) ? $app_setting->favicon : '';

            if ($request->hasFile('company_logo')) {

                $remove_company_logo_file = $company_logo_file;

                Storage::disk('company')->delete(
                    [
                        $remove_company_logo_file
                    ]
                );

                $upload_dir = Config::get('constants.upload.company.upload_path');
                $company_logo = $request->company_logo;

                $extension = $company_logo->getClientOriginalExtension();
                $company_logo_file_name = 'logo_company' . '.' . $extension;
                $path = Storage::disk('company')->putFileAs('/', $company_logo, $company_logo_file_name);
                $company_logo_file_name = basename($path);

                $image = Image::make($company_logo);
                $file_path = $upload_dir . $company_logo_file_name;
                $image->save($file_path);
                $image->destroy();

                $company_logo_file = (isset($company_logo_file_name)) ? $company_logo_file_name : '';
            }

            if ($request->hasFile('invoice_print_logo')) {
                $remove_invoice_print_logo_file = $invoice_print_logo_file;

                Storage::disk('company')->delete(
                    [
                        $remove_invoice_print_logo_file
                    ]
                );

                $upload_dir = Config::get('constants.upload.company.upload_path');
                $invoice_print_logo = $request->invoice_print_logo;

                $extension = $invoice_print_logo->getClientOriginalExtension();
                $invoice_print_logo_file_name = 'logo_invoice_print' . '.' . $extension;
                $path = Storage::disk('company')->putFileAs('/', $invoice_print_logo, $invoice_print_logo_file_name);
                $invoice_print_logo_file_name = basename($path);

                $image = Image::make($invoice_print_logo);
                $file_path = $upload_dir . $invoice_print_logo_file_name;
                //$image->resize(160, 80);
                $image->save($file_path);
                $image->destroy();

                $invoice_print_logo_file = (isset($invoice_print_logo_file_name)) ? $invoice_print_logo_file_name : '';
            }

            if ($request->hasFile('navbar_logo')) {

                $remove_navbar_logo_file = $navbar_logo_file;

                Storage::disk('company')->delete(
                    [
                        $remove_navbar_logo_file
                    ]
                );

                $upload_dir = Config::get('constants.upload.company.upload_path');
                $navbar_logo = $request->navbar_logo;

                $extension = $navbar_logo->getClientOriginalExtension();
                $navbar_logo_file_name = 'logo_navbar' . '.' . $extension;
                $path = Storage::disk('company')->putFileAs('/', $navbar_logo, $navbar_logo_file_name);
                $navbar_logo_file_name = basename($path);

                $image = Image::make($navbar_logo);
                $file_path = $upload_dir . $navbar_logo_file_name;
                $image->save($file_path);
                $image->destroy();

                $navbar_logo_file = (isset($navbar_logo_file_name)) ? $navbar_logo_file_name : '';
            }

            if ($request->hasFile('favicon')) {

                $remove_favicon_file = $favicon_file;

                Storage::disk('company')->delete(
                    [
                        $remove_favicon_file
                    ]
                );

                $upload_dir = Config::get('constants.upload.company.upload_path');
                $favicon = $request->favicon;

                $extension = $favicon->getClientOriginalExtension();
                $favicon_file_name = 'favicon' . '.' . $extension;
                $path = Storage::disk('company')->putFileAs('/', $favicon, $favicon_file_name);
                $favicon_file_name = basename($path);

                $image = Image::make($favicon);
                $file_path = $upload_dir . $favicon_file_name;
                $image->save($file_path);
                $image->destroy();

                $favicon_file = (isset($favicon_file_name)) ? $favicon_file_name : '';
            }


            $app_setting = [
                "company_name" => $request->company_name,
                "app_date_time_format" => $request->date_time_format,
                "app_date_format" => $request->date_format,
                "company_logo" => $company_logo_file,
                "invoice_print_logo" => $invoice_print_logo_file,
                "navbar_logo" => $navbar_logo_file,
                "favicon" => $favicon_file,
                "updated_by" => $request->logged_user_id
            ];

            $action_response = SettingAppModel::where('id', 1)->update($app_setting);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("App settings updated successfully"),
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function remove_company_image(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_APP_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $type = $request->type;
            $app_setting = SettingAppModel::select('company_name', 'company_logo', 'invoice_print_logo', 'navbar_logo', 'favicon')->first();

            switch ($type) {
                case 'company_logo':
                    if ($app_setting->company_logo != '') {
                        Storage::disk('company')->delete(
                            [
                                $app_setting->company_logo
                            ]
                        );
                    }

                    $app_setting_array = [
                        'company_logo' => '',
                    ];
                    break;
                case 'invoice_print_logo':
                    if ($app_setting->invoice_print_logo != '') {
                        Storage::disk('company')->delete(
                            [
                                $app_setting->invoice_print_logo
                            ]
                        );
                    }

                    $app_setting_array = [
                        'invoice_print_logo' => '',
                    ];
                    break;
                case 'navbar_logo':
                    if ($app_setting->navbar_logo != '') {
                        Storage::disk('company')->delete(
                            [
                                $app_setting->navbar_logo
                            ]
                        );
                    }

                    $app_setting_array = [
                        'navbar_logo' => '',
                    ];
                    break;
                case 'favicon':
                    if ($app_setting->favicon != '') {
                        Storage::disk('company')->delete(
                            [
                                $app_setting->favicon
                            ]
                        );
                    }

                    $app_setting_array = [
                        'favicon' => '',
                    ];
                    break;
            }


            $data = SettingAppModel::where('company_name', $app_setting->company_name)->update($app_setting_array);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Company Logo removed successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function add_setting_sms(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_SMS_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_sms_setting_request($request);

            DB::beginTransaction();

            $sms_setting = [
                "slack" => $this->generate_slack("sms_gateway_settings"),
                "api_key" => $request->api_key,
                "user_name" => $request->user_name,
                "sender_name" => $request->sender_name,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

            $setting_id = SmsSettingModel::create($sms_setting)->id;

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("SMS settings added successfully"),
                    "data"    => $sms_setting['slack']
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function update_setting_sms(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_SMS_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_sms_setting_request($request);

            $sms_setting_data_exists = SmsSettingModel::select('id')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            if (empty($sms_setting_data_exists)) {
                throw new Exception(trans("Trying to update invalid sms setting"), 400);
            }

            DB::beginTransaction();

            $sms_setting = [
                "api_key" => $request->api_key,
                "user_name" => $request->user_name,
                "sender_name" => $request->sender_name,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];

            $action_response = SmsSettingModel::where('slack', $slack)
                ->update($sms_setting);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("SMS settings updated successfully"),
                    "data"    => $slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function validate_email_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'host' => $this->get_validation_rules("name_label", true),
            'port' => $this->get_validation_rules("name_label", true),
            'username' => $this->get_validation_rules("name_label", true),
            'password' => $this->get_validation_rules("name_label", true),
            'encryption' => $this->get_validation_rules("name_label", true),
            'from_email' => $this->get_validation_rules("email", true),
            'from_email_name' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function validate_app_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => $this->get_validation_rules("name_label", true),
            'date_time_format' => 'max:50|required',
            'date_format' => 'max:50|required',
            'company_logo' => $this->get_validation_rules("company_logo", false),
            'invoice_print_logo' => $this->get_validation_rules("invoice_print_logo", false),
            'navbar_logo' => $this->get_validation_rules("navbar_logo", false),
            'favicon' => $this->get_validation_rules("favicon", false),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function validate_sms_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'max:150|required',
            'user_name' => 'max:150|required',
            'sender_name' => 'max:150|required',
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function total_dropdown_list()
    {

        try {

            $data['suppliers'] = SupplierModel::select('slack', 'supplier_code', 'name')->orderBy('id', 'asc')->active()->get();

            $data['categories'] = CategoryModel::select('slack', 'category_code', 'id', 'parent', 'label')->categoryStore()->orderBy('id', 'asc')->active()->get();

            $data['taxcodes'] = TaxcodeModel::select(DB::raw("CONVERT(id,CHAR) as tax_code_id"),'slack', 'tax_code', 'label','total_tax_percentage')->orderBy('id', 'asc')->active()->get();

            $currentdate = date('Y-m-d H:i:sa');
            $data['discount_codes'] = DiscountcodeModel::select('id','slack', 'discount_applied_on', 'limit_on_discount', 'discount_applicable_products', 'discount_applicable_categories', 'discount_not_applicable_products', 'discount_code', 'label')
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->orderBy('id', 'asc')->active()->get();

            // $data['measurement_units'] = MeasurementUnitModel::select('slack', 'unit_code', 'label')->orderBy('id', 'asc')->active()->get();

            $data['measurement'] = MeasurementModel::select('id', 'slack', 'measurement_category_id', 'label')->orderBy('id', 'asc')->active()->get();

            $data['brand'] = BrandModel::select('slack', 'id', 'label')->orderBy('id', 'asc')->active()->get();

            $data['measurement_categories_data'] = MeasurementCategoryModel::select('id', 'slack', 'label')->orderBy('id', 'asc')->active()->get();

            // $data['product'] = ProductModel::select('slack', 'id', 'name','store_id','main_category_id','category_id')->orderBy('id', 'asc')->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Total Drop Down List"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }


    /*
        -------------------------------
        ------ HELPER FUNCTIONS -------
        -------------------------------
    */

    public function copy_products_and_ingredients_between_stores(Request $request)
    {
        
        $product_count = 0;
        $product_update_count = 0;
        $product_modifier_count = 0;
        $product_ingredient_count = 0;
        $product_price_count = 0;
        $tax_code_count = 0;
        $product_ids = [];

        $move_from_store_id = $request->move_from_store_id;
        $move_to_store_id = $request->move_to_store_id;
        $transfer_products_only = $request->transfer_products_only;

        if (!isset($transfer_products_only)) {
            throw new Exception('Please Choose What to Transfer', 400);
        }

        $move_from_store = StoreModel::where('id',$move_from_store_id)->select('name')->first();
        $move_to_store = StoreModel::where('id',$move_to_store_id)->select('name')->first();

        // transfer products
        $products = ProductModel::with('product_modifiers','ingredients')->withoutGlobalScopes()->where('store_id', $move_from_store_id)->get();
        
        DB::beginTransaction();

        foreach ($products as $product) {

            $is_exists = ProductModel::withoutGlobalScopes()
            ->where('store_id',$move_to_store_id)
            ->whereRaw('LOWER(name) = ?', [trim( strtolower($product->name) )])
            ->where('is_ingredient',$product->is_ingredient)
            ->first();

            if(empty($is_exists)){
                
                // echo "not exists: ".$product->name."(".$product->is_ingredient.")<br>";

                /* Replicating Products */
                $new_product = ProductModel::withoutGlobalScopes()->where('id',$product->id)->first();
                $newProduct = $new_product->replicate();
                $newProduct->slack = $this->generate_slack('products');
                $newProduct->store_id = $move_to_store_id;
                $newProduct->save();

                $product_count++;
                
                
                /* Replicating Product's Modifiers */
                if(isset($product->product_modifiers)){
                    
                    foreach($product->product_modifiers as $product_modifier){
                        
                        $newProductModifier = $product_modifier->replicate();
                        $newProductModifier->slack = $this->generate_slack('product_modifiers');
                        $newProductModifier->product_id = $newProduct->id;
                        $newProductModifier->save();  
                        $product_modifier_count++;

                        
                    }
                }

                /* Replicating Product's Taxes */
                if( !is_null($product->tax_code_id) ){

                    if($product->tax_code_id == 0){
                    
                        // assigning existing tax code id to transferring product 
                        ProductModel::withoutGlobalScopes()
                        ->where('id',$newProduct->id)
                        ->update(['tax_code_id'=> $move_to_store->tax_code_id]);
                    
                    }else{

                        $from_store_tax_code = TaxcodeModel::with('tax_components')->withoutGlobalScopes()->where('id',$product->tax_code_id)->first();
    
                        $to_store_tax_code = TaxcodeModel::withoutGlobalScopes()
                        ->where('store_id',$move_to_store_id)
                        ->whereRaw('LOWER(tax_code) = ?', [trim( strtolower($from_store_tax_code->tax_code) )])
                        ->where('total_tax_percentage',$from_store_tax_code->total_tax_percentage)
                        ->select('id')
                        ->first();
    
                        if(!isset($to_store_tax_code)){
    
                            // creating new tax code for transferring store
                            $newTaxCode = $from_store_tax_code->replicate();
                            $newTaxCode->slack = $this->generate_slack('tax_codes');
                            $newTaxCode->store_id = $move_to_store_id;
                            $newTaxCode->save();
                            $tax_code_count++;
                            
                            foreach($from_store_tax_code->tax_components as $tax_code_type){
                                $newTaxCodeType = $tax_code_type->replicate();
                                $newTaxCodeType->tax_code_id = $newTaxCode->id;
                                $newTaxCodeType->save();
                            }
    
                            // assigning newly created tax code id to transferring product 
                            ProductModel::withoutGlobalScopes()
                            ->where('id',$newProduct->id)
                            ->update(['tax_code_id'=> $newTaxCode->id]);
                        }else{
    
                            // assigning existing tax code id to transferring product 
                            ProductModel::withoutGlobalScopes()
                            ->where('id',$newProduct->id)
                            ->update(['tax_code_id'=> $to_store_tax_code->id]);
                        }
                    }


                }

                if($product->is_ingredient == 0) {
                    $product_ids[] = $newProduct->id;
                }
                
            }else{
                $is_exists->update([
                    'quantity' => ( isset($product->quantity) ) ? $product->quantity : "-1",
                    'purchase_amount_excluding_tax' => $product->purchase_amount_excluding_tax,
                    'sale_amount_excluding_tax' => $product->sale_amount_excluding_tax,
                    'sale_amount_including_tax' => $product->sale_amount_including_tax,
                    'price_type' => $product->price_type,
                    'sales_price_including_boolean_and_price' => $product->sales_price_including_boolean_and_price
                ]);
                $product_update_count++;
            }

        }
       
        // Replicating Product's Ingredients
        
        $products = ProductModel::withoutGlobalScopes()->whereIn('id', $product_ids)->get();

        foreach ($products as $product) {

            $from_product = ProductModel::with('ingredients')
            ->withoutGlobalScopes()
            ->where('store_id',$move_from_store_id)
            ->whereRaw('LOWER(name) = ?', [trim( strtolower($product->name) )])
            ->where('is_ingredient',$product->is_ingredient)
            ->first();
            
            if(isset($from_product) && $from_product->ingredients){
                foreach($from_product->ingredients as $ingredient){

                    $from_store_product_ingredient = ProductModel::withoutGlobalScopes()
                    ->where('id',$ingredient->ingredient_product_id)
                    ->where('store_id',$move_from_store_id)
                    ->select('name','category_id','store_id')
                    ->first();
                    
                    $to_store_product_ingredient = ProductModel::withoutGlobalScopes()
                    ->whereRaw('LOWER(name) = ?', [trim( strtolower($from_store_product_ingredient->name) )])
                    ->where('is_ingredient',1)
                    ->where('category_id',$from_store_product_ingredient->category_id)
                    ->where('store_id',$move_to_store_id)
                    ->select('id')
                    ->first();

                    if(isset($to_store_product_ingredient)){
                        
                        // Replicating Product's Ingredients
                        $newProductIngredient = $ingredient->replicate();
                        $newProductIngredient->slack = $this->generate_slack('product_ingredients');
                        $newProductIngredient->product_id = $product->id;
                        $newProductIngredient->ingredient_product_id = $to_store_product_ingredient->id;
                        $newProductIngredient->save();
                        $product_ingredient_count++;

                    }

                }
            }
            
        }

        
        // Replicating Product's Multiple Prices
        
        $all_products = ProductModel::withoutGlobalScopes()->whereIn('id', $product_ids)->get();

        foreach ($all_products as $product) {

            $from_product = ProductModel::with('product_prices')
            ->withoutGlobalScopes()
            ->where('store_id',$move_from_store_id)
            ->whereRaw('LOWER(name) = ?', [trim( strtolower($product->name) )])
            ->where('is_ingredient',$product->is_ingredient)
            ->first();

            if(isset($from_product) && $from_product->product_prices){
                foreach($from_product->product_prices as $product_price){

                    $from_store_product_ingredient = ProductModel::withoutGlobalScopes()
                    ->where('id',$product_price->product_id)
                    ->where('store_id',$move_from_store_id)
                    ->select('name','category_id','store_id')
                    ->first();
                    
                    $to_store_product_ingredient = ProductModel::withoutGlobalScopes()
                    ->whereRaw('LOWER(name) = ?', [trim( strtolower($from_store_product_ingredient->name) )])
                    ->where('category_id',$from_store_product_ingredient->category_id)
                    ->where('store_id',$move_to_store_id)
                    ->select('id')
                    ->first();

                    $from_price_id = Price::withoutGlobalScopes()->where('id',$product_price->price_id)->first();

                    if(isset($from_price_id)){
                        $to_price_id = Price::withoutGlobalScopes()->where('name',$from_price_id->name)->where('store_id',$move_to_store_id)->first();
                    }
                    
                    if( isset($to_price_id) && isset($to_store_product_ingredient)){
                        
                        // Replicating Product's multiple prices
                        $newProductPrice = $product_price->replicate();
                        $newProductPrice->slack = $this->generate_slack('product_prices');
                        $newProductPrice->product_id = $product->id;
                        $newProductPrice->price_id = $to_price_id->id;
                        $newProductPrice->save();
                        $product_price_count++;

                    }

                }
            }
            
        }
        
        DB::commit();

        return response()->json([
            'Action' => 'Products, Products Ingredients, Products Modifiers and Product Taxes Replicated Sucessfully',
            'Transffered From Store' => $move_from_store->name,
            'Transffered To Store' => $move_to_store->name,
            'Number of Products Created' => $product_count,
            'Number of Products Updated' => $product_update_count,
            'Number of Modifiers Created' => $product_modifier_count,
            'Number of Ingredients Created' => $product_ingredient_count,
            'Number of Multiple Prices Created' => $product_price_count,
            'Number of Tax Codes Created' => $tax_code_count
        ], 200);
    
    }

    public function reset_admin_menus()
    {

        try {

            $menus = MenuModel::all();

            DB::beginTransaction();
            // remove all existing menus assigned to admin
            RoleMenuModel::where('role_id', 1)->delete();
            UserMenuModel::where('user_id', 1)->delete();

            $data['updated_rows'] = 0;

            foreach ($menus as $menu) {
                RoleMenuModel::create([
                    'role_id' => 1,
                    'menu_id' => $menu->id,
                ]);
                UserMenuModel::create([
                    'user_id' => 1,
                    'menu_id' => $menu->id,
                ]);
                $data['updated_rows']++;
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Admin menus updated successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    // public function copy_modifiers_between_stores_v2(Request $request){

    //     $last_product_modifier_id = $request->last_product_modifier_id;

    //     $product_modifiers = ProductModifierModel::withoutGlobalScopes()->where('id','<=',$last_product_modifier_id)->get();

    //     $existing_modifier_count = $product_modifiers->count();
    //     $new_modifier_count = 0;

    //     if(isset($product_modifiers)){

    //         foreach($product_modifiers as $product_modifier){

    //             $existing_product = ProductModel::withoutGlobalScopes()->where('id',$product_modifier->product_id)->first();

    //             $other_products = ProductModel::withoutGlobalScopes()->where('id','!=',$existing_product->id)->where('name',$existing_product->name)->where('name_ar',$existing_product->name_ar)->get();

    //             foreach($other_products as $other_product){

    //                 $already_exists = ProductModifierModel::where('product_id',$other_product->id)->where('modifier_id',$product_modifier->modifier_id)->first();

    //                 if(!isset($already_exists)){

    //                     $new_product_modifier = new ProductModifierModel();
    //                     $new_product_modifier->slack = $this->generate_slack('product_modifiers');
    //                     $new_product_modifier->product_id = $other_product->id;
    //                     $new_product_modifier->modifier_id = $product_modifier->modifier_id;
    //                     $new_product_modifier->status = $product_modifier->status;
    //                     $new_product_modifier->created_by = $product_modifier->created_by;
    //                     $new_product_modifier->save();

    //                     $new_modifier_count++;

    //                 }


    //             }

    //         }

    //     }

    //     return response()->json([
    //         'existing_modifier_count' => $existing_modifier_count,
    //         'new_modifier_count' => $new_modifier_count,
    //         'total_modifiers' => $existing_modifier_count + $new_modifier_count,
    //     ]);

    // }

    /* to fetch current app version of wosul application */
    public function get_app_version()
    {
        $data['live_versions'] = [];

        try {

            $ios_version =  WebsiteSettingModel::where('key', 'IOS_APP_VERSION')->first();
            if (isset($ios_version)) {
                $data['live_versions']['iOS'] = $ios_version->value;
            }
            $android_version =  WebsiteSettingModel::where('key', 'ANDROID_APP_VERSION')->first();
            if (isset($android_version)) {
                $data['live_versions']['android'] = $android_version->value;
            }
            $is_mandate_update =  WebsiteSettingModel::where('key', 'IS_MANDATE_UPDATE')->first();
            if (isset($is_mandate_update)) {
                $data['live_versions']['is_mandate_update'] = $is_mandate_update->value;
            }

            return response()->json($this->generate_response(
                array(
                    "message" => 'Data Found',
                    "data"    => $data
                ),
                'SUCCESS'
            ));
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
