<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionMenu extends Model
{
    protected $table = 'subscription_menus';
    protected $hidden = ['id'];

    public function scopeActive($query){
        return $query->where('status', 1);
    }
}