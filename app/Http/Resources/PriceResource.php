<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PriceResource extends Resource
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
            'price_code' => $this->price_code,
            'store_id' => $this->store_id,
            'name' => $this->name,
            'name_ar' => $this->name_ar ,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_PRICE'], true))?route('price', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at)
        ];
    }
}
