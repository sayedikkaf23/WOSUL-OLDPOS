<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterStatus;
use App\Models\Store as StoreModel;
use Carbon\Carbon;

class InvoiceReturn extends Controller
{
    
    public function index(Request $request){
        
        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_RETURN_INVOICE';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
    
        //available return order status
        $data['return_statuses'] = MasterStatus::select('value', 'label')->filterByKey('INVOICE_STATUS')->active()->orderBy('label','DESC')->get();

        $data['store'] = StoreModel::select('currency_name', 'currency_code','store_opening_time','store_closing_time')
        ->where('id', $request->logged_user_store_id)
        ->first();
        $data['store_opening_time'] = Carbon::now()->subMonth()->format("Y-m-d")."T".$data['store']->store_opening_time;
        $data['store_closing_time'] = Carbon::now()->format("Y-m-d")."T".$data['store']->store_closing_time;

        return view('invoice_return.invoice_return_list', $data);
    }

}
