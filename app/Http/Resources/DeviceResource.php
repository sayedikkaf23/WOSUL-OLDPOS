<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DeviceResource extends Resource
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
            'title_ar' => $this->title_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'price' => $this->price,
            'image' => $this->image,
            'image_path' => $this->image_path,
            'status' => new MasterStatusResource($this->status_data),
            'deleted_at_label' => $this->parseDate($this->created_at),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
        ];
    }
}
