<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodProduct extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'qoyod_product_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
