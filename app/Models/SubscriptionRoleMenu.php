<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRoleMenu extends Model
{
    protected $table = 'subscription_role_menus';
    protected $hidden = ['id'];
    protected $fillable = ['subscription_id', 'menu_id', 'created_by', 'updated_by'];

    public function subscription(){
        return $this->belongsTo('App\Models\Subscription', 'id', 'subscription_id');
    }

    public function subscription_menu(){
        return $this->belongsTo('App\Models\SubscriptionMenu', 'id', 'menu_id');
    }
}