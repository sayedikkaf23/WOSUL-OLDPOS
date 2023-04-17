<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="slack == ''">{{
              $t("Add Device")
            }}</span>
            <span class="text-title" v-else>{{ $t("Edit Device") }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <div class="form-row mb-2">
          <div class="col-md-3 form-group">
            <label for="title">{{ $t("Title") }}</label>
            <input
              type="text"
              name="title"
              v-model="title"
              v-validate="'required|max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter title"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title') }">{{
              errors.first("title")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="title_ar">{{ $t("Title (Arabic)") }}</label>
            <input
              type="text"
              name="title_ar"
              v-model="title_ar"
              v-validate="'max:150'"
              class="form-control form-control-custom"
              placeholder="Please enter Title in Arabic"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('title_ar') }">{{
              errors.first("title_ar")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="description">{{ $t("Description") }}</label>
            <textarea
              name="description"
              v-model="description"
              v-validate="'required|max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Short Description"
            ></textarea>
            <span v-bind:class="{ error: errors.has('description') }">{{
              errors.first("description")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="description_ar">{{
              $t("Description (Arabic) ")
            }}</label>
            <textarea
              name="description_ar"
              v-model="description_ar"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              placeholder="Enter Short Description in Arabic"
            ></textarea>
            <span v-bind:class="{ error: errors.has('description_ar') }">{{
              errors.first("description_ar")
            }}</span>
          </div>

          <div class="form-group col-md-3">
            <label for="image">{{
              $t("Image") + " (jpeg, jpg, png, webp)"
            }}</label>
            <input
              type="file"
              class="form-control-file form-control form-control-custom file-input"
              name="image"
              ref="image"
              accept="image/x-png,image/jpeg,image/webp"
              v-validate="'ext:jpg,jpeg,png,webp|size:3000'"
            />
            <small class="form-text text-muted mb-1">
              {{ $t("Allowed file size per file is 3 MB") }}</small
            >
            <span v-bind:class="{ error: errors.has('image') }">{{
              errors.first("image")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="currency">{{ $t("Currency") }}</label>
            <select
              name="currency"
              v-model="currency"
              v-validate="'required'"
              class="form-control form-control-custom custom-select"
            >
              <!-- <option value="SAR" selected>SAR</option> -->
              <option value="">Choose Currency..</option>
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

          <div class="col-md-3 form-group">
            <label for="price">{{ $t("Price") }}</label>
            <input
              type="number"
              step="1"
              name="price"
              v-model="price"
              v-validate="'required'"
              class="form-control form-control-custom"
              placeholder="Please enter price"
              autocomplete="off"
            />
            <span v-bind:class="{ error: errors.has('price') }">{{
              errors.first("price")
            }}</span>
          </div>

          <div class="col-md-3 form-group">
            <label for="status">{{ $t("Status") }}</label>
            <select
              name="status"
              v-model="status"
              v-validate="'required|numeric'"
              class="form-control form-control-custom custom-select"
            >
              <option value="">Choose Status..</option>
              <option
                v-for="(status, index) in statuses"
                v-bind:value="status.value"
                v-bind:key="index"
              >
                {{ status.label }}
              </option>
            </select>
            <span v-bind:class="{ error: errors.has('status') }">{{
              errors.first("status")
            }}</span>
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
        Confirm
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">
          If device unit is inactive all the products using this device unit
          will get affected.
        </p>
        Are you sure you want to proceed?
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          Cancel
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          Continue
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script type="text/javascript">
"use strict";

export default {
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,

      // additional elements
      slack: this.device_data == null ? "" : this.device_data.slack,
      api_link:
        this.device_data == null
          ? "/api/add_device"
          : "/api/update_device/" + this.device_data.slack,

      // props data
      currency_list: this.currency_data == null ? "" : this.currency_data,

      // form elements
      title: this.device_data == null ? "" : this.device_data.title,
      title_ar: this.device_data == null ? "" : this.device_data.title_ar,
      description: this.device_data == null ? "" : this.device_data.description,
      description_ar:
        this.device_data == null ? "" : this.device_data.description_ar,
      currency: this.device_data == null ? "" : this.device_data.currency,
      price: this.device_data == null ? "" : this.device_data.price,
      status:
        this.device_data == null
          ? "1"
          : this.device_data.status == null
          ? ""
          : this.device_data.status,
    };
  },
  props: {
    statuses: Array,
    device_data: [Array, Object],
    currency_data: [Array, Object],
  },
  mounted() {
    console.log("Add Device Page Loaded");
  },
  methods: {
    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            // other elements
            formData.append("access_token", window.settings.access_token);
            formData.append("slack", this.slack == null ? "" : this.slack);

            // form elements
            formData.append("title", this.title == null ? "" : this.title);
            formData.append(
              "title_ar",
              this.title_ar == null ? "" : this.title_ar
            );
            formData.append(
              "description",
              this.description == null ? "" : this.description
            );
            formData.append(
              "description_ar",
              this.description_ar == null ? "" : this.description_ar
            );
            formData.append(
              "image",
              this.$refs.image.files.length > 0
                ? this.$refs.image.files[0]
                : null
            );
            formData.append(
              "currency",
              this.currency == null ? "" : this.currency
            );
            formData.append("price", this.price == null ? "" : this.price);
            formData.append("status", this.status == null ? "" : this.status);

            axios
              .post(this.api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  // console.log(response.data);
                  this.show_response_message(response.data.msg, "SUCCESS");
                  this.show_modal = false;
                  this.processing = false;
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
  },
};
</script>
