<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BonatStoreCounterPointsSettings extends Model
{
    protected $table = 'bonat_store_counter_points_settings';
    protected $hidden = ['id'];
    protected $fillable = ['slack', 'merchant_id', 'store_id', 'counter_id', 'status', 'is_verify', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'bonat_user_points_settings.status');
            $join->where('master_status.key', '=', 'BUPS_SETTING_STATUS');
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'BUPS_SETTING_STATUS');
    }

    public function verify_status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'is_verify')->where('key', 'BONAT_VERIFY_SETTING_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
