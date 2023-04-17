<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactColumnToExpresspayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresspay', function (Blueprint $table) {
            $table->string('contact_number')->nullable()->after('payment_link');
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
            $table->dropColumn('contact_number');
        });
    }
}
