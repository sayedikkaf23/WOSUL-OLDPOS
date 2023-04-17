<?php

namespace App\Http\Controllers;

use App\Models\Subscription as SubscriptionModel;
use App\Models\Device as DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $merchant_subscription_details = [];
        if($request->session()->has("logged_merchant_id"))
        {
          $merchant_subscription_details = DB::select("select * from merchant_subscriptions where merchant_id=".$request->session()->get("logged_merchant_id")." order by id desc");
        }
        if(isset($merchant_subscription_details[0]->end_date))
        {
            $data['subscription_start_date'] = (new \DateTime($merchant_subscription_details[0]->end_date))->add(new \DateInterval("P1D"))->format("Y-m-d");
        }
        else
        {
            $data['subscription_start_date'] =  date('Y-m-d');
        }
        $data['subscriptions'] = SubscriptionModel::with('features')->withoutTrashed()->active()->get();
        $data['devices'] = DeviceModel::withoutTrashed()->active()->get();

        $enable_payment_gateway =  DB::connection('mysql_admin')->table('website_settings')->where('key','ENABLE_PAYMENT_GATEWAY')->first();

        if(isset($enable_payment_gateway) && $enable_payment_gateway->value == 'true' ){
            $data['enable_payment_gateway'] = 1;
        }else{
            $data['enable_payment_gateway'] = 0;
        }

        return view('pricing', compact('data'));
    }
}