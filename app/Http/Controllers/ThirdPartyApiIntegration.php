<?php

namespace App\Http\Controllers;


use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Models\UserPointsSettings as UserPointsSettingsModel;
use App\Http\Resources\UserPointsResource;
use App\Models\BonatUserPointsSettings as BonatUserPointsSettingsModel;
use App\Http\Resources\BonatUserPointsResource;
use App\Models\BonatStoreCounterPointsSettings as BonatStoreCounterPointsSettingsModel;
use App\Http\Resources\BonatStoreCounterPointsResource; 
use App\Models\Store as StoreModel;
use Illuminate\Support\Facades\DB;

class ThirdPartyApiIntegration extends Controller
{
    public function index(Request $request)
    {
    }

    public function show_user_points_settings(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_USERPOINTS';
        check_access([$data['sub_menu_key']]);

        $abkhas_setting = UserPointsSettingsModel::select('*')->first();
        $abkhas_setting_data = collect();
        if (!empty($abkhas_setting)) {
            $abkhas_setting_data = new UserPointsResource($abkhas_setting);
        }
        $data['abkhas_setting'] = $abkhas_setting_data;
        return view('setting.third_party_integration.user_points_setting', $data);
    }

    public function edit_user_points_setting($slack = null)
    {
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_USERPOINTS';
        $data['action_key'] = 'A_EDIT_USERPOINTS_SETTING';
        check_access([$data['action_key']]);

        $abkhas_setting = UserPointsSettingsModel::select('*')
            ->when($slack, function ($query, $slack) {
                $query->where('slack', $slack);
            })->first();

        $abkhas_setting_data = collect();
        if (!empty($abkhas_setting)) {
            $abkhas_setting_data = new UserPointsResource($abkhas_setting);
        }
        $data['setting_data'] = $abkhas_setting_data;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('UPS_SETTING_STATUS')->active()->sortValueAsc()->get();

        return view('setting.third_party_integration.edit_user_points_setting', $data);
    }

    public function show_bonat_user_points_settings(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_BONATUSERPOINTS';
        check_access([$data['sub_menu_key']]);

        $bonat_setting = BonatUserPointsSettingsModel::select('*')->first();
        $bonat_setting_data = collect();
        if (!empty($bonat_setting)) {
            $bonat_setting_data = new BonatUserPointsResource($bonat_setting);
        }
       
        $data['bonat_setting'] = $bonat_setting_data;
        return view('setting.third_party_integration.bonat_user_points_setting', $data);
    }

    public function edit_bonat_user_points_setting($slack = null)
    {

        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_BONATUSERPOINTS';
        $data['action_key'] = 'A_EDIT_BONATUSERPOINTS_SETTING';
        check_access([$data['action_key']]);

        $bonat_setting = BonatUserPointsSettingsModel::select('*')
            ->when($slack, function ($query, $slack) {
                $query->where('slack', $slack);
            })->first();

        $bonat_setting_data = collect();
        if (!empty($bonat_setting)) {
            $bonat_setting_data = new BonatUserPointsResource($bonat_setting);
        }
        $data['setting_data'] = $bonat_setting_data;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('BUPS_SETTING_STATUS')->active()->sortValueAsc()->get();

        return view('setting.third_party_integration.edit_bonat_user_points_setting', $data);
    }

    public function store_counters(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_BONATSTORECOUNTERPOINTS_SETTING';
        check_access([$data['sub_menu_key']]);

        return view('setting.third_party_integration.store_counters', $data);
    }

    public function show_bonat_store_counter_points_settings($slack)
    {
        //check access
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_BONATSTORECOUNTERPOINTS_SETTING';
        check_access([$data['sub_menu_key']]);

        $store_details = StoreModel::select('slack')->where('id', session('store_id'))->first();
        $store_slack = $store_details->slack;
        $merchant_slack = session('merchant_slack');
        $bonat_setting =array();
        $bonat_setting = BonatStoreCounterPointsSettingsModel::select('*')
        ->where('store_id',$store_slack)->where('counter_id',$slack)->where('merchant_id',$merchant_slack)->first();


        $bonat_setting_data = collect();
        if (!empty($bonat_setting)) {
            $bonat_setting_data = new BonatStoreCounterPointsResource($bonat_setting);
        }
       
        $data['bonat_setting'] = $bonat_setting_data;
        $data['view_counter_id'] = $slack;
        return view('setting.third_party_integration.bonat_store_counter_points_setting', $data);
    }


    public function edit_bonat_store_counter_points_setting($slack = null)
    {
        $data['menu_key'] = 'MM_LOYALITY';
        $data['sub_menu_key'] = 'SM_BONATSTORECOUNTERPOINTS_SETTING';
        $data['action_key'] = 'A_EDIT_BONATSTORECOUNTERPOINTS_SETTING';
        check_access([$data['action_key']]);


        $bonat_setting = BonatStoreCounterPointsSettingsModel::select('*')


            ->when($slack, function ($query, $slack) {
                $query->where('counter_id', $slack);
            })

           
            ->first();

        $bonat_setting_data = collect();
        if (!empty($bonat_setting)) {
            $bonat_setting_data = new BonatUserPointsResource($bonat_setting);
        }
        $data['setting_data'] = $bonat_setting_data;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('BUPS_SETTING_STATUS')->active()->sortValueAsc()->get();
        $data['view_counter_id'] = $slack;
        return view('setting.third_party_integration.edit_bonat_store_counter_points_setting', $data);
    }
    
    
}
