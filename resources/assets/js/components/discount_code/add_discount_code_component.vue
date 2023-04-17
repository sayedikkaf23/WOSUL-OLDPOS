<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="discount_code_slack == ''">{{
              $t("Add Discount")
            }}</span>
            <span class="text-title" v-else>{{ $t("Edit Discount") }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
          >
            <label for="discount_name">{{ $t("Discount Name") }}</label>
            <input
              type="text"
              name="discount_name"
              v-model="discount_name"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              :placeholder="enter_discount_name"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('discount_name') }">{{
              errors.first("discount_name")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
          >
            <div
              class="d-flex flex-column justify-content-between align-items-center"
            >
              <div
                class="d-flex flex-row justify-content-between align-items-center mt-5"
              >
                <label for="discounttype">{{ $t("Discount Type") }}</label>
                <label
                  ><input
                    type="radio"
                    class="mr-1 ml-3"
                    name="discount_type_choose"
                    id="discount_type_code"
                    @click="showDiscountCodeSection()"
                    checked
                  />{{ $t("Code") }}</label
                >
                <label
                  ><input
                    type="radio"
                    class="ml-4 mr-1"
                    name="discount_type_choose"
                    id="discount_type_automatic"
                    @click="showDiscountAutomaticSection()"
                  />{{ $t("Automatic") }}</label
                >
              </div>

              <div id="discount_automatic_section" class="d-none ml-5">
                <div
                  class="d-flex flex-row justify-content-between align-items-center mt-5"
                >
                  <label
                    ><input
                      type="radio"
                      class="mr-1 ml-3"
                      name="discount_automatic"
                      id="discount_automatic_inventory"
                      @click="setAutomaticDiscountToInventory()"
                    />{{ $t("Inventory") }}</label
                  >
                  <label
                    ><input
                      type="radio"
                      class="ml-4 mr-1"
                      name="discount_automatic"
                      id="discount_automatic_cashier"
                      @click="setAutomaticDiscountToCashier()"
                    />{{ $t("Cashier") }}</label
                  >
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-row mb-2 discount-code-section">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
            id="discount_code_section"
          >
            <label for="discount_code">{{ $t("Discount Code") }}</label>
            <input
              type="text"
              name="discount_code"
              v-model="discount_code"
              id="discount_code"
              class="form-control form-control-custom"
              :placeholder="enter_discount_code"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('discount_code') }">{{
              errors.first("discount_code")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
          >
            <label for="discount_type">{{ $t("Discount Type") }}</label>
            <select
              name="discount_type"
              id="discount_type"
              v-model="discount_type"
              v-on:change="setDiscountType()"
              class="form-control form-control-custom custom-select"
            >
              <option
                value="amount"
                v-if="discount_type == 'amount'"
                selected
                >{{ $t("Amount") }}</option
              >
              <option value="amount" v-else>{{ $t("Amount") }}</option>
              <option
                value="percentage"
                v-if="discount_type == 'percentage'"
                selected
                >{{ $t("Percentage") }}(%)</option
              >
              <option value="percentage" v-else
                >{{ $t("Percentage") }}(%)</option
              >
            </select>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
            :style="discount_type == 'amount' ? '' : 'display:none;'"
            id="discount_value_section"
          >
            <label for="discount_value">{{ $t("Discount Amount") }}</label>
            <input
              type="number"
              name="discount_value"
              id="discount_value_txt"
              v-model="discount_value"
              class="form-control form-control-custom"
              :placeholder="enter_discount_value"
              autocomplete="off"
              step="1"
              min="0"
            />
            <span v-bind:class="{ error: errors.has('discount_value') }">{{
              errors.first("discount_value")
            }}</span>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
            id="discount_percentage_section"
            :style="discount_type == 'percentage' ? '' : 'display:none;'"
          >
            <label for="discount_percentage">{{
              $t("Discount Percentage")
            }}</label>
            <input
              type="number"
              name="discount_percentage"
              v-model="discount_percentage"
              class="form-control form-control-custom"
              :placeholder="enter_discount_percentage"
              autocomplete="off"
              step="0.01"
              min="0"
            />
            <span v-bind:class="{ error: errors.has('discount_percentage') }">{{
              errors.first("discount_percentage")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2 discount-code-section">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2',
            ]"
          >
            <div
              class="d-flex flex-row justify-content-between align-items-center "
            >
              <label for="discount_start_date">{{
                $t("Discount Start Date")
              }}</label>
              <label
                ><input
                  type="checkbox"
                  class="mr-1"
                  id="chk_is_always"
                  v-on:click="setToAlways()"
                  :checked="is_always > 0 ? 'checked' : ''"
                />{{ $t("Always") }}</label
              >
            </div>
            <input
              type="date"
              :value="discount_start_date"
              :min="new Date().toISOString().split('T')[0]"
              @input="discount_start_date = $event.target.value"
              class="form-control bg-white"
              id="discount_start_date"
              v-if="is_always_checked == 0"
              :formatter="format"
            />
            <span v-bind:class="{ error: errors.has('discount_start_date') }">{{
              errors.first("discount_start_date")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2',
            ]"
          >
            <label for="discount_start_time"></label>
            <input
              type="time"
              id="discount_start_time"
              :value="discount_start_time"
              v-if="is_always_checked == 0" 
              @input="discount_start_time = $event.target.value"
              class="form-control bg-white mt-2"
            />
          </div>
        </div>

        <div class="form-row mb-2 discount-code-section">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2',
            ]"
          >
            <label for="discount_end_date" id="discount_expiry_label" v-if="is_always_checked == 0">{{
              $t("Discount Expiry Date")
            }}</label>
            <input
              type="date"
              :value="discount_end_date"
              :min="new Date().toISOString().split('T')[0]"
              id="discount_end_date"
              v-if="is_always_checked == 0"
              @input="discount_end_date = $event.target.value"
              :formatter="format"
              class="form-control bg-white"
            />
            <span v-bind:class="{ error: errors.has('discount_end_date') }">{{
              errors.first("discount_end_date")
            }}</span>
          </div>
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2',
            ]"
          >
            <label for="discount_end_time"></label>
            <input
              type="time"
              id="discount_end_time"
              v-if="is_always_checked == 0"
              :value="discount_end_time"
              @input="discount_end_time = $event.target.value"
              class="form-control
            bg-white mt-2"
            />
          </div>
        </div>

        <div class="form-row mb-2 mt-2 discount-code-section">
          <div
          id="apply_discount_on_section"
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2',
            ]"
          >
            <label for="discount_apply_on">{{ $t("Apply Discount On") }}</label>
            <div class="d-flex flex-column">
              <div
                class="d-flex flex-row justify-content-start align-items-center"
              >
              <label class="ml-2"><input
                  type="radio"
                  class="check"
                  name="discount_apply_on"
                  v-on:click="openProductList('all_products')"
                  :checked="
                    discount_applied_on == 'all_products' ? 'checked' : ''
                  "
                />
                {{ $t("All Products") }}</label>
              </div>
              <div
                class="d-flex flex-row justify-content-start align-items-center"
              >
              <label class="ml-2"><input
                  type="radio"
                  class="check"
                  name="discount_apply_on"
                  v-on:click="openProductList('specific_products')"
                  :checked="
                    discount_applied_on == 'specific_products' ? 'checked' : ''
                  "
                />
                {{ $t("Specific Products") }}</label>
              </div>
              <div
                class="d-flex flex-row justify-content-start align-items-center"
              >
              <label class="ml-2"><input
                  type="radio"
                  class="check"
                  name="discount_apply_on"
                  v-on:click="openProductList('specific_product_categories')"
                  :checked="
                    discount_applied_on == 'specific_product_categories'
                      ? 'checked'
                      : ''
                  "
                />
                {{
                  $t("Specific Categories of Products")
                }}</label>
              </div>
              <div
                class="d-flex flex-row justify-content-start align-items-center"
              >
              <label class="ml-2"><input
                  type="radio"
                  class="check"
                  name="discount_apply_on"
                  v-on:click="openProductList('all_products_except_specific')"
                  :checked="
                    discount_applied_on == 'all_products_except_specific'
                      ? 'checked'
                      : ''
                  "
                />
                {{
                  $t("All Products except specific products")
                }}</label>
              </div>
            </div>
            <span v-bind:class="{ error: errors.has('discount_apply_on') }">{{
              errors.first("discount_apply_on")
            }}</span>
          </div>

          <div
            class="col-md-4
              'form-group mb-1 p-0 ml-2 mr-2"
            id="products_section"
            :style="
              discount_applied_on == 'specific_products' ||
              discount_applied_on == 'all_products_except_specific'
                ? ''
                : 'display:none;'
            "
          >
            <label for="products_section">{{ $t("Choose Product") }}</label>

            <input
              type="search"
              class="form-control mb-2"
              placeholder="Search"
              id="search_box"
              v-on:input="filterProducts()"
            />
            <div
              style="height:300px;overflow-y:scroll;"
              id="product_list"
              @click="addToProductsList"
            ></div>

            <div class="d-flex align-items-center justify-content-between">
              <button
                class="btn btn-primary"
                id="cancelproductsbtn"
                @click="cancelProductsList"
              >
                {{ $t("Deselect All") }}
              </button>
            </div>
          </div>

          <div
            class="col-md-4
              'form-group mb-1 p-0 ml-2 mr-2"
            id="category_section"
            :style="
              discount_applied_on == 'specific_product_categories'
                ? ''
                : 'display:none;'
            "
          >
            <label for="category_section">{{
              $t("Choose Product Category")
            }}</label>

            <input
              type="search"
              class="form-control mb-2"
              placeholder="Search"
              id="search_box_for_category"
              v-on:input="filterCategories()"
            />
            <div
              style="height:300px;overflow-y:scroll;"
              id="category_list"
              @click="addToCategoriesList"
            ></div>

            <div class="d-flex align-items-center justify-content-between">
              <button
                class="btn btn-primary"
                id="cancelcategoriesbtn"
                @click="cancelCategoriesList"
              >
                {{ $t("Deselect All") }}
              </button>
            </div>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1 p-0 ml-2 mr-2',
            ]"
          >
            <div
              class="d-flex flex-row justify-content-between align-items-center "
            >
              <label for="limit_of_discount">{{
                $t("Limit Of Discount")
              }}</label>
              <label
                ><input
                  type="checkbox"
                  class="mr-1"
                  id="chk_unlimited"
                  v-on:click="setToUnlimited()"
                  :checked="limit_of_discount == '-1' ? 'checked' : ''"
                />{{ $t("Unlimited") }}</label
              >
            </div>
            <input
              type="number"
              name="limit_of_discount"
              id="limit_of_discount_txt"
              :value="limit_of_discount == '-1' ? '' : limit_of_discount"
              @input="limit_of_discount = $event.target.value"
              class="form-control form-control-custom"
              v-if="limit_of_discount == '-1'"
              disabled
            />
            <input
              type="number"
              name="limit_of_discount"
              id="limit_of_discount_txt"
              :value="limit_of_discount == '-1' ? '' : limit_of_discount"
              @input="limit_of_discount = $event.target.value"
              class="form-control form-control-custom"
              v-else
            />
            <span v-bind:class="{ error: errors.has('limit_of_discount') }">{{
              errors.first("limit_of_discount")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2 discount-code-section">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
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

        <div class="form-row mb-2 discount-code-section">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
          >
            <label for="description">{{ $t("Description") }}</label>
            <textarea
              name="description"
              v-model="description"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              :placeholder="enter_description"
            ></textarea>
            <span v-bind:class="{ error: errors.has('description') }">{{
              errors.first("description")
            }}</span>
          </div>
        </div>

        <div class="flex-wrap mb-4 discount-code-section">
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
        {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
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
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script>
"use strict";
import moment from "moment";

export default {
  data() {
    return {
      discount_type:
        this.discount_code_data == null
          ? "percentage"
          : this.discount_code_data.discount_type,
      enter_discount_value: this.$t("Please enter discount amount"),
      server_errors: "",
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,
      api_link:
        this.discount_code_data == null
          ? "/api/add_discount_code"
          : "/api/update_discount_code/" + this.discount_code_data.slack,

      discount_code_slack:
        this.discount_code_data == null ? "" : this.discount_code_data.slack,

      is_always:
        this.discount_code_data == null ? 0 : this.discount_code_data.is_always,
      is_always_checked: this.discount_code_data == null ? 0 : this.discount_code_data.is_always,

      discount_name:
        this.discount_code_data == null ? "" : this.discount_code_data.label,
      discount_start_date:
        this.discount_code_data == null
          ? moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("YYYY-MM-DD")
          : moment(
              this.discount_code_data.discount_start_date
            ).format("YYYY-MM-DD"),
      discount_start_time:
        this.discount_code_data == null
          ? moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("HH:mm")
          : moment(
              this.discount_code_data.discount_start_date
            ).format("HH:mm"),
      discount_end_date:
        this.discount_code_data == null
          ? moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("YYYY-MM-DD")
          : moment(this.discount_code_data.discount_end_date).format(
              "YYYY-MM-DD"
            ),
      discount_end_time:
        this.discount_code_data == null
          ? moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("HH:mm")
          : moment(this.discount_code_data.discount_end_date).format(
              "HH:mm"
            ),
      discount_applied_on:
        this.discount_code_data == null
          ? "all_products"
          : this.discount_code_data.discount_applied_on,
      limit_of_discount:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.limit_on_discount,
      discount_code:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.discount_code,
      discount_percentage:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.discount_percentage,
      discount_value:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.discount_value,
      status:
        this.discount_code_data == null
          ? "1"
          : this.discount_code_data.status == null
          ? ""
          : this.discount_code_data.status.value,
      description:
        this.discount_code_data == null
          ? []
          : this.discount_code_data.description,
      tmp_product_list: [],
      final_product_list:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.discount_applicable_products != null &&
            this.discount_code_data.discount_applicable_products.length > 0
          ? this.discount_code_data.discount_applicable_products
          : this.discount_code_data.discount_not_applicable_products,
      tmp_category_list: [],
      discounttype:
        this.discount_code_data == null
          ? "code"
          : this.discount_code_data.discounttype,
      final_category_list:
        this.discount_code_data == null
          ? ""
          : this.discount_code_data.discount_applicable_categories,
      enter_discount_name: this.$t("Please enter discount name"),
      enter_discount_code: this.$t("Please enter discount code"),
      enter_description: this.$t("Please enter description"),
      enter_discount_percentage: this.$t("Please enter discount percentage"),
      reload_on_submit: {
                  type : String,
                  default : true,
                },
      success: this.$t("SUCCESS"),
    };
  },
  props: {
    statuses: Array,
    products: Array,
    categories: Array,
    discount_code_data: [Array, Object],
    reload_on_submit: {
      type: Boolean,
      default: true,
    },
  },
  mounted() {
    console.log("Add Discount Code page loaded");
    if (
      this.discount_applied_on == "specific_products" ||
      this.discount_applied_on == "all_products_except_specific"
    ) {
      this.filterProducts();
    } else if (this.discount_applied_on == "specific_product_categories") {
      this.filterCategories();
    }
    if (this.discounttype == "code") {
      $("#discount_type_code").click();
      this.hideApplyDiscountOnSection();
    } else if (this.discounttype == "inventory") {
      $("#discount_type_automatic").click();
      $("#discount_automatic_inventory").click();
    } else if (this.discounttype == "cashier") {
      $("#discount_type_automatic").click();
      $("#discount_automatic_cashier").click();
      this.hideApplyDiscountOnSection();
    }

    if (this.is_always > 0) {
      $("#discount_start_date").attr("disabled", true);
      $("#discount_end_date").attr("disabled", true);
      $("#discount_start_time").attr("disabled", true);
      $("#discount_end_time").attr("disabled", true);
    }
  },
  methods: {
    hideApplyDiscountOnSection(){
       $("#apply_discount_on_section").css("display","none");
       this.openProductList("all_products");
    },
    showApplyDiscountOnSection(){
       $("#apply_discount_on_section").css("display","block");
    },
    setToAlways() {
      if (document.querySelector("#chk_is_always").checked == true) {
        $("#discount_start_date").attr("disabled", true);
        $("#discount_end_date").attr("disabled", true);
        $("#discount_start_time").attr("disabled", true);
        $("#discount_end_time").attr("disabled", true);
        this.is_always_checked = 1;
      } else {
        $("#discount_start_date").attr("disabled", false);
        $("#discount_end_date").attr("disabled", false);
        $("#discount_start_time").attr("disabled", false);
        $("#discount_end_time").attr("disabled", false);
        this.is_always_checked = 0;
        this.discount_start_date = moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("YYYY-MM-DD");
        this.discount_end_date = moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("YYYY-MM-DD");

        this.discount_end_time = moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("HH:mm");
        this.discount_start_time = moment(new Date().toLocaleString("en-US",{timeZone:"Asia/Riyadh"})).format("HH:mm");
      }
    },
    setAutomaticDiscountToInventory() {
      this.discounttype = "inventory";
      if (
        document.querySelector("#discount_automatic_inventory").checked == true
      ) {
        this.showApplyDiscountOnSection();
        $(".discount-code-section").removeClass("d-none");
        $("#discount_code_section").addClass("d-none");
      } else {
        $(".discount-code-section").addClass("d-none");
        $("#discount_code_section").removeClass("d-none");
      }
    },
    setAutomaticDiscountToCashier() {
      this.discounttype = "cashier";
      if (
        document.querySelector("#discount_automatic_cashier").checked == true
      ) {
        this.hideApplyDiscountOnSection();
        $(".discount-code-section").removeClass("d-none");
        $("#discount_code_section").addClass("d-none");
        $("#discount_code").attr("v-validate", "");
        $("#discount_code_section").addClass("d-none");
      } else {
        $(".discount-code-section").addClass("d-none");
        $("#discount_code_section").removeClass("d-none");
      }
    },
    showDiscountAutomaticSection() {
      if (document.querySelector("#discount_type_automatic").checked == true) {
        $("#discount_automatic_section").removeClass("d-none");
        $("#discount_code").attr("v-validate", "");
        $("#discount_automatic_inventory").click();
      } else {
        $("#discount_automatic_section").addClass("d-none");
      }
    },
    showDiscountCodeSection() {
      this.hideApplyDiscountOnSection();
      this.discounttype = "code";
      if (document.querySelector("#discount_type_code").checked == true) {
        $("#discount_code").attr("v-validate", "'required|alpha_dash|max:30'");
        $("#discount_code_section").removeClass("d-none");
        $(".discount-code-section").removeClass("d-none");
        $("#discount_automatic_section").addClass("d-none");
      } else {
        $("#discount_code").attr("v-validate", "");
        $("#discount_code_section").addClass("d-none");
        $(".discount-code-section").addClass("d-none");
      }
    },
    format(value, event) {
      return moment(value).format("YYYY-MM-DD");
    },
    setToUnlimited() {
      this.limit_of_discount = "";
      if (document.querySelector("#chk_unlimited").checked == true) {
        document.querySelector("#limit_of_discount_txt").value = "";
        document.querySelector("#limit_of_discount_txt").disabled = true;
      } else {
        document.querySelector("#limit_of_discount_txt").disabled = false;
      }
    },
    addToProductsList(event) {
      let elements = document.querySelectorAll("[id^='chk_product_']");
      this.tmp_product_list = this.final_product_list.split(',');
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_product_list.push(elements[i].id.split("_")[2]);
          this.tmp_product_list = this.tmp_product_list.filter((v, i, a) => a.indexOf(v) === i);
        } else if (elements[i].checked == false && this.tmp_product_list.includes(elements[i].id.split("_")[2])) {
          var tmp_product_list = JSON.parse(JSON.stringify(this.tmp_product_list));
          let index = tmp_product_list.indexOf(elements[i].id.split("_")[2]);
          tmp_product_list.splice(index,1);
          this.tmp_product_list=tmp_product_list;
        }
      }
      this.tmp_product_list = this.tmp_product_list.filter((v, i, a) => a.indexOf(v) === i);
      this.final_product_list = [...new Set(this.tmp_product_list)]
          .join(",")
          .replace(/(,$)/g, "");
    },
    cancelProductsList(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_product_']");
      for (let i = 0; i < elements.length; i++) {
        elements[i].checked = false;
      }
      this.tmp_product_list = [];
      this.final_product_list = "";
      document.querySelector(
        "#addselectedproductsbtn"
      ).innerHTML = `Add Selected Products`;
    },
    addToCategoriesList(event) {
      let elements = document.querySelectorAll("[id^='chk_category_']");
      this.tmp_category_list = this.final_category_list.split(',');
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_category_list.push(elements[i].id.split("_")[2]);
          this.tmp_category_list = this.tmp_category_list.filter((v, i, a) => a.indexOf(v) === i);
        } else if (elements[i].checked == false && this.tmp_category_list.includes(elements[i].id.split("_")[2])) {
          var tmp_category_list = JSON.parse(JSON.stringify(this.tmp_category_list));
          let index = tmp_category_list.indexOf(elements[i].id.split("_")[2]);
          tmp_category_list.splice(index,1);
          this.tmp_category_list=tmp_category_list;
        }
      }
      this.tmp_category_list = this.tmp_category_list.filter((v, i, a) => a.indexOf(v) === i);
      this.final_category_list = [...new Set(this.tmp_category_list)]
          .join(",")
          .replace(/(,$)/g, "");
    },
    cancelCategoriesList(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_category_']");
      for (let i = 0; i < elements.length; i++) {
        elements[i].checked = false;
      }
      this.tmp_category_list = [];
      this.final_category_list = "";
      document.querySelector(
        "#addselectedcategoriesbtn"
      ).innerHTML = `Add Selected Categories`;
    },
    filterProducts() {
      //document.querySelector("#product_list_error").innerHTML = ""
      let search_value = document.querySelector("#search_box").value.toLowerCase();
      let product_list = [],
        products = [];
      let strHTML = `<div class="card product-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                </div>
              </div>`;
      document.querySelector("#product_list").innerHTML = strHTML;
      let productarray =
        this.final_product_list != null
          ? this.final_product_list.split(",")
          : [];
      if (this.products.length > 0) {
        for (let i = 0; i < this.products.length; i++) {

          let searched_product_name =  this.products[i].name.toLowerCase();
          let searched_product_code =  (this.products[i].product_code != null) ? this.products[i].product_code.toLowerCase() : null;

          if (
            ( search_value != "" && searched_product_name.includes(search_value) ) || (search_value != "" && searched_product_code != null && searched_product_code.includes(search_value)) 
          ) {
            product_list.push(this.products[i]);
          }
        }
        if (product_list.length > 0) {
          products = product_list;
        } else {
          products = this.products;
        }
        for (let i = 0; i < products.length; i++) {
          strHTML += `<div
                class="d-flex flex-column justify-content-start align-items-center mb-4"
                id="product_list_${products[i].id}">
                <div
                  class="card product-card"
                  id="product_${products[i].id}"
                  style="width:100%;"
                >
                  <div
                    class="card-body p-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="d-flex flex-row align-items-center">`;
          if (
            (this.discount_applied_on == "specific_products" ||
              this.discount_applied_on == "all_products_except_specific") &&
            productarray.indexOf(String(products[i].id)) != -1
          ) {
            strHTML += `<input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_product_${products[i].id}"
                      checked/>`;
          } else {
            strHTML += `<input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_product_${products[i].id}"
                      />`;
          }
          strHTML += `${this.products[i].product_thumb_image}
                      <div class="product-title" align="left">
                        ${products[i].name} ${(products[i].product_code != null) ? '('+products[i].product_code+')' : ''} </div>
                    </div>
                    <div class="product-price" align="right">
                      <b
                        >Sale Price : SAR
                        ${Number(products[i].sale_amount_excluding_tax).toFixed(
                          2
                        )}</b
                      ><br />
                      <b
                        >Purchase Price : SAR
                        ${Number(
                          products[i].purchase_amount_excluding_tax
                        ).toFixed(2)}</b
                      >
                    </div>
                  </div>
                </div>
              </div>`;
        }
      } else {
        strHTML = `<div class="card product-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                  <div class="d-flex flex-row align-items-center">
                    <p>No products Found</p>
                  </div>
                </div>
              </div>`;
      }
      document.querySelector("#product_list").innerHTML = strHTML;
    },
    filterCategories() {
      //document.querySelector("#product_list_error").innerHTML = "";

      let search_value = document.querySelector("#search_box_for_category")
        .value;

      let strHTML = `<div class="card category-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                </div>
              </div>`;
      document.querySelector("#category_list").innerHTML = strHTML;
      strHTML = ``;
      let categoryarray =
        this.final_category_list != null
          ? this.final_category_list.split(",")
          : [];
      let category_list = [],
        categories = [];
      if (this.categories.length > 0) {
        for (let i = 0; i < this.categories.length; i++) {
          if (
            search_value != "" &&
            this.categories[i].label.includes(search_value)
          ) {
            category_list.push(this.categories[i]);
          }
        }
        if (category_list.length > 0) {
          categories = category_list;
        } else {
          categories = this.categories;
        }
        if (categories.length > 0) {
          for (let i = 0; i < categories.length; i++) {
            strHTML += `<div
                class="d-flex flex-column justify-content-start align-items-center mb-4"
                id="category_list_${categories[i].id}"
              >
                <div
                  class="card category-card"
                  id="category_${categories[i].id}"
                  style="width:100%;"
                >
                  <div
                    class="card-body p-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="d-flex flex-row align-items-center">`;
            if (
              this.discount_applied_on == "specific_product_categories" &&
              categoryarray.indexOf(String(categories[i].id)) != -1
            ) {
              strHTML += ` <input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_category_${categories[i].id}"
                      checked/>`;
            } else {
              strHTML += ` <input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_category_${categories[i].id}"
                      />`;
            }
            strHTML += `<div class="category-title" align="left">
                        ${categories[i].label}
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;
          }
        } else {
          strHTML = `<div class="card category-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                  <div class="d-flex flex-row align-items-center">
                    <p>No Categories Found</p>
                  </div>
                </div>
              </div>`;
        }
        document.querySelector("#category_list").innerHTML = strHTML;
      }
    },
    openProductList(value) {
      if (value == "all_products") {
        document.querySelector("#products_section").style.display = "none";
        document.querySelector("#category_section").style.display = "none";
      } else if (value == "specific_product_categories") {
        document.querySelector("#products_section").style.display = "none";
        document.querySelector("#category_section").style.display = "block";
        this.filterCategories();
      } else {
        document.querySelector("#products_section").style.display = "block";
        document.querySelector("#category_section").style.display = "none";
        this.filterProducts();
      }
      this.discount_applied_on = value;
    },
    setDiscountType() {
      if (this.discount_type == "percentage") {
        document.querySelector("#discount_percentage_section").style.display =
          "block";
        document.querySelector("#discount_value_section").style.display =
          "none";
        document.querySelector("#discount_value_txt").disabled = true;
      } else {
        document.querySelector("#discount_percentage_section").style.display =
          "none";
        document.querySelector("#discount_value_section").style.display =
          "block";
        document.querySelector("#discount_value_txt").disabled = false;
      }
    },
    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          if (this.reload_on_submit) {
            this.show_modal = true;
            this.$on("submit", function() {
              this.processing = true;
              this.form_data();
              //this.$off("submit");
            });

            this.$on("close", function() {
              this.show_modal = false;
            });
          } else {
            // request coming from other component
            this.$on("submit", function() {
              this.processing = true;
              this.form_data();
              //this.$off("submit");
            });

            this.$on("close", function() {
              this.show_modal = false;
            });
          }
        }
      });
    },
    form_data() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append(
        "label",
        this.discount_name == null ? "" : this.discount_name
      );
      formData.append(
        "discount_code",
        this.discount_code == null ? "" : this.discount_code
      );
      formData.append(
        "is_always",
        document.querySelector("#chk_is_always").checked == true ? 1 : 0
      );
      formData.append(
        "description",
        this.description == null ? "" : this.description
      );
      formData.append("status", this.status == null ? "" : this.status);
      formData.append("discount_type", this.discount_type);
      if (this.discount_type == "amount") {
        formData.append("discount_value", this.discount_value);
      } else {
        formData.append(
          "discount_percentage",
          this.discount_percentage == null ? "" : this.discount_percentage
        );
      }
      formData.append("discount_applied_on", this.discount_applied_on);
      if (
        this.discount_applied_on == "specific_products" &&
        this.final_product_list != null
      ) {
        formData.append(
          "discount_applicable_products",
          this.final_product_list
        );
      } else if (
        this.discount_applied_on == "all_products_except_specific" &&
        this.final_product_list != null
      ) {
        formData.append(
          "discount_not_applicable_products",
          this.final_product_list
        );
      } else if (
        this.discount_applied_on == "specific_product_categories" &&
        this.final_category_list != null
      ) {
        formData.append(
          "discount_applicable_categories",
          this.final_category_list
        );
      }
      if (document.querySelector("#chk_unlimited").checked == true) {
        formData.append("limit_on_discount", -1);
      } else {
          formData.append("limit_on_discount", this.limit_of_discount);
      }
      formData.append("discount_start_date", this.discount_start_date);
      formData.append("discount_end_date", this.discount_end_date);

      formData.append("discount_start_time", this.discount_start_time);
      formData.append("discount_end_time", this.discount_end_time);
      formData.append("discounttype", this.discounttype);
      axios
        .post(this.api_link, formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, this.success);
            this.$emit("refreshDiscountCodes", response.data.data);
            this.processing = false;
            this.show_modal = false;
            if (this.reload_on_submit) {
            setTimeout(function() {
              window.location = "/discount_codes";
            }, 1000);
            }
          } else {
            window.scrollTo(0, 0);
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
    },
  },
};
</script>
