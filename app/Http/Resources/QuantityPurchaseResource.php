<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class QuantityPurchaseResource extends Resource
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
            'po_number' => $this->po_number,
            'po_reference' => $this->po_reference,
            'order_date' => $this->parseDateOnly($this->order_date),
            'order_due_date' => $this->parseDateOnly($this->order_due_date),
            'order_date_raw' => $this->order_date,
            'order_due_date_raw' => $this->order_due_date,
            'supplier_code' => $this->supplier_code,
            'supplier_name' => $this->supplier_name,
            'supplier_address' => $this->supplier_address,
            'supplier' => new SupplierResource($this->supplier),
            'currency_name' => $this->currency_name,
            'currency_code' => $this->currency_code,
            'discount_type' => $this->discount_type,
            'discount_rate' => $this->discount_rate,
            'subtotal_excluding_tax' => $this->subtotal_excluding_tax,
            'total_discount_amount' => $this->total_discount_amount,
            'total_after_discount' => $this->total_after_discount,
            'total_tax_amount' => $this->total_tax_amount,
            'shipping_charge' => $this->shipping_charge,
            'packing_charge' => $this->packing_charge,
            'total_order_amount' => $this->total_order_amount,
            'tax_option_data' => new MasterTaxOptionResource($this->tax_option_data),
            'update_stock' => $this->update_stock,
            'terms' => $this->terms,
            'products' => QuantityPurchaseProductResource::collection($this->products),
            'store' => new StoreResource($this->storeData),
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_QUANTITY_PURCHASE'], true))?route('quantity_purchase_detail', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
