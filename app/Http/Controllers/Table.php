<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MasterStatus;
use App\Models\Table as TableModel;

use App\Http\Resources\TableResource;

class Table extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_RESTAURANT';
        $data['sub_menu_key'] = 'SM_RESTAURANT_TABLES';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('table.tables', $data);
    }

    //This is the function that loads the add/edit page
    public function add_table($slack = null){
        //check access
        $data['menu_key'] = 'MM_RESTAURANT';
        $data['sub_menu_key'] = 'SM_RESTAURANT_TABLES';
        $data['action_key'] = ($slack == null)?'A_ADD_RESTAURANT_TABLE':'A_EDIT_RESTAURANT_TABLE';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('RESTAURANT_TABLE_STATUS')->active()->sortValueAsc()->get();

        $data['table_data'] = null;
        if(isset($slack)){
            $table = TableModel::where('slack', '=', $slack)->first();
            if (empty($table)) {
                abort(404);
            }
            
            $table_data = new TableResource($table);
            $data['table_data'] = $table_data;
        }

        return view('table.add_table', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_RESTAURANT';
        $data['sub_menu_key'] = 'SM_RESTAURANT_TABLES';
        $data['action_key'] = 'A_DETAIL_RESTAURANT_TABLE';
        check_access([$data['action_key']]);

        $table = TableModel::where('slack', '=', $slack)->first();
        
        if (empty($table)) {
            abort(404);
        }

        $table_data = new TableResource($table);
        
        $data['table_data'] = $table_data;

        return view('table.table_detail', $data);
    }
}
