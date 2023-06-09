<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SettingApp extends Model
{
    protected $table = 'setting_app';
    protected $hidden = [];
    protected $fillable = ['company_name', 'app_date_time_format', 'app_date_format', 'company_logo', 'invoice_print_logo', 'navbar_logo', 'favicon', 'qoyod_api_key', 'updated_by','tax_setting_updated_at','qoyod_last_sync_time','expresspay_merchant_key','expresspay_password','expresspay_sms_template'];


    /* For view files */

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by') ->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
