<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappVisit extends Model
{
    protected $table = 'whatsapp_page_visit';

    public $fillable = ['ip','tracking_data'];
    
}
