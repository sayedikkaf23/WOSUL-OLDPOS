<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantMenu extends Model
{
    protected $hidden = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
