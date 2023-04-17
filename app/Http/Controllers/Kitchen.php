<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MasterStatus;

class Kitchen extends Controller
{
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_RESTAURANT';
        $data['sub_menu_key'] = 'SM_RESTAURANT_KITCHEN';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        $data['kitchen_statuses'] = MasterStatus::select('value_constant', 'label')->filterByKey('ORDER_KITCHEN_STATUS')->active()->sortValueAsc()->get();
        
        $data['change_kitchen_order_status'] = check_access(['A_CHANGE_KITCHEN_ORDER_STATUS'] ,true);

        $data['pos_order_edit'] = check_access(['A_EDIT_ORDER'] ,true);
        
        return view('kitchen.kitchen', $data);
    }

    public function waiter(Request $request){
        //check access
        $data['menu_key'] = 'MM_RESTAURANT';
        $data['sub_menu_key'] = 'SM_RESTAURANT_WAITER';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('kitchen.waiter', $data);
    }
}
