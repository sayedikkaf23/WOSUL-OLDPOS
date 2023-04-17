<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertExpresspayPermissionForUserRoleMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $submenu = DB::table('menus')->where('menu_key','SM_EXPRESSPAY')->first();
        if(isset($submenu)){
            DB::table('role_menus')->where('id',$submenu->id)->updateOrInsert(
                ['role_id'=>1,'menu_id'=>$submenu->id],
                ['role_id'=>1,'menu_id'=>$submenu->id]
            );
            DB::table('user_menus')->where('id',$submenu->id)->updateOrInsert(
                ['user_id'=>1,'menu_id'=>$submenu->id],
                ['user_id'=>1,'menu_id'=>$submenu->id]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $submenu = DB::table('menus')->where('menu_key','SM_EXPRESSPAY')->first();
        if(isset($submenu)){
            DB::table('role_menus')->where('menu_id',$submenu->id)->where('role_id',1)->delete();
            DB::table('user_menus')->where('menu_id',$submenu->id)->where('user_id',1)->delete();
        }
    }
}
