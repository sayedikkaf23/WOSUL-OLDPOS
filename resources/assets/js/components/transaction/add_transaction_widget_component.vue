<template>
  <div class="row">
    <div class="col-md-12">
      <form class="mb-3" v-on:submit="submit_form">
        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div v-if="payment_pending_amount > 0">
          <div
            class="alert alert-primary"
            role="alert"
            v-show="show_currency_alert == true"
          >
            The store currency is in {{ currency_codes.store_currency }}, but
            the invoice currency is in {{ currency_codes.invoice_currency }}.
            Please convert and provide the payment amount in
            {{ currency_codes.store_currency }}.
          </div>

          <div class="form-row mb-2">
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Total Amount") }} ({{
                  this.currency_codes.store_currency
                }})</label
              >
              <div class="text-subtitle">{{ payment_total_amount }}</div>
            </div>
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Paid Amount") }} ({{
                  this.currency_codes.store_currency
                }})</label
              >
              <div class="text-subtitle">{{ payment_paid_amount }}</div>
            </div>
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Pending Amount") }} ({{
                  this.currency_codes.store_currency
                }})</label
              >
              <div class="text-subtitle">{{ payment_pending_amount }}</div>
            </div>
          </div>

          <div class="form-row mb-2" v-if="partial_payment_details != ''">
        <tr> {{ $t("Payment History") }}</tr>
            <table class="table table-striped ">
              <thead>
              
                <tr>
                  <th class="nowrap">Date<br /></th>
                  <th class="nowrap">Amount(SAR)<br /></th>
                  <th class="nowrap">Payment Method<br /></th>
                  <th class="nowrap">Notes<br /></th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="partial_payment_detail in partial_payment_details"
                  :key="partial_payment_detail.id"
                >
                  <td>{{ format_date_without_time(partial_payment_detail.transaction_date) }}</td>
                  <td>{{ partial_payment_detail.amount }}</td>
                  <td>{{ partial_payment_detail.payment_method }}</td>
                  <td>{{ partial_payment_detail.notes }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="form-row mb-2">
            <div class="form-group col-md-6">
              <label for="transaction_date">{{ $t("Transaction Date") }}</label>
              <date-picker
                :format="date.format"
                :lang="date.lang"
                v-model="transaction_date"
                v-validate="'required|date_format:yyyy-MM-dd'"
                input-class="form-control form-control-custom bg-white"
                ref="transaction_date"
                name="transaction_date"
                :placeholder="enter_transaction_date"
                autocomplete="off"
                :disabled-date="(date) => date >= new Date()"
              ></date-picker>
              <span v-bind:class="{ error: errors.has('transaction_date') }">{{
                errors.first("transaction_date")
              }}</span>
            </div>
            <div class="form-group col-md-6">
              <label for="account">{{ $t("Account") }}</label>
              <select
                name="account"
                v-model="account"
                v-validate="'required'"
                class="form-control form-control-custom custom-select"
              >
                <option value="">{{ $t("Choose Account..") }}</option>
                <option
                  v-for="(account, index) in accounts"
                  v-bind:value="account.slack"
                  v-bind:key="index"
                >
                  {{ account.label }} ({{ $t(account.account_type_label) }})
                </option>
              </select>
              <span v-bind:class="{ error: errors.has('account') }">{{
                errors.first("account")
              }}</span>
            </div>
          </div>

          <div class="form-row mb-2">
            
            <div class="form-group col-md-6">
              <label for="amount"
                >{{ $t("Amount") }} ({{ currency_codes.store_currency }})</label
              >
              <input
                type="number"
                name="amount"
                v-model="amount"
                v-validate="
                  `required|decimal|max_value:${payment_pending_amount}`
                "
                class="form-control form-control-custom"
                :placeholder="enter_the_amount"
                autocomplete="off"
                step="0.01"
                min="0"
              />
              <span v-bind:class="{ error: errors.has('amount') }">{{
                errors.first("amount")
              }}</span>
            </div>
            <div class="form-group col-md-6">
              <label for="payment_method">{{ $t("Payment Method") }}</label>
              <select
                name="payment_method"
                v-model="payment_method"
                v-validate="'required'"
                class="form-control form-control-custom custom-select"
                @change="onPaymentChange($event)"
              >
                <option value="">{{ $t("Choose Payment Method..") }}</option>
                <option
                  v-for="(payment_method, index) in payment_methods"
                  v-bind:value="payment_method.slack"
                  v-bind:key="index"
                >
                  {{ payment_method.label }}
                </option>
              </select>
              <span v-bind:class="{ error: errors.has('payment_method') }">{{
                errors.first("payment_method")
              }}</span>
            </div>
          </div>
          <div class="form-row mb-2" v-if="this.is_express_pay==1">

            <div class="form-group col-md-6" v-if="this.is_express_pay==1">
              <label for="contact"
              >{{ $t("Contact Number") }}</label
              >
              <input
                  type="text"
                  name="contact"
                  v-model="contact"
                  v-validate="
                  `required|numeric`
                "
                  class="form-control form-control-custom"
                  :placeholder="enter_the_contact_number"
                  autocomplete="off"
              />
              <span v-bind:class="{ error: errors.has('contact') }">{{
                  errors.first("contact")
                }}</span>
            </div>
            <div class="form-group col-md-6" v-if="this.is_express_pay==1">
              <img :src="this.mada_visa_master_img_link" alt="mada image" />
            </div>
          </div>

          <div class="form-row mb-2">
            <div class="form-group col-md-12">
              <label for="notes">{{ $t("Notes") }}</label>
              <textarea
                name="notes"
                v-model="notes"
                v-validate="'max:65535'"
                class="form-control form-control-custom"
                rows="3"
                :placeholder="enter_notes"
              ></textarea>
              <span v-bind:class="{ error: errors.has('notes') }">{{
                errors.first("notes")
              }}</span>
            </div>
          </div>
        </div>

        <div v-if="payment_pending_amount == 0">
          <p>{{ $t("You have already made the payment(s)") }}.</p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
"use strict";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import moment from "moment";
import { event_bus } from "../../event_bus.js";

export default {
  data() {
    return {
      date: {
        lang: "en",
        format: "YYYY-MM-DD",
      },

      server_errors: "",
      error_class: "",
      api_link: "/api/add_transaction",
      bill_to: this.bill_to_prop,
      bill_to_slack: this.invoice_slack,
      transaction_date: "",
      account: "",
      transaction_type: this.transaction_type_data,
      mada_visa_master_img_link: this.mada_visa_master_img,
      payment_method: "",
      partial_payment_details: "",
      amount: "",
      notes: "",
      show_currency_alert:
        this.currency_codes.store_currency !=
        this.currency_codes.invoice_currency
          ? true
          : false,
      block_make_payment: ["CANCELLED", "PAID", "VOID", "WRITEOFF"],

      calculate_invoice_payment_api_link:
        "/api/get_invoice_pending_payment_data/" + this.invoice_slack,
      get_payment_method_api_link:
        "/api/payment_method",
      payment_total_amount: "-",
      payment_paid_amount: "-",
      payment_pending_amount: -1,
      payment_date: "-",
      enter_transaction_date: this.$t("Please enter transaction date"),
      enter_the_amount: this.$t("Please enter the amount"),
      enter_the_contact_number: this.$t("Please enter the contact number"),
      enter_notes: this.$t("Enter notes"),
      success: this.$t("SUCCESS"),
      show_express_payment_link_btn : false,
      is_express_pay:0,
      contact : (this.contact_number != null) ? this.contact_number : null 
    };
  },
  props: {
    transaction_type_data: String,
    accounts: [Array, Object],
    payment_methods: [Array, Object],
    invoice_slack: String,
    bill_to_prop: String,
    currency_codes: [Array, Object],
    express_payment_slack : String,
    contact_number : Number,
    mada_visa_master_img : String
  },
  watch : {
    payment_method : function(value){
      if(this.express_payment_slack != null && this.express_payment_slack != undefined && this.express_payment_slack == value){
        this.$emit('show_express_payment_link_btn',true);
        this.show_express_payment_link_btn = true;
      }else{
        this.$emit('show_express_payment_link_btn',false);
        this.show_express_payment_link_btn = false;
      }
    }
  },  
  mounted() {
    this.payment_pending_amount = -1;
    if (this.accounts) {
      this.account = this.accounts[0].slack;
    }

    event_bus.$on("submit_transaction", this.submit_form);

    switch (this.bill_to) {
      case "INVOICE":
        this.calculate_invoice_pending_transaction();
        break;
    }
  },
  methods: {
    format_date(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY hh:mm:ss");
      }
    },

    format_date_without_time(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY");
      }
    },

    convert_date_format(date) {
      return date != "" ? moment(date).format("YYYY-MM-DD") : "";
    },
    submit_form() {
      this.$off("submit_transaction");
      this.$off("cancel_transaction");

      this.$validator.validateAll().then((result) => {
        if (result) {
          event_bus.$emit("start_processing");
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append("bill_to", this.bill_to == null ? "" : this.bill_to);
          formData.append(
            "bill_to_slack",
            this.bill_to_slack == null ? "" : this.bill_to_slack
          );
          formData.append(
            "transaction_date",
            this.transaction_date == null
              ? ""
              : this.convert_date_format(this.transaction_date)
          );
          formData.append("account", this.account == null ? "" : this.account);
          formData.append(
            "transaction_type",
            this.transaction_type == null ? "" : this.transaction_type
          );
          formData.append("amount", this.amount == null ? "" : this.amount);
          formData.append(
            "payment_method",
            this.payment_method == null ? "" : this.payment_method
          );
        formData.append(
            "notes",
            this.notes == null ? "" : this.notes
          );

          if(this.show_express_payment_link_btn){
            this.api_link = '/api/add_expresspay_transaction';
            formData.append(
                "contact",
                this.contact == null ? "" : this.contact
            );
          }

          axios
            .post(this.api_link, formData)
            .then((response) => {
              if (response.data.status_code == 200) {
                this.show_response_message(response.data.msg, this.success);

                setTimeout(function() {
                  location.reload();
                }, 1000);
              } else {
                event_bus.$emit("stop_processing");
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

          this.$on("cancel_transaction", function() {
            event_bus.$emit("cancel_transaction");
          });
        }
      });
    },

    calculate_invoice_pending_transaction() {
      var formData = new FormData();

      formData.append("access_token", window.settings.access_token);

      axios
        .post(this.calculate_invoice_payment_api_link, formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.payment_total_amount = response.data.data.total_amount;
            this.payment_paid_amount = response.data.data.paid_amount;
            this.payment_pending_amount = response.data.data.pending_amount;
            this.payment_date = response.data.data.transaction_paid_date;
            this.partial_payment_details =
              response.data.data.invoice_transaction_details;

            if (
              !isNaN(this.payment_pending_amount) &&
              this.payment_pending_amount == 0
            ) {
              event_bus.$emit("invoice_paid");
            }
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = "error";
            this.show_modal = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onPaymentChange(event) {
      if(this.express_payment_slack == this.payment_method){
        this.is_express_pay = 1;
      }else{
        this.is_express_pay = 0;
      }
    }
  },
};
</script>
