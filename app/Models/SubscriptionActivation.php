<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionActivation extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $table="merchant_subscriptions";

    protected $fillable = [
        'subscription_id',
        'merchant_id',
        'start_date',
        'end_date',
        'status',
    ];
}