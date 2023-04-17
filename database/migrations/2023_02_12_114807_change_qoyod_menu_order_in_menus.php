<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQoyodMenuOrderInMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menus')->where('menu_key','SM_QOYOD')->update(['sort_order'=>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        DB::table('menus')->where('menu_key','SM_QOYOD')->update(['sort_order'=>0]);
    }
}
