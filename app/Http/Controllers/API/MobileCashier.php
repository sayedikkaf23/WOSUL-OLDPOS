<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\MobileCashierCollection;
use App\Http\Resources\MobileCashierResource;
use Illuminate\Http\Request;
use App\Models\MobileCashier as MobileCashierModel;
use Exception;

class MobileCashier extends Controller
{   

    public function index(Request $request){

        try {
            
            $mobile_cashiers =  MobileCashierModel::where('store_id',$request->store_id)->paginate();
            $data = new MobileCashierCollection($mobile_cashiers);

            return response()->json($this->generate_response(
                array(
                    "message" => "Mobile Cashiers loaded successfully",
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
        @request : type, store_id, device_id, response_data, device_type
        @response : MobileCashier Model
    */

    public function store(Request $request){
        
        try {
            
            $mobile_cashier = MobileCashierModel::where('store_id',$request->store_id)->where('device_id',$request->device_id)->first();
            
            if(isset($mobile_cashier)){
                throw new Exception('This device is already registered',401);    
            }
            
            if($request->type == 'CASHIER'){
                $mobile_cashier = MobileCashierModel::where('store_id',$request->store_id)->where('type','CASHIER')->first();
                if(isset($mobile_cashier)){
                    throw new Exception('You cant register more than one cashier for same store',401);    
                }
            }

            $mobile_cashier = MobileCashierModel::create($request->except('access_token'));
            $mobile_cashier = new MobileCashierResource($mobile_cashier);

            return response()->json($this->generate_response(
                array(
                    "message" => $request->type." Added Successfully",
                    "data"    => $mobile_cashier
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
        @request 
        store_id, device_id
        @response : Boolean
    */
    public function assign(Request $request){

        try {

            $mobile_cashier = MobileCashierModel::where('store_id',$request->store_id)->where('device_id',$request->device_id)->first();
            if(isset($mobile_cashier)){
                MobileCashierModel::where('store_id',$request->store_id)->update(['type'=>'SUB_CASHIER']);
                MobileCashierModel::where('store_id',$request->store_id)->where('device_id',$request->device_id)->update(['type'=>'CASHIER']);
                $mobile_cashier = MobileCashierModel::where('store_id',$request->store_id)->where('device_id',$request->device_id)->first();
            }else{
                throw new Exception('Mobile cashier not found',404);
            }
            
            return response()->json($this->generate_response(
                array(
                    "message" => "Cashier Device Updated Successfully",
                    "data"    => $mobile_cashier
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
