<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingApp;
use App\Http\Resources\QoyodResource;

class Qoyod extends Controller
{
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_MARKETPLACE';
        $data['sub_menu_key'] = 'SM_QOYOD';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        $data['qoyod_data'] = null;

        $qoyod = SettingApp::select('qoyod_api_key','qoyod_status','qoyod_last_sync_time')->first();
        if($qoyod->qoyod_status>0){
            $data['qoyod_data'] = array(
                'api_key'=>$qoyod->qoyod_api_key,
                'status'=>$qoyod->qoyod_status,
                'last_syc_time'=>$qoyod->qoyod_last_sync_time,
            );
        }
        return view('qoyod.add_qoyod', $data);
    }
}
