<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MobileCashierResource extends Resource
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
            'type' => $this->type,
            'status' => $this->status,
            'store_id' => $this->store_id,
            'device_id' => $this->device_id,
            'response_data' => $this->response_data,
            'device_type' => $this->device_type,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
        ];
    }
}
