<?php

namespace App\Http\Resources;

use DB;
use App\Models\Order as OrderModel;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\ReturnOrders;
use App\Models\ReturnOrders as ReturnOrdersModel;
class BusinessRegisterResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $net_cash_payment = '';
        $payment_array = [];
        $total_net_cash_amount = 0.00;
        $total_net_credit_amount = 0.00;
        $total_payment_amount = 0.00;
        $total_return_amount = 0.00;

        $id = $this->makeVisible(['id'])->id;

        /* 
            Fetching Credit Transactions
        */
        $payment_methods = PaymentMethodModel::select('id', 'label','payment_constant')
                ->active()
                ->get();
        $cash_payment_method_id = 4;
        $cash_payment_in_credit_section = 0;
        foreach ($payment_methods as $payment_method) {

            if($payment_method->payment_constant != 'CASH'){
                    
                $payment_order_amount = OrderModel::query()
                    ->whereIn('orders.status',[1,6]) // closed orders only
                    ->where('register_id', $id)
                    ->where('orders.payment_method_id', $payment_method->id)
                    ->select(
                        DB::Raw('IFNULL (SUM(orders.credit_amount), 0) as amount'),
                        DB::Raw('IFNULL (SUM(orders.cash_amount), 0) as cash_amount'),
                    )
                    ->first()->toArray();
                $cash_payment_in_credit_section += $payment_order_amount['cash_amount'];
                $payment_return_amount = ReturnOrdersModel::query()
                    // ->where('orders.status', 1) // closed orders only
                    ->where('register_id', $id)
                    ->where('order_return.payment_method_id', $payment_method->id)
                    ->select(
                        DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount')
                    )
                    ->first()->toArray();
                    $payment_array[] = [
                        // 'type' => 'CREDIT',
                        'title' => $payment_method->label,
                        'amount' => (string) ($payment_order_amount['amount'] - $payment_return_amount['amount']),
                    ];
                    $total_net_credit_amount += $payment_order_amount['amount'] - $payment_return_amount['amount'];

            }elseif($payment_method->payment_constant == 'CASH'){
                $cash_payment_method_id = $payment_method->id;
            }
        }
        $total_net_credit_amount = $total_net_credit_amount;

        /* 
            Fetching Cash Transactions
        */
        $cash_order_amount = OrderModel::query()
        ->whereIn('orders.status', [1,6]) // closed and fully return orders only
        ->where('orders.payment_method_id', $cash_payment_method_id)
        ->where('register_id', $id)
        ->select(
            DB::Raw('IFNULL (SUM(orders.cash_amount), 0) as amount'),
            DB::Raw('IFNULL (SUM(orders.change_amount), 0) as change_amount')
        )
        ->first()->toArray();

        $payment_order_return = ReturnOrdersModel::query()
            ->where('order_return.status', 1) // closed  orders only
            ->where('order_return.payment_method_id', $cash_payment_method_id)
            ->where('register_id', $id)
            ->select(
                DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount'),
            )
            ->first()->toArray();

        $cash_amount_without_change = round(($cash_order_amount['amount'] + $cash_payment_in_credit_section) - 
                $cash_order_amount['change_amount'] - $payment_order_return['amount'], 2);
        $total_net_cash_amount = (string) $cash_amount_without_change;

        $payment_array[] = [
            // 'type' => 'CASH',
            'title' => 'Cash',
            'amount' => $total_net_cash_amount
        ];

        /* 
            Fetching Total Returned Amount
        */
        $closed_register_return = ReturnOrders::select(DB::raw('SUM(total_order_amount) as order_return_amount'))
        ->where('register_id',$id)
        ->where('returning_register_id', $id)
        ->where('status',1)
        ->whereBetween('order_return.value_date', [$request->from_date, $request->to_date])
        ->first();

        if(isset($closed_register_return)){
            $closed_register_return = $closed_register_return->order_return_amount;
        }
        
        $open_register_return = ReturnOrders::select(DB::raw('SUM(total_order_amount) as order_return_amount'))
        ->where('register_id',$id - 1)
        ->where('returning_register_id', $id - 1)
        ->where('status',1)
        ->whereBetween('order_return.value_date', [$request->from_date, $request->to_date])
        ->first();

        if(isset($open_register_return)){
            $open_register_return =  $open_register_return->order_return_amount;
        }

        $total_return_amount = (double) $closed_register_return + (double) $open_register_return;
        

        $total_payment_amount = $total_net_credit_amount + $total_net_cash_amount;
        $total_return_amount = !empty($total_return_amount) ? $total_return_amount : 0;

        $payment_array[] = ['title' => 'Total Payments', 'amount' => format_decimal($total_payment_amount)];
        $payment_array[] = ['title' => 'Total Returns (Closed Registers)', 'amount' => format_decimal($closed_register_return)];
        $payment_array[] = ['title' => 'Total Returns (Open Register)', 'amount' => format_decimal($open_register_return)];
        $payment_array[] = ['title' => 'Net Payments', 'amount' => format_decimal($total_payment_amount)];

        $net_cash_payment_amount = $total_net_cash_amount;
        $net_credit_payment_amount = $total_net_credit_amount;
        
        $expected_cash_amount = $this->opening_amount + $net_cash_payment_amount;
        // $variance_cash = $total_net_cash_amount - $expected_cash_amount;
        $variance_cash = $this->cheques - $expected_cash_amount;

        $expected_credit_amount = $net_credit_payment_amount;
        // $variance_credit = $total_net_credit_amount - $expected_credit_amount;
        $variance_credit = $this->credit_card_slips - $expected_credit_amount;

        // $expected_amount = $this->opening_amount + $net_cash_payment_amount + $net_credit_payment_amount;
        // $variance = $this->closing_amount - $expected_amount;

        return [
            'slack' => $this->slack,
            'opening_date' => $this->opening_date,
            'closing_date' => $this->closing_date,
            'opening_date_label' => $this->parseDate($this->opening_date),
            'closing_date_label' => $this->parseDate($this->closing_date),
            'user' => new UserResource($this->user),
            'opening_amount' => $this->opening_amount,
            'net_cash_payment' => format_decimal($net_cash_payment_amount),
            'net_credit_payment' => format_decimal($net_credit_payment_amount),
            'expected_cash_amount' => format_decimal($expected_cash_amount),
            'cash' => format_decimal($this->cheques), // format_decimal($total_net_cash_amount),
            'variance_cash' =>  format_decimal($variance_cash),
            'expected_credit_amount' => format_decimal($expected_credit_amount),
            'credit' => format_decimal($this->credit_card_slips),//format_decimal($total_net_credit_amount),
            'variance_credit' => format_decimal($variance_credit),
            'manual_drawer_opens' => $this->manual_drawer_opens,
            'payments' => $payment_array,
            'detail_link' => (check_access(['A_DETAIL_BUSINESS_REGISTER'], true))?route('business_register', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'store' => $this->store,
            'billing_counter' => $this->billing_counter,
        ];
    }
}
