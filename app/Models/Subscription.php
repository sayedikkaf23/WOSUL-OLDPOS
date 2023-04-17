<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{

    use SoftDeletes;
    protected $table = 'subscriptions';
    protected $hidden = [
        // 'id'
    ];

    protected $fillable = [
        'slack',
        'title',
        'title_ar',
        'short_description',
        'short_description_ar',
        'plan_tenure',
        'currency',
        'amount',
        'discount',
        'discount_description',
        'discount_description_ar',
        'url',
        'url_ar',
        'color_code',
        'is_featured',
        'is_live',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeActive($query)
    {
        return $query->where('subscriptions.status', 1);
    }

    public function scopeIsLive($query)
    {
        return $query->where('is_live', 1);
    }

    public function scopeSortTitleAsc($query)
    {
        return $query->orderBy('subscriptions.title', 'asc');
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'subscriptions.status');
            $join->where('master_status.key', '=', 'SUBSCRIPTION_STATUS');
        });
    }

    // Eloquent Relations
    public function subscription_features()
    {
        return $this->hasMany('App\Models\SubscriptionFeature');
    }

    /* For view files */

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'SUBSCRIPTION_STATUS');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class, 'subscription_id', 'id');
    }
}
