<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

/* Models */
use App\Models\Device as DeviceModel;

/* Resources */
use App\Http\Resources\DeviceResource;
use Illuminate\Support\Facades\Storage;

class Device extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_DEVICE';
            // if(check_access(array($data['action_key']), true) == false){
            //     $response = $this->no_access_response_for_listing_table();
            //     return $response;
            // }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];
            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = DeviceModel::select('devices.*', 'master_status.label as status_label', 'master_status.color as status_color')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()

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

            $devices = DeviceResource::collection($query);

            $total_count = DeviceModel::select("id")->get()->count();

            $item_array = [];
            foreach ($devices as $key => $device) {

                $device = $device->toArray($request);

                $item_array[$key][] = $device['image_path'];
                $item_array[$key][] = $device['title'];
                $item_array[$key][] = $device['title_ar'];
                $item_array[$key][] = $device['description'];
                $item_array[$key][] = $device['description_ar'];
                $item_array[$key][] = $device['price'];
                $item_array[$key][] = (isset($device['status']['label'])) ? view('common.status', ['status_data' => ['label' => $device['status']['label'], "color" => $device['status']['color']]])->render() : '-';
                $item_array[$key][] = $device['created_at_label'];
                $item_array[$key][] = $device['updated_at_label'];
                $item_array[$key][] = view('device.layouts.device_actions', ['device' => $device])->render();
            }

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
    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_DEVICE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $device_data_exists = DeviceModel::select('id')
                ->where('title', '=', trim($request->title))
                ->first();
            if (!empty($device_data_exists)) {
                throw new Exception("Device already exists", 400);
            }

            DB::beginTransaction();


            $device = [
                "slack" => $this->generate_slack("devices"),
                "title" => $request->title,
                "title_ar" => $request->title_ar,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "currency" => $request->currency,
                "price" => $request->price,
                "status" => $request->status
            ];

            $device_id = DeviceModel::create($device)->id;

            if (isset($request->image)) {

                $device_image = $request->image;
                $extension = $device_image->getClientOriginalExtension();
                $file_name = $device['slack'] . '_' . uniqid() . '.' . $extension;
                $path = Storage::disk('device')->putFileAs('/', $device_image, $file_name);
                $file_name = basename($path);

                DeviceModel::where('id', $device_id)->update(['image' => $file_name]);
            }

            DB::commit();

            $data['devices'] = DeviceModel::select('slack', 'title')->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Device created successfully"),
                    "data"    => $data['devices']
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

    public function update(Request $request)
    {


        try {

            if (!check_access(['A_ADD_DEVICE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $device_data_exists = DeviceModel::select('id')
                ->where('title', '=', trim($request->title))
                ->where('slack', '!=', trim($request->slack))
                ->first();
            if (!empty($device_data_exists)) {
                throw new Exception(trans("Device already exists"), 400);
            }

            DB::beginTransaction();

            $device = [
                "title" => $request->title,
                "title_ar" => $request->title_ar,
                "description" => $request->description,
                "description_ar" => $request->description_ar,
                "currency" => $request->currency,
                "price" => $request->price,
                "status" => $request->status
            ];

            DeviceModel::where('slack', $request->slack)->update($device);
            $device_id = DeviceModel::where('slack', $request->slack)->first()->id;

            if (isset($request->image)) {

                $device_image = $request->image;
                $extension = $device_image->getClientOriginalExtension();
                $file_name = $request->slack . '_' . uniqid() . '.' . $extension;
                $path = Storage::disk('device')->putFileAs('/', $device_image, $file_name);
                $file_name = basename($path);

                DeviceModel::where('id', $device_id)->update(['image' => $file_name]);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Device updated successfully"),
                    "data"    => $device_id
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
    public function update_role(Request $request, $slack)
    {

        try {

            if (!check_access(['A_EDIT_ROLE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $validator = Validator::make($request->all(), [
                'role_menus'  => $this->get_validation_rules("role_menus", true)
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            DB::beginTransaction();

            $role_details = DeviceModel::select('*')
                ->where([
                    ['slack', '=', $slack]
                ])
                ->first();
            $role_current_menus = SubscriptionRoleMenuModel::where('subscription_id', '=', $role_details->id)->pluck('menu_id')->toArray();
            (count($role_current_menus) > 0) ? sort($role_current_menus) : $role_current_menus;

            $role_menus = explode(",", $request->role_menus);
            (count($role_menus) > 0) ? sort($role_menus) : $role_menus;

            if (count($role_menus) > 0 && ($role_current_menus != $role_menus)) {

                SubscriptionRoleMenuModel::where('subscription_id', $role_details->id)->delete();

                $role_menus = array_unique($role_menus);

                foreach ($role_menus as $role_menu) {
                    $menu = [
                        'subscription_id' => $role_details->id,
                        'menu_id' => $role_menu,
                        'created_by' => $request->logged_user_id
                    ];
                    SubscriptionRoleMenuModel::create($menu);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Role updated successfully",
                    "data"    => $role_details->slack
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


    public function load_devices(Request $request)
    {

        try {

            $category_id = $request->category_id;

            if ($category_id == "") {
                abort(404);
            }

            $conversion_units = DeviceModel::where('subscription_category_id', $request->category_id)
                ->active()
                ->get();

            $response = [
                'data' => $conversion_units
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



    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'title_ar' => 'required',
            'description' => 'required',
            'description_ar' => 'required',
            'currency' => 'required',
            'price' => 'required',
            'status' => 'required'
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function get_news_devices(Request $request)
    {
        try {

            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }
            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';
            $data['action_key'] = 'A_VIEW_NEWS_DEVICE';
            // if(check_access(array($data['action_key']), true) == false){
            //     $response = $this->no_access_response_for_listing_table();
            //     return $response;
            // }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];
            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = NewsDeviceModel::select('newsletter_subscription.*', 'master_status.label as status_label', 'master_status.color as status_color')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_on', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })->whereBetween('newsletter_subscription.created_on', [$from_date, $to_date]);

            if ($request->status != "") {
                $query->where('newsletter_subscription.status', '=', $request->status);
            }

            $query = $query->get();

            $devices = NewsDeviceResource::collection($query);

            $total_count = NewsDeviceModel::select("slack")->get()->count();

            $item_array = [];
            foreach ($devices as $key => $subscription) {

                $subscription = $subscription->toArray($request);

                $item_array[$key][] = $subscription['email'];
                $item_array[$key][] = (isset($subscription['status']['label'])) ? view('common.status', ['status_data' => ['label' => $subscription['status']['label'], "color" => $subscription['status']['color']]])->render() : '-';
                $item_array[$key][] = $subscription['created_on'];
            }

            // dd($item_array);

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
}
