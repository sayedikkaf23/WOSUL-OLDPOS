<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodVendor extends Model
{
    protected $fillable = [
        'store_id',
        'vendor_id',
        'qoyod_vendor_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
