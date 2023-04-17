<?php

namespace App\Models;

use Carbon\Carbon;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Device extends Model
{
    use SoftDeletes;

    protected $table = 'devices';

    protected $hidden = ['id'];

    protected $fillable = [
        'slack',
        'title',
        'title_ar',
        'description',
        'description_ar',
        'image',
        'price',
        'currency',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeActive($query)
    {
        return $query->where('devices.status', 1);
    }

    public function getImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/device/' . $this->attributes['image'];
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'devices.status');
            $join->where('master_status.key', '=', 'DEVICE_STATUS');
        });
    }

    /* For view files */
    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'DEVICE_STATUS');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }
}
