<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slack')->unique();
            $table->string('name');
            $table->bigInteger('store_id');
            $table->bigInteger('category_id');
            $table->tinyInteger('is_discount_enabled')->default(0);
            $table->string('discount_type')->comment('Constant:AMOUNT,PERCENTAGE')->nullable();
            $table->double('discount_value',10,2)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0-Inactive,1-Active');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('combos');
    }
}
