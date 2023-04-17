<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TaxNameResource extends Resource
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
            'id' => $this->id,
            'tax_name' => $this->tax_name,
            'percentage' => $this->percentage,
            'is_visible' => $this->is_visible,
            'is_default' => $this->is_default,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_TAXNAME'], true))?route('tax_name', ['id' => $this->id]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
