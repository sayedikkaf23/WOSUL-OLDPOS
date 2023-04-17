<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCustomerPhoneEmailOptionalInOrderReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return', function (Blueprint $table) {
            $table->string('customer_phone',50)->nullable()->change();
            $table->string('customer_email',50)->nullable()->change();
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
            $table->string('customer_phone',50)->nullable(false)->change();
            $table->string('customer_email',50)->nullable(false)->change();
        });
    }
}
