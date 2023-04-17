<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="price_slack == ''">{{ $t("Add Price") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Price") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <name for="name">{{ $t("Name") }}</name>
                        <input type="text" name="name" v-model="name" v-validate="'required|max:150'" class="form-control form-control-custom" :placeholder="enter_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('name') }">{{ errors.first('name') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <name for="name_ar">{{ $t("Name (Arabic)") }}</name>
                        <input type="text" name="name_ar" v-model="name_ar" v-validate="'required|max:150'" class="form-control form-control-custom" :placeholder="enter_name_ar"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('name_ar') }">{{ errors.first('name_ar') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <name for="status">{{ $t("Status") }}</name>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                   
                </div>
                <div class="form-row mb-2" v-if="stores.length">
                    <div class="form-group col-md-12">
                        <label for="status"
                        ><label for="select_all_stores" 
                        ><input
                        type="checkbox"
                        id="select_all_stores"
                        v-model="select_all_stores"
                        />
                        {{ $t("Select All Stores") }}
                    </label></label></div>
                    <div v-for="(store,index) in stores" :key="index" class="form-group col-md-2">
                        <div  class="card" @click="select_store(index)">
                        <div class="card-body text-center" :class="[ store.is_selected ? 'border border-2 border-primary' : '' ] ">
                            <img class="card-img-top img img-responsive " style="width:50px;height:50px;" :src="store.logo" alt="No Store Logo Found">
                            <h5 class="card-title pt-3">{{ store.name }}</h5>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="error" v-show="store_selection_error"> {{ $t('Please select a store') }} </span> 

                    </div>
                </div>

                 <div class="flex-wrap mb-4">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>

            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t('Confirm') }}
            </template>
            <template v-slot:modal-body>
                {{ $t('Are you sure you want to proceed?') }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Cancel') }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Continue') }}</button>
            </template>
        </modalcomponent>
        
    </div>
</template>

<script>
    'use strict';

    import { extend } from 'vee-validate';

    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : (this.price_data == null)?'/api/add_price':'/api/update_price/'+this.price_data.slack,
                price_slack  : (this.price_data == null)?'':this.price_data.slack,
                name : (this.price_data == null)?'':this.price_data.name,
                name_ar : (this.price_data == null)?'':this.price_data.name_ar,
                status : (this.price_data == null)?'1':(this.price_data.status == null)?'':this.price_data.status.value,
                enter_name:this.$t('Please enter name'),
                enter_name_ar:this.$t('Please enter name (Arabic)'),
                success:this.$t('SUCCESS'),
                reload_on_submit: {
                  type : String,
                  default : true,
                },
                select_all_stores: false,
                stores : [],
                store_selection_error : false
            }
        },
        props: {
            statuses: Array,
            store_data: Array,
            reload_on_submit: {
              type : String,
              default : true,
            },
            price_data : [Object,Array]
        },
        
        watch : {
            select_all_stores : function(value){
                if(value){
                    for (let i = 0; i < this.stores.length; i++) {
                        this.stores[i].is_selected = true;
                    }
                }else{
                    for (let i = 0; i < this.stores.length; i++) {
                        this.stores[i].is_selected = false;
                    }
                }
            }
        },
        created() {
            if(this.price_slack == ''){
                // add form
                var all_stores = [];
                if(this.store_data.length){
                    for (let i = 0; i < this.store_data.length; i++) {
                        let store_template = {
                            slack : this.store_data[i].slack,
                            name : this.store_data[i].name,
                            logo : this.store_data[i].store_logo_path,
                            is_selected : false
                        };
                        this.stores.push(store_template);
                    }
                }

            }
        },
        methods: {
            select_store(index){
                this.stores[index].is_selected = !this.stores[index].is_selected;
            },
            submit_form(){

                var selected_stores = [];
                if(this.price_slack == '' && this.stores.length ){
                    for (let i = 0; i < this.stores.length; i++) {
                        if(this.stores[i].is_selected){
                            selected_stores.push(this.stores[i]);
                        }
                    }
                    if(selected_stores.length == 0){
                        this.store_selection_error = true;
                        return false;
                    }
                }

                this.$off("submit");
                this.$off("close");

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        this.$on("submit",function () {
                            
                            this.processing = true;
                            var formData = new FormData();

                            formData.append("access_token", window.settings.access_token);
                            formData.append("name", (this.name == null)?'':this.name);
                            formData.append("name_ar", (this.name_ar == null)?'':this.name_ar);
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("selected_stores", JSON.stringify(selected_stores));

                            axios.post(this.api_link, formData).then((response) => {

                                // console.log(response);
                                
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, this.success);
                                    // this.$emit('refreshMeasurements', response.data.data);                    
                                    this.show_modal = false;
                                    this.processing = false;
                                    
                                    // setTimeout(function(){
                                    //     location.reload();
                                    // }, 1000);

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
                        });
                        
                        this.$on("close",function () {
                            this.show_modal = false;
                        });
                    }
                });
            }
        }
    }
</script>
