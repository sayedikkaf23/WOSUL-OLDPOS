<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Menu;

class CreateMenuForTaxReturnReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $exists = Menu::where('menu_key','SM_TAX_RETURN_REPORT')->where('route','tax_return_report')->exists();
        if($exists == false){
            $menu_id = Menu::insertGetId([
                'type' => 'SUB_MENU', 
                'menu_key' => 'SM_TAX_RETURN_REPORT',
                'label' => 'Tax Return Report',
                'route' => 'tax_return_report', 
                'parent' => 7, 
                'sort_order' => 8, 
                'icon' => NULL, 
                'image' => 'reports/category.png', 
                'is_restaurant_menu' => 0, 
                'status' => '1', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if(!empty($menu_id)){

                DB::table('user_menus')->insert([
                    'user_id' => '1', 
                    'menu_id' => $menu_id,
                    'created_by' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
    
                DB::table('role_menus')->insert([
                    'role_id' => '1', 
                    'menu_id' => $menu_id,
                    'created_by' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
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
        DB::table('menus')->where('menu_key','SM_TAX_RETURN_REPORT')
        ->where('route','tax_return_report')->delete();
    }
}
