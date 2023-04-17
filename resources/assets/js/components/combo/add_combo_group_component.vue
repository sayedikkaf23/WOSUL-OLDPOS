<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="combo_group_slack == ''">{{ $t("Add Combo Group") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit combo_group") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2 mt-2 py-2">

                    <div class="col-12">
                        <label for="group">{{ $t("Select Group Type") }}</label>
                    </div>

                    <div class="col-3" v-for="(type, index) in group_types" :key="index">
                        <div class="card" style="cursor:pointer;" @click="group_type = type" :class="[ (group_type == type) ? 'border-1 border-primary shadow-lg text-primary' : '' ]">
                            <div class="card-body">
                                {{ type }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row mb-2 my-4" >
                    <div class="col-md-3" v-if="group_type == 'SUBGROUP'">
                        <name for="group">{{ $t("Select Parent Group") }}</name>
                        <select name="parent_group" v-model="parent_group" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Group..') }}</option>
                            <option v-for="(group, index) in parent_groups" :value="group.id" :key="index">
                                {{ $t(group.name) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('group') }">{{ errors.first('group') }}</span> 
                    </div>

                    <div class="col-md-3">
                        <name for="name">{{ $t("Name") }} <span class="text-danger">*</span> </name>
                        <input type="text" name="name" v-model="name" v-validate="'required|max:50'" class="form-control form-control-custom" :placeholder="enter_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('name') }">{{ errors.first('name') }}</span> 
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
                modal           : false,
                show_modal      : false,
                api_link        : (this.combo_group_data == null)?'/api/add_combo_group':'/api/update_combo_group/'+this.combo_group_data.slack,
                combo_group_slack  : (this.combo_group_data == null)?'':this.combo_group_data.slack,
                name : (this.combo_group_data == null)?'':this.combo_group_data.name,
                parent_group : (this.combo_group_data == null) ? '' : (this.combo_group_data.parent == null)?'':this.combo_group_data.parent.id,
                enter_name: this.$t('Please enter name'),
                success: this.$t('SUCCESS'),
                parent_groups : (this.parent_group_data == null) ? [] : this.parent_group_data,
                group_types : ['GROUP','SUBGROUP'],
                group_type : 'GROUP'
            }
        },
        props: {
            combo_group_data : [Object,Array],
            parent_group_data : [Object,Array]
        },
        mounted() {
            console.log('Add Combo Group Page Loaded');
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
                            formData.append("parent", (this.parent_group == null)?'':this.parent_group);
                            formData.append("name", (this.name == null)?'':this.name);

                            axios.post(this.api_link, formData).then((response) => {

                                // console.log(response);
                                
                                if(response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, this.success);
                                    // this.$emit('refreshMeasurements', response.data.data);                    
                                    this.show_modal = false;
                                    this.processing = false;
                                    
                                    window.location.href = response.data.link;

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
