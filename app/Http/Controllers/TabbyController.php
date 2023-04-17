<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceivedForHyperPay;
use App\Models\HyperPayOrders;
use App\Models\MerchantSubscription;
use App\Models\OrderDevices;
use App\Models\OrderSubscriptions;
use App\Models\SubscriptionActivation;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\TabbyTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentMethod;
use App\Models\Subscription;

class TabbyController extends Controller
{
    public function finalize(Request $request)//Final Worked Code
    {
        try {
            $payment_id = $request->input('payment_id');
            $capturedDetails = [];
            $merchant_id = 0;
            if (isset($payment_id)) {
                $url = env('tabby_payment_status') . $payment_id;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers = array(
                    "Content-Type: application/json",
                    "Authorization: Bearer " . env('tabby_secret_key'),
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);

                $merchant_id = $data["meta"]["customer"];
                if (strtolower($data["status"]) == "created") {
                    $status = "cancel";
                } else if (strtolower($data["status"]) == "closed" || strtolower($data["status"]) == "authorized") {
                    $this->capturePayment($payment_id,$data);
                    $status = "success";
                    $request->session()->forget("merchant_email");
                } else {
                    $status = "failed";
                }
                $response = [];
                $paymentdetails = [
                    "payment_id" => $data["id"],
                    "merchant_id" => $merchant_id,
                    "status" => $status,
                    "amount" => $data["amount"],
                    "currency" => $data["currency"]
                ];
                $response['paymentdetails'] = $paymentdetails;
                $request->session()->pull("uid");

                if($status == "success"){
                    $data['merchant'] = Merchant::where('id',$merchant_id)->first()->toArray();
                    $merchant_email = $data['merchant']['email'];

                    $data['transaction'] = TabbyTransaction::where('payment_id','like','%' . $payment_id . '%')->first()->toArray();
                    $products = json_decode($data['transaction']['trackable_data']);

                    $payment_method_id = PaymentMethod::where('label','tabby')->first();
                    $payment_method_id = isset($payment_method_id)?$payment_method_id->id:0;

                    /*Entries in the tables*/
                    DB::beginTransaction();

                    /*1. Change the transaction table status*/
                    TabbyTransaction::where('payment_id', $payment_id)->update(['status' => $status,'updated_at'=> Carbon::now()]);

                    /*2. Entry in Order table*/
                    //Entry in order table
                    $orders = new HyperPayOrders;
                    $orders->order_number = $this->retrieveOrderNumber($merchant_id);
                    $orders->merchant_id = $merchant_id;
                    $orders->address_line1 = $request->session()->get('street')[0];
                    $orders->address_line2 = $request->session()->get('street')[0];
                    $orders->city = $request->session()->get('city')[0];
                    $orders->zipcode = $request->session()->get('zipcode')[0];
                    $orders->country = $request->session()->get('country')[0];
                    $orders->order_amount = $data['amount'] - $data['order']['tax_amount'];
                    $orders->tax_rate = 15.00;
                    $orders->tax_amount = $data['order']['tax_amount'];
                    $orders->discount_rate = 0;
                    $orders->discount_amount = 0;
                    $orders->total_amount = $data['amount'];
                    $orders->status = 1;
                    $orders->payment_status = 1;
                    $orders->payment_method_id = $payment_method_id;
                    $orders->updated_at = Carbon::now();
                    $orders->created_at = Carbon::now();
                    $orders->save();

                    /*3. entry in product and device table*/
                    for($i = 0;$i<count($products);$i++){

                        if($products[$i]->product_type=="subscription")
                        {
                            $data['subscription'] = Subscription::where('id',$products[$i]->product_id)->first();
                            $subscription = new OrderSubscriptions;
                            $subscription->order_id = $orders->id;
                            $subscription->subscription_id = $products[$i]->product_id;
                            $subscription->subscription_title = $data['subscription']->title;
                            $subscription->subscription_period = $data['subscription']->subscription_type;
                            $subscription->start_date = $products[$i]->subscription_start_date;
                            $subscription->end_date =$this->retrieveEndDate($products[$i]->subscription_start_date,$products[$i]->subscription_days);
                            $subscription->basic_amount = $products[$i]->amount;
                            $subscription->tax_amount = $products[$i]->tax_amount;
                            $subscription->amount = $products[$i]->total_amount;
                            $subscription->status = 1;
                            $subscription->created_at = Carbon::now();
                            $subscription->updated_at = Carbon::now();
                            $subscription->save();

                            $selected_subscription = MerchantSubscription::withTrashed()->where('subscription_id',$products[$i]->product_id)->where('merchant_id',$merchant_id)->where('status','0')->first();
                            if(isset($selected_subscription) && $selected_subscription->id>0){
                                //update the status
                                MerchantSubscription::withTrashed()->where('id',$selected_subscription->id)->update(['status'=>1]);
                            }else{
                                //insert new subscription record
                                $merchant_subscription = [
                                    'subscription_id' => $products[$i]->product_id,
                                    'merchant_id' => $merchant_id,
                                    'start_date' => $products[$i]->subscription_start_date,
                                    'end_date' => $this->retrieveEndDate($products[$i]->subscription_start_date,$products[$i]->subscription_days),
                                    'status' => 1, //active
                                    'is_trial' => 0,
                                ];
                                MerchantSubscription::create($merchant_subscription);
                            }
                        }
                        else{
                            $device = new OrderDevices;
                            $device->order_id = $orders->id;
                            $device->device_id = $products[$i]->product_id;
                            $device->device_name = $products[$i]->product_name;
                            $device->quantity = $products[$i]->device_count;
                            $device->basic_amount = $products[$i]->amount;
                            $device->tax_amount = $products[$i]->tax_amount;
                            $device->amount = $products[$i]->total_amount;
                            $device->status = 1;
                            $device->created_at = Carbon::now();
                            $device->updated_at = Carbon::now();
                            $device->save();
                        }
                    }
                    DB::commit();

                    /*Send Mail*/
                    $billing_data = [];
                    $billing_data['address_line1'] = $request->session()->get('street')[0];
                    $billing_data['state'] = $request->session()->get('state')[0];
                    $billing_data['city'] = $request->session()->get('city')[0];
                    $billing_data['zipcode'] = $request->session()->get('zipcode')[0];
                    $billing_data['country'] = $request->session()->get('country')[0];
                    $mail_data = array_merge((array)$data['merchant'],(array)$data['transaction'],$billing_data);
                     if (env('APP_ENV') == 'production') {
                         Mail::to($merchant_email)->send(new OrderReceivedForHyperPay($mail_data));
                         Mail::to(env('WOSUL_SALES_EMAIL'))->send(new OrderReceivedForHyperPay($mail_data));
                         Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new OrderReceivedForHyperPay($mail_data));
                         Mail::to(env('WOSUL_OPERATION_EMAIL'))->send(new OrderReceivedForHyperPay($mail_data));
                     }

                    $request->session()->push('street', '');
                    $request->session()->push('city', '');
                    $request->session()->push('state', '');
                    $request->session()->push('zipcode', '');
                    $request->session()->push('country', '');
                }
                
                return view('payment.tabby', $response);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function capturePayment($payment_id, $data)
    {
        try {
            $url = str_replace("{id}", $payment_id, env('tabby_capture_url'));
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env('tabby_secret_key'),
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);
            return $data;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function retrieveOrderNumber($merchantid){
        $details = DB::select("select * from orders order by id desc");
        if(count($details)>0){
            $order_id = (int)$details[0]->id;
            return $order_id+1;
        }
        else
        {
            return 1;
        }
    }
    public function retrieveEndDate($startdate,$tenure){
        if($tenure==1)
        {
            return (new \DateTime($startdate))->add(new \DateInterval("P30D"))->format('Y-m-d');
        }
        else
        {
            return (new \DateTime($startdate))->add(new \DateInterval("P365D"))->format('Y-m-d');
        }
    }
}
