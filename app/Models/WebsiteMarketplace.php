<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class WebsiteMarketplace extends Model
{

    protected $fillable = [
        'slack',
        'title',
        'title_ar',
        'thumb_image',
        'banner_image',
        'short_description',
        'short_description_ar',
        'long_description',
        'long_description_ar',
        'status',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    /* Scopes */

    public function scopeActive($query)
    {
        return $query->where('website_marketplaces.status', 1);
    }

    /* Accessors & Mutators */

    public function getStatusTextAttribute()
    {
        return ($this->status == 1) ? 'Active' : 'Inactive';
    }

    public function getThumbImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/website_marketplace/' . $this->attributes['thumb_image'];
    }

    public function getBannerImagePathAttribute()
    {
        return env('WEBSITE_MEDIA_URL') . '/storage/website_marketplace/' . $this->attributes['banner_image'];
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == 'true') ? 1 : 0;
    }

    /* Joins */
    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'website_marketplaces.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'website_marketplaces.updated_by');
        });
    }

    /* Relations */


    public function specifications()
    {
        return $this->hasMany(WebsiteMarketplaceSpecification::class, 'marketplace_id', 'id');
    }

    public function createdUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'name', 'email', 'phone']);
    }

    public function updatedUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'name', 'email', 'phone']);
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("constants.date_time_format")) : null;
    }
}
