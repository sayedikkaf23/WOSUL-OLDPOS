<?php

namespace App\Http\Controllers\API;

use App\Models\QoyodInventory;
use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;

use App\Http\Resources\StoreResource;
use App\Models\Store as StoreModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\Country as CountryModel;
use App\Models\Account as AccountModel;
use App\Models\MasterAccountType as MasterAccountTypeModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Models\UserStore as UserStoreModel;
use App\Models\Order as OrderModel;

use App\Http\Resources\Collections\StoreCollection;
use App\Models\Product;
use App\Models\SettingApp;
use App\Models\TaxcodeType;
use App\Models\TaxName;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\QoyodApiTrait;

class Store extends Controller
{
    use QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_STORE_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = StoreModel::select('stores.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->createdUser()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->get();

            $stores = StoreResource::collection($query);

            $total_count = StoreModel::select("id")->get()->count();

            $item_array = [];
            foreach ($stores as $key => $store) {

                $store = $store->toArray($request);

                $item_array[$key][] = $store['store_code'];
                $item_array[$key][] = $store['name'];
                $item_array[$key][] = (isset($store['status']['label'])) ? view('common.status', ['status_data' => ['label' => $store['status']['label'], "color" => $store['status']['color']]])->render() : '-';
                $item_array[$key][] = $store['created_at_label'];
                $item_array[$key][] = $store['updated_at_label'];
                $item_array[$key][] = (isset($store['created_by']) && isset($store['created_by']['fullname'])) ? $store['created_by']['fullname'] : '-';
                $item_array[$key][] = view('store.layouts.store_actions', array('store' => $store))->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_STORE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $store_name_exists = StoreModel::select('id')
                ->where('name', '=', trim($request->name))
                ->first();
            if (!empty($store_name_exists)) {
                throw new Exception(trans("Store name already assigned to a store"), 400);
            }

            $store_data_exists = StoreModel::select('id')
                ->where('store_code', '=', trim($request->store_code))
                ->first();
            if (!empty($store_data_exists)) {
                throw new Exception(trans("Store code already assigned to a store"), 400);
            }

            if ($request->country_code) {
                $country_data = CountryModel::select('id')
                    ->where('code', '=', trim($request->country_code))
                    ->active()
                    ->first();

                $request->request->add(['country' => $country_data->id]);
            } else {
                $country_data = CountryModel::select('id')
                    ->where('id', '=', trim($request->country))
                    ->active()
                    ->first();
            }

            if (empty($country_data)) {
                throw new Exception(trans("Invalid country selected"), 400);
            }

            $currency_data = CountryModel::select('currency_code', 'currency_name')
                ->where('currency_code', '=', trim($request->currency_code))
                ->active()
                ->first();
            if (empty($currency_data)) {
                throw new Exception(trans("Invalid currency selected"), 400);
            }

            $billing_type = MasterBillingTypeModel::select('id', 'label')
                ->active()
                ->where('billing_type_constant', '=', trim($request->restaurant_billing_type))
                ->first();

            $waiter_role = RoleModel::select('id', 'slack', 'role_code', 'label')->resolveSuperAdminRole()->active()->where('slack', '=', trim($request->restaurant_waiter_role))->first();

            $tax_code_id = NULL;
            if (isset($request->tax_code)) {
                $tax_code_id = $request->tax_code;
            }

            DB::beginTransaction();

            $store_logo = null;
            if ($request->hasFile('store_logo')) {
                $image = $request->file('store_logo');
                $store_logo   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('store')->put('/' . $store_logo, $img, 'public');
            }

            if ($request->store_opening_time == null || $request->store_opening_time == '') {
                $request->store_opening_time = '00:00';
            }
            if ($request->store_closing_time == null || $request->store_closing_time == '') {
                $request->store_closing_time = '00:00';
            }

            $store = [
                "slack" => $this->generate_slack("stores"),
                "store_code" => strtoupper(trim($request->store_code)),
                "name" => $request->name,
                "vat_number" => $request->vat_number,
                "tax_registration_name" => ($request->tax_registration_name != null) ? $request->tax_registration_name : '',
                "address" => $request->address,
                "country_id" => $request->country,
                "pincode" => $request->pincode,
                "primary_contact" => $request->primary_contact,
                "secondary_contact" => $request->secondary_contact,
                "primary_email" => $request->primary_email,
                "secondary_email" => $request->secondary_email,
                "invoice_type" => $request->invoice_type,
                "currency_code" => $currency_data->currency_code,
                "currency_name" => $currency_data->currency_name,
                "restaurant_mode" => !empty($request->restaurant_mode) ? $request->restaurant_mode : 0,
                "restaurant_billing_type_id" => (!empty($billing_type)) ? $billing_type->id : '',
                "restaurant_waiter_role_id" => (!empty($waiter_role)) ? $waiter_role->id : '',
                "enable_customer_popup" => !empty($request->enable_customer_popup) ? $request->enable_customer_popup : 0,
                "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "bank_name" => $request->bank_name,
                "account_holder_name" => $request->account_holder_name,
                "iban_number" => $request->iban_number,
                "pos_invoice_policy_information" => $request->pos_invoice_policy_information,
                "invoice_policy_information" => $request->invoice_policy_information,
                "purchase_policy_information" => $request->purchase_policy_information,
                "quotation_policy_information" => $request->quotation_policy_information,
                "store_logo" => $store_logo,
               // "tax_code_id" => $tax_code_id,
                "zid_store_api_token" => $request->zid_store_api_token,
                "zid_store_id" => $request->zid_store_id,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "store_opening_time" => $request->store_opening_time,
                "store_closing_time" => $request->store_closing_time,
                "is_store_closing_next_day" => ($request->is_store_closing_next_day == 'true' || $request->is_store_closing_next_day == 1) ? 1 : 0,
                "idle_time_status" => $request->idle_time_status,
                "idle_time" => $request->idle_time,
                "platform_mode" => $request->platform_mode,
                "platform_type" => $request->platform_type,
                "store_invoice_color" => $request->store_invoice_color,
            ];

            $store_id = StoreModel::create($store)->id;
            $this->create_default_business_account($request, $store_id);
            $tax_names = TaxName::all();
            
            if(!empty($tax_names)){
                foreach($tax_names as $tax_name){
                    $tax_code = strtoupper(str_replace(' ','_',$tax_name->tax_name));
                    $tax_code_id = TaxcodeModel::create([
                        'slack' => $this->generate_slack("tax_codes"), 
                        'store_id' => $store_id, 
                        'tax_code' => $tax_code, 
                        'label' => $tax_name->tax_name, 
                        'total_tax_percentage' => $tax_name->percentage, 
                        //'description' => $tax_name->tax_name, 
                        'created_by' => $request->logged_user_id, 
                        'updated_by' => $request->logged_user_id
                    ])->id;
                    $tax_code_type_arr = [
                        'tax_code_id' => $tax_code_id, 
                        'tax_type' => $tax_name->tax_name, 
                        'tax_percentage' => $tax_name->percentage, 
                        'tax_name_id' => $tax_name->id, 
                        'created_by' => $request->logged_user_id 
                    ];
                    if($tax_code == 'VAT_TAX' || $tax_name->percentage == 15.00){
                        StoreModel::where('id',$store_id)->update(["tax_code_id" => $tax_code_id]);
                    }
                    $tax_code_status = TaxcodeType::create($tax_code_type_arr);
                }

            }

            //Qoyod
            if(Session('qoyod_status')){
                $store = array_merge($store,array('store_id'=>$store_id));
                $this->qoyod_create_inventory((object)$store);
            }
            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Store created successfully"),
                    "data"    => $store['slack']
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

    /**
     * Display the specified resource.
     *
     *  param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_STORE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = StoreModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new StoreResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Store loaded successfully"),
                    "data"    => $item_data
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

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_STORE_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $list = new StoreCollection(StoreModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stores loaded successfully"),
                    "data"    => $list
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {

        try {

            if (!check_access(['A_EDIT_STORE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $store_name_exists = StoreModel::select('id')
                ->where([
                    ['slack', '!=', $slack],
                    ['name', '=', trim($request->name)]
                ])
                ->first();
            if (!empty($store_name_exists)) {
                throw new Exception(trans("Store name already assigned to a store"), 400);
            }

            $store_data_exists = StoreModel::select('id')
                ->where([
                    ['slack', '!=', $slack],
                    ['store_code', '=', trim($request->store_code)]
                ])
                ->first();
            if (!empty($store_data_exists)) {
                throw new Exception(trans("Store code already assigned to a store"), 400);
            }

            $store_data = StoreModel::where('slack', $slack)->first();

            $tax_code_id = NULL;
            if (isset($request->tax_code)) {
                $tax_code_id = $request->tax_code;
                // $taxcode_data = TaxcodeModel::select('id')
                // ->where('slack', '=', trim($request->tax_code))
                // ->active()
                // ->first();
                // if (empty($taxcode_data)) {
                //     throw new Exception("Tax code not found or inactive in the system", 400);
                // }
                // $tax_code_id = $taxcode_data->id;
            }

            $discount_code_id = NULL;
            if (isset($request->discount_code)) {
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                    ->where('slack', '=', trim($request->discount_code))
                    ->whereRaw("discounttype='code'")
                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                    ->whereRaw("limit_on_discount=-1 OR limit_on_discount>0")
                    ->active()
                    ->first();
                if (empty($discount_code_data)) {
                    throw new Exception(trans("Discount code not found or inactive in the system"), 400);
                }
                $discount_code_id = $discount_code_data->id;
            }

            $country_data = CountryModel::select('id')
                ->where('id', '=', trim($request->country))
                ->active()
                ->first();
            if (empty($country_data)) {
                throw new Exception(trans("Invalid country selected"), 400);
            }

            $currency_data = CountryModel::select('currency_code', 'currency_name')
                ->where('currency_code', '=', trim($request->currency_code))
                ->active()
                ->first();
            if (empty($currency_data)) {
                throw new Exception(trans("Invalid currency selected"), 400);
            }

            if ($request->status == 0) {
                $active_store_exists = StoreModel::select('id')
                    ->where([
                        ['slack', '!=', $slack],
                        ['status', '=', 1]
                    ])
                    ->count();
                if ($active_store_exists == 0) {
                    throw new Exception(trans("Atleast one store needs to be active in the system"), 400);
                }
            }

            $billing_type = MasterBillingTypeModel::select('id', 'label')
                ->active()
                ->where('billing_type_constant', '=', trim($request->restaurant_billing_type))
                ->first();

            $waiter_role = RoleModel::select('id', 'slack', 'role_code', 'label')->resolveSuperAdminRole()->active()->where('slack', '=', trim($request->restaurant_waiter_role))->first();

            if ($request->store_opening_time == null || $request->store_opening_time == '') {
                $request->store_opening_time = '00:00';
            }
            if ($request->store_closing_time == null || $request->store_closing_time == '') {
                $request->store_closing_time = '00:00';
            }

            DB::beginTransaction();

            $store = [
                "store_code" => strtoupper(trim($request->store_code)),
                "name" => $request->name,
                "vat_number" => $request->vat_number,
                "tax_registration_name" => ($request->tax_registration_name != null) ? $request->tax_registration_name : '',
                "tax_code_id" => $tax_code_id,
                "discount_code_id" => $discount_code_id,
                "address" => $request->address,
                "country_id" => $request->country,
                "pincode" => $request->pincode,
                "primary_contact" => $request->primary_contact,
                "secondary_contact" => $request->secondary_contact,
                "primary_email" => $request->primary_email,
                "secondary_email" => $request->secondary_email,
                "invoice_type" => $request->invoice_type,
                "currency_code" => $currency_data->currency_code,
                "currency_name" => $currency_data->currency_name,
                "restaurant_mode" => $request->restaurant_mode,
                "restaurant_billing_type_id" => (!empty($billing_type)) ? $billing_type->id : '',
                "restaurant_waiter_role_id" => (!empty($waiter_role)) ? $waiter_role->id : '',
                "enable_customer_popup" => $request->enable_customer_popup,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id,
                "bank_name" => $request->bank_name,
                "iban_number" => $request->iban_number,
                "account_holder_name" => $request->account_holder_name,
                "pos_invoice_policy_information" => $request->pos_invoice_policy_information,
                "invoice_policy_information" => $request->invoice_policy_information,
                "purchase_policy_information" => $request->purchase_policy_information,
                "quotation_policy_information" => $request->quotation_policy_information,
                "zid_store_api_token" => $request->zid_store_api_token,
                "zid_store_id" => $request->zid_store_id,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "store_opening_time" => $request->store_opening_time,
                "store_closing_time" => $request->store_closing_time,
                "is_store_closing_next_day" => ($request->is_store_closing_next_day == 'true' || $request->is_store_closing_next_day == 1) ? 1 : 0,
                "idle_time_status" => $request->idle_time_status,
                "idle_time" => $request->idle_time,
                "platform_mode" => $request->platform_mode,
                "platform_type" => $request->platform_type,
                "store_invoice_color" => $request->store_invoice_color,
            ];

            $store['store_logo'] = $store_data->store_logo;
            if ($request->hasFile('store_logo')) {

                $image = $request->file('store_logo');
                $store['store_logo']   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('store')->put('/' . $store['store_logo'], $img, 'public');

                // Delete old image
                if (!empty($store_data->store_logo)) {
                    Storage::disk('store')->delete($store_data->store_logo);
                }
            }

            $action_response = StoreModel::where('slack', $slack)
                ->update($store);

            //Qoyod
            if(Session('qoyod_status')){
                $qoyod_category = StoreModel::withoutGlobalScopes()->select('qoyod_inventory.qoyod_inventory_id')->leftJoin('qoyod_inventory','qoyod_inventory.store_id','=','stores.id')->where('stores.slack',$slack)->first();
                if(isset($qoyod_category) && $qoyod_category->qoyod_inventory_id>0){
                    $this->qoyod_update_inventory((object)$store,$qoyod_category->qoyod_inventory_id);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Store updated successfully"),
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

    public function getStoreTaxDetails(Request $request)
    {
        try {
            if (!check_access(['A_DETAIL_STORE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }
            if (!isset($request->slack) && empty($request->slack)) {
                throw new Exception(trans("Invalid request"), 400);
            }
            $store_tax_data = StoreModel::select('id','slack','name','vat_number','tax_registration_name','tobacco_tax_val','tax_code_id')
                ->where('slack', $request->slack)
                ->first();
            $tax_codes = DB::table('tax_codes')->where('store_id',$store_tax_data->id)->where('status',1)->get();
            $data['tax_codes'] = $tax_codes;
            $data['store_tax_data'] = $store_tax_data;
            //dd($tax_codes, $store_tax_data);
            $store_tax_view = view('store.layouts.store_tax_details', $data)->render();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Store tax details loaded successfully"),
                    "data"    => $store_tax_view,
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

    public function taxDetailsUpdate(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_STORE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_tax_update_request($request);
            $pre_store_tax = StoreModel::where('slack',$slack)->first(['id','tax_code_id','tobacco_tax_val']);
            $prod_update_status = 0;
            DB::beginTransaction();

            $store = [
                "vat_number" => $request->vat_number,
                "tax_registration_name" => ($request->tax_registration_name != null) ? $request->tax_registration_name : '',
                "tax_code_id" => $request->tax_code_id,
                "tobacco_tax_val" => $request->tobacco_tax_val,
            ];

            $action_response = StoreModel::where('slack', $slack)->update($store);
            $request->session()->put('store_tax_code', $request->tax_code_id);
            //dd($pre_store_tax->tax_code_id, $request->tax_code_id);
            //if(($pre_store_tax->tax_code_id != $request->tax_code_id) || ($pre_store_tax->tobacco_tax_val != $request->tobacco_tax_val)){
            if($request->tobacco_tax_val == 0){
                $prod_update_status = DB::update("UPDATE products as p
                JOIN stores s ON s.id = p.store_id 
                LEFT JOIN tax_codes tc ON p.store_id = tc.store_id and tc.id = s.tax_code_id
                SET p.sale_amount_excluding_tax = (CASE WHEN p.sale_amount_including_tax > 0 THEN round(((p.sale_amount_including_tax / (100 + tc.total_tax_percentage) ) * 100),4) WHEN p.sale_amount_including_tax = 0 OR  p.sale_amount_including_tax IS NULL THEN p.sale_amount_excluding_tax END), p.tax_code_id = s.tax_code_id, 
                p.tobacco_tax_percentage = 0, p.is_tobacco_tax = 0
                WHERE p.store_id=$pre_store_tax->id");
            }elseif($request->tobacco_tax_val > 0){
                $prod_update_status = DB::update("UPDATE products as p
                JOIN stores s ON s.id = p.store_id 
                LEFT JOIN tax_codes tc ON p.store_id = tc.store_id and tc.id = s.tax_code_id
                SET p.sale_amount_excluding_tax = (CASE WHEN p.sale_amount_including_tax > 0 THEN round( ((((p.sale_amount_including_tax / (100 + tc.total_tax_percentage)) * 100)) / (100 + s.tobacco_tax_val)) * 100,4) WHEN p.sale_amount_including_tax = 0 OR  p.sale_amount_including_tax IS NULL THEN p.sale_amount_excluding_tax END), 
                p.tax_code_id = s.tax_code_id, p.tobacco_tax_percentage = s.tobacco_tax_val, p.is_tobacco_tax = 1
                WHERE p.store_id=$pre_store_tax->id");
            }
            //}

            DB::commit();

            return response()->json(
                $this->generate_response(
                array(
                    "message" => trans("Store tax details updated successfully."),
                    "data"    => $slack,
                    "products_update_count" => $prod_update_status
                ),
                'SUCCESS'
            )
        );
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function create_default_business_account($request, $store_id)
    {

        $account_exists = AccountModel::select('id')
            ->where('store_id', '=', trim($store_id))
            ->first();

        if (!empty($account_exists)) {
            return;
        }

        $account_type_data = MasterAccountTypeModel::select('id')
            ->where('account_type_constant', '=', 'BASIC')
            ->first();

        $account = [
            "slack" => $this->generate_slack("accounts"),
            "store_id" => $store_id,
            "account_code" => Str::random(6),
            "account_type" => $account_type_data->id,
            "label" => 'Default Sales Account',
            "description" => 'Default Sales Account',
            "initial_balance" => 0,
            "pos_default" => 1,
            "status" => 1,
            "created_by" => $request->logged_user_id
        ];

        $account_id = AccountModel::create($account)->id;

        $code_start_config = Config::get('constants.unique_code_start.account');
        $code_start = (isset($code_start_config)) ? $code_start_config : 100;

        $account_code = [
            "account_code" => ($code_start + $account_id)
        ];

        AccountModel::withoutGlobalScopes()->where('id', $account_id)
            ->update($account_code);
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => $this->get_validation_rules("name_label", true),
            'address' => $this->get_validation_rules("text", true),
            'pincode' => $this->get_validation_rules("pincode", false),
            'store_code' => $this->get_validation_rules("codes", true),
            'vat_number' => $this->get_validation_rules("name_label", false),
            'primary_contact' => $this->get_validation_rules("phone", false),
            'secondary_contact' => $this->get_validation_rules("phone", false),
            'primary_email' => $this->get_validation_rules("email", false),
            'secondary_email' => $this->get_validation_rules("email", false),
            'invoice_type' => 'max:50|required',
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function validate_tax_update_request($request)
    {
        $validator = Validator::make($request->all(), [
            
            'tax_code_id' => $this->get_validation_rules("numeric", true),
            'vat_number' => $this->get_validation_rules("name_label", false),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function list_stores(Request $request)
    {
        try{
            $user = UserModel::where(['email' => $request->email, 'status' => 1])->first();

            if(!$user)
                throw new Exception("Invalid User");

            $user_id = $user->id;
            if($request->email == 'admin@wosul.sa'){
                $stores = StoreModel::select('stores.id as id', 'stores.name as name', 'stores.slack as slack', 'stores.store_logo as store_logo', 
                    'tax_codes.total_tax_percentage as total_tax_percentage') 
                    ->leftJoin('tax_codes', function ($join) {
                        $join->on('stores.tax_code_id', '=', 'tax_codes.id');
                    })
                    ->where('stores.status', '=', 1)
                    ->whereNotNull('stores.id')->orderby('stores.name')->get();
            }else{
                $stores = UserStoreModel::select('stores.id as id', 'stores.name as name', 'stores.slack as slack', 'stores.store_logo as store_logo', 
                    'tax_codes.total_tax_percentage as total_tax_percentage')
                    ->where('user_stores.user_id', '=', $user_id)
                    ->whereNotNull('stores.id')->storeData()->orderby('stores.name')->get();
            }
                
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Store listed successfully"),
                    "data"    => $stores
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

    public function switch(Request $request)
    {

        $previous_store_id = $request->store;
        $store_data = StoreModel::withoutGlobalScopes()->where('id',$previous_store_id)->first();

        UserModel::where('id', session('user_id'))->update(['store_id' => $request->store]);
        
        request()->logged_user_store_id = $request->store;
        
        $user_data = UserModel::select('users.*', 'stores.currency_name', 'stores.currency_code', 'stores.tax_code_id', 'stores.store_logo', 'stores.slack')->roleJoin()
            ->storeJoin()
            ->where('users.id', session('user_id'))
            ->active()
            ->first();
        $request->session()->put('currency_code', $user_data->currency_code);
        $request->session()->put('currency_name', $user_data->currency_name);
        $request->session()->put('store_tax_code', $user_data->tax_code_id);
        $request->session()->put('store_id', $user_data->store_id);
        $request->session()->put('store_logo', $user_data->store_logo);
        $request->session()->put('store_slack', $user_data->slack);

        return response()->json($this->generate_response(
            array(
                "message" => trans("Store Switched successfully"),
                "data"    => []
            ),
            'SUCCESS'
        ));
    }

    public function update_tax_setting_for_all_stores(Request $request){
        
        DB::beginTransaction();
        
        // 1. getting all the existing taxes and storing them into an array
        $all_stores = StoreModel::withoutGlobalScopes()->orderBy('id','ASC')->get();
        $existing_store_taxes = [];

        if(isset($all_stores)){
            
            foreach($all_stores as $store){

                $dataset = [];
                $dataset['store_id'] = $store->id;
                $dataset['percentage'] = 0; // default tax percentage
                
                if(isset($store->tax_code_id)){
                    $tax_code = TaxcodeModel::select('total_tax_percentage')->withoutGlobalScopes()->where('id',$store->tax_code_id)->first();
                    if(isset($tax_code)){
                        $dataset['percentage'] = $tax_code->total_tax_percentage; 
                    }
                }

                $existing_store_taxes[] = $dataset;
            }
        
        }

        // 2. not truncating all tax data
        TaxcodeType::truncate();
        TaxcodeModel::truncate();

        $tax_names = TaxName::all();
        
        // 3. storing all the default taxes for all the stores
        if(isset($all_stores)){
            
            foreach($all_stores as $store){
                
                    foreach($tax_names as $tax_name){
                    
                        $tax_code =  TaxCodeModel::create([
                            'slack' => $this->generate_slack('tax_codes'),
                            'store_id' => $store->id,
                            'label' => $tax_name['tax_name'],
                            'tax_code' => strtoupper(str_replace(' ','_',$tax_name['tax_name'])),
                            'total_tax_percentage' => $tax_name['percentage'],
                            'created_by' => 1,
                        ]);
                        
                        TaxCodeType::create( [
                            'tax_code_id' => $tax_code->id,
                            'tax_type' => $tax_name['tax_name'],
                            'tax_percentage' => $tax_name['percentage'],
                            'tax_name_id' => $tax_name['id'],
                            'created_by' => 1,
                        ]);
                        
                    }
         
            }
            
        }
        
        // 4. setting back all the previous existing taxes for all stores
        if(isset($existing_store_taxes) && count($existing_store_taxes) > 0){
            
            foreach($existing_store_taxes as $store){
                
                if($store['percentage'] > 0 ){
                    $new_tax_code_id =  TaxcodeModel::withoutGlobalScopes()->where('store_id',$store['store_id'])->where('tax_code','VAT')->first();
                }else{
                    $new_tax_code_id =  TaxcodeModel::withoutGlobalScopes()->where('store_id',$store['store_id'])->where('tax_code','NO_TAX')->first();
                }

                StoreModel::withoutGlobalScopes()->where('id',$store['store_id'])->update(['tax_code_id'=>$new_tax_code_id->id]);
                Product::withoutGlobalScopes()->where('store_id',$store['store_id'])->update(['tax_code_id'=>$new_tax_code_id->id]);
                
                /* ---------------------------------------------- */
                /* Updating reference number for existing orders */
                /* ---------------------------------------------- */
                $orders =  OrderModel::withoutGlobalScopes()->where('store_id',$store['store_id'])->orderBy('id','ASC')->get();
                $reference_number = 1;
                foreach($orders as $order){
                    OrderModel::withoutGlobalScopes()->where('id',$order->id)->update(['reference_number'=>$reference_number++]);
                }

            }

        }

        SettingApp::query()->update(['tax_setting_updated_at'=>Carbon::now()]);
        $request->session()->put('store_tax_code', $new_tax_code_id->id);

        DB::commit();

        return response()->json($this->generate_response(
            array(
                "message" => trans("Store Tax Updated successfully"),
                "data"    => []
            ),
            'SUCCESS'
        ));

    }

}
