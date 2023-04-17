<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Scopes\StoreScope;

use Illuminate\Database\Eloquent\Model;

class MerchantSupportTicket extends Model
{
    protected $connection= 'mysql_admin';
    protected $table = 'merchant_support_tickets';

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new StoreScope);
        // static::addGlobalScope(new CategoryStoreScope);
    }

    /* Accessors and Mutators */

    /* Joins */

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'merchant_support_tickets.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'merchant_support_tickets.updated_by');
        });
    }


    /* For view files */
    
    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'name', 'email']);
    }


    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

}
