<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class OrderProductModifierOption extends Model
{
    protected $table = 'order_product_modifier_options';
    protected $hidden = ['id'];
    protected $fillable = ['slack','order_product_id', 'modifier_option_id','modifier_option_price', 'created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new StoreScope);
    }
    
    public function scopeActive($query){
        return $query->where('order_product_modifier_options.status', 1);
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'order_product_modifier_options.status');
            $join->where('master_status.key', '=', 'PRODUCT_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'order_product_modifier_options.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'order_product_modifier_options.updated_by');
        });
    }

    /* For view files */

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
    
    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'PRODUCT_STATUS');
    }
    
    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function parseDateOnly($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_format")):null;
    }
    
}
