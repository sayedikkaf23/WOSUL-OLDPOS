<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileCashiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_cashiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('CASHIER')->comment('CASHIER','SUB_CASHIER');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('store_id');
            $table->tinyInteger('device_id');
            $table->json('response_data');
            $table->string('device_type')->comment('IPAD','ANDROID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_cashiers');
    }
}
