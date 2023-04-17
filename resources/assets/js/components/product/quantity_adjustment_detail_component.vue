<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">
                <span class="text-muted"
                  >{{ $t("Quantity Adjustment") }}
                  <span v-if="quantity_adjustment.status == 'closed'"
                    ><span class="badge badge-secondary">{{
                      $t("Closed")
                    }}</span></span
                  ></span
                >
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-2">
        <span class="text-subhead">{{ $t("Basic Information") }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="product_code">{{ $t("Reference") }}</label>
          <p>{{ quantity_adjustment.reference }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="name">{{ $t("Branch") }}</label>
          <p>{{ quantity_adjustment.store.name }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="name">{{ $t("Reason") }}</label>
          <p>{{ quantity_adjustment.reason }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="name">{{ $t("Created By") }}</label>
          <p>{{ quantity_adjustment.user.fullname }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="name">{{ $t("Created At") }}</label>
          <p>{{ quantity_adjustment.created_at }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="name">{{ $t("Submitted At") }}</label>
          <p>{{ quantity_adjustment.submitted_at }}</p>
        </div>
      </div>

      <hr />
      <div class="mb-2">
        <span class="text-subhead">{{ $t("Products") }}</span>
      </div>
      <div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>{{ $t("Name") }}</th>
              <th>{{ $t("Product code") }}</th>
              <th>{{ $t("Deducted Quantity") }}</th>
              <th>{{ $t("Remaining Quantity") }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(product, index) in this.quantity_adjustment_products"
              v-bind:key="index"
            >
              <td>{{ product.product.name }}</td>
              <td>{{ product.product.product_code }}</td>
              <td>{{ product.quantity }}</td>
              <td>{{ product.product.quantity }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
"use strict";

export default {
  data() {
    return {};
  },
  props: {
    quantity_adjustment: Array,
    quantity_adjustment_products: Array,
  },
  mounted() {},
  methods: {},
};
</script>
