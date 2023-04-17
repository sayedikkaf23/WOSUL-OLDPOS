<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 d-flex flex-wrap mb-4">
                    <div class="mr-auto">
                        <div class="d-flex">
                            <div>
                                <span class="text-title mr-5">
                                    {{ $t("Transaction ID") }} #{{ order_basic.id }}
                                </span>
                                <span class="text-title">
                                    {{ $t("Reference No") }} #{{ order_basic.reference_number }}
                                </span><br />
                                <span class="text-title">
                                    {{ $t("Order") }} #{{ order_basic.order_number }} 
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <span v-show="(return_order_exist == true && order_basic.status.value == 1) ||
                            (damage_order_exist == true && order_basic.status.value == 1)" class="label green-label"> Partial Returned/Damaged
                        </span>
                        <span v-if="order_basic.restaurant_mode == 1 && order_basic.kitchen_status != null " 
                            v-bind:class="order_basic.kitchen_status.color" class="mr-2">
                            {{ $t(order_basic.kitchen_status.label) }}
                        </span>
                        <span v-bind:class="order_basic.status.color">
                            {{ order_basic.status.label.toLowerCase() == "return" ? $t("Return/Damaged"): $t(order_basic.status.label)}}</span>
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-wrap mb-4">
                    <p v-html="server_errors" v-bind:class="[error_class]"></p>

                    <div class="ml-auto">
                        <!-- <button v-show="return_order_exist == true" class="btn btn-success mr-1" >
                                <i
                                class="fa fa-circle-notch fa-spin"
                                v-if="order_processing == true"
                                ></i>
                                {{ $t("Order Returned") }}
                            </button> -->
                           
                        <a type="submit" class="btn btn-warning mr-1" v-show="order_basic.status.label.toLowerCase() !== 'return'" 
                            v-on:click="return_order()" v-bind:disabled="order_processing == true" v-if="is_return == true">
                            <i class="fa fa-circle-notch fa-spin" v-if="order_processing == true "></i>{{ $t("Return Order") }}
                        </a>
                        <button type="button" v-if="order_basic.status.constant == 'PARTIAL_PAYMENT'" v-on:click="showPartialPaymentPopup()" 
                            class="btn btn-warning mr-3 ml-2" :class="[processing ? 'disabled' : '']" :disabled="processing"> 
                            <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                            {{ $t('Make Payment') }} 
                        </button>
                        <!--<button
                            type="submit"
                            class="btn btn-danger mr-1"
                            v-if="delete_order_access == true"
                            v-on:click="delete_order()"
                            v-bind:disabled="order_processing == true"
                        >
                            <i
                            class="fa fa-circle-notch fa-spin"
                            v-if="order_processing == true"
                            ></i>
                            {{ $t("Delete Order") }}
                        </button>-->
                        <button type="button" class="btn btn-outline-primary mr-1" v-if="share_invoice_sms_access == true" 
                            v-on:click="share_invoice_as_sms()" v-bind:disabled="send_sms_processing == true">
                            <i class="fa fa-circle-notch fa-spin" v-if="send_sms_processing == true"></i>
                            {{ $t("Share Invoice as SMS") }}
                        </button>

                        <a class="btn btn-outline-primary" v-if="print_option_status.includes(parseInt(order_basic.status.value))" 
                            v-bind:href="print_order_link" target="_blank">
                            {{ $t("Print Receipt") }}
                        </a>

                        <a class="btn btn-success text-white" v-if="print_option_status.includes(parseInt(order_basic.status.value))" 
                            v-bind:href="print_pos_receipt_link" target="_blank">
                            {{ $t("Print POS Receipt") }}
                        </a>
                    </div>
                </div>
            </div>
            <div v-show="order_basic.restaurant_mode == 1">
                <div class="mb-2">
                    <span class="text-subhead">{{$t("Restaurant Mode Information")}}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="email">{{ $t("Order Type") }}</label>
                        <p>{{ order_basic.order_type }}</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="email">{{ $t("Billing Type") }}</label>
                        <p>
                            {{ order_basic.billing_type_data != null ? order_basic.billing_type_data.label : "-" }}
                        </p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="email">{{ $t("Counter Name") }}</label>
                        <p>
                            {{ order_basic.counter_name != null ? order_basic.counter_name : "-"}}
                        </p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="email">{{ $t("Table Number or Name") }}</label>
                        <p>{{ order_basic.table != null ? order_basic.table : "-" }}</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="email">{{ $t("Waiter") }}</label>
                        <p>
                            {{ order_basic.waiter_data != null ? order_basic.waiter_data.fullname 
                                + " (" + order_basic.waiter_data.user_code + ")" : "-" }}
                        </p>
                    </div>
                </div>
            </div>
            <hr />

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="email">{{ $t("Email") }}</label>
                    <p>{{ order_basic.customer_email }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">{{ $t("Phone") }}</label>
                    <p>{{ order_basic.customer_phone }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">{{ $t("Payment Type") }}</label>
                    <p v-if="order_basic.credit_amount > 0" > 
                        {{ order_basic.payment_method }}: {{ order_basic.credit_amount}} {{ order_basic.currency_code}}  
                    </p>
                    <p v-else >
                        {{ order_basic.payment_method }}
                    </p>
                    <p v-if="order_basic.credit_amount > 0 && order_basic.cash_amount > 0" > 
                        {{ $t('Cash')}}: {{ order_basic.cash_amount}} {{ order_basic.currency_code}}
                    </p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>
                        {{order_basic.created_by == null ? "-" : order_basic.created_by["fullname"] + " (" + order_basic.created_by["user_code"] +")"}}
                    </p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>
                        {{ order_basic.updated_by == null ? "-" : order_basic.updated_by["fullname"] + " (" + order_basic.updated_by["user_code"] + ")" }}
                    </p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ order_basic.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ order_basic.updated_at_label }}</p>
                </div>
            </div>
            <hr />

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Order Level Tax Information")}}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="tax_code">{{ $t("Tax Type") }}</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tax_percentage">{{ $t("Tax Percentage") }}</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tax_amount">{{ $t("Tax Amount") }}</label>
                    </div>
                </div>
                <div class="form-row mb-2" v-for="tax_component in order_data.order_level_tax_components" :key="tax_component.tax_type" 
                    v-if="tax_component.tax_type != 'No Tax' && tax_component.tax_type != 'NO TAX'">
                    <div class="form-group col-md-3">
                        <p>{{ tax_component.tax_type }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <p>{{ tax_component.tax_percentage }}%</p>
                    </div>
                    <div class="form-group col-md-3">
                        <p>{{ tax_component.tax_amount | roundDecimal}}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- <div class="row">
                        <div class="table-responsive" v-if="order_basic.order_level_tax_percentage > 0" >
                        <table class="table display nowrap text-nowrap w-100">
                            <thead>
                            <tr>
                                <th scope="col">{{ $t("Tax Type") }}</th>
                                <th scope="col">{{ $t("Tax Percentage") }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="(tax_component,
                                key,
                                index) in order_basic.order_level_tax_components"
                                v-bind:key="index"
                            >
                                <td>{{ tax_component.tax_type }}</td>
                                <td>{{ Math.round(tax_component.tax_percentage) }}%</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                        <span class="mb-2" v-else>{{$t("No Order Level Tax Components") }}</span>
                    </div> -->
                </div>
            </div>
            <hr />

            <!-- <div class="mb-3">
                    <div class="mb-2">
                    <span class="text-subhead">{{ $t("Store Level Tax Information") }}</span>
                    </div>

                    <div class="mb-3"> {{ $t("No Store Level Tax Information") }} </div>
                </div> -->
            <hr />

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Order Level Discount Information")}}</span>
                </div>
                <div class="form-row mb-2" v-if="order_basic.order_level_discount_percentage > 0">
                    <div class="form-group col-md-3">
                        <label for="discount_code">{{ $t("Discount Code") }}</label>
                        <p>{{ order_basic.order_level_discount_code }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="discount_percentage">{{ $t("Discount Percentage") }}</label>
                        <p>{{ order_basic.order_level_discount_percentage }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="discount_code_label">{{ $t("Discount Amount") }}</label>
                        <p>{{ order_basic.order_level_discount_amount }}</p>
                    </div>
                </div>
                <div class="mb-3" v-else>
                    {{ $t("No Order Level Discount Information") }}
                </div>
            </div>
            <hr />

            <div class="mb-3" v-show="return_reason_data != ''">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Reason for Return") }}</span>
                </div>
                <div class="mb-3">{{ return_reason_data }}</div>
            </div>

            <div class="mb-3" v-show="damage_reason_data != ''">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Reason for Damage") }}</span>
                </div>
                <div class="mb-3">{{ damage_reason_data }}</div>
            </div>
            <hr />

            <div id="product_details" v-show="view_product_details">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Product Information") }}</span>
                </div>
                <!-- {{products[0]}} -->
                <div class="table-responsive mb-2">
                    <table class="table table-striped display nowrap text-nowrap w-100">
                        <thead>
                            <tr>
                                <!-- <th scope="col">#</th> -->
                                <th scope="col">{{ $t("Product") }}</th>
                                <th scope="col">{{ $t("Product Code") }}</th>
                                <th scope="col" class="text-right">{{ $t("Quantity") }}</th>
                                <th scope="col" class="text-right">{{ $t("Price") }} ({{ $t("EXCL Tax") }})</th>
                                <th scope="col" class="text-right" v-if="order_basic.bonat_discount > 0">{{ $t("Discount") }}</th>
                                <th scope="col" class="text-right">{{ $t("Discount %") }}</th>
                                <th scope="col" class="text-right">{{ $t("Discount Amount") }} </th>
                                <!-- <th scope="col" class="text-right">{{ $t("Tax %") }}</th>
                                <th scope="col" class="text-right">{{ $t("Tax Amount") }}</th> -->
                                <th scope="col" class="text-right">{{ $t("Total Price") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(order_product, key, index) in products" v-bind:value="order_product.product_slack" v-bind:key="index" class="bg-white">
                                
                                <!-- <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">{{ key + 1 }}</td> -->
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                                    <span v-if="order_product.name == undefined" class="text-bold fs-15">{{ order_product.combo_name }}</span>
                                    <span v-else>
                                        <span :class="(order_product.name != undefined && order_product.combo_name != '') ? 'text-muted border-0' : 'text-dark fs-15' " >{{ order_product.name }}</span>
                                    </span>
                                   
                                    <i class="fa fa-gift text-success" aria-hidden="true" style="font-size: 11px;" v-if= "order_product.is_gifted  == 1"></i>
                                    <br />
                                    <small v-if="order_product.modifier_options.length" v-for="modifier in order_product.modifier_options">
                                        {{ modifier.modifier_label }} : {{ modifier.modifier_option_label }} <br />
                                    </small>
                                    <span v-if="order_product.note != '' && order_product.note != null">
                                        <small class="text-danger">Note: {{ order_product.note }}</small>
                                    </span>

                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                                    <span v-if="order_product.combo_cart_id == undefined || order_product.combo_cart_id == ''">{{ order_product.product_code }}</span>
                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right">
                                    <span :class="(order_product.name != undefined && order_product.combo_name != '') ? 'text-muted border-0' : '' ">{{ order_product.quantity }}</span>
                                     <br />
                                    <small v-if="order_product.modifier_options.length" v-for="modifier in order_product.modifier_options">
                                        - <br />
                                    </small>
                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">
                                     {{ order_product.price }}
                                    <br />
                                    <small :class="(order_product.name != undefined && order_product.combo_name != '') ? 'text-muted border-0' : '' " v-if="order_product.modifier_options.length" v-for="modifier in order_product.modifier_options">
                                        {{ modifier.modifier_option_price }} <br />
                                    </small>
                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-if="order_product.bonat_discount == true" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">
                                    {{ order_product.bonat_discount == true ? "BONAT" : "-" }}
                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">
                                    {{ order_product.discount_percentage }}
                                </td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">{{ order_product.discount_amount }}</td>
                                <!-- <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right">{{ order_product.tax_percentage }}</td>
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right">{{ order_product.tax_amount }}</td> -->
                              
                                <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">
                                    <span v-if="order_product.name == undefined" >{{ order_product.combo_total_price }}</span>
                                    <span v-else> <span v-if="order_product.combo_name == undefined || order_product.combo_name == '' ">{{ order_product.total_after_discount }}</span> </span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"> {{ $t("Sub Total") }} ({{ $t("EXCL Tax") }}) </td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.sale_amount_subtotal_excluding_tax }}
                                </td>
                               
                            </tr>

                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right">{{ $t("Additional Discount") }}
                                    <span v-if="order_basic.additional_discount_percentage && order_basic.sale_amount_subtotal_excluding_tax > 0">
                                        ({{ order_basic.additional_discount_percentage }} % )
                                    </span>
                                </td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    <span v-if="order_basic.sale_amount_subtotal_excluding_tax > 0">
                                        {{ order_basic.additional_discount_amount }}
                                    </span>
                                    <span v-else>
                                        0.00
                                    </span>
                                </td>
                               
                            </tr>

                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right">{{ $t("Total After Discount") }}</td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.total_after_discount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right">{{ $t("Total Tax") }}</td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.total_tax_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right text-bold">
                                    {{ $t("Total") }} ({{ $t("INCL Tax") }})
                                </td>
                                <td colspan="1" :class=" lang == 'ar' ? 'text-right text-bold' : 'text-right text-bold'" 
                                    v-html="calculateTotalIncludingTax()"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="edit_product" v-show="view_edit_product">
                <form @submit.prevent="select_reason()" class="mb-3">
                    <div class="mb-2">
                        <span class="text-subhead">{{$t("Edit Product Information") }}</span>
                    </div>
                    <div class="table-responsive mb-2">
                        <table class="table table-striped display nowrap text-nowrap w-100">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col">{{ $t("Product") }}</th>
                                    <td scope="col" v-show="restaurant_mode == true">{{ $t("Is Wastage?") }}</td>
                                    <th scope="col">{{ $t("Product Code") }}</th>
                                    <th scope="col" class="text-center">{{ $t("Max. Ret. Quantity") }}</th>
                                    <th scope="col" class="text-right">{{ $t("Quantity") }}</th>
                                    <th scope="col" class="text-right">{{ $t("Price") }} ({{ $t("EXCL Tax") }})</th>
                                    <th scope="col" class="text-right">{{ $t("Discount %") }}</th>
                                    <th scope="col" class="text-right">{{ $t("Discount Amount") }} </th>
                                    <!-- <th scope="col" class="text-right">{{ $t("Tax %") }}</th>
                                    <th scope="col" class="text-right">{{ $t("Tax Amount") }}</th> -->
                                    <th scope="col" class="text-right">{{ $t("Total Price") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(order_product, key, index) in products" v-bind:value="order_product.product_slack" v-bind:key="index" :id="'order_' + key"  class="bg-white" v-if="(order_product.combo_id != undefined && !order_product.is_returned)" >
                                    
                                    <!-- <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " scope="row">{{ key + 1 }}</td> -->
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " v-show="restaurant_mode == true">
                                        <input type="checkbox" v-model="order_product.is_wastage" />
                                    </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                                        <span v-if="order_product.name == undefined" class="text-bold fs-15">{{ order_product.combo_name }}</span>
                                        <span v-else>
                                            <span :class="(order_product.name != undefined && order_product.combo_name != '') ? 'text-muted border-0' : 'text-dark fs-15' " >{{ order_product.name }}</span>
                                        </span>
                                        
                                        <i class="fa fa-gift text-success" aria-hidden="true" style="font-size: 11px;" v-if= "order_product.is_gifted  == 1"></i>
                                    </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' ">
                                        <span v-if="order_product.combo_cart_id == undefined || order_product.combo_cart_id == ''">{{ order_product.product_code }}</span>
                                    </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-center"> <span v-if="order_product.name != undefined">{{ order_product.max_quantity }}</span> </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-center" v-if="order_product.is_gifted == 1">{{ order_product.quantity }}</td>
                                    
                                    <td  :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-if="order_product.is_gifted == 0">
                                        
                                        <div v-if="order_product.name != undefined && order_product.combo_name != ''"></div>
                                        <div v-else>
                                            <input type="number" class="form-control form-control-custom" v-bind:name="'order_product.quantity_' + key" 
                                                v-model="order_product.quantity" v-validate="'required|decimal|min_value:0'" data-vv-as="Quantity" autocomplete="off" 
                                                @keypress="formatQuantityDecimal(key, $event)"
                                                step="0.01" min="0" :id="'return_order_quantity_' + key" v-bind:max="order_product.max_quantity" 
                                                v-on:input="validate_quantity($event);"  />
                                            <span v-bind:class="{ error: errors.has('product_quantity') }">
                                                {{ errors.first("product_quantity") }}
                                            </span>
                                        </div>
                                    </td>
                                    <td v-else>
                                        <input type="checkbox" :value="order_product.combo_name" @input="add_combo_to_return_cart($event,order_product.combo_cart_id)" > Return
                                    </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">{{ order_product.price }} <br />
                                        <small v-if="order_product.modifier_options.length" v-for="modifier in order_product.modifier_options">
                                            {{ modifier.modifier_label }} : {{ modifier.modifier_option_label }} -- {{ modifier.modifier_option_price }} <br />
                                        </small>
                                    </td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">{{ order_product.discount_percentage }}</td>
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">{{ order_product.discount_amount }}</td>
                                    <!-- <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right">{{ order_product.tax_percentage }}</td> -->
                                    <!-- <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right">{{ order_product.tax_amount }}</td> -->
                                    <td :class="(order_product.name != undefined && order_product.combo_name != '') ? 'py-0 text-muted border-0' : '' " class="text-right" v-bind:style = "(order_product.is_gifted  == 1)?'text-decoration: line-through;':''">
                                    <span v-if="order_product.name == undefined" > <span v-if="check_if_returning(order_product.combo_cart_id)">{{ order_product.combo_total_price }}</span> <span v-else>0.00</span> </span>
                                    <span v-else> <span v-if="order_product.combo_id == undefined || order_product.combo_id == '' ">{{ order_product.total_after_discount }}</span> </span>
                                    </td>
                                </tr>

                            <tr>
                                
                                <td colspan="6"></td>
                                <td class="text-right"> {{ $t("Sub Total Return") }} ({{ $t("EXCL Tax") }}) </td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.sale_amount_subtotal_excluding_tax }} 
                                </td>
                               
                            </tr>

                            <tr>
                                <td colspan="6"></td>
                                <td class="text-right">{{ $t("Additional Discount") }}
                                    <span v-if="order_basic.additional_discount_percentage && order_basic.sale_amount_subtotal_excluding_tax > 0">
                                        ({{ order_basic.additional_discount_percentage }} % )
                                    </span>
                                </td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    <span v-if="order_basic.sale_amount_subtotal_excluding_tax > 0">
                                        {{ order_basic.additional_discount_amount }}
                                    </span>
                                    <span v-else>
                                        0.00
                                    </span>
                                </td>
                               
                            </tr>

                            <tr>
                                <td colspan="6"></td>
                                <td class="text-right">{{ $t("Total After Discount") }}</td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.total_after_discount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td class="text-right">{{ $t("Total Tax") }}</td>
                                <td colspan="1" :class="lang == 'ar' ? 'text-right' : 'text-right'">
                                    {{ order_basic.total_tax_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td class="text-right text-bold">
                                    {{ $t("Total") }} ({{ $t("INCL Tax") }})
                                </td>
                                <td colspan="1" :class=" lang == 'ar' ? 'text-right text-bold' : 'text-right text-bold'" 
                                    v-html="calculateTotalIncludingTax()"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex-wrap mb-4">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true" @click="clicked_button = 'return'">
                                <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                                {{ $t("Return") }}
                            </button>
                            <input type="hidden" id="txtOption" value="" />
                            <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true" @click="clicked_button = 'damage'">
                                <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                                {{ $t("Damage") }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" v-if="order_basic.status.constant == 'PARTIAL_PAYMENT'">
                <div class="col-md-12 mb-3">
                    <div class="form-row mb-2 alert-warning">
                        <div class="form-group col-6 text-right">
                            <label for="transaction_date">{{ $t("Total Amount") }} ({{ order_basic.currency_code }})</label>
                            <div class="text-subtitle">{{ order_basic.total_order_amount }}</div>
                        </div>
                        <div class="form-group col-3 text-right">
                            <label for="transaction_date">{{ $t("Paid Amount") }} ({{ order_basic.currency_code }})</label>
                            <div class="text-subtitle">{{ partial_pay_total_paid_amount }}</div>
                        </div>
                        <div class="form-group col-3 text-right">
                            <label for="transaction_date">{{ $t("Pending Amount") }} ({{ order_basic.currency_code }})</label>
                            <div class="text-subtitle">{{ partial_pay_pending_amount }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr />

            <transactionlistcomponent :transaction_list="transactions"></transactionlistcomponent>
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">
                    {{ $t("Cancel") }}
                </button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true">
                    <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                    {{ $t("Continue") }}
                </button>
            </template>
        </modalcomponent>

        <modalcomponent v-if="show_share_invoice_sms_modal" v-on:close="show_share_invoice_sms_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                {{ $t("Are you sure you want to share the invoice as SMS to") }}
                {{ order_basic.customer_phone }}?
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">
                    {{ $t("Cancel") }}
                </button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true">
                    <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                    {{ $t("Continue") }}
                </button>
            </template>
        </modalcomponent>

        <modalcomponent v-if="show_no_mobile_modal" v-on:close="show_no_mobile_modal = false" :modal_title_class="modal_status">
            <template v-slot:modal-header>
                {{ $t("Error") }}
            </template>
            <template v-slot:modal-body>
                {{ $t("There is no mobile number registered for the customer") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-primary" @click="$emit('close')">
                    {{ $t("OK") }}
                </button>
            </template>
        </modalcomponent>

        <modalcomponent v-if="show_reason_modal" v-on:close="show_reason_modal = false">
            <template v-slot:modal-header>
                {{ $t("Please Choose A Reason for Return") }}
            </template>
            <template v-slot:modal-body>
                <p
                    v-html="return_form_server_errors"
                    v-bind:class="[error_class]"
                ></p>
                <form data-vv-scope="return_form">
                    <div class="form-group">
                        <select v-if="order_basic.status.constant == 'HOLD'" v-model="selected_payment" name="return_payment" 
                            class="form-control form-control-custom custom-select">
                            <option value="" selected>Select Payment</option>
                            <option v-for="payment in payment_methods" :value="payment.slack" :key="payment.slack">{{ payment.label }}</option>
                        </select>
                        <select v-else v-model="selected_payment" name="return_payment"  v-validate="'required'" class="form-control form-control-custom custom-select">
                            <option value="" selected>Select Payment</option>
                            <option v-for="payment in payment_methods" :value="payment.slack" :key="payment.slack">{{ payment.label }}</option>
                        </select>
                        <span v-bind:class="{ error: errors.has('return_form.return_payment'),}">{{ errors.first("return_form.return_payment") }}</span>
                    </div>
                    <div class="form-group">
                        <select v-if="order_basic.status.constant == 'HOLD'" v-model="return_reason" name="return_reason" 
                            class="form-control form-control-custom custom-select">
                            <option value="" selected>Select Reason</option>
                            <option value="Customer Cancelled">Customer Cancelled</option>
                            <option value="Product Not Available">Product Not Available</option>
                            <option value="0">Other</option>
                        </select>
                        <select v-else  v-model="return_reason" name="return_reason" v-validate="'required'" 
                            class="form-control form-control-custom custom-select">
                            <option value="" selected>Select Reason</option>
                            <option value="Customer Cancelled">Customer Cancelled</option>
                            <option value="Product Not Available">Product Not Available</option>
                            <option value="0">Other</option>
                        </select>
                        <span v-bind:class="{error: errors.has('return_form.return_reason'),}">{{ errors.first("return_form.return_reason") }}</span>
                    </div>
                    <input v-if="return_reason == 0 && return_reason != ''" v-model="other_return_reason" class="form-control form-control-custom mt-3" autocomplete="off" placeholder="Enter Your Reason.." />
                </form>
            </template>
            <template v-slot:modal-footer>
                <button v-if="order_basic.status.constant == 'HOLD'" type="button" class="btn btn-light" @click="submit_form()">
                    {{ $t("Skip") }}
                </button>
                <button v-else type="button" class="btn btn-light" @click="$emit('close')">
                    {{ $t("Cancel") }}
                </button>
                <button type="button" class="btn btn-primary" @click="submit_form()">
                    {{ $t("Continue") }}
                </button>
            </template>
        </modalcomponent>

        <modalcomponent v-if="show_payment_details_modal" v-on:close="show_payment_details_modal = false" :modal_width="'modal-container-md'">
            <template v-slot:modal-header>
                {{ $t("Make Payment") }}
            </template>
            <template v-slot:modal-body>
                <div class="row">
                <div class="col-md-12 mb-3">
                    <p v-if="partial_pay_server_errors.length > 0" class="alert alert-danger" v-html="partial_pay_server_errors" v-bind:class="[error_class]"></p>

                    <div v-if="partial_pay_pending_amount > 0">
                    <div class="form-row mb-2">
                        <div class="form-group col-4 text-right">
                        <label for="transaction_date">{{ $t("Total Amount") }} ({{ order_basic.currency_code }})</label>
                        <div class="text-subtitle">{{ order_basic.total_order_amount }}</div>
                        </div>
                        <div class="form-group col-4 text-right">
                        <label for="transaction_date">{{ $t("Paid Amount") }} ({{ order_basic.currency_code }})</label>
                        <div class="text-subtitle">{{ partial_pay_total_paid_amount }}</div>
                        </div>
                        <div class="form-group col-4 text-right">
                        <label for="transaction_date">{{ $t("Pending Amount") }} ({{ order_basic.currency_code }})</label>
                        <div class="text-subtitle">{{ partial_pay_pending_amount }}</div>
                        </div>
                    </div>

                    <div class="form-row mb-2" v-if="transactions != ''">
                        <h5> {{ $t("Payment History") }}</h5>
                        <table class="table table-striped ">
                        <thead>
                            <tr>
                            <th class="nowrap">Date<br /></th>
                            <th class="nowrap">Amount(SAR)<br /></th>
                            <th class="nowrap">Payment Method<br /></th>
                            <th class="nowrap">Notes<br /></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="partial_payment_detail in transactions" :key="partial_payment_detail.id">
                            <td>{{ partial_payment_detail.transaction_date }}</td>
                            <td>{{ partial_payment_detail.amount }}</td>
                            <td>{{ partial_payment_detail.payment_method }}</td>
                            <td>{{ partial_payment_detail.notes }}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                        <label for="amount">{{ $t("Amount") }} ({{ order_basic.currency_code }})</label>
                        <input type="number" name="amount" v-model="partial_pay_paid_amount" v-validate="`required|decimal|max_value:${partial_pay_pending_amount}`" 
                            class="form-control form-control-custom" autocomplete="off" step="0.01" min="0" />
                        <span v-bind:class="{ error: errors.has('amount') }">{{ errors.first("amount") }}</span>
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-12">
                        <label for="notes">{{ $t("Notes") }}</label>
                        <textarea name="notes" v-model="partial_pay_notes" v-validate="'max:65535'"
                            class="form-control form-control-custom" rows="3" ></textarea>
                        <span v-bind:class="{ error: errors.has('notes') }">{{errors.first("notes")}}</span>
                        </div>
                    </div>
                    </div>
                    <div v-if="partial_pay_pending_amount == 0">
                    <p>{{ $t("You have already made the payment(s)") }}.</p>
                    </div>
                </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="show_payment_details_modal = false">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="submit_partial_payment()" v-bind:disabled="processing == true"> 
                    <i class='fa fa-circle-notch fa-spin' v-if="processing == true"></i> 
                    <span>{{ $t("Continue") }}</span>
                </button>
            </template>
        </modalcomponent>
    </div>
</template>

<script>
"use strict";

import number_format from "locutus/php/strings/number_format";

export default {
    data() {
        return {
            clickcount: 0,
            selected_payment: "",
            server_errors: "",
            partial_pay_server_errors: '',
            error_class: "",
            modal_status: "",
            order_processing: false,
            processing: false,
            show_modal: false,

            show_share_invoice_sms_modal: false,
            show_no_mobile_modal: false,
            send_sms_processing: false,
            view_edit_product: false,
            view_product_details: true,

            delete_order_api_link: "/api/delete_order/" + this.order_data.slack,
            sms_invoice_api_link: "/api/share_invoice_sms/" + this.order_data.slack,
            return_order_api_link: "/api/return_order_list",

            slack: this.order_data.slack,
            order_basic: this.order_data,
            products: this.order_data.products,
            transactions: this.order_data.transactions,
            grand_total: 0,
           // store_tax_percentage_amt: this.store_tax_percentage == null ? "" : this.store_tax_percentage,
            total_product_price: 0,
            show_reason_modal: false,
            clicked_button: "",
            return_reason: "",
            is_return: false,
            other_return_reason: "",
            item_level_total_tobacco_tax_details: [],
            item_level_total_tax_details: [],
            additional_discount_percentage: this.order_data.additional_discount_percentage,
            sale_amount_subtotal_excluding_tax: this.order_data.sale_amount_subtotal_excluding_tax,
            additional_discount_amount: this.order_data.additional_discount_amount,
            print_option_status: [1,2,5,7],
            returning_combos : [],
            partial_pay_paid_amount: 0,
            partial_pay_notes: '',
            partial_pay_total_paid_amount: 0,
            partial_pay_pending_amount: 0,
            show_payment_details_modal: false,
        };
    },
    props: {
      //  store_tax_percentage: "",
        order_data: [Array, Object],
        payment_methods: [Array, Object],
        damage_quantity: String,
        return_quantity: String,
        delete_order_access: Boolean,
        return_order_exist: Boolean,
        damage_order_exist: Boolean,
        share_invoice_sms_access: Boolean,
        print_order_link: String,
        print_pos_receipt_link: String,
        additional_discount_amt: String,
        restaurant_mode: Boolean,
        return_reason_data: String,
        damage_reason_data: String,
        lang: String,
    },
    mounted() {
        console.log("order_basic.status.value =", this.order_basic.status);
        // console.log('this.products =',this.products);
        // this.calculate_price();
  
        var valObj = this.order_basic.products.filter(function(elem){
            if(elem.is_gifted == 0 && elem.quantity > elem.return_quantity ) {
                return 1;
            }
        });
       
        if(valObj.length > 0){
            this.is_return = true;
        }

        this.showPartialPayment();

    },
    filters: {
        roundDecimal: function(value) {
            // return number_format(value, 2, ".", "");
            return Number(parseFloat(value).toFixed(2)).toLocaleString();
        },
        formatDecimal: function(value) {
            var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
            return Number(value.toString().match(re)[0]).toLocaleString();
            // return parseFloat(value).toFixed(2);
        }
    },
    methods: {
       
        calculateTotalAfterDiscount() {
            return Number(this.order_basic.total_after_discount).toFixed(2);
        },
        calculateTotalIncludingTax() {
            return Number(this.order_basic.total_order_amount).toFixed(2);
        },
        delete_order() {
            this.$off("submit");
            this.$off("close");
            this.show_modal = true;

            this.$on("submit", function () {
                this.processing = true;
                this.order_processing = true;

                var formData = new FormData();
                formData.append("access_token", window.settings.access_token);

                axios
                    .post(this.delete_order_api_link, formData)
                    .then((response) => {
                        if (response.data.status_code == 200) {
                            if (response.data.link != "") {
                                window.location.href = response.data.link;
                            } else {
                                location.reload();
                            }
                        } else {
                            this.show_modal = false;
                            this.processing = false;
                            try {
                                var error_json = JSON.parse(response.data.msg);
                                this.loop_api_errors(error_json);
                            } catch (err) {
                                this.server_errors = response.data.msg;
                            }
                            this.error_class = "error";
                        }
                        this.order_processing = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            });

            this.$on("close", function () {
                this.show_modal = false;
            });
        },

        return_order() {
            this.view_edit_product = true;
            this.view_product_details = false;
            this.clickcount++;
            if (this.clickcount == 1) {
                let index = 0;
                // console.log("this.products", this.products);
                for (let product in this.products) {
                    this.products[product].ordered_quantity = Number( this.products[product].quantity );
                    this.products[product].quantity = (parseFloat(this.products[product].quantity) - parseFloat(this.products[product].return_quantity)).toFixed(2);
                    this.products[product].max_quantity = (parseFloat(this.products[product].max_quantity) - parseFloat(this.products[product].return_quantity)).toFixed(2);

                    if (this.products[product].quantity == 0) {
                        document.getElementById("order_" + product).style.display = "none";
                    }
                }
           
                this.calculate_price();
            }
            document.getElementById("product_details").scrollIntoView();
        },

        calculate_price() {
            var app_obj = this;
            var grand_total = 0;
            var item_total_excl_tax_before_discount = 0;
            var item_total_excluding_tax = 0;
            var last_additional_discount_amount = 0;
            var final_total_tax = 0;
            var total_quantity = 0;
            var total_max_quantity = 0;
            var additional_discount_amount = parseFloat(this.additional_discount_amount);
            if(isNaN(additional_discount_amount)){ additional_discount_amount = 0; }
            var sale_amount_subtotal_excluding_tax = this.sale_amount_subtotal_excluding_tax;
            var additional_discount_percentage = this.additional_discount_percentage;
            if (this.order_basic.discount_type == 1) {
                additional_discount_percentage = (parseFloat(additional_discount_amount) / sale_amount_subtotal_excluding_tax)*100;
                if(isNaN(additional_discount_percentage)){
                    additional_discount_percentage = 0;
                }
            } 
            
            if (this.item_level_total_tax_details == undefined) {
              this.item_level_total_tax_details = []
            }

            if (this.item_level_total_tobacco_tax_details == undefined) {
                this.item_level_total_tobacco_tax_details = [];
            }

            for (var index in this.products) {
               
                if(this.products[index].is_gifted == 0){

                    if(this.products[index].tobacco_tax_components != null ){
                        var tobacco_tax_detail = this.products[index].tobacco_tax_components[0];
                                    
                        this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id] = tobacco_tax_detail.tax_name_id;
                        if (this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id]['item_tax_percentage'] == undefined) {
                            this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id] = [];
                        }
                        this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id]['item_tax_percentage'] = tobacco_tax_detail.tax_percentage;
                        this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id]['item_tax_label'] = tobacco_tax_detail.tax_type;
                        this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id]['item_tax_total'] = 0;
                    }

                    if (this.products[index].tax_code_id > 0) {
                        $.each(this.products[index].tax_components, function(tax_key, tax_detail) {
                            // console.log('tax_key =',tax_key);
                            app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = tax_detail.tax_name_id
                            if (app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] == undefined) {
                                app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = []
                            }
                            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] = tax_detail.tax_percentage
                            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_label'] = tax_detail.tax_type
                            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_total'] = 0;                        
                        });
                    }
                }
            }

            for (var index in this.products) {
                if(this.products[index].is_gifted == 0){
                    var cart_length = Object.keys(this.products).length;
                    let modifier_price = 0; //this.products[index].total_modifier_amount;
                    for (let modifier in this.products[index].order_product_modifier_options) {
                        modifier_price += parseFloat(this.products[index].order_product_modifier_options[ modifier].modifier_option_price);
                    }

                    // returning item
                    // console.log(this.products[index],'returning item');
                    
                    var item_total_max = 0;
                    var tax_amount = 0;
                    var item_total = 0;
                    
                    if(this.products[index].combo_cart_id != undefined && this.products[index].combo_cart_id != '' ){
                        
                        if(this.returning_combos.find( (value) => { return value == this.products[index].combo_cart_id } )){
                            var quantity = this.products[index].quantity;
                            var max_quantity = quantity;
                            var ordered_quantity = quantity;
                        }else{
                            var quantity = 0;
                            var max_quantity = quantity;
                            var ordered_quantity = quantity;
                        }
                        
                    }else{
                        
                        var quantity = this.products[index].quantity;
                        var max_quantity = this.products[index].max_quantity;
                        var ordered_quantity = this.products[index].ordered_quantity;
                    }

                    var unit_price = parseFloat(this.products[index].price) + parseFloat(modifier_price);
                    var discount_percentage = this.products[index].discount_percentage;
                    var discount_amount = this.products[index].discount_amount;
                    // console.log('discount_amount ==', discount_amount, 'discount_percentage =', discount_percentage);
                    var tax_percentage = this.products[index].tax_percentage;
                    var total_after_discount = 0;
                    if ( !isNaN(quantity) && quantity != null && quantity != "" && !isNaN(unit_price) && unit_price != null && unit_price != "") {
                        
                        item_total_max = parseFloat(ordered_quantity) * parseFloat(unit_price);
                        // console.log('this.products[product].ordered_quantity = ', this.products[index].ordered_quantity);
                        item_total = parseFloat(quantity) * parseFloat(unit_price);
                        item_total_excl_tax_before_discount = parseFloat(item_total_excl_tax_before_discount) + item_total;
                        if ( !isNaN(discount_percentage) && discount_percentage != null && discount_percentage != "" && discount_percentage > 0 ) {
                            discount_amount = this.calculate_discount( item_total,discount_percentage);
                            // console.log("discount_amount =", discount_amount);
                            //discount_amount = number_format(discount_amount, 4, ".", "");
                            //this.products[index].discount_amount = number_format(discount_amount, 4, ".", "");
                            item_total = parseFloat(item_total) - parseFloat(discount_amount);
                        }else if ( !isNaN(discount_amount) && discount_amount != null && discount_amount != "" && discount_amount > 0) {
                            discount_percentage = (parseFloat(discount_amount) / item_total_max)*100;
                            if(isNaN(discount_percentage)){
                                discount_percentage = 0;
                            }
                            discount_amount = this.calculate_discount( item_total,discount_percentage);
                            item_total = parseFloat(item_total) - parseFloat(discount_amount);
                            this.products[index].discount_amount_calc = number_format(discount_amount, 4, ".", "");
                        }
                        // console.log("item_total with dis =", item_total);
                        var item_total_curr = number_format(item_total, 4, ".", "");
                        this.products[index].total_price = item_total_curr;
                        this.products[index].sub_total_purchase_price_excluding_tax = item_total_curr;
                        this.products[index].total_after_discount = item_total_curr;

                        // if(this.products[index].is_gifted == 0){
                            item_total_excluding_tax = parseFloat(item_total_excluding_tax) + parseFloat(item_total);
                        // }
                        if(additional_discount_percentage > 0){
                            additional_discount_amount = this.calculate_discount( item_total, additional_discount_percentage );
                        }
                        total_after_discount = parseFloat(item_total) - parseFloat(additional_discount_amount);
                        item_total = total_after_discount;
                        // console.log('total_after_discount ==',total_after_discount);
                        // console.log('tobacco components =',this.products[index].tobacco_tax_components[0].tax_percentage);
                        var tobacco_tax_amount = 0;
                        if(this.products[index].tobacco_tax_components != null ){
                            if(this.products[index].tobacco_tax_components.length > 0){
                                tobacco_tax_amount = this.calculate_tax(total_after_discount, this.products[index].tobacco_tax_components[0].tax_percentage);
                                // console.log("tax_amount tobacco =", tobacco_tax_amount);
                                item_total = parseFloat(total_after_discount) + parseFloat(tobacco_tax_amount);
                                this.products[index].tobacco_tax_components[0].tax_amount = parseFloat(tobacco_tax_amount).toFixed(4);

                                var tobacco_tax_detail = this.products[index].tobacco_tax_components[0];
                                this.item_level_total_tobacco_tax_details[tobacco_tax_detail.tax_name_id]['item_tax_total'] += parseFloat(tobacco_tax_amount); 
                            }
                        }
                        // console.log("item_total with tobacco =", item_total);
                        if ( !isNaN(tax_percentage) && tax_percentage != null && tax_percentage != "" ) {
                            tax_amount = this.calculate_tax(item_total, tax_percentage);
                            // console.log("tax_amount =", tax_amount);
                            var tax_splitup_totals = 0;
                            $.each(this.products[index].tax_components, function(tax_key, tax_detail) {
                                let curr_total_tax = app_obj.calculate_tax(item_total, tax_detail.tax_percentage);
                                curr_total_tax = parseFloat(curr_total_tax);
                                tax_splitup_totals = parseFloat(tax_splitup_totals) + curr_total_tax;
                                app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_total'] += curr_total_tax;

                                app_obj.products[index].tax_components[tax_key].tax_amount = curr_total_tax.toFixed(4);
                            });                        
                        }
                        
                        this.products[index].sub_total = item_total;
                        final_total_tax = parseFloat(final_total_tax) + parseFloat(tax_splitup_totals) + parseFloat(tobacco_tax_amount);
                        item_total = parseFloat(item_total) + parseFloat(tax_splitup_totals);
                        // console.log("item_total with tax =", item_total);
                        this.products[index].total_amount = item_total.toFixed(4);
                        this.products[index].tax_amount = number_format(tax_splitup_totals, 4, ".", "");
                        // if(this.products[index].is_gifted == 0){
                            last_additional_discount_amount += parseFloat(additional_discount_amount);
                            total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
                            total_max_quantity = parseFloat(total_max_quantity) + parseFloat(max_quantity);
                            grand_total = parseFloat(grand_total) + parseFloat(item_total);
                        // }
                        // console.log("grand_total with tax =", grand_total);
                    } else {
                        continue;
                    }
               }
            }

            this.order_basic.additional_discount_amount = number_format( last_additional_discount_amount, 2, ".", "" );
            // console.log('last_additional_discount_amount =', last_additional_discount_amount);

            if ( !isNaN(this.order_basic.sale_amount_subtotal_excluding_tax) && this.order_basic.sale_amount_subtotal_excluding_tax != "") {
                this.order_basic.sale_amount_subtotal_excluding_tax = number_format(item_total_excluding_tax, 2, ".", "");
            }
            // console.log('sale_amount_subtotal_excluding_tax 2 =', sale_amount_subtotal_excluding_tax);
            if ( !isNaN(this.order_basic.additional_discount_amount) && this.order_basic.additional_discount_amount != "" ) {
                let total_exclude_tax_with_disc = parseFloat(item_total_excluding_tax) - parseFloat(last_additional_discount_amount);
                this.order_basic.total_after_discount = number_format( total_exclude_tax_with_disc, 2, ".", "" );
            }

            let order_level_tax_components = [];
            $.each(this.item_level_total_tobacco_tax_details, function(tax_key, tax_detail) {
                if (tax_detail != undefined) {
                    let tax_obj = {
                        tax_type: tax_detail.item_tax_label,
                        tax_percentage: tax_detail.item_tax_percentage,
                        tax_amount: parseFloat(tax_detail.item_tax_total).toFixed(4)
                    };
                    order_level_tax_components.push(tax_obj);
                }
            });
            $.each(this.item_level_total_tax_details, function(tax_key, tax_detail) {
                if (tax_detail != undefined) {
                let tax_obj = {
                    tax_type: tax_detail.item_tax_label,
                    tax_percentage: tax_detail.item_tax_percentage,
                    tax_amount: parseFloat(tax_detail.item_tax_total).toFixed(4)
                }
                order_level_tax_components.push(tax_obj)
                }
            })
            this.order_basic.order_level_tax_components = order_level_tax_components;
            // console.log('this.order_level_tax_components vvvvvv =', this.order_basic.order_level_tax_components);

            this.order_basic.total_tax_amount = number_format(final_total_tax, 2, ".", "");

            this.grand_total = grand_total.toFixed(2);
            this.order_basic.total_order_amount = this.grand_total;
        },

        calculate_tax(item_total, tax_percentage) {
            var tax_amount = (parseFloat(tax_percentage) / 100) * parseFloat(item_total);
            return tax_amount;
        },

        calculate_discount(item_total, discount_percentage) {
            var discount_amount = (parseFloat(discount_percentage) / 100) * parseFloat(item_total);
            return discount_amount;
        },
        select_reason() {
            this.show_reason_modal = true;
        },
        submit_form() {
            this.$off("submit");
            this.$off("close");

            var total_quantity = 0;
            for (var index in this.products) {
                var discount_amount = 0;
                var tax_amount = 0;
                var item_total = 0;

                var quantity = this.products[index].quantity;
                total_quantity = parseFloat(total_quantity) + parseFloat(quantity);
            }

            if (total_quantity == 0) {
                alert("Quantity cannot be zero.");
                return false;
            }
            this.$validator.validateAll("return_form").then((result) => {
                if (result) {
                    this.show_reason_modal = false;
                    this.show_modal = true;
                    this.$on("submit", function () {
                        this.processing = true;
                        var formData = new FormData();

                        if (this.return_reason == "0") {
                            var return_reason = this.other_return_reason;
                        } else {
                            var return_reason = this.return_reason;
                        }
                        let product_list = [];
                        for (let product in this.products) {
                            if (parseFloat(this.products[product].quantity) > 0) {
                                product_list.push(this.products[product]);
                            }
                        }
                        
                        formData.append("access_token", window.settings.access_token);
                        formData.append("order_slack", this.slack);
                        formData.append("payment_slack", this.selected_payment);
                        formData.append("products", JSON.stringify(product_list));
                        formData.append("order_basic", JSON.stringify(this.order_basic));
                        formData.append("return_reason", return_reason);
                        if (this.clicked_button == "return") {
                            formData.append("return_type", 'Return');
                        }else{
                            formData.append("return_type", 'Damage');
                        }

                        formData.append("returning_combos", JSON.stringify(this.returning_combos) );

                        axios
                            .post(this.return_order_api_link, formData)
                            .then((response) => {
                                if (response.data.status_code == 200) {
                                    this.show_response_message(response.data.msg, "SUCCESS");
                                    if ( typeof response.data.link != "undefined" && response.data.link != "" ) {
                                        this.show_modal = false;
                                        // console.log(response.data.order);
                                        this.processing = false;

                                        //if (response.data.new_tab == true) {
                                        window.open(response.data.link, "_blank");
                                        window.location.reload();
                                        /*} else {
                                            window.location.href = response.data.link;
                                        }*/
                                        this.view_edit_product = false;
                                        this.view_product_details = true;
                                        this.return_order_exist = true;
                                    }
                                } else {
                                    this.show_modal = false;
                                    this.processing = false;
                                    window.scrollTo(0, 0);
                                    try {
                                        var error_json = JSON.parse(response.data.msg);

                                        this.loop_api_errors(error_json);
                                    } catch (err) {
                                        this.server_errors = response.data.msg;
                                    }
                                    this.error_class = "error";
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                        
                    });

                    this.$on("close", function () {
                        this.show_modal = false;
                    });
                }
            });
        },

        submit_partial_payment() {
            this.processing = true;
            if (this.partial_pay_paid_amount <= 0) {
                this.processing = false;
                this.partial_pay_server_errors = 'Please enter amount greater than zero!';
                return false;
            }
            var formData = new FormData();
            formData.append('access_token', window.settings.access_token);
            formData.append('order_slack', this.order_basic.slack);
            formData.append('paid_amount', this.partial_pay_paid_amount);
            formData.append('notes', this.partial_pay_notes);
            axios
                .post('/api/order/partial_pay', formData)
                .then(response => {
                console.log('data= ', response.data.status_code);
                if (response.data.status_code == 200) {
                    this.show_payment_details_modal = false;
                    this.partial_pay_paid_amount = 0;
                    this.partial_pay_server_errors = '';
                    this.partial_pay_notes = '';
                    this.show_response_message(response.data.msg, 'SUCCESS');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                    
                } else {
                    try {
                        var error_json = JSON.parse(response.data.msg);
                        this.loop_api_errors(error_json);
                    } catch (err) {
                        this.partial_pay_server_errors = response.data.msg;
                        this.show_response_message(response.data.msg, 'ERROR');
                        return false;
                    }
                    this.error_class = 'error';
                }
                this.processing = false;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        showPartialPayment() {
            var total_paid_amount = 0;
            this.transactions.forEach((transaction, index) => {
                total_paid_amount += parseFloat(transaction.amount);
            }); 
            this.partial_pay_total_paid_amount = total_paid_amount;
            this.partial_pay_pending_amount = parseFloat(this.order_basic.total_order_amount) - total_paid_amount;
        },

        showPartialPaymentPopup() {
            this.show_payment_details_modal = true;
            this.$on("close", function () {
                this.show_payment_details_modal = false;
            });
            var total_paid_amount = 0;
            this.transactions.forEach((transaction, index) => {
                total_paid_amount += parseFloat(transaction.amount);
            }); 
            this.partial_pay_total_paid_amount = total_paid_amount;
            this.partial_pay_pending_amount = parseFloat(this.order_basic.total_order_amount) - total_paid_amount;
        },
        share_invoice_as_sms() {
            this.$off("submit");
            this.$off("close");
            this.show_share_invoice_sms_modal = true;

            this.$on("submit", function () {
                this.processing = true;
                this.send_sms_processing = true;

                var formData = new FormData();
                formData.append("access_token", window.settings.access_token);

                axios
                    .post(this.sms_invoice_api_link, formData)
                    .then((response) => {
                        if (response.data.status_code == 200) {
                            this.show_response_message(response.data.msg, "SUCCESS");
                            if (
                                typeof response.data.link != "undefined" &&
                                response.data.link != ""
                            ) {
                                if (response.data.new_tab == true) {
                                    window.open(response.data.link, "_blank");
                                } else {
                                    window.location.href = response.data.link;
                                }

                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            }
                        } else {
                            this.show_share_invoice_sms_modal = false;
                            this.processing = false;
                            // try {
                            //   var error_json = JSON.parse(response.data.msg);
                            //   this.loop_api_errors(error_json);
                            // } catch (err) {
                            //   this.server_errors = response.data.msg;
                            // }
                            // this.error_class = "error";
                            this.no_mobile_error();
                        }
                        this.send_sms_processing = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            });

            this.$on("close", function () {
                this.show_share_invoice_sms_modal = false;
            });
        },
        no_mobile_error() {
            this.$off("close");
            this.show_no_mobile_modal = true;
            this.modal_status = "error";
            this.$on("close", function () {
                this.show_no_mobile_modal = false;
            });
        },
        validate_quantity: _.debounce(function(event) {
            var entered_quantity = event.target.value;
            if (entered_quantity >= 0 || entered_quantity == '') {
                this.calculate_price();
            } 
        }, 1000),
        formatQuantityDecimal(cart_slack,event){
            let keyCode = (event.keyCode ? event.keyCode : event.which);
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.products[cart_slack].quantity.indexOf('.') != -1)) { 
                event.preventDefault();
            }
            if(this.products[cart_slack].quantity!=null && this.products[cart_slack].quantity.indexOf(".")>-1 && (this.products[cart_slack].quantity.split('.')[1].length > 1)){
                event.preventDefault();
            }
        },
        add_combo_to_return_cart(event,combo_cart_id){
            if(event.target.checked){
                this.returning_combos.push(combo_cart_id);
            }else{
                this.returning_combos.splice(this.returning_combos.indexOf(combo_cart_id),1);
            }

            this.calculate_price();

        },
        check_if_returning(combo_cart_id){
            return this.returning_combos.find( (value) => value == combo_cart_id );
        }
    },
};
</script>

<style scoped>

.fs-15{
    font-size: 15px;
}

</style>