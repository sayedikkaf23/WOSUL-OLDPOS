<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Traits\ExpresspayTrait;
use App\Models\Expresspay;
use App\Models\Invoice;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use PhpParser\JsonDecoder;
use Illuminate\Support\Facades\Storage;
use mysqli;
use Illuminate\Support\Str;
use App\Http\Traits\QoyodApiTrait;
use App\Http\Traits\CommonApiTrait;

class ExpresspayController extends Controller
{
    use ExpresspayTrait,QoyodApiTrait,CommonApiTrait;

    public function checkout(Request $request)
    {   
        
        if(!isset($request->merchant_id) && !isset($request->payment_slack)){
            $message_params = [
                'message' => 'Invalid payment link!',
                'type' => 'error',
            ];
            return view('custom_message', compact('message_params'));
        }

        $data['title'] = 'Expresspay Checkout';
        $data['merchant_id'] = $request->merchant_id;
        $data['payment_slack'] = $request->payment_slack;

        $merchant = Merchant::find($data['merchant_id']);
        $data['company_url'] = $merchant->company_url;
        if(empty($merchant)){
            $message_params = [
                'message' => 'Invalid payment link!',
                'type' => 'error',
            ];
            return view('custom_message', compact('message_params'));
        }
        
        $merchant_db = strtolower($data['company_url']. "_wosul");
        $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);

        $payment_detail =  mysqli_query($connect, "SELECT * FROM expresspay WHERE slack = '".$data["payment_slack"]."' LIMIT 1");
        
        if (mysqli_num_rows($payment_detail) > 0) {
            $data['payment_detail'] = mysqli_fetch_assoc($payment_detail);
        }else{
            $message_params = [
                'message' => 'Invalid payment link!',
                'type' => 'error',
            ];
            return view('custom_message', compact('message_params'));
        }
        mysqli_close($connect);

        $data_json =  json_decode($data['payment_detail']['data_json'],true);
        $data['invoice'] = $data_json['invoice'];

        $count = mb_substr_count($data_json['customer']['bill_to_name'],'?');
        if ($count>0 || !preg_match('/[a-zA-Z]/', $data_json['customer']['bill_to_name'])) {
            if($data_json['invoice']['bill_to']=='COMPANY'){
                $data['customer']['bill_to_name'] = 'Company';
            }else{
                $data['customer']['bill_to_name'] = 'Customer';
            }
            $data['customer']['bill_to_address'] = $data_json['customer']['bill_to_address'];
            $data['customer']['bill_to_contact'] = $data_json['customer']['bill_to_contact'];
        }else{
            $data['customer'] = $data_json['customer'];
        }

        $data['transaction'] = $data_json['transaction'];

        return view('expresspay.checkout', compact('data'));

    }

    public function pay(Request $request){
        
        $payment_slack = $request->payment_slack;
        $merchant_db = strtolower($request->company_url . "_wosul");
        
        $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);

        $payment_detail =  mysqli_query($connect, "SELECT * FROM expresspay WHERE slack = '".$payment_slack."' LIMIT 1");
        
        if (mysqli_num_rows($payment_detail) > 0) {
            $data['payment_detail'] = mysqli_fetch_assoc($payment_detail);
        }
        
        $setting =  mysqli_query($connect, "SELECT * FROM setting_app LIMIT 1");
        
        if (mysqli_num_rows($setting) > 0) {
            $data['setting'] = mysqli_fetch_assoc($setting);
        }
        mysqli_close($connect);

        if($data['setting'] == '' || $data['setting'] == null){
            $message_params = [
                'message' => 'Please enter your expresspay merchant keys from app setting',
                'type' => 'error',
            ];
            return view('custom_message', compact('message_params'));
        }

        $data_json =  json_decode($data['payment_detail']['data_json'],true);
        $invoice = $data_json['invoice'];
        $transaction = $data_json['transaction'];
        $customer = $data_json['customer'];
        $count = mb_substr_count($customer['bill_to_name'],'?');
        if ($count>0 || !preg_match('/[a-zA-Z]/', $customer['bill_to_name'])) {
            if($invoice['bill_to']=='COMPANY'){
                $customer['bill_to_name'] = 'Company';
            }else{
                $customer['bill_to_name'] = 'Customer';
            }
        }
        $order_number = $merchant_db."-".$transaction['bill_to_id'];
        $order_amount = number_format($transaction['amount'],2);
        $currency_code = $invoice['currency_code'];
        $order_bill_to = $transaction['bill_to'];

        $hash = $order_number."".$order_amount."".$currency_code."".$order_bill_to."".$data['setting']['expresspay_password'];
        $hash = sha1( md5(strtoupper($hash)) );

        $form_data = [  
            "merchant_key" => $data['setting']['expresspay_merchant_key'],
            "operation" => "purchase",
            "methods" => [
                "card"
            ],
            "order" => [
                "number" => $order_number,
                "amount" =>  $order_amount,
                "currency" => $currency_code,
                "description" => $order_bill_to
            ],
            "cancel_url" => env('APP_URL')."/expresspay/cancel",
            "success_url" => env('APP_URL')."/expresspay/success",
            "customer" => [
                "name" => (isset($customer) && isset($customer['bill_to_name'])) ? $customer['bill_to_name'] : '',
                "email" => (isset($customer) && isset($customer['bill_to_email'])) ? $customer['bill_to_email'] : 'info@wosul.sa'
            ],
            "billing_address" => [
                "country" => "SA",
                "state" =>  "Riyadh",
                "city" => "Riyadh",
                "address" => "Anas Bin Malik Road",
                "zip" => "13524",
                "phone" => "966595916792"
            ],
            "recurring_init" =>  "true",
            "hash" => $hash
        ];
        
        $response = $this->expresspay_authenticate($form_data);
    
        if(isset($response) && isset($response['data']['redirect_url'])){

            $request_json = json_encode($form_data);
            $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);
            $payment_detail =  mysqli_query($connect, "UPDATE expresspay SET request_json = '".$request_json."' WHERE slack = '".$payment_slack."'  ");
            mysqli_close($connect);

            $redirect_url = $response['data']['redirect_url'];
            return redirect()->to($redirect_url);
        }else{
        return redirect()->back();
        }

    }

    public function cancel(Request $request){
        
        $data['title'] = "Express Payment Cancel";
        return view('expresspay.cancel',$data);
    }
    
    public function success(Request $request){
        
        $data['title'] = "Express Payment Success";
        return view('expresspay.success',$data);
    }

    public function notification(Request $request){

        $data = [];

        $values = $request->all();

        if(isset($values)){
            foreach($values as $key => $value){
                
                if($key == "order_number"){
                    $order_number = explode("-",$value);
                    $data['merchant_db'] = $order_number[0];
                    $data['bill_to_id'] = $order_number[1];
                }
                if($key == "order_amount"){
                    $data['order_amount'] = $value;
                }

            }
        }

        $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $data['merchant_db']);
        $payment_detail =  mysqli_query($connect, "SELECT * FROM expresspay WHERE bill_to_id = '".$data['bill_to_id']."' AND amount = '".$data['order_amount']."' AND status = 0 LIMIT 1");
        
        if (mysqli_num_rows($payment_detail) > 0) {
            
            $response = mysqli_fetch_assoc($payment_detail);
            $data_json = json_decode($response['data_json'],true);
            $invoice_data = $data_json['invoice'];
            $transaction_data = $data_json['transaction'];
            
            $transaction_code = '';
            $transaction =  mysqli_query($connect, "SELECT transaction_code FROM transactions ORDER BY id DESC LIMIT 1 ");
            if (mysqli_num_rows($transaction) > 0) {
                $transaction = mysqli_fetch_assoc($transaction);
                $transaction_code = $transaction['transaction_code']  + 1;
            }else{
                $transaction_code = 1;
            }
            $transaction_date = Carbon::now()->format('Y-m-d');

            $query = "INSERT INTO transactions(
                notes,
                slack,
                amount,
                bill_to,
                store_id,
                account_id,
                bill_to_id,
                created_by,
                bill_to_name,
                currency_code,
                payment_method,
                bill_to_address,
                bill_to_contact,
                transaction_code,
                transaction_date,
                transaction_type,
                payment_method_id) 
                VALUES(
                '".mysqli_real_escape_string($connect,$transaction_data['notes'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['slack'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['amount'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['bill_to'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['store_id'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['account_id'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['bill_to_id'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['created_by'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['bill_to_name'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['currency_code'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['payment_method'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['bill_to_address'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['bill_to_contact'])."',
                '".$transaction_code."',
                '".$transaction_date."',
                '".mysqli_real_escape_string($connect,$transaction_data['transaction_type'])."',
                '".mysqli_real_escape_string($connect,$transaction_data['payment_method_id'])."'
            ); ";

            mysqli_query($connect,$query);
            
            // update status to paid    
            mysqli_query($connect, "UPDATE expresspay SET status = '1', paid_at = '".$transaction_date."', response_json = '".json_encode($values)."'  WHERE bill_to_id = '".$data['bill_to_id']."' AND amount = '".$data['order_amount']."' AND status = 0");

            $already_paid_amount =  mysqli_query($connect, "SELECT SUM(amount) as already_paid_amount FROM transactions WHERE bill_to = '".$transaction_data['bill_to']."' AND bill_to_id = '".$transaction_data['bill_to_id']."' LIMIT 1 ");
            if (mysqli_num_rows($already_paid_amount) > 0) {
                $already_paid_amount = mysqli_fetch_assoc($already_paid_amount);
                $already_paid_amount = $already_paid_amount['already_paid_amount'];
            }else{
                $already_paid_amount = 0;
            }
                
            $total_amount_including_tax =  mysqli_query($connect, "SELECT subtotal_including_tax FROM invoices WHERE id = '".$transaction_data['bill_to_id']."' LIMIT 1 ");
            if (mysqli_num_rows($total_amount_including_tax) > 0) {
                $total_amount_including_tax = mysqli_fetch_assoc($total_amount_including_tax);
                $total_amount_including_tax = $total_amount_including_tax['subtotal_including_tax'];
            }else{
                $total_amount_including_tax = 0;
            }
            
            $total_invoice_charges =  mysqli_query($connect, "SELECT SUM(amount) as total_invoice_charges FROM invoice_charges WHERE invoice_id = '".$transaction_data['bill_to_id']."' LIMIT 1 ");
            if (mysqli_num_rows($total_invoice_charges) > 0) {
                $total_invoice_charges = mysqli_fetch_assoc($total_invoice_charges);
                $total_invoice_charges = $total_invoice_charges['total_invoice_charges'];
            }else{
                $total_invoice_charges = 0;
            }

            $total_amount = $total_amount_including_tax + $total_invoice_charges;
            $paid_amount = $already_paid_amount;

            if ($total_amount == $paid_amount) {
                $status = 3;
            } else {
                $status = 7;
            }

            mysqli_query($connect, "UPDATE invoices SET `status` = '".$status."' WHERE id = '".$data['bill_to_id']."' ");

            //Qoyod
            if(Session('qoyod_status')){

                $invoice_detail =  mysqli_query($connect, "SELECT slack FROM invoices WHERE id = '".$data['bill_to_id']."' LIMIT 1");
                $sales_detail = mysqli_fetch_assoc($invoice_detail);

                $account_detail =  mysqli_query($connect, "SELECT label FROM accounts WHERE id = '".$transaction_data['account_id']."' LIMIT 1");
                $account_detail = mysqli_fetch_assoc($invoice_detail);

                //create the bill payment
                $qoyod_cash_account = $this->qoyod_get_account(0,'q[en_name_eq]='.$account_detail['label']); //Cash Account

                if(!$qoyod_cash_account['status']){
                    $qoyod_cash_account = $this->qoyod_get_account(0,'q[code_eq]=110101'); //Cash Account
                }

                $qoyod_invoice = $this->qoyod_get_invoice(0,'q[reference_eq]='.$sales_detail['slack']); //invoice data
                $payment_data =array(
                    'reference'=>$this->getRandomString(10),
                    'invoice_id'=>$qoyod_invoice['data']->invoices[0]->id,
                    'account_id'=>$qoyod_cash_account['data']->accounts[0]->id,
                    'date'=>$transaction_date,
                    'amount'=>$transaction_data['amount'],
                );

                $this->qoyod_create_invoice_payment($payment_data);
            }

        }
        
        mysqli_close($connect);

        return true;
    }

}
