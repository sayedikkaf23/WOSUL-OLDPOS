<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDetailsOnInvoicesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices_return', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices_return', 'payment_method_id')) {
                $table->integer('payment_method_id')->nullable();
            }
            if (!Schema::hasColumn('invoices_return', 'payment_method_slack')) {
                $table->string('payment_method_slack',100)->nullable();
            }
            if (!Schema::hasColumn('invoices_return', 'payment_method')) {
                $table->string('payment_method',50)->nullable();
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
        Schema::table('invoices_return', function (Blueprint $table) {
            if (Schema::hasColumn('invoices_return', 'payment_method_id')) {
                $table->dropColumn('payment_method_id');
            }
            if (Schema::hasColumn('invoices_return', 'payment_method_slack')) {
                $table->dropColumn('payment_method_slack');
            }
            if (Schema::hasColumn('invoices_return', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
           
    }
}
