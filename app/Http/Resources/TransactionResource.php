<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TransactionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $blocking_recurring_data_in_transaction = (isset($request->blocking_recurring_data_in_transaction))?$request->blocking_recurring_data_in_transaction:false;

        $payment_mode = '';
        $credit_amount = 0;
        $cash_amount = 0;

        if($this->bill_to == 'POS_ORDER'){

            $order = new OrderResource($this->order);

            if(isset($order)){

                if($order['credit_amount'] > 0 && $order['cash_amount'] > 0 ){
                    
                        $payment_mode = 'Cash/Credit';
                        $credit_amount = $order['credit_amount'];
                        $cash_amount = $order['cash_amount'];
                }else if($order['credit_amount'] > 0){

                    $payment_mode = 'Credit';
                    $credit_amount = $order['credit_amount'];
                    $cash_amount = 0;
                
                }else if($order['cash_amount'] > 0){

                    $payment_mode = 'Cash';
                    $credit_amount = 0;
                    $cash_amount = $order['cash_amount'];
                
                }

            }

        }else{

            if($this->payment_method == 'Cash'){
                $payment_mode = 'Cash';
                if($this->bill_to == 'INVOICE'){
                    $cash_amount = $this->amount;
                }
            }else{
                $payment_mode = 'Credit';
                if($this->bill_to == 'INVOICE'){
                    $credit_amount = $this->amount;
                }
            }
        }

        return [
            'slack' => $this->slack,
            'transaction_code' => $this->transaction_code,
            'payment_method' => $this->payment_method,
            'currency_code' => $this->currency_code,
            'amount' => $this->amount,
            'pg_transaction_id' => $this->pg_transaction_id,
            'pg_transaction_status' => $this->pg_transaction_status,
            'payment_method_data' => new PaymentMethodResource($this->payment_method_data),
            'account' => new AccountResource($this->account),
            'transaction_type_data' => new MasterTransactionTypeResource($this->transaction_type_data),
            'bill_to' => $this->bill_to,
            'bill_to_id' => $this->bill_to_id,
            'bill_to_name' => $this->bill_to_name,
            'bill_to_contact' => $this->bill_to_contact,
            'bill_to_address' => $this->bill_to_address,
            'order' => ($this->bill_to == "POS_ORDER" && $blocking_recurring_data_in_transaction == false)?new OrderResource($this->order):'',
            'invoice' => ($this->bill_to == "INVOICE" && $blocking_recurring_data_in_transaction == false)?new InvoiceResource($this->invoice):'',
            'supplier' => ($this->bill_to == "SUPPLIER" && $blocking_recurring_data_in_transaction == false)? new SupplierResource($this->supplier):'',
            'customer' => ($this->bill_to == "CUSTOMER" && $blocking_recurring_data_in_transaction == false)? new CustomerResource($this->customer):'',
            'notes' => $this->notes,
            'transaction_date' => $this->parseDateOnly($this->transaction_date),
            'detail_link' => (check_access(['A_DETAIL_TRANSACTION'], true))?route('transaction', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'payment_mode' => $payment_mode,
            'credit_amount' => $credit_amount,
            'cash_amount' => $cash_amount
        ];
    }
}
