<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceTypeToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 'price_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->collation = 'utf8mb4_unicode_ci';
                $table->string('price_type',50)->nullable()->default('fixed')->after('sale_amount_including_tax');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'price_type')) {
                $table->dropColumn('price_type');
            }
        });
    }
}
