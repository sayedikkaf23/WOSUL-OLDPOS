<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                         <span class="text-title">{{ $t("Open Register") }}</span>
                    </div>
                    <div class="" v-if="open_register_appicable > 0">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true" > <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Open Register & Continue") }}</button>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div v-if="open_register_appicable > 0">
                <div class="form-group">
                    <label for="billing_counter d-block">{{ $t("Choose Billing Counter") }}</label>
                    <div class="d-flex flex-wrap">
                        <div class="row flex-fill">
                            <div class="col-md-3" v-for="(billing_counter_item, index) in billing_counters" v-bind:key="index">
                                <input type="radio" class="check d-none" name="billing_counter" v-model="billing_counter" v-bind:value="billing_counter_item.slack" v-bind:id="'billing_counter'+index" v-validate="'required'" key='billing_counter'>
                                <label class="check-buttons w-100 text-truncate" v-bind:for="'billing_counter'+index">{{ billing_counter_item.billing_counter_code }} - {{ billing_counter_item.counter_name }}</label>
                            </div>
                        </div>
                    </div>
                    <span v-bind:class="{ 'error' : errors.has('billing_counter') }">{{ errors.first('billing_counter') }}</span> 
                </div>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="opening_amount">{{ $t("Cash in Hand") }}</label>
                        <input type="number" name="opening_amount" v-model="opening_amount" v-validate="'decimal|min_value:0'" class="form-control form-control-custom" placeholder="Please enter Cash in Hand"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('opening_amount') }">{{ errors.first('opening_amount') }}</span> 
                    </div>
                </div>
                </div>
                <div v-else>
                    <p class="alert alert-danger"><i class="fa fa-notice "></i>  <span style="font-size:18px;"> Sorry, you have no counter assigned to make an Order, please try to login with different account. Thanks</span> </p>
                </div>

            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                Confirm
            </template>
            <template v-slot:modal-body>
                Are you sure you want to open the register?
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
                api_link        : '/api/open_register',

                opening_amount  : '0',
                billing_counter : ''
            }
        },
        props: {
            billing_counters : [Array, Object]
        },
        mounted() {
            console.log('Open register page loaded');
        },
        computed: {
            open_register_appicable: function () {
                return this.billing_counters.length;
            }
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
                            formData.append("opening_amount", this.opening_amount);
                            formData.append("billing_counter", this.billing_counter);

                            axios.post(this.api_link, formData).then((response) => {
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, 'SUCCESS');
                                    if(typeof response.data.link != 'undefined' && response.data.link != ""){

                                        if(response.data.new_tab == true){
                                            window.open(response.data.link, '_blank');
                                        }else{
                                            window.location.href = response.data.link;
                                        }

                                        setTimeout(function(){
                                            location.reload();
                                        }, 1000);
                                    }else{
                                        setTimeout(function(){
                                            location.reload();
                                        }, 1000);
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
