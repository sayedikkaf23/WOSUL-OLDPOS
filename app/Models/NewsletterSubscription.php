<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class NewsletterSubscription extends Model
{
    use SoftDeletes;

    protected $table = 'newsletter_subscription';
    protected $hidden = ['id'];

    protected $fillable = [
        'slack',
        'email',
        'status',
        'created_by',
        'updated_by',
        'created_on',
        'updated_on',
    ];


    function insert($data)
    {
        DB::table('newsletter_subscription')->insert($data);
    }
}
