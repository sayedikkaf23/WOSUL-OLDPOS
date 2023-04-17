<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <p class="alert alert-success" v-show="msg.success.length">{{ $t(msg.success) }}</p>

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="supplier_slack == ''">{{ $t("Add Supplier") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Supplier") }}</span>
                    </div>
                    
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="supplier_name">{{ $t("Supplier Name") }}</label>
                        <input type="text" name="supplier_name" v-model="supplier_name" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="this.enter_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('supplier_name') }">{{ errors.first('supplier_name') }}</span> 
                    </div>
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="tax_number">{{ $t("Tax Number") }}</label>
                        <input type="text" name="tax_number" v-model="tax_number" class="form-control form-control-custom" :placeholder="this.enter_tax_number"  autocomplete="off">
                    </div>
                </div>
                

                <div class="d-flex flex-wrap mb-1">
                    <div class="mr-auto">
                        <span class="text-subhead">{{ $t("Contact Information") }}</span>
                    </div>
                    <div class="">
                        
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="phone">{{ $t("Contact No.") }}</label>
                        <input type="text" name="phone" v-model="phone" v-validate="'min:10|max:15'" class="form-control form-control-custom" :placeholder="this.enter_contactno" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('phone') }">{{ errors.first('phone') }}</span> 
                    </div>
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="email">{{ $t("Email") }}</label>
                        <input type="text" name="email" v-model="email" v-validate="'email'" class="form-control form-control-custom" :placeholder="this.enter_email" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('email') }">{{ errors.first('email') }}</span> 
                    </div>
                </div>
                <div class="form-row mb-2">
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="website">{{ $t("Website") }}</label>
                        <input type="text" name="website" v-model="website" class="form-control form-control-custom" :placeholder="this.enter_website" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('website') }">{{ errors.first('website') }}</span> 
                    </div>
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="organization_name">{{ $t("Organization Name") }}</label>
                        <input type="text" name="organization_name" v-model="organization_name" class="form-control form-control-custom" :placeholder="this.enter_organization_name" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('organization_name') }">{{ errors.first('organization_name') }}</span> 
                    </div>
                </div>
                    <div class="form-row mb-2">
                   <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="building_number">{{ $t("Building Number") }}</label>
                        <input type="text" name="building_number" v-model="building_number" class="form-control form-control-custom" :placeholder="this.enter_building_number" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('building_number') }">{{ errors.first('building_number') }}</span> 
                    </div>
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                      <label for="street_name">{{ $t("Street Name") }}</label>
                      <input type="text" name="street_name" v-model="street_name" class="form-control form-control-custom" :placeholder="this.enter_street_name">
                        <span v-bind:class="{ 'error' : errors.has('street_name') }">{{ errors.first('street_name') }}</span>
                    </div>
                </div>
                       <div class="form-row mb-2">
                     <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="district">{{ $t("District") }}</label>
                        <input type="text" name="district" v-model="district" class="form-control form-control-custom" :placeholder="this.enter_district" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('district') }">{{ errors.first('district') }}</span> 
                    </div>
                        <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                    <label for="country">{{ $t("Country") }}</label>
                    <select
                    name="country"
                    v-model="country"
                    v-validate="'required'"
                    class="form-control form-control-custom custom-select"
                    >
                    <option value="">{{ $t("Choose Country..") }}</option>
                    <option
                    v-for="(country_item, index) in country_list"
                    v-bind:value="country_item.country_id"
                    v-bind:key="index"
                    >
                    {{ country_item.code }} - {{ country_item.name }}
                    </option>
                    </select>
                    <span v-bind:class="{ error: errors.has('country') }">{{
                    errors.first("country")
                    }}</span>
                    </div>
                </div>
                       <div class="form-row mb-2">
                     <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="city">{{ $t("City") }}</label>
                        <input type="text" name="city" v-model="city" class="form-control form-control-custom" :placeholder="this.enter_city" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('city') }">{{ errors.first('city') }}</span> 
                    </div>
                        <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                      <label for="pincode">{{ $t("Pincode") }}</label>
                      <input type="text" name="pincode" v-model="pincode" v-validate="'max:15'" class="form-control form-control-custom" :placeholder="this.enter_pincode">
                        <span v-bind:class="{ 'error' : errors.has('pincode') }">{{ errors.first('pincode') }}</span>
                    </div>
                   
                </div>
                <div class="form-row mb-2">
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="address">{{ $t("Address") }} ( {{ $t("optional") }} )</label>
                        <textarea name="address" v-model="address" v-validate="'max:65535'" class="form-control form-control-custom" rows="5" :placeholder="this.enter_address"></textarea>
                        <span v-bind:class="{ 'error' : errors.has('address') }">{{ errors.first('address') }}</span>
                    </div>
                     <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                      <label for="other_seller_id">{{ $t("Other Seller Id") }}</label>
                      <input type="text" name="other_seller_id" v-model="other_seller_id" class="form-control form-control-custom" :placeholder="this.enter_other_seller_id">
                        <span v-bind:class="{ 'error' : errors.has('other_seller_id') }">{{ errors.first('other_seller_id') }}</span>
                    </div>
                
                </div>


                <div class="d-flex flex-wrap mb-1">
                    <div class="mr-auto">
                        <span class="text-subhead">{{ $t("Status Information") }}</span>
                    </div>
                    <div class="">
                        
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div class="" v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t("Choose Status..") }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>



                 <div
            class="col-md-4
              'form-group mb-1 p-0 ml-2 mr-2"
            id="stores_section"
            :style="supplier_data==null ? 'display:block;' : 'display:none;'"
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
                </div>

                <div class="flex-wrap mb-4">
                    <div class="pull-right text-right">
                        <button type="submit" class="btn btn-primary " v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>

            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                <p v-if="status == 0"> {{ $t("If supplier is inactive all the products with this supplier will get affected.") }}</p>
                 {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Continue") }} </button>
            </template>
        </modalcomponent>
        
    </div>
</template>

<script>
    'use strict';
    
    export default {
        data(){
        
            return{
                final_store_list:"",
                tmp_store_list: [],
                server_errors   : "",
                error_class     : "",
                processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : (this.supplier_data == null)?'/api/add_supplier':'/api/update_supplier/'+this.supplier_data.slack,

                supplier_slack  : (this.supplier_data == null)?"":this.supplier_data.slack,
                supplier_name   : (this.supplier_data == null)?"":this.supplier_data.name,
                tax_number   : (this.supplier_data == null)?"":this.supplier_data.tax_number,
                status          : (this.supplier_data == null)?'1':(this.supplier_data.status == null)?'':this.supplier_data.status.value,
                address         : (this.supplier_data == null)? '' : this.supplier_data.address,
                phone           : (this.supplier_data == null)? '' : this.supplier_data.phone,
                email           : (this.supplier_data == null)? '' : this.supplier_data.email,
                pincode         : (this.supplier_data == null)? '' : this.supplier_data.pincode,
                website         : (this.supplier_data == null)? '' : this.supplier_data.website,
                organization_name : (this.supplier_data == null)? '' : this.supplier_data.organization_name,
                building_number : (this.supplier_data == null)? '' : this.supplier_data.building_number,
                street_name : (this.supplier_data == null)? '' : this.supplier_data.street_name,
                district : (this.supplier_data == null)? '' : this.supplier_data.district,
                city : (this.supplier_data == null)? '' :this.supplier_data.city,
                country: this.supplier_data == null  ? "" : this.supplier_data.country_id == null  ? "" : this.supplier_data.country_id,
                other_seller_id : (this.supplier_data == null)? '' : this.supplier_data.other_seller_id,
                reload_on_submit : true,
                enter_name:this.$t("Please enter supplier name"),
                enter_tax_number:this.$t("Please enter supplier tax number"),
                enter_address:this.$t("Enter address"),
                enter_email:this.$t("Please enter contact email"),
                enter_contactno:this.$t("Please enter contact number"),
                enter_pincode:this.$t("Enter pincode"),
                enter_website:this.$t("Please enter website "),
                enter_organization_name:this.$t("Please enter organization name"),
                enter_building_number:this.$t("Please enter building number"),
                enter_street_name:this.$t("Please enter street name"),
                enter_district:this.$t("Please enter district"),
                enter_city:this.$t("Please enter city"),
                enter_other_seller_id: this.$t("Please enter other seller id"),

                success:this.$t("SUCCESS"),
                success_msg:(this.supplier_data == null)?this.$t("Supplier Created Successfully"):this.$t("Supplier Updated Successfully"),
                msg : {
                    success : '',
                    error : '',
                    info : '',
                },
                
            }
        },
        props: {
            stores: Array,
            statuses: Array,
            country_list: Array,
            supplier_data: [Array, Object],
            selection_stores: [Array, Object],
            reload_on_submit: {
              type : Boolean,
              default : true,
            },
        
             
        },
        mounted() {
          if(this.supplier_data==null)
          {
            this.openStoreList();
          }
            // console.log(this.reload_on_submit);
            console.log('Add supplier page loaded');
            // console.log(this.country_list);
        },
        methods: {
            cancelStoresList(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_store_']");
      for (let i = 0; i < elements.length; i++) {
        elements[i].checked = false;
      }
      this.tmp_store_list = [];
      this.final_store_list = "";
    },
            addToStoresList(event) {
      let elements = document.querySelectorAll("[id^='chk_store_']");
      this.tmp_store_list = [];
      for (let i = 0; i < elements.length; i++) {
        if (elements[i].checked == true) {
          this.tmp_store_list.push(elements[i].id.split("_")[2]);
        } 
      }
      this.final_store_list = [...new Set(this.tmp_store_list)]
        .join(",")
        .replace(/(,$)/g, "");
    },
    addAllStores(event) {
      event.preventDefault();
      let elements = document.querySelectorAll("[id^='chk_store_']");
      this.tmp_store_list = [];
      for (let i = 0; i < elements.length; i++) {
          elements[i].checked = true;
          this.tmp_store_list.push(elements[i].id.split("_")[2]);
      }
      this.final_store_list = [...new Set(this.tmp_store_list)]
        .join(",")
        .replace(/(,$)/g, "");
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
                          <div>Logo</div>
                    </div>
                   <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                          <div>Store Name</div>
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
                      ${stores[i].store_logo}
                      </div>
                      <div class="d-flex flex-row align-items-center justify-content-center w-50"
                          style="gap:5px;">
                      <div class="store-title" style="width:100%;white-space:wrap;">
                        ${stores[i].text}
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
            submit_form(){

                this.$validator.validateAll().then((result) => {
                    if (result) {

                        if(this.reload_on_submit == true){

                            this.show_modal = true;
                            this.$on("submit",function () {
                                this.form_data();
                                this.$off("submit");
                            });

                            this.$on("close",function () {
                                this.show_modal = false;
                                this.$off("close");
                            });

                        }else{
  
                           this.form_data();

                        }

                    }
                });
            },

            form_data(){
              this.processing = true;
              var formData = new FormData();

              formData.append("access_token", window.settings.access_token);
              formData.append("supplier_name", (this.supplier_name == null)?'':this.supplier_name);
              formData.append("address", (this.address == null)?'':this.address);
              formData.append("phone", (this.phone == null)?'':this.phone);
              formData.append("email", (this.email == null)?'':this.email);
              formData.append("pincode", (this.pincode == null)?'':this.pincode);
              formData.append("website", (this.website == null)?'':this.website);
              formData.append("organization_name", (this.organization_name == null)?'':this.organization_name);
              formData.append("other_seller_id", (this.other_seller_id == null)?'':this.other_seller_id);
              formData.append("building_number", (this.building_number == null)?'':this.building_number);
              formData.append("street_name", (this.street_name  == null)?'':this.street_name);
              formData.append("district", (this.district == null)?'':this.district);
              formData.append("country", (this.country == null)?'': this.country);
              formData.append("city", (this.city == null)?'':this.city);
              formData.append("status", (this.status == null)?'':this.status);
              formData.append("tax_number", (this.tax_number == null)?'':this.tax_number);
              formData.append("supplier_applicable_stores",this.final_store_list);
              axios.post(this.api_link, formData).then((response) => {

                  if(response.data.status_code == 200) {
                      this.show_response_message(response.data.msg, this.success);
                      
                      this.$emit('refreshSupplier',response.data.data);
                      this.msg.success = this.success_msg;

                      if(this.reload_on_submit == true){
                        this.show_modal = false;
                        this.processing = false;

                        setTimeout(function(){
                            window.location = "/suppliers";
                         },1000);
                      }

                  }else{
                      this.show_modal = false;
                      this.processing = false;
                      try{
                          var error_json = JSON.parse(response.data.msg);
                          this.loop_api_errors(error_json);
                      }catch(err){
                          this.server_errors = response.data.msg;
                      }
                      this.error_class = 'error';
                  }
              })
              .catch((error) => {
                  console.log(error);
              });
            }
        }
    }
</script>
