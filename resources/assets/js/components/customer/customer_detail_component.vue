<template>
  <div class="row">
    <div class="col-md-12">

      

      <div class="mb-2">
        <span class="text-subhead">{{ $t("Basic Information") }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3">
          <label for="fullname">{{ $t("Fullname") }}</label>
          <p>{{ (customer.name == '' || customer.name == null) ? '-' : customer.name }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="email">{{ $t("Email") }}</label>
          <p>{{ (customer.email == '' || customer.email == null) ? '-' : customer.email }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="phone">{{ $t("Phone") }}</label>
          <p>{{ (customer.phone == '' || customer.phone == null) ? '-' : customer.phone }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="role">{{ $t("Address") }}</label>
          <p>{{ (customer.address == '' || customer.address == null) ? '-' : customer.address }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="role">{{ $t("Tax number") }}</label>
          <p>{{ (customer.tax_number == '' || customer.tax_number == null) ? '-' : customer.tax_number }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="role">{{ $t("Website") }}</label>
          <p>{{ (customer.website == '' || customer.website == null) ? '-' : customer.website }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="role">{{ $t("Organization Name") }}</label>
          <p>{{
              (customer.organization_name == '' || customer.organization_name == null) ? '-' : customer.organization_name
            }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="building_number">{{ $t("Building Number") }}</label>
          <p>{{ customer.building_number }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="street_name">{{ $t("Street Name") }}</label>
          <p>{{ customer.street_name }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="district">{{ $t("District") }}</label>
          <p>{{ customer.district }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="city">{{ $t("City") }}</label>
          <p>{{ customer.city }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="pincode">{{ $t("Pincode") }}</label>
          <p>{{ customer.pincode }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="other_seller_id">{{ $t("Other Seller Id") }}</label>
          <p>{{ customer.other_seller_id }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_by">{{ $t("Created By") }}</label>
          <p>{{
              (customer.created_by == null) ? '-' : customer.created_by['fullname'] + ' (' + customer.created_by['customer_code'] + ')'
            }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_by">{{ $t("Updated By") }}</label>
          <p>{{
              (customer.updated_by == null) ? '-' : customer.updated_by['fullname'] + ' (' + customer.updated_by['customer_code'] + ')'
            }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="created_on">{{ $t("Created On") }}</label>
          <p>{{ customer.created_at_label }}</p>
        </div>
        <div class="form-group col-md-3">
          <label for="updated_on">{{ $t("Updated On") }}</label>
          <p>{{ customer.updated_at_label }}</p>
        </div>
      </div>
      <div class="mb-2" v-if="customer_add_info_list.length>0">
        <span class="text-subhead">{{ $t("Additional Information") }}</span>
      </div>
      <div class="form-row mb-2">
        <div class="form-group col-md-3" v-for="add_info in customer_add_info_list">
          <label>{{ add_info.title }}</label>
          <p>{{ add_info.description }}</p>
        </div>
      </div>


    </div>
  </div>
</template>

<script>
'use strict';

export default {
  data() {
    return {
      customer: this.customer_data,
      customer_add_info_list: this.customer_data != null ? this.customer_data.add_infos : [],
    }
  },
  props: {
    customer_data: [Array, Object],
  },
  mounted() {
    console.log('Customer detail page loaded');
    console.log(this.customer_add_info_list);
  },
  methods: {}
}
</script>