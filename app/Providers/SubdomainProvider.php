<?php

namespace App\Providers;

use App\Models\Merchant;
use Illuminate\Support\ServiceProvider;

use DB;

class SubdomainProvider extends ServiceProvider
{
   /**
    * Register services.
    *
    * @return void
    */
   public function register()
   {

      if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {

         $host =  $_SERVER['HTTP_HOST'];
         $urls = explode(".", $host);

         if ($urls[0] == "www") {
            $subdomain = $urls[1];
         } else {
            $subdomain = $urls[0];
         }

         if ($subdomain == env('DB_PREFIX') || $subdomain == "admin") {
            // wosul website & admin panel
            $merchant_database = "wosulerp_admin";
         } else {
            $merchant_database = $subdomain . "_wosul";
            $merchant =  DB::table('merchants')->where('company_url', $subdomain)->first();
            if (isset($merchant) && $merchant->status != 1) {
               echo $html = "Your account is on hold, please contact administrator";
               exit;
            }
         }

         config([
            'database.connections.mysql.database' => $merchant_database,
         ]);

         DB::purge('mysql');
         DB::reconnect('mysql');
      }
   }

   /**
    * Bootstrap services.
    *
    * @return void
    */
   public function boot()
   {
   }
}
