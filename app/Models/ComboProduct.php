<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    protected $table = 'combo_products';
    
    protected $fillable = [
        'id',
        'slack',
        'combo_id',
        'combo_size_id',
        'combo_group_id',
        'product_id',
        'measurement_id',
        'quantity',
        'price',
        'price_after_discount',
        'created_by',
        'updated_by'
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'combo_products.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'combo_products.updated_by');
        });
    }

    /* For view files */

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }
    
    public function measurement(){
        return $this->hasOne('App\Models\Measurement','id','measurement_id');
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
