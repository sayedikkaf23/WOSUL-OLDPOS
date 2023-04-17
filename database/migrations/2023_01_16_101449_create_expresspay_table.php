<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpresspayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expresspay', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slack',50);
            $table->string('bill_to',50)->comment('INVOICE,POS');
            $table->unsignedBigInteger('bill_to_id');
            $table->decimal('amount',10,2);
            $table->string('payment_link')->nullable();
            $table->json('data_json')->nullable();
            $table->json('response_json')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0-Pending,1-Paid');
            $table->datetime('paid_at')->nullable();
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
        Schema::dropIfExists('expresspay');
    }
}
