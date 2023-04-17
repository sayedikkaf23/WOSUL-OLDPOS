<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use App\Models\Taxcode as TaxcodeModel;

use App\Http\Resources\TaxcodeResource;
use App\Http\Resources\TaxNameResource;
use App\Models\TaxName;

class TaxNameMaster extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_TAX_AND_DISCOUNT';
        $data['sub_menu_key'] = 'SM_TAXNAMES';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('tax_name_master.tax_name_list', $data);
    }

    //This is the function that loads the add/edit page
    public function add_tax_name($slack = null){
        
        $data['menu_key'] = 'MM_TAX_AND_DISCOUNT';
        $data['sub_menu_key'] = 'SM_TAXNAMES';
        // $data['action_key'] = ($slack == null)?'A_ADD_TAXNAME':'A_EDIT_TAXNAME';
        // check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('TAX_NAME_STATUS')->active()->sortValueAsc()->get();
        $data['tax_name_data'] = null;
        if(isset($slack)){
            
            $tax_name = TaxName::where('id', '=', $slack)->first();
            if (empty($tax_name)) {
                abort(404);
            }
            if ($tax_name->is_default == 1) {
                return redirect()->route('tax_names');
            }
            $tax_name_data = new TaxNameResource($tax_name);
            $data['tax_name_data'] = $tax_name_data;
        }
        $data['tax_names_list_route'] = route('tax_names');
        return view('tax_name_master.add_tax_name', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_TAX_AND_DISCOUNT';
        $data['sub_menu_key'] = 'SM_TAXNAMES';
        $data['action_key'] = 'A_DETAIL_TAXNAME';
        check_access([$data['action_key']]);

        $tax_name = TaxName::where('id', '=', $slack)->first();
        
        if (empty($tax_name)) {
            abort(404);
        }

        $tax_name_data = new TaxNameResource($tax_name);
        
        $data['tax_name_data'] = $tax_name_data;

        return view('tax_name_master.tax_name_detail', $data);
    }
}
