<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class InsertExpresspaySettingSubmenuInMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu =  DB::table('menus')->where('menu_key','SM_EXPRESSPAY')->first();
        if(!isset($menu)){
            DB::table('menus')->insert([
                'type' => 'SUB_MENU',
                'label' => 'Expresspay',
                'menu_key' => 'SM_EXPRESSPAY',
                'route' => 'expresspay_setting',
                'parent' => 8,
                'sort_order' => 1,
                'icon' => '',
                'image' => 'setting_user.png',
                'status' => 1,
                'created_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menus')->where('menu_key','SM_EXPRESSPAY')->delete();
    }
}
