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
use App\Models\Subscription as SubscriptionModel;
use App\Models\SubscriptionFeature as SubscriptionFeatureModel;
use App\Models\SubscriptionRoleMenu as SubscriptionRoleMenuModel;
use App\Models\NewsSubscription as NewsSubscriptionModel;

/* Resources */
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\NewsSubscriptionResource;

class Subscription extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_SUBSCRIPTION';
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

            $query = SubscriptionModel::select('subscriptions.*', 'master_status.label as status_label', 'master_status.color as status_color')
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

            $subscriptions = SubscriptionResource::collection($query);
            $total_count = SubscriptionModel::select("id")->get()->count();

            $item_array = [];
            foreach ($subscriptions as $key => $subscription) {

                $subscription = $subscription->toArray($request);

                // $item_array[$key][] = $subscription_category['unit_code'];
                $item_array[$key][] = $subscription['title'];
                $item_array[$key][] = $subscription['short_description'];
                $item_array[$key][] = ($subscription['plan_tenure'] == 1) ? 'Monthly' : 'Yearly';
                $item_array[$key][] = $subscription['amount'];
                $item_array[$key][] = number_format($subscription['discount'], 0) . "%";
                $item_array[$key][] = $subscription['currency'];
                $item_array[$key][] = $subscription['discount_description'];
                $item_array[$key][] = ($subscription['is_live'] == 1) ? '<span class="text-success"><strong>Yes</strong></span>' : '<span class="text-muted">No</span>';

                $item_array[$key][] = (isset($subscription['status']['label'])) ? view('common.status', ['status_data' => ['label' => $subscription['status']['label'], "color" => $subscription['status']['color']]])->render() : '-';
                $item_array[$key][] = $subscription['created_at_label'];
                $item_array[$key][] = $subscription['updated_at_label'];
                // $item_array[$key][] = (isset($subscription['created_by']['fullname']))?$subscription['created_by']['fullname']:'-';
                $item_array[$key][] = view('subscription.layouts.subscription_actions', ['subscription' => $subscription])->render();
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
    public function store(Request $request)
    {

        try {

            if (!check_access(['A_ADD_SUBSCRIPTION'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $subscription_data_exists = SubscriptionModel::select('id')
                ->where('title', '=', trim($request->title))
                ->first();
            if (!empty($subscription_data_exists)) {
                throw new Exception("Subscription already exists", 400);
            }

            DB::beginTransaction();

            $subscription = [
                "slack" => $this->generate_slack("subscriptions"),
                "title" => $request->title,
                "title_ar" => $request->title_ar,
                "short_description" => $request->short_description,
                "short_description_ar" => $request->short_description_ar,
                "plan_tenure" => $request->plan_tenure,
                "currency" => $request->currency,
                "amount" => $request->amount,
                "discount" => ($request->discount == null || $request->discount == '') ? 0 : $request->discount,
                "discount_description" => $request->discount_description,
                "discount_description_ar" => $request->discount_description_ar,
                "url" => $request->url,
                "url_ar" => $request->url_ar,
                "color_code" => $request->color_code,
                "is_featured" => $request->is_featured,
                "is_live" => $request->is_live,
                "status" => $request->status
            ];

            $subscription_id = SubscriptionModel::create($subscription)->id;

            $subscription_features = json_decode($request->subscription_features);
            if (!empty($subscription_features)) {
                foreach ($subscription_features as $rs) {
                    SubscriptionFeatureModel::create([
                        'subscription_id' => $subscription_id,
                        'title' => $rs->title,
                        'title_ar' => $rs->title_ar
                    ]);
                }
            }

            DB::commit();

            $data['subscriptions'] = SubscriptionModel::select('slack', 'title')->sortTitleAsc()->active()->get();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Subscription created successfully"),
                    "data"    => $data['subscriptions']
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

            if (!check_access(['A_ADD_SUBSCRIPTION'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $subscription_data_exists = SubscriptionModel::select('id')
                ->where('title', '=', trim($request->title))
                ->where('slack', '!=', trim($request->slack))
                ->first();
            if (!empty($subscription_data_exists)) {
                throw new Exception(trans("Subscription already exists"), 400);
            }

            DB::beginTransaction();

            $subscription = [
                "title" => $request->title,
                "title_ar" => $request->title_ar,
                "short_description" => $request->short_description,
                "short_description_ar" => $request->short_description_ar,
                "plan_tenure" => $request->plan_tenure,
                "currency" => $request->currency,
                "amount" => $request->amount,
                "discount" => $request->discount,
                "discount_description" => $request->discount_description,
                "discount_description_ar" => $request->discount_description_ar,
                "url" => $request->url,
                "url_ar" => $request->url_ar,
                "color_code" => ($request->color_code == null) ? '#FFFFFF' : $request->color_code,
                "is_featured" => $request->is_featured,
                "is_live" => $request->is_live,
                "status" => $request->status
            ];

            SubscriptionModel::where('slack', $request->slack)->update($subscription);
            $subscription_id = SubscriptionModel::where('slack', $request->slack)->first()->id;

            $subscription_features = json_decode($request->subscription_features);

            if (!empty($subscription_features)) {

                if (count($subscription_features) > 0) {
                    SubscriptionFeatureModel::where('subscription_id', $subscription_id)->delete();
                }

                foreach ($subscription_features as $rs) {
                    $item = (array) $rs;
                    $item['subscription_id'] = $subscription_id;
                    $item['updated_at'] = now();
                    SubscriptionFeatureModel::insert($item);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Subscription updated successfully"),
                    "data"    => $subscription_id
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

            $role_details = SubscriptionModel::select('*')
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


    public function load_subscriptions(Request $request)
    {

        try {

            $category_id = $request->category_id;

            if ($category_id == "") {
                abort(404);
            }

            $conversion_units = SubscriptionModel::where('subscription_category_id', $request->category_id)
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

    public function load_subscription(Request $request)
    {
        try{
            $subscription_id = $request->id;
            $subscription_data = SubscriptionModel::where('id', $subscription_id)
                ->active()
                ->get();
            $response = ['data'=>$subscription_data];
            return response()->json($response);
        }
        catch(Exception $ex){
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
            // 'title_ar' => 'required',
            'plan_tenure' => 'required',
            'currency' => 'required',
            'amount' => 'required|numeric',
            // 'discount' => 'numeric',
            // 'color_code' => 'required',
            'is_live' => 'required',
            'status' => 'required'
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function get_news_subscriptions(Request $request)
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
            $data['action_key'] = 'A_VIEW_NEWS_SUBSCRIPTION';
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

            $query = NewsSubscriptionModel::select('newsletter_subscription.*', 'master_status.label as status_label', 'master_status.color as status_color')
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

            $subscriptions = NewsSubscriptionResource::collection($query);

            $total_count = NewsSubscriptionModel::select("slack")->get()->count();

            $item_array = [];
            foreach ($subscriptions as $key => $subscription) {

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
