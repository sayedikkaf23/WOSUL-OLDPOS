<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WebsiteGallery extends Model
{
    protected $table = 'website_gallery';

    public function scopeActive($query)
    {
        return $query->where('website_gallery.status', 1);
    }

    public function getImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/website_gallery/' . $this->attributes['image'];
    }
}
