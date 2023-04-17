<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Price as PriceModel;
use App\Models\Store as StoreModel;
use App\Models\MasterStatus;
use App\Http\Resources\PriceResource;
use App\Http\Resources\StoreResource;

class Price extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRICE';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('price.prices', $data);
    }

     //This is the function that loads the add/edit page
    public function add_price($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRICE';
        $data['action_key'] = ($slack == null)?'A_ADD_PRICE':'A_EDIT_PRICE';
        
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('PRICE_STATUS')->active()->sortValueAsc()->get();
        $data['price_data'] = null;
        
        $stores = StoreModel::active()->orderBy('name','ASC')->get();
        $data['store_data'] = StoreResource::collection($stores);

        if(isset($slack)){
            
            $price = PriceModel::where('slack', '=', $slack)->first();
            if (empty($price)) {
                abort(404);
            }
            
            $price_data = new PriceResource($price);
            $data['price_data'] = $price_data;
            $data['store_data'] = [];
        }

        return view('price.add_price', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRICE';
        $data['action_key'] = 'A_DETAIL_PRICE';
        check_access([$data['action_key']]);

        $price = PriceModel::where('slack', '=', $slack)->first();
        
        if (empty($price)) {
            abort(404);
        }

        $price_data = new PriceResource($price);
        
        $data['price_data'] = $price_data;

        return view('price.price_detail', $data);
    }

    public function delete_price($slack = null){

        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_PRICE';
        $data['action_key'] = ($slack == null)?'A_ADD_PRICE':'A_EDIT_PRICE';
        check_access(array($data['action_key']));

        // soft delete prices and its options

        $price = PriceModel::where('slack',$slack)->first();
        if(empty($price)){
            abort(404);
        }

        PriceModel::where('slack',$slack)->delete();
        
        return redirect()->back();

    }


}
