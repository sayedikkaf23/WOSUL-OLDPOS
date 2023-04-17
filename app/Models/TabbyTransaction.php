<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabbyTransaction extends Model
{
    protected $table = 'tabby_transactions';
    protected $hidden = ['id'];
    protected $fillable = ['merchant_id','payment_id', 'status', 'amount', 'currency', 'trackable_data','created_at', 'updated_at'];

}
