<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="modifier_slack == ''">{{ $t("Add Modifier") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Modifier") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="label">{{ $t("Label") }}</label>
                        <input type="text" name="label" v-model="label" v-validate="'required|max:150'" class="form-control form-control-custom" :placeholder="enter_label"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('label') }">{{ errors.first('label') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']" >
                        <label for="is_multiple">{{ $t("Is Multiple") }}</label>
                        <br>
                        <label for="is_multiple_true"><input id="is_multiple_true" type="radio" v-model="is_multiple" value="1" /> Yes</label>
                        <label for="is_multiple_false p-1"><input id="is_multiple_false" type="radio" v-model="is_multiple" value="0" /> No</label>
                        <span v-bind:class="{ error: errors.has('is_multiple') }">{{ errors.first("is_multiple") }}</span>
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
                <p v-if="status == 0">{{ $t('If modifier is inactive all the products using this modifier may get affected.') }}</p>
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
                api_link        : (this.modifier_data == null)?'/api/add_modifier':'/api/update_modifier/'+this.modifier_data.slack,
                modifier_slack  : (this.modifier_data == null)?'':this.modifier_data.slack,
                label : (this.modifier_data == null)?'':this.modifier_data.label,
                status : (this.modifier_data == null)?'1':(this.modifier_data.status == null)?'':this.modifier_data.status.value,
                enter_label:this.$t('Please enter label'),
                success:this.$t('SUCCESS'),
                reload_on_submit: {
                  type : String,
                  default : true,
                },
                is_multiple : (this.modifier_data == null) ? 0 :this.modifier_data.is_multiple
            }
        },
        props: {
            statuses: Array,
            reload_on_submit: {
              type : String,
              default : true,
            },
            modifier_data : [Object,Array]
        },
        mounted() {
            console.log('Add modifier page loaded');
            // console.log(this.modifier_data);
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
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("is_multiple", (this.is_multiple == null)?'':this.is_multiple);

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
