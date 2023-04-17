<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductModifierResource extends Resource
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
            'label' => $this->label,
            'status' => new MasterStatusResource($this->status_data),
            'product_id' => $this->product_id,
            'modifier_id' => $this->modifier_id,
            'modifier' => new ModifierResource($this->modifier),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
