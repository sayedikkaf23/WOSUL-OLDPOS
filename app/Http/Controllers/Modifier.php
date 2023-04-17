<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Modifier as ModifierModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\MasterStatus;
use App\Http\Resources\ModifierResource;

class Modifier extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('modifier.modifiers', $data);
    }

     //This is the function that loads the add/edit page
    public function add_modifier($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER';
        $data['action_key'] = ($slack == null)?'A_ADD_MODIFIER':'A_EDIT_MODIFIER';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MODIFIER_STATUS')->active()->sortValueAsc()->get();

        $data['modifier_data'] = null;
        if(isset($slack)){
            $modifier = ModifierModel::where('slack', '=', $slack)->first();
            if (empty($modifier)) {
                abort(404);
            }
            
            $modifier_data = new ModifierResource($modifier);
            $data['modifier_data'] = $modifier_data;
        }

        return view('modifier.add_modifier', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER';
        $data['action_key'] = 'A_DETAIL_MODIFIER';
        check_access([$data['action_key']]);

        $modifier = ModifierModel::where('slack', '=', $slack)->first();
        
        if (empty($modifier)) {
            abort(404);
        }

        $modifier_data = new ModifierResource($modifier);
        
        $data['modifier_data'] = $modifier_data;

        return view('modifier.modifier_detail', $data);
    }

    public function delete_modifier($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MODIFIER';
        $data['action_key'] = ($slack == null)?'A_ADD_MODIFIER':'A_EDIT_MODIFIER';
        check_access(array($data['action_key']));

        // soft delete modifiers and its options

        $modifier = ModifierModel::where('slack',$slack)->first();
        if(empty($modifier)){
            abort(404);
        }

        ModifierModel::where('slack',$slack)->delete();
        ModifierOptionModel::where('modifier_id',$modifier->id)->delete();
        
        return redirect()->back();

    }


}
