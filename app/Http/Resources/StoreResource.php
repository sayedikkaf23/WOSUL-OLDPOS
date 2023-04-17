<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

use App\Http\Resources\MasterStatusResource;

class StoreResource extends Resource
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
            'store_code' => $this->store_code,
            'name' => $this->name,
            'vat_number' => $this->vat_number,
            'address' => $this->address,
            'pincode' => $this->pincode,
            'primary_contact' => $this->primary_contact,
            'secondary_contact' => $this->secondary_contact,
            'primary_email' => $this->primary_email,
            'secondary_email' => $this->secondary_email,
            'country' => new CountryResource($this->country),
            'tax_code' => new TaxcodeResource($this->tax_code),
            'discount_code' => new DiscountcodeResource($this->discount_code),
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_STORE'], true)) ? route('store', ['slack' => $this->slack]) : '',
            'invoice_type' => $this->invoice_print_type,
            'currency_code' => $this->currency_code,
            'currency_name' => $this->currency_name,
            'restaurant_mode' => $this->restaurant_mode,
            'restaurant_waiter_role' => new RoleResource($this->waiter_role_data),
            'restaurant_billing_type' => new MasterBillingTypeResource($this->restaurant_billing_type),
            'enable_customer_popup' => $this->enable_customer_popup,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'bank_name' => $this->bank_name,
            'account_holder_name' => $this->account_holder_name,
            'iban_number' => $this->iban_number,
            'pos_invoice_policy_information' => $this->pos_invoice_policy_information,
            'invoice_policy_information' => $this->invoice_policy_information,
            'quotation_policy_information' => $this->quotation_policy_information,
            'purchase_policy_information' => $this->purchase_policy_information,
            'store_invoice_color' => $this->store_invoice_color,
            'store_logo' => $this->store_logo,
            'store_logo_path' => $this->store_logo_path,
            'zid_store_id' => $this->zid_store_id,
            'zid_store_api_token' => $this->zid_store_api_token,
            "building_number" => $this->building_number,
            "street_name" => $this->street_name,
            "district" => $this->district,
            "city" => $this->city,
            "other_seller_id" => $this->other_seller_id,
            "store_opening_time" => $this->store_opening_time,
            "store_closing_time" => $this->store_closing_time,
            "is_store_closing_next_day" => $this->is_store_closing_next_day,
            "tax_registration_name" => $this->tax_registration_name,
            'last_reference_number' => $this->last_reference_number,
            'idle_time' => $this->idle_time,
            'idle_time_status' => $this->idle_time_status,
            'idle_time_in_seconds' => $this->idle_time_in_seconds,
            'idle_time_in_minutes' => $this->idle_time_in_minutes,
            'platform_type' => $this->platform_type,
            'platform_mode' => $this->platform_mode,
            'price_id' => $this->price_id,
            'is_price_enabled' => $this->is_price_enabled,
        ];
    }
}
