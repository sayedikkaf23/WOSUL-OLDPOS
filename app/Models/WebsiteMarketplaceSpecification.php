<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class WebsiteMarketplaceSpecification extends Model
{

    protected $fillable = [
        'name',
        'name_ar',
        'value',
        'value_ar',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    /* Scopes */

    public function scopeActive($query)
    {
        return $query->where('website_marketplaces.status', 1);
    }

    /* Relations */

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("constants.date_time_format")) : null;
    }
}
