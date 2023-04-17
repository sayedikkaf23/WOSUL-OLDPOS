<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class Expresspay extends Model
{
    protected $table = 'expresspay';
    protected $hidden = ['id',];
    protected $fillable = ['slack', 'bill_to', 'bill_to_id', 'amount' , 'payment_link', 'invoice_link' , 'data_json', 'response_json', 'status', 'paid_it', 'created_at', 'updated_at'];

    public function scopeActive($query){
        return $query->where('users.status', 1);
    }

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'EXPRESSPAY_STATUS');
    }

    /* For view files */

    public function parseDateOnly($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_format")):null;
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
