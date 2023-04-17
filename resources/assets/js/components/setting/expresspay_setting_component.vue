<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title">{{ $t("Edit Expresspay Setting") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label>{{ $t("Merchant Key") }}</label>
                        <input type="text" name="merchant_key" v-model="merchant_key" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="enter_merchant_key"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('merchant_key') }">{{ errors.first('merchant_key') }}</span> 
                    </div>
                 
                    <div class="form-group col-md-3">
                        <label>{{ $t("Password") }}</label>
                        <input type="text" name="password" v-model="password" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="enter_password"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('password') }">{{ errors.first('password') }}</span> 
                    </div>
                 
                </div>
              <div class="form-row mb-2">
                <div class="form-group col-md-3">
                  <label>{{ $t("SMS Template Variables") }}</label>
                  <span class="d-block">[INVOICE_NUMBER] : {{ $t("For Invoice Number") }}</span>
                  <span class="d-block">[AMOUNT] : {{ $t("For Invoice Amount") }}</span>
                  <span class="d-block">[INVOICE_LINK] : {{ $t("For Invoice PDF Link") }}</span>
                  <span class="d-block">[PAYMENT_LINK] : {{ $t("For Invoice Payment Link") }}</span>
                </div>
                <div class="form-group col-md-9">
                  <label>{{ $t("SMS Template") }}</label>
                  <textarea name="sms_template" v-model="sms_template" v-validate="'required|max:1000'" class="form-control form-control-custom" rows="5" :placeholder="enter_sms_template"></textarea>
                  <span v-bind:class="{ 'error' : errors.has('sms_template') }">{{ errors.first('sms_template') }}</span>
                  <span>{{ $t("Note: Square bracket([ ]) words will replace with the data like 'Your invoice number:[INVOICE_NUMBER]' to 'Your invoice number:001' in SMS") }}</span>
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
    
    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                api_link        : '/api/expresspay_setting',
                modal           : false,
                show_modal      : false,
                
                merchant_key    : (this.expresspay_data.expresspay_merchant_key == null)?'':this.expresspay_data.expresspay_merchant_key,
                password    : (this.expresspay_data.expresspay_password == null)?'':this.expresspay_data.expresspay_password,
              sms_template    : (this.expresspay_data.expresspay_sms_template == null)?'':this.expresspay_data.expresspay_sms_template,
                success:this.$t('SUCCESS'),
                enter_merchant_key:this.$t('Please enter merchant key'),
                enter_password:this.$t('Please enter password'),
                enter_sms_template:this.$t('Please enter SMS template')

            }
        },
        props: {
            expresspay_data: [Array, Object],
        },
        mounted() {
            console.log('Expresspay Setting Page Loaded');
        },
        methods: {
            submit_form(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        this.$on("submit",function () {
                            
                            this.processing = true;
                            var formData = new FormData();
                            
                            formData.append("access_token", window.settings.access_token);
                            formData.append("merchant_key", (this.merchant_key == null)?'':this.merchant_key);
                            formData.append("password", (this.password == null)?'':this.password);
                            formData.append("sms_template", (this.sms_template == null)?'':this.sms_template);

                            axios.post(this.api_link, formData).then((response) => {
                                
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, this.success);
                                
                                    setTimeout(function(){
                                        location.reload();
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
                            this.$off("submit");
                        });
                        
                        this.$on("close",function () {
                            this.show_modal = false;
                            this.$off("close");
                        });
                    }
                });
            },

          
        }
    }
</script>