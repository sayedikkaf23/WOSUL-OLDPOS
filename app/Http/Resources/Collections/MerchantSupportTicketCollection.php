<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\MerchantSupportTicketResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\MeasurementResource;

class MerchantSupportTicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => MerchantSupportTicketResource::collection($this->collection),
            'links' => [
                'has_more_items' => $this->hasMorePages(),
                'current_page' => $this->currentPage(),
                'next_page' => $this->nextPageUrl(),
                'total_records' => $this->total()
            ]
        ];
    }
}
