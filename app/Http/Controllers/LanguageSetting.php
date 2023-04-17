<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Models\LanguageSetting as LanguageSettingModel;
use App\Models\LanguageSettingPhrase as LanguageSettingPhraseModel;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\LanguageSettingResource;
use App\Http\Resources\LanguageSettingPhraseResource;

class LanguageSetting extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_LANGUAGE';
        check_access(array($data['menu_key'],$data['sub_menu_key']));       
        return view('language_setting.list', $data);
    }

    //This is the function that loads the add/edit page
    public function add_edit_lang($slack = null){
        //check access
   
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_LANGUAGE';
        $data['action_key'] = ($slack == null)?'A_ADD_LANGUAGE':'A_EDIT_LANGUAGE';
       
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('LANGUAGE_STATUS')->active()->sortValueAsc()->get();

        $data['lang_data'] = null;
        if(isset($slack)){
            
            $lang = LanguageSettingModel::where('slack', '=', $slack)->first();
            if (empty($lang)) {
                abort(404);
            }

            $lang_data = new LanguageSettingResource($lang);
            $data['lang_data'] = $lang_data;
        }

        return view('language_setting.create', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_LANGUAGE';
        $data['action_key'] = 'A_DETAIL_LANGUAGE';
        check_access([$data['action_key']]);

        $language_setting = LanguageSettingModel::where('slack', '=', $slack)->first();
      
        if (empty($language_setting)) {
            abort(404);
        }

        $language_setting_data = new LanguageSettingResource($language_setting);
        
        $data['language_setting_data'] = $language_setting_data;

        return view('language_setting.detail', $data);
    }

    //This is the function that loads the detail page
    public function add_lang_phrase($slack){
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_LANGUAGE';
        $data['action_key'] = 'A_PHRASE_LANGUAGE';
        check_access([$data['action_key']]);

       
        $data['slack'] =$slack;
        return view('language_setting.phrase.list', $data);
    }

    //This is the function that loads the detail page
  
    public function add_edit_lang_phrase($lang_slack=null,$slack = null){
        //check access
       
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_LANGUAGE';
        $data['action_key'] = ($slack == null)?'A_ADD_PHRASE_LANGUAGE':'A_EDIT_PHRASE_LANGUAGE';
       
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('LANGUAGE_STATUS')->active()->sortValueAsc()->get();

        $data['lang_data'] = null;
        if(isset($slack)){
            
            $lang = LanguageSettingPhraseModel::where('slack', '=', $slack)->first();
            if (empty($lang)) {
                abort(404);
            }

            $lang_data = new LanguageSettingPhraseResource($lang);
            $data['lang_data'] = $lang_data;
        }

        $language_setting = LanguageSettingModel::where('slack', '=', $lang_slack)->first();
        $language_setting_data = new LanguageSettingResource($language_setting);
        $data['language_setting_data']=$language_setting_data;
       
        return view('language_setting.phrase.create', $data);
    }
  


}
