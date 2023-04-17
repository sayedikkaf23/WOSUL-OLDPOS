<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="tax_name_id == ''">{{ $t("Add Tax Name") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Tax Name") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="tax_name">{{ $t("Tax Name") }}</label>
                        <input type="text" name="tax_name" v-model="tax_name" v-validate="'required|max:250'" 
                            class="form-control form-control-custom" autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('tax_name') }">{{ errors.first('tax_name') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-2' : 'col-md-12', 'form-group']">
                        <label for="percentage">{{ $t("Tax percentage") }}</label>
                        <input type="number" name="percentage" id="tax_percentage" v-model="percentage" v-validate="'required|decimal'" 
                            class="form-control form-control-custom"  autocomplete="off" step="0.01" min="0">
                        <span v-bind:class="{ 'error' : errors.has('percentage') }">{{ errors.first('percentage') }}</span> 
                    </div>
                    <!-- <div v-bind:class="[reload_on_submit ? 'col-md-1' : 'col-md-12', 'form-group']">
                        <label for="percentage">{{ $t("Visibility") }}</label>
                        <input type="checkbox" name="is_visible" id="is_visible" v-model="is_visible" style="margin-left:9px;"/>
                        <span v-bind:class="{ 'error' : errors.has('is_visible') }">{{ errors.first('is_visible') }}</span> 
                    </div> -->
                    <!-- <div v-bind:class="[reload_on_submit ? 'col-md-2' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t("Choose Status..") }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div> -->
                </div>

                <div class="flex-wrap mb-4">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> 
                            <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>
            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                <!-- <p v-if="status == 0">{{ $t("You are going to Inactive the tax! ") }}</p> -->
                {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> 
                    <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Continue") }}</button>
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
                api_link        : (this.tax_name_data == null)?'/api/add_tax_name':'/api/update_tax_name/'+this.tax_name_data.id,

                tax_name_id  : (this.tax_name_data == null)?'':this.tax_name_data.id,
                tax_name  : (this.tax_name_data == null)?'':this.tax_name_data.tax_name,
                percentage  : (this.tax_name_data == null)?'':this.tax_name_data.percentage,
                // status          : (this.tax_name_data == null)?'1':(this.tax_name_data.status == null)?'':this.tax_name_data.status.value,
                // is_visible: (this.tax_name_data == null || this.tax_name_data.is_visible == null || this.tax_name_data.is_visible == 0)? false : true,
                is_visible: true,
                success:this.$t("SUCCESS")
            }
        },
        props: {
            tax_names_list_route: String,
            statuses: Array,
            tax_name_data: [Array, Object],
            reload_on_submit: {
              type : Boolean,
              default : true,
            }
        },
        computed : {
            query_param(){
                var query_param = window.location.href;
                query_param = query_param.split('?');
                return (query_param.length > 1) ? query_param[query_param.length - 1] : null;
            }
        },
        mounted() {
            console.log('Add Tax Code page loaded');
        },
        methods: {

            submit_form(){

                // this.$off("submit");
                // this.$off("close");

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        
                            if(this.reload_on_submit){

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
                                               
                        this.$on("close",function () {
                            this.show_modal = false;
                        });
                    }
                });
            },
            form_data(){
                this.processing = true;
                var formData = new FormData();
                
                formData.append("access_token", window.settings.access_token);
                formData.append("tax_name", (this.tax_name == null)?'':this.tax_name.trim());
                formData.append("percentage",(this.percentage == null)?'0':this.percentage);
                formData.append("is_visible", this.is_visible);
                // formData.append("status", (this.status== null)?'':this.status);

                axios.post(this.api_link, formData).then((response) => {
                    
                    if(response.data.status_code == 200) {
                        
                        this.show_response_message(response.data.msg, this.success);
                        this.processing = false;                    
                        this.show_modal = false;

                        window.location.href = (this.query_param == null) ? this.tax_names_list_route : '/edit_tax_code/'+this.query_param;
                        
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
