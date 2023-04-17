<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    
    protected $table = 'customers';
    protected $hidden = ['id'];
    protected $fillable = ['slack', 'customer_type', 'name', 'email', 'phone', 'address', 'tax_number', 'website', 'organization_name', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','building_number', 'street_name', 'district', 'country_id', 'city', 'other_seller_id','pincode'];

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeSkipDefaultCustomer($query){
        return $query->where('customer_type', '!=', 'DEFAULT');
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'customers.status');
            $join->where('master_status.key', '=', 'CUSTOMER_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'customers.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'customers.updated_by');
        });
    }

    public function scopeQoyodCustomerJoin($query){
        return $query->leftJoin('qoyod_customers', function ($join) {
            $join->on('qoyod_customers.customer_id', '=', 'customers.id');
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'SUPPLIER_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function add_infos(){
        return $this->hasMany('App\Models\CustomerAdditionalInfo', 'customer_id', 'id');
    }
}
