<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeSortOrderForMainMenusInMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menus')->where('type','MAIN_MENU')->update(['sort_order'=>null]);
        DB::table('menus')->where('menu_key','MM_DASHBOARD')->update(['sort_order'=>1]);
        DB::table('menus')->where('menu_key','MM_STOCK')->update(['sort_order'=>2]);
        DB::table('menus')->where('menu_key','MM_PURCHASE')->update(['sort_order'=>3]);
        DB::table('menus')->where('menu_key','MM_ORDERS')->update(['sort_order'=>4]);
        DB::table('menus')->where('menu_key','MM_ACCOUNT')->update(['sort_order'=>5]);
        DB::table('menus')->where('menu_key','MM_TAX_AND_DISCOUNT')->update(['sort_order'=>6]);
        DB::table('menus')->where('menu_key','MM_RESTAURANT')->update(['sort_order'=>7]);
        DB::table('menus')->where('menu_key','MM_SUPPLIER')->update(['sort_order'=>8]);
        DB::table('menus')->where('menu_key','MM_USER')->update(['sort_order'=>9]);
        DB::table('menus')->where('menu_key','MM_IMPORT')->update(['sort_order'=>10]);
        DB::table('menus')->where('menu_key','MM_NOTIFICATION')->update(['sort_order'=>11]);
        DB::table('menus')->where('menu_key','MM_REPORT')->update(['sort_order'=>12]);
        DB::table('menus')->where('menu_key','MM_SETTINGS')->update(['sort_order'=>13]);
        DB::table('menus')->where('menu_key','MM_LOYALITY')->update(['sort_order'=>14]);
        DB::table('menus')->where('menu_key','MM_ZID')->update(['sort_order'=>15]);
        DB::table('menus')->where('menu_key','MM_HR')->update(['sort_order'=>16]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
