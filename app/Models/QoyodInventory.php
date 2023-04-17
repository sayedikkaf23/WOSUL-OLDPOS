<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodInventory extends Model
{
    protected $table = 'qoyod_inventory';
    protected $fillable = [
        'store_id',
        'qoyod_inventory_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
