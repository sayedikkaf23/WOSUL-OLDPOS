<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <div>
              <span
                class="text-title"
                v-if="quantity_adjustment.status == null"
              >
                {{ $t("Add Quantity Adjustment") }}</span
              >
              <span
                class="text-title"
                v-if="quantity_adjustment.status == 'draft'"
              >
                {{ $t("Edit Quantity Adjustment")
                }}<span class="badge badge-primary">{{
                  $t("draft")
                }}</span></span
              >
            </div>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-4',
              'form-group mb-1',
            ]"
          >
            <label for="quantity_adjustment_branch">{{ $t("Branch") }}</label>
            <select
              name="quantity_adjustment_branch"
              id="quantity_adjustment_branch"
              class="form-control form-control-custom custom-select"
              v-model="branch_id"
            >
              <option
                v-for="(store, index) in stores"
                v-bind:value="store.id"
                v-bind:key="index"
              >
                {{ $t(store.name) }}
              </option>
            </select>
            <span
              v-bind:class="{
                error: errors.has('quantity_adjustment_branch'),
              }"
              >{{ errors.first("quantity_adjustment_branch") }}</span
            >
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-4',
              'form-group mb-1',
            ]"
          >
            <label for="reason">{{ $t("Reason") }}</label>
            <input
              type="text"
              name="reason"
              v-model="reason"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              :placeholder="enter_reason"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('reason') }">{{
              errors.first("reason")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2 mt-4">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-9',
              'form-group mb-1',
            ]"
          >
            <label for="">{{ $t("Items") }}</label>
          </div>

          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-3',
              'form-group mb-1',
              'd-flex',
              'align-items-center',
              'justify-content-end',
            ]"
          >
            <button type="button" class="btn btn-primary" @click="addItem()">
              {{ $t("Add Item") }}
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="edit_quantity_enabled"
              style="margin-left:15px;"
              @click="editQuantity()"
            >
              {{ $t("Edit Quantity") }}
            </button>
          </div>
        </div>

        <div class="form-row mb-2 mt-2">
          <div
            v-bind:class="[
              reload_on_submit ? 'col-md-3' : 'col-md-12',
              'form-group mb-1',
            ]"
          >
            <div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{ $t("Name") }}</th>
                    <th>{{ $t("Product Code") }}</th>
                    <th>{{ $t("Deducted Quantity") }}</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody id="item_list" @click="deleteProduct">
                  <tr>
                    <td colspan="3" class="text-center">
                      {{ $t("No Item to Display") }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <modalcomponent
          v-if="show_item_modal"
          v-on:close="show_item_modal = false"
        >
          <template v-slot:modal-header>
            {{ $t("Add Product") }}
          </template>
          <template v-slot:modal-body>
            <label>{{ $t("Choose Products") }}</label>
            <input
              type="search"
              class="form-control mb-2"
              @input="searchProducts"
              placeholder="Search Product"
            />
            <select
              name="chosen_products_for_quantity_adjustment"
              v-model="products_list"
              class="form-control form-control-custom custom-select"
              multiple="multiple"
            >
              <option
                v-for="(product, index) in searched_products"
                v-bind:value="product.id"
                v-bind:key="index"
              >
                {{ $t(product.name) }}
              </option>
            </select>
          </template>
          <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">
              {{ $t("Cancel") }}
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="addProductsToList()"
              v-bind:disabled="processing == true"
            >
              {{ $t("Continue") }}
            </button>
          </template>
        </modalcomponent>

        <modalcomponent
          v-if="show_edit_modal"
          v-on:close="show_edit_modal = false"
        >
          <template v-slot:modal-header>
            {{ $t("Edit Quantity") }}
          </template>
          <template v-slot:modal-body>
            <div class="form-row mb-2">
              <div
                v-bind:class="[
                  reload_on_submit ? 'col-md-3' : 'col-md-12',
                  'form-group mb-1',
                ]"
                v-for="(product, index) in selected_products_list"
                v-bind:key="index"
              >
                <label>{{ product.name }}</label>
                <input
                  type="number"
                  class="form-control"
                  :id="`selected_quantity_${product.id}`"
                  :value="`${product.quantity}`"
                   min = 1
                  :max="`${product.original_quantity}`"
                  />
                  <!-- @change="checkQuantity(`${product.quantity}`,`${product.original_quantity}`,`${product.id}`,product.name)" -->
              </div>
            </div>
          </template>
          <template v-slot:modal-footer>
            <button
              type="button"
              class="btn btn-primary"
              @click="saveProductInfo()"
            >
              {{ $t("Save") }}
            </button>
          </template>
        </modalcomponent>
        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button
              type="button"
              class="btn btn-primary"
              v-bind:disabled="processing == true"
              v-if="quantity_adjustment.status == null"
              @click="submitDraft()"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Save as draft") }}
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-bind:disabled="processing == true"
              @click="submitQA()"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Submit") }}
            </button>
          </div>
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
              type="submit"
              class="btn btn-primary"
              @click="submit_form()"
              v-bind:disabled="processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Continue") }}
            </button>
          </template>
        </modalcomponent>
      </form>
    </div>
  </div>
</template>

<script>
"use strict";
import moment from "moment";
export default {
  data() {
    return {
      branch_name:
        localStorage.getItem("branch_name") != null
          ? localStorage.getItem("branch_name")
          : null,
      branch_id:
        localStorage.getItem("branch_id") != null
          ? localStorage.getItem("branch_id")
          : null,
      searched_products: this.products,
      enter_branch_name: this.$t("Please enter branch name"),
      reason:
        localStorage.getItem("reason") != null
          ? localStorage.getItem("reason")
          : "Expired",
      action:
        localStorage.getItem("action") != null
          ? localStorage.getItem("action")
          : "Expired",
      enter_reason: this.$t("Please enter the reason"),
      business_date: null,
      clicked_button: "",
      error_class: "",
      processing: false,
      show_item_modal: false,
      products_list: [],
      selected_products_list: [],
      edit_quantity_enabled: false,
      show_edit_modal: false,
      show_modal: false,
    };
  },
  methods: {
    checkQuantity(entered_quantity,original_quantity,product_id,product_name)
    {
      if(parseInt($(`#selected_quantity_${product_id}`).val())>parseInt(original_quantity))
      {
         alert(`Entered Quantity For the product '${product_name}' Exceeds available quantity!!!`);
         $(`#selected_quantity_${product_id}`).val(parseInt(entered_quantity));
      }
    },
    addItem() {
      this.show_item_modal = true;
      let products = [];
      for (let product in this.products) {
        products.push(this.products[product]);
      }
      this.searched_products = products;
    },
    searchProducts(event) {
      let products = [];
      for (let product in this.products) {
        if (
          this.products[product].name
            .toLowerCase()
            .match(`${event.target.value.toLowerCase()}`) != null
        ) {
          products.push(this.products[product]);
        }
      }
      this.searched_products = products;
    },
    addProductsToList() {
      this.show_item_modal = false;
      let products = [];
      if (this.products_list.length > 0) {
        products = this.products_list;
      }
      let strHTML = ``;
      for (let product in this.products) {
        if (products.indexOf(this.products[product].id) != -1) {
          this.products[product].original_quantity = this.products[product].quantity;
          this.products[product].quantity = 1;
          this.selected_products_list.push(this.products[product]);
        }
      }
      this.refreshTables();
    },
    deleteProduct(event) {
      let productdetail = event.target.id.split("_");
      let products = [];
      if (productdetail[0] == "btn") {
        for (let product in this.selected_products_list) {
          if (this.selected_products_list[product].id != productdetail[1]) {
            products.push(this.selected_products_list[product]);
          }
        }
      }
      this.selected_products_list = products;
      this.refreshTables();
    },
    refreshTables() {
      let strHTML = ``;
      let deletestr = this.$t("Delete");
      for (let product in this.selected_products_list) {
        strHTML += `<tr id="product_${this.selected_products_list[product].id}">
                        <td id="product_name_${this.selected_products_list[product].slack}">${this.selected_products_list[product].name}</td>
                        <td id="product_sku_${this.selected_products_list[product].slack}">${this.selected_products_list[product].product_code}</td>
                        <td id="product_quantity_${this.selected_products_list[product].slack}">${this.selected_products_list[product].quantity} Unit</td>
                        <td><button type="button" id="btn_${this.selected_products_list[product].id}" class="btn btn-primary">${deletestr}</button></td>
                      </tr>`;
      }
      if (this.selected_products_list.length == 0) {
        document.querySelector("#item_list").innerHTML = `<tr>
                    <td colspan="3" class="text-center">No Item to Display</td>
                  </tr>`;
        this.edit_quantity_enabled = false;
      } else {
        this.edit_quantity_enabled = true;
        document.getElementById("item_list").innerHTML = strHTML;
      }
    },
    saveProductInfo() {
      for (let product in this.selected_products_list) {
        this.selected_products_list[product].quantity = document.querySelector(
          `#selected_quantity_${this.selected_products_list[product].id}`
        ).value;
      }
      this.show_edit_modal = false;
      this.refreshTables();
    },
    editQuantity() {
      this.show_edit_modal = true;
    },
    submitDraft() {
      this.clicked_button = "draft";
      this.show_modal = true;
    },
    submitQA() {
      this.clicked_button = "closed";
      this.show_modal = true;
    },
    submit_form() {
      let products = [];
      for (let product in this.selected_products_list) {
        products.push(this.selected_products_list[product]);
      }
      let status;
      if (this.clicked_button == "draft") {
        status = "draft";
      } else {
        status = "closed";
      }
      this.processing = true;
      var formData = new FormData();
      formData.append(
        "quantity_adjustment_slack",
        this.quantity_adjustment.slack != null
          ? this.quantity_adjustment.slack
          : ""
      );
      formData.append("access_token", window.settings.access_token);
      formData.append("store_id", this.branch_id);
      formData.append("reason", this.reason);
      formData.append("action", this.action);
      formData.append("status", status);
      formData.append("products", JSON.stringify(products));
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          axios
            .post("/api/add_quantity_adjustment", formData)
            .then((response) => {
              if (response.data.status_code == 200) {
                this.processing = false;
                this.show_modal = false;
                this.show_response_message(response.data.msg, this.success);
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  window.location = response.data.link;
                }
              } else {
                window.scrollTo(0, 0);
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
              this.show_modal = false;
              console.log(error.message);
            });
        }
      });
      this.$on("close", function() {
        this.show_modal = false;
      });
    },
  },
  props: {
    products: Array,
    stores: Array,
    quantity_adjustment: Array,
    quantity_adjustment_products: Array,
  },
  mounted() {
    console.log(this.products);
    console.log(this.quantity_adjustment_products.length);
    if (this.quantity_adjustment.store_id != null) {
      this.branch_id = this.quantity_adjustment.store_id;
    }
    if (this.quantity_adjustment.reason != null) {
      this.reason = this.quantity_adjustment.reason;
    }
    if (this.quantity_adjustment_products.length > 0) {
      this.selected_products_list = this.quantity_adjustment_products;
      this.refreshTables();
    }
  },
};
</script>
