<?php

namespace App\Http\Controllers;

use App\Models\InventoryCount as InventoryCountModel;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryCount extends Controller
{
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_INVENTORY_COUNT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['stores'] = Store::select('id','name')->active()->get()->toArray();

        session()->forget('inventory_count_store_id');
        session()->forget('inventory_count_id');

        // dd($data['stores']);

        return view('inventory.inventory_count', $data);
    }

    public function view_inventory_count()
    {
        $store_id = session('inventory_count_store_id');
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_INVENTORY_COUNT';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        $data['store'] = Store::select('id','name')->where('id',$store_id)->active()->first();
        $data['items'] = Product::select('id','name')->where('store_id',$store_id)->removeUnlimitedQuantity()->active()->get()->toArray();
        $data['stores'] = Store::select('id','name')->active()->get()->toArray();

        $data['ic_data']['inventory_count_id'] = '';
        $data['ic_data']['store_name'] = '';
        $data['ic_data']['user_name'] = '';
        $data['ic_data']['business_date'] = '';
        $data['ic_data']['status'] = '';
        $data['ic_data']['updated'] = '';
        $data['ic_data']['has_item'] = false;
        
        if(session()->has('inventory_count_id')){
            $inventory_count_id = session('inventory_count_id');
            $inventory_count = InventoryCountModel::find($inventory_count_id);
            
            $ic_store_id = $inventory_count->store_id;
            $ic_business_date = $inventory_count->business_date;
            $ic_user_name = User::find($inventory_count->user_id)->fullname;
            $ic_status = $inventory_count->status;
            $ic_updated = Carbon::parse($inventory_count->updated_at)->format('Y-m-d');

            $data['ic_data']['inventory_count_id'] = $inventory_count_id;
            $data['ic_data']['store_name'] = $ic_store_id;
            $data['ic_data']['user_name'] = $ic_user_name;
            $data['ic_data']['business_date'] = $ic_business_date;
            $data['ic_data']['status'] = $ic_status;
            $data['ic_data']['updated'] = $ic_updated;
            $data['ic_data']['has_item'] = true;
        }
        
        return view('inventory.inventory_count_items', $data);
    }
}
