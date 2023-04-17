<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class InsertReturnStatusOnMasterStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu_exist = DB::select("SELECT id FROM `master_status` WHERE `key` = 'ORDER_STATUS' and value_constant ='RETURN'");
        if(!isset($menu_exist[0])){
            $status = DB::statement("INSERT INTO `master_status` (`key`, `value`, `value_constant`, `label`, `color`, `status`, `created_at`, `updated_at`) VALUES
                                ('ORDER_STATUS',	'6',	'RETURN',	'Return',	'label yellow-label',	1,	now(),	now())");
            if($status == true){
                echo "\n Return Status inserted successfully";
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
