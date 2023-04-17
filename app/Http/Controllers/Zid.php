<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ZidAction as ZidActionModel;
use App\Models\ZidActivity as ZidActivityModel;
use App\Models\ZidConfig as ZidConfigModel;
use App\Models\ZidStore as ZidStoreModel;
use App\Models\Store as StoreModel;
use GuzzleHttp\Client;

use App\Models\Product as ProductModel;
use App\Http\Traits\CommonApiTrait;
use App\Http\Traits\ZidApiTrait;
use Illuminate\Support\Facades\Config;

class Zid extends Controller
{

    use CommonApiTrait;
    use ZidApiTrait;

    // to list all the av4
    public function stores()
    {
        // check access
        $data['menu_key'] = 'MM_ZID';
        $data['sub_menu_key'] = 'SM_ZID_STORE';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['zid_store'] = ZidStoreModel::get();
        $data['redirection_url'] = env('APP_URL') . "/oauth/redirect";

        return view('zid.stores', $data);
    }

    //This is the function that loads the listing page
    public function oauthRedirect()
    {
        $queries = http_build_query([
            'client_id' => env('ZID_CLIENT_ID'),
            'redirect_uri' =>  env('APP_URL') . '/oauth/callback',
            'response_type' => 'code'
        ]);

        return redirect('https://oauth.zid.sa' . '/oauth/authorize?' . $queries);
    }

    public function oauthCallback()
    {

        dd(0);
        $urls =  explode(".", $_SERVER['HTTP_REFERER'])[0];
        $merchant_db = explode('://', $urls)[1] . "_wosul";
        $client_data = ZidConfigModel::first();

        $client = new Client();
        $params['form_params'] = array(
            'grant_type' => 'authorization_code',
            'client_id' => $client_data->client_id,
            'client_secret' => $client_data->client_secret,
            'redirect_uri' => env('APP_URL') . '/oauth/callback',
            'code' => \request()->query('code')
        );

        $response = $client->post('https://oauth.zid.sa/oauth/token', $params);

        if ($response->getStatusCode() == 200) {

            $response_body = json_decode($response->getBody(), true);
            // fetching zid store id 
            $zid_profile = $this->sync_zid_get_profile($response_body['access_token'], $response_body['authorization']);

            $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);
            $query = 'INSERT INTO zid_store(zid_store_id,authorization,access_token,expires_in) VALUES(
                "' . $zid_profile['user']['store']['id'] . '",
                "' . $response_body['authorization'] . '",
                "' . $response_body['access_token'] . '",
                "' . $response_body['expires_in'] . '"
            )';
            mysqli_query($connect, $query);
            mysqli_close($connect);

            return redirect()->back()->withSuccess('Store Configured Successully');
        } else {
            return redirect()->back()->withError('Something went wrong with configuration');
        }
    }



    //This is the function that loads the listing page
    public function action(Request $request)
    {
        // check access
        $data['menu_key'] = 'MM_ZID';
        $data['sub_menu_key'] = 'SM_SYNC_ZID';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['actions'] = ZidActionModel::with('last_sync_at')->oldest()->get();

        $data['sync_history'] = ZidActivityModel::select('remark', 'created_at')->latest()->get();

        return view('zid.action', $data);
    }

    public function order_create_webhook(Request $request)
    {

        $zid_store_id = $request->store_id;
        $zid_products = $request->products;

        if (isset($zid_products) && count($zid_products) > 0) {

            $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), 'wosul_admin');
            $merchant_stores = "SELECT m.company_url FROM merchants m INNER JOIN merchant_zid_stores mzs ON m.id = mzs.merchant_id WHERE mzs.zid_store_id = '" . $zid_store_id . "' ORDER BY mzs.id LIMIT 1";
            $merchant_stores = mysqli_query($connect, $merchant_stores);
            $company_url = "";
            if (mysqli_num_rows($merchant_stores) > 0) {
                while ($row = mysqli_fetch_assoc($merchant_stores)) {
                    $company_url = $row['company_url'];
                }
            }

            if (isset($company_url) && $company_url != "") {

                $merchant_db = $company_url . "_wosul";
                $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);

                $zid_store_api_token = "SELECT zid_store_api_token FROM stores WHERE zid_store_id = '" . $zid_store_id . "' LIMIT 1 ";
                $zid_store_api_token = mysqli_query($connect, $zid_store_api_token);
                $zid_store_api_token = mysqli_fetch_assoc($zid_store_api_token);
                $zid_store_api_token = $zid_store_api_token['zid_store_api_token'];

                $zid_store = ZidStoreModel::where('store_id', request()->logged_user_store_id)->first();
                $auth_token = 'Bearer ' . $zid_store->authorization;
                $params['headers'] = [
                    'Accept' => 'application/json',
                    'Authorization' => $auth_token,
                    'Accept-Language' => 'ar',
                ];

                foreach ($zid_products as $product) {

                    // logger("Zid Product:".$product);

                    $select_product = "SELECT id,zid_product_id FROM products WHERE REPLACE(zid_product_id,'-','') = '" . $product['id'] . "' ORDER BY id DESC LIMIT 1 ";
                    // $select_product = "SELECT id,zid_product_id FROM products WHERE zid_product_id = '".$product['id']."' ORDER BY id DESC LIMIT 1 ";
                    // logger("Zid Product Id: ".$product['id']);
                    $select_product = mysqli_query($connect, $select_product);

                    if (mysqli_num_rows($select_product) > 0) {

                        $select_product = mysqli_fetch_assoc($select_product);
                        $zid_product_id_dashed = $select_product['zid_product_id'];
                        $product_id = $select_product['id'];

                        $api_url = 'https://api.zid.dev/app/v2/products/' . $zid_product_id_dashed . '/';
                        $client = new \GuzzleHttp\Client();

                        // get updated product detail
                        $params['headers']['X-MANAGER-TOKEN'] = $zid_store_api_token;
                        $params['headers']['STORE-ID'] = $zid_store_id;
                        $params['headers']['Accept-Language'] = 'en';
                        $params['headers']['Role'] = 'Manager';

                        $response = $client->request('GET', $api_url, $params);

                        if ($response->getStatusCode() == 200) {
                            $product_detail = $response->getBody()->getContents();
                            $product_detail = json_decode($product_detail, true);

                            // logger($product_detail['quantity']);
                            $update_product_quantity = "UPDATE products SET quantity = '" . $product_detail['quantity'] . "' WHERE id = '" . $product_id . "' ";
                            // logger($update_product_quantity);
                            mysqli_query($connect, $update_product_quantity);
                        } else {
                            logger("Zid Order Create Webhook Error: " . $response->getReasonPhrase());
                        }
                    }

                    // if product is variant then update the parent's quantity 
                    if ($product['parent_id'] != null) {

                        $select_product = "SELECT id,zid_product_id FROM products WHERE REPLACE(zid_product_id,'-','') = '" . $product['parent_id'] . "' ORDER BY id DESC LIMIT 1 ";
                        // $select_product = "SELECT id,zid_product_id FROM products WHERE zid_product_id = '".$product['id']."' ORDER BY id DESC LIMIT 1 ";
                        // logger("Zid Product Id: ".$product['id']);
                        $select_product = mysqli_query($connect, $select_product);

                        if (mysqli_num_rows($select_product) > 0) {

                            $select_product = mysqli_fetch_assoc($select_product);
                            $zid_product_id_dashed = $select_product['zid_product_id'];
                            $product_id = $select_product['id'];

                            $api_url = 'https://api.zid.dev/app/v2/products/' . $zid_product_id_dashed . '/';
                            $client = new \GuzzleHttp\Client();

                            // get updated product detail
                            $params['headers']['X-MANAGER-TOKEN'] = $zid_store_api_token;
                            $params['headers']['STORE-ID'] = $zid_store_id;
                            $params['headers']['Accept-Language'] = 'en';
                            $params['headers']['Role'] = 'Manager';

                            $response = $client->request('GET', $api_url, $params);

                            if ($response->getStatusCode() == 200) {
                                $product_detail = $response->getBody()->getContents();
                                $product_detail = json_decode($product_detail, true);

                                // logger($product_detail['quantity']);
                                $update_product_quantity = "UPDATE products SET quantity = '" . $product_detail['quantity'] . "' WHERE id = '" . $product_id . "' ";
                                // logger($update_product_quantity);
                                mysqli_query($connect, $update_product_quantity);
                            } else {
                                logger("Zid Order Create Webhook Error While Updating Parent: " . $response->getReasonPhrase());
                            }
                        }
                    }
                }
            }
        }
    }
}
