<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignVisit extends Model
{
    protected $table = 'campaign_visit';

    public $fillable = ['pt', 'ip'];

    public $timestamps = false;
}
