<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="customer_slack == ''">{{
              $t("Add Customer")
            }}</span>
            <span class="text-title" v-else>{{ $t("Edit Customer") }}</span>
          </div>
          <!-- <div class="">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div> -->
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="mb-2">
          <span class="text-subhead">{{ $t("Basic Information") }}</span>
        </div>
        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="name">{{ $t("Fullname") }}</label>
            <input
              type="text"
              name="name"
              v-model="name"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="enter_fullname"
            />
            <span v-bind:class="{ error: errors.has('name') }">{{
              errors.first("name")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="email">{{ $t("Email") }}</label>
            <input
              type="text"
              name="email"
              v-model="email"
              v-validate="{
                email: true,
                max: 150,
              }"
              class="form-control form-control-custom"
              :placeholder="enter_email"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('email') }">{{
              errors.first("email")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="phone">{{ $t("Contact No.") }}</label>
            <input
              type="text"
              name="phone"
              v-model="phone"
              v-validate="{  min: 10, max: 15 }"
              class="form-control form-control-custom"
              :placeholder="enter_contactno"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('phone') }">{{
              errors.first("phone")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
            v-if="bill_to != 'CUSTOMER'"
          >
            <label for="organization_name">{{ $t("Organization Name") }}</label>
            <input
              type="text"
              name="organization_name"
              v-model="organization_name"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="enter_organization"
            />
            <span v-bind:class="{ error: errors.has('organization_name') }">{{
              errors.first("organization_name")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
            v-if="bill_to != 'CUSTOMER'"
          >
            <label for="tax_number">{{ $t("Tax Number") }}</label>
            <input
              type="text"
              name="tax_number"
              v-model="tax_number"
              class="form-control form-control-custom"
              :placeholder="enter_tax_number"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('tax_number') }">{{
              errors.first("tax_number")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
            v-if="bill_to != 'CUSTOMER'"
          >
            <label for="website">{{ $t("Website") }}</label>
            <input
              type="text"
              name="website"
              v-model="website"
              class="form-control form-control-custom"
              :placeholder="enter_website"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('website') }">{{
              errors.first("website")
            }}</span>
          </div>
        </div>
        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="building_number">{{ $t("Building Number") }}</label>
            <input
              type="text"
              name="building_number"
              v-model="building_number"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="enter_building_number"
            />
            <span v-bind:class="{ error: errors.has('building_number') }">{{
              errors.first("building_number")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="street_name">{{ $t("Street Name") }}</label>
            <input
              type="text"
              name="street_name"
              v-model="street_name"
              class="form-control form-control-custom"
              :placeholder="enter_street_name"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('street_name') }">{{
              errors.first("street_name")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="district">{{ $t("District") }}</label>
            <input
              type="text"
              name="district"
              v-model="district"
              class="form-control form-control-custom"
              :placeholder="enter_district"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('district') }">{{
              errors.first("district")
            }}</span>
          </div>
        </div>
        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="city">{{ $t("City") }}</label>
            <input
              type="text"
              name="city"
              v-model="city"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="enter_city"
            />
            <span v-bind:class="{ error: errors.has('city') }">{{
              errors.first("city")
            }}</span>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="country">{{ $t("Country") }}</label>
            <select
              name="country"
              v-model="country"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Country..") }}</option>
              <option
                v-for="(country_item, index) in country_list"
                v-bind:value="country_item.country_id"
                v-bind:key="index"
              >
                {{ country_item.code }} - {{ country_item.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('country') }">{{
              errors.first("country")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="pincode">{{ $t("Pincode") }}</label>
            <input
              type="text"
              name="pincode"
              v-model="pincode"
              v-validate="'max:15'"
              class="form-control form-control-custom"
              :placeholder="enter_pincode"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('pincode') }">{{
              errors.first("pincode")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
            v-if="bill_to != 'CUSTOMER'"
          >
            <label for="other_seller_id">{{ $t("Other Seller Id") }}</label>
            <input
              type="text"
              name="other_seller_id"
              v-model="other_seller_id"
              class="form-control form-control-custom"
              :placeholder="enter_other_seller_id"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('other_seller_id') }">{{
              errors.first("other_seller_id")
            }}</span>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="address"
              >{{ $t("Address") }} ( {{ $t("optional") }} )</label
            >
            <textarea
              name="address"
              v-model="address"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              :placeholder="enter_address"
            ></textarea>
            <span v-bind:class="{ error: errors.has('address') }">{{
              errors.first("address")
            }}</span>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group',
            ]"
          >
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="status"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('status') }">{{
              errors.first("status")
            }}</span>
          </div>
        </div>
        <div class="mb-2">
          <span class="text-subhead">{{ $t("Additional Information") }}</span>
        </div>
        <div class="form-row">
          <div class="form-group col-md-2 mb-1">
            <label for="name">{{ $t("Title") }}</label>
          </div>
          <div class="form-group col-md-6 mb-1">
            <label for="quantity">{{ $t("Description") }}</label>
          </div>
        </div>

        <div class="form-row mb-2" v-for="(add_info, index) in add_infos" :title="index">
          <div class="form-group col-md-2">
            <input type="text" v-bind:name="'add_info.title_' + index" v-model="add_info.title" v-validate="'max:250'" :data-vv-as="add_info_title" class="form-control form-control-custom" autocomplete="off" />
            <span v-bind:class="{ error: errors.has('add_info.title_' + index) }">{{ errors.first("add_info.title_" + index) }}</span>
          </div>
          <div class="form-group col-md-6">
            <input type="text" v-bind:name="'add_info.desc_' + index" v-model="add_info.desc" v-validate="'max:250'" :data-vv-as="add_info_desc" class="form-control form-control-custom" autocomplete="off" />
            <span v-bind:class="{ error: errors.has('add_info.desc_' + index) }">{{ errors.first("add_info.desc_" + index) }}</span>
          </div>
          <div class="form-group col-md-1" v-if="add_infos.length > 1">
            <button type="button" class="btn btn-outline-danger" @click="remove_add_info(index)">
              <i class="fas fa-times"></i>
            </button>
          </div>

        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="add_new_add_info">
          {{ $t("Add More") }}
        </button>

        <div class="flex-wrap mb-4">
          <div class=" pull-right text-right">
            <button
              type="submit"
              class="btn btn-primary "
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
        {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">
          {{ $t("You are making the customer inactive.") }}
        </p>
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
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i
          >{{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>
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
      modal: false,
      show_modal: false,
      api_link:
        this.customer_data == null
          ? "/api/add_customer"
          : "/api/update_customer/" + this.customer_data.slack,

      customer_slack:
        this.customer_data == null ? "" : this.customer_data.slack,
      email: this.customer_data == null ? "" : this.customer_data.email,
      name: this.customer_data == null ? "" : this.customer_data.name,
      phone: this.customer_data == null ? "" : this.customer_data.phone,
      address: this.customer_data == null ? "" : this.customer_data.address,
      tax_number:
        this.customer_data == null ? "" : this.customer_data.tax_number,
      website: this.customer_data == null ? "" : this.customer_data.website,
      organization_name:
        this.customer_data == null ? "" : this.customer_data.organization_name,
      status:
        this.customer_data == null
          ? "1"
          : this.customer_data.status == null
          ? ""
          : this.customer_data.status.value,
      pincode: this.customer_data == null ? "" : this.customer_data.pincode,
      building_number:
        this.customer_data == null ? "" : this.customer_data.building_number,
      street_name:
        this.customer_data == null ? "" : this.customer_data.street_name,
      district: this.customer_data == null ? "" : this.customer_data.district,
      city: this.customer_data == null ? "" : this.customer_data.city,
      country:
        this.customer_data == null
          ? ""
          : this.customer_data.country_id == null
          ? ""
          : this.customer_data.country_id,
      other_seller_id:
        this.customer_data == null ? "" : this.customer_data.other_seller_id,
      reload_on_submit: true,
      add_info_title: this.$t("Title"),
      add_info_desc: this.$t("Description"),
      enter_fullname: this.$t("Please enter fullname"),
      enter_address: this.$t("Enter Address"),
      enter_email: this.$t("Please enter email"),
      enter_contactno: this.$t("Please enter Contact Number"),
      enter_tax_number: this.$t("Please enter tax number"),
      enter_website: this.$t("Please enter website "),
      enter_organization: this.$t("Please enter organization name"),
      enter_building_number: this.$t("Please enter building number"),
      enter_street_name: this.$t("Please enter street name"),
      enter_district: this.$t("Please enter district"),
      enter_city: this.$t("Please enter city"),
      enter_other_seller_id: this.$t("Please enter other seller id"),
      enter_pincode: this.$t("Enter pincode"),
      success: this.$t("SUCCESS"),
      add_infos: [],
      add_infos_template: {
        slack: "",
        title: "",
        desc: "",
      },
      customer_add_info_list: this.customer_data != null ? this.customer_data.add_infos : [],
    };
  },
  props: {
    statuses: Array,
    bill_to: String,
    country_list: Array,
    customer_data: [Array, Object],
    reload_on_submit: {
      type: String,
      default: true,
    },
  },
  mounted() {
    console.log("Add customer page loaded");
  },
  computed: {
    email_required() {
      if (this.phone === "") return true;
      return false;
    },
    phone_required() {
      if (this.email === "") return true;
      return false;
    },
  },
  created() {
    this.update_add_info_list(this.customer_add_info_list);
  },
  methods: {
    add_new_add_info() {
      this.add_infos.push({
        slack: "",
        title: "",
        desc: "",
      });
    },

    remove_add_info(index) {
      this.add_infos.splice(index, 1);
    },
    update_add_info_list(customer_add_infos) {
      if ( customer_add_infos != null && customer_add_infos.length > 0 ) {
        this.add_infos = [];
        for (let i = 0; i < customer_add_infos.length; i++) {
          var individual_add_info = {
            slack: customer_add_infos[i].slack,
            title: customer_add_infos[i].title,
            desc: customer_add_infos[i].description,
          };
          this.add_infos.push(individual_add_info);

        }
      } else {
        this.add_infos = [];
        this.add_infos.push(this.add_infos_template);
      }
      this.calculate_total_bill_disc();
      this.calculate_price();
    },
    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          if (this.reload_on_submit) {
            this.show_modal = true;

            this.$on("submit", function() {
              // Submit form data
              this.form_data();
            });

            this.$on("close", function() {
              this.show_modal = false;
            });
          } else {
            // request coming from other component
            this.form_data();
          }
        }
      });
    },

    form_data() {
      this.processing = true;
      var formData = new FormData();

      formData.append("access_token", window.settings.access_token);
      formData.append("name", this.name == null ? "" : this.name);
      formData.append("email", this.email == null ? "" : this.email);
      formData.append("phone", this.phone == null ? "" : this.phone);
      formData.append("address", this.address == null ? "" : this.address);
      formData.append(
        "tax_number",
        this.tax_number == null ? "" : this.tax_number
      );
      formData.append("website", this.website == null ? "" : this.website);
      formData.append(
        "organization_name",
        this.organization_name == null ? "" : this.organization_name
      );
      formData.append("status", this.status == null ? "" : this.status);
      formData.append(
        "other_seller_id",
        this.other_seller_id == null ? "" : this.other_seller_id
      );
      formData.append(
        "building_number",
        this.building_number == null ? "" : this.building_number
      );
      formData.append(
        "street_name",
        this.street_name == null ? "" : this.street_name
      );
      formData.append("district", this.district == null ? "" : this.district);
      formData.append("country", this.country == null ? "" : this.country);
      formData.append("city", this.city == null ? "" : this.city);
      formData.append("pincode", this.pincode == null ? "" : this.pincode);
      formData.append("add_infos", JSON.stringify(this.add_infos));

      axios
        .post(this.api_link, formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, this.success);
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            this.processing = false;
            this.show_modal = false;
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
  },
};
</script>
