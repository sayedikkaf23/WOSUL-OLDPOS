<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class QoyodResource extends Resource
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
            'merchant_id' => $this->merchant_id,
            'api_key' => $this->api_key,
            'status' => $this->status,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at)
        ];
    }
}
