<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SubscriptionResource extends Resource
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
            'title' => $this->title,
            'short_description' => $this->short_description,
            'plan_tenure' => $this->plan_tenure,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'discount' => $this->discount,
            'discount_description' => $this->discount_description,
            'url' => $this->url,
            'url_ar' => $this->url_ar,
            'color_code' => $this->color_code,
            'is_live' => $this->is_live,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_SUBSCRIPTION'], true))?route('subscription', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
