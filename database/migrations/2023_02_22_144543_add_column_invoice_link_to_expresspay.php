<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInvoiceLinkToExpresspay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresspay', function (Blueprint $table) {
            $table->string('invoice_link')->nullable()->after('payment_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expresspay', function (Blueprint $table) {
            $table->dropColumn('invoice_link');
        });
    }
}
