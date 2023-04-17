<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertPricesSubMenuToMenus extends Migration
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
                'menu_key' => 'SM_PRICE',
                'label' => 'Prices',
                'route' => 'prices',
                'parent' => $parent->id,
                'sort_order' => '',
                'icon' => '',
                'image' => 'inventory/prices.png',
                'is_restaurant_menu' => '0',
                'status' => '1'
            ]);
            $submenu = DB::table('menus')->where('menu_key','SM_PRICE')->first();
            if(isset($submenu)){
                $submenus = array([
                    'type' => 'ACTIONS',
                    'menu_key' => 'A_ADD_PRICE',
                    'label' => 'Add Price',
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
                    'menu_key' => 'A_EDIT_PRICE',
                    'label' => 'Edit Price',
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
                    'menu_key' => 'A_DETAIL_PRICE',
                    'label' => 'View Price Detail',
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
                    'menu_key' => 'A_VIEW_PRICE_LISTING',
                    'label' => 'View Price Listing',
                    'route' => '',
                    'parent' => $submenu->id,
                    'sort_order' => '4',
                    'icon' => '',
                    'image' => '',
                    'is_restaurant_menu' => '0',
                    'status' => '1'
                ]
                );
                DB::table('menus')->insert($submenus); 

                $menus = DB::table('menus')->whereIn('menu_key',['SM_PRICE','A_ADD_PRICE','A_EDIT_PRICE','A_DETAIL_PRICE','A_VIEW_PRICE_LISTING'])->get();

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
        DB::table('menus')->where('menu_key','SM_PRICE')->delete(); 
        DB::table('menus')->where('menu_key','A_ADD_PRICE')->delete(); 
        DB::table('menus')->where('menu_key','A_EDIT_PRICE')->delete(); 
        DB::table('menus')->where('menu_key','A_DETAIL_PRICE')->delete(); 
        DB::table('menus')->where('menu_key','A_VIEW_PRICE_LISTING')->delete(); 
    }
}
