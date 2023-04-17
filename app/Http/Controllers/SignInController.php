<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use App\Models\Merchant as MerchantModel;
use Illuminate\Support\Facades\Hash;

class SignInController extends Controller
{   

    public function index(Request $request)
    {   
        $data = [];
        return view('signin', compact('data'));
    }

    public function signin(Request $request)
    {   
        $merchant = MerchantModel::where('email', $request->email)->first();
     
        if (isset($merchant) && Hash::check($request->password, $merchant->password)) {

            Session::put('logged_merchant_id', $merchant->id);

            return redirect()->route('after_signin');
    
        } else {
    
            return redirect()->back()->withErrors(['msg' => 'Invalid Username or Password']);
    
        }
    
    }

    public function after_signin(){
        
        dd(session('logged_merchant_id'));

    }

   
}
