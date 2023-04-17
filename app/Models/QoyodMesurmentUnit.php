<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodMesurmentUnit extends Model
{
    protected $fillable = [
        'store_id',
        'unit_id',
        'qoyod_unit_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
