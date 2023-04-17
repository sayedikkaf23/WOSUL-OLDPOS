<template>
    <div class="row">
        <div class="col-md-12">
           
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="lang_setting_slack == ''">{{ $t("Add Phrase") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Phrase") }} </span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="lang_phrase">{{ $t("Phrase") }}</label>
                        <input type="text" name="lang_phrase" v-model="lang_phrase" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="please_enter_phrase"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('lang_phrase') }">{{ errors.first('lang_phrase') }}</span> 
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="lang_label">{{ $t("Label") }}</label>
                        <input type="text" name="lang_label" v-model="lang_label" class="form-control form-control-custom" :placeholder="please_enter_label"  autocomplete="off">
                        <!-- <span v-bind:class="{ 'error' : errors.has('lang_label') }">{{ errors.first('lang_label') }}</span>  -->
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
                <p v-if="status == 0" class="mt-3">{{ $t('You are making the language label inactive.') }}</p>
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
                api_link        : (this.lang_data == null)?'/api/add_lang_setting_phrase':'/api/update_lang_setting_phrase/'+this.lang_data.slack,

                lang_slack  : this.language_setting_data.slack,
                lang_setting_slack  : (this.lang_data == null)?'':this.lang_data.slack,
                lang_phrase   : (this.lang_data == null)?'':this.lang_data.lang_phrase,
                lang_label     : (this.lang_data == null)?'':this.lang_data.lang_label,              
                status        : (this.lang_data == null)?'1':(this.lang_data.status == null)?'':this.lang_data.status.value,
                please_enter_phrase:this.$t('Please enter phrase'),
                please_enter_label:this.$t('Please enter label')
            }
        },
        props: {
            statuses: Array,
            lang_data: [Array, Object],
            language_setting_data: [Array, Object],

            
        },
        mounted() {
            console.log('Add lang page loaded');
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
                            formData.append("lang_phrase", (this.lang_phrase == null)?'':this.lang_phrase);

                            formData.append("lang_label", (this.lang_label == null)?'':this.lang_label);
                           
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("lang_slack",this.lang_slack);
                            axios.post(this.api_link, formData).then((response) => {

                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, 'SUCCESS');
                                    window.location.reload();

                                    // setTimeout(function(){
                                  
                                    //     window.location.href='/language/phrase/'+response.data.data.lang_slack;
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
