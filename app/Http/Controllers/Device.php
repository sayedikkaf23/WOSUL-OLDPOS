<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/* Models */
use App\Models\Device as DeviceModel;
use App\Models\DeviceFeature as DeviceFeatureModel;
use App\Models\Country as CountryModel;
use App\Models\DeviceRole as DeviceRoleModel;
use App\Models\DeviceRoleMenu as DeviceRoleMenuModel;

/* Resources */
use App\Http\Resources\DeviceResource;
use App\Http\Resources\DeviceFeatureResource;
use App\Http\Resources\RoleResource;


class Device extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request)
    {

        //check access
        $data['menu_key'] = 'MM_DEVICE';
        $data['sub_menu_key'] = 'SM_DEVICE';
        // check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('device.devices', $data);
    }

    //This is the function that loads the add/edit page
    public function add_device($slack = null)
    {

        //check access
        $data['menu_key'] = 'MM_DEVICE';
        $data['sub_menu_key'] = 'SM_DEVICE';
        $data['action_key'] = ($slack == null) ? 'A_ADD_DEVICE' : 'A_EDIT_DEVICE';
        // check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('DEVICE_STATUS')->active()->sortValueAsc()->get();

        $data['currency_data'] = CountryModel::select('currency_code', 'currency_name')
            ->where('currency_code', '!=', '')
            ->whereNotNull('currency_code')
            ->active()
            ->groupBy('currency_code')
            ->get();

        $data['device_data'] = null;

        if (isset($slack)) {

            $device = DeviceModel::where('slack', '=', $slack)->first();

            if (empty($device)) {
                abort(404);
            }
            $data['device_data'] = $device;
        }

        return view('device.add_device', $data);
    }


    //This is the function that loads the detail page
    public function detail($slack)
    {

        $data['menu_key'] = 'MM_DEVICE';
        $data['sub_menu_key'] = 'SM_DEVICE';
        $data['action_key'] = 'A_DETAIL_DEVICE';
        check_access([$data['action_key']]);

        $device = DeviceModel::where('slack', '=', $slack)->first();

        if (empty($device)) {
            abort(404);
        }

        $data['device_data'] = new DeviceResource($device);
        $data['device_feature_data'] = DeviceFeatureModel::where('device_id', $data['device_data']->id)->get();

        return view('device.device_detail', $data);
    }

    // new devices

    //This is the function that loads the add/edit page
    public function add_device_category($slack = null)
    {


        //check access
        $data['menu_key'] = 'MM_DEVICE';
        $data['sub_menu_key'] = 'SM_DEVICE';
        $data['action_key'] = ($slack == null) ? 'A_ADD_DEVICE' : 'A_EDIT_DEVICE';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('SUBSCRIPTION_CATEGORY_STATUS')->active()->sortValueAsc()->get();

        $data['device_category_data'] = null;
        if (isset($slack)) {
            $device = DeviceCategoryModel::where('slack', '=', $slack)->first();
            if (empty($device)) {
                abort(404);
            }

            $device_category_data = new DeviceUnitResource($device);
            $data['device_category_data'] = $device_category_data;
        }

        return view('device.add_device_category', $data);
    }

    public function manage_device_role($slack)
    {

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('ROLE_STATUS')->active()->sortValueAsc()->get();

        $data['role_data'] = null;
        if (isset($slack)) {

            $role = DeviceModel::where('slack', '=', $slack)->first();
            if (empty($role)) {
                abort(404);
            }

            $role_data = new DeviceResource($role);

            $menus = DeviceRoleMenuModel::where('device_id', '=', $role->id)->pluck('menu_id')->toArray();

            $data['role_data'] = collect($role_data)->union(collect(['menus' => $menus]));
        }

        $menu_data = $this->get_menus();
        $data['access_menus'] = $menu_data;

        return view('device.manage_device_role', $data);
    }

    private function get_menus()
    {

        $menu_array = [];
        $menu_tree = [];

        $main_menus = DB::table('device_menus')
            ->select('id', 'label', 'type')
            ->orderBy('sort_order', 'ASC')
            ->where('status', '=', 1)
            ->where('type', '=', 'MAIN_MENU')
            ->get();

        foreach ($main_menus as $main_menu) {

            $menu_array['_' . $main_menu->id] = [
                "menu_key" => $main_menu->id,
                "label" => $main_menu->label,
                "type" => $main_menu->type,
            ];

            $sub_menus = DB::table('device_menus')
                ->select('id', 'label', 'type')
                ->orderBy('sort_order', 'ASC')
                ->where('status', '=', 1)
                //->where('type', '=', 'SUB_MENU')
                ->where('parent', '=', $main_menu->id)
                ->get();

            $all_sub_menu_ids = array_pluck($sub_menus, 'id');
            $menu_array['_' . $main_menu->id]['childs'] = $all_sub_menu_ids;

            foreach ($sub_menus as $sub_menu) {

                $menu_array['_' . $main_menu->id]['sub_menu'][$sub_menu->id] = [
                    "menu_key" => $sub_menu->id,
                    "label" => $sub_menu->label,
                    "type" => $sub_menu->type,
                    "sub_items" => [$sub_menu->id]
                ];
                $menu_array['_' . $main_menu->id]['sub_menu'][$sub_menu->id]['siblings'] = $all_sub_menu_ids;

                if ($sub_menu->type == 'SUB_MENU') {

                    $action_menus = DB::table('device_menus')
                        ->select('id', 'label', 'type')
                        ->orderBy('sort_order', 'ASC')
                        ->where('status', '=', 1)
                        ->where('type', '=', 'ACTIONS')
                        ->where('parent', '=', $sub_menu->id)
                        ->get();

                    $all_action_menu_ids = array_pluck($action_menus, 'id');
                    $menu_array['_' . $main_menu->id]['childs'] = array_merge($menu_array['_' . $main_menu->id]['childs'], $all_action_menu_ids);
                    $menu_array['_' . $main_menu->id]['sub_menu'][$sub_menu->id]['childs'] = $all_action_menu_ids;

                    foreach ($action_menus as $action_menu) {
                        $menu_array['_' . $main_menu->id]['sub_menu'][$sub_menu->id]['actions'][] = [
                            "menu_key" => $action_menu->id,
                            "label" => $action_menu->label,
                            "type" => $action_menu->type,
                            "siblings" => $all_action_menu_ids
                        ];
                    }
                }
            }
        }
        return $menu_array;
    }


    public function get_news_devices(Request $request)
    {

        //check access
        $data['menu_key'] = 'MM_NEWS_DEVICE';
        // $data['sub_menu_key'] = 'SM_DEVICE';
        // check_access(array($data['menu_key']));
        $data['order_statuses'] = MasterStatus::select('value', 'label')->filterByKey('NEWS_DEVICE_STATUS')->active()->sortValueAsc()->get();

        return view('news_device.devices', $data);
    }
}
