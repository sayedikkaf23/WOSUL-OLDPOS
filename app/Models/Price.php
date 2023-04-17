<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\StoreScope;

class Price extends Model
{

    protected $fillable = [
        'slack',
        'price_code',
        'store_id',
        'name',
        'name_ar',
        'status',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);

    }

    public function scopeActive($query)
    {
        return $query->where('prices.status', 1);
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'prices.status');
            $join->where('master_status.key', '=', 'SUBSCRIPTION_STATUS');
        });
    }

    // Eloquent Relations

    /* For view files */

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'PRICE_STATUS');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

}
