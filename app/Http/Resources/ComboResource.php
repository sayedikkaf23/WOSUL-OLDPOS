<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ComboResource extends Resource
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
            'slack' => $this->slack,
            'store_id' => $this->store_id,
            'name' => $this->name,
            'is_discount_enabled' => $this->is_discount_enabled,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'status' => new MasterStatusResource($this->status_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            // relations
            'sizes' => $this->sizes,
            'groups' => $this->groups,
            'category' => $this->category,
            'products' => ComboProductResource::collection($this->products),
        ];
    }
}
