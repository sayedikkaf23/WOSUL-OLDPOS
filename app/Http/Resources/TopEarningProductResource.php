<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TopEarningProductResource extends Resource
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
            'amount' => $this->amount,
            'total_sales_margin_amount' => $this->total_sales_margin_amount,
            'percent' => $this->percent,
           ];
    }
}
