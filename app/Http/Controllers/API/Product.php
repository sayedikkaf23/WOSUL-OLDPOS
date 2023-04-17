<?php

namespace App\Http\Controllers\API;


use App\Models\QoyodAccount;
use App\Models\QoyodCategory;
use App\Models\QoyodMesurmentUnit;
use App\Models\QoyodProduct;
use App\Models\QoyodUsersAccount;
use App\Models\ProductIngredient;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;

use App\Models\Product as ProductModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Category as CategoryModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\MasterStatus;
use App\Models\QuantityAdjustments;
use App\Models\QuantityAdjustmentItems;
use App\Http\Resources\QuantityAdjustmentResource;
use App\Models\StockTransfer as StockTransferModel;
use App\Models\StockTransferProduct as StockTransferProductModel;
use App\Models\ProductImages as ProductImagesModel;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;
use Illuminate\Support\Facades\Storage;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\Modifier as ModifierModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\ProductModifier as ProductModifierModel;
use App\Models\Store as StoreModel;
use App\Models\ProductBarcodeDetail;

use App\Http\Controllers\API\StockTransfer as StockTransferAPI;

use App\Http\Resources\ProductResource;

use App\Http\Resources\Collections\ProductCollection;
use App\Http\Resources\ComboProductResource;
use App\Http\Resources\ComboResource;
use App\Http\Resources\ProductBarcodeDetailResource;
use Mpdf\Mpdf;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use App\Http\Traits\ZidApiTrait;
use App\Http\Traits\CommonApiTrait;
use App\Models\Combo;
use App\Models\ComboGroup;
use App\Models\ComboProduct;
use App\Models\Price;
use App\Models\ProductPrice;
use PhpParser\JsonDecoder;
use App\Http\Traits\QoyodApiTrait;

class Product extends Controller
{
    public $product_limit;

    public function __construct()
    {
        $this->product_limit = 30;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ZidApiTrait,CommonApiTrait,QoyodApiTrait;

    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_PRODUCT_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $product_filter = (isset($request->product_filter)) ? $request->product_filter : 'billing_products';

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            // $filter_columns = array_filter(data_get($request->columns, '*.name'));
            $filter_columns = ['products.name', 'products.product_code', 'products.barcode'];

            $query = ProductModel::select('products.*', 'master_status.label as status_label', 'master_status.color as status_color', 'suppliers.name as supplier_name', 'suppliers.status as supplier_status', 'category.label as category_label', 'category.status as category_status', 'tax_codes.tax_code as tax_code_label', 'tax_codes.status as tax_code_status', 'discount_codes.discount_code as discount_code_label', 'discount_codes.status as discount_code_status', 'user_created.fullname')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->categoryJoin()
                // ->mainCategoryJoin()
                ->supplierJoin()
                ->taxcodeJoin()
                ->discountcodeJoin()
                ->createdUser()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->when($product_filter == 'billing_products', function ($query) {
                    $query->mainProduct();
                })

                ->when($product_filter == 'ingredients', function ($query) {
                    $query->isIngredient();
                });

            $count_query = $query;

            $query = $query->get();

            $products = ProductResource::collection($query);
            $count_query->getQuery()->limit = null;
            $count_query->getQuery()->offset = null;
            $total_count = $count_query->get()->count();

            $item_array = [];

            foreach ($products as $key => $product) {
                if ($product->category_id != 0) {
                    $category = CategoryModel::find($product->category_id);
                    if ($category->parent > 0) {
                        $category_name = $category->label . "/" . CategoryModel::find($category->parent)->label;
                    } else {
                        $category_name = $category->label;
                    }
                } else {
                    $category_name = '-';
                }

                $product = $product->toArray($request);

                $item_array[$key][] = $product['product_code'];
                if ($product['product_thumb_image'] != '') {
                    $item_array[$key][] = Storage::disk("product")->url($product['product_thumb_image']);
                } else {
                    $item_array[$key][] = null;
                }
                $item_array[$key][] = Str::limit($product['name'], 50);
                $item_array[$key][] = (isset($product['supplier']['status'])) ? (view('common.status_indicators', ['status' => $product['supplier']['status']])->render()) . Str::limit($product['supplier']['name'], 50) . " (" . $product['supplier']['supplier_code'] . ")" : '-';
                // $item_array[$key][] = (isset($product['main_category']['status']))?(view('common.status_indicators', ['status' => $product['main_category']['status']])->render()).Str::limit($product['main_category']['label'], 50)." (".$product['main_category']['category_code'].")":'-';
                $item_array[$key][] = $category_name;

                $item_array[$key][] = (isset($product['discount_code']['status']) && $product['discount_code']['status'] != null) ? (view('common.status_indicators', ['status' => $product['discount_code']['status']])->render() . Str::limit($product['discount_code']['label'], 50) . " (" . $product['discount_code']['discount_code'] . ")") : '-';

                $measurement = ($product['measurement_id'] == null) ? '' : MeasurementModel::find($product['measurement_id'])->label;
                $item_array[$key][] = (!isset($product['quantity'])) ? '-' : ($product['quantity'] != '-1.00' ? $product['quantity'] . " " . $measurement : 'Unlimited');
                // $item_array[$key][] = '123';
                $item_array[$key][] = (isset($product['sale_amount_excluding_tax'])) ? $product['sale_amount_excluding_tax'] : '-';
                $item_array[$key][] = ($product['is_taxable'] == 1 && isset($product['tax_code']->tax_code) && $product['tax_code']->tax_code != 'NO_TAX') ? 
                    ' <span class="text-success"> YES </span>' : 'NO';
                $item_array[$key][] = (isset($product['status']['label'])) ? view('common.status', ['status_data' => ['label' => $product['status']['label'], "color" => $product['status']['color']]])->render() : '-';
                $item_array[$key][] = (isset($product['created_at_label'])) ? $product['created_at_label'] : '-';
                $item_array[$key][] = (isset($product['updated_at_label'])) ? $product['updated_at_label'] : '-';
                $item_array[$key][] = (isset($product['created_by']) && $product['created_by']['fullname'] != '') ? $product['created_by']['fullname'] : '-';
                if($product_filter == 'billing_products'){
                    $item_array[$key][] = view('product.layouts.product_actions', array('product' => $product))->render();
                }else{
                    $item_array[$key][] = view('product.layouts.ingredient_actions', array('product' => $product))->render();
                }
            }
            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_PRODUCT'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            // $product_data_exists = ProductModel::select('id')
            //     ->where('product_code', '=', trim($request->product_code))
            //     ->first();
            // if (!empty($product_data_exists)) {
            //     throw new Exception(trans("Product code already assigned to a product"), 400);
            // }

            if(!empty($request->ingredients)){
                if($request->logged_user_store_restaurant_mode==0){
                    throw new Exception(trans("Restaurant Mode is not Enable to add ingredient!"), 400);
                }
            }

            $discount_code_id = NULL;
            if (isset($request->discount_code)) {
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")
                    ->whereRaw("discounttype!='cashier'")
                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                    ->active()
                    ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::select('*')
                    ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")->first();
                    if(isset($discount_code_data->label))
                    {
                       throw new Exception(trans("Discount '{$discount_code_data->label}' has been Expired or is not found in the system"), 400);
                    }
                    else
                    {
                       throw new Exception(trans("Discount is not found in the system"), 400);
                    }
                } else {
                    $discount_details = DB::select("select * from discount_codes where id=" . $request->discount_code);
                    $discount_details = isset($discount_details[0]) ? $discount_details[0] : [];
                    if ($discount_details->discount_applied_on == "specific_product_categories") {
                        $discount_categories = explode(",", $discount_details->discount_applicable_categories);
                        foreach ($discount_categories as $discount_category) {
                            $categories = CategoryModel::where('parent', $request->category_id)->active()->get();
                            foreach ($categories as $category) {
                                array_push($discount_categories, $category->id);
                            }
                        }
                        if (in_array($request->main_category, $discount_categories) == false) {
                            if (in_array($request->category, $discount_categories) == false) {
                                throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$request->product_name}'");
                            }
                        }
                    }
                }
                $discount_code_id = $discount_code_data->id;
            }

            if (isset($request->stock_transfer_product_slack) && $request->stock_transfer_product_slack != '') {
                $stock_transfer_api = new StockTransferAPI();
                $validate_response = $stock_transfer_api->validate_verify_stock_transfer($request, $request->stock_transfer_product_slack, $request->quantity);
                $stock_transfer_details = $validate_response['stock_transfer_details'];
                $stock_transfer_product_details = $validate_response['stock_transfer_product_details'];
                $stock_transfer_status = $validate_response['stock_transfer_status'];
                $stock_transfer_product_status = $validate_response['stock_transfer_product_status'];
            }

            // if ($request->unlimited_quantity != 'true') {
            //     if ((int)$request->quantity == 0) {
            //         throw new Exception("Product Quantity must be greater than 0");
            //     }
            // }

            DB::beginTransaction();
            $product = [
                "slack" => $this->generate_slack("products"),
                "store_id" => $request->logged_user_store_id,
                "name" => $request->product_name,
                "name_ar" => (isset($request->arabic_product_name)) ? $request->arabic_product_name : NULL,
                "product_code" => (isset($request->product_code)) ? strtoupper($request->product_code) : NULL,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "category_id" => (isset($request->category)) ? $request->category : $request->main_category,
                "discount_code_id" => $discount_code_id,
                "alert_quantity" => (!isset($request->alert_quantity)) ? 0.00 : $request->alert_quantity,
                "tax_code_id" => $request->tax_code_id ?? session('store_tax_code'),
                "purchase_amount_excluding_tax" => $request->purchase_price,
                "sale_amount_excluding_tax" => $request->sale_price,
                "sale_amount_including_tax" => $request->sale_price_including_tax,
                "price_type" => $request->price_type,
                "is_ingredient" => $request->is_ingredient ? $request->is_ingredient : 0,
                "status" => $request->status,
                "inventory_type" => $request->inventory_type,
                "shows_in" => $request->shows_in,
                "show_description_in" => $request->show_description_in,
                "created_by" => $request->logged_user_id,
                "barcode" => $request->barcode,
                "measurement_id" => $request->measurement_id,
                "brand_id" => $request->brand_id,
                "product_border_color" => $request->product_border_color,
                "product_manufacturer_date" => $request->product_manufacturer_date,
                "product_expiry_date" => $request->product_expiry_date,
                "is_taxable" => ($request->is_taxable == 'true') ? 1 : 0,
                "quantity" => ($request->unlimited_quantity == 'true') ? -1 : $request->quantity,
                "supplier_id" => (!empty($request->supplier)) ? SupplierModel::where('slack', $request->supplier)->first()->id : "",
                "sales_price_including_boolean_and_price" => isset($request->sales_price_including_boolean_and_price) ? $request->sales_price_including_boolean_and_price : '',
            ];

            $product['is_tobacco_tax'] = $request->is_tobacco_tax ?? 0;
            if ($request->is_tobacco_tax == 1) {
                $product['tobacco_tax_percentage'] = 100; // tax percentage
            } else {
                $product['tobacco_tax_percentage'] = 0; // tax percentage
            }

            // adding product thumbnail image
            $product['product_thumb_image'] = null;
            if ($request->hasFile('product_thumb_image')) {
                $image = $request->file('product_thumb_image');
                $product['product_thumb_image']   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('product')->put('/' . $product['product_thumb_image'], $img, 'public');
            }
            
            $product_result = ProductModel::create($product);
            
            $product_obj = [];
            array_push($product_obj,$product_result);


            if(isset($request->product_applicable_stores) && $request->product_applicable_stores!="")
            {
                $product_applicable_stores = explode(",",$request->product_applicable_stores);
                
                $product_applicable_store_quantities = json_decode($request->product_applicable_store_quantities,true);
                
                for($i=0;$i<count($product_applicable_stores);$i++)
                {
                    $record = $product_result->replicate();
                    $record->slack = $this->generate_slack("products");
                    $record->quantity = (int)$product_applicable_store_quantities[$i];
                    
                    $record->store_id = (int)$product_applicable_stores[$i];
                    $record->save();
                    array_push($product_obj,$record);
                    
                }
            }

            foreach($product_obj as $product_result)
            {
                if(isset($request->product_prices)){
                    $this->add_multiple_prices($product_result,$request->product_prices);
                }

                if ($request->zid_sync_option == "true") {
                    $this->zid_add_product($request, $product_result->id);
                }

                if (isset($request->modifier)) {
                    $product_modifiers_ids = explode(',', $request->modifier);
                    $product_modifiers = [];
                    foreach ($product_modifiers_ids as $modifier_id) {
                        $dataset = [
                            'slack' => $this->generate_slack("product_modifiers"),
                            'product_id' => $product_result->id,
                            'modifier_id' => $modifier_id,
                            "status" => 1,
                            "created_by" => $request->logged_user_id
                        ];
                        $product_modifiers[] = $dataset;
                    }
                    ProductModifierModel::insert($product_modifiers);
                }

                $this->add_ingredients($request, $product_result->slack);

                $this->upload_product_images($request, $product_result->slack);
                $forward_link = '';

                if (isset($request->stock_transfer_product_slack) && $request->stock_transfer_product_slack != '') {

                    $source_store_product = ProductModel::withoutGlobalScopes()->where('id', $stock_transfer_product_details->product_id);
                    $source_store_product->decrement('quantity', $request->quantity);

                    $stock_transfer = [];
                    $stock_transfer['status'] = $stock_transfer_status->value;
                    $stock_transfer['updated_at'] = now();
                    $stock_transfer['updated_by'] = $request->logged_user_id;

                    $action_response = StockTransferModel::withoutGlobalScopes()->where('id', $stock_transfer_product_details->stock_transfer_id)
                        ->update($stock_transfer);

                    $stock_transfer_product = [];

                    $stock_transfer_product['inward_type'] = 'NEW';
                    $stock_transfer_product['accepted_quantity'] = $product_result->quantity;
                    $stock_transfer_product['destination_product_id'] = $product_result->id;
                    $stock_transfer_product['destination_product_slack'] = $product_result->slack;
                    $stock_transfer_product['destination_product_code'] = $product_result->product_code;
                    $stock_transfer_product['destination_product_name'] = $product_result->name;
                    // $stock_transfer_product['destination_barcode'] = $product['barcode'];

                    $stock_transfer_product['status'] = $stock_transfer_product_status->value;
                    $stock_transfer_product['updated_at'] = now();
                    $stock_transfer_product['updated_by'] = $request->logged_user_id;

                    $action_response = StockTransferProductModel::where('slack', $request->stock_transfer_product_slack)
                        ->update($stock_transfer_product);

                    $stock_transfer_api->check_and_update_stock_transfer_status($request, $stock_transfer_details->slack);

                    $forward_link = route('verify_stock_transfer', ['slack' => $stock_transfer_details->slack]);
                }

                // Add quantity history
                if((int)$product_result->quantity != -1){

                    $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_result->id,$product_result->store_id,'PRODUCT','INCREMENT',$product_result->quantity,$product_result->id);

                }
                if(isset($request->tax_code_id) && $request->tax_code_id!="")
                {
                    $taxcodedetails = TaxcodeModel::where("id",$request->tax_code_id)->where("store_id",$product_result->store_id)->first();
                    if(!isset($taxcodedetails->id))
                    {
                        $taxcodedetails = TaxcodeModel::where("id",$request->tax_code_id)->where("store_id",$product['store_id'])->first();
                        $taxcoderesult = $this->checkAlreadyExist($request->tax_code_id,$product_result->store_id,"tax_codes");
                        if($taxcoderesult==0 && isset($taxcodedetails->id))
                        {
                        $taxcode = $taxcodedetails->replicate();
                        $taxcode->slack = $this->generate_slack("tax_codes");
                        $taxcode->store_id = $product_result->store_id;
                        $taxcode->save();
                        $product_result->tax_code_id = $taxcode->id;
                        $product_result->save();
                        }
                        else
                        {
                            $product_result->tax_code_id = $taxcoderesult;
                            $product_result->save();
                        }
                    }
                }
                if(isset($discount_code_data->id) && (int)$discount_code_data->id>0)
                {
                    $discountcodedetails = DiscountcodeModel::where("id",$discount_code_data->id)->where("store_id",$product_result->store_id)->first();
                    if(!isset($discountcodedetails->id))
                    {
                        $discountcodedetails = DiscountcodeModel::where("id",$discount_code_data->id)->where("store_id",$product['store_id'])->first();
                        $discountcoderesult = $this->checkAlreadyExist($discount_code_data->id,$product_result->store_id,"discount_codes");
                        if($discountcoderesult==0 && isset($discountcodedetails->id))
                        {
                            $discountcode = $discountcodedetails->replicate();
                            $discountcode->slack = $this->generate_slack("discount_codes");
                            $discountcode->store_id = $product_result->store_id;
                            $discountcode->save();
                            $product_result->discount_code_id = $discountcode->id;
                            $product_result->save();
                        }
                        else {
                            $product_result->discount_code_id = $discountcoderesult;
                            $product_result->save();
                        }
                    }
                }
                if(isset($request->supplier) && $request->supplier!="")
                {
                    $supplierdetails = SupplierModel::whereRaw("id='{$request->supplier}' or slack='{$request->supplier}'")->where("store_id",$product_result->store_id)->first();
                    if(!isset($supplierdetails->id))
                    {
                        $supplierdetails = SupplierModel::whereRaw("id='{$request->supplier}' or slack='{$request->supplier}'")->where("store_id",$product['store_id'])->first();
                        $supplierresult = $this->checkAlreadyExist($request->supplier,$product_result->store_id,"suppliers");
                        if($supplierresult==0 && isset($supplierdetails->id))
                        {
                        $supplier = $supplierdetails->replicate();
                        $supplier->slack = $this->generate_slack("suppliers");
                        $supplier->store_id = $product_result->store_id;
                        $supplier->save();
                        $product_result->supplier_id = $supplier->id;
                        $product_result->save();
                        }
                        else
                        {
                        $product_result->supplier_id = $supplierresult;
                        $product_result->save();
                        }
                    }
                }
                //Qoyod
                if(Session('qoyod_status')){
                    $qoyod_product = ProductModel::where('id',$product_result->id)->first();
                    $this->qoyod_create_product($qoyod_product);
                }
            }
         
            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product created successfully"),
                    "data"    => $product['slack'],
                    "link"    => $forward_link
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    public function checkAlreadyExist($id,$storeid,$type)
    {
        try
        {
           if($type=="tax_codes")
           {
              $originalrecord = DB::select("select * from tax_codes where id='{$id}' or slack='{$id}'");
              $originalrecord = isset($originalrecord[0])?$originalrecord[0]:[];
              $taxcodes = DB::select("select * from tax_codes where store_id={$storeid}");
              //$keyslist = [];
              if(count($taxcodes)>0)
              {
                foreach($taxcodes as $taxcode)
                {
                 $keyslist = array_diff_assoc((array)$originalrecord,(array)$taxcode);
                  if(count(array_keys($keyslist))==5 || count(array_keys($keyslist))==7)
                  {
                      return $taxcode->id;
                  }
                }
              }
            }
            else if($type=="discount_codes")
            {
                $originalrecord = DB::select("select * from discount_codes where id={$id}");
              $originalrecord = isset($originalrecord[0])?$originalrecord[0]:[];
              $discountcodes = DB::select("select * from discount_codes where store_id={$storeid}");
              //$keyslist = [];
              if(count($discountcodes)>0)
              {
                foreach($discountcodes as $discountcode)
                {
                 $keyslist = array_diff_assoc((array)$originalrecord,(array)$discountcode);
                  if(count(array_keys($keyslist))==5 || count(array_keys($keyslist))==7)
                  {
                      return $discountcode->id;
                  }
                }
              }
            }
            else if($type=="suppliers")
            {
                $originalrecord = DB::select("select * from suppliers where id='{$id}' or slack='{$id}'");
              $originalrecord = isset($originalrecord[0])?$originalrecord[0]:[];
              $suppliers = DB::select("select * from suppliers where store_id={$storeid}");
              //$keyslist = [];
              if(count($suppliers)>0)
              {
                foreach($suppliers as $supplier)
                {
                 $keyslist = array_diff_assoc((array)$originalrecord,(array)$supplier);
                  if(count(array_keys($keyslist))==5 || count(array_keys($keyslist))==7)
                  {
                      return $supplier->id;
                  }
                }
              }
            }
            return 0;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
    public function list_main_categories(Request $request)
    {
        try
        {
            $category_details = CategoryModel::select('id', 'slack', 'category_code', 'label', 'category_applied_on', 'category_applicable_stores')->parentCategory()->categoryStore()->sortLabelAsc()->active()->get();
            return response()->json($category_details);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
    public function list_discounts(Request $request)
    {
        try
        {
            $currentdate = date('Y-m-d H:i:s');
            $discount_details = DiscountcodeModel::select('*')
            ->whereRaw("discounttype!='cashier'")
            ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
            ->whereRaw("(discount_applied_on='all_products') OR (discount_applied_on='specific_products' and FIND_IN_SET('{$request->product_id}',discount_applicable_products)>0) OR (discount_applied_on='all_products_except_specific' and FIND_IN_SET('{$request->product_id}',discount_not_applicable_products)=0) OR (discount_applied_on='specific_product_categories' and (FIND_IN_SET('{$request->main_category_id}',discount_applicable_categories)>0 OR FIND_IN_SET('{$request->sub_category_id}',discount_applicable_categories)>0))")
            ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
            ->sortLabelAsc()->active()->get();
            return response()->json($discount_details);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
    public function list_measurements(Request $request)
    {
        try
        {
            $measurement_data = MeasurementModel::singleValue()->active()->get();
            return response()->json($measurement_data);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
    public function list_measurement_categories(Request $request)
    {
        try
        {
            $measurement_data = MeasurementCategoryModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();
            return response()->json($measurement_data);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    // added later
    public function store_ingredient(Request $request)
    {

        try {
            if (!check_access(['A_ADD_PRODUCT'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            // $product_data_exists = ProductModel::select('id')
            //     ->where('product_code', '=', trim($request->product_code))
            //     ->first();
            // if (!empty($product_data_exists)) {
            //     throw new Exception(trans("Product code already assigned to a product"), 400);
            // }

            if ($request->is_taxable  == 'true') {
                $taxcode_data = TaxcodeModel::select('id')
                    ->where('id', '=', trim($request->tax_code))
                    //->active()
                    ->first();
                if (empty($taxcode_data)) {
                    throw new Exception("Tax code not found or inactive in the system", 400);
                }
            }

            $discount_code_id = NULL;
            if (isset($request->discount_code)) {
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('id')
                    ->where('id', trim($request->discount_code))
                    ->whereRaw("discounttype!='cashier'")
                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                    ->active()
                    ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::select('*')
                        ->where('id', trim($request->discount_code))->first();
                    throw new Exception(trans("Discount '{$discount_code_data->label}'  has been Expired or is not found in the system"), 400);
                } else {
                    $discount_details = DB::select("select * from discount_codes where id=" . $request->discount_code);
                    $discount_details = isset($discount_details[0]) ? $discount_details[0] : [];
                    if ($discount_details->discount_applied_on == "specific_product_categories") {
                        $discount_categories = explode(",", $discount_details->discount_applicable_categories);
                        foreach ($discount_categories as $discount_category) {
                            $categories = CategoryModel::where('parent', $request->category_id)->active()->get();
                            foreach ($categories as $category) {
                                array_push($discount_categories, $category->id);
                            }
                        }
                        if (in_array($request->main_category, $discount_categories) == false) {
                            if (in_array($request->category, $discount_categories) == false) {
                                throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$request->product_name}'");
                            }
                        }
                    }
                }
                $discount_code_id = $discount_code_data->id;
            }

            if (isset($request->stock_transfer_product_slack) && $request->stock_transfer_product_slack != '') {
                $stock_transfer_api = new StockTransferAPI();
                $validate_response = $stock_transfer_api->validate_verify_stock_transfer($request, $request->stock_transfer_product_slack, $request->quantity);
                $stock_transfer_details = $validate_response['stock_transfer_details'];
                $stock_transfer_product_details = $validate_response['stock_transfer_product_details'];
                $stock_transfer_status = $validate_response['stock_transfer_status'];
                $stock_transfer_product_status = $validate_response['stock_transfer_product_status'];
            }

            DB::beginTransaction();

            if ($request->hasFile('product_thumb_image')) {

                $upload_dir = Config::get('constants.upload.product.upload_path');
                $product_thumb_image = $request->product_thumb_image;

                $extension = $product_thumb_image->getClientOriginalExtension();
                $product_thumb_image_file_name = 'SC_' . uniqid() . '.' . $extension;
                $path = Storage::disk('product')->putFileAs('/', $product_thumb_image, $product_thumb_image_file_name);
                $product_thumb_image_file_name = basename($path);

                $image = Image::make($product_thumb_image);
                $file_path = $upload_dir . $product_thumb_image_file_name;
                $image->save($file_path);
                $image->destroy();

                $product_thumb_image = (isset($product_thumb_image_file_name)) ? $product_thumb_image_file_name : '';
            } else {
                $product_thumb_image = null;
            }

            if (!empty($request->supplier)) {
                $supplier_id = SupplierModel::where('slack', $request->supplier)->first()->id;
            } else {
                $supplier_id = "";
            }

            $product = [
                "slack" => $this->generate_slack("products"),
                "store_id" => $request->logged_user_store_id,
                "name" => $request->product_name,
                "product_code" => (isset($request->product_code)) ? strtoupper($request->product_code) : NULL,
                "description" => $request->description,
                "category_id" => (isset($request->category)) ? $request->category : $request->main_category,
                "supplier_id" => $supplier_id,
                "discount_code_id" => $discount_code_id,
                "quantity" => $request->quantity,
                "alert_quantity" => (!isset($request->alert_quantity)) ? 0.00 : $request->alert_quantity,
                "purchase_amount_excluding_tax" => $request->purchase_price,
                "sale_amount_excluding_tax" => $request->sale_price,
                "is_ingredient" => 1,
                "status" => $request->status,
                "measurement_id" => $request->measurement_id,
                "shows_in" => $request->shows_in,
                "created_by" => $request->logged_user_id,
                "product_thumb_image" => $product_thumb_image,
                "product_manufacturer_date" => $request->product_manufacturer_date,
                "product_expiry_date" => $request->product_expiry_date,
                "tax_code_id" => $request->tax_code,
                "brand_id" => $request->brand_id,
                "barcode" => $request->barcode,
            ];


            // if ($request->is_taxable == 'true') {
            //     $product['tax_code_id'] = 1;
            // } else {
            //     $product['tax_code_id'] = 0;
            // }
            if($request->is_taxable=='true'){
                $product['tax_code_id'] = $taxcode_data->id>0 ? $taxcode_data->id : session('store_tax_code');
            }else{
                $product['tax_code_id'] = 0;
            }
            $product_result = ProductModel::create($product);

            $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_result->id,request()->logged_user_store_id,'INGREDIENT','INCREMENT',$request->quantity,$product_result->id);

            $this->upload_product_images($request, $product['slack']);

            $forward_link = '';

            //Qoyod
            if(Session('qoyod_status')){
                $qoyod_product = ProductModel::where('id',$product_result->id)->first();
                $this->qoyod_create_product($qoyod_product);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Ingredient Added successfully"),
                    "data"    => $product['slack'],
                    "link"    => $forward_link
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function update_ingredient(Request $request, $slack)
    {
        try {
            if (!check_access(['A_EDIT_PRODUCT'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $product_data = ProductModel::where('slack', $request->slack)->first();
            $product_quantity = $product_data->quantity;
            $this->validate_request($request);
            $taxcode_data = NULL;
            if ($request->is_taxable  == 'true') {
                $taxcode_data = TaxcodeModel::select('id')
                    ->where('id', '=', trim($request->tax_code))
                    //->active()
                    ->first();
                if (empty($taxcode_data)) {
                    throw new Exception("Tax code not found or inactive in the system", 400);
                }
            }
            $discount_code_id = NULL;
            if (isset($request->discount_code)) {
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('*')
                    ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")
                    ->whereRaw("discounttype!='cashier'")
                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                    ->active()
                    ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::select('*')
                        ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")
                        ->first();
                    if(isset($discount_code_data->label))
                    {
                        throw new Exception(trans("Discount '{$discount_code_data->label}' has been Expired or is not found in the system"), 400);
                    }
                    else
                    {
                        throw new Exception(trans("Discount is not found in the system"), 400);
                    }
                } else {
                    $discount_details = DB::select("select * from discount_codes where id=" . $request->discount_code);
                    $discount_details = isset($discount_details[0]) ? $discount_details[0] : [];
                    if ($discount_details->discount_applied_on == "specific_products") {
                        if (in_array($product_data->id, explode(",", $discount_details->discount_applicable_products)) == false) {
                            throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                        }
                    } else if ($discount_details->discount_applied_on == "all_products_except_specific") {
                        if (in_array($product_data->id, explode(",", $discount_details->discount_not_applicable_products)) == true) {
                            throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                        }
                    } else if ($discount_details->discount_applied_on == "specific_product_categories") {
                        $discount_categories = explode(",", $discount_details->discount_applicable_categories);
                        foreach ($discount_categories as $discount_category) {
                            $categories = CategoryModel::where('parent', $discount_category)->active()->get();
                            foreach ($categories as $category) {
                                array_push($discount_categories, $category->id);
                            }
                        }
                        if (in_array($request->main_category, $discount_categories) == false) {
                            if (in_array($request->category, $discount_categories) == false) {
                                throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                            }
                        }
                    }
                }
                $discount_code_id = $discount_code_data->id;
            }

            DB::beginTransaction();

            if (!empty($request->supplier)) {
                $supplier_id = SupplierModel::where('slack', $request->supplier)->first()->id;
            } else {
                $supplier_id = "";
            }
            $product = [
                "name" => $request->product_name,
                "product_code" => (isset($request->product_code)) ? strtoupper($request->product_code) : NULL,
                "description" => $request->description,
                "category_id" => (isset($request->category)) ? $request->category : $request->main_category,
                "supplier_id" => $supplier_id,
                "discount_code_id" => $discount_code_id,
                "quantity" => $request->quantity,
                "alert_quantity" => (!isset($request->alert_quantity)) ? 0.00 : $request->alert_quantity,
                "purchase_amount_excluding_tax" => $request->purchase_price,
                "sale_amount_excluding_tax" => $request->sale_price,
                "store_id" => $request->logged_user_store_id,
                "status" => $request->status,
                "measurement_id" => $request->measurement_id,
                "shows_in" => $request->shows_in,
                "updated_by" => $request->logged_user_id,
                "product_manufacturer_date" => $request->product_manufacturer_date,
                "product_expiry_date" => $request->product_expiry_date,
                "tax_code_id" => $request->tax_code,
                "brand_id" => $request->brand_id,
                "barcode" => $request->barcode,
            ];

            $product['product_thumb_image'] = $product_data->product_thumb_image;
            if ($request->hasFile('product_thumb_image')) {

                $image = $request->file('product_thumb_image');
                $product['product_thumb_image']   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('product')->put('/' . $product['product_thumb_image'], $img, 'public');

                // Delete old image
                if (!empty($product_data->product_thumb_image)) {
                    Storage::disk('product')->delete($product_data->product_thumb_image);
                }
            }
            if($request->is_taxable=='true'){
                $product['tax_code_id'] = $taxcode_data->id>0 ? $taxcode_data->id : session('store_tax_code');
            }else{
                $product['tax_code_id'] = 0;
            }


            if (!empty($request->category)) {
                $product['category_id'] = $request->category;
            } else {
                $product['category_id'] = $request->main_category;
            }

            $action_response = ProductModel::where('slack', $slack)->update($product);
            $parent_products = ProductIngredient::select('product_id')->where('ingredient_product_id',$product_data->id)->get();
            foreach ($parent_products as $parent_product){
                ProductModel::where('id', $parent_product->product_id)->update(array('updated_at'=> Carbon::now()->format('Y-m-d H:i:s')));
            }
            //Qoyod
            if(Session('qoyod_status')){
                $qoyod_product = ProductModel::withoutGlobalScopes()->select('products.id', 'products.store_id', 'products.name', 'products.slack', 'products.barcode', 'products.name', 'products.name_ar', 'products.description', 'products.quantity', 'products.purchase_amount_excluding_tax', 'products.sale_amount_excluding_tax', 'products.category_id', 'products.measurement_id', 'products.tax_code_id','qoyod_products.qoyod_product_id')->leftJoin('qoyod_products','qoyod_products.product_id','=','products.id')->where('products.slack',$slack)->first();
                if(isset($qoyod_product) && $qoyod_product->qoyod_product_id>0){
                    $this->qoyod_update_product($qoyod_product,$qoyod_product->qoyod_product_id);
                }
            }
            DB::commit();

            //Entry in "quantity_history" table
            if($request->quantity > $product_quantity){
                $increased_quantity = $request->quantity - $product_quantity;
                // Add quantity history
                $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_data->id,request()->logged_user_store_id,'INGREDIENT','INCREMENT',$increased_quantity,$product_data->id);

            }else if($request->quantity < $product_quantity){

                $decreased_quantity = $product_quantity - $request->quantity;
                // Add quantity history
                $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_data->id,request()->logged_user_store_id,'INGREDIENT','DECREMENT',$decreased_quantity,$product_data->id);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Ingredient updated successfully"),
                    "data"    => $slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_PRODUCT'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = ProductModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new ProductResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product loaded successfully"),
                    "data"    => $item_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_PRODUCT_LISTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new ProductCollection(ProductModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Products loaded successfully"),
                    "data"    => $list
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        try {

            if ($request->quantity == 'Unlimited') {
                $request['quantity'] = -1;
            }

            if (!check_access(['A_EDIT_PRODUCT'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $product_data = ProductModel::where('slack', $request->slack)->first();
            $product_quantity = $product_data->quantity;

            $this->validate_request($request);

            // $product_data_exists = ProductModel::select('id')
            //     ->where([
            //         ['slack', '!=', $slack],
            //         ['product_code', '=', trim($request->product_code)],
            //     ])
            //     ->first();

            // if (!empty($product_data_exists)) {
            //     throw new Exception(trans("Product code already assigned to a product"), 400);
            // }
            if(!empty($request->ingredients)){
                if($request->logged_user_store_restaurant_mode==0){
                    throw new Exception(trans("Restaurant Mode is not Enable to add/edit ingredient!"), 400);
                }
            }
            if (isset($product_data->discount_code_id) && $product_data->discount_code_id != "") {
                $discount_code_details = DB::select("select * from discount_codes where discounttype='inventory' and id=" . $product_data->discount_code_id . " and store_id=" . $request->logged_user_store_id);
            } else {
                $discount_code_details = [];
            }
            if (count($discount_code_details) > 0 && $request->discount_code != $product_data->discount_code_id) {
                if ($discount_code_details[0]->discount_applied_on == "specific_products") {
                    $discount_applicable_products = explode(",", $discount_code_details[0]->discount_applicable_products);
                    $discount_applicable_products = array_filter($discount_applicable_products, function ($item) use ($product_data) {
                        return $item != $product_data->id;
                    });
                    if (count($discount_applicable_products) == 0) {
                        $discount_applicable_products = "";
                        DB::update("update discount_codes set status = 0 where id=" . $product_data->discount_code_id);
                    } else {
                        $discount_applicable_products = implode(",", $discount_applicable_products);
                        DB::update("update discount_codes set discount_applicable_products='" . $discount_applicable_products . "' where id=" . $product_data->discount_code_id);
                    }
                } else if ($discount_code_details[0]->discount_applied_on == "specific_product_categories") {
                    $discount_applicable_categories = explode(",", $discount_code_details[0]->discount_applicable_categories);
                    $discount_applicable_categories = array_filter($discount_applicable_categories, function ($item) use ($product_data) {
                        return $item != $product_data->category->id;
                    });
                    if (count($discount_applicable_categories) == 0) {
                        $discount_applicable_categories = "";
                        DB::update("update discount_codes set status = 0 where id=" . $product_data->discount_code_id);
                    } else {
                        $discount_applicable_categories = implode(",", $discount_applicable_categories);
                        DB::update("update discount_codes set discount_applicable_categories='" . $discount_applicable_categories . "' where id=" . $product_data->discount_code_id);
                    }
                } else if ($discount_code_details[0]->discount_applied_on == "all_products_except_specific") {
                    $discount_not_applicable_products = explode(",", $discount_code_details[0]->discount_not_applicable_products);
                    array_push($discount_not_applicable_products, $product_data->id);
                    if (count($discount_not_applicable_products) == 0 || count($discount_not_applicable_products) == count(ProductModel::active()->get())) {
                        $discount_not_applicable_products = "";
                        DB::update("update discount_codes set status = 0 where id=" . $product_data->discount_code_id);
                    } else {
                        $discount_not_applicable_products = implode(",", $discount_not_applicable_products);
                        $discount_not_applicable_products = preg_replace("/,$/", "", $discount_not_applicable_products);
                        DB::update("update discount_codes set discount_not_applicable_products='" . $discount_not_applicable_products . "' where id=" . $product_data->discount_code_id);
                    }
                } else if ($discount_code_details[0]->discount_applied_on == "all_products") {
                    DB::update("update discount_codes set discount_applied_on='all_products_except_specific',discount_not_applicable_products='" . $product_data->id . "' where id=" . $product_data->discount_code_id);
                }
            }
            // if($request->is_taxable  == 'true'){
            //     $taxcode_data = TaxcodeModel::select('id')
            //     ->where('slack', '=', trim($request->tax_code))
            //     //->active()
            //     ->first();
            //     if (empty($taxcode_data)) {
            //         throw new Exception("Taxcode not found or inactive in the system", 400);
            //     }
            // }

            $discount_code_id = NULL;
            if (isset($request->discount_code)) {
                $currentdate = date('Y-m-d H:i:sa');
                $discount_code_data = DiscountcodeModel::select('*')
                ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")
                    ->whereRaw("discounttype!='cashier'")
                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                    ->active()
                    ->first();
                if (empty($discount_code_data)) {
                    $discount_code_data = DiscountcodeModel::select('*')
                    ->whereRaw("id='{$request->discount_code}' OR slack = '{$request->discount_code}'")
                    ->first();
                if(isset($discount_code_data->label))
                {
                   throw new Exception(trans("Discount '{$discount_code_data->label}' has been Expired or is not found in the system"), 400);
                }
                else
                {
                   throw new Exception(trans("Discount is not found in the system"), 400);
                }
                } else {
                    $discount_details = DB::select("select * from discount_codes where id=" . $request->discount_code);
                    $discount_details = isset($discount_details[0]) ? $discount_details[0] : [];
                    if ($discount_details->discount_applied_on == "specific_products") {
                        if (in_array($product_data->id, explode(",", $discount_details->discount_applicable_products)) == false) {
                            throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                        }
                    } else if ($discount_details->discount_applied_on == "all_products_except_specific") {
                        if (in_array($product_data->id, explode(",", $discount_details->discount_not_applicable_products)) == true) {
                            throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                        }
                    } else if ($discount_details->discount_applied_on == "specific_product_categories") {
                        $discount_categories = explode(",", $discount_details->discount_applicable_categories);
                        foreach ($discount_categories as $discount_category) {
                            $categories = CategoryModel::where('parent', $discount_category)->active()->get();
                            foreach ($categories as $category) {
                                array_push($discount_categories, $category->id);
                            }
                        }
                        if (in_array($request->main_category, $discount_categories) == false) {
                            if (in_array($request->category, $discount_categories) == false) {
                                throw new Exception("Discount '{$discount_details->label}' not applicable to Product '{$product_data->name}'");
                            }
                        }
                    }
                }
                $discount_code_id = $discount_code_data->id;
            }

            // if ($request->unlimited_quantity != 'true') {
            //     if ((int)$request->quantity == 0) {
            //         throw new Exception("Product Quantity must be greater than 0");
            //     }
            // }

            DB::beginTransaction();
            
            //print_r($request->product_applied_on);die;
            $product = [
                "name" => $request->product_name,
                "name_ar" => (isset($request->arabic_product_name)) ? $request->arabic_product_name : NULL,
                "product_code" => (isset($request->product_code)) ? strtoupper($request->product_code) : NULL,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "discount_code_id" => $discount_code_id,
                "purchase_amount_excluding_tax" => $request->purchase_price,
                "sale_amount_excluding_tax" => $request->sale_price,
                "sale_amount_including_tax" => $request->sale_price_including_tax,
                "price_type" => $request->price_type,
                "is_ingredient" => $request->is_ingredient,
                "status" => $request->status,
                "inventory_type" => $request->inventory_type,
                "shows_in" => $request->shows_in,
                "show_description_in" => $request->show_description_in,
                "created_by" => $request->logged_user_id,
                "barcode" => $request->barcode,
                "measurement_id" => $request->measurement_id,
                "brand_id" => $request->brand_id,
                "product_border_color" => $request->product_border_color,
                "product_manufacturer_date" => $request->product_manufacturer_date,
                "product_expiry_date" => $request->product_expiry_date,
                "is_taxable" => ($request->is_taxable == 'true') ? 1 : 0,
                "quantity" => ($request->unlimited_quantity == 'true') ? -1 : $request->quantity,
                "alert_quantity" => (!isset($request->alert_quantity)) ? 0.00 : $request->alert_quantity,
                "supplier_id" => (!empty($request->supplier)) ? SupplierModel::where('slack', $request->supplier)->first()->id : "",
                "sales_price_including_boolean_and_price" => isset($request->sales_price_including_boolean_and_price) ? $request->sales_price_including_boolean_and_price : '',
                "updated_at"=>Carbon::now()
            ];

            // dd($product);

            if (isset($request->modifier)) {
                $product_modifiers_ids = explode(',', $request->modifier);
                $product_modifiers = [];

                ProductModifierModel::whereNotIn('modifier_id', $product_modifiers_ids)->where('product_id', $product_data->id)->delete();

                foreach ($product_modifiers_ids as $modifier_id) {

                    ProductModifierModel::updateOrCreate(
                        [
                            'product_id' => $product_data->id,
                            'modifier_id' => $modifier_id,
                        ],
                        [
                            'slack' => $this->generate_slack("product_modifiers"),
                            'product_id' => $product_data->id,
                            'modifier_id' => $modifier_id,
                            "status" => 1,
                            "created_by" => $request->logged_user_id
                        ]
                    );
                }
            }else{
                ProductModifierModel::where('product_id', $product_data->id)->delete();
            }


            // if($request->is_taxable == 'true'){
            //     $product['tax_code_id'] = $taxcode_data->id;
            // }else{
            //     $product['tax_code_id'] = 0;
            // }

            // if($request->unlimited_quantity == 'true'){
            //     $product['quantity'] = -1;
            //     $product['alert_quantity'] = 0.00;
            // }
            $product['tax_code_id'] = $request->tax_code_id ?? session('store_tax_code');
            $product['is_tobacco_tax'] = $request->is_tobacco_tax ?? 0;
            if ($request->is_tobacco_tax == 1) {
                $product['tobacco_tax_percentage'] = 100; // tax percentage
            } else {
                $product['tobacco_tax_percentage'] = 0; // tax percentage
            }

            if (!empty($request->supplier)) {
                $supplier_data = SupplierModel::select('id')
                    ->where('slack', '=', trim($request->supplier))
                    //->active()
                    ->first();
                $product['supplier_id'] = $supplier_data->id;
            }
            if (!empty($request->category)) {
                $product['category_id'] = $request->category;
            } else {
                $product['category_id'] = $request->main_category;
            }


            $product['product_thumb_image'] = $product_data->product_thumb_image;
            if ($request->hasFile('product_thumb_image')) {

                $image = $request->file('product_thumb_image');
                $product['product_thumb_image']   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('product')->put('/' . $product['product_thumb_image'], $img, 'public');

                // Delete old image
                if (!empty($product_data->product_thumb_image)) {
                    Storage::disk('product')->delete($product_data->product_thumb_image);
                }
            }

            $action_response = ProductModel::where('slack', $slack)->update($product);

            if(isset($request->product_prices) ){
                $this->update_multiple_prices($product_data,$request->product_prices);
            }

            // update product on ZID also 
            $zid_status = $this->check_zid_status();
            if (check_access(['A_SYNC_ZID_PRODUCT'], true) && $zid_status) {

                $zid_store_detail = StoreModel::find(session('store_id'))->first();

                if (empty($zid_store_detail)) {
                    throw new Exception(trans("Please update your Zid Store API token or Zid Store Id from store settings"), 400);
                }

                $zid_store_api_token = $zid_store_detail->zid_store_api_token;
                $zid_store_id = $zid_store_detail->zid_store_id;

                if ($product_data->zid_product_id == null && $request->zid_sync_option == "true") {
                    // Add as a new ZID Product
                    $this->zid_add_product($request, $product_data->id);
                } else if ($product_data->zid_product_id != null && $request->zid_sync_option == "true") {
                    // Update an already added ZID product
                    $this->zid_update_product($request, $product_data->zid_product_id);
                } else if ($product_data->zid_product_id != null && $request->zid_sync_option == "false") {
                    // Delete a ZID Product
                    $this->sync_zid_delete_product($zid_store_api_token, $zid_store_id, $product_data->zid_product_id);
                    ProductModel::where('id', $product_data->id)->update(['zid_product_id' => null]);
                }
            }

            $this->add_ingredients($request, $slack);

            $this->upload_product_images($request, $slack);

            if($request->quantity > $product_quantity){

                $increased_quantity = $request->quantity - $product_quantity;
                // Add quantity history
                $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_data->id,request()->logged_user_store_id,'PRODUCT','INCREMENT',$increased_quantity,$product_data->id);

            }else if($request->quantity < $product_quantity){
                
                $decreased_quantity = $product_quantity - $request->quantity;
                // Add quantity history
                $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_data->id,request()->logged_user_store_id,'PRODUCT','DECREMENT',$decreased_quantity,$product_data->id);
            }


            //Qoyod
            if(Session('qoyod_status')){
                $qoyod_product = ProductModel::withoutGlobalScopes()->select('products.id', 'products.store_id', 'products.name', 'products.slack', 'products.barcode', 'products.name', 'products.name_ar', 'products.description', 'products.quantity', 'products.purchase_amount_excluding_tax', 'products.sale_amount_excluding_tax', 'products.category_id', 'products.measurement_id', 'products.tax_code_id','qoyod_products.qoyod_product_id')->leftJoin('qoyod_products','qoyod_products.product_id','=','products.id')->where('products.slack',$slack)->first();
                if(isset($qoyod_product) && $qoyod_product->qoyod_product_id>0){
                    $this->qoyod_update_product($qoyod_product,$qoyod_product->qoyod_product_id);
                }
            }
            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product updated successfully"),
                    "data"    => $slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function validate_request($request)
    {
        $request->merge(['ingredients' => json_decode($request->ingredients, true)]);

        $validator = Validator::make($request->all(), [
            'product_name' => $this->get_validation_rules("name_label", true),
            'product_code' => $this->get_validation_rules("codes", false),
            'purchase_price' => $this->get_validation_rules("numeric", true),
            "sale_price" => "required_if:price_type,==,fixed",
            'quantity' => $this->get_validation_rules("numeric", false),
            'alert_quantity' => $this->get_validation_rules("numeric", false),
            // 'supplier' => $this->get_validation_rules("slack", true),
            // 'category' => $this->get_validation_rules("slack", true),
            // 'tax_code' => $this->get_validation_rules("slack", true),
            'description' => $this->get_validation_rules("text", false),
            'status' => $this->get_validation_rules("status", true),
            'product_images.*' => $this->get_validation_rules("product_image", false)
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    /**
     * get products from order page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_product(Request $request)
    {

        $product_data = [];
        $data['product_data'] = [];

        try {

            $barcode = $request->barcode;
            $product_title = $request->product_title;
            $product_code = $request->product_code;
            $product_category = $request->product_category;
            $parent_category_id = $request->parent_category_id;
            $search = $request->search;

            $type = 1; // normal search

            $search_by_barcode = ProductModel::select('products.*')->with('product_modifiers')->categoryJoin()
                ->supplierJoin()
                ->Active()
                ->categoryActive()
                ->productTypePos()
                ->mainProduct()
                ->get();

            if ($search != '' && isset($search_by_barcode) && count($search_by_barcode) > 0) {

                $product_data = $search_by_barcode;
                $type = 2; // search by barcode

            } else {

                $query = ProductModel::select('products.*')->with('product_modifiers')->categoryJoin()
                    ->supplierJoin()
                    ->Active()
                    //            ->taxcodeJoin()
                    //            ->discountcodeJoin()
                    ->categoryActive()
                    //            ->supplierActive()
                    //            ->taxcodeActive()
                    //  ->quantityCheck()
                    ->productTypePos()
                    ->mainProduct();

                if (isset($search) && $search != '') {
                    $query->where([
                        ['products.product_code', 'like', '%' . trim($search) . '%']
                    ]);

                    $query->orWhere([
                        ['products.name', 'like', '%' . trim($search) . '%']
                    ]);
                }


                // if (isset($product_code) && $product_code != '') {
                //     $query->where([
                //         ['products.product_code', 'like', '%' . trim($product_code) . '%']
                //     ]);
                // }
                // if (isset($product_title) && $product_title != '') {
                //     $query->where([
                //         ['products.name', 'like', '%' . trim($product_title) . '%']
                //     ]);
                // }


                if (isset($product_category) && $product_category != '' && $product_category != '0') {

                    $query->where([
                        ['category.slack', '=', trim($product_category)]
                    ]);
                }

                if (isset($parent_category_id) && $parent_category_id != '' && $parent_category_id != '0') {

                    $query->where([
                        ['category.id', '=', trim($parent_category_id)]
                    ]);
                    $query->orWhere([
                        ['category.parent', '=', trim($parent_category_id)]
                    ]);
                }

                // if ($product_code == '' && $product_title == '' && $product_category == '') {
                //     $query->orderProduct()
                //         ->orderJoin()
                //         ->where('orders.status', 1) //closed orders
                //         ->orderBy('order_products.quantity', 'DESC')
                //         ->groupBy('product_code')
                //         ->limit(10);
                // }

                $product_data = $query->get();
            }

            $total_count = count($product_data);
            $product_data = ProductResource::collection($product_data);

            if (!empty($product_data)) {

                foreach ($product_data as $product) {

                    $dataset = [];
                    $dataset = $product;
                    $requested_product_quantity = 1;

                    if ($this->__check_ingredient_stock($product->id, $requested_product_quantity)) {
                        $dataset['ingredient_low_stock'] = 1;
                    } else {
                        $dataset['ingredient_low_stock'] = 0;
                    }

                    $data['product_data'][] = $dataset;
                }
            }

            if (empty($data['product_data'])) {
                throw new Exception("Product not available", 400);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $data['product_data'],
                    "type"    => $type,
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function load_pos_products(Request $request)
    {

        $data['products_data'] = [];

        $products_counter = json_decode($request->products_counter);

        try {

            $query = ProductModel::select(['products.*', 'tax_codes.total_tax_percentage', 'tax_codes.label as tax_code_label'])
                ->with('product_modifiers')->categoryJoin()
                ->supplierJoin()
                ->Active()
                ->categoryActive()
                ->productTypePos()
                ->taxcodeJoin()
                ->mainProduct();


            $total = $query->get()->count();

            $products_data = $query->skip($products_counter->offset)->take($this->product_limit)->get();
            $total_count = count($products_data);

            $data['products_counter']['offset'] = $products_counter->offset + $this->product_limit; // default offset
            $data['products_counter']['total'] = $total;

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

                    $data['products_data'][] = $dataset;
                }
            }

            if (empty($data['products_data'])) {
                throw new Exception("Product not available", 400);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function load_pos_products_by_subcategory(Request $request)
    {

        $data['products_data'] = [];

        $products_counter = json_decode($request->products_counter);
        $subcategory_slack = $request->subcategory_slack;
        
        $category_type = $request->category_type;
        
        try {

            $products_data = ProductModel::select(['products.*', 'tax_codes.total_tax_percentage', 'tax_codes.label as tax_code_label'])
                ->with('product_modifiers')->categoryJoin()
                ->supplierJoin()
                ->Active()
                ->categoryActive()
                ->productTypePos()
                ->taxcodeJoin()
                ->mainProduct();

            if ($subcategory_slack != '0') {

                $products_data->when($category_type == 'CHILD', function ($query) use ($subcategory_slack) {
                    $query->where('category.slack', $subcategory_slack);
                });

                $products_data->when($category_type == 'PARENT', function ($query) use ($subcategory_slack) {
                    $query->where('category.id', trim($subcategory_slack));
                    $query->orWhere('category.parent', trim($subcategory_slack));
                });
            }

            $total = $products_data->get()->count();

            $products_data = $products_data->take($this->product_limit)->get();

            $total_count = count($products_data);

            $data['products_counter']['offset'] = 0 + $this->product_limit; // default offset
            $data['products_counter']['total'] = $total;

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

                    $data['products_data'][] = $dataset;
                }
            }

            $data['combos'] = [];
            if($category_type == 'PARENT'){

                $category_id = $request->subcategory_slack;
                $combos = Combo::select('id','name','is_discount_enabled','discount_type','discount_value')->where('category_id',$category_id)->active()->get();
                
                foreach($combos as $combo){

                    $combo_products = [];
                    
                    $dataset = [];
                    $dataset['combo'] = $combo; 
                    
                    $combo_sizes = $combo->sizes->pluck('id','size_name');
                    $combo_subgroups = $this->__get_combo_groups($combo->id);
                    
                    $dataset['combo_groups'] = array_values(array_unique(array_column($combo_subgroups,'group_name')));
                    
                    $i=0;
                    foreach($combo_sizes as $size_name => $size_id){

                        $j=0;
                        $group_items = [];
                        foreach($dataset['combo_groups'] as $group_name){
                            
                            $subgroup_items = [];
                            foreach($combo_subgroups as $subgroup){
                                
                                if($subgroup['group_name'] == $group_name){

                                    $products = ComboProduct::where('combo_size_id',$size_id)
                                    ->where('combo_group_id',$subgroup['subgroup_id'])  
                                    ->get();

                                    foreach($products as $product){
                                        $subgroup_items[] = new ComboProductResource($product);
                                    }

                                }
                            }

                            $group_items[] = $subgroup_items;

                        }
                        
                        $combo_products[$i] = $group_items;

                        $i++;
                    }

                    $dataset['combo_products'] = $combo_products;
                    $data['combos'][] = $dataset;
                }
                
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    private function __get_combo_groups($combo_id){

        $combo_group_data = [];
        $combo_group_ids = ComboProduct::select('combo_group_id')->where('combo_id',$combo_id)->groupBy('combo_group_id')->pluck('combo_group_id');
        foreach($combo_group_ids as $combo_group_id){
            $group = ComboGroup::find($combo_group_id);
            $dataset = [];
            $dataset['group_id'] = ($group->parent != null) ? $group->parent : $combo_group_id;
            $dataset['group_name'] = ($group->parent != null) ? ComboGroup::find($group->parent)->name : $group->name;
            $dataset['subgroup_id'] = $combo_group_id;
            $dataset['subgroup_name'] = $group->name;
            $combo_group_data[] = $dataset; 
        }

        return $combo_group_data ;
    }

    public function load_pos_products_by_keyword(Request $request)
    {

        $data['products_data'] = [];

        $products_counter = json_decode($request->products_counter);
        $subcategory_slack = $request->subcategory_slack;
        $search_term = $request->search_term;
        $search_type = $request->search_type;

        $category_type = $request->category_type;

        try {

            $products_data = ProductModel::select(['products.*', 'tax_codes.total_tax_percentage', 'tax_codes.label as tax_code_label'])
                ->with('product_modifiers')->categoryJoin()
                ->supplierJoin()
                ->Active()
                ->categoryActive()
                ->productTypePos()
                ->taxcodeJoin()
                ->mainProduct();

            $products_data->when($search_type == 'BARCODE', function ($query) use ($search_term) {
                $query->where('products.barcode', trim($search_term));
            });

            if ($subcategory_slack != '0') {

                $products_data->when($category_type == 'CHILD', function ($query) use ($subcategory_slack) {
                    $query->where('category.slack', $subcategory_slack);
                });

                $products_data->when($category_type == 'PARENT', function ($query) use ($subcategory_slack) {
                    $query->where('category.id', trim($subcategory_slack));
                    $query->orWhere('category.parent', trim($subcategory_slack));
                });
            }

            $products_data->when($search_type == 'KEYWORD', function ($query) use ($search_term) {
                $query->where('products.name', 'like', '%' . $search_term . '%');
                $query->orWhere('products.product_code', 'like', '%' . $search_term . '%');
            });

            $total = $products_data->get()->count();

            $products_data = $products_data->take($this->product_limit)->get();

            $total_count = count($products_data);

            $data['products_counter']['offset'] = 0 + $this->product_limit; // default offset
            $data['products_counter']['total'] = $total;

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

                    $data['products_data'][] = $dataset;
                }
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function get_product_detail(Request $request)
    {
        $slack = $request->slack;
        $data['measurement_categories_data'] = MeasurementCategoryModel::select('id', 'slack', 'label')->sortLabelAsc()->active()->get();
        $data['measurement_category_id'] = null;
        $data['measurements_data'] = MeasurementModel::singleValue()->active()->get();

        $product = ProductModel::with('measurements')->where('slack', $slack)->first();
        if ($product != null) {
            if (isset($product->measurements)) {
                $data['measurement_category_id'] = $product->measurements->measurement_category_id;
                $data['measurements_data'] = MeasurementModel::where('measurement_category_id', $data['measurement_category_id'])->get();
            }

            if ($this->__check_ingredient_stock($product->id, 1)) {
                $product['ingredient_low_stock'] = 1;
            } else {
                $product['ingredient_low_stock'] = 0;
            }
            $data['product'] = $product;
        } else {
            $data['product'] = [];
        }
        return response()->json($data);
    }


    public function check_product_quantity(Request $request)
    {
        $slack = trim($request->slack);
        $quantity = trim($request->quantity);



        $product = ProductModel::with('measurements')->where('slack', $slack)->first();


        if (($product->quantity < $quantity) && ($product->quantity != '-1.00')) {
            $product['low_product_stock'] =   1;
        } else {
            $product['low_product_stock'] = 0;
        }


        if ($this->__check_ingredient_stock($product->id, $quantity)) {
            $product['ingredient_low_stock'] = 1;
        } else {
            $product['ingredient_low_stock'] = 0;
        }

        $data['product'] = $product;

        return response()->json($data);
    }

    public function check_cart_ingredient_stock(Request $request)
    {
        return $this->__check_ingredient_stock($request->product_id, $request->quantity); // true = low on ingredient or false
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

                        if (isset($measurement_conversion)) {
                            $quantity = ((float) $measurement_conversion->value * $product_ingredient->quantity) * $requested_product_quantity;

                            if ($ingredient->quantity < $quantity && ($ingredient->quantity != '-1.00')) {
                                $low_ingredient_stock = 1;
                            }
                        } else {
                            // dd($measurement_conversion);
                            return 0;
                        }
                    }
                }
            }
        }

        return $low_ingredient_stock;
    }

    public function get_product_modifiers(Request $request)
    {

        try {

            $product_data = ProductModel::select('id', 'slack')->where('slack', $request->product_slack)->first();
            $modifiers =
                ProductModifierModel::join('modifiers', 'modifiers.id', 'product_modifiers.modifier_id')
                ->where('product_id', $product_data->id)
                ->select('modifiers.*')
                ->get();

            $data['modifiers'] = [];
            if (!empty($modifiers)) {
                foreach ($modifiers as $modifier) {
                    $dataset = [];
                    $dataset['id'] = $modifier['id'];
                    $dataset['label'] = $modifier['label'];
                    $dataset['is_multiple'] = $modifier['is_multiple'];
                    $dataset['modifier_options'] = ModifierOptionModel::select('id', 'label', 'price')->where('modifier_id', $modifier['id'])->get();
                    $data['modifiers'][] = $dataset;
                }
            }

            $data['product_id'] = $product_data->id;
            $data['product_slack'] = $product_data->slack;

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product Modifier listed successfully"),
                    "data"    => $data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function generate_barcodes(Request $request)
    {
        try {
            //$request->merge(['products' => json_decode($request->products, true)]);

            $validator = Validator::make($request->all(), [
                'quantity' => 'min:1|numeric',
                'product_name' => 'required',
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }
            $input = $request->all();
            //$product_array = $request->products;

            // $upload_folder = Config::get('constants.upload.barcode.dir');
            // $upload_path = Config::get('constants.upload.barcode.upload_path');
            // $view_path = Config::get('constants.upload.barcode.view_path');
            $generator  = new \Picqer\Barcode\BarcodeGeneratorPNG();

            $barcode_type = $generator::TYPE_CODE_128;
            $barcode_array = [];
            $remove_file_array = [];
            $download_link = '';

            if (!isset($request->product_slack) || empty($request->product_slack)) {
                throw new Exception(trans("Product details are missing"), 400);
            }

            // if (empty((array)$product_array)) {
            //     throw new Exception(trans("Product list should not be empty"), 400);
            // }

            $product_data = ProductModel::select('id', 'slack', 'product_code', 'name', 'sale_amount_excluding_tax', 'barcode', 'is_ingredient')
                ->where('slack', '=', $request->product_slack)
                ->active()
                ->first();

            if (empty($request->barcode_no)) {
                $product_data->barcode = $this->generate_product_barcode();
                $product_data->update(['barcode' => $product_data->barcode]);
            }

            if (empty($product_data)) {
                throw new Exception(trans("Invalid product provided"), 400);
            }

            $input['product_id'] = $product_data->id;
            $input['product_slack'] = $product_data->slack;
            $input['is_ingredient'] = $product_data->is_ingredient;
            $input['barcode_no'] = $product_data->barcode;
            $input['store_id'] = request()->logged_user_store_id;
            $input['show_barcode_value'] = (isset($request->show_barcode_value) && $request->show_barcode_value == 'on') ? 1 : 0;
            $input['show_item_name'] = (isset($request->show_item_name) && $request->show_item_name == 'on') ? 1 : 0;
            $input['show_item_price_with_vat'] = (isset($request->show_item_price_with_vat) && $request->show_item_price_with_vat == 'on') ? 1 : 0;
            $input['show_store_name'] = (isset($request->show_store_name) && $request->show_store_name == 'on') ? 1 : 0;
            $input['show_manufacturing_date'] = (isset($request->show_manufacturing_date) && $request->show_manufacturing_date == 'on') ? 1 : 0;
            $input['manufacturing_date'] = (isset($request->manufacturing_date) && !empty($request->manufacturing_date)) ? $request->manufacturing_date : NULL;
            $input['show_expiry_date'] = (isset($request->show_expiry_date) && $request->show_expiry_date == 'on') ? 1 : 0;
            $input['expiry_date'] = (isset($request->expiry_date) && !empty($request->expiry_date)) ? $request->expiry_date : NULL;
            $input['show_notes'] = (isset($request->show_notes) && $request->show_notes == 'on') ? 1 : 0;
            $input['notes'] = $request->notes;
            $input['status'] = 1;
            $input['updated_by'] = $request->logged_user_id;

            $barcodeDetailEditData = ProductBarcodeDetail::where('product_slack', $request->product_slack)->first();
            if (!empty($barcodeDetailEditData)) {
                $barcodeDetailEditData->update($input);
            } else {
                $productBarcodeDetail = new ProductBarcodeDetail;
                $input['created_by'] = $request->logged_user_id;
                $productBarcodeDetail->fill($input)->save();
            }
            $productBarcodeDetails = ProductBarcodeDetail::with('storeData')->where('product_slack', $request->product_slack)->first();
            if (!empty($productBarcodeDetails)) {
                $generator  = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $productBarcodeDetails_data = new ProductBarcodeDetailResource($productBarcodeDetails);
            }
            //$barcode_data = $generator->getBarcode($product_code, $barcode_type);
            $barcode_data = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($product_data->barcode, $generator::TYPE_CODE_128)) . '" style="width: 100%;">';
            return response()->json(
                $this->generate_response(
                    array(
                        "message" => trans("Barcodes generated successfully"),
                        'barcode' => $barcode_data,
                        'barcode_data' => $productBarcodeDetails_data,
                    ),
                    'SUCCESS'
                )
            );

            // foreach ($product_array as $product_array_item) {

            //     return $barcode_data;
            //     exit;
            //     echo $barcode_data;

            //     $filename = $product_slack . ".jpg";

            //     Storage::disk('public')->put($upload_folder . $filename, $barcode_data);

            //     $barcode_path = $upload_path . $filename;
            //     $image_resize = Image::make($barcode_path);
            //     $image_resize->resize(300, 100);
            //     $image_resize->save($barcode_path);

            //     $barcode_array[] = [
            //         'product_code' => $product_code,
            //         'count' => $product_array_item['quantity'],
            //         'product_name' => Str::limit($product_array_item['name'], 28),
            //         'price' => $request->logged_user_store_currency . ' ' . $price,
            //         'product_barcode' => $barcode_path,
            //     ];

            //     $remove_file_array[] = $upload_folder . $filename;
            // }

            // if (count($barcode_array) > 0) {

            //     $date = Carbon::now();
            //     $current_date = $date->format('d-m-Y H:i');
            //     $store = $request->logged_user_store_code . '-' . $request->logged_user_store_name;

            //     $print_barcode_page = view('product.barcode.barcode_print', ['data' => $barcode_array, 'store' => $store, 'date' => $current_date])->render();

            //     $pdf_filename = "barcode_export_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";

            //     ini_set("pcre.backtrack_limit", "5000000");
            //     set_time_limit(180);

            //     $mpdf_config = [
            //         'mode'          => 'utf-8',
            //         'format'        => 'A4',
            //         'orientation'   => 'L',
            //         'margin_left'   => 0,
            //         'margin_right'  => 0,
            //         'margin_top'    => 0,
            //         'margin_bottom' => 0,
            //         'margin_footer' => 1,
            //         'tempDir' => storage_path() . "/pdf_temp"
            //     ];

            //     $css_file = 'css/barcode_print.css';
            //     $stylesheet = File::get(public_path($css_file));
            //     $mpdf = new Mpdf($mpdf_config);
            //     $mpdf->curlAllowUnsafeSslRequests = true;
            //     $mpdf->SetDisplayMode('real');
            //     $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            //     $mpdf->SetHTMLFooter('<div class="footer">store: ' . $store . ' | generated on: ' . $current_date . ' | page: {PAGENO}/{nb}</div>');
            //     $mpdf->WriteHTML($print_barcode_page);
            //     $mpdf->Output(public_path('storage/barcode') . '/' . $pdf_filename, \Mpdf\Output\Destination::FILE);

            //     $download_link = asset($view_path . $pdf_filename);
            // }

            //Storage::disk('public')->delete($remove_file_array);

            // return response()->json($this->generate_response(
            //     array(
            //         "message" => trans("Barcodes generated successfully"),
            //         'link' => ($download_link != '') ? $download_link : ''
            //     ),
            //     'SUCCESS'
            // ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * get products for po page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function load_product_for_po(Request $request)
    {
        try {

            $keywords = $request->keywords;

            // $supplier_slack = $request->supplier;

            $query = ProductModel::select(
                'products.slack as product_slack',
                'products.product_code as product_code',
                'products.name as label',
                'products.is_taxable',
                'products.purchase_amount_excluding_tax',
                'tax_codes.total_tax_percentage as tax_percentage',
                'discount_codes.discount_percentage as discount_percentage'
            )
                ->categoryJoin()
                ->supplierJoin()
                ->taxcodeJoin()
                ->discountcodeJoin()
                // ->categoryActive()
                // ->supplierActive()
                // ->taxcodeActive()
                // ->where('suppliers.slack', $supplier_slack)
                ->where(function ($query) use ($keywords) {
                    $query->where('products.product_code', 'like', $keywords . '%')
                        ->orWhere('products.name', 'like', $keywords . '%');
                });

            $product_data = $query->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $product_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function load_product_for_stock_transfer(Request $request)
    {
        try {

            $keywords = $request->keywords;

            $query = ProductModel::select('products.slack as product_slack', 'products.product_code as product_code', 'products.name as label', 'products.quantity')
                ->categoryJoin()
                // ->supplierJoin()
                ->taxcodeJoin()
                ->discountcodeJoin()
                // ->categoryActive()
                // ->supplierActive()
                ->taxcodeActive()
                ->where(function ($query) use ($keywords) {
                    $query->where('products.product_code', 'like', $keywords . '%')
                        ->orWhere('products.name', 'like', $keywords . '%');
                });

            $product_data = $query->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product listed successfully"),
                    "data"    => $product_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function upload_product_images(Request $request, $product_slack)
    {
        if ($request->hasFile('product_images')) {
            $product_data_exists = ProductModel::select('id')
                ->where('slack', '=', trim($product_slack))
                ->first();

            if (!empty($product_data_exists)) {

                $product_id = $product_data_exists->id;

                $upload_dir = Config::get('constants.upload.product.upload_path');
                $product_images_array = $request->product_images;

                foreach ($product_images_array as $product_images_array_item) {

                    $extension = $product_images_array_item->getClientOriginalExtension();
                    $file_name = $product_slack . '_' . uniqid() . '.' . $extension;
                    $path = Storage::disk('product')->putFileAs('/', $product_images_array_item, $file_name);
                    $file_name = basename($path);

                    $image = Image::make($product_images_array_item);
                    $file_path = $upload_dir . 'thumb_' . $file_name;
                    $image->fit(150);
                    $image->fit(150, 150, function ($constraint) {
                        $constraint->upsize();
                    });
                    $image->save($file_path);
                    $image->destroy();

                    $status_data = MasterStatus::select('value')->filterByValueConstant('PRODUCT_IMAGE_STATUS', 'ACTIVE')->first();

                    $product_image_array = [
                        "slack" => $this->generate_slack("product_images"),
                        "product_id" => $product_id,
                        "filename" => $file_name,
                        "status" => $status_data->value,
                        "created_by" => $request->logged_user_id
                    ];

                    $product_image_id = ProductImagesModel::create($product_image_array)->id;
                }
            }
        }
    }

    public function delete_product_image(Request $request)
    {
        try {

            $image_slack = $request->image_slack;

            $product_image_data = ProductImagesModel::select('filename')
                ->where('slack', '=', trim($image_slack))
                ->first();

            DB::beginTransaction();

            ProductImagesModel::where('slack', $image_slack)
                ->delete();

            DB::commit();

            if (!empty($product_image_data)) {
                Storage::disk('product')->delete(
                    [
                        $product_image_data->filename,
                        'thumb_' . $product_image_data->filename
                    ]
                );
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product image deleted successfully"),
                    "data"    => $image_slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function load_ingredients(Request $request)
    {

        try {

            $keywords = $request->keywords;

            $query = ProductModel::select('products.*')
                ->categoryJoin()
                ->supplierJoin()
                // ->taxcodeJoin()
                ->discountcodeJoin()
                ->categoryActive()
                ->supplierActive()
                // ->taxcodeActive()
                ->quantityCheck()
                ->isIngredient();
            // ->where(function($query) use ($keywords){
            //     $query->where('products.product_code', 'like', $keywords.'%')
            //     ->orWhere('products.name', 'like', $keywords.'%');
            // });

            $product_data = $query->get();


            $product_data = ProductResource::collection($product_data);

            if (empty($product_data)) {
                throw new Exception(trans("Ingredient not available"), 400);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Ingredient list loaded successfully"),
                    "data"    => $product_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function add_ingredients(Request $request, $product_slack)
    {
        $product_ingredient_array = [];

        $product_data = ProductModel::select('id', 'is_ingredient')
            ->where('slack', '=', trim($product_slack))
            ->first();

        $restaurant_mode = $request->logged_user_store_restaurant_mode;

        if (!empty($product_data) && $restaurant_mode == 1 && isset($product_data->id)) {

            if ($request->is_ingredient == false && !empty($request->ingredients)) {


                $ingredients = $request->ingredients;
                $is_ingredient_price = $request->is_ingredient_price;

                $item_sale_price = 0;
                $item_purchase_price = 0;

                ProductIngredientModel::where('product_id', $product_data->id)->delete();

                foreach ($ingredients as $key => $ingredient) {

                    if (!empty($ingredient['ingredient_slack'])) {

                        $ingredient_data = ProductModel::select('id', 'sale_amount_excluding_tax', 'purchase_amount_excluding_tax')
                            ->where('slack', '=', trim($ingredient['ingredient_slack']))
                            ->active()
                            ->first();

                        if (empty($ingredient_data)) {
                            throw new Exception(trans("Invalid ingredient selected at line") . ($key + 1), 400);
                        }

                        //                        $ingredient['id'] = $ingredient['unit_slack'];
                        // $measurement_data = MeasurementModel::select('id')
                        // ->where('id', '=', trim($ingredient['unit_slack']))
                        // ->active()
                        // ->first();

                        // dd($ingredient['id']);

                        if (empty($ingredient_data)) {
                            throw new Exception(trans("Invalid ingredient selected at") . ($key + 1), 400);
                        }

                        $product_ingredient_array[] = [
                            "slack" => $this->generate_slack("product_ingredients"),
                            "product_id" => $product_data->id,
                            "ingredient_product_id" => $ingredient_data->id,
                            "quantity" => $ingredient['quantity'],
                            "measurement_id" => (isset($ingredient['measurement_id'])) ? $ingredient['measurement_id'] : '',
                            "created_by" => $request->logged_user_id
                        ];

                        $individual_sale_price = ($ingredient['quantity'] * $ingredient_data->sale_amount_excluding_tax);
                        $individual_purchase_price = ($ingredient['quantity'] * $ingredient_data->purchase_amount_excluding_tax);

                        $item_sale_price = $item_sale_price + $individual_sale_price;
                        $item_purchase_price = $item_purchase_price + $individual_purchase_price;
                    }
                }

                if (!empty($product_ingredient_array) && count($product_ingredient_array) > 0) {
                    foreach ($product_ingredient_array as $product_ingredient_array_item) {
                        ProductIngredientModel::create($product_ingredient_array_item);
                    }
                }

                if ($is_ingredient_price == true || $is_ingredient_price == 1) {
                    $product = [
                        "sale_amount_excluding_tax" => $item_sale_price,
                        "purchase_amount_excluding_tax" => $item_purchase_price,
                        "is_ingredient_price" => 1
                    ];
                    ProductModel::where('id', $product_data->id)->update($product);
                }else{
                    ProductModel::where('id', $product_data->id)->update(["is_ingredient_price" => 0]);
                }
            } else if ($request->is_ingredient == true) {
                ProductIngredientModel::where('product_id', $product_data->id)->delete();
                $product = [
                    "is_ingredient_price" => 0
                ];
                ProductModel::where('id', $product_data->id)->update($product);
            } else if ($request->is_ingredient == false && empty($request->ingredients)) {

                ProductIngredientModel::where('product_id', $product_data->id)->delete();
            }
        } else if ($restaurant_mode == 0 && isset($product_data->id)) {
            ProductIngredientModel::where('product_id', $product_data->id)->delete();

            $product = [
                "is_ingredient" => 0,
                "is_ingredient_price" => 0
            ];
            ProductModel::where('id', $product_data->id)
                ->update($product);
        }
    }

    public function load_products(Request $request)
    {
        $supplier_data = SupplierModel::where('slack', $request->supplier)->first();
        $product_data = ProductModel::select('slack', 'name')->where('supplier_id', $supplier_data->id)->active()->get();
        return response()->json($product_data);
    }

    public function load_products_by_category(Request $request)
    {

        $products = ProductModel::where('category_id', $request->category_id)->active()->get();
        return response()->json($products);
    }

    public function get_products_by_category(Request $request)
    {

        $products = ProductModel::where('category_id', $request->category_id)->active()->get();
        $products = ProductResource::collection($products);
        
        return response()->json($products);
    }
    
    public function get_products_by_category_with_pagination(Request $request)
    {

        try{

            $products = ProductModel::where('category_id', $request->category_id)->active()->paginate(30);
            $products = new ProductCollection($products);
    
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Product list loaded successfully"),
                    "data"    => $products
                ),
                'SUCCESS'
            ));

        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
        
    }

    public function calculate_ingredient_cost(Request $request)
    {

        try {

            $ingredients = json_decode($request->ingredients);

            $data['ingredient_costs'] = [];
            $data['ingredient_total_purchase_cost'] = 0;
            $data['ingredient_total_sale_cost'] = 0;

            foreach ($ingredients as $ingredient) {

                $dataset = [];
                $cart_ingredient_slack = $ingredient->ingredient_slack;
                $cart_measurement_id = $ingredient->measurement_id;

                $ingredient_detail = ProductModel::select('measurement_id', 'purchase_amount_excluding_tax', 'sale_amount_excluding_tax')->where('slack', $cart_ingredient_slack)->first();
                $ingredient_measurement_id = $ingredient_detail->measurement_id;
                $ingredient_purchase_price_per_unit = $ingredient_detail->purchase_amount_excluding_tax;
                $ingredient_sale_price_per_unit = $ingredient_detail->sale_amount_excluding_tax;

                $measurement = MeasurementModel::find($cart_measurement_id);

                if (!empty($measurement)) {

                    $dataset['ingredient_slack'] = $cart_ingredient_slack;
                    if ($ingredient_measurement_id == $cart_measurement_id) {

                        // standard rate per quantity
                        $dataset['ingredient_purchase_price_per_unit'] = (float) $ingredient_purchase_price_per_unit;
                        $dataset['ingredient_sale_price_per_unit'] = (float) $ingredient_sale_price_per_unit;

                        // echo $dataset['ingredient_sale_price_per_unit'];
                        // // echo $ingredient->quantity;
                        // exit;
                        // amount based on selected quantities
                        $dataset['ingredient_purchase_amount_per_unit'] = (float) $dataset['ingredient_purchase_price_per_unit'] * $ingredient->quantity;
                        $dataset['ingredient_sale_amount_per_unit'] = (float) $dataset['ingredient_sale_price_per_unit'] * $ingredient->quantity;

                        $data['ingredient_total_purchase_cost'] += $dataset['ingredient_purchase_amount_per_unit'];
                        $data['ingredient_total_sale_cost'] += $dataset['ingredient_sale_amount_per_unit'];
                    } else {

                        $conversion = MeasurementConversionModel::where([
                            'from_measurement_id' => $ingredient_measurement_id,
                            'to_measurement_id' => $cart_measurement_id
                        ])->first();

                        if (isset($conversion) && $conversion->value > 1) {

                            // standard rate per quantity
                            $dataset['ingredient_purchase_price_per_unit'] = (float) $ingredient_purchase_price_per_unit * 1 / $conversion->value;
                            $dataset['ingredient_sale_price_per_unit'] =  (float) $ingredient_sale_price_per_unit * 1 / $conversion->value;

                            $dataset['ingredient_purchase_amount_per_unit'] = (float) $dataset['ingredient_purchase_price_per_unit'] * (int) $ingredient->quantity;

                            $dataset['ingredient_sale_amount_per_unit'] = (float) $dataset['ingredient_sale_price_per_unit'] * (int) $ingredient->quantity;

                            $data['ingredient_total_purchase_cost'] += $dataset['ingredient_purchase_amount_per_unit'];
                            $data['ingredient_total_sale_cost'] += $dataset['ingredient_sale_amount_per_unit'];
                        }
                    }

                    $data['ingredient_total_sale_cost'] = number_format((float)$data['ingredient_total_sale_cost'], 2, '.', '');
                    $data['ingredient_total_purchase_cost'] = number_format((float)$data['ingredient_total_purchase_cost'], 2, '.', '');

                    $data['ingredient_costs'][] = $dataset;
                }
            }

            $response = [
                'data' => $data
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

    public function get_active_product()
    {

        $list = ProductModel::select('*')->active()->orderBy('created_at', 'desc')->get();

        $product_data = ProductResource::collection($list);

        return response()->json($this->generate_response(
            array(
                "message" => trans("Products loaded successfully"),
                "data"    => $product_data
            ),
            'SUCCESS'
        ));
    }

    public function zid_add_product($request, $new_product_id)
    {

        $category_id = (isset($request->category)) ? $request->category : $request->main_category;
        $category = CategoryModel::find($category_id)->toArray();

        $form_params = [
            "cost" => $request->purchase_price,
            "quantity" => ($request->unlimited_quantity == 'true') ? 0 : $request->quantity,
            "name" => [
                "ar" => $request->arabic_product_name,
                "en" => $request->product_name,
            ],
            "description" => [
                "ar" => $request->description_ar,
                "en" => $request->description,
            ],
            "price" => ($request->sale_price_including_tax != "") ? $request->sale_price_including_tax : $request->sale_price,
            "sale_price" => $request->sale_price,
            "is_published" => true,
            "is_infinite" => ($request->unlimited_quantity == 'true') ? true : false,
        ];

        $store_zid_product_response = $this->sync_zid_add_product($form_params);

        if ($store_zid_product_response['status'] == false) {
            throw new Exception(trans($store_zid_product_response['message']), 400);
        }

        // store the product id returnd by zid API
        $zid_product_id = $store_zid_product_response['data']['product_id'];

        $store_zid_product_category_response = $this->sync_zid_assign_category_to_product($category, $zid_product_id);

        if ($store_zid_product_category_response['status'] == false) {
            throw new Exception(trans($store_zid_product_category_response['message']), 400);
        }

        ProductModel::where('id', $new_product_id)->update(['zid_product_id' => $zid_product_id]);
    }

    public function zid_update_product($request, $zid_product_id)
    {

        $form_params = [
            "cost" => $request->purchase_price,
            "quantity" => ($request->unlimited_quantity == 'true') ? 0 : $request->quantity,
            "name" => [
                "en" => $request->product_name,
                "ar" => $request->arabic_product_name,
            ],
            "description" => [
                "en" => $request->description,
                "ar" => $request->description_ar,
            ],
            "price" => ($request->sale_price_including_tax != "") ? $request->sale_price_including_tax : $request->sale_price,
            "sale_price" => $request->sale_price,
            "is_published" => true,
            "is_infinite" => ($request->unlimited_quantity == 'true') ? true : false,
        ];

        $update_zid_product_response = $this->sync_zid_update_product($form_params, $zid_product_id);

        if (!$update_zid_product_response) {
            throw new Exception(trans("Product failed to add on Zid"), 400);
        }
    }

    public function generate_product_barcode()
    {

        return $this->generate_barcode_string('products');
    }

    public function add_quantity_adjustment(Request $request)
    {
        try {

            $data['menu_key'] = 'MM_STOCK';
            $data['sub_menu_key'] = 'A_ADD_QUANTITY_ADJUSTMENT';
            check_access(array($data['menu_key'], $data['sub_menu_key']));
            $this->validate_quantity_adjustment_request($request);
            $products = json_decode($request->products);
            if ($request->store_id == 0) {
                throw new Exception("Branch field is required");
            }
            if ($request->reason == "") {
                throw new Exception("Reason field is required");
            }
            if ($request->action == "") {
                throw new Exception("Action field is required");
            }
            
            if (count($products) == 0) {
                throw new Exception("Product field is required");
            }
            if ($request->status == "draft") {
                DB::beginTransaction();
                $quantity_adjustment_obj['reference'] = "";
                $quantity_adjustment_obj['slack'] = $this->generate_slack("quantity_adjustments");
                $quantity_adjustment_obj['store_id'] = $request->store_id;
                $quantity_adjustment_obj['reason'] = $request->reason;
                $quantity_adjustment_obj['action'] = $request->action;
                $quantity_adjustment_obj['status'] = $request->status;
                $quantity_adjustment_obj['created_at'] = Carbon::now();
                $quantity_adjustment_obj['created_by'] =  $request->logged_user_id;
                $quantity_adjustment_result = QuantityAdjustments::create($quantity_adjustment_obj);
                foreach ($products as $product) {
                    $original_quantity = DB::select("select quantity from products where id={$product->id}");
                    if(count($original_quantity)>0 && (int)$product->quantity>(int)$original_quantity[0]->quantity && $request->action == 'DECREMENT')
                    {
                        throw new Exception("Entered Quantity For the product '{$product->name}' Exceeds available quantity!!!");
                    }
                    else
                    {
                      $quantity_adjustment_items = [];
                      $quantity_adjustment_items['quantity_adjustment_id'] = $quantity_adjustment_result->id;
                      $quantity_adjustment_items['product_id'] = $product->id;
                      $quantity_adjustment_items['quantity'] = $product->quantity;
                      $quantity_adjustment_items['created_at'] = Carbon::now();
                      $quantity_adjustment_items['updated_at'] = Carbon::now();
                      QuantityAdjustmentItems::create($quantity_adjustment_items);
                    }
                }
                DB::commit();
                $forward_link = route("quantity_adjustments");
                return response()->json($this->generate_response(
                    array(
                        "message" => trans("Quantity Adjustment saved as draft"),
                        "link"    => $forward_link
                    ),
                    'SUCCESS'
                ));
            } else {
                DB::beginTransaction();
                $quantity_adjustment_obj = [];
                $quantity_adjustment_obj['slack'] = $this->generate_slack("quantity_adjustments");
                $quantity_adjustment_obj['reference'] = $this->generateReference();
                $quantity_adjustment_obj['store_id'] = $request->store_id;
                $quantity_adjustment_obj['reason'] = $request->reason;
                $quantity_adjustment_obj['action'] = $request->action;
                $quantity_adjustment_obj['status'] = $request->status;
                $quantity_adjustment_obj['created_by'] =  $request->logged_user_id;
                $quantity_adjustment_obj['submitted_at'] = Carbon::now();
                if ($request->quantity_adjustment_slack != "") {
                    unset($quantity_adjustment_obj['slack']);
                    $quantity_adjustment_result = (object)QuantityAdjustments::where('slack', $request->quantity_adjustment_slack)->first();
                    $update = QuantityAdjustments::where('slack', $request->quantity_adjustment_slack)->update($quantity_adjustment_obj);
                } else {
                    $quantity_adjustment_result = QuantityAdjustments::create($quantity_adjustment_obj);
                }
                $products = json_decode($request->products);
                $this->delete_products($quantity_adjustment_result->id);
                foreach ($products as $product) {
                    $original_quantity = DB::select("select quantity from products where id={$product->id}");
                    if(count($original_quantity)>0 && (int)$product->quantity>(int)$original_quantity[0]->quantity && $request->action == 'DECREMENT')
                    {
                        throw new Exception("Entered Quantity For the product '{$product->name}' Exceeds available quantity!!!");
                    }
                    else
                    {
                      $quantity_adjustment_items = [];
                      $quantity_adjustment_items['quantity_adjustment_id'] = $quantity_adjustment_result->id;
                      $quantity_adjustment_items['product_id'] = $product->id;
                      $quantity_adjustment_items['quantity'] = $product->quantity;
                      $quantity_adjustment_items['created_at'] = Carbon::now();
                      $quantity_adjustment_items['updated_at'] = Carbon::now();
                      $this->updateProductQuantity($product->id, $product->quantity,$quantity_adjustment_result->id,$request->action);
                      QuantityAdjustmentItems::create($quantity_adjustment_items);
                    }
                }
                DB::commit();
                $forward_link = route("quantity_adjustments");
                return response()->json($this->generate_response(
                    array(
                        "message" => trans("Quantity Adjustment submitted successfully"),
                        "link"    => $forward_link
                    ),
                    'SUCCESS'
                ));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json($this->generate_response(
                array(
                    "message" => $ex->getMessage(),
                    "status_code" => $ex->getCode()
                )
            ));
        }
    }
    public function generateReference()
    {
        $quantityadjustments = QuantityAdjustments::where("status","closed")->get();
        $reference = "QA-00000" . (count($quantityadjustments)+1);
        return $reference;
    }
    public function updateProductQuantity($productid, $quantity,$quantity_adjustment_id,$action)
    {
        $product_details = ProductModel::where("id", $productid)->first();
        if($action == 'INCREMENT'){
            $product_quantity = (int)$product_details["quantity"] + (int)$quantity;
        }else{
            $product_quantity = (int)$product_details["quantity"] - (int)$quantity;
        }
        ProductModel::where("id", $productid)->update([
            "quantity" => $product_quantity
        ]);

        $this->addQuantityHistory($this->generate_slack('quantity_history'),$productid,request()->logged_user_store_id,'QUANTITY_ADJUSTMENT',$action,$quantity,$quantity_adjustment_id);

        // Add quantity history
    }
    public function delete_products($id)
    {
        QuantityAdjustmentItems::where('quantity_adjustment_id',$id)->delete();
    }
    public function validate_quantity_adjustment_request($request)
    {

        $validator = Validator::make($request->all(), [
            'store_id' => $this->get_validation_rules("numeric", true),
            'reason' => $this->get_validation_rules("text", false),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }
    public function quantity_adjustments(Request $request)
    {
        try {

            $data['menu_key'] = 'MM_STOCK';
            $data['sub_menu_key'] = 'A_VIEW_QUANTITY_ADJUSTMENT';
            check_access(array($data['menu_key'], $data['sub_menu_key']));

            $item_array = array();

            $status = (isset($request->status)) ? $request->status : '';

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;



            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];
            $search_data = isset($request->search['value']) ? $request->search['value'] : "";
            $filter_columns = ['quantity_adjustments.reference'];
            if ($status == "" || $status == "all") {
                $query = QuantityAdjustments::select('*')
                    ->whereRaw("status in ('closed','draft') and reference like '%{$search_data}%'")
                    ->take($limit)
                    ->skip($offset);
            } else {
                $query = QuantityAdjustments::select('*')
                    ->whereRaw("status='{$status}' and reference like '%{$search_data}%'")
                    ->take($limit)
                    ->skip($offset);
            }
            $count_query = $query;

            $query = $query->get();

            $quantity_adjustments = QuantityAdjustmentResource::collection($query);

            $total_q = QuantityAdjustments::all();


            $total_count = $total_q->count();

            foreach ($quantity_adjustments as $key => $quantity_adjustment) {



                $quantity_adjustment = $quantity_adjustment->toArray($request);

                $item_array[$key][] = empty($quantity_adjustment['reference']) ? "-" : $quantity_adjustment['reference'];
                $item_array[$key][] = $quantity_adjustment['branch'];
                $item_array[$key][] = $quantity_adjustment['action'];
                $item_array[$key][] = $quantity_adjustment['reason'];
                $item_array[$key][] = $quantity_adjustment['status'];
                $item_array[$key][] = empty($quantity_adjustment['submitted_at']) ? "-" : $quantity_adjustment['submitted_at'];
                $item_array[$key][] = $quantity_adjustment['created_by'];
                $item_array[$key][] = view('product.layouts.quantity_adjustment_actions', array('slack' => $quantity_adjustment['slack'], 'status' => $quantity_adjustment['status']))->render();
            }
            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];
            //print_r($response['data']);die;
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

    public function product_list_by_modified_date(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_PRODUCT_LISTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $from_date = $request->modified_date == '' ? '' : $request->modified_date;
            $from_date = str_replace('T', ' ', $from_date);

            $list = new ProductCollection(ProductModel::select('*')
                ->where('updated_at', '>=', $from_date)
                ->where('is_ingredient', 0)
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Products loaded successfully"),
                    "data"    => $list
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function add_multiple_prices($product,$prices){
        
        $prices = json_decode($prices,true);
        
        foreach($prices as $price){
            
            $price_data =  Price::withoutGlobalScopes()->where('price_code',$price['code'])->where('store_id',$product->store_id)->first();
  
            if( isset($price_data) ){
                
                ProductPrice::create([
                    'slack' => $this->generate_slack('product_prices'),
                    'product_id' => $product->id,
                    'price_id' => $price_data->id,
                    'sale_amount_excluding_tax' => $price['sale_price_excluding_tax'],
                    'sale_amount_including_tax' => $price['sale_price_including_tax']
                ]);
            
            }

        }

    }
    
    public function update_multiple_prices($product,$prices){
        
        $prices = json_decode($prices,true);
        
        foreach($prices as $price){
            
            $price_data =  Price::withoutGlobalScopes()->where('slack',$price['slack'])->first();
            
            if( isset($price_data) ){
                
                $product_price =  ProductPrice::where('price_id',$price_data->id)->where('product_id',$product->id)->first();

                if(isset($product_price)){
                    ProductPrice::where('id',$product_price->id)->update([
                        'sale_amount_excluding_tax' => $price['sale_price_excluding_tax'],
                        'sale_amount_including_tax' => $price['sale_price_including_tax']
                    ]);
                }else{
                    ProductPrice::create([
                        'slack' => $this->generate_slack('product_prices'),
                        'product_id' => $product->id,
                        'price_id' => $price_data->id,
                        'sale_amount_excluding_tax' => $price['sale_price_excluding_tax'],
                        'sale_amount_including_tax' => $price['sale_price_including_tax']
                    ]);
                }
            
            }

        }

    }
}


