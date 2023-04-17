<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;


class SupplierResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $request->request->add(['blocking_recurring_data_in_transaction' => true]);

        
        return [
            'slack' => $this->slack,
            'supplier_code' => $this->supplier_code,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'pincode' => $this->pincode,
            'website' => $this->website,
            'organization_name' => $this->organization_name,
            "building_number" =>$this->building_number,
            "street_name" => $this->street_name,
            "district" => $this->district,
            "country_id" => $this->country_id,
            "city" => $this->city,
            "other_seller_id" => $this->other_seller_id,
            'tax_number' => $this->tax_number,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_SUPPLIER'], true))?route('supplier', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
