<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ModifierOptionIngredientResource extends Resource
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
            'modifier_option_id' => $this->modifier_option_id,
            'ingredient_id' => $this->ingredient_id,
            'quantity' => $this->quantity,
            'measurement_id' => $this->measurement_id,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
