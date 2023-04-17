<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDuplicationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_duplication_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number');
            $table->dateTime('order_created_at');
            $table->dateTime('order_updated_at');
            $table->json('order_data');
            $table->json('transaction_data')->nullable();
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
        Schema::dropIfExists('order_duplication_logs');
    }
}
