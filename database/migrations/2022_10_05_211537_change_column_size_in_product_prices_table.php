<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnSizeInProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->decimal('sale_amount_excluding_tax',10,4)->change();
            $table->decimal('sale_amount_including_tax',10,4)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->decimal('sale_amount_excluding_tax',10,2)->change();
            $table->decimal('sale_amount_including_tax',10,2)->change();
        });
    }
}
