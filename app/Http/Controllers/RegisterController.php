<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Merchant as MerchantModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\MerchantStoreRequest;

class RegisterController extends Controller
{
    public function index()
    {
        if (session('logged_merchant_id')) {
            return redirect()->route('profile', ['lang' => request()->lang]);
        } else {
            return view('register');
        }
    }

    public function store(Request $request)
    {
        dd($request);

        // $merchant = MerchantModel::where('email', $request->email)->first();

        // if (isset($merchant) && Hash::check($request->password, $merchant->password)) {

        //     Session::put('logged_merchant_id', $merchant->id);
        //     Session::put('logged_merchant_name', $merchant->name);
        //     Session::put('logged_merchant_email', $merchant->email);
        //     Session::put('logged_merchant_company_name', $merchant->company_name);
        //     Session::put('logged_merchant_company_url', $merchant->company_url);

        //     return redirect()->route('profile', ['lang' => request()->lang]);
        // } else {
        //     return redirect()->back()->withErrors(['msg' => 'Invalid Username or Password']);
        // }


        try {
            DB::beginTransaction();

            $request['password'] = Str::random(8);
            if ($request->get("from_payment") != null) {
                $merchant = [
                    'slack' => generateSlack('App\Models\Merchant'),
                    'subscription_id' => 1,
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_name' => $request->company_name,
                    'company_url' =>  strtolower($request->company_url),
                    'db_name' => $request->company_url . "_wosul",
                    'status' => 1,
                    'is_central' => 1
                ];
            } else {
                $merchant = [
                    'slack' => generateSlack('App\Models\Merchant'),
                    'subscription_id' => $request->subscription_id,
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_name' => $request->company_name,
                    'company_url' =>  strtolower($request->company_url),
                    'promo_code' => $request->promo_code,
                    'recommendation' => $request->recommendation,
                    'db_name' => $request->company_url . "_wosul",
                    'status' => 1,
                    'is_central' => 1
                ];
            }
            $merchant_id = MerchantModel::create($merchant)->id;
            if ($request->get('from_payment') != null) {
                $subscription_activation = [
                    'subscription_id' => 1,
                    'merchant_id' => $merchant_id,
                    'start_date' => date('Y-m-d'),
                    'end_date' => (new \DateTime(date('Y-m-d')))->add(new \DateInterval("P14D"))->format('Y-m-d'),
                    'activation_code' => Str::random(20),
                    'status' => 1
                ];
                SubscriptionActivationModel::create($subscription_activation);
            }
            $this->create_database("`" . $request->company_url . "_wosul`");

            $this->config_database($request, $request->company_url);

            if (env('APP_ENV') == 'production') {
                Mail::to($request->email)->send(new MerchantRegistered($request));
                Mail::to(env('WOSUL_SALES_EMAIL'))->send(new MerchantMasterAccountCreated($request));
                Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new MerchantMasterAccountCreated($request));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        if ($request->get("from_payment") == null) {
            session()->flash('success', 'Merchant Account Has Been Created Successfully, Please Check Your Mail For Login Details. Thank You');
            return redirect()->back();
        } else {
            return ["status" => 200];
        }
    }
}
