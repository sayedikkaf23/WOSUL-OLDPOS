<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

use App\Models\SettingEmail as SettingEmailModel;
use App\Models\SettingApp as SettingAppModel;
use App\Models\SettingSms as SettingSmsModel;
use App\Models\SmsSetting as SmsSettingModel;
use App\Models\MasterStatus;
use App\Models\MasterDateFormat;

use App\Http\Resources\SettingEmailResource;
use App\Http\Resources\SettingSmsResource;
use App\Http\Resources\SmsSettingResource;
use App\Http\Resources\SettingAppResource;
use Carbon\Carbon;
use mysqli;

use Illuminate\Support\Facades\Storage;

class Setting extends Controller
{
    public function email_setting(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_EMAIL_SETTING';
        check_access([$data['sub_menu_key']]);

        $email_setting = SettingEmailModel::select('*')->first();
        $email_setting_data = collect();
        if (!empty($email_setting)) {
            $email_setting_data = new SettingEmailResource($email_setting);
        }
        $data['email_setting'] = $email_setting_data;

        return view('setting.email.email_setting', $data);
    }

    public function edit_email_setting($slack = null)
    {
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_EMAIL_SETTING';
        $data['action_key'] = 'A_EDIT_EMAIL_SETTING';
        check_access([$data['action_key']]);

        $email_setting = SettingEmailModel::select('*')

            ->when($slack, function ($query, $slack) {
                $query->where('slack', $slack);
            })

            ->first();

        $email_setting_data = collect();
        if (!empty($email_setting)) {
            $email_setting_data = new SettingEmailResource($email_setting);
        }
        $data['setting_data'] = $email_setting_data;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('MAIL_SETTING_STATUS')->active()->sortValueAsc()->get();

        return view('setting.email.edit_email_setting', $data);
    }

    public function app_setting(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_APP_SETTING';
        check_access([$data['sub_menu_key']]);

        $app_setting = SettingAppModel::select('*')->first();
        $app_setting_data = collect();
        if (!empty($app_setting)) {
            $app_setting_data = new SettingAppResource($app_setting);
        }
        $data['setting_data'] = $app_setting_data;

        return view('setting.app.app_setting', $data);
    }

    public function edit_app_setting($slack = null)
    {
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_APP_SETTING';
        $data['action_key'] = 'A_EDIT_APP_SETTING';
        check_access([$data['action_key']]);

        $data['date_time_formats'] = MasterDateFormat::select('date_format_value', 'date_format_label')->where([
            ['key', '=', 'DATE_TIME_FORMAT'],
            ['status', '=', 1],
        ])->get();

        $data['date_formats'] = MasterDateFormat::select('date_format_value', 'date_format_label')->where([
            ['key', '=', 'DATE_FORMAT'],
            ['status', '=', 1],
        ])->get();

        $app_setting = SettingAppModel::select('*')
            ->first();

        $app_setting_data = collect();
        if (!empty($app_setting)) {
            $app_setting_data = new SettingAppResource($app_setting);
        }
        $data['setting_data'] = $app_setting_data;

        return view('setting.app.edit_app_setting', $data);
    }

    public function cpanel_migrate()
    {

        if (App::environment('production')) {
            try {

                DB::connection()->getPdo();
                if (DB::connection()->getDatabaseName()) {
                    echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName() . '<br>';
                } else {
                    die("Could not find the database. Please check your configuration.");
                }

                Artisan::call('migrate', [
                    '--force' => true,
                ]);

                echo 'Migration done!';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }

    public function cpanel_storage_link()
    {

        if (App::environment('production')) {
            try {

                Artisan::call('storage:link');

                echo 'Storage linking done!';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }

    public function cpanel_intial_config()
    {

        if (App::environment('production')) {
            try {
                Artisan::call('config:clear');
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                echo 'Cleared!';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }

    public function sms_setting(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_SMS_SETTING';
        check_access([$data['sub_menu_key']]);

        $sms_setting = SmsSettingModel::select('*')->first();
        $sms_setting_data = collect();
        if (!empty($sms_setting)) {
            $sms_setting_data = new SmsSettingResource($sms_setting);
        }
        $data['sms_setting'] = $sms_setting_data;
        return view('setting.sms.sms_setting', $data);
    }

    public function edit_sms_setting($slack = null)
    {
        $data['menu_key'] = 'MM_SETTINGS';
        $data['sub_menu_key'] = 'SM_SMS_SETTING';
        $data['action_key'] = 'A_EDIT_SMS_SETTING';
        check_access([$data['action_key']]);

        $sms_setting = SmsSettingModel::select('*')

            ->when($slack, function ($query, $slack) {
                $query->where('slack', $slack);
            })

            ->first();

        $sms_setting_data = collect();
        if (!empty($sms_setting)) {
            $sms_setting_data = new SmsSettingResource($sms_setting);
        }
        $data['setting_data'] = $sms_setting_data;

        $data['statuses'] = MasterStatus::select('value', 'label')->filterByKey('SMS_SETTING_STATUS')->active()->sortValueAsc()->get();

        return view('setting.sms.edit_sms_setting', $data);
    }

    public function update_roles_for_admin()
    {

        $databases = [
            'merchant_copy'
        ];

        $role_id = 1;
        $user_id = 1;

        foreach ($databases as $database) {
            $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $database);
            if ($connect) {

                $menus = "SELECT id FROM menus WHERE status = '1' ORDER BY id ASC ";
                $menus = mysqli_query($connect, $menus);
                while ($menu = mysqli_fetch_assoc($menus)) {

                    if ($menu['id'] != $role_id && $menu['id'] != $user_id) {
                        $update_role_menu = "INSERT INTO role_menus (role_id,menu_id)
                        SELECT * FROM (SELECT '" . $role_id . "', '" . $menu['id'] . "' ) AS tmp
                        WHERE NOT EXISTS (
                            SELECT role_id,menu_id FROM role_menus WHERE role_id = '" . $role_id . "' AND menu_id = '" . $menu['id'] . "'
                        ) LIMIT 1; ";
                        mysqli_query($connect, $update_role_menu);

                        $update_user_menu = "INSERT INTO user_menus (user_id,menu_id)
                        SELECT * FROM (SELECT '" . $user_id . "', '" . $menu['id'] . "' ) AS tmp
                        WHERE NOT EXISTS (
                            SELECT user_id,menu_id FROM user_menus WHERE user_id = '" . $user_id . "' AND menu_id = '" . $menu['id'] . "'
                            ) LIMIT 1; ";
                        mysqli_query($connect, $update_user_menu);
                    }
                }
            }
        }
    }



    /*
        -------------------------------
        ------ HELPER FUNCTIONS -------
        -------------------------------
    */

    public function run_alter_query_all_db(Request $request)
    {

        $host = ($request->host == '' || is_null($request->host)) ? 'LOCAL' : $request->host;
        $start_point = $request->start_point;
        $db_limit = $request->db_limit;
        $start_db_name = $request->start_db_name;
        // $host = "LIVE";  // live or local

        if ($host == 'LOCAL') {
            $host = 'localhost';
            $username = 'root';
            $password = '';
        } else if ($host == 'STAGING') {
            $host = '104.248.21.161';
            $username = 'wosul_staging';
            $password = '2Xt2c@j4';
        } else if ($host == 'PRODUCTION') {
            $host = '165.227.88.181';
            $username = 'wosul';
            $password = '2Xt2c@j4';
        }

        $connect = mysqli_connect($host, $username, $password, env('DB_DATABASE'));
        $master_query = "SELECT company_url FROM merchants WHERE id >= ( SELECT id FROM merchants WHERE company_url = '{$start_db_name}' ) ORDER BY id ASC LIMIT {$start_point},{$db_limit}";

        $get_all_databases = mysqli_query($connect, $master_query);

        $count = 0;
        $updated_dbs = [];

        if (mysqli_num_rows($get_all_databases) > 0) {
            while ($db = mysqli_fetch_assoc($get_all_databases)) {

                $db = strtolower($db['company_url'] . "_wosul");

                $check_if_db_exists = "select schema_name from information_schema.schemata where schema_name = '" . $db . "' ";
                $check_if_db_exists = mysqli_query($connect, $check_if_db_exists);

                if (mysqli_num_rows($check_if_db_exists) > 0) {

                    if ($db_connect = mysqli_connect($host, $username, $password, $db)) {
        
                            $query = "ALTER TABLE `orders` ADD `reference_number` BIGINT(11) NULL";
                            mysqli_query($db_connect, $query);
        
                            /*
                                    CUSTOM QUERY SECTION : START
                            */
                            $stores = mysqli_query($db_connect, 'SELECT * FROM stores ORDER BY id ASC');
        
                            if (mysqli_num_rows($stores) > 0) {
        
                                while ($store = mysqli_fetch_assoc($stores)) {
                                    $orders = mysqli_query($db_connect, 'SELECT * FROM orders WHERE store_id = "' . $store['id'] . '" ORDER BY id ASC');
                                    $i = 1;
                                    if (mysqli_num_rows($orders) > 0) {
                                        while ($order = mysqli_fetch_assoc($orders)) {
                                            mysqli_query($db_connect, 'UPDATE orders SET reference_number = "' . $i . '" WHERE id = "' . $order['id'] . '"');
                                            $i++;
                                        }
                                    }
                                }
                            }
        
        
                            /*
                                    CUSTOM QUERY SECTION : END
                                */
                    }

                    $count++;

                    array_push($updated_dbs, $db);
                }
            }
        }


        echo "<pre>";
        echo $count . " databases updated";
        print_r($updated_dbs);

        exit;
    }

    public function run_alter_query_single_db($db, $env = 'LOCAL')
    {

        if ($env == 'LOCAL') {
            $host = 'localhost';
            $username = 'root';
            $password = 'root';
        } else {
            $host = '165.227.88.181';
            $username = 'wosul';
            $password = '2Xt2c@j4';
        }

        $connect = mysqli_connect($host, $username, $password, $db);
        if ($connect) {
        }

        echo "<br>";
        echo "Done";
    }

    public function update_tax_setting($db, $env = 'LOCAL')
    {

        if ($env == 'LOCAL') {
            $host = 'localhost';
            $username = 'root';
            $password = 'root';
        } else {
            $host = '165.227.88.181';
            $username = 'wosul';
            $password = '2Xt2c@j4';
        }

        $connect = mysqli_connect($host, $username, $password, $db);
        if ($connect) {

            $stores = 'SELECT * FROM stores';

        }

        echo "<br>";
        echo "Done";
    }

    public function generate_merchant_tax_type_report()
    {
        
        // local/dev 
        // $host = 'localhost';
        // $username = 'root';
        // $password = '';
    
        // production
        $host = '165.227.88.181';
        $username = 'wosul';
        $password = '2Xt2c@j4';
    
        $connect = mysqli_connect($host, $username, $password, 'wosulerp_admin');
        $master_query = "SELECT company_url FROM merchants ORDER BY id ASC LIMIT 221,250";
        $get_all_databases = mysqli_query($connect, $master_query);

        $count = 0;
        $data = [];

        if (mysqli_num_rows($get_all_databases) > 0) {
            while ($db = mysqli_fetch_assoc($get_all_databases)) {

                $merchant_name = $db['company_url'];
                $db = strtolower($db['company_url'] . "_wosul");
                
                $check_if_db_exists = "select schema_name from information_schema.schemata where schema_name = '" . $db . "' ";
                $check_if_db_exists = mysqli_query($connect, $check_if_db_exists);

                if (mysqli_num_rows($check_if_db_exists) > 0) {

                    if (!$db_connect = mysqli_connect($host, $username, $password, $db)) {
                        die('No connection: ' . mysqli_connect_error());
                    }

                    $query = "SELECT tax_type,tax_percentage FROM tax_code_type where tax_percentage != 15 ";
                    $query = mysqli_query($db_connect, $query);
                    if(mysqli_num_rows($query) > 0){
                        while($row = mysqli_fetch_assoc($query)){
                            $dataset = [];
                            $dataset['Merchant'] = $merchant_name;
                            $dataset['Tax Type'] = $row['tax_type'];
                            $dataset['Tax Percentage'] = $row['tax_percentage'];
                            
                            $data[]  = $dataset;
                        }

                    }
                
                }
            }
        }

        $myfile = fopen("tax_report.txt", "w");
        fwrite($myfile, print_r($data, true));
        fclose($myfile);

        echo "<br>";
        echo "Done";
    }

    public function reset_merchant_copy_menus(){
        
        // local/dev 
        $host = 'localhost';
        $username = 'root';
        $password = '';
    
        $connect = mysqli_connect($host, $username, $password, 'merchant_copy');
        $menus = mysqli_query($connect,"SELECT * FROM menus ORDER BY id ASC");
        
        // remove all existing menus assigned to admin
        mysqli_query($connect,"DELETE FROM role_menus WHERE role_id = '1' ");
        mysqli_query($connect,"DELETE FROM user_menus WHERE user_id = '1' ");

        $data['updated_rows'] = 0;

        while($menu = mysqli_fetch_assoc($menus)){
         
            mysqli_query($connect,"INSERT INTO role_menus(role_id,menu_id) VALUES('1','".$menu['id']."')  ");
            mysqli_query($connect,"INSERT INTO user_menus(user_id,menu_id) VALUES('1','".$menu['id']."')  ");

            $data['updated_rows']++;

        }

        echo "updated ".$data['updated_rows']." rows";
         
    }
}
