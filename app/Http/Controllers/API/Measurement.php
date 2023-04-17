<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\MeasurementCollection;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\MeasurementUnitResource;
use App\Http\Resources\MeasurementResource;

use App\Models\Measurement as MeasurementModel;
use App\Models\MeasurementCategory as MeasurementCategoryModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;
use App\Models\Product as ProductModel;

use App\Http\Resources\Collections\MeasurementUnitCollection;
use App\Http\Traits\QoyodApiTrait;
use App\Models\QoyodMesurmentUnit;

class Measurement extends Controller
{
    use QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_MEASUREMENT';
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
            
            $query = MeasurementModel::select('measurements.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

            $measurements = MeasurementResource::collection($query);

            $total_count = MeasurementModel::select("id")->get()->count();

            $item_array = [];
            foreach($measurements as $key => $measurement){
                
                $measurement = $measurement->toArray($request);

                // $item_array[$key][] = $measurement_category['unit_code'];
                $item_array[$key][] = $measurement['label'];
               
                $measurement_category = MeasurementCategoryModel::find($measurement['measurement_category_id']);
                if(isset($measurement_category)){
                    $item_array[$key][] = $measurement_category->label;
                }else{
                    $item_array[$key][] = "";
                }
                $item_array[$key][] = (isset($measurement['status']['label']))?view('common.status', ['status_data' => ['label' => $measurement['status']['label'], "color" => $measurement['status']['color']]])->render():'-';
                $item_array[$key][] = $measurement['created_at_label'];
                $item_array[$key][] = $measurement['updated_at_label'];
                $item_array[$key][] = (isset($measurement['created_by']['fullname']))?$measurement['created_by']['fullname']:'-';
                $item_array[$key][] = view('measurement_unit.layouts.measurement_actions', ['measurement' => $measurement])->render();

            }
            // dd($item_array);
            
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
    public function store(Request $request)
    {
        // dd($request);
        try {

            if(!check_access(['A_ADD_MEASUREMENT'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $measurement_data_exists = MeasurementModel::select('id')
            ->where('label', '=', trim($request->label))
            ->first();
            if (!empty($measurement_data_exists)) {
                throw new Exception(trans("Measurement already exists"), 400);
            }

            DB::beginTransaction();
            
            $measurement = [
                "slack" => $this->generate_slack("measurements"),
                "label" => $request->label,
                "measurement_category_id" => $request->category_id,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

            $measurement_id = MeasurementModel::create($measurement)->id;

            //qoyod entry
            if(Session('qoyod_status')){
                $unit_data = array(
                    'id' => $measurement_id,
                    'label' => $measurement['label'],
                );
                $this->qoyod_create_unit((object)$unit_data);
            }
            DB::commit();

            $data['measurements'] = MeasurementModel::select('slack','label')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurement created successfully"), 
                    "data"    => $data['measurements']
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

    public function update(Request $request)
    {
        try {

            if(!check_access(['A_ADD_MEASUREMENT'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $measurement_data_exists = MeasurementModel::select('id')
            ->where('label', '=', trim($request->label))
            ->where('slack', '!=', trim($request->slack))
            ->first();
            if (!empty($measurement_data_exists)) {
                throw new Exception(trans("Measurement already exists"), 400);
            }

            DB::beginTransaction();
            
            $measurement = [
                "label" => $request->label,
                "measurement_category_id" => $request->category_id,
                "status" => $request->status
            ];
            $measurement_data = MeasurementModel::where('slack', '!=', trim($request->slack))
            ->first();
            if($measurement_data->measurement_category_id != $request->category_id)
            {
                MeasurementConversionModel::whereRaw("from_measurement_id={$measurement_data->id} or to_measurement_id={$measurement_data->id}")->delete();
            }
            $measurement_id = MeasurementModel::where('slack',$request->slack)->update($measurement);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurement updated successfully"), 
                    "data"    => $measurement_id
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

    public function store_conversion(Request $request)
    {
        try {

            if(!check_access(['A_ADD_MEASUREMENT'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            // $this->validate_request($request);

            DB::beginTransaction();

            $measurement_detail = MeasurementModel::where('slack',$request->slack)->first();
            $conversions = json_decode($request->conversions);

            if(isset($conversions)){

                foreach($conversions as $rs){

                    $check = [
                        "from_measurement_id"=>$measurement_detail->id,
                        "to_measurement_id"=>$rs->to_measurement_id,
                    ];
                    $checkIfAlreadyExists = MeasurementConversionModel::where($check)->active()->first();

                    if(isset($checkIfAlreadyExists)){
                        // udpating the existing conversions
                        MeasurementConversionModel::where($check)->update([
                            "value" => $rs->to_measurement_value
                        ]);
                    }else{
                        // adding new conversions
                        $new = $check;
                        $new['slack'] = $this->generate_slack("measurement_conversions");
                        $new['value'] = $rs->to_measurement_value;
                        MeasurementConversionModel::create($new);
                    }
                    
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurement Conversions Updated successfully"), 
                    "data"    => $conversions
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

   public function get_conversion_units(Request $request){

        if(isset($request->measurement_slack)){

        }else{

            try {
                
                $conversion_units = MeasurementModel::where('measurement_category_id',$request->category_id)
                ->active()
                ->get();

                $response = [
                    // 'draw' => $draw,
                    // 'recordsTotal' => $total_count,
                    // 'recordsFiltered' => $total_count,
                    'data' => $conversion_units
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

   } 

   public function load_measurements(Request $request){

        try {

            $category_id = $request->category_id;

            if($category_id == ""){
                abort(404);
            }

            $conversion_units = MeasurementModel::where('measurement_category_id',$request->category_id)
            ->active()
            ->get();

            $response = [
                'data' => $conversion_units
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

   public function load_measurements_for_ingredient(Request $request){

        try {

            $ingredient_slack = $request->ingredient_slack;

            if($ingredient_slack == ""){
                abort(404);
            }

            $measurement_detail = ProductModel::select('measurement_id','purchase_amount_excluding_tax','sale_amount_excluding_tax')->where('slack',$ingredient_slack)->first();
            $measurement_id = $measurement_detail->measurement_id;
            $ingredient_purchase_price_per_unit = $measurement_detail->purchase_amount_excluding_tax;
            $ingredient_sale_price_per_unit = $measurement_detail->sale_amount_excluding_tax;

            $measurement_category_id = MeasurementModel::find($measurement_id)->measurement_category_id;
            $measurements = MeasurementModel::where('measurement_category_id',$measurement_category_id)->active()->get();

            $data['measurements'] = [];
            if(!empty($measurements)){
                foreach($measurements as $rs){

                    $dataset = [];
                    if($rs->id == $measurement_id){
                        $dataset = $rs;
                        $dataset['ingredient_purchase_price_per_unit'] = $ingredient_sale_price_per_unit;
                        $dataset['ingredient_sale_price_per_unit'] = $ingredient_purchase_price_per_unit;

                    }else{
                        $conversion = MeasurementConversionModel::where([
                            'from_measurement_id' =>$measurement_id,
                            'to_measurement_id' => $rs->id
                        ])->first();

                        if(isset($conversion) && $conversion->value > 1 ){
                            $dataset = $rs;
                            $dataset['ingredient_purchase_price_per_unit'] = $ingredient_purchase_price_per_unit * 1 / $conversion->value;
                            $dataset['ingredient_sale_price_per_unit'] = $ingredient_sale_price_per_unit * 1 / $conversion->value;
                        }
                    }
                    if(!empty($dataset)){
                        $data['measurements'][] = $dataset;
                    }
                }
            }

            $response = [
                'data' => $data['measurements']
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


   public function validate_request($request)
   {
        $validator = Validator::make($request->all(), [
            'label' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

    // Its used by mobile application to know actual product ingredient quantity
    public function measurement_with_conversions(Request $request)
    {
        try{
            
            $updated_at = (isset($request->updated_at) && $request->updated_at != '') ? $request->updated_at : '';

            $measurements = MeasurementModel::with('conversions')->when( ($updated_at != '' ), function($query) use($updated_at) {
                $query->where('updated_at','>=',$updated_at);
            })->paginate();
            $data = new MeasurementCollection($measurements);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurements loaded successfully"), 
                    "data"    => $data
                ), trans('SUCCESS')
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

}
