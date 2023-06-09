<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class BusinessRegister extends Model
{
    protected $table = 'business_registers';
    protected $hidden = ['id', 'store_id'];
    protected $fillable = ['slack', 'store_id', 'user_id', 'billing_counter_id', 'current_register', 'opening_date', 'closing_date', 'opening_amount', 'closing_amount', 'manual_drawer_opens', 'credit_card_slips', 'cheques', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeUser($query){
        return $query->leftJoin('users AS user', function ($join) {
            $join->on('user.id', '=', 'business_registers.user_id');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'business_registers.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'business_registers.updated_by');
        });
    }

    /* For view files */
    public function store()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id')->select(['name']);
    }

    public function billing_counter()
    {
        return $this->hasOne('App\Models\BillingCounter', 'id', 'billing_counter_id')->select(['counter_name']);
    }

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select(['slack', 'fullname', 'email', 'user_code']);
    }

     public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
