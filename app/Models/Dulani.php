<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dulani extends Model
{
    protected $table = 'dulani';

    public $fillable = ['full_name', 'email', 'phone_number', 'created_at','discount'];

    public $timestamps = false;
}
