<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDamageReportMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu_exist = DB::select("SELECT id FROM menus WHERE menu_key = 'SM_DAMAGE_REPORT'");
        if(!isset($menu_exist[0])){
            $menu_status = DB::statement("insert into menus(type,menu_key,label,route,parent,sort_order,icon,image,is_restaurant_menu,status)
                                            values('SUB_MENU','SM_DAMAGE_REPORT','Damage Report','damage_reports',7,5,NULL,'reports/category.png',0,1)");
            if($menu_status == true){
                $menu_access = DB::unprepared("SET @menu_id = (SELECT id FROM menus WHERE menu_key = 'SM_DAMAGE_REPORT');
                INSERT INTO `role_menus` ( `role_id`, `menu_id`, `created_by`, `created_at`, `updated_at`) VALUES ( '1', @menu_id, '1', now(), now());
                INSERT INTO `user_menus` ( `user_id`, `menu_id`, `created_by`, `created_at`, `updated_at`) VALUES ( '1', @menu_id, '1', now(), now());");
                // DB::statement("insert into user_menus(user_id,menu_id,created_by, created_at, updated_at)
                //                 select id as user_id,(select id from menus where label='Damage Report') as menu_id,1,now(),now() from users");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // Schema::table('menus', function (Blueprint $table) {
        //     //
        // });
    }
}
