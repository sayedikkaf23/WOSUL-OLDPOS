<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    
    protected $table = 'employee_history';
    protected $fillable = ['email','user_id'];
    public $timestamps = false;
}


