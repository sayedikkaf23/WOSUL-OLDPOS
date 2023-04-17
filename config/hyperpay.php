<?php

return [
    'sandboxMode' => env('SANDBOX_MODE', true),

    'entityIdMada' => env('ENTITY_ID_MADA'),

    'entityIdApplePay' =>  env('ENTITY_ID_APPLE_PAY'),

    'entityId' => env('ENTITY_ID'),

    'access_token' => env('ACCESS_TOKEN'),

    'testMode'=>'INTERNAL',

    'currency' => env('CURRENCY', 'SAR'),

    'redirect_url' => '/finalize',

    'model' => env('PAYMENT_MODEL', class_exists(App\Models\Merchant::class) ? App\Models\Merchant::class : App\Merchant::class),
    /**
     * if you are using multi-tenant you can create a new model like.
     *
     * use Hyn\Tenancy\Traits\UsesTenantConnection;
     * use Devinweb\LaravelHyperpay\Models\Transaction as ModelsTransaction;
     * class Transaction extends ModelsTransaction {
     *
     *  use UsesTenantConnection;
     *
     * }
     */
    'transaction_model' => 'App\Models\HyperPayTransaction',
    'notificationUrl' => '/hyperpay/webhook',
];