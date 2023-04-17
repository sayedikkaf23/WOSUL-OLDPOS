<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InvoiceReturnResource extends Resource
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

        return [
            'slack' => $this->slack,
            'store_id' => $this->store_id,
            'return_invoice_number' =>$this->return_invoice_number,
            'invoice_slack' => $this->invoice_slack,
            'invoice_number' => $this->invoice_number,
            'invoice_reference' => $this->invoice_reference,
            'invoice_date' => $this->parseDateOnly($this->invoice_date),
            'invoice_due_date' => $this->parseDateOnly($this->invoice_due_date),
            'parent_po_id' => $this->parent_po_id,
            'invoice_date_raw' => $this->invoice_date,
            'invoice_due_date_raw' => $this->invoice_due_date,
            'bill_to' => $this->bill_to,
            'bill_to_id' => $this->bill_to_id,
            'bill_to_code' => $this->bill_to_code,
            'bill_to_name' => ($this->bill_to_name == "") ? '<span class="badge badge-primary ">DEFAULT</span>' : $this->bill_to_name,
            'bill_to_email' => $this->bill_to_email,
            'bill_to_contact' => $this->bill_to_contact,
            'bill_to_address' => $this->bill_to_address,
            'supplier' => ($this->bill_to == "SUPPLIER")? new SupplierResource($this->supplier):'',
            'customer' => ($this->bill_to == "CUSTOMER")?new CustomerResource($this->customer):'',
            'currency_name' => $this->currency_name,
            'currency_code' => $this->currency_code,
            'subtotal_excluding_tax' => $this->subtotal_excluding_tax,
            'total_discount_amount' => $this->total_discount_amount,
            'total_after_discount' => $this->total_after_discount,
            'total_tax_amount' => $this->total_tax_amount,
            'shipping_charge' => $this->shipping_charge,
            'packing_charge' => $this->packing_charge,
            'total_order_amount' => $this->total_order_amount,
            'tax_option_id' => $this->tax_option_id,
            'tax_option_data' => new MasterTaxOptionResource($this->tax_option_data),
            'terms' => $this->terms,
            'products' => InvoiceProductResource::collection($this->products),
            'store' => new StoreResource($this->storeData),
            'status' => new MasterStatusResource($this->status_data),
            'transactions' => TransactionResource::collection($this->transactions),
            'detail_link' => (check_access(['A_DETAIL_INVOICE'], true))?route('invoice_detail', ['slack' => $this->slack]):'',
            'print_invoice_return_link' => (check_access(['A_DETAIL_INVOICE'], true))?route('invoice_return_receipt', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'invoice_color_code' => $this->invoice_color_code,
            'reason' => $this->reason,
            
        ];
    }
}
