<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store as StoreModel;
use App\Models\Category as CategoryModel;

use App\Http\Resources\CategoryResource;
use App\Http\Traits\ZidApiTrait;

class Expresspay extends Controller
{

    //This is the function that loads the listing page
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_EXPRESSPAY';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['expresspay_data'] = DB::table('setting_app')->first();
        return view('setting.expresspay_setting', $data);
    }

}
