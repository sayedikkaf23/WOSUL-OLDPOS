<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataSizeOfModifierOptionPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_modifier_options', function (Blueprint $table) {
            $table->decimal('modifier_option_price', 10, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_modifier_options', function (Blueprint $table) {
            $table->decimal('modifier_option_price', 10, 2)->change();
        });
    }
}
