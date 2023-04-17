<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Store as StoreModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\BillingCounter as BillingCounterModel;

class BonatStoreCounterPointsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'slack' => isset($this->slack) ? $this->slack : '-', 
            'merchant_id' => isset($this->merchant_id) ? $this->merchant_id : '-',
            'store_id' => isset($this->store_id) ? $this->store_id : '-',
            'counter_id' => isset($this->counter_id) ? $this->counter_id : '-',
            'status' => new MasterStatusResource($this->status_data),
            'verify' => new MasterStatusResource($this->verify_status_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
