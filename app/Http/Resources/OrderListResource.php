<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderListResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $request->request->add(['blocking_recurring_data_in_transaction' => true]);

        $data = [
            'total_order_amount3' => 3,
            'id' => isset($this->transactions[0]) ? $this->transactions[0]['bill_to_id'] : '',
            'slack' => $this->slack,
            'order_number' => $this->order_number,
            'value_date' => (string) $this->value_date,
            'reference_number' => $this->reference_number,
            'restaurant_mode' => $this->restaurant_mode,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'counter_name' => $this->counter_name,
            'currency_name' => $this->currency_name,
            'currency_code' => $this->currency_code,
            'discount_type' => $this->discount_type,
            'order_level_discount_code' => $this->store_level_discount_code,
            'order_level_discount_percentage' => $this->store_level_total_discount_percentage,
            'order_level_discount_amount' => $this->store_level_total_discount_amount,
            'product_level_total_discount' => $this->product_level_total_discount_amount,
            'order_level_tax_code' => $this->store_level_tax_code,
            'order_level_tax_percentage' => $this->store_level_total_tax_percentage,
            // 'order_level_tax_amount' => $this->store_level_total_tax_amount,
            'order_level_tax_amount' => $this->total_tax_amount,
            'order_level_tax_components' => ($this->store_level_total_tax_components != '') ? json_decode($this->store_level_total_tax_components) : [],
            'product_level_total_tax' => $this->product_level_total_tax_amount,
            'purchase_amount_subtotal_excluding_tax' => $this->purchase_amount_subtotal_excluding_tax,
            'sale_amount_subtotal_excluding_tax' => $this->sale_amount_subtotal_excluding_tax,
            'total_amount_before_additional_discount' => $this->total_amount_before_additional_discount,
            'total_discount_before_additional_discount' => $this->total_discount_before_additional_discount,
            'additional_discount_percentage' => $this->additional_discount_percentage,
            'additional_discount_amount' => $this->additional_discount_amount,
            'total_discount_amount' => $this->total_discount_amount,
            'total_after_discount' => $this->total_after_discount,
            'total_tax_amount' => $this->total_tax_amount,
            'total_order_amount' => isset($this->return_order_amount) ? bcsub($this->total_order_amount, $this->return_order_amount, 2)  : $this->total_order_amount,
            'total_order_amount_rounded' => $this->total_order_amount_rounded,
            'payment_method' => $this->payment_method,
            'customer' => new CustomerResource($this->customer_data),
            'products' => OrderProductResource::collection($this->products),
            'store' => new StoreResource($this->storeData),
            'status' => new MasterStatusResource($this->status_data),
            'kitchen_status' => new MasterStatusResource($this->kitchen_status_data),
            'detail_link' => (check_access(['A_DETAIL_ORDER'], true)) ? route('order_detail', ['slack' => $this->slack]) : '',
            'edit_link' => (check_access(['A_EDIT_ORDER'], true)) ? route('edit_order', ['slack' => $this->slack]) : '',
            'transactions' => TransactionResource::collection($this->transactions),
            'order_type_data' => new MasterOrderTypeResource($this->order_type_data),
            'order_type' => $this->order_type,
            'restaurant_table_data' => new TableResource($this->restaurant_table_data),
            'table' => $this->table_number,
            'waiter_data' => new UserResource($this->waiterUser),
            'billing_type_data' => new MasterBillingTypeResource($this->billing_type_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'create_at_utc' => strtotime($this->created_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'total_order_amount2' => 2,
            'has_combo' => $this->has_combo,
        ];

        return $data;
    }
}
