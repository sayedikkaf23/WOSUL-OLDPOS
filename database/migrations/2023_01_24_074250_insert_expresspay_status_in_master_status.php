<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InsertExpresspayStatusInMasterStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('master_status')->insert([
            'key' => 'EXPRESSPAY_STATUS',
            'value' => '1',
            'value_constant' => 'PAID',
            'label' => 'Paid',
            'color' => 'label green-label',
            'status' => '1',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        
        DB::table('master_status')->insert([
            'key' => 'EXPRESSPAY_STATUS',
            'value' => '0',
            'value_constant' => 'PENDING',
            'label' => 'Pending',
            'color' => 'label red-label',
            'status' => '1',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('master_status')->where('key','EXPRESSPAY_STATUS')->delete();
        DB::table('master_status')->where('key','EXPRESSPAY_STATUS')->delete();
    }
}
