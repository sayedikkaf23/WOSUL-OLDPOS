<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class ProductModifier extends Model
{
    protected $table = 'product_modifiers';
    protected $hidden = ['id'];
    protected $fillable = ['slack','product_id', 'modifier_id','status', 'created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new StoreScope);
    }
    
    public function scopeActive($query){
        return $query->where('product_modifiers.status', 1);
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'product_modifiers.status');
            $join->where('master_status.key', '=', 'PRODUCT_STATUS');
        });
    }


    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'product_modifiers.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'product_modifiers.updated_by');
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

    public function modifier()
    {
        return $this->belongsTo('App\Models\Modifier', 'modifier_id');
    }
    
}
