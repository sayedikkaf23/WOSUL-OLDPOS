<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductBarcodeDetailResource extends Resource
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

            'product_barcode_id'  => $this->id,
            'product_id' => $this->product_id,
            'barcode_no'  => $this->barcode_no,
            'product_slack'  => $this->product_slack,
            'is_ingredient'  => $this->is_ingredient,
            'quantity'  => $this->quantity,
            'show_barcode_value'  => $this->show_barcode_value,
            'show_item_name'  => $this->show_item_name,
            'show_item_price_with_vat'  => $this->show_item_price_with_vat,
            'show_store_name'  => $this->show_store_name,
            'store_id'  => $this->store_id,
            'store_name'  => $this->storeData->name,
            'show_manufacturing_date'  => $this->show_manufacturing_date,
            'manufacturing_date_raw'  => $this->manufacturing_date,
            'manufacturing_date' => !empty($this->manufacturing_date)?$this->parseDateOnly($this->manufacturing_date):'',
            'show_expiry_date'  => $this->show_expiry_date,
            'expiry_date_raw'  => $this->expiry_date,
            'expiry_date'  => !empty($this->expiry_date)? $this->parseDateOnly($this->expiry_date):'',
            'show_notes'  => $this->show_notes,
            'notes'  => $this->notes,
            'status'  => $this->status,
            'created_at' => $this->parseDate($this->created_at),
            'updated_at' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
        ];
    }
}
