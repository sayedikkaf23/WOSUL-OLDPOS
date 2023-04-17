<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\MainCategoryResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Category as CategoryModel;
use App\Models\Store as StoreModel;
use App\Models\Product as ProductModel;
use App\Models\ZidActivity as ZidActivityModel;
use App\Models\ZidAction as ZidActionModel;
use App\Models\ZidConfig as ZidConfigModel;
use App\Models\ZidStore as ZidStoreModel;

use App\Http\Resources\Collections\CategoryCollection;

use App\Http\Traits\ZidApiTrait;
use App\Models\ZidConfig;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Carbon;
use GuzzleHttp\Client;

class Zid extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ZidApiTrait;

    public function sync(Request $request)
    {

        try {

            // fetch categories
            $categories = $this->sync_zid_get_all_categories();

            // sync categories
            if (isset($categories)) {
                foreach ($categories['categories'] as $category) {
                    $this->__update_or_create_category($category, $request);
                }
            }

            // fetch products
            $products = $this->sync_zid_get_all_products();

            // sync products
            if (isset($products) && count($products) > 0) {
                foreach ($products['results'] as $product) {
                    $this->__update_or_create_product($product, $request);
                }
            }

            $zid_action = ZidActionModel::where('key', 'synczid_product')->first();

            ZidActivityModel::create([
                'zid_action_id' => $zid_action->id,
                'store_id' => $request->logged_user_store_id,
                'remark' => 'Product & Categories are Synced with ZID',
                'created_by' => $request->logged_user_id,
                'created_at' => Carbon::now()
            ]);

            $response = [
                'status' => 1,
                'msg' => 'Product Sync Done'
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

    private function __update_or_create_product($product, $request, $is_parent = true)
    {

        if ($product['name'] != '') {

            $category_id = '';
            if ($is_parent && isset($product['categories'])) {

                if (isset($product['categories'][0])) {
                    $zid_product_category_id = $product['categories'][0]['id'];
                    $product_category = CategoryModel::where('zid_category_id', $zid_product_category_id)->first();
                    if (isset($product_category)) {
                        $category_id = $product_category->id;
                    }
                }
            } else {

                $parent_product = ProductModel::where('zid_product_id', $product['parent_id'])->first();
                if (isset($parent_product)) {
                    $category_id = $parent_product->category_id;
                }
            }

            // sync product
            $product_id =  ProductModel::updateOrCreate(
                [
                    'zid_product_id' => $product['id']
                ],
                [
                    "slack" => $this->generate_slack("products"),
                    "store_id" => $request->logged_user_store_id,
                    "name" => $product['name'],
                    "name_ar" => (isset($product['name']['ar'])) ? $product['name']['ar'] : '',
                    "description" => (isset($product['seo'])) ? $product['seo']['description'] : '',
                    "category_id" => $category_id,
                    "purchase_amount_excluding_tax" => $product['price'],
                    "sale_amount_excluding_tax" => (!is_null($product['sale_price'])) ? $product['sale_price'] : $product['price'],
                    "sale_amount_including_tax" => (!is_null($product['sale_price'])) ? $product['sale_price'] : $product['price'],
                    "shows_in" => '3',
                    "created_by" => $request->logged_user_id,
                    "quantity" => ($product['is_infinite']) ? -1 : $product['quantity'],
                    "sales_price_including_boolean_and_price" => (!is_null($product['sale_price'])) ? $product['sale_price'] : $product['price'],
                    'zid_product_id' => $product['id'],
                    'zid_parent_product_id' => (!is_null($product['parent_id'])) ? $product['parent_id'] : null,
                    'status' => (!$product['is_draft']) ? 1 : 2,
                ]
            );

            if (!isset($product['parent_id']) || $product['parent_id'] = '') {

                // sync product variant (as separate product)
                $product_detail = $this->sync_zid_get_product_detail($product['id']);
                if (isset($product_detail['variants']) && count($product_detail['variants']) > 0) {

                    foreach ($product_detail['variants'] as $product_variant) {
                        $this->__update_or_create_product($product_variant, $request, false);
                    }
                }
            }
        }

        return true;
    }

    private function __update_or_create_category($category, $request, $parent_category_id = 0)
    {

        // sync category
        $new_category = CategoryModel::updateOrCreate(
            [
                'zid_category_id' => $category['id']
            ],
            [
                "slack" => $this->generate_slack("category"),
                "parent" => ($parent_category_id > 0) ? $parent_category_id : null,
                "store_id" => $request->logged_user_store_id,
                "category_code" => Str::random(6),
                "label" => $category['names']['en'],
                "label_ar" => $category['names']['ar'],
                "description" => $request->description,
                "created_by" => $request->logged_user_id,
                "zid_parent_category_id" => (!is_null($category['parent_id'])) ? $category['parent_id'] : null,
                'status' => ($category['is_published']) ? 1 : 2,
            ]
        );


        if (is_null($category['parent_id']) && isset($category['sub_categories'])) {

            // sync sub category
            foreach ($category['sub_categories'] as $sub_category) {

                $this->__update_or_create_category($sub_category, $request, $new_category['id']);
            }
        }
    }
}
