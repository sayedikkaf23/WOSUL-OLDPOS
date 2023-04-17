<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantityAdjustments extends Model
{
    protected $table = 'quantity_adjustments';
    protected $fillable = ['id', 'reference','store_id','action','reason','status','created_at','submitted_at','created_by','updated_at','slack'];
    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
}