<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PleskX\Api\Client;

/* Models */
use App\Models\Merchant as MerchantModel;

/* Resources */
use App\Http\Resources\MerchantResource;

class Merchant extends Controller
{
    private $host;
    private $login;
    private $password;
    private $parentdomain;

    public function __construct(){

        // PLesk API Access
        $this->host = "165.227.88.181";
        $this->login = "admin";
        $this->password = "Wosul@789456123Riyadh";
        $this->parentdomain = "wosul.sa";

    }

    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_MERCHANT';
        $data['sub_menu_key'] = 'SM_MERCHANT';
        // check_access(array($data['menu_key'],$data['sub_menu_key']));

        $merchants = MerchantModel::select("*")->get();

        $total_branches_count = 0;

        foreach($merchants as $key => $merchant){
                
            $merchant_id = $merchant->id;

            $db_name = $merchant['company_url'].'_wosul';

            $db_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($db_query, [$db_name]);
            $no_of_branches = 0;
            if (!empty($db)) {

                $connect = mysqli_connect('localhost',config('database.connections.mysql.username'),config('database.connections.mysql.password'), $db_name);
                $no_of_branches = mysqli_query($connect,"SELECT COUNT(*) AS store_count FROM stores");
                if(!$no_of_branches || mysqli_num_rows($no_of_branches) == 0){
                    $no_of_branches = 0;
                }
                else{
                    $no_of_branches = mysqli_fetch_assoc($no_of_branches);
                    $total_branches_count += $no_of_branches['store_count'];
                }
                mysqli_close($connect);
            }

        }

        $data['total_branches_count'] = $total_branches_count;

        return view('merchant.merchants', $data);
    }

    //This is the function that loads the add/edit page
    public function add_merchant($slack = null){

        //check access
        $data['menu_key'] = 'MM_MERCHANT';
        $data['sub_menu_key'] = 'SM_MERCHANT';
        $data['action_key'] = ($slack == null)?'A_ADD_MERCHANT':'A_EDIT_MERCHANT';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MERCHANT_STATUS')->active()->sortValueAsc()->get();
        $data['merchant_data'] = null;
        if(isset($slack)){
            $merchant = MerchantModel::where('slack', '=', $slack)->first();
            if (empty($merchant)) {
                abort(404);
            }
            
            $merchant = new MerchantResource($merchant);
            $data['merchant_data'] = $merchant;
        }
        
        return view('merchant.add_merchant', $data);
    }

    
    //This is the function that loads the detail page
    public function detail($slack){

        $data['menu_key'] = 'MM_MERCHANT';
        $data['sub_menu_key'] = 'SM_MERCHANT';
        $data['action_key'] = 'A_DETAIL_MERCHANT';
        check_access([$data['action_key']]);

        $merchant = MerchantModel::where('slack', '=', $slack)->first();

        if (empty($merchant)) {
            abort(404);
        }

        $data['merchant_data'] = new MerchantResource($merchant);

        return view('merchant.merchant_detail', $data);
    }

    // new merchants

    //This is the function that loads the add/edit page
    public function add_merchant_category($slack = null){

        //check access
        $data['menu_key'] = 'MM_MERCHANT';
        $data['sub_menu_key'] = 'SM_MERCHANT';
        $data['action_key'] = ($slack == null)?'A_ADD_MERCHANT':'A_EDIT_MERCHANT';
        check_access(array($data['action_key']));

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MERCHANT_CATEGORY_STATUS')->active()->sortValueAsc()->get();

        $data['merchant_category_data'] = null;
        if(isset($slack)){
            $merchant = MerchantCategoryModel::where('slack', '=', $slack)->first();
            if (empty($merchant)) {
                abort(404);
            }
            
            $merchant_category_data = new MerchantUnitResource($merchant);
            $data['merchant_category_data'] = $merchant_category_data;
        }

        return view('merchant.add_merchant_category', $data);
    }

    public function delete($slack){
        
        $merchant = MerchantModel::where('slack',$slack)->first();

        try {
    
            /* STEP 1 - Deleting Subdomain */ 
            /* $client = new Client($this->host);
            $client->setCredentials($this->login, $this->password);
            $subdomain = $merchant->company_url.".wosul.sa";
            $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
                <packet>
                <subdomain>
                <del>
                   <filter>
                      <name>$subdomain</name>
                   </filter>
                </del>
                </subdomain>
                </packet>
            XML;
            $xml=simplexml_load_string($xml);
            $requestReponse =  $client->request($xml,2);*/

            /* STEP 2 - Deleting Database */
            $db_name = $merchant->company_url."_wosul";
            DB::statement("DROP DATABASE ".$db_name);

            /* STEP 3 - Delete Merchant's Entry From Admin Database */
            MerchantModel::where('slack',$slack)->delete();

            return redirect()->back();
        }

        catch(Exception $ex)    {
        
            return response()->json("Error in deleting subdomain");
        
        }

    }
}
