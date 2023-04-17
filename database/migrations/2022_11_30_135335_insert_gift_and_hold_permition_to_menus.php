<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertGiftAndHoldPermitionToMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $submenu = DB::table('menus')->where('menu_key','SM_POS_ORDERS')->first();
        if(isset($submenu)){
            $submenus = array([
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_HOLD_ORDER',
                    'label' => 'Can Hold Order',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '8',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ],
                [
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_GIFT_ORDER',
                    'label' => 'Can Gift Order',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '9',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ],
            );
            DB::table('menus')->insert($submenus);

            $menus = DB::table('menus')->whereIn('menu_key',['A_HOLD_ORDER','A_GIFT_ORDER'])->get();
            if(isset($menus)){
                foreach($menus as $menu){
                    DB::table('role_menus')->where('id',$menu->id)->insert(['role_id'=>1,'menu_id'=>$menu->id]);
                    DB::table('user_menus')->where('id',$menu->id)->insert(['user_id'=>1,'menu_id'=>$menu->id]);
                }
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
        DB::table('menus')->where('menu_key','A_HOLD_ORDER')->delete();
        DB::table('menus')->where('menu_key','A_GIFT_ORDER')->delete();
    }
}
