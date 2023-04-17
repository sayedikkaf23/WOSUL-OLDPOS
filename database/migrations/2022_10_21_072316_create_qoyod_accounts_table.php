<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQoyodAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoyod_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slack',30)->nullable();
            $table->unsignedBigInteger('store_id')->default(0);
            $table->string('api_key',255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-Inactive, 1-Active');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qoyod_accounts');
    }
}
