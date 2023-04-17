<?php

namespace App\Http\Traits;

use App\Http\Controllers\API\Order as OrderAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use App\Models\Product as ProductModel;
use App\Models\Order as OrderModel;
use App\Models\Store as StoreModel;
use App\Http\Resources\ProductResource;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;
use Illuminate\Http\Request;
use Exception;
use App\Models\Invoice as InvoiceModel;
use App\Models\Quotation as QuotationModel;
use App\Models\InvoiceReturn as InvoiceReturnModel;
use App\Models\QuantityHistory;
use Illuminate\Support\Facades\DB;
use App\Models\SettingApp;

trait CommonApiTrait
{


    public function update_ingredient_quantity(Request $request, $product_id, $cart_quantity, $type = 'decrement')
    {

        $requested_product_quantity = $cart_quantity;

        $product_ingredients = ProductIngredientModel::with('measurements')->where('product_id', $product_id)->get();

        if (!empty($product_ingredients)) {

            foreach ($product_ingredients as $product_ingredient) {

                $ingredient = ProductModel::where('id', $product_ingredient->ingredient_product_id)->active()->first();

                if ($ingredient->measurement_id == $product_ingredient->measurement_id || $product_ingredient->measurements->measurement_category_id == "") {

                    $quantity = $product_ingredient->quantity * $requested_product_quantity;
                    if ($type == "decrement") {
                        ProductModel::find($product_ingredient->ingredient_product_id)->decrement('quantity', $quantity);
                    } else{
                        ProductModel::find($product_ingredient->ingredient_product_id)->increment('quantity', $quantity);
                    }
                } else {

                    // if not same then we need to get the conversion, means 1 == ?
                    $measurement_conversion = MeasurementConversionModel::where([
                        'from_measurement_id' => $product_ingredient->measurement_id,
                        'to_measurement_id' => $ingredient->measurement_id
                    ])->active()->first();

                    if(isset($measurement_conversion->value))
                    {
                        $quantity = ((float) $measurement_conversion->value * $product_ingredient->quantity) * $requested_product_quantity;
                    }
                    else
                    {
                        $quantity = ((float)$product_ingredient->quantity) * $requested_product_quantity;
                    }

                    // dd($measurement_conversion);
                    if ($type == "decrement") {
                        ProductModel::find($product_ingredient->ingredient_product_id)->decrement('quantity', $quantity);
                    }else{
                        ProductModel::find($product_ingredient->ingredient_product_id)->increment('quantity', $quantity);
                    }
                }
            }
        }

        // $restaurant_mode = $request->logged_user_store_restaurant_mode;

        // if (!empty($product_id) && $restaurant_mode == 1) {

        //     $product_data = ProductModel::select('id', 'product_code', 'name', 'is_ingredient')
        //         ->where('id', '=', trim($product_id))
        //         ->first();

        //     if ($product_data->is_ingredient == 0) {
        //         $ingredient_list = ProductIngredientModel::select('*')
        //             ->where('product_id', '=', trim($product_id))
        //             ->get();

        //         if (!empty($ingredient_list)) {
        //             foreach ($ingredient_list as $ingredient_list_item) {

        //                 $total_ingredient_quantity = $cart_quantity * $ingredient_list_item->quantity;

        //                 if ($type == "decrement") {
        //                     $ingredient_data = ProductModel::select('products.id as product_id')
        //                         ->where('products.id', '=', trim($ingredient_list_item['ingredient_product_id']))
        //                         ->categoryJoin()
        //                         ->supplierJoin()
        //                         ->taxcodeJoin()
        //                         ->discountcodeJoin()
        //                         ->categoryActive()
        //                         ->supplierActive()
        //                         ->taxcodeActive()
        //                         ->quantityCheck($total_ingredient_quantity)
        //                         ->active()
        //                         ->first();
        //                     if (empty($ingredient_data)) {
        //                         throw new Exception("Ingredient for product " . $product_data->product_code . " - " . $product_data->name . " is currently out of stock", 400);
        //                     }

        //                     $product = ProductModel::find($ingredient_data['product_id']);
        //                     $product->decrement('quantity', $total_ingredient_quantity);
        //                 } else {
        //                     $ingredient_data = ProductModel::select('products.id as product_id')
        //                         ->where('products.id', '=', trim($ingredient_list_item['ingredient_product_id']))
        //                         ->categoryJoin()
        //                         ->supplierJoin()
        //                         ->taxcodeJoin()
        //                         ->discountcodeJoin()
        //                         ->categoryActive()
        //                         ->supplierActive()
        //                         ->taxcodeActive()
        //                         ->active()
        //                         ->first();

        //                     if (!empty($ingredient_data)) {
        //                         $product = ProductModel::find($ingredient_data['product_id']);
        //                         $product->increment('quantity', $total_ingredient_quantity);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
    }

    public function __check_ingredient_stock($product_id, $requested_product_quantity)
    {

        $product_ingredients = ProductIngredientModel::with('measurements')->where('product_id', $product_id)->get();

        $low_ingredient_stock = 0;

        if (!empty($product_ingredients)) {
            foreach ($product_ingredients as $product_ingredient) {

                $ingredient = ProductModel::where('id', $product_ingredient->ingredient_product_id)->active()->first();

                if ($product_ingredient->measurements != null) {

                    if ($ingredient->measurement_id == $product_ingredient->measurement_id || $product_ingredient->measurements->measurement_category_id == "") {

                        $quantity = $product_ingredient->quantity * $requested_product_quantity;
                        if (($ingredient->quantity < $quantity) && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                    } else {

                        // if not same then we need to get the conversion, means 1 == ?
                        $measurement_conversion = MeasurementConversionModel::where([
                            'from_measurement_id' => $product_ingredient->measurement_id,
                            'to_measurement_id' => $ingredient->measurement_id
                        ])->active()->first();

                        if(isset($measurement_conversion->value))
                        {
                           $quantity = ((float) $measurement_conversion->value * $product_ingredient->quantity) * $requested_product_quantity;
                        }
                        else
                        {
                            $quantity = ((float)$product_ingredient->quantity) * $requested_product_quantity;
                        }
                        if ($ingredient->quantity < $quantity && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                    }
                }
            }
        }

        return $low_ingredient_stock;
    }

    /* 
        to check whether a store is opened or closed
    */
    public function getStoreStatus()
    {

        $status = false;
        $message = '';

        if (isset(request()->logged_store_opening_time) && isset(request()->logged_store_closing_time)) {

            $now = Carbon::now()->format('Y-m-d H:i:s');
            if ($now >= request()->logged_store_opening_time && $now <= request()->logged_store_closing_time) {
                $status = true;
            } else {
                $status = false;
                $message = 'Sorry! You can"t create order out of your store"s opening and closing time';
            }
        } else {
            $status = false;
            $message = 'Oops! Please set your store"s opening and closing timings from store setting';
        }

        return ['status' => $status, 'message' => $message];
    }

    public function getNextOrderNumber()
    {

        $now = Carbon::now()->format('Y-m-d H:i:s');

        if ($now >= request()->logged_store_opening_time && $now <= request()->logged_store_closing_time) {

            $order_value_date = new OrderAPI();
            $order_value_date = $order_value_date->get_order_value_date();

            $last_order_number = OrderModel::where('store_id', request()->logged_user_store_id)->where('value_date', $order_value_date)->orderBy('id', 'DESC')->select('order_number')->first();
            if (isset($last_order_number)) {
                $next_order_number = (int) $last_order_number->order_number + 1;
            } else {
                $next_order_number = 1;
            }
        } else {

            $data['menu_key'] = 'MM_ORDERS';
            $data['sub_menu_key'] = 'SM_POS_ORDERS';
            $data['action_key'] = 'A_ADD_ORDER';
            check_access(array($data['action_key']));

            $data['message']  = "You are not allowed to create orders out of store's opening and closing time ";
            $data['store_slack'] = request()->logged_user_store_slack;
            return view('message', $data);
        }

        return $next_order_number;
    }


    public function getNextInvoiceNumber()
    {
        $last_invoice_number = InvoiceModel::orderBy('id', 'DESC')->select('*')->first();
        if (isset($last_invoice_number)) {
            $next_invoice_number =   (int) $last_invoice_number->invoice_number + 1;
        } else {
            $next_invoice_number = 1;
        }
        return $next_invoice_number;
    }


    public function getLastInvoiceNumber()
    {
        $last_invoice_number = InvoiceModel::orderBy('id', 'DESC')->select('*')->first();
        if (isset($last_invoice_number)) {
            $next_invoice_number =   (int) $last_invoice_number->invoice_number;
        } else {
            $next_invoice_number = 0;
        }
        return $next_invoice_number;
    }

    public function getLastQuotationNumber()
    {
        $last_quotation_number = QuotationModel::orderBy('id', 'DESC')->select('*')->first();
        if (isset($last_quotation_number)) {
            $next_quotation_number =   (int) $last_quotation_number->quotation_number;
        } else {
            $next_quotation_number = 0;
        }
        return $next_quotation_number;
    }


    public function getNextReturnInvoiceNumber()
    {
        $last_invoice_number = InvoiceReturnModel::orderBy('id', 'DESC')->select('*')->first();
        if (isset($last_invoice_number)) {
            $next_invoice_number =   (int) $last_invoice_number->return_invoice_number + 1;
        } else {
            $next_invoice_number = 1;
        }
        return $next_invoice_number;
    }


    public function getLastReturnInvoiceNumber()
    {
        $last_invoice_number = InvoiceReturnModel::orderBy('id', 'DESC')->select('*')->first();
        if (isset($last_invoice_number)) {
            $next_invoice_number =   (int) $last_invoice_number->return_invoice_number;
        } else {
            $next_invoice_number = 0;
        }
        return $next_invoice_number;
    }

    public function getMerchantUrl()
    {

        $host =  $_SERVER['HTTP_HOST'];
        $urls = explode(".", $host);
        return $urls[0];
    }

    public function addQuantityHistory($slack,$product_id,$store_id,$type,$action,$quantity,$table_id){
        $data = [
            'slack' => $slack,
            'product_id' => $product_id,
            'store_id' => $store_id,
            'type' => $type,
            'action' => $action,
            'quantity' => $quantity,
            'table_id' => $table_id,
            'date' => Carbon::now()->format('Y-m-d'),
            'created_by' => request()->logged_user_id
        ];
        QuantityHistory::create($data);
    }

    public function prepare_express_pay_message($invoice_no,$amount,$pdf_link,$payment_link){
        $template = SettingApp::select('expresspay_sms_template')->where('id',1)->first();
        if(isset($template) && $template->expresspay_sms_template!=''){
            $template = str_replace("[INVOICE_NUMBER]",$invoice_no,$template->expresspay_sms_template);
            $template = str_replace("[AMOUNT]",$amount,$template);
            $template = str_replace("[INVOICE_LINK]",$pdf_link,$template);
            $template = str_replace("[PAYMENT_LINK]",$payment_link,$template);
        }else{
            $template = "Thank you for shopping!  Invoice number: ".$invoice_no." Amount: ".$amount."SAR, Link to view your bill: ".$pdf_link." Payment link: ".$payment_link;
        }
        return $template;
    }
    public function sendSMS($number, $message){
        $client = new Client;
        $url = "https://www.4jawaly.net/api/sendsms.php?username=".env('SMS_USERNAME')."&password=".env('SMS_PASSWORD')."&message=".$message."&numbers=".$number."&sender=WOSUL&unicode=e&Rmduplicated=1&return=xml";
        $response = $client->request('GET', $url);
        $code =  $response->getStatusCode();

        if ($code == 200 || $code == 201) {
            $result = array('code'=>$code,'status'=>true);
        } else {
            $result = array('code'=>$code,'status'=>false);
        }
        return $result;
    }

    public function getRandomString($length = 16)
    {
        $stringSpace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($stringSpace, '8bit') - 1;
        for ($i = 0; $i < $length; ++ $i) {
            $pieces[] = $stringSpace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
