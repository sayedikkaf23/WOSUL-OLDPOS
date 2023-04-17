<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\Product as ProductModel;
use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Taxcode;
use App\Models\Discountcode;
use Mpdf\Mpdf;
use QrCode;
use Schema;
class GenerateQrCode extends Controller
{
   

    public function create_qr_code(Request $request){
    
      try {
       $hasTable=Schema::hasTable('qr_codes');
       
       if($hasTable){
        $store_id=$request->store_id;
        if($store_id!=null){    
         
         $store=Store::where('id',$store_id)->first();

          if($store->name!=""){
          
             if($store->primary_email!=""){
           
              $data = [
                
                'owner_name'=>$store->name,
                'name' => $this->makeSubdomain($store->name),
                'owner_email'=>$store->primary_email,
                'owner_phone'=>$store->primary_contact
                ];
            
            $url=env('WOSULIN_URL')."api/v2/client/restaurant/create";

            $ch = curl_init( $url );
            # Setup request to send json via POST.

            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            # Send request.
            $response = curl_exec($ch);
            // print_r($response);die;
            curl_close($ch);
           

            $result=json_decode($response);
          
            if(@$result[0]->data=='error'){
                
              return response()->json($this->generate_response(
                array(
                    "message" => trans($result[0]->message), 
                    "data"    => "error",
                )
              ));
            }

            $restaurant_id=@$result[0]->data->id;
            $store_name=@$result[0]->data->subdomain;

            if($restaurant_id!="" && $store_name!=""){
                
                //DB::table('qr_codes')->where('store_id',$user->store_id)->delete();
                
                DB::beginTransaction();

                $qr_codes=DB::table('qr_codes')->insert(['slack'=>$this->generate_slack("qr_codes"),'store_id'=>$store_id,'restaurant_id'=>isset($restaurant_id)?$restaurant_id:'']);

                DB::commit();
                return response()->json($this->generate_response(
                    array(
                        "message" => trans("QR Code created successfully"), 
                        "data"    => $qr_codes
                    ), trans('SUCCESS')
                ));
              }else{
                return response()->json($this->generate_response(
                    array(
                        "message" => trans("Missing restaurant information"), 
                        "data"    => "error",
                    )
                ));
               }  
         

                 } else {
                 return response()->json($this->generate_response(
                        array(
                            "message" => trans("Store email is required to create QR code"), 
                            "data"    => "error",
                        )
                    ));
                  }
         } else {
         return response()->json($this->generate_response(
                array(
                    "message" => trans("Store name is required to create QR code"), 
                    "data"    => "error",
                )
            ));
          }    


        

          }else{
             return response()->json($this->generate_response(
                    array(
                        "message" => trans("Store not found"), 
                        "data"    => "error",
                    )
                ));
          }
    }else{
         return response()->json($this->generate_response(
                array(
                    "message" => trans("Missing QR table"), 
                    "data"    => "error",
                )
            ));
      }  

    }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }


    }

    public function makeSubdomain($name)   {
        $cyr = [
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q', ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    public function sync_category_product(Request $request) {

      try {

       if($request->user_id!="" && $request->restaurant_id!="" && $request->store_id!=""){
        $user_id=$request->user_id;
        $restaurant_id=$request->restaurant_id;
        $store_id=$request->store_id;

        $categories = Category::active()
                        ->orderBy('id', 'asc')
                        ->whereNotNull('label')
                        ->get();
          $check_products = Product::active()
                        ->where(['store_id'=>$store_id,'is_ingredient'=>0])
                        ->get()
                        ->toArray();               
        $sync_data=array(); 
        if(empty($categories->toArray())){
           return response()->json($this->generate_response(
               array(
                   "message" => trans("Category not found or inactive in the system"), 
                   "data"    => "error",
               )
           ));
        }else if(empty($check_products)){
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product not found or inactive in the system"), 
                    "data"    => "error",
                )
            ));
        }else{              
        foreach($categories as $category) {
             $products = Product::active()

                        ->where(['products.store_id'=>$store_id,'category_id'=>$category->id,'is_ingredient'=>0])
                        ->whereNotNull('products.name')
                        ->orderBy('products.id', 'asc')
                        ->get();

            $product_data=array();

            foreach($products as $product) {

                $tax=0;
                if(session('store_tax_code') != ""){
                    $tax = Taxcode::active()
                    ->where(['id'=>session('store_tax_code')])
                    ->first();
                    if(isset($tax)){
                        $tax = (float) $tax->total_tax_percentage;
                    }
                }

                $discount_amount = 0;
                if($product->discount_code_id != ""){
                    $get_discount = DiscountCode::where('id',$product->discount_code_id)->first();
                    if(isset($get_discount)){
                        $discount_amount = $product->sale_amount_excluding_tax * (float) $get_discount->discount_percentage / 100;
                    }
                }   

                $product_price = $product->sale_amount_excluding_tax - $discount_amount;
                
                $tax_amount = 0;
                if($tax > 0){
                    $tax_amount = $product_price * $tax / 100;
                }

                $product_price = $product_price + $tax_amount;
                
                $product_data[]=[
                    'product_id'=>$product->id,
                    'product_name'=>$product->name,
                    'product_code'=>$product->product_code,
                    'product_price'=>$product_price? number_format((float)$product_price, 2, '.', ''):0,
                    'product_description'=>$product->description,
                    'product_image'=>file_exists(public_path('storage/'.session('merchant_id').'/product/').$product->product_thumb_image) && $product->product_thumb_image!=""?url('/').'/storage/'.session('merchant_id').'/product/'.$product->product_thumb_image:'',
                    'product_status'=>$product->status,
                    'product_tax'=>$tax,
                ];
            } 

            $sync_data[]=[
                'category_id'=>$category->id,
                'category_code'=>$category->category_code,
                'category_name'=>$category->label,
                'category_description'=>$category->description,
                'restaurant_id'=>$restaurant_id,
                'category_status'=>$category->status,
                'products'=>$product_data

            ] ;
           
        }

            $url=env('WOSULIN_URL')."api/v2/client/sync_data";

            $ch = curl_init( $url );
            # Setup request to send json via POST.

            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($sync_data) );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            # Send request.
            $response = curl_exec($ch);
            curl_close($ch);
            //print_r($response);
            $result=json_decode($response);

           
           //die;
            
            if(@$result[0]->data=='error'){
                
              return response()->json($this->generate_response(
                array(
                    "message" => trans($result[0]->message), 
                    "data"    => "error",
                )
              ));
            }
            
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Sync data has been processed successfully"), 
                    "data"    => $result,
                )
            ));
        }      

       } else {
         return response()->json($this->generate_response(
                array(
                    "message" => trans("Missing store information!"), 
                    "data"    => "error",
                )
            ));
       }  
       
     }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }  

    }
 
    
}
