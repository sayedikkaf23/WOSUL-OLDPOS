<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModifierOption extends Model
{
    protected $table = 'modifier_options';
    // protected $hidden = ['id'];
    protected $fillable = ['id','modifier_id','slack', 'label','price','status', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeActive($query){
        return $query->where('modifier_options.status', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('modifier_options.label', 'asc');
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'modifier_options.status');
            $join->where('master_status.key', '=', 'MODIFIER_OPTION_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'modifier_options.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'modifier_options.updated_by');
        });
    }

    /* For view files */

    public function ingredients(){
        return $this->hasMany('App\Models\ModifierOptionIngredient');
    }

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
    
    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'MODIFIER_OPTION_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
