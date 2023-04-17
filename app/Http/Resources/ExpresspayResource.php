<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ExpresspayResource extends Resource
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
            'bill_to' => $this->bill_to,
            'bill_to_id' => $this->bill_to_id,
            'amount' => $this->amount,
            'payment_link' => $this->payment_link,
            'data_json' => $this->data_json,
            'response_json' => $this->response_json,
            'response' =>  json_decode($this->response_json,true),
            'status' => new MasterStatusResource($this->status_data),
            'paid_at' => $this->paid_at,
            'paid_at_label' => $this->parseDate($this->paid_at),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
        ];
    }
}
