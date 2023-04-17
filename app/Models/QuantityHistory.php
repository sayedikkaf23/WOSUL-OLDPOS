<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuantityHistory extends Model
{
    protected $table = 'quantity_history';
    protected $hidden = ['id'];
    protected $fillable = ['slack', 'product_id', 'store_id', 'type', 'action', 'quantity', 'table_id', 'date', 'created_at','created_by'];

    public $timestamps = false;
    
    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
