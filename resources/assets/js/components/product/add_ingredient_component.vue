<template>
  <div class="row">
    <div class="col-md-12">

      <form @submit.prevent="submit_form" class="mb-3">

        <div class="alert alert-success" v-show="show_msg">{{ msg }}</div>

        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <div v-if="typeof stock_transfer_product_slack == 'undefined' || stock_transfer_product_slack == ''">
              <span class="text-title" v-if="product_data !== null">{{ $t("Edit Ingredient") }}</span>
              <span class="text-title" v-else>{{ $t("Add Ingredient") }}</span>
            </div>
            <div v-else>
              <span class="text-title">{{ $t("Add Stock Transfer Product") }}</span>
            </div>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div v-if="typeof stock_transfer_product_slack != 'undefined' && stock_transfer_product_slack != ''">
          <div class="d-flex flex-wrap mb-1">
            <div class="mr-auto">
              <span class="text-subhead">{{ $t("Stock Transfer Information") }}</span>
            </div>
            <div class="">

            </div>
          </div>
          <div class="form-row mb-3 border-bottom">
            <div class="form-group col-md-3">
              <label for="stock_transfer_product_code">{{ $t("Source Store Product Code") }}</label>
              <p>{{ stock_transfer_product.product_code }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_product_name">{{ $t("Source Store Product Name") }}</label>
              <p>{{ stock_transfer_product.product_name }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_quantity">{{ $t("Transferred Quantity") }}</label>
              <p>{{ stock_transfer_product.quantity }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_quantity">{{ $t("Current Status") }}</label>
              <p><span v-bind:class="stock_transfer_product.status.color">{{
                  stock_transfer_product.status.label
                }}</span></p>
            </div>

          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t("Ingredient Information") }}</span>
          </div>
          <div class="">

          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="name">{{ $t("Name") }}</label>
            <input type="text" name="name" v-model="product_name" v-validate="'required|max:250'"
                   class="form-control form-control-custom" :placeholder="ingredient_placeholder" autocomplete="off">
            <span v-bind:class="{ 'error' : errors.has('name') }">{{ errors.first('name') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="product_code">{{ $t("Product Code") }}</label>
            <input type="text" name="product_code" v-model="product_code" v-validate="'alpha_dash|max:30'"
                   class="form-control form-control-custom" :placeholder="product_code_placeholder" autocomplete="off">
            <span v-bind:class="{ 'error' : errors.has('product_code') }">{{ errors.first('product_code') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="supplier">{{ $t("Supplier") }}
              <button type="button" class="btn btn-sm btn-secondary" @click="addSupplier()">+</button>
            </label>
            <select name="supplier" v-model="supplier" class="form-control form-control-custom custom-select">
              <option value="">{{ $t("Choose Supplier..") }}</option>
              <option v-for="(supplier, index) in suppliers" v-bind:value="supplier.slack" v-bind:key="index">
                {{ supplier.name }}
              </option>
            </select>
            <!-- <span v-bind:class="{ 'error' : errors.has('supplier') }">{{ errors.first('supplier') }}</span>  -->
          </div>
          <div class="form-group col-md-3">
            <label for="main_category"
            >{{ $t("Category") }}
              <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="addMainCategory()"
              >
                +
              </button>
            </label
            >
            <select
                name="main_category"
                v-model="main_category"
                v-validate="'required'"
                class="form-control form-control-custom custom-select"
                v-on:change="loadSubcategories()"
                id="main_category_id"
            >
              <option value="">{{ $t("Choose Category..") }}</option>
              <option
                  v-for="(main_cat, index) in main_categories"
                  v-bind:value="main_cat.id"
                  v-bind:key="index"
              >
                {{ main_cat.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('main_category') }">{{
                errors.first("main_category")
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="category"
            >{{ $t("Sub Category") }}
              <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="addCategory()"
              >
                +
              </button>
            </label
            >
            <select
                name="category"
                v-model="category"
                id="sub_category"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Sub Category..") }}</option>
              <option
                  v-for="(category, index) in categories"
                  v-bind:value="category.id"
                  v-bind:key="index"
              >
                {{ category.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('category') }">{{
                errors.first("category")
              }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="shows_in">{{ $t("Shows In") }}</label>
            <select
                name="shows_in"
                v-model="shows_in"
                v-validate="'required|numeric'"
                class="form-control form-control-custom custom-select"
            >
              <option value="" selected>{{ $t("Choose Shows In..") }}</option>
              <option value="0">{{ $t("Don't Show Anywhere") }}</option>
              <option value="1">{{ $t("POS") }}</option>
              <option value="2">{{ $t("Invoice") }}</option>
              <option value="3">{{ $t("Both (POS & Invoice)") }}</option>
            </select>
            <span v-bind:class="{ error: errors.has('shows_in') }">{{
                errors.first("shows_in")
              }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select name="status" v-model="status" v-validate="'required|numeric'"
                    class="form-control form-control-custom custom-select">
              <option value="">{{ $t("Choose Status..") }}</option>
              <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                {{ $t(status.label) }}
              </option>
            </select>
            <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="product_manufacturer_date">{{ $t("Product Manufacturer Date") }}</label>
            <input type="date" v-model="product_manufacturer_date" class="form-control form-control-custom"
                   v-validate="'date_format:yyyy-MM-dd'">
            <span v-bind:class="{ 'error' : errors.has('product_manufacturer_date') }">{{
                errors.first('product_manufacturer_date')
              }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="product_expiry_date">{{ $t("Product Expiry Date") }}</label>
            <input type="date" v-model="product_expiry_date" v-validate="'date_format:yyyy-MM-dd'"
                   class="form-control form-control-custom">
            <span v-bind:class="{ 'error' : errors.has('product_expiry_date') }">{{
                errors.first('product_expiry_date')
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="barcode">{{ $t("Barcode") }} &nbsp; </label>
            <div class="input-group mb-3">
              <input
                  type="text"
                  name="barcode"
                  v-model="barcode"
                  class="form-control form-control-custom"
                  :placeholder="barcode_placeholder"
                  autocomplete="off"
              />
              <div class="input-group-append">
                <button
                    class="btn btn-sm btn-secondary"
                    type="button"
                    @click="generate_product_barcode()"
                >
                  Generate Barcode
                </button>
              </div>
            </div>

            <span v-bind:class="{ error: errors.has('barcode') }">{{
                errors.first("barcode")
              }}</span>
          </div>

        </div>

        <div class="form-row mb-2">

          <!-- <div class="form-group col-md-3">
              <label for="inventory_type">{{ $t("Inventory Type") }}</label>
              <select name="inventory_type" v-model="inventory_type" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                  <option value="" selected>{{ $t("Choose Inventory Type..") }}</option>
                  <option value="1">{{ $t("Inventory") }}</option>
                  <option value="2">{{ $t("Raw Material") }}</option>
              </select>
              <span v-bind:class="{ 'error' : errors.has('inventory_type') }">{{ errors.first('inventory_type') }}</span>
          </div> -->


        </div>

        <hr>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t("Price, Quantity and Tax Information") }}</span>
          </div>
          <div class="">

          </div>
        </div>
        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="purchase_price">{{ $t("Purchase Price Excluding Tax") }} ({{ currency_code }})</label>
            <input type="number" name='purchase_price' v-model="purchase_price" v-validate="'required|decimal'"
                   class="form-control form-control-custom" :placeholder="purchase_price_placeholder" autocomplete="off"
                   step="0.01" min="0">
            <span v-bind:class="{ 'error' : errors.has('purchase_price') }">{{ errors.first('purchase_price') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="sale_price">{{ $t("Sale Price Excluding Tax") }} ({{ currency_code }})</label>
            <input type="number" name='sale_price' v-model="sale_price" v-validate="'required|decimal'"
                   class="form-control form-control-custom" :placeholder="sale_price_placeholder" autocomplete="off"
                   step="0.01" min="0">
            <span v-bind:class="{ 'error' : errors.has('sale_price') }">{{ errors.first('sale_price') }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="measurement_category"
            >{{ $t("Measurement Category") }}<button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addMeasurementCategory()"
            >
              +
            </button>
            </label>
            <select
                name="measurement_category"
                v-model="measurement_category"
                class="form-control form-control-custom custom-select"
                @change="loadMeasurements(measurement_category)"
            >
              <option value="" selected
              >{{ $t("Choose Measurement Category..") }}
              </option>
              <option
                  v-for="(measurement_category, index) in measurement_categories"
                  v-bind:value="measurement_category.id"
                  v-bind:key="index"
              >
                {{ measurement_category.label }}
              </option>
            </select>
            <span
                v-bind:class="{ error: errors.has('measurement_category') }"
            >{{ errors.first("measurement_category") }}</span
            >
          </div>
          <div class="form-group col-md-2">
            <label for="measurement">{{ $t("Measurement") }}
              <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="addMeasurementUnit()"
              >
                +
              </button>
            </label>
            <select
                name="measurement"
                v-model="measurement"
                class="form-control form-control-custom custom-select"
            >
              <option value="" selected=""
              >{{ $t("Choose Measurement Unit..") }}
              </option>
              <option
                  v-for="(measurement, index) in measurements"
                  v-bind:value="measurement.id"
                  v-bind:key="index"
              >
                {{ measurement.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('measurement') }">{{
                errors.first("measurement")
              }}</span>
          </div>

        </div>
        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Quantity") }}</label>
            <input type="number" name='quantity' v-model="quantity" v-validate="quantity_validate"
                   class="form-control form-control-custom" :placeholder="quantity_placeholder" autocomplete="off"
                   step="0.01" min="0">
            <span v-bind:class="{ 'error' : errors.has('quantity') }">{{ errors.first('quantity') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Stock Alert Quantity") }}</label>
            <input type="number" name='alert_quantity' v-model="alert_quantity" v-validate="'decimal'"
                   class="form-control form-control-custom" :placeholder="alert_quantity_placeholder" autocomplete="off"
                   step="0.01" min="0">
            <span v-bind:class="{ 'error' : errors.has('alert_quantity') }">{{ errors.first('alert_quantity') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t("Discount") }}
              <button type="button" class="btn btn-sm btn-secondary" @click="addDiscount()">+</button>
            </label>
            <select name="discount_code" v-model="discount_code" v-validate="''"
                    class="form-control form-control-custom custom-select">
              <option value="">{{ $t("Choose Discount Code..") }}</option>
              <option v-for="(discount_code, index) in discount_codes" v-bind:value="discount_code.id"
                      v-bind:key="index">
                {{ discount_code.label }}
              </option>
            </select>
            <span v-bind:class="{ 'error' : errors.has('discount_code') }">{{ errors.first('discount_code') }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="brand">
              {{ $t("Brand") }}
              <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="addBrand()"
              >
                +
              </button>
            </label
            >
            <select
                name="brand"
                v-model="brand"
                class="form-control form-control-custom custom-select"
            >
              <option value="" selected="">{{ $t("Choose Brand..") }}</option>
              <option
                  v-for="(brand, index) in brands"
                  v-bind:value="brand.id"
                  v-bind:key="index"
              >
                {{ brand.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('brand') }">{{
                errors.first("brand")
              }}</span>
          </div>

        </div>
        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="is_taxable"><input type="checkbox" id="is_taxable" v-model="is_taxable"> {{ $t("Is Taxable") }}</label>
            <select name="tax_code" v-model="tax_code_id" v-validate="''"
                    class="form-control form-control-custom custom-select" v-show="is_taxable" readonly>
              <option v-for="(taxcode, index) in taxcodes" v-bind:value="taxcode.id" v-bind:key="index">
                {{ taxcode.tax_code }} - {{ taxcode.label }}
              </option>
            </select>
          </div>
        </div>
        <div class="form-row mb-2">
          <div class="col-md-3">
            <label for="product_thumb_image">{{ $t("Ingredient Thumb Image") + " (jpeg, jpg, png, webp)" }}</label>
            <input type="file" class="form-control-file form-control form-control-custom file-input"
                   name="product_thumb_image" ref="product_thumb_image" accept="image/x-png,image/jpeg,image/webp"
                   v-validate="'ext:jpg,jpeg,png,webp|size:3000'">
            <small class="form-text text-muted mb-1">{{ $t("Allowed file size per file is 3 MB") }}</small>
            <span v-bind:class="{ 'error' : errors.has('product_thumb_image') }">{{
                errors.first('product_thumb_image')
              }}</span>
          </div>
          <!--
                              <div class="form-group col-md-3">
                                  <label for="product_image">{{ $t("Product Image")+" (jpeg, jpg, png, webp)" }}</label>
                                  <input type="file" class="form-control-file form-control form-control-custom file-input" name="product_image" ref="product_image" accept="image/x-png,image/jpeg,image/webp" v-validate="'ext:jpg,jpeg,png,webp|size:1500'" multiple="multiple">
                                  <small class="form-text text-muted mb-1">Allowed file size per file is 1.5 MB</small>
                                  <small class="form-text text-muted">Hold down CTRL or Command for choosing multiple files</small>
                                  <span v-bind:class="{ 'error' : errors.has('product_image') }">{{ errors.first('product_image') }}</span>
                              </div>
           -->
          <div class="form-group col-md-3">
            <label for="description">{{ $t("Description") }}</label>
            <textarea name="description" v-model="description" v-validate="'max:65535'"
                      class="form-control form-control-custom" rows="5"
                      :placeholder="description_placeholder"></textarea>
            <span v-bind:class="{ 'error' : errors.has('description') }">{{ errors.first('description') }}</span>
          </div>

        </div>

        <div class="mb-2">
          <div class="d-flex flex-row flex-wrap">
            <div class="" v-for="(image, index) in images" v-bind:value="image.slack" v-bind:key="index">
              <button type="button" aria-label="Close" class="close bg-light image-remove"
                      v-on:click="remove_image(image.slack)">
                <span aria-hidden="true">&times;</span>
              </button>
              <img :src="image.thumbnail" alt="" class="rounded mr-3 mb-3" v-on:click="open_image(image.filename)">
            </div>
          </div>
        </div>

        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"><i
                class='fa fa-circle-notch fa-spin' v-if="processing == true"></i> {{ $t("Save") }}
            </button>
          </div>
        </div>

        <hr>

      </form>

    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">{{ $t('Ingredient status is inactive.') }}</p>
        {{ $t('Are you sure you want to proceed?') }}
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Cancel') }}</button>
        <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"><i
            class='fa fa-circle-notch fa-spin' v-if="processing == true"></i> {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add Supplier Modal -->
    <modalcomponent v-if="add_supplier_modal" v-on:close="add_supplier_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add New Supplier') }}
      </template>
      <template v-slot:modal-body>
        <addsuppliercomponent :statuses="statuses" :reload_on_submit="false"
                              @refreshSupplier="refreshSupplier"></addsuppliercomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add Main Category Modal -->
    <modalcomponent v-if="add_maincategory_modal" v-on:close="add_maincategory_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add New Category') }}
      </template>
      <template v-slot:modal-body>
        <addmaincategorycomponent :statuses="statuses" :reload_on_submit="false"
                                  @refreshMainCategory="refreshMainCategory"></addmaincategorycomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add Category Modal -->
    <modalcomponent v-if="add_category_modal" v-on:close="add_category_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add New Category') }}
      </template>
      <template v-slot:modal-body>
        <addcategorycomponent :statuses="statuses" :main_categories="main_categories" :main_category_id="main_category"
                              :reload_on_submit="false" @closeCategoryModal="closeCategoryModal"></addcategorycomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add Tax Modal -->
    <modalcomponent v-if="add_tax_modal" v-on:close="add_tax_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add New Tax') }}
      </template>
      <template v-slot:modal-body>
        <addtaxcodecomponent :statuses="statuses" :tax_code_data="null" :reload_on_submit="false"
                             @refreshTaxCodes="refreshTaxCodes"></addtaxcodecomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add Discount Code Modal -->
    <modalcomponent v-if="add_discount_modal" v-on:close="add_discount_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add Discount Code') }}
      </template>
      <template v-slot:modal-body>
        <adddiscountcodecomponent :statuses="statuses" :products="product_list" :categories="category_list"
                                  :discount_code_data="null" :reload_on_submit="false"
                                  @refreshDiscountCodes="refreshDiscountCodes"></adddiscountcodecomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add brand Modal -->
    <modalcomponent v-if="add_brand_modal" v-on:close="add_brand_modal = false" :hide_modal_footer="true"
                    :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Add Brand') }}
      </template>
      <template v-slot:modal-body>
        <addbrandcomponent :statuses="statuses" :brand_data="null" :reload_on_submit="false"
                           @refreshBrands="refreshBrands"></addbrandcomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
      </template>
    </modalcomponent>

    <!-- Add measurement unit modal -->
    <modalcomponent
        v-if="add_measurement_unit_modal"
        v-on:close="add_measurement_unit_modal = false"
        :hide_modal_footer="true"
        :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add Measurement") }}
      </template>
      <template v-slot:modal-body>
        <addmeasurementcomponent
            :statuses="statuses"
            :reload_on_submit="false"
            :measurement_categories="measurement_categories"
            @refreshMeasurements="refreshMeasurementDetails"
        ></addmeasurementcomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add Measurement Category-->
    <modalcomponent
        v-if="add_measurement_category_modal"
        v-on:close="add_measurement_category_modal = false"
        :hide_modal_footer="true"
        :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add Measurement Category") }}
      </template>
      <template v-slot:modal-body>
        <addmeasurementcategorycomponent
            :statuses="statuses"
            :reload_on_submit="false"
            :measurement_categories="measurement_categories"
            @refreshMeasurementCategory="refreshMeasurementCategoryDetails"
        ></addmeasurementcategorycomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

  </div>
</template>

<script>
'use strict';

import {CoolSelect} from "vue-cool-select";
import 'vue-cool-select/dist/themes/bootstrap.css';

export default {
  data() {
    return {
      server_errors: '',
      add_measurement_category_modal: false,

      error_class: '',
      processing: false,
      modal: false,
      show_modal: false,
      api_link: (this.product_data == null) ? '/api/add_ingredient' : '/api/update_ingredient/' + this.product_data.slack,

      product_slack: this.product_data == null ? "" : this.product_data.slack,
      product_name: (this.product_data == null) ? '' : this.product_data.name,
      product_code: (this.product_data == null) ? '' : this.product_data.product_code,
      barcode: this.product_data == null ? "" : this.product_data.barcode,
      description: (this.product_data == null) ? '' : this.product_data.description,
      supplier: (this.product_data == null) ? '' : (this.product_data.supplier == null) ? '' : this.product_data.supplier.slack,
      brand:
          this.product_data == null
              ? ""
              : this.product_data.brand_id == null
                  ? ""
                  : this.product_data.brand_id,
      measurement: (this.product_data == null) ? '' : (this.product_data.measurement_id == null) ? '' : this.product_data.measurement_id,
      main_category: (this.main_category_id == null) ? '' : this.main_category_id,
      category: (this.category_id == null) ? '' : this.category_id,
      main_category_id: Number,
      tax_code_id : (this.product_data == null || this.product_data.tax_code_id == 0)? this.store_tax_id :this.product_data.tax_code_id,
      discount_code: (this.product_data == null) ? '' : (this.product_data.discount_code == null) ? '' : this.product_data.discount_code.id,
      product_manufacturer_date: (this.product_data == null) ? '' : (this.product_data.product_manufacturer_date != null) ? this.product_data.product_manufacturer_date_raw : '',

      product_expiry_date: (this.product_data == null) ? '' : (this.product_data.product_expiry_date != null) ? this.product_data.product_expiry_date_raw : '',
      quantity: (this.product_data == null) ? '' : this.product_data.quantity,
      alert_quantity: (this.product_data == null) ? '' : this.product_data.alert_quantity,
      sale_price: (this.product_data == null) ? '' : this.product_data.sale_amount_excluding_tax,
      purchase_price: (this.product_data == null) ? '' : this.product_data.purchase_amount_excluding_tax,
      status: (this.product_data == null) ? '1' : this.product_data.status.value,
      // inventory_type  : (this.product_data == null)?'':this.product_data.inventory_type.value,
      shows_in: this.product_data == null ? 1 : this.product_data.shows_in,
      images: (this.product_data == null) ? '' : this.product_data.images,
      currency_code: window.settings.currency_code,

      stock_transfer_max_quantity: (this.stock_transfer_data == null) ? '' : this.stock_transfer_product_data.quantity,
      stock_transfer_product_slack: (this.stock_transfer_data == null) ? '' : this.stock_transfer_product_data.slack,
      stock_transfer: (this.stock_transfer_data == null) ? '' : this.stock_transfer_data,
      stock_transfer_product: (this.stock_transfer_product_data == null) ? '' : this.stock_transfer_product_data,

      quantity_validate: {
        required: true,
        decimal: true
      },

      is_ingredient: (this.product_data == null) ? true : (this.product_data.is_ingredient != null) ? ((this.product_data.is_ingredient == 1) ? true : false) : false,

      ingredient_list: [],
      search_ingredients: '',
      ingredient_template: {
        ingredient_slack: '',
        name: '',
        sale_price: '',
        purchase_price: '',
        quantity: '',
        unit_slack: ''
      },

      product_ingredient_list: (this.product_data != null) ? this.product_data.ingredients : [],

      ingredients: [],

      restaurant_mode: window.settings.restaurant_mode,
      ingredient_purchase_price: 0,
      ingredient_selling_price: 0,

      is_ingredient_price: (this.product_data == null) ? false : (this.product_data.is_ingredient_price != null) ? ((this.product_data.is_ingredient_price == 1) ? true : false) : false,
      categories: this.subcategories == null ? [] : this.subcategories,
      add_supplier_modal: false,
      add_maincategory_modal: false,
      add_category_modal: false,
      add_tax_modal: false,
      add_discount_modal: false,
      add_brand_modal: false,
      add_measurement_unit_modal: false,
      // statuses : [
      //   {
      //     value : 1,
      //     label : 'Active',
      //   },
      //     {
      //     value : 0,
      //     label : 'In Active',
      //   }
      // ],
      msg: '',
      show_msg: false,
      is_taxable: (this.product_data == null) ? false : (this.product_data.tax_code_id > 0) ? true : false,
      measurement_categories: (this.measurement_categories_data == null) ? '' : this.measurement_categories_data,
      measurements:
          this.measurements_data == null ? [] : this.measurements_data,
      measurement_category:
          this.measurement_category_id == null ? 0 : this.measurement_category_id,
      ingredient_placeholder: this.$t("Please enter ingredient name"),
      product_code_placeholder: this.$t("Please enter product code"),
      barcode_placeholder: this.$t("Please enter barcode"),
      purchase_price_placeholder: this.$t("Please enter purchase price excluding tax"),
      sale_price_placeholder: this.$t("Please enter sale price excluding tax"),
      quantity_placeholder: this.$t("Please enter quantity"),
      alert_quantity_placeholder: this.$t("Please enter stock alert quantity"),
      description_placeholder: this.$t("Enter description"),
      ingredient_added: this.$t("Ingredient Added Successfully"),
      success: this.$t("SUCCESS"),
      /*tax_code_id : (this.product_data == null || this.product_data.tax_code_id == 0)? this.store_tax_id :this.product_data.tax_code_id,*/

    }
  },
  props: {
    statuses: [Array, Object],
    suppliers: [Array, Object],
    main_categories: [Array, Object],
    // categories: [Array, Object],
    taxcodes: [Array, Object],
    discount_codes: [Array, Object],
    product_data: [Array, Object],
    stock_transfer_data: [Array, Object],
    stock_transfer_product_data: [Array, Object],
    measurement_data: [Array, Object],
    brands: [Array, Object],
    store_tax_slack: String,
    product_list: Array,
    category_list: Array,
    measurement_categories_data: [Array, Object],
    category_id: Number,
    main_category_id: Number,
    subcategories: [Array, Object],
    measurement_category_id: [Array, Object],
    measurements_data: [Array, Object],
    store_tax_id: Number,
    tax_code: Number,
  },
  mounted() {
    console.log('Add ingredient page loaded');
    console.log(this.product_data);
  },
  created() {
    this.set_product_quantity_validation();
    this.update_ingredient_list(this.product_ingredient_list);
  },
  watch: {
    // inventory_type : function(val){
    //     this.is_ingredient = (val == 2) ? true : false;
    // }
  },
  methods: {
    refreshMeasurementCategoryDetails(details){
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      this.add_measurement_category_modal = false;
      axios
          .post("/api/list_measurement_category_for_product", formData)
          .then((response) => {
            if(response.data.length>0)
            {
              this.measurement_categories = response.data;
            }
          })
          .catch((error)=>{
            console.log(error);
          });
    },
    refreshMeasurementDetails(details) {
      var formData = new FormData();
      this.add_measurement_unit_modal = false;
      formData.append("access_token", window.settings.access_token);
      axios
          .post("/api/list_measurement_for_product", formData)
          .then((response) => {
            if (response.data.length > 0) {
              this.measurements = response.data;
            }
          })
          .catch((error) => {
            console.log(error);
          });
    },
    submit_form() {

      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function () {

            this.processing = true;
            var formData = new FormData();

            // for( var i = 0; i < this.$refs.product_image.files.length; i++ ){
            //     let file = this.$refs.product_image.files[i];
            //     formData.append('product_images[' + i + ']', file);
            // }

            formData.append("access_token", window.settings.access_token);
            formData.append("product_name", (this.product_name == null) ? '' : this.product_name);
            formData.append("product_code", (this.product_code == null) ? '' : this.product_code);
            formData.append("supplier", (this.supplier == null) ? '' : this.supplier);
            formData.append("main_category", (this.main_category == null) ? '' : this.main_category);
            formData.append("category", (this.category == null) ? '' : this.category);
            formData.append("is_taxable", (this.is_taxable == null) ? '' : this.is_taxable);
            formData.append("tax_code", (this.tax_code_id == null) ? '' : this.tax_code_id);
            formData.append("discount_code", (this.discount_code == null) ? '' : this.discount_code);
            formData.append("status", (this.status == null) ? '' : this.status);
            formData.append("product_manufacturer_date", (this.product_manufacturer_date == null) ? '' : this.product_manufacturer_date);
            formData.append("product_expiry_date", (this.product_expiry_date == null) ? '' : this.product_expiry_date);

            formData.append("shows_in", (this.shows_in == null) ? '' : this.shows_in);
            formData.append("quantity", (this.quantity == null) ? '' : this.quantity);
            formData.append("alert_quantity", (this.alert_quantity == null) ? '' : this.alert_quantity);
            formData.append("sale_price", (this.sale_price == null) ? '' : this.sale_price);
            formData.append("purchase_price", (this.purchase_price == null) ? '' : this.purchase_price);
            formData.append("description", (this.description == null) ? '' : this.description);
            formData.append("is_ingredient", (this.is_ingredient == true) ? 1 : 0);
            formData.append("ingredients", (this.is_ingredient == false) ? JSON.stringify(this.ingredients) : []);
            formData.append("is_ingredient_price", (this.is_ingredient_price == true) ? 1 : 0);
            formData.append("stock_transfer_product_slack", (this.stock_transfer_product_data == null) ? '' : this.stock_transfer_product_data.slack);
            formData.append('product_thumb_image', (this.$refs.product_thumb_image.files.length > 0) ? this.$refs.product_thumb_image.files[0] : null);
            formData.append(
                "barcode",
                this.barcode == null ? "" : this.barcode
            );
            formData.append("brand_id", (this.brand == null) ? '' : this.brand);
            formData.append("measurement_id", (this.measurement == null) ? '' : this.measurement);

            axios.post(this.api_link, formData).then((response) => {
              if (response.data.status_code == 200) {
                this.show_response_message(response.data.msg, this.success);

                if (typeof response.data.link != 'undefined' && response.data.link != "") {

                  if (response.data.new_tab == true) {
                    window.open(response.data.link, '_blank');
                  } else {
                    window.location.href = response.data.link;
                  }

                  this.show_msg = true;
                  this.msg = this.ingredient_added;

                  setTimeout(function () {
                    location.reload();
                  }, 1000);

                } else {

                  this.show_msg = true;
                  this.msg = this.ingredient_added;
                  setTimeout(function () {
                    location.reload();
                  }, 1000);
                }

              } else {
                this.show_modal = false;
                this.processing = false;
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.loop_api_errors(error_json);
                } catch (err) {
                  this.server_errors = response.data.msg;
                }
                this.error_class = 'error';
              }
            })
                .catch((error) => {
                  console.log(error);
                });

          });
          this.$on("close", function () {
            this.show_modal = false;
          });
        }
      });
    },
    addMeasurementCategory() {
      this.add_measurement_category_modal = true;
    },
    set_product_quantity_validation() {
      if (typeof this.stock_transfer_product_slack != 'undefined' && this.stock_transfer_product_slack != '') {
        this.quantity_validate = {
          required: true,
          decimal: true,
          min_value: 0.01,
          max_value: this.stock_transfer_max_quantity
        }
      }
    },

    remove_image(image_slack) {
      if (image_slack != '') {

        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        formData.append("image_slack", image_slack);

        axios.post('/api/delete_product_image', formData).then((response) => {
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, this.success);
            setTimeout(function () {
              location.reload();
            }, 1000);
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = 'error';
          }
        })
            .catch((error) => {
              console.log(error);
            });
      }
    },

    open_image(image_link) {
      window.open(image_link, '_blank');
    },

    load_ingredients(keywords) {
      if (typeof keywords != 'undefined') {
        if (keywords.length > 0) {

          var formData = new FormData();
          formData.append("access_token", window.settings.access_token);
          formData.append("keywords", keywords);

          axios.post('/api/load_ingredients', formData).then((response) => {
            if (response.data.status_code == 200) {
              this.ingredient_list = response.data.data;
            }
          })
              .catch((error) => {
                console.log(error);
              });
        }
      }
    },

    add_ingredient_to_list(item) {

      if (item.slack != '') {
        var current_ingredient = {
          ingredient_slack: item.slack,
          name: item.name,
          quantity: 1,
          sale_price: item.sale_amount_excluding_tax,
          purchase_price: item.purchase_amount_excluding_tax,
          unit_slack: ''
        };
      }

      var item_found = false;
      for (var i = 0; i < this.ingredients.length; i++) {
        if (this.ingredients[i].ingredient_slack == item.slack) {
          this.ingredients[i].quantity++;
          item_found = true;
        }
      }

      if (this.ingredients[0].name == '' && this.ingredients[0].quantity == '') {
        this.$set(this.ingredients, 0, current_ingredient);
      } else {
        if (item_found == false) {
          this.ingredients.push(current_ingredient);
        }
      }
      this.ingredient_list = [];
      this.update_ingredient_prices();
    },

    remove_ingredient(index) {
      this.ingredients.splice(index, 1);
      if (index == 0) {
        this.update_ingredient_list();
      }
    },

    update_ingredient_list(ingredient_list) {
      if (ingredient_list != null && ingredient_list.length > 0) {
        this.ingredients = [];
        for (let i = 0; i < ingredient_list.length; i++) {
          var individual_product = {
            ingredient_slack: ingredient_list[i].ingredient_product.slack,
            name: ingredient_list[i].ingredient_product.name,
            quantity: ingredient_list[i].quantity,
            sale_price: ingredient_list[i].ingredient_product.sale_amount_excluding_tax,
            purchase_price: ingredient_list[i].ingredient_product.purchase_amount_excluding_tax,
            unit_slack: (ingredient_list[i].measurement == null) ? '' : ingredient_list[i].measurement.slack
          };
          this.ingredients.push(individual_product);
        }
      } else {
        this.ingredients = [];
        this.ingredients.push(this.ingredient_template);
      }
      this.update_ingredient_prices();
    },

    update_ingredient_prices() {

      this.ingredient_purchase_price = 0.00;
      this.ingredient_selling_price = 0.00;

      for (let i = 0; i < this.ingredients.length; i++) {
        var ingredient_data = this.ingredients[i];
        if (ingredient_data.quantity != "") {

          var quantity = parseFloat(ingredient_data.quantity);
          if (!isNaN(quantity)) {
            var selling_price = parseFloat(quantity) * parseFloat(ingredient_data.sale_price);
            var purchase_price = parseFloat(quantity) * parseFloat(ingredient_data.purchase_price);
            this.ingredient_selling_price += parseFloat(selling_price);
            this.ingredient_purchase_price += parseFloat(purchase_price);
          }
        }
      }
      this.ingredient_selling_price = this.ingredient_selling_price.toFixed(2);
      this.ingredient_purchase_price = this.ingredient_purchase_price.toFixed(2);
    },

    loadSubcategories() {

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("catetgory_id", $('#main_category_id').val());

      axios.post('/api/load_subcategories', formData).then((response) => {
        this.categories = response.data;
      })
          .catch((error) => {
            console.log(error);
          });

    },
    addSupplier() {
      this.add_supplier_modal = true;
    },
    addCategory() {
      this.add_category_modal = true;
    },
    addMainCategory() {
      this.add_maincategory_modal = true;
    },
    addTax() {
      this.add_tax_modal = true;
    },
    addDiscount() {
      this.add_discount_modal = true;
    },
    addBrand() {
      this.add_brand_modal = true;
    },
    addMeasurementUnit() {
      this.add_measurement_unit_modal = true;
    },
    refreshSupplier(supplier_list) {
      // refresh the data in the dropdown once the new data has been added
      this.suppliers = supplier_list;
      this.add_supplier_modal = false;
    },
    refreshMainCategory(main_category_list) {
      // refresh the data in the dropdown once the new data has been added
      this.main_categories = main_category_list;
      this.add_maincategory_modal = false;
    },
    closeCategoryModal() {
      this.add_category_modal = false;
      this.loadSubcategories();
    },
    refreshTaxCodes(taxcode_list) {
      // refresh the data in the dropdown once the new data has been added
      this.taxcodes = taxcode_list;
      this.add_tax_modal = false;
    },
    refreshDiscountCodes(discount_list) {
      // refresh the data in the dropdown once the new data has been added
      this.discount_codes = discount_list;
      this.add_discount_modal = false;
    },
    refreshBrands(brand_list) {
      // refresh the data in the dropdown once the new data has been added
      this.brands = brand_list;
      this.add_brand_modal = false;
    },
    generate_product_barcode() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);

      axios
          .post("/api/generate_product_barcode", formData)
          .then((response) => {
            this.barcode = response.data;
          })
          .catch((error) => {
            console.log(error);
          });
    },
    refreshMeasurements(measurement_list) {
      // refresh the data in the dropdown once the new data has been added
      this.measurement_units = measurement_list;
      this.add_measurement_unit_modal = false;
    },
    loadMeasurements(measurement_category) {
      if(measurement_category=="")
      {
        this.refreshMeasurementDetails();
      }
      else
      {
        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        formData.append("category_id", measurement_category);

        axios
            .post("/api/load_measurements", formData)
            .then((response) => {
              this.measurements = response.data.data;
              if (this.measurements.length == 0) {
                this.measurement = "";
              }
            })
            .catch((error) => {
              console.log(error);
            });
      }
    },

  }
}
</script>
