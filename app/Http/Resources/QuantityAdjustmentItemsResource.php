<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;

class QuantityAdjustmentItemsResource extends Resource
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
            'quantity_adjustment_id' => $this->quantity_adjustment_id,
            'product_id' => $this->product_id,
            'product' => $this->product,
            'quantity'=>$this->quantity
        ];
    }
}