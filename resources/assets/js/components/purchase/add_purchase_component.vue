<template>
  <div class="row">
    <div class="col-lg-8 tab-menu">
      <ul class="nav nav-tabs category-tag-wrapper">
        <li>
          <a
            class="category-tag active"
            data-toggle="tab"
            href="javascript:void(0)"
            v-on:click="loadSubcategories(0)"
            >{{ $t("All") }}</a
          >
        </li>
        <li v-for="main_category in main_categories">
          <a
            class="category-tag"
            data-toggle="tab"
            href="javascript:void(0)"
            v-on:click="
              loadSubcategories(main_category.id),
                loadProducts(main_category.id)
            "
            >{{ main_category.label }}</a
          >
        </li>
      </ul>

      <ul class="nav nav-tabs category-tag-wrapper sub-categories">
        <li>
          <a
            class="category-tag active"
            data-toggle="tab"
            href="javascript:void(0)"
            v-on:click="loadProducts(0)"
            >{{ $t("All") }}</a
          >
        </li>
        <li v-if="subcategories.length" v-for="subcategory in subcategories">
          <a
            class="category-tag"
            data-toggle="tab"
            href="javascript:void(0)"
            v-on:click="loadProducts(subcategory.id)"
            >{{ subcategory.label }}</a
          >
        </li>
      </ul>

      <div class="tab-content">
        <div id="menu2" class="tab-pane fade in active show">
          <div class="form-row ml-0 mb-0">
              <div class="input-group mb-3 col-md-12 p-0">
                <input
                  type="search"
                  name="search"
                  v-model="search"
                  class="form-control form-control-lg pos-search-bar"
                  autocomplete="off"
                  :placeholder="search_placeholder"
                  style="border: 1px solid #186ca5 !important;border-top-right-radius: 0 !important;border-bottom-right-radius: 0 !important;"
                  @input="searchProduct(search)"
                />
              </div>
          </div>
          <div class="text-muted">{{ $t(product_msg) }}</div>
          <div class="row" v-if="products.length">
            <div
              class="col-lg-4 mt-15"
              style="padding-left: 11px;"
              align="center"
              v-for="product in products"
            >
              <div
                class="card product-card"
                v-on:click="addProductToBag(product)"
              >
                <div class="card-body p-0">
                  <img
                    :src="[
                      product.product_thumb_image
                        ? '/storage/'+merchant_id+'/product/' + product.product_thumb_image
                        : 'images/default-product-image.png',
                    ]"
                    height="180"
                    style="height:100px;"
                    class="mt-1"
                  />
                  <div class="row" style="padding:15px;">
                    <div class="col-6">
                      <div class="product-title" align="left">
                        {{ product.name }}
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="product-price" align="right">
                        <b>{{ product.purchase_amount_excluding_tax }}</b>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="menu3" class="tab-pane fade">
          <h3>Menu 3</h3>
          <p>Some content in menu 3.</p>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card" v-if="product_bag.length">
        <div class="card-body">
          <div
            class="row product-row"
            v-for="item in product_bag"
            :id="'product_item_' + item.index"
          >
            <div class="col-sm-12 select-product-img-blk">
              <div class="select-product-img">
                <img
                  :src="[
                    item.product_image
                      ? '/storage/'+merchant_id+'/product/' + item.product_image
                      : 'images/default-product-image.png',
                  ]"
                  alt="No Product Image Found"
                  :style="[
                    item.product_image
                      ? { border: '1px solid' + item.product_border_color }
                      : {},
                  ]"
                  class="product-image img-fluid"
                  @error="imageUrl = 'images/default-product-image.png'"
                />
              </div>
            </div>
            <div class="col-sm-12 select-product-price-blk">
              <h6>{{ item.product_name }}</h6>
              <div class="row">
                <div class="col-6">
                  <div class="select-product-piece-label">
                    <label>{{ item.product_unit }}</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="select-product-piece-count">
                    <div class="row align-items-center">
                      <div class="col-4 p-0 text-center">
                        <a
                          href="#"
                          class="count-add-minus"
                          v-on:click="updateQty(item.index, -1)"
                          >-</a
                        >
                      </div>
                      <div class="col-4 p-0 text-center">
                        <!--                                                <label :id="'product_qty_label_'+item.index">1</label>-->

                        <input
                          type="number"
                          v-on:input="
                            updateQty(item.index, item.product_qty, 1)
                          "
                          v-model="item.product_qty"
                          class="form-control form-control-custom cart-product-quantity mr-2 ml-3"
                          autocomplete="off"
                          min="0"
                        />
                      </div>
                      <div class="col-4 p-0 text-center">
                        <a
                          href="#"
                          class="count-add-minus"
                          v-on:click="updateQty(item.index, 1)"
                          >+</a
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-6 mt-3">
                  <div class="select-product-piece-label">
                    <label>{{ item.product_price }}</label>
                  </div>
                </div>
                <div class="col-6 mt-3">
                  <div class="select-product-btn">
                    <button
                      class="btn btn-primary btn-block remove-btn"
                      v-on:click="removeProductFromBag(item.index)"
                    >
                      {{ $t("Remove") }}
                    </button>
                  </div>
                </div>
                <div class="col-md-12">
                  <small class="text-muted">
                    <span class="piece">{{ item.product_qty }}</span>
                    <span class="multiple">*</span>
                    <span class="price">{{ item.product_price }}</span>
                    <span class="equal">=</span>
                    <span class="total-price">{{ item.product_amount }}</span>
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <!-- <div class="row" style="min-height: 70px;"> -->
          <form class="purchases-form">
            <!--                                      <div class="row">-->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for=""
                    >{{ $t("Order Date") }}
                    <span class="text-danger">*</span></label
                  >
                  <input
                    type="date"
                    class="form-control"
                    placeholder=""
                    v-model="order_date"
                    v-validate="'date_format:yyyy-MM-dd'"
                  />
                  <span class="text-danger" v-if="order_date_error"
                    >please select date</span
                  >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="supplier"
                    >{{ $t("Supplier") }}
                    <span class="text-danger">*</span></label
                  >
                  <select name="supplier" id="supplier" class="form-control">
                    <option
                      v-for="supplier in suppliers"
                      :value="supplier.slack"
                      >{{ supplier.name }}</option
                    >
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for=""
                    >{{ $t("PO Number") }}
                    <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    :value="po_number"
                    readonly
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ $t("PO Reference Number") }} </label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="po_reference"
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ $t("Discount Type") }} </label>
                  <select
                    class="form-control"
                    v-model="discount_type"
                    v-on:input="calculateTotal()"
                  >
                    <option value="1" selected="">{{ $t("Amount") }}</option>
                    <option value="2">{{ $t("Percentage") }} (%)</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ $t("Discount") }} </label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder=""
                    v-on:input="calculateTotal()"
                    v-model="discount_rate"
                  />
                </div>
              </div>
            </div>
            <!-- <div class="row">
                                            <div class="col-12">
                                              <div class="form-group">
                                                  <label for="">Store</label>
                                                <select name="store" id="store" class="form-control">
                                                  <option v-for="store in stores" :value="store.id">{{ store.name }}</option>
                                                </select>
                                              </div>
                                            </div>
                                          </div> -->

            <div class="row" v-if="sub_total > 0">
              <div class="col-md-6">
                <span class="fs-15 text-muted"
                  ><strong>{{ $t("Sub Total") }} </strong></span
                >
              </div>
              <div class="col-md-6 text-right">
                <span class="fs-15 text-muted"
                  ><strong
                    >{{ sub_total }} {{ this.session_currency_code }}</strong
                  ></span
                >
              </div>
            </div>

            <div class="row" v-if="discount_amount > 0">
              <div class="col-md-6">
                <span class="fs-15 text-muted"
                  ><strong
                    >{{ $t("Discount") }}
                    <span v-if="discount_type == 2 && discount_rate > 0">
                      ({{ discount_rate }}%)
                    </span></strong
                  ></span
                >
              </div>
              <div class="col-md-6 text-right">
                <span class="fs-15 text-muted"
                  ><strong
                    >{{ discount_amount }}
                    {{ this.session_currency_code }}</strong
                  ></span
                >
              </div>
            </div>

            <div class="form-row mb-2">
              <div class="form-group col-md-12">
                <label for="terms">{{ $t("Terms") }}</label>
                <textarea
                  name="terms"
                  v-model="terms"
                  v-validate="'max:65535'"
                  class="form-control form-control-custom"
                  rows="5"
                  placeholder="Enter Terms"
                ></textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <span class="fs-18 text-blue"
                  ><strong>{{ $t("Total") }}</strong></span
                >
              </div>
              <div class="col-md-6 text-right">
                <span class="fs-18 text-blue"
                  ><strong
                    >{{ total_amount }} {{ this.session_currency_code }}</strong
                  ></span
                >
              </div>
            </div>
            <!-- 
                                          <div class="col-md-3 select-total-label mt-3">
                                            <p><b>{{ $t("Total") }}</b></p>
                                          </div>
                                          <div class="col-md-9 select-total-price mt-3 text-right">
                                            <p><b>{{ total_amount }}</b> <small> SR</small></p>
                                          </div> -->
            <!--                                        </div>-->
            
            <button
              type="button"
              class="btn payment-btn"
              v-on:click="showModal(true)"
              v-if="this.purchase_order_data == null"
            >
              {{ $t("Add Quantity Purchase") }}
            </button>
            
            <button
              type="button"
              class="btn payment-btn"
              v-on:click="showModal(true)"
              v-else
            >
              {{ $t("Update Quantity Purchase") }}
            </button>

          </form>
          <!-- </div> -->
        </div>
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
        <button
          type="button"
          class="btn btn-light"
          v-on:click="showModal(false)"
        >
          {{ $t("Cancel") }}
        </button>
        <button type="button" class="btn btn-primary" v-on:click="submitForm()">
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-if="message_modal">
      <template v-slot:modal-header>
        {{ $t("SUCCESS") }}
      </template>
      <template v-slot:modal-body>
        {{ $t("Purchase order created successfully") }}
      </template>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-light"
          v-on:click="message_modal = false"
        >
          Ok
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script>
"use strict";

import moment from "moment";
import number_format from "locutus/php/strings/number_format";

export default {
  data() {
    return {
      topnav_heading_image: "images/purchase-logo.jpg",
      subcategories: [],
      products: {
        type: [Object, Array],
        value: [],
      },
      api_link        : (this.purchase_order_data == null)?'/api/add_purchase':'/api/update_purchase/'+this.purchase_order_data.slack,
      product_bag: [],
      product_bag_items: 0,
      total_amount: 0,
      discount_rate: 0,
      discount_amount: 0,
      order_date: (this.purchase_order_data != null) ? this.purchase_order_data.order_date_raw : new Date().toISOString().substr(0, 10), // dd/mm/yyyy
      show_modal: false,
      order_date_error: false,
      discount_type: 1,
      po_reference: null,
      sub_total: 0,
      message_modal: false,
      product_msg: "",
      success: this.$t("SUCCESS"),
      terms:
        this.purchase_policy_information == null
          ? ""
          : this.purchase_policy_information,
      store_tax_percentage_amt:
        this.store_tax_percentage == null ? "" : this.store_tax_percentage,
      search : '',
      products_on_load : [Array,Object],
      search_placeholder: this.$t(
        "Search Product Name"
      ),
      total_tax_amount : 0,
    };
  },

  props: [
    "store_tax_percentage",
    "main_categories",
    "suppliers",
    "stores",
    "po_number",
    "session_currency_code",
    "purchase_policy_information",
    "merchant_id",
    "purchase_order_data"
  ],

  mounted() {
    
    /* edit purchase order */

    if(this.purchase_order_data != null){

        $("#supplier").val(this.purchase_order_data.supplier.slack);
        this.po_number = this.purchase_order_data.po_number;
        this.po_reference = (this.purchase_order_data.po_reference != 'null') ? this.purchase_order_data.po_reference : '';
        this.discount_type = this.purchase_order_data.discount_type;
        this.discount_rate = this.purchase_order_data.discount_rate;
        this.terms = this.purchase_order_data.terms;

        if(this.purchase_order_data.products != null){
          
          this.loadEditPurchaseOrder();
    
      }
    
    }

    $("#topnav_heading_image").attr("src", this.topnav_heading_image);
    this.loadSubcategories(0);
    this.loadProducts(0);
  },
  methods: {

    loadEditPurchaseOrder(){
      
      var added_products = [];

      console.log(this.purchase_order_data.products);

      $.each(this.purchase_order_data.products, function(index, product) {

        let item = {};
        
        item['index'] = index++;
        item['product_image'] = null;
        item['product_name'] = product.name;
        item['product_slack'] = product.product_slack;
        item['product_code'] = product.product_code;
        item['product_price'] = parseFloat(product.amount_excluding_tax);
        item['product_unit'] = 1;
        item['product_qty'] = parseInt(product.quantity);
        item['product_amount'] = parseFloat(product.amount_excluding_tax) * parseInt(product.quantity);
        item['product_id'] = product.id;
        
        added_products.push(item);

      });

      
      this.product_bag = added_products;
      this.calculateTotal();

    },
    loadSubcategories(catetgory_id) {
      let formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("catetgory_id", catetgory_id);

      axios.post("/api/load_subcategories", formData).then((response) => {
        // console.log(response.data);
        this.subcategories = response.data;
      });
    },

    loadProducts(category_id) {
      let formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("category_id", category_id);

      axios
        .post("/api/purchase_orders/load_products", formData)
        .then((response) => {
          this.product_msg = response.data.msg;
          this.products = response.data.data;
          this.products_on_load = this.products;
        });
    },

    addProductToBag(product) {
      
      this.product_bag_items = this.product_bag.length;
      
      let item = {
        index: this.product_bag_items++,
        product_image: product.product_thumb_image,
        product_name: product.name,
        product_slack: product.slack,
        product_code: product.product_code,
        product_price: product.purchase_amount_excluding_tax,
        product_unit: 1,
        product_qty: 1,
        product_amount: product.purchase_amount_excluding_tax,
        product_id: product.id
      };

      // skip to add if product is already added into bag
      if (
        this.product_bag.find(
          (item) => item.product_id === product.id
        )
      ) {
        return false;
      }

      this.product_bag.push(item);

      this.calculateTotal();
    },

    removeProductFromBag(index) {
      this.product_bag = $.grep(this.product_bag, function(e) {
        return e.index != index;
      });
      $("#product_item_" + index).remove();
      this.calculateTotal();
    },

    updateQty(index, qty, tmp = 0) {

      let this_item = this.findByValue(this.product_bag, index);

      if (tmp == 1) {
        var new_qty = qty;
      } else {
        var new_qty = parseFloat(this_item.product_qty) + parseFloat(qty);
      }

      if (new_qty > 0) {
        this_item.product_qty = new_qty;
        this_item.product_amount =
          parseFloat(new_qty) * parseFloat(this_item.product_price);
      }

      this.calculateTotal();
    },

    findByValue(object, index) {
      let obj = object.find((item) => item.index === index);
      return obj;
    },

    calculateTotal() {
      let total = 0;
      let discount_amount = 0;
      let discounted_sub_total = 0;
      let tax_total_amt = 0;
      let sub_total_amt = 0;
      $.each(this.product_bag, function(index, value) {
        total += parseFloat(value.product_amount);
      });

      if (this.discount_type == 1) {
        discount_amount = this.discount_rate;
      } else if (this.discount_type == 2) {
        discount_amount = (total * this.discount_rate) / 100;
      }

      this.sub_total = total;

      discount_amount = number_format(discount_amount, 2, ".", "");
      this.discount_amount = discount_amount;

      discounted_sub_total = total - discount_amount;
      discounted_sub_total = number_format(discounted_sub_total, 2, ".", "");
      var total_tax = this.calculate_tax(
        discounted_sub_total,
        this.store_tax_percentage
      );

      tax_total_amt = number_format(total_tax, 2, ".", "");
      // console.log(tax_total_amt,'tax_total_amt');
      sub_total_amt =
        parseFloat(discounted_sub_total) + parseFloat(tax_total_amt);
      this.total_amount = number_format(sub_total_amt, 2, ".", "");

      this.total_tax_amount = tax_total_amt;
      // console.log(this.total_amount,'this.total_amount');
    },

    calculate_tax(item_total, tax_percentage) {
      var tax_amount =
        (parseFloat(tax_percentage) / 100) * parseFloat(item_total);
      return number_format(tax_amount, 3, ".", "");
    },

    convert_date_format(date) {
      return date != "" ? moment(date).format("YYYY-MM-DD") : "";
    },

    showModal(option) {
      this.show_modal = option;
    },

    submitForm() {
      if (this.order_date == null) {
        this.order_date_error = true;
        this.showModal(false);
        return false;
      } else {
        this.order_date_error = false;
      }

      let formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("supplier", $("#supplier").val());
      formData.append("order_date", this.convert_date_format(this.order_date));
      formData.append(
        "order_due_date",
        this.convert_date_format(this.order_date)
      );
      formData.append("currency", this.session_currency_code);
      formData.append("shipping_charge", 0);
      formData.append("packing_charge", 0);
      formData.append("tax_option", "DEFAULT_TAX");
      formData.append("update_stock", 1);
      formData.append("po_number", this.po_number);
      formData.append("po_reference", this.po_reference);
      formData.append("products", JSON.stringify(this.product_bag));
      formData.append("discount_type", this.discount_type);
      formData.append("discount_rate", this.discount_rate);
      formData.append("store_tax_percentage", this.store_tax_percentage);
      formData.append("total_discount_amount", this.discount_amount);
      formData.append("terms", this.terms);
      formData.append("total_tax_amount", this.total_tax_amount);

      // let api_link = '/api/add_purchase';
      // if(this.purchase_order_data != null){
      //   api_link = '/api/update_purchase';
      // }

      axios
        .post(this.api_link, formData)
        .then((response) => {

          this.show_modal = false;
          if (response.data.status) {
            if (response.data.status_code == 200) {
              this.show_response_message(response.data.msg, this.success);
              location.href = "/purchase_order/" + response.data.data;
            } else {
              this.processing = false;
              this.show_error_response_message(response.data.msg, this.error);
              try {
                var error_json = JSON.parse(response.data.msg);
                this.loop_api_errors(error_json);
              } catch (err) {
                this.close_register_server_errors = response.data.msg;
              }
              this.error_class = "error";
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    searchProduct(){
      
      if(this.search != ''){

        var products = this.products_on_load.filter(item => {
         return item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1
        })

        this.products = products;

      }else{
        
        this.products = this.products_on_load;
      
      }
      
      this.product_msg = this.products.length+" products found"; 

    }
  },
};
</script>
