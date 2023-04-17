<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">
                <span class="text-muted">{{ $t("Discount Code") }}</span>
                {{ discount_code.label }} ({{ discount_code.discount_code }})
              </span>
            </div>
          </div>
        </div>
        <div class="">
          <span v-bind:class="discount_code.status.color">{{
            $t(discount_code.status.label)
          }}</span>
        </div>
      </div>

      <div class="mb-2">
        <span class="text-subhead">{{ $t("Basic Information") }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="discount_code">{{ $t("Discount Code") }}</label>
          <p>{{ discount_code.discount_code }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="label">{{ $t("Name") }}</label>
          <p>{{ discount_code.label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="label">{{ $t("Discount Type") }}</label>
          <p>{{ discount_code.discount_type }}</p>
        </div>
        <div
          class="form-group col-md-3"
          v-if="discount_code.discount_type == 'percentage'"
        >
          <label for="discount_percentage">{{
            $t("Discount Percentage")
          }}</label>
          <p>{{ discount_code.discount_percentage }}%</p>
        </div>
        <div class="form-group col-md-3" v-else>
          <label for="discount_percentage">{{ $t("Discount Amount") }}</label>
          <p>{{ discount_code.discount_value }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="label">{{ $t("Discount Applied On") }}</label>
          <p>{{ discount_code.discount_applied_on.split("_").join(" ") }}</p>
        </div>
        <div
          class="form-group col-md-3"
          v-if="discount_code.limit_on_discount == '-1'"
        >
          <label for="discount_percentage">{{ $t("Limit on Discount") }}</label>
          <p>{{ $t("Unlimited") }}</p>
        </div>
        <div class="form-group col-md-3" v-else>
          <label for="discount_percentage">{{ $t("Limit on Discount") }}</label>
          <p>{{ discount_code.limit_on_discount }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_by">{{ $t("Created By") }}</label>
          <p>
            {{
              discount_code.created_by == null
                ? "-"
                : discount_code.created_by["fullname"] +
                  " (" +
                  discount_code.created_by["user_code"] +
                  ")"
            }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_by">{{ $t("Updated By") }}</label>
          <p>
            {{
              discount_code.updated_by == null
                ? "-"
                : discount_code.updated_by["fullname"] +
                  " (" +
                  discount_code.updated_by["user_code"] +
                  ")"
            }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_on">{{ $t("Created On") }}</label>
          <p>{{ discount_code.created_at_label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_on">{{ $t("Updated On") }}</label>
          <p>{{ discount_code.updated_at_label }}</p>
        </div>
      </div>

      <div class="form-row mb-2">
        <div class="form-group col-md-6">
          <label for="description">{{ $t("Description") }}</label>
          <p>{{ discount_code.description }}</p>
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
      discount_code: this.discount_code_data,
    };
  },
  props: {
    discount_code_data: [Array, Object],
  },
  mounted() {
    console.log("Discount code detail page loaded");
  },
  methods: {},
};
</script>
