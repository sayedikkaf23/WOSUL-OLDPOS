<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReturningRegisterIdToOrderReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return', function (Blueprint $table) {
            $table->bigInteger('returning_register_id')->nullable()->after('register_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return', function (Blueprint $table) {
            $table->dropColumn('returning_register_id');
        });
    }
}
