<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\ModifierResource;

use App\Models\Modifier as ModifierModel;

use App\Http\Resources\Collections\MeasurementUnitCollection;

class Modifier extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_MODIFIER';
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
            
            $query = ModifierModel::select('modifiers.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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
            ->groupBy('modifiers.id')

            ->get();

            $modifiers = ModifierResource::collection($query);

            $total_count = $query->count();

            $item_array = [];
            foreach($modifiers as $key => $modifier){
                
                $modifier = $modifier->toArray($request);

                // $item_array[$key][] = $modifier['unit_code'];
                $item_array[$key][] = $modifier['label'];
                $item_array[$key][] = (isset($modifier['status']['label']))?view('common.status', ['status_data' => ['label' => $modifier['status']['label'], "color" => $modifier['status']['color']]])->render():'-';
                $item_array[$key][] = $modifier['created_at_label'];
                $item_array[$key][] = $modifier['updated_at_label'];
                $item_array[$key][] = (isset($modifier['created_by']['fullname']))?$modifier['created_by']['fullname']:'-';
                $item_array[$key][] = view('modifier.layouts.modifier_actions', ['modifier' => $modifier])->render();
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

            if(!check_access(['A_ADD_MODIFIER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $modifier_data_exists = ModifierModel::select('id')
            ->where('label', '=', trim($request->label))
            ->active()
            // ->merchantJoin()
            ->first();

            if (!empty($modifier_data_exists)) {
                throw new Exception(trans("Modifier already exists"), 400);
            }

            DB::beginTransaction();
            
            $modifier = [
                "slack" => $this->generate_slack("modifiers"),
                "label" => $request->label,
                "status" => $request->status,
                "is_multiple" => $request->is_multiple,
                "created_by" => $request->logged_user_id
            ];

            $modifier_id = ModifierModel::create($modifier)->id;

            DB::commit();

            $data['modifiers'] = ModifierModel::select('slack', 'label')
                ->sortLabelAsc()
                // ->merchantJoin()
                ->active()
                ->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Modifier created successfully"), 
                    "data"    => $data['modifiers']
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

            if(!check_access(['A_EDIT_MODIFIER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $modifier = [
                "label" => $request->label,
                "status" => $request->status,
                'is_multiple' => $request->is_multiple,
                'updated_by' => $request->logged_user_id
            ];

            $action_response = ModifierModel::where('slack', $slack)
            ->update($modifier);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Modifier updated successfully"), 
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
