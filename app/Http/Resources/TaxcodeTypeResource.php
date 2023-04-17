<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TaxcodeTypeResource extends Resource
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
            'tax_type' => $this->tax_type,
            'tax_percentage' => $this->tax_percentage,
            'tax_name_id' => $this->tax_name_id,
            'created_by' => new UserResource($this->createdUser),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
        ];
    }
}
