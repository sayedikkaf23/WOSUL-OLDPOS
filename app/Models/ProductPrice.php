<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\StoreScope;

class ProductPrice extends Model
{

    protected $fillable = [
        'slack',
        'product_id',
        'price_id',
        'sale_amount_excluding_tax',
        'sale_amount_including_tax',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    /* For view files */

    public function price(){
        return $this->belongsTo('App\Models\Price');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

}
