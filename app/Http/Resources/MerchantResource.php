<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MerchantResource extends Resource
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
            'name' => $this->name,
            'company_name' => $this->company_name,
            'company_url' => $this->company_url,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_MERCHANT'], true))?route('merchant', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
