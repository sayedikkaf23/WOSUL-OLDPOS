<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WebsiteService extends Model
{

    public function scopeActive($query)
    {
        return $query->where('website_services.status', 1);
    }

    public function getImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/website_service/' . $this->attributes['image'];
    }
}
