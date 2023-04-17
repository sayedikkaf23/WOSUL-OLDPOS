<?php

return [

    'config' => [
        'merchant_id' => '',
    ],

    'upload' => [
        'profile' => [
            'default' => '/images/profile_default.jpg',
            'dir' => 'profile/',
            'view_path' => '/storage/profile/',
            'upload_path' => 'storage/profile/'
        ],
        'company' => [
            'company_logo_default' => '/images/logo_word_mark.png',
            'invoice_logo_default' => '/images/logo_invoice_print.png',
            'navbar_logo_default' => '/images/logo_small.png',
            'favicon_default' => '/images/favicon_32_32.png',
            'dir' => 'company/',
            'view_path' => '/storage/company/',
            'upload_path' => 'storage/company/'
        ],
        'category' => [
            'dir' => 'category/',
            'view_path' => '/storage/category/',
            'upload_path' => 'storage/category/'
        ],
        'imports' => [
            'default' => '',
            'dir' => 'imports/',
            'view_path' => '/storage/imports/',
            'upload_path' => 'storage/imports/',
            'user_format' => 'excel_formats/import/user_format.xls',
            'store_format' => 'excel_formats/import/store_format.xls',
            'supplier_format' => 'excel_formats/import/supplier_format.xls',
            'category_format' => 'excel_formats/import/category_format.xls',
            'product_format' => 'excel_formats/import/product_format.xls',
            'ingredient_format' => 'excel_formats/import/ingredient_format.xls'
        ],
        'updates' => [
            'default' => '',
            'dir' => 'updates/',
            'view_path' => '/storage/updates/',
            'upload_path' => 'storage/updates/',
            'user_format' => 'excel_formats/update/user_format.xls',
            'store_format' => 'excel_formats/update/store_format.xls',
            'supplier_format' => 'excel_formats/update/supplier_format.xls',
            'category_format' => 'excel_formats/update/category_format.xls',
            'product_format' => 'excel_formats/update/product_format.xls',
            'ingredient_format' => 'excel_formats/update/ingredient_format.xls'
        ],
        'barcode' => [
            'default' => '',
            'dir' => 'barcode/',
            'view_path' => '/storage/barcode/',
            'upload_path' => 'storage/barcode/'
        ],
        'reports' => [
            'default' => '',
            'dir' => 'reports/',
            'view_path' => '/storage/reports/',
            'upload_path' => 'storage/reports/'
        ],
        'product' => [
            'dir' => 'product/',
            'view_path' => '/storage/product/',
            'upload_path' => 'storage/product/'
        ],
        'order' => [
            'dir' => 'order/',
            'view_path' => '/storage/order/',
            'upload_path' => 'storage/order/'
        ],
        'invoice' => [
            'dir' => 'invoice/',
            'view_path' => '/storage/invoice/',
            'upload_path' => 'storage/invoice/'
        ],
        'invoice_return' => [
            'dir' => 'invoice_return/',
            'view_path' => '/storage/invoice_return/',
            'upload_path' => 'storage/invoice_return/'
        ],
        'device' => [
            'dir' => 'device/',
            'view_path' => '/storage/device/',
            'upload_path' => 'storage/device/'
        ],
    ],

    'unique_code_start' => [
        'user'          => 100,
        'role'          => 100,
        'order'         => 100,
        'category'      => 100,
        'supplier'      => 100,
        'invoice'       => 100,
        'quotation'     => 100,
        'account'       => 100,
        'transaction'   => 100,
        'stock_transfer' => 100,
        'stock_return'  => 100,
        'return_invoice' => 100
    ],

    'demo_notification' => 'This demo version will reset all the data every hour! In case if you got logged out during a session, please login again and continue browsing our demo version',
    'subdomain_name' => 'wosul',
    'default_taxes' => [
        [ 'id' => '1','tax_name' => 'No Tax','percentage' => '0.00','status' => '1','is_visible' => '0','is_default' => '1','created_at' => '2022-07-12 12:23:41','created_by' => '1','updated_at' => '2022-07-12 12:23:41','updated_by' => '1'],
        [ 'id' => '2','tax_name' => 'VAT Tax','percentage' => '15.00','status' => '1','is_visible' => '1','is_default' => '1','created_at' => '2022-07-12 12:23:41','created_by' => '1','updated_at' => '2022-07-12 12:23:41','updated_by' => '1'],
        [ 'id' => '3','tax_name' => 'Zero Tax','percentage' => '0.00','status' => '1','is_visible' => '1','is_default' => '1','created_at' => '2022-07-12 12:23:41','created_by' => '1','updated_at' => '2022-07-12 12:23:41','updated_by' => '1'],
        [ 'id' => '4','tax_name' => 'Exempt Tax','percentage' => '0.00','status' => '1','is_visible' => '1','is_default' => '1','created_at' => '2022-07-12 12:23:41','created_by' => '1','updated_at' => '2022-07-12 12:23:41','updated_by' => '1']
    ]
];
