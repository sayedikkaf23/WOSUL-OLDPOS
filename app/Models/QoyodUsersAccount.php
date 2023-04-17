<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QoyodUsersAccount extends Model
{
    protected $fillable = [
        'store_id',
        'account_id',
        'qoyod_account_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}
