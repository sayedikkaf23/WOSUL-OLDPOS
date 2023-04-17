<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatformTypeToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('platform_mode')->default('ONLINE')->comment('ONLINE,OFFLINE')->after('idle_time');
            $table->string('platform_type')->comment('IPAD,ANDROID')->nullable()->after('platform_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('platform_mode');
            $table->dropColumn('platform_type');
        });
    }
}
