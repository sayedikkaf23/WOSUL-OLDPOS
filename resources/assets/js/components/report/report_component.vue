<template>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex flex-wrap mb-4">
        <div class="mr-auto">
          <div class="d-flex">
            <div>
              <span class="text-title">{{ $t("Reports") }}</span>
            </div>
          </div>
        </div>
        <div class=""></div>
      </div>

      <form
        @submit.prevent="download_user_report('user_report_form')"
        data-vv-scope="user_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("User Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="user_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="user_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="user_report_form.server_errors"
          v-bind:class="[user_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="user_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="user_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="role">{{ $t("Role") }}</label>
            <select
              name="role"
              v-model="user_report_form.role"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Role..") }}</option>
              <option
                v-for="(role, index) in roles"
                v-bind:value="role.slack"
                v-bind:key="index"
              >
                {{ role.role_code }} - {{ role.label }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="user_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in user_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_user_wise_sales_report('user_wise_sales_report_form')
        "
        data-vv-scope="user_wise_sales_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("User Wise Sales Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="user_wise_sales_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="user_wise_sales_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="user_wise_sales_report_form.server_errors"
          v-bind:class="[user_wise_sales_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="branch">{{ $t("Select Branch") }}</label>
            <select
              id="branch"
              v-model="user_wise_sales_report_form.store"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("All Stores") }}</option>
              <option
                v-for="(store, index) in stores"
                v-bind:value="store.id"
                v-bind:key="index"
              >
                {{ $t(store.name) }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="user_wise_sales_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="user_wise_sales_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>

          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="user_wise_sales_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in user_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_product_report('product_report_form')"
        data-vv-scope="product_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Product Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="product_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="product_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="product_report_form.server_errors"
          v-bind:class="[product_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3" v-if="restaurant_mode == 1">
            <label for="product_type">{{ $t("Product Type") }}</label>
            <select
              name="product_type"
              v-model="product_report_form.product_type"
              class="form-control form-control-custom custom-select"
            >
              <option value="all">{{ $t("All") }}</option>
              <option value="billing_products">{{
                $t("Billing Products")
              }}</option>
              <option value="ingredients">{{ $t("Ingredients") }}</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="product_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="product_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="supplier">{{ $t("Supplier") }}</label>
            <select
              name="supplier"
              v-model="product_report_form.supplier"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Supplier..") }}</option>
              <option
                v-for="(supplier, index) in suppliers"
                v-bind:value="supplier.slack"
                v-bind:key="index"
              >
                {{ supplier.supplier_code }} - {{ supplier.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('supplier') }">{{
              errors.first("supplier")
            }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="product">{{ $t("Product") }}</label>
            <select
              name="product"
              v-model="product_report_form.product"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Product") }}</option>
              <option
                v-for="(product, index) in product_list"
                v-bind:value="product.slack"
                v-bind:key="index"
              >
                {{ product.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('product') }">{{
              errors.first("product")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="category">{{ $t("Category") }}</label>
            <select
              name="category"
              v-model="product_report_form.category"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Category..") }}</option>
              <option
                v-for="(category, index) in categories"
                v-bind:value="category.slack"
                v-bind:key="index"
              >
                {{ category.category_code }} - {{ category.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('category') }">{{
              errors.first("category")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_code">{{ $t("Tax Code") }}</label>
            <select
              name="tax_code"
              v-model="product_report_form.tax_code"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Tax Code..") }}</option>
              <option
                v-for="(taxcode, index) in taxcodes"
                v-bind:value="taxcode.slack"
                v-bind:key="index"
              >
                {{ taxcode.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('tax_code') }">{{
              errors.first("tax_code")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t("Discount Code") }}</label>
            <select
              name="discount_code"
              v-model="product_report_form.discount_code"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Discount Code..") }}</option>
              <option
                v-for="(discount_code, index) in discountcodes"
                v-bind:value="discount_code.slack"
                v-bind:key="index"
              >
                {{ discount_code.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('discount_code') }">{{
              errors.first("discount_code")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="product_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in user_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_product_wise_sales_report('product_wise_sales_report_form')
        "
        data-vv-scope="product_wise_sales_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Product Wise Sales Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="
                product_wise_sales_report_form.processing == true
              "
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="product_wise_sales_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="product_wise_sales_report_form.server_errors"
          v-bind:class="[product_wise_sales_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3" v-if="restaurant_mode == 1">
            <label for="product_type">{{ $t("Product Type") }}</label>
            <select
              name="product_type"
              v-model="product_wise_sales_report_form.product_type"
              class="form-control form-control-custom custom-select"
            >
              <option value="all">{{ $t("All") }}</option>
              <option value="billing_products">{{
                $t("Billing Products")
              }}</option>
              <option value="ingredients">{{ $t("Ingredients") }}</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="product_wise_sales_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="product_wise_sales_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="supplier">{{ $t("Supplier") }}</label>
            <select
              name="supplier"
              v-model="product_wise_sales_report_form.supplier"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Supplier..") }}</option>
              <option
                v-for="(supplier, index) in suppliers"
                v-bind:value="supplier.slack"
                v-bind:key="index"
              >
                {{ supplier.supplier_code }} - {{ supplier.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('supplier') }">{{
              errors.first("supplier")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="category">{{ $t("Category") }}</label>
            <select
              name="category"
              v-model="product_wise_sales_report_form.category"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Category..") }}</option>
              <option
                v-for="(category, index) in categories"
                v-bind:value="category.slack"
                v-bind:key="index"
              >
                {{ category.category_code }} - {{ category.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('category') }">{{
              errors.first("category")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_code">{{ $t("Tax Code") }}</label>
            <select
              name="tax_code"
              v-model="product_wise_sales_report_form.tax_code"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Tax Code..") }}</option>
              <option
                v-for="(taxcode, index) in taxcodes"
                v-bind:value="taxcode.slack"
                v-bind:key="index"
              >
                {{ taxcode.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('tax_code') }">{{
              errors.first("tax_code")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t("Discount Code") }}</label>
            <select
              name="discount_code"
              v-model="product_wise_sales_report_form.discount_code"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Discount Code..") }}</option>
              <option
                v-for="(discount_code, index) in discountcodes"
                v-bind:value="discount_code.slack"
                v-bind:key="index"
              >
                {{ discount_code.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('discount_code') }">{{
              errors.first("discount_code")
            }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="product_wise_sales_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in user_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_order_report('order_report_form')"
        data-vv-scope="order_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Order Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="order_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="order_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="order_report_form.server_errors"
          v-bind:class="[order_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="order_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="order_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="order_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in order_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />
      <form
        @submit.prevent="
          download_purchase_order_report('purchase_order_report_form')
        "
        data-vv-scope="purchase_order_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Purchase Order Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="purchase_order_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="purchase_order_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="purchase_order_report_form.server_errors"
          v-bind:class="[purchase_order_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="purchase_order_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="purchase_order_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="purchase_order_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in purchase_order_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_customer_report('customer_report_form')"
        data-vv-scope="customer_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Customer Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="customer_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="customer_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="customer_report_form.server_errors"
          v-bind:class="[customer_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="customer_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="customer_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="customer_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in customer_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_invoice_report('invoice_report_form')"
        data-vv-scope="invoice_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Invoice Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="invoice_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="invoice_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="invoice_report_form.server_errors"
          v-bind:class="[invoice_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="invoice_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="invoice_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="invoice_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in invoice_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_quotation_report('quotation_report_form')"
        data-vv-scope="quotation_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Quotation Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="quotation_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="quotation_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="quotation_report_form.server_errors"
          v-bind:class="[quotation_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="quotation_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="quotation_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="quotation_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in quotation_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_transaction_report('transaction_report_form')"
        data-vv-scope="transaction_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Transaction Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="transaction_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="transaction_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="transaction_report_form.server_errors"
          v-bind:class="[transaction_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="transaction_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="transaction_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="account">{{ $t("Account") }}</label>
            <select
              name="account"
              v-model="transaction_report_form.account"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Account..") }}</option>
              <option
                v-for="(account, index) in accounts"
                v-bind:value="account.slack"
                v-bind:key="index"
              >
                {{ account.label }} ({{ account.account_type_label }})
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="transaction_type">{{ $t("Transaction Type") }}</label>
            <select
              name="transaction_type"
              v-model="transaction_report_form.transaction_type"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Transaction Type..") }}</option>
              <option
                v-for="(transaction_type_item, index) in transaction_types"
                v-bind:value="transaction_type_item.transaction_type_constant"
                v-bind:key="index"
              >
                {{ transaction_type_item.label }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="payment_method">{{ $t("Payment Method") }}</label>
            <select
              name="payment_method"
              v-model="transaction_report_form.payment_method"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Payment Method..") }}</option>
              <option
                v-for="(payment_method, index) in payment_methods"
                v-bind:value="payment_method.slack"
                v-bind:key="index"
              >
                {{ payment_method.label }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_taxcode_report('taxcode_report_form')"
        data-vv-scope="taxcode_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Tax Code Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="taxcode_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="taxcode_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="taxcode_report_form.server_errors"
          v-bind:class="[taxcode_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="taxcode_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="taxcode_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <!-- <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="taxcode_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in taxcode_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div> -->
          <div class="form-group col-md-3">
            <label for="all_stores">{{ $t("All Stores") }}</label>
            <br />
            <input input-class="form-control bg-white" style="padding-top: 20px" type="checkbox" name="all_stores" v-model="taxcode_report_form.all_stores" />

          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_discountcode_report('discountcode_report_form')
        "
        data-vv-scope="discountcode_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Discount Code Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="discountcode_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="discountcode_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="discountcode_report_form.server_errors"
          v-bind:class="[discountcode_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="discountcode_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="discountcode_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="discountcode_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in discountcode_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_supplier_report('supplier_report_form')"
        data-vv-scope="supplier_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Supplier Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="supplier_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="supplier_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="supplier_report_form.server_errors"
          v-bind:class="[supplier_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="supplier_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="supplier_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="supplier_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in supplier_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_category_report('category_report_form')"
        data-vv-scope="category_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Category Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="category_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="category_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="category_report_form.server_errors"
          v-bind:class="[category_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="category_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="category_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="category_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in category_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_return_order_report('return_order_report_form')
        "
        data-vv-scope="return_order_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Return Order Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="return_order_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="return_order_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="return_order_report_form.server_errors"
          v-bind:class="[return_order_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="return_order_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="return_order_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="return_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in product_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        v-show="logged_in_store_id == 1"
        @submit.prevent="
          download_stock_status_report('stock_status_report_form')
        "
        data-vv-scope="stock_status_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Stock Status Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="stock_status_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="stock_status_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="stock_status_report_form.server_errors"
          v-bind:class="[stock_status_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="stock_status_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="stock_status_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="branch">{{ $t("Select Branch") }}</label>
            <select
              id="branch"
              v-model="stock_status_report_form.store"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("All Stores") }}</option>
              <option
                v-for="(store, index) in stores"
                v-bind:value="store.id"
                v-bind:key="index"
              >
                {{ $t(store.name) }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="return_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in product_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_quantity_purchase_report('quantity_purchase_report_form')
        "
        data-vv-scope="quantity_purchase_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Quantity Purchase Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="quantity_purchase_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="quantity_purchase_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="quantity_purchase_report_form.server_errors"
          v-bind:class="[quantity_purchase_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="quantity_purchase_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="quantity_purchase_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="branch">{{ $t("Select Branch") }}</label>
            <select
              id="branch"
              v-model="quantity_purchase_report_form.store"
              class="form-control form-control-custom custom-select"
              @change="loadProductsByStore(quantity_purchase_report_form.store)"
            >
              <option value="">{{ $t("Choose Store") }}</option>
              <option
                v-for="(store, index) in stores"
                v-bind:value="store.id"
                v-bind:key="index"
              >
                {{ $t(store.name) }}
              </option>
            </select>
          </div>
          <div
            class="form-group col-md-3"
            v-show="quantity_purchase_report_form.store != null"
          >
            <label for="product">{{ $t("Product") }}</label>
            <select
              name="product"
              v-model="quantity_purchase_report_form.product"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("All Products") }}</option>
              <option
                v-for="(product, index) in products"
                v-bind:value="product.slack"
                v-bind:key="index"
              >
                {{ $t(product.name) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="
          download_supplier_invoice_report('supplier_invoice_report_form')
        "
        data-vv-scope="supplier_invoice_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{
                  $t("Supplier Invoice Report")
                }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="supplier_invoice_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="supplier_invoice_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="supplier_invoice_report_form.server_errors"
          v-bind:class="[supplier_invoice_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="supplier_invoice_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="supplier_invoice_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="supplier">{{ $t("Supplier") }}</label>
            <select
              name="supplier"
              v-model="supplier_invoice_report_form.supplier"
              v-validate="''"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("All Suppliers") }}</option>
              <option
                v-for="(supplier, index) in suppliers"
                v-bind:value="supplier.slack"
                v-bind:key="index"
              >
                {{ supplier.supplier_code }} - {{ supplier.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('supplier') }">{{
              errors.first("supplier")
            }}</span>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
        @submit.prevent="download_inventory_report('inventory_report_form')"
        data-vv-scope="inventory_report_form"
        class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Inventory Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="inventory_report_form.processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="inventory_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
          </div>
        </div>

        <p
          v-html="inventory_report_form.server_errors"
          v-bind:class="[inventory_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="inventory_report_form.from_created_date"
              input-class="form-control bg-white"
              :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
              type="date"
              :lang="date.lang"
              :format="'YYYY-MM-DD'"
              v-model="inventory_report_form.to_created_date"
              input-class="form-control bg-white"
              :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="inventory_report_form.status"
              class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                v-for="(status, index) in inventory_statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
        </div>
      </form>

      <hr class="mb-4" />

      <form
          @submit.prevent="download_tax_report('tax_report_form')"
          data-vv-scope="tax_report_form"
          class="mb-2"
      >
        <div class="d-flex flex-wrap mb-2">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <span class="text-subhead">{{ $t("Tax Report") }}</span>
              </div>
            </div>
          </div>
          <div class="">
            <button
                type="submit"
                class="btn btn-primary"
                v-bind:disabled="tax_report_form.processing == true"
            >
              <i
                  class="fa fa-circle-notch fa-spin"
                  v-if="tax_report_form.processing == true"
              ></i>
              {{ $t("Download") }}
            </button>
            <button
                type="button"
                class="btn btn-primary"
                v-bind:disabled="tax_report_form.processing == true"
                @click="download_tax_report_pdf()"
            >
              <i
                  class="fa fa-circle-notch fa-spin"
                  v-if="tax_report_form.processing == true"
              ></i>
              {{ $t("Download PDF") }}
            </button>
          </div>
        </div>

        <p
            v-html="tax_report_form.server_errors"
            v-bind:class="[tax_report_form.error_class]"
        ></p>

        <div class="form-row mb-1">
          <div class="form-group col-md-3" v-if="restaurant_mode == 1">
            <label for="product_type">{{ $t("Product Type") }}</label>
            <select
                name="product_type"
                v-model="tax_report_form.product_type"
                class="form-control form-control-custom custom-select"
            >
              <option value="all">{{ $t("All") }}</option>
              <option value="billing_products">{{
                  $t("Billing Products")
                }}</option>
              <option value="ingredients">{{ $t("Ingredients") }}</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="from_created_date">{{ $t("From Created Date") }}</label>
            <date-picker
                type="date"
                :lang="date.lang"
                :format="'YYYY-MM-DD'"
                v-model="tax_report_form.from_created_date"
                input-class="form-control bg-white"
                :placeholder="s_from_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="to_created_date">{{ $t("To Created Date") }}</label>
            <date-picker
                type="date"
                :lang="date.lang"
                :format="'YYYY-MM-DD'"
                v-model="tax_report_form.to_created_date"
                input-class="form-control bg-white"
                :placeholder="s_to_created_date"
            ></date-picker>
          </div>
          <div class="form-group col-md-3">
            <label for="supplier">{{ $t("Supplier") }}</label>
            <select
                name="supplier"
                v-model="tax_report_form.supplier"
                v-validate="''"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Supplier..") }}</option>
              <option
                  v-for="(supplier, index) in suppliers"
                  v-bind:value="supplier.slack"
                  v-bind:key="index"
              >
                {{ supplier.supplier_code }} - {{ supplier.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('supplier') }">{{
                errors.first("supplier")
              }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="product">{{ $t("Product") }}</label>
            <select
                name="product"
                v-model="tax_report_form.product"
                v-validate="''"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Product") }}</option>
              <option
                  v-for="(product, index) in product_list"
                  v-bind:value="product.slack"
                  v-bind:key="index"
              >
                {{ product.name }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('product') }">{{
                errors.first("product")
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="category">{{ $t("Category") }}</label>
            <select
                name="category"
                v-model="tax_report_form.category"
                v-validate="''"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Category..") }}</option>
              <option
                  v-for="(category, index) in categories"
                  v-bind:value="category.slack"
                  v-bind:key="index"
              >
                {{ category.category_code }} - {{ category.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('category') }">{{
                errors.first("category")
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="tax_code">{{ $t("Tax Code") }}</label>
            <select
                name="tax_code"
                v-model="tax_report_form.tax_code"
                v-validate="''"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Tax Code..") }}</option>
              <option
                  v-for="(taxcode, index) in taxcodes"
                  v-bind:value="taxcode.slack"
                  v-bind:key="index"
              >
                {{ taxcode.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('tax_code') }">{{
                errors.first("tax_code")
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="discount_code">{{ $t("Discount Code") }}</label>
            <select
                name="discount_code"
                v-model="tax_report_form.discount_code"
                v-validate="''"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Discount Code..") }}</option>
              <option
                  v-for="(discount_code, index) in discountcodes"
                  v-bind:value="discount_code.slack"
                  v-bind:key="index"
              >
                {{ discount_code.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('discount_code') }">{{
                errors.first("discount_code")
              }}</span>
          </div>
          <div class="form-group col-md-3">
            <label for="status">{{ $t("Status") }}</label>
            <select
                name="status"
                v-model="tax_report_form.status"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose Status..") }}</option>
              <option
                  v-for="(status, index) in user_statuses"
                  v-bind:value="status.value"
                  v-bind:key="index"
              >
                {{ $t(status.label) }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="role">{{ $t("Created By") }}</label>
            <select
                name="role"
                v-model="tax_report_form.created_by"
                class="form-control form-control-custom custom-select"
            >
              <option value="">{{ $t("Choose User..") }}</option>
              <option
                  v-for="(user, index) in users"
                  v-bind:value="user.slack"
                  v-bind:key="index"
              >
                {{ user.fullname }}
              </option>
            </select>
          </div>
        </div>
      </form>


    </div>
  </div>
</template>

<script>
"use strict";

import DatePicker from "vue2-datepicker";
import moment from "moment";

export default {
  data() {
    return {
      date: {
        lang: "en",
        format: "YYYY-MM-DD",
      },
      restaurant_mode: window.settings.restaurant_mode,
      s_from_created_date: this.$t("Select from created date"),
      s_to_created_date: this.$t("Select to created date"),
      user_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        role: "",
        status: "",
      },
      user_wise_sales_report_form: {
        store: "",
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        // user : '',
        status: "",
      },

      product_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        supplier: "",
        product: "",
        category: "",
        tax_code: "",
        discount_code: "",
        product_type: "all",
        status: "",
      },

      product_wise_sales_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        supplier: "",
        category: "",
        tax_code: "",
        discount_code: "",
        product_type: "billing_products",
        status: "",
      },

      order_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      return_order_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      purchase_order_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      customer_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      store_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      taxcode_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
        all_stores: false
      },

      discountcode_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      supplier_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      category_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      return_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      invoice_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      quotation_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },

      transaction_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        account: "",
        transaction_type: "",
        payment_method: "",
      },

      stock_status_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        store: "",
        status: "",
      },

      quantity_purchase_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        store: "",
        product: "",
        status: "",
      },

      supplier_invoice_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        supplier: "",
      },

      inventory_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        status: "",
      },
      tax_report_form: {
        server_errors: "",
        error_class: "",
        processing: false,

        from_created_date: "",
        to_created_date: "",
        supplier: "",
        product: "",
        category: "",
        tax_code: "",
        discount_code: "",
        product_type: "all",
        status: "",
        created_by: "",
      },
      products: Array,
    };
  },
  components: {
    DatePicker,
  },
  props: {
    user_statuses: Array,
    roles: Array,
    users: Array,
    product_list: Array,
    product_statuses: Array,
    suppliers: Array,
    categories: Array,
    taxcodes: Array,
    discountcodes: Array,

    order_statuses: Array,

    purchase_order_statuses: Array,

    customer_statuses: Array,

    category_statuses: Array,

    supplier_statuses: Array,

    taxcode_statuses: Array,

    discountcode_statuses: Array,

    invoice_statuses: Array,

    quotation_statuses: Array,

    inventory_statuses: Array,

    transaction_types: Array,
    accounts: Array,
    payment_methods: Array,

    stores: [Array, Object],
    logged_in_store_id: Number,
  },
  mounted() {
    console.log("Report page loaded");
    console.log(this.users);
    // alert(this.logged_in_store_id);
  },
  methods: {
    formatDate(d) {
      // you could also provide your own month names array
      const months = this.$refs.datePicker.translation.months;
      return `${d
        .getDate()
        .toString()
        .padStart(2, 0)} ${months[d.getMonth()]} ${d.getFullYear()}`;
    },
    convert_date_format(date) {
      return date != "" && date != null
        ? moment(date).format("YYYY-MM-DD")
        : "";
    },

    download_user_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.user_report_form.processing = true;
          var formData = new FormData();
          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.user_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.user_report_form.to_created_date)
          );
          formData.append("role", this.user_report_form.role);
          formData.append("status", this.user_report_form.status);

          axios
            .post("/api/user_report", formData)
            .then((response) => {
              this.user_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.user_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.user_report_form.server_errors = response.data.msg;
                }
                this.user_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_user_wise_sales_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.user_wise_sales_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append("store", this.user_wise_sales_report_form.store);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.user_wise_sales_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.user_wise_sales_report_form.to_created_date
            )
          );
          formData.append("status", this.user_wise_sales_report_form.status);

          axios
            .post("/api/user_wise_sales_report", formData)
            .then((response) => {
              this.user_wise_sales_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.user_wise_sales_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.user_wise_sales_report_form.server_errors =
                    response.data.msg;
                }
                this.user_wise_sales_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_product_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.product_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.product_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.product_report_form.to_created_date)
          );
          formData.append("supplier", this.product_report_form.supplier);
          formData.append("product", this.product_report_form.product);
          formData.append("category", this.product_report_form.category);
          formData.append("tax_code", this.product_report_form.tax_code);
          formData.append(
            "discount_code",
            this.product_report_form.discount_code
          );
          formData.append(
            "product_type",
            this.product_report_form.product_type
          );
          formData.append("status", this.product_report_form.status);

          axios
            .post("/api/product_report", formData)
            .then((response) => {
              this.product_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.product_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.product_report_form.server_errors = response.data.msg;
                }
                this.product_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_product_wise_sales_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.product_wise_sales_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.product_wise_sales_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.product_wise_sales_report_form.to_created_date
            )
          );
          formData.append(
            "supplier",
            this.product_wise_sales_report_form.supplier
          );
          formData.append(
            "category",
            this.product_wise_sales_report_form.category
          );
          formData.append(
            "tax_code",
            this.product_wise_sales_report_form.tax_code
          );
          formData.append(
            "discount_code",
            this.product_wise_sales_report_form.discount_code
          );
          formData.append(
            "product_type",
            this.product_wise_sales_report_form.product_type
          );
          formData.append("status", this.product_wise_sales_report_form.status);

          axios
            .post("/api/product_wise_sales_report", formData)
            .then((response) => {
              this.product_wise_sales_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.product_wise_sales_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.product_wise_sales_report_form.server_errors =
                    response.data.msg;
                }
                this.product_wise_sales_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_order_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.order_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.order_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.order_report_form.to_created_date)
          );
          formData.append("status", this.order_report_form.status);

          axios
            .post("/api/order_report", formData)
            .then((response) => {
              this.order_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.order_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.order_report_form.server_errors = response.data.msg;
                }
                this.order_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_purchase_order_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.purchase_order_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.purchase_order_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.purchase_order_report_form.to_created_date
            )
          );
          formData.append("status", this.purchase_order_report_form.status);

          axios
            .post("/api/purchase_order_report", formData)
            .then((response) => {
              this.purchase_order_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.purchase_order_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.purchase_order_report_form.server_errors =
                    response.data.msg;
                }
                this.purchase_order_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_customer_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.customer_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.customer_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.customer_report_form.to_created_date)
          );
          formData.append("status", this.customer_report_form.status);

          axios
            .post("/api/customer_report", formData)
            .then((response) => {
              this.customer_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.customer_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.customer_report_form.server_errors = response.data.msg;
                }
                this.customer_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_store_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.store_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.store_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.store_report_form.to_created_date)
          );
          formData.append("status", this.store_report_form.status);

          axios
            .post("/api/store_report", formData)
            .then((response) => {
              this.store_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.store_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.store_report_form.server_errors = response.data.msg;
                }
                this.store_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_taxcode_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.taxcode_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.taxcode_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.taxcode_report_form.to_created_date)
          );
          formData.append("status", this.taxcode_report_form.status);
          formData.append("all_stores", this.taxcode_report_form.all_stores);

          axios
            .post("/api/taxcode_report", formData)
            .then((response) => {
              this.taxcode_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.taxcode_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.taxcode_report_form.server_errors = response.data.msg;
                }
                this.taxcode_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_discountcode_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.discountcode_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.discountcode_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.discountcode_report_form.to_created_date
            )
          );
          formData.append("status", this.discountcode_report_form.status);

          axios
            .post("/api/discountcode_report", formData)
            .then((response) => {
              this.discountcode_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.discountcode_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.discountcode_report_form.server_errors =
                    response.data.msg;
                }
                this.discountcode_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_supplier_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.supplier_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.supplier_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.supplier_report_form.to_created_date)
          );
          formData.append("status", this.supplier_report_form.status);

          axios
            .post("/api/supplier_report", formData)
            .then((response) => {
              this.supplier_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.supplier_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.supplier_report_form.server_errors = response.data.msg;
                }
                this.supplier_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_category_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.category_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.category_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.category_report_form.to_created_date)
          );
          formData.append("status", this.category_report_form.status);

          axios
            .post("/api/category_report", formData)
            .then((response) => {
              this.category_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.category_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.category_report_form.server_errors = response.data.msg;
                }
                this.category_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_return_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.return_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.return_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.return_report_form.to_created_date)
          );
          formData.append("status", this.return_report_form.status);

          axios
            .post("/api/category_report", formData)
            .then((response) => {
              this.return_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.return_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.return_report_form.server_errors = response.data.msg;
                }
                this.return_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_invoice_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.invoice_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(this.invoice_report_form.from_created_date)
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.invoice_report_form.to_created_date)
          );
          formData.append("status", this.invoice_report_form.status);

          axios
            .post("/api/invoice_report", formData)
            .then((response) => {
              this.invoice_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.invoice_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.invoice_report_form.server_errors = response.data.msg;
                }
                this.invoice_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_quotation_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.quotation_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.quotation_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.quotation_report_form.to_created_date)
          );
          formData.append("status", this.quotation_report_form.status);

          axios
            .post("/api/quotation_report", formData)
            .then((response) => {
              this.quotation_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.quotation_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.quotation_report_form.server_errors = response.data.msg;
                }
                this.quotation_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_transaction_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.transaction_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.transaction_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.transaction_report_form.to_created_date
            )
          );
          formData.append("account", this.transaction_report_form.account);
          formData.append(
            "transaction_type",
            this.transaction_report_form.transaction_type
          );
          formData.append(
            "payment_method",
            this.transaction_report_form.payment_method
          );

          axios
            .post("/api/transaction_report", formData)
            .then((response) => {
              this.transaction_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (response.data.link != "") {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.transaction_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.transaction_report_form.server_errors =
                    response.data.msg;
                }
                this.transaction_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    download_return_order_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.return_order_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.return_order_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.return_order_report_form.to_created_date
            )
          );
          formData.append("status", this.return_order_report_form.status);

          axios
            .post("/api/return_order_report", formData)
            .then((response) => {
              this.return_order_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.return_order_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.return_order_report_form.server_errors =
                    response.data.msg;
                }
                this.return_order_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    download_stock_status_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.stock_status_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.stock_status_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.stock_status_report_form.to_created_date
            )
          );
          formData.append("store", this.stock_status_report_form.store);
          formData.append("status", this.stock_status_report_form.status);

          console.log(this.stock_status_report_form);

          axios
            .post("/api/stock_status_report", formData)
            .then((response) => {
              this.stock_status_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.stock_status_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.stock_status_report_form.server_errors =
                    response.data.msg;
                }
                this.stock_status_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    loadProductsByStore(store) {
      var formData = new FormData();

      formData.append("access_token", window.settings.access_token);
      formData.append("store_id", store);

      axios
        .post("/api/purchase_orders/load_products_by_store", formData)
        .then((response) => {
          this.products = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    download_quantity_purchase_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.quantity_purchase_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.quantity_purchase_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.quantity_purchase_report_form.to_created_date
            )
          );
          formData.append("store", this.quantity_purchase_report_form.store);
          formData.append(
            "product",
            this.quantity_purchase_report_form.product
          );
          formData.append("status", this.quantity_purchase_report_form.status);

          axios
            .post("/api/quantity_purchase_report", formData)
            .then((response) => {
              this.quantity_purchase_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.quantity_purchase_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.quantity_purchase_report_form.server_errors =
                    response.data.msg;
                }
                this.quantity_purchase_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    download_supplier_invoice_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.supplier_invoice_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.supplier_invoice_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(
              this.supplier_invoice_report_form.to_created_date
            )
          );
          formData.append(
            "supplier",
            this.supplier_invoice_report_form.supplier
          );

          axios
            .post("/api/supplier_invoice_report", formData)
            .then((response) => {
              this.supplier_invoice_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.supplier_invoice_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.supplier_invoice_report_form.server_errors =
                    response.data.msg;
                }
                this.supplier_invoice_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    download_inventory_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.inventory_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
            "from_created_date",
            this.convert_date_format(
              this.inventory_report_form.from_created_date
            )
          );
          formData.append(
            "to_created_date",
            this.convert_date_format(this.inventory_report_form.to_created_date)
          );
          formData.append("status", this.inventory_report_form.status);

          axios
            .post("/api/inventory_report", formData)
            .then((response) => {
              this.inventory_report_form.processing = false;
              if (response.data.status_code == 200) {
                if (
                  typeof response.data.link != "undefined" &&
                  response.data.link != ""
                ) {
                  const link = document.createElement("a");
                  link.href = response.data.link;
                  document.body.appendChild(link);
                  link.click();
                } else {
                  location.reload();
                }
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.inventory_report_form.server_errors = this.loop_api_errors(
                    error_json
                  );
                } catch (err) {
                  this.inventory_report_form.server_errors = response.data.msg;
                }
                this.inventory_report_form.error_class = "error";
              }
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },
    download_tax_report(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.tax_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
              "from_created_date",
              this.convert_date_format(this.tax_report_form.from_created_date)
          );
          formData.append(
              "to_created_date",
              this.convert_date_format(this.tax_report_form.to_created_date)
          );
          formData.append("supplier", this.tax_report_form.supplier);
          formData.append("product", this.tax_report_form.product);
          formData.append("category", this.tax_report_form.category);
          formData.append("tax_code", this.tax_report_form.tax_code);
          formData.append(
              "discount_code",
              this.tax_report_form.discount_code
          );
          formData.append(
              "product_type",
              this.tax_report_form.product_type
          );
          formData.append("status", this.tax_report_form.status);
          formData.append("created_by", this.tax_report_form.created_by);

          axios
              .post("/api/tax_report", formData)
              .then((response) => {
                this.tax_report_form.processing = false;
                if (response.data.status_code == 200) {
                  if (
                      typeof response.data.link != "undefined" &&
                      response.data.link != ""
                  ) {
                    const link = document.createElement("a");
                    link.href = response.data.link;
                    document.body.appendChild(link);
                    link.click();
                  } else {
                    location.reload();
                  }
                } else {
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.tax_report_form.server_errors = this.loop_api_errors(
                        error_json
                    );
                  } catch (err) {
                    this.tax_report_form.server_errors = response.data.msg;
                  }
                  this.tax_report_form.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
        }
      });
    },
    download_tax_report_pdf(scope){
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.tax_report_form.processing = true;
          var formData = new FormData();

          formData.append("access_token", window.settings.access_token);
          formData.append(
              "from_created_date",
              this.convert_date_format(this.tax_report_form.from_created_date)
          );
          formData.append(
              "to_created_date",
              this.convert_date_format(this.tax_report_form.to_created_date)
          );
          formData.append("supplier", this.tax_report_form.supplier);
          formData.append("product", this.tax_report_form.product);
          formData.append("category", this.tax_report_form.category);
          formData.append("tax_code", this.tax_report_form.tax_code);
          formData.append(
              "discount_code",
              this.tax_report_form.discount_code
          );
          formData.append(
              "product_type",
              this.tax_report_form.product_type
          );
          formData.append("status", this.tax_report_form.status);
          formData.append("responseType", 'blob');
          formData.append("created_by", this.tax_report_form.created_by);
          axios
              .post("/api/tax_report_pdf", formData,{timeout: 300000000})
              .then((response) => {
                this.tax_report_form.processing = false;
                if (response.data.status_code == 200) {
                  if (
                      typeof response.data.link != "undefined" &&
                      response.data.link != ""
                  ) {
                    const link = document.createElement("a");
                    link.target = "_blank";
                    link.href = response.data.link;
                    document.body.appendChild(link);
                    link.click();

                  } else {
                    location.reload();
                  }
                } else {
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.tax_report_form.server_errors = this.loop_api_errors(
                        error_json
                    );
                  } catch (err) {
                    this.tax_report_form.server_errors = response.data.msg;
                  }
                  this.tax_report_form.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
        }
      });
    },
  },
};
</script>
