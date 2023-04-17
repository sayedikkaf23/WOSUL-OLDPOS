<?php

namespace App\Models;

use Carbon\Carbon;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantSubscription extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'subscription_id',
        'merchant_id',
        'start_date',
        'end_date',
        'status',
        'is_trial'
    ];
}
