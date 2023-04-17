<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SubscriptionFeatureResource extends Resource
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
            'subscription_id' => $this->subscription_id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at)
        ];
    }
}
