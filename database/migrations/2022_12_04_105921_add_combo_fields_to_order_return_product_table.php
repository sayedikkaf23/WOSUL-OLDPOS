<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComboFieldsToOrderReturnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return_product', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->string('combo_cart_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return_product', function (Blueprint $table) {
            $table->dropColumn('combo_id');
            $table->dropColumn('combo_cart_id');
        });
    }
}
