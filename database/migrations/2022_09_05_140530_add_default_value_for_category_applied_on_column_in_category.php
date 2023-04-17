<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Store;
use App\Models\Category;

class AddDefaultValueForCategoryAppliedOnColumnInCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {

            $stores = Store::pluck('id');
            if(isset($stores)){
                $store_ids = implode(",",$stores->toArray());
                Category::where('parent',null)->update(['parent'=>0]);
                Category::where('category_applied_on',null)
                ->orWhere('category_applicable_stores',null)
                ->update(['category_applied_on'=>'all_stores','category_applicable_stores'=>$store_ids]);
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            //
        });
    }
}
