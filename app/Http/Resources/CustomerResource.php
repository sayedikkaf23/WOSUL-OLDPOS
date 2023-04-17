<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CustomerResource extends Resource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'tax_number' => $this->tax_number,
            'website' => $this->website,
            'organization_name' => $this->organization_name,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_CUSTOMER'], true))?route('customer', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            "building_number" =>$this->building_number,
            "street_name" => $this->street_name,
            "district" => $this->district,
            "country_id" => $this->country_id,
            "city" => $this->city,
            "other_seller_id" => $this->other_seller_id,
            'pincode' => $this->pincode,
            "add_infos"=> CustomerAdditionalInfoResource::collection($this->add_infos),
            'deleted_at_label' => $this->parseDate($this->deleted_at)
        ];
    }
}
