<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodCustomer extends Model
{
    protected $fillable = [
        'store_id',
        'customer_id',
        'qoyod_customer_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
