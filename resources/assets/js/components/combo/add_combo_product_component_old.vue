<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="tax_code_slack == ''">{{ $t("Add Combo Product") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Combo Product") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="combo_name">{{ $t("Combo Product Name") }}</label>
                        <input type="text" name="combo_name" v-model="combo_name" v-validate="'required|max:250'" class="form-control form-control-custom" :placeholder="enter_combo_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('combo_name') }">{{ errors.first('combo_name') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="combo_code">{{ $t("Combo Code") }}</label>
                        <input type="text" name="combo_code" v-model="combo_code" v-validate="'required|alpha_dash|max:30'" class="form-control form-control-custom" :placeholder="enter_combo_code"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('combo_code') }">{{ errors.first('combo_code') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Category") }}</label>
                        <select name="main_category" v-model="main_category" v-validate="'required'"
                            class="form-control form-control-custom custom-select" id="main_category_id">
                            <option value="">{{ $t("Choose Category..") }} </option>
                            <option v-for="(main_cat, index) in main_categories" v-bind:value="main_cat.id" v-bind:key="index" >
                                {{ main_cat.label }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                </div>

                
                <div class="d-flex flex-wrap mb-1">
                    <div class="mr-auto">
                        <span class="text-subhead">{{ $t("Combo Groups") }}</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="add_new_size_type">{{ $t("Add Size") }}</button>
                    </div>
                </div>
                

                <div class="form-row mb-2" v-for="(size_type_item, index) in size_type" :key="index">
                    <div class="col-md-12 mb-4">
                        <div class="col-md-3">
                            <input type="text" v-bind:name="'size_type_item.size_type_'+index" v-model="size_type_item.size_name" v-validate="'required'" 
                                class="form-control form-control-custom" :placeholder="enter_size_name"  autocomplete="off">
                        </div>
                    </div>
                    <!-- <div class="col-md-12 ">
                        <div class="col-md-3">
                            <label for="tax_type">{{ $t("Product") }}</label>

                        </div>
                        <div class="col-md-3">
                            <label for="tax_percentage">{{ $t("Price (Include Tax)") }}</label>  
                        </div>
                    </div> -->
                    <div class="col-md-12 mb-4">
                        <div class="col-md-3">
                            <input type="text" v-bind:name="'size_type_item.group_name_'+index" v-model="size_type_item.group_name" v-validate="'required'" 
                                class="form-control form-control-custom" :placeholder="enter_group_name"  autocomplete="off">
                        </div>
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="tax_percentage">{{ $t("Price (Include Tax)") }}</label>  
                        <div class="form-row mb-2" >
                            <select v-bind:name="'size_type_item.item_'+index" :id="'tax_name_id_'+index"  v-model="size_type_item.tax_name_id" 
                                v-validate="'required'" class="form-control form-control-custom custom-select" :placeholder="select_product">
                                <option value="">{{ $t("Choose Product..") }} </option>
                                <option v-for="(product, index) in product_list" v-bind:value="product.id" v-bind:key="index">
                                    {{ product.name }}
                                </option> 
                            </select>
                            <span v-bind:class="{ 'error' : errors.has('size_type_item.item_'+index) }">{{ errors.first('size_type_item.tax_type_'+index) }}</span> 
                            
                        </div>

                        <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                            <label for="tax_percentage">{{ $t("Price (Include Tax)") }}</label>  
                            <input type="number" v-bind:name="'size_type_item.price'+index"  :id="'tax_name_percentage_'+index" 
                                v-model="size_type_item.price" v-validate="'required|decimal'" 
                                class="form-control form-control-custom"  autocomplete="off" step="0.01" min="0">
                            <span v-bind:class="{ 'error' : errors.has('size_type_item.price'+index) }">{{ errors.first('size_type_item.price'+index) }}</span> 
                        </div>
                    </div>



                    
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']" v-if="tax_types.length>1">
                        <button type="button" class="btn btn-outline-danger" @click="remove_tax_type(index)"><i class="fas fa-times"></i></button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="add_new_size_type">{{ $t("Add Option") }}</button>

                </div>

                

                <div class="form-row mb-2 mt-3">
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
                <div class="flex-wrap mb-4">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Save") }}</button>
                    </div>
                </div>
                
                
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
                combo_name      : (this.tax_code_data == null)?'':this.tax_code_data.name,
                combo_code      : (this.tax_code_data == null)?'':this.tax_code_data.tax_code,
                total_tax_percentage  : (this.tax_code_data == null)?'':this.tax_code_data.total_tax_percentage,
                status          : (this.tax_code_data == null)?'0':(this.tax_code_data.status == null)?'':this.tax_code_data.status.value,
                main_category   : (this.tax_code_data == null)?'':this.tax_code_data.description,
                tax_code_type_data : (this.tax_code_data == null)?[]:(this.tax_code_data.tax_components == null)?[]:this.tax_code_data.tax_components,
                size_type       : [
                    {
                        size_name : '',
                    }
                ],
                tax_types       : [
                    {
                        tax_name_id : '',
                        tax_type : '',
                        tax_percentage : ''
                    }
                ],
                product_list: this.product_data == null ? [] : this.product_data,
                enter_combo_code:this.$t("Please enter tax code"),
                enter_combo_name:this.$t("Please enter tax code name"),
                enter_size_name:this.$t("Please enter size name"),
                enter_group_name:this.$t("Please enter group name"),
                select_product:this.$t("Choose Product.."),
                success:this.$t("SUCCESS")
            }
        },
        props: {
            statuses: Array,
            tax_names: [Array, Object],
            tax_code_data: [Array, Object],
            main_categories: [Array, Object],
            product_data: [Array, Object],
            reload_on_submit: {
              type : Boolean,
              default : true,
            }
        },
        mounted() {
            console.log('Add Tax Code page loaded');
            console.log('main_categories =', this.main_categories);
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
            add_new_size_type(){
                this.size_type.push({
                    size_name: '',
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
