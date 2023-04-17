<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\BillingCounter as BillingCounterModel;

use App\Http\Resources\BusinessRegisterResource;

class BusinessRegister extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_ACCOUNT';
        $data['sub_menu_key'] = 'SM_BUSINESS_REGISTERS';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        return view('business_register.business_registers', $data);
    }

    //This is the function that loads the add/edit page
    public function add_business_register(){
        //check access
        $data['menu_key'] = 'MM_ACCOUNT';
        $data['sub_menu_key'] = 'SM_BUSINESS_REGISTERS';
        $data['action_key'] = 'A_ADD_ORDER';
        check_access(array($data['action_key']));

        $data['billing_counters'] = $this->get_free_billing_counters();

        return view('business_register.add_business_register', $data);
    }

    //This is the function that loads the detail page
    public function detail($slack){
        $data['menu_key'] = 'MM_ACCOUNT';
        $data['sub_menu_key'] = 'SM_BUSINESS_REGISTERS';
        $data['action_key'] = 'A_DETAIL_BUSINESS_REGISTER';
        check_access([$data['action_key']]);

        $business_register = BusinessRegisterModel::where('slack', '=', $slack)->first();
        
        if (empty($business_register)) {
            abort(404);
        }

        $business_register_data = new BusinessRegisterResource($business_register);
        
        $data['business_register_data'] = $business_register_data;

        $data['delete_register_access'] = check_access(['A_DELETE_BUSINESS_REGISTER'] ,true);

        return view('business_register.business_register_detail', $data);
    }

    public function get_free_billing_counters(){
        $available_counters = [];

        $billing_counters = BillingCounterModel::select('id', 'billing_counters.slack', 'billing_counter_code', 'counter_name')
        ->active()
        ->get();

        foreach($billing_counters as $billing_counter){
            
            $counter_occupied = BusinessRegisterModel::select('id')
            ->where('billing_counter_id', '=', $billing_counter->id)
            ->whereNull('closing_date')
            ->get()->count();

            if ($counter_occupied == 0) {
                $available_counters[] = $billing_counter;
            }
        }

        return $available_counters;
    }
}
