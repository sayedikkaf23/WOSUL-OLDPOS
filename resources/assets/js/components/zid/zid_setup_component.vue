<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="name">{{ $t("Name") }}</label>
            <input
              type="text"
              name="name"
              v-model="name"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              :placeholder="enter_store_name"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('name') }">{{
              errors.first("name")
            }}</span>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
"use strict";

export default {
  components: {},
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,
      api_link:
        this.zid_store_data == null ? "/api/zid/setup" : "/api/zid/update",
      store_slack: this.zid_store_data == null ? "" : this.zid_store_data.slack,
      enter_authorization: this.$t("Please enter authorization"),
      enter_access_token: this.$t("Please enter access token"),
    };
  },
  props: {
    zid_store_data: null,
  },
  mounted() {
    console.log(this.tax_codes);
    // console.log(this.zid_store_data.vat_number);
    console.log("Add store page loaded");
  },
  methods: {
    create_qr_code() {
      this.processing_qr = true;

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("user_id", this.user_id);
      formData.append("store_id", this.store_id);

      axios
        .post(this.base_url + "/api/create_qr_code", formData)
        .then((response) => {
          console.log("response", response.data);

          if (response.data.status_code == 200) {
            if (response.data.data == "error") {
              $(".print-message").html(
                '<span class="alert alert-danger alert-dismissible fade show">' +
                  response.data.msg +
                  "</span>"
              );
              $("#btn_qr_code").css("display", "inline");
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
            this.error_class = "error";
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    sync_data() {
      this.processing_sync_data = true;

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("user_id", this.user_id);
      formData.append("restaurant_id", this.restaurant_id);
      formData.append("store_id", this.store_id);
      axios
        .post(this.base_url + "/api/sync_category_product", formData)
        .then((response) => {
          console.log("response", response.data);

          if (response.data.status_code == 200) {
            if (response.data.data == "error") {
              $(".print-message").html(
                '<span class="alert alert-danger alert-dismissible fade show">' +
                  response.data.msg +
                  "</span>"
              );
              $("#btn_sync_data").css("display", "inline");
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
            this.error_class = "error";
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            formData.append("access_token", window.settings.access_token);
            formData.append("name", this.name == null ? "" : this.name);
            formData.append(
              "store_code",
              this.store_code == null ? "" : this.store_code
            );
            formData.append(
              "vat_number",
              this.vat_number == null ? "" : this.vat_number
            );
            formData.append(
              "primary_contact",
              this.primary_contact == null ? "" : this.primary_contact
            );
            formData.append(
              "secondary_contact",
              this.secondary_contact == null ? "" : this.secondary_contact
            );
            formData.append(
              "primary_email",
              this.primary_email == null ? "" : this.primary_email
            );
            formData.append(
              "secondary_email",
              this.secondary_email == null ? "" : this.secondary_email
            );
            formData.append(
              "address",
              this.address == null ? "" : this.address
            );
            formData.append(
              "store_opening_time",
              this.store_opening_time == null ? "" : this.store_opening_time
            );
            formData.append(
              "store_closing_time",
              this.store_closing_time == null ? "" : this.store_closing_time
            );
            formData.append(
              "is_store_closing_next_day",
              this.is_store_closing_next_day == null
                ? false
                : this.is_store_closing_next_day
            );
            formData.append(
              "country",
              this.country == null ? "" : this.country
            );
            formData.append(
              "pincode",
              this.pincode == null ? "" : this.pincode
            );
            formData.append(
              "tax_code",
              this.tax_code == null ? "" : this.tax_code
            );
            formData.append(
              "discount_code",
              this.discount_code == null ? "" : this.discount_code
            );
            formData.append(
              "invoice_type",
              this.print_type == null ? "" : this.print_type
            );
            formData.append(
              "currency_code",
              this.currency_code == null ? "" : this.currency_code
            );
            formData.append(
              "restaurant_mode",
              this.restaurant_mode == null ? "" : this.restaurant_mode
            );
            formData.append(
              "restaurant_billing_type",
              this.restaurant_billing_type == null
                ? ""
                : this.restaurant_billing_type
            );
            formData.append(
              "restaurant_waiter_role",
              this.restaurant_waiter_role == null
                ? ""
                : this.restaurant_waiter_role
            );
            formData.append(
              "enable_customer_popup",
              this.enable_customer_popup == null
                ? ""
                : this.enable_customer_popup
            );
            formData.append("status", this.status == null ? "" : this.status);
            formData.append(
              "bank_name",
              this.bank_name == null ? "" : this.bank_name
            );
            formData.append(
              "iban_number",
              this.iban_number == null ? "" : this.iban_number
            );
            formData.append(
              "account_holder_name",
              this.account_holder_name == null ? "" : this.account_holder_name
            );
            formData.append(
              "pos_invoice_policy_information",
              this.pos_invoice_policy_information == null
                ? ""
                : this.pos_invoice_policy_information
            );
            formData.append(
              "invoice_policy_information",
              this.invoice_policy_information == null
                ? ""
                : this.invoice_policy_information
            );
            formData.append(
              "purchase_policy_information",
              this.purchase_policy_information == null
                ? ""
                : this.purchase_policy_information
            );
            formData.append(
              "quotation_policy_information",
              this.quotation_policy_information == null
                ? ""
                : this.quotation_policy_information
            );
            formData.append(
              "store_logo",
              this.$refs.store_logo.files.length > 0
                ? this.$refs.store_logo.files[0]
                : null
            );
            formData.append(
              "zid_store_api_token",
              this.zid_store_api_token == null ? "" : this.zid_store_api_token
            );

            formData.append(
              "zid_store_id",
              this.zid_store_id == null ? "" : this.zid_store_id
            );

            formData.append(
              "building_number",
              this.building_number == null ? "" : this.building_number
            );
            formData.append(
              "street_name",
              this.street_name == null ? "" : this.street_name
            );
            formData.append(
              "district",
              this.district == null ? "" : this.district
            );
            formData.append("city", this.city == null ? "" : this.city);
            formData.append(
              "other_seller_id",
              this.other_seller_id == null ? "" : this.other_seller_id
            );

            axios
              .post(this.api_link, formData)
              .then((response) => {
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
  },
};
</script>
