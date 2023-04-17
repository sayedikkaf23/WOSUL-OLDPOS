<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CorrectingInvoiceListingMenuKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menus')->where('menu_key','A_VIEW_QUANTITY_PURCHASE_ORDER')->update(['menu_key' => 'A_VIEW_PURCHASE_ORDER_LISTING']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menus')->where('menu_key','A_VIEW_PURCHASE_ORDER_LISTING')->update(['menu_key' => 'A_VIEW_QUANTITY_PURCHASE_ORDER']);
    }
}
