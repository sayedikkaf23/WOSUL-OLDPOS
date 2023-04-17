<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';

    public $fillable = ['full_name', 'email', 'phone_number'];

    public $timestamps = false;
}
