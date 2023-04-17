<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="measurement_slack == ''">{{ $t("Add Measurement") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Measurement") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="label">{{ $t("Label") }}</label>
                        <input type="text" name="label" v-model="label" v-validate="'required|max:150'" class="form-control form-control-custom" :placeholder="label_placeholder"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('label') }">{{ errors.first('label') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="measurement_category">{{ $t("Measurement Category") }}</label>
                        <select name="measurement_category" v-model="measurement_category" class="form-control form-control-custom custom-select" @change="showConversions(measurement_category)">
                            <option value="">{{ $t("Choose Measurement Category..") }}</option>
                            <option v-for="(category, index) in measurement_categories" v-bind:value="category.id" v-bind:key="index">
                                {{ category.label }}
                            </option>
                        </select> 
                        <span v-bind:class="{ 'error' : errors.has('measurement_category') }">{{ errors.first('measurement_category') }}</span>
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t("Choose Status..") }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
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
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                <p v-if="status == 0">{{ $t("If measurement unit is inactive all the products using this measurement unit will get affected") }}.</p>
                {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Continue") }}</button>
            </template>
        </modalcomponent>
        
    </div>
</template>

<script>
    'use strict';
    
    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                modal           : false,
                show_modal      : false,
                label_placeholder:this.$t("Please enter label"),
                api_link        : (this.measurement_data == null)?'/api/add_measurement':'/api/update_measurement/'+this.measurement_data.slack,
                measurement_slack  : (this.measurement_data == null)?'':this.measurement_data.slack,
                label : (this.measurement_data == null)?'':this.measurement_data.label,
                measurement_category : (this.measurement_data == null)?'':this.measurement_data.measurement_category_id,
                status : (this.measurement_data == null)?'1':(this.measurement_data.status == null)?'':this.measurement_data.status.value,
                conversion : [Object,Array],
                // conversions : this.measurement_data,
                success:this.$t("SUCCESS"),
                reload_on_submit: {
                  type : String,
                  default : true,
                },
            }
        },
        props: {
            statuses: Array,
            measurement_data: [Array, Object],
            measurement_categories: [Array, Object],
            reload_on_submit: {
              type : String,
              default : true,
            }
        },
        mounted() {
            console.log('Add measurement unit page loaded');
            // console.log(this.measurement_data);
            // console.log(this.conversions);
            // return false;
        },
        methods: {
            submit_form(){

                this.$off("submit");
                this.$off("close");

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        this.$on("submit",function () {
                            
                            this.processing = true;
                            var formData = new FormData();

                            formData.append("access_token", window.settings.access_token);
                            formData.append("label", (this.label == null)?'':this.label);
                            formData.append("category_id", (this.measurement_category == null)?'':this.measurement_category);
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("slack", (this.measurement_slack == null)?'':this.measurement_slack);
                            
                            axios.post(this.api_link, formData).then((response) => {
                                
                                if(response.data.status_code == 200) {

                                    console.log(response.data);
                                    this.show_response_message(response.data.msg, this.success);
                                    this.$emit('refreshMeasurements', response.data.data);                    
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
            },
            showConversions(category_id){

                let formData = new FormData();
                formData.append("access_token", window.settings.access_token);
                formData.append('category_id',category_id);

                axios.post('/api/get_conversion_units', formData).then((rs) => {
                    
                    this.conversions = [];
                    rs.data.data.forEach((item) => {
                         var conversion_template = {
                            from_measurement_unit : 1,
                            from_measurement_label : this.label,
                            to_measurement_id : item.id,
                            to_measurement_label : item.label,
                            to_measurement_value : 0
                        };
                        this.addItemToConversion(conversion_template);
                    });

                });
            },
            addItemToConversion(item){
                this.conversions.push(item);
                console.log(this.conversions);
            }
        }
    }
</script>
