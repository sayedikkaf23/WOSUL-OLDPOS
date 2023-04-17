<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ZidStore extends Model
{
    protected $table = 'zid_store';
    // protected $hidden = ['created_by'];
    protected $fillable = ['id', 'authorization', 'access_token', 'expires_in', 'created_by', 'created_at'];

    public $timestamps = false;

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'stores.status');
            $join->where('master_status.key', '=', 'SYNC_ZID_STATUS');
        });
    }

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'stores.created_by');
        });
    }

    // public function store()
    // {
    //     return $this->belongsTo('App\Models\Store', 'id', 'store_id');
    // }
}
