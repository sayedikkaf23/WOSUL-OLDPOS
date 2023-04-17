<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnActionInQuantityAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quantity_adjustments', function (Blueprint $table) {
            $table->string('action')->default('DECREMENT')->after('store_id')->comment('values: INCREMENT,DECREMENT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quantity_adjustments', function (Blueprint $table) {
            $table->dropColumn('action');
        });
    }
}
