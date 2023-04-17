<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveQuotationNumberUniqueFromQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {

            $keyExists = DB::select(
                DB::raw(
                    'SHOW KEYS
                    FROM quotations
                    WHERE Key_name="quotations_quotation_number_unique" '
                )
            );

            if (!empty($keyExists)) {
                $table->dropUnique('quotations_quotation_number_unique');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->unique('quotation_number');
        });
    }
}
