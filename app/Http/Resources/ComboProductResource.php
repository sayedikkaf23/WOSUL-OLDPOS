<?php

namespace App\Http\Resources;

use App\Models\ComboSize;
use Illuminate\Http\Resources\Json\Resource;

class ComboProductResource extends Resource
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
            'combo_id' => $this->combo_id,
            'combo_size_id' => $this->combo_size_id,
            'combo_group_id' => $this->combo_group_id,
            'product_id' => $this->product_id,
            'measurement_id' => $this->measurement_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'price_after_discount' => $this->price_after_discount,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            // relations
            'product' => new ProductResource($this->product),
            'measurement' => $this->measurement,
        ];
    }
}
