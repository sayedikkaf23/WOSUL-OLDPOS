<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQoyodKeyColumnToSettingAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_app', function (Blueprint $table) {
            $table->string('qoyod_api_key')->nullable()->after('favicon');
            $table->tinyInteger('qoyod_status')->default(0)->comment('0-Inactive,1-Active')->after('qoyod_api_key');
            $table->dateTime('qoyod_last_sync_time')->nullable()->default(null)->after('qoyod_status');
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
            $table->dropColumn('qoyod_api_key');
            $table->dropColumn('qoyod_status');
            $table->dropColumn('qoyod_last_sync_time');
        });
    }
}
