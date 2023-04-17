<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComboGroupResource;
use App\Models\ComboGroup as ComboGroupModel;
use Illuminate\Support\Str;

class ComboGroup extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_COMBO_GROUP_LISTING';
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
            
            $query =  ComboGroupModel::select('combo_groups.*', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
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

            $total_count = $query->count();
            $combo_groups = ComboGroupResource::collection($query);
            
            $item_array = [];
            foreach($combo_groups as $key => $combo_group){

                $combo_group = $combo_group->toArray($request);
                $item_array[$key][] = ( isset($combo_group['parent']) ) ? $combo_group['parent']['name'] : '';
                $item_array[$key][] = $combo_group['name'];
                $item_array[$key][] = $combo_group['created_at_label'];
                $item_array[$key][] = $combo_group['updated_at_label'];
                $item_array[$key][] = (isset($combo_group['created_by']) && isset($combo_group['created_by']['fullname']))?$combo_group['created_by']['fullname']:'-';
                $item_array[$key][] = view('combo.layouts.combo_group_actions', array('combo_group' => $combo_group))->render();
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

            if(!check_access(['A_ADD_COMBO_GROUP'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $combo_group_data_exists = ComboGroupModel::select('id')
            ->where('name', '=', trim($request->name))
            ->first();

            if (!empty($combo_group_data_exists)) {
                throw new Exception(trans("Combo group already exists"), 400);
            }

            DB::beginTransaction();
            
            $combo_group = [
                "slack" => $this->generate_slack("modifiers"),
                "name" =>  Str::upper($request->name),
                "parent" => $request->parent,
                "store_id" => $request->logged_user_store_id,
                "created_by" => $request->logged_user_id
            ];

            $combo_group = ComboGroupModel::create($combo_group);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Combo group created successfully"), 
                    "data"    => $combo_group,
                    "link"    => route('combo_groups')
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

            if(!check_access(['A_EDIT_COMBO_GROUP'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $combo_group = [
                "parent" => $request->parent,
                "name" => $request->name,
                'updated_by' => $request->logged_user_id
            ];

            $action_response = ComboGroupModel::where('slack', $slack)
            ->update($combo_group);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Combo group updated successfully"), 
                    "data"    => $slack,
                    "link"    => route('combo_groups')
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
            'name' => $this->get_validation_rules("name_label", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
