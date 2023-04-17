<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantityAdjustmentItems extends Model
{
    protected $table = 'quantity_adjustment_items';
    protected $fillable = ['id', 'quantity_adjustment_id','product_id','quantity','created_at','updated_at'];
    public function product()
    {
        return $this->hasOne('App\Models\Product','id','product_id');
    }
}