<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;

class QuantityAdjustmentResource extends Resource
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
            'reference' => $this->reference,
            'branch' => $this->store->name,
            'action' => $this->action,
            'reason' => $this->reason,
            'status' => $this->status,
            'created_at' => $this->created_at!=null?(new \DateTime($this->created_at))->format("Y-m-d H:i:s"):null,
            'submitted_at' => $this->submitted_at!=null?(new \DateTime($this->submitted_at))->format("Y-m-d H:i:s"):null,
            'created_by' => $this->user->fullname,
            'slack'=>$this->slack,
            'store'=>$this->store,
            'user'=>$this->user
            
        ];
    }
}