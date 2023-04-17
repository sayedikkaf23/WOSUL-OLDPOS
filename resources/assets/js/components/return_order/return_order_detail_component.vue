<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">{{ $t('Return Order') }} #{{ order_basic.return_order_number }}</span>
              <br />
              <span class="text-title">{{ $t('Transaction ID') }} #{{ order_basic.id }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap mb-4">
        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="ml-auto">
          <button
            type="submit"
            class="btn btn-danger mr-1"
            v-if="delete_order_access == true"
            v-on:click="delete_order()"
            v-bind:disabled="order_processing == true"
          >
            <i class="fa fa-circle-notch fa-spin" v-if="order_processing == true"></i>
            {{ $t('Delete Order') }}
          </button>

          <button
            type="button"
            class="btn btn-outline-primary mr-1"
            v-if="share_invoice_sms_access == true"
            v-on:click="share_invoice_as_sms()"
            v-bind:disabled="send_sms_processing == true"
          >
            <i class="fa fa-circle-notch fa-spin" v-if="send_sms_processing == true"></i>
            {{ $t('Share Invoice as SMS') }}
          </button>

          <a class="btn btn-outline-primary" v-if="order_basic.status.value == 1" v-bind:href="print_return_order_link" target="_blank">
            {{ $t('Print Receipt') }}
          </a>

          <a class="btn btn-success text-white" v-if="order_basic.status.value == 1" v-bind:href="print_return_pos_receipt_link" target="_blank">
            {{ $t('Print POS Receipt') }}
          </a>
        </div>
      </div>

      <div v-show="order_basic.restaurant_mode == 1">
        <div class="mb-2">
          <span class="text-subhead">{{ $t('Restaurant Mode Information') }}</span>
        </div>
        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="email">{{ $t('Order Type') }}</label>
            <p>{{ order_basic.order_type }}</p>
          </div>
          <div class="form-group col-md-2">
            <label for="email">{{ $t('Billing Type') }}</label>
            <p>
              {{ order_basic.billing_type_data != null ? order_basic.billing_type_data.label : '-' }}
            </p>
          </div>
          <div class="form-group col-md-2">
            <label for="email">{{ $t('Counter Name') }}</label>
            <p>
              {{ order_basic.counter_name != null ? order_basic.counter_name : '-' }}
            </p>
          </div>
          <div class="form-group col-md-2">
            <label for="email">{{ $t('Table Number or Name') }}</label>
            <p>{{ order_basic.table != null ? order_basic.table : '-' }}</p>
          </div>
          <div class="form-group col-md-2">
            <label for="email">{{ $t('Waiter') }}</label>
            <p>
              {{ order_basic.waiter_data != null ? order_basic.waiter_data.fullname + ' (' + order_basic.waiter_data.user_code + ')' : '-' }}
            </p>
          </div>
        </div>
      </div>
      <hr />

      <div class="mb-2">
        <span class="text-subhead">{{ $t('Basic Information') }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="email">{{ $t('Email') }}</label>
          <p>{{ order_basic.customer_email }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="email">{{ $t('Phone') }}</label>
          <p>{{ order_basic.customer_phone }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="email">{{ $t('Payment Mode') }}</label>
          <p>{{ order_basic.payment_method }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_by">{{ $t('Created By') }}</label>
          <p>
            {{ order_basic.created_by == null ? '-' : order_basic.created_by['fullname'] + ' (' + order_basic.created_by['user_code'] + ')' }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_by">{{ $t('Updated By') }}</label>
          <p>
            {{ order_basic.updated_by == null ? '-' : order_basic.updated_by['fullname'] + ' (' + order_basic.updated_by['user_code'] + ')' }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_on">{{ $t('Created On') }}</label>
          <p>{{ order_basic.created_at_label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_on">{{ $t('Updated On') }}</label>
          <p>{{ order_basic.updated_at_label }}</p>
        </div>
      </div>
      <hr />

      <div class="mb-3">
        <div class="mb-2">
          <span class="text-subhead">{{ $t('Order Level Tax Information') }}</span>
        </div>
        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="tax_code">{{ $t('Tax Type') }}</label>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_percentage">{{ $t('Tax Percentage') }}</label>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_amount">{{ $t('Tax Amount') }}</label>
          </div>
        </div>
        <div class="form-row mb-2" v-for="tax_component in return_order_data.order_level_tax_components" :key="tax_component.tax_type">
          <div class="form-group col-md-3">
            <p>{{ tax_component.tax_type }}</p>
          </div>
          <div class="form-group col-md-3">
            <p>{{ tax_component.tax_percentage }}%</p>
          </div>
          <div class="form-group col-md-3">
            <p>{{ tax_component.tax_amount | roundDecimal }}</p>
          </div>
        </div>

        <div class="col-md-6">
          <!-- <div class="row">
            <div
              class="table-responsive"
              v-if="order_basic.order_level_tax_percentage > 0"
            >
              <table class="table display nowrap text-nowrap w-100">
                <thead>
                  <tr>
                    <th scope="col">{{ $t("Tax Type") }}</th>
                    <th scope="col">{{ $t("Tax Percentage") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(tax_component,
                    key,
                    index) in order_basic.order_level_tax_components"
                    v-bind:key="index"
                  >
                    <td>{{ tax_component.tax_type }}</td>
                    <td>{{ tax_component.tax_percentage }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <span class="mb-2" v-else>{{
              $t("No Order Level Tax Components")
            }}</span>
          </div> -->
        </div>
      </div>
      <hr />

      <div class="mb-3">
        <div class="mb-2">
          <span class="text-subhead">{{ $t('Order Level Discount Information') }}</span>
        </div>
        <div class="form-row mb-2" v-if="order_basic.order_level_discount_percentage > 0">
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t('Discount Code') }}</label>
            <p>{{ order_basic.order_level_discount_code }}</p>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_percentage">{{ $t('Discount Percentage') }}</label>
            <p>{{ order_basic.order_level_discount_percentage }}</p>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code_label">{{ $t('Discount Amount') }}</label>
            <p>{{ order_basic.order_level_discount_amount }}</p>
          </div>
        </div>
        <div class="mb-3" v-else>
          {{ $t('No Order Level Discount Information') }}
        </div>
      </div>
      <hr />

      <div id="product_details" v-show="view_product_details">
        <div class="mb-2">
          <span class="text-subhead">{{ $t('Product Information') }}</span>
        </div>
        <div class="table-responsive mb-2">
          <table class="table table-striped display nowrap text-nowrap w-100">
            <thead>
              <tr>
                <!-- <th scope="col">#</th> -->
                <th scope="col">{{ $t('Product') }}</th>
                <th scope="col">{{ $t('Product Code') }}</th>
                <th scope="col" class="text-right">{{ $t('Quantity') }}</th>
                <th scope="col" class="text-right">{{ $t('Price') }} ({{ $t('EXCL Tax') }})</th>
                <th scope="col" class="text-right">{{ $t('Discount %') }}</th>
                <th scope="col" class="text-right">
                  {{ $t('Discount Amount') }}
                </th>
                <!-- <th scope="col" class="text-right">{{ $t("Tax %") }}</th>
                <th scope="col" class="text-right">{{ $t("Tax Amount") }}</th> -->
                <th scope="col" class="text-right">{{ $t('Total') }}</th>
              </tr>
            </thead>
            <tbody v-if="products.length > 0">
              <tr v-for="(order_product, key, index) in products" v-bind:value="order_product.product_slack" v-bind:key="index">
                <!-- <th scope="row" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">{{ key + 1 }}</th> -->

                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                    <span v-if="order_product.name == undefined" class="text-bold fs-15">{{ order_product.combo_name }}</span>
                    <span v-else>
                        <span :class="(order_product.name != undefined && order_product.combo_name != '') ? 'text-muted border-0' : 'text-dark fs-15' " >{{ order_product.name }}</span>
                    </span>
                    
                    <i class="fa fa-gift text-success" aria-hidden="true" style="font-size: 11px;" v-if= "order_product.is_gifted  == 1"></i>
                    <br />
                    <small v-if="order_product.modifier_options.length" v-for="modifier in order_product.modifier_options">
                        {{ modifier.modifier_label }} : {{ modifier.modifier_option_label }} <br />
                    </small>
                    <span v-if="order_product.note != '' && order_product.note != null">
                        <small class="text-danger">Note: {{ order_product.note }}</small>
                    </span>

                </td>

                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">{{ order_product.product_code }}</td>

                <td class="text-right" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                  <span v-if="order_product.name != undefined && order_product.combo_id == '' " :class="(order_product.combo_cart_id == undefined || order_product.combo_cart_id == '') ? 'text-muted border-0' : '' ">
                    {{ order_product.quantity }}
                  </span>
                  <br />
                  <small v-if="order_product.order_product_modifier_options.length" v-for="modifier in order_product.order_product_modifier_options">
                    -
                    <br />
                  </small>
                </td>
                <td class="text-right" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                  {{ order_product.price }}
                  <br />
                  <small v-if="order_product.order_product_modifier_options.length" v-for="modifier in order_product.order_product_modifier_options">
                    {{ modifier.modifier_option_price }}
                    <br />
                  </small>
                </td>
                <td class="text-right" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                  {{ order_product.discount_percentage }}
                </td>
                <td class="text-right" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">{{ order_product.discount_amount }}</td>
                <!-- <td class="text-right">{{ order_product.tax_percentage }}</td> -->
                <!-- <td class="text-right">{{ order_product.tax_amount }}</td> -->
                <td class="text-right" :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                  <!-- <span>{{ order_product.total_after_discount }}</span> -->
                  <span v-if="order_product.name == undefined" >{{ order_product.combo_total_price }}</span>
                  <span v-else> <span v-if="order_product.combo_id == undefined || order_product.combo_id == '' ">{{ order_product.total_after_discount }}</span> </span>

                </td>
              </tr>
              <tr>
                <td colspan="6" class="text-right" >{{ $t('Sub Total') }} ({{ $t('EXCL Tax') }})</td>
                <td class="text-right">
                  {{ order_basic.sale_amount_subtotal_excluding_tax }}
                </td>
              </tr>

              <tr>
                <td colspan="6" class="text-right">
                  {{ $t('Additional Discount') }}
                  <span v-if="order_basic.additional_discount_percentage && order_basic.sale_amount_subtotal_excluding_tax > 0">
                    ({{ order_basic.additional_discount_percentage }} % )
                  </span>
                </td>
                <td class="text-right">
                  {{ order_basic.additional_discount_amount }}
                </td>
              </tr>

              <tr>
                <td colspan="6" class="text-right">{{ $t('Total After Discount') }}</td>
                <td class="text-right">{{ order_basic.total_after_discount }}</td>
              </tr>
              <!-- <tr>
                <td colspan="6" class="text-right">{{ $t('Total Tax') }}</td>
                <td class="text-right">{{ order_basic.total_tax_amount }}</td>
              </tr> -->
              <tr v-for="tax_component in order_basic.order_level_tax_components" :key="tax_component.tax_type">
                  <td colspan="6" class="text-right">
                      {{ $t(tax_component.tax_type) }} {{ $t(tax_component.tax_percentage + ' %') }}
                  </td>
                  <td class="text-right"> {{ tax_component.tax_amount | roundDecimal }} </td>
              </tr>
              <tr>
                <td colspan="6" class="text-right text-bold">{{ $t('Total') }} ({{ $t('INCL Tax') }})</td>
                <td class="text-right text-bold">
                  {{ order_basic.currency_code }}
                  {{ order_basic.total_order_amount }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div id="edit_product" v-show="view_edit_product">
        <form @submit.prevent="submit_form" class="mb-3">
          <div class="mb-2">
            <span class="text-subhead">{{ $t('Edit Product Information') }}</span>
          </div>
          <div class="table-responsive mb-2">
            <table class="table table-striped display nowrap text-nowrap w-100">
              <thead>
                <tr>
                  <!-- <th scope="col">#</th> -->
                  <th scope="col">{{ $t('Product Code') }}</th>
                  <th scope="col">{{ $t('Product') }}</th>
                  <th scope="col" class="text-right">{{ $t('Quantity') }}</th>
                  <th scope="col" class="text-right">{{ $t('Price') }} ({{ $t('EXCL Tax') }})</th>
                  <th scope="col" class="text-right">{{ $t('Discount %') }}</th>
                  <th scope="col" class="text-right">
                    {{ $t('Discount Amount') }}
                  </th>
                  <th scope="col" class="text-right">{{ $t('Total') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(order_product, key, index) in products" v-bind:value="order_product.product_slack" v-bind:key="index">
                  <th scope="row">{{ key + 1 }}</th>
                  <td>{{ order_product.product_code }}</td>
                  <td>{{ order_product.name }}</td>
                  <td class="text-right">
                    <input
                      type="number"
                      v-bind:name="'order_product.quantity_' + key"
                      v-model="order_product.quantity"
                      v-validate="'required|decimal|min_value:0'"
                      data-vv-as="Quantity"
                      class="form-control form-control-custom"
                      autocomplete="off"
                      step="1"
                      v-bind:max="order_product.max_quantity"
                      min="0"
                      v-on:input="calculate_price"
                    />
                    <span v-bind:class="{error: errors.has('product_quantity')}">{{ errors.first('product_quantity') }}</span>
                  </td>
                  <td class="text-right">{{ order_product.price }}</td>
                  <td class="text-right">
                    {{ order_product.discount_percentage }}
                  </td>
                  <td class="text-right">
                    {{ order_product.discount_amount }}
                  </td>
                  <td class="text-right">{{ order_product.total_price }}</td>
                </tr>
                <tr>
                  <td colspan="6" class="text-right">{{ $t('Sub Total') }} ({{ $t('EXCL Tax') }})</td>
                  <td class="text-right">
                    {{ order_basic.total_amount_before_additional_discount }}
                  </td>
                </tr>

                <tr>
                  <td colspan="6" class="text-right">
                    {{ $t('Additional Discount') }}
                    <span v-if="order_basic.additional_discount_percentage">({{ order_basic.additional_discount_percentage }}%)</span>
                  </td>
                  <td class="text-right">
                    {{ order_basic.additional_discount_amount }}
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="text-right">
                    {{ $t('Total After Discount') }}
                  </td>
                  <td class="text-right">
                    {{ order_basic.total_after_discount }}
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="text-right">
                    {{ $t('Total Tax') }}
                  </td>
                  <td class="text-right">{{ order_basic.total_tax_amount }}</td>
                </tr>
                <tr>
                  <td colspan="6" class="text-right text-bold">{{ $t('Total') }} (INCL Tax)</td>
                  <td class="text-right text-bold">
                    {{ order_basic.currency_code }}
                    {{ order_basic.total_order_amount }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="flex-wrap mb-4">
            <div class="text-right">
              <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true">
                <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                {{ $t('Return') }}
              </button>
            </div>
          </div>
        </form>
      </div>

      <hr />

      <transactionlistcomponent :transaction_list="transactions"></transactionlistcomponent>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
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

    <modalcomponent v-if="show_share_invoice_sms_modal" v-on:close="show_share_invoice_sms_modal = false">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
        {{ $t('Are you sure you want to share the invoice as SMS to') }}
        {{ order_basic.customer_phone }}?
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

import number_format from 'locutus/php/strings/number_format';

export default {
  data() {
    return {
      server_errors: '',
      error_class: '',
      order_processing: false,
      processing: false,
      show_modal: false,

      show_share_invoice_sms_modal: false,
      send_sms_processing: false,
      view_edit_product: false,
      view_product_details: true,

      delete_order_api_link: '/api/delete_order/' + this.return_order_data.slack,
      sms_invoice_api_link: '/api/share_invoice_sms/' + this.return_order_data.slack,
      return_order_api_link: '/api/return_order_list',

      slack: this.return_order_data.slack,
      order_basic: this.return_order_data,
      products: this.return_order_data.products,
      damageproducts: this.return_order_data.damageproducts,
      transactions: this.return_order_data.transactions,
      grand_total: 0,
      store_tax_percentage_amt: this.store_tax_percentage == null ? '' : this.store_tax_percentage,
      total_product_price: 0
    };
  },
  props: {
    store_tax_percentage: '',
    return_order_data: [Array, Object],
    delete_order_access: Boolean,
    return_order_exist: Boolean,
    share_invoice_sms_access: Boolean,
    print_return_order_link: String,
    print_return_pos_receipt_link: String,
    additional_discount_amt: String
  },
  filters: {
    roundDecimal: function(value) {
        // return number_format(value, 2, ".", "");
        return Number(parseFloat(value).toFixed(2)).toLocaleString();
    },
    formatDecimal: function(value) {
        var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
        return Number(value.toString().match(re)[0]).toLocaleString();
        // return parseFloat(value).toFixed(2);
    }
  },
  mounted() {
    console.log('Order detail page loaded');
    console.log(this.products);
  },
  methods: {
    delete_order() {
      this.$off('submit');
      this.$off('close');
      this.show_modal = true;

      this.$on('submit', function() {
        this.processing = true;
        this.order_processing = true;

        var formData = new FormData();
        formData.append('access_token', window.settings.access_token);

        axios
          .post(this.delete_order_api_link, formData)
          .then(response => {
            if (response.data.status_code == 200) {
              if (response.data.link != '') {
                window.location.href = response.data.link;
              } else {
                location.reload();
              }
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
            this.order_processing = false;
          })
          .catch(error => {
            console.log(error);
          });
      });

      this.$on('close', function() {
        this.show_modal = false;
      });
    },

    return_order() {
      this.view_edit_product = true;
      this.view_product_details = false;
    },

    calculate_price() {
      var grand_total = 0;
      var item_total_excluding_tax = 0;
      var total_additional_discount_amount = 0;
      var last_additional_discount_amount = 0;
      var final_total_tax = 0;
      var total_quantity = 0;
      var total_max_quantity = 0;
      if (this.products.length > 0) {
        for (var index in this.products) {
          var cart_length = Object.keys(this.products).length;
          var discount_amount = 0;
          var tax_amount = 0;
          var item_total = 0;

          var quantity = this.products[index].quantity;
          var max_quantity = this.products[index].max_quantity;
          var unit_price = this.products[index].price;
          var discount_percentage = this.products[index].discount_percentage;
          var tax_percentage = this.products[index].tax_percentage;

          if (!isNaN(quantity) && quantity != null && quantity != '' && !isNaN(unit_price) && unit_price != null && unit_price != '') {
            item_total = parseFloat(quantity) * parseFloat(unit_price);

            if (!isNaN(discount_percentage) && discount_percentage != null && discount_percentage != '') {
              if (discount_percentage >= 0) {
                discount_amount = this.calculate_discount(item_total, discount_percentage);
                discount_amount = number_format(discount_amount, 2, '.', '');
                this.products[index].discount_amount = discount_amount;
                item_total = parseFloat(item_total) - parseFloat(discount_amount);
              }
            }
            item_total = number_format(item_total, 2, '.', '');
            this.products[index].total_price = item_total;
            this.products[index].sub_total_purchase_price_excluding_tax = item_total;
            this.products[index].total_after_discount = item_total;
            item_total_excluding_tax = parseFloat(item_total_excluding_tax) + parseFloat(item_total);
            item_total_excluding_tax = number_format(item_total_excluding_tax, 2, '.', '');
            if (!isNaN(tax_percentage) && tax_percentage != null && tax_percentage != '') {
              if (tax_percentage >= 0) {
              }
            }
            tax_amount = number_format(tax_amount, 2, '.', '');
            this.products[index].sub_total = item_total;

            item_total = parseFloat(item_total) + parseFloat(tax_amount);
            item_total = item_total.toFixed(2);
            this.products[index].amount = item_total;

            total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
            total_max_quantity = parseFloat(total_max_quantity) + parseFloat(max_quantity);
            grand_total = parseFloat(grand_total) + parseFloat(item_total);
          } else {
            continue;
          }
        }
      } else {
        for (var index in this.damageproducts) {
          var cart_length = Object.keys(this.damageproducts).length;
          var discount_amount = 0;
          var tax_amount = 0;
          var item_total = 0;

          var quantity = this.damageproducts[index].quantity;
          var max_quantity = this.damageproducts[index].max_quantity;
          var unit_price = this.damageproducts[index].price;
          var discount_percentage = this.damageproducts[index].discount_percentage;
          var tax_percentage = this.damageproducts[index].tax_percentage;

          if (!isNaN(quantity) && quantity != null && quantity != '' && !isNaN(unit_price) && unit_price != null && unit_price != '') {
            item_total = parseFloat(quantity) * parseFloat(unit_price);

            if (!isNaN(discount_percentage) && discount_percentage != null && discount_percentage != '') {
              if (discount_percentage >= 0) {
                discount_amount = this.calculate_discount(item_total, discount_percentage);
                discount_amount = number_format(discount_amount, 2, '.', '');
                this.damageproducts[index].discount_amount = discount_amount;
                item_total = parseFloat(item_total) - parseFloat(discount_amount);
              }
            }
            item_total = number_format(item_total, 2, '.', '');
            this.damageproducts[index].total_price = item_total;
            this.damageproducts[index].sub_total_purchase_price_excluding_tax = item_total;
            this.damageproducts[index].total_after_discount = item_total;
            item_total_excluding_tax = parseFloat(item_total_excluding_tax) + parseFloat(item_total);
            item_total_excluding_tax = number_format(item_total_excluding_tax, 2, '.', '');
            if (!isNaN(tax_percentage) && tax_percentage != null && tax_percentage != '') {
              if (tax_percentage >= 0) {
              }
            }
            tax_amount = number_format(tax_amount, 2, '.', '');
            this.damageproducts[index].sub_total = item_total;

            item_total = parseFloat(item_total) + parseFloat(tax_amount);
            item_total = item_total.toFixed(2);
            this.damageproducts[index].amount = item_total;

            total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
            total_max_quantity = parseFloat(total_max_quantity) + parseFloat(max_quantity);
            grand_total = parseFloat(grand_total) + parseFloat(item_total);
          } else {
            continue;
          }
        }
      }
      grand_total = number_format(grand_total, 2, '.', '');

      if (this.order_basic.discount_type == '1') {
        last_additional_discount_amount = parseFloat(this.order_basic.total_discount_amount) / parseFloat(total_max_quantity);
        last_additional_discount_amount = number_format(last_additional_discount_amount, 2, '.', '');

        last_additional_discount_amount = parseFloat(last_additional_discount_amount) * parseFloat(total_quantity);
        last_additional_discount_amount = number_format(last_additional_discount_amount, 2, '.', '');
      } else {
        last_additional_discount_amount = this.calculate_discount(grand_total, this.order_basic.additional_discount_percentage);
      }

      last_additional_discount_amount = number_format(last_additional_discount_amount, 2, '.', '');
      this.order_basic.additional_discount_amount = last_additional_discount_amount;

      if (!isNaN(this.order_basic.total_amount_before_additional_discount) && this.order_basic.total_amount_before_additional_discount != '') {
        this.order_basic.total_amount_before_additional_discount = number_format(grand_total, 2, '.', '');
      }

      if (!isNaN(this.order_basic.additional_discount_amount) && this.order_basic.additional_discount_amount != '') {
        grand_total = parseFloat(grand_total) - parseFloat(this.order_basic.additional_discount_amount);
        this.order_basic.total_after_discount = number_format(grand_total, 2, '.', '');
      }

      final_total_tax = this.calculate_tax(this.order_basic.total_after_discount, this.store_tax_percentage);

      this.order_basic.total_tax_amount = number_format(final_total_tax, 2, '.', '');
      if (!isNaN(this.order_basic.total_tax_amount) && this.order_basic.total_tax_amount != '') {
        grand_total = parseFloat(grand_total) + parseFloat(this.order_basic.total_tax_amount);
      }

      this.grand_total = grand_total.toFixed(2);
      this.order_basic.total_order_amount = this.grand_total;
    },

    calculate_tax(item_total, tax_percentage) {
      var tax_amount = (parseFloat(tax_percentage) / 100) * parseFloat(item_total);
      return tax_amount;
    },

    calculate_discount(item_total, discount_percentage) {
      var discount_amount = (parseFloat(discount_percentage) / 100) * parseFloat(item_total);
      return discount_amount;
    },

    submit_form() {
      this.$off('submit');
      this.$off('close');

      var total_quantity = 0;
      if (this.products.length > 0) {
        for (var index in this.products) {
          var discount_amount = 0;
          var tax_amount = 0;
          var item_total = 0;

          var quantity = this.products[index].quantity;
          total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
        }
      } else {
        for (var index in this.damageproducts) {
          var discount_amount = 0;
          var tax_amount = 0;
          var item_total = 0;

          var quantity = this.damageproducts[index].quantity;
          total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
        }
      }

      if (total_quantity == 0) {
        alert('Quantity cannot be zero.');
        return false;
      }

      this.$validator.validateAll().then(result => {
        if (result) {
          this.show_modal = true;
          this.$on('submit', function() {
            this.processing = true;
            var formData = new FormData();

            formData.append('access_token', window.settings.access_token);
            formData.append('order_slack', this.slack);
            formData.append('products', this.products.length > 0 ? JSON.stringify(this.products) : JSON.stringify(this.damageproducts));
            formData.append('order_basic', JSON.stringify(this.order_basic));

            axios
              .post(this.return_order_api_link, formData)
              .then(response => {
                if (response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, 'SUCCESS');
                  if (typeof response.data.link != 'undefined' && response.data.link != '') {
                    this.show_modal = false;

                    this.processing = false;

                    if (response.data.new_tab == true) {
                      window.open(response.data.link, '_blank');
                    } else {
                      window.location.href = response.data.link;
                    }
                    this.view_edit_product = false;
                    this.view_product_details = true;
                    this.return_order_exist = true;

                    setTimeout(function() {
                      //window.location.reload();
                    }, 1000);
                  } else {
                    setTimeout(function() {
                      window.location.reload();
                    }, 1000);
                  }
                } else {
                  this.show_modal = false;
                  this.processing = false;
                  window.scrollTo(0, 0);
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

    share_invoice_as_sms() {
      this.$off('submit');
      this.$off('close');
      this.show_share_invoice_sms_modal = true;

      this.$on('submit', function() {
        this.processing = true;
        this.send_sms_processing = true;

        var formData = new FormData();
        formData.append('access_token', window.settings.access_token);

        axios
          .post(this.sms_invoice_api_link, formData)
          .then(response => {
            if (response.data.status_code == 200) {
              this.show_response_message(response.data.msg, 'SUCCESS');
              if (typeof response.data.link != 'undefined' && response.data.link != '') {
                if (response.data.new_tab == true) {
                  window.open(response.data.link, '_blank');
                } else {
                  window.location.href = response.data.link;
                }

                setTimeout(function() {
                  window.location.reload();
                }, 1000);
              } else {
                setTimeout(function() {
                  window.location.reload();
                }, 1000);
              }
            } else {
              this.show_share_invoice_sms_modal = false;
              this.processing = false;
              try {
                var error_json = JSON.parse(response.data.msg);
                this.loop_api_errors(error_json);
              } catch (err) {
                this.server_errors = response.data.msg;
              }
              this.error_class = 'error';
            }
            this.send_sms_processing = false;
          })
          .catch(error => {
            console.log(error);
          });
      });

      this.$on('close', function() {
        this.show_share_invoice_sms_modal = false;
      });
    },
    getTotalProductPrice(order_product) {
      console.log(order_product);
      let product_price = 0;
      if (typeof order_product.total_amount !== 'undefined') {
        product_price = parseFloat(order_product.total_amount);
      } else {
        product_price = parseFloat(order_product.total_price);
      }
      product_price = order_product.quantity * parseFloat(product_price);

      return product_price;
    }
  }
};
</script>
