<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Http\Traits\ZidApiTrait;
use App\Models\ProductIngredient;
use Illuminate\Support\Facades\DB;
use App\Models\Brand as BrandModel;

use App\Models\QuantityAdjustments;
use App\Models\Store as StoreModel;
use App\Models\ProductBarcodeDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ProductResource;
use App\Models\Country as CountryModel;
use App\Models\Product as ProductModel;
use App\Models\QuantityAdjustmentItems;
use App\Models\Taxcode as TaxcodeModel;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Models\Category as CategoryModel;
use App\Models\Modifier as ModifierModel;
use App\Models\Supplier as SupplierModel;
use App\Http\Resources\StockTransferResource;
use App\Models\Measurement as MeasurementModel;
use App\Models\TaxcodeType as TaxcodeTypeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Http\Resources\QuantityAdjustmentResource;
use App\Models\StockTransfer as StockTransferModel;
use App\Http\Resources\ProductBarcodeDetailResource;
use App\Http\Resources\StockTransferProductResource;
use App\Http\Resources\QuantityAdjustmentItemsResource;
use App\Models\MeasurementUnit as MeasurementUnitModel;
use App\Models\ProductModifier as ProductModifierModel;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\Price;
use App\Models\StockTransferProduct as StockTransferProductModel;
use Twilio\TwiML\Voice\Echo_;

class Product extends Controller
{
    use ZidApiTrait;

    //This is the function that loads the listing page
    public function index(Request $request)
    {

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCTS';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['restaurant_mode'] = $request->logged_user_store_restaurant_mode;

        $data['main_categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label')->parentCategory()->sortLabelAsc()->active()->get();

        $data['categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label')->childCategory()->sortLabelAsc()->active()->get();

        $data['taxcodes'] = TaxcodeModel::select('id','slack', 'tax_code', 'label','total_tax_percentage')->sortLabelAsc()->active()->get();

        return view('product.products', $data);
    }

    //This is the function that loads the add/edit page
    public function add_product($slack = null)
    {
        
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCTS';
        $data['action_key'] = ($slack == null) ? 'A_ADD_PRODUCT' : 'A_EDIT_PRODUCT';
        check_access(array($data['action_key']));

        $current_route = Route::currentRouteName();
        $data['stock_transfer_data'] = null;
        $productList = ProductModel::where("is_ingredient",0)->active()->get();
          foreach($productList as $product)
          {
            if($product->product_thumb_image)
            {
               $product->product_thumb_image = '<img src="'.Storage::disk("product")->url($product->product_thumb_image).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $product->product_thumb_image = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
        $data['product_list'] = $productList;
        $data['category_list'] = CategoryModel::active()->get();
        $data['stock_transfer_product_data'] = null;
        if ($current_route == "add_new_stock_transfer_product") {

            $current_selected_store = request()->logged_user_store_id;
            $stock_transfer_product_slack = $slack;
            $slack = null;

            //get stock transfer product details
            $stock_transfer_product_details = StockTransferProductModel::where('slack', $stock_transfer_product_slack)->verifiable()->first();
            if (empty($stock_transfer_product_details)) {
                abort(404);
            }
            $stock_transfer_product_details = new StockTransferProductResource($stock_transfer_product_details);

            $stock_transfer_details = StockTransferModel::withoutGlobalScopes()->where('id', $stock_transfer_product_details->stock_transfer_id)->resolveStore($current_selected_store)->first();
            if (empty($stock_transfer_details)) {
                abort(404);
            }
            $stock_transfer_details = new StockTransferResource($stock_transfer_details);

            $data['stock_transfer_data'] = $stock_transfer_details;
            $data['stock_transfer_product_data'] = $stock_transfer_product_details;
        }

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('PRODUCT_STATUS')->active()->sortValueAsc()->get();


        $data['country_list'] = CountryModel::select('id as country_id', 'name', 'code')
        ->active()
        ->groupBy('code')
        ->get();

        $data['suppliers'] = SupplierModel::select('slack', 'supplier_code', 'name')->sortNameAsc()->active()->get();

        $data['main_categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label', 'category_applied_on', 'category_applicable_stores')->parentCategory()->categoryStore()->sortLabelAsc()->active()->get();

        $data['categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label')->childCategory()->sortLabelAsc()->active()->get();

        $data['taxcodes'] = TaxcodeModel::select('id','slack', 'tax_code', 'label','total_tax_percentage')->sortLabelAsc()->active()->get();
        
        $currentdate = date('Y-m-d H:i:sa');
        $data['discount_codes'] = DiscountcodeModel::select('*')
                                ->whereRaw("discounttype!='cashier'")
                                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                                ->sortLabelAsc()->active()->get();
        $data['measurement_categories_data'] = MeasurementCategoryModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();

        $data['brands'] = BrandModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();

        $query = ProductModel::with('measurements')->select('products.*')
            // ->categoryJoin()
            ->supplierJoin()
            // ->taxcodeJoin()
            ->discountcodeJoin()
            //->categoryActive()
            // ->supplierActive()
            // ->taxcodeActive()
            ->quantityCheck()
            ->isIngredient();
        $data['ingredient_data'] = $query->get();

        $data['product_data'] = null;
        $data['main_category_id'] = null;
        $data['category_id'] = null;
        $data['subcategories'] = null;
        $data['is_ingredient'] = 0;
        $data['measurement_category_id'] = null;
        $data['measurements_data'] = MeasurementModel::singleValue()->active()->get();

        $data['modifiers'] = ModifierModel::active()->get();
        $data['product_modifiers_data'] = null;
        $data['sync_zid_product'] = false;

        if (check_access(['A_SYNC_ZID_PRODUCT'], true)) {
            $data['sync_zid_product'] = true;
        }

        $data['price_data'] = Price::where('store_id',request()->logged_user_store_id)
        ->select('id','slack','price_code')
        ->when(app()->getLocale() == 'ar', function($query){
            $query->addSelect('name_ar as name');
        })
        ->when(app()->getLocale() == 'en', function($query){
            $query->addSelect('name as name');
        })
        ->active()
        ->get();
        
        if (isset($slack)) {

            $product = ProductModel::with('measurements', 'ingredients')->where('products.slack', '=', $slack)->first();

            if (empty($product)) {
                abort(404);
            }

            $data['product_modifiers_data'] = ProductModifierModel::where('product_id', $product->id)->pluck('modifier_id');

            $product_data = new ProductResource($product);
            
            $data['product_data'] = $product_data;
            $data['product_data']['is_ingredient'] = $product->is_ingredient;
            $discountindex = 0;
            foreach($data['discount_codes'] as $discount_code){
                $discountindex+=1;
                if($discount_code->limit_on_discount==0){
                    unset($data['discount_codes'][$discountindex-1]);
                }
                if($discount_code->discount_applied_on=="specific_products")
                {
                    if(!in_array($product->id,explode(",",$discount_code->discount_applicable_products))){
                          unset($data['discount_codes'][$discountindex-1]);
                    }
                }
                else if($discount_code->discount_applied_on=="all_products_except_specific"){
                    if(in_array($product->id,explode(",",$discount_code->discount_not_applicable_products))){
                        unset($data['discount_codes'][$discountindex-1]);
                  }
                }
                else if($discount_code->discount_applied_on=="specific_product_categories"){
                    $discount_categories = explode(",",$discount_code->discount_applicable_categories);
                    foreach($discount_categories as $discount_category)
                    {
                      $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                      foreach($categories as $category) {
                        array_push($discount_categories,$category->id);
                      }
                    }
                    if(!in_array($product->category_id,$discount_categories)){
                        unset($data['discount_codes'][$discountindex-1]);
                  }
                }
                
            }
            if ($product->category_id > 0) {

                $category = CategoryModel::find($product->category_id);

                if (isset($product->measurements)) {
                    $data['measurement_category_id'] = $product->measurements->measurement_category_id;
                    $data['measurements_data'] = MeasurementModel::where('measurement_category_id', $data['measurement_category_id'])->get();
                }

                if ($category->parent > 0) {
                    $data['main_category_id'] = $category->parent;
                    $data['category_id'] = $category->id;
                    $data['subcategories'] = CategoryModel::where('parent', $category->parent)->active()->get();
                } else {
                    $data['main_category_id'] = $category->id;
                    $data['category_id'] = null;
                    $data['subcategories'] = CategoryModel::where('parent', $category->id)->active()->get();
                }
            }
        }
        $currentstoreid = request()->logged_user_store_id;
        if(request()->logged_user_id == 1)
        {
            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->oldest()->active()->get();
        }
        else
        {

            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->userStores()->oldest()->active()->get();
        }
        $data['selection_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->whereRaw("id!={$currentstoreid}")->active()->get();

        foreach($data['all_stores'] as $store)
          {
            if($store->store_logo)
            {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
          foreach($data['selection_stores'] as $store)
          {
            if($store->store_logo)
            {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
        $data['category_data'] = null;
        $data['category_stores'] = null;

        if(isset($category))
        {
            $category_data = new CategoryResource($category);
            $data['category_data'] = $category_data;            
            
            if(isset($category->mainCategory) && $category->mainCategory->category_applied_on == 'specific_stores')
            {            
                $stores = explode(',', $category->mainCategory->category_applicable_stores);
                $data['category_stores'] = StoreModel::whereIn('id', $stores)->select('id','name as text', 'store_logo')->oldest()->active()->get();
            }
            else
            {
                $data['category_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->userStores()->oldest()->active()->get();
            }        

        }

        $data['store_tax_slack'] = "";
        if (session('store_tax_code') != "" && (int)session('store_tax_code')>0) {
            $data['store_tax_slack'] = TaxcodeModel::withoutGlobalScopes()->where('id', session('store_tax_code'))->first()->slack;
        }
        $data['store_tax_percentage'] = "";
        if (session('store_tax_code') != "" && (int)session('store_tax_code')>0) {
            $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id', session('store_tax_code'))->first()->total_tax_percentage;
        }
        $data['store_tax_id'] = session('store_tax_code');
        $data['zid_status'] = $this->check_zid_status();
        $data['product_clone'] = 0;
        return view('product.add_product', $data);
    }

    public function clone_product($slack = null)
    {
        
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCTS';
        $data['action_key'] = ($slack == null) ? 'A_ADD_PRODUCT' : 'A_EDIT_PRODUCT';
        check_access(array($data['action_key']));

        $current_route = Route::currentRouteName();
        $data['stock_transfer_data'] = null;
        $productList = ProductModel::where("is_ingredient",0)->active()->get();
          foreach($productList as $product)
          {
            if($product->product_thumb_image)
            {
               $product->product_thumb_image = '<img src="'.Storage::disk("product")->url($product->product_thumb_image).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $product->product_thumb_image = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
        $data['product_list'] = $productList;
        $data['category_list'] = CategoryModel::active()->get();
        $data['stock_transfer_product_data'] = null;
        if ($current_route == "add_new_stock_transfer_product") {

            $current_selected_store = request()->logged_user_store_id;
            $stock_transfer_product_slack = $slack;
            $slack = null;

            //get stock transfer product details
            $stock_transfer_product_details = StockTransferProductModel::where('slack', $stock_transfer_product_slack)->verifiable()->first();
            if (empty($stock_transfer_product_details)) {
                abort(404);
            }
            $stock_transfer_product_details = new StockTransferProductResource($stock_transfer_product_details);

            $stock_transfer_details = StockTransferModel::withoutGlobalScopes()->where('id', $stock_transfer_product_details->stock_transfer_id)->resolveStore($current_selected_store)->first();
            if (empty($stock_transfer_details)) {
                abort(404);
            }
            $stock_transfer_details = new StockTransferResource($stock_transfer_details);

            $data['stock_transfer_data'] = $stock_transfer_details;
            $data['stock_transfer_product_data'] = $stock_transfer_product_details;
        }

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('PRODUCT_STATUS')->active()->sortValueAsc()->get();


        $data['country_list'] = CountryModel::select('id as country_id', 'name', 'code')
        ->active()
        ->groupBy('code')
        ->get();

        $data['suppliers'] = SupplierModel::select('slack', 'supplier_code', 'name')->sortNameAsc()->active()->get();

        $data['main_categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label', 'category_applied_on', 'category_applicable_stores')->parentCategory()->categoryStore()->sortLabelAsc()->active()->get();

        $data['categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label')->childCategory()->sortLabelAsc()->active()->get();

        $data['taxcodes'] = TaxcodeModel::select('id','slack', 'tax_code', 'label','total_tax_percentage')->sortLabelAsc()->active()->get();
        
        $currentdate = date('Y-m-d H:i:sa');
        $data['discount_codes'] = DiscountcodeModel::select('*')
                                ->whereRaw("discounttype!='cashier'")
                                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                                ->sortLabelAsc()->active()->get();
        $data['measurement_categories_data'] = MeasurementCategoryModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();

        $data['brands'] = BrandModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();

        $query = ProductModel::with('measurements')->select('products.*')
            // ->categoryJoin()
            ->supplierJoin()
            // ->taxcodeJoin()
            ->discountcodeJoin()
            //->categoryActive()
            // ->supplierActive()
            // ->taxcodeActive()
            ->quantityCheck()
            ->isIngredient();
        $data['ingredient_data'] = $query->get();

        $data['product_data'] = null;
        $data['main_category_id'] = null;
        $data['category_id'] = null;
        $data['subcategories'] = null;
        $data['is_ingredient'] = 0;
        $data['measurement_category_id'] = null;
        $data['measurements_data'] = MeasurementModel::singleValue()->active()->get();

        $data['modifiers'] = ModifierModel::active()->get();
        $data['product_modifiers_data'] = null;
        $data['sync_zid_product'] = false;

        if (check_access(['A_SYNC_ZID_PRODUCT'], true)) {
            $data['sync_zid_product'] = true;
        }

        $data['price_data'] = Price::where('store_id',request()->logged_user_store_id)
        ->select('id','slack','price_code')
        ->when(app()->getLocale() == 'ar', function($query){
            $query->addSelect('name_ar as name');
        })
        ->when(app()->getLocale() == 'en', function($query){
            $query->addSelect('name as name');
        })
        ->active()
        ->get();
        
        if (isset($slack)) {

            $product = ProductModel::with('measurements', 'ingredients')->where('products.slack', '=', $slack)->first();

            if (empty($product)) {
                abort(404);
            }

            $data['product_modifiers_data'] = ProductModifierModel::where('product_id', $product->id)->pluck('modifier_id');

            $product_data = new ProductResource($product);
            
            $data['product_data'] = $product_data;
            $data['product_data']['is_ingredient'] = $product->is_ingredient;
            $discountindex = 0;
            foreach($data['discount_codes'] as $discount_code){
                $discountindex+=1;
                if($discount_code->limit_on_discount==0){
                    unset($data['discount_codes'][$discountindex-1]);
                }
                if($discount_code->discount_applied_on=="specific_products")
                {
                    if(!in_array($product->id,explode(",",$discount_code->discount_applicable_products))){
                          unset($data['discount_codes'][$discountindex-1]);
                    }
                }
                else if($discount_code->discount_applied_on=="all_products_except_specific"){
                    if(in_array($product->id,explode(",",$discount_code->discount_not_applicable_products))){
                        unset($data['discount_codes'][$discountindex-1]);
                  }
                }
                else if($discount_code->discount_applied_on=="specific_product_categories"){
                    $discount_categories = explode(",",$discount_code->discount_applicable_categories);
                    foreach($discount_categories as $discount_category)
                    {
                      $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                      foreach($categories as $category) {
                        array_push($discount_categories,$category->id);
                      }
                    }
                    if(!in_array($product->category_id,$discount_categories)){
                        unset($data['discount_codes'][$discountindex-1]);
                  }
                }
                
            }
            if ($product->category_id > 0) {

                $category = CategoryModel::find($product->category_id);

                if (isset($product->measurements)) {
                    $data['measurement_category_id'] = $product->measurements->measurement_category_id;
                    $data['measurements_data'] = MeasurementModel::where('measurement_category_id', $data['measurement_category_id'])->get();
                }

                if ($category->parent > 0) {
                    $data['main_category_id'] = $category->parent;
                    $data['category_id'] = $category->id;
                    $data['subcategories'] = CategoryModel::where('parent', $category->parent)->active()->get();
                } else {
                    $data['main_category_id'] = $category->id;
                    $data['category_id'] = null;
                    $data['subcategories'] = CategoryModel::where('parent', $category->id)->active()->get();
                }
            }
        }
        $currentstoreid = request()->logged_user_store_id;
        if(request()->logged_user_id == 1)
        {
            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->oldest()->active()->get();
        }
        else
        {

            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->userStores()->oldest()->active()->get();
        }
        $data['selection_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->whereRaw("id!={$currentstoreid}")->active()->get();

        foreach($data['all_stores'] as $store)
          {
            if($store->store_logo)
            {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
          foreach($data['selection_stores'] as $store)
          {
            if($store->store_logo)
            {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
        $data['category_data'] = null;
        $data['category_stores'] = null;

        if(isset($category))
        {
            $category_data = new CategoryResource($category);
            $data['category_data'] = $category_data;            
            
            if(isset($category->mainCategory) && $category->mainCategory->category_applied_on == 'specific_stores')
            {            
                $stores = explode(',', $category->mainCategory->category_applicable_stores);
                $data['category_stores'] = StoreModel::whereIn('id', $stores)->select('id','name as text', 'store_logo')->oldest()->active()->get();
            }
            else
            {
                $data['category_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->userStores()->oldest()->active()->get();
            }        

        }

        $data['store_tax_slack'] = "";
        if (session('store_tax_code') != "" && (int)session('store_tax_code')>0) {
            $data['store_tax_slack'] = TaxcodeModel::withoutGlobalScopes()->where('id', session('store_tax_code'))->first()->slack;
        }
        $data['store_tax_percentage'] = "";
        if (session('store_tax_code') != "" && (int)session('store_tax_code')>0) {
            $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id', session('store_tax_code'))->first()->total_tax_percentage;
        }
        $data['store_tax_id'] = session('store_tax_code');
        $data['zid_status'] = $this->check_zid_status();
        $data['product_clone'] = 1;
        return view('product.add_product', $data);
    }

    public function add_ingredient($slack = null)
    {

        //check access
        $currentdate = date('Y-m-d H:i:sa');
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCTS';
        $data['action_key'] = ($slack == null) ? 'A_ADD_PRODUCT' : 'A_EDIT_PRODUCT';
        check_access(array($data['action_key']));

        $current_route = Route::currentRouteName();
        $data['stock_transfer_data'] = null;
        $data['stock_transfer_product_data'] = null;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('PRODUCT_STATUS')->active()->sortValueAsc()->get();

        $data['suppliers'] = SupplierModel::select('slack', 'supplier_code', 'name')->sortNameAsc()->active()->get();


        $data['main_categories'] = CategoryModel::select('id', 'category_code', 'label')->parentCategory()->sortLabelAsc()->active()->get();

        $data['categories'] = CategoryModel::select('id', 'slack', 'category_code', 'label')->childCategory()->sortLabelAsc()->active()->get();

        $data['taxcodes'] = TaxcodeModel::select('id','slack', 'tax_code', 'label')->sortLabelAsc()->active()->get();

        $data['discount_codes'] = DiscountcodeModel::select('*')
        ->whereRaw("discounttype!='cashier' ")
        ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
        ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
        ->sortLabelAsc()->active()->get();

        $data['measurements_data'] = MeasurementModel::singleValue()->active()->get();

        $data['measurement_categories_data'] = MeasurementCategoryModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();

        $data['brands'] = BrandModel::select('id','slack', 'label')->sortLabelAsc()->active()->get();

        $data['product_data'] = null;
        $data['main_category_id'] = null;
        $data['category_id'] = null;
        $data['subcategories'] = null;
        $data['measurement_category_id'] = null;

        if (isset($slack)) {

            $product = ProductModel::where('products.slack', '=', $slack)->first();

            if (empty($product)) {
                abort(404);
            }

            $product_data = new ProductResource($product);
            $data['product_data'] = $product_data;
            $category = CategoryModel::find($product->category_id);

            if (isset($product->measurements)) {
                $data['measurement_category_id'] = $product->measurements->measurement_category_id;
                $data['measurements_data'] = MeasurementModel::where('measurement_category_id', $data['measurement_category_id'])->get();
            }

            if ($category->parent > 0) {
                $data['main_category_id'] = $category->parent;
                $data['category_id'] = $category->id;
                $data['subcategories'] = CategoryModel::where('parent', $category->parent)->active()->get();
            } else {
                $data['main_category_id'] = $category->id;
                $data['category_id'] = null;
                $data['subcategories'] = CategoryModel::where('parent', $category->id)->active()->get();
            }
        }

        $data['store_tax_slack'] = "";
        if (session('store_tax_code') != "") {
            $data['store_tax_slack'] = TaxcodeModel::where('id', session('store_tax_code'))->first() == null ? null : TaxcodeModel::where('id', session('store_tax_code'))->first()->slack;
        }
        $productList = ProductModel::where("is_ingredient",0)->active()->get();
          foreach($productList as $product)
          {
            if($product->product_thumb_image)
            {
               $product->product_thumb_image = '<img src="'.Storage::disk("product")->url($product->product_thumb_image).'" style="width:50px;height:50px;"/>'; 
            }
            else
            {
              $product->product_thumb_image = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
            } 
          }
        $data['product_list'] = $productList;
        $data['category_list'] = CategoryModel::active()->get();
        $data['store_tax_id'] = session('store_tax_code');
        return view('product.add_ingredient', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack)
    {
// dd($slack);
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCTS';
        $data['action_key'] = 'A_DETAIL_PRODUCT';
        check_access([$data['action_key']]);

        $product = ProductModel::where('products.slack', '=', $slack)->first();

        if (empty($product)) {
            abort(404);
        }

        $data['product_data'] = new ProductResource($product);

        $data['tax_data'] = 0;
        if ($product->tax_code_id > 0) {
            $data['tax_data'] = TaxcodeModel::where('id', $product->tax_code_id)->first();
        }
        if($data['product_data']->is_ingredient == 1){
            $data['ingredient_products']  = ProductModel::query()
                ->join('product_ingredients','product_ingredients.product_id','products.id')
                ->join('category','category.id','products.category_id')
                ->leftjoin('category as main_cat','main_cat.id','category.parent')
                ->leftjoin('measurements','measurements.id','products.measurement_id')
                ->where('product_ingredients.ingredient_product_id',$product->id)
                ->select('products.*','product_ingredients.quantity as measure_quabtity','measurements.label','category.label as category','category.parent','main_cat.label as main_category')
                ->get();
            //$data['ingredient_products'] = ProductResource::collection($product_ingredients);
        }else{
            $data['ingredient_products'] = [];
        }
        return view('product.product_detail', $data);
    }

    //This is the function that loads the barcode generate page
    public function generate_barcode($slack = null)
    {
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRODUCT_LABEL';
        check_access([$data['menu_key'], $data['sub_menu_key']]);
        $data['barcode_data'] = '';
        $data['productBarcodeDetails'] = [];
        if (isset($slack)) {
            $product = ProductModel::with('measurements', 'ingredients')->where('products.slack', '=', $slack)->first();
            $data['product'] = $product;
            if (empty($product)) {
                abort(404);
            }
            $product_data = new ProductResource($product);
            $data['product_data'] = $product_data;
            $productBarcodeDetails = ProductBarcodeDetail::with('storeData')->where('product_slack',$slack)->first();
            if(!empty($productBarcodeDetails)){
                $generator  = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $data['barcode_data'] = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($product_data->barcode, $generator::TYPE_CODE_128)) . '" style="width: 100%;">';
                $productBarcodeDetails_data = new ProductBarcodeDetailResource($productBarcodeDetails);
                //dd($productBarcodeDetails->storeData->name, $productBarcodeDetails_data->storeData->name);
                //dd(json_encode($product_data), json_encode($productBarcodeDetails_data));
                $data['productBarcodeDetails'] = $productBarcodeDetails_data;
            }
            
        }else{
            if (empty($product)) {
                abort(404);
            }
        }
        
        return view('product.product_barcode', $data);
    }

    /* 
        Helper function to move products between store
    */
    public function move_products_between_stores($store_last_product_id)
    {

        $products = ProductModel::where('id', '<=', $store_last_product_id)->get();

        $product_ids = [];
        foreach ($products as $product) {

            $new_product = ProductModel::find($product->id);
            $newPost = $new_product->replicate();
            $newPost->slack = $this->generate_slack('products');
            $newPost->store_id = 2;
            $newPost->quantity = 0;
            $newPost->save();

            $dataset['old_id'] = $product->id;
            $dataset['new_id'] = $newPost->id;
            $product_ids[] = $dataset;
        }

        foreach ($product_ids as $pid) {

            $ingredients = ProductIngredient::where('product_id', $pid['old_id'])->get();
            if (!empty($ingredients)) {

                foreach ($ingredients as $ingredient) {

                    // find ingredient product id
                    $ingredient_product_id = 0;
                    foreach ($product_ids as $val) {
                        if ($val['old_id'] == $ingredient->ingredient_product_id) {
                            $ingredient_product_id = $val['new_id'];
                        }
                    }

                    // create new ingredient
                    $new_ingredient = new ProductIngredient();
                    $new_ingredient->slack = $this->generate_slack('product_ingredients');
                    $new_ingredient->product_id = $pid['new_id'];
                    $new_ingredient->ingredient_product_id = $ingredient_product_id;
                    $new_ingredient->quantity = $ingredient->quantity;
                    $new_ingredient->measurement_id = $ingredient->measurement_id;
                    $new_ingredient->save();
                }
            }
        }

        echo "Done";
    }

    public function quantity_adjustments(Request $request)
    {
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_QUANTITY_ADJUSTMENT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));
        
        $data['stores'] = StoreModel::where('status',1)->get();
        $data['reasons'] = ["Expired", "Damaged","Saved"];
        return view('quantity_adjustments.quantity_adjustment', $data);
    }
    public function quantity_adjustment($slack)
    {
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'A_EDIT_QUANTITY_ADJUSTMENT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));
        
        $products = ProductModel::whereRaw('quantity >= 0')->active()->get();
        $data['products'] = $products;
        $data['stores'] = StoreModel::where('status',1)->get();
        $data['quantity_adjustment_details'] = QuantityAdjustments::where('slack',$slack)->get()->toArray();
        $data['quantity_adjustment_details'] = $data['quantity_adjustment_details'][0];
        $data['quantity_adjustment_products'] = QuantityAdjustmentItems::where('quantity_adjustment_id',$data['quantity_adjustment_details']['id'])->get();
        $products = [];
        foreach($data['quantity_adjustment_products'] as $product)
        {
            $product->product->quantity = $product->quantity;
            array_push($products,$product->product);
        }
        $data['quantity_adjustment_products'] = $products;
        return view('quantity_adjustments.add_quantity_adjustment',$data);
    }
    public function quantity_adjustment_view($slack){

        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'A_VIEW_QUANTITY_ADJUSTMENT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data = [];
        $data['quantity_adjustment_details'] = QuantityAdjustments::where('slack',$slack)->first();
        $data['quantity_adjustment_details'] = new QuantityAdjustmentResource($data['quantity_adjustment_details']);
        $quantity_adjustment_products = QuantityAdjustmentItems::where('quantity_adjustment_id',$data['quantity_adjustment_details']->id)->get();
        $products = [];
        foreach($quantity_adjustment_products as $quantity_adjustment_product)
        {
          array_push($products,new QuantityAdjustmentItemsResource($quantity_adjustment_product));
        }
        $data['quantity_adjustment_products'] = $products;
        return view('quantity_adjustments.quantity_adjustment_detail',$data);
    }
    public function add_quantity_adjustment(Request $request)
    {
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'A_ADD_QUANTITY_ADJUSTMENT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $products = ProductModel::whereRaw('quantity >= 0')->active()->get();
        $data['products'] = $products;
        $data['stores'] = StoreModel::where('status',1)->get();
        return view('quantity_adjustments.add_quantity_adjustment',$data);
    }
}