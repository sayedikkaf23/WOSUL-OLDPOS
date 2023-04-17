<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promoter extends Model
{
    protected $table = 'promoter';

    public $fillable = ['full_name', 'email', 'phone_number'];

    public $timestamps = false;
}
