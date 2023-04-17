<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class InvoiceCharge extends Model
{
    protected $table = 'invoice_charges';
    protected $hidden = ['id'];
    protected $fillable = ['slack','invoice_id','name','amount','created_at', 'updated_at'];
    
}
