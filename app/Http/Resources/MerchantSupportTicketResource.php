<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class MerchantSupportTicketResource extends Resource
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
            'title' => $this->title,
            'description' => $this->description,
            'merchant_id' => $this->merchant_id,
            'merchant_company_name' => $this->merchant_company_name,
            'ticket_type' => $this->ticket_type,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'priority' => $this->priority,
            'reporting_date' => $this->reporting_date,
            'status' => $this->status,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
        ];
    }
}
