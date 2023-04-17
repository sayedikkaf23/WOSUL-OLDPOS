<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InsertExpresspayIntoPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!isset($exists)){
            DB::table('payment_methods')->insert([
                'slack' => 'wdgHgKPXATLB0VbrShlP42sLG',
                'payment_constant' => 'EXPRESSPAY',
                'label' => 'Express Pay',
                'description' => 'Express Pay',
                'status' => '1',
                'created_at' => Carbon::now(),
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
        $exists = DB::table('payment_methods')->where('payment_constant','EXPRESSPAY')->first();
        if(isset($exists)){
            $exists->delete();
        }
    }
}
