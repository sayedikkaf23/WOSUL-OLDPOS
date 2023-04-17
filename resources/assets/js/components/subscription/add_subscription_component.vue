<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="slack == ''">{{
              $t("Add Subscription")
            }}</span>
            <span class="text-title" v-else>{{ $t("Edit Subscription") }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div class="col-md-3 form-group">
            <label for="title">{{ $t("Title") }}</label>
            <input
              type="text"
              name="title"
              v-model="title"
              v-validate="'required|max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter title"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title') }">{{
              errors.first("title")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="title_ar">{{ $t("Title (Arabic)") }}</label>
            <input
              type="text"
              name="title_ar"
              v-model="title_ar"
              v-validate="'max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter Title in Arabic"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title_ar') }">{{
              errors.first("title_ar")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="short_description">{{ $t("Short Description") }}</label>
            <textarea
              name="short_description"
              v-model="short_description"
              v-validate="'required|max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Short Description"
            ></textarea>
            <span v-bind:class="{ error: errors.has('short_description') }">{{
              errors.first("short_description")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="short_description_ar">{{
              $t("Short Description (Arabic) ")
            }}</label>
            <textarea
              name="short_description_ar"
              v-model="short_description_ar"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Short Description in Arabic"
            ></textarea>
            <span
              v-bind:class="{ error: errors.has('short_description_ar') }"
              >{{ errors.first("short_description_ar") }}</span
            >
          </div>

          <div class="col-md-3 form-group">
            <label for="plan_tenure">{{ $t("Plan Tenure") }}</label>
            <select
              name="plan_tenure"
              v-model="plan_tenure"
              class="form-control form-control-custom custom-select"
              v-validate="'required'"
            >
              <option value="">Choose Plan Tenure..</option>
              <option value="1">Monthly</option>
              <option value="2">Yearly</option>
            </select>
            <span v-bind:class="{ error: errors.has('plan_tenure') }">{{
              errors.first("plan_tenure")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="currency">{{ $t("Currency") }}</label>
            <select
              name="currency"
              v-model="currency"
              v-validate="'required'"
              class="form-control form-control-custom custom-select"
            >
              <!-- <option value="SAR" selected>SAR</option> -->
              <option value="">Choose Currency..</option>
              <option
                v-for="(currency_item, index) in currency_list"
                v-bind:value="currency_item.currency_code"
                v-bind:key="index"
                :data-id="currency_item.currency_code"
              >
                {{ currency_item.currency_code }} -
                {{ currency_item.currency_name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('currency') }">{{
              errors.first("currency")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="amount">{{ $t("Amount") }}</label>
            <input
              type="number"
              step="1"
              name="amount"
              v-model="amount"
              v-validate="'required'"
              class="form-control form-control-custom"
              placeholder="Please enter amount"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('amount') }">{{
              errors.first("amount")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="discount">{{ $t("Discount") }}</label>
            <input
              type="number"
              step="1"
              name="discount"
              v-model="discount"
              class="form-control form-control-custom"
              placeholder="Please enter discount"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('discount') }">{{
              errors.first("discount")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="discount_description">{{
              $t("Discount Description")
            }}</label>
            <textarea
              name="discount_description"
              v-model="discount_description"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Discount Description"
            ></textarea>
            <span
              v-bind:class="{ error: errors.has('discount_description') }"
              >{{ errors.first("discount_description") }}</span
            >
          </div>

          <div class="col-md-3 form-group">
            <label for="discount_description_ar">{{
              $t("Discount Description (Arabic)")
            }}</label>
            <textarea
              name="discount_description_ar"
              v-model="discount_description_ar"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Discount Description in Arabic"
            ></textarea>
            <span
              v-bind:class="{ error: errors.has('discount_description_ar') }"
              >{{ errors.first("discount_description_ar") }}</span
            >
          </div>

          <div class="col-md-3 form-group">
            <label for="url">{{ $t("Plan URL") }}</label>
            <input
              type="text"
              name="url"
              v-model="url"
              v-validate="'required|max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter URL"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('url') }">{{
              errors.first("url")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="url_ar">{{ $t("Plan URL (Arabic)") }}</label>
            <input
              type="text"
              name="url_ar"
              v-model="url_ar"
              v-validate="'max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter URL for Arabic"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('url_ar') }">{{
              errors.first("url_ar")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="color_code">{{ $t("Color Code") }}</label>
            <input
              type="color"
              v-model="color_code"
              class="form-control form-control-custom"
            />
            <span v-bind:class="{ error: errors.has('color_code') }">{{
              errors.first("color_code")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="is_featured">{{ $t("Is Featured") }}</label>
            <select
              name="is_featured"
              v-model="is_featured"
              class="form-control form-control-custom custom-select"
              v-validate="'required'"
            >
              <option value="" selected="">Choose Option..</option>
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
            <span v-bind:class="{ error: errors.has('is_featured') }">{{
              errors.first("is_featured")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="is_live">{{ $t("Is Live") }}</label>
            <select
              name="is_live"
              v-model="is_live"
              class="form-control form-control-custom custom-select"
              v-validate="'required'"
            >
              <option value="" selected="">Choose Option..</option>
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
            <span v-bind:class="{ error: errors.has('is_live') }">{{
              errors.first("is_live")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="status"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">Choose Status..</option>
              <option
                v-for="(status, index) in statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ status.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('status') }">{{
              errors.first("status")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2 mt-5">
          <div class="col-md-12">
            <h5 for="features">{{ $t("Subscription Features") }}</h5>
          </div>
        </div>

        <div
          class="form-row mb-2"
          v-if="subscription_features.length > 0"
          v-for="(subscription_feature, index) in subscription_features"
        >
          <div class="col-md-5 form-group">
            <input
              type="text"
              v-model="subscription_feature.title"
              v-validate="'required|max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter feature"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title') }">{{
              errors.first("title")
            }}</span>
          </div>
          <div class="col-md-5 form-group">
            <input
              type="text"
              v-model="subscription_feature.title_ar"
              v-validate="'max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter feature in Arabic"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title_ar') }">{{
              errors.first("title_ar")
            }}</span>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-1">
            <button
              class="btn btn-xs btn-danger"
              type="button"
              @click="removeFeature(index)"
            >
              X
            </button>
          </div>
        </div>

        <div class="form-row mb-3">
          <div class="col-md-12">
            <button
              class="btn btn-sm btn-info"
              type="button"
              @click="addFeature()"
            >
              + Add Feature
            </button>
          </div>
        </div>

        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Save") }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        Confirm
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">
          If subscription unit is inactive all the products using this
          subscription unit will get affected.
        </p>
        Are you sure you want to proceed?
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          Cancel
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          Continue
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script type="text/javascript">
"use strict";

export default {
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,

      // additional elements
      slack: this.subscription_data == null ? "" : this.subscription_data.slack,
      api_link:
        this.subscription_data == null
          ? "/api/add_subscription"
          : "/api/update_subscription/" + this.subscription_data.slack,

      // props data
      currency_list: this.currency_data == null ? "" : this.currency_data,
      subscription_features:
        this.subscription_features_data == null
          ? []
          : this.subscription_features_data == null
          ? []
          : this.subscription_features_data,

      // form elements
      title: this.subscription_data == null ? "" : this.subscription_data.title,
      title_ar:
        this.subscription_data == null ? "" : this.subscription_data.title_ar,
      short_description:
        this.subscription_data == null
          ? ""
          : this.subscription_data.short_description,
      short_description_ar:
        this.subscription_data == null
          ? ""
          : this.subscription_data.short_description_ar,
      plan_tenure:
        this.subscription_data == null
          ? ""
          : this.subscription_data.plan_tenure,
      currency:
        this.subscription_data == null ? "" : this.subscription_data.currency,
      amount:
        this.subscription_data == null ? "" : this.subscription_data.amount,
      discount:
        this.subscription_data == null ? "" : this.subscription_data.discount,
      discount_description:
        this.subscription_data == null
          ? ""
          : this.subscription_data.discount_description,
      discount_description_ar:
        this.subscription_data == null
          ? ""
          : this.subscription_data.discount_description_ar,
      url: this.subscription_data == null ? "" : this.subscription_data.url,
      url_ar:
        this.subscription_data == null ? "" : this.subscription_data.url_ar,
      color_code:
        this.subscription_data == null
          ? "#072F78"
          : this.subscription_data.color_code,
      is_featured:
        this.subscription_data == null
          ? ""
          : this.subscription_data.is_featured,
      is_live:
        this.subscription_data == null ? "" : this.subscription_data.is_live,
      status:
        this.subscription_data == null
          ? "1"
          : this.subscription_data.status == null
          ? ""
          : this.subscription_data.status,
    };
  },
  props: {
    statuses: Array,
    subscription_data: [Array, Object],
    subscription_features_data: [Array, Object],
    currency_data: [Array, Object],
  },
  mounted() {
    console.log("Add Subscription Page Loaded");
    // console.log(this.subscription_features);
    console.log(this.api_link);
    // console.log(this.conversions);
    // return false;
  },
  methods: {
    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            // other elements
            formData.append("access_token", window.settings.access_token);
            formData.append("slack", this.slack == null ? "" : this.slack);

            // form elements
            formData.append("title", this.title == null ? "" : this.title);
            formData.append(
              "title_ar",
              this.title_ar == null ? "" : this.title_ar
            );
            formData.append(
              "short_description",
              this.short_description == null ? "" : this.short_description
            );
            formData.append(
              "short_description_ar",
              this.short_description_ar == null ? "" : this.short_description_ar
            );
            formData.append(
              "plan_tenure",
              this.plan_tenure == null ? "" : this.plan_tenure
            );
            formData.append(
              "currency",
              this.currency == null ? "" : this.currency
            );
            formData.append("amount", this.amount == null ? "" : this.amount);
            formData.append(
              "discount",
              this.discount == null ? "" : this.discount
            );
            formData.append(
              "discount_description",
              this.discount_description == null ? "" : this.discount_description
            );
            formData.append(
              "discount_description_ar",
              this.discount_description_ar == null
                ? ""
                : this.discount_description_ar
            );
            formData.append("url", this.url == null ? "" : this.url);
            formData.append("url_ar", this.url_ar == null ? "" : this.url_ar);
            formData.append(
              "color_code",
              this.color_code == null ? "" : this.color_code
            );
            formData.append(
              "is_featured",
              this.is_featured == null ? "" : this.is_featured
            );
            formData.append(
              "is_live",
              this.is_live == null ? "" : this.is_live
            );
            formData.append("status", this.status == null ? "" : this.status);
            formData.append(
              "subscription_features",
              this.subscription_features == null
                ? ""
                : JSON.stringify(this.subscription_features)
            );

            axios
              .post(this.api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  // console.log(response.data);
                  this.show_response_message(response.data.msg, "SUCCESS");
                  // this.$emit('refreshSubscriptions', response.data.data);
                  this.show_modal = false;
                  this.processing = false;

                  // setTimeout(function(){
                  //     location.reload();
                  // }, 1000);
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
    addFeature() {
      var item = {
        title: "",
        title_ar: "",
      };
      this.subscription_features.push(item);
    },
    removeFeature(index) {
      this.subscription_features.splice(index, 1);
    },
  },
};
</script>
