<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <div v-if="typeof stock_transfer_product_slack == 'undefined' || stock_transfer_product_slack == ''">
              <span class="text-title" v-if="product_slack == '' && product_clone == 0">{{ $t("Add Product")}}</span>
              <span class="text-title" v-if="product_slack !== ''  && product_clone == 1">{{ $t("Clone Product")}}</span>
              <span class="text-title" v-if="product_slack !== '' && product_clone == 0">{{ $t("Edit Product") }}</span>
            </div>
            <div v-else>
              <span class="text-title">{{ $t("Add Stock Transfer Product") }}</span>
            </div>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div
          v-if=" typeof stock_transfer_product_slack != 'undefined' && stock_transfer_product_slack != '' ">
          <div class="d-flex flex-wrap mb-1">
            <div class="mr-auto">
              <span class="text-subhead">{{
                $t("Stock Transfer Information")
              }}</span>
            </div>
            <div class=""></div>
          </div>
          <div class="form-row mb-3 border-bottom">
            <div class="form-group col-md-3">
              <label for="stock_transfer_product_code">{{
                $t("Source Store Product Code")
              }}</label>
              <p>{{ stock_transfer_product.product_code }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_product_name">{{
                $t("Source Store Product Name")
              }}</label>
              <p>{{ stock_transfer_product.product_name }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_quantity">{{
                $t("Transferred Quantity")
              }}</label>
              <p>{{ stock_transfer_product.quantity }}</p>
            </div>
            <div class="form-group col-md-3">
              <label for="stock_transfer_quantity">{{
                $t("Current Status")
              }}</label>
              <p>
                <span v-bind:class="stock_transfer_product.status.color">{{
                  stock_transfer_product.status.label
                }}</span>
              </p>
            </div>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t("Product Information") }}  </span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="name">{{ $t("Name") }} <span class="text-danger">*</span>  </label>
            <input
              type="text"
              name="name"
              v-model="product_name"
              v-validate="'required|max:50'"
              class="form-control form-control-custom"
              :placeholder="enter_product_placeholder"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('name') }">{{
              errors.first("name")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="name">{{ $t("Arabic Name") }}</label>
            <input
              type="text"
              name="arabic_product_name"
              v-model="arabic_product_name"
              v-validate="'max:50'"
              class="form-control form-control-custom"
              :placeholder="enter_product_placeholder"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('arabic_product_name') }">{{
              errors.first("arabic_product_name")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="product_code">{{ $t("Product Code") }}</label>
            <input
              type="text"
              name="product_code"
              v-model="product_code"
              v-validate="'alpha_dash|max:30'"
              class="form-control form-control-custom"
              :placeholder="search_product_code_placeholder"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('product_code') }">{{
              errors.first("product_code")
            }}</span>
          </div>
          
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="status"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }} </option>
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

          <div class="form-group col-md-3">
            <label for="main_category"
              >{{ $t("Category") }} <span class="text-danger ">*</span>
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
              <option value="">{{ $t("Choose Category..") }} </option>
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
              <option value="3">{{ $t("Both (POS & Invoice)") }} </option>
            </select>
            <span v-bind:class="{ error: errors.has('shows_in') }">{{
              errors.first("shows_in")
            }}</span>
          </div>

          
          <div v-if="shows_in != 0" class="form-group col-md-3">
              <label for="description"> {{ $t("Show Description In") }}
                </label>
                <br/>

            <select
              name="show_description_in"
              v-model="show_description_in"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="" selected>{{ $t("Choose Show Description ..") }}</option>
              <option v-if="shows_in == 1 || shows_in == 3" value="1">{{ $t("Show in Bill") }}</option>
              <option v-if="shows_in == 1 || shows_in == 3" value="2">{{ $t("Show in Cashier Screen") }}</option>
              <option v-if="shows_in == 1" value="3">{{ $t("Show in Both") }}</option>            
              <option v-if="shows_in == 2 || shows_in == 3" value="4">{{ $t("Show in Invoice") }} </option>
              <option v-if="shows_in == 3"  value="5">{{ $t("Show in Bill and Invoice") }} </option>
              <option v-if="shows_in == 3" value="6">{{ $t("Show in All") }} </option>
              <option value="0">{{ $t("Don't Show Anywhere") }}</option>
            </select>
              <span v-bind:class="{ error: errors.has('show_description_in') }">
              {{ errors.first("show_description_in") }}
              </span>

          </div>

          <div class="form-group col-md-3">
            <label for="supplier"
              >{{ $t("Supplier") }}
              <button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addSupplier()"
              >
                +
              </button></label
            >
            <select
              name="supplier"
              v-model="supplier"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Supplier..") }}</option>
              <option
                v-for="(supplier, index) in suppliers"
                v-bind:value="supplier.slack"
                v-bind:key="index"
              >
                {{ supplier.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('supplier') }">{{
              errors.first("supplier")
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
          <div class="form-group col-md-3">
            <label for="description">{{ $t("Description") }}</label>
            <textarea
              type="text"
              name="description"
              v-model="description"
              class="form-control form-control-custom"
              :placeholder="enter_product_placeholder"
              v-validate="'max:250'"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('description') }">{{
              errors.first("description")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="description_ar">{{ $t("Description (Arabic)") }}</label>
            <textarea
              type="text"
              name="description_ar"
              v-model="description_ar"
              v-validate="'max:250'"
              class="form-control form-control-custom"
              :placeholder="enter_product_description_placeholder"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('description_ar') }">{{
              errors.first("description_ar")
            }}</span>
          </div>

        </div>

        <div class="form-row mb-2">
          <!-- <div class="form-group col-md-3">
                        <label for="inventory_type">{{ $t("Inventory Type") }}</label>
                        <select name="inventory_type" v-model="inventory_type" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="" selected>Choose Inventory Type..</option>
                            <option value="1">Inventory</option>
                            <option value="2">Raw Material</option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('inventory_type') }">{{ errors.first('inventory_type') }}</span>
                    </div> -->
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{
              $t("Price and Inventory Information")
            }}</span>
          </div>
          <div class=""></div>
        </div>
        <div class="form-row mb-2">
          
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Price Type") }}</label>
            <select name="price_type" v-model="price_type" class="form-control form-control-custom custom-select" >
              <option value="fixed" >{{ $t('Fixed Price') }}</option>
              <option value="open" >{{ $t('Open Price') }}</option>
            </select>
          </div>
          
          <div class="form-group col-md-3">
              <label for="tax_code"> {{ $t("Choose Tax") }}
                <label v-if="store_tax_percentage_amt == 15.00" for="is_tobacco_tax" style="margin:0px;">
                  <input type="checkbox" id="is_tobacco_tax" v-model="is_tobacco_tax" style="margin-left:160px;"/>
                  {{ $t("Tobacco Tax") }}
                </label>
              </label>
              <!-- <label for="is_taxable"><input type="checkbox" id="is_taxable" v-model="is_taxable" > Is Taxable</label> -->
              <select name="tax_code" id="tax_code_id" v-model="tax_code_id" v-validate="'required'" class="form-control form-control-custom custom-select">
                  <!-- <option v-for="(taxcode, index) in taxcodes"  v-bind:value="taxcode.id" v-bind:key="index">
                        {{ taxcode.tax_code }} - {{ taxcode.label }} </option> -->
                <option v-for="(code, code_index) in taxcodes" :key="code_index" :value="code.id" :data-percent="code.total_tax_percentage">
                  {{ code.label }}
                </option>
              </select> 
            
          </div>
          <div class="form-group col-md-3">
            
          
            <label for="purchase_price">{{ $t("Purchase Price Excluding Tax") }} ({{currency_code}}) <span class="text-danger">*</span></label>
              
            <input type="text" name="purchase_price" v-model="purchase_price"
              v-validate="'required|decimal'" class="form-control form-control-custom"
              :placeholder="purchase_price_excluding_tax_placeholder" autocomplete="off" step="0.01" min="0"
            />
            <span v-bind:class="{ error: errors.has('purchase_price') }">{{ errors.first("purchase_price")}}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="status"
              >{{ $t("Quantity") }} <span class="text-danger">*</span>
              <label for="unlimited_quantity" style="margin:0px;"
                ><input
                  type="checkbox"
                  id="unlimited_quantity"
                  v-model="unlimited_quantity"
                  style="margin-left:160px;"
                />
                {{ $t("Unlimited Quantity") }}
              </label></label
            >
            <input
              v-if="!unlimited_quantity"
              type="number"
              name="quantity"
              v-model="quantity"
              v-validate="quantity_validate"
              @keypress="formatDecimal"
              class="form-control form-control-custom"
              :placeholder="quantity_placeholder"
              autocomplete="off"
              step="0.01"
              min="0"
            />
            <span
              v-if="!unlimited_quantity"
              v-bind:class="{ error: errors.has('quantity') }"
              >{{ errors.first("quantity") }}</span
            >
          </div>
          <div class="form-group col-md-3" v-if="!unlimited_quantity">
            <label for="status">{{ $t("Stock Alert Quantity") }}</label>
            <input
              type="number"
              name="alert_quantity"
              v-model="alert_quantity"
              v-validate="'decimal'"
              class="form-control form-control-custom"
              :placeholder="search_stock_alert_placeholder"
              autocomplete="off"
              step="0.01"
              min="0"
            />
            <span v-bind:class="{ error: errors.has('alert_quantity') }">{{ errors.first("alert_quantity")}}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t("Discount") }}
              <button type="button" class="btn btn-sm btn-secondary" @click="addDiscount()" >+</button>
            </label>
            <select
              name="discount_code"
              v-model="discount_code"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Discount..") }}</option>
              <option
                v-for="(discount_code, index) in discount_codes"
                v-bind:value="discount_code.id"
                v-bind:key="index"
              >
                {{ discount_code.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('discount_code') }">{{
              errors.first("discount_code")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          

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
              <!--    <option value="0" selected="">DEFAULT</option> -->
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
          <div class="form-group col-md-3">
            <label for="measurement">{{ $t("Measurement") }}<button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addMeasurementUnit()"
              >
                +
              </button></label>
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

          <div class="form-group col-md-3">
            <label for="product_manufacturer_date">{{
              $t("Product Manufacturer Date")
            }}</label>
            <input
              type="date"
              v-model="product_manufacturer_date"
              class="form-control form-control-custom"
            />
            <span
              v-bind:class="{ error: errors.has('product_manufacturer_date') }"
              >{{ errors.first("product_manufacturer_date") }}</span
            >
          </div>

          <div class="form-group col-md-3">
            <label for="product_expiry_date">{{
              $t("Product Expiry Date")
            }}</label>
            <input
              type="date"
              v-model="product_expiry_date"
              class="form-control form-control-custom"
            />
            <span v-bind:class="{ error: errors.has('product_expiry_date') }">{{
              errors.first("product_expiry_date")
            }}</span>
          </div>
       
                   
        </div>
        <div class="form-row mb-2">
          
          <div class="form-group col-md-3">
            <label for="brand">
              {{ $t("Brand") }}
              <button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addBrand()"
              >
                +
              </button></label
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
          

          <div class="form-group col-md-3">
            <label for="product_thumb_image">{{
              $t("Product Thumb Image") + " (jpeg, jpg, png, webp)"
            }}</label>
            <input
              type="file"
              class="form-control-file form-control form-control-custom file-input"
              name="product_thumb_image"
              ref="product_thumb_image"
              accept="image/x-png,image/jpeg,image/webp"
              v-validate="'ext:jpg,jpeg,png,webp|size:3000'"
            />
            <small class="form-text text-muted mb-1">
              {{ $t("Allowed file size per file is 3 MB") }}</small
            >
            <span v-bind:class="{ error: errors.has('product_thumb_image') }">{{
              errors.first("product_thumb_image")
            }}</span>
          </div>


          <div
            class="form-group col-md-6"
            v-show="show_zid_sync_option && zid_status"
          >
            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                class="custom-control-input"
                id="zid_sync_option"
                v-model="zid_sync_option"
              />
              <label class="custom-control-label" for="zid_sync_option">{{
                $t("Add to Zid")
              }}</label>
              <small class="form-text text-muted"
                >{{
                  $t(
                    "If this option is enabled, product will be also added on ZID platform simultaneously."
                  )
                }}
              </small>
            </div>
          </div>
          </div>

          <div class="d-flex flex-wrap mb-1">
            <div class="mr-auto">
              <span class="text-subhead">{{
                $t("Select Modifiers and Stores")
              }}</span>
            </div>
            <div class=""></div>
          </div>
          

        
        <div class="form-row mb-2">
          <div class="form-group col-md-2 p-1">
            <label for="modifier"> {{ $t("Choose Modifier") }}</label>

            <input
              type="search"
              class="form-control mb-2"
              placeholder="Search"
              id="search_box_for_modifier"
              v-on:input="filterModifiers()"
            />
            <div
              style="overflow-y:scroll;"
              id="modifier_list"
              @click="addToModifiersList"
            ></div>

            <span v-bind:class="{ error: errors.has('modifier') }">{{
              errors.first("modifier")
            }}</span>
          </div>

          <div
            class="col-md-4 form-group p-1"
            id="stores_section"
            :style="product_data==null ? 'display:block;' : 'display:none;'"
          >
            <label for="stores_list">{{ $t("Choose Stores") }}</label>
            <input
              type="search"
              class="form-control mb-2"
              placeholder="Search"
              id="search_box_for_stores"
              v-on:input="filterStores()"
            />
            <div
              style="height:300px;overflow-y:scroll;"
              id="store_list"
              @click="addToStoresList"
              @change="addToStoresList"
            ></div>

            <div class="d-flex align-items-center justify-content-between">
              <button
                class="btn btn-primary mr-2"
                id="cancelproductsbtn"
                @click="cancelStoresList"
              >
                {{ $t("Deselect All") }}
              </button>
              <button
                class="btn btn-primary ml-2"
                id="serlectallproductsbtn"
                @click="addAllStores"
              >
                {{ $t("Select All") }}
              </button>
            </div>
          </div>
          <div class="col-md-6 form-group p-1">
            <label for="modifier"> {{ $t("Enter Price Variants") }}</label>
            <br>  
            <table class="table table-striped table-condensed table-bordered">
              <thead>
                <tr>
                  <td> {{ $t("Service") }} </td>
                  <td> {{ $t("Sale Price Including Tax") }} 
                      <span v-if="is_ingredient_price == true">{{ $t(" *For enable edit uncheck 'Set Product Price as Ingredient Cost' ") }}</span>
                      <input type="checkbox" id="sale_price_including_tax_checkbox" v-model="sale_price_including_tax_checkbox" style="margin-left:9px;"/>
                  </td>
                  <td> {{ $t("Sale Price Excluding Tax") }} </td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td> <span class="fw-bold"> Default </span></td>
                  <td>
                    <input v-if="sale_price_including_tax_checkbox" id="sale_price_including_tax" type="text" name="sale_price_including_tax" v-model="sale_price_including_tax" v-validate="quantity_validate" class="form-control form-control-custom" :placeholder="search_sale_price_including_tax_placeholder" autocomplete="off" step="1" min="0" v-on:change="setTaxPercentage(tax_code_id)"/>
                    <span v-if="sale_price_including_tax_checkbox" v-bind:class="{ error: errors.has('sale_price_including_tax') }">
                    {{ errors.first("sale_price_including_tax") }} </span>
                  </td>
                  <td> 
                    <input type="text" name="sale_price" v-model="sale_price" id="sale_price" v-validate=" price_type == 'fixed' ? 'required|decimal' : 'decimal' " class="form-control form-control-custom" :placeholder="sale_price_excluding_tax_placeholder" autocomplete="off" step="0.01" min="0" :disabled="sale_price_including_tax_checkbox"/>
                    <span v-bind:class="{ error: errors.has('sale_price') }">{{errors.first("sale_price")}}</span>
                    <span v-if="is_ingredient_price == true">{{ $t(" *For enable edit uncheck 'Set Product Price as Ingredient Cost' ") }}</span>
                  </td>
                </tr>
                <tr v-for="(price,index) in product_prices" :key="index">
                  <td> {{ $t(price.name) }} </td>
                  <td> <input @input="calc_product_variant_price" v-if="sale_price_including_tax_checkbox" v-model="price.sale_price_including_tax" class="form-control form-control-custom">  </td>
                  <td> <input @input="calc_product_variant_price" v-model="price.sale_price_excluding_tax" class="form-control form-control-custom" step="0.01" min="0" :disabled="sale_price_including_tax_checkbox">  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
          <div class="form-group col-md-12">
            <div class="col-md-12">
              <hr />
            </div>
            <div class="form-row mb-2">
              <div class="form-group col-md-4">
                <label for="barcode"
                  >{{ $t("Search and Add Ingredients") }}
                </label>
                <select
                  name="search_ingredients"
                  v-model="search_ingredients"
                  class="form-control form-control-custom custom-select"
                  @change="add_ingredient_to_list(search_ingredients)"
                >
                  <option value="">{{ $t("Choose Ingredient..") }} </option>
                  <option
                    v-for="(ingredient, index) in ingredient_list"
                    v-bind:value="ingredient.slack"
                    v-bind:key="index"
                  >
                    {{ ingredient.name }}
                  </option>
                </select>
                <!-- <cool-select type="text" v-model="search_ingredients" autocomplete="off" inputForTextClass="form-control form-control-custom" :items="ingredient_list" item-text="name" itemValue='name' :resetSearchOnBlur="false" disable-filtering-by-search @search='load_ingredients' @select='add_ingredient_to_list' :placeholder="search_typing_placeholder">
                                     <template #item="{ item }">
                                        <div class='d-flex justify-content-start'>
                                        <div>
                                            {{ item.product_code }} - {{ item.name }}
                                        </div>
                                        </div>
                                    </template>
                                </cool-select> -->
                <small class="form-text text-muted"
                  >{{
                    $t(
                      "Choose ingredients for preparing 1 Unit or Quantity of the product"
                    )
                  }}
                </small>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4 mb-1">
                <label for="name">{{ $t("Name & Description") }}</label>
              </div>
              <div class="form-group col-md-2 mb-1">
                <label for="purchase_price">{{
                  $t("Purchase Price of 1 Unit")
                }}</label>
              </div>
              <div class="form-group col-md-2 mb-1">
                <label for="sale_price">{{ $t("Sale Price of 1 Unit") }}</label>
              </div>
              <div class="form-group col-md-1 mb-1">
                <label for="quantity">{{ $t("Quantity") }}</label>
              </div>
              <div class="form-group col-md-2 mb-1">
                <label for="measurement_unit">{{ $t("Measuring Unit") }}</label>
              </div>
            </div>

            <div
              class="form-row mb-2"
              v-for="(ingredient, index) in ingredients"
              :key="index"
            >
              <div class="form-group col-md-4">
                <input
                  type="text"
                  v-bind:name="'ingredient.name_' + index"
                  v-model="ingredient.name"
                  v-validate="'max:250'"
                  data-vv-as="Name"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly="true"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.name_' + index),
                  }"
                  >{{ errors.first("ingredient.name_" + index) }}</span
                >
              </div>
              <div class="form-group col-md-2">
                <input
                  type="number"
                  v-bind:name="'ingredient.purchase_price_' + index"
                  v-model="ingredient.purchase_price"
                  data-vv-as="Purchase Price"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly="true"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.purchase_price_' + index),
                  }"
                  >{{
                    errors.first("ingredient.purchase_price_" + index)
                  }}</span
                >
              </div>
              <div class="form-group col-md-2">
                <input
                  type="number"
                  v-bind:name="'ingredient.sale_price_' + index"
                  v-model="ingredient.sale_price"
                  data-vv-as="Sale Price"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly="true"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.sale_price_' + index),
                  }"
                  >{{ errors.first("ingredient.sale_price_" + index) }}</span
                >
              </div>
              <div class="form-group col-md-1">
                <input
                  type="text"
                  v-bind:name="'ingredient.quantity_' + index"
                  :value="ingredient.quantity"
                  @input="ingredient.quantity = $event.target.value"
                  v-validate="
                    ingredient.name != '' ? 'required|decimal' : ''
                  "
                  data-vv-as="Quantity"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  step="0.01"
                  min="0.01"
                  v-on:change="calculateIngredientCost"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.quantity_' + index),
                  }"
                  >{{ errors.first("ingredient.quantity_" + index) }}</span
                >
              </div>
              <div class="form-group col-md-2">
                <select
                  v-bind:name="'ingredient.measurement_id_' + index"
                  v-model="ingredient.measurement_id"
                  v-validate="ingredient.measurement_id != '' ? 'required' : ''"
                  class="form-control form-control-custom custom-select ingredient_measurement"
                  @change="calculateIngredientCost"
                >
                  <option value="">{{
                    $t("Choose Measurement Unit..")
                  }}</option>
                  <option
                    v-for="(measurement,
                    measurement_index) in ingredient.measurements"
                    v-bind:value="measurement.id"
                    v-bind:key="measurement_index"
                  >
                    {{ measurement.label }}
                  </option>
                </select>
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.measurement_id_' + index),
                  }"
                  >{{
                    errors.first("ingredient.measurement_id_" + index)
                  }}</span
                >
              </div>
              <div class="form-group col-md-1">
                <button
                  type="button"
                  class="btn btn-outline-danger"
                  @click="remove_ingredient(index)"
                >
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>

            <div class="form-row mb-2">
              <div class="form-group col-md-3">
                <label for="description">{{
                  $t("Total Ingredient Purchase Price")
                }}</label>
                <p>{{ currency_code }} {{ ingredient_purchase_price }}</p>
              </div>
              <div class="form-group col-md-3">
                <label for="description">{{
                  $t("Total Ingredient Selling Price")
                }}</label>
                <p>{{ currency_code }} {{ ingredient_selling_price }}</p>
              </div>
            </div>
            <div class="form-row mb-2">
              <div class="form-group col-md-6">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    id="is_ingredient_price"
                    v-model="is_ingredient_price"
                    v-on:change="setProductPriceAsIngredientPrice"
                  />
                  <label
                    class="custom-control-label"
                    for="is_ingredient_price"
                    >{{ $t("Set Product Price as Ingredient Cost") }}</label
                  >
                  <small class="form-text text-muted"
                    >{{
                      $t(
                        "If this option is enabled, product sale price and purchase price will be updated with ingredient cost."
                      )
                    }}
                  </small>
                  <label class="text-danger d-none" id="is_ingredient_price_error" for="is_ingredient_price">
                    {{ $t("Please select ingredient(s) with measurement") }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        

        

        <div class="flex-wrap mb-4">
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
        <p v-if="status == 0">{{ $t("Product status is inactive.") }}</p>
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

    <!-- Add Supplier Modal -->
    <modalcomponent
      v-if="add_supplier_modal"
      v-on:close="add_supplier_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add New Supplier") }}
      </template>
      <template v-slot:modal-body>
        <addsuppliercomponent
          :statuses="statuses"
          :country_list="country_list"
          :reload_on_submit="false"
          @refreshSupplier="refreshSupplier"
        ></addsuppliercomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add Main Category Modal -->
    <modalcomponent
      v-if="add_maincategory_modal"
      v-on:close="add_maincategory_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add New Category") }}
      </template>
      <template v-slot:modal-body>
        <addmaincategorycomponent
          :statuses="statuses"
          :reload_on_submit="false"
          :stores="stores"
          @refreshMainCategory="refreshMainCategory"
        ></addmaincategorycomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add Category Modal -->
    <modalcomponent
      v-if="add_category_modal"
      v-on:close="add_category_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add New Category") }}
      </template>
      <template v-slot:modal-body>
        <addcategorycomponent
          :statuses="statuses"
          :main_categories="main_categories"
          :store_data="stores"
          :stores="store_data"
          :reload_on_submit="false"
          @closeCategoryModal="closeCategoryModal"
          :main_category_id="main_category"
          :category_data="main_category.subCategories"
        ></addcategorycomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add Discount Code Modal -->
    <modalcomponent
      v-if="add_discount_modal"
      v-on:close="add_discount_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add Discount Code") }}
      </template>
      <template v-slot:modal-body>
        <adddiscountcodecomponent
          :statuses="statuses"
          :discount_code_data="null"
          :products="product_list"
          :categories="category_list"
          :reload_on_submit="false"
          @refreshDiscountCodes="refreshDiscountCodes"
        ></adddiscountcodecomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <!-- Add brand Modal -->
    <modalcomponent
      v-if="add_brand_modal"
      v-on:close="add_brand_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add Brand") }}
      </template>
      <template v-slot:modal-body>
        <addbrandcomponent
          :statuses="statuses"
          :brand_data="null"
          :reload_on_submit="false"
          @refreshBrands="refreshBrands"
        ></addbrandcomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>
    <!-- Add Measurement Category -->
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
          :measurement_categories ="measurement_categories"
          @refreshMeasurements="refreshMeasurementDetails"
        ></addmeasurementcomponent>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script type="application/javascript">
"use strict";

import { CoolSelect } from "vue-cool-select";
import "vue-cool-select/dist/themes/bootstrap.css";
import number_format from "locutus/php/strings/number_format";
import _ from 'lodash';

export default {
  
  data() {
    return {
      server_errors: "",
      tmp_quantity_list : [],
      final_quantity_list : "",
      old_discount_codes : this.discount_codes,
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,
      api_link: this.product_clone == 0 ?  this.product_data == null
          ? "/api/add_product"
          : "/api/update_product/" + this.product_data.slack : "/api/add_product",

      product_slack: this.product_data == null ? "" : this.product_data.slack,
      product_name: this.product_clone == 0 ? this.product_data == null ? "" : this.product_data.name : this.product_data.name,
      arabic_product_name: this.product_clone == 0 ?
        this.product_data == null ? "" : this.product_data.name_ar : this.product_data.name_ar,
      barcode: this.product_clone == 0 ? this.product_data == null ? "" : this.product_data.barcode : "",
      product_code: this.product_clone == 0 ?
        this.product_data == null ? "" : this.product_data.product_code : "",
      description: this.product_clone == 0 ?
        this.product_data == null ? "" : this.product_data.description : "",
      description_ar: this.product_clone == 0 ?
        this.product_data == null ? "" : this.product_data.description_ar : "",
      product_border_color:
        this.product_data == null ? "" : this.product_data.product_border_color,
      success: this.$t("SUCCESS"),
      product_manufacturer_date:
        this.product_data == null
          ? ""
          : this.product_data.product_manufacturer_date != null
          ? this.product_data.product_manufacturer_date_raw
          : "",

      product_expiry_date:
        this.product_data == null
          ? ""
          : this.product_data.product_expiry_date != null
          ? this.product_data.product_expiry_date_raw
          : "",

      supplier:
        this.product_data == null
          ? ""
          : this.product_data.supplier == null
          ? ""
          : this.product_data.supplier.slack,
      brand:
        this.product_data == null
          ? ""
          : this.product_data.brand_id == null
          ? ""
          : this.product_data.brand_id,
      measurement:
        this.product_data == null
          ? ""
          : this.product_data.measurement_id == null
          ? ""
          : this.product_data.measurement_id,
      main_category: this.main_category_id == null ? "" : this.main_category_id,
      category: this.category_id == null ? "" : this.category_id,
      tax_code_id : (this.product_data == null || this.product_data.tax_code_id == 0)? this.store_tax_id :this.product_data.tax_code_id,
      discount_code:
        this.product_data == null
          ? ""
          : this.product_data.discount_code == null
          ? ""
          : this.product_data.discount_code.id,
      quantity: this.product_data == null ? "" : this.product_data.quantity,
      alert_quantity:
        this.product_data == null ? "" : this.product_data.alert_quantity,
      sale_price: this.product_data == null ? "" : this.product_data.base_sale_amount_excluding_tax,
      purchase_price: this.product_data == null ? "" : this.product_data.purchase_amount_excluding_tax,
      status: this.product_data == null ? "1" : this.product_data.status.value,
      price_type: this.product_data == null ? "fixed" : this.product_data.price_type,
      // inventory_type  : (this.product_data == null)?'':this.product_data.inventory_type.value,
      shows_in: this.product_data == null ? 1 : this.product_data.shows_in,
      show_description_in: this.product_data == null ? 0 : this.product_data.show_description_in,
      images: this.product_data == null ? "" : this.product_data.images,
      currency_code: window.settings.currency_code,
      store_tax_percentage_amt:
        this.store_tax_percentage == null ? "0" : this.store_tax_percentage,
      stock_transfer_max_quantity:
        this.stock_transfer_data == null
          ? ""
          : this.stock_transfer_product_data.quantity,
      stock_transfer_product_slack:
        this.stock_transfer_data == null
          ? ""
          : this.stock_transfer_product_data.slack,
      stock_transfer:
        this.stock_transfer_data == null ? "" : this.stock_transfer_data,
      stock_transfer_product:
        this.stock_transfer_product_data == null
          ? ""
          : this.stock_transfer_product_data,

      quantity_validate: {
        required: true,
        decimal: true,
      },

      is_ingredient:
        this.product_data == null
          ? false
          : this.product_data.is_ingredient != null
          ? this.product_data.is_ingredient == 1
            ? true
            : false
          : false,

      // ingredient_list   : [],
      ingredient_list: this.ingredient_data == null ? [] : this.ingredient_data,
      search_ingredients: "",
      ingredient_template: {
        ingredient_slack: "",
        name: "",
        sale_price: "",
        purchase_price: "",
        quantity: "",
        measurement_id: "",
        measurements: "",
      },
      product_ingredient_list:
        this.product_data != null ? this.product_data.ingredients : [],

      ingredients: [],

      restaurant_mode: window.settings.restaurant_mode,
      ingredient_purchase_price: 0,
      ingredient_selling_price: 0,

      is_ingredient_price: this.product_data == null ? false : 
          this.product_data.is_ingredient_price != null ? this.product_data.is_ingredient_price == 1 ? true : 
          false : false,
      categories: this.subcategories == null ? [] : this.subcategories,
      add_supplier_modal: false,
      add_category_modal: false,
      add_maincategory_modal: false,
      add_tax_modal: false,
      add_discount_modal: false,
      add_measurement_category_modal: false,
      add_brand_modal: false,
      add_measurement_unit_modal: false,
      search_sale_price_including_tax_placeholder: this.$t(
        "Please enter sale price including tax"
      ),
      search_typing_placeholder: this.$t("Start Typing.."),
      search_stock_alert_placeholder: this.$t(
        "Please enter stock alert quantity"
      ),
      enter_product_placeholder: this.$t("Please enter product name"),
      enter_product_description_placeholder: this.$t(
        "Please enter product description"
      ),
      search_product_code_placeholder: this.$t("Please enter product code"),
      barcode_placeholder: this.$t("Please enter barcode"),
      purchase_price_excluding_tax_placeholder: this.$t(
        "Please enter purchase price excluding tax"
      ),
      sale_price_excluding_tax_placeholder: this.$t(
        "Please enter sale price excluding tax"
      ),
      quantity_placeholder: this.$t("Please enter quantity"),

      statuses: [
        {
          value: 1,
          label: "Active",
        },
        {
          value: 0,
          label: "In Active",
        },
      ],
      is_taxable: true,
      is_tobacco_tax: this.product_data == null ? false : this.product_data.is_tobacco_tax == 1 ? true: false,
      tobacco_tax_percentage: 100,
      unlimited_quantity:
        this.product_data == null
          ? false
          : this.product_data.quantity == "Unlimited"
          ? true
          : false,
      measurement_categories:
        this.measurement_categories_data == null
          ? ""
          : this.measurement_categories_data,
      measurements:
        this.measurements_data == null ? [] : this.measurements_data,
      measurement_category:
        this.measurement_category_id == null ? 0 : this.measurement_category_id,
      ingredient_measurements: [Array, Object],
      sale_price_including_tax_checkbox:
        this.product_data == null
          ? false
          : this.product_data.base_sale_amount_including_tax == null ||
            this.product_data.base_sale_amount_including_tax == ""
          ? false
          : true,
      sale_price_including_tax:
        this.product_data == null
          ? ""
          : this.product_data.base_sale_amount_including_tax,
      // modifier:
      // this.product_data == null
      // ? ""
      // : this.product_data.modifier_id,
      modifier:
        this.modifiers == null ? "" : this.modifiers,

      tmp_modifier_list: [],
      final_store_list: "",
      final_modifier_list:        
             this.product_modifiers_data == null ? null : this.product_modifiers_data.toString(),
      
      zid_sync_option:
        this.product_data != null
          ? this.product_data.zid_product_id != null
            ? true
            : false
          : false,
      show_zid_sync_option: this.sync_zid_product,
      prices:
        this.price_data == null ? "" : this.price_data,
      product_prices : this.product_data == null ? [] : this.product_data.product_prices == null ? [] : this.product_data.product_prices,
    };
  },
  props: {
    // statuses: [Array, Object],
    suppliers: [Array, Object],
    country_list: Array,
    main_categories: [Array, Object],
    // categories: [Array, Object],
    taxcodes: [Array, Object],
    discount_codes: [Array, Object],
    product_data: [Array, Object],
    ingredient_data: [Array, Object],
    stock_transfer_data: [Array, Object],
    stock_transfer_product_data: [Array, Object],
    measurement_categories_data: [Array, Object],
    brands: [Array, Object],
    category_id: Number,
    main_category_id: Number,
    subcategories: [Array, Object],
    store_tax_slack: String,
    store_tax_percentage: String,
    store_tax_id: Number,
    measurements_data: [Array, Object],
    measurement_category_id: [Array, Object],
    total_tax: Number,
    modifiers: [Array, Object],
    product_modifiers_data: [Array, Object],
    sync_zid_product: Boolean,
    product_details: [Array, Object],
    zid_status: Boolean,
    product_clone: Boolean,

    product_list: Array,
    category_list: Array,
    stores: [Array, Object],
    selection_stores: [Array, Object],
    store_data: [Array, Object],
    price_data : [Array,Object],
    // product_prices : []
    
  },
  mounted() {
    if(this.product_data==null)
    {
      this.openStoreList();
    }
    console.log("Add product page loaded");
    // console.log('taxcodes =',this.taxcodes);
    var data_percent = $('#tax_code_id :selected').attr('data-percent');
    this.store_tax_percentage_amt = data_percent;
    this.filterModifiers();
    // alert(this.zid_sync_option);
    // this.load_ingredients();


    if (this.product_data == null) {
      let product_details = JSON.parse(localStorage.getItem("add_product"));
      if (product_details != null) {
        this.product_name = product_details.product_name;
        this.arabic_product_name = product_details.arabic_product_name;
        this.product_code = product_details.product_code;
        this.main_category = product_details.main_category;
        this.purchase_price = product_details.purchase_price;
        this.sale_price = product_details.sale_price;
        this.quantity = product_details.quantity;
        localStorage.removeItem("add_product");
      }
    }

    if(this.product_data !=null && this.is_ingredient_price == true){
      $('#sale_price').prop('readonly', true);
    }
    
    var price_variants = [];

      if(this.prices.length){
        for (let i = 0; i < this.prices.length; i++) {

          var price = {
              'slack' : this.prices[i].slack,
              'code' : this.prices[i].price_code,
              'name' : this.prices[i].name,
              'sale_price_including_tax' : 0,
              'sale_price_excluding_tax' : 0,
          }

          if( this.product_data != null && this.product_data.product_prices.length){
            var this_price_code = this.prices[i].price_code;
            let result = _.find(this.product_data.product_prices, function(val) {
                if(val.price.price_code == this_price_code){
                  return val;
                }
            });
            if(result != undefined){
              price.sale_price_including_tax = parseFloat(result.sale_amount_including_tax);
              price.sale_price_excluding_tax = parseFloat(result.sale_amount_excluding_tax);
            }
          }
          price_variants.push(price);
        }
        this.product_prices = price_variants;
      }
    
  },
  created() {
    this.set_product_quantity_validation();

    // loading measurements for already added ingredients in product editing
    if (this.product_data.ingredients != null) {
      this.product_data.ingredients.map((item) => {
        this.load_measurements_for_ingredient(item.ingredient_product.slack);
      });

      if (this.product_ingredient_list.length > 0) {
        this.update_ingredient_list(this.product_ingredient_list);
      }
    }

  
    
  },
  watch: {
   
    sale_price_including_tax_checkbox: function(val){
      if(this.is_ingredient_price == false){
        // console.log(this.tax_code_id);
        if(this.sale_price_including_tax_checkbox == true){
          this.setTaxPercentage(this.tax_code_id);
          if(this.product_prices.length){
              for (let i = 0; i < this.product_prices.length; i++) {
                  this.product_prices[i].sale_price_including_tax = 0;
                  this.product_prices[i].sale_price_excluding_tax = 0;
              }
          }
        }else{
          this.sale_price_including_tax = 0;
        }
      }else{
        $("#sale_price_including_tax_checkbox").prop("checked", false);
        this.sale_price_including_tax_checkbox = false;
      }
    },
    is_tobacco_tax: function(val){
      // console.log(val);
      this.setTaxPercentage(this.tax_code_id);
    },
    tax_code_id : function(val){
        this.setTaxPercentage(val);
    },
    // quantity: function(val){
    //   this.filterQuantity(val);
    // }, 
  },
  methods: {
    calc_product_variant_price(){
      
      // if(this.product_data == null){
        if(this.product_prices.length){
            var tax_id = this.tax_code_id;
            for (let i = 0; i < this.product_prices.length; i++) {

                if (tax_id == 0) {
                    this.store_tax_percentage_amt = 0;
                } else {
                    var tax_percentage = this.taxcodes.filter(function (elem) {
                        if (elem.id == tax_id) return elem;
                    });
                    this.store_tax_percentage_amt = tax_percentage[0].total_tax_percentage;
                }

                if(this.sale_price_including_tax_checkbox == true){

                  if(this.is_tobacco_tax == true && this.store_tax_percentage_amt == 15.00){
                    
                    // excluding normal tax
                    let sale_price_excluding_tax = this.calculate_sale_price_excluding_tax( parseFloat(this.product_prices[i].sale_price_including_tax), parseFloat(this.store_tax_percentage_amt) );
                    
                    // excluding tobacco tax
                    sale_price_excluding_tax = this.calculate_sale_price_excluding_tax( sale_price_excluding_tax, parseFloat(this.tobacco_tax_percentage) );
                    
                    this.product_prices[i].sale_price_excluding_tax = number_format(sale_price_excluding_tax, 4, ".", "");
                    
                  }else{
              
                    // excluding normal tax
                    let sale_price_excluding_tax = this.calculate_sale_price_excluding_tax( parseFloat(this.product_prices[i].sale_price_including_tax), parseFloat(this.store_tax_percentage_amt) );
                    this.product_prices[i].sale_price_excluding_tax = number_format(sale_price_excluding_tax, 4, ".", "");

                  }
                }
            }
        }
      // }

    },
    addToStoresList(event) {
      let elements = document.querySelectorAll("[id^='chk_store_']");
      let quantities = document.querySelectorAll("[id^='store_quantity_']");

      this.tmp_store_list = [];
      this.tmp_quantity_list = [];
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_store_list.push(elements[i].id.split("_")[2]);
          if(quantities[i].value == '' || quantities[i].value == null){
            quantities[i].value = 0;
          }
          this.tmp_quantity_list.push(quantities[i].value);


        } 
      }

      this.final_store_list = [...new Set( JSON.parse(JSON.stringify(this.tmp_store_list)) )]
        .join(",")
        .replace(/(,$)/g, "");

        this.final_quantity_list  = JSON.parse(JSON.stringify(this.tmp_quantity_list));
        
        //  this.final_quantity_list = [...new Set( JSON.parse(JSON.stringify(this.tmp_quantity_list)) )]
        // .join(",")
        // .replace(/(,$)/g, "");
        // console.log(this.final_quantity_list);
        // return false;
    },
    addAllStores(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_store_']");
      let quantities = document.querySelectorAll("[id^='store_quantity_']");
      this.tmp_store_list = [];
      this.tmp_quantity_list = [];
      for (let i = 0; i < elements.length; i++) {
          elements[i].checked = true;
          this.tmp_store_list.push(elements[i].id.split("_")[2]);
          this.tmp_quantity_list.push(quantities[i].value);
      }
      

      this.final_store_list = [...new Set(this.tmp_store_list)]
        .join(",")
        .replace(/(,$)/g, "");
        this.final_quantity_list  = JSON.parse(JSON.stringify(this.tmp_quantity_list));
        
        // this.final_quantity_list = [...new Set(this.tmp_quantity_list)]
        // .join(",")
        // .replace(/(,$)/g, "");
    },
    cancelStoresList(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_store_']");
      for (let i = 0; i < elements.length; i++) {
        elements[i].checked = false;
      }
      this.tmp_store_list = [];
      this.final_store_list = "";

      this.tmp_quantity_list  = [];
      this.final_quantity_list = "";
    },
    openStoreList() {
        document.querySelector("#stores_section").style.display = "block";
        this.filterStores();
    },
    filterStores() {
      let search_value = document.querySelector("#search_box_for_stores")
        .value;
      let strHTML = `<div class="card store-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                </div>
              </div>`;
      document.querySelector("#store_list").innerHTML = strHTML;
      strHTML = `<div class="d-flex flex-row align-items-center" style="width:100%;">
                    <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                          <div>[]</div>
                    </div>
                   <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                          <div>Store Name</div>
                          </div>
                      <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                          <div>Quantity</div>
                          </div>
                 </div>`;
      let store_list = [],
        stores = [];
      if (this.selection_stores.length > 0) {
        for (let i = 0; i < this.selection_stores.length; i++) {
          if (
            search_value != "" &&
            this.selection_stores[i].text.includes(search_value)
          ) {
            store_list.push(this.selection_stores[i]);
          }
        }
        if (store_list.length > 0) {
          stores = store_list;
        } else {
          stores = this.selection_stores;
        }
        if (stores.length > 0) {
          for (let i = 0; i < stores.length; i++) {
            strHTML += `<div
                class="d-flex flex-column justify-content-start align-items-center mb-4"
                id="store_list_${stores[i].id}"
              >
                <div
                  class="card store-card"
                  id="store_${stores[i].id}"
                  style="width:100%;"
                >
                  <div
                    class="card-body p-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="d-flex flex-row align-items-center" style="width:100%;">`;
            
              strHTML += ` <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                          <input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_store_${stores[i].id}"
                      />
                      </div>`;
            strHTML += `
                      
                      <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                      <div class="store-title" style="width:100%;white-space:wrap;">
                        ${stores[i].text}
                      </div>
                      </div>
                      <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                      <div class="store-title">
                         <input type="number" id="store_quantity_${stores[i].id}" placeholder="Quantity" class="form-control" value="0" min="0"/>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;
          }
        } else {
          strHTML = `<div class="card store-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                  <div class="d-flex flex-row align-items-center">
                    <p>No Stores Found</p>
                  </div>
                </div>
              </div>`;
        }
        document.querySelector("#store_list").innerHTML = strHTML;
      }
    },

    openModifierList() {
        document.querySelector("#modifier_section").style.display = "block";
        this.filterModifiers();
    },
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
    refreshMeasurementDetails(details){
       var formData = new FormData();
       this.add_measurement_unit_modal = false;
        formData.append("access_token", window.settings.access_token);
        axios
          .post("/api/list_measurement_for_product", formData)
          .then((response) => {
              if(response.data.length>0)
              {
                this.measurements = response.data;
              }
          })
          .catch((error)=>{
            console.log(error);
          });
    },
    filterModifiers() {
      //document.querySelector("#product_list_error").innerHTML = "";

      let search_value = document.querySelector("#search_box_for_modifier")
        .value;

      let strHTML = `<div class="card category-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                </div>
              </div>`;
      document.querySelector("#modifier_list").innerHTML = strHTML;
      // console.log(this.modifier);
      strHTML = ``;
      let modifierarray =
        this.final_modifier_list != null
          ? this.final_modifier_list.split(",")
          : [];
      let modifier_list = [],
        modifiers = [];
      if (this.modifier.length > 0) {
        for (let i = 0; i < this.modifier.length; i++) {
          if (
            search_value != "" &&
            this.modifier[i].label.includes(search_value)
          ) {
            modifier_list.push(this.modifier[i]);
          }
        }
        if (modifier_list.length > 0) {
          modifiers = modifier_list;
        } else {
          modifiers = this.modifier;
        }
        if (modifiers.length > 0) {
          for (let i = 0; i < modifiers.length; i++) {
            strHTML += `<div
                class="d-flex flex-column justify-content-start align-items-center mb-4"
                id="modifier_list_${modifiers[i].id}"
              >
                <div
                  class="card category-card"
                  id="modifier_${modifiers[i].id}"
                  style="width:100%;"
                >
                  <div
                    class="card-body p-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="d-flex flex-row align-items-center">`;
            if (
               modifierarray.indexOf(String(modifiers[i].id)) != -1
            ) {
              strHTML += ` <input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_modifier_${modifiers[i].id}"
                      checked/>`;
            } else {
              strHTML += ` <input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_modifier_${modifiers[i].id}"
                      />`;
            }
            strHTML += `<div class="category-title" align="left">
                        ${modifiers[i].label}
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
                    <p>No Modifiers Found</p>
                  </div>
                </div>
              </div>`;
        }
        document.querySelector("#modifier_list").innerHTML = strHTML;
      }
    },


    addToModifiersList(event) {
      //event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_modifier_']");

       this.tmp_modifier_list = [];
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_modifier_list.push(elements[i].id.split("_")[2]);
        } else if (
          elements[i].checked == false &&
          this.tmp_modifier_list.indexOf(elements[i].id.split("_")[2])
        ) {
          this.tmp_modifier_list.splice(i, 1);
        }
      }
      this.final_modifier_list = [...new Set(this.tmp_modifier_list)]
        .join(",")
        .replace(/(,$)/g, "");
    },




    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            // for( var i = 0; i < this.$refs.product_image.files.length; i++ ){
            //     let file = this.$refs.product_image.files[i];
            //     formData.append('product_images[' + i + ']', file);
            // }

            formData.append("access_token", window.settings.access_token);
            formData.append(
              "product_name",
              this.product_name == null ? "" : this.product_name
            );
            formData.append(
              "arabic_product_name",
              this.arabic_product_name == null ? "" : this.arabic_product_name
            );
            formData.append(
              "product_code",
              this.product_code == null ? "" : this.product_code
            );
            formData.append(
              "supplier",
              this.supplier == null ? "" : this.supplier
            );
            formData.append(
              "main_category",
              this.main_category == null ? "" : this.main_category
            );
            formData.append(
              "category",
              this.category == null ? "" : this.category
            );
            formData.append("is_taxable", true);
            formData.append(
              "unlimited_quantity",
              this.unlimited_quantity == null ? false : this.unlimited_quantity
            );
            formData.append("tax_code_id", this.tax_code_id);
            formData.append("is_tobacco_tax",this.is_tobacco_tax == true ? 1 : 0);
            formData.append(
              "discount_code",
              this.discount_code == null ? "" : this.discount_code
            );
            formData.append("status", this.status == null ? "" : this.status);
            // formData.append("inventory_type", (this.inventory_type == null)?'':this.inventory_type);
            formData.append(
              "shows_in",
              this.shows_in == null ? "" : this.shows_in
            );
            formData.append(
              "show_description_in",
              this.show_description_in == null ? 0 : this.show_description_in
            );
            formData.append(
              "quantity", this.product_clone == 0 ? 
              this.quantity == null ? "" : this.quantity : ""
            );
            formData.append(
              "alert_quantity",
              this.alert_quantity == null ? "" : this.alert_quantity
            );
            formData.append(
              "sale_price",
              this.sale_price == null ? "" : this.sale_price
            );
            formData.append(
              "purchase_price",
              this.purchase_price == null ? "" : this.purchase_price
            );
            formData.append(
              "description",
              this.description == null ? "" : this.description
            );
            formData.append(
              "description_ar",
              this.description_ar == null ? "" : this.description_ar
            );
            // formData.append(
            //   "product_border_color",
            //   this.product_border_color == null ? "" : this.product_border_color
            // );
            formData.append(
              "product_manufacturer_date",
              this.product_manufacturer_date == null
                ? ""
                : this.product_manufacturer_date
            );
            formData.append(
              "product_expiry_date",
              this.product_expiry_date == null ? "" : this.product_expiry_date
            );
            formData.append(
              "is_ingredient",
              this.is_ingredient == true ? 1 : 0
            );
            formData.append(
              "ingredients", this.is_ingredient == false ? JSON.stringify(this.ingredients) : []
            );
            formData.append( "is_ingredient_price",this.is_ingredient_price == true ? 1 : 0 );
            formData.append(
              "stock_transfer_product_slack",
              this.stock_transfer_product_data == null
                ? ""
                : this.stock_transfer_product_data.slack
            );
            // added later
            formData.append(
              "barcode",
              this.barcode == null ? "" : this.barcode
            );
            formData.append("brand_id", this.brand == null ? "" : this.brand);
            formData.append(
              "measurement_id",
              this.measurement == null ? "" : this.measurement
            );
            formData.append(
              "product_thumb_image",
              this.$refs.product_thumb_image.files.length > 0
                ? this.$refs.product_thumb_image.files[0]
                : null
            );
            var sale_price_including_tax_checkbox = this.sale_price_including_tax_checkbox;
            if (sale_price_including_tax_checkbox === false) {
              this.sale_price_including_tax = null;
            }
            formData.append("sale_price_including_tax", this.sale_price_including_tax == null ? "" : this.sale_price_including_tax);
            formData.append("price_type", this.price_type == null ? "fixed" : this.price_type);

            formData.append(
              "modifier",
              this.final_modifier_list == null ? "" : this.final_modifier_list
            );
            
            formData.append("zid_sync_option", this.zid_sync_option);
            formData.append("product_applicable_stores",this.final_store_list);
            formData.append("product_applicable_store_quantities", JSON.stringify(this.final_quantity_list) );
            
            
            if(this.product_prices.length){
              formData.append("product_prices", JSON.stringify(this.product_prices));
            }
            axios
              .post(this.api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, this.success);

                  if (
                    typeof response.data.link != "undefined" &&
                    response.data.link != ""
                  ) {
                    if (response.data.new_tab == true) {
                      window.open(response.data.link, "_blank");
                    } else {
                      window.location.href = response.data.link;
                    }

                    // alert('Product Added Successfully');
                    // setTimeout(function(){
                    //     location.reload();
                    // }, 1000);
                    window.location.href = "/products";
                  } else {
                    // alert('Product Added Successfully');
                    window.location.href = "/products";
                    // setTimeout(function(){
                    //     location.reload();
                    // }, 1000);
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
                  this.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
          });
          this.$on("close", function() {
            this.show_modal = false;
          });
        }
      });
    },

    set_product_quantity_validation() {
      if (
        typeof this.stock_transfer_product_slack != "undefined" &&
        this.stock_transfer_product_slack != ""
      ) {
        this.quantity_validate = {
          required: true,
          decimal: true,
          min_value: 0.01,
          max_value: this.stock_transfer_max_quantity,
        };
      }
    },

    remove_image(image_slack) {
      if (image_slack != "") {
        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        formData.append("image_slack", image_slack);

        axios
          .post("/api/delete_product_image", formData)
          .then((response) => {
            if (response.data.status_code == 200) {
              this.show_response_message(response.data.msg, this.success);
              setTimeout(function() {
                location.reload();
              }, 1000);
            } else {
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
      }
    },

    open_image(image_link) {
      window.open(image_link, "_blank");
    },

    load_ingredients(keywords) {
      // if(typeof keywords != 'undefined'){
      // if (keywords.length > 0) {

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      // formData.append("keywords", keywords);

      axios
        .post("/api/load_ingredients", formData)
        .then((response) => {
          // return false;

          if (response.data.status_code == 200) {
            // console.log(response.data);
            this.ingredient_list = response.data.data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
      // }
      // }
    },

    add_ingredient_to_list(ingredient) {
      this.load_measurements_for_ingredient(ingredient);

      let item = this.ingredient_list.find(function(rs) {
        return rs.slack == ingredient;
      });

      if (item.slack != "") {
        var current_ingredient = {
          ingredient_slack: item.slack,
          name: item.name,
          quantity: 1,
          sale_price: item.sale_amount_excluding_tax,
          purchase_price: item.purchase_amount_excluding_tax,
          measurement_id: "",
          measurements: "",
        };
      }

      var item_found = false;
      for (var i = 0; i < this.ingredients.length; i++) {
        if (this.ingredients[i].ingredient_slack == item.slack) {
          this.ingredients[i].quantity++;
          item_found = true;
        }
      }

      // if( this.ingredients[0].name == '' && this.ingredients[0].quantity == ''){
      if (this.ingredients.length == 0) {
        this.$set(this.ingredients, 0, current_ingredient);
      } else {
        if (item_found == false) {
          this.ingredients.push(current_ingredient);
        }
      }

      // this.ingredient_list = [];
      // this.update_ingredient_prices();
      this.calculateIngredientCost();
      $('#is_ingredient_price_error').addClass('d-none');
      this.is_ingredient_price = false;
      $('#sale_price').prop('readonly',false);
    },

    remove_ingredient(index) {
      this.ingredients.splice(index, 1);
      if (index == 0) {
        // this.update_ingredient_list();
      }
      this.calculateIngredientCost();
    },

    update_ingredient_list(ingredient_list) {
      // console.log('ingredient_list');
      // console.log(ingredient_list);

      if (ingredient_list != null && ingredient_list.length > 0) {
        this.ingredients = [];
        for (let i = 0; i < ingredient_list.length; i++) {
          var individual_product = {
            ingredient_slack: ingredient_list[i].ingredient_product.slack,
            name: ingredient_list[i].ingredient_product.name,
            quantity: ingredient_list[i].quantity,
            sale_price:
              ingredient_list[i].ingredient_product.sale_amount_excluding_tax,
            purchase_price:
              ingredient_list[i].ingredient_product
                .purchase_amount_excluding_tax,
            measurement_id:
              ingredient_list[i].measurement_id == null
                ? ""
                : ingredient_list[i].measurement_id,
            measurements: "",
          };

          this.ingredients.push(individual_product);
        }
      } else {
        this.ingredients = [];
        this.ingredients.push(this.ingredient_template);
      }
      // this.update_ingredient_prices();
      this.calculateIngredientCost();
    },

    update_ingredient_prices() {
      this.ingredient_purchase_price = 0.0;
      this.ingredient_selling_price = 0.0;

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
      if(this.is_ingredient_price == true){
        this.purchase_price = this.ingredient_purchase_price;
        this.sale_price = this.ingredient_selling_price;
      }
    },

    loadSubcategories() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("catetgory_id", $("#main_category_id").val());

      axios
        .post("/api/load_subcategories", formData)
        .then((response) => {
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
    addIngredient() {
      this.add_ingredient_modal = true;
    },
    addBrand() {
      this.add_brand_modal = true;
    },
    addMeasurementUnit() {
      this.add_measurement_unit_modal = true;
    },
    addMeasurementCategory() {
      this.add_measurement_category_modal = true;
    },
    refreshSupplier(supplier_list) {
      // refresh the data in the dropdown once the new data has been added
      this.suppliers = supplier_list;
      this.add_supplier_modal = false;
    },
    refreshMainCategory(main_category_list) {
      // refresh the data in the dropdown once the new data has been added
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      axios
        .post("/api/list_main_categories", formData)
        .then((response) => {
          this.main_categories = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
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
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      if(typeof(this.product_data.id)!=="undefined")
      {
         formData.append("product_id", this.product_data.id);
      }
      else
      {
        formData.append("product_id",0);
      }

      if(typeof(this.product_data.category_id)!=="undefined")
      {
        formData.append("main_category_id", this.product_data.category_id);
      }
      else
      {
        formData.append("main_category_id",0);
      }

      if(typeof(this.product_data.category.id)!=="undefined")
      {
        formData.append("sub_category_id", this.product_data.category.id);
      }
      else
      {
        formData.append("sub_category_id",0);
      }

      axios
        .post("/api/list_discounts", formData)
        .then((response) => {
          this.discount_codes = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
         this.add_discount_modal = false;
    },
    refreshBrands(brand_list) {
      // refresh the data in the dropdown once the new data has been added
      this.brands = brand_list;
      this.add_brand_modal = false;
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
    load_measurements_for_ingredient(ingredient_slack) {
      // alert(ingredient_slack);
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("ingredient_slack", ingredient_slack);

      axios
        .post("/api/load_measurements_for_ingredient", formData)
        .then((response) => {
          for (var i in this.ingredients) {
            if (this.ingredients[i].ingredient_slack == ingredient_slack) {
              this.ingredients[i].measurements = response.data.data;
              // console.log(this.ingredients[i].measurements);
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    calculateIngredientCost() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("ingredients", JSON.stringify(this.ingredients));

      axios
        .post("/api/calculate_ingredient_cost", formData)
        .then((response) => {
          var ingredient_costs = response.data.data;
          this.ingredient_purchase_price = parseFloat(ingredient_costs.ingredient_total_purchase_cost);
          this.ingredient_selling_price = parseFloat(ingredient_costs.ingredient_total_sale_cost);
          if(this.is_ingredient_price == true){
            this.purchase_price = this.ingredient_purchase_price;
            this.sale_price = this.ingredient_selling_price;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    setProductPriceAsIngredientPrice() {
      var app_obj = this;
      var measurement_error = 0;
      if($('#is_ingredient_price').is(':checked')){
        
        if($('.ingredient_measurement').length > 0){
          $('.ingredient_measurement').each(function(i, el){
            if($(el).val() == '' || $(el).val() < 1){
              measurement_error = 1;
            }
          });
        }else{
          measurement_error = 1;
        }
        if(measurement_error == 0){
          this.purchase_price = this.ingredient_purchase_price;
          this.sale_price = this.ingredient_selling_price;
          $('#sale_price').prop('readonly',true);
          $('#is_ingredient_price_error').addClass('d-none');
          this.sale_price_including_tax_checkbox = false;
        }else{
          $('#is_ingredient_price_error').removeClass('d-none');
          $('#is_ingredient_price').prop('checked',false);
          app_obj.is_ingredient_price = false;
        }
      }else{
        $('#is_ingredient_price_error').addClass('d-none');
        app_obj.is_ingredient_price = false;
        $('#sale_price').prop('readonly',false);
      }
      // console.log('app_obj.is_ingredient_price =', app_obj.is_ingredient_price);
    },

    calculateSalePrice() {
      var sale_price_including_tax = this.sale_price_including_tax;
      var amount = this.calculate_sale_price_excluding_tax( sale_price_including_tax, this.store_tax_percentage_amt );
      var sale_price_excluding_tax = number_format(amount, 4, ".", "");
      this.sale_price = sale_price_excluding_tax;
    },

    calculateTobaccoSalePrice() {
      var sale_price_including_tax = this.sale_price_including_tax;
      var amount = this.calculate_sale_price_excluding_tax( sale_price_including_tax, this.store_tax_percentage_amt );
      var sale_price_excluding_tax = number_format(amount, 4, ".", "");
      
      var amount = this.calculate_sale_price_excluding_tax( sale_price_excluding_tax, this.tobacco_tax_percentage );
      var sale_price_excluding_tobacco_tax = number_format(amount, 4, ".", "");
      this.sale_price = sale_price_excluding_tobacco_tax;
    },

    calculate_sale_price_excluding_tax(item_total, tax_percentage) {
      var itemTotal = parseFloat(item_total);
      var taxPercentage = parseFloat(tax_percentage);

      var amount = (itemTotal / (100 + taxPercentage)) * 100;

      return amount;
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
    setTaxPercentage(tax_id = this.tax_code_id ) {

        // console.log('tax_id ==',tax_id);
        if (tax_id == 0) {
            this.store_tax_percentage_amt = 0;
        } else {
            var tax_percentage = this.taxcodes.filter(function (elem) {
                if (elem.id == tax_id) return elem;
            });
            // console.log(tax_percentage[0].total_tax_percentage);
            this.store_tax_percentage_amt = tax_percentage[0].total_tax_percentage;
        }
        if(this.store_tax_percentage_amt != 15.00 ){
          this.is_tobacco_tax = false;
        }
        if(this.sale_price_including_tax_checkbox == true){
          if(this.is_tobacco_tax == true && this.store_tax_percentage_amt == 15.00){
            this.calculateTobaccoSalePrice();
          }
          else{
            this.calculateSalePrice();
          }
        }
    },
    formatDecimal($event){
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

      if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.quantity.indexOf('.') != -1)) { 
        $event.preventDefault();
      }

      if(this.quantity!=null && this.quantity.indexOf(".")>-1 && (this.quantity.split('.')[1].length > 1)){
        $event.preventDefault();
      }
    }

  },
};
</script>
