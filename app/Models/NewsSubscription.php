<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NewsSubscription extends Model
{

    protected $table = 'newsletter_subscription';
    protected $hidden = [
        'id'
    ];

    protected $fillable = [ 
        'slack',
        'email',
        'status',
        'created_by',
        'updated_by',
        'created_on',
        'updated_on'
    ];


    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'newsletter_subscription.status');
            $join->where('master_status.key', '=', 'NEWS_SUBSCRIPTION_STATUS');
        });
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'NEWS_SUBSCRIPTION_STATUS');
    }
}
