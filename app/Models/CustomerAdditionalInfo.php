<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAdditionalInfo extends Model
{
    protected $table = 'customer_additional_info';
    protected $hidden = ['id'];
    protected $fillable = ['slack', 'customer_id', 'title', 'description'];
}
