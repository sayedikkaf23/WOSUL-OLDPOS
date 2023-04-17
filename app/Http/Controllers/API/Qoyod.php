<?php

namespace App\Http\Controllers\API;

use App\Models\MasterAccountType;
use App\Models\ProductIngredient;
use App\Models\QoyodCategory;
use App\Models\QoyodInventory;
use App\Models\QoyodMesurmentUnit;
use App\Models\QoyodProduct;
use App\Models\QoyodUsersAccount;
use App\Models\QoyodVendor;
use App\Models\QoyodCustomer;
use Exception;
use Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\SettingApp;
use Illuminate\Support\Str;
use App\Http\Traits\QoyodApiTrait;
use App\Models\Account;
use App\Models\Store;
use App\Models\Category;
use App\Models\Measurement;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;

class Qoyod extends Controller
{
    use QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if(!check_access(['A_ADD_QOYOD'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }
            $this->validate_request($request);
            $result = $this->qoyod_verify_key($request->api_key);
            if($result['status']==1){
                $qoyod = SettingApp::select('id','qoyod_api_key')->first();
                //Update
                DB::beginTransaction();
                SettingApp::where('id',$qoyod->id)->update(['qoyod_api_key' => $request->api_key,'qoyod_status'=>1]);
                //store the id in session
                $request->session()->put('qoyod_status', 1);
                $request->session()->put('qoyod_api_key', $request->api_key);
                DB::commit();

                if($qoyod->qoyod_api_key!=''){
                    return response()->json($this->generate_response(
                        array(
                            "message" => trans("Your qoyod account refreshed successfully!"),
                            "data"    => ''
                        ), 'SUCCESS'
                    ));
                }else{
                    return response()->json($this->generate_response(
                        array(
                            "message" => trans("Your qoyod account connected successfully!"),
                            "data"    => ''
                        ), 'SUCCESS'
                    ));
                }
            }else{
                throw new Exception("API Key not valid!", 400);
            }

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * sync_qoyod_account is use for sync the qoyod business account
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sync_qoyod_data(Request $request){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '30000');
        try{
            if(!check_access(['A_ADD_QOYOD'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $qoyod_sync = $this->qoyod_is_sync();
            if($qoyod_sync['status']) {
                $this->sync_account();
                $this->sync_inventory();
                $this->sync_vendor();
                $this->sync_customer();
                $this->sync_category();
                $this->sync_unit();
                $this->sync_ingredient();
                $this->sync_product();

                $qoyod = SettingApp::select('id')->first();
                DB::beginTransaction();
                SettingApp::where('id',$qoyod->id)->update(['qoyod_last_sync_time' => date('Y-m-d H:i:s')]);
                DB::commit();
            }
            return response()->json($this->generate_response(
                array(
                    "message" => trans("All Masters data sync successfully"),
                    "data"    => ''
                ), 'SUCCESS'
            ));

        } catch (Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    //Account Sync
    public function sync_account(){
        $store_accounts = Account::withoutGlobalScopes()->select('accounts.id as account_id','accounts.account_code','accounts.account_type','accounts.label','accounts.description','qoyod_users_accounts.qoyod_account_id')->QoyodAccountJoin()->Active()->get();

        foreach ($store_accounts as $store_account){
            if(!isset($store_account->qoyod_account_id) && $store_account->qoyod_account_id==''){
                $this->qoyod_create_account($store_account);
            }
        }

        //getting the account

        $search_cash_account = $this->qoyod_get_account(0,'q[code_eq]=110101');
        if($search_cash_account['status']){
            $cash_account_id = $search_cash_account['data']->accounts[0]->id;
            $cash_account = QoyodUsersAccount::where('qoyod_account_id',$cash_account_id)->first();
            $wosul_cash_account = Account::withoutGlobalScopes()->where('account_code','110101')->first();
            if(!isset($cash_account) && !isset($cash_account->id) && !isset($wosul_cash_account) && !isset($wosul_cash_account->id)){
                $account_type = MasterAccountType::where('account_type_constant','ASSET')->first();

                //entry in wosul account table
                $account = [
                    "slack" => $this->generate_slack("accounts"),
                    "store_id" => session('store_id'),
                    "account_code" => $search_cash_account['data']->accounts[0]->code,
                    "account_type" => $account_type->id,
                    "label" => $search_cash_account['data']->accounts[0]->name_en,
                    "description" => $search_cash_account['data']->accounts[0]->name_en,
                    "initial_balance" => $search_cash_account['data']->accounts[0]->balance,
                    "pos_default" => 0,
                    "status" => 1,
                    "created_by" => session('user_id')
                ];
                $account_id = Account::create($account)->id;

                //entry in qoyod_account_table
                $qoyod_account = array(
                    'account_id'=>$account_id,
                    'qoyod_account_id'=>$cash_account_id,
                );
                QoyodUsersAccount::create($qoyod_account);
            }
        }
    }

    //Inventory Sync
    public function sync_inventory(){
        $stores = Store::withoutGlobalScopes()->select('stores.id as store_id','stores.name','stores.address','stores.city','stores.pincode','stores.country_id','qoyod_inventory.qoyod_inventory_id')->QoyodInventoryJoin()->Active()->get();
        foreach ($stores as $store){
            if(!isset($store->qoyod_inventory_id) && $store->qoyod_inventory_id==''){
                $this->qoyod_create_inventory($store);
            }
        }
    }

    //Category Sync
    public function sync_category(){
        $qoyod_categories = Category::withoutGlobalScopes()->select('category.id','category.label','category.description','category.parent','qoyod_categories.qoyod_category_id')->QoyodCategoryJoin()->orderBy('parent','ASC')->get();
        foreach ($qoyod_categories as $qoyod_category){
            if(!isset($qoyod_category->qoyod_category_id) && $qoyod_category->qoyod_category_id==''){
                $this->qoyod_create_category($qoyod_category);
            }
        }
    }

    //Unit Sync
    public function sync_unit(){
        $measurments = Measurement::withoutGlobalScopes()->select('measurements.id','measurements.label','qoyod_mesurment_units.qoyod_unit_id')->QoyodUnitJoin()->Active()->get();
        foreach ($measurments as $measurment){
            if(!isset($measurment->qoyod_unit_id) && $measurment->qoyod_unit_id==''){
                $this->qoyod_create_unit($measurment);
            }
        }
    }

    //Ingredient Sync
    public function sync_ingredient(){
        $products = Product::withoutGlobalScopes()->select('products.id', 'products.store_id', 'products.name', 'products.slack', 'products.barcode', 'products.name', 'products.name_ar', 'products.description', 'products.quantity', 'products.purchase_amount_excluding_tax', 'products.sale_amount_excluding_tax', 'products.category_id', 'products.measurement_id', 'products.tax_code_id','qoyod_products.qoyod_product_id')->Active()->QoyodProductJoin()->where('is_ingredient', 1)->where('purchase_amount_excluding_tax','>',0)->get()->chunk(30);
        foreach ($products as $product){
            foreach ($product as $row){
                if(!isset($row->qoyod_product_id) && $row->qoyod_product_id==''){
                    $this->qoyod_create_product($row);
                }
            }
        }
    }

    //Product Sync
    public function sync_product(){
        $products = Product::withoutGlobalScopes()->select('products.id', 'products.store_id', 'products.name', 'products.slack', 'products.barcode', 'products.name', 'products.name_ar', 'products.description', 'products.quantity', 'products.purchase_amount_excluding_tax', 'products.sale_amount_excluding_tax', 'products.category_id', 'products.measurement_id', 'products.tax_code_id','qoyod_products.qoyod_product_id')->Active()->QoyodProductJoin()->where('is_ingredient', 0)->where('purchase_amount_excluding_tax','>',0)->get()->chunk(30);
        foreach ($products as $product){
            foreach ($product as $row){
                if(!isset($row->qoyod_product_id) && $row->qoyod_product_id==''){
                    $this->qoyod_create_product($row);
                }
            }
        }
    }

    //Vendor Sync
    public function sync_vendor(){
        $vendors = Supplier::withoutGlobalScopes()->select('suppliers.id','suppliers.name','suppliers.email','suppliers.tax_number','suppliers.phone','suppliers.organization_name','qoyod_vendors.qoyod_vendor_id')->QoyodVendorJoin()->Active()->get();
        foreach ($vendors as $vendor){
            if(!isset($vendor->qoyod_vendor_id) && $vendor->qoyod_vendor_id==''){
                $this->qoyod_create_vendor($vendor);
            }
        }
    }

    //Customer Sync
    public function sync_customer(){
        $customers = Customer::withoutGlobalScopes()->select('customers.id','customers.name','customers.email','customers.status','customers.phone','customers.organization_name','qoyod_customers.qoyod_customer_id')->QoyodCustomerJoin()->Active()->get();
        foreach ($customers as $customer){
            if(!isset($customer->qoyod_customer_id) && $customer->qoyod_customer_id=='' && $customer->name!=''){
                $this->qoyod_create_customer($customer);
            }
        }
    }

    //Validation
    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => $this->get_validation_rules("name_label", true),
        ]);
        $validation_status = $validator->fails();

        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

    //Account Async
    public function async_qoyod_data(Request $request){
        try{
            if(!check_access(['A_ADD_QOYOD'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }
            $merchant_id = $request->session()->get('merchant_id');
            $qoyod_sync = $this->qoyod_is_sync();
            if($qoyod_sync['status']) {
                //remove the entry from setting table
                DB::beginTransaction();

                $qoyod = SettingApp::select('id','qoyod_api_key')->first();
                SettingApp::where('id',$qoyod->id)->update(['qoyod_api_key'=>NULL,'qoyod_status'=>0,'qoyod_last_sync_time' => NULL]);

                QoyodUsersAccount::query()->truncate();
                QoyodInventory::query()->truncate();
                QoyodCategory::query()->truncate();
                QoyodCustomer::query()->truncate();
                QoyodVendor::query()->truncate();
                QoyodMesurmentUnit::query()->truncate();
                QoyodProduct::query()->truncate();

                //store the id in session
                $request->session()->put('qoyod_status', 0);
                $request->session()->put('qoyod_api_key', '');

                DB::commit();
            }
            return response()->json($this->generate_response(
                array(
                    "message" => trans("All Masters data async successfully"),
                    "data"    => ''
                ), 'SUCCESS'
            ));

        } catch (Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
}
