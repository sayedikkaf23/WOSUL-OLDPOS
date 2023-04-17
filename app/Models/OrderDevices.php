<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDevices extends Model
{
    protected $table = 'order_devices';
    protected $fillable = ['id', 'order_id','device_id','amount','status','updated_at','created_at'];
}
