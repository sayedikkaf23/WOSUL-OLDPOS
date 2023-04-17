<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSubscriptions extends Model
{
    protected $table = 'order_subscriptions';
    protected $fillable = ['id', 'order_id','subscription_id','subscription_period','start_date','end_date','amount','status','updated_at','created_at'];


}
