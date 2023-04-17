<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DiscountcodeResource;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\Product;
use App\Models\Store as StoreModel;
use App\Models\Category as CategoryModel;
use App\Http\Resources\ProductResource;
use App\Models\Product as ProductModel;
use App\Http\Resources\Collections\DiscountcodeCollection;

use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;


class Discountcode extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['action_key'] = 'A_VIEW_DISCOUNTCODE_LISTING';
            if(check_access(array($data['action_key']), true) == false){
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;
            
            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));
            
            $query = DiscountcodeModel::select('discount_codes.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
            ->statusJoin()
            ->createdUser()

            ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                $query->orderBy($order_by_column, $order_direction);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                $query->where(function ($query) use ($filter_string, $filter_columns){
                    foreach($filter_columns as $filter_column){
                        $query->orWhere($filter_column, 'like', '%'.$filter_string.'%');
                    }
                });
            })

            ->get();
            $discount_codes = DiscountcodeResource::collection($query);
           
            $total_count = DiscountcodeModel::select("id")->get()->count();

            $item_array = [];
            foreach($discount_codes as $key => $discount_code){
                $discount_code = $discount_code->toArray($request);

                $item_array[$key][] = $discount_code['label'];
                $item_array[$key][] = $discount_code['discounttype']."_".$discount_code['discount_code'];
                $item_array[$key][] = $discount_code['discount_percentage'];
                $item_array[$key][] = $discount_code['discount_value'];
                $item_array[$key][] = (isset($discount_code['status']['label']))?view('common.status', ['status_data' => ['label' => $discount_code['status']['label'], "color" => $discount_code['status']['color']]])->render():'-';
                $item_array[$key][] = $discount_code['created_at_label'];
                $item_array[$key][] = $discount_code['updated_at_label'];
                $item_array[$key][] = (isset($discount_code['created_by']) && isset($discount_code['created_by']['fullname']))?$discount_code['created_by']['fullname']:'-';
                $item_array[$key][] = view('discount_code.layouts.discount_code_actions', array('discount_code' => $discount_code))->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];
            return response()->json($response);
        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function filter(Request $request){
        if($request->get("search_data")!="")
        {
          $searchstring = $request->get("search_data");
          $products = DB::select("select * from products where name like '%{$searchstring}%'");
          foreach($products as $product)
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
        }
        else
        { 
            $products = Product::all();
            foreach($products as $product)
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
        }
        return response()->json($products);
    }

    public function filterCategories(Request $request){
         if($request->get('search_data')!="")
         {
            $searchstring = $request->get("search_data");
            $categories = DB::select("select * from category where label like '%{$searchstring}%'");
         }
         else
         {
            $categories = DB::select("select * from category");
         }
         return response()->json($categories);
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

            if(!check_access(['A_ADD_DISCOUNTCODE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            if($request->discounttype=="code")
            {  
                $discount_code_exists = DiscountcodeModel::select('id')
                ->where('discount_code', '=', trim($request->discount_code))
                ->first();
                if (!empty($discount_code_exists)) {
                throw new Exception(trans("Discount code already exists : {$request->discount_code}"), 400);
                }
            }
            $product_amount = 0;
            if($request->discount_applied_on=="all_products")
            {
               $product = Product::select("sale_amount_excluding_tax")->where("is_ingredient",0)->active()->orderBy('sale_amount_excluding_tax', 'ASC')->first();
               if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="specific_products")
            {
                $product = Product::select("sale_amount_excluding_tax")->where("is_ingredient",0)->whereIn("id",explode(",",$request->discount_applicable_products))->Active()
                ->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="specific_product_categories")
            {
                $discount_categories = explode(",",$request->discount_applicable_categories);
                foreach($discount_categories as $discount_category)
                {
                    $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                    foreach($categories as $category) {
                        array_push($discount_categories,$category->id);
                    }
                }
                $product = Product::select("sale_amount_excluding_tax")->where("is_ingredient",0)->whereIn("category_id",$discount_categories)->Active()->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="all_products_except_specific")
            {
                $product = Product::select("sale_amount_excluding_tax")->where("is_ingredient",0)->whereNotIn("id",explode(",",$request->discount_not_applicable_products))->Active()
                ->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            if($request->discount_type=="amount")
            {
                if($product_amount<$request->discount_value)
                {
                    throw new Exception("Discount should be less than Minimum Product price");
                }
            }
            else
            {
                 $calculated_discount_amount = (0.01*$request->discount_percentage)*$product_amount;
                 if(($product_amount-$calculated_discount_amount)<0)
                 {
                     throw new Exception("Discount should be less than Minimum Product price");
                 }
            }
            if($request->is_always==0)
            {
              $discountstartdate = new \DateTime(date('Y-m-d H:i:s', strtotime("$request->discount_start_date $request->discount_start_time")));
              $discountenddate = new \DateTime(date('Y-m-d H:i:s', strtotime("$request->discount_end_date $request->discount_end_time")));
              if($discountstartdate>$discountenddate)
              {
                throw new Exception("Discount start date must be less than Discount end Date");
              }
            }
            if($request->is_always==0)
            {
            $discount_code_details = DiscountcodeModel::where('store_id',$request->logged_user_store_id)->get();
            foreach($discount_code_details as $discountcode)
            {
                $currentdate = date('Y-m-d H:i:sa');
                $discountstartdate = date('Y-m-d H:i:sa', strtotime("$request->discount_start_date $request->discount_start_time"));
                $discountenddate = date('Y-m-d H:i:sa', strtotime("$request->discount_end_date $request->discount_end_time"));
                $discountcodeinfo = DB::select("select * from discount_codes where (('{$discountstartdate}'>=discount_start_date and '{$discountstartdate}'<=discount_end_date) or ('{$discountenddate}'>=discount_start_date and '{$discountenddate}'<=discount_end_date)) and id = {$discountcode->id} and is_always=0 and status=1");
                if(count($discountcodeinfo)>0)
                {
                    $labels ="";
                    foreach($discountcodeinfo as $discount)
                    {
                        $labels.="'{$discount->label}',";
                    }
                    $labels = preg_replace("/,$/","",$labels);
                    throw new Exception("Discounts {$labels} have same time frame");
                }
            }
        }
        if($request->limit_on_discount!=-1)
        {
           if($request->limit_on_discount==0)
           {
               throw new Exception("limit on discount should be greater than 0");
           }
        }
        if($request->is_always==0)
        {
            $discountstartdate = date('Y-m-d H:i:s', strtotime("$request->discount_start_date $request->discount_start_time"));
            $discountenddate = date('Y-m-d H:i:s', strtotime("$request->discount_end_date $request->discount_end_time"));
            if(new \DateTime($discountstartdate)==new \DateTime($discountenddate))
            {
                throw new Exception("Discount have same start and end date");
            }    
        }
        if(strtolower($request->discount_type)=="amount")
        {
            if($request->discount_value==0)
            {
                throw new Exception("Discount amount should be greater than 0");
            }
        }
        else
        {
            if($request->discount_percentage==0)
            {
                throw new Exception("Discount percentage should be greater than 0");
            } 
        }
        $cashier_discounts = DiscountcodeModel::select('*')
                             ->where("store_id",$request->logged_user_store_id)
                             ->where("discounttype", "cashier")
                             ->where("status", 1)
                             ->get();

        if(count($cashier_discounts)>0)
        {
            if($request->discounttype=="cashier"  && (int)$request->status==1)
            {
                $label = "";
                foreach($cashier_discounts as $discount)
                {
                    if($discount->status==1)
                    {
                     $label.="'".$discount->label."'";
                     $label.=",";
                    }
                }
                $label = preg_replace("/,$/","",$label);
              throw new Exception("There is already an automatic cashier discount named {$label} added");
            }
        }
            DB::beginTransaction();
            $discount_code = [
                "slack" => $this->generate_slack("discount_codes"),
                "store_id" => $request->logged_user_store_id,
                "label" => $request->label,
                "discounttype"=>$request->discounttype,
                "discount_code" => strtoupper($request->discount_code),
                "description" => $request->description,
                "status" => $request->status,
                "is_always"=>$request->is_always,
                "created_by" => $request->logged_user_id,
                "discount_type"=>$request->discount_type,
                "discount_percentage"=>$request->discount_percentage,
                "discount_value"=>$request->discount_value,
                "discount_applicable_products"=>$request->discount_applicable_products,
                "discount_applicable_categories"=>$request->discount_applicable_categories,
                "discount_not_applicable_products"=>$request->discount_not_applicable_products,
                "limit_on_discount"=>$request->limit_on_discount,
                "discount_applied_on"=>$request->discount_applied_on,
                "discount_start_date"=>date('Y-m-d H:i:sa', strtotime("$request->discount_start_date $request->discount_start_time")),
                "discount_end_date"=>date('Y-m-d H:i:sa', strtotime("$request->discount_end_date $request->discount_end_time")),
              ];
              if($discount_code["discounttype"]=="inventory"||$discount_code["discounttype"]=="cashier")
              {
                  unset($discount_code["discount_code"]);
              }
              $discountcode = new DiscountcodeModel();
              $discountcode->slack = $discount_code["slack"];
              $discountcode->store_id = $discount_code["store_id"];
              $discountcode->label = $discount_code["label"];
              if(isset($discount_code["discount_code"]))
              {
                $discountcode->discount_code = $discount_code["discount_code"];
              }
              else
              {
                $discountcode->discount_code = "";
              }
              $discountcode->description = $discount_code["description"];
              $discountcode->status = $discount_code["status"];
              $discountcode->is_always = $discount_code["is_always"];
              $discountcode->created_by = $discount_code["created_by"];
              $discountcode->discounttype = $discount_code["discounttype"];
              $discountcode->discount_type = $discount_code["discount_type"];
              $discountcode->discount_percentage = $discount_code["discount_percentage"];
              $discountcode->discount_value = $discount_code["discount_value"];
              $discountcode->discount_applicable_products = $discount_code["discount_applicable_products"];
              $discountcode->discount_not_applicable_products = $discount_code["discount_not_applicable_products"];
              $discountcode->discount_applicable_categories = $discount_code["discount_applicable_categories"];
              $discountcode->limit_on_discount = $discount_code["limit_on_discount"];
              $discountcode->discount_applied_on = $discount_code["discount_applied_on"];
              $discountcode->discount_start_date = $discount_code["discount_start_date"];
              $discountcode->discount_end_date = $discount_code["discount_end_date"];
              $discountcode->save();
            //$discount_code_id = DiscountcodeModel::create($discount_code)->id;
              $discount_code_id = $discountcode->id;
              if($discount_code["discounttype"]=="inventory")
              {
              if($discount_code["discount_applied_on"]=="all_products")
              {
                  $products = Product::Active()
                  ->get();
 
                  foreach($products as $product)
                  {
                      Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id]);
                  }
              }
              else if($discount_code["discount_applied_on"]=="specific_products")
              {
                $products = Product::whereIn("id",explode(",",$discount_code["discount_applicable_products"]))->Active()
                ->get();
                Product::where("discount_code_id",$discount_code_id)->update(["discount_code_id"=>null]);
                foreach($products as $product)
                {
                    Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id]);
                }
              }
              else if($discount_code["discount_applied_on"]=="specific_product_categories")
              {
                  $discount_categories = explode(",",$discount_code['discount_applicable_categories']);
                  foreach($discount_categories as $discount_category)
                  {
                      if($discount_category>0){
                          $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                          foreach($categories as $category) {
                              array_push($discount_categories,$category->id);
                          }
                      }
                  }
                  //Product::where("discount_code_id",$discount_code_id)->update(["discount_code_id"=>null]);
                  $products = Product::whereIn("category_id",$discount_categories)->Active()
                      ->get();
                  foreach($products as $product)
                  {
                      Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id]);
                  }
              }
              else if($discount_code["discount_applied_on"]=="all_products_except_specific")
              {
                $products = Product::whereNotIn("id",explode(",",$discount_code["discount_not_applicable_products"]))->Active()
                ->get();
                Product::where("discount_code_id",$discount_code_id)->update(["discount_code_id"=>null]);
                foreach($products as $product)
                {
                    Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id]);
                }
              }
            }
            DB::commit();

            $data['discount_codes'] = DiscountcodeModel::select('slack', 'discount_code', 'label')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Discount code Created Successfully"),
                    "link"    =>  '/discount_codes', 
                    "data"    => $data['discount_codes']
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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

            if(!check_access(['A_DETAIL_DISCOUNTCODE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = DiscountcodeModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new DiscountcodeResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Discount code loaded successfully", 
                    "data"    => $item_data
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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

            if(!check_access(['A_VIEW_DISCOUNTCODE_LISTING'], true)){
                throw new Exception("Invalid request", 400);
            }

            $updated_at = (isset($request->updated_at) && $request->updated_at != '') ? $request->updated_at : '';

            $list = new DiscountcodeCollection(DiscountcodeModel::select('*')
            ->when( ($updated_at != '' ), function($query) use($updated_at) {
                $query->where('updated_at','>=',$updated_at);
            })
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Discount codes loaded successfully", 
                    "data"    => $list
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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

            if(!check_access(['A_EDIT_DISCOUNTCODE'], true)){
                throw new Exception("Invalid request", 400);
            }
            $this->validate_request($request);
            if($request['discounttype']=="code")
            {
                $discount_code_exists = DiscountcodeModel::select('id')
                ->where([
                ['slack', '!=', $slack],
                ['discount_code', '=', trim($request->discount_code)],
                ])
                ->first();
                if (!empty($discount_code_exists)) {
                   throw new Exception(trans("Discount code already exists : {$request->discount_code}"), 400);
                }
            }
            $product_amount = 0;
            if($request->discount_applied_on=="all_products")
            {
               $product = Product::select("sale_amount_excluding_tax")->where("price_type",'fixed')->where("is_ingredient",0)->active()->orderBy('sale_amount_excluding_tax', 'ASC')->first();
               if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="specific_products")
            {
                $product = Product::select("sale_amount_excluding_tax")->where("price_type",'fixed')->where("is_ingredient",0)->whereIn("id",explode(",",$request->discount_applicable_products))->Active()
                ->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="specific_product_categories")
            {
                $discount_categories = explode(",",$request->discount_applicable_categories);
                foreach($discount_categories as $discount_category)
                {
                    $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                    foreach($categories as $category) {
                        array_push($discount_categories,$category->id);
                    }
                }
                $product = Product::select("sale_amount_excluding_tax")->where("price_type",'fixed')->where("is_ingredient",0)->whereIn("category_id",$discount_categories)->Active()->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            else if($request->discount_applied_on=="all_products_except_specific")
            {
                $product = Product::select("sale_amount_excluding_tax")->where("price_type",'fixed')->where("is_ingredient",0)->whereNotIn("id",explode(",",$request->discount_not_applicable_products))->Active()
                ->orderBy('sale_amount_excluding_tax', 'ASC')->first();
                if(isset($product->sale_amount_excluding_tax))
               {
                 $product_amount = $product->sale_amount_excluding_tax;
               }
            }
            if($request->discount_type=="amount")
            {
                if($product_amount<$request->discount_value)
                {
                    throw new Exception("Discount should be less than Minimum Product price");
                }
            }
            else
            {
                 $calculated_discount_amount = (0.01*$request->discount_percentage)*$product_amount;
                 if(($product_amount-$calculated_discount_amount)<0)
                 {
                     throw new Exception("Discount should be less than Minimum Product price");
                 }
            }
            if($request->is_always==0)
            {
              $discountstartdate = new \DateTime(date('Y-m-d H:i:s', strtotime("$request->discount_start_date $request->discount_start_time")));
              $discountenddate = new \DateTime(date('Y-m-d H:i:s', strtotime("$request->discount_end_date $request->discount_end_time")));
              if($discountstartdate>$discountenddate)
              {
                throw new Exception("Discount start date must be less than Discount end Date");
              }
            }
            if($request->is_always==0)
            {
            $discount_code_details = DiscountcodeModel::where('store_id',$request->logged_user_store_id)->get();
            foreach($discount_code_details as $discountcode)
            {
                $currentdate = date('Y-m-d H:i:sa');
                $discountstartdate = date('Y-m-d H:i:sa', strtotime("$request->discount_start_date $request->discount_start_time"));
                $discountenddate = date('Y-m-d H:i:sa', strtotime("$request->discount_end_date $request->discount_end_time"));
                $discountcodeinfo = DB::select("select * from discount_codes where (('{$discountstartdate}'>=discount_start_date and '{$discountstartdate}'<=discount_end_date) or ('{$discountenddate}'>=discount_start_date and '{$discountenddate}'<=discount_end_date)) and id = {$discountcode->id} and is_always=0 and status=1 and slack!='".$slack."'");
                if(count($discountcodeinfo)>0)
                {
                    $labels ="";
                    foreach($discountcodeinfo as $discount)
                    {
                        $labels.="'{$discount->label}',";
                    }
                    $labels = preg_replace("/,$/","",$labels);
                    throw new Exception("Discounts {$labels} have same time frame");
                }
            }
        }
        if($request->limit_on_discount!=-1)
        {
           if($request->limit_on_discount==0)
           {
               throw new Exception("limit on discount should be greater than 0");
           }
        }
        if($request->is_always==0)
        {
            $discountstartdate = date('Y-m-d H:i:s', strtotime("$request->discount_start_date $request->discount_start_time"));
            $discountenddate = date('Y-m-d H:i:s', strtotime("$request->discount_end_date $request->discount_end_time"));
            if(new \DateTime($discountstartdate)==new \DateTime($discountenddate))
            {
                throw new Exception("Discount have same start and end date");
            }    
        }
        if(strtolower($request->discount_type)=="amount")
        {
            if($request->discount_value==0)
            {
                throw new Exception("Discount amount should be greater than 0");
            }
        }
        else
        {
            if($request->discount_percentage==0)
            {
                throw new Exception("Discount percentage should be greater than 0");
            } 
        }
        $cashier_discounts = DiscountcodeModel::select('*')
                             ->where('slack', '!=', $slack)
                             ->where("store_id",$request->logged_user_store_id)
                             ->where("discounttype", "cashier")
                             ->where("status",1)
                             ->get();
        if(count($cashier_discounts)>0)
        {
            if($request->discounttype=="cashier" && (int)$request->status==1)
            { 
                $label = "";
                foreach($cashier_discounts as $discount)
                {
                    if($discount->status==1)
                    {
                     $label.="'".$discount->label."'";
                     $label.=",";
                    }
                }
                $label = preg_replace("/,$/","",$label);
              throw new Exception("There is already an automatic cashier discount named {$label} added");
            }
        }

            DB::beginTransaction();
             
            $discount_code = [
                //"slack" => $this->generate_slack("discount_codes"),
                "store_id" => $request->logged_user_store_id,
                "label" => $request->label,
                "discount_code" => strtoupper($request->discount_code),
                "description" => $request->description,
                "discounttype"=>$request->discounttype,
                "status" => $request->status,
                "is_always"=>$request->is_always,
                "created_by" => $request->logged_user_id,
                "discount_type"=>$request->discount_type,
                "discount_percentage"=>$request->discount_percentage,
                "discount_value"=>$request->discount_value,
                "discount_applicable_products"=>$request->discount_applicable_products,
                "discount_not_applicable_products"=>$request->discount_not_applicable_products,
                "discount_applicable_categories"=>$request->discount_applicable_categories,
                "limit_on_discount"=>$request->limit_on_discount,
                "discount_applied_on"=>$request->discount_applied_on,
                "discount_start_date"=>date('Y-m-d H:i:sa', strtotime("$request->discount_start_date $request->discount_start_time")),
                "discount_end_date"=>date('Y-m-d H:i:sa', strtotime("$request->discount_end_date $request->discount_end_time")),
              ];
              if($request->discounttype=="cashier"||$request->discounttype=="inventory")
              {
                  $discount_code["discount_code"] = "";
              }
              $currentdate = date('Y-m-d H:i:sa');
              $discount_code_id = DiscountcodeModel::select('id')
              ->where('slack', $slack)->first();


              $discount_type = DiscountcodeModel::select('*')
              ->where('slack', $slack)->first();

              if($discount_type->discounttype=="inventory" && (int)$request->status==1)
              {
                 if($request->discounttype=="code" || $request->discounttype=="cashier")
                 {
                    DB::update("update products set discount_code_id=null where discount_code_id=".$discount_type->id);
                 }
              }
              if($discount_type->discounttype=="code" && (int)$request->status==1)
              {
                if($request->discounttype=="cashier")
                {
                    DB::update("update products set discount_code_id=null where discount_code_id=".$discount_type->id); 
                }
              }
              if(!isset($discount_code_id->id))
              {
                $discount_code_id = new \stdClass();
                $discount_code_id->id = 0;
              }
              $action_response = DiscountcodeModel::where('slack', $slack)
            ->update($discount_code);

            
            
            if($discount_code["discounttype"]=="inventory")
            {
            if($discount_code["discount_applied_on"]=="all_products")
              {
                  $products = Product::active()
                  ->get();

                  foreach($products as $product)
                  {
                      Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id->id]);
                  }
              }
              else if($discount_code["discount_applied_on"]=="specific_products")
              {
                $products = Product::whereIn("id",explode(",",$discount_code["discount_applicable_products"]))->Active()
                ->get();
                Product::where("discount_code_id",$discount_code_id->id)->update(["discount_code_id"=>null]);
                foreach($products as $product)
                {
                    Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id->id]);
                }
              }
              else if($discount_code["discount_applied_on"]=="specific_product_categories")
              {
                $discount_categories = explode(",",$discount_code['discount_applicable_categories']);
                foreach($discount_categories as $discount_category)
                {
                    if($discount_category>0){
                        $categories = CategoryModel::where('parent',$discount_category)->active()->get();
                        foreach($categories as $category) {
                            array_push($discount_categories,$category->id);
                        }
                    }
                }
                Product::where("discount_code_id",$discount_code_id->id)->update(["discount_code_id"=>null]);
                $products = Product::whereIn("category_id",$discount_categories)->Active()
                ->get();
                foreach($products as $product)
                {
                    Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id->id]);
                }
              }
              else if($discount_code["discount_applied_on"]=="all_products_except_specific")
              {
                $products = Product::whereNotIn("id",explode(",",$discount_code["discount_not_applicable_products"]))->Active()
                ->get();
                Product::where("discount_code_id",$discount_code_id->id)->update(["discount_code_id"=>null]);
                foreach($products as $product)
                {
                    Product::where("id",$product->id)->update(["discount_code_id"=>$discount_code_id->id]);
                }
              }
            }
            DB::commit();
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Discount code Updated Successfully"), 
                    "data"    => $slack
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validate_request($request)
    {
        $validations = [
            'label' => $this->get_validation_rules("name_label", true),
            'discount_code' => $this->get_validation_rules("codes", true),
            'discount_start_date'=>$this->get_validation_rules("text", true),
            'discount_end_date'=>$this->get_validation_rules("text", true),
            'description' => $this->get_validation_rules("text", false),
            'status' => $this->get_validation_rules("status", true),
        ];
        if($request->discounttype=="inventory"||$request->discounttype=="cashier")
        {
            unset($validations["discount_code"]);
        }
        if($request->is_always==1){
            unset($validations['discount_start_date']);
            unset($validations['discount_end_date']);
        }
        if($request->limit_on_discount!=-1){
            $validations['limit_on_discount'] = $this->get_validation_rules("numeric", true);
        }
        if($request->discount_type=="amount")
        {
            $validations['discount_value'] = $this->get_validation_rules("numeric", true);
        }
        else
        {
            $validations['discount_percentage'] = $this->get_validation_rules("numeric", true);
        }
        
        if($request->discount_applied_on=="specific_products"){
            $validations['discount_applicable_products'] = $this->get_validation_rules("text", true);
        }
        else if($request->discount_applied_on=="all_products_except_specific"){
            $validations['discount_not_applicable_products'] = $this->get_validation_rules("text", true);
        }
        else if($request->discount_applied_on=="specific_product_categories"){
            $validations['discount_applicable_categories'] = $this->get_validation_rules("text", true);
        }
        $validator = Validator::make($request->all(),$validations);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

    public function get_cashier_discount(Request $request)
    {
        try
        {
           $data = [];
           $store_data = StoreModel::select('id', 'tax_code_id', 'discount_code_id', 'currency_code', 'restaurant_billing_type_id', 'restaurant_waiter_role_id', 'enable_customer_popup', 'store_opening_time', 'store_closing_time', 'is_store_closing_next_day',)
            ->where([
                ['id', '=', $request->logged_user_store_id],
                ['status', '=', 1]
            ])
            ->first();
            if(isset($store_data->id))
            {
                $currentdate = date('Y-m-d H:i:sa');
              $discountcodes_cashier = DiscountcodeModel::select('*')
                ->where('store_id', '=', trim($store_data->id))
                ->where("discounttype","cashier")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->get();
                $discountcodes = DiscountcodeModel::select('*')
                ->where('store_id', '=', trim($store_data->id))
                ->where("discounttype","code")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->get();
                $data['store_discount_codes'] = !empty($discountcodes) ? json_encode($discountcodes) : [];
                $data['cashier_discounts'] = json_encode($discountcodes_cashier);


                $products_data = $query = ProductModel::select(['products.*','tax_codes.total_tax_percentage', 'tax_codes.label as tax_code_label'])
                ->with('product_modifiers')->categoryJoin()
                ->supplierJoin()
                ->Active()
                ->categoryActive()
                ->productTypePos()
                ->taxcodeJoin()
                ->mainProduct();

        $total = $query->get()->count();

        $products_data = $products_data->take(30)->get();
        $total_count = count($products_data);

        $data['products_counter']['offset'] = 30; // default offset
        $data['products_counter']['total'] = $total;

        $products_data = ProductResource::collection($products_data);
        $data['product_list'] = null;
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

                $data['product_list'][] = $dataset;
            }
        }
                $data['product_list'] = json_encode($data['product_list']);
                return response()->json($data);
            }
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
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

                        if(isset($measurement_conversion))
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