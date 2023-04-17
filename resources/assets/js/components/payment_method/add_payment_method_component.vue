<template>
    <h1>cvbcvb</h1>
</template>
<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="payment_method_slack == ''">{{ $t("Add Payment Method") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Payment Method") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="payment_method_name">{{ $t("Method Name") }}</label>
                        <input type="text" name="payment_method_name" v-model="payment_method_name" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="enter_payment_method_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('payment_method_name') }">{{ errors.first('payment_method_name') }}</span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index" >
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="key_1">{{ $t("Key 1") }}</label>
                        <input type="text" name="key_1" v-model="key_1"  class="form-control form-control-custom" :placeholder="first_key"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('key_1') }">{{ errors.first('key_1') }}</span> 
                    </div>
                     <div class="form-group col-md-3">
                        <label for="key_2">{{ $t("Key 2") }}</label>
                        <input type="text" name="key_2" v-model="key_2"  class="form-control form-control-custom" :placeholder="second_key"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('key_2') }">{{ errors.first('key_2') }}</span> 
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="description">{{ $t("Description") }}</label>
                        <textarea name="description" v-model="description" v-validate="'max:65535'" class="form-control form-control-custom" rows="5" :placeholder="enter_description"></textarea>
                        <span v-bind:class="{ 'error' : errors.has('description') }">{{ errors.first('description') }}</span>
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
                <p v-if="status == 0">{{ $t('If payment method is inactive transactions can not be done') }}</p>
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
    
    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : (this.payment_method_data == null)?'/api/add_payment_method':'/api/update_payment_method/'+this.payment_method_data.slack,
                payment_method_slack  : (this.payment_method_data == null)?'':this.payment_method_data.slack,           
                payment_method_name   : (this.payment_method_data == null)?'':this.payment_method_data.label,
                key_1:(this.payment_method_data == null)?'':this.payment_method_data.key_1,
                key_2:(this.payment_method_data == null)?'':this.payment_method_data.key_2, 
                description     : (this.payment_method_data == null)?'':this.payment_method_data.description,              
                status          : (this.payment_method_data == null)?'1':(this.payment_method_data.status == null)?'':this.payment_method_data.status.value,
                enter_payment_method_name:this.$t('Please enter payment method name'),
                first_key:this.$t('Please enter First key'),
                second_key:this.$t('Please enter Second key'),
                enter_description:this.$t('Enter description'),
                success:this.$t("SUCCESS")
            }
        },
        props: {
            statuses: Array,
            payment_method_data: [Array, Object]
        },
        mounted() {
            console.log('Add payment page loaded');
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
                            formData.append("payment_method_name", (this.payment_method_name == null)?'':this.payment_method_name);                       
                            formData.append("description", (this.description == null)?'':this.description);
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("key_1", (this.key_1 == null)?'':this.key_1);
                            formData.append("key_2", (this.key_2 == null)?'':this.key_2);
                            axios.post(this.api_link, formData).then((response) => {
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg,this.success);
                                
                                    setTimeout(function(){
                                        window.location.href='/payment_methods';
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
            }
        }
    }
</script>
