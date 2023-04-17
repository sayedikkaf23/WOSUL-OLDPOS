<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Store as StoreModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\BillingCounter as BillingCounterModel;

class BonatUserPointsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $store_detail = StoreModel::find(session('store_id'));
        if(isset($store_detail)){
            $store_detail = $store_detail->first();
        }else{
            $store_detail = '';
        }
        $business_register_data = BusinessRegisterModel::select('slack','billing_counter_id')
        ->where('user_id', '=', trim(session('user_id')))
        ->where('store_id', '=', trim(session('store_id')))
        ->whereNull('closing_date');
        if(isset($business_register_data)){
            $business_register_data = $business_register_data->first();
        }else{
            $business_register_data = '';
        }

        if(isset($business_register_data->billing_counter_id))
        {
            $billing_counter = BillingCounterModel::select('id', 'slack')->where('id', $business_register_data->billing_counter_id)
            ->active();
            if(isset($billing_counter))
            {
                $billing_counter =$billing_counter->first();
            }
            else {
                $billing_counter ='';
            }
          
        }

        return [
            'slack' => $this->slack,
            'bonat_merchant_id' => $this->bonat_merchant_id,
            'merchant_id' => $this->merchant_id,
            'store_slack' => isset($store_detail->slack) ? $store_detail->slack : '-',
            'counter_slack' => isset($billing_counter->slack) ? $billing_counter->slack : '-',
            'status' => new MasterStatusResource($this->status_data),
            'verify' => new MasterStatusResource($this->verify_status_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
