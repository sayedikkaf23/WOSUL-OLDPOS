<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="store_slack == ''">{{ $t('Add Store') }}</span>
            <span class="text-title" v-else>{{ $t('Edit Store') }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="name">{{ $t('Name') }}</label>
            <input
              type="text"
              name="name"
              v-model="name"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              :placeholder="enter_store_name"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('name')}">{{ errors.first('name') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="store_code">{{ $t('Store Code') }}</label>
            <input
              type="text"
              name="store_code"
              v-model="store_code"
              v-validate="'required|alpha_dash|max:30'"
              class="form-control form-control-custom"
              :placeholder="enter_store_code"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('store_code')}">{{ errors.first('store_code') }}</span>
          </div>
          <!-- <div class="form-group col-md-3">
                        <label for="tax_number">{{ $t("Tax Number or GST number") }}</label>
                        <input type="text" name="tax_number" v-model="tax_number" v-validate="'max:50'" class="form-control form-control-custom" placeholder="Please enter tax number or GST number"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('tax_number') }">{{ errors.first('tax_number') }}</span> 
                    </div> -->
          <!-- <div class="form-group col-md-3">
            <label for="vat_number">{{ $t('VAT Number') }}</label>
            <input
              type="text"
              name="vat_number"
              v-model="vat_number"
              v-validate="'max:50'"
              class="form-control form-control-custom"
              :placeholder="enter_vat_number"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('vat_number')}">{{ errors.first('vat_number') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_registration_name">{{ $t('Tax Registration Name') }}</label>
            <input
              type="text"
              name="tax_registration_name"
              v-model="tax_registration_name"
              v-validate="'max:50'"
              class="form-control form-control-custom"
              :placeholder="enter_tax_registration_name"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('tax_registration_name')}">{{ errors.first('tax_registration_name') }}</span>
          </div> -->
        </div>
        <!-- <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="tax_code">{{ $t('Select Tax') }}</label>
            <select name="tax_code" v-model="tax_code" v-validate="'required'" class="form-control form-control-custom custom-select">
              <option value="1">{{ $t('No Tax') }}</option>
              <option v-for="(code, index) in tax_codes" :key="index" :value="code.id">
                {{ code.label }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('tax_code')}">{{ errors.first('tax_code') }}</span>
          </div>
          
        </div> -->

        <hr />
        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Contact Information') }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="primary_contact">{{ $t('Primary Contact No.') }}</label>
            <input
              type="text"
              name="primary_contact"
              v-model="primary_contact"
              v-validate="'min:10|max:15'"
              class="form-control form-control-custom"
              :placeholder="primary_contact_number"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('primary_contact')}">{{ errors.first('primary_contact') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="phone">{{ $t('Secondary Contact No.') }}</label>
            <input
              type="text"
              name="secondary_contact"
              v-model="secondary_contact"
              v-validate="'min:10|max:15'"
              class="form-control form-control-custom"
              :placeholder="secondary_contact_number"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('secondary_contact')}">{{ errors.first('secondary_contact') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="primary_contact">{{ $t('Primary Email') }}</label>
            <input
              type="text"
              name="primary_email"
              v-model="primary_email"
              v-validate="'email'"
              class="form-control form-control-custom"
              :placeholder="enter_primary_email"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('primary_email')}">{{ errors.first('primary_email') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="phone">{{ $t('Secondary Email') }}</label>
            <input
              type="text"
              name="secondary_email"
              v-model="secondary_email"
              v-validate="'email'"
              class="form-control form-control-custom"
              :placeholder="enter_secondary_email"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('secondary_email')}">{{ errors.first('secondary_email') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="building_number">{{ $t('Building Number') }}</label>
            <input
              type="text"
              name="building_number"
              v-model="building_number"
              class="form-control form-control-custom"
              :placeholder="enter_building_number"
            />
            <span v-bind:class="{error: errors.has('building_number')}">{{ errors.first('building_number') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="street_name">{{ $t('Street Name') }}</label>
            <input type="text" name="street_name" v-model="street_name" class="form-control form-control-custom" :placeholder="enter_street_name" />
            <span v-bind:class="{error: errors.has('street_name')}">{{ errors.first('street_name') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="district">{{ $t('District') }}</label>
            <input type="text" name="district" v-model="district" class="form-control form-control-custom" :placeholder="enter_district" />
            <span v-bind:class="{error: errors.has('district')}">{{ errors.first('district') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="city">{{ $t('City') }}</label>
            <input type="text" name="city" v-model="city" class="form-control form-control-custom" :placeholder="enter_city" />
            <span v-bind:class="{error: errors.has('city')}">{{ errors.first('city') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="country">{{ $t('Country') }}</label>
            <select name="country" v-model="country" v-validate="'required'" class="form-control form-control-custom custom-select">
              <option value="">{{ $t('Choose Country..') }}</option>
              <option v-for="(country_item, index) in country_list" v-bind:value="country_item.country_id" v-bind:key="index">
                {{ country_item.code }} - {{ country_item.name }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('country')}">{{ errors.first('country') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="pincode">{{ $t('Pincode') }}</label>
            <input
              type="text"
              name="pincode"
              v-model="pincode"
              v-validate="'max:15'"
              class="form-control form-control-custom"
              :placeholder="enter_pincode"
            />
            <span v-bind:class="{error: errors.has('pincode')}">{{ errors.first('pincode') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="other_seller_id">{{ $t('Other Seller Id') }}</label>
            <input
              type="text"
              name="other_seller_id"
              v-model="other_seller_id"
              class="form-control form-control-custom"
              :placeholder="enter_other_seller_id"
            />
            <span v-bind:class="{error: errors.has('other_seller_id')}">{{ errors.first('other_seller_id') }}</span>
          </div>
          <div class="form-group col-md-3"></div>
          <div class="form-group col-md-3">
            <label for="address">{{ $t('Address') }} ( {{ $t('optional') }} )</label>
            <textarea
              name="address"
              v-model="address"
              v-validate="'required|max:65535'"
              class="form-control form-control-custom"
              rows="5"
              :placeholder="store_address"
            ></textarea>
            <span v-bind:class="{error: errors.has('address')}">{{ errors.first('address') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="store_opening_time">{{ $t('Store Opening Time') }}</label>
            <input
              type="time"
              name="store_opening_time"
              v-model="store_opening_time"
              class="form-control form-control-custom"
              v-validate="'required'"
              :placeholder="enter_store_opening_time"
            />
            <span v-bind:class="{error: errors.has('store_opening_time')}">{{ errors.first('store_opening_time') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="store_closing_time">{{ $t('Store Closing Time') }}</label>
            <input
              type="time"
              name="store_closing_time"
              v-model="store_closing_time"
              v-validate="'required'"
              class="form-control form-control-custom"
              :placeholder="enter_store_closing_time"
            />
            <span v-bind:class="{error: errors.has('store_closing_time')}">{{ errors.first('store_closing_time') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="is_store_closing_next_day">{{ $t('Is Store Closing Next Day') }}</label>
            <br />
            <input type="checkbox" name="is_store_closing_next_day" v-model="is_store_closing_next_day" />
            {{ $t('Yes') }}
            <span v-bind:class="{error: errors.has('is_store_closing_next_day')}">{{ errors.first('is_store_closing_next_day') }}</span>
          </div>
           <hr />
          
          <div class="col-12">
            <div class="mr-auto">
              <span class="text-subhead">{{ $t('Idle Time (For Mobile Application)') }}</span>
            </div>
          </div>
          
          <div class="col-3">
            <label for="idle_time_status">{{ $t('Idle Time Status') }}</label>
            <select
              name="idle_time_status"
              v-model="idle_time_status"
              class="form-control form-control-custom custom-select"
            >
            <option value="0">{{ $t('Inactive') }}</option>
            <option value="1">{{ $t('Active') }}</option>
            </select>
            <span v-bind:class="{error: errors.has('idle_time_status')}">{{ errors.first('idle_time_status') }}</span>
          </div>
          <div class=" form-group col-3">
            <label for="platform_mode">{{ $t('Platform Mode') }} </label>
            <select
              name="platform_mode"
              v-model="platform_mode"
              v-validate="'required'"
              class="form-control form-control-custom custom-select"
            >
            <option value="ONLINE">{{ $t('Online') }}</option>
            <option value="OFFLINE">{{ $t('Offline') }}</option>
            </select>
            <span v-bind:class="{error: errors.has('platform_mode')}">{{ errors.first('platform_mode') }}</span>
          </div>
          <div v-if="platform_mode == 'OFFLINE' " class=" form-group col-3">
            <label for="platform_type">{{ $t('Platform Type') }} <i class="fa fa-info-circle text-danger" title="By using this switch you can restrict users to use cashier on only one platform at a time whether it can be Web, iOS Application or Android"></i> </label>
            <select
              name="platform_type"
              v-model="platform_type"
              v-validate="[ platform_mode == 'OFFLINE' ? 'required' : '' ]"
              class="form-control form-control-custom custom-select"
            >
            <option value="IOS">{{ $t('iOS App') }}</option>
            <option value="ANDROID">{{ $t('Android App') }}</option>
            </select>
                        <span v-bind:class="{error: errors.has('platform_type')}">{{ errors.first('platform_type') }}</span>

            <div class="alert alert-warning p-1"> {{ $t('Remember, Once you changed platform type then you won\'t be able to access cashier screen from other two non selected types') }} </div>
          </div>
          <br>
          <div class="form-group col-md-3" v-show="idle_time_status == 1">
            <label for="idle_time_in_minutes">{{ $t('Minutes') }}</label>
            <input type="number" min="0" name="idle_time_in_minutes" v-model="idle_time_in_minutes" class="form-control form-control-custom" :placeholder="idle_time" />
          </div>
           <div class="form-group col-md-3" v-show="idle_time_status == 1">
            <label for="idle_time_in_seconds">{{ $t('Seconds') }}</label>
            <select
              name="idle_time_in_seconds"
              v-model="idle_time_in_seconds"
              class="form-control form-control-custom custom-select"
            >
            <option v-for="(second,index) in idle_time_in_seconds_options " :key="index">{{ second }}</option>
            </select>
            <span v-bind:class="{error: errors.has('idle_time_in_seconds')}">{{ errors.first('idle_time_in_seconds') }}</span>
          </div>
          </div>
        <hr />
        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Restaurant Mode') }}</span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="restaurant_mode">{{ $t('Enable Restaurant Mode') }}</label>
            <select
              name="restaurant_mode"
              v-model="restaurant_mode"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t('Choose Restaurant Mode..') }}</option>
              <option v-for="(restaurant_mode_option, index) in restaurant_mode_options" v-bind:value="index" v-bind:key="index">
                {{ $t(restaurant_mode_option) }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('restaurant_mode')}">{{ errors.first('restaurant_mode') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="restaurant_billing_type">{{ $t('Default Billing Type') }}</label>
            <select
              name="restaurant_billing_type"
              v-model="restaurant_billing_type"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t('Choose Default Billing Type..') }}</option>
              <option
                v-for="(billing_type_list_item, index) in billing_type_list"
                v-bind:value="billing_type_list_item.billing_type_constant"
                v-bind:key="index"
              >
                {{ billing_type_list_item.label }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('restaurant_billing_type')}">{{ errors.first('restaurant_billing_type') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="restaurant_waiter_role">{{ $t('Role for Waiter') }}</label>
            <select
              name="restaurant_waiter_role"
              v-model="restaurant_waiter_role"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t('Choose Role for Waiter..') }}</option>
              <option v-for="(waiter_role_item, index) in waiter_role" v-bind:value="waiter_role_item.slack" v-bind:key="index">
                {{ waiter_role_item.role_code }} - {{ waiter_role_item.label }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('restaurant_waiter_role')}">{{ errors.first('restaurant_waiter_role') }}</span>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Status Information') }}</span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="status">{{ $t('Status') }}</label>
            <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
              <option value="">{{ $t('Choose Status..') }}</option>
              <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                {{ $t(status.label) }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('status')}">{{ errors.first('status') }}</span>
          </div>
        </div>
        <hr />

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('POS Screen Setting') }}</span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="status">{{ $t('Enable Customer Detail Popup') }}</label>
            <select
              name="enable_customer_popup"
              v-model="enable_customer_popup"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t('Choose Customer Popup..') }}</option>
              <option v-for="(customer_popup_option, index) in customer_popup_options" v-bind:value="index" v-bind:key="index">
                {{ $t(customer_popup_option) }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('enable_customer_popup')}">{{ errors.first('enable_customer_popup') }}</span>
          </div>
        </div>
        <hr />

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Invoice Print & Currency Details') }}</span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="print_type">{{ $t('Invoice Print Type') }}</label>
            <select name="print_type" v-model="print_type" v-validate="'required'" class="form-control form-control-custom custom-select">
              <option value="">{{ $t('Choose Invoice Print Type..') }}</option>
              <option
                v-for="(invoice_print_type, index) in invoice_print_types"
                v-bind:value="invoice_print_type.print_type_value"
                v-bind:key="index"
              >
                {{ invoice_print_type.print_type_label }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('print_type')}">{{ errors.first('print_type') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="currency_code">{{ $t('Currency') }}</label>
            <select name="currency_code" v-model="currency_code" v-validate="'required'" class="form-control form-control-custom custom-select">
              <option value="">{{ $t('Choose Currency..') }}</option>
              <!-- <option value="SAR" selected="">SAR - Saudi Riyal</option> -->
              <option v-for="(currency_item, index) in currency_list" v-bind:value="currency_item.currency_code" v-bind:key="index">
                {{ currency_item.currency_code }} -
                {{ currency_item.currency_name }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('currency_code')}">{{ errors.first('currency_code') }}</span>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Bank Details') }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="bank_name">{{ $t('Bank Name') }}</label>
            <input
              type="text"
              name="bank_name"
              v-model="bank_name"
              class="form-control form-control-custom"
              :placeholder="enter_bank_name"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('bank_name')}">{{ errors.first('bank_name') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="iban_number">{{ $t('Bank Account Number') }}</label>
            <input
              type="text"
              name="iban_number"
              v-model="iban_number"
              class="form-control form-control-custom"
              :placeholder="bank_account_number"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('iban_number')}">{{ errors.first('iban_number') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="account_holder_name">{{ $t('Account Holder Name') }}</label>
            <input
              type="text"
              name="account_holder_name"
              v-model="account_holder_name"
              class="form-control form-control-custom"
              :placeholder="bank_ifsc_code"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('account_holder_name')}">{{ errors.first('account_holder_name') }}</span>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t('Policy Information') }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="pos_invoice_policy_information">{{ $t('POS Invoice Policy Information') }}</label>
            <textarea
              name="pos_invoice_policy_information"
              v-model="pos_invoice_policy_information"
              class="form-control form-control-custom"
              :placeholder="enter_POS_invoice_policy_information"
              autocomplete="off"
            ></textarea>
            <span v-bind:class="{error: errors.has('pos_invoice_policy_information')}">
              {{ errors.first('pos_invoice_policy_information') }}
            </span>
          </div>
          <div class="form-group col-md-3">
            <label for="invoice_policy_information">{{ $t('Invoice Policy Information') }}</label>
            <textarea
              name="invoice_policy_information"
              v-model="invoice_policy_information"
              class="form-control form-control-custom"
              :placeholder="enter_invoice_policy_information"
              autocomplete="off"
            ></textarea>
            <span v-bind:class="{error: errors.has('invoice_policy_information')}">{{ errors.first('invoice_policy_information') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="purchase_policy_information">{{ $t('Purchase Policy Information') }}</label>
            <textarea
              name="purchase_policy_information"
              v-model="purchase_policy_information"
              class="form-control form-control-custom"
              :placeholder="enter_purchase_policy_information"
              autocomplete="off"
            ></textarea>
            <span v-bind:class="{error: errors.has('purchase_policy_information')}">
              {{ errors.first('purchase_policy_information') }}
            </span>
          </div>
          <div class="form-group col-md-3">
            <label for="quotation_policy_information">{{ $t('Quotation Policy Information') }}</label>
            <textarea
              name="quotation_policy_information"
              v-model="quotation_policy_information"
              class="form-control form-control-custom"
              :placeholder="enter_quotation_policy_information"
              autocomplete="off"
            ></textarea>
            <span v-bind:class="{error: errors.has('quotation_policy_information')}">
              {{ errors.first('quotation_policy_information') }}
            </span>
          </div>
          <div class="form-group col-md-3">
            <label for="store_logo">{{ $t('Store Logo') + ' (jpeg, jpg, png, webp)' }}</label>
            <input
              v-if="store_slack == ''"
              type="file"
              class="form-control-file form-control form-control-custom file-input"
              name="store_logo"
              ref="store_logo"
              accept="image/x-png,image/jpeg,image/webp"
              v-validate="'required|ext:jpg,jpeg,png,webp|size:3000'"
            />
            <input
              v-if="store_slack != ''"
              type="file"
              class="form-control-file form-control form-control-custom file-input"
              name="store_logo"
              ref="store_logo"
              accept="image/x-png,image/jpeg,image/webp"
              v-validate="'ext:jpg,jpeg,png,webp|size:3000'"
            />
            <small class="form-text text-muted mb-1">{{ $t('Allowed file size per file is 3 MB') }}</small>
            <span v-bind:class="{error: errors.has('store_logo')}">{{ errors.first('store_logo') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="zid_store_id">{{ $t('ZID Store Id') }}</label>
            <input
              type="number"
              name="zid_store_id"
              v-model="zid_store_id"
              class="form-control form-control-custom"
              :placeholder="enter_zid_store_id"
              autocomplete="off"
            />
            <span v-bind:class="{error: errors.has('zid_store_id')}">
              {{ errors.first('zid_store_id') }}
            </span>
          </div>

          <div class="form-group col-md-3">
            <label for="zid_store_api_token">{{ $t('ZID Store API Token') }}</label>
            <textarea
              name="zid_store_api_token"
              v-model="zid_store_api_token"
              class="form-control form-control-custom"
              :placeholder="enter_zid_store_api_token"
              autocomplete="off"
            ></textarea>
            <span v-bind:class="{error: errors.has('zid_store_api_token')}">
              {{ errors.first('zid_store_api_token') }}
            </span>
          </div>

          <div class="form-group col-md-3">
            <label for="store_invoice_color">{{
                $t("Store Invoice Color")
              }}</label>
            <input
                type="color"
                v-model="store_invoice_color"
                class="form-control form-control-custom"
            />
            <span
                v-bind:class="{ error: errors.has('store_invoice_color') }"
            >{{ errors.first("store_invoice_color") }}</span
            >
          </div>

        </div>

        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true">
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Save') }}
            </button>
          </div>
        </div>
      </form>

      <div class="row mb-5" v-show="store_id">
        <div class="col-md-2 w-25">
          <div class=" mr-auto">
            <span class="text-title">{{ $t('QR Code') }}</span>
          </div>
        </div>

        <div class="col-md-2 text-right w-25 mt-4">
          <div v-if="qrValue"><vue-qrcode v-bind:value="qrValue" /></div>
          <div v-else>
            <button type="button" class="btn btn-primary" v-bind:disabled="processing_qr == true" v-on:click="create_qr_code" id="btn_qr_code">
              <i class="fa fa-circle-notch fa-spin" v-if="processing_qr == true"></i>
              {{ $t('Create QR Code') }}
            </button>
          </div>
        </div>

        <div class="col-md-2 text-right w-25 mt-4">
          <button type="button" class="btn btn-primary" v-bind:disabled="processing_sync_data == true" v-on:click="sync_data" id="btn_sync_data">
            <i class="fa fa-circle-notch fa-spin" v-if="processing_sync_data == true"></i>
            {{ $t('Sync Data') }}
          </button>
        </div>
      </div>

      <div class="row mb-5">
        <div class="col-md-12 text-center print-message"></div>
      </div>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>{{ $t('Confirm') }}</template>
      <template v-slot:modal-body>
        <p v-if="status == 0">{{ $t('You are making the store inactive.') }}</p>
        {{ $t('Are you sure you want to proceed?') }}
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t('Cancel') }}
        </button>
        <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true">
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script>
'use strict';
import { off } from 'process';
import VueQrcode from 'vue-qrcode';
export default {
  components: {
    VueQrcode
  },
  data() {
    return {
      server_errors: '',
      error_class: '',
      processing: false,
      processing_qr: false,
      processing_sync_data: false,
      modal: false,
      show_modal: false,
      qrValue: this.restaurant_url,
      api_link: this.store_data == null ? '/api/add_store' : '/api/update_store/' + this.store_data.slack,
      restaurant_mode_options: {
        1: 'Yes',
        0: 'No'
      },
      customer_popup_options: {
        1: 'Yes',
        0: 'No'
      },
      store_slack: this.store_data == null ? '' : this.store_data.slack,
      name: this.store_data == null ? '' : this.store_data.name,
      store_code: this.store_data == null ? '' : this.store_data.store_code,
      vat_number: this.store_data == null ? '' : this.store_data.vat_number,
      tax_registration_name: this.store_data == null ? '' : this.store_data.tax_registration_name,
      primary_contact: this.store_data == null ? '' : this.store_data.primary_contact,
      secondary_contact: this.store_data == null ? '' : this.store_data.secondary_contact,
      primary_email: this.store_data === null ? '' : this.store_data.primary_email,
      secondary_email: this.store_data == null ? '' : this.store_data.secondary_email,
      address: this.store_data == null ? '' : this.store_data.address,
      store_opening_time: this.store_data == null ? '00:00' : this.store_data.store_opening_time,
      store_closing_time: this.store_data == null ? '23:59' : this.store_data.store_closing_time,
      is_store_closing_next_day: this.store_data == null ? false : this.store_data.is_store_closing_next_day,
      building_number: this.store_data == null ? '' : this.store_data.building_number,
      street_name: this.store_data == null ? '' : this.store_data.street_name,
      district: this.store_data == null ? '' : this.store_data.district,
      city: this.store_data == null ? '' : this.store_data.city,
      other_seller_id: this.store_data == null ? '' : this.store_data.other_seller_id,
      country: this.store_data == null ? '' : this.store_data.country == null ? '' : this.store_data.country.id,
      pincode: this.store_data == null ? '' : this.store_data.pincode,
      tax_code: this.store_data == null ? '1' : this.store_data.tax_code == null ? '1' : this.store_data.tax_code.id,
      discount_code: this.store_data == null ? '' : this.store_data.discount_code == null ? '' : this.store_data.discount_code.slack,
      print_type: this.store_data == null ? '' : this.store_data.invoice_type == null ? '' : this.store_data.invoice_type.print_type_value,
      currency_code:
        this.store_data == null ? this.session_currency_code : this.store_data.currency_code == null ? '' : this.store_data.currency_code,
      restaurant_mode: this.store_data == null ? 0 : this.store_data.restaurant_mode,
      restaurant_billing_type:
        this.store_data == null
          ? ''
          : this.store_data.restaurant_billing_type == null
          ? ''
          : this.store_data.restaurant_billing_type.billing_type_constant,
      restaurant_waiter_role:
        this.store_data == null ? '' : this.store_data.restaurant_waiter_role == null ? '' : this.store_data.restaurant_waiter_role.slack,
      status: this.store_data == null ? '1' : this.store_data.status.value,
      enable_customer_popup: this.store_data == null ? 0 : this.store_data.enable_customer_popup,
      bank_name: this.store_data == null ? '' : this.store_data.bank_name,
      account_holder_name: this.store_data == null ? '' : this.store_data.account_holder_name,
      iban_number: this.store_data == null ? '' : this.store_data.iban_number,
      pos_invoice_policy_information: this.store_data == null ? '' : this.store_data.pos_invoice_policy_information,
      invoice_policy_information: this.store_data == null ? '' : this.store_data.invoice_policy_information,
      purchase_policy_information: this.store_data == null ? '' : this.store_data.purchase_policy_information,
      quotation_policy_information: this.store_data == null ? '' : this.store_data.quotation_policy_information,
      enter_store_code: this.$t('Please enter store code'),
      enter_store_name: this.$t('Please enter store name'),
      enter_vat_number: this.$t('Please enter VAT Number'),
      enter_tax_registration_name: this.$t('Please enter Tax Registration Name'),
      primary_contact_number: this.$t('Please enter primary contact number'),
      secondary_contact_number: this.$t('Please enter secondary contact number'),
      enter_primary_email: this.$t('Please enter primary email'),
      enter_secondary_email: this.$t('Please enter secondary email'),
      store_address: this.$t('Enter store address'),
      enter_store_opening_time: this.$t('Enter store opening time'),
      enter_store_closing_time: this.$t('Enter store closing time'),
      enter_pincode: this.$t('Enter Pincode'),
      enter_bank_name: this.$t('Please enter bank name'),
      bank_account_number: this.$t('Please enter bank account number'),
      bank_ifsc_code: this.$t('Please enter bank ifsc code'),
      enter_POS_invoice_policy_information: this.$t('Please enter POS invoice policy information'),
      enter_invoice_policy_information: this.$t('Please enter invoice policy information'),
      enter_purchase_policy_information: this.$t('Please enter purchase policy information'),
      enter_quotation_policy_information: this.$t('Please enter quotation policy information'),
      success: this.$t('SUCCESS'),
      zid_store_api_token: this.store_data == null ? '' : this.store_data.zid_store_api_token,
      enter_zid_store_api_token: this.$t('Please enter store API token'),
      zid_store_id: this.store_data == null ? '' : this.store_data.zid_store_id,
      enter_zid_store_id: this.$t('Please enter Zid store Id'),
      enter_building_number: this.$t('Please enter building number'),
      enter_street_name: this.$t('Please enter street name'),
      enter_district: this.$t('Please enter district'),
      enter_city: this.$t('Please enter city'),
      enter_other_seller_id: this.$t('Please enter other seller id'),
      idle_time_in_minutes: this.store_data == null ? 0 : this.store_data.idle_time_in_minutes,
      idle_time_in_seconds: this.store_data == null ? 0 : this.store_data.idle_time_in_seconds,
      idle_time_in_seconds_options : [0,5,10,15,20,25,30,35,40,45,50,55],
      idle_time_status : this.store_data == null ? 0 : (this.store_data.idle_time_status == null) ? 0 : this.store_data.idle_time_status, 
      platform_mode: (this.store_data == null) ? 'ONLINE' : (this.store_data.platform_mode == null) ? 'ONLINE' : this.store_data.platform_mode,
      platform_type: (this.store_data == null) ? 'IOS' : (this.store_data.platform_type == null) ? 'IOS' : this.store_data.platform_type,
      store_invoice_color:
        this.store_data != null ? this.store_data.store_invoice_color : "" ,
    };
  },
  props: {
    statuses: Array,
    discount_codes: Array,
    invoice_print_types: Array,
    currency_list: Array,
    country_list: Array,
    accounts: Array,
    store_data: [Array, Object],
    billing_type_list: [Array, Object],
    waiter_role: [Array, Object],
    session_currency_code: String,
    tax_codes: [Array, Object],
    restaurant_url: null,
    restaurant_id: null,
    user_id: null,
    store_id: null,
    base_url: null,
    logged_user_store_id : Number,
  },
 
  mounted() {
    console.log('tax_codes =', this.tax_codes);
    // console.log(this.store_data.vat_number);
    console.log(this.store_data);
  },
  watch: {
    tax_code: function(val) {
      this.console.log('this.tax_code', val);
    }
  },
  methods: {
    create_qr_code() {
      this.processing_qr = true;

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('user_id', this.user_id);
      formData.append('store_id', this.store_id);

      axios
        .post(this.base_url + '/api/create_qr_code', formData)
        .then(response => {
          console.log('response', response.data);

          if (response.data.status_code == 200) {
            if (response.data.data == 'error') {
              $('.print-message').html('<span class="alert alert-danger alert-dismissible fade show">' + response.data.msg + '</span>');
              $('#btn_qr_code').css('display', 'inline');
              this.processing_qr = false;
              return false;
            }
            this.show_response_message(response.data.msg, this.success);

            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            this.show_modal = false;
            this.processing_qr = false;
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = 'error';
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    sync_data() {
      this.processing_sync_data = true;

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('user_id', this.user_id);
      formData.append('restaurant_id', this.restaurant_id);
      formData.append('store_id', this.store_id);
      axios
        .post(this.base_url + '/api/sync_category_product', formData)
        .then(response => {
          console.log('response', response.data);

          if (response.data.status_code == 200) {
            if (response.data.data == 'error') {
              $('.print-message').html('<span class="alert alert-danger alert-dismissible fade show">' + response.data.msg + '</span>');
              $('#btn_sync_data').css('display', 'inline');
              this.processing_sync_data = false;
              return false;
            }
            this.show_response_message(response.data.msg, this.success);

            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            this.show_modal = false;
            this.processing_sync_data = false;
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = 'error';
          }
        })
        .catch(error => {
          console.log(error);
        });
    },

    submit_form() {
      this.$off('submit');
      this.$off('close');

      this.$validator.validateAll().then(result => {
        if (result) {
          this.show_modal = true;
          this.$on('submit', function() {
            this.processing = true;
            var formData = new FormData();

            if(this.idle_time_in_minutes > 0){
              var idle_time =  this.idle_time_in_minutes * 60;
              idle_time += parseInt(this.idle_time_in_seconds); 
            }else{
              var idle_time = parseInt(this.idle_time_in_seconds); 
            }
            
            formData.append('access_token', window.settings.access_token);
            formData.append('name', this.name == null ? '' : this.name);
            formData.append('store_code', this.store_code == null ? '' : this.store_code);
            formData.append('vat_number', this.vat_number == null ? '' : this.vat_number);
            formData.append('tax_registration_name', this.tax_registration_name == null ? '' : this.tax_registration_name);
            formData.append('primary_contact', this.primary_contact == null ? '' : this.primary_contact);
            formData.append('secondary_contact', this.secondary_contact == null ? '' : this.secondary_contact);
            formData.append('primary_email', this.primary_email == null ? '' : this.primary_email);
            formData.append('secondary_email', this.secondary_email == null ? '' : this.secondary_email);
            formData.append('address', this.address == null ? '' : this.address);
            formData.append('store_opening_time', this.store_opening_time == null ? '' : this.store_opening_time);
            formData.append('store_closing_time', this.store_closing_time == null ? '' : this.store_closing_time);
            formData.append('is_store_closing_next_day', this.is_store_closing_next_day == null ? false : this.is_store_closing_next_day);
            formData.append('idle_time_status', this.idle_time_status);
            formData.append('idle_time', idle_time);
            formData.append('country', this.country == null ? '' : this.country);
            formData.append('pincode', this.pincode == null ? '' : this.pincode);
            formData.append('tax_code', this.tax_code == null ? '' : this.tax_code);
            formData.append('discount_code', this.discount_code == null ? '' : this.discount_code);
            formData.append('invoice_type', this.print_type == null ? '' : this.print_type);
            formData.append('currency_code', this.currency_code == null ? '' : this.currency_code);
            formData.append('restaurant_mode', this.restaurant_mode == null ? '' : this.restaurant_mode);
            formData.append('restaurant_billing_type', this.restaurant_billing_type == null ? '' : this.restaurant_billing_type);
            formData.append('restaurant_waiter_role', this.restaurant_waiter_role == null ? '' : this.restaurant_waiter_role);
            formData.append('enable_customer_popup', this.enable_customer_popup == null ? '' : this.enable_customer_popup);
            formData.append('status', this.status == null ? '' : this.status);
            formData.append('bank_name', this.bank_name == null ? '' : this.bank_name);
            formData.append('iban_number', this.iban_number == null ? '' : this.iban_number);
            formData.append('account_holder_name', this.account_holder_name == null ? '' : this.account_holder_name);
            formData.append('pos_invoice_policy_information', this.pos_invoice_policy_information == null ? '' : this.pos_invoice_policy_information);
            formData.append('invoice_policy_information', this.invoice_policy_information == null ? '' : this.invoice_policy_information);
            formData.append('purchase_policy_information', this.purchase_policy_information == null ? '' : this.purchase_policy_information);
            formData.append('quotation_policy_information', this.quotation_policy_information == null ? '' : this.quotation_policy_information);
            formData.append('store_logo', this.$refs.store_logo.files.length > 0 ? this.$refs.store_logo.files[0] : null);
            formData.append('zid_store_api_token', this.zid_store_api_token == null ? '' : this.zid_store_api_token);

            formData.append('zid_store_id', this.zid_store_id == null ? '' : this.zid_store_id);

            formData.append('building_number', this.building_number == null ? '' : this.building_number);
            formData.append('street_name', this.street_name == null ? '' : this.street_name);
            formData.append('district', this.district == null ? '' : this.district);
            formData.append('city', this.city == null ? '' : this.city);
            formData.append('other_seller_id', this.other_seller_id == null ? '' : this.other_seller_id);
            formData.append('platform_mode', this.platform_mode == null ? 'ONLINE' : this.platform_mode);
            let platform_type = (this.platform_mode == 'ONLINE') ? '' : this.platform_type;
            formData.append('platform_type', platform_type);
            formData.append(
                "store_invoice_color",
                this.store_invoice_color == null ? "" : this.store_invoice_color
            );
            axios
              .post(this.api_link, formData)
              .then(response => {
                if (response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, this.success);

                  setTimeout(function() {
                    location.reload();
                  }, 1000);
                } else {
                  this.show_modal = false;
                  this.processing = false;
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.loop_api_errors(error_json);
                  } catch (err) {
                    this.server_errors = response.data.msg;
                  }
                  this.error_class = 'error';
                }
              })
              .catch(error => {
                console.log(error);
              });
          });

          this.$on('close', function() {
            this.show_modal = false;
          });
        }
      });
    },
    
  }
};
</script>
