<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InsertIntoPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('payment_methods')->where('payment_constant','SUREPAY')->first();

        if(!isset($exists)){
            DB::table('payment_methods')->insert([
                'slack' => 'wdgH8KPXATLB0VbrShlP42sLJ',
                'payment_constant' => 'SUREPAY',
                'label' => 'Sure Pay',
                'description' => 'Sure Pay',
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
        $exists = DB::table('payment_methods')->where('payment_constant','SUREPAY')->first();
        if(isset($exists)){
            $exists->delete();
        }
    }
}
