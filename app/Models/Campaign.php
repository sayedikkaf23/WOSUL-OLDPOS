<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaign';

    public $fillable = ['full_name', 'email', 'phone_number','pt'];

    public $timestamps = false;
}
