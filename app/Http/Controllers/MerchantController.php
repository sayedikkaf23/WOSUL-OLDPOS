<?php

namespace App\Http\Controllers;

// Models
use App\Models\OrderDevices;
use App\Models\OrderSubscriptions;
use App\Models\Subscription as SubscriptionModel;
use App\Models\Merchant as MerchantModel;
use Illuminate\Http\Request;
use App\Http\Requests\MerchantStoreRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Traits\SubdomainTrait;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\MerchantRegistered;
use App\Mail\MerchantMasterAccountCreated;
use App\Models\MerchantSubscription;
use App\Models\MerchantSoftwareVersion;
use Illuminate\Support\Str;
use App\Models\HyperPayOrders;
use App\Models\Subscription;

class MerchantController extends Controller
{
    use SubdomainTrait;

    /**
     * @var string
     */
    private $project_path;
    public $mail;

    public function index($lang)
    {
        if (session('logged_merchant_id')>0) {
            return redirect()->route('profile', ['lang' => request()->lang]);
        }else{

            $enable_payment_gateway =  DB::connection('mysql_admin')->table('website_settings')->where('key','ENABLE_PAYMENT_GATEWAY')->first();

            if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true' ){
                $data['enable_payment_gateway'] = 1;
                $data['suscription_plans'] = Subscription::Active()->get()->toArray();
            }else{
                $data['suscription_plans'] = [];
                $data['enable_payment_gateway'] = 0;
            }
            return view('register', compact('data'));
        }
    }

    public function register_without_payment($lang,$subscription_id){
        
        $data['subscription'] = SubscriptionModel::where('id', $subscription_id)->active()->withoutTrashed()->firstOrFail();
        $data['subscription_id'] = $subscription_id;
        
        $enable_payment_gateway =  DB::connection('mysql_admin')->table('website_settings')->where('key','ENABLE_PAYMENT_GATEWAY')->first();

        if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true' ){
            $data['enable_payment_gateway'] = 1;
        }else{
            $data['enable_payment_gateway'] = 0;
        }
        
        return view('register', compact('data'));
     
    }

    public function store(MerchantStoreRequest $request)
    {
        // dd($request->all());
        if($request->subscription_id>0){
            $subscription = SubscriptionModel::Active()->find($request->subscription_id);
            if(isset($subscription) && $subscription->id>0){
                if($request->is_trial==1){
                    $free_trial_days =  DB::connection('mysql_admin')->table('website_settings')->where('key','FREE_TRIAL_DAYS')->first()->value;
                    $subscription_expire_date = Carbon::now()->addDay($free_trial_days)->format('Y-m-d');
                }else{
                    if ($subscription->subscription_type == '1') {
                        // for monthly subscription
                        $subscription_expire_date = Carbon::now()->addMonth($subscription->subscription_tenure)->format('Y-m-d');
                    } else if ($subscription->subscription_type == '2') {
                        // for annual subscription
                        $subscription_expire_date = Carbon::now()->addYear($subscription->subscription_tenure)->format('Y-m-d');
                    }
                }
            }else{
                return redirect()->route('merchant_register', ['lang' => request()->lang])->with('error', trans('Selected Subscription is Inactive!'));
            }
        }else{
            return redirect()->route('merchant_register', ['lang' => request()->lang])->with('error', trans('Subscription not selected!'));
        }

        try {
            DB::beginTransaction();

            $enable_payment_gateway =  DB::connection('mysql_admin')->table('website_settings')->where('key','ENABLE_PAYMENT_GATEWAY')->first();

            $request['password'] = Str::random(8);
            if ($request->get("from_payment") != null) {
                $merchant = [
                    'slack' => generateSlack('App\Models\Merchant'),
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_name' => $request->company_name,
                    'company_url' =>  strtolower($request->company_url),
                    'db_name' => $request->company_url . "_wosul",
                    'status' => 1,
                ];
            } else {
                $merchant = [
                    'slack' => generateSlack('App\Models\Merchant'),
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_name' => $request->company_name,
                    'company_url' =>  strtolower($request->company_url),
                    'referral_code' => $request->referral_code,
                    'recommendation' => $request->recommendation!=''?$request->recommendation:'',
                    'user_type' => 1,
                    'merchant_business' => $request->merchant_business,
                    'other_merchant_business' => $request->other_merchant_business,
                    'db_name' => $request->company_url . "_wosul",
                    'status' => 1,
                ];
            }

            $merchant_id = MerchantModel::create($merchant)->id;

            Session::put('logged_merchant_id', $merchant_id);
            Session::put('logged_merchant_name', $request->name);
            Session::put('logged_merchant_email', $request->email);
            Session::put('logged_merchant_company_name', $request->company_name);
            Session::put('logged_merchant_company_url', strtolower($request->company_url));
            $status = 1;
            if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true'){
                $status = 0;
            }
            if($request->subscription_id>0) {
                $merchant_subscription = [
                    'subscription_id' => $subscription->id,
                    'merchant_id' => $merchant_id,
                    'start_date' => Carbon::now()->format('Y-m-d'),
                    'end_date' => $subscription_expire_date,
                    'status' => $status,
                    'is_trial' => $request->is_trial>0?$request->is_trial:0,
                ];
                MerchantSubscription::create($merchant_subscription);

                if (env('APP_ENV') == 'production') {

                    $this->create_database($request->company_url . "_wosul");
                    $this->config_database($request, $request->company_url);

                    Mail::to($request->email)->send(new MerchantRegistered($request));
                    Mail::to(env('WOSUL_SALES_EMAIL'))->send(new MerchantMasterAccountCreated($request));
                    Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new MerchantMasterAccountCreated($request));
                }
            }

            DB::commit();
    
            if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true' && $request->subscription_id > 0 ){
                if($request->is_trial==1){
                    return redirect()->back()->with('success', trans('Trial account has been created successfully, Thank you!'));
                }else{
                    return redirect(app()->getLocale().'/checkout')->with('success', trans('Merchant Account Has Been Created Successfully, Thank You!'));
                }
            }else{
                return redirect()->back()->with('success', trans('Merchant Account Has Been Created Successfully, Thank You!'));
            }

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }


    }

    public function destroy(Request $request)
    {
        try {
            $merchant_data = Merchantmodel::where("email", $request->email)->first();
            DB::delete("delete from merchants where id=" . $merchant_data->id);
            $dbname = "`" . $request->company_url . "_wosul`";
            $databases = DB::select("SHOW databases");
            foreach ($databases as $database) {
                if ($database->Database == $request->company_url . "_wosul") {
                    $this->drop_database("`" . $request->company_url . "_wosul`");
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function is_email_exists(Request $request)
    {

        $merchant_email = MerchantModel::where('email', $request->email)->withoutTrashed()->first();

        if (!empty($merchant_email)) {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    public function is_company_url_exists(Request $request)
    {

        $company_url = MerchantModel::where('company_url', $request->company_url)->withoutTrashed()->first();

        if (!empty($company_url)) {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    public function profile()
    {
        if (session('logged_merchant_id')>0) {
            return view('profile');
        }else{
            return redirect()->route('login', app()->getLocale());
        }
    }

    public function update_installation(Request $request)
    {
        try {

            $merchant_software_version =  MerchantSoftwareVersion::where('os', $request->os)->where(
                'unique_deviceid',
                $request->unique_deviceid
            )->first();

            if (isset($merchant_software_version)) {
                MerchantSoftwareVersion::where('os', $request->os)->where(
                    'unique_deviceid',
                    $request->unique_deviceid
                )->update($request->all());

                $message =  'Software Version Has Been Updated';
            } else {
                MerchantSoftwareVersion::create($request->all());
                $message =  'Software Version Has Been Added';
            }

            return response()->json($this->generate_response(
                array(
                    "message" => $message,
                    "data"    => []
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

    public function my_orders(){
        if (session('logged_merchant_id')>0) {
            $data['orders'] =HyperPayOrders::select('orders.*','payment_methods.payment_constant')
                ->where('merchant_id',session('logged_merchant_id'))
                ->paymentTypeJoin()
                ->orderBy('id','DESC')
                ->paginate(12);
            return view('my_orders', compact('data'));
        }else{
            return redirect()->route('login', app()->getLocale());
        }
    }
    public function order_detail($lang,$id){
        $data['order'] = HyperPayOrders::select('orders.*','payment_methods.payment_constant')
            ->where('merchant_id',session('logged_merchant_id'))
            ->where('orders.id',$id)
            ->paymentTypeJoin()
            ->first();
        if(isset($data['order']) && isset($data['order']->id)){
            $data['subscription'] = OrderSubscriptions::where('order_id',$data['order']->id)->first();
            $data['devices'] = OrderDevices::where('order_id',$data['order']->id)->get();
            return view('my_order_detail', compact('data'));
        }else{
            return redirect()->route('my_orders', app()->getLocale());
        }
    }

    public function get_subscription_detail(Request $request)
    {
        $sunscription_detail = Subscription::where('id', $request->subscription_id)->withoutTrashed()->first()->toArray();
        return response()->json($this->generate_response(
            array(
                "message" => 'Subscription detail',
                "data"    => $sunscription_detail
            ),
            'SUCCESS'
        ));
    }
}
