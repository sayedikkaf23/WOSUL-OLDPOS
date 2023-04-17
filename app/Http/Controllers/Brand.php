<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Brand as BrandModel;

use App\Http\Resources\BrandResource;

class Brand extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_BRAND';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('brand.brands', $data);
    }

    //This is the function that loads the add/edit page
    public function add_brand($slack = null){
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_BRAND';
        $data['action_key'] = ($slack == null)?'A_ADD_BRAND':'A_EDIT_BRAND';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('BRAND_STATUS')->active()->sortValueAsc()->get();

        $data['brand_data'] = null;
        if(isset($slack)){
            $brand = BrandModel::where('slack', '=', $slack)->first();
            if (empty($brand)) {
                abort(404);
            }
            
            $brand_data = new BrandResource($brand);
            $data['brand_data'] = $brand_data;
        }

        return view('brand.add_brand', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_BRAND';
        $data['action_key'] = 'A_DETAIL_BRAND';
        check_access([$data['action_key']]);

        $brand = BrandModel::where('slack', '=', $slack)->first();
        if (empty($brand)) {
            abort(404);
        }

        $brand_data = new BrandResource($brand);
        $data['brand_data'] = $brand_data;

        return view('brand.brand_detail', $data);
    }
}
