<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class NewsSubscriptionResource extends Resource
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
            'email' => $this->email,
            'status' => new MasterStatusResource($this->status_data),
            'created_on' => $this->parseDate($this->created_on),
            'updated_on' => $this->parseDate($this->updated_on),
            'created_by' => new UserResource($this->created_by),
            'updated_by' => new UserResource($this->updated_by)
        ];
    }
}
