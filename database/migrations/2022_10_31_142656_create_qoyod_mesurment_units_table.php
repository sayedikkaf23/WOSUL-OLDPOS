<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQoyodMesurmentUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoyod_mesurment_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('qoyod_unit_id');
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
        Schema::dropIfExists('qoyod_mesurment_units');
    }
}
