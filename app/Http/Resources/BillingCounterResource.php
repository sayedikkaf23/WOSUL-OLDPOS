<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BillingCounterResource extends Resource
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
            'slack' => $this->slack,
            'store_id' =>  $this->store_id,
            'store_slack' => isset($this->get_store_slack->slack) ? $this->get_store_slack->slack : '',
            'billing_counter_code' => $this->billing_counter_code,
            'counter_name' => $this->counter_name,
            'description' => $this->description,
            'business_register' => new BusinessRegisterResource($this->businessRegister),
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_BILLING_COUNTER'], true))?route('billing_counter', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
