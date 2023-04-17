<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $table = 'measurements';
    // protected $hidden = ['id'];
    protected $fillable = ['id','slack','measurement_category_id' ,'label', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeActive($query){
        return $query->where('measurements.status', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('measurements.label', 'asc');
    }

    public function scopeSingleValue($query){
        return $query->where('measurement_category_id',0)->orWhere('measurement_category_id',NULL);
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'measurements.status');
            $join->where('master_status.key', '=', 'MEASUREMENT_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'measurements.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'measurements.updated_by');
        });
    }

    public function scopeQoyodUnitJoin($query){
        return $query->leftJoin('qoyod_mesurment_units', function ($join) {
            $join->on('qoyod_mesurment_units.unit_id', '=', 'measurements.id');
        });
    }

    /* relations */
    public function conversions(){
        return $this->hasMany('App\Models\MeasurementConversion', 'from_measurement_id', 'id');
    }

    /* For view files */

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }
    
    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'MEASUREMENT_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function same_categories(){
        return $this->hasMany('App\Models\Measurement','measurement_category_id','measurement_category_id');
    }


}
