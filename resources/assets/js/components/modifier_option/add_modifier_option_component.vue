<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="mb-3">

                <div class="d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <span class="text-title" v-if="modifier_option_slack == ''">{{ $t("Add Modifier Option") }}</span>
                        <span class="text-title" v-else>{{ $t("Edit Modifier Option") }}</span>
                    </div>
                </div>
                    
                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-2">
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="modifier">{{ $t("Modifier") }}</label>
                        <select name="modifier" v-model="modifier" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Modifier..') }}</option>
                            <option v-for="(modifier, modifier_index) in modifiers" v-bind:value="modifier.id" v-bind:key="modifier_index">
                                {{ modifier.label }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('modifier') }">{{ errors.first('modifier') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="label">{{ $t("Label") }}</label>
                        <input type="text" name="label" v-model="label" v-validate="'required|max:150'" class="form-control form-control-custom" :placeholder="enter_label"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('label') }">{{ errors.first('label') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="price">{{ $t("Price") }}</label>
                        <input type="text" name="price" v-model="price" v-validate="'required'" class="form-control form-control-custom" :placeholder="enter_price"  autocomplete="off">
                        <span v-bind:class="{ 'error' : errors.has('price') }">{{ errors.first('price') }}</span> 
                    </div>
                    <div v-bind:class="[reload_on_submit ? 'col-md-3' : 'col-md-12', 'form-group']">
                        <label for="status">{{ $t("Status") }}</label>
                        <select name="status" v-model="status" v-validate="'required|numeric'" class="form-control form-control-custom custom-select">
                            <option value="">C{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>
                        <span v-bind:class="{ 'error' : errors.has('status') }">{{ errors.first('status') }}</span> 
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col-6">
                    <label for="status">{{ $t("Select Ingredient") }}</label>

                    <select
                        name="search_ingredients"
                        v-model="search_ingredients"
                        class="form-control form-control-custom custom-select"
                        @change="add_ingredient_to_list(search_ingredients)"
                    >
                        <option value="">{{ $t("Choose Ingredient..") }} </option>
                        <option
                        v-for="(ingredient, index) in ingredient_list"
                        v-bind:value="ingredient.slack"
                        v-bind:key="index"
                        
                        >
                        {{ ingredient.name }}
                        </option>
                    </select>

                    </div>
                </div>
            <div class="form-row mt-5">
              <div class="form-group col-md-4 mb-1">
                <label for="name">{{ $t("Name & Description") }}</label>
              </div>
              <div class="form-group col-md-1 mb-1">
                <label for="quantity">{{ $t("Quantity") }}</label>
              </div>
              <div class="form-group col-md-2 mb-1">
                <label for="measurement_unit">{{ $t("Measuring Unit") }}</label>
              </div>
            </div>

        
            <div
              class="form-row mb-2"
              v-for="(ingredient, index) in ingredients"
              :key="index"
            >
              <div class="form-group col-md-4">
                <input
                  type="text"
                  v-bind:name="'ingredient.name_' + index"
                  v-model="ingredient.name"
                  v-validate="'max:250'"
                  data-vv-as="Name"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly="true"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.name_' + index),
                  }"
                  >{{ errors.first("ingredient.name_" + index) }}</span
                >
              </div>
           
              <div class="form-group col-md-1">
                <input
                  type="text"
                  v-bind:name="'ingredient.quantity_' + index"
                  :value="ingredient.quantity"
                  @input="ingredient.quantity = $event.target.value"
                  v-validate="
                    ingredient.name != '' ? 'required|decimal' : ''
                  "
                  data-vv-as="Quantity"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  step="0.01"
                  min="0.01"
                />
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.quantity_' + index),
                  }"
                  >{{ errors.first("ingredient.quantity_" + index) }}</span
                >
              </div>
              <div class="form-group col-md-2">
                <select
                  v-bind:name="'ingredient.measurement_id_' + index"
                  v-model="ingredient.measurement_id"
                  v-validate="ingredient.measurement_id != '' ? 'required' : ''"
                  class="form-control form-control-custom custom-select ingredient_measurement"
                >
                  <option value="">{{
                    $t("Choose Measurement Unit..")
                  }}</option>
                  <option
                    v-for="(measurement,
                    measurement_index) in ingredient.measurements"
                    v-bind:value="measurement.id"
                    v-bind:key="measurement_index"
                  >
                    {{ measurement.label }}
                  </option>
                </select>
                <span
                  v-bind:class="{
                    error: errors.has('ingredient.measurement_id_' + index),
                  }"
                  >{{
                    errors.first("ingredient.measurement_id_" + index)
                  }}</span
                >
              </div>
              <div class="form-group col-md-1">
                <button
                  type="button"
                  class="btn btn-outline-danger"
                  @click="remove_ingredient(index)"
                >
                  <i class="fas fa-times"></i>
                </button>
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
                api_link        : (this.modifier_option_data == null)?'/api/add_modifier_option':'/api/update_modifier_option/'+this.modifier_option_data.slack,
                modifier_option_slack  : (this.modifier_option_data == null)?'':this.modifier_option_data.slack,
                label : (this.modifier_option_data == null)?'':this.modifier_option_data.label,
                price : (this.modifier_option_data == null)?'':this.modifier_option_data.price,
                status : (this.modifier_option_data == null)?'1':(this.modifier_option_data.status == null)?'':this.modifier_option_data.status.value,
                modifier : (this.modifier_option_data == null) ? '' : this.modifier_option_data.modifier_id,
                modifiers : (this.modifier_data == null) ? '' : this.modifier_data,
                enter_price:this.$t('Please enter price'),
                enter_label:this.$t('Please enter label'),
                success:this.$t('SUCCESS'),
                ingredient_list: this.ingredient_data == null ? [] : this.ingredient_data,
                search_ingredients: "",
                ingredients: this.modifier_option_ingredients_data != null ? this.modifier_option_ingredients_data : []
            }
        },
        props: {
            statuses: Array,
            reload_on_submit: {
              type : Boolean,
              default : true,
            },
            modifier_data : [Object,Array],
            modifier_option_data : [Object,Array],
            ingredient_data : [Object,Array],
            modifier_option_ingredients_data : [Object,Array]
        },
        mounted() {
            console.log('modifier option page loaded');
            // console.log(this.modifier_option_data);
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
                            formData.append("modifier_id", (this.price == null)?'':this.modifier);
                            formData.append("label", (this.label == null)?'':this.label);
                            formData.append("price", (this.price == null)?'':this.price);
                            formData.append("status", (this.status == null)?'':this.status);
                            formData.append("ingredients", JSON.stringify(this.ingredients));
                            
                            axios.post(this.api_link, formData).then((response) => {

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
            },
            load_measurements_for_ingredient(ingredient_slack) {
                // alert(ingredient_slack);
                var formData = new FormData();
                formData.append("access_token", window.settings.access_token);
                formData.append("ingredient_slack", ingredient_slack);

                axios
                    .post("/api/load_measurements_for_ingredient", formData)
                    .then((response) => {
                        console.log(response.data.data);
                    for (var i in this.ingredients) {
                        if (this.ingredients[i].ingredient_slack == ingredient_slack) {
                        this.ingredients[i].measurements = response.data.data;
                        // console.log(this.ingredients[i].measurements);
                        }
                    }
                    })
                    .catch((error) => {
                    console.log(error);
                    });
                },
            add_ingredient_to_list(ingredient) {
                this.load_measurements_for_ingredient(ingredient);

                let item = this.ingredient_list.find(function(rs) {
                    return rs.slack == ingredient;
                });

                if (item.slack != "") {
                    var current_ingredient = {
                    ingredient_id: item.id,
                    ingredient_slack: item.slack,
                    name: item.name,
                    quantity: 1,
                    measurement_id: "",
                    measurements: "",
                    };
                }

                var item_found = false;
                for (var i = 0; i < this.ingredients.length; i++) {
                    if (this.ingredients[i].ingredient_slack == item.slack) {
                    this.ingredients[i].quantity++;
                    item_found = true;
                    }
                }

                // if( this.ingredients[0].name == '' && this.ingredients[0].quantity == ''){
                if (this.ingredients.length == 0) {
                    this.$set(this.ingredients, 0, current_ingredient);
                } else {
                    if (item_found == false) {
                    this.ingredients.push(current_ingredient);
                    }
                }

            },
            remove_ingredient(index) {
                this.ingredients.splice(index, 1);
                if (index == 0) {
                    // this.update_ingredient_list();
                }
            },
        }
    }
</script>
