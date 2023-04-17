<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MobileCashier extends Model
{
    protected $fillable = ['type','status','store_id','device_id','response_data','device_type'];

    protected $casts = [
        'response_data' => 'json'
    ];

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

    public function parseDateOnly($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_format")) : null;
    }

}
