<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TopSellingProductResource extends Resource
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
            'name' => $this->name,
            'sum' => $this->sum,
            'total_sales_quantity' => $this->amount,
            'percent' => $this->percent,
           ];
    }
}
