<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Artisan::call('cache:clear');

$subdomain = env('DB_PREFIX');

if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {

    $host =  $_SERVER['HTTP_HOST'];
    $urls = explode(".", $host);
    if ($urls[0] == "www") {
        $subdomain = $urls[1];
    } else {
        $subdomain = $urls[0];
    }

    // this is for hrm redirect 
    Config::set('constants.subdomain_name', $subdomain);
}

if ($subdomain == env('DB_PREFIX')) {

    Route::middleware(['Localization', 'WebMenuMiddleware'])->group(function () {

        Route::prefix('{lang?}')->group(function() {
        
            // rest of the routes
            // if( env('APP_ENV') == "dev" ){
            //     Route::get('/', function(){
            //         return redirect('https://wosul.sa');
            //     });
            // }else{
                Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');


            Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
            Route::get('/sectors', [\App\Http\Controllers\AboutController::class, 'index'])->name('sectors');
            Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
            Route::get('/inquiry', [\App\Http\Controllers\ContactController::class, 'inquiry'])->name('inquiry');
            Route::get('/pricing', [\App\Http\Controllers\PricingController::class, 'index'])->name('pricing');
            Route::get('/forgot_password', [\App\Http\Controllers\LoginController::class, 'forgot_password'])->name('forgot_password');
            Route::get('/login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
            Route::get('/logout', [\App\Http\Controllers\LoginController::class, 'merchant_logout'])->name('merchant_logout');
            Route::post('/authenticate', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('authenticate');
            Route::get('/faq', [\App\Http\Controllers\FaqController::class, 'index'])->name('faq');
            Route::get('/response/{paymentid?}', [\App\Http\Controllers\TabbyController::class, 'finalize'])->name('tabbyresponse')->middleware('SubscriptionMiddleware');
            Route::get('/finalize', 'HyperPayPaymentController@finalize')->name('finalize')->middleware('SubscriptionMiddleware');
            Route::get('/checkout', 'HyperPayPaymentController@checkout')->name('checkout')->middleware('SubscriptionMiddleware');
            //Route::get('/checkout_new', 'HyperPayPaymentController@checkout_new')->name('checkout_new');
        
            Route::get('/term_and_condition', [\App\Http\Controllers\TermsAndConditionController::class, 'index'])->name('term_and_condition');
            Route::get('/privacy_policy', [\App\Http\Controllers\PrivacyPolicyController::class, 'index'])->name('privacy_policy');
            Route::get('/profile', [\App\Http\Controllers\MerchantController::class, 'profile'])->name('profile');
            Route::get('/my_orders', [\App\Http\Controllers\MerchantController::class, 'my_orders'])->name('my_orders');
            Route::get('/my_orders/{id}', [\App\Http\Controllers\MerchantController::class, 'order_detail'])->name('my_order_detail');
            Route::get('/marketplace', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
            Route::get('/marketplace/detail/{slack}', [\App\Http\Controllers\MarketplaceController::class, 'detail'])->name('marketplace_detail');
            // Route::get('/marketplace/zid', [\App\Http\Controllers\MarketplaceController::class, 'zid'])->name('marketplce_zid');
            Route::get('/wosul', 'HomeController@wosul')->name('wosul');
            Route::get('/release', 'ReleaseController@index');
        
            /* merchant registration */
            Route::get('/merchant/register', 'MerchantController@index')->name('merchant_register');
            Route::get('/merchant/register/{id}', 'MerchantController@register_without_payment')->name('merchant_register_without_payment');
        
            if(env('APP_ENV') == 'production'){
                Route::get('/qr', [\App\Http\Controllers\SurveyController::class, 'index'])->name('add_survey');
                Route::get('/qr/response', [\App\Http\Controllers\SurveyController::class, 'response'])->name('survey_response');
                Route::get('/campaign', [\App\Http\Controllers\CampaignController::class, 'index'])->name('add_campaign');
                Route::get('/campaign/response', [\App\Http\Controllers\CampaignController::class, 'response'])->name('campaign_response');
                Route::post('/campaign/change_status', [\App\Http\Controllers\CampaignController::class, 'change_status'])->name('change_status');
                Route::get('/promoter', [\App\Http\Controllers\PromoterController::class, 'index'])->name('add_promoter');
                Route::get('/promoter/response', [\App\Http\Controllers\PromoterController::class, 'response'])->name('promoter_response');
                Route::get('/dulani', [\App\Http\Controllers\DulaniController::class, 'index'])->name('add_dulani');
                Route::get('/dulani/response', [\App\Http\Controllers\DulaniController::class, 'response'])->name('dulani_response');
            }
            Route::get('/registration_form', [\App\Http\Controllers\RegisterationFormController::class, 'index'])->name('add_registration_form');
            Route::get('/registration_form/response', [\App\Http\Controllers\RegisterationFormController::class, 'response'])->name('registration_form_response');
            Route::get('/registration_form_in', [\App\Http\Controllers\RegisterationFormInController::class, 'index'])->name('add_registration_form_in');
            Route::get('/registration_form_in/response', [\App\Http\Controllers\RegisterationFormInController::class, 'response'])->name('registration_form_in_response');
        
        });
        // expresspay  
        Route::get('/expresspay/checkout/{merchant_id?}/{payment_slack?}', 'ExpresspayController@checkout');
        Route::post('/expresspay/checkout', 'ExpresspayController@pay')->name('expresspay_checkout');
        Route::get('/expresspay/cancel', 'ExpresspayController@cancel');
        Route::get('/expresspay/success', 'ExpresspayController@success');  
        Route::post('/merchant/register', [\App\Http\Controllers\MerchantController::class, 'store'])->name('save_register_merchant');
        
    });

} else {

    Route::group(['middleware' => ['subdomain']], function () {

        Route::group(['middleware' => ['demo_check']], function () {

            Route::get('/', "Entry@sign_in")->name('home');
            Route::get('/logout', "Entry@logout")->name('logout');
            Route::get('/forgot_password', "Entry@forgot_password")->name('forgot_password');
            Route::get('/reset_password/{user_slack}/{forgot_password_token}', "Entry@reset_password")->name('reset_password');
            Route::get('/generate_lockout_password/{password_string?}', "Entry@generate_lockout_password")->name('generate_lockout_password');
        });

        Route::group(['middleware' => ['token_auth', 'user_menu']], function () {

            //search 
            Route::get('/search', "Search@index")->name('search');

            //dashboard
            Route::get('/dashboard', "Dashboard@index")->name('dashboard');
            Route::get('/billing_counter_dashboard', "Dashboard@billing_counter_dashboard")->name('billing_counter_dashboard');

            //user
            Route::get('/users', "User@index")->name('users');
            Route::get('/user/{slack}', "User@detail")->name('user');
            Route::get('/add_user', "User@add_user")->name('add_user');
            Route::get('/edit_user/{slack?}', "User@add_user")->name('edit_user');
            Route::get('/profile/{slack}', "User@profile")->name('profile');
            Route::get('/edit_profile', "User@edit_profile")->name('edit_profile');

            //role
            Route::get('/roles', "Role@index")->name('roles');
            Route::get('/role/{slack}', "Role@detail")->name('role');
            Route::get('/add_role', "Role@add_role")->name('add_role');
            Route::get('/edit_role/{slack?}', "Role@add_role")->name('edit_role');

            //customer
            Route::get('/customers', "Customer@index")->name('customers');
            Route::get('/customer/{slack}', "Customer@detail")->name('customer');
            Route::get('/add_customer', "Customer@add_customer")->name('add_customer');
            Route::get('/edit_customer/{slack?}', "Customer@add_customer")->name('edit_customer');
            Route::get('/delete_customer/{slack?}', "Customer@delete_customer")->name('delete_customer');

            //product
            Route::get('/products', "Product@index")->name('products');
            Route::get('/product/{slack}', "Product@detail")->name('product');
            Route::get('/add_product', "Product@add_product")->name('add_product');
            Route::get('/add_ingredient', "Product@add_ingredient")->name('add_ingredient');
            Route::get('/edit_product/{slack?}', "Product@add_product")->name('edit_product');
            Route::get('/clone_product/{slack?}', "Product@clone_product")->name('clone_product');
            Route::get('/edit_ingredient/{slack?}', "Product@add_ingredient")->name('edit_ingredient');
            Route::get('/generate_barcode/{slack?}', "Product@generate_barcode")->name('generate_barcode');
            Route::get('/add_new_stock_transfer_product/{slack?}', "Product@add_product")->name('add_new_stock_transfer_product');

            // Combo
            Route::get('/combos', "Combo@index")->name('combos');
            Route::get('/add_combo', "Combo@add")->name('add_combo');
            Route::get('/edit_combo/{slack?}', "Combo@add")->name('edit_combo');

            Route::get('/combo_groups', "ComboGroup@index")->name('combo_groups');
            Route::get('/add_combo_group', "ComboGroup@add")->name('add_combo_group');
            Route::get('/edit_combo_group/{slack?}', "ComboGroup@add")->name('edit_combo_group');

            // main category
            Route::get('/main_categories', "MainCategory@index")->name('main_categories');
            Route::get('/main_category/{slack}', "MainCategory@detail")->name('main_category');
            Route::get('/add_maincategory', "MainCategory@add_category")->name('add_main_category');
            Route::post('/add_maincategory', "MainCategory@save_category")->name('save_main_category');
            Route::get('/edit_main_category/{slack?}', "MainCategory@add_category")->name('edit_main_category');

            //category
            Route::get('/categories', "Category@index")->name('categories');
            Route::get('/category-display', "Category@category_screen")->name('category_screen');
            Route::get('/category/{slack}', "Category@detail")->name('category');
            Route::get('/add_category', "Category@add_category")->name('add_category');
            Route::get('/edit_category/{slack?}', "Category@add_category")->name('edit_category');

            //inventory
            Route::get('/inventory-count', "InventoryCount@index")->name('inventory-count');
            // Route::get('/branch/{store_id}/add_inventory_count', "InventoryCount@add_inventory_count")->name('add_inventory_count');
            // Route::get('/branch/{store_id}/edit_inventory_count/{id?}', "InventoryCount@add_inventory_count")->name('edit_inventory_count');
            Route::get('/inventory-count/view', "InventoryCount@view_inventory_count")->name('view-inventory-count');


            //supplier
            Route::get('/suppliers', "Supplier@index")->name('suppliers');
            Route::get('/supplier/{slack}', "Supplier@detail")->name('supplier');
            Route::get('/add_supplier', "Supplier@add_supplier")->name('add_supplier');
            Route::get('/edit_supplier/{slack?}', "Supplier@add_supplier")->name('edit_supplier');

            //tax code
            Route::get('/tax_codes', "Taxcode@index")->name('tax_codes');
            Route::get('/tax_code/{slack}', "Taxcode@detail")->name('tax_code');
            Route::get('/add_tax_code', "Taxcode@add_tax_code")->name('add_tax_code');
            Route::get('/edit_tax_code/{slack?}', "Taxcode@add_tax_code")->name('edit_tax_code');

            //tax name master
            Route::get('/tax_names', "TaxNameMaster@index")->name('tax_names');
            Route::get('/tax_name/{id}', "TaxNameMaster@detail")->name('tax_name');
            Route::get('/add_tax_name', "TaxNameMaster@add_tax_name")->name('add_tax_name');
            Route::get('/edit_tax_name/{id?}', "TaxNameMaster@add_tax_name")->name('edit_tax_name');

            //order
            Route::get('/orders', "Order@index")->name('orders');
            Route::get('/order/{slack}', "Order@detail")->name('order_detail');
            Route::get('/add_order', "Order@add_order")->name('add_order');
            Route::get('/edit_order/{slack?}', "Order@add_order")->name('edit_order');
            Route::get('/print_order/{slack}', "Order@print_order")->name('print_order');
            Route::get('/order_summary/{slack}', "Order@order_summary")->name('order_summary');
            // Route::get('/order_receipt/{slack}', "Order@order_receipt")->name('order_receipt');
            Route::get('/return_order_receipt/{slack}', "Order@return_order_receipt")->name('return_order_receipt');
            Route::get('/damage_order_receipt/{slack}', "Order@damage_order_receipt")->name('damage_order_receipt');
            Route::get('/print_pos_receipt/{slack}', "Order@print_pos_receipt")->name('print_pos_receipt');
            Route::get('/return_orders', "ReturnOrder@index")->name('return_orders');

            Route::get('/return_order_detail/{slack}', "ReturnOrder@detail")->name('return.order.detail');
            Route::get('/return_print_order/{slack}', "ReturnOrder@print_order")->name('return_print_order');
            Route::get('/return_print_pos_receipt/{slack}', "ReturnOrder@print_pos_receipt")->name('return_print_pos_receipt');
            Route::get('/orders/testprint', "Order@testprint")->name('testprint');

            /*Added by chandan*/
            Route::get('/add_pos', "Order@add_pos")->name('add_pos');
            Route::get('/pos/subcategories', "Order@subcategories");
            Route::get('/pos/products', "Order@products");




            //store
            Route::get('/stores', "Store@index")->name('stores');
            Route::get('/store/{slack}', "Store@detail")->name('store');
            Route::get('/add_store', "Store@add_store")->name('add_store');
            Route::get('/edit_store/{slack?}', "Store@add_store")->name('edit_store');
            Route::get('/select_store', "Store@select_store")->name('select_store');

            Route::get('/update_duplicate_taxId_on_stores/{key?}', "Store@updateDuplicateTaxIdOnStores")->name('select_store');

            //uploads
            Route::get('/import_data', "Import@index")->name('import_data');
            Route::get('/update_data', "Import@update_data")->name('update_data');

            //discount code
            Route::get('/discount_codes', "Discountcode@index")->name('discount_codes');
            Route::get('/discount_code/{slack}', "Discountcode@detail")->name('discount_code');
            Route::get('/add_discount_code', "Discountcode@add_discount_code")->name('add_discount_code');
            Route::get('/edit_discount_code/{slack?}', "Discountcode@add_discount_code")->name('edit_discount_code');

            //payment methods
            Route::get('/payment_methods', "PaymentMethod@index")->name('payment_methods');
            Route::get('/payment_method/{slack}', "PaymentMethod@detail")->name('payment_method');
            Route::get('/add_payment_method', "PaymentMethod@add_payment_method")->name('add_payment_method');
            Route::get('/edit_payment_method/{slack?}', "PaymentMethod@add_payment_method")->name('edit_payment_method');
            
            //language setting
            Route::get('/languages', "LanguageSetting@index")->name('lang.list');
            Route::get('/lang_setting/{slack}', "LanguageSetting@detail")->name('lang.detail');
            Route::get('/add_language', "LanguageSetting@add_edit_lang")->name('lang.add');
            Route::get('/edit_language/{slack?}', "LanguageSetting@add_edit_lang")->name('lang.edit');
            Route::get('/expresspay_setting', "Expresspay@index")->name('expresspay_setting');
            
            Route::get('/language/phrase/{slack}', "LanguageSetting@add_lang_phrase")->name('lang.phrase.list');
            
            Route::get('/add/language_phrase/{lang_slack}', "LanguageSetting@add_edit_lang_phrase")->name('add.lang.phrase');
            Route::get('/edit/language_phrase/{lang_slack}/{slack?}', "LanguageSetting@add_edit_lang_phrase")->name('edit.lang.phrase');



            //reports
            Route::get('/download_reports', "Report@index")->name('download_reports');
            Route::get('/best_seller_report', "Report@best_seller_report")->name('best_seller_report');
            Route::get('/day_wise_sale_report', "Report@day_wise_sale_report")->name('day_wise_sale_report');
            Route::get('/catgeory_report', "Report@catgeory_report")->name('catgeory_report');
            Route::get('/product_quantity_alert', "Report@product_quantity_alert")->name('product_quantity_alert');
            Route::get('/store_stock_chart', "Report@store_stock_chart")->name('store_stock_chart');
            Route::get('/tax_return_report', "Report@taxReturnReport")->name('tax_return_report');
            Route::get('/inventory_report', "Report@inventory_report")->name('inventory_report');
            Route::get('/payment_report', "Report@payment_report")->name('payment_report');
            /* Damage report */
            Route::get('/damage_reports', "Report@damage_report")->name('damage_reports');

            //setting email
            Route::get('/email_setting', "Setting@email_setting")->name('email_setting');
            Route::get('/edit_email_setting/{slack?}', "Setting@edit_email_setting")->name('edit_email_setting');

            //setting app
            Route::get('/app_setting', "Setting@app_setting")->name('app_setting');
            Route::get('/edit_app_setting', "Setting@edit_app_setting")->name('edit_app_setting');

            // quantity purchase
            Route::get('/quantity_purchases', "QuantityPurchase@index")->name('quantity_purchases');
            Route::get('/add_quantity_purchase', "QuantityPurchase@add_quantity_purchase")->name('add_quantity_purchase');
            Route::get('/edit_quantity_purchase/{slack?}', "QuantityPurchase@add_quantity_purchase")->name('edit_quantity_purchase');
            Route::get('/quantity_purchase/{slack}', "QuantityPurchase@detail")->name('quantity_purchase_detail');
            Route::get('/print_quantity_purchase/{slack}', "QuantityPurchase@print_quantity_purchase")->name('print_quantity_purchase');

            //purchase order
            Route::get('/purchase_orders', "PurchaseOrder@index")->name('purchase_orders');
            Route::get('/purchase_order/{slack}', "PurchaseOrder@detail")->name('purchase_order_detail');
            Route::get('/add_purchase_order', "PurchaseOrder@add_purchase_order")->name('add_purchase_order');
            Route::get('/edit_purchase_order/{slack?}', "PurchaseOrder@add_purchase_order")->name('edit_purchase_order');
            Route::get('/print_purchase_order/{slack}', "PurchaseOrder@print_purchase_order")->name('print_purchase_order');

            // Route::get('/purchase/subcategories', "PurchaseOrder@subcategories");
            Route::get('/purchase/products', "PurchaseOrder@products");

            //invoice
            Route::get('/invoices', "Invoice@index")->name('invoices');
            Route::get('/invoice/{slack}', "Invoice@detail")->name('invoice_detail');
            Route::get('/invoice_return_details/{slack}', "Invoice@invoice_return_details")->name('invoice_return_details');
            Route::get('/add_invoice', "Invoice@add_invoice")->name('add_invoice');
            Route::get('/edit_invoice/{slack?}', "Invoice@add_invoice")->name('edit_invoice');
            Route::get('/print_invoice/{slack}', "Invoice@print_invoice")->name('print_invoice');
            Route::get('/print_invoice_alt/{slack}', "Invoice@print_invoice_alt")->name('print_invoice_alt');
            Route::get('/purchase_invoice_detail/{slack}', "Invoice@purchase_invoice_detail")->name('purchase_invoice_detail');
            Route::get('/invoice_return_receipt/{slack}', "Invoice@invoice_return_receipt")->name('invoice_return_receipt');
            Route::get('/invoice_return', "InvoiceReturn@index")->name('invoice_return');
            Route::get('/invoices/generate_pdf/{slack}', 'Invoice@generate_invoice_return_pdf');

            //quotation
            Route::get('/quotations', "Quotation@index")->name('quotations');
            Route::get('/quotation/{slack}', "Quotation@detail")->name('quotation_detail');
            Route::get('/quotation_einvoice/{slack}', "Quotation@quotation_einvoice")->name('quotation_einvoice');
            Route::get('/add_quotation', "Quotation@add_quotation")->name('add_quotation');
            Route::get('/edit_quotation/{slack?}', "Quotation@add_quotation")->name('edit_quotation');
            Route::get('/print_quotation/{slack}', "Quotation@print_quotation")->name('print_quotation');
            Route::get('/print_quotation_alt/{slack}', "Quotation@print_quotation_alt")->name('print_quotation_alt');

            //payment gateway
            Route::get('/payment_gateway/{type}/{slack}', "Order@payment_gateway")->name('payment_gateway');

            //accounts
            Route::get('/accounts', "Account@index")->name('accounts');
            Route::get('/account/{slack}', "Account@detail")->name('account');
            Route::get('/add_account', "Account@add_account")->name('add_account');
            Route::get('/edit_account/{slack?}', "Account@add_account")->name('edit_account');

            //transaction
            Route::get('/transactions', "Transaction@index")->name('transactions');
            Route::get('/transaction/{slack}', "Transaction@detail")->name('transaction');
            Route::get('/add_transaction', "Transaction@add_transaction")->name('add_transaction');
            Route::get('/edit_transaction/{slack?}', "Transaction@add_transaction")->name('edit_transaction');

            //restaurant table
            Route::get('/tables', "Table@index")->name('tables');
            Route::get('/table/{slack}', "Table@detail")->name('table');
            Route::get('/add_table', "Table@add_table")->name('add_table');
            Route::get('/edit_table/{slack?}', "Table@add_table")->name('edit_table');

            //kitchen
            Route::get('/kitchen', "Kitchen@index")->name('kitchen');

            //Quantity Adjustments
            Route::get('/quantity-adjustments', "Product@quantity_adjustments")->name('quantity_adjustments');
            Route::get('/quantity-adjustment/{slack}', "Product@quantity_adjustment")->name('quantity_adjustment_edit');
            Route::get('/add-quantity-adjustment', "Product@add_quantity_adjustment")->name('add_quantity_adjustment');
            Route::get('/quantity-adjustment/view/{slack}', "Product@quantity_adjustment_view")->name("quantity_adjustment_view");

            //target
            Route::get('/targets', "Target@index")->name('targets');
            Route::get('/target/{slack}', "Target@detail")->name('target');
            Route::get('/add_target', "Target@add_target")->name('add_target');
            Route::get('/edit_target/{slack?}', "Target@add_target")->name('edit_target');

            //stock transfers
            Route::get('/stock_transfers', "StockTransfer@index")->name('stock_transfers');
            Route::get('/stock_transfer/{slack}', "StockTransfer@detail")->name('stock_transfer');
            Route::get('/add_stock_transfer', "StockTransfer@add_stock_transfer")->name('add_stock_transfer');
            Route::get('/edit_stock_transfer/{slack?}', "StockTransfer@add_stock_transfer")->name('edit_stock_transfer');
            Route::get('/verify_stock_transfer/{slack?}', "StockTransfer@verify_stock_transfer")->name('verify_stock_transfer');

            //stock return
            Route::get('/stock_returns', "StockReturn@index")->name('stock_returns');
            Route::get('/stock_return/{slack}', "StockReturn@detail")->name('stock_return_detail');
            Route::get('/add_stock_return', "StockReturn@add_stock_return")->name('add_stock_return');
            // Route::get('/edit_stock_return/{slack?}', "StockReturn@add_stock_return")->name('edit_stock_return');
            Route::get('/print_stock_return/{slack}', "StockReturn@print_stock_return")->name('print_stock_return');

            //notifications
            Route::get('/notifications', "Notification@index")->name('notifications');
            Route::get('/notification/{slack}', "Notification@detail")->name('notification');
            Route::get('/add_notification', "Notification@add_notification")->name('add_notification');
            Route::get('/edit_notification/{slack?}', "Notification@add_notification")->name('edit_notification');

            //business registers
            Route::get('/business_registers', "BusinessRegister@index")->name('business_registers');
            Route::get('/business_register/{slack}', "BusinessRegister@detail")->name('business_register');
            Route::get('/add_business_register', "BusinessRegister@add_business_register")->name('add_business_register');

            //setting sms
            Route::get('/sms_setting', "Setting@sms_setting")->name('sms_setting');
            Route::get('/edit_sms_setting/{slack?}', "Setting@edit_sms_setting")->name('edit_sms_setting');

            //sms templates
            Route::get('/sms_templates', "SmsTemplates@index")->name('sms_templates');
            Route::get('/sms_template/{slack}', "SmsTemplates@detail")->name('sms_template');
            Route::get('/edit_sms_template/{slack}', "SmsTemplates@add_sms_template")->name('add_sms_template');

            //billing counter
            Route::get('/billing_counters', "BillingCounter@index")->name('billing_counters');
            Route::get('/billing_counter/{slack}', "BillingCounter@detail")->name('billing_counter');
            Route::get('/add_billing_counter', "BillingCounter@add_billing_counter")->name('add_billing_counter');
            Route::get('/edit_billing_counter/{slack?}', "BillingCounter@add_billing_counter")->name('edit_billing_counter');

            //measurement unit
            Route::get('/measurement_units', "MeasurementUnit@index")->name('measurement_units');
            Route::get('/measurement_unit/{slack}', "MeasurementUnit@detail")->name('measurement_unit');
            Route::get('/add_measurement_unit', "MeasurementUnit@add_measurement_unit")->name('add_measurement_unit');
            Route::get('/edit_measurement_unit/{slack?}', "MeasurementUnit@add_measurement_unit")->name('edit_measurement_unit');

            //measurement unit
            Route::get('/measurement_units', "MeasurementUnit@index")->name('measurement_units');
            Route::get('/measurement_unit/{slack}', "MeasurementUnit@detail")->name('measurement_unit');
            Route::get('/add_measurement_unit', "MeasurementUnit@add_measurement_unit")->name('add_measurement_unit');
            Route::get('/edit_measurement_unit/{slack?}', "MeasurementUnit@add_measurement_unit")->name('edit_measurement_unit');

            // new measurement category
            Route::get('/measurement_categories', "MeasurementCategory@index")->name('measurement_categories');
            Route::get('/add_measurement_category', "MeasurementCategory@add_measurement_category")->name('add_measurement_category');
            Route::get('/edit_measurement_category/{slack?}', "MeasurementCategory@add_measurement_category")->name('edit_measurement_category');

            // new measurement unit
            Route::get('/measurements', "Measurement@index")->name('measurements');
            Route::get('/measurement/{slack}', "Measurement@detail")->name('measurement');
            Route::get('/add_measurement', "Measurement@add_measurement")->name('add_measurement');
            Route::get('/edit_measurement/{slack}', "Measurement@add_measurement")->name('edit_measurement');
            Route::get('/add_measurement_conversion/{slack}', "Measurement@add_measurement_conversion")->name('add_measurement_conversion');

            // Merchants

            Route::get('/merchants', "Merchant@index")->name('merchants');
            Route::get('/add_merchant', "Merchant@add_merchant")->name('add_merchant');
            Route::get('/edit_merchant/{slack}', "Merchant@add_merchant")->name('edit_merchant');
            Route::get('/merchant/{slack}', "Merchant@detail")->name('merchant');
            Route::get('/delete_merchant/{slack}', "Merchant@delete")->name('delete_merchant');

            // Subscriptions
            Route::get('/subscriptions', "Subscription@index")->name('subscriptions');
            Route::get('/subscription/{slack}', "Subscription@detail")->name('subscription');
            Route::get('/add_subscription', "Subscription@add_subscription")->name('add_subscription');
            Route::get('/edit_subscription/{slack}', "Subscription@add_subscription")->name('edit_subscription');
            Route::get('/manage_subscription_role/{slack}', "Subscription@manage_subscription_role")->name('manage_subscription_role');

            //News Subscription
            Route::get('/news_subscriptions', "Subscription@get_news_subscriptions")->name('news_subscriptions');

            //brand
            Route::get('/brands', "Brand@index")->name('brands');
            Route::get('/brand/{slack}', "Brand@detail")->name('brand');
            Route::get('/add_brand', "Brand@add_brand")->name('add_brand');
            Route::get('/edit_brand/{slack?}', "Brand@add_brand")->name('edit_brand');

            //waiter view
            Route::get('/waiter', "Kitchen@waiter")->name('waiter');


            // new modifiers
            Route::get('/modifiers', "Modifier@index")->name('modifiers');
            Route::get('/modifier/{slack}', "Modifier@detail")->name('modifier');
            Route::get('/add_modifier', "Modifier@add_modifier")->name('add_modifier');
            Route::get('/edit_modifier/{slack}', "Modifier@add_modifier")->name('edit_modifier');
            Route::get('/delete_modifier/{slack}', "Modifier@delete_modifier")->name('delete_modifier');

            // new modifier options
            Route::get('/modifier_options', "ModifierOption@index")->name('modifier_options');
            Route::get('/modifier_option/{slack}', "ModifierOption@detail")->name('modifier_option');
            Route::get('/add_modifier_option', "ModifierOption@add_modifier_option")->name('add_modifier_option');
            Route::get('/edit_modifier_option/{slack}', "ModifierOption@add_modifier_option")->name('edit_modifier_option');
            Route::get('/delete_modifier_option/{slack}', "ModifierOption@delete_modifier_option")->name('delete_modifier_option');
            //Qrcode
            Route::get('/qr_code', 'GenerateQrCode@index')->name('qr_code');

            // Sync Zid
            Route::get('/zid/stores', 'Zid@stores')->name('zid_store');
            Route::post('/zid/stores', 'Zid@activate')->name('zid_store_activate');
            Route::get('/zid/action', 'Zid@action')->name('zid_action');

            Route::get('/import_product', 'Import@add_import_product')->name('add_import_product');
            Route::post('/import_product', 'Import@save_import_product')->name('save_import_product');

            // abkhas integration
            Route::get('/user_points_settings', "ThirdPartyApiIntegration@show_user_points_settings")->name('user_points_settings');
            Route::get('/edit_user_points_setting/{slack?}', "ThirdPartyApiIntegration@edit_user_points_setting")->name('edit_user_points_setting');

            // bonat integration
            Route::get('/bonat_user_points_settings', "ThirdPartyApiIntegration@show_bonat_user_points_settings")->name('bonat_user_points_settings');
            Route::get('/edit_bonat_user_points_setting/{slack}', "ThirdPartyApiIntegration@edit_bonat_user_points_setting")->name('edit_bonat_user_points_setting');

            // bonat store counter integration

            Route::get('/store_counters', 'ThirdPartyApiIntegration@store_counters')->name('store_counters');
            Route::get('/edit_bonat_store_counter_points_setting/{slack}', "ThirdPartyApiIntegration@edit_bonat_store_counter_points_setting")->name('edit_bonat_store_counter_points_setting');
            Route::get('/bonat_store_counter_points_settings/{slack}', "ThirdPartyApiIntegration@show_bonat_store_counter_points_settings")->name('bonat_store_counter_points_settings');

            // prices
            Route::get('/prices', "Price@index")->name('prices');
            Route::get('/price/{slack}', "Price@detail")->name('price');
            Route::get('/add_price', "Price@add_price")->name('add_price');
            Route::get('/edit_price/{slack}', "Price@add_price")->name('edit_price');
            Route::get('/delete_price/{slack}', "Price@delete_price")->name('delete_price');

            //qoyod
            Route::get('/qoyod', "Qoyod@index")->name('qoyod');
        });


        Route::get('/order_public/{slack}', "Order@detail_public_view")->name('order_public');
        Route::get('/order_receipt/{slack}', "Order@order_receipt")->name('order_receipt');

        //Routes for C Panel
        Route::get('/execute_database_migrations', "Setting@cpanel_migrate");
        Route::get('/execute_create_storage_link', "Setting@cpanel_storage_link");
        Route::get('/execute_initial_configs', "Setting@cpanel_intial_config");

        // Additional routes for DB adjustments or configurations
        Route::get('/update_roles_for_admin', "Setting@update_roles_for_admin");
    });
}

Route::get('/test_request', function(){
    return 'success';
});

// Zid Webhook Routes
// Route::webhooks('zid/product_update_webhook');
Route::post('zid/product_update_webhook', 'Zid@product_update_webhook');
Route::post('zid/order_create_webhook', 'Zid@order_create_webhook');
Route::post('/expresspay/notification', 'ExpresspayController@notification');  

/* system config routes for helper functions */

// for running custom queries to merchant databases, here env refers to live or local 
Route::post('/run_alter_query_all_db', 'Setting@run_alter_query_all_db');
Route::get('/run_alter_query_single_db/{db}/{env}', 'Setting@run_alter_query_single_db');
Route::get('/generate_merchant_tax_type_report', 'Setting@generate_merchant_tax_type_report');
Route::get('/update_tax_setting/{db}/{env}', 'Setting@update_tax_setting');
Route::get('/reset_merchant_copy_menus', "Setting@reset_merchant_copy_menus");
