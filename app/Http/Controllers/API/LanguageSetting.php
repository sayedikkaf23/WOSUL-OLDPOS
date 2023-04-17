<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;

use App\Http\Resources\LanguageSettingResource;
use App\Models\LanguageSetting as LanguageSettingModel;
use App\Http\Resources\Collections\LanguageSettingCollection;
use App\Models\LanguageSettingPhrase as LanguageSettingPhraseModel;
use App\Http\Resources\LanguageSettingPhraseResource;
use App\Http\Resources\Collections\LanguageSettingPhraseCollection;
use File;
class LanguageSetting extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {

            $data['action_key'] = 'A_LISTING_LANGUAGE';
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
            
            $query = LanguageSettingModel::select('language_setting.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

            $LanguageSetting = LanguageSettingResource::collection($query);
           
            $total_count = LanguageSettingModel::select("id")->get()->count();

            $item_array = [];
            foreach($LanguageSetting as $key => $lang){

                $lang = $lang->toArray($request);

                $item_array[$key][] = $lang['lang_name'];
                $item_array[$key][] = $lang['lang_culture'];
                $item_array[$key][] = $lang['lang_code'];
                $item_array[$key][] = (isset($lang['status']['label']))?view('common.status', ['status_data' => ['label' => $lang['status']['label'], "color" => $lang['status']['color']]])->render():'-';
                $item_array[$key][] = $lang['created_at_label'];
                $item_array[$key][] = $lang['updated_at_label'];
                $item_array[$key][] = (isset($lang['created_by']) && isset($lang['created_by']['fullname']))?$lang['created_by']['fullname']:'-';
              
                $item_array[$key][] = view('language_setting.layouts.lang_actions', array('lang' => $lang))->render();
            }

            //dd($item_array);

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
        try {

            if(!check_access(['A_ADD_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $lang_data_exists = LanguageSettingModel::select('id')
            ->where('lang_name', '=', trim($request->lang_name))
            ->first();
            if (!empty($lang_data_exists)) {
                throw new Exception(trans("Language already exists"), 400);
            }

            DB::beginTransaction();
            
            $language_setting = [
                "slack" => $this->generate_slack("language_setting"),
                "lang_name" => Str::title($request->lang_name),
                "lang_culture" => $request->lang_culture,
                "lang_code" => $request->lang_code,
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

         
            $language_setting_id = LanguageSettingModel::create($language_setting)->id;

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language created successfully"), 
                    "data"    => $language_setting['slack']
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
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    { 
        try {

            if(!check_access(['A_DETAIL_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = LanguageSettingModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new LanguageSettingResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language loaded successfully"), 
                    "data"    => $item_data
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
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if(!check_access(['A_LISTING_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $list = new LanguageSettingCollection(LanguageSettingModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language loaded successfully"), 
                    "data"    => $list
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

            if(!check_access(['A_EDIT_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);
            
            $lang_data_exists = LanguageSettingModel::select('id')
            ->where([
                ['slack', '!=', $slack],
                ['lang_name', '=', trim($request->lang_name)],
            ])
            ->first();
            if (!empty($lang_data_exists)) {
                throw new Exception(trans("Language already exists"), 400);
            }

            DB::beginTransaction();
            $language_setting = [
                "lang_name" => Str::title($request->lang_name),
                "lang_culture" => $request->lang_culture,
                "lang_code" => $request->lang_code,
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];
            

            $action_response = LanguageSettingModel::where('slack', $slack)
            ->update($language_setting);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language updated successfully"), 
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'lang_name' => $this->get_validation_rules("name_label", true),
            'lang_culture' => $this->get_validation_rules("text", true),
            'lang_code' => $this->get_validation_rules("text", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
    public function validate_request_phrase($request)
    {
        $validator = Validator::make($request->all(), [
            'lang_phrase' => $this->get_validation_rules("name_label", true),
            // 'lang_label' => $this->get_validation_rules("text", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

 public function phrase(Request $request,$slack)
    {

       
        try {

            $data['action_key'] = 'A_PHRASE_LANGUAGE';
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
             $lang_setting = LanguageSettingModel::where('slack', '=', $slack)->first();
            $query = LanguageSettingPhraseModel::select('language_setting_phrases.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
           
            ->take($limit)
            ->skip($offset)
            ->statusJoin()
            ->createdUser()
            ->where('language_setting_phrases.lang_setting_id','=',$lang_setting->id)
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

            $LanguageSetting = LanguageSettingPhraseResource::collection($query);
           
            $total_count = LanguageSettingPhraseModel::select("id")->get()->count();

            $item_array = [];
            foreach($LanguageSetting as $key => $lang){

                $lang = $lang->toArray($request);

                $item_array[$key][] = $lang['lang_phrase'];
                $item_array[$key][] = $lang['lang_label'];
              
                $item_array[$key][] = (isset($lang['status']['label']))?view('common.status', ['status_data' => ['label' => $lang['status']['label'], "color" => $lang['status']['color']]])->render():'-';
                $item_array[$key][] = $lang['created_at_label'];
                $item_array[$key][] = $lang['updated_at_label'];
                $item_array[$key][] = (isset($lang['created_by']) && isset($lang['created_by']['fullname']))?$lang['created_by']['fullname']:'-';
              
                $item_array[$key][] = view('language_setting.phrase.layouts.lang_actions', array('lang' => $lang,'slack'=>$slack))->render();
            }

            //dd($item_array);

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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_phrase(Request $request)
    { 
       
        try {

           
            if(!check_access(['A_ADD_PHRASE_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request_phrase($request);

            // $lang_data_exists = LanguageSettingPhraseModel::select('id')
            // ->where('lang_label', '=', trim($request->lang_label))
            // ->first();
            // if (!empty($lang_data_exists)) {
            //     throw new Exception("Language label already exists", 400);
            // }

            DB::beginTransaction();

             $lang_setting_data = LanguageSettingModel::where('slack', '=', $request->lang_slack)->first();

            // dd($request->all());
           
            $language_setting = [
                "slack" => $this->generate_slack("language_setting_phrases"),
                "lang_setting_id" => $lang_setting_data?$lang_setting_data->id:'',
                "lang_phrase" => $request->lang_phrase,
                "lang_label" => Str::title($request->lang_label),
                "status" => $request->status,
                "created_by" => $request->logged_user_id
            ];

         
            $language_setting_id = LanguageSettingPhraseModel::create($language_setting)->id;

        //
        $lang_setting_data = LanguageSettingModel::select('lang_code')
                            ->where('slack', '=', $request->lang_slack)
                            ->first();
       // Read File
        $json_file_path=base_path('resources/lang/'.$lang_setting_data->lang_code.'.json');       
        $jsonString = file_get_contents($json_file_path);
        $lang_data = json_decode($jsonString, true);
       
        // Update Key
        if(array_key_exists($request->lang_phrase, $lang_data)){
          $lang_data[$request->lang_phrase]=Str::title($request->lang_label);
        }
        else{
          $lang_data[$request->lang_phrase]=Str::title($request->lang_label);
         }
          $newJsonString = json_encode($lang_data,JSON_UNESCAPED_UNICODE);
          file_put_contents($json_file_path, stripslashes($newJsonString)); 

          DB::commit();
          //run npm run dev
          //$base_path=base_path().'/demo.wosul.sa';
          $base_path='/var/www/vhosts/wosul.sa/demo.wosul.sa';
          $output=shell_exec('cd '.$base_path.' && npm run dev');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language created successfully"), 
                    "data"    => [$language_setting['slack'],'lang_slack'=>$request->lang_slack],
                    "lang_slack"=>$request->lang_slack
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
    public function update_phrase(Request $request, $slack)
    { 

        try {

            if(!check_access(['A_EDIT_LANGUAGE'], true)){
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request_phrase($request);
            
            $lang_data_exists = LanguageSettingPhraseModel::select('id')
            ->where([
                ['slack', '!=', $slack],
                ['lang_label', '=', trim($request->lang_label)],
            ])
            ->first();
            if (!empty($lang_data_exists)) {
                throw new Exception(trans("Language label already exists"), 400);
            }

            DB::beginTransaction();
            $language_setting = [
                "lang_phrase" => $request->lang_phrase,
                "lang_label" => Str::title($request->lang_label),
                "status" => $request->status,
                "updated_by" => $request->logged_user_id
            ];
            

            $action_response = LanguageSettingPhraseModel::where('slack', $slack)
            ->update($language_setting);

                      
        $lang_setting_data = LanguageSettingModel::select('lang_code')
                            ->where('slack', '=', $request->lang_slack)
                            ->first();
        // Read File
        $json_file_path=base_path('resources/lang/'.$lang_setting_data->lang_code.'.json'); 

        $jsonString = file_get_contents($json_file_path);
        $lang_data = json_decode($jsonString, true);
      
        // Update Key
        if(array_key_exists($request->lang_phrase, $lang_data)){
            $lang_data[$request->lang_phrase]=Str::title($request->lang_label);
        }
        else{
            $lang_data[$request->lang_phrase]=Str::title($request->lang_label);
        }    
   
        $newJsonString = json_encode($lang_data,JSON_UNESCAPED_UNICODE);
          file_put_contents($json_file_path, stripslashes($newJsonString)); 

            DB::commit();
           //run npm run dev
            //$base_path=base_path().'/demo.wosul.sa';
            $base_path='/var/www/vhosts/wosul.sa/demo.wosul.sa';
           
            $output=shell_exec('cd '.$base_path.' && npm run dev');
          
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Language updated successfully"), 
                    "data"    => ['slack'=>$slack,'lang_slack'=>$request->lang_slack],
                   
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


}
