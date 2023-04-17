<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="tax_code_slack == ''">{{ $t("Add Tax Code") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Tax Code") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="tax_code_label">{{ $t("Tax Code Name") }}</label>
                        <input type="text" name="tax_code_label" v-model="tax_code_label" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="enter_taxcode_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('tax_code_label') }">{{ errors.first('tax_code_label') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="tax_code">{{ $t("Tax Code or HSN Code") }}</label>
                        <input type="text" name="tax_code" v-model="tax_code" v-validate="'required|alpha_dash|max:30'" class="form-control form-control-custom" :placeholder="enter_taxcode"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('tax_code') }">{{ errors.first('tax_code') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t("Choose Status..") }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                </div>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="description">{{ $t("Description") }}</label>
                        <textarea name="description" v-model="description" v-validate="'max:65535'" class="form-control form-control-custom" rows="5" :placeholder="enter_description"></textarea>
                        <span v-bind:class="{ 'error' : errors.has('description') }">{{ errors.first('description') }}</span>
                    </div>
                </div>

                <div class="d-flex flex-wrap mb-1">
                    <div class="mr-auto">
                        <span class="text-subhead">{{ $t("Tax Types") }}</span>
                    </div>
                </div>

                 <div class="form-row">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group mb-1']">
                        <label for="tax_type">{{ $t("Tax Type") }}</label>

                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group mb-1']">
                        <label for="tax_percentage">{{ $t("Tax Percentage") }}</label>  
                    </div>
                </div>

                <div class="form-row mb-2" v-for="(tax_type_item, index) in tax_types" :key="index">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <!-- <input type="text" v-bind:name="'tax_type_item.tax_type_'+index" :data-vv-as="enter_tax_type" v-model="tax_type_item.tax_type" v-validate="'required'" class="form-control form-control-custom" :placeholder="enter_taxtype"  autocomplete="off"> -->

                        <select v-bind:name="'tax_type_item.tax_type_'+index" :id="'tax_name_id_'+index" :data-vv-as="enter_tax_type" 
                            v-model="tax_type_item.tax_name_id" v-validate="'required'" 
                            class="form-control form-control-custom custom-select" :placeholder="enter_taxtype" @change="update_new_tax_type(index)">
                            <!-- <option v-for="(taxcode, index) in taxcodes"  v-bind:value="taxcode.id" v-bind:key="index">
                                {{ taxcode.tax_code }} - {{ taxcode.label }} </option> -->
                            <option v-for="(code, code_index) in tax_names" :key="code_index" :value="code.id" :data-percent="code.percentage">
                            {{ code.tax_name }}
                            </option>
                            <option value="">--Add New Tax--</option>
                        </select>

                        <span v-bind:class="{ 'error' : errors.has('tax_type_item.tax_type_'+index) }">{{ errors.first('tax_type_item.tax_type_'+index) }}</span> 
                
                    </div>



                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <input type="number" readonly v-bind:name="'tax_type_item.tax_percentage_'+index" :data-vv-as="enter_tax_per" 
                            :id="'tax_name_percentage_'+index" v-model="tax_type_item.tax_percentage" v-validate="'required|decimal'" 
                            class="form-control form-control-custom"  autocomplete="off" step="0.01" min="0">
                        <span v-bind:class="{ 'error' : errors.has('tax_type_item.tax_percentage_'+index) }">{{ errors.first('tax_type_item.tax_percentage_'+index) }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']" v-if="tax_types.length>1">
                        <button type="button" class="btn btn-outline-danger" @click="remove_tax_type(index)"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="flex-wrap mb-4">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>
                
                <button type="button" class="btn btn-sm btn-outline-primary" @click="add_new_tax_type">{{ $t("Add More") }}</button>
            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                <p v-if="status == 0">{{ $t("If tax code is inactive all the products with this tax code will get affected.") }}</p>
                {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Continue") }}</button>
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
                api_link        : (this.tax_code_data == null)?'/api/add_tax_code':'/api/update_tax_code/'+this.tax_code_data.slack,

                tax_code_slack  : (this.tax_code_data == null)?'':this.tax_code_data.slack,
                tax_code_label  : (this.tax_code_data == null)?'':this.tax_code_data.label,
                tax_code        : (this.tax_code_data == null)?'':this.tax_code_data.tax_code,
                total_tax_percentage  : (this.tax_code_data == null)?'':this.tax_code_data.total_tax_percentage,
                status          : (this.tax_code_data == null)?'1':(this.tax_code_data.status == null)?'':this.tax_code_data.status.value,
                description     : (this.tax_code_data == null)?'':this.tax_code_data.description,
                tax_code_type_data : (this.tax_code_data == null)?[]:(this.tax_code_data.tax_components == null)?[]:this.tax_code_data.tax_components,
                tax_types       : [
                    {
                        tax_name_id : '',
                        tax_type : '',
                        tax_percentage : ''
                    }
                ],
               
                enter_taxcode:this.$t("Please enter tax code"),
                enter_taxcode_name:this.$t("Please enter tax code name"),
                enter_description:this.$t("Please enter description"),
                enter_taxtype:this.$t("Please enter tax type"),
                enter_tax_percentage:this.$t("Please enter tax percentage"),
                enter_tax_type:this.$t("Tax Type"),
                enter_tax_per:this.$t("Tax Percentage"),
                success:this.$t("SUCCESS")
            }
        },
        props: {
            statuses: Array,
            tax_names: [Array, Object],
            tax_code_data: [Array, Object],
            reload_on_submit: {
              type : Boolean,
              default : true,
            }
        },
        mounted() {
            console.log('Add Tax Code page loaded');
            console.log('tax_types', this.tax_types);
        },
        created(){
            this.update_tax_code_type();
        },
        methods: {
            update_new_tax_type(index){
                
                var tax_name_id = $('#tax_name_id_'+index).val();
                if(tax_name_id == '' || tax_name_id == null){
                    window.open('/add_tax_name?'+this.tax_code_slack,'_BLANK');
                }
                var tax_percentage = $('#tax_name_id_'+index+' :selected').attr('data-percent');
                var tax_name_label = $('#tax_name_id_'+index+' :selected').text().trim();
                this.tax_types[index].tax_name_id = tax_name_id;
                this.tax_types[index].tax_type = tax_name_label;
                this.tax_types[index].tax_percentage = tax_percentage;
                $('#tax_name_percentage_'+index).val(tax_percentage);
                console.log('this.tax_types =',this.tax_types);
            },
            add_new_tax_type(){
                this.tax_types.push({
                    tax_name_id: '',
                    tax_type: '',
                    tax_percentage: ''
                });
            },
            remove_tax_type(index){
                this.tax_types.splice(index, 1);
            },
            update_tax_code_type(){
                if(this.tax_code_type_data.length>0){
                    this.tax_types = [];
                    for(var i=0; i< this.tax_code_type_data.length; i++){
                        var tax_code_type_array = {
                            tax_name_id : this.tax_code_type_data[i].tax_name_id,
                            tax_type : this.tax_code_type_data[i].tax_type,
                            tax_percentage : this.tax_code_type_data[i].tax_percentage
                        }
                        this.tax_types.push(tax_code_type_array);
                    }
                }
            },
            submit_form(){

                // this.$off("submit");
                // this.$off("close");

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        
                            if(this.reload_on_submit){

                                this.show_modal = true;
                                this.$on("submit",function () {
                                    this.form_data();
                                    this.$off("submit");
                                });

                                this.$on("close",function () {
                                    this.show_modal = false;
                                    this.$off("close");
                                });

                            }else{

                                this.form_data();

                            }
                                               
                        this.$on("close",function () {
                            this.show_modal = false;
                        });
                    }
                });
            },
            form_data(){
                this.processing = true;
                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                formData.append("tax_code_name", (this.tax_code_label == null)?'':this.tax_code_label);
                formData.append("tax_code", (this.tax_code == null)?'':this.tax_code);
                formData.append("tax_percentage", null);
                formData.append("description", (this.description == null)?'':this.description);
                formData.append("status", (this.status== null)?'':this.status);
                formData.append("tax_types", JSON.stringify(this.tax_types));

                axios.post(this.api_link, formData).then((response) => {
                    
                    if(response.data.status_code == 200) {

                        this.show_response_message(response.data.msg, this.success);
                        this.$emit('refreshTaxCodes', response.data.data); 
                        this.processing = false;                    
                        this.show_modal = false;

                        setTimeout(function(){
                            window.location.href = response.data.link;
                        }, 1300);

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
            }
        }
    }
</script>
