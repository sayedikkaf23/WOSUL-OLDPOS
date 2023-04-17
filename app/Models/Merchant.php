<?php

namespace App\Models;

use Carbon\Carbon;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;

class Merchant extends Model
{
    use ManageUserTransactions;
    use SoftDeletes;

    protected $table = 'merchants';

    protected $hidden = ['id'];

    protected $fillable = [
        'slack',
        'subscription_id',
        'name',
        'phone_number',
        'email',
        'password',
        'company_name',
        'company_url',
        'address',
        'referral_code',
        'recommendation',
        'user_type',
        'merchant_business',
        'other_merchant_business',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeActive($query)
    {
        return $query->where('merchants.status', 1);
    }

    public function scopeSortLabelAsc($query)
    {
        return $query->orderBy('merchants.label', 'asc');
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'merchants.status');
            $join->where('master_status.key', '=', 'MERCHANT_STATUS');
        });
    }



    /* For view files */

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'MERCHANT_STATUS');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

    public static function user_types($id = null){
        $array = [
            1 => "Real Client",
            2 => "Test",
        ];

        return $id ? $array[$id] : $array;
    }
    public static function merchant_business($id = null){
        $array = [
            1 => "Restaurants",
            2 => "Caffe",
            3 => "Clothes",
            4 => "Makeup Accessories",
            5 => "other",
        ];

        return $id ? $array[$id] : $array;
    }
}
