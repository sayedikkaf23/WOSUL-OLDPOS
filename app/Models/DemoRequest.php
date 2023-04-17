<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    public $fillable = ['first_name', 'last_name', 'contact_number', 'email', 'city', 'domain'];
}
