<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <span class="text-title"> {{ $t("Abkhas User Points Settings") }}</span>
        </div>
      </div>

      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="token_id">{{ $t("Token Id") }}</label>
          <p class="text-truncate">{{ token_id }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="secret_key">{{ $t("Secret Key") }}</label>
          <p class="text-truncate">{{ secret_key }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="status">{{ $t("Status") }}</label>
          <p>
            <span v-bind:class="status_color">{{ status_label }}</span>
          </p>
        </div>
      </div>

      <div class="flex-wrap mb-4">
        <div class="text-right">
          <a v-bind:href="edit_link" class="btn btn-primary">
            {{ $t("Edit") }}</a
          >
        </div>
      </div>
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
      edit_link:
        this.abkhas_setting.length == 0
          ? "/edit_user_points_setting"
          : "/edit_user_points_setting/" + this.abkhas_setting.slack,
      token_id:
        this.abkhas_setting.length == 0 ? "-" : this.abkhas_setting.token_id,
      secret_key:
        this.abkhas_setting.length == 0 ? "-" : this.abkhas_setting.secret_key,
      status_label:
        this.abkhas_setting.length == 0
          ? "-"
          : this.abkhas_setting.status.label,
      status_color:
        this.abkhas_setting.length == 0 ? "" : this.abkhas_setting.status.color,
    };
  },
  props: {
    abkhas_setting: [Array, Object],
  },
  mounted() {
    console.log("Abkhas setting page loaded");
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
  methods: {},
};
</script>
