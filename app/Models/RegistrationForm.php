<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    protected $table = 'registration_form';

    public $fillable = [
        'type',
        'name',
        'email',
        'phone_number',
        'business_type',
        // 'number_of_branch'
    ];

    public $timestamps = true;
}
