<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class SecUserAccess extends Model
{
    
    protected $table = 'sec_user_access_tbl';
    protected $fillable = ['fk_role_id','fk_user_id'];
    public $timestamps = false;
}


