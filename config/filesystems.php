<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'profile' => [
            'driver' => 'local',
            'root' => storage_path('app/public/profile'),
            'url' => env('APP_URL') . '/storage/profile',
            'visibility' => 'public',
        ],

        'imports' => [
            'driver' => 'local',
            'root' => storage_path('app/public/imports'),
            'url' => env('APP_URL') . '/storage/imports',
            'visibility' => 'public',
        ],

        'updates' => [
            'driver' => 'local',
            'root' => storage_path('app/public/updates'),
            'url' => env('APP_URL') . '/storage/updates',
            'visibility' => 'public',
        ],

        'reports' => [
            'driver' => 'local',
            'root' => storage_path('app/public/reports'),
            'url' => env('APP_URL') . '/storage/reports',
            'visibility' => 'public',
        ],

        'barcode' => [
            'driver' => 'local',
            'root' => storage_path('app/public/barcode'),
            'url' => env('APP_URL') . '/storage/barcode',
            'visibility' => 'public',
        ],

        'category' => [
            'driver' => 'local',
            'root' => storage_path('app/public/category'),
            'url' => env('APP_URL') . '/storage/category',
            'visibility' => 'public',
        ],

        'company' => [
            'driver' => 'local',
            'root' => storage_path('app/public/company'),
            'url' => env('APP_URL') . '/storage/company',
            'visibility' => 'public',
        ],

        'store' => [
            'driver' => 'local',
            'root' => storage_path('app/public/store'),
            'url' => env('APP_URL') . '/storage/store',
            'visibility' => 'public',
        ],

        'product' => [
            'driver' => 'local',
            'root' => storage_path('app/public/product'),
            'url' => env('APP_URL') . '/storage/product',
            'visibility' => 'public',
        ],

        'invoice' => [
            'driver' => 'local',
            'root' => storage_path('app/public/invoice'),
            'url' => env('APP_URL') . '/storage/invoice',
            'visibility' => 'public',
        ],

        'order' => [
            'driver' => 'local',
            'root' => storage_path('app/public/order'),
            'url' => env('APP_URL') . '/storage/order',
            'visibility' => 'public',
        ],

        'tax_report' => [
            'driver' => 'local',
            'root' => storage_path('app/public/tax_report'),
            'url' => env('APP_URL') . '/storage/tax_report',
            'visibility' => 'public',
        ],

        'invoice_return' => [
            'driver' => 'local',
            'root' => storage_path('app/public/invoice_return'),
            'url' => env('APP_URL') . '/storage/invoice_return',
            'visibility' => 'public',
        ],

        'device' => [
            'driver' => 'local',
            'root' => storage_path('app/public/device'),
            'url' => env('APP_URL') . '/storage/device',
            'visibility' => 'public',
        ],
        'crm_ticket' => [
            'driver' => 'local',
            'root' => '../../'.env('CRM_URL').'/public/storage/support_ticket',
            /*'root' => '../crm/public/storage/support_ticket',*/
            'url' => env('CRM_URL') . '/storage/support_ticket',
            /*'visibility' => 'public',*/
        ],

    ],

];
