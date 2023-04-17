<!--<style src="../../../css/inventory_count.css"></style>-->

<template>
  <div class="row">
    <div class="col-md-12">
      <!-- Table -->
      <div class="inventory-count-title d-flex justify-content-between">
        <h3>Inventory Count</h3>
        <button class="btn btn-primary btn-action-primary ml-2" @click="add_new_inventory_count">New Inventory Count</button>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="d-flex justify-content-between px-4 pt-4 pb-4 border-bottom">
              <div>
                <a href="#" class="mr-3" :class="{ 'status-selected': status === null }" @click="submitFilters(null)">All</a>
                <a href="#" class="mr-3" :class="{ 'status-selected': status === 0 }" @click="submitFilters(0)">Draft</a>
                <a href="#" class="mr-3" :class="{ 'status-selected': status === 1 }" @click="submitFilters(1)">Closed</a>
              </div>
              <div>
                <button type="button" class="btn btn-outline-secondary" @click="add_filter"><i class="fa fa-filter" style="font-size: 13px;" aria-hidden="true"></i><span class="pl-2">filter</span></button>
              </div>
            </div>
            <div class="table-responsive">
              <table
                id="listing-table"
                class="table align-items-center table-flush"
              >
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Reference No</th>
                    <th scope="col">Branch</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Business Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Updated On</th>
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

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
        <template v-slot:modal-header>
            New Inventory Count
        </template>
        <template v-slot:modal-body>
            <p
              v-html="new_inventory_count_server_errors"
              v-bind:class="[error_class]"
            ></p>
            <form data-vv-scope="new_inventory_count_form">
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
                 v-validate="'required|numeric'"
                :options="store_options"
                :value="store_value"
                style="width:100% !important"
                name="stores"
                placeholder="Choose..."
              />
              <span
                v-bind:class="{
                  error: errors.has('new_inventory_count_form.stores'),
                }">
                {{ errors.first("new_inventory_count_form.stores") }}</span>
                <div class="col-sm-4">
                </div>
              </div>
            </form>
        </template>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Close') }}</button>
            <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Save') }}</button>
        </template>
    </modalcomponent>

    <modalcomponent v-if="show_filters_modal" v-on:close="show_filters_modal = false">
        <template v-slot:modal-header>
            New Inventory Count
        </template>
        <template v-slot:modal-body>
              <div class="form-group">
                <label class="control-label">Reference No</label>
                <Select2
                v-model="reference_no"
                :options="reference_nos"
                :value="reference_no"
                style="width:100% !important"
              />
              </div>

              <div class="form-group">
                <label class="control-label">Branch</label>
                <Select2
                v-model="branch"
                :options="branches"
                :value="branch"
                style="width:100% !important"
              />

              </div><div class="form-group">
                <label class="control-label">Created By</label>
                <Select2
                v-model="user_name"
                :options="user_names"
                :value="user_name"
                style="width:100% !important"
              />
              </div>
              
              <div class="form-group d-grid">
                  <label class="control-label">Business Date</label>
                  <!-- <date-picker type="month" :lang='date.lang' :format="date.format" v-model="month" @change="month_change" input-class="form-control bg-white"></date-picker> -->
                  <date-range-picker
                          ref="picker"
                          opens="center"
                          :locale-data="customPickerData"
                          :timePicker="false"
                          :timePicker24Hour="false"
                          :showWeekNumbers="true"
                          :showDropdowns="true"
                          :autoApply="true"
                          v-model="business_dates"
                      >
                      <template v-slot:input="picker" style="min-width: 350px;">
                          {{ picker.startDate | date }} - {{ picker.endDate | date }}
                      </template>
                  </date-range-picker>
              </div>
              
              <div class="form-group">
                <label class="control-label">Status</label>
                <Select2
                v-model="status"
                :options="status_filters"
                :value="status"
                style="width:100% !important"
              />
              </div>
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
import DateRangePicker from 'vue2-daterange-picker';
import moment from "moment";
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

export default {
  components: { 
    DateRangePicker,
    Select2,
    "vue-select": require("vue-select2/src/vue-select.js")
   },
  data() {
    let today = new Date();
    let startDate = new Date();
    let endDate = today;
    startDate.setDate(startDate.getDate() - 30)
    return {
      processing: false,
      modal: false,
      show_modal: false,
      show_filters_modal: false,
      store_options: [],
      reference_no: '',
      reference_nos: [],
      branch: '',
      branches: [],
      user_name: '',
      user_names: [],
      status_filter: '',
      status_filters: [
        {id: 0, text: 'Draft'},
        {id: 1, text: 'Closed'}
      ],
      business_dates: {startDate, endDate},
      customPickerData: {
          direction: 'ltr',
          format: 'dd-mm-yyyy',
          separator: ' - ',
          applyLabel: 'Apply',
          cancelLabel: 'Cancel',
          weekLabel: 'W',
          customRangeLabel: 'Custom Range',
          daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
          monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          firstDay: 0
      },
      store_value: '',
      status: null,
      new_inventory_count_server_errors: '',
      error_class: ''
    };
  },
  props: {
    stores: Array
  },
  mounted(){
    this.store_options = this.get_option_array(this.stores);
  },
  filters: {
    date (val) {
        let date = new Date(val);
        return val ? moment(date).format('DD-MM-YYYY') : ''
    }
  },
  methods: {
    add_new_inventory_count(){
      this.$off("submit");
      this.$off("close");

      this.show_modal = true;

      this.$on("submit", function() {
        this.$validator.validateAll("close_register_form").then((isValid) => {
          if (isValid) {
            this.processing = true;
            var formData = new FormData();

            formData.append("access_token", window.settings.access_token);
            // formData.append("closing_amount", this.closing_amount);
            formData.append("store_id", this.store_value);

            axios
              .post('/api/inventory_count/add_inventory_count', formData)
              .then((response) => {
                if (response.data.status_code == 200) 
                  window.location.href = '/inventory-count/view';
              })
              .catch((error) => {
                console.log(error);
              });
          }
        });
      });

      this.$on("close", function() {
        this.show_modal = false;
      });
    },
    add_filter(){
      this.$off("submit");
      this.$off("close");

      this.show_filters_modal = true;

      this.$on("submit", function() {
        this.processing = true;
        this.submitFilters(this.status);

        this.processing = false;
        this.show_filters_modal = false;
      });

      this.$on("close", function() {
        this.show_filters_modal = false;
      });
    },
    get_filter_data(){
      var formData = new FormData();

      formData.append("access_token", window.settings.access_token);
      axios
        .post('/api/inventory_count/get_filter_data', formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.reference_nos = this.get_filter_option_array(response.data.data.reference_nos);
            this.branches = this.get_option_array(response.data.data.store_names);
            this.user_names = this.get_option_array(response.data.data.user_names);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    submitFilters(status = null){
      this.status = status;
      event = new CustomEvent('filters',{
          detail: {
              reference_no: this.reference_no,
              branch: this.branch,
              user_name: this.user_name,
              status: this.status,
              start_date: moment(this.business_dates.startDate).format('YYYY-MM-DD'),
              end_date: moment(this.business_dates.endDate).format('YYYY-MM-DD')
          }
      });
      document.dispatchEvent(event);
    },
    get_option_array(array){
      let option_array = [];

      array.forEach(item => {
        option_array.push({id: item.id, text: item.name});
      });

      return option_array;
    },
    get_filter_option_array(array){
      let option_array = [];

      array.forEach(item => {
        option_array.push({id: item.name, text: item.name});
      });

      return option_array;
    },
  },
  beforeMount(){
    this.get_filter_data()
  },
};
</script>
