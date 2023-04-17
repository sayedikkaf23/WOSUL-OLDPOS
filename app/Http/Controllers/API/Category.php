<?php

namespace App\Http\Controllers\API;

use App\Models\QoyodCategory;
use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\MainCategoryResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Category as CategoryModel;
use App\Models\MainCategory as MainCategoryModel;
use App\Models\Store as StoreModel;

use App\Http\Resources\Collections\CategoryCollection;

use App\Http\Traits\ZidApiTrait;
use Illuminate\Support\Facades\App;
use App\Http\Traits\QoyodApiTrait;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ZidApiTrait,QoyodApiTrait;

    public function index(Request $request)
    {

        try {
            //print_r($request->access_token);die;
            $data['action_key'] = 'A_VIEW_CATEGORY_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();
            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];

            $order_by_column =   explode(".", $request->columns[$order_by]['name']);
            $order_by_column = end($order_by_column);

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $category_type_filter = $request->category_type;

            $query = CategoryModel::select('category.*', 'master_status.label as status_label', 'master_status.color as status_color')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->categoryStore()
                ->createdUser()
                ->WithCategoryType()

                ->when($category_type_filter == 1, function ($query) {
                    $query->where('parent', 0);
                })

                ->when($category_type_filter == 2, function ($query) {
                    $query->where('parent', '>', 0);
                })

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->get();

            $categories = CategoryResource::collection($query);

            $item_array = [];
            foreach ($categories as $key => $category) {
                $category = $category->toArray($request);
                $item_array[$key][] = $category['label'];

                $item_array[$key][] = $category['category_type'];
                $item_array[$key][] = $category['category_code'];
                $item_array[$key][] = (isset($category['status']['label'])) ? view('common.status', ['status_data' => ['label' => $category['status']['label'], "color" => $category['status']['color']]])->render() : '-';
                $item_array[$key][] = $category['created_at_label'];
                $item_array[$key][] = $category['updated_at_label'];
                $item_array[$key][] = (isset($category['created_by']['fullname'])) ? $category['created_by']['fullname'] : '-';

                $item_array[$key][] = ($category['parent'] == 0 || $category['parent'] == null) ? view('category.layouts.main_category_actions', ['category' => $category])->render() : view('category.layouts.category_actions', ['category' => $category])->render();
            }
            $total_count = count($item_array);

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Filter the categories by category filter
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function filter_by_type(Request $request)
    {

        try {

            $data['action_key'] = 'A_VIEW_CATEGORY_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();
            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];

            $order_by_column =   explode(".", $request->columns[$order_by]['name']);
            $order_by_column = end($order_by_column);

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));


            // $categories = CategoryModel::with('mainCategory')->get();
            // $categories = $this->formatTripDetails($categories);

            $query = CategoryModel::select('category.*', 'master_status.label as status_label', 'master_status.color as status_color')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->createdUser()
                ->WithCategoryType()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->get();

            $categories = CategoryResource::collection($query);
            dd($categories);

            $total_count = CategoryModel::select("id")->get()->count();


            $all_categories = $data;
            $item_array = [];

            foreach ($all_categories as $key => $category) {

                $category = $category->toArray($request);
                $item_array[$key][] = $category['label'];
                $item_array[$key][] = $category['category_code'];
                $item_array[$key][] = (isset($category['status']['label'])) ? view('common.status', ['status_data' => ['label' => $category['status']['label'], "color" => $category['status']['color']]])->render() : '-';
                $item_array[$key][] = $category['created_at_label'];
                $item_array[$key][] = $category['updated_at_label'];
                $item_array[$key][] = (isset($category['created_by']['fullname'])) ? $category['created_by']['fullname'] : '-';
                $item_array[$key][] = view('category.layouts.category_actions', ['category' => $category])->render();
            }

            dd($item_array);

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function set_category_order(Request $request){
        try
        {
        //$request->session()->put("category_list_".$request->logged_user_id,$request->category_ids);
        $category_ids = explode(",",$request->category_ids);
        DB::beginTransaction();
        $index = 1;
        foreach($category_ids as $id)
        {
            DB::update("update category set sort_order=".$index." where id=".$id);
            $index++;
        }
        DB::commit();
        return response()->json($this->generate_response(
            array(
                "message" => trans("Category sorted successfully"),
                "data"    => $request->category_ids
            ),
            'SUCCESS'
        ));
    }
    catch(Exception $ex)
    {
        return response()->json($this->generate_response(
            array(
                "message" => $ex->getMessage(),
                "status_code" => $ex->getCode()
            )
        ));
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            if (!check_access(['A_ADD_CATEGORY'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $category_data_exists = CategoryModel::select('id')
                ->where('label', '=', trim($request->category_name))
                ->first();
            if (!empty($category_data_exists)) {
                throw new Exception(trans("Category already exists"), 400);
            }

            DB::beginTransaction();

            $category_image_file = null;
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $category_image_file   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('category')->put('/' . $category_image_file, $img, 'public');
            }

            if($request->category_applied_on == 'all_stores')
            {
                if($request->logged_user_id == 1)
                {
                    $category_applicable_stores_list = StoreModel::select('id')->oldest()->active()->pluck('id');
                    $category_applicable_store_array  = $category_applicable_stores_list ->toArray();
                }
                else
                {
                    $category_applicable_stores_list = StoreModel::select('stores.id as id', 'stores.created_at as created_at')->userStores()->oldest()->active()->pluck('id');
                    $category_applicable_store_array  = $category_applicable_stores_list ->toArray();
                }
                $category_applicable_stores = implode(",", $category_applicable_store_array);
            }
            else
            {
                $category_applicable_stores = $request->category_applicable_stores;
            }

            $category = [
                "slack" => $this->generate_slack("category"),
                "parent" => $request->parent,
                "store_id" => $request->logged_user_store_id,
                "category_code" => Str::random(6),
                "label" => Str::title($request->category_name),
                "label_ar" => $request->category_name_ar,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "category_applied_on" => $request->category_applied_on,
                "category_applicable_stores" => $category_applicable_stores,
                "status" => $request->status,
                "created_by" => $request->logged_user_id,
                "category_image" => $category_image_file
            ];

            $category_id = CategoryModel::create($category)->id;

            $code_start_config = Config::get('constants.unique_code_start.category');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;

            $category_code = [
                "category_code" => "CAT" . ($code_start + $category_id)
            ];
            CategoryModel::where('id', $category_id)
                ->update($category_code);

            if ($request->zid_sync_option == "true") {

                $zid_store_detail = StoreModel::find(session('store_id'))->first();

                if (empty($zid_store_detail)) {
                    throw new Exception(trans("Please update your Zid Store API token or Zid Store Id from store settings"), 400);
                }

                $zid_store_api_token = $zid_store_detail->zid_store_api_token;

                $store_zid_sub_category_response = $this->store_zid_sub_category($request->all());
                if ($store_zid_sub_category_response['status'] == false) {
                    throw new Exception(trans($store_zid_sub_category_response['message']), 400);
                }

                $zid_subcategory_id = $this->sync_zid_find_category_id($category);
                CategoryModel::where('id', $category_id)->update(['zid_category_id' => $zid_subcategory_id]);
            }

            //qoyod entry
            if(Session('qoyod_status')){
                $cat_data = array(
                    'id' => $category_id,
                    'label' => $category['label'],
                    'description' => $category['description'],
                    'parent' => $category['parent'],
                );
                $this->qoyod_create_category((object)$cat_data);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Category created successfully"),
                    "data"    => $category['slack']
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_CATEGORY'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $item = CategoryModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new CategoryResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Category loaded successfully",
                    "data"    => $item_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_CATEGORY_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $list = new CategoryCollection(CategoryModel::select('*')
                ->categoryStore()->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Categories loaded successfully",
                    "data"    => $list
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {

        try {

            if (!check_access(['A_EDIT_CATEGORY'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $category_data_exists = CategoryModel::select('id')
                ->where([
                    ['slack', '!=', $slack],
                    ['label', '=', trim($request->category_name)],
                ])
                ->first();
            if (!empty($category_data_exists)) {
                throw new Exception("Category already exists", 400);
            }

            $category_data = CategoryModel::where('slack', $request->slack)->first();

            DB::beginTransaction();

            $category_image_file = $category_data->category_image;
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $category_image_file   = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('category')->put('/' . $category_image_file, $img, 'public');
            }

            if($request->category_applied_on == 'all_stores')
            {
                if($request->logged_user_id == 1)
                {
                    $category_applicable_stores_list = StoreModel::select('id')->oldest()->active()->pluck('id');
                    $category_applicable_store_array  = $category_applicable_stores_list ->toArray();
                }
                else
                {
                    $category_applicable_stores_list = StoreModel::select('stores.id as id', 'stores.created_at as created_at')->userStores()->oldest()->active()->pluck('id');
                    $category_applicable_store_array  = $category_applicable_stores_list ->toArray();
                }
                $category_applicable_stores = implode(",", $category_applicable_store_array);
            }
            else
            {
                $category_applicable_stores = $request->category_applicable_stores;
            }

            $category = [
                "label" => Str::title($request->category_name),
                "label_ar" => Str::title($request->category_name_ar),
                "parent" => $request->parent,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "category_applied_on" => $request->category_applied_on,
                "category_applicable_stores" => $category_applicable_stores,
                "status" => $request->status,
                'updated_by' => $request->logged_user_id,
                "category_image" => $category_image_file,
            ];

            $action_response = CategoryModel::where('slack', $slack)
                ->update($category);

            //Qoyod
            if(Session('qoyod_status')){
                $qoyod_category = QoyodCategory::select('qoyod_category_id')->where('category_id',$category_data->id)->first();
                if(isset($qoyod_category) && $qoyod_category->qoyod_category_id>0){
                    $cat_data = array(
                        'label' => $category['label'],
                        'description' => $category['description'],
                        'parent' => $category['parent'],
                    );
                    $this->qoyod_update_category((object)$cat_data,$qoyod_category->qoyod_category_id);
                }
            }

            DB::commit();

            // update category on ZID also 
            if ($request->zid_sync_option == "true") {
                $this->update_zid_sub_category($request->all(), $category_data->zid_category_id);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Category updated successfully"),
                    "data"    => $slack
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => $this->get_validation_rules("name_label", true),
            'status' => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }


    public function load_categories_by_supplier(Request $request)
    {

        //$categories = CategoryModel::parentCategory()->active()->get();
        DB::enableQueryLog();
        $query = CategoryModel::select('category.*')
            ->categoryStore()
            ->createdUser()
            ->WithCategoryType()
            ->where('parent', 0)
            ->where('category.status', 1)
            ->get();
        $categories = CategoryResource::collection($query);
        return response()->json($categories);
    }

    public function load_subcategories(Request $request)
    {

        if ($request->catetgory_id == 0) {
            $subcategories = CategoryModel::childCategory()->categoryStore()->active()->get();
        } else {
            $subcategories = CategoryModel::where('parent', $request->catetgory_id)->categoryStore()->active()->get();
        }

        return response()->json($subcategories);
    }

    public function get_active_category(Request $request)
    {
        try {

            $updated_at = (isset($request->updated_at) && $request->updated_at != '') ? $request->updated_at : '';
            
            $list = CategoryModel::select('*')->categoryStore()->active()->orderBy('created_at', 'desc')
            ->when( ($updated_at != '' ), function($query) use($updated_at) {
                $query->where('updated_at','>=',$updated_at);
            })->get();

            $category_data = CategoryResource::collection($list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Categories loaded successfully",
                    "data"    => $category_data
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function store_zid_sub_category($category)
    {

        $parent_category = CategoryModel::findOrFail($category['parent']);
        $zid_parent_category_id = $parent_category->zid_category_id;

        // store category for english 
        $form_params = [
            'parent_id' => $zid_parent_category_id,
            'name[en]' => $category['category_name'],
            'name[ar]' => strip_tags($category['category_name_ar']),
            'description[en]' => $category['description'],
            'description[ar]' => strip_tags($category['description_ar'])
        ];

        return $this->sync_zid_add_category($form_params);
    }

    public function update_zid_sub_category($category, $zid_category_id)
    {

        $form_params = [
            'name[en]' => $category['category_name'],
            'description[en]' => $category['description'],
            'name[ar]' => strip_tags($category['category_name_ar']),
            'description[ar]' => strip_tags($category['description_ar'])
        ];

        return $this->sync_zid_update_category($form_params, $zid_category_id);
    }

}
