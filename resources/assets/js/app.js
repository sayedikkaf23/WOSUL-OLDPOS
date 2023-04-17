/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

"use strict";

require("./bootstrap");

window.Vue = require("vue");

window.JQuery = require("jquery");

import VeeValidate, { Validator } from "vee-validate";

import { mixin } from "./mixin";

import { event_bus } from "./event_bus.js";

import Notifications from "vue-notification";

import { dictionary } from "./validation_custom_message";

import { i18n, language, messages } from "./localization.js";

import { commonFunctions } from "./common_function.js";

import VueToastr from "vue-toastr";

Vue.use(VueToastr);

Vue.use(VeeValidate);

Vue.use(Notifications);

Vue.mixin(mixin);

Vue.use(require("vue-shortkey"));

//Validator.localize('en', dictionary.en);

Validator.localize(language, dictionary[language]);

/*
 * Common Function Library
 */

Vue.use(commonFunctions);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//commons
Vue.component(
  "priceswitchcomponent",
  require("./components/commons/price_switch_component.vue").default
);
Vue.component(
  "modalcomponent",
  require("./components/commons/modal_component.vue").default
);
Vue.component(
  "quickpanelcomponent",
  require("./components/commons/quickpanel_component.vue").default
);
Vue.component(
  "storeselectorcomponent",
  require("./components/commons/store_selector_component.vue").default
);
Vue.component(
  "searchcomponent",
  require("./components/search/search_component.vue").default
);
Vue.component(
  "languageswitchercomponent",
  require("./components/commons/language_switcher_component.vue").default
);
Vue.component(
  "notificationcomponent",
  require("./components/commons/notification_component.vue").default
);

Vue.component(
  "signincomponent",
  require("./components/entry/sign_in_component.vue").default
);
Vue.component(
  "forgotpasswordcomponent",
  require("./components/entry/forgot_password_component.vue").default
);
Vue.component(
  "resetpasswordcomponent",
  require("./components/entry/reset_password_component.vue").default
);

Vue.component(
  "dashboardcomponent",
  require("./components/dashboard/dashboard_component.vue").default
);
Vue.component(
  "billingcounterdashboardcomponent",
  require("./components/dashboard/billing_counter_dashboard_component.vue")
    .default
);

Vue.component(
  "addusercomponent",
  require("./components/user/add_user_component.vue").default
);
Vue.component(
  "userdetailcomponent",
  require("./components/user/user_detail_component.vue").default
);

Vue.component(
  "profilecomponent",
  require("./components/user/profile_component.vue").default
);
Vue.component(
  "editprofilecomponent",
  require("./components/user/edit_profile_component.vue").default
);

Vue.component(
  "addrolecomponent",
  require("./components/role/add_role_component.vue").default
);
Vue.component(
  "roledetailcomponent",
  require("./components/role/role_detail_component.vue").default
);

Vue.component(
  "addcustomercomponent",
  require("./components/customer/add_customer_component.vue").default
);
Vue.component(
  "customerdetailcomponent",
  require("./components/customer/customer_detail_component.vue").default
);
Vue.component(
    "customerstatisticscomponent",
    require("./components/customer/customer_statistics_component.vue").default
);

Vue.component(
  "addcategorycomponent",
  require("./components/category/add_category_component.vue").default
);
Vue.component(
  "addmaincategorycomponent",
  require("./components/category/add_maincategory_component.vue").default
);
Vue.component(
  "categorydetailcomponent",
  require("./components/category/category_detail_component.vue").default
);
Vue.component(
  "maincategorydetailcomponent",
  require("./components/category/main_category_detail_component.vue").default
);

Vue.component(
  "inventorycountcomponent",
  require("./components/inventory/inventory_count_component.vue").default
);

Vue.component(
  "inventorycountitemscomponent",
  require("./components/inventory/inventory_count_items_component.vue").default
);

Vue.component(
  "addproductcomponent",
  require("./components/product/add_product_component.vue").default
);
Vue.component(
  "addquantityadjustmentcomponent",
  require("./components/product/add_quantity_adjustment_component.vue").default
);
Vue.component(
  "quantityadjustmentdetailcomponent",
  require("./components/product/quantity_adjustment_detail_component.vue")
    .default
);
Vue.component(
  "productdetailcomponent",
  require("./components/product/product_detail_component.vue").default
);
Vue.component(
  "productbarcodecomponent",
  require("./components/product/product_barcode_component.vue").default
);

Vue.component(
  "addcombogroupcomponent",
  require("./components/combo/add_combo_group_component.vue").default
);
Vue.component(
  "addcombocomponent",
  require("./components/combo/add_combo_component.vue").default
);

Vue.component(
  "addsuppliercomponent",
  require("./components/supplier/add_supplier_component.vue").default
);
Vue.component(
  "supplierdetailcomponent",
  require("./components/supplier/supplier_detail_component.vue").default
);

Vue.component(
  "addtaxcodecomponent",
  require("./components/tax_code/add_tax_code_component.vue").default
);
Vue.component(
  "taxcodedetailcomponent",
  require("./components/tax_code/tax_code_detail_component.vue").default
);

Vue.component(
  "addtaxnamecomponent",
  require("./components/tax_name/add_tax_name_component.vue").default
);
Vue.component(
  "taxnamedetailcomponent",
  require("./components/tax_name/tax_name_detail_component.vue").default
);

// Vue.component('addordercomponent', require('./components/order/add_order_component.vue').default);
Vue.component(
  "addordercomponent",
  require("./components/order/add_order_component.vue").default
);
Vue.component(
  "orderdetailcomponent",
  require("./components/order/order_detail_component.vue").default
);
Vue.component(
  "orderdetailpubliccomponent",
  require("./components/order/order_detail_public_component.vue").default
);
Vue.component(
  "ordersummarycomponent",
  require("./components/order/order_summary_component.vue").default
);
Vue.component(
  "orderreceiptcomponent",
  require("./components/order/order_receipt_component.vue").default
);
Vue.component(
  "returnorderdetailcomponent",
  require("./components/return_order/return_order_detail_component.vue").default
);

Vue.component(
  "addstorecomponent",
  require("./components/store/add_store_component.vue").default
);
Vue.component(
  "selectstorecomponent",
  require("./components/store/select_store_component.vue").default
);
Vue.component(
  "storedetailcomponent",
  require("./components/store/store_detail_component.vue").default
);

Vue.component(
  "adddiscountcodecomponent",
  require("./components/discount_code/add_discount_code_component.vue").default
);
Vue.component(
  "discountcodedetailcomponent",
  require("./components/discount_code/discount_code_detail_component.vue")
    .default
);

Vue.component(
  "importcomponent",
  require("./components/import/import_component.vue").default
);
Vue.component(
  "updatedatacomponent",
  require("./components/import/update_data_component.vue").default
);

Vue.component(
  "addpaymentmethodcomponent",
  require("./components/payment_method/add_payment_method_component.vue")
    .default
);
Vue.component(
  "paymentmethoddetailcomponent",
  require("./components/payment_method/payment_method_detail_component.vue")
    .default
);

Vue.component(
  "addlangsettingcomponent",
  require("./components/language_setting/add_lang_component.vue").default
);
Vue.component(
  "detaillangsettingcomponent",
  require("./components/language_setting/detail_lang_component.vue").default
);
Vue.component(
  "addlangsettingphrasecomponent",
  require("./components/language_setting/phrase/add_lang_phrase_component.vue")
    .default
);

Vue.component(
  "reportcomponent",
  require("./components/report/report_component.vue").default
);
Vue.component(
  "taxreturnreportcomponent",
  require("./components/report/tax_return_report_component.vue").default
);
Vue.component(
  "bestsellerreportcomponent",
  require("./components/report/best_seller_report_component.vue").default
);
Vue.component(
  "daywisesalereportcomponent",
  require("./components/report/day_wise_sale_report_component.vue").default
);
Vue.component(
  "catgeoryreportcomponent",
  require("./components/report/catgeory_report_component.vue").default
);
Vue.component(
  "productquantityalertcomponent",
  require("./components/report/product_quantity_alert_component.vue").default
);
Vue.component(
  "storestockchartcomponent",
  require("./components/report/store_stock_chart_component.vue").default
);

Vue.component(
  "inventoryreportcomponent",
  require("./components/report/inventory_report_component.vue").default
);

Vue.component(
  "paymentreportcomponent",
  require("./components/report/payment_report_component.vue").default
);

Vue.component(
  "addquantitypurchasecomponent",
  require("./components/quantity_purchase/add_quantity_purchase_component.vue")
    .default
);
Vue.component(
  "quantitypurchasedetailcomponent",
  require("./components/quantity_purchase/quantity_purchase_detail_component.vue")
    .default
);

Vue.component(
  "addpurchaseordercomponent",
  require("./components/purchase_order/add_purchase_order_component.vue")
    .default
);
Vue.component(
  "purchaseorderdetailcomponent",
  require("./components/purchase_order/purchase_order_detail_component.vue")
    .default
);

Vue.component(
  "emailsettingcomponent",
  require("./components/setting/email/email_setting_component.vue").default
);
Vue.component(
  "editemailsettingcomponent",
  require("./components/setting/email/edit_email_setting_component.vue").default
);
Vue.component(
  "appsettingcomponent",
  require("./components/setting/app/app_setting_component.vue").default
);
Vue.component(
  "editappsettingcomponent",
  require("./components/setting/app/edit_app_setting_component.vue").default
);
Vue.component(
  "smssettingcomponent",
  require("./components/setting/sms/sms_setting_component.vue").default
);
Vue.component(
  "editsmssettingcomponent",
  require("./components/setting/sms/edit_sms_setting_component.vue").default
);

Vue.component(
  "addinvoicecomponent",
  require("./components/invoice/add_invoice_component.vue").default
);
Vue.component(
  "invoicedetailcomponent",
  require("./components/invoice/invoice_detail_component.vue").default
);
Vue.component(
  "invoicecolorcodecomponent",
  require("./components/invoice/invoice_color_code_component.vue").default
);
Vue.component(
  "invoicereturndetailcomponent",
  require("./components/invoice/invoice_return_detail_component.vue").default
);

Vue.component(
  "addquotationcomponent",
  require("./components/quotation/add_quotation_component.vue").default
);
Vue.component(
  "quotationdetailcomponent",
  require("./components/quotation/quotation_detail_component.vue").default
);

Vue.component(
  "addaccountcomponent",
  require("./components/account/add_account_component.vue").default
);
Vue.component(
  "accountdetailcomponent",
  require("./components/account/account_detail_component.vue").default
);

Vue.component(
  "addtransactioncomponent",
  require("./components/transaction/add_transaction_component.vue").default
);
Vue.component(
  "transactiondetailcomponent",
  require("./components/transaction/transaction_detail_component.vue").default
);
Vue.component(
  "addtransactionwidgetcomponent",
  require("./components/transaction/add_transaction_widget_component.vue")
    .default
);
Vue.component(
  "transactionlistcomponent",
  require("./components/transaction/transaction_list_component.vue").default
);

Vue.component(
  "addtablecomponent",
  require("./components/table/add_table_component.vue").default
);
Vue.component(
  "tabledetailcomponent",
  require("./components/table/table_detail_component.vue").default
);

Vue.component(
  "kitchenviewcomponent",
  require("./components/kitchen/kitchen_view_component.vue").default
);
Vue.component(
  "waiterviewcomponent",
  require("./components/kitchen/waiter_view_component.vue").default
);

Vue.component(
  "addtargetcomponent",
  require("./components/target/add_target_component.vue").default
);
Vue.component(
  "targetdetailcomponent",
  require("./components/target/target_detail_component.vue").default
);

Vue.component(
  "addstocktransfercomponent",
  require("./components/stock_transfer/add_stock_transfer_component.vue")
    .default
);
Vue.component(
  "verifystocktransfercomponent",
  require("./components/stock_transfer/verify_stock_transfer_component.vue")
    .default
);
Vue.component(
  "stocktransferdetailcomponent",
  require("./components/stock_transfer/stock_transfer_detail_component.vue")
    .default
);

Vue.component(
  "addstockreturncomponent",
  require("./components/stock_return/add_stock_return_component.vue").default
);
Vue.component(
  "stockreturndetailcomponent",
  require("./components/stock_return/stock_return_detail_component.vue").default
);

Vue.component(
  "addnotificationcomponent",
  require("./components/notification/add_notification_component.vue").default
);
Vue.component(
  "notificationdetailcomponent",
  require("./components/notification/notification_detail_component.vue").default
);

Vue.component(
  "addbusinessregistercomponent",
  require("./components/business_register/add_business_register_component.vue")
    .default
);
Vue.component(
  "businessregisterdetailcomponent",
  require("./components/business_register/business_register_detail_component.vue")
    .default
);

Vue.component(
  "addsmstemplatecomponent",
  require("./components/sms_template/add_sms_template_component.vue").default
);
Vue.component(
  "smstemplatedetailcomponent",
  require("./components/sms_template/sms_template_detail_component.vue").default
);

Vue.component(
  "addbillingcountercomponent",
  require("./components/billing_counter/add_billing_counter_component.vue")
    .default
);
Vue.component(
  "billingcounterdetailcomponent",
  require("./components/billing_counter/billing_counter_detail_component.vue")
    .default
);

Vue.component(
  "addmeasurementunitcomponent",
  require("./components/measurement_unit/add_measurement_unit_component.vue")
    .default
);
Vue.component(
  "measurementunitdetailcomponent",
  require("./components/measurement_unit/measurement_unit_detail_component.vue")
    .default
);

// new measurement category
Vue.component(
  "addmeasurementcategorycomponent",
  require("./components/measurement_unit/add_measurement_category_component.vue")
    .default
);

// new modifier
Vue.component(
  "addmodifiercomponent",
  require("./components/modifier/add_modifier_component.vue").default
);
Vue.component(
  "modifierdetailcomponent",
  require("./components/modifier/modifier_detail_component.vue").default
);

// new modifier option
Vue.component(
  "addmodifieroptioncomponent",
  require("./components/modifier_option/add_modifier_option_component.vue")
    .default
);

// new measurement unit
Vue.component(
  "addmeasurementcomponent",
  require("./components/measurement_unit/add_measurement_component.vue").default
);
Vue.component(
  "addmeasurementconversioncomponent",
  require("./components/measurement_unit/add_measurement_conversion_component.vue")
    .default
);
Vue.component(
  "measurementdetailcomponent",
  require("./components/measurement_unit/measurement_detail_component.vue")
    .default
);

// subscription
Vue.component(
  "addsubscriptioncomponent",
  require("./components/subscription/add_subscription_component.vue").default
);
Vue.component(
  "subscriptiondetailcomponent",
  require("./components/subscription/subscription_detail_component.vue").default
);
Vue.component(
  "managesubscriptionrolecomponent",
  require("./components/subscription/manage_subscription_role_component.vue")
    .default
);

// device
Vue.component(
  "adddevicecomponent",
  require("./components/device/add_device_component.vue").default
);

Vue.component(
  "addbrandcomponent",
  require("./components/brand/add_brand_component.vue").default
);
Vue.component(
  "branddetailcomponent",
  require("./components/brand/brand_detail_component.vue").default
);
Vue.component(
  "topnavcomponent",
  require("./components/layout/top_nav_component.vue").default
);

// Additional Componenet - Added by chandan
Vue.component(
  "poscomponent",
  require("./components/pos/pos_component.vue").default
);
Vue.component(
  "addpurchasecomponent",
  require("./components/purchase/add_purchase_component.vue").default
);
Vue.component(
  "posprintcomponent",
  require("./components/pos/pos_print_component.vue").default
);
Vue.component(
  "headercomponent",
  require("./components/layout/header_component.vue").default
);
Vue.component(
  "addingredientcomponent",
  require("./components/product/add_ingredient_component.vue").default
);

// abkhassetting
Vue.component(
  "userpointssettingcomponent",
  require("./components/setting/third_party_integration/user_points_setting_component.vue")
    .default
);
Vue.component(
  "edituserpointssettingcomponent",
  require("./components/setting/third_party_integration/edit_user_points_setting_component.vue")
    .default
);

// bonatsetting
Vue.component(
  "bonatuserpointssettingcomponent",
  require("./components/setting/third_party_integration/bonat_user_points_setting_component.vue")
    .default
);
Vue.component(
  "editbonatuserpointssettingcomponent",
  require("./components/setting/third_party_integration/edit_bonat_user_points_setting_component.vue")
    .default
);

// bonatStoreCountersetting
Vue.component(
  "bonatstorecounterpointssettingcomponent",
  require("./components/setting/third_party_integration/bonat_store_counter_points_setting_component.vue")
    .default
);
Vue.component(
  "editbonatstorecounterpointssettingcomponent",
  require("./components/setting/third_party_integration/edit_bonat_store_counter_points_setting_component.vue")
    .default
);

// price

Vue.component(
  "addpricecomponent",
  require("./components/price/add_price_component.vue").default
);

// Qoyod

Vue.component(
    "addqoyodcomponent",
    require("./components/qoyod/add_qoyod_component.vue").default
);

/***********************/
/* WOSUL ADMIN : START */
/***********************/

Vue.component(
  "addmerchantcomponent",
  require("./components/merchant/add_merchant_component.vue").default
);
Vue.component(
  "merchantdetailcomponent",
  require("./components/merchant/merchant_detail_component.vue").default
);
Vue.component(
  "expresspaysettingcomponent",
  require("./components/setting/expresspay_setting_component.vue").default
);

/***********************/
/* WOSUL ADMIN : END */
/***********************/

// Added by jollin
Vue.component(
  "editpurchaseordercomponent",
  require("./components/purchase_order/edit_purchase_order_component.vue")
    .default
);
const app = new Vue({
  el: "#app",
  i18n,
});

window.vm = app;