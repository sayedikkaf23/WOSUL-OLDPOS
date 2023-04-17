<?php

namespace App\Http\Controllers;

use App\Models\MerchantSubscription;
use Illuminate\Http\Request;

use App\Models\Merchant as MerchantModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserToken as UserTokenModel;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function index()
    {
        if (session('logged_merchant_id')>0) {
            if(session('logged_merchant_id')>0){
                return redirect()->route('checkout', ['lang' => request()->lang]);
            }else{
                return redirect()->route('profile', ['lang' => request()->lang]);
            }
        } else {

            $enable_payment_gateway =  DB::connection('mysql_admin')->table('website_settings')->where('key','ENABLE_PAYMENT_GATEWAY')->first();
            $data['free_trial_days'] =  DB::connection('mysql_admin')->table('website_settings')->where('key','FREE_TRIAL_DAYS')->first()->value;

            if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true' ){
                $data['enable_payment_gateway'] = 1;
            }else{
                $data['enable_payment_gateway'] = 0;
            }

            return view('login', compact('data'));
        }
    }

    public function authenticate(Request $request)
    {
        $merchant = MerchantModel::where('email', $request->email)->first();
        if (isset($merchant) && Hash::check($request->password, $merchant->password)) {
            Session::put('logged_merchant_id', $merchant->id);
            Session::put('logged_merchant_name', $merchant->name);
            Session::put('logged_merchant_email', $merchant->email);
            Session::put('logged_merchant_company_name', $merchant->company_name);
            Session::put('logged_merchant_company_url', $merchant->company_url);

            $merchant_subscription = DB::connection('mysql_admin')->table('merchant_subscriptions')->where('merchant_id',$merchant->id)->where('status',1)->first();

            if(isset($merchant_subscription) && $merchant_subscription->id >0){
                if ($request->has("from_payment") && $request->from_payment > 0) {
                    return redirect()->route('checkout', ['lang' => request()->lang]);
                } else {
                    return redirect()->route('profile', ['lang' => request()->lang]);
                }
            }else{
                return redirect()->route('pricing', ['lang' => request()->lang]);
            }

        } else {
            return redirect()->back()->withErrors(['msg' => 'Invalid Username or Password']);
        }
    }

    public function forgot_password()
    {
        if (session('logged_merchant_id')>0) {
            return view('profile');
        }else {
            return view('forgot_password');
        }
    }

    public function logout()
    {
        $session_id = session()->getId();
        $user_id = session('user_id');
        UserTokenModel::select('session_id')->where('user_id', $user_id)->where('session_id', $session_id)->delete();
        session()->flush();
        return redirect(request()->lang . '/login');
    }

    public function merchant_logout()
    {
        session()->flush();
        return redirect(request()->lang . '/login');
    }
}
