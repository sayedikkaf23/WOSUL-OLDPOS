<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdfapayTransaction extends Model
{
    protected $table = 'edfapay_transactions';
    protected $hidden = ['id'];
    protected $fillable = [
        'order_id',
        'order_json',
        'transaction_id',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
