<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Customer as CustomerModel;

use App\Http\Resources\CustomerResource;

use App\Http\Resources\Collections\CustomerCollection;
use App\Http\Traits\QoyodApiTrait;
use App\Models\QoyodCustomer;
use App\Models\CustomerAdditionalInfo;

class Customer extends Controller
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

            $data['action_key'] = 'A_VIEW_CUSTOMER_LISTING';
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
            
            $query = CustomerModel::select('customers.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()
            ->createdUser()
            ->skipDefaultCustomer()

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

            $customers = CustomerResource::collection($query);
           
            $total_count = CustomerModel::select('id')->skipDefaultCustomer()->get()->count();

            $item_array = [];
            foreach($customers as $key => $customer){
                
                $customer = $customer->toArray($request);

                $item_array[$key][] = (!empty($customer['name']))?$customer['name']:'-';
                $item_array[$key][] = (!empty($customer['email']))?$customer['email']:'-';
                $item_array[$key][] = (!empty($customer['phone']))?$customer['phone']:'-';
                $item_array[$key][] = (isset($customer['status']['label']))?view('common.status', ['status_data' => ['label' => $customer['status']['label'], "color" => $customer['status']['color']]])->render():'-';
                $item_array[$key][] = $customer['created_at_label'];
                $item_array[$key][] = $customer['updated_at_label'];
                $item_array[$key][] = (isset($customer['created_by']) && isset($customer['created_by']['fullname']))?$customer['created_by']['fullname']:'-';
                $item_array[$key][] = view('customer.layouts.customer_actions', array('customer' => $customer))->render();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        try {

            if(!check_access(['A_ADD_CUSTOMER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);
            
            //check email already exists
            if($request->email != ''){
                $customer_email_exists = CustomerModel::where('email', $request->email)->first();
                if ($customer_email_exists) {
                    throw new Exception(trans("Customer email already exists"));
                }
            }

            //check phone already exists
            if($request->phone != ''){
                $customer_phone_exists = CustomerModel::where('phone', $request->phone)->first();
                if ($customer_phone_exists) {
                    throw new Exception(trans("Customer phone already exists"));
                }
            }

            DB::beginTransaction();

            $customer = [
                "slack" => $this->generate_slack("customers"),
                'customer_type' => (isset($request->customer_type) && $request->customer_type != '' ) ? $request->customer_type : 'CUSTOM' ,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address, 
                "tax_number" => $request->tax_number,
                "website" => $request->website,
                "organization_name" => $request->organization_name,
                "status" => (isset($request->status) && $request->status != null ) ? $request->status : 1,
                "created_by" => $request->logged_user_id,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "country_id" => $request->country,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "pincode" => $request->pincode,
            ];
            
            $customer_id = CustomerModel::create($customer)->id;

            $additional_info = json_decode($request->add_infos);
            if($customer_id>0 && !empty($additional_info)){
                //add the additional info
                foreach($additional_info as $add_info){
                    if($add_info->title!='' && $add_info->desc!=''){
                        $add_array = array(
                            "slack" => $this->generate_slack("customer_additional_info"),
                            "customer_id" => $customer_id,
                            "title" =>$add_info->title,
                            "description" => $add_info->desc,
                        );
                        CustomerAdditionalInfo::create($add_array);
                    }
                }
            }

            //qoyod entry
            if(Session('qoyod_status')){
                $customer = array_merge($customer,array('id'=>$customer_id));
                $this->qoyod_create_customer((object)$customer);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Customer created successfully"), 
                    "data"    => $customer['slack']
                ), trans('SUCCESS')
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

    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    { 
        try {

            if(!check_access(['A_DETAIL_CUSTOMER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = CustomerModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new CustomerResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Customer loaded successfully", 
                    "data"    => $item_data
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

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if(!check_access(['A_VIEW_CUSTOMER_LISTING'], true)){
                throw new Exception("Invalid request", 400);
            }

            $list = new CustomerCollection(CustomerModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Customers loaded successfully", 
                    "data"    => $list
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        try {

            if(!check_access(['A_EDIT_CUSTOMER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            //check email already exists
            if($request->email != ''){
                $customer_email_exists = CustomerModel::where('email', $request->email)->where('slack', '!=', $slack)->first();
                if ($customer_email_exists) {
                    throw new Exception(trans("Customer email already exists"));
                }
            }

            //check phone already exists
            if($request->phone != ''){
                $customer_phone_exists = CustomerModel::where('phone', $request->phone)->where('slack', '!=', $slack)->first();
                if ($customer_phone_exists) {
                    throw new Exception(trans("Customer phone already exists"));
                }
            }

            DB::beginTransaction();
            $customer_details = CustomerModel::where('slack', $slack)->first();
            $customer = [        
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address,
                "tax_number" => $request->tax_number,
                "website" => $request->website,
                "organization_name" => $request->organization_name,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "country_id" => $request->country,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "pincode" => $request->pincode,
            ];

            $data = CustomerModel::where('slack', $slack)
            ->update($customer);

            //Qoyod
            if(Session('qoyod_status')){
                $customer_id = CustomerModel::select('id')->where('slack',$slack)->first();
                $qoyod_customer = QoyodCustomer::select('qoyod_customer_id')->where('customer_id',$customer_id->id)->first();
                if(isset($qoyod_customer) && $qoyod_customer->qoyod_customer_id>0){
                    $this->qoyod_update_customer((object)$customer,$qoyod_customer->qoyod_customer_id);
                }
            }

            $additional_info = json_decode($request->add_infos);
            if($customer_details->id>0 && !empty($additional_info)){
                //delete the additional info
                CustomerAdditionalInfo::where('customer_id', $customer_details->id)->delete();
                //update the additional info
                foreach($additional_info as $add_info){
                    if($add_info->title!='' && $add_info->desc!='') {
                        $add_array = array(
                            "slack" => $this->generate_slack("customer_additional_info"),
                            "customer_id" => $customer_details->id,
                            "title" => $add_info->title,
                            "description" => $add_info->desc,
                        );
                        CustomerAdditionalInfo::create($add_array);
                    }
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Customer updated successfully"), 
                    "data"    => $data
                ), trans("SUCCESS")
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

    public function load_customer_list(Request $request)
    {
        try {
            $keywords = $request->keywords;
            $type = $request->type;

            $customer_list = CustomerModel::select('slack', 'name', 'email', 'phone')
            ->when($type == 'phone', function ($query, $sortBy) use ($keywords){
                return $query->where('phone', 'like', $keywords.'%');
            }, function ($query) use ($keywords) {
                return $query->where('email', 'like', $keywords.'%');
            })
            ->skipDefaultCustomer()
            ->get();
            
            return response()->json($this->generate_response(
                array(
                    "message" => "Customers loaded successfully", 
                    "data"    => $customer_list
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

    public function filter_customers(Request $request){
        try{

            $keyword = $request->keyword;

            $customer_list = CustomerModel::select("*")
            ->where('name', 'like', $keyword.'%')
            ->orWhere('email', 'like', $keyword.'%')
            ->orWhere('phone', 'like', $keyword.'%')
            ->limit(25)
            ->get();
            $customers = CustomerResource::collection($customer_list);
           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Customers filtered successfully"), 
                    "data" => $customers
                ), trans('SUCCESS')
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

    public function filter_single_customer(Request $request){
        try{

            $keywords = $request->keywords??'';
            $type = $request->type??'';

            $customer_list = CustomerModel::select("*")
            ->where('name', 'like', $keywords.'%')
            ->orWhere('email', 'like', $keywords.'%')
            ->orWhere('phone', 'like', $keywords.'%')
            ->limit(25)
            ->get();
            $customers = CustomerResource::collection($customer_list);
           
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Customers filtered successfully"), 
                    "data" => $customers
                ), trans('SUCCESS')
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

    public function load_customers(){

        $data = CustomerModel::select('id','slack','name', 'phone', 'email')->where('status',1)->get();
        return response()->json($data);

    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'email'  => 'required_if:phone,""'.'|'.$this->get_validation_rules("email", false),
            'phone'  => 'required_if:email,""'.'|'.$this->get_validation_rules("phone", false),
            'name'   => $this->get_validation_rules("fullname", true),
            'address'=> $this->get_validation_rules("text", false),
            // 'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
