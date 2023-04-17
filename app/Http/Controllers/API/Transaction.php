<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\TransactionResource;

use App\Models\Account as AccountModel;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Store as StoreModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Customer as CustomerModel;
use App\Models\Invoice as InvoiceModel;
use App\Models\HRM\AccTransaction as AccTransactionModel;
use App\Models\HRM\AccCoa as AccCoaModel;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use App\Models\InvoiceCharge;


use App\Http\Resources\Collections\TransactionCollection;
use App\Models\Expresspay;
use App\Http\Traits\CommonApiTrait;
use App\Http\Traits\QoyodApiTrait;

class Transaction extends Controller
{
    use CommonApiTrait, QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }
            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $data['action_key'] = 'A_VIEW_TRANSACTION_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = TransactionModel::select('transactions.*', 'user_created.fullname')
                ->take($limit)
                ->skip($offset)
                ->masterTransactionTypeJoin()
                ->accountJoin()
                ->createdUser()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })->whereBetween('transactions.created_at', [$from_date, $to_date]);

            if ($request->status != "") {
                $query->where('transactions.status', '=', $request->status);
            }
            $query = $query->get();

            $transactions = TransactionResource::collection($query);
            $total_q = TransactionModel::select("id")->whereBetween('transactions.created_at', [$from_date, $to_date]);

            if ($request->status != "") {
                $total_q->where('transactions.status', '=', $request->status);
            }

            $total_count = $total_q->get()->count();

            $item_array = [];
            foreach ($transactions as $key => $transaction) {

                $transaction = $transaction->toArray($request);

                $item_array[$key][] = $transaction['transaction_code'];
                $item_array[$key][] = $transaction['transaction_date'];
                $item_array[$key][] = (isset($transaction['transaction_type_data']['label'])) ? $transaction['transaction_type_data']['label'] : '-';
                $item_array[$key][] = (isset($transaction['account']['label'])) ? $transaction['account']['label'] : '-';
                $item_array[$key][] = $transaction['payment_method'];
                $item_array[$key][] = str_replace('_', ' ', Str::title($transaction['bill_to']));
                $item_array[$key][] = ($transaction['bill_to_name']) ? $transaction['bill_to_name'] : '-';
                $item_array[$key][] = $transaction['amount'];
                $item_array[$key][] = $transaction['created_at_label'];
                $item_array[$key][] = $transaction['updated_at_label'];
                $item_array[$key][] = (isset($transaction['created_by']['fullname'])) ? $transaction['created_by']['fullname'] : '-';
                $item_array[$key][] = view('transaction.layouts.transaction_actions', ['transaction' => $transaction])->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_TRANSACTION'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            if ($request->bill_to == 'SUPPLIER') {
                $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'email', 'phone', 'address', 'pincode')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Invalid supplier selected"), 400);
                }

                $bill_to_id = $supplier_data->id;
                $bill_to_name = $supplier_data->name;
                $bill_to_contact = implode(', ', [$supplier_data->phone, $supplier_data->email]);
                $bill_to_address = $supplier_data->address . ',' . $supplier_data->pincode;
            } else if ($request->bill_to == 'CUSTOMER') {
                $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($customer_data)) {
                    throw new Exception(trans("Invalid customer selected"), 400);
                }

                $bill_to_id = $customer_data->id;
                $bill_to_name = $customer_data->name;
                $bill_to_contact = implode(', ', [$customer_data->phone, $customer_data->email]);
                $bill_to_address = $customer_data->address;
            } else if ($request->bill_to == 'INVOICE') {
                $invoice_data = InvoiceModel::with('invoiceCharges')->select('id', 'slack', 'bill_to_id', 'bill_to_name', 'bill_to_email', 'bill_to_contact', 'bill_to_address', 'total_order_amount', 'subtotal_including_tax')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->first();
                if (empty($invoice_data)) {
                    throw new Exception(trans("Invalid invoice selected"), 400);
                }

                $invoice_slack = $invoice_data->slack;


                $bill_to_id = $invoice_data->id;
                $bill_to_name = $invoice_data->bill_to_name;
                $bill_to_contact = implode(', ', [$invoice_data->bill_to_contact, $invoice_data->bill_to_email]);
                $bill_to_address = $invoice_data->bill_to_address;
                // check total paid or partial paid
                $already_paid_amount = 0;

                $already_paid_amount = TransactionModel::select('id')->where([
                    ['bill_to', '=', 'INVOICE'],
                    ['bill_to_id', $bill_to_id]
                ])->sum('amount');

                $total_amount_including_tax =  $invoice_data->subtotal_including_tax;
                $invoice_api = new Invoice();
                $total_invoice_charges = $invoice_api->get_total_invoice_charge($invoice_data->id);
                $total_amount = $total_amount_including_tax + $total_invoice_charges;

                $paid_amount = $request->amount + $already_paid_amount;


                $payment_method_data = PaymentMethodModel::select('id', 'label')
                    ->where('slack', '=', trim($request->payment_method))
                    ->first();
                if (empty($payment_method_data)) {
                    throw new Exception("Invalid payment method selected", 400);
                }

                $payment_method = $payment_method_data->label;

                if ($total_amount == $paid_amount) {
                    $status = 3;
                } else {
                    $status = 7;
                }

                $forward_link = asset('storage/' . config('constants.config.merchant_id') . '/invoice/temp_invoice_' . $invoice_slack . '.pdf');

                // $this->record_invoice_hrm_transaction($invoice_slack, $forward_link, $payment_method, $request->amount, $bill_to_name, $status);

                $invoice_details = [
                    "status" => $status
                ];
                InvoiceModel::where('slack', $request->bill_to_slack)
                    ->update($invoice_details);
            }

            $account_data = AccountModel::select('id','label')
                ->where('slack', '=', trim($request->account))
                ->first();
            if (empty($account_data)) {
                throw new Exception(trans("Invalid account selected"), 400);
            }

            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', trim($request->transaction_type))
                ->first();
            if (empty($transaction_type_data)) {
                throw new Exception("Invalid transaction type selected", 400);
            }

            $payment_method_data = PaymentMethodModel::select('id', 'label')
                ->where('slack', '=', trim($request->payment_method))
                ->first();
            if (empty($payment_method_data)) {
                throw new Exception("Invalid payment method selected", 400);
            }

            $store_data = StoreModel::select('currency_name', 'currency_code')
                ->where([
                    ['stores.id', '=', $request->logged_user_store_id]
                ])
                ->active()
                ->first();
            if (empty($store_data)) {
                throw new Exception(trans("Invalid store selected"));
            }

            DB::beginTransaction();

            $transaction = [
                "slack" => $this->generate_slack("transactions"),
                "store_id" => $request->logged_user_store_id,
                "transaction_code" => Str::random(6),
                "account_id" => $account_data->id,
                "transaction_type" => $transaction_type_data->id,
                "payment_method_id" => $payment_method_data->id,
                "payment_method" => $payment_method_data->label,
                "bill_to" => $request->bill_to,
                "bill_to_id" => $bill_to_id,
                "bill_to_name" => $bill_to_name,
                "bill_to_contact" => $bill_to_contact,
                "bill_to_address" => $bill_to_address,
                "currency_code" => $store_data->currency_code,
                "amount" => $request->amount,
                "notes" => $request->notes,
                "transaction_date" => $request->transaction_date,
                "created_by" => $request->logged_user_id
            ];

            $transaction_id = TransactionModel::create($transaction)->id;

            $code_start_config = Config::get('constants.unique_code_start.transaction');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;

            $transaction_code = [
                "transaction_code" => ($code_start + $transaction_id)
            ];
            TransactionModel::where('id', $transaction_id)
                ->update($transaction_code);

            //Qoyod
            if(Session('qoyod_status')){
                $sales_detail = InvoiceModel::with('invoiceCharges')->select('slack', 'invoice_date', 'total_order_amount')->where('slack', '=', trim($request->bill_to_slack))->first();

                //create the bill payment
                $qoyod_cash_account = $this->qoyod_get_account(0,'q[en_name_eq]='.$account_data->label); //Cash Account

                if(!$qoyod_cash_account['status']){
                    $qoyod_cash_account = $this->qoyod_get_account(0,'q[code_eq]=110101'); //Cash Account
                }

                $qoyod_invoice = $this->qoyod_get_invoice(0,'q[reference_eq]='.$sales_detail->slack); //invoice data
                $payment_data =array(
                    'reference'=>$this->getRandomString(10),
                    'invoice_id'=>$qoyod_invoice['data']->invoices[0]->id,
                    'account_id'=>$qoyod_cash_account['data']->accounts[0]->id,
                    'date'=>$request->transaction_date,
                    'amount'=>$request->amount,
                );

                $this->qoyod_create_invoice_payment($payment_data);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transaction added successfully"),
                    "data"    => $transaction['slack']
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    
    public function store_expresspay(Request $request)
    {

        try {

            if (!check_access(['A_ADD_TRANSACTION'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);
            $invoice_data = InvoiceModel::with('invoiceCharges')->select('id', 'slack', 'invoice_number','bill_to_id', 'bill_to_name', 'bill_to_email', 'bill_to_contact', 'bill_to_address', 'total_order_amount', 'subtotal_including_tax')
                ->where('slack', '=', trim($request->bill_to_slack))
                ->first();
            if ($request->bill_to == 'SUPPLIER') {
                $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'email', 'phone', 'address', 'pincode')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Invalid supplier selected"), 400);
                }

                $bill_to_id = $supplier_data->id;
                $bill_to_name = $supplier_data->name;
                $bill_to_contact = implode(', ', [$supplier_data->phone, $supplier_data->email]);
                $bill_to_address = $supplier_data->address . ',' . $supplier_data->pincode;
            } else if ($request->bill_to == 'CUSTOMER') {
                $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($customer_data)) {
                    throw new Exception(trans("Invalid customer selected"), 400);
                }

                $bill_to_id = $customer_data->id;
                $bill_to_name = $customer_data->name;
                $bill_to_contact = implode(', ', [$customer_data->phone, $customer_data->email]);
                $bill_to_address = $customer_data->address;
            } else if ($request->bill_to == 'INVOICE') {

                $invoice_slack = $invoice_data->slack;

                $bill_to_id = $invoice_data->id;
                $bill_to_name = $invoice_data->bill_to_name;
                $bill_to_contact = implode(', ', [$invoice_data->bill_to_contact, $invoice_data->bill_to_email]);
                $bill_to_address = $invoice_data->bill_to_address;
                // check total paid or partial paid
                $already_paid_amount = 0;

                $already_paid_amount = TransactionModel::select('id')->where([
                    ['bill_to', '=', 'INVOICE'],
                    ['bill_to_id', $bill_to_id]
                ])->sum('amount');

                $total_amount_including_tax =  $invoice_data->subtotal_including_tax;
                $invoice_api = new Invoice();
                $total_invoice_charges = $invoice_api->get_total_invoice_charge($invoice_data->id);
                $total_amount = $total_amount_including_tax + $total_invoice_charges;

                $paid_amount = $request->amount + $already_paid_amount;


                $payment_method_data = PaymentMethodModel::select('id', 'label')
                    ->where('slack', '=', trim($request->payment_method))
                    ->first();
                if (empty($payment_method_data)) {
                    throw new Exception("Invalid payment method selected", 400);
                }

                $payment_method = $payment_method_data->label;

                // if ($total_amount == $paid_amount) {
                //     $status = 3;
                // } else {
                //     $status = 7;
                // }

                $forward_link = asset('storage/' . config('constants.config.merchant_id') . '/invoice/temp_invoice_' . $invoice_slack . '.pdf');

                // $this->record_invoice_hrm_transaction($invoice_slack, $forward_link, $payment_method, $request->amount, $bill_to_name, $status);

                // $invoice_details = [
                //     "status" => $status
                // ];
                // InvoiceModel::where('slack', $request->bill_to_slack)
                //     ->update($invoice_details);
            }

            $account_data = AccountModel::select('id')
                ->where('slack', '=', trim($request->account))
                ->first();
            if (empty($account_data)) {
                throw new Exception(trans("Invalid account selected"), 400);
            }

            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', trim($request->transaction_type))
                ->first();
            if (empty($transaction_type_data)) {
                throw new Exception("Invalid transaction type selected", 400);
            }

            $payment_method_data = PaymentMethodModel::select('id', 'label')
                ->where('slack', '=', trim($request->payment_method))
                ->first();
            if (empty($payment_method_data)) {
                throw new Exception("Invalid payment method selected", 400);
            }

            $store_data = StoreModel::select('currency_name', 'currency_code')
                ->where([
                    ['stores.id', '=', $request->logged_user_store_id]
                ])
                ->active()
                ->first();
            if (empty($store_data)) {
                throw new Exception(trans("Invalid store selected"));
            }

            DB::beginTransaction();

            $transaction = [
                "slack" => $this->generate_slack("transactions"),
                "store_id" => $request->logged_user_store_id,
                "transaction_code" => Str::random(6),
                "account_id" => $account_data->id,
                "transaction_type" => $transaction_type_data->id,
                "payment_method_id" => $payment_method_data->id,
                "payment_method" => $payment_method_data->label,
                "bill_to" => $request->bill_to,
                "bill_to_id" => $bill_to_id,
                "bill_to_name" => $bill_to_name,
                "bill_to_contact" => $bill_to_contact,
                "bill_to_address" => $bill_to_address,
                "currency_code" => $store_data->currency_code,
                "amount" => $request->amount,
                "notes" => $request->notes,
                "transaction_date" => $request->transaction_date,
                "created_by" => $request->logged_user_id
            ];

            // remove previously created payment link before new payment link generated
            Expresspay::where('bill_to',$request->bill_to)->where('bill_to_id',$bill_to_id)->where('status',0)->delete();
            
            $data_json['transaction'] = $transaction;
            $data_json['invoice'] =   $invoice_data->first()->toArray();
            $data_json['customer'] =   [
                "bill_to_name" => $bill_to_name,
                "bill_to_contact" => $bill_to_contact,
                "bill_to_address" => $bill_to_address
            ];

            $expresspay = [
                'slack' => $this->generate_slack('expresspay'),
                'bill_to' => $request->bill_to,
                'bill_to_id' => $bill_to_id,
                'amount' => $request->amount,
                'data_json' => json_encode($data_json),
                'contact_number'=>$request->contact,
                'invoice_link' => $forward_link,
            ];
            $expresspay =  Expresspay::create($expresspay);

            $payment_link = env('APP_URL').'/expresspay/checkout/'.session('merchant_id').'/'.$expresspay->slack;
            Expresspay::where('id',$expresspay->id)->update(['payment_link'=>$payment_link,'contact_number'=>$request->contact]);

            //SMS Code
            if($request->contact!=''){
                $message = $this->prepare_express_pay_message($invoice_data->invoice_number,number_format($request->amount,2),$forward_link,$payment_link);
                $this->sendSMS($request->contact,$message);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Payment Link generated successfully"),
                    "data"    => $expresspay
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }


    public function send_transaction_sms(Request $request){
        try {
            $express_pay = Expresspay::select('expresspay.*','invoices.invoice_number')
                ->join('invoices', 'invoices.id', '=', 'expresspay.bill_to_id','left')
                ->where('expresspay.id',$request->transaction_id)
                ->first();

            //SMS Code
            if($express_pay->contact_number!=''){
                $message = $this->prepare_express_pay_message($express_pay->invoice_number,number_format($express_pay->amount,2),$express_pay->invoice_link,$express_pay->payment_link);
                $this->sendSMS($express_pay->contact_number,$message);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transaction link has been sent!"),
                    "data"    => $express_pay
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_TRANSACTION'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $item = TransactionModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new TransactionResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transaction loaded successfully"),
                    "data"    => $item_data
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_TRANSACTION_LISTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new TransactionCollection(TransactionModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transactions loaded successfully"),
                    "data"    => $list
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slack)
    {
        try {

            if (!check_access(['A_DELETE_TRANSACTION'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $transaction_detail = TransactionModel::select('id')->where('slack', $slack)->first();
            if (empty($transaction_detail)) {
                throw new Exception(trans("Invalid transaction provided"), 400);
            }

            $transaction_id = $transaction_detail->id;

            DB::beginTransaction();

            TransactionModel::where([
                ['id', '=', $transaction_id],
            ])->delete();

            DB::commit();

            $forward_link = route('transactions');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transaction deleted successfully"),
                    "data" => $slack,
                    "link" => $forward_link
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function filter_transactions(Request $request)
    {
        try {

            $keyword = $request->keyword;

            $transaction_list = TransactionModel::select("*")
                ->where('transaction_code', 'like', $keyword . '%')
                ->orWhere('payment_method', 'like', $keyword . '%')
                ->orWhere('bill_to_contact', 'like', $keyword . '%')
                ->orWhere('bill_to_name', 'like', $keyword . '%')
                ->limit(25)
                ->get();

            $transactions = TransactionResource::collection($transaction_list);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Transactions filtered successfully"),
                    "data" => $transactions
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * method : generate_pdf
     * param  : start_date,end_date

     **/
    public function generate_pdf(Request $request)
    {

        try {
            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }

            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $query = TransactionModel::select('transactions.*', 'user_created.fullname')
                ->createdUser()
                ->orderBy('transactions.id', 'desc')
                ->storeJoin()
                ->whereBetween('transactions.created_at', [$from_date, $to_date]);
            if ($request->order_status != "") {
                $query->where('transactions.status', '=', $request->order_status);
            }

            $query = $query->get();

            $transactions = TransactionResource::collection($query);

            if (isset($transactions)) {

                $view_file = 'transaction.pdf.generate';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [100, 150];
                $print_logo_path = session('store_logo');

                $print_data = view($view_file, ['transactions' => json_encode($transactions), 'print_logo_path' => $print_logo_path, 'from_date' => $from_date, 'to_date' => $to_date])->render();

                $mpdf_config = [
                    'mode'          => 'utf-8',
                    'format'        => $format,
                    'orientation'   => 'P',
                    'margin_left'   => 7,
                    'margin_right'  => 7,
                    'margin_top'    => 7,
                    'margin_bottom' => 7,
                    'tempDir' => storage_path() . "/pdf_temp"
                ];

                $stylesheet = File::get(public_path($css_file));
                $mpdf = new Mpdf($mpdf_config);
                $mpdf->curlAllowUnsafeSslRequests = true;
                $mpdf->SetDisplayMode('real');
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($print_data);

                $pdf_filename = "transaction_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.order.view_path');
                $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Transaction PDF created successfully",
                        "data" => $transactions,
                        'orders' => $transactions,
                        "link" => '/storage/' . session('merchant_id') . '/order/' . $pdf_filename,
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No transaction found.'),
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'bill_to_slack' => $this->get_validation_rules("slack", true),
            'bill_to' => $this->get_validation_rules("string", true),
            'transaction_date' => 'date|required',
            'account' => $this->get_validation_rules("slack", true),
            'transaction_type' => $this->get_validation_rules("string", true),
            'amount' => $this->get_validation_rules("numeric", true),
            'payment_method' => $this->get_validation_rules("slack", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function record_invoice_hrm_transaction($invoice_slack, $transaction_receipt_link, $payment_method, $amount_paid, $bill_to_name, $paid_status)
    {
        $invoice_detail = InvoiceModel::select('*')->where('slack', $invoice_slack)->first();
        $acc_transaction = AccTransactionModel::orderBy('id', 'DESC')->first();

        $transaction_id = isset($acc_transaction->ID) ? $acc_transaction->ID : 0;
        $Vtype = "JV";
        $voucher_no = "Journal-" . bcadd($transaction_id, 1);


        $id = isset($invoice_detail->id) ? $invoice_detail->id : NULL;


        //Accounts Receivable check
        $account_receivable_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'Accounts Receivable')
            ->first();
        if (empty($account_receivable_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }


        if ($paid_status == 3) {


            if (isset($invoice_detail->total_order_amount)) {

                $account_receivable_debit =  0;
                $account_receivable_credit = isset($invoice_detail->total_order_amount) ? $invoice_detail->total_order_amount : 0;

                $sales_transaction = [
                    'VNo'            =>  $voucher_no,
                    'Vtype'          =>  $Vtype,
                    'VDate'          =>  date('Y-m-d'),
                    'COAID'          =>  isset($account_receivable_headcode->HeadCode) ? $account_receivable_headcode->HeadCode : '',
                    'Narration'      =>  $bill_to_name . ' - Invoice Transaction Entry',
                    'Debit'          =>  empty($account_receivable_debit) ? 0 : $account_receivable_debit,
                    'Credit'         =>  empty($account_receivable_credit) ? 0 : $account_receivable_credit,
                    'transaction_id' => isset($id) ? $id : NULL,
                    'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                    'order_slack' => $invoice_slack,
                    'IsPosted'       => 1,
                    'CreateBy'       => request()->logged_user_id,
                    'CreateDate'     => date('Y-m-d'),
                    'IsAppove'       => 0
                ];


                $transaction_id = AccTransactionModel::create($sales_transaction)->id;
            }
        } else {



            $account_receivable_debit =  0;
            $account_receivable_credit = isset($amount_paid) ? $amount_paid : 0;

            $sales_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($account_receivable_headcode->HeadCode) ? $account_receivable_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Transaction Entry',
                'Debit'          =>  empty($account_receivable_debit) ? 0 : $account_receivable_debit,
                'Credit'         =>  empty($account_receivable_credit) ? 0 : $account_receivable_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_transaction)->id;
        }


        // Bank check
        $cash_In_hand_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Cash In Hand%')
            ->first();
        if (empty($cash_In_hand_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        $cash_at_bank_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Default%')
            ->where('PHeadName', 'like', '%Cash At Bank%')
            ->first();
        if (empty($cash_at_bank_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if ($payment_method != 'Cash') {
            $credit_amount_debit =  isset($amount_paid) ? $amount_paid : 0;
            $credit_amount_credit =  0;

            $cash_at_bank_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($cash_at_bank_headcode->HeadCode) ? $cash_at_bank_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Transaction Entry',
                'Debit'          =>  empty($credit_amount_debit) ? 0 : $credit_amount_debit,
                'Credit'         =>  empty($credit_amount_credit) ? 0 : $credit_amount_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($cash_at_bank_transaction)->id;
        } else {
            //Cash check
            $cash_in_hand_debit =  isset($amount_paid) ? $amount_paid : 0;
            $cash_in_hand_credit = 0;

            $cash_in_hand_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($cash_In_hand_headcode->HeadCode) ? $cash_In_hand_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Transaction Entry',
                'Debit'          =>  empty($cash_in_hand_debit) ? 0 : $cash_in_hand_debit,
                'Credit'         =>  empty($cash_in_hand_credit) ? 0 : $cash_in_hand_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($cash_in_hand_transaction)->id;
        }
    }
}
