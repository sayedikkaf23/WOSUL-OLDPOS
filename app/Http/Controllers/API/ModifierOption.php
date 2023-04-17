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
use App\Http\Resources\ModifierOptionResource;

use App\Models\Modifier as ModifierModel;
use App\Models\ModifierOption as ModifierOptionModel;

use App\Http\Resources\Collections\MeasurementUnitCollection;
use App\Models\ModifierOptionIngredient;

class ModifierOption extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_MODIFIER_OPTION';
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
            
            $query = ModifierOptionModel::select('modifier_options.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

            $modifier_options = ModifierOptionResource::collection($query);

            $total_count = ModifierOptionModel::select("id")->get()->count();

            $item_array = [];
            foreach($modifier_options as $key => $modifier_option){

                $modifier_id =  $modifier_option['modifier_id'];
                $modifier_option = $modifier_option->toArray($request);

                $item_array[$key][] = $modifier_option['label'];
                // $item_array[$key][] = ModifierModel::find($modifier_option['modifier_id'])->label;
                $item_array[$key][] = $modifier_option['price'];
                // $item_array['modifier_label'][] = ModifierModel::where('id',$modifier_id)->first()->label;
                $item_array[$key][] = (isset($modifier_option['status']['label']))?view('common.status', ['status_data' => ['label' => $modifier_option['status']['label'], "color" => $modifier_option['status']['color']]])->render():'-';
                $item_array[$key][] = $modifier_option['created_at_label'];
                $item_array[$key][] = $modifier_option['updated_at_label'];
                $item_array[$key][] = (isset($modifier_option['created_by']['fullname']))?$modifier_option['created_by']['fullname']:'-';
                $item_array[$key][] = view('modifier_option.layouts.modifier_option_actions', ['modifier' => $modifier_option])->render();
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

            $modifier_data_exists = ModifierOptionModel::select('id')
            ->where('label', '=', trim($request->label))
            ->where('modifier_id', '=', trim($request->modifier_id))
            ->active()
            ->first();
            if (!empty($modifier_data_exists)) {
                throw new Exception(trans("Modifier Option already exists"), 400);
            }

           
            DB::beginTransaction();
            
            $modifier_option = [
                "slack" => $this->generate_slack("modifier_options"),
                "label" => $request->label,
                "modifier_id" => $request->modifier_id,
                "price" => $request->price,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

            $modifier_option_id = ModifierOptionModel::create($modifier_option)->id;

            $ingredients = json_decode($request->ingredients,true);

            if(isset($ingredients) && count($ingredients) > 0){
                foreach($ingredients as $ingredient){
                    
                    ModifierOptionIngredient::create([
                        'modifier_option_id' => $modifier_option_id,
                        'ingredient_id'=> $ingredient['ingredient_id'],
                        'quantity'=> $ingredient['quantity'],
                        'measurement_id'=> $ingredient['measurement_id'],
                        'created_by'=> $request->logged_user_id
                    ]);
                }
            }

            DB::commit();

            $data['modifier_options'] = ModifierOptionModel::select('slack', 'label', 'price')->sortLabelAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Modifier Option created successfully"), 
                    "data"    => $data['modifier_options']
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
        // dd($request);

        try {

            if(!check_access(['A_EDIT_MODIFIER'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $modifier_option_id = ModifierOptionModel::where('slack',$slack)->first()->id;
            
            $modifier_option = [
                "label" => $request->label,
                "modifier_id" => $request->modifier_id,
                "price" => $request->price,
                "status" => $request->status,
                'updated_by' => $request->logged_user_id
            ];

            $action_response = ModifierOptionModel::where('slack', $slack)
            ->update($modifier_option);

            $ingredients = json_decode($request->ingredients,true);

            if(isset($ingredients) && count($ingredients) > 0){

                // unlink old values 
                $modifier_option_ingredients = ModifierOptionIngredient::where('modifier_option_id',$modifier_option_id)->delete();

                foreach($ingredients as $ingredient){
                    
                    ModifierOptionIngredient::create([
                        'modifier_option_id' => $modifier_option_id,
                        'ingredient_id'=> $ingredient['ingredient_id'],
                        'quantity'=> $ingredient['quantity'],
                        'measurement_id'=> $ingredient['measurement_id'],
                        'created_by'=> $request->logged_user_id
                    ]);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Modifier option updated successfully"), 
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
            'price' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

   
   
}
