<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class Combo extends Model
{
    protected $table = 'combos';

    protected $fillable = ['id','slack','store_id','category_id','name', 'is_discount_enabled', 'discount_type', 'discount_value','status', 'created_by', 'updated_by'];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeActive($query){
        return $query->where('combos.status', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('combos.name', 'asc');
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'combos.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'combos.updated_by');
        });
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'products.status');
            $join->where('master_status.key', '=', 'COMBO_STATUS');
        });
    }


    /* For view files */

    public function sizes(){ 
        return $this->hasMany('App\Models\ComboSize','combo_id','id');
    }
    
    public function groups(){ 
        return $this->hasMany('App\Models\ComboGroup','store_id','store_id');
    }
    
    public function category(){ 
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function products(){ 
        return $this->hasMany('App\Models\ComboProduct','combo_id','id');
    }

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
    
    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'TAX_CODE_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
