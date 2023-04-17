<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRequestJsonToExpresspay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresspay', function (Blueprint $table) {
            $table->json('request_json')->nullable()->after('data_json');
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
            $table->dropColumn('request_json');
        });
    }
}
