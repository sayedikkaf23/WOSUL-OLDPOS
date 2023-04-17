<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertMarketPlaceAndQoyodMenusToMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menus')->insert([
            'type' => 'MAIN_MENU',
            'menu_key' => 'MM_MARKETPLACE',
            'label' => 'Marketplace',
            'route' => '',
            'parent' => 0,
            'sort_order' => 18,
            'icon' => '',
            'image' => 'marketplace.png',
            'is_restaurant_menu' => '0',
            'status' => '1'
        ]);

        $parent = DB::table('menus')->where('menu_key','MM_MARKETPLACE')->first();

        if(isset($parent)){
            DB::table('menus')->insert([
                'type' => 'SUB_MENU',
                'menu_key' => 'SM_QOYOD',
                'label' => 'Qoyod',
                'route' => 'qoyod',
                'parent' => $parent->id,
                'sort_order' => '',
                'icon' => '',
                'image' => 'marketplaces/qoyod.png',
                'is_restaurant_menu' => '0',
                'status' => '1'
            ]);
            $submenu = DB::table('menus')->where('menu_key','SM_QOYOD')->first();
            if(isset($submenu)){
                $submenus = array(
                    [
                        'type' => 'ACTIONS',
                        'menu_key' => 'A_ADD_QOYOD',
                        'label' => 'Add Qoyod',
                        'route' => '',
                        'parent' => $submenu->id,
                        'sort_order' => '1',
                        'icon' => '',
                        'image' => '',
                        'is_restaurant_menu' => '0',
                        'status' => '1'
                    ],
                    [
                        'type' => 'ACTIONS',
                        'menu_key' => 'A_EDIT_QOYOD',
                        'label' => 'Edit Qoyod',
                        'route' => '',
                        'parent' => $submenu->id,
                        'sort_order' => '2',
                        'icon' => '',
                        'image' => '',
                        'is_restaurant_menu' => '0',
                        'status' => '1'
                    ],
                    [
                        'type' => 'ACTIONS',
                        'menu_key' => 'A_SYNC_QOYOD_BUSINESS_ACCOUNT',
                        'label' => 'Sync Business Account',
                        'route' => '',
                        'parent' => $submenu->id,
                        'sort_order' => '3',
                        'icon' => '',
                        'image' => '',
                        'is_restaurant_menu' => '0',
                        'status' => '1'
                    ]
                );
                DB::table('menus')->insert($submenus);

                $menus = DB::table('menus')->whereIn('menu_key',['MM_MARKETPLACE','SM_QOYOD','A_ADD_QOYOD','A_EDIT_QOYOD','A_SYNC_QOYOD_BUSINESS_ACCOUNT'])->get();

                if(isset($menus)){
                    foreach($menus as $menu){
                        DB::table('role_menus')->where('id',$menu->id)->insert(['role_id'=>1,'menu_id'=>$menu->id]);
                        DB::table('user_menus')->where('id',$menu->id)->insert(['user_id'=>1,'menu_id'=>$menu->id]);
                    }
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
        DB::table('menus')->where('menu_key','MM_MARKETPLACE')->delete();
        DB::table('menus')->where('menu_key','SM_QOYOD')->delete();
        DB::table('menus')->where('menu_key','A_ADD_QOYOD')->delete();
        DB::table('menus')->where('menu_key','A_EDIT_QOYOD')->delete();
        DB::table('menus')->where('menu_key','A_SYNC_QOYOD_BUSINESS_ACCOUNT')->delete();
    }
}
