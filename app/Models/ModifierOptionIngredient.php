<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModifierOptionIngredient extends Model
{
    protected $table = 'modifier_option_ingredients';
    // protected $hidden = ['id'];
    protected $fillable = ['id','modifier_option_id','ingredient_id', 'quantity','measurement_id','created_by', 'updated_by', 'created_at', 'updated_at'];

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'modifier_option_ingredients.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'modifier_option_ingredients.updated_by');
        });
    }

    /* For view files */

    // public function ingredient(){
    //     return $this->belongsTo('App\Models\Product','ingredient_id','id');
    // }

    // public function measurement(){
    //     return $this->belongsTo('App\Models\Measurement','measurement_id','id');
    // }

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
