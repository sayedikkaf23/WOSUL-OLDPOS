<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPartialPaymentToMasterStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('master_status')->where('key','ORDER_STATUS')
                ->where('value_constant','PARTIAL_PAYMENT')->where('value',7)->exists();
        if($exists == false){
            DB::table('master_status')->insert([
                'key' => 'ORDER_STATUS', 
                'value' => '7',
                'value_constant' => 'PARTIAL_PAYMENT', 
                'label' => 'Partial Payment', 
                'color' => 'label orange-label', 
                'status' => '1', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('master_status')->where('key','ORDER_STATUS')
            ->where('value_constant','PARTIAL_PAYMENT')->where('value',7)->delete();
    }
}
