<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\ZidAction as ZidActionModel;
use App\Models\ZidActivity as ZidActivityModel;

class ZidAction extends Model
{
    protected $table = 'zid_action';
    protected $hidden = ['created_by', 'updated_by'];
    protected $fillable = ['id', 'key', 'title', 'action', 'endpoint'];

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'stores.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'stores.updated_by');
        });
    }

    public function last_sync_at()
    {
        return $this->hasMany(ZidActivityModel::class, 'zid_action_id', 'id')->latest()->take(1);
    }
}
