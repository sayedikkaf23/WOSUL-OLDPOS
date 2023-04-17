<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category as CategoryModel;
use App\Models\Store as StoreModel;

use App\Http\Resources\MainCategoryResource;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Traits\ZidApiTrait;
use Illuminate\Support\Facades\Storage;

class MainCategory extends Controller
{
    use ZidApiTrait;

    //This is the function that loads the listing page
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MAIN_CATEGORY';
        // check_access(array($data['menu_key'],$data['sub_menu_key']));

        $data['main_categories'] = CategoryModel::all();

        return view('category.main_categories', $data);
    }

    //This is the function that loads the add/edit page
    // public function add_category($slack = null){
    //     //check access
    //     $data['menu_key'] = 'MM_STOCK';
    //     $data['sub_menu_key'] = 'SM_MAIN_CATEGORY';
    //     $data['action_key'] = ($slack == null)?'A_ADD_CATEGORY':'A_EDIT_CATEGORY';
    //     check_access(array($data['action_key']));
    //     $data['action'] = route('save_main_category');

    //     $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MAIN_CATEGORY_STATUS')->active()->sortValueAsc()->get();

    //     $data['main_category_data'] = null;

    //     return view('category.add_main_category', $data);
    // }

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
        $data['category_data'] = null;


        if (isset($slack)) {
            $category = CategoryModel::where('slack', '=', $slack)->first();
            if (empty($category)) {
                abort(404);
            }

            $category_data = new MainCategoryResource($category);
            $data['category_data'] = $category_data;
        }

        // $data['all_stores'] = StoreModel::select('id','name as text', 'store_logo')->oldest()->active()->get();

        if(request()->logged_user_id == 1)
        {
            $data['all_stores'] = StoreModel::select('id','name as text', 'store_logo')->oldest()->active()->get();
        }
        else    
        {
            $data['all_stores'] = StoreModel::select('stores.id as id','name as text', 'store_logo', 'stores.created_at as created_at')->userStores()->oldest()->active()->get();
        }

        $data['zid_status'] = $this->check_zid_status();

        return view('category.add_maincategory', $data);
    }

    // public function save_category(MainCategoryRequest $request){

    //     CategoryModel::create([
    //         'label' => $request->label,
    //         'description' => $request->description,0
    //         'category_code' => $request->category_code,
    //         'status' => $request->status
    //     ]);

    //     session()->flash('alert-success','Main Category Added Successfully');
    //     return redirect()->back();

    // }

    //This is the function that loads the detail page
    public function detail($slack)
    {

        $data['menu_key'] = 'MM_STOCK';
        $data['sub_menu_key'] = 'SM_MAIN_CATEGORY';
        $data['action_key'] = 'A_DETAIL_CATEGORY';
        check_access([$data['action_key']]);

        $category = CategoryModel::where('slack', '=', $slack)->first();

        if (empty($category)) {
            abort(404);
        }

        $data['subcategory_data'] = CategoryModel::where('parent', $category->id)->get();
        $category_data = new MainCategoryResource($category);

        $data['category_data'] = $category_data;

        if($category_data->category_applied_on == "specific_stores")
        {
            $stores = explode(',', $category_data->category_applicable_stores);
            $data['store_data'] = StoreModel::whereIn('id', $stores)->pluck('name');
        }
        else
        {
            $data['store_data'] = StoreModel::userStores()->pluck('name');
        }

        $data['category_data']['category_image'] = '<img src="'.Storage::disk('category')->url($data['category_data']['category_image']).'" width="50" height="50" />';
        foreach($data['subcategory_data']as $category)
        {
            if($category->category_image!=null)
            {
              $category->category_image = '<img src="'.Storage::disk('category')->url($category->category_image).'" width="50" height="50" />';
            }
            else
            {
                $category->category_image="No Image Found";
            }
        }

        return view('category.main_category_detail', $data);
    }
}
