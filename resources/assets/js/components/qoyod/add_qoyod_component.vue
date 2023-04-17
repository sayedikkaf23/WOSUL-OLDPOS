<template>
    <div class="row">
        <div class="col-md-12">

            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title">{{ $t("Qoyod") }}</span>
                    </div>
                </div>

                <p v-html="server_errors" v-bind:class="[error_class]"></p>
                <div class="d-flex flex-wrap mb-1"><div class="mr-auto"><span class="text-subhead">{{ $t("Qoyod Configuration") }}  </span></div> <div></div></div>
                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <name for="name">{{ $t("API Key") }}</name>
                        <input type="text" name="api_key" v-model="api_key" v-validate="'required|max:100'" class="form-control form-control-custom" :placeholder="enter_api_key"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('api_key') }">{{ errors.first('api_key') }}</span>
                    </div>
                </div>
                <div class="flex-wrap mb-4">
                    <div class="text-left" v-if="status==0">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Link") }}</button>
                    </div>
                    <div class="text-left" v-else>
                      <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Referesh") }}</button>
                      <a class="btn btn-primary" v-bind:disabled="processing == true" id="unlink" @click="unlink()"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Unlink") }}</a>
                    </div>
                </div>

            </form>
            <div class="d-flex flex-wrap mb-1" v-if="status==1"><div class="mr-auto"><span class="text-subhead">{{ $t("Wosul Data Sync with Qoyod") }}  </span><span class="text-sm">(It may take time if your data is large!)</span></div> <div></div></div>
            <div class="flex-wrap mb-4" v-if="status==1">
              <div class="text-left">
                <a class="btn btn-primary text-white" v-bind:disabled="sync_processing == true" @click="sync_business_account()"> <i class='fa fa-circle-notch fa-spin' v-if="sync_processing == true"></i> {{ $t("Sync Wosul Data") }}</a>
              </div>
            </div>
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
    import { extend } from 'vee-validate';

    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                sync_processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : '/api/add_qoyod',
                sync_qoyod_data_api_link        : '/api/sync_qoyod_data',
                unlink_qoyod_account        : '/api/async_qoyod_data',
                api_key : (this.qoyod_data == null)?'':this.qoyod_data.api_key,
                status : (this.qoyod_data == null)?0:this.qoyod_data.status,
                enter_api_key:this.$t('Please enter API key'),
                success:this.$t('SUCCESS'),
                reload_on_submit: {
                  type : String,
                  default : true,
                }
            }
        },
        props: {
            reload_on_submit: {
              type : String,
              default : true,
            },
            qoyod_data : [Object,Array]
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
                            formData.append("api_key", (this.api_key == null)?'':this.api_key);

                            axios.post(this.api_link, formData).then((response) => {

                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, this.success);
                                    // this.$emit('refreshMeasurements', response.data.data);
                                    this.show_modal = false;
                                    this.processing = false;

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
                        });

                        this.$on("close",function () {
                            this.show_modal = false;
                        });
                    }
                });
            },
            sync_business_account(){
              this.sync_processing = true;
              var formData = new FormData();

              formData.append("access_token", window.settings.access_token);
              formData.append("api_key", (this.api_key == null)?'':this.api_key);

              axios.post(this.sync_qoyod_data_api_link, formData).then((response) => {

                if(response.data.status_code == 200) {
                  this.show_response_message(response.data.msg, this.success);
                  // this.$emit('refreshMeasurements', response.data.data);
                  this.sync_processing = false;

                  setTimeout(function(){
                    location.reload();
                  }, 1000);

                }else{
                  this.sync_processing = false;
                  try{
                    var error_json = JSON.parse(response.data.msg);
                    this.loop_api_errors(error_json);
                  }catch(err){
                    this.server_errors = response.data.msg;
                  }
                  this.error_class = 'error';
                }
              }).catch((error) => {
                console.log(error);
              });
            },
            unlink(){
              this.show_modal = true;
              this.$on("submit",function () {

                this.processing = true;
                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                formData.append("api_key", (this.api_key == null)?'':this.api_key);

                axios.post(this.unlink_qoyod_account, formData).then((response) => {

                  if(response.data.status_code == 200) {
                    this.show_response_message(response.data.msg, this.success);
                    // this.$emit('refreshMeasurements', response.data.data);
                    this.show_modal = false;
                    this.processing = false;

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
              });

              this.$on("close",function () {
                this.show_modal = false;
              });
            }
        }
    }
</script>
