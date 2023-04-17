<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQoyodCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoyod_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('qoyod_category_id');
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
        Schema::dropIfExists('qoyod_categories');
    }
}
