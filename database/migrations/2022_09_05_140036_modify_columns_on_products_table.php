<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsOnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->nullable()->default(null)->change();
            $table->decimal('sale_amount_excluding_tax', 13, 4)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->default(0.00)->change();
            $table->decimal('sale_amount_excluding_tax', 13, 4)->change();
        });
    }
}
