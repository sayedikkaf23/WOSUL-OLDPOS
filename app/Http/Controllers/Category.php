<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store as StoreModel;
use App\Models\Category as CategoryModel;

use App\Http\Resources\CategoryResource;
use App\Http\Traits\ZidApiTrait;

class Category extends Controller
{
    use ZidApiTrait;

    //This is the function that loads the listing page
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_CATEGORY';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        return view('category.categories', $data);
    }

    public function category_screen(Request $request){
        try
        {
            $data['menu_key'] = 'MM_STOCK';
            $data['sub_menu_key'] = 'SM_CATEGORY';
            check_access(array($data['menu_key'], $data['sub_menu_key']));
            $data['categories'] = CategoryModel::parentCategory()->categoryStore()->active()->get();
            $data['categories'] = $data['categories']->toArray();
            $data['user_id'] = $request->logged_user_id;
            array_multisort(array_column($data['categories'],'label'), SORT_ASC, $data['categories']);
            
            return view('category.category_screen', $data);
        }
        catch(Exception $ex){
            throw $ex;
        }
    }

    //This is the function that loads the add/edit page
    public function add_category($slack = null)
    {
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_CATEGORY';
        $data['action_key'] = ($slack == null) ? 'A_ADD_CATEGORY' : 'A_EDIT_CATEGORY';
        check_access(array($data['action_key']));

        $data['sync_zid_category'] = false;

        if (check_access(['A_SYNC_ZID_CATEGORY'], true)) {
            $data['sync_zid_category'] = true;
        }

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('CATEGORY_STATUS')->active()->sortValueAsc()->get();

        // $data['main_categories'] = MasterStatus::select('value', 'label')->filterByKey('CATEGORY_STATUS')->active()->sortValueAsc()->get();
        $data['main_categories'] = CategoryModel::parentCategory()->categoryStore()->active()->get();
        if(request()->logged_user_id == 1)
        {
            $data['all_stores'] = StoreModel::select('id','name as text', 'store_logo')->oldest()->active()->get();
        }
        else
        {
            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at as created_at')->userStores()->oldest()->active()->get();
        }
        $data['category_data'] = null;
        if (isset($slack)) {
            $category = CategoryModel::where('slack', '=', $slack)->categoryStore()->first();
            if (empty($category)) {
                abort(404);
            }

            $category_data = new CategoryResource($category);
            $data['category_data'] = $category_data;
        }

        if (isset($category))
        {
            if(isset($category->mainCategory) && $category->mainCategory->category_applied_on == 'specific_stores')
            {            
                $stores = explode(',', $category->mainCategory->category_applicable_stores);
                $data['category_stores'] = StoreModel::whereIn('id', $stores)->select('id','name as text', 'store_logo')->oldest()->active()->get();
            }
            else
            {
                if(request()->logged_user_id == 1)
                {
                    $data['category_stores'] = StoreModel::select('id','name as text', 'store_logo')->oldest()->active()->get();
                }
                else
                {
                    $data['category_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at as created_at')->userStores()->oldest()->active()->get();
                }
            }
        }
        else
        {
            $data['category_stores'] = null;
        }

        $data['zid_status'] = $this->check_zid_status();

        if (isset($slack)) {
            if ($category->parent == 0 || $category->parent == null) {
                // Is Parent Category
                return view('category.add_maincategory', $data);
            } else {
                return view('category.add_category', $data);
            }
        } else {
            return view('category.add_category', $data);
        }
    }

    //This is the function that loads the detail page
    public function detail($slack)
    {

        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_CATEGORY';
        $data['action_key'] = 'A_DETAIL_CATEGORY';
        check_access([$data['action_key']]);

        $category = CategoryModel::where('slack', '=', $slack)->first();

        if (empty($category)) {
            abort(404);
        }



        $category_data = new CategoryResource($category);
        $data['category_data'] = $category_data;

        if($category_data->category_applied_on == "specific_stores")
        {
            $stores = explode(',', $category_data->category_applicable_stores);
            $data['store_data'] = StoreModel::whereIn('id', $stores)->pluck('name');
        }
        else
        {
            $data['store_data'] = null;
        }

        return view('category.category_detail', $data);
    }
}
