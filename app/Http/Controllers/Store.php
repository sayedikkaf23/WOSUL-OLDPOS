<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Store as StoreModel;
use App\Models\UserStore as UserStoreModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\MasterInvoicePrintType as MasterInvoicePrintTypeModel;
use App\Models\Country as CountryModel;
use App\Models\Account as AccountModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\Role as RoleModel;
use App\Http\Resources\TaxcodeResource;
use App\Http\Resources\StoreResource;
use App\Models\TaxcodeType;
use Schema;

class Store extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_STORE';
        check_access(array($data['menu_key'], $data['sub_menu_key']));
        // $tax_codes = TaxcodeModel::select('tax_codes.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
        //     ->statusJoin()
        //     ->createdUser()
        //     ->get();

        // $data['tax_codes'] = TaxcodeResource::collection($tax_codes);
        return view('store.stores', $data);
    }

    //This is the function that loads the add/edit page
    public function add_store($slack = null)
    {

        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_STORE';
        $data['action_key'] = ($slack == null) ? 'A_ADD_STORE' : 'A_EDIT_STORE';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('STORE_STATUS')->active()->sortValueAsc()->get();

        $data['invoice_print_types'] = MasterInvoicePrintTypeModel::select('print_type_value', 'print_type_label')->active()->get();

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

        $data['billing_type_list'] = MasterBillingTypeModel::select('id', 'billing_type_constant', 'label')
            ->active()
            ->get();

        $data['waiter_role'] = RoleModel::select('slack', 'role_code', 'label')->resolveSuperAdminRole()->active()->sortLabelAsc()->get();


        $tax_codes = TaxcodeModel::select('tax_codes.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->statusJoin()
            ->createdUser()
            ->get();

        $data['tax_codes'] = TaxcodeResource::collection($tax_codes);

        $data['store_data'] = null;
        $data['discount_codes'] = null;
        $data['accounts'] = null;

        if (isset($slack)) {

            $store = StoreModel::where('slack', '=', $slack)->first();
            if (empty($store)) {
                abort(404);
            }

            $data['accounts'] = AccountModel::withoutGlobalScopes()->select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')
                ->where('store_id', '=', $store->id)
                ->masterAccountTypeJoin()
                ->active()
                ->get();

            $store_data = new StoreResource($store);

            $data['store_data'] = $store_data;
            $data['restaurant_url'] = '';
            $data['restaurant_id'] = '';
            $data['store_id'] = '';
            // $data['tax_codes'] = TaxcodeModel::withoutGlobalScopes()->select('slack', 'tax_code', 'label')->where('store_id', $store_data->id)->active()->sortLabelAsc()->get();

            $currentdate = date('Y-m-d H:i:sa');
            $data['discount_codes'] = DiscountcodeModel::withoutGlobalScopes()->select('slack', 'discount_code', 'label')
                ->where('store_id', $store_data->id)
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->active()->sortLabelAsc()->get();
            //store QR

            $data['store_id'] = isset($store_data) ? $store_data->id : '';

            $hasTable = Schema::hasTable('qr_codes');

            if ($data['store_id'] && $hasTable) {

                $qrcode = DB::table('qr_codes')->select('id', 'restaurant_id')->where('store_id', '=', $data['store_id'])->first();

                if ($qrcode) {
                    $data['restaurant_id'] = isset($qrcode) ? $qrcode->restaurant_id : '';
                    $data['store_id'] = $data['store_id'];

                    $store_name = isset($store_data) ? $store_data->name : '';

                    $data['restaurant_url'] = isset($store_name) ? env('WOSULIN_URL') . 'restaurant/' . $this->makeSubdomain($store_name) : '';
                }
            }


            $data['user_id'] = session()->get('user_id') != null ? session()->get('user_id') : '';
            //Store QR
        }
        $data['logged_user_store_id'] = request()->logged_user_store_id;
        
        return view('store.add_store', $data);
    }

    public function makeSubdomain($name)
    {
        $cyr = [
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я',
        ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q',
        ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    //This is the function that loads the detail page
    public function detail($slack)
    {

        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_STORE';
        $data['action_key'] = 'A_DETAIL_STORE';
        check_access([$data['action_key']]);

        $store = StoreModel::where('slack', '=', $slack)->first();

        if (empty($store)) {
            abort(404);
        }

        $store_data = new StoreResource($store);

        $data['store_data'] = $store_data;

        return view('store.store_detail', $data);
    }

    public function select_store(Request $request)
    {
        $user_id = $request->logged_user_id;

        if ($request->is_super_admin == true) {
            $data['stores'] = StoreModel::select('stores.slack as store_slack', 'stores.store_code', 'stores.name', 'stores.address')->active()->get();
        } else {
            $data['stores'] = UserStoreModel::select('stores.slack as store_slack', 'stores.store_code', 'stores.name', 'stores.address')
                ->where('user_stores.user_id', '=', $user_id)
                ->storeData()
                ->get();
        }
        $data['is_super_admin'] = $request->is_super_admin;

        return view('store.select_store', $data);
    }

    public function updateDuplicateTaxIdOnStores($key){
        if($key = 999){
            $tax_code_status = false;
            $stores_list = DB::select("select s.id store_id,s.tax_code_id from stores s 
                                        where s.tax_code_id in 
                                            (select s2.tax_code_id from stores s2
                                            group by s2.tax_code_id 
                                            having count(s2.tax_code_id) > 1)
                                        order by s.id
                                        limit 1, 50");
            
            if(!empty($stores_list)){
                foreach($stores_list as $store){
                    
                    $tax_codes_defaults = DB::table('tax_codes')->where('id',$store->tax_code_id)->get();
                    //dump($tax_codes_defaults);
                    if(isset($tax_codes_defaults[0]) && !empty($tax_codes_defaults[0])){
                        foreach($tax_codes_defaults as $tax_codes){
                            $tax_code_array = [
                                    'slack' => $this->generate_slack("tax_codes"), 
                                    'store_id' => $store->store_id, 
                                    'tax_code' => $tax_codes->tax_code, 
                                    'label' => $tax_codes->label, 
                                    'total_tax_percentage' => $tax_codes->total_tax_percentage, 
                                    'description' => $tax_codes->description, 
                                    'status' => $tax_codes->status, 
                                    'created_by' => 1, 
                                    'updated_by' => 1
                                ];
                            $tax_code_id = TaxcodeModel::create($tax_code_array)->id;
                            $tax_code_type_arr = [
                                'tax_code_id' => $tax_code_id, 
                                'tax_type' => $tax_codes->label, 
                                'tax_percentage' => $tax_codes->total_tax_percentage, 
                                'created_by' => 1 
                            ];
                            if($tax_codes->tax_code == 'vat-tax' || $tax_codes->total_tax_percentage == 15.00){
                                $tax_code_type_arr['tax_name_id'] = 2;
            
                                StoreModel::where('id',$store->store_id)->update(["tax_code_id" => $tax_code_id]);
                            }elseif($tax_codes->tax_code == 'zero-tax'){
                                $tax_code_type_arr['tax_name_id'] = 3;
                            }elseif($tax_codes->tax_code == 'exempt-tax'){
                                $tax_code_type_arr['tax_name_id'] = 4;
                            }else{
                                $tax_code_type_arr['tax_name_id'] = 1;
                            }
                            $tax_code_status = TaxcodeType::create($tax_code_type_arr);
                        }
                    }
                }
            }
    
            dump($tax_code_status);
        }
    }
}
