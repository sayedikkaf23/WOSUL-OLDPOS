<?php

namespace App\Http\Resources;

use App\Models\Customer as CustomerModel;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
//use App\Models\DamageReportModel;

class OrderResource extends Resource
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
        $customer_name = '';
        $customer_details = CustomerModel::find($this->customer_id);
        if(!empty($customer_details)){
            $customer_name = $customer_details->name;
        }    
        return [
            'id' => $this->id,
            'slack' => $this->slack,
            'order_number' => (string) $this->order_number,
            'reference_number' => (int) $this->reference_number,
            'value_date' => (string) $this->value_date,
            'device_id' => $this->device_id,
            'restaurant_mode' => $this->restaurant_mode,
            'customer_name' => $customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'counter_name' => $this->counter_name,
            'currency_name' => $this->currency_name,
            'currency_code' => $this->currency_code,
            'discount_type' => $this->discount_type,
            'order_bonat_discount' => $this->bonat_discount,
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
            // 'tax_components' => new TaxcodeResource($this->storeData->tax_code->tax_components),
            'total_tax_amount' => $this->total_tax_amount,
            'total_order_amount' => $this->total_order_amount,
            'total_order_amount_rounded' => $this->total_order_amount_rounded,
            'payment_method_id' => $this->payment_method_id,
            'payment_method' => $this->payment_method,
            'cash_amount' => $this->cash_amount,
            'credit_amount' => $this->credit_amount,
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
            'created_at_iso' => Carbon::parse($this->created_at)->toISOString(),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'has_combo' => $this->has_combo,
            'nearpay_json' => $this->nearpay_json,

        ];
    }
}