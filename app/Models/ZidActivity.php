<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ZidActivity extends Model
{
    protected $table = 'zid_activity';
    // protected $hidden = ['created_by'];
    protected $fillable = ['id', 'zid_action_id', 'store_id', 'remark', 'created_by', 'created_at'];

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
}
