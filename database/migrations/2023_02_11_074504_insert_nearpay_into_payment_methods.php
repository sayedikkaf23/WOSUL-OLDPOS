<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class InsertNearpayIntoPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('payment_methods')->where('payment_constant','NEARPAY')->first();

        if(!isset($exists)){
            DB::table('payment_methods')->insert([
                'slack' => 'wdgH8nPXATLB0VbrShlP42nLJ',
                'payment_constant' => 'NEARPAY',
                'label' => 'Near Pay',
                'description' => 'Near Pay',
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
        $exists = DB::table('payment_methods')->where('payment_constant','NEARPAY')->first();
        if(isset($exists)){
            $exists->delete();
        }
    }
}
