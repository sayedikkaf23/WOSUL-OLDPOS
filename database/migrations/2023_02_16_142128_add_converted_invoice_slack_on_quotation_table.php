<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvertedInvoiceSlackOnQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('quotations', 'converted_invoice_slack')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->string('converted_invoice_slack',100)->nullable();
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
        if (Schema::hasColumn('quotations', 'converted_invoice_slack')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->dropColumn('converted_invoice_slack');
            });
        }
    }
}
