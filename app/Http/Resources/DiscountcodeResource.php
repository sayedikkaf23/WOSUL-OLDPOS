<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DiscountcodeResource extends Resource
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
            'label' => $this->label,
            'discount_code' => $this->discount_code,
            'discounttype'=>$this->discounttype,
            'is_always'=>$this->is_always,
            'discount_type'=>$this->discount_type,
            'discount_value'=>$this->discount_value,
            'discount_start_date'=>$this->discount_start_date,
            'discount_end_date'=>$this->discount_end_date,
            'discount_applied_on'=>$this->discount_applied_on,
            'limit_on_discount'=>$this->limit_on_discount,
            'discount_applicable_products'=>$this->discount_applicable_products,
            'discount_not_applicable_products'=>$this->discount_not_applicable_products,
            'discount_applicable_categories'=>$this->discount_applicable_categories,
            'discount_percentage' => $this->discount_percentage,
            'description' => $this->description,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_DISCOUNTCODE'], true))?route('discount_code', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'id'=>$this->id
        ];
    }
}
