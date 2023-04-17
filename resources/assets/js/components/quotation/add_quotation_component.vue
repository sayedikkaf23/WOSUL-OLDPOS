<style scoped>
.pad-top-32 {
  padding-top: 32px;
}
</style>

<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="quotation_slack == ''">{{
              $t("Add Quotation")
            }}</span>
            <span class="text-title" v-else>{{ $t("Edit Quotation") }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>
        <p v-html="add_product_errors" class="error"></p>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="bill_to">{{ $t("Bill To") }} *</label>
            <select
              name="bill_to"
              v-model="bill_to"
              v-validate="'required'"
              class="form-control form-control-custom custom-select"
              @change="loadSupplierCustomer(bill_to)"
            >
              <option value=""> {{ $t("Choose Bill To..") }}</option>
              <option
                v-for="(bill_to_item, index) in bill_to_master_list"
                v-bind:value="bill_to_item"
                v-bind:key="index"
              >
                {{ $t(bill_to_item) }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('bill_to') }">{{
              errors.first("bill_to")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="bill_to_slack"
              >{{ $t("Choose Customer") }} *<button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addSupplier()"
                v-if="bill_to == 'SUPPLIER'"
              >
                +
              </button>
              <button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addCustomer()"
              >
                +
              </button>
            </label>
            <select
              class="form-control"
              v-model="bill_to_slack"
              id="choose_customer_dropdown"
              @change="loadCategories(bill_to_slack)"
            >
              <option value="">--{{ $t("Choose Customer") }}--</option>
              <option v-if="bill_to == 'SUPPLIER'" value="DEFAULT">{{
                $t("DEFAULT")
              }}</option>
              <option
                v-for="(supplier, index) in suppliers_or_customers"
                :key="index"
                :value="supplier.slack"
                >
                {{ (supplier.name != '') ? supplier.name : (supplier.phone != '') ? supplier.phone : supplier.email }}
                </option
              >
            </select>
            <!-- <cool-select type="text" name="bill_to_slack" v-validate="'required'" placeholder="Please choose Customer or Supplier"  autocomplete="off" v-model="bill_to_slack" :items="bill_to_list" item-text="label" itemValue='slack' @search='load_bill_to_list' ref="bill_to_label">
                        </cool-select> -->
            <span v-bind:class="{ error: errors.has('bill_to_slack') }">{{
              errors.first("bill_to_slack")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="quotation_reference">{{
              $t("Quotation Reference #")
            }}</label>
            <input
              type="text"
              name="quotation_reference"
              v-model="quotation_reference"
              v-validate="'max:30'"
              class="form-control form-control-custom"
              :placeholder="quotation_reference_placeholder"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('quotation_reference') }">{{
              errors.first("quotation_reference")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="quotation_date">{{ $t("Quotation Date") }}</label>
            <input
              type="date"
              :lang="date.lang"
              v-model="quotation_date"
              v-validate="'required|date_format:yyyy-MM-dd'"
              class="form-control form-control-custom bg-white"
              ref="quotation_date"
              name="quotation_date"
              :placeholder="enter_quotation_date"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('quotation_date') }">{{
              errors.first("quotation_date")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="quotation_due_date">{{ $t("Quotation Due Date") }}</label>
            <input
              type="date"
              :lang="date.lang"
              v-model="quotation_due_date"
              :disabled-date="not_before_order_date"
              name="quotation_due_date"
              v-validate="'required|date_format:yyyy-MM-dd'"
              class="form-control form-control-custom bg-white"
              :placeholder="enter_quotation_due_date"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('quotation_due_date') }">{{
              errors.first("quotation_due_date")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3">
            <label for="currency">{{ $t("Currency") }}</label>
            <select
              name="currency"
              v-model="currency"
              v-validate="'required'"
              class="form-control form-control-custom custom-select"
            >
              <!-- <option value="SAR" selected>SAR</option> -->
              <option value="">{{ $t("Choose Currency..") }}</option>
              <option
                v-for="(currency_item, index) in currency_list"
                v-bind:value="currency_item.currency_code"
                v-bind:key="index"
                :data-id="currency_item.currency_code"
              >
                {{ currency_item.currency_code }} -
                {{ currency_item.currency_name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('currency') }">{{
              errors.first("currency")
            }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-6">
            <label for="terms">{{ $t("Terms") }}</label>
            <textarea
              name="terms"
              v-model="terms"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Terms"
            ></textarea>
            <span v-bind:class="{ error: errors.has('terms') }">{{
              errors.first("terms")
            }}</span>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-1">
          <div class="mr-auto">
            <span class="text-subhead">{{ $t("Products") }} *</span>
          </div>
          <div class=""></div>
        </div>

        <div class="form-row mb-2">
          <div class="form-group col-md-3" v-show="bill_to == 'CUSTOMER'">
            <label for="supplier">{{ $t("Choose Supplier") }}</label>
            <!-- <cool-select type="text" name="supplier" placeholder="Please choose supplier"  autocomplete="off" v-model="supplier" :items="supplier_list" item-text="label" itemValue='slack' @search='load_suppliers'>
                        </cool-select> -->
            <Select2
              v-model="supplier"
              name="supplier"
              :options="supplierOptions"
              @change="loadCategories(supplier)"
            />
          </div>
          <div class="form-group col-md-3" v-if="all_categories.length">
            <label for="category">{{ $t("Select Category") }}</label>
            <Select2
              v-model="category"
              :options="categoryOptions"
              @change="
                loadSubCategories(category);
                loadProductsByCategory(category);
              "
            />
          </div>
          <div class="form-group col-md-3" v-if="all_subcategories.length">
            <label for="subcategory">{{ $t("Select Sub Category") }}</label>
            <Select2
              v-model="subcategory"
              :options="subcategoryOptions"
              @change="loadProductsByCategory(subcategory)"
            />
          </div>
          <div class="form-group col-md-3" v-if="all_products.length">
            <label for="product">{{ $t("Select Products") }}</label>
            <Select2 v-model="product" :options="productOptions" />
          </div>
          <div class="form-group col-md-3 pad-top-32">
            <button
              type="button"
              class="btn btn-primary btn-md"
              @click="add_product_to_list(product, 1)"
            >
              {{ $t("Add") }} {{ $t("Product") }}
            </button>
            <button
              type="button"
              class="btn btn-success btn-md"
              @click="add_product_to_list(product, 2)"
            >
              {{ $t("Add") }} {{ $t("Service") }}
            </button>
          </div>
        </div>

        <div class="" v-if="show_product_list == true || quotation_slack != ''">
          <div class="form-row">
            <div class="form-group col-md-2 mb-1">
              <label for="name">{{ $t("Product or Service") }}</label>
            </div>
            <div class="form-group col-md-2 mb-1">
              <label for="name">{{ $t("Description") }}</label>
            </div>
            <div class="form-group col-md-1 mb-1">
              <label for="quantity">{{ $t("Quantity") }}</label>
            </div>
            <div class="form-group col-md-1 mb-1">
              <label for="unit_price">{{ $t("Price Per One") }}</label>
            </div>
            <div class="form-group col-md-1 mb-1">
              <label for="discount_percentage">{{ $t("Discount %") }}</label>
            </div>

            <div class="form-group col-md-1 mb-1">
              <label for="tax_code">{{ $t("Select Tax") }}</label>
            </div>

            <div class="form-group col-md-1 mb-1">
              <label for="tax_percentage">{{ $t("Tax") }}</label>
            </div>
            <div class="form-group col-md-1 mb-1">
              <label for="measurement">{{ $t("Measurement") }}</label>
            </div>
            <div class="form-group col-md-2 mb-1">
              <label for="amount">{{ $t("Amount") }}</label>
            </div>
          </div>

          <div
            class="form-row mb-2"
            v-for="(product, index) in products"
            :key="index"
            v-if="products.length"
          >
            <div class="form-group col-md-2">
              <input
                type="text"
                v-bind:name="'product.name_' + index"
                v-model="product.name"
                v-validate="'required|max:250'"
                :data-vv-as="product_name"
                class="form-control form-control-custom"
                autocomplete="off"
                :readonly="product.product_type == 1"
              />
              <span
                v-bind:class="{ error: errors.has('product.name_' + index) }"
                >{{ errors.first("product.name_" + index) }}</span
              >
            </div>
             <div class="form-group col-md-2">
              <input
                type="text"
                v-bind:name="'product.description_' + index"
                v-model="product.description"
                v-validate=""
                :data-vv-as="product_description"
                class="form-control form-control-custom"
                autocomplete="off"
                :readonly="product.product_type == 1"
              />
              <!-- <span
                v-bind:class="{ error: errors.has('product.description_' + index) }"
                >{{ errors.first("product.description_" + index) }}</span
              > -->
            </div>
            <div class="form-group col-md-1">
              <input
                type="number"
                v-bind:name="'product.quantity_' + index"
                v-model="product.quantity"
                v-validate="'required|decimal'"
                data-vv-as="Quantity"
                class="form-control form-control-custom"
                autocomplete="off"
                step="0.01"
                v-on:input="
                  calculate_price();
                  check_product_quantity($event, product.slack);
                "
              />
              <span
                v-bind:class="{
                  error: errors.has('product.quantity_' + index),
                }"
                >{{ errors.first("product.quantity_" + index) }}</span
              >
            </div>
            <div class="form-group col-md-1">
              <input
                type="number"
                v-bind:name="'product.unit_price_' + index"
                v-model="product.unit_price"
                v-validate="'required|decimal|min_value:0'"
                data-vv-as="Unit Price"
                class="form-control form-control-custom"
                autocomplete="off"
                                step="0.00001"

                min="0"
                v-on:input="calculate_price"
              />
              <span
                v-bind:class="{
                  error: errors.has('product.unit_price_' + index),
                }"
                >{{ errors.first("product.unit_price_" + index) }}</span
              >
            </div>
            <div class="form-group col-md-1">
              <input
                type="number"
                v-bind:name="'product.discount_percentage_' + index"
                v-model="product.discount_percentage"
                v-validate="'decimal|min_value:0'"
                data-vv-as="Discount %"
                class="form-control form-control-custom"
                autocomplete="off"
                step="0.1"
                min="0"
                v-on:input="calculate_price"
              />
              <span
                v-bind:class="{
                  error: errors.has('product.discount_percentage_' + index),
                }"
                >{{
                  errors.first("product.discount_percentage_" + index)
                }}</span
              >
            </div>
            <div class="form-group col-md-1" v-if="product.is_taxable == 0">
              <input
                type="text"
                disabled=""
                readonly=""
                :value="non_taxable_label"
                class="form-control form-control-custom"
              />
            </div>
            <div class="form-group col-md-1" v-if="product.is_taxable == 1">
              <select
                class="form-control"
                v-model="product.tax_code"
                v-bind:name="'product.tax_code_' + index"
                placeholder="Please choose tax code"
                autocomplete="off"
                @change="setTaxPercentage(product.tax_code, index)"
              >
                <option value="0">{{ $t("Choose Tax Code..") }}</option>
                <option
                  v-for="(code, code_index) in tax_codes"
                  :key="code_index"
                  :value="code.id"
                  >{{ code.label }}
                </option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <input
                type="number"
                v-bind:name="'product.tax_percentage_' + index"
                v-model="product.tax_percentage"
                v-validate="'decimal|min_value:0'"
                data-vv-as="Tax %"
                class="form-control form-control-custom"
                autocomplete="off"
                step="1"
                min="0"
                v-on:input="calculate_price"
                readonly
              />
              <span
                v-bind:class="{
                  error: errors.has('product.tax_percentage_' + index),
                }"
                >{{ errors.first("product.tax_percentage_" + index) }}</span
              >
            </div>
            <div class="form-group col-md-1">
              <select
                v-bind:name="'product.measurement_id_' + index"
                v-model="product.measurement_id"
                v-validate="product.measurement_id != '' ? 'required' : ''"
                class="form-control form-control-custom custom-select"
                disabled
              >
                <option value="">{{ $t("Choose Measurement Unit") }}..</option>
                <option
                  v-for="(measurement,
                  measurement_index) in product.measurements"
                  v-bind:value="measurement.id"
                  v-bind:key="measurement_index"
                >
                  {{ measurement.label }}
                </option>
              </select>
              <span
                v-bind:class="{
                  error: errors.has('product.measurement_id_' + index),
                }"
                >{{ errors.first("product.measurement_id_" + index) }}</span
              >
            </div>
            <div class="form-group col-md-2">
              <input
                type="number"
                v-bind:name="'product.amount_' + index"
                v-model="product.amount"
                v-validate="'required|decimal|min_value:0'"
                data-vv-as="Amount"
                class="form-control form-control-custom"
                autocomplete="off"
                step="1"
                min="0"
                readonly="true"
              />
              <span
                v-bind:class="{ error: errors.has('product.amount_' + index) }"
                >{{ errors.first("product.amount_" + index) }}</span
              >
            </div>
            <div class="form-group col-md-1" v-if="products.length > 1">
              <button
                type="button"
                class="btn btn-outline-danger"
                @click="remove_product(index)"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>
        <!--    
               
                <div class="form-row">
                    <div class="form-group col-md-4 mb-1">
                        <label for="service_name">{{ $t("Service") }}</label>
                    </div>
                    <div class="form-group col-md-1 mb-1">
                        <label for="service_quantity">{{ $t("Quantity") }}</label>
                    </div>
                    <div class="form-group col-md-1 mb-1">
                        <label for="service_unit_price">{{ $t("Price Per One") }}</label>
                    </div>
                    <div class="form-group col-md-1 mb-1">
                        <label for="service_discount_percentage">{{ $t("Discount %") }}</label>
                    </div>
                    <div class="form-group col-md-1 mb-1">
                        <label for="service_tax_code">{{ $t("Select Tax") }}</label>
                    </div>
                    <div class="form-group col-md-1 mb-1">
                        <label for="service_tax_percentage">{{ $t("Tax") }}</label>
                    </div>
                     <div class="form-group col-md-2 mb-1">
                        <label for="service_amount">{{ $t("Amount") }}</label>
                    </div>
                </div> -->

        <!-- <div class="form-row mb-2" v-for="(service, service_index) in services" :key="'A'+service_index" v-if="services.length">
                    <div class="form-group col-md-4">
                        <input type="text" v-bind:name="'service.name_'+'A'+service_index" v-model="service.name" v-validate="'required|max:250'" :data-vv-as="service_name" class="form-control form-control-custom" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('service.name_'+'A'+service_index) }">{{ errors.first('service.name_'+'A'+service_index) }}</span>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" v-bind:name="'service.quantity_'+'A'+service_index" v-model="service.quantity" v-validate="'required|decimal|min_value:1'" data-vv-as="Quantity" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" v-on:input="calculate_price">
                        <span v-bind:class="{ 'error' : errors.has('service.quantity_'+'A'+service_index) }">{{ errors.first('service.quantity_'+'A'+service_index) }}</span>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" v-bind:name="'service.unit_price_'+'A'+service_index" v-model="service.unit_price" v-validate="'required|decimal|min_value:0'" data-vv-as="Unit Price" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" v-on:input="calculate_price">
                        <span v-bind:class="{ 'error' : errors.has('service.unit_price_'+'A'+service_index) }">{{ errors.first('service.unit_price_'+'A'+service_index) }}</span>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" v-bind:name="'service.discount_percentage_'+'A'+service_index" v-model="service.discount_percentage" v-validate="'decimal|min_value:0'" data-vv-as="Discount %" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" v-on:input="calculate_price">
                        <span v-bind:class="{ 'error' : errors.has('service.discount_percentage_'+'A'+service_index) }">{{ errors.first('service.discount_percentage_'+'A'+service_index) }}</span>
                    </div>  
                    <div class="form-group col-md-1">
                        <select class="form-control" v-model="service.tax_code" v-bind:name="'service.tax_code_'+'A'+service_index" placeholder="Please choose tax code" autocomplete="off" @change="setTaxPercentageService(service.tax_code,'A'+service_index)">
                          <option value="0">{{ $t("--Choose Tax Code--") }}</option>
                          <option v-for="(code,code_service_index) in tax_codes" :key="code_service_index" :value="code.id" >{{ code.label }} </option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" v-bind:name="'service.tax_percentage_'+'A'+service_index" v-model="service.tax_percentage" v-validate="'decimal|min_value:0'" data-vv-as="Tax %" class="form-control form-control-custom" autocomplete="off" step="1" min="0" v-on:input="calculate_price" readonly>
                        <span v-bind:class="{ 'error' : errors.has('service.tax_percentage_'+'A'+service_index) }">{{ errors.first('service.tax_percentage_'+'A'+service_index) }}</span>
                    </div>
                    <div class="form-group col-md-2">
                        <input type="number" v-bind:name="'service.amount_'+'A'+service_index" v-model="service.amount" v-validate="'required|decimal|min_value:0'" data-vv-as="Amount" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" readonly="true">
                        <span v-bind:class="{ 'error' : errors.has('service.amount_'+'A'+service_index) }">{{ errors.first('service.amount_'+'A'+service_index) }}</span>
                    </div>
                    <div class="form-group col-md-1" v-if="services.length>1">
                        <button type="button" class="btn btn-outline-danger" @click="remove_service(service_index)"><i class="fas fa-times"></i></button>
                    </div>
                </div> -->

        <!-- <button type="button" class="btn btn-sm btn-outline-primary" @click="add_new_product">{{ $t("Add More") }}</button> -->

        <!-- <div class="form-row mb-3">
                    <div class="col-md-2 offset-md-7 text-right">
                        <span class="align-text-top">{{ $t("Shipping Charges") }}</span>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="shipping_charge" v-model="shipping_charge" v-validate="'decimal|min_value:0'" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" v-on:input="calculate_price">
                        <span v-bind:class="{ 'error' : errors.has('shipping_charge') }">{{ errors.first('shipping_charge') }}</span>
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-2 offset-md-7 text-right">
                        <span class="align-text-top">{{ $t("Packing Charges") }}</span>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="packing_charge" v-model="packing_charge" v-validate="'decimal|min_value:0'" class="form-control form-control-custom"  autocomplete="off" step="1" min="0" v-on:input="calculate_price">
                        <span v-bind:class="{ 'error' : errors.has('packing_charge') }">{{ errors.first('packing_charge') }}</span>
                    </div>
                </div> -->
       
        <div class="form-row  mb-3">
          <div class="col-md-2 offset-md-7 text-right">
            {{ $t("Total Tax Amount") }}
          </div>
          <div class="col-md-2">
            {{ quotation_tax_amount }}
          </div>
        </div>
        <div class="form-row  mb-3">
          <div class="col-md-2 offset-md-7 text-right">
            {{ $t("Total") }}
          </div>
          <div class="col-md-2">
            {{ grand_total }}
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
          :country_list="country_list"
          :statuses="statuses"
          :reload_on_submit="false"
          :show_supplier_modal="show_supplier_modal"
          @refreshSupplier="refreshSupplier"
        ></addsuppliercomponent>
      </template>
      <template v-slot:modal-footer style="display: none;">
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Close") }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent
      v-if="add_customer_modal"
      v-on:close="add_customer_modal = false"
      :hide_modal_footer="true"
      :show_footer="false"
    >
      <template v-slot:modal-header>
        {{ $t("Add New Customer") }}
      </template>
      <template v-slot:modal-body>
        <addcustomercomponent
          :country_list="country_list"
          :statuses="statuses"
          :reload_on_submit="false"
          :bill_to="bill_to"
        ></addcustomercomponent>
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
"use strict";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import moment from "moment";
import { CoolSelect } from "vue-cool-select";
import "vue-cool-select/dist/themes/bootstrap.css";
import Select2 from "v-select2-component";
export default {
  components: {
    CoolSelect,
    Select2,
  },
  data() {
    return {
      date: {
        lang: "en",
        format: "YYYY-MM-DD",
      },
      supplierOptions: [],
      categoryOptions: [],
      subcategoryOptions: [],
      show_product_list: false,
      show_service_list: false,
      productOptions: [],
      server_errors: "",
      add_product_errors: "",
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,
      api_link:
        this.quotation_data == null
          ? "/api/add_quotation"
          : "/api/update_quotation/" + this.quotation_data.slack,

      bill_to_master_list: ["CUSTOMER", "COMPANY"],
      bill_to_list: [],
      product_list: [],
      search_product: "",
      supplier: "",
      supplier_list: [],

      bill_to:
        this.quotation_data == null ? "CUSTOMER" : this.quotation_data.bill_to,
      bill_to_slack:
        this.quotation_data == null
          ? ""
          : this.quotation_data.bill_to == "SUPPLIER"
          ? this.quotation_data.supplier.slack
          : this.quotation_data.customer != null
          ? this.quotation_data.customer.slack
          : "",
      bill_to_label:
        this.quotation_data == null ? "" : this.quotation_data.bill_to_name,

      quotation_slack: this.quotation_data == null ? "" : this.quotation_data.slack,
      quotation_reference:
        this.quotation_data == null
          ? ""
          : this.quotation_data.quotation_reference != null
          ? this.quotation_data.quotation_reference
          : "",
      quotation_date:
        this.quotation_data == null
          ? new Date().toISOString().substr(0, 10)
          : this.quotation_data.quotation_date != null
          ? this.quotation_data.quotation_date_raw
          : "",
      quotation_due_date:
        this.quotation_data == null
          ? new Date().toISOString().substr(0, 10)
          : this.quotation_data.quotation_due_date != null
          ? this.quotation_data.quotation_due_date_raw
          : "",
      currency:
        this.quotation_data == null
          ? this.session_currency_code
          : this.quotation_data.currency_code != null
          ? this.quotation_data.currency_code
          : "",
      tax_option:
        this.quotation_data == null
          ? 0
          : this.quotation_data.tax_option_id != null
          ? this.quotation_data.tax_option_id
          : "",

      // shipping_charge : (this.quotation_data == null)?'':(this.quotation_data.shipping_charge != null)?this.quotation_data.shipping_charge:'',
      // packing_charge : (this.quotation_data == null)?'':(this.quotation_data.packing_charge != null)?this.quotation_data.packing_charge:'',
      terms:
        this.quotation_data == null
          ? this.store_data.quotation_policy_information
          : this.quotation_data.terms != null
          ? this.quotation_data.terms
          : "",
      grand_total: 0,

      product: [Array, Object],
      service: [Array, Object],
      products: [],
      services: [],
      products_template: {
        slack: "",
        name: "",
        quantity: "",
        unit_price: "",
        discount_percentage: "",
        is_taxable: "",
        tax_code: "",
        tax_percentage: "",
        amount: "",
        product_type: "",
      },

      quotation_product_list:
        this.quotation_data != null ? this.quotation_data.products : [],

      today: new Date(),
      add_supplier_modal: false,
      add_customer_modal: false,
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
      show_supplier_modal: true,
      suppliers_or_customers:
        this.quotation_data != null
          ? this.loadSupplierCustomer(this.quotation_data.bill_to)
          : "",
      suppliers: [Array, Object],
      customers: [Array, Object],
      store_tax_code_id:
        this.store_data == null ? "" : this.store_data.tax_code_id,
      category: Object,
      subcategory: Object,
      categories: Object,
      subcategories: [Array, Object],
      all_products: this.product_data == null ? "" : this.product_data,
      all_categories: this.category_data == null ? "" : this.category_data,
      all_subcategories:
        this.subcategory_data == null ? "" : this.subcategory_data,
      quotation_tax_amount: Number,
      quotation_reference_placeholder: this.$t(
        "Please enter Quotation Reference #"
      ),
      product_name: this.$t("Name"),
      service_name: this.$t("Name"),
      success: this.$t("SUCCESS"),
      non_taxable_label: this.$t("Non Taxable"),
    };
  },

  props: {
    currency_list: Array,
    quotation_data: [Array, Object],
    store_data: [Array, Object],
    tax_options: [Array, Object],
    product_data: [Array, Object],
    category_data: [Array, Object],
    subcategory_data: [Array, Object],
    session_currency_code: String,
    session_currency_name: String,
    tax_codes: [Array, Object],
    country_list: Array,
  },

  watch: {
    bill_to_slack: function(val) {
      if (val) {
        this.product_list = [];
        if (this.bill_to == "SUPPLIER") {
          this.update_product_list();
        }
      }
    },
  },

  mounted() {
    this.loadSupplierCustomer("CUSTOMER");
    console.log("Add quotation page loaded");
    console.log(this.quotation_data);
    // console.log(this.quotation_data.total_tax_amount);
    // console.log(this.quotation_tax_amount);
    // console.log(this.all_products);
    // console.log(this.tax_codes);
    // console.log(this.store_tax_code_id);
  },

  created() {
    this.update_product_list(this.quotation_product_list);
    // if(this.quotation_data.supplier.slack){
    //     this.all_products = this.loadProducts(this.quotation_data.supplier.slack);
    // }
  },

  methods: {
    check_product_quantity(event, slack) {
      var product_quantity = event.target.value;
      var product_slack = slack;
      this.add_product_errors = "";
      this.server_errors = "";

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("slack", product_slack);
      formData.append("quantity", product_quantity);

      axios
        .post("/api/check_product_quantity", formData)
        .then((response) => {
          let item = response.data.product;

          if (item.slack != "") {
            if (item.low_product_stock == 1) {
              var msg = item.name + " is out of stock";
              this.add_product_errors = msg;
              window.scrollTo(0, 0);
              return false;
            }

            if (item.ingredient_low_stock == 1) {
              var msg = item.name + " low on Ingredient stock";
              this.add_product_errors = msg;
              window.scrollTo(0, 0);
              return false;
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    convert_date_format(date) {
      return date != "" ? moment(date).format("YYYY-MM-DD") : "";
    },

    not_before_order_date(date) {
      return date < this.order_date;
    },

    load_bill_to_list(keywords) {
      if (typeof keywords != "undefined") {
        if (keywords.length > 0) {
          var formData = new FormData();
          formData.append("access_token", window.settings.access_token);
          formData.append("keywords", keywords);
          formData.append("type", this.bill_to);

          axios
            .post("/api/load_bill_to_list", formData)
            .then((response) => {
              if (response.data.status_code == 200) {
                this.bill_to_list = response.data.data;
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      }
    },

    load_suppliers(keywords) {
      if (typeof keywords != "undefined") {
        if (keywords.length > 0) {
          var formData = new FormData();
          formData.append("access_token", window.settings.access_token);
          formData.append("keywords", keywords);
          this.supplier_list = [];
          axios
            .post("/api/load_suppliers", formData)
            .then((response) => {
              if (response.data.status_code == 200) {
                this.supplier_list = response.data.data;
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      }
    },

    load_products(keywords) {
      if (typeof keywords != "undefined") {
        var supplier =
          this.bill_to == "SUPPLIER" ? this.bill_to_slack : this.supplier;

        // if (keywords.length > 0 && supplier != '') {
        if (keywords.length > 0) {
          var formData = new FormData();
          formData.append("access_token", window.settings.access_token);
          formData.append("keywords", keywords);
          // formData.append("supplier", supplier);
          this.product_list = [];
          axios
            .post("/api/load_product_for_po", formData)
            .then((response) => {
              if (response.data.status_code == 200) {
                this.product_list = response.data.data;
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      }
    },

    add_product_to_list(slack, product_type) {
      this.add_product_errors = "";
      this.server_errors = "";
      if (product_type == 2) {
        this.show_product_list = true;
        // Adding a Service
        var current_product = {
          slack: "",
          name: "",
          description: "",
          quantity: 1,
          unit_price: "",
          discount_percentage: "",
          is_taxable: 1,
          tax_code: 0,
          tax_percentage: 0,
          amount: "",
          product_type: product_type,
        };

        this.products.push(current_product);
      } else {
        // Adding a Product

        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        formData.append("slack", slack);

        axios
          .post("/api/get_product_detail", formData)
          .then((response) => {
            let item = response.data.product;
            let measurement_category_id = response.data.measurement_category_id;
            let measurements_data = response.data.measurements_data;

            if (item.slack != "") {
              this.show_product_list = true;
              var current_product = {
                slack: item.slack,
                name: item.name,
                description: item.description,
                quantity: 1,
                unit_price: item.sale_amount_excluding_tax,
                discount_percentage: 0, // item.discount_percentage,
                is_taxable: item.is_taxable, // item.tax_percentage,
                tax_code: 0, // item.tax_percentage,
                tax_percentage: 0, // item.tax_percentage,
                amount: "",
                product_type: product_type,
                measurement_id: item.measurement_id,
                measurements: measurements_data,
              };

              if (item.quantity <= 0 && item.quantity != -1) {
                var msg = item.name + " is out of stock";
                this.add_product_errors = msg;
                window.scrollTo(0, 0);
                return false;
              }

              if (item.ingredient_low_stock == 1) {
                var msg = item.name + " low on Ingredient stock";
                this.add_product_errors = msg;
                window.scrollTo(0, 0);
                return false;
              }
            }

            var item_found = false;
            for (var i = 0; i < this.products.length; i++) {
              if (this.products[i].slack == item.slack) {
                this.products[i].quantity++;
                item_found = true;
              }
            }

            if (
              this.products[0] != undefined &&
              this.products[0].name == "" &&
              this.products[0].quantity == "" &&
              this.products[0].unit_price == ""
            ) {
              this.$set(this.products, 0, current_product);
            } else {
              if (item_found == false) {
                this.products.push(current_product);
              }
            }
            this.product_list = [];
            this.calculate_price();
          })
          .catch((error) => {
            console.log(error);
          });
      }

      return false;
    },
    add_service_to_list() {
      var current_service = {
        name: "",
        quantity: 1,
        unit_price: 0,
        discount_percentage: 0,
        is_taxable: 1,
        tax_code: 0,
        tax_percentage: 0,
        amount: 0,
      };
      this.services.push(current_service);
    },

    calculate_tax(item_total, tax_percentage) {
      var tax_amount =
        (parseFloat(tax_percentage) / 100) * parseFloat(item_total);
      return tax_amount.toFixed(2);
    },

    calculate_discount(item_total, discount_percentage) {
      var discount_amount =
        (parseFloat(discount_percentage) / 100) * parseFloat(item_total);
      return discount_amount.toFixed(2);
    },

    calculate_price() {
      var quotation_tax = $("#tax_option option:selected").attr("data-value");

      var grand_total = 0;
      var grand_total_tax = 0;

      for (var index in this.products) {
        var discount_amount = 0;
        var tax_amount = 0;
        var item_total = 0;

        var quantity = this.products[index].quantity;
        var unit_price = this.products[index].unit_price;
        var discount_percentage = this.products[index].discount_percentage;
        var tax_percentage = this.products[index].tax_percentage;

        if (
          !isNaN(quantity) &&
          quantity != null &&
          quantity != "" &&
          !isNaN(unit_price) &&
          unit_price != null &&
          unit_price != ""
        ) {
          item_total = parseFloat(quantity) * parseFloat(unit_price);

          if (
            !isNaN(discount_percentage) &&
            discount_percentage != null &&
            discount_percentage != ""
          ) {
            if (discount_percentage >= 0) {
              discount_amount = this.calculate_discount(
                item_total,
                discount_percentage
              );
              item_total = parseFloat(item_total) - parseFloat(discount_amount);
            }
          }
          if (
            !isNaN(tax_percentage) &&
            tax_percentage != null &&
            tax_percentage != ""
          ) {
            if (tax_percentage >= 0) {
              tax_amount = this.calculate_tax(item_total, tax_percentage);
            }
          }

          item_total = parseFloat(item_total) + parseFloat(tax_amount);
          item_total = item_total.toFixed(2);
          this.products[index].amount = item_total;
          grand_total_tax =
            parseFloat(grand_total_tax) + parseFloat(tax_amount);
          grand_total_tax = grand_total_tax.toFixed(2);
          grand_total = parseFloat(grand_total) + parseFloat(item_total);
        } else {
          continue;
        }
      }

      // if(quotation_tax == undefined && this.quotation_data != null){
      //     this.quotation_tax_amount = parseFloat(this.quotation_data.total_tax_amount);
      // }
      // else{
      //     this.quotation_tax_amount = (quotation_tax > 0) ? total_charges * quotation_tax / 100 : 0;
      // }

      this.quotation_tax_amount = grand_total_tax;

      this.grand_total = grand_total.toFixed(2);
    },

    add_new_product() {
      this.products.push({
        slack: "",
        name: "",
        description: "",
        quantity: "",
        unit_price: "",
        discount_percentage: "",
        tax_code: "",
        tax_percentage: 0,
        amount: "",
      });
      this.calculate_price();
    },

    remove_product(index) {
      this.products.splice(index, 1);
      this.calculate_price();
    },
    remove_service(index) {
      this.services.splice(index, 1);
      this.calculate_price();
    },

    update_product_list(purchase_order_products) {
      if (
        purchase_order_products != null &&
        purchase_order_products.length > 0
      ) {
        this.products = [];
        for (let i = 0; i < purchase_order_products.length; i++) {
          var individual_product = {
            slack: purchase_order_products[i].product_slack,
            name: purchase_order_products[i].name,
            description: purchase_order_products[i].description,
            quantity: purchase_order_products[i].quantity,
            unit_price: purchase_order_products[i].amount_excluding_tax,
            discount_percentage: purchase_order_products[i].discount_percentage,
            is_taxable: purchase_order_products[i].is_taxable == 0 ? 0 : 1,
            tax_code: purchase_order_products[i].tax_code_id,
            tax_percentage: purchase_order_products[i].tax_percentage,
            amount: purchase_order_products[i].total_amount,
            product_type: purchase_order_products[i].product_type,
            measurement_id: purchase_order_products[i].measurement_id,
            measurements: purchase_order_products[i].measurements,
          };
          this.products.push(individual_product);
        }
      } else {
        this.products = [];
      }
      this.calculate_price();
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

            formData.append("access_token", window.settings.access_token);
            formData.append(
              "bill_to",
              this.bill_to != null ? this.bill_to : ""
            );
            formData.append(
              "bill_to_slack",
              this.bill_to_slack != null ? this.bill_to_slack : ""
            );
            formData.append(
              "quotation_reference",
              this.quotation_reference != null ? this.quotation_reference : ""
            );
            formData.append(
              "quotation_date",
              this.convert_date_format(this.quotation_date)
            );
            formData.append(
              "quotation_due_date",
              this.convert_date_format(this.quotation_due_date)
            );
            formData.append(
              "currency",
              this.currency != null ? this.currency : ""
            );
            // formData.append("shipping_charge", this.shipping_charge);
            // formData.append("packing_charge", this.packing_charge);
            formData.append("terms", this.terms);
            formData.append("tax_option", this.tax_option);
            formData.append("quotation_tax_amount", this.quotation_tax_amount);
            formData.append("products", JSON.stringify(this.products));

            this.add_product_errors = "";
            axios
              .post(this.api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, this.success);

                  setTimeout(function() {
                    location.href = "/quotation/" + response.data.data;
                  }, 1000);
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
                  window.scrollTo(0, 0);
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

    addSupplier() {
      this.add_supplier_modal = true;
    },
    addCustomer() {
      this.add_customer_modal = true;
    },
    loadSupplierCustomer(bill_to) {
      if (bill_to == "SUPPLIER") {
        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        this.suppliers_or_customers = [];

        axios.post("/api/load_suppliers", formData).then((response) => {
          this.suppliers_or_customers = response.data;
        });
      } else if (bill_to == "CUSTOMER") {
        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        this.supplierOptions = [];
        axios.post("/api/load_suppliers", formData).then((response) => {
        this.suppliers = [];
         this.suppliers = response.data;

          this.suppliers.forEach((supplier, index) => {
            this.supplierOptions.push({
              id: supplier.slack,
              text: supplier.name,
            });
          });
        });

        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);
        this.suppliers_or_customers = [];
        axios.post("/api/load_customers", formData).then((response) => {
          this.suppliers_or_customers = response.data;
        });
      }
    },
    refreshSupplier(supplier_list) {
      // refresh the data in the dropdown once the new data has been added

      this.suppliers_or_customers = supplier_list;
      this.add_supplier_modal = false;
    },

    loadProductsByCategory(category_id, category_type) {
      let form_data = new FormData();
      form_data.append("access_token", window.settings.access_token);
      form_data.append("category_id", category_id);
      form_data.append("category_type", category_type);
      this.productOptions = [];
      axios
        .post("/api/load_products_by_category", form_data)
        .then((response) => {
          // console.log(response.data);
          this.all_products = response.data;
          this.all_products.forEach((product, index) => {
            this.productOptions.push({ id: product.slack, text: product.name });
          });
        });
    },

    setTaxPercentage(tax_code_id, index) {
      // alert(tax_code_id);
      if (tax_code_id == 0) {
        // alert(0);
        this.products[index].tax_percentage = 0;
        var tax_amount = 0;
      } else {
        // alert(1);
        var tax_percentage = this.tax_codes.filter(function(elem) {
          if (elem.id == tax_code_id) return elem;
        });
        this.products[index].tax_percentage =
          tax_percentage[0].total_tax_percentage;

        if (tax_percentage[0].total_tax_percentage > 0) {
          var tax_amount =
            (this.products[index].amount *
              tax_percentage[0].total_tax_percentage) /
            100;
        }
      }

      let product_amount_including_tax =
        parseFloat(tax_amount) + parseFloat(this.products[index].amount);
      this.products[index].amount = product_amount_including_tax.toFixed(2);
      this.calculate_price();
    },
    setTaxPercentageService(tax_code_id, index) {
      // alert(tax_code_id);
      if (tax_code_id == 0) {
        // alert(0);
        this.services[index].tax_percentage = 0;
        var tax_amount = 0;
      } else {
        // alert(1);
        var tax_percentage = this.tax_codes.filter(function(elem) {
          if (elem.id == tax_code_id) return elem;
        });
        this.services[index].tax_percentage =
          tax_percentage[0].total_tax_percentage;

        if (tax_percentage[0].total_tax_percentage > 0) {
          var tax_amount =
            (this.services[index].amount *
              tax_percentage[0].total_tax_percentage) /
            100;
        }
      }

      let service_amount_including_tax =
        parseFloat(tax_amount) + parseFloat(this.services[index].amount);
      this.services[index].amount = service_amount_including_tax.toFixed(2);
      this.calculate_price();
    },
    loadCategories(supplier_slack) {
      let form_data = new FormData();
      form_data.append("access_token", window.settings.access_token);
      form_data.append("supplier_slack", supplier_slack);

      (this.categoryOptions = []),
        axios
          .post("/api/load_categories_by_supplier", form_data)
          .then((response) => {
            // console.log(response.data);
            this.all_categories = response.data;
            this.all_categories.forEach((category, index) => {
              this.categoryOptions.push({
                id: category.id,
                text: category.label,
              });
            });
          });
    },
    loadSubCategories(main_category_id) {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("catetgory_id", main_category_id);
      (this.subcategoryOptions = []),
        axios
          .post("/api/load_subcategories", formData)
          .then((response) => {
            // console.log(response);
            this.all_subcategories = response.data;
            this.all_subcategories.forEach((subcategory, index) => {
              this.subcategoryOptions.push({
                id: subcategory.id,
                text: subcategory.label,
              });
            });
          })
          .catch((error) => {
            console.log(error);
          });
    },
  },
};
</script>
