<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Store") }}</span> {{ store.name }} ({{ store.store_code }}) </span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <span v-bind:class="store.status.color">{{ $t(store.status.label) }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="store_logo">{{ $t("Store Logo") }}</label><br>
                    <img :src="'../storage/store/'+store.store_logo" alt="" style="width:100px;">
                </div>
                <div class="form-group col-md-3">
                    <label for="store_code">{{ $t("Store Code") }}</label>
                    <p>{{ store.store_code }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="name">{{ $t("Name") }}</label>
                    <p>{{ store.name }}</p>
                </div>
                <!-- <div class="form-group col-md-3">
                    <label for="tax_number">{{ $t("Tax Number or GST number") }}</label>
                    <p>{{ store.tax_number }}</p>
                </div> -->
                <div class="form-group col-md-3">
                    <label for="vat_number">{{ $t("Vat Number") }}</label>
                    <p>{{ store.vat_number }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (store.created_by == null)?'-':store.created_by['fullname']+' ('+store.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (store.updated_by == null)?'-':store.updated_by['fullname']+' ('+store.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ store.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ store.updated_at_label }}</p>
                </div>
            </div>
            <hr>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Contact Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="primary_contact">{{ $t("Primary Contact No.") }}</label>
                    <p>{{ (store.primary_contact != null)?store.primary_contact:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="secondary_contact">{{ $t("Secondary Contact No.") }}</label>
                    <p>{{  (store.secondary_contact != null)?store.secondary_contact:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="primary_email">{{ $t("Primary Email") }}</label>
                    <p>{{  (store.secondary_contact != null)?store.primary_email:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="secondary_email">{{ $t("Secondary Email") }}</label>
                    <p>{{  (store.secondary_contact != null)?store.secondary_email:'-' }}</p>
                </div>
                  <div class="form-group col-md-3">
                    <label for="building_number">{{ $t("Building Number") }}</label>
                    <p>{{ (store.building_number != null)?store.building_number:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="street_name">{{ $t("Street Name") }}</label>
                     <p>{{ (store.street_name != null)?store.street_name:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="district">{{ $t("District") }}</label>
                    <p>{{ (store.district != null)?store.district:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="city">{{ $t("City") }}</label>
                    <p>{{ (store.city != null)?store.city:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="other_seller_id">{{ $t("Other Seller Id") }}</label>
                   <p>{{ (store.other_seller_id != null)?store.other_seller_id:'-' }}</p> 
                </div>
                 <div class="form-group col-md-3">
                    <label for="pincode">{{ $t("Pincode") }}</label>
                    <p>{{ (store.pincode != null)?store.pincode:'-' }}</p>
                </div>
              
                <div class="form-group col-md-3">
                    <label for="address">{{ $t("Address") }}</label>
                    <p>{{ store.address }}</p>
                </div>
               
            </div>
            <hr>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Restaurant Mode Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="restaurant_mode">{{ $t("Enable Restaurant Mode") }}</label>
                    <p>{{ restaurant_mode }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="restaurant_billing_type">{{ $t("Default Billing Type") }}</label>
                    <p>{{ (store.restaurant_billing_type != null)?store.restaurant_billing_type.label:'-' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="restaurant_waiter_role">{{ $t("Role for Waiter") }}</label>
                    <p>{{ (store.restaurant_waiter_role != null)?store.restaurant_waiter_role.role_code+' - '+store.restaurant_waiter_role.label:'-' }}</p>
                </div>
            </div>
            <hr>

            <div class="mb-3">
                
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Tax Information") }}</span>
                </div>
                <div class="form-row mb-2" v-if="store.tax_code != null">
                    <div class="form-group col-md-3">
                        <label for="tax_code">{{ $t("Tax Code") }}</label>
                        <p>{{ store.tax_code.tax_code }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="total_tax_percentage">{{ $t("Tax Percentage") }}</label>
                        <p>{{ store.tax_code.total_tax_percentage }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tax_code_label">{{ $t("Tax Name") }}</label>
                        <p>{{ store.tax_code.label }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="table-responsive" v-if="store.tax_code != null">
                            <table class="table display nowrap text-nowrap w-100">
                                <thead>
                                    <tr>
                                    <th scope="col">{{ $t("Tax Type") }}</th>
                                    <th scope="col">{{ $t("Tax Percentage") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tax_component, key, index) in store.tax_code.tax_components" v-bind:key="index">
                                        <td>{{ tax_component.tax_type }}</td>
                                        <td>{{ tax_component.tax_percentage }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <span class="mb-2" v-else>No Tax Components</span>
                    </div>
                </div>
                
            </div>
            <hr>

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Discount Information") }}</span>
                </div>
                <div class="form-row mb-2" v-if="store.discount_code != null">
                    <div class="form-group col-md-3">
                        <label for="discount_code">{{ $t("Discount Code") }}</label>
                        <p>{{ store.discount_code.discount_code }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="discount_percentage">{{ $t("Discount Percentage") }}</label>
                        <p>{{ store.discount_code.discount_percentage }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="discount_code_label">{{ $t("Discount Name") }}</label>
                        <p>{{ store.discount_code.label }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="discount_code_description">{{ $t("Discount Description") }}</label>
                        <p>{{ store.discount_code.description }}</p>
                    </div>
                </div>
                <div class="mb-3" v-else>No Discount Information</div>
            </div>
            <hr>

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("POS Screen Setting") }}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="invoice_type">{{ $t("Enable Customer Detail Popup") }}</label>
                        <p>{{ enable_customer_popup }}</p>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Invoice Print Type Details") }}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label for="invoice_type">{{ $t("Invoice Print Type") }}</label>
                        <p>{{ store.invoice_type.print_type_label }}</p>
                    </div>
                </div>
            </div>
            <hr>

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Bank Details") }}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3" >
                        <label for="bank_name">{{ $t("Bank Name") }}</label>
                        <p>{{ store.bank_name }}</p>
                    </div>
                    <div class="form-group col-md-3" >
                        <label for="account_holder_name">{{ $t("Account Holder Name") }}</label>
                        <p>{{ store.account_holder_name }}</p>
                    </div>
                    <div class="form-group col-md-3" >
                        <label for="iban_number">{{ $t("IBAN Number") }}</label>
                        <p>{{ store.iban_number }}</p>
                    </div>
                </div>
            </div>
            <hr>

            <div class="mb-3">
                <div class="mb-2">
                    <span class="text-subhead">{{ $t("Policy Information") }}</span>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3" >
                        <label for="pos_invoice_policy_information">{{ $t("POS Policy Information") }}</label>
                        <p>{{ store.pos_invoice_policy_information }}</p>
                    </div>
                    <div class="form-group col-md-3" >
                        <label for="invoice_policy_information">{{ $t("Invoice Policy Information") }}</label>
                        <p>{{ store.invoice_policy_information }}</p>
                    </div>
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
                store : this.store_data,
                restaurant_mode : (this.store_data.restaurant_mode == 1)?'Yes':'No',
                enable_customer_popup: (this.store_data.enable_customer_popup == 1)?'Yes':'No',
            }
        },
        props: {
            store_data: [Array, Object]
        },
        mounted() {
            console.log('Store detail page loaded');
            console.log(this.store_data);
        },
        methods: {
           
        }
    }
</script>
