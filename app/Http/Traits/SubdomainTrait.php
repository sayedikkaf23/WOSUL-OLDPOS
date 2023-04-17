<?php

namespace App\Http\Traits;

use PleskX\Api\Client;
use DB;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\SubscriptionMenu;
use App\Models\Menu;
use App\Models\MerchantMenu;
use Artisan;

trait SubdomainTrait
{

    public function create_database($db_name)
    {

        $db_host = "localhost";
        $db_path = env('MERCHANT_DB_PATH');

        $db_name = strtolower($db_name);
        DB::statement("CREATE DATABASE " . $db_name);
        $import_db = "mysql --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " " . "-h " . $db_host . " -D " . $db_name . " < " . $db_path . " ";
        shell_exec($import_db);
    }

    public function drop_database($db_name)
    {

        $db_host = "localhost";
        $db_path = env('MERCHANT_DB_PATH');

        $db_name = strtolower($db_name);
        DB::statement("DROP DATABASE " . $db_name);
        $import_db = "mysql --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " " . "-h " . $db_host . " -D " . $db_name . " < " . $db_path . " ";
        shell_exec($import_db);
    }

    public function config_database($request, $company_url)
    {

        // Update default username and password with registered username and password : start
        $menu_ids = SubscriptionMenu::where('subscription_id', $request->subscription_id)->pluck('menu_id')->toArray();
        $menu_keys = MerchantMenu::whereIn('id', $menu_ids)->pluck('menu_key')->toArray();

        $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), strtolower($company_url) . "_wosul");

        $user_id = 'SELECT id FROM users WHERE email = "merchant@wosul.sa" ';
        $user_id = mysqli_query($connect, $user_id);
        $user_id = mysqli_fetch_assoc($user_id);
        $user_id = $user_id['id'];

        mysqli_query($connect, 'UPDATE users SET 
            fullname = "' . $request->name . '", 
            email = "' . $request->email . '", 
            password = "' . Hash::make($request->password) . '" 
            WHERE id = "' . $user_id . '" ');

        // creating the menus for merchant based on subscription plan
        $menu_keys = '"' . implode('","', $menu_keys) . '"';
        $adding_role_menus = mysqli_query($connect, 'SELECT id FROM menus WHERE menu_key IN(' . $menu_keys . ') ');
        while ($role_menu = mysqli_fetch_assoc($adding_role_menus)) {
            mysqli_query($connect, 'INSERT INTO role_menus(role_id,menu_id) VALUES(' . $user_id . ',' . $role_menu['id'] . ') ');
            mysqli_query($connect, 'INSERT INTO user_menus(user_id,menu_id) VALUES(' . $user_id . ',' . $role_menu['id'] . ') ');
        }
        // Update default username and password with registered username and password : ends

        mysqli_close($connect);
    }
}
