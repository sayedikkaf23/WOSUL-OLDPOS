<?php

namespace App\Http\Controllers\API;

use App\Models\Scopes\StoreScope;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\UserPointsSettings as UserPointsSettingsModel;
use App\Models\BonatUserPointsSettings as BonatUserPointsSettingsModel;
use App\Http\Resources\UserPointsResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Product as ProductModel;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\BonatApiTrait;
use App\Models\BillingCounter as BillingCounterModel;
use App\Http\Resources\BillingCounterResource;
use App\Models\Store as StoreModel;
use App\Models\BonatStoreCounterPointsSettings as BonatStoreCounterPointsSettingsModel;
use App\Models\Category as CategoryModel;


class ThirdPartyApiIntegration extends Controller
{
    use BonatApiTrait;

    public function add_setting_user_points(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_USERPOINTS_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_user_points_setting_request($request);



            $user_points_setting = [
                "slack" => $this->generate_slack("user_points_settings"),
                "token_id" => $request->token_id,
                "secret_key" => $request->secret_key,
                "merchant_id" => session()->get('merchant_id'),
                "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "created_at" => NOW()
            ];

            $setting_id = UserPointsSettingsModel::create($user_points_setting)->id;

           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Abkhas User points settings added successfully"),
                    "data"    => $user_points_setting['slack']
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

    public function update_setting_user_points(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_USERPOINTS_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_user_points_setting_request($request);

            $user_points_setting_data_exists = UserPointsSettingsModel::select('id')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            if (empty($user_points_setting_data_exists)) {
                throw new Exception(trans("Trying to update invalid user points setting"), 400);
            }

          
            $user_points_setting = [
                "token_id" => $request->token_id,
                "secret_key" => $request->secret_key,
                "merchant_id" => session()->get('merchant_id'),
                "status" => $request->status,
                "updated_by" => $request->logged_user_id,
                "updated_at" => NOW()
            ];

            $action_response = UserPointsSettingsModel::where('slack', $slack)
                ->update($user_points_setting);

    
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Abkhas User points settings updated successfully"),
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

    public function validate_user_points_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'token_id' => 'max:150|required',
            'secret_key' => 'max:150|required',
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }


    public function add_setting_bonat_user_points(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_BONATUSERPOINTS_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_bonat_user_points_setting_request($request);


            $user_points_setting = [
                "slack" => $this->generate_slack("user_points_settings"),
                "bonat_merchant_id" => session()->get('merchant_slack'),
                "merchant_id" => session()->get('merchant_slack'),
                "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "created_at" => NOW()
            ];

            $setting_id = BonatUserPointsSettingsModel::create($user_points_setting)->id;

           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Bonat User points settings added successfully"),
                    "data"    => $user_points_setting['slack'],
                    "link"=>  route('bonat_user_points_settings'),
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

    public function update_setting_bonat_user_points(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_BONATUSERPOINTS_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_bonat_user_points_setting_request($request);

            $user_points_setting_data_exists = BonatUserPointsSettingsModel::select('id')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            if (empty($user_points_setting_data_exists)) {
                throw new Exception(trans("Trying to update invalid user points setting"), 400);
            }

          
            $user_points_setting = [
                "bonat_merchant_id" => session()->get('merchant_slack'),
                "merchant_id" => session()->get('merchant_slack'),
                "status" => $request->status,
                "updated_by" => $request->logged_user_id,
                "updated_at" => NOW()
            ];

            $action_response = BonatUserPointsSettingsModel::where('slack', $slack)
                ->update($user_points_setting);

    
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Bonat User points settings updated successfully"),
                    "data"    => $slack,
                    "link"=>  route('bonat_user_points_settings'),
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

    public function validate_bonat_user_points_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function validate_bonat_store_counter_points_setting_request($request)
    {
        $validator = Validator::make($request->all(), [
            'status' => $this->get_validation_rules("status", true),
            'counter_id' => $this->get_validation_rules("counter_id", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

   public function verify_bonat_merchant_setting(Request $request)
   {

    try {

        $this->validate_bonat_merchant_setting($request);

        $api_detail = BonatUserPointsSettingsModel::select('*')->where('merchant_id', session()->get('merchant_slack'))->first();


        $status = isset($api_detail->status) ? $api_detail->status : '';
        $bonat_merchant_id = isset($api_detail->bonat_merchant_id) ? $api_detail->bonat_merchant_id : '';
        $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';

        if ($status == 1 && isset($bonat_merchant_id)) {

            if($subdomain_name == 'demo')
            {
                $url = env('BONAT_API_URL').'/webhook/wosul/pos/v1/auth/check';
            }
            else
            {
                $url = env('BONAT_API_URL').'/webhook/wosul/pos/v1/auth/check';
            }
            
            $result = $this->verify_bonat_merchant($url,$bonat_merchant_id);
           
            if($result == TRUE)
            {
                $user_points_setting = [
                    "status" => 1,
                    "is_verify" =>1,
                    "updated_by" => $request->logged_user_id,
                    "updated_at" => NOW()
                ];
        
                $action_response = BonatUserPointsSettingsModel::where('bonat_merchant_id', $bonat_merchant_id)
                    ->update($user_points_setting);

                    return response()->json($this->generate_response(
                        array(
                            "message" => trans("Verifed Successfully."),
                        ),
                        'SUCCESS'
                    ));

            }
            else {

                $user_points_setting = [
                    "status" => 1,
                    "is_verify" =>0,
                    "updated_by" => $request->logged_user_id,
                    "updated_at" => NOW()
                ];
        
                $action_response = BonatUserPointsSettingsModel::where('bonat_merchant_id', $bonat_merchant_id)
                    ->update($user_points_setting);

                return response()->json($this->generate_response(
                    array(
                        "message" => trans("Verifed Failed."),
                        "status_code" => '400'
                    )
                  
                ));
            }            
        }
       
        return response()->json($this->generate_response(
            array(
                "message" => trans("Verifed Failed."),
                "status_code" => '400'
            )
           
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

   public function validate_bonat_merchant_setting($request)
   {
       $validator = Validator::make($request->all(), [
           'bonat_merchant_id' => 'required'
       ]);
       $validation_status = $validator->fails();
       if ($validation_status) {
        throw new Exception($validator->errors(), 400);
        // throw new Exception($validator->errors());
    }
   }

   

    public function get_bonat_product($slack)
    {      
            $item = ProductModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new ProductResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product loaded successfully"),
                    "data"    => $item_data
                ),
                'SUCCESS'
            ));
        
    }

    public function store_counters(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_STORE_COUNTER_LISTING';
            if(check_access(array($data['action_key']), true) == false){
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
           
            
            $query = BillingCounterModel::select('billing_counters.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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
                $query->where(function ($query) use ($filter_string, $filter_columns){
                    foreach($filter_columns as $filter_column){
                        $query->orWhere($filter_column, 'like', '%'.$filter_string.'%');
                    }
                });
            })

            ->get();
            
            $billing_counters = BillingCounterResource::collection($query);
           
            $total_count = BillingCounterModel::select("id")->get()->count();

            $item_array = [];
            foreach($billing_counters as $key => $billing_counter){
                
                $billing_counter = $billing_counter->toArray($request);

                $item_array[$key][] = $billing_counter['billing_counter_code'];
                $item_array[$key][] = $billing_counter['counter_name'];
                $item_array[$key][] = (isset($billing_counter['status']['label']))?view('common.status', ['status_data' => ['label' => $billing_counter['status']['label'], "color" => $billing_counter['status']['color']]])->render():'-';
                $item_array[$key][] = $billing_counter['created_at_label'];
                $item_array[$key][] = $billing_counter['updated_at_label'];
                $item_array[$key][] = (isset($billing_counter['created_by']['fullname']))?$billing_counter['created_by']['fullname']:'-';
                $item_array[$key][] = view('setting.third_party_integration.layouts.store_counter_actions', ['billing_counter' => $billing_counter])->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];
            
            return response()->json($response);
        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function add_bonat_store_counter_setting(Request $request)
    {
        try {

            if (!check_access(['A_EDIT_BONATSTORECOUNTERPOINTS_SETTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_bonat_store_counter_points_setting_request($request);

            $store_details = StoreModel::select('slack')->where('id', session('store_id'))->first();
            $store_slack = $store_details->slack;

            $user_points_setting = [
                "slack" => $this->generate_slack("user_points_settings"),
                "merchant_id" => session()->get('merchant_slack'),
                "store_id" => $store_slack,
                "counter_id" => $request->counter_id,
                "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "created_at" => NOW()
            ];

            $setting_id = BonatStoreCounterPointsSettingsModel::create($user_points_setting)->id;

           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Bonat Store counter points settings added successfully"),
                    "data"    => $user_points_setting['slack'],
                    "link"=>  route('store_counters'),
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

    public function update_bonat_store_counter_setting(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_BONATSTORECOUNTERPOINTS_SETTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_bonat_store_counter_points_setting_request($request);

            $user_points_setting_data_exists = BonatStoreCounterPointsSettingsModel::select('id')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            if (empty($user_points_setting_data_exists)) {
                throw new Exception(trans("Trying to update invalid store counter points setting"), 400);
            }

          
            $user_points_setting = [
                "status" => $request->status,
                "updated_by" => $request->logged_user_id,
                "updated_at" => NOW()
            ];

            $action_response = BonatStoreCounterPointsSettingsModel::where('slack', $slack)
                ->update($user_points_setting);

    
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Bonat Store counter points settings updated successfully"),
                    "data"    => $slack,
                    "link"=>  route('store_counters'),
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

    public function verify_store_bonat_merchant_setting(Request $request)
   {

    try {


        $api_detail = BonatStoreCounterPointsSettingsModel::select('*')->where('merchant_id', $request->bonat_merchant_id)->where('store_id', $request->store_id)->where('counter_id', $request->counter_id)->first();
        
        $status = isset($api_detail->status) ? $api_detail->status : '';
        $bonat_merchant_id = isset($api_detail->merchant_id) ? $api_detail->merchant_id : '';
        $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';
        
        if ($status == 1 && isset($bonat_merchant_id)) {

            if($subdomain_name == 'demo')
            {
                $url = env('BONAT_API_URL').'/webhook/wosul/pos/v1/auth/check';
            }
            else
            {
                $url = env('BONAT_API_URL').'/webhook/wosul/pos/v1/auth/check';
            }
            
            $result = $this->verify_bonat_merchant($url,$bonat_merchant_id);
           
            if($result == TRUE)
            {
                $user_points_setting = [
                    "status" => 1,
                    "is_verify" =>1,
                    "updated_by" => $request->logged_user_id,
                    "updated_at" => NOW()
                ];
        
                $action_response = BonatStoreCounterPointsSettingsModel::where('merchant_id', $bonat_merchant_id)->where('store_id', $request->store_id)->where('counter_id', $request->counter_id)
                    ->update($user_points_setting);

                    return response()->json($this->generate_response(
                        array(
                            "message" => trans("Verifed Successfully."),
                        ),
                        'SUCCESS'
                    ));

            }
            else {

                $user_points_setting = [
                    "status" => 1,
                    "is_verify" =>0,
                    "updated_by" => $request->logged_user_id,
                    "updated_at" => NOW()
                ];
        
                $action_response = BonatUserPointsSettingsModel::where('bonat_merchant_id', $bonat_merchant_id)
                    ->update($user_points_setting);

                return response()->json($this->generate_response(
                    array(
                        "message" => trans("Verifed Failed."),
                        "status_code" => '400'
                    )
                  
                ));
            }            
        }
       
        return response()->json($this->generate_response(
            array(
                "message" => trans("Verifed Failed."),
                "status_code" => '400'
            )
           
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
    
   public function verify_coupon_details(Request $request)
   {
      
       $merchant_slack = session()->get('merchant_slack');
       $store_id = session()->get('store_id');

       $store_details = StoreModel::select('slack')->where('id', $store_id)->first();
       $store_slack = $store_details->slack;
       $counter_slack = request()->counter_slack;
       $coupon = request()->coupon;
       $data =array();
       $api_detail = BonatStoreCounterPointsSettingsModel::select('*')->where('merchant_id', $merchant_slack)->where('store_id', $store_slack)->where('counter_id', $counter_slack)->first();

       $data['coupon'] =  $coupon;
       $data['branch'] =  $store_slack;
       $data['device_id'] =  $counter_slack;
       $data['merchant_id'] =  $merchant_slack;
       
       $status = isset($api_detail->status) ? $api_detail->status : '';
       $verify = isset($api_detail->is_verify) ? $api_detail->is_verify : '';
       $bonat_merchant_id = isset($api_detail->bonat_merchant_id) ? $api_detail->bonat_merchant_id : '';
       $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';

       if ($status == 1 && isset($bonat_merchant_id) && $verify == 1) {

           if($subdomain_name == 'demo')
           {
               $url = env('BONAT_API_URL');
           }
           else
           {
               $url = env('BONAT_API_URL');
           }
           
           
           $product = array();
           $product_result= array();
           $result = $this->verify_coupon($url,$data);
           if($result != FALSE)
           {
           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Coupon verified successfully."),
                    "data"    => $result,
                ),
                'SUCCESS'
            ));

           }
           else {

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Coupon not valid."),
                    "status_code" => 400,
                )
            ));

               
           }            
       }
       else {

        return response()->json($this->generate_response(
            array(
                "message" => trans("Please verify the bonat merchant."),
                "status_code" => 400,
            )
        ));
          
       }  

   }

   public function categories()
   {
       $categories = CategoryModel::select('id', 'slack', 'category_code', 'label')->sortLabelAsc()->active()->get();
       return response()->json($categories);
   }

   public function products()
   {
        // $header = request()->header('auth');
        $products = ProductModel::withoutGlobalScope(StoreScope::class)->select('id', 'slack', 'product_code', 'name', 'sale_amount_including_tax', 'category_id')->sortNameAsc()->active()->get();
        return response()->json($products);  
   }

   public function devices()
   {
        $devices = BillingCounterModel::withoutGlobalScope(StoreScope::class)->select('id', 'slack', 'billing_counter_code', 'counter_name')->orderBy('counter_name', 'ASC')->active()->get();
        return response()->json($devices);  
   }

   public function branches()
   {
        $branches = StoreModel::select('id', 'slack', 'store_code', 'name')->orderBy('name', 'ASC')->active()->get();
        return response()->json($branches);  
   }

}
