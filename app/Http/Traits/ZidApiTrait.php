<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;

// Models
use App\Models\Store as StoreModel;
use App\Models\ZidStore as ZidStoreModel;
use App\Models\Product as ProductModel;
use App\Models\Category as CategoryModel;

use Illuminate\Support\Facades\App;
use GuzzleHttp\Psr7;
use PhpParser\JsonDecoder;

// use GuzzleHttp\Exception;
trait ZidApiTrait
{

    public $auth_token, $params;

    public function define_headers()
    {

        $zid_store = ZidStoreModel::first();

        $this->params['headers'] = [
            'Accept' => 'application/json',
            'X-MANAGER-TOKEN' => $zid_store->access_token,
            'STORE-ID' => $zid_store->zid_store_id,
            'Authorization' => 'Bearer ' . $zid_store->authorization,
            'Accept-Language' => 'ar',
        ];
    }

    /* To Adding Category and Subcategories into Zid Using ZID API */

    public function sync_zid_add_category($form_params)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/managers/store/categories/add';

        $client = new \GuzzleHttp\Client();

        $this->params['form_params'] = $form_params;

        try {

            $response = $client->post($api_url, $this->params);

            if ($response->getStatusCode() == 200) {

                return [
                    'status' => true,
                    'message' => 'Zid Category Created Successfully',
                    'data' => [],
                ];
            }

            // } catch (RequestException $e) {
        } catch (\GuzzleHttp\Exception $e) {

            return [
                'status' => false,
                'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
            ];
        }
    }

    /* To get list of all the categories and subcategories of Zid Using ZID API */

    public function sync_zid_get_all_categories()
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/managers/store/categories';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $api_url, $this->params);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }

    /* To get list of all the products from ZID Platform */

    public function sync_zid_get_all_products()
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/products/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['Accept-Language'] = 'en';

        $response = $client->request('GET', $api_url, $this->params);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }

    /* To get product detail based on product id from ZID Platform */

    public function sync_zid_get_product_detail($zid_product_id)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/products/' . $zid_product_id . '/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['Accept-Language'] = 'en';
        $this->params['headers']['Role'] = 'Manager';

        $response = $client->request('GET', $api_url, $this->params);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }

    /* To add a new product From Wosul Using ZID API */

    public function sync_zid_add_product($form_params)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/products/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['ROLE'] = "Manager";

        try {

            $response = $client->post($api_url, [
                'headers' => $this->params['headers'],
                'json' => $form_params,
            ]);

            if ($response->getStatusCode() == 201) {

                $product = json_decode($response->getBody()->getContents(), true);

                return [
                    'status' => true,
                    'message' => 'Zid Product Created Successfully',
                    'data' => ['product_id' => $product['id']],
                ];
            }
        } catch (RequestException $e) {

            return [
                'status' => false,
                'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
            ];
        }
    }

    /* To add a new product From Wosul Using ZID API */

    public function sync_zid_update_product($form_params, $product_id)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/products/' . $product_id . '/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['ROLE'] = "Manager";

        $response = $client->patch($api_url, [
            'headers' => $this->params['headers'],
            'json' => $form_params,
        ]);

        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }

    /* To link a product to particular category */

    public function sync_zid_assign_category_to_product($category, $product_id)
    {

        $this->define_headers();

        $category_id = $this->sync_zid_find_category_id($category);

        $api_url = env('ZID_ENDPOINT') . '/products/' . $product_id . '/categories/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['ROLE'] = "Manager";
        $this->params['headers']['Product-Id'] = $product_id;

        try {

            $response = $client->post($api_url, [
                'headers' => $this->params['headers'],
                'json' => [
                    'id' => $category_id
                ],
            ]);

            if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {

                return [
                    'status' => true,
                    'message' => 'Zid Product and Category Linked Successfully',
                    'data' => [],
                ];
            }
        } catch (RequestException $e) {

            return [
                'status' => false,
                'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
            ];
        }
    }

    /* Delete Product */

    public function sync_zid_delete_product($product_id)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/products/' . $product_id . '/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['ROLE'] = "Manager";

        $response = $client->delete($api_url, [
            'headers' => $this->params['headers'],
        ]);

        if ($response->getStatusCode() == 204) {
            return true;
        } else {
            return false;
        }
    }

    /* Delete Product */

    public function sync_zid_delete_category_product($product_id)
    {

        // get category id associated with product

        $this->define_headers();

        $product_category_id = ProductModel::where('zid_product_id', $product_id)->first()->category_id;
        $category = CategoryModel::find($product_category_id)->toArray();
        $category_id = $this->sync_zid_find_category_id($category);

        $api_url = env('ZID_ENDPOINT') . '/products/' . $product_id . '/categories/' . $category_id . '/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers']['ROLE'] = "Manager";

        $response = $client->delete($api_url, [
            'headers' => $this->params['headers'],
        ]);

        if ($response->getStatusCode() == 204) {
            return true;
        } else {
            return false;
        }
    }

    public function sync_zid_find_category_id($category)
    {

        $category_id = 0;

        $category_type = ($category['parent'] == 0) ? 1 : 2;
        $zid_categories = $this->sync_zid_get_all_categories();

        if (!empty($zid_categories)) {
            foreach ($zid_categories['categories'] as $zid_category) {

                if ($category_type == 2) {

                    if (isset($zid_category['sub_categories']) && count($zid_category['sub_categories']) > 0) {
                        foreach ($zid_category['sub_categories'] as $zid_sub_category) {

                            if ($zid_sub_category['names']['en'] != '' && $category['label'] == $zid_sub_category['names']['en']) {
                                $category_id = $zid_sub_category['id'];
                            }
                            if ($zid_sub_category['names']['ar'] != '' && $category['label_ar'] == $zid_sub_category['names']['ar']) {
                                $category_id = $zid_sub_category['id'];
                            }
                        }
                    }
                } else {

                    if ($zid_category['names']['en'] != '' && $category['label'] == $zid_category['names']['en']) {
                        $category_id = $zid_category['id'];
                    }
                    if ($zid_category['names']['ar'] != '' && $category['label_ar'] == $zid_category['names']['ar']) {
                        $category_id = $zid_category['id'];
                    }
                }
            }
        }

        return $category_id;
    }

    /* Update Category */

    public function sync_zid_update_category($form_params, $zid_category_id)
    {

        $this->define_headers();

        $api_url = env('ZID_ENDPOINT') . '/managers/store/categories/' . $zid_category_id . '/update';

        $client = new \GuzzleHttp\Client();

        $form_params['_method'] = "put";

        $response = $client->post($api_url, [
            'headers' => $this->params['headers'],
            'form_params' => $form_params,
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }

    /* check whether zid is enabled or not */

    public function check_zid_status()
    {
        $zid_store = ZidStoreModel::first();
        if (isset($zid_store)) {
            return true;
        } else {
            return false;
        }
    }

    /* To get product detail based on product id from ZID Platform */

    public function sync_zid_get_profile($access_token, $authorization)
    {

        $api_url = env('ZID_ENDPOINT') . '/managers/account/profile/';

        $client = new \GuzzleHttp\Client();

        $this->params['headers'] = [
            'Accept' => 'application/json',
            'X-MANAGER-TOKEN' => $access_token,
            'Authorization' => 'Bearer ' . $authorization,
            'Accept-Language' => 'en',
        ];

        $response = $client->request('GET', $api_url, $this->params);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return json_decode($response->getReasonPhrase(), true);
        }
    }
}
