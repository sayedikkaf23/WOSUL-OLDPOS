<?php

namespace App\Http\Controllers;

use App\Models\WhatsappVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RegistrationForm;
use Illuminate\Support\Carbon;
use Exception;

class ContactController extends Controller
{
    public function index(){
        return view('contact');
    }
    
    public function inquiry(){
        return view('inquiry');
    }

    public function track_whatsapp_page_visits(Request $request){

        $data = [];
        $data['ip'] = $request->ip();
        $ip_location = \Location::get($data['ip']);
        $data['tracking_data'] = ($ip_location != false) ? json_encode($ip_location) : null;

        if($data['ip'] != ''){
            WhatsappVisit::firstOrCreate([
                'ip' => $data['ip'],
                'tracking_data' => $data['tracking_data'],
            ]);
            $status = true;
        }else{
            $status = false;
        }
        
        return response()->json(['status'=>$status,'data'=>$data]);

    }
}
