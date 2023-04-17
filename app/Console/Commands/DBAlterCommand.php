<?php

namespace App\Console\Commands;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DBAlterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:alter {database?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used for running alteration queries onto database';

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

                if(Schema::hasTable('orders')){

                    /* Alter: structural changes for `orders` table */
                    if(!Schema::hasColumn('orders','counter_slack')){
                        DB::update("ALTER TABLE `orders` ADD `counter_slack` VARCHAR(30) NULL AFTER `customer_email`, ADD `counter_name` VARCHAR(50) NULL AFTER `counter_slack`;");
                    }
                    if(!Schema::hasColumn('orders','return_order_amount')){
                        DB::update("ALTER TABLE `orders` ADD `return_order_amount` DECIMAL(13,2) NULL DEFAULT NULL AFTER `transaction_id`;");
                    }
                    if(!Schema::hasColumn('orders','bonat_discount')){
                        DB::update("ALTER TABLE `orders` ADD `bonat_discount` TINYINT(4) NOT NULL DEFAULT '0' AFTER `return_order_amount`;");
                    }
                    if(!Schema::hasColumn('orders','store_level_total_tobacco_tax_percentage')){
                        DB::update("ALTER TABLE `orders` ADD `store_level_total_tobacco_tax_percentage` DECIMAL(8,2) NOT NULL DEFAULT '0.00' AFTER `product_level_total_discount_amount`, ADD `store_level_total_tobacco_tax_amount` DECIMAL(13,2) NOT NULL DEFAULT '0.00' AFTER `store_level_total_tobacco_tax_percentage`;");
                    }
                    if(!Schema::hasColumn('orders','device_id')){
                        DB::update("ALTER TABLE `orders` ADD `device_id` VARCHAR(50) NULL COMMENT 'to know from which device order has been created' AFTER `order_number`;");
                    }
                    if(!Schema::hasColumn('orders','reference_number')){
                        DB::update("ALTER TABLE `orders` ADD `reference_number` BIGINT(11) NULL DEFAULT '0' AFTER `bonat_discount`;");
                    }
                    
                    /* Alter: structural changes for `stores` table */
                    if(!Schema::hasColumn('stores','zid_store_api_token')){
                        DB::update("ALTER TABLE `stores` ADD `zid_store_api_token` INT(11) NULL AFTER `quotation_policy_information`;");
                    }
                    if(!Schema::hasColumn('stores','zid_store_id')){
                        DB::update("ALTER TABLE `stores` ADD `zid_store_id` INT(11) NULL AFTER `zid_store_api_token`;");
                    }
                    if(!Schema::hasColumn('stores','store_invoice_return_number')){
                        DB::update("ALTER TABLE `stores` ADD `store_invoice_return_number` INT(11) NOT NULL DEFAULT '0' AFTER `store_order_return_number`;");
                    }
                    if(!Schema::hasColumn('stores','store_opening_time')){
                        DB::update("ALTER TABLE `stores` ADD `store_opening_time` TEXT NULL AFTER `zid_store_id`, ADD `store_closing_time` TIME NULL AFTER `store_opening_time`;");
                    }
                    if(!Schema::hasColumn('stores','is_store_closing_next_day')){
                        DB::update("ALTER TABLE `stores` ADD `is_store_closing_next_day` BOOLEAN NOT NULL DEFAULT FALSE AFTER `store_closing_time`;");
                    }
                    if(!Schema::hasColumn('stores','tobacco_tax_val')){
                        DB::update("ALTER TABLE `stores` ADD `tobacco_tax_val` FLOAT NOT NULL DEFAULT '0' AFTER `vat_number`;");
                    }
                    if(!Schema::hasColumn('stores','tax_registration_name')){
                        DB::update("ALTER TABLE `stores` ADD `tax_registration_name` VARCHAR(255) NULL AFTER `is_store_closing_next_day`;");
                    }
                    if(!Schema::hasColumn('stores','idle_time')){
                        DB::update("ALTER TABLE `stores` ADD `idle_time` INT NULL AFTER `tax_registration_name`;");
                    }
                    if(!Schema::hasColumn('stores','idle_time_status')){
                        DB::update("ALTER TABLE `stores` ADD `idle_time_status` TINYINT(3) NOT NULL DEFAULT '0' AFTER `tax_registration_name`;");
                    }
                    if(!Schema::hasColumn('stores','building_number')){
                        DB::update("ALTER TABLE  `stores`  ADD `building_number` VARCHAR(50) NULL DEFAULT NULL  AFTER `address`,  ADD `street_name`  VARCHAR(50) NULL DEFAULT NULL  AFTER `building_number`,  ADD `district` VARCHAR(50) NULL DEFAULT NULL  AFTER `street_name`,   ADD `city` VARCHAR(50) NULL DEFAULT NULL  AFTER `district`,  ADD `other_seller_id` VARCHAR(50) NULL DEFAULT NULL  AFTER `city`;");
                    }

                    /* Alter: structural changes for `products` table */
                    if(!Schema::hasColumn('products','shows_in')){
                        DB::update("ALTER TABLE products ADD shows_in VARCHAR(20) NULL;");
                    }
                    if(!Schema::hasColumn('products','barcode')){
                        DB::update("ALTER TABLE products ADD barcode TEXT NULL;");
                    }
                    if(!Schema::hasColumn('products','brand_id')){
                        DB::update("ALTER TABLE products ADD brand_id integer NULL;");
                    }
                    if(!Schema::hasColumn('products','measurement_id')){
                        DB::update("ALTER TABLE products ADD measurement_id integer NULL;");
                    }
                    if(!Schema::hasColumn('products','product_thumb_image')){
                        DB::update("ALTER TABLE products ADD product_thumb_image VARCHAR(200) NULL;");
                    }
                    if(!Schema::hasColumn('products','product_border_color')){
                        DB::update("ALTER TABLE products ADD product_border_color VARCHAR(15) NULL;");
                    }
                    if(!Schema::hasColumn('products','product_manufacturer_date')){
                        DB::update("ALTER TABLE products ADD product_manufacturer_date DATE NULL;");
                    }
                    if(!Schema::hasColumn('products','product_expiry_date')){
                        DB::update("ALTER TABLE products ADD product_expiry_date DATE NULL;");
                    }
                    if(!Schema::hasColumn('products','main_category_id')){
                        DB::update("ALTER TABLE products ADD main_category_id INT  NULL;");
                    }
                    if(!Schema::hasColumn('products','is_taxable')){
                        DB::update("ALTER TABLE products ADD is_taxable TINYINT NULL;");
                    }
                    if(!Schema::hasColumn('products','inventory_type')){
                        DB::update("ALTER TABLE products ADD inventory_type VARCHAR(20) NULL;");
                    }
                    if(!Schema::hasColumn('products','product_applied_on')){
                        DB::update("ALTER TABLE products ADD product_applied_on VARCHAR(50) DEFAULT 'all_stores';");
                    }
                    if(!Schema::hasColumn('products','product_applicable_stores')){
                        DB::update("ALTER TABLE products ADD product_applicable_stores TEXT DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('products','sale_amount_including_tax')){
                        DB::update("ALTER TABLE `products` ADD `sale_amount_including_tax` DECIMAL(13,2) NULL DEFAULT NULL AFTER `sale_amount_excluding_tax`;");
                    }
                    if(!Schema::hasColumn('products','modifier_id')){
                        DB::update("ALTER TABLE `products` ADD `modifier_id` INT(11) NULL AFTER `is_taxable`;");
                    }
                    if(!Schema::hasColumn('products','sales_price_including_boolean_and_price')){
                        DB::update("ALTER TABLE `products` ADD `sales_price_including_boolean_and_price` DECIMAL(13,2) NULL AFTER `is_taxable`;");
                    }
                    if(!Schema::hasColumn('products','zid_product_id')){
                        DB::update("ALTER TABLE `products` ADD `zid_product_id` VARCHAR(255) NULL COMMENT 'Product Id assigned by ZID Platform' AFTER `sales_price_including_boolean_and_price`;");
                    }
                    if(!Schema::hasColumn('products','name_ar')){
                        DB::update("ALTER TABLE `products` ADD `name_ar` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `name`;");
                    }
                    if(!Schema::hasColumn('products','zid_parent_product_id')){
                        DB::update("ALTER TABLE `products` ADD `zid_parent_product_id` VARCHAR(255) NULL AFTER `zid_product_id`;");
                    }
                    if(!Schema::hasColumn('products','description_ar')){
                        DB::update("ALTER TABLE `products` ADD `description_ar` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `description`;");
                    }
                    if(!Schema::hasColumn('products','show_description_in')){
                        DB::update("ALTER TABLE `products` ADD `show_description_in` TINYINT NULL DEFAULT '3' AFTER `shows_in`;");
                    }
                
                    /* Alter: structural changes for `category` table */
                    if(!Schema::hasColumn('category','parent')){
                        DB::update("ALTER TABLE category ADD parent integer NULL;");
                    }
                    if(!Schema::hasColumn('category','category_image')){
                        DB::update("ALTER TABLE category ADD category_image VARCHAR(100) NULL;");
                    }
                    if(!Schema::hasColumn('category','zid_category_id')){
                        DB::update("ALTER TABLE `category` ADD `zid_category_id` BIGINT(255) NULL AFTER `category_image`;");
                    }
                    if(!Schema::hasColumn('category','zid_parent_category_id')){
                        DB::update("ALTER TABLE `category` ADD `zid_parent_category_id` INT(11) NULL AFTER `zid_category_id`;");
                    }                
                    if(!Schema::hasColumn('category','label_ar')){
                        DB::update("ALTER TABLE `category` ADD `label_ar` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `label`;");
                    }                
                    if(!Schema::hasColumn('category','description_ar')){
                        DB::update("ALTER TABLE `category` ADD `description_ar` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `description`;");
                    }                
                    if(!Schema::hasColumn('category','sort_order')){
                        DB::update("ALTER TABLE `category` ADD sort_order INT(11) DEFAULT 1;");
                    }                
                    if(!Schema::hasColumn('category','category_applied_on')){
                        DB::update("ALTER TABLE `category` ADD `category_applied_on` VARCHAR(100) NULL DEFAULT 'all_stores' AFTER `description_ar`, ADD `category_applicable_stores` TEXT NULL AFTER `category_applied_on`;");
                    }
                    
                    /* Alter: structural changes for `order_products` table */
                    if(!Schema::hasColumn('order_products','modifier_option_id')){
                        DB::update("ALTER TABLE `order_products` ADD `modifier_option_id` INT(11) NULL AFTER `updated_at`, ADD `modifier_option_amount` DECIMAL(10,2) NULL AFTER `modifier_option_id`;");
                    }
                    if(!Schema::hasColumn('order_products','total_modifier_option_amount')){
                        DB::update("ALTER TABLE `order_products` ADD `total_modifier_option_amount` DECIMAL(10,2) NULL AFTER `modifier_option_amount`;");
                    }
                    if(!Schema::hasColumn('order_products','total_modifier_amount')){
                        DB::update("ALTER TABLE `order_products` ADD `total_modifier_amount` DOUBLE(10,2) NULL AFTER `updated_at`;");
                    }
                    if(!Schema::hasColumn('order_products','note')){
                        DB::update("ALTER TABLE `order_products` ADD `note` VARCHAR(255) NULL AFTER `total_modifier_amount`;");
                    }
                    if(!Schema::hasColumn('order_products','bonat_discount')){
                        DB::update("ALTER TABLE `order_products` ADD `bonat_discount` TINYINT(4) NOT NULL DEFAULT '0' AFTER `note`;");
                    }
                    if(!Schema::hasColumn('order_products','bonat_discount_price')){
                        DB::update("ALTER TABLE `order_products` ADD `bonat_discount_price` DECIMAL(13,2) NOT NULL DEFAULT '0.0' AFTER `bonat_discount`;");
                    }
                    if(!Schema::hasColumn('order_products','bonat_coupon')){
                        DB::update("ALTER TABLE `order_products` ADD `bonat_coupon` VARCHAR(50) NULL DEFAULT NULL AFTER `bonat_discount_price`;");
                    }
                    
                    /* Alter: structural changes for `users` table */
                    if(!Schema::hasColumn('users','is_master')){
                        DB::update("ALTER TABLE `users` ADD `is_master` TINYINT(3) NOT NULL DEFAULT '0' AFTER `is_admin`;");
                    }
                    if(!Schema::hasColumn('users','login_code')){
                        DB::update("ALTER TABLE `users` ADD `login_code` INT(8) NULL AFTER `password`;");
                    }
                    
                    /* Alter: structural changes for `order_return` table */
                    if(!Schema::hasColumn('order_return','reason')){
                        DB::update("ALTER TABLE `order_return` ADD `reason` VARCHAR(255) NULL AFTER `transaction_id`;");
                    }
                    if(!Schema::hasColumn('order_return','reference_number')){
                        DB::update("ALTER TABLE `order_return` ADD `reference_number` int NULL DEFAULT '0';");
                    }
                    if(!Schema::hasColumn('order_return','return_type')){
                        DB::update("ALTER TABLE order_return ADD return_type VARCHAR(255) DEFAULT 'Return';");
                    }
                    
                    /* Alter: structural changes for `order_return_products` table */
                    if(!Schema::hasColumn('order_return_product','is_wastage')){
                        DB::update("ALTER TABLE `order_return_product` ADD `is_wastage` TINYINT(3) NULL AFTER `updated_at`;");
                    }
                    if(!Schema::hasColumn('order_return_product','return_type')){
                        DB::update("ALTER TABLE `order_return_product` ADD `return_type` varchar(10) NULL DEFAULT 'Return' AFTER `status`;");
                    }
                    if(!Schema::hasColumn('order_return_product','time')){
                        DB::update("ALTER TABLE `order_return_product` ADD `time` text COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('order_return_product','reason')){
                        DB::update("ALTER TABLE `order_return_product` ADD `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('order_return_product','branch')){
                        DB::update("ALTER TABLE `order_return_product` ADD `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0';");
                    }
                    if(!Schema::hasColumn('order_return_product','branch_reference')){
                        DB::update("ALTER TABLE `order_return_product` ADD `branch_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0';");
                    }
                    if(!Schema::hasColumn('order_return_product','added_by')){
                        DB::update("ALTER TABLE `order_return_product` ADD `added_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0';");
                    }
                    if(!Schema::hasColumn('order_return_product','order_reference')){
                        DB::update("ALTER TABLE `order_return_product` ADD `order_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0';");
                    }
                    if(!Schema::hasColumn('order_return_product','order_id')){
                        DB::update("ALTER TABLE `order_return_product` ADD `order_id` int(11) DEFAULT 0;");
                    }
                    if(!Schema::hasColumn('order_return_product','order_slack')){
                        DB::update("ALTER TABLE `order_return_product` ADD `order_slack` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '';");
                    }
                    if(!Schema::hasColumn('order_return_product','order_product_modifier_options')){
                        DB::update("ALTER TABLE `order_return_product` ADD `order_product_modifier_options` text COLLATE utf8mb4_unicode_ci DEFAULT NULL");
                    }
  
                    
                    /* Alter: structural changes for `order_return_products` table */
                    if(!Schema::hasColumn('invoice_products','measurement_id')){
                        DB::update("ALTER TABLE `invoice_products` ADD `measurement_id` INT(11) NULL DEFAULT NULL AFTER `total_amount`;");
                    }
                    if(!Schema::hasColumn('invoice_products','description')){
                        DB::update("ALTER TABLE `invoice_products` ADD `description` TEXT NULL AFTER `name`;");
                    }
                    if(!Schema::hasColumn('invoice_products','show_description_in')){
                        DB::update("ALTER TABLE `invoice_products` ADD `show_description_in` TINYINT NULL DEFAULT '6' AFTER `description`;");
                    }
                    
                    /* Alter: structural changes for `discount_codes` table */
                    if(!Schema::hasColumn('discount_codes','discount_type')){
                        DB::update("ALTER TABLE `discount_codes` add `discount_type` text NOT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','discounttype')){
                        DB::update("ALTER TABLE  `discount_codes` add `discounttype` varchar(255) NOT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','is_always')){
                        DB::update("ALTER TABLE `discount_codes` add `is_always` int(11) NOT NULL DEFAULT 0;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_value')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_value` int(11) DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','limit_on_discount')){
                        DB::update("ALTER TABLE  `discount_codes` add `limit_on_discount` int(11) NOT NULL DEFAULT 0;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_start_date')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_start_date` datetime NOT NULL DEFAULT current_timestamp();");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_end_date')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_end_date` datetime NOT NULL DEFAULT current_timestamp();");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_applicable_products')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_applicable_products` text DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_not_applicable_products')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_not_applicable_products` text DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_applicable_categories')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_applicable_categories` text  DEFAULT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_applied_on')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_applied_on` text NOT NULL;");
                    }
                    if(!Schema::hasColumn('discount_codes','discount_status')){
                        DB::update("ALTER TABLE  `discount_codes` add `discount_status` tinyint(4) NOT NULL DEFAULT 1;");
                    }
                    
                    /* Alter: structural changes for `acc_transaction` table */
                    if(!Schema::hasColumn('acc_transaction','transaction_id')){
                        DB::update("ALTER TABLE `acc_transaction` ADD `transaction_id` INT(11) NULL DEFAULT NULL AFTER `Credit`, ADD `receipt_image` VARCHAR(50) NULL DEFAULT NULL AFTER `transaction_id`, ADD `transaction_receipt_link` VARCHAR(150) NULL DEFAULT NULL AFTER `receipt_image`;");
                    }
                    if(!Schema::hasColumn('acc_transaction','order_slack')){
                        DB::update("ALTER TABLE `acc_transaction` ADD `order_slack` VARCHAR(50) NULL DEFAULT NULL AFTER `receipt_image`;");
                    }
                    
                    /* Extra */
                    if(!Schema::hasColumn('stock_return_products','measurement_id')){
                        DB::update("ALTER TABLE `stock_return_products` ADD `measurement_id` INT(11) NULL DEFAULT NULL AFTER `total_amount`;");
                    }
                    if(!Schema::hasColumn('modifiers','is_multiple')){
                        DB::update("ALTER TABLE `modifiers` ADD `is_multiple` INT(11) NOT NULL COMMENT '0-false,1-True' AFTER `slack`;");
                    }
                    if(!Schema::hasColumn('suppliers','tax_number')){
                        DB::update("ALTER TABLE `suppliers` ADD `tax_number` VARCHAR(255) NULL AFTER `pincode`;");
                    }
                    if(!Schema::hasColumn('suppliers','building_number')){
                        DB::update("ALTER TABLE `suppliers`  ADD `building_number` VARCHAR(50) NULL DEFAULT NULL  AFTER `address`,  ADD `street_name` VARCHAR(50) NULL DEFAULT NULL  AFTER `building_number`,  ADD `district` VARCHAR(50) NULL DEFAULT NULL  AFTER `street_name`,  ADD `country_id` INT(11) NULL DEFAULT NULL  AFTER `district`,  ADD `city` VARCHAR(50) NULL DEFAULT NULL  AFTER `country_id`,  ADD `other_seller_id` VARCHAR(50) NULL DEFAULT NULL  AFTER `city`;");
                    }
                    if(!Schema::hasColumn('suppliers','website')){
                        DB::update("ALTER TABLE `suppliers`  ADD `website` VARCHAR(255) NULL DEFAULT NULL AFTER `pincode`, ADD `organization_name` VARCHAR(255) NULL DEFAULT NULL AFTER `website`;");
                    }
                    if(!Schema::hasColumn('purchase_orders','transaction_id')){
                        DB::update("ALTER TABLE `purchase_orders` ADD `transaction_id` INT(11) NOT NULL AFTER `discount_rate`;");
                    }
                    if(!Schema::hasColumn('customers','tax_number')){
                        DB::update("ALTER TABLE `customers` ADD `tax_number` VARCHAR(255) NULL DEFAULT NULL AFTER `address`, ADD `website` VARCHAR(255) NULL DEFAULT NULL AFTER `tax_number`, ADD `organization_name` VARCHAR(255) NULL DEFAULT NULL AFTER `website`;");
                    }
                    if(!Schema::hasColumn('customers','building_number')){
                        DB::update("ALTER TABLE `customers`  ADD `building_number` VARCHAR(50) NULL DEFAULT NULL  AFTER `address`,  ADD `street_name` VARCHAR(50) NULL DEFAULT NULL  AFTER `building_number`,  ADD `district` VARCHAR(50) NULL DEFAULT NULL  AFTER `street_name`,  ADD `country_id` INT(11) NULL DEFAULT NULL  AFTER `district`,  ADD `city` VARCHAR(50) NULL DEFAULT NULL  AFTER `country_id`,  ADD `pincode` VARCHAR(50) NULL DEFAULT NULL  AFTER `city`,  ADD `other_seller_id` VARCHAR(50) NULL DEFAULT NULL  AFTER `pincode`;");
                    }
                    if(!Schema::hasColumn('invoices','return_invoice_amount')){
                        DB::update("ALTER TABLE `invoices` ADD `return_invoice_amount` DECIMAL(13,2) NULL DEFAULT NULL AFTER `invoice_color_code`;");
                    }
                    if(!Schema::hasColumn('business_registers','manual_drawer_opens')){
                        DB::update("ALTER TABLE `business_registers` ADD `manual_drawer_opens` DECIMAL(13,2) NOT NULL DEFAULT '0.00' AFTER `closing_amount`;");
                    }
                    if(!Schema::hasColumn('setting_app','tax_setting_updated_at')){
                        DB::update("ALTER TABLE `setting_app` ADD `tax_setting_updated_at` DATETIME NULL AFTER `updated_at`;");
                    }
                    
                    if(!Schema::hasColumn('purchase_order_products','tax_code_id')){
                        DB::update("ALTER TABLE `purchase_order_products` ADD `tax_code_id` int NOT NULL DEFAULT '0' AFTER `discount_percentage`;");
                    }
                    if(!Schema::hasColumn('product_barcode_details','is_ingredient')){
                        DB::update("ALTER TABLE `product_barcode_details` ADD `is_ingredient` tinyint(1) NOT NULL DEFAULT '0' AFTER `product_slack`;");
                    }
                    if(!Schema::hasColumn('stock_return_products','tax_code_id')){
                        DB::update("ALTER TABLE `stock_return_products` ADD `tax_code_id` int NOT NULL DEFAULT '0' AFTER `discount_percentage`;");
                    }
                    if(!Schema::hasColumn('tax_codes','is_tax_return')){
                        DB::update("ALTER TABLE `tax_codes` ADD `is_tax_return` tinyint(1) NULL DEFAULT '0' AFTER `description`;");
                    }
                    if(!Schema::hasColumn('tax_code_type','tax_name_id')){
                        DB::update("ALTER TABLE `tax_code_type` ADD `tax_name_id` int NOT NULL DEFAULT '1' AFTER `tax_percentage`;");
                    }
                    if(!Schema::hasColumn('products','tobacco_tax_percentage')){
                        DB::update("ALTER TABLE `products` ADD `tobacco_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0' AFTER `tax_code_id`, ADD `is_tobacco_tax` tinyint NOT NULL DEFAULT '0' AFTER `tobacco_tax_percentage`;");
                    }
                    if(!Schema::hasColumn('order_products','tobacco_tax_components')){
                        DB::update("ALTER TABLE `order_products` ADD `tobacco_tax_components` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `tax_components`;");
                    }
                    if(!Schema::hasColumn('order_return_product','tobacco_tax_components')){
                        DB::update("ALTER TABLE `order_return_product` ADD `tobacco_tax_components` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `tax_components`;");
                    }
                    if(!Schema::hasColumn('order_products','additional_discount_percentage')){
                        DB::update("ALTER TABLE `order_products` ADD `additional_discount_percentage` decimal(8,2) NOT NULL DEFAULT '0' AFTER `discount_percentage`;");
                    }

                    $progressBar->advance();
        
                    $this->info("\n". $db_name ." updated successfully");            
                    $count++;
    
                }
                
            }
    
        }
        
        $progressBar->finish();
        $this->info("\n total ". $count ." merchants updated");
        // $this->info("\n". json_encode($queries) ."\n");
    }
}
