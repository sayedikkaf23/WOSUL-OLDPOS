<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">
                {{ $t("TAX Invoice") }} : {{ invoice_basic.bill_to }} </span
              ><br />
            </div>
          </div>
        </div>
        <div class="">
         
          <span
            v-if="
              invoice_basic.restaurant_mode == 1 &&
                invoice_basic.kitchen_status != null
            "
            v-bind:class="invoice_basic.kitchen_status.color"
            class="mr-2"
            >{{ $t(invoice_basic.kitchen_status.label) }}</span
          >
          <span v-bind:class="invoice_basic.status.color">{{
            $t(invoice_basic.status.label)
          }}</span>
        </div>
      </div>

      <div class="d-flex flex-wrap mb-4">
        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="ml-auto">
          <a
            class="btn btn-warning mr-1"
            v-on:click="back_invoice()"
            target="_blank"
            >{{ $t("Back") }}</a
          >
          <div class="ml-auto">
            <!-- <button
            v-show="return_invoice_exist == true"
            class="btn btn-success mr-1"
          >
            <i
              class="fa fa-circle-notch fa-spin"
              v-if="order_processing == true"
            ></i>
            {{ $t("Invoice Returned") }}
          </button> -->
          </div>
        </div>
      </div>
      <hr />

      <div class="col-lg-12 ">
        <div class="row">
          <div class="col-lg-10">
            <p>
              <b
                >{{ $t("Invoice Number") }} #:
                {{ invoice_basic.invoice_number }}</b
              >
            </p>
            <p>{{ $t("Invoice Date") }} : {{ invoice_basic.invoice_date }}</p>
            <p>
              {{ $t("Invoice Due Date") }} :
              {{ invoice_basic.invoice_due_date }}
            </p>
          </div>
          <div class="col-lg-2">
            <span></span>
          </div>
        </div>
      </div>
      <hr />
      <div class="col-lg-12 ">
        <div class="row">
          <div class="col-lg-6" v-if="suppliers!=null">
            <br />
            <p v-if="suppliers.name != null">
              <b>{{ $t("Customer Name") }} : {{ suppliers.name }}</b>
            </p>
            <p v-if="suppliers.email != null">
              {{ $t("Email") }} : {{ suppliers.email }}
            </p>
            <p v-if="suppliers.street_name != null">
              {{ $t("Street Name") }} : {{ suppliers.street_name }}
            </p>
            <p v-if="suppliers.district != null">
              {{ $t("District") }} : {{ suppliers.district }}
            </p>
            <p v-if="suppliers.city != null">
              {{ $t("City") }} : {{ suppliers.city }}
            </p>
            <p v-if="suppliers.country_name != null">
              {{ $t("Country") }} : {{ suppliers.country_name }}
            </p>
            <p v-if="suppliers.pincode != null">
              {{ $t("Pin Code") }} : {{ suppliers.pincode }}
            </p>
            <p v-if="suppliers.phone != null">
              {{ $t("Telephone") }} : {{ suppliers.phone }}
            </p>
            <p v-if="suppliers.tax_number != null">
              {{ $t("Tax Number") }} : {{ suppliers.tax_number }}
            </p>
            <p v-if="suppliers.other_seller_id != null">
              {{ $t("Other Seller Id") }} : {{ suppliers.other_seller_id }}
            </p>
          </div>

          <div class="col-lg-6 ">
            <br />
            <p v-if="store_details.name != null">
              <b>{{ $t("Name") }} : {{ store_details.name }}</b>
            </p>
            <p v-if="store_details.primary_email != null">
              {{ $t("Email") }} : {{ store_details.primary_email }}
            </p>
            <p v-if="store_details.street_name != null">
              {{ $t("Street Name") }} : {{ store_details.street_name }}
            </p>
            <p v-if="store_details.district != null">
              {{ $t("District") }} : {{ store_details.district }}
            </p>
            <p v-if="store_details.city != null">
              {{ $t("City") }} : {{ store_details.city }}
            </p>
            <p v-if="store_details.country_name != null">
              {{ $t("Country") }} : {{ store_details.country_name }}
            </p>
            <p v-if="store_details.pincode != null">
              {{ $t("Pin Code") }} : {{ store_details.pincode }}
            </p>
            <p v-if="store_details.primary_contact != null">
              {{ $t("Telephone") }} : {{ store_details.primary_contact }}
            </p>
            <p v-if="store_details.vat_number != null">
              {{ $t("Tax Number") }} : {{ store_details.vat_number }}
            </p>
            <p v-if="store_details.other_seller_id != null">
              {{ $t("Other Seller Id") }} : {{ store_details.other_seller_id }}
            </p>
          </div>
        </div>
      </div>
      <hr v-show="return_reason_data != ''"/>
      <div class="mb-3 mt-3" v-show="return_reason_data != ''">
        <div class="mb-2">
          <span class="text-subhead">{{ $t("Reason for Return") }}</span>
        </div>
        <div class="mb-3">{{ return_reason_data }}</div>
      </div>
      <hr />

      <div id="edit_product" v-show="view_edit_product">
        <form @submit.prevent="select_reason()" class="mb-3">
          <div class="mb-2">
            <span class="text-subhead">{{
              $t("Edit Product Information")
            }}</span>
          </div>
          <div class="table-responsive mb-2">
            <table class="table table-striped display nowrap text-nowrap w-100">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">{{ $t("Product Code") }}</th>
                  <td scope="col" v-show="restaurant_mode == true">
                    Is Wastage?
                  </td>
                  <th scope="col">{{ $t("Product") }}</th>
                  <th scope="col" class="text-right">{{ $t("Quantity") }}</th>
                  <th scope="col" class="text-right">
                    {{ $t("Unit Price") }}
                  </th>

                  <th scope="col" class="text-right">
                    {{ $t("Discount Amount") }}
                  </th>
                  <th scope="col" class="text-right">
                    {{ $t("Tax") }}
                  </th>
                  <th scope="col" class="text-right">{{ $t("Total") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(invoice_product, key, index) in products"
                  v-bind:value="invoice_product.product_slack"
                  v-bind:key="index"
                >
                  <th scope="row">{{ key + 1 }}</th>
                  <td v-show="restaurant_mode == true">
                    <input
                      type="checkbox"
                      v-model="invoice_product.is_wastage"
                    />
                  </td>
                  <td>{{ invoice_product.product_code }}</td>
                  <td>
                  
                    {{ invoice_product.name }}

                    <small v-if=" invoice_product.product_type == 1 && invoice_product.description != null && ( invoice_product.show_description_in == 1 || invoice_product.show_description_in == 3) " class="text-muted">  <br>{{ invoice_product.description }} </small>
                    <small v-if=" invoice_product.product_type == 2 && invoice_product.description != null " class="text-muted">  <br>{{ invoice_product.description }} </small>
                  </td>
                  <td class="text-right" v-if="invoice_product.max_quantity>0">
                    <input
                      type="number"
                      v-bind:name="'invoice_product.quantity_' + key"
                      v-model="invoice_product.quantity"
                      v-validate="'required|min_value:0'"
                      data-vv-as="Quantity"
                      class="form-control form-control-custom"
                      autocomplete="off"
                      step=".01"
                      v-bind:max="invoice_product.max_quantity"
                      min="0"
                      v-on:input="calculate_price"
                    />
                    <span
                      v-bind:class="{ error: errors.has('product_quantity') }"
                      >{{ errors.first("product_quantity") }}</span
                    >
                  </td>
                  <td class="text-right" v-else>
                    <input
                        type="hidden"
                        v-bind:name="'invoice_product.quantity_' + key"
                        v-model="invoice_product.quantity"
                        data-vv-as="Quantity"
                        class="form-control form-control-custom"
                        autocomplete="off"
                        step=".01"
                        v-bind:max="invoice_product.max_quantity"
                        min="0"
                    />{{ invoice_product.main_quantity }} <span class="label yellow-label"> Returned</span>
                  </td>
                  <td class="text-right">
                    {{ invoice_product.amount_excluding_tax }}
                  </td>

                  <td class="text-right">
                    {{ invoice_product.discount_amount }}
                  </td>
                  <td class="text-right">
                    {{ invoice_product.tax_amount }}
                  </td>

                  <td class="text-right">
                    {{ invoice_product.amount }}
                  </td>
                </tr>
                <tr>
                  <td
                    :colspan="[restaurant_mode == true ? 8 : 7]"
                    class="text-right"
                  >
                    {{ $t("Sub Total") }}
                  </td>
                  <td class="text-right">
                    {{ invoice_basic.subtotal_excluding_tax }}
                  </td>
                </tr>

                <tr>
                  <td
                    :colspan="[restaurant_mode == true ? 8 : 7]"
                    class="text-right"
                  >
                    {{ $t("Discount") }}
                  </td>
                  <td class="text-right">
                    {{ invoice_basic.total_discount_amount }}
                  </td>
                </tr>
                <tr>
                  <td
                    :colspan="[restaurant_mode == true ? 8 : 7]"
                    class="text-right"
                  >
                    {{ $t("Total After Discount") }}
                  </td>
                  <td class="text-right">
                    {{ invoice_basic.total_after_discount }}
                  </td>
                </tr>
                <tr>
                  <td
                    :colspan="[restaurant_mode == true ? 8 : 7]"
                    class="text-right"
                  >
                    {{ $t("Total Tax") }}
                  </td>
                  <td class="text-right">
                    {{ invoice_basic.total_tax_amount }}
                  </td>
                </tr>
                <tr>
                  <td
                    :colspan="[restaurant_mode == true ? 8 : 7]"
                    class="text-right text-bold"
                  >
                    {{ $t("Total") }}
                  </td>
                  <td class="text-right text-bold">
                    {{ invoice_basic.currency_code }}
                    {{ invoice_basic.total_order_amount }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="flex-wrap mb-4">
            <div class="text-right">
              <button
                v-show="return_invoice_exist == false"
                type="submit"
                class="btn btn-primary"
                v-bind:disabled="processing == true"
              >
                <i
                  class="fa fa-circle-notch fa-spin"
                  v-if="processing == true"
                ></i>
                {{ $t("Return") }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
        {{ $t("Are you sure you want to proceed?") }}
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Cancel") }}
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent
      v-if="show_share_invoice_sms_modal"
      v-on:close="show_share_invoice_sms_modal = false"
    >
      <template v-slot:modal-header>
        {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
        {{ $t("Are you sure you want to share the invoice as SMS to") }}
        {{ invoice_basic.customer_phone }}?
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Cancel") }}
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent
      v-if="show_reason_modal"
      v-on:close="show_reason_modal = false"
    >
      <template v-slot:modal-header>
        {{ $t("Please Choose A Reason for Return") }}
      </template>
      <template v-slot:modal-body>
        <form data-vv-scope="return_form">
          <div class="form-group">
            <select v-model="selected_payment" name="return_payment"  v-validate="'required'" class="form-control form-control-custom custom-select">
                <option value="" selected>Select Payment Type</option>
                <option v-for="payment in payment_methods" :value="payment.slack" :key="payment.slack">{{ payment.label }}</option>
            </select>
            <span v-bind:class="{ error: errors.has('return_form.return_payment'),}">
              {{ errors.has('return_form.return_payment')? $t("Payment type is required!"):'' }}
            </span>
          </div>
          <div class="form-group">
            <select v-model="return_reason" class="form-control form-control-custom custom-select" v-validate="'required'" name="return_reason">
              <option value="" selected>Select Reason</option>
              <option value="Customer Cancelled">Customer Cancelled</option>
              <option value="Product Not Available">Product Not Available</option>
              <option value="0">Other</option>
            </select>
            <span v-bind:class="{error: errors.has('return_form.return_reason'),}">
              {{ errors.has('return_form.return_reason')?$t("Reason is required!"):'' }}</span>
          </div>
          
          <input
            v-if="return_reason == 0 && return_reason != ''"
            v-model="other_return_reason"
            class="form-control form-control-custom mt-3"
            autocomplete="off"
            placeholder="Enter Your Reason.."
          />
        </form>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="show_reason_modal = false">
          {{ $t("Cancel") }}
        </button>
        <button type="button" class="btn btn-primary" @click="submit_form()">
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script>
"use strict";

import number_format from "locutus/php/strings/number_format";

export default {
  data() {
    return {
      server_errors: "",
      selected_payment: "",
      error_class: "",
      order_processing: false,
      processing: false,
      show_modal: false,

      show_share_invoice_sms_modal: false,
      send_sms_processing: false,
      view_edit_product: true,
      view_product_details: false,

      delete_order_api_link: "/api/delete_order/" + this.invoice_data.slack,
      sms_invoice_api_link: "/api/share_invoice_sms/" + this.invoice_data.slack,
      return_invoice_api_link: "/api/invoice_return_list",
      invoice_details_link: "/invoice/" + this.invoice_data.slack,

      slack: this.invoice_data.slack,
      invoice_basic: this.invoice_data,
      store_details: this.store_detail,
      suppliers: this.supplier,
      products: this.invoice_data.products,
      transactions: this.invoice_data.transactions,
      grand_total: 0,
      store_tax_percentage_amt:
      this.store_tax_percentage = 0,
      total_product_price: 0,
      show_reason_modal: false,
      return_reason: "",
      other_return_reason: "",
    };
  },
  props: {
    store_tax_percentage: "",
    invoice_data: [Array, Object],
    store_detail: [Array, Object],
    supplier: [Array, Object],
    delete_order_access: Boolean,
    return_invoice_exist: Boolean,
    share_invoice_sms_access: Boolean,
    print_order_link: String,
    print_pos_receipt_link: String,
    additional_discount_amt: String,
    restaurant_mode: Boolean,
    return_reason_data: String,
    payment_methods: [Array, Object],
  },
  mounted() {
    console.log("Invoice detail page loaded");
    this.calculate_price();
    console.log(this.return_invoice_exist);
  },
  methods: {

    calculate_price() {
      var grand_total = 0;
      var item_total_excluding_tax = 0;
      var total_additional_discount_amount = 0;
      var product_discount_amount = 0;
      var final_total_tax = 0;
      var total_quantity = 0;
      var total_max_quantity = 0;
      var total_product_discount_amount = 0;
      var total_product_tax_amount = 0;
      var product_total_amount_after_discount = 0;

      for (var index in this.products) {

        var cart_length = Object.keys(this.products).length;
        var discount_amount = 0;
        var tax_amount = 0;
        var item_total = 0;
        var tax_percentage = 0;
        var product_discount_amount = 0;
        var discount_type = 0;
        var discount_percentage = 0;
        var product_tax_amount = 0;


        var quantity = this.products[index].quantity;
        var max_quantity = this.products[index].max_quantity;
        var unit_price = this.products[index].amount_excluding_tax;
        var discount_type = this.products[index].discount_type;
        var discount_percentage = this.products[index].discount_percentage;
        var product_discount_amount = this.products[index].discount_amount;
        var tax_percentage = this.products[index].tax_percentage;
        var product_tax_amount = this.products[index].tax_amount;

        if ( !isNaN(quantity) && quantity != null && quantity != "" && !isNaN(unit_price) && unit_price != null && unit_price != "" ) {
          console.log("discount_type::"+discount_type);
          console.log("unit_price::"+unit_price);
          console.log("quantity::"+quantity);
          item_total = parseFloat(quantity) * parseFloat(unit_price);
          console.log("item_total::"+item_total);
          item_total = number_format(item_total, 2, ".", "");
          if ( !isNaN(discount_percentage) && discount_percentage != null && discount_percentage != "") {
            product_discount_amount = this.calculate_discount( item_total, discount_type, discount_percentage, unit_price, quantity, this.products[index].main_quantity);
            product_discount_amount = number_format( product_discount_amount,2, ".", "");
            this.products[index].discount_amount = product_discount_amount;
          }else{
            product_discount_amount = 0;
          }
          console.log(product_discount_amount);
          this.products[index].total_after_discount = parseFloat(item_total) - parseFloat(product_discount_amount);
          product_total_amount_after_discount = this.products[index].total_after_discount;
          this.products[index].subtotal_amount_excluding_tax = item_total;
          this.products[index].sub_total_purchase_price_excluding_tax = item_total;
          
          item_total_excluding_tax = parseFloat(item_total_excluding_tax) + parseFloat(item_total);
          item_total_excluding_tax = number_format(item_total_excluding_tax,2, ".", "" );
          if ( !isNaN(tax_percentage) && tax_percentage != null && tax_percentage != "" ) {
            if (tax_percentage >= 0) {
              product_tax_amount = this.calculate_tax( product_total_amount_after_discount, tax_percentage );
              product_tax_amount = number_format( product_tax_amount, 2, ".", "");
              this.products[index].tax_amount = product_tax_amount;
            }
          }
          // tax_amount = number_format(tax_amount, 2, ".", "");
          this.products[index].sub_total = item_total;
          item_total = parseFloat(product_total_amount_after_discount) + parseFloat(product_tax_amount);
          item_total = item_total.toFixed(2);
          this.products[index].amount = item_total;
          total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
          total_max_quantity = parseFloat(total_max_quantity) + parseFloat(max_quantity);
          grand_total = parseFloat(grand_total) + parseFloat(this.products[index].sub_total);
        } else {
          this.products[index].subtotal_amount_excluding_tax = '0.00';
          continue;
        }

        total_product_discount_amount = parseFloat(total_product_discount_amount) + parseFloat(product_discount_amount);
        
        total_product_tax_amount = parseFloat(total_product_tax_amount) + parseFloat(product_tax_amount);
      }

      grand_total = number_format(grand_total, 2, ".", "");
      total_product_tax_amount = number_format(total_product_tax_amount, 2, ".", "");
      total_product_discount_amount = number_format(total_product_discount_amount, 2, ".", "");


      if ( !isNaN(this.invoice_basic.subtotal_excluding_tax) && this.invoice_basic.subtotal_excluding_tax != "" ) {
        this.invoice_basic.subtotal_excluding_tax = number_format( grand_total, 2, ".", "" );
      }

      if (
        !isNaN(this.invoice_basic.total_discount_amount) &&
        this.invoice_basic.total_discount_amount != ""
      ) {
        grand_total = parseFloat(grand_total) - parseFloat(total_product_discount_amount);
        this.invoice_basic.total_after_discount = number_format(
          grand_total,
          2,
          ".",
          ""
        );
      }

      final_total_tax = total_product_tax_amount;

      this.invoice_basic.total_tax_amount = number_format(
        final_total_tax,
        2,
        ".",
        ""
      );
 
      if (
        !isNaN(this.invoice_basic.total_tax_amount) &&
        this.invoice_basic.total_tax_amount != ""
      ) {
        grand_total =
          parseFloat(grand_total) +
          parseFloat(this.invoice_basic.total_tax_amount);
      }


      this.invoice_basic.total_tax_amount = total_product_tax_amount;
      this.invoice_basic.total_discount_amount = total_product_discount_amount ;


      this.grand_total = grand_total.toFixed(2);
      this.invoice_basic.total_order_amount = this.grand_total;
    },

    calculate_tax(item_total, tax_percentage) {
      var tax_amount =
        (parseFloat(tax_percentage) / 100) * parseFloat(item_total);
      return tax_amount;
    },

    calculate_discount(item_total, discount_type, discount_percentage, unit_price, qty,
    max_qty) {
      if(discount_type == 1){
        var discount_amount = parseFloat(discount_percentage) * parseFloat(qty)/ parseFloat(max_qty);
      }else{
        var discount_amount = (parseFloat(discount_percentage) / 100) * parseFloat(item_total);
      }
      return discount_amount;
    },
    select_reason() {
      this.show_reason_modal = true;
    },
    submit_form() {

      this.$off("submit");
      this.$off("close");

      var total_quantity = 0;
      for (var index in this.products) {
        var discount_amount = 0;
        var tax_amount = 0;
        var item_total = 0;

        var quantity = this.products[index].quantity;
        total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
      }

      if (total_quantity == 0) {
        alert("Quantity cannot be zero.");
        return false;
      }
      // console.log(this.products);
      // return;
      this.$validator.validateAll("return_form").then((result) => {
        console.log('result ==', result);
        if (result) {
          this.show_reason_modal = false;
          this.show_modal = true;
          this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            if (this.return_reason == "0") {
              var return_reason = this.other_return_reason;
            } else {
              var return_reason = this.return_reason;
            }

            formData.append("access_token", window.settings.access_token);
            formData.append("invoice_slack", this.slack);
            formData.append("products", JSON.stringify(this.products));
            formData.append(
              "invoice_basic",
              JSON.stringify(this.invoice_basic)
            );
            formData.append("return_reason", return_reason);
            formData.append("payment_slack", this.selected_payment);
            axios
              .post(this.return_invoice_api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, "SUCCESS");
                  if (
                    typeof response.data.link != "undefined" &&
                    response.data.link != ""
                  ) {
                     this.show_modal = false;

                    this.processing = false;

                    if (response.data.new_tab == true) {
                      window.open(response.data.link, "_blank");
                    } else {
                      window.location.href = response.data.link;
                    }
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
                  this.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
          });

          this.$on("close", function() {
            this.show_modal = false;
          });
        }
      });
    },

    back_invoice() {
      window.location.href = this.invoice_details_link;
    },

    share_invoice_as_sms() {
      this.$off("submit");
      this.$off("close");
      this.show_share_invoice_sms_modal = true;

      this.$on("submit", function() {
        this.processing = true;
        this.send_sms_processing = true;

        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);

        axios
          .post(this.sms_invoice_api_link, formData)
          .then((response) => {
            if (response.data.status_code == 200) {
              this.show_response_message(response.data.msg, "SUCCESS");
              if (
                typeof response.data.link != "undefined" &&
                response.data.link != ""
              ) {
                if (response.data.new_tab == true) {
                  window.open(response.data.link, "_blank");
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
              this.error_class = "error";
            }
            this.send_sms_processing = false;
          })
          .catch((error) => {
            console.log(error);
          });
      });

      this.$on("close", function() {
        this.show_share_invoice_sms_modal = false;
      });
    },
  },
};
</script>
