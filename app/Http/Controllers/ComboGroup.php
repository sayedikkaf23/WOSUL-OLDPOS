<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComboGroupResource;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Models\ComboProduct as ComboProductModel; 
use App\Models\TaxcodeType as TaxcodeTypeModel;
use Illuminate\Support\Facades\DB;
use App\Models\Category as CategoryModel;
use App\Http\Resources\ComboProductResource;
use App\Models\ComboGroup as ComboGroupModel;
use App\Models\Product as ProductModel;

class ComboGroup extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_COMBO';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('combo.combo_groups', $data);
    }

    //This is the function that loads the add/edit page
    public function add($slack = null){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_COMBO';
        $data['action_key'] = ($slack == null)?'A_ADD_COMBO_GROUP':'A_EDIT_COMBO_GROUP';
        check_access(array($data['action_key']));
        
        $data['parent_group_data'] = ComboGroupModel::parentCategories()->get();
        
        $data['combo_group_data'] = null;

        if(isset($slack)){
            
            $combo_group = ComboGroupModel::where('slack', '=', $slack)->first();
            if (empty($combo_group)) {
                abort(404);
            }
            $combo_group = new ComboGroupResource($combo_group);
            $data['combo_group_data'] = $combo_group;
        }
        
        return view('combo.add_combo_group', $data);
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
