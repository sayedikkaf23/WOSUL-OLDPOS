<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageReportModel extends Model
{
    protected $table  = "order_damage";
    protected $fillable = ["product","branch","store_id","branch_reference","order_type","added_by","order_reference","time","quantity","amount","reason","order_product_modifier_options","created_at","updated_at",'product_slack','name'];
    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.user_code', '=', 'order_damage.added_by');
        });
    }

    public function user(){
        return $this->hasOne('App\Models\User', 'user_code', 'added_by');
    }

    public function store(){
        return $this->hasOne('App\Models\Store', 'store_code', 'branch_reference');
    }
}