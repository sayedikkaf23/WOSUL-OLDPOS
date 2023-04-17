<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

/* Models */
use App\Models\Merchant as MerchantModel;

/* Resources */
use App\Http\Resources\MerchantResource;

use Carbon\Carbon;

use mysqli;

class Merchant extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_MERCHANT';
            // if(check_access(array($data['action_key']), true) == false){
            //     $response = $this->no_access_response_for_listing_table();
            //     return $response;
            // }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;
            
            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];
            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            
            $query = MerchantModel::select('merchants.*', 'master_status.label as status_label', 'master_status.color as status_color')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()

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

            $merchants = MerchantResource::collection($query);

            $total_count = MerchantModel::select("id")->get()->count();

            foreach($merchants as $key => $merchant){
                
                $merchant_id = $merchant->id;

                $merchant = $merchant->toArray($request);

                // $item_array[$key][] = $merchant_category['unit_code'];

                $db_name = $merchant['company_url'].'_wosul';

                $subscription = DB::table('subscription_activations')->select('start_date')->where('merchant_id',$merchant_id)->get();

                $db_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $db = DB::select($db_query, [$db_name]);
                $no_of_branches = '-';
                $subscription_date = '-';
                if (!empty($db)) {

                    $connect = mysqli_connect('localhost',config('database.connections.mysql.username'),config('database.connections.mysql.password'), $db_name);
                    $no_of_branches = mysqli_query($connect,"SELECT COUNT(*) AS store_count FROM stores");
                    if(!$no_of_branches || mysqli_num_rows($no_of_branches) == 0){
                        $no_of_branches = '-';
                    }
                    else{
                        $no_of_branches = mysqli_fetch_assoc($no_of_branches);
                        $no_of_branches = $no_of_branches['store_count'];
                    }
                    mysqli_close($connect);
                }

                if(isset($subscription[0]->start_date))
                    $subscription_date = Carbon::parse($subscription[0]->start_date)->addMonth()->format('d-m-Y');

                $item_array[$key][] = $merchant['name'];
                $item_array[$key][] = $merchant['company_name'];
                $item_array[$key][] = $merchant['company_url'];
                $item_array[$key][] = ($merchant['phone'] == null) ? 'N/A' : $merchant['phone'];
                $item_array[$key][] = $merchant['email'];
                
                $item_array[$key][] = (isset($merchant['status']['label']))?view('common.status', ['status_data' => ['label' => $merchant['status']['label'], "color" => $merchant['status']['color']]])->render():'-';
                $item_array[$key][] = $no_of_branches;
                $item_array[$key][] = $subscription_date;
                $item_array[$key][] = $merchant['created_at_label'];
                $item_array[$key][] = $merchant['updated_at_label'];
                // $item_array[$key][] = (isset($merchant['created_by']['fullname']))?$merchant['created_by']['fullname']:'-';
                $item_array[$key][] = view('merchant.layouts.merchant_actions', ['merchant' => $merchant])->render();

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
    public function store(Request $request)
    {
        // dd($request);
        try {
            if(!check_access(['A_ADD_MERCHANT'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $merchant_data_exists = MerchantModel::select('id')
            ->where('label', '=', trim($request->label))
            ->first();
            if (!empty($merchant_data_exists)) {
                throw new Exception("Merchant already exists", 400);
            }

            DB::beginTransaction();
            
            $merchant = [
                "slack" => $this->generate_slack("merchants"),
                "label" => $request->label,
                "merchant_category_id" => $request->category_id,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];
            
            $merchant_id = MerchantModel::create($merchant)->id;

            DB::commit();

            $data['merchants'] = MerchantModel::select('slack','label')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => "Merchant created successfully", 
                    "data"    => $data['merchants']
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

    public function update(Request $request)
    {
        try {

            if(!check_access(['A_ADD_MERCHANT'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $merchant_data_exists = MerchantModel::select('id')
            ->where('label', '=', trim($request->label))
            ->where('slack', '!=', trim($request->slack))
            ->first();
            if (!empty($merchant_data_exists)) {
                throw new Exception("Merchant already exists", 400);
            }

            DB::beginTransaction();
            
            $merchant = [
                "label" => $request->label,
                "merchant_category_id" => $request->category_id,
                "status" => $request->status
            ];

            $merchant_id = MerchantModel::where('slack',$request->slack)->update($merchant);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Merchant updated successfully", 
                    "data"    => $merchant_id
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

    public function store_conversion(Request $request)
    {
        try {

            if(!check_access(['A_ADD_MERCHANT'], true)){
                throw new Exception("Invalid request", 400);
            }

            // $this->validate_request($request);

            DB::beginTransaction();

            $merchant_detail = MerchantModel::where('slack',$request->slack)->first();
            $conversions = json_decode($request->conversions);

            if(isset($conversions)){

                foreach($conversions as $rs){

                    $check = [
                        "from_merchant_id"=>$merchant_detail->id,
                        "to_merchant_id"=>$rs->to_merchant_id,
                    ];
                    $checkIfAlreadyExists = MerchantConversionModel::where($check)->active()->first();

                    if(isset($checkIfAlreadyExists)){
                        // udpating the existing conversions
                        MerchantConversionModel::where($check)->update([
                            "value" => $rs->to_merchant_value
                        ]);
                    }else{
                        // adding new conversions
                        $new = $check;
                        $new['slack'] = $this->generate_slack("merchant_conversions");
                        $new['value'] = $rs->to_merchant_value;
                        MerchantConversionModel::create($new);
                    }
                    
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Merchant Conversions Updated successfully", 
                    "data"    => $conversions
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

   public function get_conversion_units(Request $request){

        if(isset($request->merchant_slack)){

        }else{

            try {
                
                $conversion_units = MerchantModel::where('merchant_category_id',$request->category_id)
                ->active()
                ->get();

                $response = [
                    // 'draw' => $draw,
                    // 'recordsTotal' => $total_count,
                    // 'recordsFiltered' => $total_count,
                    'data' => $conversion_units
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

   } 

   public function load_merchants(Request $request){

        try {

            $category_id = $request->category_id;

            if($category_id == ""){
                abort(404);
            }

            $conversion_units = MerchantModel::where('merchant_category_id',$request->category_id)
            ->active()
            ->get();

            $response = [
                'data' => $conversion_units
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

   public function load_merchants_for_ingredient(Request $request){

        try {
            $ingredient_slack = $request->ingredient_slack;

            if($ingredient_slack == ""){
                abort(404);
            }

            $merchant_detail = ProductModel::select('merchant_id','purchase_amount_excluding_tax','sale_amount_excluding_tax')->where('slack',$ingredient_slack)->first();
            $merchant_id = $merchant_detail->merchant_id;
            $ingredient_purchase_price_per_unit = $merchant_detail->purchase_amount_excluding_tax;
            $ingredient_sale_price_per_unit = $merchant_detail->sale_amount_excluding_tax;

            $merchant_category_id = MerchantModel::find($merchant_id)->merchant_category_id;
            $merchants = MerchantModel::where('merchant_category_id',$merchant_category_id)->active()->get();

            $data['merchants'] = [];
            if(!empty($merchants)){
                foreach($merchants as $rs){

                    $dataset = [];
                    if($rs->id == $merchant_id){
                        $dataset = $rs;
                        $dataset['ingredient_purchase_price_per_unit'] = $ingredient_sale_price_per_unit;
                        $dataset['ingredient_sale_price_per_unit'] = $ingredient_purchase_price_per_unit;

                    }else{
                        $conversion = MerchantConversionModel::where([
                            'from_merchant_id' =>$merchant_id,
                            'to_merchant_id' => $rs->id
                        ])->first();

                        if(isset($conversion) && $conversion->value > 1 ){
                            $dataset = $rs;
                            $dataset['ingredient_purchase_price_per_unit'] = $ingredient_purchase_price_per_unit * 1 / $conversion->value;
                            $dataset['ingredient_sale_price_per_unit'] = $ingredient_sale_price_per_unit * 1 / $conversion->value;
                        }
                    }
                    if(!empty($dataset)){
                        $data['merchants'][] = $dataset;
                    }
                }
            }

            // dd($data['merchants']);

            $response = [
                'data' => $data['merchants']
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


   public function validate_request($request)
   {
        $validator = Validator::make($request->all(), [
            'label' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

    public function verifyMerchant(Request $request){ 
        $data['merchant'] = DB::select("select * from merchants where email='".$request->get('merchant_email_id')."'");
        $subscription_start_date = "";
        if(count($data['merchant'])>0 && isset($data['merchant'][0]->id))
        {
            $orderdetails = DB::select("select * from orders where merchant_id=".$data['merchant'][0]->id);
            if(count($orderdetails)>0 && isset($orderdetails[0]->id)){
                $subscription_details = DB::select("select * from order_subscriptions where order_id=".$orderdetails[0]->id);
                if(count($subscription_details)>0)
                {
                    for($i = 0;$i<count($subscription_details);$i++){
                          $subscription_start_date = $subscription_details[$i]->end_date;
                    }
                }
                $subscription_start_date = (new \DateTime($subscription_start_date))->add(new \DateInterval("P1D"))->format("Y-m-d");
             }
             else{
                $subscription_start_date = date('Y-m-d');
            }
        }
        
        /*$subscriptionDetails = DB::select("select * from subscription_device_orders where merchant_id=".((isset($data['merchant'][0]))?$data['merchant'][0]->id:0));
        $subscriptionDays = 0;
        if(count($subscriptionDetails)>0)
        {
            foreach($subscriptionDetails as $subscription)
            {
                if($subscription->product_type=="subscription")
                {
                  $subscriptionDate = $subscription->subscription_expiration_date;
                }
            }
            $subscriptionDate = (new \DateTime($subscriptionDate))->add(new \DateInterval("P1D"))->format("Y-m-d");
        }
        else
        {
            $subscriptionDate = date("Y-m-d");
        }
        $subscriptionDate = date("Y-m-d");*/
        $response = [
            'data' => (isset($data['merchant'][0]))?$data['merchant'][0]:[],
            'subscriptionDate'=> $subscription_start_date
        ];
        return response()->json($response);
    }

    public function setProductListInSession(Request $request){
        $request->session()->put("productlist",json_decode($request->productlist));
        $request->session()->put("language",json_decode($request->language));
        $response = [
            "status"=>200
        ];
        return response()->json($response);
    }

    public function logoutMerchant(Request $request){
        $request->session()->forget("merchant_email");
        $request->session()->forget("merchant_start_date");
        $response = [
            "status" => "200"
        ];
        return response()->json($response);
    }

}
