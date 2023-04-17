<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;
use App\Models\Role as RoleModel;
use App\Models\SmsTemplate as SmsTemplateModel;
use App\Models\Order as OrderModel;
use App\Models\SettingSms as SettingSmsModel;
use App\Models\SmsSetting as SmsSettingModel;


use App\Http\Resources\NotificationResource;
use App\Http\Resources\Collections\NotificationCollection;
use App\Http\Resources\OrderResource;

use Twilio\Rest\Client;

class Notification extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_NOTIFICATION_LISTING';
            if(check_access(array($data['action_key']), true) == false){
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
            
            $query = NotificationModel::select('notifications.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
            ->take($limit)
            ->skip($offset)
            ->notifiedUser()
            ->statusJoin()
            ->createdUser()
            ->chooseUser($request->logged_user_id)

            ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                $query->orderBy($order_by_column, $order_direction);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                $query->where(function ($query) use ($filter_string, $filter_columns){
                    foreach($filter_columns as $filter_column){
                        $query->orWhere($filter_column, 'like', '%'.$filter_string.'%');
                    }
                });
            })

            ->get();

            $notifications = NotificationResource::collection($query);
           
            $total_count = NotificationModel::select("id")->chooseUser($request->logged_user_id)->get()->count();

            $item_array = [];
            foreach($notifications as $key => $notification){
                
                $notification = $notification->toArray($request);

                $receipt_indicator = '<i class="fas fa-arrow-up mr-1"></i>';
                if($notification['user']['slack'] == $request->logged_user_slack ){
                    $receipt_indicator = '<i class="fas fa-arrow-down mr-1"></i>';
                }

                $item_array[$key][] = Str::limit($notification['notification_text'], 50);
                $item_array[$key][] = $receipt_indicator.((isset($notification['user']['fullname']))?$notification['user']['fullname'] ." (".$notification['user']['user_code'].")":'');
                $item_array[$key][] = (isset($notification['status']['label']))?view('common.status', ['status_data' => ['label' => $notification['status']['label'], "color" => $notification['status']['color']]])->render():'-';
                $item_array[$key][] = $notification['created_at_label'];
                $item_array[$key][] = $notification['updated_at_label'];
                $item_array[$key][] = (isset($notification['created_by']['fullname']))?$notification['created_by']['fullname']:'-';
                $item_array[$key][] = view('notification.layouts.notification_actions', ['notification' => $notification])->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];
            
            return response()->json($response);
        }catch(Exception $e){
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if(!check_access(['A_ADD_NOTIFICATION'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $users = json_decode($request->users, true);

            DB::beginTransaction();

            if($request->role == '' && count($users) == 0){
                throw new Exception(trans("Please choose either role or user"), 400);
            }

            if(count($users) == 0){
                $role_data = RoleModel::select('id')->where('slack', '=', $request->role)->resolveSuperAdminRole()->active()->first();
                if (!$role_data) {
                    throw new Exception(trans("Invalid role selected"), 400);
                }
                
                $users = UserModel::where('role_id', $role_data->id)->active()->get();
            }
            
            foreach($users as $user){

                $user_data = UserModel::where('slack', $user['slack'])->first();

                $notification = [
                    "slack" => $this->generate_slack("notifications"),
                    "user_id" => $user_data->id,
                    "notification_text" => $request->notification_text,
                    "created_by" => $request->logged_user_id
                ];
                
                $notification_id = NotificationModel::create($notification)->id;
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Notification created successfully"), 
                    "data"    => $notification['slack']
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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

            if(!check_access(['A_DETAIL_NOTIFICATION'], true)){
                throw new Exception("Invalid request", 400);
            }

            $item = NotificationModel::select('*')
            ->where('slack', $slack)
            ->first();

            $item_data = new NotificationResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Stock Transfer loaded successfully"), 
                    "data"    => $item_data
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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

            if(!check_access(['A_VIEW_NOTIFICATION_LISTING'], true)){
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new NotificationCollection(NotificationModel::select('*')
            ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Notification loaded successfully"), 
                    "data"    => $list
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slack)
    {
        try{

            if(!check_access(['A_DELETE_NOTIFICATION'], true)){
                throw new Exception("Invalid request", 400);
            }

            $notification_detail = NotificationModel::select('id')->where('slack', $slack)->first();
            if (empty($notification_detail)) {
                throw new Exception(trans("Invalid Notification selected"), 400);
            }
            $notification_id = $notification_detail->id;

            DB::beginTransaction();

            NotificationModel::where('id', $notification_id)->delete();

            DB::commit();

            $forward_link = route('notifications');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Notification deleted successfully"), 
                    "data" => $slack,
                    "link" => $forward_link
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function load_notification(Request $request)
    {
        try{

            DB::beginTransaction();

            $page = $request->page;

            $notifications =  new NotificationCollection(NotificationModel::select('*')->where('user_id', $request->logged_user_id)
            ->orderBy('created_at', 'desc')->paginate(10));

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Notifications loaded successfully"), 
                    "data" => $notifications,
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
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
            'notification_text' => $this->get_validation_rules("text", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }

    public function send_sms($template_key, $operation_id){
        try {
            $message = '';
            $recipient_number = '';

            switch($template_key){
                case 'POS_SALE_BILL_MESSAGE':
                    
                    $sms_template_data = SmsTemplateModel::select('message')
                    ->where([
                        ['template_key', '=', $template_key]
                    ])
                    ->active()
                    ->first();

                    if(!empty($sms_template_data)){
                        $order = OrderModel::where('slack', $operation_id)->first();
                        $order_data = json_decode(json_encode(new OrderResource($order), true));

                        $recipient_number = $order_data->customer_phone;

                        $order_variables = [
                            '{order_number}'    => $order_data->order_number,
                            '{order_amount}'    => $order_data->total_order_amount,
                            '{currency_code}'   => $order_data->currency_code,
                            '{payment_method}'  => $order_data->payment_method,
                            '{customer_name}'   => $order_data->customer->name,
                            '{customer_email}'  => $order_data->customer_email,
                            '{customer_phone}'  => $order_data->customer_phone,
                            '{order_date}'      => $order_data->created_at_label,
                            '{public_order_link}' => route('order_receipt', ['slack' => $order_data->slack]),
                            '{pos_order_receipt_url}' => route('order_receipt', ['slack' => $order_data->slack]),
                        ];

                        $message = strtr($sms_template_data['message'], $order_variables);
                    }

                break;
            }

            if($message != '' && $recipient_number != '' && $recipient_number != '0000000000'){
                $sms_setting_data = SmsSettingModel::select('*')
                ->active()
                ->first();

                if(!empty($sms_setting_data)){
                    
                    $api_key = $sms_setting_data->api_key;
                    $user_name = $sms_setting_data->user_name;
                    $sender_name = $sms_setting_data->sender_name;

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, TRUE);

                    $fields = json_encode(['userName' => $user_name, 'numbers' => $recipient_number,'userSender' => $sender_name,'apiKey' => $api_key,'msg' => $message]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                      "Content-Type: application/json",));

                    $response = curl_exec($ch);

                    Log::info($template_key .'-'. $operation_id);
                    Log::info($response);
                }
            }else{
                if($message == ''){
                    throw new Exception(trans("SMS not sent : Template is inactive!"), 400);
                }
                if($recipient_number == ''){
                    throw new Exception(trans("SMS not sent : Recipient number is not available!"), 400);
                }
                if($recipient_number == '0000000000'){
                    throw new Exception(trans("SMS not sent : Can't send SMS to walkin customer!"), 400);
                }
                throw new Exception(trans("SMS not sent"), 400);
            }

            return response()->json($this->generate_response(
                array(
                    "message" => trans("SMS sent successfully"),
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            Log::info($template_key .'-'. $operation_id);
            Log::info($e->getMessage());
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
}
