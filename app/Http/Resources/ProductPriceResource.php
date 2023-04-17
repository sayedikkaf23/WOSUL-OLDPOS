<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductPriceResource extends Resource
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
            'product_id' => $this->product_id,
            'price_id' => $this->price_id,
            'sale_amount_excluding_tax' => $this->sale_amount_excluding_tax,
            'sale_amount_including_tax' => $this->sale_amount_including_tax ,
            'price' => $this->price ,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at)
        ];
    }
}
