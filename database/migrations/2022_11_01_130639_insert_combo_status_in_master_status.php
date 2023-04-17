<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class InsertComboStatusInMasterStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('master_status')->insert([
            'key' => 'COMBO_STATUS',
            'value' => '1',
            'value_constant' => 'ACTIVE',
            'label' => 'Active',
            'color' => 'label green-label',
            'status' => '1',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        
        DB::table('master_status')->insert([
            'key' => 'COMBO_STATUS',
            'value' => '0',
            'value_constant' => 'INACTIVE',
            'label' => 'Inactive',
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
        DB::table('master_status')->where('key','COMBO_STATUS')->delete();
        DB::table('master_status')->where('key','COMBO_STATUS')->delete();
    }
}
