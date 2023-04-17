<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/authenticate_hrm_user/{slack}', "API\User@authenticate_hrm_user")->name('authenticate_hrm_user');

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/user/authenticate', 'API\User@authenticate');
    Route::post('/login_with_code', 'API\User@login_with_code');
    Route::post('/store_list_by_code', 'API\User@store_list_by_code');
    Route::post('/get_login_details', 'API\User@get_login_details');
    Route::post('/user/forgot_password', 'API\User@forgot_password');
    Route::post('/user/generate_login_code', 'API\User@generate_login_code');
    
    Route::post('/store/list_stores', 'API\Store@list_stores');
    Route::post('/user/reset_password', 'API\User@reset_password');
    Route::post('/user/language', 'API\User@user_language');
    Route::post('/filter_by_type', 'API\Category@index');
    
    Route::post('hyperpay/payment', 'API\HyperPayPaymentController@payment')->name('payment');
    Route::post('tabby/payment', 'API\TabbyController@payment')->name('payment');
    Route::post('tabby/newregistration', 'API\TabbyController@register')->name('register');
    Route::post('hyperpay/payment-status', 'API\HyperPayPaymentController@paymentStatus')->name('payment-status');
    Route::post('hyperpay/add_to_orders', 'API\HyperPayPaymentController@addToHyperpayOrders')->name('add_to_orders');
    Route::post('tabby/add_to_orders', 'API\TabbyController@add_to_orders')->name('tabby_add_to_orders');
    Route::post('/setProductListInSession', 'API\Merchant@setProductListInSession');
    Route::post('/verify-merchant', 'API\Merchant@verifyMerchant');
    Route::post('/webhook', 'API\TabbyController@webhook')->name('webhook_response');
});

Route::group(['middleware' => ['subdomain']], function () {


    Route::group(['middleware' => ['token_auth']], function () {
        //user
        Route::post('/users', 'API\User@index');
        Route::post('/add_user', 'API\User@store');
        Route::post('/update_user/{slack}', 'API\User@update')->name('update_user');
        Route::post('/reset_user_password/{slack}', 'API\User@reset_user_password');
        Route::post('/load_users', 'API\User@load_user_list');
        Route::post('/user/{slack}', 'API\User@show');
        Route::post('/user_list', 'API\User@list');
        Route::post('/logout', 'API\User@logout');

        //profile
        Route::post('/update_basic_profile', 'API\User@update_basic_profile')->name('update_basic_profile');
        Route::post('/update_password', 'API\User@update_password')->name('update_password');
        Route::post('/update_profile_image', 'API\User@update_profile_image')->name('update_profile_image');
        Route::post('/remove_profile_image', 'API\User@remove_profile_image')->name('remove_profile_image');
        Route::post('/update_profile_store', 'API\User@update_profile_store')->name('update_profile_store');
        Route::post('/update_profile_language', 'API\User@update_profile_language')->name('update_profile_language');

        //dashboard
        Route::post('/get_dashboard_stats', 'API\Dashboard@get_dashboard_stats');
        Route::post('/get_order_chart_stats', 'API\Dashboard@get_order_chart_stats');
        Route::post('/get_order_chart_timely_stats', 'API\Dashboard@get_order_chart_timely_stats');
        Route::post('/get_recent_trasactions', 'API\Dashboard@get_recent_trasactions');
        Route::post('/get_billing_counter_stats', 'API\Dashboard@get_billing_counter_stats');
        Route::post('/set_combined_stores', 'API\Dashboard@set_combined_stores');

        //role
        Route::post('/roles', 'API\Role@index');
        Route::post('/add_role', 'API\Role@store');
        Route::post('/update_role/{slack}', 'API\Role@update');
        Route::post('/role/{slack}', 'API\Role@show');
        Route::post('/role_list', 'API\Role@list');

        //customer
        Route::post('/customers', 'API\Customer@index');
        Route::post('/add_customer', 'API\Customer@store');
        Route::post('/update_customer/{slack}', 'API\Customer@update');
        Route::post('/load_customers', 'API\Customer@load_customer_list');
        Route::post('/customer/{slack}', 'API\Customer@show');
        Route::post('/customer_list', 'API\Customer@list');

        //category
        Route::post('/categories', 'API\Category@index');
        Route::post('/add_category', 'API\Category@store');
        Route::post('/add_maincategory', 'API\MainCategory@store');
        Route::post('/update_category/{slack}', 'API\Category@update');
        Route::post('/update_maincategory/{slack}', 'API\MainCategory@update');
        Route::post('/category/{slack}', 'API\Category@show');
        Route::post('/category/{slack}', 'API\Category@show');
        Route::post('/category_list', 'API\Category@list');
        Route::post('/set_category_order', 'API\Category@set_category_order');
        Route::post('/get_active_category', 'API\Category@get_active_category');

        Route::post('/load_categories_by_supplier', "API\Category@load_categories_by_supplier");
        Route::post('/load_subcategories', "API\Category@load_subcategories");
        Route::post('/load_products', "API\Product@load_products");
        Route::post('/load_products_by_category', "API\Product@load_products_by_category");
        Route::post('/get_products_by_category', "API\Product@get_products_by_category");
        Route::post('/get_products_by_category_with_pagination', "API\Product@get_products_by_category_with_pagination");
        Route::post('/load_pos_products_by_subcategory', "API\Product@load_pos_products_by_subcategory");
        Route::post('/add_quantity_adjustment', "API\Product@add_quantity_adjustment");
        Route::post('/quantity_adjustments', "API\Product@quantity_adjustments");

        //supplier
        Route::post('/suppliers', 'API\Supplier@index');
        Route::post('/add_supplier', 'API\Supplier@store');
        Route::post('/update_supplier/{slack}', 'API\Supplier@update');
        Route::post('/load_supplier_list', 'API\Supplier@load_supplier_list');
        Route::post('/supplier/{slack}', 'API\Supplier@show');
        Route::post('/supplier_list', 'API\Supplier@list');

        //product
        Route::post('/products', 'API\Product@index');
        Route::post('/add_product', 'API\Product@store');
        Route::post('/update_product/{slack}', 'API\Product@update');
        Route::post('/get_product', 'API\Product@get_product');
        Route::post('/get_product_modifier_options', 'API\Product@get_product_modifier_options');
        Route::post('/generate_barcodes', 'API\Product@generate_barcodes');
        Route::post('/load_product_for_po', 'API\Product@load_product_for_po');
        Route::post('/load_product_for_stock_transfer', 'API\Product@load_product_for_stock_transfer');
        Route::post('/product/{slack}', 'API\Product@show');
        Route::post('/product_list', 'API\Product@list');
        Route::post('/list_measurement_for_product','API\Product@list_measurements');
        Route::post('/list_measurement_category_for_product','API\Product@list_measurement_categories');
        Route::post('/list_main_categories','API\Product@list_main_categories');
        Route::post('/list_discounts','API\Product@list_discounts');
        Route::post('/delete_product_image', 'API\Product@delete_product_image');
        Route::post('/load_ingredients', 'API\Product@load_ingredients');
        Route::post('/get_product_detail', 'API\Product@get_product_detail');
        Route::post('/get_active_product', 'API\Product@get_active_product');
        Route::post('/get_product_modifiers', 'API\Product@get_product_modifiers');
        Route::post('/load_pos_products', 'API\Product@load_pos_products');
        Route::post('/load_pos_products_by_subcategory', 'API\Product@load_pos_products_by_subcategory');
        Route::post('/load_pos_products_by_keyword', 'API\Product@load_pos_products_by_keyword');
        Route::post('/generate_product_barcode', 'API\Product@generate_product_barcode');
        Route::post('/check_product_quantity', 'API\Product@check_product_quantity');


        /* added later */
        Route::post('/add_ingredient', 'API\Product@store_ingredient');
        Route::post('/update_ingredient/{slack}', 'API\Product@update_ingredient');
        Route::post('/check_cart_ingredient_stock', 'API\Product@check_cart_ingredient_stock');

        //combo group
        Route::post('/combo_groups', "API\ComboGroup@index");
        Route::post('/add_combo_group', "API\ComboGroup@store");
        Route::post('/update_combo_group/{slack}', "API\ComboGroup@update");
        
        Route::post('/combos', "API\Combo@index");
        Route::post('/add_combo', "API\Combo@store");
        Route::post('/update_combo/{slack}', "API\Combo@update");
        Route::post('/combo_list', 'API\Combo@list');
        
        //tax code
        Route::post('/tax_codes', 'API\Taxcode@index');
        Route::post('/add_tax_code', 'API\Taxcode@store');
        Route::post('/update_tax_code/{slack}', 'API\Taxcode@update');
        Route::post('/tax_code/{slack}', 'API\Taxcode@show');
        Route::post('/tax_code_list', 'API\Taxcode@list');

        //tax name master
        Route::post('/tax_names', 'API\TaxNameMaster@index');
        Route::post('/add_tax_name', 'API\TaxNameMaster@store');
        Route::post('/update_tax_name/{slack}', 'API\TaxNameMaster@update');
        Route::post('/tax_name/{slack}', 'API\TaxNameMaster@show');
        Route::post('/tax_name_list', 'API\TaxNameMaster@list');

        //order
        Route::post('/orders', 'API\Order@index');
        Route::post('/add_order', 'API\Order@store');
        Route::post('/cancel_order', 'API\Order@cancel_order');
        Route::post('/get_order_list', 'API\Order@get_order_list');
        Route::post('/get_order_receipt', 'API\Order@get_order_receipt');
        Route::post('/return_order_list', 'API\Order@return_order_list');
        Route::post('/order_list_by_date', 'API\Order@order_list_by_date');
        Route::post('/return_orders', 'API\ReturnOrder@index');
        Route::post('/add_damage_order', 'API\Report@add_damage_order');
        Route::post('/damage_orders', 'API\Report@damage_orders');
        Route::post('/return_order/generate_pdf', 'API\ReturnOrder@generate_return_order_pdf');
        Route::post('/return_order', 'API\Order@return_order');
        Route::post('/customer_order_invoice', 'API\Order@customer_order_invoice');
        Route::post('/get_order_pending_payment_data/{slack}', 'API\Order@get_order_pending_payment_data');
        Route::post('/order/generate_pdf', 'API\Order@generate_order_pdf');
        Route::post('/damage_order/generate_pdf', 'API\Report@generate_damage_order_pdf');

        Route::post('/order/partial_pay', 'API\Order@orderPartialPayment');
        Route::post('/update_order/{slack}', 'API\Order@update');
        Route::post('/delete_order/{slack}', 'API\Order@destroy');
        Route::post('/get_hold_list', 'API\Order@get_hold_list');
        Route::post('/get_in_kitchen_order_list', 'API\Order@get_in_kitchen_order_list');
        Route::post('/update_kitchen_order_status', 'API\Order@update_kitchen_order_status');
        Route::post('/get_register_order_amount', 'API\Order@get_register_order_amount');
        Route::post('/get_running_order_list', 'API\Order@get_running_order_list');
        Route::post('/share_invoice_sms/{slack}', 'API\Order@share_invoice_sms');
        Route::post('/order/{slack}', 'API\Order@show');
        Route::post('/order_list', 'API\Order@list');
        Route::post('/get_waiter_order_list', 'API\Order@get_waiter_order_list');
        Route::post('/update_kitchen_item_status', 'API\Order@update_kitchen_item_status');
        Route::post('/close_order_from_kitchen', 'API\Order@close_order_from_kitchen');

        //store
        Route::post('/stores', 'API\Store@index');
        Route::post('/add_store', 'API\Store@store');
        Route::post('/update_store/{slack}', 'API\Store@update');
        Route::post('/store/{slack}', 'API\Store@show');
        Route::post('/store_list', 'API\Store@list');
        Route::post('/switch_store', 'API\Store@switch');
        Route::post('/get_store_tax_details', 'API\Store@getStoreTaxDetails');
        Route::post('/store_tax_details_update/{slack}', 'API\Store@taxDetailsUpdate');
        Route::post('/update_tax_setting_for_all_stores', 'API\Store@update_tax_setting_for_all_stores');

        //import
        Route::post('/import_data', 'API\Import@index');
        Route::post('/update_data', 'API\Import@update_data');
        Route::post('/download_reference_sheet', 'API\Import@generate_reference_sheet');

        //discount code
        Route::post('/discount_codes', 'API\Discountcode@index');
        Route::post('/add_discount_code', 'API\Discountcode@store');
        Route::post('/update_discount_code/{slack}', 'API\Discountcode@update');
        Route::post('/discount_code/{slack}', 'API\Discountcode@show');
        Route::post('/discount_code_list', 'API\Discountcode@list');
        Route::post('/get_cashier_discounts', 'API\Discountcode@get_cashier_discount');

        //payment method
        Route::post('/payment_methods', 'API\PaymentMethod@index');
        Route::post('/add_payment_method', 'API\PaymentMethod@store');
        Route::post('/update_payment_method/{slack}', 'API\PaymentMethod@update');
        Route::post('/payment_method/{slack}', 'API\PaymentMethod@show');
        Route::post('/payment_method_list', 'API\PaymentMethod@list');
        Route::post('/load_payment_options', 'API\PaymentMethod@load_payment_options');

        //language setting

        Route::post('/languages', 'API\LanguageSetting@index');
        Route::post('/add_lang_setting', 'API\LanguageSetting@store');
        Route::post('/update_lang_setting/{slack}', 'API\LanguageSetting@update');
        Route::post('/lang_setting/{slack}', 'API\LanguageSetting@show');
        Route::post('/lang_setting_list', 'API\LanguageSetting@list');

        Route::post('/language/phrase/{slack}', 'API\LanguageSetting@phrase');
        Route::post('/add_lang_setting_phrase', 'API\LanguageSetting@add_phrase');
        Route::post('/update_lang_setting_phrase/{slack}', 'API\LanguageSetting@update_phrase');

        //reports
        Route::post('/user_report', 'API\Report@user_report');
        Route::post('/user_wise_sales_report', 'API\Report@user_wise_sales_report');
        Route::post('/tax_return_report', 'API\Report@taxReturnReport');
        Route::post('/tax_return_report_excel', 'API\Report@taxReturnReportExcel');
        Route::post('/category_report', 'API\Report@category_report');
        Route::post('/customer_report', 'API\Report@customer_report');
        Route::post('/supplier_report', 'API\Report@supplier_report');
        Route::post('/taxcode_report', 'API\Report@taxcode_report');
        Route::post('/discountcode_report', 'API\Report@discountcode_report');
        Route::post('/product_report', 'API\Report@product_report');
        Route::post('/product_wise_sales_report', 'API\Report@product_wise_sales_report');
        Route::post('/store_report', 'API\Report@store_report');
        Route::post('/order_report', 'API\Report@order_report');
        Route::post('/purchase_order_report', 'API\Report@purchase_order_report');
        Route::post('/invoice_report', 'API\Report@invoice_report');
        Route::post('/quotation_report', 'API\Report@quotation_report');
        Route::post('/transaction_report', 'API\Report@transaction_report');
        Route::post('/get_trending_products', 'API\Report@get_trending_products');
        Route::post('/get_category_performance', 'API\Report@get_category_performance');
        Route::post('/get_payment_data', 'API\Report@get_payment_data');
        Route::post('/product_alert_report', 'API\Report@product_alert_report');
        Route::post('/store_stock_chart', 'API\Report@store_stock_chart');
        Route::post('/pos_order_report', 'API\Report@pos_order_report');
        Route::post('/damage_order_report', 'API\Report@damage_order_report');
        Route::post('/return_order_report', 'API\Report@return_order_report');
        Route::post('/return_order_list_report', 'API\Report@returnOrderListReport');
        Route::post('/stock_status_report', 'API\Report@stock_status_report');
        Route::post('/quantity_purchase_report', 'API\Report@quantity_purchase_report');
        Route::post('/supplier_invoice_report', 'API\Report@supplier_invoice_report');
        Route::post('/sales_report', 'API\Report@sales_report');
        Route::post('/category_wise_product_sales_report', 'API\Report@category_wise_product_sales_report');
        Route::post('/invoice_return_report', 'API\Report@invoice_return_report');
        Route::post('/inventory_report', 'API\InventoryReport@index');
        Route::post('/inventory_report/download_excel', 'API\InventoryReport@download_excel');
        Route::post('/tax_report', 'API\Report@tax_report');
        Route::post('/tax_report_pdf', 'API\Report@tax_report_pdf');
        // quantity purchase
        Route::post('/quantity_purchases', 'API\QuantityPurchase@index');
        Route::post('/add_quantity_purchase', 'API\QuantityPurchase@store');
        Route::post('/update_quantity_purchase/{slack}', 'API\QuantityPurchase@update_quantity_purchase');
        Route::post('/delete_quantity_purchase/{slack}', 'API\QuantityPurchase@destroy');

        //purchase order
        Route::post('/purchase_orders', 'API\PurchaseOrder@index');
        Route::post('/add_purchase_order', 'API\PurchaseOrder@store');
        Route::post('/update_purchase/{slack}', 'API\PurchaseOrder@update_purchase');
        Route::post('/update_purchase_order/{slack}', 'API\PurchaseOrder@update');
        Route::post('/update_po_status/{slack}', 'API\PurchaseOrder@update_po_status');
        Route::post('/delete_purchase_order/{slack}', 'API\PurchaseOrder@destroy');
        Route::post('/purchase_order/{slack}', 'API\PurchaseOrder@show');
        Route::post('/purchase_order_list', 'API\PurchaseOrder@list');
        Route::post('/generate_invoice_from_po/{slack}', 'API\PurchaseOrder@generate_invoice_from_po');
        Route::post('/purchase_orders/load_products', 'API\PurchaseOrder@load_products');
        Route::post('/purchase_orders/load_products_by_store', 'API\PurchaseOrder@load_products_by_store');


        //setting
        Route::post('/add_setting_email', 'API\Setting@add_setting_email');
        Route::post('/update_setting_email/{slack}', 'API\Setting@update_setting_email');
        Route::post('/total_dropdown_list', "API\Setting@total_dropdown_list")->name('total_dropdown_list');

        Route::post('/update_setting_app', 'API\Setting@update_setting_app');
        Route::post('/remove_company_logo', 'API\Setting@remove_company_image');

        Route::post('/add_setting_sms', 'API\Setting@add_setting_sms');
        Route::post('/update_setting_sms/{slack}', 'API\Setting@update_setting_sms');

        //search
        Route::post('/filter_orders', 'API\Order@filter_orders');
        Route::post('/filter_customers', 'API\Customer@filter_customers');
        Route::post('/filter_single_customer', 'API\Customer@filter_single_customer');
        Route::post('/filter_purchase_orders', 'API\PurchaseOrder@filter_purchase_orders');
        Route::post('/filter_users', 'API\User@filter_users');
        Route::post('/filter_invoices', 'API\Invoice@filter_invoices');
        Route::post('/filter_quotations', 'API\Quotation@filter_quotations');
        Route::post('/filter_transactions', 'API\Transaction@filter_transactions');
        Route::post('/transaction/generate_pdf', 'API\Transaction@generate_pdf');

        //invoice
        Route::post('/invoices', 'API\Invoice@index');
        Route::post('/add_invoice', 'API\Invoice@store');
        Route::post('/quotation_to_invoice/{slack}', 'API\Invoice@quotationToInvoice')->name('quotation_to_invoice');
        Route::post('/add_invoice_service', 'API\Invoice@store_service');
        Route::post('/update_invoice/{slack}', 'API\Invoice@update');
        Route::post('/update_invoice_status/{slack}', 'API\Invoice@update_invoice_status');
        Route::post('/load_bill_to_list', 'API\Invoice@load_bill_to_list');
        Route::post('/delete_invoice/{slack}', 'API\Invoice@destroy');
        Route::post('/get_invoice_pending_payment_data/{slack}', 'API\Invoice@get_invoice_pending_payment_data');
        Route::post('/invoice/{slack}', 'API\Invoice@show');
        Route::post('/invoice_list', 'API\Invoice@list');
        Route::post('/update_invoice_color_code', 'API\Invoice@update_color_code');
        Route::post('/invoice_return_list', 'API\Invoice@invoice_return_list');
        Route::post('/invoice_return', 'API\InvoiceReturn@index');
        Route::post('/generate_invoice_return_pdf', 'API\InvoiceReturn@generate_invoice_return_pdf');

        //quotation
        Route::post('/quotations', 'API\Quotation@index');
        Route::post('/add_quotation', 'API\Quotation@store');
        Route::post('/update_quotation/{slack}', 'API\Quotation@update');
        Route::post('/update_quotation_status/{slack}', 'API\Quotation@update_quotation_status');
        Route::post('/delete_quotation/{slack}', 'API\Quotation@destroy');
        Route::post('/quotation/{slack}', 'API\Quotation@show');
        Route::post('/quotation_list', 'API\Quotation@list');

        //payment gateway
        Route::post('/get_stripe_payment_intent', 'API\PaymentGateway@get_stripe_payment_intent');
        Route::post('/record_stripe_payment_success', 'API\PaymentGateway@record_stripe_payment_success');
        Route::post('/get_paypal_order_data', 'API\PaymentGateway@get_paypal_order_data');

        //account
        Route::post('/accounts', 'API\Account@index');
        Route::post('/add_account', 'API\Account@store');
        Route::post('/update_account/{slack}', 'API\Account@update');
        Route::post('/account/{slack}', 'API\Account@show');
        Route::post('/account_list', 'API\Account@list');

        //transactions
        Route::post('/transactions', 'API\Transaction@index');
        Route::post('/add_transaction', 'API\Transaction@store');
        Route::post('/update_transaction/{slack}', 'API\Transaction@update');
        Route::post('/delete_transaction/{slack}', 'API\Transaction@destroy');
        Route::post('/transaction/{slack}', 'API\Transaction@show');
        Route::post('/transaction_list', 'API\Transaction@list');
        Route::post('/add_expresspay_transaction', 'API\Transaction@store_expresspay');
        Route::post('/send_transaction_sms', "API\Transaction@send_transaction_sms")->name('send_transaction_sms');

        //tables
        Route::post('/tables', 'API\Table@index');
        Route::post('/add_table', 'API\Table@store');
        Route::post('/update_table/{slack}', 'API\Table@update');
        Route::post('/delete_table/{slack}', 'API\Table@destroy');
        Route::post('/table/{slack}', 'API\Table@show');
        Route::post('/table_list', 'API\Table@list');

        //target
        Route::post('/targets', 'API\Target@index');
        Route::post('/add_target', 'API\Target@store');
        Route::post('/update_target/{slack}', 'API\Target@update');
        Route::post('/delete_target/{slack}', 'API\Target@destroy');
        Route::post('/target/{slack}', 'API\Target@show');
        Route::post('/target_list', 'API\Target@list');

        //stock transfer
        Route::post('/stock_transfers', 'API\StockTransfer@index');
        Route::post('/add_stock_transfer', 'API\StockTransfer@store');
        Route::post('/update_stock_transfer/{slack}', 'API\StockTransfer@update');
        Route::post('/delete_stock_transfer/{slack}', 'API\StockTransfer@destroy');
        Route::post('/reject_stock_transfer_product/{slack}', 'API\StockTransfer@reject_stock_transfer_product');
        Route::post('/merge_product_stock', 'API\StockTransfer@merge_product_stock');
        Route::post('/stock_transfer/{slack}', 'API\StockTransfer@show');
        Route::post('/stock_transfer_list', 'API\StockTransfer@list');

        //stock return
        Route::post('/stock_returns', 'API\StockReturn@index');
        Route::post('/add_stock_return', 'API\StockReturn@store');
        Route::post('/update_stock_return/{slack}', 'API\StockReturn@update');
        Route::post('/delete_stock_return/{slack}', 'API\StockReturn@destroy');
        Route::post('/stock_return/{slack}', 'API\StockReturn@show');
        Route::post('/stock_return_list', 'API\StockReturn@list');

        //notification
        Route::post('/notifications', 'API\Notification@index');
        Route::post('/add_notification', 'API\Notification@store');
        Route::post('/delete_notification/{slack}', 'API\Notification@destroy');
        Route::post('/load_notification', 'API\Notification@load_notification');
        Route::post('/notification/{slack}', 'API\Notification@show');
        Route::post('/notification_list', 'API\Notification@list');

        //business registers
        Route::post('/business_registers', 'API\BusinessRegister@index');
        Route::post('/open_register', 'API\BusinessRegister@open_register');
        Route::post('/close_register', 'API\BusinessRegister@close_register');
        Route::post('/register_by_date', 'API\BusinessRegister@register_by_date');
        Route::post('/delete_register/{slack}', 'API\BusinessRegister@destroy');
        Route::post('/business_register/{slack}', 'API\BusinessRegister@show');
        Route::post('/business_register_list', 'API\BusinessRegister@list');

        //sms templates
        Route::post('/sms_templates', 'API\SmsTemplate@index');
        Route::post('/update_sms_template/{slack}', 'API\SmsTemplate@update');

        //billing_counter
        Route::post('/billing_counters', 'API\BillingCounter@index');
        Route::post('/add_billing_counter', 'API\BillingCounter@store');
        Route::post('/update_billing_counter/{slack}', 'API\BillingCounter@update');
        Route::post('/billing_counter/{slack}', 'API\BillingCounter@show');
        Route::post('/billing_counter_list', 'API\BillingCounter@list');

        //inventory_count
        Route::post('/inventory-count/generate-view', "API\InventoryCount@generate_view");
        Route::post('/inventory_count/add_inventory_count', 'API\InventoryCount@add_inventory_count');
        Route::post('/inventory_count/get_filter_data', 'API\InventoryCount@get_filter_data');
        Route::post('/inventory_count/get_inventory_count_data', 'API\InventoryCount@get_inventory_count_data');
        Route::post('/inventory_count/get_inventory_count_item_data', 'API\InventoryCount@get_inventory_count_item_data');
        Route::post('/inventory_count/get_items/{id?}', 'API\InventoryCount@get_items');
        Route::post('/inventory_count/add_item', 'API\InventoryCount@add_item');
        Route::post('/inventory_count/change_branch', 'API\InventoryCount@change_branch');
        Route::post('/inventory_count/add_quantities', 'API\InventoryCount@add_quantities');
        Route::post('/inventory_count/delete', 'API\InventoryCount@delete_inventory_count');
        Route::post('/inventory_count/submit', 'API\InventoryCount@submit_inventory_count');

        //masters
        Route::post('/get_billing_master_account_type', 'API\Master@get_billing_master_account_type');
        Route::post('/get_billing_type', 'API\Master@get_billing_type');
        Route::post('/get_master_invoice_print_type', 'API\Master@get_master_invoice_print_type');
        Route::post('/get_master_order_type', 'API\Master@get_master_order_type');
        Route::post('/get_master_status', 'API\Master@get_master_status');
        Route::post('/get_master_transaction_type', 'API\Master@get_master_transaction_type');

        //measurement unit
        Route::post('/measurement_units', 'API\MeasurementUnit@index');
        Route::post('/add_measurement_unit', 'API\MeasurementUnit@store');
        Route::post('/update_measurement_unit/{slack}', 'API\MeasurementUnit@update');
        Route::post('/measurement_unit/{slack}', 'API\MeasurementUnit@show');
        Route::post('/measurement_unit/{slack}', 'API\MeasurementUnit@show');
        Route::post('/measurement_unit_list', 'API\MeasurementUnit@list');
        
        // new measurement categories
        Route::post('/measurement_categories', 'API\MeasurementCategory@index');
        Route::post('/add_measurement_category', 'API\MeasurementCategory@store');
        Route::post('/update_measurement_category/{slack}', 'API\MeasurementCategory@update');
        
        // new measurements
        Route::post('/measurements', 'API\Measurement@index');
        Route::post('/get_conversion_units', 'API\Measurement@get_conversion_units');
        Route::post('/add_measurement', 'API\Measurement@store');
        Route::post('/update_measurement/{slack}', 'API\Measurement@update');
        Route::post('/add_measurement_conversion', 'API\Measurement@store_conversion');
        Route::post('/update_measurement_conversion', 'API\Measurement@update_conversion');
        Route::post('/load_measurements', 'API\Measurement@load_measurements');
        Route::post('/load_measurements_for_ingredient', 'API\Measurement@load_measurements_for_ingredient');
        Route::post('/measurement_with_conversions', 'API\Measurement@measurement_with_conversions');

        // merchants
        Route::post('/merchants', 'API\Merchant@index');

        // subscriptions
        Route::post('/subscriptions', 'API\Subscription@index');
        Route::post('/add_subscription', 'API\Subscription@store');
        Route::post('/update_subscription/{slack}', 'API\Subscription@update');
        Route::post('/update_subscription_role/{slack}', 'API\Subscription@update_role');
        Route::post('/news_subscriptions_report', 'API\Report@news_subscriptions_report');
        Route::post('/get_news_subscriptions', 'API\Subscription@get_news_subscriptions');

        // new ingredient routes
        Route::post('/calculate_ingredient_cost', 'API\Product@calculate_ingredient_cost');

        //brand
        Route::post('/brands', 'API\Brand@index');
        Route::post('/add_brand', 'API\Brand@store');
        Route::post('/update_brand/{slack}', 'API\Brand@update');
        Route::post('/brand/{slack}', 'API\Brand@show');
        Route::post('/brand/{slack}', 'API\Brand@show');
        Route::post('/brand_list', 'API\Brand@list');

        // API Calls
        Route::post('/get_sub_menus', 'API\User@get_sub_menus');
        Route::post('/load_suppliers', 'API\Supplier@load_suppliers');
        Route::post('/load_customers', 'API\Customer@load_customers');

        // new modifiers
        Route::post('/modifiers', 'API\Modifier@index');
        Route::post('/add_modifier', 'API\Modifier@store');
        Route::post('/update_modifier/{slack}', 'API\Modifier@update');

        // new modifier options
        Route::post('/modifier_options', 'API\ModifierOption@index');
        Route::post('/add_modifier_option', 'API\ModifierOption@store');
        Route::post('/update_modifier_option/{slack}', 'API\ModifierOption@update');

        //return order invoice
        // Route::post('/return_order_report', 'API\ReportReturn@return_order_report');
        //qr code
        Route::post('/create_qr_code', 'API\GenerateQrCode@create_qr_code');
        //Sync Data
        Route::post('/sync_category_product', 'API\GenerateQrCode@sync_category_product');

        // Zid
        Route::post('/zid/sync', 'API\Zid@sync');

        // Abkhas
        Route::post('/add_setting_user_points', 'API\ThirdPartyApiIntegration@add_setting_user_points');
        Route::post('/update_setting_user_points/{slack}', 'API\ThirdPartyApiIntegration@update_setting_user_points');

        // bonat

        Route::post('/add_setting_bonat_user_points', 'API\ThirdPartyApiIntegration@add_setting_bonat_user_points');

        Route::post('/update_setting_bonat_user_points/{slack}', 'API\ThirdPartyApiIntegration@update_setting_bonat_user_points');
        Route::post('/verify_bonat_merchant_setting', 'API\ThirdPartyApiIntegration@verify_bonat_merchant_setting');
        Route::post('/store_counters', 'API\ThirdPartyApiIntegration@store_counters');
        Route::post('/add_bonat_store_counter_setting', 'API\ThirdPartyApiIntegration@add_bonat_store_counter_setting');
        Route::post('/update_bonat_store_counter_setting/{slack}', 'API\ThirdPartyApiIntegration@update_bonat_store_counter_setting');
        Route::post('/verify_store_bonat_merchant_setting', 'API\ThirdPartyApiIntegration@verify_store_bonat_merchant_setting');
        Route::post('/verify_bonat_coupon', 'API\ThirdPartyApiIntegration@verify_coupon_details');
    
        Route::post('/order_list_by_modified_date', 'API\Order@order_list_by_modified_date')->name('order_list_by_modified_date');
        Route::post('/product_list_by_modified_date', 'API\Product@product_list_by_modified_date')->name('product_list_by_modified_date');

        /* Mobile Cashier */
        //order
        Route::post('/mobile_cashiers', 'API\MobileCashier@index');
        Route::post('/add_mobile_cashier', 'API\MobileCashier@store');
        Route::post('/assign_mobile_cashier', 'API\MobileCashier@assign');

        // prices
        Route::post('/prices', 'API\Price@index');
        Route::post('/add_price', 'API\Price@store');
        Route::post('/update_price/{slack}', 'API\Price@update');
        Route::post('/update_price_id', 'API\Price@update_price_id');
        Route::post('/price/status', 'API\Price@get_status');
        Route::post('/price/status/update', 'API\Price@update_status');
        Route::post('/list_prices', 'API\Price@list');
        
        //Qoyod
        Route::post('/add_qoyod','API\Qoyod@store');
        Route::post('/sync_qoyod_data','API\Qoyod@sync_qoyod_data');
        Route::post('/async_qoyod_data','API\Qoyod@async_qoyod_data');
        Route::post('/initiate_edfa_transaction','API\EdfaTransaction@store');
        Route::post('/update_edfa_transaction_status','API\EdfaTransaction@update_status');
        Route::post('/check_edfa_transaction_status','API\EdfaTransaction@check_transaction_status');
        
        // Other APIs
        Route::post('/get_updated_api_list','API\Other@get_updated_api_list');

        //For Merchant Tickets
        Route::post('/merchant_support_tickets','API\Other@merchant_support_tickets');
        Route::post('/merchant_support_tickets/{id}','API\Other@merchant_support_tickets_detail');
        Route::post('/add_merchant_support_tickets','API\Other@add_merchant_support_tickets');
    });
});

// Wosul Website API'S
Route::post('is_email_exists', 'MerchantController@is_email_exists')->name('is_email_exists');
Route::post('is_company_url_exists', 'MerchantController@is_company_url_exists')->name('is_company_url_exists');
Route::post('get_subscription_detail', 'MerchantController@get_subscription_detail')->name('get_subscription_detail');
Route::post('demo_request/store', 'API\Website\Home@store')->name('save_demo_request');
Route::post('survey/store', 'SurveyController@store')->name('save_survey');
Route::post('campaign/store', 'CampaignController@store')->name('save_campaign');
Route::post('promoter/store', 'PromoterController@store')->name('save_promoter');
Route::post('dulani/store', 'DulaniController@store')->name('save_dulani');
Route::post('registration_form/store', 'RegisterationFormController@store')->name('save_registration_form');

// central api to know application update status of each merchant
Route::post('update_installation', 'MerchantController@update_installation')->name('update_installation');
Route::post('get_app_version', 'API\Setting@get_app_version')->name('get_app_version');

// for transfering products from one branch to store, along with ingredients
Route::post('/copy_products_and_ingredients_between_stores', 'API\Setting@copy_products_and_ingredients_between_stores');

Route::get('/bonat/categories', 'API\ThirdPartyApiIntegration@categories')->name('bonat.categories');
Route::get('/bonat/products', 'API\ThirdPartyApiIntegration@products')->name('bonat.products');
Route::get('/bonat/devices', 'API\ThirdPartyApiIntegration@devices')->name('bonat.devices');
Route::get('/bonat/branches', 'API\ThirdPartyApiIntegration@branches')->name('bonat.branches');
Route::post('/track_whatsapp_page_visits', 'ContactController@track_whatsapp_page_visits');
Route::post('/reset_admin_menus', 'API\Setting@reset_admin_menus');
Route::post('/expresspay_setting', 'API\Expresspay@setting');

