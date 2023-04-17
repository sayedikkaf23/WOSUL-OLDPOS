<?php

namespace App\Console\Commands;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DBCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {database?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used for creating new database tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $db = $this->argument('database');
        $merchants = DB::table('merchants')
        ->select('company_url')
        ->when($db != '', function($query) use($db)  {
            $query->where('company_url',$db);
        })
        ->get();

        $progressBar = $this->output->createProgressBar($merchants->count());

        $progressBar->start();

        $data = [];

        $count = 0;
        foreach($merchants as $merchant){
            
            $db_name =  Str::lower($merchant->company_url).'_wosul';
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$db_name]);
           
            if (!empty($db)) {

                config(["database.connections.mysql.database" => $db_name]);
                DB::purge('mysql');
                DB::reconnect('mysql');

                /* Alter: to update old quotation table structure with new one, skip if already updated */
                if(!Schema::hasColumn('quotation_products','show_description_in')){

                    DB::statement('DROP TABLE IF EXISTS `quotations`');
                    DB::statement('CREATE TABLE `quotations` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `terms` text COLLATE utf8mb4_unicode_ci,
                        `store_id` int(11) NOT NULL,
                        `quotation_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `quotation_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `quotation_date` date DEFAULT NULL,
                        `quotation_due_date` date DEFAULT NULL,
                        `subject` text COLLATE utf8mb4_unicode_ci,
                        `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `bill_to_id` int(11) NOT NULL,
                        `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_address` text COLLATE utf8mb4_unicode_ci,
                        `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `tax_option_id` int(11) DEFAULT NULL,
                        `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `subtotal_including_tax` decimal(10,2) NOT NULL,
                        `total_discount_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `total_tax_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `shipping_charge` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `packing_charge` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `total_order_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `notes` text COLLATE utf8mb4_unicode_ci,
                        `status` tinyint(4) NOT NULL DEFAULT "1",
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
                    
                    DB::statement('DROP TABLE IF EXISTS `quotation_products`;');

                    DB::statement('CREATE TABLE `quotation_products` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `quotation_id` int(11) NOT NULL,
                        `product_id` int(11) DEFAULT NULL,
                        `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `description` text COLLATE utf8mb4_unicode_ci,
                        `show_description_in` int(11) DEFAULT "0",
                        `quantity` decimal(8,2) NOT NULL DEFAULT "0.00",
                        `measurement_id` int(11) DEFAULT NULL,
                        `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `discount_percentage` decimal(8,2) NOT NULL DEFAULT "0.00",
                        `tax_code_id` int(11) DEFAULT NULL,
                        `tax_percentage` decimal(8,2) NOT NULL DEFAULT "0.00",
                        `discount_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `tax_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `tax_components` text COLLATE utf8mb4_unicode_ci,
                        `total_amount` decimal(13,2) NOT NULL DEFAULT "0.00",
                        `status` tinyint(4) NOT NULL DEFAULT "1",
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `product_type` int(11) DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                    DB::update('ALTER TABLE `quotations`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `quotations_slack_unique` (`slack`),
                    ADD UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
                    ADD KEY `quotation_indexes` (`store_id`,`quotation_number`,`quotation_reference`,`bill_to`,`bill_to_id`,`status`);');
                    
                    DB::update('ALTER TABLE `quotation_products`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `quotation_products_slack_unique` (`slack`),
                    ADD KEY `quotation_products_quotation_id_status_index` (`quotation_id`,`status`);');
                    
                    DB::update('ALTER TABLE `quotations`
                    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;');

                    DB::update('ALTER TABLE `quotation_products`
                    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                    ');
                }

                if(!Schema::hasTable('measurements')){
                    
                    DB::statement("CREATE TABLE `measurements` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `measurement_category_id` int(11) NOT NULL,
                        `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT 1,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `measurements_slack_unique` (`slack`),
                        KEY `measurements_status_index` (`status`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                    
                }

                if(!Schema::hasTable('measurement_categories')){
                    DB::statement("CREATE TABLE `measurement_categories` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT 1,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `measurement_categories_slack_unique` (`slack`),
                        KEY `measurement_categories_status_index` (`status`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                }

                if(Schema::hasTable('main_category')){
                    DB::statement("DROP TABLE `main_category`;");
                }

                if(Schema::hasTable('synczid')){ // inverse check if exists
                    
                    DB::statement('DROP TABLE IF EXISTS `synczid`');
                    if(Schema::hasTable('synczid_store')){
                        DB::statement('DROP TABLE IF EXISTS `synczid_store`');
                    }
                    
                    DB::statement("CREATE TABLE `zid_store` (
                        `id` int(11) NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `zid_store_id` varchar(20) DEFAULT NULL,
                        `authorization` text NOT NULL,
                        `access_token` text NOT NULL,
                        `expires_in` varchar(50) NOT NULL,
                        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
                      
                      DB::update("ALTER TABLE `zid_store` ADD PRIMARY KEY (`id`);");
                      
                      DB::update("ALTER TABLE `zid_store` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
                      
                      DB::statement("CREATE TABLE `zid_config` (
                        `id` int(11) NOT NULL,
                        `client_id` text NOT NULL,
                        `client_secret` text NOT NULL,
                        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                      
                      DB::update("ALTER TABLE `zid_config` ADD PRIMARY KEY (`id`);");
                      
                      DB::update("ALTER TABLE `zid_config` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
                      
                      DB::statement("CREATE TABLE `zid_action` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `action` text COLLATE utf8mb4_unicode_ci NOT NULL,
                        `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                      
                      DB::insert("INSERT INTO `zid_action` (`id`, `key`, `title`, `action`, `endpoint`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
                      (1, 'synczid_product', 'Synz Products & Categories', 'It will import all the products & categories from Zid into Wosul ERP ', '', NULL, NULL, NULL, NULL);");
                      
                      DB::update("ALTER TABLE `zid_action` ADD PRIMARY KEY (`id`);");
                      
                      DB::update("ALTER TABLE `zid_action` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");
                      
                      DB::statement("CREATE TABLE `zid_activity` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `zid_action_id` int(11) NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `remark` text COLLATE utf8mb4_unicode_ci,
                        `created_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                      
                      DB::update("ALTER TABLE `zid_activity` ADD PRIMARY KEY (`id`);");
                      
                      DB::update("ALTER TABLE `zid_activity` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;");
                }

                if(!Schema::hasTable('bonat_user_points_settings')){
                    DB::statement("CREATE TABLE `bonat_user_points_settings` (
                        `id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY ,
                        `slack` varchar(50) NOT NULL,
                        `bonat_merchant_id` varchar(50) DEFAULT NULL,
                        `merchant_id` int(11) DEFAULT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `is_verify` tinyint(4) NOT NULL DEFAULT '0',
                        `created_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;");
                }
                if(!Schema::hasTable('bonat_store_counter_points_settings')){
                    DB::statement("CREATE TABLE `bonat_store_counter_points_settings` (
                        `id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY ,
                        `slack` varchar(50) NOT NULL,
                        `merchant_id` varchar(50) DEFAULT NULL,
                        `store_id` varchar(50) DEFAULT NULL,
                        `counter_id` varchar(50) DEFAULT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `is_verify` tinyint(4) NOT NULL DEFAULT '0',
                        `created_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;");
                }
                if(!Schema::hasTable('quantity_purchases')){
                    DB::statement("CREATE TABLE `quantity_purchases` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `po_number` int(11) NOT NULL,
                        `po_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `order_date` date DEFAULT NULL,
                        `order_due_date` date DEFAULT NULL,
                        `supplier_id` int(11) NOT NULL,
                        `supplier_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `supplier_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `supplier_address` text COLLATE utf8mb4_unicode_ci,
                        `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `tax_option_id` int(11) DEFAULT NULL,
                        `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `terms` text COLLATE utf8mb4_unicode_ci,
                        `update_stock` tinyint(4) NOT NULL DEFAULT '0',
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `business_account_id` int(11) DEFAULT NULL,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `discount_type` int(11) DEFAULT NULL,
                        `discount_rate` decimal(10,2) DEFAULT NULL,
                        `transaction_id` int(11) NOT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                    DB::update("ALTER TABLE `quantity_purchases` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `purchase_orders_slack_unique` (`slack`), ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`), ADD KEY `purchase_orders_store_id_po_number_supplier_id_status_index` (`store_id`,`po_number`,`supplier_id`,`status`);");
                    DB::update("ALTER TABLE `quantity_purchases` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");
                    
                }

                if(!Schema::hasTable('quantity_purchase_products')){
                    DB::statement("CREATE TABLE `quantity_purchase_products` (
                        `id` int(10) UNSIGNED NOT NULL,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `purchase_order_id` int(11) NOT NULL,
                        `product_id` int(11) DEFAULT NULL,
                        `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `tax_components` text COLLATE utf8mb4_unicode_ci,
                        `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `stock_update` tinyint(4) NOT NULL DEFAULT '0',
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                    DB::update("ALTER TABLE `quantity_purchase_products` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `purchase_order_products_slack_unique` (`slack`), ADD KEY `purchase_order_products_purchase_order_id_status_index` (`purchase_order_id`,`status`);");
                    DB::update("ALTER TABLE `quantity_purchase_products` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;");
                }
                
                if(!Schema::hasTable('invoices_return')){
                    DB::statement("CREATE TABLE `invoices_return` (
                        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `return_invoice_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `invoice_slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `invoice_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `invoice_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `invoice_date` date DEFAULT NULL,
                        `invoice_due_date` date DEFAULT NULL,
                        `parent_po_id` int(11) DEFAULT NULL,
                        `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `bill_to_id` int(11) NOT NULL,
                        `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `bill_to_address` text COLLATE utf8mb4_unicode_ci,
                        `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `tax_option_id` int(11) DEFAULT NULL,
                        `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `subtotal_including_tax` decimal(10,2) NOT NULL,
                        `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `notes` text COLLATE utf8mb4_unicode_ci,
                        `terms` text COLLATE utf8mb4_unicode_ci,
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `invoice_color_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Default Color Code - #094269',
                        `reason` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            PRIMARY KEY (`id`),
                           UNIQUE KEY `invoices_slack_unique` (`slack`),
                         KEY `invoice_indexes` (`store_id`,`invoice_reference`,`bill_to`,`bill_to_id`,`status`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                }

                if(!Schema::hasTable('invoice_return_products')){
                    DB::statement("CREATE TABLE `invoice_return_products` (
                        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `return_invoice_id` int(11) NOT NULL,
                       `return_invoice_slack`  varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `product_id` int(11) DEFAULT NULL,
                        `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `tax_code_id` int(11) DEFAULT NULL,
                        `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
                        `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `tax_components` text COLLATE utf8mb4_unicode_ci,
                        `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
                        `measurement_id` int(11) DEFAULT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `product_type` int(11) DEFAULT NULL COMMENT '1-Product 2-Service',
                        `is_wastage` tinyint(3) DEFAULT NULL,
                         PRIMARY KEY (`id`),
                           UNIQUE KEY `invoice_products_slack_unique` (`slack`),
                         KEY `invoice_return_products_invoice_id_status_index` (`return_invoice_id`,`status`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                }

                if(!Schema::hasTable('order_damage')){
                    DB::statement("CREATE TABLE `order_damage` (
                        `id` int(11) NOT NULL,
                        `product` varchar(255) NOT NULL,
                        `branch` varchar(255) NOT NULL,
                        `branch_reference` varchar(255) NOT NULL,
                        `order_type` varchar(255) NOT NULL,
                        `added_by` int(11) NOT NULL,
                        `order_reference` int(11) NOT NULL,
                        `time` text NOT NULL,
                        `quantity` int(11) NOT NULL,
                        `amount` decimal(5,0) NOT NULL,
                        `reason` varchar(255) NOT NULL,
                        `created_at` datetime NOT NULL,
                        `updated_at` datetime NOT NULL,
                        `order_slack` text NOT NULL,
                        `tax_amount` decimal(25,0) NOT NULL DEFAULT '0',
                        `discount_amount` decimal(25,0) NOT NULL DEFAULT '0',
                        `product_code` decimal(25,0) NOT NULL DEFAULT '0',
                        `return_order_id` int(11) DEFAULT '0',
                        `order_id` int(11) DEFAULT '0',
                        `product_id` int(11) DEFAULT '0',
                        `name` varchar(255) DEFAULT '',
                        `product_slack` varchar(255) DEFAULT '0',
                        `purchase_amount_excluding_tax` decimal(5,2) DEFAULT '0.00',
                        `sale_amount_excluding_tax` decimal(5,2) DEFAULT '0.00',
                        `discount_code_id` int(11) DEFAULT '0',
                        `discount_code` varchar(255) DEFAULT '0',
                        `discount_percentage` decimal(5,2) DEFAULT '0.00',
                        `tax_code_id` int(11) DEFAULT '0',
                        `tax_code` int(11) DEFAULT '0',
                        `tax_percentage` decimal(5,2) DEFAULT '0.00',
                        `tax_components` decimal(5,2) DEFAULT '0.00',
                        `sub_total_purchase_price_excluding_tax` decimal(5,2) DEFAULT '0.00',
                        `total_after_discount` decimal(5,2) DEFAULT '0.00',
                        `total_amount` decimal(5,2) DEFAULT '0.00',
                        `is_ready_to_serve` int(11) DEFAULT '0',
                        `status` int(11) DEFAULT '0',
                        `created_by` int(11) DEFAULT '0',
                        `is_wastage` int(11) DEFAULT '0',
                        `order_product_modifier_options` text
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
                    DB::update("ALTER TABLE `order_damage` ADD PRIMARY KEY (`id`);");
                    DB::update("ALTER TABLE `order_damage` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
                }
                
                if(!Schema::hasTable('discount_codes')){
                    DB::statement("CREATE TABLE `discount_codes` (
                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                        `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `discount_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `discount_percentage` decimal(8,2) DEFAULT NULL,
                        `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT 1,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `discount_codes_slack_unique` (`slack`),
                        KEY `discount_codes_status_store_id_discount_code_index` (`status`,`store_id`,`discount_code`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                }
                
                if(!Schema::hasTable('product_barcode_details')){
                    DB::statement("CREATE TABLE `product_barcode_details` (
                        `id` bigint(20) NOT NULL AUTO_INCREMENT,
                        `product_id` bigint(20) NOT NULL,
                        `barcode_no` varchar(255) COLLATE utf8mb4_german2_ci NOT NULL,
                        `product_slack` varchar(30) COLLATE utf8mb4_german2_ci NOT NULL,
                        `is_ingredient` tinyint(1) NOT NULL DEFAULT '0',
                        `quantity` decimal(15,2) NOT NULL,
                        `show_barcode_value` tinyint(1) NOT NULL DEFAULT '0',
                        `show_item_name` tinyint(1) NOT NULL DEFAULT '0',
                        `show_item_price_with_vat` tinyint(1) NOT NULL DEFAULT '0',
                        `show_store_name` tinyint(1) NOT NULL DEFAULT '0',
                        `store_id` int(10) unsigned NOT NULL,
                        `show_manufacturing_date` tinyint(1) NOT NULL DEFAULT '0',
                        `manufacturing_date` date DEFAULT NULL,
                        `show_expiry_date` tinyint(1) NOT NULL DEFAULT '0',
                        `expiry_date` date DEFAULT NULL,
                        `show_notes` tinyint(1) NOT NULL,
                        `notes` text COLLATE utf8mb4_german2_ci,
                        `status` tinyint(1) NOT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `product_id` (`product_id`),
                        UNIQUE KEY `product_slack` (`product_slack`),
                        KEY `store_id` (`store_id`),
                        CONSTRAINT `product_barcode_details_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;");
                }
                
                if(!Schema::hasTable('quantity_adjustments')){
                    DB::statement("CREATE TABLE `quantity_adjustments` (
                        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
                        `reference` varchar(255) DEFAULT '',
                        `store_id` int(11) DEFAULT NULL,
                        `reason` varchar(255) DEFAULT NULL COMMENT 'content',
                        `status` varchar(255) DEFAULT NULL,
                        `created_at` datetime DEFAULT NULL,
                        `submitted_at` datetime DEFAULT NULL,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_at` datetime DEFAULT NULL,
                        `slack` varchar(255) DEFAULT '',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='newTable';");
                }

                if(!Schema::hasTable('quantity_adjustment_items')){
                    DB::statement("CREATE TABLE `quantity_adjustment_items` (
                        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
                        `quantity_adjustment_id` int(11) DEFAULT NULL,
                        `product_id` int(11) DEFAULT NULL,
                        `quantity` int(11) DEFAULT NULL,
                        `created_at` datetime DEFAULT NULL,
                        `updated_at` datetime DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `quantity_adjustment_id` (`quantity_adjustment_id`),
                        CONSTRAINT `quantity_adjustment_items_ibfk_1` FOREIGN KEY (`quantity_adjustment_id`) REFERENCES `quantity_adjustments` (`id`) ON DELETE CASCADE
                      ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='newTable';");
                }

                if(!Schema::hasTable('inventory_counts')){
                    DB::statement("CREATE TABLE `inventory_counts` ( 
                        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `reference_no` varchar(30) NOT NULL, 
                        `store_id` INT(11) NOT NULL , 
                        `user_id` INT(11) NOT NULL , 
                        `business_date` DATE NULL , 
                        `status` SMALLINT(1) NOT NULL DEFAULT '0' , 
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)) ENGINE = InnoDB;");
                }

                if(!Schema::hasTable('inventory_count_items')){
                    DB::statement("CREATE TABLE `inventory_count_items` ( 
                        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT , 
                        `inventory_count_id` INT(11) NOT NULL , 
                        `product_id` INT(11) NOT NULL , 
                        `original_quantity` DECIMAL(5,2) NULL , 
                        `entered_quantity` DECIMAL(5,2) NULL , 
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)) ENGINE = InnoDB;");
                }

                if(!Schema::hasTable('tax_names')){
                    DB::statement("CREATE TABLE `tax_names` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `tax_name` varchar(100) NOT NULL,
                        `percentage` decimal(6,2) NOT NULL,
                        `is_visible` tinyint(4) NOT NULL DEFAULT '1',
                        `is_default` tinyint(4) NOT NULL DEFAULT '0',
                        `created_at` timestamp NULL DEFAULT NULL,
                        `created_by` int(11) DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        `updated_by` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;");
                    DB::insert("INSERT INTO `tax_names` (`id`, `tax_name`, `percentage`, `status`, `is_visible`, `is_default`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
                    (1, 'No Tax', 0.00, 1,  0,  1,  now(),  1,  now(),  1),
                    (2, 'VAT Tax',  15.00,  1,  1,  1,  now(),  1,  now(),  1),
                    (3, 'Zero Tax', 0.00, 1,  1,  1,  now(),  1,  now(),  1),
                    (4, 'Exempt Tax', 0.00, 1,  1,  1,  now(),  1,  now(),  1);");
                }

                if(!Schema::hasTable('quantity_history')){
                    DB::statement("CREATE TABLE `quantity_history` (
                        `id` int(11) NOT NULL,
                        `slack` varchar(55) NOT NULL,
                        `product_id` int(11) NOT NULL,
                        `store_id` int(11) NOT NULL,
                        `type` varchar(55) NOT NULL COMMENT 'PRODUCT,PURCHASE_ORDER,QUANTITY_PURCHASE,QUANTITY_ADJUSTMENT,STOCK_TRANSFER',
                        `action` varchar(55) NOT NULL COMMENT 'INCREMENT,DECREMENT',
                        `quantity` int(11) NOT NULL,
                        `table_id` int(11) NOT NULL,
                        `date` date NOT NULL,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `created_by` int(11) DEFAULT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                    DB::update("ALTER TABLE `quantity_history` ADD PRIMARY KEY (`id`);");
                    DB::update("ALTER TABLE `quantity_history` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
                }
                
                $progressBar->advance();
    
                $this->info("\n". $db_name ." updated successfully");            
                $count++;
                    
            }
    
        }
        
        $progressBar->finish();
        $this->info("\n total ". $count ." merchants updated");
        // $this->info("\n". json_encode($queries) ."\n");
    }
}
