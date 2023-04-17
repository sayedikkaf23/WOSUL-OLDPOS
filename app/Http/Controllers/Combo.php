<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ComboProduct;
use App\Http\Resources\CategoryResource;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Models\ComboProduct as ComboProductModel; 
use App\Models\TaxcodeType as TaxcodeTypeModel;
use Illuminate\Support\Facades\DB;
use App\Models\Category as CategoryModel;
use App\Http\Resources\ComboProductResource;
use App\Models\ComboGroup;
use App\Models\Product as ProductModel;
use App\Http\Resources\ComboGroupResource;
use App\Http\Resources\ComboResource;
use App\Http\Resources\MeasurementResource;
use App\Http\Resources\ProductResource;
use App\Models\Combo as ComboModel;
use App\Models\Measurement;

class Combo extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_COMBO';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('combo.combos', $data);
    }

    //This is the function that loads the add/edit page
    public function add($slack = null){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_COMBO';
        $data['action_key'] = ($slack == null)?'A_ADD_COMBO':'A_EDIT_COMBO';
        check_access(array($data['action_key']));


        $data['statuses_data'] = MasterStatus::select('value', 'label')->filterByKey('TAX_CODE_STATUS')->active()->sortValueAsc()->get();
        $categories = CategoryModel::select('id', 'slack', 'category_code', 'label', 'category_applied_on', 'category_applicable_stores')
                                    ->parentCategory()->categoryStore()->sortLabelAsc()->active()->get();
        $data['categories_data'] = CategoryResource::collection($categories);
        
        $groups = ComboGroup::parentCategories()->get();
        $data['groups_data'] = ComboGroupResource::collection($groups);

        $products_data = ProductModel::active()->get();
        $data['products_data'] = ProductResource::collection($products_data);

        $data['combo_data'] = null;
        
        if(isset($slack)){
            
            $combo = ComboModel::where('slack', '=', $slack)->first();
            if (empty($combo)) {
                abort(404);
            }

            $combo = new ComboResource($combo);
            $data['combo_data'] = $combo;
            
            $combo_sizes = [];
            foreach($combo->sizes as $size){
                $dataset = [];
                $dataset['size_name'] = $size->size_name;

                // $items = [];
                $products = ComboProductModel::where('combo_id',$combo->id)->where('combo_size_id',$size->id)->get();
                $products = ComboProductResource::collection($products);

                if(isset($products)){
                    foreach($products as $product){
                        $item = [];
                        $item['product'] = new ProductResource($product->product);
                        $item['product_measurement'] = ($product->measurement_id > 0 ) ? $product->measurement_id : '';
                        $item['product_price'] = $product->price;
                        $item['product_quantity'] = $product->quantity;
                        $item['subgroup'] = '';
                        
                        $group = ComboGroup::where('id',$product->combo_group_id)->first();
                        
                        if($group->parent != null){
                            $item['group'] = $group->parent;
                            $subgroups = ComboGroup::where('parent',$group->parent)->get();
                            $item['subgroup'] = $product->combo_group_id;
                        }else{
                            $item['group'] = $group->id;
                            $subgroups = ComboGroup::where('parent',$group->id)->get();
                        }
                        $item['subgroups'] = ComboGroupResource::collection($subgroups);

                        if(isset($product->measurement_id) && $product->measurement_id > 0){
                            $measurements = Measurement::with('same_categories')->where('id',$product->measurement_id)->first();
                            if(isset($measurements)){
                                $item['measurements'] = $measurements->same_categories;
                            }
                        }
                        $dataset['items'][] = $item;
                    }
                }

                $combo_sizes[] = $dataset;
            
            }
            
            $data['combo_data']['sizes'] = $combo_sizes;
        }
        
        return view('combo.add_combo', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_TAX_AND_DISCOUNT';
        $data['sub_menu_key'] = 'SM_TAXCODES';
        $data['action_key'] = 'A_DETAIL_TAXCODE';
        check_access([$data['action_key']]);

        $tax_code = TaxcodeModel::where('slack', '=', $slack)->first();
        
        if (empty($tax_code)) {
            abort(404);
        }

        $tax_code_data = new TaxcodeResource($tax_code);
        
        $data['tax_code_data'] = $tax_code_data;

        return view('combo_product.combo_product_detail', $data);
    }
}
