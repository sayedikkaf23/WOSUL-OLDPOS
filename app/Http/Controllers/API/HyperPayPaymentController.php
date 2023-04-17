<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MerchantSubscription;
use Illuminate\Http\Request;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Support\Str;
use App\Models\Merchant;
use App\Billing\HyperPayBilling;
use Illuminate\Support\Facades\DB;
use App\Models\HyperPayOrders;
use App\Models\OrderSubscriptions;
use App\Models\OrderDevices;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\OrderReceivedForHyperPay;
use App\Mail\SubscriptionPurchased;

class HyperPayPaymentController extends Controller
{
    
    public function payment(Request $request)
    {
        $model = config('hyperpay.model');
        $user = app($model)->where("id",$request->merchant_id)->first();
        
        $amount = $request->amount;
        $brand = $request->brand;
        $trackable_data = $request->get('products');
        $request->merchantCapabilities = "capability3DS";
        $request->supportedNetworks = ["mada","visa","masterCard"];
        $request->supportedCountries =["SA"];
        $merchantTransactionId = Str::random(16);
        return LaravelHyperpay::addMerchantTransactionId($merchantTransactionId)->addBilling(new HyperPayBilling($request))->checkout($trackable_data, $user, $amount, $brand, $request);
    }

    public function paymentStatus(Request $request)
    {
        $resourcePath = $request->get('resourcePath');
        $checkout_id = $request->get('id');
        return LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);
    }
    public function retrieveTaxAmount($products){
        $taxamount = 0;
        $products = (array)$products;
        if(count($products)>0){
            for($i=0;$i<count($products);$i++){
               $taxamount += $products[$i]->tax_amount;
            }
        }
        return $taxamount;
    }
    public function retrieveDiscountAmount($products){
        $taxamount = 0;
        return $taxamount;
    }
    public function retrieveTotalAmount($products){
        $totalamount = 0;
        foreach($products as $product)
        {
            $totalamount += (float)$product->total_amount;
        }
        return $totalamount;
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
    public function addToHyperpayOrders(Request $request){ //working code
        try{
        $checkoutid = $request->get("id");
        if(isset($request->card_details) && $request->card_details!="")
        {
            DB::update("update hyperpay_transactions set card_details=".json_encode($request->card_details)." where checkout_id  like '%{$checkoutid}%'");
        }
        $data['transaction'] = DB::select("select * from hyperpay_transactions where checkout_id  like '%{$checkoutid}%'");
        $merchant_id = $data['transaction'][0]->merchant_id;
        $products = json_decode($data['transaction'][0]->trackable_data);
        if(isset($products->amount))
        {
          unset($products->amount);
        }
        $products = (array)$products;
        $payment_method_id = DB::select("select * from payment_methods where label='hyperpay'");
        $payment_method_id = isset($payment_method_id[0])?$payment_method_id[0]->id:0;
        if($data['transaction'][0]->status=="success")
        {
          DB::beginTransaction();

          $data['merchant'] = DB::select("select * from merchants where id=".$merchant_id);
          $merchant_email = $data['merchant'][0]->email;
          $tax_amount = $this->retrieveTaxAmount($products);
          $orders = new HyperPayOrders;
          $orders->order_number = $this->retrieveOrderNumber($merchant_id);
          $orders->merchant_id = $merchant_id;

          $orders->address_line1 = $request->delivery_address_1;
          $orders->address_line2 = $request->delivery_address_2;
          $orders->city = $request->delivery_city;
          $orders->zipcode = $request->delivery_zipcode;
          $orders->country = $request->delivery_country;
          
          $orders->order_amount = $this->retrieveTotalAmount($products) - $tax_amount;
          $orders->tax_rate = 15.00;
          $orders->tax_amount = $tax_amount;
          $orders->discount_rate = isset($products[count($products)-1]->discountamount)?$products[count($products)-1]->discountamount:0;
          $orders->discount_amount = $this->retrieveDiscountAmount($products);
          $orders->total_amount = $this->retrieveTotalAmount($products);
          $orders->status = 1;
          $orders->payment_status = 1;
          $orders->payment_method_id= $payment_method_id;
          $orders->updated_at = Carbon::now();
          $orders->created_at = Carbon::now();
          $orders->save();
          for($i = 0;$i<count($products);$i++){
            if($products[$i]->product_type=="subscription")
            {
              $subscription = new OrderSubscriptions;
              $subscription->order_id = $orders->id;
              $subscription->subscription_id = $products[$i]->product_id;
              $data['subscription'] = DB::select("select * from subscriptions where id=".$products[$i]->product_id);
              $subscription->subscription_title = $data['subscription'][0]->title;
              $subscription->subscription_period = $data['subscription'][0]->subscription_type;
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
        $request->session()->pull("uid");
      DB::commit();
      $billing_data = [];
      $billing_data['address_line1'] = $request->delivery_address_1;
      $billing_data['state'] = $request->delivery_state;
      $billing_data['city'] = $request->delivery_city;
      $billing_data['zipcode'] = $request->delivery_zipcode;
      $billing_data['country'] = $request->delivery_country; 
          $mail_data = array_merge((array)$data["merchant"][0],(array)$data['transaction'][0],$billing_data);
          if (env('APP_ENV') == 'production') {
            Mail::to($merchant_email)->send(new OrderReceivedForHyperPay($mail_data));
            Mail::to(env('WOSUL_SALES_EMAIL'))->send(new OrderReceivedForHyperPay($mail_data));
            Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new OrderReceivedForHyperPay($mail_data));
         }
        }
      return ["status"=>200];
    }catch(Exception $ex){
        DB::rollback();
        throw $ex;
    } 
   }
   public function retrieveDeviceAmount($products)
   {
       try
       {
           $deviceamount = 0;
            foreach($products as $product)
            {
                if($product->product_type=="device")
                {
                  $deviceamount+=$product->total_amount;
                }
            }
            return $deviceamount;
       }
       catch(Exception $ex)
       {
           throw $ex;
       }
   }
   public function retrieveSubscriptionAmount($products)
   {
       try
       {
           $subscriptionamount = 0;
            foreach($products as $product)
            {
                if($product->product_type=="subscription")
                {
                  $subscriptionamount+=$product->total_amount;
                }
            }
            return $subscriptionamount;
       }
       catch(Exception $ex)
       {
           throw $ex;
       }
   }
}