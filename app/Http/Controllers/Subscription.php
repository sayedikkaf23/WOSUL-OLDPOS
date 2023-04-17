<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/* Models */
use App\Models\Subscription as SubscriptionModel;
use App\Models\SubscriptionFeature as SubscriptionFeatureModel;
use App\Models\Country as CountryModel;
use App\Models\SubscriptionRole as SubscriptionRoleModel;
use App\Models\SubscriptionRoleMenu as SubscriptionRoleMenuModel;

/* Resources */
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SubscriptionFeatureResource;
use App\Http\Resources\RoleResource;


class Subscription extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        
        //check access
        $data['menu_key'] = 'MM_SUBSCRIPTION';
        $data['sub_menu_key'] = 'SM_SUBSCRIPTION';
        // check_access(array($data['menu_key'],$data['sub_menu_key']));


        return view('subscription.subscriptions', $data);
    }

    //This is the function that loads the add/edit page
    public function add_subscription($slack = null){

        //check access
        $data['menu_key'] = 'MM_SUBSCRIPTION';
        $data['sub_menu_key'] = 'SM_SUBSCRIPTION';
        $data['action_key'] = ($slack == null)?'A_ADD_SUBSCRIPTION':'A_EDIT_SUBSCRIPTION';
        // check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('SUBSCRIPTION_STATUS')->active()->sortValueAsc()->get();

        $data['currency_data'] = CountryModel::select('currency_code', 'currency_name')
        ->where('currency_code', '!=', '')
        ->whereNotNull('currency_code')
        ->active()
        ->groupBy('currency_code')
        ->get();

        $data['subscription_data'] = null;
        $data['subscription_features_data'] = null;
        if(isset($slack)){
            
            $subscription = SubscriptionModel::with('subscription_features')->where('slack', '=', $slack)->first();
                    
            if (empty($subscription)) {
                abort(404);
            }
            $data['subscription_data'] = $subscription;

            if (empty($subscription->subscription_features)) {
                abort(404);
            }
            $data['subscription_features_data'] = $subscription->subscription_features;

        }

        return view('subscription.add_subscription', $data);
    }

    
    //This is the function that loads the detail page
    public function detail($slack){

        $data['menu_key'] = 'MM_SUBSCRIPTION';
        $data['sub_menu_key'] = 'SM_SUBSCRIPTION';
        $data['action_key'] = 'A_DETAIL_SUBSCRIPTION';
        check_access([$data['action_key']]);

        $subscription = SubscriptionModel::where('slack', '=', $slack)->first();

        if (empty($subscription)) {
            abort(404);
        }

        $data['subscription_data'] = new SubscriptionResource($subscription);
        $data['subscription_feature_data'] = SubscriptionFeatureModel::where('subscription_id',$data['subscription_data']->id)->get();
        
        return view('subscription.subscription_detail', $data);
    }

    // new subscriptions

    //This is the function that loads the add/edit page
    public function add_subscription_category($slack = null){


        //check access
        $data['menu_key'] = 'MM_SUBSCRIPTION';
        $data['sub_menu_key'] = 'SM_SUBSCRIPTION';
        $data['action_key'] = ($slack == null)?'A_ADD_SUBSCRIPTION':'A_EDIT_SUBSCRIPTION';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('SUBSCRIPTION_CATEGORY_STATUS')->active()->sortValueAsc()->get();

        $data['subscription_category_data'] = null;
        if(isset($slack)){
            $subscription = SubscriptionCategoryModel::where('slack', '=', $slack)->first();
            if (empty($subscription)) {
                abort(404);
            }
            
            $subscription_category_data = new SubscriptionUnitResource($subscription);
            $data['subscription_category_data'] = $subscription_category_data;
        }

        return view('subscription.add_subscription_category', $data);
    }

    public function manage_subscription_role($slack){

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('ROLE_STATUS')->active()->sortValueAsc()->get();

        $data['role_data'] = null;
        if(isset($slack)){
            
            $role = SubscriptionModel::where('slack', '=', $slack)->first();
            if (empty($role)) {
                abort(404);
            }

            $role_data = new SubscriptionResource($role);

            $menus = SubscriptionRoleMenuModel::where('subscription_id', '=', $role->id)->pluck('menu_id')->toArray();

            $data['role_data'] = collect($role_data)->union(collect(['menus' => $menus]));
        }

        $menu_data = $this->get_menus();
        $data['access_menus'] = $menu_data;

        return view('subscription.manage_subscription_role', $data);

    }

    private function get_menus(){

        $menu_array = [];
        $menu_tree = [];
        
        $main_menus = DB::table('subscription_menus')
        ->select('id', 'label', 'type')
        ->orderBy('sort_order', 'ASC')
        ->where('status', '=', 1)
        ->where('type', '=', 'MAIN_MENU')
        ->get();

        foreach($main_menus as $main_menu){
            
            $menu_array['_'.$main_menu->id] = [
                "menu_key" => $main_menu->id,
                "label" => $main_menu->label,
                "type" => $main_menu->type,
            ];

            $sub_menus = DB::table('subscription_menus')
            ->select('id', 'label' , 'type')
            ->orderBy('sort_order', 'ASC')
            ->where('status', '=', 1)
            //->where('type', '=', 'SUB_MENU')
            ->where('parent', '=', $main_menu->id)
            ->get();

            $all_sub_menu_ids = array_pluck($sub_menus, 'id');
            $menu_array['_'.$main_menu->id]['childs'] = $all_sub_menu_ids;

            foreach($sub_menus as $sub_menu){
                
                $menu_array['_'.$main_menu->id]['sub_menu'][$sub_menu->id] = [
                    "menu_key" => $sub_menu->id,
                    "label" => $sub_menu->label,
                    "type" => $sub_menu->type,
                    "sub_items" => [$sub_menu->id]
                ];
                $menu_array['_'.$main_menu->id]['sub_menu'][$sub_menu->id]['siblings'] = $all_sub_menu_ids;

                if($sub_menu->type == 'SUB_MENU'){
                    
                    $action_menus = DB::table('subscription_menus')
                    ->select('id', 'label', 'type')
                    ->orderBy('sort_order', 'ASC')
                    ->where('status', '=', 1)
                    ->where('type', '=', 'ACTIONS')
                    ->where('parent', '=', $sub_menu->id)
                    ->get();

                    $all_action_menu_ids = array_pluck($action_menus, 'id');
                    $menu_array['_'.$main_menu->id]['childs'] = array_merge($menu_array['_'.$main_menu->id]['childs'], $all_action_menu_ids);
                    $menu_array['_'.$main_menu->id]['sub_menu'][$sub_menu->id]['childs'] = $all_action_menu_ids;

                    foreach ($action_menus as $action_menu) {
                        $menu_array['_'.$main_menu->id]['sub_menu'][$sub_menu->id]['actions'][] = [
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

    
    public function get_news_subscriptions(Request $request){
        
        //check access
        $data['menu_key'] = 'MM_NEWS_SUBSCRIPTION';
        // $data['sub_menu_key'] = 'SM_SUBSCRIPTION';
        // check_access(array($data['menu_key']));
        $data['order_statuses'] = MasterStatus::select('value', 'label')->filterByKey('NEWS_SUBSCRIPTION_STATUS')->active()->sortValueAsc()->get();
        
        return view('news_subscription.subscriptions', $data);
    }

}
