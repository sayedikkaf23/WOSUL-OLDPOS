<?php

namespace App\Http\Traits;

use App\Models\Account;
use App\Models\Account as AccountModel;
use App\Models\Country;
use App\Models\Customer;
use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProducts;
use App\Models\MasterAccountType;
use App\Models\Measurement as MeasurementModel;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Models\PurchaseOrderProduct;
use App\Models\QoyodCustomer;
use App\Models\QoyodInventory;
use App\Models\QoyodVendor;
use App\Models\Quotation;
use App\Models\ReturnOrdersProducts;
use App\Models\SettingApp;
use App\Models\QoyodCategory;
use App\Models\QoyodMesurmentUnit;
use App\Models\QoyodProduct;
use App\Models\QoyodUsersAccount;
use App\Models\Supplier;
use App\Models\Taxcode as TaxcodeModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Measurement;
use App\Models\QuotationProduct;

trait QoyodApiTrait{

    public $params;
    public $cash_account_code = '110101'; // if you change it then do change in Transaction API
    //Define Header
    public function qoyod_define_headers($api_key = ''){
        $this->params['headers'] = [
            'Content-Type' => 'application/json',
            'API-KEY' => $api_key!=''?$api_key:Session('qoyod_api_key'),
        ];
    }

    //check account sync with our db
    public function qoyod_is_sync(){
        $qoyod = SettingApp::select('id','qoyod_api_key','qoyod_status','qoyod_last_sync_time')->first();
        if(isset($qoyod) && $qoyod->qoyod_status==1){
            $api_key_is_valid = $this->qoyod_verify_key($qoyod->qoyod_api_key)['status'];
            if($api_key_is_valid){
                $data = array('api_key'=>$qoyod->qoyod_api_key,'status'=>$qoyod->qoyod_status,'last_sync_time'=>$qoyod->qoyod_last_sync_time);
                return array('status'=>true,'data'=>$data);
            }else{
                SettingApp::where('id', $qoyod->id)->update(array('qoyod_api_key'=>'','qoyod_status'=>'','qoyod_last_sync_time'=>''));
                return array('status'=>false,'data'=>array());
            }
        }else{
            return array('status'=>false,'data'=>array());
        }
    }

    //Verifiy the API key
    public function qoyod_verify_key($api_key){
        $api_url = env('QOYOD_ENDPOINT') . 'accounts';
        return $this->api_call('GET', $api_url, $api_key);
    }

    //ACCOUNT
    //Get qoyod account
    public function qoyod_get_account($account_id=0, $search=''){
        if($account_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'accounts/'.$account_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'accounts?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'accounts';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create account entry in wosul
    public function account_entry_in_w_qoyod($qoyod_account, $account){
        $wosul_entry = array(
            'account_id'=>$account->account_id,
            'qoyod_account_id'=>$qoyod_account['data']->accounts[0]->id,
        );
        QoyodUsersAccount::create($wosul_entry);
        return $qoyod_account['data']->accounts[0]->id;
    }
    //Create qoyod account
    public function qoyod_create_account($account){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_account(0, 'q[en_name_eq]='.$account->label);
        if($search_data['status'] && !empty($search_data['data'])){
            //only entry in wosul's qoyod
            $qoyod_id = $this->account_entry_in_w_qoyod($search_data,$account);
        }else{
            $search_data = $this->qoyod_get_account(0, 'q[ar_name_eq]='.$account->label);
            if($search_data['status'] && !empty($search_data['data'])){
                $qoyod_id = $this->account_entry_in_w_qoyod($search_data,$account);
            }else{
                //entry in qoyod as well as wosul's qoyod
                $qoyod_type = array('1'=>'Sales','2'=>'FixedAsset','3'=>'Liability','4'=>'Equity','5'=>'Revenue','6'=>'Expense');
                $data = array(
                    'name_en' => $account->label,
                    'name_ar' => $account->label,
                    'code' => $account->account_code,
                    'description' => $account->description!=null?$account->description:"",
                    'receive_payments' => in_array($account->account_type,[1,5])?"true":"false",
                    'type' => $qoyod_type[$account->account_type],
                );
                $api_url = env('QOYOD_ENDPOINT') . 'accounts';
                $response = $this->api_call_with_body($api_url,'POST',['account' => $data]);

                if($response['status'] && !empty($response['data']) && $account->account_id>0 && $response['data']->account->id>0){
                    $qoyod_account = array(
                        'account_id'=>$account->account_id,
                        'qoyod_account_id'=>$response['data']->account->id,
                    );
                    QoyodUsersAccount::create($qoyod_account);
                    $qoyod_id = $response['data']->account->id;
                }
            }
        }
        return $qoyod_id;
    }

    //INVENTORY
    //Get qoyod inventory
    public function qoyod_get_inventory($inventory_id=0, $search=''){
        if($inventory_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'inventories/'.$inventory_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'inventories?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'inventories';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod inventory
    public function qoyod_create_inventory($store){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_inventory(0, 'q[name_eq]='.$store->name);

        if($search_data['status'] && !isset($search_data['data'])){
            //only entry in wosul's qoyod
            $wosul_entry = array(
                'store_id'=>$store->store_id,
                'qoyod_inventory_id'=>$search_data['data']->inventories[0]->id,
            );
            QoyodInventory::create($wosul_entry);
            $qoyod_id = $search_data['data']->inventories[0]->id;
        }else{
            //entry in qoyod as well as wosul's qoyod
            $account_id = $this->get_qoyod_account_id_from_db('Inventory');
            $country = '';
            if($store->country_id>0){
                $country = Country::where('id',$store->country_id)->first()->name;
            }
            $address = array(
                'shipping_address'=>$store->address!=''?$store->address:'',
                'shipping_city'=>$store->city!=''?$store->city:'',
                'shipping_state'=>$store->city!=''?$store->city:'',
                'shipping_zip'=>$store->pincode!=''?$store->pincode:'',
                'shipping_country'=>$country,
            );
            $data = array(
                'name' => $store->name,
                'ar_name' => $store->name,
                'account_id' => $account_id,
                'address' => $address,
            );
            $api_url = env('QOYOD_ENDPOINT') . 'inventories';
            $response = $this->api_call_with_body($api_url,'POST',['inventory' => $data]);
            if($response['status'] && !empty($response['data'])){
                $qoyod_inventory = array(
                    'store_id'=>$store->store_id,
                    'qoyod_inventory_id'=>$response['data']->id,
                );
                QoyodInventory::create($qoyod_inventory);
            }
            $qoyod_id = $response['data']->id;
        }
        return $qoyod_id;
    }

    //Update qoyod inventory
    public function qoyod_update_inventory($store, $inventory_id){
        $country = '';
        if($store->country_id>0){
            $country = Country::where('id',$store->country_id)->first()->name;
        }
        $address = array(
            'shipping_address'=>$store->address!=''?$store->address:'',
            'shipping_city'=>$store->city!=''?$store->city:'',
            'shipping_state'=>$store->city!=''?$store->city:'',
            'shipping_zip'=>$store->pincode!=''?$store->pincode:'',
            'shipping_country'=>$country,
        );
        $data = array(
            'name' => $store->name,
            'ar_name' => $store->name,
            'address' => $address,
        );
        $api_url = env('QOYOD_ENDPOINT') . 'inventories/'.$inventory_id;
        return $this->api_call_with_body($api_url,'PUT',['inventory' => $data]);
    }
    //Create inventory adjustment
    public function qoyod_adjustment_inventory($store_id,$products,$action){

        $store = $this->get_db_qoyod_inventory_id($store_id);

        $expense_account = $this->get_account_data_from_type(6); //6 Expense
        if(!isset($expense_account)){
            $expense_account = $this->get_account_data_from_type(1);
        }
        $revenue_account = $this->get_account_data_from_type(5); //5 Revenue
        if(!isset($revenue_account)){
            $revenue_account = $this->get_account_data_from_type(1);
        }
        if($expense_account->qoyod_account_id>0 && $revenue_account->qoyod_account_id>0){
            foreach ($products as $product){
                $qyd_product_id = $this->get_db_qoyod_product_id($product['product_id']);
                if($qyd_product_id>0){
                    $item[] = array(
                        'product_id'=>$qyd_product_id,
                        'actual_quantity'=>$product['quantity'],
                        'rate'=>$product['purchase_amount'],
                    );
                }
            }
            if(isset($store) && $store->qoyod_inventory_id>0 && $revenue_account->qoyod_account_id>0 && $expense_account->qoyod_account_id>0 && !empty($item)){
                $data = array(
                    'inventory_id' => $store->qoyod_inventory_id,
                    'revenue_account_id' => $revenue_account->qoyod_account_id,
                    'expense_account_id' => $expense_account->qoyod_account_id,
                    'date' => date('Y-m-d'),
                    'description' => 'Product '.$action,
                    'line_items' => $item,
                );
                $api_url = env('QOYOD_ENDPOINT') . 'inventory_adjustments';
                $this->api_call_with_body($api_url,'POST',['inventory_adjustment' => $data]);
            }
        }
        return true;
    }
    //Inventory Transfer
    public function qoyod_inventory_transfer($transfer_data){
        $from_store = $this->get_db_qoyod_inventory_id($transfer_data['from_location']);
        $to_store = $this->get_db_qoyod_inventory_id($transfer_data['to_location']);

        foreach ($transfer_data['products'] as $product){
            $qyd_product = $this->qoyod_get_product(0, 'q[en_name_eq]='.$product->product_name);
            if(!empty($qyd_product['data'])){
                $item[] = array(
                    'product_id'=>$qyd_product['data']->products[0]->id,
                    'quantity'=>$product->accepted_quantity,
                );
            }
        }
        if(!empty($item) && $from_store->qoyod_inventory_id>0 && $to_store->qoyod_inventory_id>0){
            $data = array(
                'from_location' => $from_store->qoyod_inventory_id,
                'to_location' => $to_store->qoyod_inventory_id,
                'date' => date('Y-m-d'),
                'description' => 'Transfer',
                'line_items' => $item,
            );
            $api_url = env('QOYOD_ENDPOINT') . 'inventory_transfers';
            $this->api_call_with_body($api_url,'POST',['inventory_transfer' => $data]);
        }
        return true;
    }

    //CATEGORY
    //Get qoyod category
    public function qoyod_get_category($category_id=0, $search=''){
        if($category_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'categories/'.$category_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'categories?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'categories';
            }
        }
        return $this->api_call('GET', $api_url);
    }

    //Create qoyod category
    public function qoyod_create_category($category){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_category(0, 'q[name_eq]='.$category->label);

        if($search_data['status'] && !empty($search_data['data'])){
            //only entry in wosul's qoyod
            $wosul_entry = array(
                'category_id'=>$category->id,
                'qoyod_category_id'=>$search_data['data']->categories[0]->id,
            );
            QoyodCategory::create($wosul_entry);
            $qoyod_id = $search_data['data']->categories[0]->id;
        } else {
            //entry in qoyod as well as wosul's qoyod
            $parent = '';
            if($category->parent>0){
                $qoyod_parent_category = QoyodCategory::select('qoyod_category_id')->where('category_id',$category->parent)->first();
                $parent = $qoyod_parent_category->qoyod_category_id;
            }
            $data = array(
                'name' => $category->label,
                'description' => $category->description!=''?$category->description:'',
                'parent_id' => $parent,
            );
            $api_url = env('QOYOD_ENDPOINT') . 'categories';
            $response = $this->api_call_with_body($api_url,'POST',['category' => $data]);
            if($response['status'] && !empty($response['data']) && $category->id>0 && $response['data']->category->id>0){
                $qoyod_category_data = array(
                    'category_id'=>$category->id,
                    'qoyod_category_id'=>$response['data']->category->id,
                );
                QoyodCategory::create($qoyod_category_data);
                $qoyod_id = $response['data']->category->id;
            }
        }
        return $qoyod_id;
    }
    //Update qoyod category
    public function qoyod_update_category($category, $category_id){
        $parent = '';
        if($category->parent>0){
            $qoyod_parent_category = QoyodCategory::where('category_id',$category->parent)->first();
            $parent = $qoyod_parent_category->qoyod_category_id;
        }
        $data = array(
            'name' => $category->label,
            'description' => $category->description!=''?$category->description:'',
            'parent_id' => $parent,
        );
        $api_url = env('QOYOD_ENDPOINT') . 'categories/'.$category_id;
        return $this->api_call_with_body($api_url,'PUT',['category' => $data]);
    }

    //PRODUCT UNIT
    //Get qoyod unit
    public function qoyod_get_unit($unit_type_id=0, $search=''){
        if($unit_type_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'product_unit_types/'.$unit_type_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'product_unit_types?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'product_unit_types';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod category
    public function qoyod_create_unit($unit){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_unit(0, 'q[unit_name_eq]='.$unit->label);

        if($search_data['status'] && !empty($search_data['data']) && !empty($search_data['data']->product_unit_types)){
            //only entry in wosul's qoyod
            $wosul_entry = array(
                'unit_id'=>$unit->id,
                'qoyod_unit_id'=>$search_data['data']->product_unit_types[0]->id,
            );
            QoyodMesurmentUnit::create($wosul_entry);
            $qoyod_id = $search_data['data']->product_unit_types[0]->id;
        } else {
            //entry in qoyod as well as wosul's qoyod
            $data = array(
                'unit_name' => $unit->label,
                'unit_representation' => $unit->label,
            );
            $api_url = env('QOYOD_ENDPOINT') . 'product_unit_types';
            $response = $this->api_call_with_body($api_url,'POST',['product_unit_type' => $data]);
            if($response['status'] && !empty($response['data']) && $unit->id>0 && $response['data']->product_unit_type->id>0){
                $qoyod_unit = array(
                    'unit_id'=>$unit->id,
                    'qoyod_unit_id'=>$response['data']->product_unit_type->id,
                );
                QoyodMesurmentUnit::create($qoyod_unit);
                $qoyod_id = $response['data']->product_unit_type->id;
            }
        }
        return $qoyod_id;
    }

    //OTHER functions
    //Return id of the account and if no there then it will create then return.
    public function get_qoyod_account_id_from_db($label){
        $account = Account::withoutGlobalScopes()->select('qoyod_users_accounts.qoyod_account_id')->QoyodAccountJoin()->Active()->where('accounts.label','=',$label)->first();
        if(isset($account) && $account->qoyod_account_id>0){
            return $account->qoyod_account_id;
        }else{
            //create account of type = Asset and name = inventory!
            $master_account_type = MasterAccountType::where('account_type_constant','ASSET')->first();
            $account = [
                "slack" => $this->generate_slack("accounts"),
                "store_id" => session('user_id'),
                "account_code" => Str::random(6),
                "account_type" => $master_account_type->id,
                "label" => 'Inventory',
                "description" => '',
                "initial_balance" => 0,
                "pos_default" => 0,
                "status" => 1,
                "created_by" => session('user_id')
            ];
            $account_id = AccountModel::create($account)->id;
            //check if account is already at qoyod then only db entry
            $qoyod_side_account = $this->qoyod_get_account(0,'q[en_name_eq]='.$label);
            if($qoyod_side_account['code']==200 && $account_id>0 && $qoyod_side_account['data']->accounts[0]->id>0){
                $qoyod_account = array(
                    'account_id'=>$account_id,
                    'qoyod_account_id'=>$qoyod_side_account['data']->accounts[0]->id,
                );
                QoyodUsersAccount::create($qoyod_account);
                return $qoyod_account['qoyod_account_id'];
            }else{
                //create an account in qoyod
                $data_array = array(
                    'label'=>$account['label'],
                    'account_code'=>$account['account_code'],
                    'description'=>'',
                    'account_type'=>$account['account_type'],
                    'account_id'=>$account_id,
                );
                $qoyod_account = $this->qoyod_create_account((object) $data_array);
                return $qoyod_account->account->id;
            }
        }
    }
    //Return the qyd_account_id from type_id
    public function get_account_data_from_type($type_id){
        return QoyodUsersAccount::withoutGlobalScopes()->join('accounts', 'accounts.id', '=', 'qoyod_users_accounts.account_id')->where('accounts.account_type',$type_id)->first();
    }
    //Return qyd_cat id from cat_id
    public function get_db_qoyod_cat_id($category_id){
        $result = QoyodCategory::select('qoyod_category_id')->where('category_id',$category_id)->first();
        if(isset($result) && $result->qoyod_category_id>0){
            return $result->qoyod_category_id;
        }else{
            return 0;
        }
    }
    //Return qyd_unit id from mesurement_id
    public function get_db_qoyod_mesurment_id($mesurement_id){
        $result = QoyodMesurmentUnit::select('qoyod_unit_id')->where('unit_id',$mesurement_id)->first();
        if(isset($result) && $result->qoyod_unit_id>0){
            return $result->qoyod_unit_id;
        }else{
            return 0;
        }
    }
    //Return qyd_unit id from product_id
    public function get_db_qoyod_product_id($product_id){
        $result = QoyodProduct::select('qoyod_product_id')->where('product_id',$product_id)->first();
        if(isset($result) && $result->qoyod_product_id>0){
            return $result->qoyod_product_id;
        }else{
            return 0;
        }
    }
    //Return inventory id from store_id
    public function get_db_qoyod_inventory_id($store_id){
        return QoyodInventory::withoutGlobalScopes()->select('qoyod_inventory_id')->where('store_id',$store_id)->first();
    }
    //Return qyd_customer id from customer_id
    public function get_db_qoyod_customer_id($customer_id){
        $customer = QoyodCustomer::select('qoyod_customer_id')->where('customer_id',$customer_id)->first();
        if(isset($customer->qoyod_customer_id) && $customer->qoyod_customer_id>0){
            return $customer->qoyod_customer_id;
        }else{
            return 0;
        }
    }
    //Create default unit
    public function create_detault_unit(){
        $measurement = [
            "slack" => $this->generate_slack("measurements"),
            "label" => 'Unit',
            "measurement_category_id" => NULL,
            "status" => 1,
            "created_by" => 1
        ];
        $measurement_id = MeasurementModel::create($measurement)->id;
        $data = array(
            'unit_name' => 'Unit',
            'unit_representation' => 'Unit',
        );
        $api_url = env('QOYOD_ENDPOINT') . 'product_unit_types';
        $response = $this->api_call_with_body($api_url,'POST',['product_unit_type' => $data]);
        if($response['status'] && !empty($response['data']) && $measurement_id>0 && $response['data']->product_unit_type->id>0) {
            $qoyod_unit = array(
                'unit_id' => $measurement_id,
                'qoyod_unit_id' => $response['data']->product_unit_type->id,
            );
            QoyodMesurmentUnit::create($qoyod_unit);
            $qoyod_unit_id = $response['data']->product_unit_type->id;
        }
        return $qoyod_unit_id;
    }


    //PRODUCT
    //Create product and ingredient array
    public function prepare_product_array($product,$method){
        $Q_category = $this->get_db_qoyod_cat_id($product->category_id);
        if($product->measurement_id>0){
            $Q_measurement = $this->get_db_qoyod_mesurment_id($product->measurement_id);
        }else{
            $measurement = QoyodMesurmentUnit::withoutGlobalScopes()->first();
            if(isset($measurement) && $measurement->qoyod_unit_id>0){
                $Q_measurement = $measurement->qoyod_unit_id;
            }else{
                //create unit default measurement and take that id
                $Q_measurement = $this->create_detault_unit();
            }
        }
        $Q_purchase_account = $this->get_account_data_from_type(6); //6 Expense

        if(!isset($Q_purchase_account)){
            $Q_purchase_account = $this->get_account_data_from_type(1);
        }
        $Q_salse_account = $this->get_account_data_from_type(5); //5 Revenue

        if(!isset($Q_salse_account)){
            $Q_salse_account = $this->get_account_data_from_type(1);
        }
        $tax_code_id =2;
        if(isset($product) && $product->tax_code_id>0){
            $store_tax = TaxcodeModel::withoutGlobalScopes()->join('tax_code_type','tax_code_type.tax_code_id','=','tax_codes.id')->where('tax_codes.id',$product->tax_code_id)->first();
            if(isset($store_tax)){
                if($store_tax->tax_name_id==2){
                    $tax_code_id =1;
                }else if($store_tax->tax_name_id==4){
                    $tax_code_id =3;
                }else{
                    $tax_code_id =2;
                }
            }
        }
        $type = "Product";
        if(isset($product->id) && $product->id>0){
            $product_ind = ProductIngredient::withoutGlobalScopes()->where('product_id',$product->id)->first();
            if(isset($product_ind) && $product_ind->id){
                $type = "Recipe";
            }
        }

        if(isset($Q_purchase_account) || isset($Q_salse_account)){
            $data_array = array(
                "sku"=> $product->slack!=""?$product->slack:"",
                "barcode"=> $product->barcode!=""?$product->barcode:"",
                "name_ar"=> $product->name_ar!=""?$product->name_ar:$product->name,
                "name_en"=> $product->name!=""?$product->name:"",
                "description"=> $product->description!=""?$product->description:"",
                "product_unit_type_id"=> $Q_measurement,
                "category_id"=> $Q_category,
                "track_quantity"=> isset($Q_purchase_account)?1:0,
                "purchase_item"=> isset($Q_purchase_account)?1:0,
                "buying_price"=> isset($Q_purchase_account)?number_format($product->purchase_amount_excluding_tax,2):"",
                "expense_account_id"=> isset($Q_purchase_account)?$Q_purchase_account->qoyod_account_id:"",
                "sale_item"=> isset($Q_salse_account)?1:0,
                "selling_price"=> isset($Q_salse_account)?number_format($product->sale_amount_excluding_tax,2):"",
                "sales_account_id"=> isset($Q_salse_account)?$Q_salse_account->qoyod_account_id:"",
                "tax_id"=> $tax_code_id>0?$tax_code_id:"",
                "type"=> $type,
            );

            $ing_array = [];
            if($type=="Recipe"){
                if($method=='Add'){
                    $ingredients = ProductIngredient::select('product_ingredients.*','products.slack as product_slack','products.id as product_id','products.name')->join('products', 'products.id', '=', 'product_ingredients.ingredient_product_id')->where('product_ingredients.product_id',$product->id)->get();
                    foreach ($ingredients as $ing){
                        if($ing['measurement_id']>0){
                            $qoyod_product = $this->get_db_qoyod_product_id($ing['product_id']);
                            $qoyod_mesurement = $this->get_db_qoyod_mesurment_id($ing['measurement_id']);
                            if($qoyod_product>0 && $qoyod_mesurement>0){
                                $ing_array[] = array('component_id'=>$qoyod_product,'unit_id'=>$qoyod_mesurement,'quantity'=>$ing['quantity']);
                            }
                        }
                    }
                    $data_array['ingredients_attributes']=$ing_array;
                }else{
                    $qoyod_product = $this->qoyod_get_product($product->qoyod_product_id);

                    $ing_ids = [];
                    if(!empty($qoyod_product['data']->product[0]->ingredients)){
                        foreach ($qoyod_product['data']->product[0]->ingredients as $ing){
                            $ing_ids[] = $ing->product_id;
                        }
                    }
                    $ingredients = ProductIngredient::select('product_ingredients.*', 'products.slack as product_slack','products.id as product_id', 'products.name')->join('products', 'products.id', '=', 'product_ingredients.ingredient_product_id')->where('product_ingredients.product_id', $product->id)->get();
                    foreach ($ingredients as $ing) {
                        if($ing['measurement_id']>0){
                            $qoyod_product = $this->get_db_qoyod_product_id($ing['product_id']);
                            $qoyod_mesurement = $this->get_db_qoyod_mesurment_id($ing['measurement_id']);
                            if($qoyod_product>0 && $qoyod_mesurement>0){
                                if (!in_array($qoyod_product, $ing_ids)) {
                                    $ing_array[] = array('component_id'=>$qoyod_product,'unit_id'=>$qoyod_mesurement,'quantity'=>$ing['quantity']);
                                }
                            }
                        }
                    }
                    $data_array['ingredients_attributes']=$ing_array;
                }
            }
            return $data_array;
        }
    }
    //Entry in Wosul's Qoyod data
    public function product_entry_in_w_qoyod($qoyod_product, $product){
        $wosul_entry = array(
            'product_id'=>$product->id,
            'qoyod_product_id'=>$qoyod_product['data']->products[0]->id,
        );
        QoyodProduct::create($wosul_entry);
        if($product->name!='' && $product->quantity>0 && $product->purchase_amount_excluding_tax>0){
            $products[] = array(
                'product_id' => $product->id,
                'quantity' => $product->quantity,
                'purchase_amount' => $product->purchase_amount_excluding_tax,
            );
            $this->qoyod_adjustment_inventory($product->store_id,$products,'Add');
        }
        return $qoyod_product['data']->products[0]->id;
    }
    //Get qoyod product
    public function qoyod_get_product($product_id=0, $search=''){
        if($product_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'products/'.$product_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'products?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'products';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod product
    public function qoyod_create_product($product){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_product(0, 'q[name_eq]='.$product->name);
        if($search_data['status'] && !empty($search_data['data']) && !empty($search_data['data']->products)){
            //only entry in wosul's qoyod
            $qoyod_id = $this->product_entry_in_w_qoyod($search_data,$product);
        } else {
            $qoyod_id = 0;
            //entry in qoyod as well as wosul's qoyod
            $data = $this->prepare_product_array($product,'Add');
            $api_url = env('QOYOD_ENDPOINT') . 'products';
            $response = $this->api_call_with_body($api_url,'POST',['product' => $data]);

            if($response['status'] && !empty($response['data']) && $product->id>0 && $response['data']->product->id>0){
                $qoyod_unit = array(
                    'product_id'=>$product->id,
                    'qoyod_product_id'=>$response['data']->product->id,
                );
                QoyodProduct::create($qoyod_unit);
                $qoyod_id = $response['data']->product->id;
                if($product->name!='' && $product->quantity>0 && $product->purchase_amount_excluding_tax>0){
                    $products[] = array(
                        'product_id' => $product->id,
                        'quantity' => $product->quantity,
                        'purchase_amount' => $product->purchase_amount_excluding_tax,
                    );
                    $this->qoyod_adjustment_inventory($product->store_id,$products,'Add');
                }
            }
        }
        return $qoyod_id;
    }
    //Update qoyod product
    public function qoyod_update_product($product, $product_id){
        $data = $this->prepare_product_array($product,'Update');
        $api_url = env('QOYOD_ENDPOINT') . 'products/'.$product_id;
        $this->api_call_with_body($api_url,'PUT',['product' => $data]);
        if($product->name!='' && $product->quantity>0 && $product->purchase_amount_excluding_tax>0){
            $products[] = array(
                'product_id' => $product->id,
                'quantity' => $product->quantity,
                'purchase_amount' => $product->purchase_amount_excluding_tax,
            );
            $this->qoyod_adjustment_inventory($product->store_id,$products,'Update');
        }
        return true;
    }

    //VENDOR
    //Get qoyod vendor
    public function qoyod_get_vendor($vendor_id=0, $search=''){
        if($vendor_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'vendors/'.$vendor_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'vendors?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'vendors';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod vendor
    public function qoyod_create_vendor($vendor){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_vendor(0, 'q[name_eq]='.$vendor->name);

        if($search_data['status'] && !empty($search_data['data'])){
            //only entry in wosul's qoyod
            $wosul_entry = array(
                'vendor_id'=>$vendor->id,
                'qoyod_vendor_id'=>$search_data['data']->vendors[0]->id,
            );
            QoyodVendor::create($wosul_entry);
            $qoyod_id = $search_data['data']->vendors[0]->id;
        }else{
            //entry in qoyod as well as wosul's qoyod
            $data = array(
                'name' => $vendor->name,
                'organization' => $vendor->organization_name!=''?$vendor->organization_name:'',
                'email' => $vendor->email!=''?$vendor->email:'',
                'phone_number' => $vendor->phone!=''?$vendor->phone:'',
                'tax_number' => $vendor->tax_number!=''?$vendor->tax_number:'',
            );
            $api_url = env('QOYOD_ENDPOINT') . 'vendors';
            $response = $this->api_call_with_body($api_url,'POST',['contact' => $data]);
            if($response['status'] && !empty($response['data']) && $vendor->id>0 && $response['data']->contact->id>0){
                $qoyod_vendor = array(
                    'vendor_id'=>$vendor->id,
                    'qoyod_vendor_id'=>$response['data']->contact->id,
                );
                QoyodVendor::create($qoyod_vendor);
                $qoyod_id = $response['data']->contact->id;
            }
        }
        return $qoyod_id;
    }
    //Update qoyod vendor
    public function qoyod_update_vendor($vendor, $vendor_id){
        $data = array(
            'name' => $vendor->name,
            'organization' => $vendor->organization_name!=''?$vendor->organization_name:'',
            'email' => $vendor->email!=''?$vendor->email:'',
            'phone_number' => $vendor->phone!=''?$vendor->phone:'',
            'tax_number' => $vendor->tax_number!=''?$vendor->tax_number:'',
        );
        $api_url = env('QOYOD_ENDPOINT') . 'vendors/'.$vendor_id;
        return $this->api_call_with_body($api_url,'PUT',['contact' => $data]);
    }

    //PURCHASE ORDER
    //Get qoyod order
    public function qoyod_get_order($order_id=0){
        if($order_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'orders/'.$order_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'orders';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod order
    public function qoyod_create_order($po_detail){
        $po_products = PurchaseOrderProduct::where('purchase_order_id',$po_detail->id)->get();
        $prod = [];
        foreach ($po_products as $po_product){
            $has_ingredient = ProductIngredient::select('id')->where('product_id',$po_product->product_id)->first();
            if(!isset($has_ingredient)){
                $product = $this->qoyod_get_product(0, 'q[en_name_eq]='.$po_product->name);
                if($product['status'] && $product['data']->products[0]->id>0 && $po_product->quantity>0 && $po_product->amount_excluding_tax>0){
                    $prod[] =array(
                        'product_id'=>$product['data']->products[0]->id,
                        'description'=>$po_product->product_code,
                        'quantity'=>$po_product->quantity,
                        'unit_price'=>$po_product->amount_excluding_tax,
                        'discount'=>$po_product->discount_percentage>0?$po_product->discount_percentage:'',
                        'discount_type'=>$po_product->discount_percentage>0?'percentage':'',
                        'tax_percent'=>$po_product->tax_percentage>0?$po_product->tax_percentage:0,
                    );
                }
            }
        }
        if(!empty($prod)){
            $vendor = $this->qoyod_get_vendor(0,'q[name_eq]='.$po_detail->supplier_name);
            $inventory = $this->get_db_qoyod_inventory_id($po_detail->store_id);
            if($vendor['data']->vendors[0]->id>0 && $inventory->qoyod_inventory_id>0){
                $data = array(
                    'contact_id' => $vendor['data']->vendors[0]->id,
                    'reference' => $po_detail->slack,
                    'issue_date' => $po_detail->order_date,
                    'expiry_date' => $po_detail->order_due_date,
                    'status' => $po_detail->update_stock==1?"Approved":"Draft",
                    'inventory_id' => $inventory->qoyod_inventory_id,
                    'notes' => "",
                    'terms_conditions' => $po_detail->terms,
                    'line_items' => $prod,
                );
                $api_url = env('QOYOD_ENDPOINT') . 'orders';
                //create the PO
                $po_response = $this->api_call_with_body($api_url,'POST',['order' => $data]);
                if($po_response['data']->order->id>0 && $po_detail->update_stock==1){
                    //create the bill
                    $this->qoyod_create_bill($data,$po_detail);
                }
            }
        }
        return true;
    }

    //BILLS
    //Get qoyod bill
    public function qoyod_get_bill($bill_id=0){
        if($bill_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'bills/'.$bill_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'bills';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod bill
    public function qoyod_create_bill($input_data,$po_detail){
        $prod = [];
        foreach ($input_data['line_items'] as $product){
            if($product['product_id']>0 && $product['quantity']>0 && $product['unit_price']>0){
                $prod[] =array(
                    'product_id'=>$product['product_id'],
                    'description'=>$product['description'],
                    'quantity'=>$product['quantity'],
                    'unit_price'=>$product['unit_price'],
                    'discount'=>$product['discount'],
                    'discount_type'=>$product['discount_type'],
                    'tax_percent'=>$product['tax_percent'],
                );
            }
        }
        if(!empty($prod) && $input_data['contact_id']>0 && $input_data['status']!='' && $input_data['issue_date']!='' && $input_data['expiry_date']!=''){
            $data = array(
                'contact_id' => $input_data['contact_id'],
                'status' => $input_data['status'],
                'issue_date' => $input_data['issue_date'],
                'due_date' => $input_data['expiry_date'],
                'reference' => $input_data['reference'],
                'inventory_id' => $input_data['inventory_id'],
                'line_items' => $prod,
            );
            $api_url = env('QOYOD_ENDPOINT') . 'bills';
            $po_bill = $this->api_call_with_body($api_url,'POST',['bill' => $data]);
            if($po_bill['data']->bill->id>0){
                //create the bill payment
                $expense_account = $this->get_account_data_from_type(6); //6 Expense
                if(!isset($expense_account)){
                    $expense_account = $this->get_account_data_from_type(1);
                }
                $payment_data =array(
                    'reference'=>$input_data['reference'],
                    'description'=>'Purchase Amount bill',
                    'bill_id'=>$po_bill['data']->bill->id,
                    'account_id'=>$expense_account->qoyod_account_id,
                    'date'=>$input_data['issue_date'],
                    'amount'=>$po_detail->total_order_amount,
                );
                $this->qoyod_create_bill_payment($payment_data);
            }
        }
        return true;
    }
    //Update qoyod bill
    public function qoyod_update_bill($data, $bill_id){
        $api_url = env('QOYOD_ENDPOINT') . 'bills/'.$bill_id;
        return $this->api_call_with_body($api_url,'PUT',['bill' => $data]);
    }
    //Delete qoyod bill
    public function qoyod_delete_bill($bill_id){
        $api_url = env('QOYOD_ENDPOINT') . 'bills/'.$bill_id;
        return $this->api_call('DELETE',$api_url);
    }

    //BILL PAYMENTS
    //Get qoyod bill payment
    public function qoyod_get_bill_payment($bill_payment_id=0){
        if($bill_payment_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'bill_payments/'.$bill_payment_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'bill_payments';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod bill payment
    public function qoyod_create_bill_payment($data){
        $api_url = env('QOYOD_ENDPOINT') . 'bill_payments';
        return $this->api_call_with_body($api_url,'POST',['bill_payment' => $data]);
    }
    //Allocation qoyod existing debit note or receipt
    public function qoyod_allocation_bill_payment($bill_payment_id,$data){
        $api_url = env('QOYOD_ENDPOINT') . 'bills/'.$bill_payment_id.'/allocations';
        return $this->api_call_with_body($api_url,'POST',['bill' => $data]);
    }

    //DEBIT NOTE
    //Get qoyod debit note
    public function qoyod_get_debit_note($debit_note_id=0){
        if($debit_note_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'debit_notes/'.$debit_note_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'debit_notes';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod debit note
    public function qoyod_create_debit_note($data){
        $api_url = env('QOYOD_ENDPOINT') . 'debit_notes';
        return $this->api_call_with_body($api_url,'POST',['note' => $data]);
    }
    //Delete qoyod debit note
    public function qoyod_delete_debit_note($debit_note_id){
        $api_url = env('QOYOD_ENDPOINT') . 'debit_notes/'.$debit_note_id;
        return $this->api_call('DELETE',$api_url);
    }

    //CUSTOMER
    //Get qoyod customer
    public function qoyod_get_customer($customer_id=0, $search=''){
        if($customer_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'customers/'.$customer_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'customers?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'customers';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod customer
    public function qoyod_create_customer($customer){
        //get the account detail from qoyod
        $search_data = $this->qoyod_get_vendor(0, 'q[name_eq]='.$customer->name);
        if($search_data['status'] && !empty($search_data['data'])){
            //only entry in wosul's qoyod
            $wosul_entry = array(
                'customer_id'=>$customer->id,
                'qoyod_customer_id'=>$search_data['data']->customers[0]->id,
            );
            QoyodCustomer::create($wosul_entry);
            $qoyod_id = $search_data['data']->customers[0]->id;
        }else {
            //entry in qoyod as well as wosul's qoyod
            $data = array(
                'name' => $customer->name,
                'organization' => $customer->organization_name!=''?$customer->organization_name:'',
                'phone_number' => $customer->phone!=''?$customer->phone:'',
                'email' => $customer->email!=''?$customer->email:'',
                'status' => $customer->status==1?'Active':'Inactive',
            );
            $api_url = env('QOYOD_ENDPOINT') . 'customers';
            $response = $this->api_call_with_body($api_url,'POST',['contact' => $data]);
            if($response['status'] && !empty($response['data']) && $customer->id>0 && $response['data']->contact->id>0){
                $qoyod_customer = array(
                    'customer_id'=>$customer->id,
                    'qoyod_customer_id'=>$response['data']->contact->id,
                );
                QoyodCustomer::create($qoyod_customer);
                $qoyod_id = $response['data']->contact->id;
            }
        }
        return $qoyod_id;
    }
    //Update qoyod customer
    public function qoyod_update_customer($customer, $customer_id){
        $data = array(
            'name' => $customer->name,
            'organization' => $customer->organization_name!=''?$customer->organization_name:'',
            'phone_number' => $customer->phone!=''?$customer->phone:'',
            'email' => $customer->email!=''?$customer->email:'',
            'status' => $customer->status==1?'Active':'Inactive',
        );
        $api_url = env('QOYOD_ENDPOINT') . 'customers/'.$customer_id;
        return $this->api_call_with_body($api_url,'PUT',['contact' => $data]);
    }

    //QUOTATION
    //Get qoyod quotation
    public function qoyod_get_quotation($order_id=0){
        if($order_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'quotes/'.$order_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'quotes';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod quotation
    public function qoyod_create_quotation($quote_id){
        $quote_detail = Quotation::where('id',$quote_id)->first();
        $quote_products = QuotationProduct::where('quotation_id',$quote_detail->id)->get();
        $prod = [];
        foreach ($quote_products as $quote_product){
            $product = $this->qoyod_get_product(0, 'q[en_name_eq]='.$quote_product->name);
            if($product['status'] && $product['data']->products[0]->id>0 && $quote_product->quantity>0 && $quote_product->amount_excluding_tax>0){
                $prod[] =array(
                    'product_id'=>$product['data']->products[0]->id,
                    'description'=>$quote_product->product_code,
                    'quantity'=>$quote_product->quantity,
                    'unit_price'=>$quote_product->amount_excluding_tax,
                    'discount'=>$quote_product->discount_percentage>0?$quote_product->discount_percentage:'',
                    'discount_type'=>$quote_product->discount_percentage>0?'percentage':'',
                    'tax_percent'=>$quote_product->tax_percentage>0?$quote_product->tax_percentage:0,
                );
            }
        }
        if(!empty($prod)){
            $customer = $this->qoyod_get_customer(0,'q[name_eq]='.$quote_detail->bill_to_name);
            $inventory = $this->get_db_qoyod_inventory_id($quote_detail->store_id);
            if($inventory->qoyod_inventory_id>0 && $customer['data']->customers[0]->id>0){
                $data = array(
                    'contact_id' => $customer['data']->customers[0]->id,
                    'quotation_no' => $quote_detail->slack,
                    'issue_date' => $quote_detail->quotation_date,
                    'expiry_date' => $quote_detail->quotation_due_date,
                    'status' => "Approved",
                    'inventory_id' => $inventory->qoyod_inventory_id,
                    'notes' => "",
                    'terms_conditions' => $quote_detail->terms,
                    'line_items' => $prod,
                );
                $api_url = env('QOYOD_ENDPOINT') . 'quotes';
                $this->api_call_with_body($api_url,'POST',['quote' => $data]);
            }
            return true;
        }else{
            return true;
        }
    }

    //INVOICE
    //Get qoyod invoice
    public function qoyod_get_invoice($invoice_id=0, $search=''){
        if($invoice_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'invoices/'.$invoice_id;
        }else{
            if($search!=''){
                $api_url = env('QOYOD_ENDPOINT') . 'invoices?'.$search;
            }else{
                $api_url = env('QOYOD_ENDPOINT') . 'invoices';
            }
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod invoice
    public function qoyod_create_invoice($sales_detail,$type){
        if($type=='Invoice'){
            $products = InvoiceProduct::where('invoice_id',$sales_detail->id)->get();
            if(isset($products) && $products[0]->product_id>0){
                $customer = $this->qoyod_get_customer(0,'q[name_eq]='.$sales_detail->bill_to_name);
                $customer_id = $customer['data']->customers[0]->id;
            }else{
                $customer_id = 0;
            }
        }elseif ($type=='Order'){
            $products = OrderProduct::where('order_id',$sales_detail->id)->get();
            $customer_id = $this->get_db_qoyod_customer_id($sales_detail->customer_id);
        }

        if($customer_id>0){
            $prod = [];
            foreach ($products as $product){
                $q_product = $this->qoyod_get_product(0, 'q[en_name_eq]='.$product->name);
                if($q_product['status'] && $q_product['data']->products[0]->id>0){
                    $prod[] =array(
                        'product_id'=>$q_product['data']->products[0]->id,
                        'description'=>$product->description!=''?$product->description:'',
                        'quantity'=>$product->quantity,
                        'unit_price'=>$type=='Invoice'?$product->amount_excluding_tax:$product->sale_amount_excluding_tax,
                        'discount'=>$product->discount_percentage>0?$product->discount_percentage:'',
                        'discount_type'=>$product->discount_percentage>0?'percentage':'',
                        'tax_percent'=>$product->tax_percentage>0?$product->tax_percentage:0,
                    );
                }
            }
            if(!empty($prod)){
                $inventory = $this->get_db_qoyod_inventory_id($sales_detail->store_id);
                $data = array(
                    'contact_id' => $customer_id,
                    'reference' => $sales_detail->slack,
                    'issue_date' => $type=='Invoice'?$sales_detail->invoice_date:$sales_detail->value_date,
                    'due_date' => $type=='Invoice'?$sales_detail->invoice_date:$sales_detail->value_date,
                    'status' => "Approved",
                    'inventory_id' => $inventory->qoyod_inventory_id,
                    'notes' => "",
                    'description' => $sales_detail->terms,
                    'line_items' => $prod,
                );
                $api_url = env('QOYOD_ENDPOINT') . 'invoices';
                $invoice_response = $this->api_call_with_body($api_url,'POST',['invoice' => $data]);
                if(!empty($invoice_response['data']) && $type=='Order'){
                    //create the bill payment
                    $qoyod_cash_account = $this->qoyod_get_account(0,'q[code_eq]='.$this->cash_account_code); //Cash Account
                    $payment_data =array(
                        'reference'=>$sales_detail->slack,
                        'invoice_id'=>$invoice_response['data']->invoice->id,
                        'account_id'=>$qoyod_cash_account['data']->accounts[0]->id,
                        'date'=>$type=='Invoice'?$sales_detail->invoice_date:$sales_detail->value_date,
                        'amount'=>$sales_detail->total_order_amount,
                    );
                    $this->qoyod_create_invoice_payment($payment_data);
                }
            }
        }
        return true;
    }
    //Delete qoyod invoice
    public function qoyod_delete_invoice($invoice_id){
        $api_url = env('QOYOD_ENDPOINT') . 'invoices/'.$invoice_id;
        return $this->api_call('DELETE',$api_url);
    }

    //INVOICE PAYMENT
    //Get qoyod invoice payment
    public function qoyod_get_invoice_payment($invoice_payment_id=0){
        if($invoice_payment_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'invoice_payments/'.$invoice_payment_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'invoice_payments';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod invoice payment
    public function qoyod_create_invoice_payment($data){
        $api_url = env('QOYOD_ENDPOINT') . 'invoice_payments';
        return $this->api_call_with_body($api_url,'POST',['invoice_payment' => $data]);
    }
    //Allocation qoyod existing credit note or receipt
    public function qoyod_allocation_invoice_payment($invoice_payment_id,$data){
        $api_url = env('QOYOD_ENDPOINT') . 'invoices/'.$invoice_payment_id.'/allocations';
        return $this->api_call_with_body($api_url,'POST',['invoice' => $data]);
    }

    //CREDIT NOTE
    //Get qoyod credit note
    public function qoyod_get_credit_note($credit_note_id=0){
        if($credit_note_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'credit_notes/'.$credit_note_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'credit_notes';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod credit note
    public function qoyod_create_credit_note($return_detail, $type){
        if($type=='Invoice'){
            $products = InvoiceReturnProducts::where('return_invoice_id',$return_detail->id)->get();
            $customer = $this->qoyod_get_customer(0,'q[name_eq]='.$return_detail->bill_to_name);
            $customer_id = $customer['data']->customers[0]->id;
        }elseif ($type=='Order'){
            $products = ReturnOrdersProducts::where('return_order_id',$return_detail->id)->get();
            $customer_id = $this->get_db_qoyod_customer_id($return_detail->customer_id);
        }
        $prod = [];
        foreach ($products as $product){
            $q_product = $this->qoyod_get_product(0, 'q[en_name_eq]='.$product->name);
            if($q_product['status'] && $q_product['data']->products[0]->id>0){
                $prod[] =array(
                    'product_id'=>$q_product['data']->products[0]->id,
                    'description'=>$product->description!=''?$product->description:'',
                    'quantity'=>$product->quantity,
                    'unit_price'=>$type=='Invoice'?$product->amount_excluding_tax:$product->sale_amount_excluding_tax,
                    'discount'=>$product->discount_percentage>0?$product->discount_percentage:'',
                    'discount_type'=>$product->discount_percentage>0?'percentage':'',
                    'tax_percent'=>$product->tax_percentage>0?$product->tax_percentage:0,
                );
            }
        }
        if(!empty($prod)){
            $inventory = $this->get_db_qoyod_inventory_id($return_detail->store_id);
            if($inventory->qoyod_inventory_id>0){
                $data = array(
                    'contact_id' => $customer_id,
                    'reference' => $return_detail->slack,
                    'issue_date' => $type=='Invoice'?$return_detail->invoice_date:$return_detail->value_date,
                    'due_date' => $type=='Invoice'?$return_detail->invoice_date:$return_detail->value_date,
                    'status' => "Approved",
                    'inventory_id' => $inventory->qoyod_inventory_id,
                    'notes' => "",
                    'description' => $return_detail->terms,
                    'line_items' => $prod,
                );
                $api_url = env('QOYOD_ENDPOINT') . 'credit_notes';
                $this->api_call_with_body($api_url,'POST',['credit_note' => $data]);
            }
            return true;
        }else{
            return true;
        }

    }
    //Delete qoyod credit note
    public function qoyod_delete_credit_note($credit_note_id){
        $api_url = env('QOYOD_ENDPOINT') . 'credit_notes/'.$credit_note_id;
        return $this->api_call('DELETE',$api_url);
    }

    //RECEIPT
    //Get qoyod receipt
    public function qoyod_get_receipt($receipt_id=0){
        if($receipt_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'receipts/'.$receipt_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'receipts';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod receipt
    public function qoyod_create_receipt($data){
        $api_url = env('QOYOD_ENDPOINT') . 'receipts';
        return $this->api_call_with_body($api_url,'POST',['receipt' => $data]);
    }
    //Allocation qoyod receipt
    public function qoyod_allocation_receipt($receipt_id,$data){
        $api_url = env('QOYOD_ENDPOINT') . 'receipts/'.$receipt_id.'/allocations';
        return $this->api_call_with_body($api_url,'POST',['allocations' => $data]);
    }
    //Delete qoyod receipt
    public function qoyod_delete_receipt($receipt_id){
        $api_url = env('QOYOD_ENDPOINT') . 'receipts/'.$receipt_id;
        return $this->api_call('DELETE',$api_url);
    }

    //JOURNAL ENTRIES
    //Get qoyod journal entry
    public function qoyod_get_journal_entry($journal_entry_id=0){
        if($journal_entry_id>0){
            $api_url = env('QOYOD_ENDPOINT') . 'journal_entries/'.$journal_entry_id;
        }else{
            $api_url = env('QOYOD_ENDPOINT') . 'journal_entries';
        }
        return $this->api_call('GET', $api_url);
    }
    //Create qoyod journal entry
    public function qoyod_create_journal_entry($data){
        $api_url = env('QOYOD_ENDPOINT') . 'journal_entries';
        return $this->api_call_with_body($api_url,'POST',['journal_entry' => $data]);
    }



    //GENERAL CALL____________________________________________________________
    //General/Common function for call
    public function api_call($method,$api_url, $api_key = ''){
        $this->qoyod_define_headers($api_key);
        $client = new Client;
        try{
            if($method=='GET'){
                $response = $client->request($method, $api_url, $this->params);
            }else if($method=='DELETE'){
                $response = $client->delete($method, $api_url, $this->params);
            }

            $code =  $response->getStatusCode();
        }catch (\GuzzleHttp\Exception\ConnectException $e){
            // This is will catch all connection timeouts
            // Handle accordinly
        }catch (\GuzzleHttp\Exception\ClientException $e) {
            // This will catch all 400 level errors.
            $code = $e->getResponse()->getStatusCode();
        }

        if ($code == 200) {
            $account = $response->getBody()->getContents();
            $result = array('code'=>$code, 'status'=>true,'data'=>json_decode($account));
        } else {
            $result = array('code'=>$code, 'status'=>false,'data'=>[]);
        }

        return $result;
    }

    //General/Common function for call with body
    public function api_call_with_body($api_url,$method,$body){
        $this->qoyod_define_headers();
        $client = new Client;
        $this->params['json'] = $body;
        try{
            if($method == 'POST'){
                $response = $client->post($api_url, $this->params);
            }else if($method == 'PUT'){
                $response = $client->put($api_url, $this->params);
            }
            $code =  $response->getStatusCode();
            $data = $response->getBody()->getContents();
        }catch (\GuzzleHttp\Exception\ClientException $e) {
            // This will catch all 400 level errors.
            $code = $e->getResponse()->getStatusCode();
        }
        if ($code == 200 || $code == 201) {
            $result = array('code'=>$code,'status'=>true,'data'=>json_decode($data));
        } else {
            $result = array('code'=>$code,'status'=>false,'data'=>[]);
        }

        return $result;
    }
}
