<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'synczid/product_update_webhook',
        'synczid/order_create_webhook',
        'survey/add',
        'en/qr',
        'ar/qr',
        'campaign/add',
        'en/campaign',
        'ar/campaign',
        'registration_form/add',
        'en/registration_form',
        'ar/registration_form',
        '*/merchant/register*',
        '*/authenticate',
        'run_alter_query_all_db',
        'expresspay/notification',
        '/api/webhook'
    ];
}