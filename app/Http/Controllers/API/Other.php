<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Collections\MerchantSupportTicketCollection;
use App\Http\Resources\MerchantSupportTicketResource;
use App\Models\MerchantSupportTicket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\MerchantSupportTicketCreated;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BillingCounter;
use App\Models\Category;
use App\Models\Combo;
use App\Models\ComboProduct;
use App\Models\Discountcode;
use App\Models\Measurement;
use App\Models\MeasurementConversion;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Price;
use App\Models\Product;
use App\Models\Table;

class Other extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_updated_api_list(Request $request)
    {   
        
        try{

            $updated_at = $request->updated_at;
    
            $updated_apis = [];

            // billing counters
            $updated_count = BillingCounter::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/billing_counter_list';
            }
            
            // payment methods
            $updated_count = PaymentMethod::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/payment_method_list';
            }
            
            // accounts
            $updated_count = Account::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/account_list';
            }
            
            // products
            $updated_count = Product::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/product_list_by_modified_date';
            }
            
            // multiple prices 
            $updated_count = Price::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/list_prices';
            }
            
            // discount codes 
            $updated_count = Discountcode::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/discount_code_list';
            }
            
            // combos 
            $updated_count = ComboProduct::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
            $updated_apis[] = 'api/combo_list';
            }
            
            // tables 
            $updated_count = Table::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/table_list';
            }
            
            // categories
            $updated_count = Category::categoryStore()->where('updated_at','>=',$request->updated_at)->active()->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/get_active_category';
            }

            // measurements with conversations
            $updated_count = Measurement::where('updated_at','>=',$request->updated_at)->count();
            $updated_count2 = MeasurementConversion::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 || $updated_count2 > 0 ){
                $updated_apis[] = 'api/measurement_with_conversions';
            }

            // order list by date 
            $updated_count = Order::where('updated_at','>=',$request->updated_at)->count();
            if($updated_count > 0 ){
                $updated_apis[] = 'api/order_list_by_date';
            }
            
            return response()->json($this->generate_response(
                array(
                    "message" => trans("Updated API list returned successfully"),
                    "data"    => $updated_apis
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


    /*Merchant Support Tickets*/
    public function merchant_support_tickets(Request $request){

        try {
            $list = new MerchantSupportTicketCollection(MerchantSupportTicket::select('*')->where('merchant_id',$request->merchant_id)
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Tickets loaded successfully",
                    "data"    => $list
                ), 'SUCCESS'
            ));

        }catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function merchant_support_tickets_detail(Request $request, $id){
        try {
            if ($id<1) {
                throw new Exception("Please Provide Ticket Id", 400);
            }
            $ticket_data = DB::connection('mysql_admin')->table('merchant_support_tickets')->where('id',$id)->orderBy('id','DESC')->first();

            $attachments = DB::connection('mysql_admin')->table('merchant_sup_ticket_images')->where('ticket_id',$id)->get()->toArray();

            $attachment_data = [];
            foreach ($attachments as $attachment){
                $attachment_data[]['attachment'] = "https://".Storage::disk('crm_ticket')->url($attachment->attachment);
            }

            $ticket_data->attachments = $attachment_data;

            return response()->json($this->generate_response(
                array(
                    "message" => "Success",
                    "data" => [$ticket_data]
                ),
                'SUCCESS'
            ));

        }catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function add_merchant_support_tickets(Request $request){
        try {
            $this->validate_request($request);

            DB::beginTransaction();
            $support_ticket = [
                'title' => $request->title,
                'description' => $request->description,
                'merchant_id' => $request->merchant_id,
                'merchant_company_name' => $request->merchant_company_name,
                'ticket_type' => $request->ticket_type,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'priority' => $request->priority,
                'reporting_date' => $request->reporting_date,
                'created_by' => $request->logged_user_id,
            ];
            $ticket =  DB::connection('mysql_admin')->insert('INSERT INTO merchant_support_tickets (title, description, merchant_id, merchant_company_name, ticket_type,user_name,email,priority,reporting_date,created_by,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)', [$request->title,$request->description,$request->merchant_id,$request->merchant_company_name,$request->ticket_type,$request->user_name,$request->email,$request->priority,$request->reporting_date,$request->logged_user_id,now(),now()]);

            $ticket_data = DB::connection('mysql_admin')->table('merchant_support_tickets')->where('created_by',$request->logged_user_id)->where('merchant_id',$request->merchant_id)->orderBy('id','DESC')->first();
            $ticket_data->attachments = [];
            $files = $request->file('attachments');
            if(!empty($files)){
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $file_name = $ticket_data->id. '_' . uniqid() . '.' . $extension;
                    $path = Storage::disk('crm_ticket')->putFileAs('/', $file, $file_name);
                    $file_name = basename($path);

                    $ticket =  DB::connection('mysql_admin')->insert('INSERT INTO merchant_sup_ticket_images (ticket_id, attachment,created_at,updated_at) VALUES (?,?,?,?)', [$ticket_data->id,$file_name,now(),now()]);
                }

                $attachments = DB::connection('mysql_admin')->table('merchant_sup_ticket_images')->where('ticket_id',$ticket_data->id)->get()->toArray();

                $attachment_data = [];
                foreach ($attachments as $attachment){
                    $attachment_data[]['attachment'] = "https://".Storage::disk('crm_ticket')->url($attachment->attachment);
                }

                $ticket_data->attachments = $attachment_data;
            }

            if (env('APP_ENV') == 'production') {
                Mail::to(env('WOSUL_SUPPORT_EMAIL'))->send(new MerchantSupportTicketCreated($support_ticket));
                if (isset($request->email) && $request->email != '') {
                    Mail::to($request->email)->send(new MerchantSupportTicketCreated($support_ticket));
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Ticket has been created successfully! Our team will review and contact you soon!",
                    "data" => [$ticket_data]
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
            'title' => $this->get_validation_rules("name_label", true),
            'description' => $this->get_validation_rules("name_label", true),
            'ticket_type' => $this->get_validation_rules("name_label", true),
            'priority' => $this->get_validation_rules("name_label", true),
            'reporting_date' => $this->get_validation_rules("name_label", true),
        ]);
        $validation_status = $validator->fails();
        if($validation_status){
            throw new Exception($validator->errors());
        }
    }
}
