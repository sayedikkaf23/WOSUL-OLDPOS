<!--<style src="../../../css/inventory_count.css"></style>-->

<template>
  <div class="row">
    <div class="col-md-12 ic-margin">
      <!-- Table -->
      <div class="inventory-count-title d-flex justify-content-between">
        <div>
            <button class="back-btn btn" @click="return_back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="5 0 20 20" class="w-5 h-5 fill-current"><path d="M7.05 9.293L6.343 10 12 15.657l1.414-1.414L9.172 10l4.242-4.243L12 4.343z"></path></svg> <span>Back</span></button>
            <div class="d-flex align-items-end"><div class="h2">Inventory Count</div><span v-if="status_object !== ''" :class="status_object.class+' ml-2 mb-2'">{{ status_object.text }}</span></div>
        </div>
        <div class="d-flex align-items-end" v-if="has_item == true">
            <button class="btn btn-outline-secondary mr-2" @click="delete_inventory_count">Delete Permenantly</button>
            <button class="btn btn-outline-secondary mr-2" @click="change_branch" v-if="status_code === 0">Change Branch</button>
            <button class="btn btn-outline-secondary" @click="submit_inventory_count" v-if="status_code === 0">Submit Count</button>
        </div>
      </div>
      <div class="row mt-5">
          <div class="col">
              <div class="card">
                  <div class="row">
                      <div class="col-6 mb-4">
                          <div class="form-group">
                              <label class="ic-label control-label">Branch</label>
                              <div class="ic-data border-bottom">{{ store.name }}</div>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label class="ic-label control-label">Business Date</label>
                              <div class="ic-data border-bottom">{{ business_date }}</div>
                          </div>
                      </div>
                  </div>
                  <div class="row" v-if="has_item == true">
                      <div class="col-6 mb-4">
                          <div class="form-group">
                              <label class="ic-label control-label">Created By</label>
                              <div class="ic-data border-bottom">{{ user_name }}</div>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label class="ic-label control-label">Updated On</label>
                              <div class="ic-data border-bottom">{{ updated_on }}</div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="row mt-5 mb-3 px-2">
          <div class="col d-flex justify-content-between">
              <label class="ic-label">Items</label>
              <div v-if="status_code != 1">
                  <button class="btn btn-outline-secondary mr-2" @click="show_add_item_modal">Add Items</button>
                  <button class="btn btn-outline-secondary" @click="add_quantity" v-if="has_item == true">Edit Quantities</button>
              </div>
          </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="table-responsive">
              <table
                id="listing-table"
                class="table align-items-center table-flush"
              >
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Original Quantity</th>
                    <th scope="col">Entered Quantity</th>
                    <th scope="col">Variance</th>
                    <th scope="col">Variance Percentage</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- <div class="card-footer py-4">
              <nav aria-label="...">
                <ul class="pagination justify-content-end mb-0">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                      <i class="fas fa-angle-left"></i>
                      <span class="sr-only">Previous</span>
                    </a>
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#"
                      >2 <span class="sr-only">(current)</span></a
                    >
                  </li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">
                      <i class="fas fa-angle-right"></i>
                      <span class="sr-only">Next</span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <modalcomponent v-if="show_inventory_count_delete_modal" v-on:close="show_inventory_count_delete_modal = false">
        <template v-slot:modal-header>
            Delete inventory Count Permenantly!
        </template>
        <template v-slot:modal-body>
            <span class="text-center">
              Are you sure you want to delete this inventory count?
            </span>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('No') }}</button>
            <button type="button" class="btn btn-danger" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Yes') }}</button>
        </template>
    </modalcomponent>

    <modalcomponent v-if="show_inventory_count_submit_modal" v-on:close="show_inventory_count_submit_modal = false">
        <template v-slot:modal-header>
            Submit inventory Count
        </template>
        <template v-slot:modal-body>
            <span class="text-center">
              Are you sure you want to submit this inventory count?
            </span>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('No') }}</button>
            <button type="button" class="btn btn-danger" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Yes') }}</button>
        </template>
    </modalcomponent>

    <modalcomponent v-if="show_item_modal" v-on:close="show_item_modal = false">
        <template v-slot:modal-header>
            Add items
        </template>
        <template v-slot:modal-body>
            <div class="form-group">
              <label for="select-store" class="control-label">
                Items
              </label>
              <!-- <vue-select class="select-store" name="select_store"
                      :options="store_option" :model.sync="store_value"
                      :searchable="true" language="en-US">
              </vue-select> -->
              <Select2
              v-model="available_item_value"
              :options="available_item_options"
              :value="available_item_value"
              style="width:100% !important"
              placeholder="Choose..."
              :settings="{ multiple: true }"
            />
              <div class="col-sm-4">
              </div>
            </div>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
            <button type="button" class="btn btn-primary" @click="add_items" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Save') }}</button>
        </template>
    </modalcomponent>

    <modalcomponent v-if="show_store_modal" v-on:close="show_store_modal = false">
        <template v-slot:modal-header>
            Change Branch
        </template>
        <template v-slot:modal-body>
            <div class="form-group">
              <label for="select-store" class="control-label">
                Branch
              </label>
              <!-- <vue-select class="select-store" name="select_store"
                      :options="store_option" :model.sync="store_value"
                      :searchable="true" language="en-US">
              </vue-select> -->
              <Select2
              v-model="store_value"
              :options="store_options"
              :value="store_value"
              style="width:100% !important"
            />
              <div class="col-sm-4">
              </div>
            </div>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
            <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Save') }}</button>
        </template>
    </modalcomponent>

    
    <modalcomponent v-if="show_quantities_modal" v-on:close="show_quantities_modal = false">
        <template v-slot:modal-header>
            Edit Quantities
        </template>
        <template v-slot:modal-body>
            <p
              v-html="quantities_server_errors"
              v-bind:class="[error_class]"
            ></p>
            <form data-vv-scope="quantities_form">
              <div v-for="item in items" :key="item.name" class="form-group">
                <label for="select-store" class="control-label">
                  {{ item.name }}
                </label>
                <!-- <vue-select class="select-store" name="select_store"
                        :options="store_option" :model.sync="store_value"
                        :searchable="true" language="en-US">
                </vue-select> -->
                <input
                v-model="item.entered_quantity"
                v-validate="'required|numeric'"
                style="width:100% !important"
                :name="item.name"
              />
              <span
                v-bind:class="{
                  error: errors.has('quantities_form.'+ item.name),
                }">
                {{ errors.first("quantities_form."+ item.name) }}</span>
              </div>
            </form>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
            <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Save') }}</button>
        </template>
    </modalcomponent>

  </div>
</template>
<script>
"use strict";

// import vueSelect from 'vue-select2/dist/vue-select.js';
import Select2 from "v-select2-component";
import moment from "moment";

export default {
  components: { 
    Select2,
    "vue-select": require("vue-select2/src/vue-select.js")
   },
  data() {
    return {
      processing: false,
      modal: false,
      show_store_modal: false,
      show_item_modal: false,
      show_quantities_modal: false,
      show_inventory_count_delete_modal: false,
      show_inventory_count_submit_modal: false,
      has_item: this.ic_data.has_item,
      inventory_count_id: this.ic_data.inventory_count_id,
      user_name: this.ic_data.user_name != ''? this.ic_data.user_name : '',
      business_date: this.ic_data.business_date != ''? moment(this.ic_data.business_date).format('DD-MM-YYYY') : '-',
      updated_on: this.ic_data.updated != ''? moment(this.ic_data.updated).format('DD-MM-YYYY') : '',
      items: [],
      available_items: [],
      store_options: [],
      available_item_options: [],
      item_options: [],
      store_value: '',
      available_item_value: [],
      status_code: this.ic_data.status !== ''? this.ic_data.status : '',
      status_arr: [
        {
          class: 'badge badge-secondary',
          text: 'draft'
        },
        {
          class: 'badge badge-info',
          text: 'closed'
        }
      ],
      quantities_server_errors: '',     
      error_class: ''
    };
  },
  items_saved: null,
  props: {
    ic_data: [Array, Object],
    store: [Array, Object],
    stores: Array
  },
  mounted(){
    this.store_options = this.get_option_array(this.stores);
    this.get_items();
  },
  filters: {},
  methods: {
    show_add_item_modal(){
      this.$off("close");

      this.processing = true;

      this.get_items(true);

      this.$on("close", function() {
        this.show_item_modal = false;
      });
    },
    change_branch(){
      this.$off("submit");
      this.$off("close");

      this.show_store_modal = true;

      this.$on("submit", function() {
            this.processing = true;
            var formData = new FormData();

            formData.append("access_token", window.settings.access_token);
            formData.append("store_id", this.store_value);

            axios
              .post('/api/inventory_count/change_branch', formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  if(response.data.data.branch === 0)
                    this.$toastr.i(response.data.msg);
                  else if(response.data.data.branch === 1){
                    this.store.id = response.data.data.store_id;
                    this.store.name = response.data.data.store_name;

                    this.$toastr.s(response.data.msg);
                  }

                  this.show_store_modal = false;
                  this.processing = false;
                }
              })
              .catch((error) => {
                console.log(error);
              });
      });
      
      this.$on("close", function() {
        this.show_store_modal = false;
      });
    },
    add_items(){
      this.processing = true;

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);

      let item_value = JSON.stringify(this.available_item_value);

      formData.append("product_ids", item_value);

      axios.post('/api/inventory_count/add_item', formData).then((response) => {
          if(response.data.status_code == 200) {
              this.user_name = response.data.data.user_name;
              this.business_date = moment(response.data.data.business_date).format('DD-MM-YYYY');
              this.updated_on = moment(response.data.data.updated).format('DD-MM-YYYY');
              this.status_code = response.data.data.status;
              this.has_item = true;
              this.get_items();
              this.updateValues();
              this.show_item_modal = false;
              this.processing = false;
          }
      })
      .catch((error) => {
          console.log(error);
      });
    },
    add_quantity(){
      this.$off("submit");
      this.$off("close");

      this.show_quantities_modal = true;

      this.$on("submit", function() {
        this.$validator.validateAll("quantities_form").then((isValid) => {
          if (isValid) {
            this.processing = true;
            var formData = new FormData();
            var quantities = JSON.stringify(this.items);

            formData.append("access_token", window.settings.access_token);
            formData.append("quantities", quantities);

            axios
              .post('/api/inventory_count/add_quantities', formData)
              .then((response) => {
                if (response.data.status_code == 200) 
                  this.updateValues();

                  this.show_quantities_modal = false;
                  this.processing = false;
              })
              .catch((error) => {
                console.log(error);
                this.items.forEach(item => {
                  item.entered_quantity = '';
                });
              });
          }
        });
      });

      this.$on("close", function() {
        this.show_quantities_modal = false;
      });
    },
    delete_inventory_count(){
      this.$off("submit");
      this.$off("close");

      this.show_inventory_count_delete_modal = true;

      this.$on("submit", function() {
        this.processing = true;

        var formData = new FormData();
        formData.append("access_token", window.settings.access_token);

        axios.post('/api/inventory_count/delete', formData).then((response) => {
            if(response.data.status_code == 200){
              window.location.href = '/inventory-count';

              this.processing = false;
              this.show_inventory_count_delete_modal = false;
            }
        })
        .catch((error) => {
            console.log(error);
        });
      });

      this.$on("close", function() {
        this.show_inventory_count_delete_modal = false;
      });
    },
    submit_inventory_count(){
      this.$off("submit");
      this.$off("close");

      this.show_inventory_count_submit_modal = true;

      var quantity_null_check = false;

      for (let item of this.items) {
        quantity_null_check = (item.entered_quantity === '' || item.entered_quantity === null) ? true : false;
      }

        this.$on("submit", function() {
          if(quantity_null_check){
            this.show_inventory_count_submit_modal = false;
            this.$toastr.e("Entered Quantity cannot be null");
          }
          else{
            this.processing = true;

            var formData = new FormData();
            var quantities = JSON.stringify(this.items);

            formData.append("access_token", window.settings.access_token);
            formData.append("business_date", this.business_date);
            formData.append("quantities", quantities);

            axios.post('/api/inventory_count/submit', formData).then((response) => {
                if(response.data.status_code == 200){
                  this.status_code = response.data.data.status;
                  this.updateValues();

                  this.processing = false;
                  this.show_inventory_count_submit_modal = false;
                  this.$toastr.s(response.data.msg);
                }
            })
            .catch((error) => {
                console.log(error);
            });
          }
        });

      this.$on("close", function() {
        this.show_inventory_count_submit_modal = false;
      });
    },
    get_items(show = null){
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("store_id", this.store.id);

      axios.post('/api/inventory_count/get_items', formData).then((response) => {
          if(response.data.status_code == 200) {
              this.available_items = response.data.data.available_items;
              this.items = response.data.data.items;

              this.available_item_options = this.get_option_array(this.available_items);

              if(show){
                this.show_item_modal = true;
                this.processing = false;
              }
          }
      })
      .catch((error) => {
          console.log(error);
      });
    },
    return_back(){
      window.location.href = '/inventory-count';
    },   
    get_option_array(array){
      let option_array = [];

      array.forEach(item => {
        option_array.push({id: item.id, text: item.name});
      });

      return option_array;
    },
    updateValues(){
        event = new CustomEvent('load-count-item-table');
        document.dispatchEvent(event);
    }
  },
  computed: {
    status_object(){
      return this.status_code !== '' ? this.status_arr[this.status_code] : '';
    }
  }
};
</script>
