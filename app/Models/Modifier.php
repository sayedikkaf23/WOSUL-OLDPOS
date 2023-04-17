<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\User as UserModel;

class Modifier extends Model
{
    protected $table = 'modifiers';
    // protected $hidden = ['id'];
    protected $fillable = ['id','modifier_id','slack', 'label', 'is_multiple' ,'status', 'created_by', 'updated_by', 'created_at', 'updated_at']; 

//    protected static function boot()
//    {
//        parent::boot();
//    }

    public function scopeActive($query){
        return $query->where('modifiers.status', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('modifiers.label', 'asc');
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'modifiers.status');
            $join->where('master_status.key', '=', 'MODIFIER_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'modifiers.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'modifiers.updated_by');
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'MODIFIER_STATUS');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function modifier_options(){
        return $this->hasMany('App\Models\ModifierOption', 'modifier_id', 'id');
    }

    public function product_modifiers()
    {
        return $this->hasMany('App\Models\ProductModifier', 'modifier_id', 'id');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }


}
