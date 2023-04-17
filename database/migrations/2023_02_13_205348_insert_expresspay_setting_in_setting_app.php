<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertExpresspaySettingInSettingApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_app', function (Blueprint $table) {
            $table->string('expresspay_merchant_key')->nullable();
            $table->string('expresspay_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_app', function (Blueprint $table) {
            $table->dropColumn('expresspay_merchant_key');
            $table->dropColumn('expresspay_password');
        });
    }
}
