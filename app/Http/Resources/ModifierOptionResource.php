<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ModifierOptionResource extends Resource
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
            'modifier_id' => $this->modifier_id,
            'slack' => $this->slack,
            'label' => $this->label,
            'price' => $this->price,
            'modifier_option_ingredients' => ModifierOptionIngredientResource::collection($this->ingredients),
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_MODIFIER'], true))?route('modifier', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
