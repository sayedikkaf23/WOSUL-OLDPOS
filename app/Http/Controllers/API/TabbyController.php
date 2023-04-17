<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\TabbyTransaction;
use App\Models\HyperPayOrders;
use App\Models\OrderSubscriptions;
use App\Models\OrderDevices;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\OrderReceived;

use App\Models\SubscriptionActivation as SubscriptionActivationModel;

class TabbyController extends Controller
{
  public function create_webhook(Request $request)
  {
    try
    {
      if($this->isWebHookExist($request)==false)
      {
        $webhook_url = env('tabby_webhook_url');
        $curl = curl_init($webhook_url);
        curl_setopt($curl, CURLOPT_URL,$webhook_url);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true); 
        $language = $request->input("language");
        $key = env('tabby_secret_key');
        
        $headers = array(

            "Content-Type: application/json",
             
            "Authorization: Bearer {$key}",

            "X-Merchant-Code : Wosul"
         
           );

        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        $endpoint_url = url('/api/webhook');
        $title = Str::random(10);
        $data = <<<DATA
        {
          "url": "$endpoint_url",
          "is_test": false,
          "header": {
            "title": "$title",
            "value": "$title"
          }
        }
        DATA;
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response,true);
        return $data;
      }
      else
      {
        return ["status"=>"error","message"=>"webhook already exist"];
      }
    }
    catch(Exception $ex){
      throw $ex;
    }
  }


  public function webhook(Request $request){
      try
      {
        $json = file_get_contents('php://input');
        $object = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
          die(header('HTTP/1.0 415 Unsupported Media Type'));
        }
        $payment_id = (string)$object->id;
        file_put_contents(storage_path($payment_id.'_webhook.txt'),$json);
        if(strtolower($object->status)=="authorized")
        {
            $this->capture_request($request,$object,$payment_id);
        }
        else
        {
          $this->insertIntoTabbyTransactions($object,$request,$payment_id);
        }

      }
      catch(Exception $ex)
      {
         throw $ex;
      }
     }
     public function capture_request(Request $request,$object,$payment_id)
     {
      try
      {
        $payment_url = env('tabby_capture_url');
        $payment_url = preg_replace("/{id}/",$payment_id,$payment_url);
        $curl = curl_init($payment_url);
        curl_setopt($curl, CURLOPT_URL,$payment_url);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true); 
        
        $key = env('tabby_secret_key');
        $headers = array(

          "Content-Type: application/json",
           
          "Authorization: Bearer {$key}",
       
         );
         $taxamount = $object->order->tax_amount;
         $discountamount = $object->order->discount_amount;
         $shipping_amount = $object->order->shipping_amount;
         $created_at = $object->created_at;
         $data = <<<DATA
         {
            "amount": "$shipping_amount",
            "tax_amount": "$taxamount",
            "shipping_amount": "$shipping_amount",
            "discount_amount": "$discountamount",
            "created_at": "$created_at"
         }
        DATA;
       curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
       curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
       $response = curl_exec($curl);
       curl_close($curl);
       $data = json_decode($response,true);
       file_put_contents(storage_path($payment_id.'_capture.txt'),json_encode($data));
       return $data;
      }
      catch(Exception $ex)
      {
        throw $ex;
      }
     }
     public function insertIntoTabbyTransactions($object,$request,$payment_id){
       try
       {
         DB::beginTransaction();
         $transactionobj = new TabbyTransaction();
         $transactionobj->payment_id = $payment_id;
         $transactionobj->status = $object->status;
         $transactionobj->merchant_id = $object->meta->customer;
         $transactionobj->amount = $object->amount;
         $transactionobj->currency = (string)$object->currency;
         $transactionobj->trackable_data = json_encode($object->order);
         $transactionobj->tabby_details = json_encode($object);
         $transactionobj->save();
         DB::commit();
         $this->add_to_orders($payment_id,$object); 
       }
       catch(Exception $ex)
       {
          //print_r($ex);die;
          throw $ex;
       }
    }
    public function retrieveTaxAmount($products){
      $taxamount = 0;
      $products = (array)$products;
      if(count($products)>0){
          for($i=0;$i<count($products);$i++){
             $taxamount += $products[$i]['tax_amount'];
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
        $totalamount += (float)$product['total_amount'];
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
    public function add_to_orders($paymentid,$object){ //Not used
       try
       {
         $data['transaction'] = DB::select("select * from tabby_transactions where payment_id  like '%{$paymentid}%'");
         $payment_method_id = DB::select("select * from payment_methods where label='tabby'");
         $payment_method_id = isset($payment_method_id[0])?$payment_method_id[0]->id:0;
         $products = json_decode($data['transaction'][0]->trackable_data);
         $merchant_id = $data['transaction'][0]->merchant_id;
         $products = (array)$products;
         if(isset($data['transaction'][0]->status) && strtolower($data['transaction'][0]->status)=="closed")
         {
           $data['merchant'] = DB::select("select * from merchants where id=".$merchant_id);
           $merchant_email = $data['merchant'][0]->email;
           DB::beginTransaction();
           $shipping_address = explode("<br>",$object->shipping_address->address);
           $orders = new HyperPayOrders;
           $orders->order_number = $this->retrieveOrderNumber($merchant_id);
           $orders->merchant_id = $merchant_id;
 
           $orders->address_line1 = isset($shipping_address[0])?$shipping_address[0]:"";
           $orders->city = isset($shipping_address[1])?$shipping_address[1]:"";
           $orders->address_line2 = isset($shipping_address[2])?$shipping_address[2]:"";
           $orders->zipcode = isset($shipping_address[3])?$shipping_address[3]:"";
           $orders->country = isset($shipping_address[4])?$shipping_address[4]:"";
           $orders->order_amount = $products["shipping_amount"];
           $orders->tax_rate = 15.00;
           $orders->tax_amount = $products['tax_amount'];
           $orders->discount_rate = $products["discount_amount"];
           $orders->discount_amount = $products["discount_amount"];
           $orders->total_amount = $products["shipping_amount"];
           $orders->status = 1;
           $orders->payment_status = 1;
           $orders->payment_method_id= $payment_method_id;
           $orders->updated_at = Carbon::now();
           $orders->created_at = Carbon::now();
           $orders->save();
           $productlist = (array)$products['items'];
           for($i = 0;$i<count($productlist);$i++){
             $productdetails = json_decode($productlist[$i]->description);
             if($productdetails->product_type=="subscription")
             {
              $subscription = new OrderSubscriptions;
              $subscription->order_id = $orders->id;
              $subscription->subscription_id = $productdetails->product_id;
              $data['subscription'] = DB::select("select * from subscriptions where id=".$productdetails->product_id);
              $subscription->subscription_title = $data['subscription'][0]->title;
              $subscription->subscription_period = $data['subscription'][0]->subscription_type;
              $subscription->start_date = $productdetails->subscription_start_date;
              $subscription->end_date =$this->retrieveEndDate($productdetails->subscription_start_date,$productdetails->subscription_days);
              $subscription->amount = $productdetails->total_amount;
              $subscription->status = 1;
              $subscription->created_at = Carbon::now();
              $subscription->updated_at = Carbon::now();

              $subscription_activations = [];
              $subscription_activations['merchant_id'] = $merchant_id;
              $subscription_activations['subscription_id'] = $productdetails->product_id;
              $subscription_activations['start_date'] = $productdetails->subscription_start_date;
              $subscription_activations['end_date'] = $this->retrieveEndDate($productdetails->subscription_start_date,$productdetails->subscription_days);
              $subscription_activations['status'] = 1;
              SubscriptionActivationModel::create($subscription_activations);
              $subscription->save();
              }
              else{
                $device = new OrderDevices;
                $device->order_id = $orders->id;
                $device->device_id = $productdetails->product_id;
                $device->device_name = $productdetails->product_name;
                $device->amount = $productdetails->total_amount;
                $device->status = 1;
                $device->created_at = Carbon::now();
                $device->updated_at = Carbon::now();
                $device->save();
              }
         }
         //$request->session()->pull("uid");
          DB::commit();
          $billing_data = [];
          $billing_data["address_line_1"] = isset($shipping_address[0])?$shipping_address[0]:"";
          $billing_data["city"] = isset($shipping_address[1])?$shipping_address[1]:"";
          $billing_data["state"] = isset($shipping_address[2])?$shipping_address[2]:"";
          $billing_data["zipcode"] = isset($shipping_address[3])?$shipping_address[3]:"";
          $billing_data["country"] = isset($shipping_address[4])?$shipping_address[4]:"";
          $mail_data = array_merge((array)$data["merchant"][0],(array)$data['transaction'][0],$billing_data);
        //   if (env('APP_ENV') == 'production') {
        //     Mail::to($merchant_email)->send(new OrderReceived($mail_data));
        //     Mail::to(env('WOSUL_SALES_EMAIL'))->send(new OrderReceived($mail_data));
        //     Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new OrderReceived($mail_data));
        //  }
         }
         return ["status"=>200];
       }
       catch(Exception $ex)
       {
          throw $ex;
       }
    }
    
   public function isWebHookExist(){
      try
      {
        $webhook_url = env('tabby_webhook_url');
        $curl = curl_init($webhook_url);
        curl_setopt($curl, CURLOPT_URL,$webhook_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $key = env('tabby_secret_key');
        $headers = array(
           "Content-Type: application/json",
           "Authorization: Bearer {$key}",
           "X-Merchant-Code: Wosul"
        );
        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response,true);
        if(count((array)$data)>0)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      catch(Exception $ex)
      {
        throw $ex;
      }
   }
    public function payment(Request $request){
      try
      {
        $payment_url = env('tabby_payment_url');
        $curl = curl_init($payment_url);
        curl_setopt($curl, CURLOPT_URL,$payment_url);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true); 
        
        $key = env('tabby_public_key');
        
        $headers = array(

            "Content-Type: application/json",
             
            "Authorization: Bearer {$key}",
         
           );
        $webhook_status = $this->create_webhook($request);
        if(isset($webhook_status["id"]) || ($webhook_status["status"]=="error" && $webhook_status["message"]=="webhook already exist"))
        {
        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        $productdata = [];
        $productlist = $request->get("products");
        $orderid = Str::random(10);
        $referenceid = Str::random(10);
        $language = $request->input("language");
        $totalamount = $request->input("totalamount");
        $merchant_id = $request->session()->get("logged_merchant_id");
        $merchant_details = DB::select("select * from merchants where id={$merchant_id}");
        $merchant_orders = DB::select("select * from orders where merchant_id={$merchant_id}");
        $registered_at = "";
        if(isset($merchant_details[0]))
        {
          $registered_at = (new \DateTime($merchant_details[0]->created_at))->format("c");
        }
        else
        {
          $registered_at = date('c', strtotime(date('Y-m-d H:i:s')));
        }
        $loyalty_level = count($merchant_orders);
        $taxamount = 0.00;
        $discountamount = 0.00;
        $updateddate = date('c', strtotime(date('Y-m-d H:i:s')));
        if($language=="ar"){
          $success_url = url('/ar/response');
          $cancel_url = url('/ar/response');
          $failed_url = url('/ar/response');
        }
        else
        {
          $success_url = url('/en/response');
          $cancel_url = url('/en/response');
          $failed_url = url('/en/response');
        }
        $product_type_details = [];
        $trackable_data = [];
        foreach($productlist as $product){
          $taxamount+=$product['tax_amount_round'];
          $discountamount +=$product['discount_amount']; 
          $unitprice = $product['product_type']=='subscription'?$product['total_amount']:number_format($product['unit_price'],2);
          $quantity = $product['product_type']=='subscription'?1:$product['device_count'];
          if($product['product_type']=="subscription")
          {
            $product_type_details["product_type"] = $product['product_type'];
            $product_type_details["product_name"] = $product['product_name'];
            $product_type_details["product_id"] = $product['product_id'];
            $product_type_details['total_amount'] = $product['total_amount'];
            $product_type_details['tax_amount'] = $product['tax_amount_round'];
            $product_type_details['amount'] = $product['amount_round'];
            $product_type_details['discount_amount'] = $product['discount_amount'];
            $product_type_details["subscription_start_date"] = $product['subscription_start_date'];
            $product_type_details["subscription_days"] = $product['subscription_days'];
            $product_type_details["product_description"] = $product['product_description'];
          }
          else
          {
            $product_type_details["product_id"] = $product['product_id'];
            $product_type_details["product_name"] = $product['product_name'];
            $product_type_details["product_type"] = $product['product_type'];
            $product_type_details['total_amount'] = $product['total_amount'];
            $product_type_details['tax_amount'] = $product['tax_amount_round'];
            $product_type_details['amount'] = $product['amount_round'];
            $product_type_details['device_count'] = $product['device_count'];
            $product_type_details['discount_amount'] = $product['discount_amount'];
            $product_type_details["product_description"] = $product['product_description'];
          }
          array_push($productdata,[
                              "title"=>$product['product_name'],
                              "description"=>json_encode($product_type_details),
                              "quantity"=>(int)$quantity,
                              "unit_price"=>(int)$unitprice,
                              "category"=>strtolower($product['product_type']),
                              "reference_id"=>"$referenceid"
          ]);

          array_push($trackable_data,$product_type_details);

        }
        $phone_number = $request->input('merchant_phone');
        $merchant_email = $request->input('merchant_email');
        $city = $request->billingCity;
        $zip = $request->billingPostCode;
        $street = $request->billingStreet;
        $country = $request->billingCountry;
        $address = $request->billingStreet."<br>".$request->billingCity."<br>".$request->billingState."<br>".$request->billingPostCode."<br>".$request->billingCountry;
        $merchantemail = $request->login_merchant_email;
        $merchant_name = $request->input('merchant_name');
        $purchasedat = date('c', strtotime(date('Y-m-d H:i:s')));
        $data = <<<DATA
        {
         "payment":{
         "amount": "$totalamount",
         "currency":"SAR",
         "description":"new_payment",
         "buyer":{
            "phone":"$phone_number",
            "email":"$merchant_email",
            "name":"$merchant_name"
          },
          "shipping_address":{
            "city":"$city",
            "zip":"$zip",
            "address":"$address"
          },
          "order":{
           "tax_amount":"$taxamount",
           "shipping_amount":"$totalamount",
           "discount_amount":"$discountamount",
           "updated_at":"$updateddate",
           "reference_id":"$referenceid"
          },
          "meta":{
            "order_id":"$referenceid",
            "customer":"$merchant_id"
           }
           
           },
           "lang":"$language",
           "merchant_code":"Wosul",
           "merchant_urls":{
             "success":"$success_url",
             "cancel":"$cancel_url",
             "failure":"$failed_url"
            }
        }
DATA;
  $data = json_decode($data);
  $data->payment->order->items = (array)$productdata;

  $order_history_item = [];
  $order_history = [];
  $order_history_item["purchased_at"] = $purchasedat;
  $order_history_item["amount"] = $totalamount;
  $order_history_item["status"] = "new";
  $order_history_item["buyer"] = ["name"=>$merchant_name,"email"=>$merchant_email,"phone"=>$phone_number];
  $order_history_item["shipping_address"] = ["city"=>$city,"zip"=>$zip,"address"=>$address];
  $order_history_item["items"] = (array)$productdata;
  array_push($order_history,$order_history_item);
  $data->payment->order_history = $order_history;

  $buyer_history = [];
  $buyer_history["registered_since"] = $registered_at;
  $buyer_history["loyalty_level"] = $loyalty_level;
  $data->payment->buyer_history = $buyer_history;


  $data = json_encode($data);
  curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
  
  $response = curl_exec($curl);
  
  curl_close($curl);

  $data = json_decode($response,true);

  if(isset($data["configuration"]))
  {
    if(!isset($data["configuration"]["products"]["installments"]["rejection_reason"]))
    {
        //Insert the transaction entry
        $tab_transaction = new TabbyTransaction;
        $tab_transaction->merchant_id = Session('logged_merchant_id');
        $tab_transaction->payment_id  = $data['payment']['id'];
        $tab_transaction->amount  = $data['payment']['amount'];
        $tab_transaction->currency  = $data['payment']['currency'];
        $tab_transaction->trackable_data  = json_encode($trackable_data);
        $tab_transaction->tabby_details  = json_encode($data['configuration']);
        $tab_transaction->status  = 'created';
        $tab_transaction->created_at  = Carbon::now();
        $tab_transaction->updated_at  = Carbon::now();
        $tab_transaction->save();

        $responsedata = $data["configuration"]["available_products"]["installments"];
        $response=[
            'url'=>$responsedata['0']['web_url']
        ];

        $request->session()->push('street', $request->billingStreet);
        $request->session()->push('city', $request->billingCity);
        $request->session()->push('state', $request->billingState);
        $request->session()->push('zipcode', $request->billingPostCode);
        $request->session()->push('country', $request->billingCountry);
    }
    else
    {
        throw new \Exception($data["configuration"]["products"]["installments"]["rejection_reason"]);
    }
  }
  else
  {
     $response = $data;
  }
  return json_encode($response);

    }
    else
    {
      throw new \Exception("Some Error have Occured");
    }
  }
  catch(Exception $ex){
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
                if($product['product_type']=="device")
                {
                  $deviceamount+=$product['total_amount'];
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
                if($product['product_type']=="subscription")
                {
                  $subscriptionamount+=$product['total_amount'];
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