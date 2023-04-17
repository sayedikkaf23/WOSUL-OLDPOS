<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ZidConfig extends Model
{
    protected $table = 'zid_config';

    protected $fillable = ['id', 'client_id', 'client_secret', 'created_by', 'created_at'];

    public $timestamps = true;
}
