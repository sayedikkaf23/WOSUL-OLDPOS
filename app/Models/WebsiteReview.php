<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WebsiteReview extends Model
{

    public function scopeActive($query)
    {
        return $query->where('website_reviews.status', 1);
    }

    public function getImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/website_review/' . $this->attributes['image'];
    }

    public function getRatingPercentageAttribute()
    {
        return $this->attributes['rating'] * 100 / 5;
    }
}
