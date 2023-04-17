<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertExpresspayActionIntoMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $submenu = DB::table('menus')->where('menu_key','SM_INVOICES')->first();
        if(isset($submenu)){
            $submenu = array([
                'type' => 'ACTIONS',
                'menu_key' => 'A_VIEW_EXPRESSPAY_TRANSACTION',
                'label' => 'View Expresspay Transactions',
                'route' => '',
                'parent' => $submenu->id,
                'sort_order' => '9',
                'icon' => '',
                'image' => '',
                'is_restaurant_menu' => '0',
                'status' => '1'
            ]);
            DB::table('menus')->insert($submenu); 
            $action = DB::table('menus')->where('menu_key','A_VIEW_EXPRESSPAY_TRANSACTION')->first();
            if(isset($action)){
                DB::table('role_menus')->where('id',$action->id)->insert(['role_id'=>1,'menu_id'=>$action->id]);
                DB::table('user_menus')->where('id',$action->id)->insert(['user_id'=>1,'menu_id'=>$action->id]);
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
        $action = DB::table('menus')->where('menu_key','A_VIEW_EXPRESSPAY_TRANSACTION')->first();
        if(isset($action)){
            DB::table('role_menus')->where('menu_id',$action->id)->where('role_id',1)->delete();
            DB::table('user_menus')->where('menu_id',$action->id)->where('user_id',1)->delete();
        }
        DB::table('menus')->where('menu_key','A_VIEW_EXPRESSPAY_TRANSACTION')->delete(); 
    }
}
