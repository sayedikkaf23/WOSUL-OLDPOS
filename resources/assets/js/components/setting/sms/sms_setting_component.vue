<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                    <span class="text-title">MSEGAT {{ $t("SMS Settings") }}</span>
                </div>
            </div>

            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="api_key">{{ $t("API KEY") }}</label>
                    <p class="text-truncate">{{ api_key }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="user_name">{{ $t("Username") }}</label>
                    <p class="text-truncate">{{ user_name | hide_sensitive_info(10) }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="sender_name">{{ $t("Sender Name") }}</label>
                    <p>{{ sender_name }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">{{ $t("Status") }}</label>
                    <p><span v-bind:class="status_color">{{ status_label }}</span></p>
                </div>
            </div>

            <div class="flex-wrap mb-4">
                <div class="text-right">
                    <a v-bind:href="edit_link" class="btn btn-primary"> {{ $t("Edit") }}</a>
                </div>
            </div>

        </div>
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
                edit_link       : (this.sms_setting.length == 0)?'/edit_sms_setting':'/edit_sms_setting/'+this.sms_setting.slack,
                
                api_key     : (this.sms_setting.length == 0)?'-':this.sms_setting.api_key,
                user_name      : (this.sms_setting.length == 0)?'-':this.sms_setting.user_name,
                sender_name   : (this.sms_setting.length == 0)?'-':this.sms_setting.sender_name,
                status_label    : (this.sms_setting.length == 0)?'-':this.sms_setting.status.label,
                status_color    : (this.sms_setting.length == 0)?'':this.sms_setting.status.color,
            }
        },
        props: {
            sms_setting: [Array, Object]
        },
        mounted() {
            console.log('SMS setting page loaded');
        },
        filters: {
            hide_sensitive_info: function(value, limit) {
                if (!value) return '';
                if (value.length > limit) {
                    value = value.substring(0, (limit - 3)) + '***';
                }
                return value;
            }
        },
        methods: {
           
        }
    }
</script>