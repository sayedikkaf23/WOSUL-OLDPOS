<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slack')->unique();
            $table->bigInteger('combo_id');
            $table->bigInteger('combo_size_id');
            $table->bigInteger('combo_group_id');
            $table->bigInteger('product_id');
            $table->bigInteger('measurement_id')->nullable();
            $table->double('quantity',10,4);
            $table->double('price',10,4);
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
        Schema::dropIfExists('combo_products');
    }
}
