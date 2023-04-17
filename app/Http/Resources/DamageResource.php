<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DamageResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $data = [
            'id' => $this->id,
            'product' => $this->name,
            'branch' => $this->branch,
            'branch_reference' => $this->branch_reference,
            'return_type' => "Damaged Quantity",
            'added_by' => $this->fullname,
            'order_reference' => $this->order_reference,
            'time' => $this->time,
            'quantity'=> $this->quantity,
            'amount' => $this->total_amount,
            'reason' => $this->reason,
            'store' => $this->store,
            'created_at'=>$this->created_at,
            'user'=>$this->user,
            'tax_amount'=>$this->tax_amount,
            'discount_amount'=>$this->discount_amount,
            'product_code'=>$this->product_code,
            'product_slack' =>$this->product_slack,
            'name'=>$this->name
        ];

       return $data;
    }
}