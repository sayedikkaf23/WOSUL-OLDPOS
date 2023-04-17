<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Firebase\JWT\JWT;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

use App\Models\User as UserModel;
use App\Models\UserToken as UserTokenModel;
use App\Models\Role as RoleModel;
use App\Models\Store as StoreModel;
use App\Models\Order as OrderModel;
use App\Models\UserStore as UserStoreModel;
use App\Models\SettingEmail as SettingEmailModel;
use App\Models\Language as LanguageModel;
use App\Models\Menu as MenuModel;
use App\Models\UserMenu as UserMenuModel;
use App\Models\HRM\Employee as EmployeeModel;

use App\Http\Resources\UserResource;

use App\Http\Resources\Collections\UserCollection;

use App\Http\Controllers\API\Role as RoleAPI;
use App\Http\Resources\StoreResource;
use App\Mail\ForgotPassword;
use App\Models\Taxcode as TaxcodeModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class User extends Controller
{
    /**
     * Authenticate API
     *
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'email'     => $this->get_validation_rules("email", true),
                'password'  => $this->get_validation_rules("password", true)
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $user_data = UserModel::select('users.*', 'stores.currency_name', 'stores.currency_code', 'stores.tax_code_id', 'stores.store_logo')->roleJoin()
                ->storeJoin()
                ->where([
                    ['roles.status', '=', 1],
                    ['email', '=', $request->email]
                ])
                ->active()
                ->first();

            if(!$user_data)
                    throw new Exception("Invalid User");
                    
            if($request->store_id !== '' && $request->store_id !== null && $request->email != 'admin@wosul.sa'){
                $store_status = UserStoreModel::where(['user_id'=>$user_data->id,'store_id'=> $request->store_id])->first();
                if(!$store_status){
                    throw new Exception("Store Not Found", 401);   
                }
            }
            
            
            $merchant_id = 0;
            $merchant_slack = 0;
            $merchant_email = UserModel::find(2);

            if (isset($merchant_email)) {
                $merchant_email = $merchant_email->email;
                $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
                $merchant_id = mysqli_query($connect, 'SELECT id,slack FROM merchants WHERE email = "' . $merchant_email . '" ');
                if (mysqli_num_rows($merchant_id) > 0) {
                    $merchant_result = mysqli_fetch_assoc($merchant_id);
                    $merchant_id = $merchant_result['id'];
                    $merchant_slack = $merchant_result['slack'];
                }
                mysqli_close($connect);
            }


            if ($user_data) {
                $password = $user_data->password;
                if (Hash::check($request->password, $password)) {

                    if(isset($merchant_id) && $merchant_id>0){
                        //today's date
                        $today = date('Y-m-d');
                        //get the active subscription
                        $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
                        $query = "SELECT * FROM merchant_subscriptions WHERE merchant_id = ".$merchant_id." AND status=1 AND start_date <= '".$today."' AND end_date >='".$today."' ";
                        $active_subscription = mysqli_query($connect, $query);
                        $active_subscription = mysqli_fetch_assoc($active_subscription);

                        //check if subscription is not found(expired!) or any subscription is in pending status
                        if(empty($active_subscription)){
                            //A. find expired subscription and update status to the 3(expired)
                            $old_subscription = mysqli_query($connect, "SELECT * FROM merchant_subscriptions WHERE merchant_id = ".$merchant_id." AND end_date <'".$today."' ORDER BY id DESC LIMIT 1");
                            $old_subscription = mysqli_fetch_assoc($old_subscription);
                            if(!empty($old_subscription)){
                                //update previous expired subscription status to 3
                                mysqli_query($connect, "UPDATE merchant_subscriptions SET status=3 WHERE merchant_id=".$merchant_id." AND end_date<'".$today."' ");

                                if($old_subscription['is_trial']==1){
                                    throw new Exception("Your trial subscription has expired! Please renew it.", 401);
                                }else{
                                    throw new Exception("Your subscription has expired! Please renew it.", 401);
                                }
                            }else{
                                //B. if pending subscription found then give appropreate message
                                $query = "SELECT * FROM merchant_subscriptions WHERE merchant_id = ".$merchant_id." AND status=0 ";
                                $pending_subscription = mysqli_query($connect, $query);
                                $pending_subscription = mysqli_fetch_assoc($pending_subscription);
                                if(isset($pending_subscription) && $pending_subscription['id']>0){
                                    throw new Exception("Your subscription is inactive! Please make payment and activate your subscription.", 401);
                                }else{
                                    throw new Exception("Your subscription has expired! Please renew it.", 401);
                                }
                            }
                        }
                    }

                    // if($request->logged_user_platform_type != 1){
                    //     // Session::flash('message', 'This is a message!'); 
                    //     // return redirect(route('logout'));
                    // }
                    
                    $store_data = StoreModel::select('*')
                        ->where('id',  trim($request->store_id))
                        ->active()
                        ->first();
                    
                    $store_data = new StoreResource($store_data);
                    
                    $request->login_from = ($request->login_from == '') ? 'WEB' : $request->login_from;

                    if(!in_array($request->login_from,['WEB','IOS','ANDROID'])){
                        throw new Exception("Login From values should be WEB, ANDROID OR IOS", 401);   
                    }
                    
                    $check_logged = json_decode($this->check_logged_in($request,$user_data));

                    if(!$check_logged->device){
                        return response()->json($this->generate_response(
                            array(
                                "message" => "You are already logged in another device, Do you want to logout from that device and login here?",
                                "data" => [
                                    "device_status"    => 0,
                                ],
                            ),
                            'SUCCESS'
                        ));
                    }

                    // Update store id of logged in user
                    if (!empty($request->store_id))
                        UserModel::roleJoin()->where(['users.email' => $request->email, 'roles.status' => 1])->update(['users.store_id' => $request->store_id]);
                    
                    //generate access token
                    $encode_array = array(
                        "user_id" => $user_data->id,
                        "user_slack" => $user_data->slack
                    );
                    $access_token = $this->jwt_encode($encode_array);

                    //Get the first link
                    $first_link = $this->get_user_default_link($user_data);
                    $user_data->initial_link = (!empty($first_link)) ? route($first_link->route) : "/";
                    if ($user_data->initial_link == "/") {
                        throw new Exception("You don't have access to the system. Please contact the system administrator for assistance.", 401);
                    }

                    //getting qoyod api key
                    $setting_data = DB::table('setting_app')->first();
                    
                    // $this->remove_expired_session($user_data->slack);
                    $this->set_user_session($user_data, $access_token);
                    // added later
                    $request->session()->put('full_name', $user_data->fullname);
                    $request->session()->put('currency_code', $user_data->currency_code);
                    $request->session()->put('currency_name', $user_data->currency_name);
                    $request->session()->put('store_tax_code', $user_data->tax_code_id);
                    $request->session()->put('store_id', $request->store_id);
                    $request->session()->put('store_logo', $user_data->store_logo);
                    $request->session()->put('merchant_id', $merchant_id);
                    $request->session()->put('merchant_slack', $merchant_slack);
                    $request->session()->put('is_admin', $user_data->is_admin);
                    $request->session()->put('qoyod_status', $setting_data->qoyod_status);
                    $request->session()->put('qoyod_api_key', $setting_data->qoyod_api_key);

                    $session_id = $request->session()->getId();

                    //update access token in user_access_tokens table
                    $user_token_array = [
                        'access_token' => $access_token,
                    ];
                    UserTokenModel::find($check_logged->access_token_data->id)->update($user_token_array);

                    $user_detail = UserModel::where('id', $user_data->id)->first();

                    $user = collect(new UserResource($user_detail));

                    $user['access_token'] = $access_token;

                    if (!empty($request->store_id)) {
                        //return last order number
                        $orderAPI = new Order();
                        $value_date = $orderAPI->get_order_value_date();
                        $order = DB::table('orders')->select('order_number')->where('store_id', '=', $request->store_id)->where('value_date', $value_date)->orderBy('id', 'DESC')->first();
                        $user['order_number'] = isset($order) ? $order->order_number : '0';

                        // return qr code link
                        $subdomain = DB::table('stores')
                            ->join('qr_codes', 'stores.id', '=', 'qr_codes.store_id')
                            ->join('users', 'users.store_id', '=', 'stores.id')
                            ->select('stores.name')
                            ->where(['users.store_id' => $request->store_id, 'stores.id' => $request->store_id])
                            ->first();

                        if (isset($subdomain) && isset($subdomain->name)) {

                            $user['wosulin_restaurant_link'] = env('WOSULIN_URL') . 'restaurant/';

                            $user['wosulin_restaurant_link'] = isset($subdomain) ? env('WOSULIN_URL') . 'restaurant/' . $this->makeAlias($subdomain->name) : '';
                        } else {
                            $user['wosulin_restaurant_link'] = "";
                        }
                        //end qr code 

                    }

                    $user_menu = UserMenuModel::with('menu')->where('user_id', $user_data->id)->get();

                    $user['user_menu_access'] = $user_menu;

                    
                    
                    $user['store'] = $store_data;
                    $user['merchant_id'] = $merchant_id;
                    $user['merchant_slack'] = $merchant_slack;

                    $default_tax_count = StoreModel::withoutGlobalScopes()->get()->count() * 4;
                    $user['is_store_tax_exists'] =  (TaxcodeModel::withoutGlobalScopes()->get()->count() == $default_tax_count) ? true : false ;
                    
                    $userLanguage = UserModel::where('id',$user_data->id)->update(
                        ["language_id"=>$request->language_id]); 
                        
                    return response()->json($this->generate_response(
                        array(
                            "message" => "Authenticated successfully",
                            "data"    => $user,
                            "link"    => $user_data->initial_link
                        ),
                        'SUCCESS'
                    ));
                } else {
                    throw new Exception("Invalid email or password", 401);
                }
            } else {
                throw new Exception("Invalid email or password", 401);
            }
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function makeAlias($name)
    {
        $cyr = [
            '?',  '?',  '?',   '?',  '?',  '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?',
            '?',  '?',  '?',   '?',  '?',  '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?',
        ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q',
        ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_USER_LISTING';
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
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = UserModel::select('users.*', 'master_status.label as status_label', 'master_status.color as status_color', 'roles.status as role_status', 'roles.label as role_label')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->roleJoin()
                ->createdUser()

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
                ->hideSuperAdminRole()
                ->hideCurrentLoggedUser($request->logged_user_id)
                ->get();

            $users = UserResource::collection($query);

            $total_count = UserModel::select('id')->hideSuperAdminRole()->hideCurrentLoggedUser($request->logged_user_id)->get()->count();

            $item_array = [];
            foreach ($users as $key => $user) {

                $user = $user->toArray($request);

                $item_array[$key][] = $user['user_code'];
                $item_array[$key][] = $user['fullname'];
                $item_array[$key][] = $user['email'];
                $item_array[$key][] = $user['phone'];
                $item_array[$key][] = (isset($user['role']['status'])) ? view('common.status_indicators', ['status' => $user['role']['status']])->render() . $user['role']['label'] : '-';
                $item_array[$key][] = (isset($user['status']['label'])) ? view('common.status', ['status_data' => ['label' => $user['status']['label'], "color" => $user['status']['color']]])->render() : '-';
                $item_array[$key][] = $user['created_at_label'];
                $item_array[$key][] = (isset($user['updated_at_label'])) ? $user['updated_at_label'] : '-';
                $item_array[$key][] = (isset($user['created_by']) && isset($user['created_by']['fullname'])) ? $user['created_by']['fullname'] : '-';
                $item_array[$key][] = view('user.layouts.user_actions', array('user' => $user))->render();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            if (!check_access(['A_ADD_USER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            //check user email already exists
            $user_email_exists = UserModel::where('email', $request->email)->first();
            if ($user_email_exists) {
                throw new Exception(trans("Email is already added, try signing in"));
            }

            $role_data = RoleModel::select('id', 'label')->where('slack', '=', $request->role)->resolveSuperAdminRole()->active()->first();
            if (!$role_data) {
                throw new Exception(trans("Invalid role selected"), 400);
            }

            $role_label = isset($role_data->label) ? $role_data->label : '';


            // $password = Str::random(6);
            $password = (isset($request->password)) ? $request->password : Str::random(6);
            $hashed_password = Hash::make($password);

            DB::beginTransaction();


            if (isset($request->is_master)) {
                $is_master = (int) $request->is_master;
            } else {
                $is_master = 0;
            }

            if (isset($request->is_cashier)) {
                $is_cashier = (int) $request->is_cashier;
                if($is_cashier == 1){
                    UserModel::query()->update(['is_cashier'=>0]);
                }
            } else {
                $is_cashier = 0;
            }

            $user = [
                "slack" => $this->generate_slack("users"),
                "user_code" => Str::random(6),
                "email" => $request->email,
                "password" => $hashed_password,
                "login_code" => $request->login_code,
                "init_password" => null,
                "fullname" => $request->fullname,
                "phone" => $request->phone,
                "role_id" => $role_data->id,
                "status" => $request->status,
                "is_master" => $is_master,
                "is_cashier" => $is_cashier,
                "created_by" => $request->logged_user_id
            ];


            $user_id = UserModel::create($user)->id;



            $code_start_config = Config::get('constants.unique_code_start.user');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;

            $user_code = [
                "user_code" => ($code_start + $user_id)
            ];
            UserModel::where('id', $user_id)
                ->update($user_code);

            $role_api = new RoleAPI();
            $role_api->update_user_roles($request, $role_data->id);

            $this->update_user_stores($request, $user['slack']);


            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("User created successfully"),
                    "data"    => $user['slack']
                ),
                trans('SUCCESS')
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

            if (!check_access(['A_DETAIL_USER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $item = UserModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new UserResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "User loaded successfully",
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

            if (!check_access(['A_VIEW_USER_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $list = new UserCollection(UserModel::select('*')
                ->hideSuperAdminRole()
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Users loaded successfully",
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
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        
        try {

            if (!check_access(['A_EDIT_USER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $this->validate_request($request);

            $this->block_admin_edit($request, $slack);

            //check user role
            $user_role = UserModel::select('role_id')->where('slack', '=', $slack)->first();
            if ($user_role->role_id == 1) {
                throw new Exception("Invalid request", 400);
            }

            //check user email already exists
            $user_email_exists = UserModel::where('email', '=', $request->email)->where('slack', '!=', $slack)->first();

            if ($user_email_exists) {
                throw new Exception("Email is already used by another user");
            }

            $user_exist = UserModel::where('slack', $slack)->first();
            $user_id = isset($user_exist->id) ? $user_exist->id : '';

            $role_data = RoleModel::select('id', 'label')->where('slack', '=', $request->role)->resolveSuperAdminRole()->active()->first();
            if (!$role_data) {
                throw new Exception("Invalid role selected", 400);
            }

            $role_label = isset($role_data->label) ? $role_data->label : '';


            if (isset($request->is_master)) {
                $is_master = (int) $request->is_master;
            } else {
                $is_master = 0;
            }
            
            if (isset($request->is_cashier)) {
                $is_cashier = (int) $request->is_cashier;
                if($is_cashier == 1 && $user_exist->is_cashier == 0){
                    UserModel::query()->update(['is_cashier'=>0]);
                }
            } else {
                $is_cashier = 0;
            }

            DB::beginTransaction();

            $user = [
                "email" => $request->email,
                "fullname" => $request->fullname,
                "login_code" => $request->login_code,
                "phone" => $request->phone,
                "role_id" => $role_data->id,
                "status" => $request->status,
                "is_master" =>  $is_master,
                "is_cashier" =>  $is_cashier,
                "updated_by" => $request->logged_user_id
            ];

            if (!empty($request->password)) {
                $user['password'] = Hash::make($request->password);
                $user['init_password'] = null;
            }

            $data = UserModel::where('slack', $slack)
                ->update($user);




            $role_api = new RoleAPI();
            $role_api->update_user_roles($request, $role_data->id);
            $this->update_user_stores($request, $slack);

            $employee_exist = EmployeeModel::where('user_id', $user_id)->first();
            if (isset($employee_exist)) {
                $employee_data = [
                    "email" => $request->email,
                ];

                $data = EmployeeModel::where('user_id', $user_id)
                    ->update($employee_data);
            }

            DB::commit();



            return response()->json($this->generate_response(
                array(
                    "message" => trans("User updated successfully"),
                    "data"    => $data
                ),
                trans('SUCCESS')
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
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update_basic_profile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'     => $this->get_validation_rules("email", true),
                'fullname' => $this->get_validation_rules("fullname", true),
                'phone'  => $this->get_validation_rules("phone", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $user_slack = $request->logged_user_slack;

            $this->block_admin_edit($request, $user_slack);

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            //check user email already exists
            $user_email_exists = UserModel::where('email', $request->email)->where('slack', '!=', $user_slack)->first();
            if ($user_email_exists) {
                throw new Exception(trans("Email is already used by another user"));
            }

            $user = [
                "email" => $request->email,
                "fullname" => $request->fullname,
                "phone" => $request->phone,
            ];

            $data = UserModel::where('slack', $user_slack)
                ->update($user);

            return response()->json($this->generate_response(
                array(
                    "message" => "Profile updated successfully",
                    "data"    => $data
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
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => $this->get_validation_rules("password", true),
                'new_password' => $this->get_validation_rules("new_password", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $user_slack = $request->logged_user_slack;

            $this->block_admin_edit($request, $user_slack);

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            $old_hashed_password = Hash::make($request->current_password);
            $new_hashed_password = Hash::make($request->new_password);

            //check old hashed password matches
            $password_matches = UserModel::where('slack', $user_slack)->first();
            if (Hash::check($request->current_password, $password_matches->password) == false) {
                throw new Exception("Current password doesn't match");
            }

            $user = array(
                "password" => $new_hashed_password,
                "init_password" => null
            );

            $data = UserModel::updateOrCreate(
                ['slack' => $user_slack],
                $user
            )->save();

            return response()->json($this->generate_response(
                array(
                    "message" => "Password updated successfully",
                    "data"    => $data
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
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update_profile_image(Request $request)
    {
        try {

            $user_slack = $request->logged_user_slack;
            $photo = $request->profile_photo;

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            $this->block_admin_edit($request, $user_slack);

            $user_image = UserModel::select('profile_image')->where('slack', '=', $user_slack)->first();

            Storage::disk('profile')->delete(
                [
                    $user_image->profile_image,
                    'medium_' . $user_image->profile_image,
                    'small_' . $user_image->profile_image
                ]
            );

            $upload_dir = Config::get('constants.upload.profile.upload_path');

            $extension = $photo->getClientOriginalExtension();
            $path = Storage::disk('profile')->putFileAs('/', $photo, $user_slack . '.' . $extension);

            $filename = basename($path);

            //large image
            $image = Image::make($photo);
            $large_path = $upload_dir . $filename;
            $image->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->resizeCanvas(400, 400, 'center', false, 'F5F5F5');
            $image->save($large_path);
            $image->destroy();

            //medium image
            $image = Image::make($photo);
            $medium_path = $upload_dir . 'medium_' . $filename;
            $image->fit(100);
            $image->fit(100, 100, function ($constraint) {
                $constraint->upsize();
            });
            $image->save($medium_path);
            $image->destroy();

            //small image
            $image = Image::make($photo);
            $small_path = $upload_dir . 'small_' . $filename;
            $image->fit(35);
            $image->fit(35, 35, function ($constraint) {
                $constraint->upsize();
            });
            $image->save($small_path);
            $image->destroy();

            $user = [
                "profile_image" => $filename,
            ];

            $data = UserModel::where('slack', $user_slack)
                ->update($user);

            return response()->json($this->generate_response(
                array(
                    "message" => "Profile photo updated successfully",
                    "data"    => $data
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
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function remove_profile_image(Request $request)
    {
        try {

            $user_slack = $request->logged_user_slack;

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            $this->block_admin_edit($request, $user_slack);

            $user_image = UserModel::select('profile_image')->where('slack', '=', $user_slack)->first();

            Storage::disk('profile')->delete(
                [
                    $user_image->profile_image,
                    'medium_' . $user_image->profile_image,
                    'small_' . $user_image->profile_image
                ]
            );

            $user = [
                "profile_image" => '',
            ];

            $data = UserModel::where('slack', $user_slack)
                ->update($user);


            return response()->json($this->generate_response(
                array(
                    "message" => "Profile photo removed successfully",
                    "data"    => $data
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

    public function update_user_stores(Request $request, $user_slack)
    {

        if ($user_slack == '') {
            return;
        }

        $selected_stores = explode(",", $request->user_stores);

        $user_data = UserModel::select('id')->where('slack', '=', $user_slack)->first();
        if (empty($user_data)) {
            return;
        }

        $user_stores = UserStoreModel::where('user_id', '=', $user_data->id)
            ->pluck('store_id')
            ->toArray();
        (count($user_stores) > 0) ? sort($user_stores) : $user_stores;

        $selected_stores_array = StoreModel::whereIn('slack', $selected_stores)
            ->pluck('id')
            ->toArray();
        (count($selected_stores_array) > 0) ? sort($selected_stores_array) : $selected_stores_array;

        if ($user_stores != $selected_stores_array) {

            $user_stores_array = [];
            foreach ($selected_stores_array as $selected_stores_array_item) {
                $user_stores_array[] = [
                    'user_id' => $user_data->id,
                    'store_id' => $selected_stores_array_item,
                    'created_by' => $request->logged_user_id,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
            }

            UserStoreModel::where('user_id', $user_data->id)->delete();
            UserStoreModel::insert($user_stores_array);
        }
    }

    public function get_user_default_link($user_data)
    {
        $default_link = '/';
        //Get the default link
        if ($user_data->role_id != 1) {
            $default_link = DB::table('user_menus')
                ->select('menus.id', 'menus.route')
                ->leftJoin('menus', function ($join) {
                    $join->on('menus.id', '=', 'user_menus.menu_id');
                    $join->where('menus.status', '=', 1);
                })
                ->where('user_menus.user_id', $user_data->id)
                ->whereRaw("menus.route IS NOT NULL and menus.route !='' ")
                ->orderBy('sort_order', 'ASC')
                ->orderBy('menus.parent', 'ASC')
                ->get()
                ->first();
        } else {
            $default_link = DB::table('menus')
                ->select('menus.id', 'menus.route')
                ->where("menus.status", '=', 1)
                ->whereRaw("menus.route IS NOT NULL and menus.route !='' ")
                ->orderBy('sort_order', 'ASC')
                ->orderBy('menus.parent', 'ASC')
                ->get()
                ->first();
        }
        return $default_link;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slack
     * @return \Illuminate\Http\Response
     */
    public function update_profile_store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'store' => $this->get_validation_rules("slack", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $store_slack    = $request->store;
            $user_slack     = $request->logged_user_slack;

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            $user_data = UserModel::select('*')->where('slack', '=', $user_slack)->active()->first();
            if (empty($user_data)) {
                throw new Exception("Invalid request", 400);
            }

            $store_data = StoreModel::select('stores.id', 'store_code', 'name', 'address')
                ->where('slack', '=', trim($store_slack))
                ->active()
                ->first();
            // dd($store_data);

            $user_stores = UserStoreModel::where('user_id', '=', $user_data->id)
                ->pluck('store_id')
                ->toArray();
            (count($user_stores) > 0) ? sort($user_stores) : $user_stores;

            if (!in_array($store_data->id, $user_stores) && $request->logged_user_role_id != 1) {
                throw new Exception("Invalid request", 400);
            }

            $user = [
                "store_id" => $store_data->id,
            ];

            $data = UserModel::where('slack', $user_slack)
                ->update($user);

            $first_link = $this->get_user_default_link($user_data);
            $default_link = (!empty($first_link)) ? route($first_link->route) : "/";

            return response()->json($this->generate_response(
                array(
                    "message" => "User store updated successfully",
                    "data"    => $data,
                    "link"    => $default_link
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

    public function forgot_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => $this->get_validation_rules("email", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $email = $request->email;
            $reset_tries = 0;

            $user_data = UserModel::select('id', 'slack', 'email', 'password_reset_max_tries', 'password_reset_last_tried_on')->where('email', $email)->first();
            if (!$user_data) {
                throw new Exception("There is no user with email: " . $email);
            }

            if ($user_data->password_reset_last_tried_on != "") {
                $current_date = date("Y-m-d");
                $last_tried_date = date("Y-m-d", strtotime($user_data->password_reset_last_tried_on));

                if ($last_tried_date == $current_date && $user_data->password_reset_max_tries >= 3) {
                    throw new Exception("You have already tried 3 times today. Please contact administrator for password reset.", 400);
                }

                if ($last_tried_date == $current_date && $user_data->password_reset_max_tries < 3) {
                    $reset_tries = $user_data->password_reset_max_tries + 1;
                } else if ($last_tried_date != $current_date) {
                    $reset_tries = $reset_tries + 1;
                }
            } else {
                $reset_tries = $reset_tries + 1;
            }

            $password_token = Str::random(50);

            /*$email_setting = SettingEmailModel::select('*')->active()->first();

            if (!$email_setting) {
                throw new Exception("Email setting not configured. Please contact administrator for password reset.");
            }   */

            DB::beginTransaction();

            Mail::to($user_data->email)->send(new ForgotPassword(['user_slack' => $user_data->slack, 'password_reset_token' => $password_token]));

            $password_array = [
                "password_reset_token" => $password_token,
                "password_reset_max_tries" => $reset_tries,
                "password_reset_last_tried_on" => now()
            ];

            $data = UserModel::where('id', $user_data->id)
                ->update($password_array);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Password reset email sent",
                    "data"    => $data
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

    public function reset_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'new_password' => $this->get_validation_rules("new_password", true),
                'new_password_confirmation' => $this->get_validation_rules("password", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $user_slack = $request->user_slack;
            $password_reset_token = $request->password_reset_token;

            if (empty($user_slack) || empty($password_reset_token)) {
                throw new Exception("Invalid request", 400);
            }

            $user_data = UserModel::select('slack')->where([
                ['slack', '=', $user_slack],
                ['password_reset_token', '=', $password_reset_token],
            ])->first();
            if (!$user_data) {
                throw new Exception("Invalid request");
            }

            $new_password_hashed_password = Hash::make($request->new_password);
            $new_password_confirmation_hashed_password = Hash::make($request->new_password_confirmation);

            //check hashed password matches
            if (Hash::check($request->new_password_confirmation, $new_password_hashed_password) == false) {
                throw new Exception("Passwords doesn't match");
            }

            $user = array(
                "password" => $new_password_hashed_password,
                "init_password" => null,
                "password_reset_token" => null,
                "password_reset_max_tries" => null,
                "password_reset_last_tried_on" => null,
            );

            $data = UserModel::where('slack', $user_data->slack)
                ->update($user);

            return response()->json($this->generate_response(
                array(
                    "message" => "Password updated successfully",
                    "data"    => $data
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

    public function validate_request($request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => $this->get_validation_rules("email", true),
            'fullname' => $this->get_validation_rules("fullname", true),
            'phone'  => $this->get_validation_rules("phone", true),
            'role'  => $this->get_validation_rules("role", true),
            'status'  => $this->get_validation_rules("status", true),
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function block_admin_edit($request, $slack)
    {

        $block_action = false;
        $user_slack = $slack;
        $route_name = Route::currentRouteName();

        if (App::environment('demo')) {

            switch ($route_name) {
                case 'update_user':
                case 'update_basic_profile':
                case 'update_password':
                case 'update_profile_image':
                case 'remove_profile_image':
                    $user_role = UserModel::select('role_id')->where('slack', '=', $user_slack)->first();
                    if ($user_role->role_id != '') {
                        $block_action = true;
                    }
                    break;
            }
        }
        if ($block_action == true) {
            throw new Exception("Editing is disabled in demo mode", 400);
        }
    }

    public function reset_user_password(Request $request, $slack)
    {

        try {

            /* Commented by chandan */
            // if($request->is_super_admin == false){
            //     throw new Exception("Invalid request", 400);
            // }

            //check user role
            $user_role = UserModel::select('role_id')->where('slack', '=', $slack)->first();
            if ($user_role->role_id == 1) {
                throw new Exception("Invalid request", 400);
            }

            DB::beginTransaction();

            $password = Str::random(10);
            $new_hashed_password = Hash::make($password);

            $user = array(
                "password" => $new_hashed_password,
                "init_password" => null,
            );

            $data = UserModel::where('slack', $slack)
                ->update($user);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "User password reset successfully",
                    "data"    => ['secret' => $password]
                ),
                trans('SUCCESS')
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

    public function remove_expired_session($user_slack)
    {
        $user_detail = UserModel::where('slack', $user_slack)->first();
        $user_id = $user_detail->id;

        $user_tokens = UserTokenModel::select('session_id')->where('user_id', $user_id)->get();

        if (count($user_tokens) > 0) {
            foreach ($user_tokens as $user_token) {
                $session_data = DB::table('sessions')->select('last_activity')->where('id', $user_token->session_id)->first();
                if ($session_data) {
                    $last_activity = $session_data->last_activity;
                    $current_time_stamp = time();
                    $days_difference = round(abs($current_time_stamp - $last_activity) / 60 / 60 / 24, 2);
                    if ($days_difference >= 2) {
                        UserTokenModel::where('session_id', $user_token->session_id)->delete();
                        DB::table('sessions')->where('id', '=', $user_token->session_id)->delete();
                    }
                }
            }
        }
    }

    public function remove_session($token)
    {
        $user_token = UserTokenModel::select('session_id')->where('access_token', $token)->first();

        UserTokenModel::where('session_id', $user_token->session_id)->delete();
        DB::table('sessions')->where('id', '=', $user_token->session_id)->delete();
    }

    public function check_logged_in(Request $request,$user_data){
        
        $session_id = $request->session()->getId();

        $device = 1;

        $user_id = $user_data->id;

        $user_session_array = [
            'user_id' => $user_id,
            'session_id' => $session_id,
        ];
        UserTokenModel::firstOrCreate($user_session_array);
        
        $user_emails = explode(',',env('DEVICE_WHITELIST'));

        $user_tokens = UserTokenModel::select('session_id')->where('user_id', $user_id)->whereNotIn('user_id',function($query)use($user_emails){
            $query->select('id')->from('users')->whereIn('email',$user_emails);
        })->where('session_id', '!=', $session_id)->get();

        $access_token_data = UserTokenModel::where('session_id', $session_id)->where('user_id', $user_id)->first()->makeVisible(['id'])->toArray();

        if ($user_tokens->count() > 0) {
            $device = 0;
            if($request->approve == 1){
                UserTokenModel::where('user_id', $user_id)->where('session_id', '!=', $session_id)->delete();
                // UserTokenModel::where('session_id', $session_id)->update(['access_old_device'=> 0]);
                $device = 1;
            }
            elseif($request->approve == 0){
                // UserTokenModel::where('session_id', $session_id)->update(['access_old_device'=> 1]);
                $device = 0;
            }
        }
        
        return json_encode(['device' => $device, 'access_token_data' => $access_token_data]);
    }

    public function filter_users(Request $request)
    {
        try {

            $keyword = $request->keyword;

            $user_list = UserModel::select("*")
                ->where('user_code', 'like', $keyword . '%')
                ->orWhere('fullname', 'like', $keyword . '%')
                ->orWhere('email', 'like', $keyword . '%')
                ->orWhere('phone', 'like', $keyword . '%')
                ->limit(25)
                ->get();

            $users = UserResource::collection($user_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Users filtered successfully",
                    "data" => $users
                ),
                trans('SUCCESS')
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

    public function update_profile_language(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lang_code' => $this->get_validation_rules("string", true),
            ]);
            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $lang_code    = $request->lang_code;
            $user_slack     = $request->logged_user_slack;

            if (empty($user_slack)) {
                throw new Exception("Invalid request", 400);
            }

            $language_data = LanguageModel::select('id')->where('language_constant', '=', $lang_code)->active()->first();
            if (empty($language_data)) {
                throw new Exception("Invalid request", 400);
            }

            $user = [
                "language_id" => $language_data->id,
            ];

            $data = UserModel::where('slack', $user_slack)
                ->update($user);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("User language updated successfully"),
                    "data"    => $data,
                ),
                trans('SUCCESS')
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

    public function load_user_list(Request $request)
    {
        try {

            $restaurant_waiter_role_id = StoreModel::find(session('store_id'))->first();

            if (empty($restaurant_waiter_role_id)) {
                throw new Exception('Waiter Role Not Found', 400);
            } else {
                $restaurant_waiter_role_id = $restaurant_waiter_role_id->restaurant_waiter_role_id;
            }

            $keywords = $request->keywords;
            $role_slack = $request->role;
            $waiter = $request->waiter;

            // $role_data = RoleModel::where('slack', '=', $role_slack)->first();

            $user_list = UserModel::select('*')
                ->when($keywords, function ($query, $keywords) {
                    $query->where(function ($query) use ($keywords) {
                        $query->where('fullname', 'like', '%' . $keywords . '%')
                            ->orWhere('email', 'like', $keywords . '%')
                            ->orWhere('phone', 'like', $keywords . '%')
                            ->orWhere('user_code', 'like', $keywords . '%');
                    });
                })
                // ->when(($role_slack != ''), function ($query, $role_slack) use ($role_data){
                //     $query->where('role_id', '=', $role_data->id);
                // })
                ->when(($waiter == true), function ($query) use ($restaurant_waiter_role_id) {
                    // $query->hideSuperAdminRole();
                    $query->where('role_id', $restaurant_waiter_role_id);
                })
                ->active()
                ->get();

            $users = UserResource::collection($user_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Users loaded successfully",
                    "data"    => $users
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

    // Added later
    public function get_sub_menus(Request $request)
    {

        $main_menu = MenuModel::where('menu_key', $request->menu_key)->first();
        $main_menu_id = $main_menu->id;

        $data = [];
        if ($main_menu->route != "") {
            $data = array(
                'is_sub_menu' => 0,
                'route' => $main_menu->route
            );
        } else {

            $is_sub_menu = 1;

            if ($request->logged_user_id == 1) {
                $submenus = MenuModel::where('parent', $main_menu_id)->where('type', 'SUB_MENU')->get();
            } else {
                $submenus = MenuModel::where('parent', $main_menu_id)->where('type', 'SUB_MENU')->active()->get();
            }

            foreach ($submenus as $submenu) {

                $user_menu = UserMenuModel::where('user_id', $request->logged_user_id)->where('menu_id', $submenu->id)->first();

                $subset = [];

                if ($request->logged_user_id != 1 && (isset($user_menu) || $user_menu != null)) {

                    $subset = array(
                        'id' => $submenu->label,
                        'label' => $submenu->label,
                        'route' => route($submenu->route),
                        'image' => $submenu->image,
                    );
                } else if ($request->logged_user_id == 1) {

                    $subset = array(
                        'id' => $submenu->label,
                        'label' => $submenu->label,
                        'route' => route($submenu->route),
                        'image' => $submenu->image,
                    );

                    // if user is not a restaurant user, orders submenu should redirect to add order
                    // commented for #550 by chandan
                    // ($request->logged_user_id != '1' && $submenu->route == 'orders') ? $subset['route'] = 'add_order' : $subset['route'] = route($submenu->route);

                } else {

                    $subset = [];
                }


                if (count($subset) > 0) {
                    $dataset[] = $subset;
                }
            }

            $data = array(
                'is_sub_menu' => 1,
                'sub_menu' => $dataset
            );
        }

        return response()->json($data);
    }

    public function authenticate_hrm_user(Request $request, $slack)
    {

        try {

            if (empty($slack)) {
                throw new Exception("No Id present");
            }

            $user_data = UserModel::select('users.*', 'stores.currency_name', 'stores.currency_code', 'stores.tax_code_id', 'stores.store_logo')->roleJoin()
                ->storeJoin()
                ->where([
                    ['roles.status', '=', 1],
                    ['users.slack', '=', $slack]
                ])
                ->active()
                ->first();

            $merchant_id = 0;
            $merchant_slack = 0;
            $merchant_email = UserModel::find(2);

            if (isset($merchant_email)) {
                $merchant_email = $merchant_email->email;
                $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
                $merchant_id = mysqli_query($connect, 'SELECT id,slack FROM merchants WHERE email = "' . $merchant_email . '" ');
                if (mysqli_num_rows($merchant_id) > 0) {
                    $merchant_result = mysqli_fetch_assoc($merchant_id);
                    $merchant_id = $merchant_result['id'];
                    $merchant_slack = $merchant_result['slack'];
                }
                mysqli_close($connect);
            }


            if ($user_data) {

                //generate access token
                $encode_array = array(
                    "user_id" => $user_data->id,
                    "user_slack" => $user_data->slack
                );
                $access_token = $this->jwt_encode($encode_array);

                //Get the first link
                $first_link = $this->get_user_default_link($user_data);
                $user_data->initial_link = (!empty($first_link)) ? route($first_link->route) : "/";
                if ($user_data->initial_link == "/") {
                    throw new Exception("You don't have access to the system. Please contact the system administrator for assistance.", 401);
                }

                $this->remove_expired_session($user_data->slack);
                $this->set_user_session($user_data, $access_token);
                // added later

                $request->session()->put('currency_code', $user_data->currency_code);
                $request->session()->put('currency_name', $user_data->currency_name);
                $request->session()->put('store_tax_code', $user_data->tax_code_id);
                $request->session()->put('store_id', $user_data->store_id);
                $request->session()->put('store_logo', $user_data->store_logo);
                $request->session()->put('merchant_id', $merchant_id);
                $request->session()->put('merchant_slack', $merchant_slack);
                $request->session()->put('is_admin', $user_data->is_admin);
                $request->session()->put('is_master', $user_data->is_master);

                $session_id = $request->session()->getId();


                //update access token in user_access_tokens table
                $user_token_array = [
                    'user_id' => $user_data->id,
                    'access_token' => $access_token,
                    'session_id' => $session_id
                ];
                UserTokenModel::create($user_token_array)->id;

                $user_detail = UserModel::where('id', $user_data->id)->first();

                $user = collect(new UserResource($user_detail));

                $user['access_token'] = $access_token;

                // Update store id of logged in user
                if (!empty($request->store_id)) {
                    UserModel::where(['email' => $request->email, 'status' => 1])->update(['store_id' => $request->store_id]);
                    //return last order number
                    $order = DB::table('orders')->select('order_number')->where('store_id', '=', $request->store_id)->latest()->first();
                    $user['order_number'] = isset($order) ? $order->order_number : '';


                    // return qr code link
                    $subdomain = DB::table('stores')
                        ->join('qr_codes', 'stores.id', '=', 'qr_codes.store_id')
                        ->join('users', 'users.store_id', '=', 'stores.id')
                        ->select('stores.name')
                        ->where(['users.store_id' => $request->store_id, 'stores.id' => $request->store_id])
                        ->first();

                    if (isset($subdomain) && isset($subdomain->name)) {

                        $user['wosulin_restaurant_link'] = env('WOSULIN_URL') . 'restaurant/';

                        $user['wosulin_restaurant_link'] = isset($subdomain) ? env('WOSULIN_URL') . 'restaurant/' . $this->makeAlias($subdomain->name) : '';
                    } else {
                        $user['wosulin_restaurant_link'] = "";
                    }
                    //end qr code 

                }

                $user_menu = UserMenuModel::with('menu')->where('user_id', $user_data->id)->get();

                $user['user_menu_access'] = $user_menu;

                $store_data = StoreModel::select('*')
                    ->where('id',  trim($request->store_id))
                    ->active()
                    ->first();
                $store_data = new StoreResource($store_data);
                $user['store'] = $store_data;
                $user['merchant_id'] = $merchant_id;
                $user['merchant_slack'] = $merchant_slack;
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));

            return redirect()->route('home');
        }
    }

    public function generate_login_code()
    {
        try {
            do {
                $login_code = $this->rand_num(5);

                $user_code = UserModel::where('login_code', $login_code)->get();
            } while (!$user_code);

            return response()->json($this->generate_response(
                array(
                    "message" => "User Login Code Generated Successfully",
                    "data" => $login_code
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                ),
            ));
        }
    }

    public function store_list_by_code(Request $request)
    {
        try {
            
            $data = [];
            $host = explode(".",$request->getHttpHost());
            $merchant_db = $host[0]."_wosul";
            
            $login_code = $request->code;

            // $merchant_db = $merchant . "_wosul";
            // $db_connection = mysqli_connect('localhost',env('DB_USERNAME'),env('DB_PASSWORD'));
            // $db_connection = mysqli_query($db_connection,'SHOW DATABASES LIKE "'.$merchant_db.'" ');

            // if (mysqli_num_rows($db_connection) == 0) 
            // {
            //     throw new Exception("Invalid Merchant", 400);
            // }

            // $merchant_db = $request->getHttpHost();
            
            $connect = mysqli_connect('localhost',env('DB_USERNAME'),env('DB_PASSWORD'),$merchant_db);
            $user = mysqli_query($connect,'SELECT id FROM users WHERE status = 1 and login_code = "'.$login_code.'" LIMIT 1 ');
            if(mysqli_num_rows($user) > 0){
                
                $user = mysqli_fetch_assoc($user);
                $user_stores = mysqli_query($connect,'SELECT s.id,s.slack,s.name FROM stores s INNER JOIN user_stores us ON s.id = us.store_id WHERE us.user_id = "'.$user['id'].'"');
                if(mysqli_num_rows($user_stores) > 0){
                    while($row = mysqli_fetch_assoc($user_stores)){
                        $dataset = [];
                        $dataset = $row;
                        $dataset['id'] = (int) $row['id'];
                        $data[] = $dataset;
                    }
                }

            }else{
                throw new Exception("Invalid User", 400);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "User Store List",
                    "data" => $data
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                ),
            ));
        }
    }

    public function login_with_code(Request $request)
    {
        try {
            $login_code = $request->code;
            $login_code_length = strlen((string)$login_code);

            if (empty($login_code)) {
                throw new Exception("Please provide a login code", 400);
            }

            if (is_numeric($login_code) != 1) {
                throw new Exception("login code is not a number", 400);
            }

            if ($login_code_length < 5 || $login_code_length > 5) {
                throw new Exception("Please provide a 5 digit login code", 400);
            }

            $user_data = UserModel::select('users.*', 'stores.currency_name', 'stores.currency_code', 'stores.tax_code_id', 'stores.store_logo')->roleJoin()
            ->storeJoin()
            ->where([
                ['roles.status', '=', 1],
                ['login_code', '=', $login_code]
            ])
            ->active()
            ->first();

            if(!$user_data)
                throw new Exception("Wrong login code", 400);

            if($request->store_id !== '' && $request->store_id !== null){
                $store_status = UserStoreModel::where(['user_id'=>$user_data->id,'store_id'=> $request->store_id])->first();
                if(!$store_status){
                    throw new Exception("Store Not Found", 401);   
                }
            }

            $merchant_id = 0;
            $merchant_slack = 0;
            $merchant_email = UserModel::find(2);

            if (isset($merchant_email)) {
                $merchant_email = $merchant_email->email;
                $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
                $merchant_id = mysqli_query($connect, 'SELECT id,slack FROM merchants WHERE email = "' . $merchant_email . '" ');
                if (mysqli_num_rows($merchant_id) > 0) {
                    $merchant_result = mysqli_fetch_assoc($merchant_id);
                    $merchant_id = $merchant_result['id'];
                    $merchant_slack = $merchant_result['slack'];
                }
                mysqli_close($connect);
            }


            if ($user_data) {

                $check_logged = json_decode($this->check_logged_in($request,$user_data));

                if(!$check_logged->device){
                    return response()->json($this->generate_response(
                        array(
                            "message" => "You are already logged in another device, Do you want to logout from that device and login here?",
                            "data" => [
                                "device_status"    => 0,
                            ],
                        ),
                        'SUCCESS'
                    ));
                }

                // Update store id of logged in user
                if (!empty($request->store_id))
                    UserModel::roleJoin()->where(['users.login_code' => $login_code, 'roles.status' => 1])->update(['users.store_id' => $request->store_id]);

                //generate access token
                $encode_array = array(
                    "user_id" => $user_data->id,
                    "user_slack" => $user_data->slack
                );
                $access_token = $this->jwt_encode($encode_array);

                //Get the first link
                $first_link = $this->get_user_default_link($user_data);
                $user_data->initial_link = (!empty($first_link)) ? route($first_link->route) : "/";
                if ($user_data->initial_link == "/") {
                    throw new Exception("You don't have access to the system. Please contact the system administrator for assistance.", 401);
                }

                // $this->remove_expired_session($user_data->slack);
                $this->set_user_session($user_data, $access_token);
                // added later
                $request->session()->put('full_name', $user_data->fullname);
                $request->session()->put('currency_code', $user_data->currency_code);
                $request->session()->put('currency_name', $user_data->currency_name);
                $request->session()->put('store_tax_code', $user_data->tax_code_id);
                $request->session()->put('store_id', $user_data->store_id);
                $request->session()->put('store_logo', $user_data->store_logo);
                $request->session()->put('merchant_id', $merchant_id);
                $request->session()->put('merchant_slack', $merchant_slack);
                $request->session()->put('is_admin', $user_data->is_admin);
                $request->session()->put('is_master', $user_data->is_master);

                $session_id = $request->session()->getId();


                //update access token in user_access_tokens table
                $user_token_array = [
                    'access_token' => $access_token
                ];
                UserTokenModel::find($check_logged->access_token_data->id)->update($user_token_array);

                $user_detail = UserModel::where('id', $user_data->id)->first();

                $user = collect(new UserResource($user_detail));

                $user['access_token'] = $access_token;
                $default_tax_count = StoreModel::withoutGlobalScopes()->get()->count() * 4;
                $user['is_store_tax_exists'] =  (TaxcodeModel::withoutGlobalScopes()->get()->count() == $default_tax_count) ? true : false ;

                if (!empty($request->store_id)) {
                    //return last order number
                    $orderAPI = new Order();
                    $value_date = $orderAPI->get_order_value_date();
                    $order = DB::table('orders')->select('order_number')->where('store_id', '=', $request->store_id)->where('value_date', $value_date)->orderBy('id', 'DESC')->first();
                    $user['order_number'] = isset($order) ? $order->order_number : '0';

                    // return qr code link
                    $subdomain = DB::table('stores')
                        ->join('qr_codes', 'stores.id', '=', 'qr_codes.store_id')
                        ->join('users', 'users.store_id', '=', 'stores.id')
                        ->select('stores.name')
                        ->where(['users.store_id' => $request->store_id, 'stores.id' => $request->store_id])
                        ->first();

                    if (isset($subdomain) && isset($subdomain->name)) {

                        $user['wosulin_restaurant_link'] = env('WOSULIN_URL') . 'restaurant/';

                        $user['wosulin_restaurant_link'] = isset($subdomain) ? env('WOSULIN_URL') . 'restaurant/' . $this->makeAlias($subdomain->name) : '';
                    } else {
                        $user['wosulin_restaurant_link'] = "";
                    }
                    //end qr code 

                }

                $user_menu = UserMenuModel::with('menu')->where('user_id', $user_data->id)->get();

                $user['user_menu_access'] = $user_menu;

                $store = StoreModel::select('*')
                    ->where('id',  trim($request->store_id))
                    ->active()
                    ->first();
                $store_data = new StoreResource($store);
                $user['store'] = $store_data;
                $user['merchant_id'] = $merchant_id;
                $user['merchant_slack'] = $merchant_slack;

                return response()->json($this->generate_response(
                    array(
                        "message" => "Authenticated successfully",
                        "data"    => $user,
                        "link"    => $user_data->initial_link
                    ),
                    'SUCCESS'
                ));
            } else {
                throw new Exception("Invalid Login", 401);
            }
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function get_login_details(Request $request)
    {
        try {
            $access_token = $request->access_token;

            $get_access_user = UserTokenModel::where('access_token',$access_token)->first();

            if(!$get_access_user){
                throw new Exception("Already logged-In by another device.", 401); 
            }

            $user_data = UserModel::select('users.*', 'stores.currency_name', 'stores.currency_code', 'stores.tax_code_id', 'stores.store_logo')->roleJoin()
                ->storeJoin()
                ->where('users.id', function ($query) use ($access_token) {
                    $query->select('user_id')
                        ->from('user_access_tokens')
                        ->where('access_token', $access_token);
                })
                ->active()
                ->first();

            $merchant_id = 0;
            $merchant_slack = 0;
            $merchant_email = UserModel::find(2);

            if (isset($merchant_email)) {
                $merchant_email = $merchant_email->email;
                $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
                $merchant_id = mysqli_query($connect, 'SELECT id,slack FROM merchants WHERE email = "' . $merchant_email . '" ');
                if (mysqli_num_rows($merchant_id) > 0) {
                    $merchant_result = mysqli_fetch_assoc($merchant_id);
                    $merchant_id = $merchant_result['id'];
                    $merchant_slack = $merchant_result['slack'];
                }
                mysqli_close($connect);
            }

            if ($user_data) {
                //generate access token
                // $encode_array = array(
                //     "user_id" => $user_data->id,
                //     "user_slack" => $user_data->slack
                // );
                // $access_token = $this->jwt_encode($encode_array);

                //Get the first link
                $first_link = $this->get_user_default_link($user_data);
                $user_data->initial_link = (!empty($first_link)) ? route($first_link->route) : "/";
                if ($user_data->initial_link == "/") {
                    throw new Exception("You don't have access to the system. Please contact the system administrator for assistance.", 401);
                }

                $this->remove_session($access_token);
                $this->set_user_session($user_data, $access_token);
                // added later
                $request->session()->put('full_name', $user_data->fullname);
                $request->session()->put('currency_code', $user_data->currency_code);
                $request->session()->put('currency_name', $user_data->currency_name);
                $request->session()->put('store_tax_code', $user_data->tax_code_id);
                $request->session()->put('store_id', $user_data->store_id);
                $request->session()->put('store_logo', $user_data->store_logo);
                $request->session()->put('merchant_id', $merchant_id);
                $request->session()->put('merchant_slack', $merchant_slack);
                $request->session()->put('is_admin', $user_data->is_admin);
                $request->session()->put('is_master', $user_data->is_master);

                $session_id = $request->session()->getId();


                //update access token in user_access_tokens table
                $user_token_array = [
                    'user_id' => $user_data->id,
                    'access_token' => $access_token,
                    'session_id' => $session_id
                ];
                UserTokenModel::create($user_token_array);

                $user_detail = UserModel::where('id', $user_data->id)->first();

                    $user = collect(new UserResource($user_detail));

                    // $user['access_token'] = $access_token;

                    // Update store id of logged in user
                    if (!empty($user_data->store_id)) {
                        // UserModel::where(['email' => $request->email, 'status' => 1])->update(['store_id' => $request->store_id]);
                        //return last order number
                        $orderAPI = new Order();
                        $value_date = $orderAPI->get_order_value_date();
                        $order = DB::table('orders')->select('order_number')->where('store_id', '=', $user_data->store_id)->where('value_date', $value_date)->orderBy('id', 'DESC')->first();
                        $user['order_number'] = isset($order) ? $order->order_number : '0';

                        // return qr code link
                        $subdomain = DB::table('stores')
                            ->join('qr_codes', 'stores.id', '=', 'qr_codes.store_id')
                            ->join('users', 'users.store_id', '=', 'stores.id')
                            ->select('stores.name')
                            ->where(['users.store_id' => $user_data->store_id, 'stores.id' => $user_data->store_id])
                            ->first();

                        if (isset($subdomain) && isset($subdomain->name)) {

                            $user['wosulin_restaurant_link'] = env('WOSULIN_URL') . 'restaurant/';

                            $user['wosulin_restaurant_link'] = isset($subdomain) ? env('WOSULIN_URL') . 'restaurant/' . $this->makeAlias($subdomain->name) : '';
                        } else {
                            $user['wosulin_restaurant_link'] = "";
                        }
                        //end qr code 

                    }

                    $user_menu = UserMenuModel::with('menu')->where('user_id', $user_data->id)->get();

                    $user['user_menu_access'] = $user_menu;

                    $store_data = StoreModel::select('*')
                        ->where('id',  trim($user_data->store_id))
                        ->active()
                        ->first();

                    $store_data = new StoreResource($store_data);

                    $user['store'] = $store_data;
                    $user['merchant_id'] = $merchant_id;
                    $user['merchant_slack'] = $merchant_slack;
                } else {
                    throw new Exception("Invalid token.", 401);
                }
                return response()->json($this->generate_response(
                    array(
                        "message" => "Logged User Data",
                        "data"    => $user,
                        "link"    => $user_data->initial_link
                    ),
                    trans('SUCCESS')
                ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                ),
            ));
        }
    }

    public function logout(Request $request)
    {
        $user_id = session('user_id');
        
        try {
            $access_token = $request->access_token;

            $this->remove_session($access_token);
            session()->flush();

            return response()->json($this->generate_response(
                array(
                    "message" => "Logged Out Successfully"
                ),
                trans('SUCCESS')
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                ),
            ));
        }
    }

    public function rand_num($digits)
    {
        return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    }

    public function user_language(Request $request){
        if( $user = UserModel::where(['email' => $request->email, 'status' => 1])->first()){
            return $user->language_id ? $user->language_id : 3;
        }else{
            return 3;
        }
    }
}
