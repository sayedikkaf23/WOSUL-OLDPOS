<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Supplier as SupplierModel;
use App\Models\Country as CountryModel;
use App\Models\Store as StoreModel;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\SupplierResource;

class Supplier extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_SUPPLIER';
        $data['sub_menu_key'] = 'SM_SUPPLIERS';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('supplier.suppliers', $data);
    }

    //This is the function that loads the add/edit page
    public function add_supplier($slack = null){
        //check access
        $data['menu_key'] = 'MM_SUPPLIER';
        $data['sub_menu_key'] = 'SM_SUPPLIERS';
        $data['action_key'] = ($slack == null)?'A_ADD_SUPPLIER':'A_EDIT_SUPPLIER';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('SUPPLIER_STATUS')->active()->sortValueAsc()->get();

        $data['country_list'] = CountryModel::select('id as country_id', 'name', 'code')
        ->active()
        ->groupBy('code')
        ->get();

        $data['supplier_data'] = null;
        if(request()->logged_user_id == 1)
            {
                $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->oldest()->active()->get();
            }
            else
            { 
               $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->userStores()->oldest()->active()->get();
            }
            foreach($data['all_stores'] as $store)
            {
              if($store->store_logo)
              {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
              }
              else
              {
               $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
              }  
            }
            $currentstoreid = request()->logged_user_store_id;
            $data['selection_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at')->whereRaw("id!={$currentstoreid}")->active()->get();
            foreach($data['selection_stores'] as $store)
            {
              if($store->store_logo)
              {
               $store->store_logo = '<img src="'.Storage::disk("store")->url($store->store_logo).'" style="width:50px;height:50px;"/>'; 
              }
              else
              {
               $store->store_logo = '<img src="'.asset('/images/placeholder_images/placeholder_image.png').'" style="width:50px;height:50px;"/>'; 
              }  
            }
        if(isset($slack)){
            
            $supplier = SupplierModel::where('slack', '=', $slack)->first();
            if (empty($supplier)) {
                abort(404);
            }
            $supplier_data = new SupplierResource($supplier);
            $data['supplier_data'] = $supplier_data;
        }

        return view('supplier.add_supplier', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_SUPPLIER';
        $data['sub_menu_key'] = 'SM_SUPPLIERS';
        $data['action_key'] = 'A_DETAIL_SUPPLIER';
        check_access([$data['action_key']]);

        $supplier = SupplierModel::where('slack', '=', $slack)->first();
        
        if (empty($supplier)) {
            abort(404);
        }

        $supplier_data = new SupplierResource($supplier);
        
        $data['supplier_data'] = $supplier_data;

        return view('supplier.supplier_detail', $data);
    }
}
