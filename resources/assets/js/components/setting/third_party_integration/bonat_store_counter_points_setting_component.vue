<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <span class="text-title">
            {{ $t("Bonat Store Points Settings") }}</span
          >
        </div>
      </div>
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="form-row mb-2">
          <div class="form-group col-md-2">
            <label for="merchant_id">{{ $t("Bonat Merchant Id") }}</label>
            <p class="text-truncate">{{ merchant_id }}</p>
          </div>

                   <div class="form-group col-md-2">
            <label for="status">{{ $t("Store Slack") }}</label>
            <p>
              <span v-bind:class="status_color">{{ store_id }}</span>
            </p>
          </div>
                   <div class="form-group col-md-2">
            <label for="status">{{ $t("Counter Slack") }}</label>
            <p>
              <span v-bind:class="status_color">{{ counter_id }}</span>
            </p>
          </div>

          <div class="form-group col-md-2">
            <label for="status">{{ $t("Verify") }}</label>
            <p>
              <span v-bind:class="verify_color">{{ verify_label }}</span>
            </p>
          </div>
          <div class="form-group col-md-2">
            <label for="status">{{ $t("Status") }}</label>
            <p>
              <span v-bind:class="status_color">{{ status_label }}</span>
            </p>
          </div>
        </div>

        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button
              class="btn btn-primary"
              v-bind:disabled="processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Verify") }}
            </button>

            <a v-bind:href="edit_link" class="btn btn-primary">
              {{ $t("Edit") }}</a
            >
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
"use strict";

export default {
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,

      verify_merchant: "/api/verify_store_bonat_merchant_setting",
      edit_link:"/edit_bonat_store_counter_points_setting/" + this.view_counter_id,
      merchant_id:
        this.bonat_setting.length == 0
          ? "-"
          : this.bonat_setting.merchant_id,
          store_id:
        this.bonat_setting.length == 0
          ? "-"
          : this.bonat_setting.store_id,
          counter_id:
        this.bonat_setting.length == 0
          ? "-"
          : this.bonat_setting.counter_id,

          
      status_label:
        this.bonat_setting.length == 0 ? "-" : this.bonat_setting.status.label,
      status_color:
        this.bonat_setting.length == 0 ? "" : this.bonat_setting.status.color,
          verify_label:
        this.bonat_setting.length == 0 ? "-" : this.bonat_setting.verify.label,
      verify_color:
        this.bonat_setting.length == 0 ? "" : this.bonat_setting.verify.color,
        store_slack:
        this.bonat_setting.length == 0 ? "" : this.bonat_setting.store_slack,
        counter_slack:
        this.bonat_setting.length == 0 ? "" : this.bonat_setting.counter_slack,
       
    };
  },
  props: {
    bonat_setting: [Array, Object],
    view_counter_id : String,
  },
  mounted() {
    console.log("Bonat setting page loaded");
    console.log(this.view_counter_id,'view_counter_id')
  },
  filters: {
    hide_sensitive_info: function(value, limit) {
      if (!value) return "";
      if (value.length > limit) {
        value = value.substring(0, limit - 3) + "***";
      }
      return value;
    },
  },
  methods: {
    submit_form() {
      this.processing = true;
      var formData = new FormData();

      formData.append("access_token", window.settings.access_token);
       formData.append("bonat_merchant_id",this.bonat_setting.merchant_id == null ? "": this.bonat_setting.merchant_id);
       formData.append("store_id",this.bonat_setting.store_id == null ? "": this.bonat_setting.store_id);
       formData.append("counter_id",this.bonat_setting.counter_id == null ? "": this.bonat_setting.counter_id);

      axios
        .post(this.verify_merchant, formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, this.success);

            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            this.processing = false;
            console.log(response.data);
         //   
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.show_error_response_message(this.server_errors, this.error);
            this.error_class = "error";
            setTimeout(function() {
              location.reload();
            }, 1000);
          }
        })
        .catch((error) => {
          console.log(error);
        });
      this.$off("submit");
    },
  },
};
</script>
