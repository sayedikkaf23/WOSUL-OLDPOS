<?php

namespace App\Http\Controllers;

use Session;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Discountcode as DiscountcodeModel;
use Exception;
use Illuminate\Support\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // jwt encode class
    public function jwt_encode($encode_data)
    {
        $payload = [
            'iss' => "jwt_token", // Issuer of the token
            'sub' => $encode_data, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => strtotime('+'.env('SESSION_LIFETIME').' minutes', time()) // Expiration time in sec, 1 year
        ];

        // As you can see we are passing `JWT_KEY` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_KEY', config('app.jwt_key')));
    }

    // decode jwt token
    public function jwt_decode($token = "")
    {
        return JWT::decode($token, env('JWT_KEY', config('app.jwt_key')), ['HS256']);
    }

    /**
     * Function that will set user session
    */
    public function set_user_session($user, $token)
    {
        session()->put('fullname', $user->fullname);
        session()->put('firstname', $user->firstname);
        session()->put('profile_image', $user->profile_image);
        session()->put('slack', $user->slack);
        session()->put('user_id', $user->id);
        session()->put('role', $user->role_id);    
        session()->put('initial_link', $user->initial_link);     
        session()->put('access_token', $token);
        //Session::save();
    }

    public function check_user_session($user, $token)
    {
        $user_slack = session('slack');
        if($user_slack != ""){
            return true;
        }else{
            return false;
        }
    }

    function generate_slack($table)
    {
        do{
            $slack = str_random(10);
            $exist = DB::table($table)->where("slack", $slack)->first();
        }while($exist);
        return $slack;
    }
    
    function generate_barcode_string($table)
    {
        do{
            $barcode = rand(1111111111,9999999999);;
            $exist = DB::table($table)->where("barcode", $barcode)->first();
        }while($exist);
        return $barcode;
    }

    function generate_response($response_array, $type = "")
    {
        switch($type){
            case "SUCCESS":
                $status_code = 200;
            break;
            case "NOT_AUTHORIZED":
                $status_code = 401;
            break;
            case "NO_ACCESS":
                $status_code = 403;
            break;
            case "BAD_REQUEST":
                $status_code = 400;
            break;
            default:
                $status_code = 200;
            break;
        }
        
        $response = array(
            'status' => true,
            'msg'    => (isset($response_array['message']))?$response_array['message']:"",
            'data'   => (isset($response_array['data']))?$response_array['data']:"",
            'status_code' => (isset($response_array['status_code']))?$response_array['status_code']:$status_code
        );
        if(isset($response_array['link'])){
            $response = array_merge($response, array("link" => $response_array['link']));
        }
        if(isset($response_array['order_item'])){
            $response = array_merge($response, array("product" => $response_array['order_item']));
        }
        if(isset($response_array['modifiers'])){
            $response = array_merge($response, array("modifiers" => $response_array['modifiers']));
        }
        if(isset($response_array['order'])){
            $response = array_merge($response, array("order" => $response_array['order']));
        }
        if(isset($response_array['products'])){
            $response = array_merge($response, array("products" => $response_array['products']));
        }
        if(isset($response_array['order_product'])){
            $response = array_merge($response, array("order_product" => $response_array['order_product']));
        }
        if(isset($response_array['pdf_link'])){
            $response = array_merge($response, array("pdf_link" => $response_array['pdf_link']));
        }
        if(isset($response_array['new_tab'])){
            $response = array_merge($response, array("new_tab" => $response_array['new_tab']));
        }
        if(isset($response_array['order'])){
            $response = array_merge($response, array("order" => $response_array['order']));
        }
        if(isset($response_array['type'])){
            $response = array_merge($response, array("type" => $response_array['type']));
        } 
        if(isset($response_array['qr_code'])){
            $response = array_merge($response, array("qr_code" => $response_array['qr_code']));
        }        
        if(isset($response_array['barcode'])){
            $response = array_merge($response, array("barcode" => $response_array['barcode']));
        }        
        if(isset($response_array['device_slack'])){
            $response = array_merge($response, array("device_slack" => $response_array['device_slack']));
        }        
        if(isset($response_array['barcode_data'])){
            $response = array_merge($response, array("barcode_data" => $response_array['barcode_data']));
        }        
        if(isset($response_array['products_update_count'])){
            $response = array_merge($response, array("products_update_count" => $response_array['products_update_count']));
        }        
        if(isset($response_array['transaction_data'])){
            $response = array_merge($response, array("transaction_data" => $response_array['transaction_data']));
        }        
        return $response;
    }

    public function no_access_response_for_listing_table(){
        $response = [
            'draw' => 0,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'access' => false
        ];
        return response()->json($response);
    }

    public function get_validation_rules($field, $required = false)
    {
        $rule = "";
        switch($field){
            case 'email' : $rule = "email|max:150|"; break;
            case 'password' : $rule = "alpha_dash|min:6|max:100|"; break;
            case 'fullname' : $rule = "alpha_spaces|max:100|"; break;
            case 'phone' : $rule = "regex:/^[0-9-+()]*$/i|max:15|"; break;
            case 'new_password' : $rule = "alpha_dash|min:6|max:100|confirmed|"; break;
            case 'status' : $rule = "numeric|"; break;
            case 'name_label' : $rule = "nullable|max:250|"; break;
            case 'role_menus' : $rule = 'string|'; break;
            case 'pincode' : $rule = "alpha_num|max:15|"; break;
            case 'text' : $rule = "max:65535|"; break;
            case 'string' : $rule = 'string|'; break;
            case 'numeric' : $rule = "numeric|"; break;
            case 'slack' : $rule = "alpha_num|"; break;
            case 'order_status' : $rule = "in:CLOSE,HOLD,IN_KITCHEN|"; break;
            case 'codes' : $rule = "alpha_dash|"; break;
            case 'filled' : $rule = "filled|"; break;
            case 'product_image' : $rule = "mimes:jpeg,jpg,png,webp|max:1500"; break;
            case 'company_logo' : $rule = "mimes:jpeg,jpg,png|max:150|"; break;
            case 'invoice_print_logo' : $rule = "mimes:jpeg,jpg,png|max:150|dimensions:width=200,height=100|"; break;
            case 'navbar_logo' : $rule = "mimes:jpeg,jpg,png|max:50|dimensions:width=30,height=30|"; break;
            case 'favicon' : $rule = "mimes:jpeg,jpg,png|max:10|dimensions:width=30,height=30|"; break;
        }

        if($required == true){
            $rule = implode('|', array('required', $rule));
        }else{
            $rule = implode('|', array('nullable', 'sometimes', $rule));
        }
        return $rule;
    }

    public function checkDiscount($request)
    {
        $cart =  json_decode($request->cart);
        $currentdate = date("Y-m-d H:i:sa");
        $request->cashier_discount_amount_ids = json_decode($request->cashier_discount_amount_ids);
        $request->cashier_discount_percentage_ids = json_decode($request->cashier_discount_percentage_ids);
        if (!empty($cart)) {
            foreach ($cart as $cart_item_key => $cart_item) {
                if (isset($cart_item->discount_code_id) && (int)$cart_item->discount_code_id>0) {
                    if(count(DiscountcodeModel::select('*')
                    ->where('id', '=', trim($cart_item->discount_code_id))->get())>0)
                    {
                        $discountcodelist = DiscountcodeModel::select('*')
                                        ->where('id', '=', trim($cart_item->discount_code_id))
                                        ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                        ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                                        ->active()
                                        ->first();
                        if (empty($discountcodelist)) {
                          DB::update("update discount_codes set status=0 where id=".$cart_item->discount_code_id);
                          DB::commit();
                          $discount_details = DB::select("select * from discount_codes where id=".$cart_item->discount_code_id);
                          if(isset($discount_details[0]->label))
                          {
                             $label = $discount_details[0]->label;
                          }
                          else
                          {
                             $label = "";
                          }
                          throw new Exception("Product Discount '{$label}' has been expired or is not found in the system");
                       }
                    }
                }
            }
        }
        if ($request->discount_code_id != "") {
            if(count(DiscountcodeModel::select('*')
            ->where('id', '=', trim($request->discount_code_id))->get())>0)
            {
               $discountcodelist = DiscountcodeModel::select('*')
                                ->where("store_id",$request->session()->get("store_id"))
                                ->where("id", trim($request->discount_code_id))
                                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                                ->active()
                                ->first();
                if (empty($discountcodelist)) {
                    DB::update("update discount_codes set status=0 where id=".$request->discount_code_id);
                    DB::commit();
                    throw new Exception("Discount code '{$request->discount_code_name}' has been expired or is not found in the system");
                }
            }
        }
        if (!is_null($request->cashier_discount_amount_ids) && count($request->cashier_discount_amount_ids) > 0) {
            foreach ($request->cashier_discount_amount_ids as $cashier_discount_amount) {
                if(count(DiscountcodeModel::select('*')
            ->where('id', '=', trim($cashier_discount_amount))->get())>0)
            {
                $discountcodelist = DiscountcodeModel::select('*')
                                    ->where("id",$cashier_discount_amount)
                                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                                    ->active()
                                    ->first();
                    if (empty($discountcodelist)) {
                        DB::update("update discount_codes set status=0 where id=".$cashier_discount_amount);
                        DB::commit();
                        $discount_details = DB::select("select * from discount_codes where id=".$cashier_discount_amount);
                        if(isset($discount_details[0]->label))
                        {
                            $label = $discount_details[0]->label;
                        }
                        else
                        {
                            $label = "";
                        }
                        throw new Exception("Cashier Discount '{$label}'  has been expired or is not found in the system");
                    }
            }
        }
    }
        if (!is_null($request->cashier_discount_percentage_ids) && count($request->cashier_discount_percentage_ids) > 0) {
            foreach ($request->cashier_discount_percentage_ids as $cashier_discount_percentage) {
                if(count(DiscountcodeModel::select('*')
                ->where('id', '=', trim($cashier_discount_percentage))->get())>0)
                {
                  $discountcodelist = DiscountcodeModel::select('*')
                                    ->where("id",$cashier_discount_percentage)
                                    ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                                    ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                                    ->active()
                                    ->first();
                  if (empty($discountcodelist)) {
                    DB::update("update discount_codes set status=0 where id=".$cashier_discount_percentage);
                    DB::commit();

                    $discount_details = DB::select("select * from discount_codes where id=".$cashier_discount_percentage);
                    if(isset($discount_details[0]->label))
                        {
                            $label = $discount_details[0]->label;
                        }
                        else
                        {
                            $label = "";
                        }
                   
                    throw new Exception("Cashier Discount '{$label}'  has been expired or is not found in the system");
                 }
                }
            }
        }
    }
    function generate_reference_no($store_id)
    {
        $last_invoice = DB::table('invoices')->select('invoice_reference')->where("store_id", $store_id)->orderBy('id','DESC')->first();
        if(isset($last_invoice) && $last_invoice->invoice_reference){
            if(is_numeric($last_invoice->invoice_reference)){
                return $last_invoice->invoice_reference + 1;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }
    
}
