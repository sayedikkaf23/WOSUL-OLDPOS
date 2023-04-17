<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use App\Models\Product as ProductModel;
use App\Http\Resources\ProductResource;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;

trait BonatApiTrait
{


    public  $params;

    public function define_bonat_headers()
    {

        $this->params['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /* Verify bonat merchant */

    public function verify_bonat_merchant($url, $bonat_merchant_id)
    {

        $this->define_bonat_headers();

        $api_url = $url;


        $client = new Client;

        $this->params['headers']['auth'] = $bonat_merchant_id;

        $response = $client->request('GET', $api_url, $this->params);

        $result = $response->getBody()->getContents();

        $result = json_decode($result);

        $code = $result->code;

        if ($code == 0) {
            $status = true;
        } else {
            $status = false;
        }

        return $status;
    }


    public function send_order_details($url, $order_data)
    {

        $status = false;
        $this->define_bonat_headers();
        $api_url = $url;
        $client = new Client;

        $body = [
            'idOrder' => $order_data['order_id'],
            'idBranch' => $order_data['store_slack'],
            'idDevice' => $order_data['counter_slack'],
            'timestamp' => Carbon::now()->toDateTimeString(),
            'idMerchant' => $order_data['merchant_slack'],
            'total' => $order_data['total_after_discount']
        ];

        $response =  $client->request('POST', $api_url, [
            'form_params' => $body,
            'http_errors' => false,
        ]);

        $status_code_details = $response->getStatusCode();
        if ($status_code_details == 200) {

            $result = $response->getBody()->getContents();
            $result = json_decode($result);

            $code = $result->code;

            if ($code == 0) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            return false;
        }

        return $status;
    }


    public function verify_coupon($url, $data)
    {


        $this->define_bonat_headers();
        $api_url = $url.'/webhook/wosul/pos/v1/coupon/verifyCoupon';
        $client = new Client;

        $coupon = $data['coupon'];
        $body = [
            'idCoupon' => $data['coupon'],
            'idMerchant' => $data['merchant_id'],
            'idBranch' => $data['branch'],
            'idDevice' =>  $data['device_id'],
        ];


        $response =  $client->request('POST', $api_url, [
            'form_params' => $body,
            'http_errors' => false,
            'verify' => true,
        ]);


        $status_code_details = $response->getStatusCode();
        if ($status_code_details == 200) {
            $result = $response->getBody()->getContents();

            $result = json_decode($result);
            $code = $result->code;

            if ($code == 0) {
                $product_array = $result->data->idProduct;
                $price = $result->data->price;
              
                $product_array = json_decode($product_array);
              
                $product_slacks = $product_array;

                $products_data = array();
                $data['products_data'] = array();
                $products_data = $query = ProductModel::select('products.*')->with('product_modifiers')->categoryJoin()->whereIn('products.slack', $product_slacks)
                    ->supplierJoin()
                    ->Active()
                    ->categoryActive()
                    ->productTypePos()
                    ->mainProduct();


                $products_data = $products_data->get();

                $products_data = ProductResource::collection($products_data);


                $data['products_data'] = null;
                if (!empty($products_data)) {

                    foreach ($products_data as $product) {

                        $dataset = [];
                        $dataset = $product;
                        $requested_product_quantity = 1;

                        if ($this->__check_ingredient_stock($product->id, $requested_product_quantity)) {
                            $dataset['ingredient_low_stock'] = 1;
                        } else {
                            $dataset['ingredient_low_stock'] = 0;
                        }

                        $dataset['sale_amount_excluding_tax'] = isset($price) ? $price : 0;
                        $dataset['sale_amount_including_tax'] = isset($price) ? $price : 0;
                        $dataset['bonat_discount'] = TRUE;
                        $dataset['bonat_price'] = isset($price) ? $price : 0;
                        $dataset['bonat_coupon'] = isset($coupon) ? $coupon :null;
                        $data['products_data'][] = $dataset;
                    }
                }

                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function set_use_coupon($url, $order_data)
    {
 
        $status = false;
        $this->define_bonat_headers();
        $api_url = $url.'/webhook/wosul/pos/v1/coupon/setUseCoupon';

        $client = new Client;

        $body = [
            'idCoupon' => $order_data['coupon_code'],
            'idMerchant' => $order_data['merchant_slack'],
            'idBranch' => $order_data['store_slack'],
            'idDevice' =>  $order_data['counter_slack'],
        ];

        $response =  $client->request('POST', $api_url, [
            'form_params' => $body,
            'http_errors' => false,
            'verify' => true,
        ]);

        $status_code_details = $response->getStatusCode();

        if ($status_code_details == 200) {
            $result = $response->getBody()->getContents();

            $result = json_decode($result);
            $code = $result->code;


            if ($code == 0) {
                $status = true;
            } else {
                $status = false;
            }

        }
        else {
            $status = false;
        }

        
        return $status;
    }

    private function __check_ingredient_stock($product_id, $requested_product_quantity)
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
}
