<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertComboMenuToMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent = DB::table('menus')->where('menu_key','MM_STOCK')->first();
        
        if(isset($parent)){
            DB::table('menus')->insert([
                'type' => 'SUB_MENU',
                'menu_key' => 'SM_COMBO',
                'label' => 'Combos',
                'route' => 'combos',
                'parent' => $parent->id,
                'sort_order' => '1',
                'icon' => '',
                'image' => 'inventory/product.png',
                'is_restaurant_menu' => '0',
                'status' => '1'
            ]);
            $submenu = DB::table('menus')->where('menu_key','SM_COMBO')->first();

            if(isset($submenu)){
            
                $submenus = array([
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_ADD_COMBO_GROUP',
                    'label' => 'Add Combo Group',
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
                    'menu_key' => 'A_EDIT_COMBO_GROUP',
                    'label' => 'Edit Combo Group',
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
                    'menu_key' => 'A_VIEW_COMBO_GROUP_LISTING',
                    'label' => 'View Combo Group Listing',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '3',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ],
                [
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_ADD_COMBO',
                    'label' => 'Add Combo',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '4',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ],
                [
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_EDIT_COMBO',
                    'label' => 'Edit Combo',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '5',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ],
                [
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_VIEW_COMBO_LISTING',
                    'label' => 'View Combo Listing',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '7',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ]
                );
                DB::table('menus')->insert($submenus); 

                $menus = DB::table('menus')->whereIn('menu_key',['SM_COMBO','A_ADD_COMBO_GROUP','A_EDIT_COMBO_GROUP','A_VIEW_COMBO_GROUP_LISTING','A_ADD_COMBO','A_EDIT_COMBO','A_DETAIL_COMBO','A_VIEW_COMBO_LISTING'])->get();

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
        DB::table('menus')->where('menu_key','SM_COMBO')->delete(); 
        DB::table('menus')->where('menu_key','A_ADD_COMBO_GROUP')->delete(); 
        DB::table('menus')->where('menu_key','A_EDIT_COMBO_GROUP')->delete(); 
        DB::table('menus')->where('menu_key','A_VIEW_COMBO_GROUP_LISTING')->delete(); 
        DB::table('menus')->where('menu_key','A_ADD_COMBO')->delete(); 
        DB::table('menus')->where('menu_key','A_EDIT_COMBO')->delete(); 
        DB::table('menus')->where('menu_key','A_DETAIL_COMBO')->delete(); 
        DB::table('menus')->where('menu_key','A_VIEW_COMBO_LISTING')->delete(); 
    }
}
