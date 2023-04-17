<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ComboSize extends Model
{
    protected $table = 'combo_sizes';

    protected $fillable = ['id','slack','combo_id', 'size_name', 'created_by', 'updated_by'];


    protected static function boot()
    {
        parent::boot();
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('combo_sizes.size_name', 'asc');
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'combo_sizes.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'combo_sizes.updated_by');
        });
    }

    /* For view files */

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
    

    public function comboGroup(){
        return $this->hasMany('App\Models\comboGroups', 'combo_size_id', 'id');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
