<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MeasurementUnit as MeasurementUnitModel;
use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;

// use App\Http\Resources\MeasurementUnitResource;
use App\Http\Resources\MeasurementResource;
use App\Http\Resources\MeasurementCategoryResource;

class Measurement extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MEASUREMENT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('measurement_unit.measurements', $data);
    }

    //This is the function that loads the add/edit page
    public function add_measurement($slack = null){


        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MEASUREMENT';
        $data['action_key'] = ($slack == null)?'A_ADD_MEASUREMENT':'A_EDIT_MEASUREMENT';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MEASUREMENT_STATUS')->active()->sortValueAsc()->get();
        $data['measurement_categories'] = MeasurementCategoryModel::active()->get();

        $data['measurement_data'] = null;
        if(isset($slack)){
            $measurement = MeasurementModel::where('slack', '=', $slack)->first();
            if (empty($measurement)) {
                abort(404);
            }
            
            $measurement = new MeasurementResource($measurement);
            $data['measurement_data'] = $measurement;
        }

        return view('measurement_unit.add_measurement', $data);
    }

    //This is the function that loads the add/edit page
    public function add_measurement_conversion($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MEASUREMENT';
        $data['action_key'] = ($slack == null)?'A_ADD_MEASUREMENT':'A_EDIT_MEASUREMENT';
        check_access(array($data['action_key']));
        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MEASUREMENT_STATUS')->active()->sortValueAsc()->get();

        $measurement_detail = MeasurementModel::where('slack',$slack)->first();
        $measurement_conversion_data = MeasurementModel::select('measurements.id as to_measurement_id','measurements.slack','measurements.measurement_category_id','measurements.label as to_measurement_label')->where('measurement_category_id',$measurement_detail->measurement_category_id)
        ->where('measurements.slack','!=',$slack)
        ->active()
        ->get();

        $data['measurement_slack_data'] = $measurement_detail->slack;
        $data['measurement_conversion_data'] = [];
        
        if(!empty($measurement_conversion_data)){
            foreach($measurement_conversion_data as $rs){
                $dataset = $rs;
                $dataset['from_measurement_label'] = $measurement_detail->label;
                $dataset['from_measurement_unit'] = 1;
                $to_measurement_value = MeasurementConversionModel::where([
                    'from_measurement_id'=> $measurement_detail->id,
                    'to_measurement_id'=> $rs->to_measurement_id
                ])->active()->first();
                if(isset($to_measurement_value)) {
                    $dataset['to_measurement_value'] = (float) $to_measurement_value->value;
                }
                $data['measurement_conversion_data'][] = collect($dataset);
            }
        }
        
        // dd($data['measurement_conversion_data']);
        // dd($data['measurement_conversion_data']);
        return view('measurement_unit.add_measurement_conversion', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MEASUREMENT';
        $data['action_key'] = 'A_DETAIL_MEASUREMENT';
        check_access([$data['action_key']]);

        $measurement = MeasurementModel::where('slack', '=', $slack)->first();

        if (empty($measurement)) {
            abort(404);
        }

        $data['measurement_data'] = new MeasurementResource($measurement);

        $measurement_conversion_data = MeasurementConversionModel::where('from_measurement_id',$measurement->id)->get();
        $data['measurement_conversion_data'] = [];
        if(!empty($measurement_conversion_data)){
            foreach($measurement_conversion_data as $rs){
                $to_measurement_category_id = MeasurementModel::where("id",$rs->to_measurement_id)->first();
                $from_measurement_category_id = MeasurementModel::where("id",$rs->from_measurement_id)->first();
                if($to_measurement_category_id->measurement_category_id==$from_measurement_category_id->measurement_category_id)
                {
                  $dataset = $rs;
                  $dataset['from_measurement_value'] = 1;
                  $dataset['from_measurement_label'] = $measurement->label;
                  $dataset['to_measurement_label'] = MeasurementModel::find($rs->to_measurement_id)->label;
                  $dataset['to_measurement_value'] = $rs->value;
                  $data['measurement_conversion_data'][] = $dataset;
                }

            }
        }

        return view('measurement_unit.measurement_detail', $data);
    }

    // new measurements

    //This is the function that loads the add/edit page
    public function add_measurement_category($slack = null){


        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MEASUREMENT';
        $data['action_key'] = ($slack == null)?'A_ADD_MEASUREMENT':'A_EDIT_MEASUREMENT';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MEASUREMENT_CATEGORY_STATUS')->active()->sortValueAsc()->get();

        $data['measurement_category_data'] = null;
        if(isset($slack)){
            $measurement_unit = MeasurementCategoryModel::where('slack', '=', $slack)->first();
            if (empty($measurement_unit)) {
                abort(404);
            }
            
            $measurement_category_data = new MeasurementUnitResource($measurement_unit);
            $data['measurement_category_data'] = $measurement_category_data;
        }

        return view('measurement_unit.add_measurement_category', $data);
    }
}
