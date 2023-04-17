<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="setting_slack == ''">{{ $t("Add SMS Setting") }} - MSEGAT</span>
                        <span class="text-title" v-else>{{ $t("Edit SMS Setting") }} - MSEGAT</span>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="api_key">{{ $t("API KEY") }}</label>
                        <input type="text" name="api_key" v-model="api_key" v-validate="'required|max:150'" class="form-control form-control-custom" placeholder="Please enter api key"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('api_key') }">{{ errors.first('api_key') }}</span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="user_name">{{ $t("Username") }}</label>
                        <input type="text" name="user_name" v-model="user_name" v-validate="'required|max:150'" class="form-control form-control-custom" placeholder="Please enter user name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('user_name') }">{{ errors.first('user_name') }}</span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sender_name">{{ $t("Sender Name") }}</label>
                        <input type="text" name="sender_name" v-model="sender_name" v-validate="'required|max:50'" class="form-control form-control-custom" placeholder="Please enter sender number"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('sender_name') }">{{ errors.first('sender_name') }}</span> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
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
                <button type="button" class="btn btn-light" @click="$emit('close')">{{$t('Cancel')}}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i>{{ $t('Continue')}}</button>
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
                api_link        : (this.setting_data.length == 0)?'/api/add_setting_sms':'/api/update_setting_sms/'+this.setting_data.slack,

                setting_slack   : (this.setting_data.length == 0)?'':this.setting_data.slack,
                api_key     : (this.setting_data.length == 0)?'':this.setting_data.api_key,
                user_name      : (this.setting_data.length == 0)?'':this.setting_data.user_name,
                sender_name   : (this.setting_data.length == 0)?'':this.setting_data.sender_name,
                status          : (this.setting_data.length == 0)?'':this.setting_data.status.value,
                success:this.$t("SUCCESS"),

            }
        },
        props: {
            statuses: Array,
            setting_data: [Array, Object]
        },
        mounted() {
            console.log('Edit SMS setting page loaded');
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
                            formData.append("api_key", (this.api_key == null)?'':this.api_key);
                            formData.append("user_name", (this.user_name == null)?'':this.user_name);
                            formData.append("sender_name", (this.sender_name == null)?'':this.sender_name);
                            formData.append("status", (this.status == null)?'':this.status);

                            axios.post(this.api_link, formData).then((response) => {
                                
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg,this.success);
                                
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
            }
        }
    }
</script>