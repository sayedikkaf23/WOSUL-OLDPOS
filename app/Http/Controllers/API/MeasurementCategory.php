<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\MeasurementCategoryResource;

use App\Models\MeasurementCategory as MeasurementCategoryModel;

use App\Http\Resources\Collections\MeasurementUnitCollection;

class MeasurementCategory extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);
        try {

            $data['action_key'] = 'A_VIEW_MEASUREMENT_CATEGORY';
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
            
            $query = MeasurementCategoryModel::select('measurement_categories.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

            $measurement_categories = MeasurementCategoryResource::collection($query);

            $total_count = MeasurementCategoryModel::select("id")->get()->count();

            $item_array = [];
            foreach($measurement_categories as $key => $measurement_category){
                
                $measurement_category = $measurement_category->toArray($request);

                // $item_array[$key][] = $measurement_category['unit_code'];
                $item_array[$key][] = $measurement_category['label'];
                $item_array[$key][] = (isset($measurement_category['status']['label']))?view('common.status', ['status_data' => ['label' => $measurement_category['status']['label'], "color" => $measurement_category['status']['color']]])->render():'-';
                $item_array[$key][] = $measurement_category['created_at_label'];
                $item_array[$key][] = $measurement_category['updated_at_label'];
                $item_array[$key][] = (isset($measurement_category['created_by']['fullname']))?$measurement_category['created_by']['fullname']:'-';
                $item_array[$key][] = view('measurement_unit.layouts.measurement_category_actions', ['measurement_category' => $measurement_category])->render();
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

     public function store(Request $request)
    {
     
        try {

            if(!check_access(['A_ADD_MEASUREMENT_CATEGORY'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $measurement_category_data_exists = MeasurementCategoryModel::select('id')
            ->where('label', '=', trim($request->label))
            ->active()
            ->first();
            if (!empty($measurement_category_data_exists)) {
                throw new Exception(trans("Measurement category already exists"), 400);
            }

            DB::beginTransaction();
            
            $measurement_category = [
                "slack" => $this->generate_slack("measurement_categories"),
                "label" => $request->label,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];
            
            $measurement_category_id = MeasurementCategoryModel::create($measurement_category)->id;

            DB::commit();

            $data['measurement_categories'] = MeasurementCategoryModel::select('slack', 'label')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurement unit created successfully"), 
                    "data"    => $data['measurement_categories']
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

            if(!check_access(['A_EDIT_MEASUREMENT_CATEGORY'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $measurement_category = [
                "label" => $request->label,
                "status" => $request->status,
                'updated_by' => $request->logged_user_id
            ];

            $action_response = MeasurementCategoryModel::where('slack', $slack)
            ->update($measurement_category);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Measurement category updated successfully"), 
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

   
   
}
