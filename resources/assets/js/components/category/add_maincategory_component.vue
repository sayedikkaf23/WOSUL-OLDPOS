<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <p class="alert alert-success" v-show="msg.success.length">
          {{ msg.success }}
        </p>

        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="category_slack == ''">{{ $t('Add Category') }}</span>
            <span class="text-title" v-else>{{ $t('Edit Category') }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>
        <div class="form-row mb-2">
          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="category_name">{{ $t('Category Name') }}</label>
            <input
              type="text"
              name="category_name"
              v-model="category_name"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="category_placeholder"
            />
            <span v-bind:class="{error: errors.has('category_name')}">{{ errors.first('category_name') }}</span>
          </div>
          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="category_name_ar">{{ $t('Category Name (Arabic) ') }}</label>
            <input
              type="text"
              name="category_name_ar"
              v-model="category_name_ar"
              v-validate="'required|max:250'"
              class="form-control form-control-custom"
              autocomplete="off"
              :placeholder="category_ar_placeholder"
            />
            <span v-bind:class="{error: errors.has('category_name_ar')}">{{ errors.first('category_name_ar') }}</span>
          </div>
          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="status">{{ $t('Status') }}</label>
            <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
              <option value="">{{ $t('Choose Status..') }}</option>
              <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                {{ $t(status.label) }}
              </option>
            </select>
            <span v-bind:class="{error: errors.has('status')}">{{ errors.first('status') }}</span>
          </div>
        </div>

        <div class="form-row mb-2">
          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="category_image">{{ $t('Category Image') + ' (jpeg, jpg, png, webp)' }}</label>
            <input
              type="file"
              class="form-control-file form-control form-control-custom file-input"
              name="category_image"
              ref="category_image"
              accept="image/x-png,image/jpeg,image/webp"
              v-validate="'ext:jpg,jpeg,png,webp|size:3000'"
            />
            <small class="form-text text-muted mb-1">{{ $t('Allowed file size per file is 3 MB') }}</small>
            <span v-bind:class="{error: errors.has('category_image')}">{{ errors.first('category_image') }}</span>
          </div>

          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="description">{{ $t('Description') }}</label>
            <textarea
              name="description"
              v-model="description"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              :placeholder="description_placeholder"
            ></textarea>
            <span v-bind:class="{error: errors.has('description')}">{{ errors.first('description') }}</span>
          </div>

          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
            <label for="description_ar">{{ $t('Description (Arabic)') }}</label>
            <textarea
              name="description_ar"
              v-model="description_ar"
              v-validate="'max:65535'"
              class="form-control form-control-custom"
              rows="5"
              :placeholder="description_ar_placeholder"
            ></textarea>
            <span v-bind:class="{error: errors.has('description_ar')}">{{ errors.first('description_ar') }}</span>
          </div>
        </div>

        <div class="form-row mb-2 mt-2 discount-code-section">
          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group mb-1 p-0 ml-2']">
            <label for="category_applied_on">{{ $t('Apply Category To') }}</label>
            <div class="d-flex flex-column">
              <div class="d-flex flex-row justify-content-start align-items-center">
                <input
                  type="radio"
                  class="check"
                  name="category_applied_on"
                  v-on:click="openStoreList('all_stores')"
                  :checked="category_applied_on == 'all_stores' ? 'checked' : ''"
                />
                <label class="ml-2">{{ $t('All Stores') }}</label>
              </div>
              <div class="d-flex flex-row justify-content-start align-items-center">
                <input
                  type="radio"
                  class="check"
                  name="category_applied_on"
                  v-on:click="openStoreList('specific_stores')"
                  :checked="category_applied_on == 'specific_stores' ? 'checked' : ''"
                />
                <label class="ml-2">{{ $t('Specific Stores') }}</label>
              </div>
            </div>
          </div>

          <div
            class="col-md-4 'form-group mb-1 p-0 ml-2 mr-2"
            id="stores_section"
            :style="category_applied_on == 'specific_stores' ? '' : 'display:none;'"
          >
            <label for="stores_section">{{ $t('Choose Store') }}</label>

            <input type="search" class="form-control mb-2" placeholder="Search" id="search_box" v-on:input="filterStores()" />
            <div style="overflow-y: scroll" id="store_list" @click="addToStoresList"></div>

            <div class="d-flex align-items-center justify-content-between">
              <button class="btn btn-primary" id="cancelstoresbtn" @click="cancelStoreList">
                {{ $t('Deselect All') }}
              </button>
            </div>
          </div>

          <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group mb-1 p-0 ml-2 mr-2']"></div>
        </div>

        <div class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']" v-if="show_zid_sync_option && zid_status">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="zid_sync_option" v-model="zid_sync_option" />
            <label class="custom-control-label" for="zid_sync_option">{{ $t('Add to Zid') }}</label>
            <small class="form-text text-muted">
              {{ $t('If this option is enabled, category will be also added on ZID platform simultaneously.') }}
            </small>
          </div>
        </div>

        <div class="flex-wrap mb-4">
          <div class="pull-right text-right">
            <button type="submit" class="btn btn-primary pull-right" v-bind:disabled="processing == true">
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Save') }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">{{ $t('If category is inactive all the products under this catgeory will get affected') }}.</p>
        {{ $t('Are you sure you want to proceed?') }}
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t('Cancel') }}
        </button>
        <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true">
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-if="show_category_store_modal" v-on:close="show_category_store_modal = false">
      <template v-slot:modal-header>
        {{ $t('Warning') }}
      </template>
      <template v-slot:modal-body>
        <p> {{ $t('If stores in the main category are modified all the subcategories under this catgeory will get affected') }} </p>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t('Ok') }}
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
      show_category_store_modal: false,
      show_category_store_modal_load: 0,
      api_link:
        this.category_data == null
          ? "/api/add_maincategory"
          : "/api/update_maincategory/" + this.category_data.slack,
      category_slack:
        this.category_data == null ? "" : this.category_data.slack,
      category_name: this.category_data == null ? "" : this.category_data.label,
      category_name_ar:
        this.category_data == null ? "" : this.category_data.label_ar,
      description:
        this.category_data == null ? "" : this.category_data.description,
      description_ar:
        this.category_data == null ? "" : this.category_data.description_ar,

      status:
        this.category_data == null
          ? "1"
          : this.category_data.status == null
          ? ""
          : this.category_data.status.value,
      main_category_id:
        this.category_data == null
          ? ""
          : this.category_data.main_category_id == null
          ? ""
          : this.category_data.main_category_id.id,
      reload_on_submit: {
        type: Boolean,
        default: true,
      },
      category_placeholder: this.$t("Enter category name"),
      category_ar_placeholder: this.$t("Enter category name (Arabic)"),
      description_placeholder: this.$t("Enter description"),
      description_ar_placeholder: this.$t("Enter description (Arabic)"),
      store_type_placeholder: this.$t("Select Store Type"),
      suceess_msg: this.$t("Category created successfully"),
      success: this.$t("SUCCESS"),

      category_applied_on:
        this.category_data == null
          ? "all_stores"
          : this.category_data.category_applied_on,

      tmp_store_list: [],
      final_store_list:
        this.category_data == null
          ? ""
          : this.category_data.category_applicable_stores != null &&
            this.category_data.category_applicable_stores.length > 0
          ? this.category_data.category_applicable_stores
          : "",
      
      // category_image   : (this.category_data.category_image == 'undefined')?null:this.category_data.category_image,
      msg: {
        success: "",
        error: "",
        info: "",
      },
      show_zid_sync_option: this.sync_zid_category,
      zid_sync_option:
        this.category_data != null
          ? this.category_data.zid_category_id != null
            ? true
            : false
          : false,
    };
  },
  props: {
    statuses: Array,
    main_categories: Array,
    stores: [Array, Object],
    category_data: [Array, Object],
    reload_on_submit: {
      type: Boolean,
      default: true,
    },
    sync_zid_category: Boolean,
    zid_status: Boolean,
  },
  mounted() {
    console.log("Add category page loaded");
    console.log(this.category_data);
    console.log(this.category_applied_on);
    if (this.category_data !=null && this.category_data.category_applied_on == "specific_stores")
    {
      this.show_category_store_modal = false;
      this.openStoreList('specific_stores', true);
    }
  },
  methods: {
    submit_form() {
      // this.$off("submit");
      // this.$off("close");

      this.$validator.validateAll().then((result) => {
        // console.log('Reload on submit: ', this.reload_on_submit);
        // console.log(result);
        if (result) {
          this.show_modal = true;
          
          if (this.reload_on_submit == true) {
            this.show_modal = true;
            this.$on("submit", function () {
              this.processing = true;
              this.form_data();
              this.$off("submit");
            });

            this.$on("close", function () {
              this.show_modal = false;
              this.$off("close");
            });
          } else {
             this.$on("submit", function () {
              this.processing = true;
              this.form_data();
              this.$off("submit");
            });

            this.$on("close", function () {
              this.show_modal = false;
              this.$off("close");
            });
          }

          this.$on("close", function () {
            this.show_modal = false;
          });
        }
      });
    },
    openStoreList(value, onLoad = false) {

      if (value == "all_stores") {
        document.querySelector("#stores_section").style.display = "none";
      } else if (value == "specific_stores") {
        document.querySelector("#stores_section").style.display = "block";
        this.filterStores();
      } 

      this.category_applied_on = value;
      if (onLoad == false)
      {
        if (this.show_category_store_modal_load < 1 && this.category_data != null)
        {
          this.showCategoryStoreModal();
          this.show_category_store_modal_load ++;
        }
      }
    },


    showCategoryStoreModal()
    {
      this.show_category_store_modal = true;

      if (this.reload_on_submit == true)
      {
        this.show_category_store_modal = true;
        this.$on("submit", function () {
          this.form_data();
          this.$off("submit");
        });

        this.$on("close", function () {
          this.show_category_store_modal = false;
          this.$off("close");
        });
      }
      else 
      {
        this.form_data();
      }

      this.$on("close", function () {
        this.show_category_store_modal = false;
      });        
    },


    addToStoresList(event) {

      let elements = document.querySelectorAll("[id^='chk_store_']");
      this.tmp_store_list = [];
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_store_list.push(elements[i].id.split("_")[2]);
        } else if (
          elements[i].checked == false &&
          this.tmp_store_list.indexOf(elements[i].id.split("_")[2])
        ) {
          this.tmp_store_list.splice(i, 1);
        }
      }
      this.final_store_list = [...new Set(this.tmp_store_list)]
        .join(",")
        .replace(/(,$)/g, "");

      if (this.show_category_store_modal_load < 1 && this.category_data != null)
      {
        this.showCategoryStoreModal();
        this.show_category_store_modal_load++;
      }
    },

    cancelStoreList(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_store_']");
      for (let i = 0; i < elements.length; i++) {
        elements[i].checked = false;
      }
      this.tmp_store_list = [];
      this.final_store_list = "";
      document.querySelector(
        "#addselectedstoressbtn"
      ).innerHTML = `Add Selected Stores`;
    },

    filterStores() {
      //document.querySelector("#product_list_error").innerHTML = ""
      let search_value = document.querySelector("#search_box").value;
      let store_list = [],
        stores = [];
      let strHTML = `<div class="card product-card" style="width:100%;">
                <div
                  class="card-body p-0 d-flex align-items-center justify-content-between"
                >
                </div>
              </div>`;
      document.querySelector("#store_list").innerHTML = strHTML;
      let storearray =
        this.final_store_list != null
          ? this.final_store_list.split(",")
          : [];
      if (this.stores.length > 0) {
        for (let i = 0; i < this.stores.length; i++) {
          if (
            search_value != "" &&
            this.stores[i].text.includes(search_value)
          ) {
            store_list.push(this.stores[i]);
          }
        }
        if (store_list.length > 0) {
          stores = store_list;
        } else {
          stores = this.stores;
        }
        for (let i = 0; i < stores.length; i++) {
          strHTML += `<div
                class="d-flex flex-column justify-content-start align-items-center mb-4"
                id="store_list_${stores[i].id}">
                <div
                  class="card product-card"
                  id="product_${stores[i].id}"
                  style="width:100%;"
                >
                  <div
                    class="card-body p-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="d-flex flex-row align-items-center">`;
          if (
            // (this.category_applied_on == "specific_stores" &&
            // storearray.indexOf(String(stores[i].id)) != -1)
            storearray.indexOf(String(stores[i].id)) != -1
          ) {
            strHTML += `<input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_store_${stores[i].id}"
                      checked/>`;
          } else {
            strHTML += `<input
                        type="checkbox"
                        class="check mr-2 ml-2"
                        id="chk_store_${stores[i].id}"
                      />`;
          }
          strHTML += `<img src="${this.stores[i].store_logo}" />
                      <div class="product-title ml-2" align="left">
                        ${stores[i].text}
                      </div>
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
                    <p>No Stores Found</p>
                  </div>
                </div>
              </div>`;
      }
      document.querySelector("#store_list").innerHTML = strHTML;
    },

    form_data() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append(
        "category_name",
        this.category_name == null ? "" : this.category_name
      );
      formData.append(
        "category_name_ar",
        this.category_name_ar == null ? "" : this.category_name_ar
      );
      formData.append(
        "description",
        this.description == null ? "" : this.description
      );
      formData.append(
        "description_ar",
        this.description_ar == null ? "" : this.description_ar
      );

      formData.append("category_applied_on", this.category_applied_on);
      formData.append("category_applicable_stores", this.final_store_list);

      formData.append("status", this.status == null ? "" : this.status);
      formData.append(
        "main_category_id",
        this.main_category_id == null ? "" : this.main_category_id
      );
      formData.append(
        "category_image",
        this.$refs.category_image.files.length > 0
          ? this.$refs.category_image.files[0]
          : null
      );
      formData.append("zid_sync_option", this.zid_sync_option);

      // console.log(formData);

      axios
        .post(this.api_link, formData)
        .then((response) => {
          // console.log(response);
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, this.suceess);
            this.$emit("refreshMainCategory", response.data.data);
            this.msg.success = this.suceess_msg;
            this.show_modal = false;

            if (this.reload_on_submit) {
              window.location.href = "/categories";
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
    },
  },
};
</script>
