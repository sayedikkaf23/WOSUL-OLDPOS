<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="combo_slack == ''">{{ $t("Add combo") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit combo") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    
                    <div class="col-md-3">
                        <label for="category_id">{{ $t("Select Category") }}</label>
                        <select name="category_id" v-model="category_id" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Category..') }}</option>
                            <option v-for="(category, index) in categories_data" :value="category.id" :key="index">
                                {{ $t(category.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('category_id') }">{{ errors.first('category_id') }}</span> 
                    </div>
                    
                    <div class="col-md-3">
                        <label for="name">{{ $t("Combo Name") }}</label>
                        <input type="text" name="name" v-model="name" v-validate="'required|max:50'" class="form-control form-control-custom" :placeholder="enter_name"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('name') }">{{ errors.first('name') }}</span> 
                    </div>

                    <div class="col-md-3">
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
                <div class="form-row mb-0 mt-5">
                    
                    <div class="col-md-3" >
                        <label for="is_discount_enabled"><input id="is_discount_enabled" type="checkbox" v-model="is_discount_enabled" value="0" /> {{ $t("Enable Discount") }}  </label>
                    </div>
                    
                </div>
                <div class="form-row mb-2" v-show="is_discount_enabled">
                    <div class="col-md-3">
                        <label for="discount_type">{{ $t("Select Discount Type") }}</label>
                        <select name="discount_type" v-model="discount_type" class="form-control form-control-custom custom-select" v-validate=" is_discount_enabled ? 'required' : ''">
                            <option value="">{{ $t('--Select Discount Type--') }}</option>
                            <option value="PERCENTAGE">{{ $t('Percentage') }}</option>
                            <option value="AMOUNT">{{ $t('Amount') }}</option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('discount_type') }">{{ errors.first('discount_type') }}</span> 
                    </div>
                    
                    <div class="col-md-3">
                        <label for="discount_value">{{ $t("Discount Value") }}</label>
                        <input type="number" name="discount_value" v-model="discount_value" class="form-control form-control-custom" :placeholder="enter_discount_value"  autocomplete="off" v-validate=" is_discount_enabled ? 'required' : ''">
                        <span v-bind:class="{ 'error' : errors.has('discount_value') }">{{ errors.first('discount_value') }}</span> 
                    </div>

                </div>

                <div class="form-row mb-2 pt-5">
                    
                    <div class="col-md-3">
                        <input type="text" name="combo_size" v-model="combo_size" v-validate="'max:20'" class="form-control form-control-custom" :placeholder="enter_combo_size"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('combo_size') }">{{ errors.first('combo_size') }}</span> 
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" @click="add_combo_size(combo_size)" >  {{ $t("+") }}</button>
                    </div>
                    
                </div>
                
                <div v-if="combo_sizes.length"> 
                    <div class="form-row mb-2 mt-3" v-for="(combo_size,index) in combo_sizes" :key="index">
                        
                        <table v-if="combo_size.items && combo_size.items.length" class="table table-striped table-condensed ">
                            <tr>
                                <td class="py-3" colspan="6"> <h5> {{ combo_size.size_name }} </h5></td>
                            </tr>
                            <tr v-for="(item,combo_size_index) in combo_size.items" :key="combo_size_index">
                                <td>
                                    <select :name="'item.group_' + combo_size_index" data-vv-as="Group" v-model="item.group" v-validate="'required'" class="form-control form-control-custom custom-select" @change="load_sub_group(index,combo_size_index)">
                                        <option value="">{{ $t('--Select Group --') }}</option>
                                        <option :value="group.id" v-for="(group,group_index) in groups_data" :key="group_index">{{ group.name }}</option>
                                    </select>
                                    <span v-bind:class="{ error: errors.has('item.group_' + combo_size_index), }">{{ errors.first("item.group_" + combo_size_index) }}</span
                                        >
                                </td>
                                <td>
                                    <select name="subgroup"  v-model="item.subgroup" class="form-control form-control-custom custom-select">
                                        <option value="">{{ $t('--Select Sub Group --') }}</option>
                                        <option :value="subgroup.id" v-for="(subgroup,subgroup_index) in item.subgroups" :key="subgroup_index">{{ subgroup.name }}</option>
                                    </select>
                                </td>
                                <td>
                                    <select :name="'item.product_' + combo_size_index" data-vv-as="Product" v-model="item.product" v-validate="'required'" class="form-control form-control-custom custom-select" @change="load_product_attribute(index,combo_size_index, item.product)">
                                        <option value="">{{ $t('--Select Product --') }}</option>
                                        <option :value="product" v-for="(product,product_index) in products" :key="product_index">{{ product.name }}</option>
                                    </select>
                                    <span v-bind:class="{ error: errors.has('item.product_' + combo_size_index), }">{{ errors.first("item.product_" + combo_size_index) }}</span>
                                </td>
                                <td>
                                    <input type="number" :name="'item.product_quantity_' + combo_size_index" data-vv-as="Quantity" step="0.001" v-model="item.product_quantity" v-validate="'required'" class="form-control form-control-custom" :placeholder="enter_product_quantity"  autocomplete="off">
                                    <span v-bind:class="{ error: errors.has('item.product_quantity_' + combo_size_index), }">{{ errors.first("item.product_quantity_" + combo_size_index) }}</span>
                                </td>
                                <td>
                                    <select v-model="item.product_measurement" class="form-control form-control-custom custom-select">
                                        <option value="">{{ $t('Unit') }}</option>
                                        <option :value="measurement.id" v-for="(measurement,measurement_index) in item.measurements" :key="measurement_index">{{ measurement.label }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" :name="'item.product_price_' + combo_size_index" data-vv-as="Price" step="0.001" v-model="item.product_price" v-validate="'required'" class="form-control form-control-custom" :placeholder="enter_product_price"  autocomplete="off">
                                    <span v-bind:class="{ error: errors.has('item.product_price_' + combo_size_index), }">{{ errors.first("item.product_price_" + combo_size_index) }}</span>
                                </td>
                                <td>
                                    <i @click="remove_combo_item(index,combo_size_index)" class="fa fa-times text-danger" style="cursor:pointer;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <button type="button" class="btn btn-primary btn-sm" @click="addItem(index)">+ {{ $t('Add New Item') }}</button>
                                </td>  
                            </tr>
                           
                        </table>
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
                <p v-if="status == 0">{{ $t('If combo is inactive all the products using this combo may get affected.') }}</p>
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
    
    import _ from 'lodash';

    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : (this.combo_data == null)?'/api/add_combo':'/api/update_combo/'+this.combo_data.slack,
                combo_slack  : (this.combo_data == null)?'':this.combo_data.slack,
                
                // form data
                name : (this.combo_data == null)?'':this.combo_data.name,
                is_discount_enabled : (this.combo_data == null)?'':this.combo_data.is_discount_enabled,
                discount_type : (this.combo_data == null)?'':this.combo_data.discount_type,
                discount_value : (this.combo_data == null)?'':this.combo_data.discount_value,
                category_id : (this.combo_data == null)?'':this.combo_data.category.id,
                status : (this.combo_data == null)?'1':(this.combo_data.status == null)?'':this.combo_data.status.value,

                enter_name:this.$t('enter combo name'),
                enter_discount_value:this.$t('enter discount value'),
                enter_combo_size:this.$t('enter combo size'),
                success:this.$t('SUCCESS'),                
                statuses : (this.statuses_data == null)?'':this.statuses_data,
                combo_size_template : {
                    size_name : '',
                    items : [
                        {
                            group : '',
                            sub_group : '',
                            product : '',
                            product_measurement : '',
                            product_quantity : 1,
                            product_price : '',
                        }
                    ]
                },
                combo_sizes : (this.combo_data != null) ? (this.combo_data.sizes != null) ? this.combo_data.sizes : [] : [],
                products : (this.products_data == null)?'':this.products_data,
            }
        },
        props: {
            combo_data: [Object,Array],
            statuses_data: [Object,Array],
            categories_data : [Object,Array],
            groups_data : [Object,Array],
            products_data : [Object,Array]
        },
        mounted() {
            console.log('Add combo page loaded');
            // console.log(this.combo_data);
        },
        methods: {
            add_combo_size(size_name){

                let combo_size_template = this.combo_size_template;
                combo_size_template.size_name = size_name;
                this.combo_sizes.push( JSON.parse(JSON.stringify(combo_size_template)) ); 
                this.combo_size = '';

            },
            addItem(index){

                let item = this.combo_size_template.items[0];
                this.combo_sizes[index].items.push( JSON.parse(JSON.stringify(item)) ); 

            },
            load_sub_group(index,subindex){

                var group_id = this.combo_sizes[index].items[subindex].group;
                let groups = this.groups_data;
                for(var i = 0; i < groups.length; i++ ){
                    if(groups[i].id == group_id){
                        this.combo_sizes[index].items[subindex].subgroups = groups[i].sub;
                    }
                }

            },
            load_product_attribute(index,subindex,product){
                this.combo_sizes[index].items[subindex].measurements = product.measurement_units;
                this.combo_sizes[index].items[subindex].product_price = (product.sale_amount_including_tax != null) ? product.sale_amount_including_tax : 0;
            },
            remove_combo_item(index,size_index){
                this.combo_sizes[index].items.splice(this.combo_sizes[index].items.indexOf(this.combo_sizes[index].items[size_index]),1);
                console.log(this.combo_sizes[index].items[size_index]);
            },
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

                            formData.append("category_id", (this.category_id == null)?'':this.category_id);
                            formData.append("combo_name", (this.name == null)?'':this.name);
                            formData.append("is_discount_enabled", (this.is_discount_enabled == null)?'':this.is_discount_enabled);
                            formData.append("discount_type", (this.discount_type == null)?'':this.discount_type);
                            formData.append("discount_value", (this.discount_value == null)?'':this.discount_value);
                            formData.append("status", (this.status == null)?'':this.status);

                            formData.append("combo_sizes", JSON.stringify(this.combo_sizes) );

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
        },
    }
</script>
