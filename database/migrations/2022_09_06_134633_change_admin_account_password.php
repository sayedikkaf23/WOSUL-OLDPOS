<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class ChangeAdminAccountPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::where('email','admin@wosul.sa')->update(['password'=>'$2y$10$DRTO/InN0Dt5uDrZS2o6SOsQZ7F8h5ElyA2h4dpbZd6r9XsRhVE3.']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('email','admin@wosul.sa')->update(['password'=>'$2y$10$zGwGQZ44dWsNuJhYBqDAEuWNbhVLDcOlAG4aXDeCrJsRphghXRnmG']);
    }
}
