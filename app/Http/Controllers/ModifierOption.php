<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Modifier as ModifierModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\MasterStatus;
use App\Models\Product as ProductModel;

use App\Http\Resources\ModifierOptionResource;
use App\Models\Measurement;
use App\Models\ModifierOptionIngredient;

class ModifierOption extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER_OPTION';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('modifier_option.modifier_options', $data);
    }


     //This is the function that loads the add/edit page
    public function add_modifier_option($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER_OPTION';
        $data['action_key'] = ($slack == null)?'A_ADD_MODIFIER_OPTION':'A_EDIT_MODIFIER_OPTION';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MODIFIER_OPTION_STATUS')->active()->sortValueAsc()->get();

        $data['modifier_data'] = ModifierModel::active()->get();
        $data['modifier_option_data'] = null;
        $query = ProductModel::with('measurements')->select('products.*')
        ->supplierJoin()
        ->discountcodeJoin()
        ->quantityCheck()
        ->isIngredient();
        $data['ingredient_data'] = $query->get();
        $data['modifier_option_ingredients_data'] = [];

        if(isset($slack)){
            $modifier_option = ModifierOptionModel::where('slack', '=', $slack)->first();
            if (empty($modifier_option)) {
                abort(404);
            }
            $modifier_option_data = new ModifierOptionResource($modifier_option);
            $data['modifier_option_data'] = $modifier_option_data;

            $modifier_option_ingredients = ProductModel::with('measurements')
            ->join('modifier_option_ingredients','modifier_option_ingredients.ingredient_id','products.id')
            ->where('modifier_option_ingredients.modifier_option_id',$modifier_option->id)
            ->select('modifier_option_ingredients.*','products.name as name','products.slack as ingredient_slack')
            ->get();

            $data['modifier_option_ingredients_data'] = $modifier_option_ingredients->toArray();
            
            if(isset($modifier_option_ingredients)){
                $modifier_option_ingredients_data = $modifier_option_ingredients->toArray();
                $i = 0;
                foreach($modifier_option_ingredients_data as $modifier_option_ingredient){
                    if(isset($modifier_option_ingredient['measurements'])){
                        $measurements = Measurement::where('measurement_category_id',$modifier_option_ingredient['measurements']['measurement_category_id'])->get();
                        if(isset($measurements)){
                            $data['modifier_option_ingredients_data'][$i]['measurements'] = $measurements->toArray();
                        }
                    }
                    $i++;
                }
            }
        }

        return view('modifier_option.add_modifier_option', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER_OPTION';
        $data['action_key'] = 'A_DETAIL_MODIFIER_OPTION';
        check_access([$data['action_key']]);

        $modifier_option = ModifierOptionModel::where('slack', '=', $slack)->first();
        
        if (empty($modifier_option)) {
            abort(404);
        }

        $modifier_option_data = new ModifierOptionResource($modifier_option);
        $data['modifier_option_data'] = $modifier_option_data;
        return view('modifier_option.modifier_option_detail', $data);
    }


}
