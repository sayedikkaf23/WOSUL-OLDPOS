<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TaxName extends Model
{
    protected $fillable = [	'tax_name','percentage','created_by','updated_by'];

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeSortIdAsc($query){
        return $query->orderBy('tax_names.id', 'asc');
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'tax_names.status');
            $join->where('master_status.key', '=', 'TAX_NAME_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'tax_names.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'tax_names.updated_by');
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'TAX_CODE_STATUS');
    }

    public function scopeTaxCodeType($query){
        return $query->leftJoin('tax_code_type', function ($join) {
            $join->on('tax_code_type.tax_name_id ', '=', 'tax_names.id');
        });
    }

    public function tax_name_ids(){
        return $this->hasMany('App\Models\TaxcodeType', 'tax_name_id', 'id');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
