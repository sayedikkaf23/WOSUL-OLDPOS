<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class AccTransaction extends Model
{
    protected $table = 'acc_transaction';
    protected $fillable = ['VNo', 'Vtype', 'VDate', 'COAID', 'Narration', 'Debit', 'Credit', 'transaction_id', 'transaction_receipt_link','order_slack', 'IsPosted', 'CreateBy', 'CreateDate', 'IsAppove'];
    public $timestamps = false;
}
