<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">
                <span class="text-muted">{{ $t("Category") }}</span>
                {{ category.label }} ({{ category.category_code }})
              </span>
            </div>
          </div>
        </div>
        <div class="">
          <span v-bind:class="category.status.color">{{
            category.status.label
          }}</span>
        </div>
      </div>

      <div class="mb-2">
        <span class="text-subhead">{{ $t("Basic Information") }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="category_code">{{ $t("Category Code") }}</label>
          <p>{{ category.category_code }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="label">{{ $t("Name") }}</label>
          <p>{{ category.label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_by">{{ $t("Created By") }}</label>
          <p>
            {{
              category.created_by == null
                ? "-"
                : category.created_by["fullname"] +
                  " (" +
                  category.created_by["user_code"] +
                  ")"
            }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_by">{{ $t("Updated By") }}</label>
          <p>
            {{
              category.updated_by == null
                ? "-"
                : category.updated_by["fullname"] +
                  " (" +
                  category.updated_by["user_code"] +
                  ")"
            }}
          </p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_on">{{ $t("Created On") }}</label>
          <p>{{ category.created_at_label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_on">{{ $t("Updated On") }}</label>
          <p>{{ category.updated_at_label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="category_applied_on">{{ $t("Category Applied On") }}</label>
          <p>{{ category.category_applied_on == "specific_stores" ? 'Specific Stores' : 'All Stores' }}</p>
        </div>
        <div class="form-group col-md-3" v-if="category.category_applied_on == 'specific_stores'">
          <label for="category_applicable_stores">{{ $t("Category Applicable Stores") }}</label>
          <ul style="margin-left:-20px">
            <li v-for="(store, index) in store_data" :key="index">{{ store }}</li>
          </ul>
        </div>
        
      </div>

      <div class="form-row mb-2">
        <div class="form-group col-md-6">
          <label for="description">{{ $t("Description") }}</label>
          <p>{{ category.description ? category.description : "-" }}</p>
        </div>
      </div>

    </div>

    <div class="col-md-12">
      <table
        class="table table-striped table-bordered table-condensed table-hover"
      >
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category Code</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created At</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(subcategory, index) in subcategory_data" :key="index">
            <td v-html="subcategory.category_image"></td>
            <td>{{ subcategory.label }}</td>
            <td>{{ subcategory.category_code }}</td>
            <td>{{ subcategory.description }}</td>
            <td>{{ subcategory.status == 1 ? "Active" : "Inactive" }}</td>
            <td>{{ subcategory.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
"use strict";

export default {
  data() {
    return {
      category: this.category_data,
    };
  },
  props: {
    category_data: [Array, Object],
    subcategory_data: [Array, Object],
    store_data: [Array, Object],
  },
  mounted() {
    // console.log(this.category_data);
    // console.log(this.subcategory_data);
    console.log("Category detail page loaded");
    console.log(this.store_data);
  },
  methods: {},
};
</script>
