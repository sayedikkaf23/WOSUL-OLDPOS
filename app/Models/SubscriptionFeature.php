<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeature extends Model
{
    
   protected $hidden = [
        'id'
    ];

    protected $fillable = [ 
        'subscription_id',
        'title',
        'title_ar'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeSortTitleAsc($query){
        return $query->orderBy('subscription_features.title', 'asc');
    }

    /* For view files */

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'SUBSCRIPTION_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

}
