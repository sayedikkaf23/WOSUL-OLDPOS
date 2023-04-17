<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class ComboGroup extends Model
{
    protected $table = 'combo_groups';

    protected $fillable = ['id','slack','store_id','parent','name','created_by', 'updated_by'];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeSortNameAsc($query){
        return $query->orderBy('combo_groups.name', 'asc');
    }
    
    public function scopeParentCategories($query){
        return $query->where('combo_groups.parent', null);
    }
    
    public function scopeSubCategories($query){
        return $query->where('combo_groups.parent', '!=' ,null);
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'combo_groups.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'combo_groups.updated_by');
        });
    }

    /* For view files */

    public function parent_group(){
        return $this->hasOne('App\Models\ComboGroup','id','parent');
    }

    public function sub_group(){
        return $this->hasMany('App\Models\ComboGroup','parent','id');
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
