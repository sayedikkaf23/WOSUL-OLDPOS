<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MeasurementCategory as MeasurementCategoryModel;

use App\Http\Resources\MeasurementCategoryResource;

class MeasurementCategory extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_MEASUREMENT_CATEGORY';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('measurement_unit.measurement_categories', $data);
    }

    //This is the function that loads the add/edit page
    public function add_measurement_category($slack = null){

        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_MEASUREMENT_CATEGORY';
        $data['action_key'] = ($slack == null)?'A_ADD_MEASUREMENT_CATEGORY':'A_EDIT_MEASUREMENT_CATEGORY';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MEASUREMENT_CATEGORY_STATUS')->active()->sortValueAsc()->get();

        $data['measurement_category_data'] = null;
        if(isset($slack)){
            $measurement_category = MeasurementCategoryModel::where('slack', '=', $slack)->first();
            if (empty($measurement_category)) {
                abort(404);
            }
            
            $measurement_category_data = new MeasurementCategoryResource($measurement_category);
            $data['measurement_category_data'] = $measurement_category_data;
        }

        return view('measurement_unit.add_measurement_category', $data);
    }
}
