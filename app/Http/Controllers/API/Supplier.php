<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;

use App\Http\Resources\SupplierResource;

use App\Http\Resources\Collections\SupplierCollection;

use App\Models\Supplier as SupplierModel;

use App\Models\Store as StoreModel;
use App\Models\QoyodVendor;

use App\Models\HRM\AccCoa as AccCoaModel;
use App\Http\Traits\QoyodApiTrait;

class Supplier extends Controller
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

            $data['action_key'] = 'A_VIEW_SUPPLIER_LISTING';
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
            
            $query = SupplierModel::select('suppliers.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

            $suppliers = SupplierResource::collection($query);
           
            $total_count = SupplierModel::select("id")->get()->count();

            $item_array = [];
            foreach($suppliers as $key => $supplier){

                $supplier = $supplier->toArray($request);

                $item_array[$key][] = Str::limit($supplier['name'], 50);
                $item_array[$key][] = $supplier['supplier_code'];
                $item_array[$key][] = (isset($supplier['status']['label']))?view('common.status', ['status_data' => ['label' => $supplier['status']['label'], "color" => $supplier['status']['color']]])->render():'-';
                $item_array[$key][] = $supplier['created_at_label'];
                $item_array[$key][] = $supplier['updated_at_label'];
                $item_array[$key][] = (isset($supplier['created_by']) && isset($supplier['created_by']['fullname']))?$supplier['created_by']['fullname']:'-';
                $item_array[$key][] = view('supplier.layouts.supplier_actions', array('supplier' => $supplier))->render();
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

            if(!check_access(['A_ADD_SUPPLIER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $supplier_data_exists = SupplierModel::select('id')
            ->where('name', '=', trim($request->supplier_name))
            ->first();
            if (!empty($supplier_data_exists)) {
                throw new Exception("Supplier already exists", 400);
            }

            DB::beginTransaction();
            $supplier_store_ids = [];
            if(isset($request->supplier_applicable_stores) && $request->supplier_applicable_stores!="")
            {
                foreach(explode(",",$request->supplier_applicable_stores) as $supplierid)
                {
                    array_push($supplier_store_ids,$supplierid);       
                }
            }
            array_push($supplier_store_ids,$request->logged_user_store_id);
            foreach($supplier_store_ids as $storeid)
            {
              $supplier = [
                "slack" => $this->generate_slack("suppliers"),
                "store_id" => $storeid,
                "supplier_code" => Str::random(6),
                "name" => Str::title($request->supplier_name),
                "address" => $request->address,
                "phone" => $request->phone,
                "email" => $request->email,
                "pincode" => $request->pincode,
                "website" => $request->website,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "country_id" => $request->country,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "organization_name" => $request->organization_name,
                "tax_number" => $request->tax_number,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
              ];
            
               $supplier_id = SupplierModel::create($supplier)->id;

               $code_start_config = Config::get('constants.unique_code_start.supplier');
               $code_start = (isset($code_start_config))?$code_start_config:100;
            
               $supplier_code = [
                 "supplier_code" => "SUP".($code_start+$supplier_id)
               ];
               SupplierModel::where('id', $supplier_id)
               ->update($supplier_code);

                //qoyod entry
                if(Session('qoyod_status')){
                    $supplier = array_merge($supplier,array('id'=>$supplier_id));
                    $this->qoyod_create_vendor((object)$supplier);
                }
            
              DB::commit();
           
              $this->add_hrm_acc_payable_supplier($supplier_id,$supplier);
              $suppliers_list = SupplierModel::select('slack', 'supplier_code', 'name')->sortNameAsc()->active()->get();
            }
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Supplier Created Successfully"), 
                    "data"    => $suppliers_list,
                    // "supplier_list"    => $suppliers_list
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
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    { 
        try {

            if(!check_access(['A_DETAIL_SUPPLIER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = SupplierModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new SupplierResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Supplier loaded successfully", 
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

            if(!check_access(['A_VIEW_SUPPLIER_LISTING'], true)){
                throw new Exception("Invalid request", 400);
            }

            $list = new SupplierCollection(SupplierModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Suppliers loaded successfully", 
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
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        try {

            if(!check_access(['A_EDIT_SUPPLIER'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $supplier_data_exists = SupplierModel::select('id')
            ->where([
                ['slack', '!=', $slack],
                ['name', '=', trim($request->supplier_name)],
            ])
            ->first();
            if (!empty($supplier_data_exists)) {
                throw new Exception("Supplier already exists", 400);
            }

            DB::beginTransaction();
            $supplier = [
                "name" => Str::title($request->supplier_name),
                "address" => $request->address,
                "phone" => $request->phone,
                "email" => $request->email,
                "pincode" => $request->pincode,
                "website" => $request->website,
                "building_number" => $request->building_number,
                "street_name" => $request->street_name,
                "district" => $request->district,
                "country_id" => $request->country,
                "city" => $request->city,
                "other_seller_id" => $request->other_seller_id,
                "organization_name" => $request->organization_name,
                "tax_number" => $request->tax_number,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];


            $action_response = SupplierModel::where('slack', $slack)
            ->update($supplier);

            //Qoyod
            if(Session('qoyod_status')){
                $vendor = SupplierModel::select('id')->where('slack',$slack)->first();
                $qoyod_vendor = QoyodVendor::select('qoyod_vendor_id')->where('vendor_id',$vendor->id)->first();
                if(isset($qoyod_vendor) && $qoyod_vendor->qoyod_vendor_id>0){
                    $this->qoyod_update_vendor((object)$supplier,$qoyod_vendor->qoyod_vendor_id);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Supplier Updated Successfully"), 
                    "data"    => $slack
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function load_supplier_list(Request $request)
    {
        try {
            $keywords = $request->keywords;
            
            $supplier_data = SupplierModel::select('slack',DB::raw("CONCAT(supplier_code,' - ',name) as label"))
            ->where(function($query) use ($keywords){
                $query->where('name', 'like', $keywords.'%')
                ->orWhere('supplier_code', 'like', $keywords.'%');
            })
            ->active()
            ->get();
            
            return response()->json($this->generate_response(
                array(
                    "message" => "Suppliers loaded successfully", 
                    "data"    => $supplier_data
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

    public function load_suppliers(){

        $data = SupplierModel::select('id','slack','name', 'email', 'phone')->where('status',1)->get();
        return response()->json($data);

    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => $this->get_validation_rules("name_label", true),
            'address' => $this->get_validation_rules("text", false),
            'pincode' => $this->get_validation_rules("pincode", false),
            'email' => $this->get_validation_rules("email", false),
            'phone' => $this->get_validation_rules("phone", false),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
        
    }

    public function generate_acc_payable_headcode_details()
	{
		$salary_headcode = "20101";
   
        $newdata = AccCoaModel::select('*')->where('HeadCode',$salary_headcode)->first(); 
        if (empty($newdata)) {
            return false;
        }
        $newidsinfo = AccCoaModel::select(DB::Raw('*, count(HeadCode) as hc'))->where('PHeadName',$newdata->HeadName)->first();
        if (empty($newidsinfo)) {
            return false;
        }


        
	  $nid  = $newidsinfo->hc;
	  $n = $nid + 1;
	  if ($n / 10 < 1)
		$HeadCode = $salary_headcode . "0" . $n;
	  else
		$HeadCode = $salary_headcode . $n;
  
	  $info = array();
	  $info['headcode'] = isset($HeadCode) ? $HeadCode : '' ;
	  $info['headlevel'] = isset($newdata->HeadLevel) ?  $newdata->HeadLevel + 1 : '';
	  return $info;
	
	}

    public function add_hrm_acc_payable_supplier($supplier_id,$supplier)
    {

        $coa = $this->generate_acc_payable_headcode_details();
        if (isset($coa['headcode'])) {
            $headcode = $coa['headcode'];
            $headlevel = $coa['headlevel'] ;
        } else {
            $headcode = "20101";
            $headlevel ="2";
        }

        $c_code = $supplier_id;
        $c_name = $supplier['name'];
        $c_acc = $c_code . '-' . $c_name;
        $createby = session()->get('user_id')!=null?session()->get('user_id'):'';
        $createdate = date('Y-m-d H:i:s');
        $data['aco']  = (object) $coaData = [
            'HeadCode'         => $headcode,
            'HeadName'         => $c_acc,
            'PHeadName'        => 'Accounts Payable',
            'HeadLevel'        => $headlevel,
            'IsActive'         => '1',
            'IsTransaction'    => '1',
            'IsGL'             => '1',
            'HeadType'         => 'L',
            'IsBudget'         => '0',
            'IsDepreciation'   => '0',
            'DepreciationRate' => '0',
            'CreateBy'         => $createby,
            'CreateDate'       => $createdate,
        ];
        $transaction_id = AccCoaModel::create($coaData)->id;

    } 
}