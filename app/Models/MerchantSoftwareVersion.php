<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantSoftwareVersion extends Model
{
    protected $hidden = ['id'];

    protected $fillable = ['merchant_id', 'os', 'unique_deviceid', 'version', 'device_token'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
