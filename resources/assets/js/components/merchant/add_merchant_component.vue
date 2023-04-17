<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="merchant_slack == ''">{{ $t("Add Merchant") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Merchant") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Basic Information") }}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-4">
                        <label for="name">{{ $t("Name") }}</label>
                        <input type="text" name="name" v-model="name" v-validate="'required|max:100'" class="form-control form-control-custom" placeholder="Please enter name"  autocomplete="off" data-vv-as="Name">
                        <span v-bind:class="{ 'error' : errors.has('name') }">{{ errors.first('name') }}</span> 
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone_number">{{ $t("Phone") }}</label>
                        <input type="text" name="phone_number" v-model="phone_number" v-validate="'required|min:10|max:15'" class="form-control form-control-custom" placeholder="Please enter Contact Number" autocomplete="off" data-vv-as="Phone Number">
                        <span v-bind:class="{ 'error' : errors.has('phone_number') }">{{ errors.first('phone_number') }}</span> 
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">{{ $t("Email") }}</label>
                        <input type="text" name="email" v-model="email" v-validate="'required|email|max:100'" class="form-control form-control-custom" placeholder="Please enter email"  autocomplete="off" data-vv-as="Email" readonly="">
                        <span v-bind:class="{ 'error' : errors.has('email') }">{{ errors.first('email') }}</span> 
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div class="form-group col-md-4">
                        <label for="company_name">{{ $t("Company Name") }}</label>
                        <input type="text" name="company_name" v-model="company_name" v-validate="'required|max:100'" class="form-control form-control-custom" placeholder="Please enter company name"  autocomplete="off" data-vv-as="Company Name">
                        <span v-bind:class="{ 'error' : errors.has('company_name') }">{{ errors.first('company_name') }}</span> 
                    </div>
                    <div class="form-group col-md-4">
                        <label for="company_url">{{ $t("Company URL") }}</label>
                        <div class="input-group mb-3">
                          <input type="text" name="company_url" v-model="company_url" v-validate="'required|max:100'" class="form-control form-control-custom" placeholder="Please enter company url" autocomplete="off" data-vv-as="Company Url" readonly="">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">.wosul.sa</span>
                          </div>
                        </div>
                        <span v-bind:class="{ 'error' : errors.has('company_url') }">{{ errors.first('company_url') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">{{ $t("Address") }}</label>
                        <textarea type="text" name="address" v-model="address" v-validate="'required|max:250'" class="form-control form-control-custom" placeholder="Please enter address"  autocomplete="off" data-vv-as="Address"></textarea>
                        <span v-bind:class="{ 'error' : errors.has('address') }">{{ errors.first('address') }}</span> 
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div class="form-group col-md-4">
                        <label for="promo_code">{{ $t("Promo Code") }}</label>
                        <input type="text" name="promo_code" v-model="promo_code" class="form-control form-control-custom" placeholder="Please enter company name"  autocomplete="off"> 
                    </div>
                    <div class="form-group col-md-4">
                        <label for="recommendation">{{ $t("Recommendation") }}</label>
                        <input type="text" name="recommendation" v-model="recommendation" class="form-control form-control-custom" placeholder="Please enter company url" autocomplete="off">
                    </div>
                     <div class="form-group col-md-4">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">Choose Status..</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ status.label }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                    
                </div>

                 <div class="mb-2">
                    <span class="text-subhead">{{ $t("To Change Password") }}</span>
                </div>

                <div class="form-row mb-2">
                    
                    <div class="form-group col-md-4">
                        <label for="password">{{ $t("Password") }} (only in case you want to set a new password)</label>
                        <input type="text" name="password" v-model="password" v-validate="{'required' : merchant_slack == '' }" class="form-control form-control-custom" placeholder="Please enter password"  autocomplete="off" data-vv-as="Password">
                        <span v-bind:class="{ 'error' : errors.has('password') }">{{ errors.first('password') }}</span> 
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
                Confirm
            </template>
            <template v-slot:modal-body>
                <p v-if="status == 0">You are making the user inactive.</p>
                Are you sure you want to proceed?
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">Cancel</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> Continue</button>
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

                api_link        : (this.merchant_data == null)?'/api/add_merchant':'/api/update_merchant/'+this.merchant_data.slack,

                merchant_slack      : (this.merchant_data == null)?'':this.merchant_data.slack,
                name : (this.merchant_data == null)?'':this.merchant_data.name,
                phone_number : (this.merchant_data == null)?'':this.merchant_data.phone_number,
                email : (this.merchant_data == null)?'':this.merchant_data.email,
                company_name : (this.merchant_data == null)?'':this.merchant_data.company_name,
                company_url : (this.merchant_data == null)?'':this.merchant_data.company_url,
                address : (this.merchant_data == null)?'':this.merchant_data.address,
                promo_code : (this.merchant_data == null)?'':this.merchant_data.promo_code,
                recommendation : (this.merchant_data == null)?'':this.merchant_data.recommendation,
                status : (this.merchant_data == null)?'1':(this.merchant_data.status == null)?'':this.merchant_data.status.value,
                password : '',
            }
        },
        props: {
            statuses: Array,
            merchant_data: [Array, Object],
        },
        mounted() {
            console.log('Add user page loaded');
            console.log(this.merchant_data);
            return false;

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
                            formData.append("slack", (this.merchant_slack == null)?'':this.merchant_slack);
                            formData.append("name", (this.name == null)?'':this.name);
                            formData.append("phone_number", (this.phone_number == null)?'':this.phone_number);
                            formData.append("email", (this.email == null)?'':this.email);
                            formData.append("company_name", (this.company_name == null)?'':this.company_name);
                            formData.append("company_url", (this.company_url == null)?'':this.company_url);
                            formData.append("address", (this.address == null)?'':this.address);
                            formData.append("promo_code", (this.promo_code == null)?'':this.promo_code);
                            formData.append("recommendation", (this.recommendation == null)?'':this.recommendation);
                            formData.append("status", (this.status == null)?'':this.status);
                     
                            if(this.password != ''){
                                formData.append("password", this.password);
                            }

                            axios.post(this.api_link, formData).then((response) => {
                            

                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, 'SUCCESS');
                                
                                    setTimeout(function(){
                                        location.href = "/merchants";
                                    }, 1000);

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

        }
    }
</script>
