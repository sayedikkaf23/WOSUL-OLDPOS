<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Merchant") }}</span> {{ merchant.name }} </span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <span v-bind:class="merchant.status.color">{{ merchant.status.label }}</span>
                </div>
            </div>

            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="email">{{ $t("Subscription") }}</label>
                    <p>{{ merchant.subscription_id }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="company_name">{{ $t("Company Name") }}</label>
                    <p>{{ merchant.company_name }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="company_url">{{ $t("Company URL") }}</label>
                    <p>{{ merchant.company_url }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">{{ $t("Email") }}/{{ $t("Username") }}</label>
                    <p>{{ merchant.email }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="phone_number">{{ $t("Phone") }}</label>
                    <p>{{ merchant.phone_number }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="address">{{ $t("Address") }}</label>
                    <p>{{ merchant.address }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="promo_code">{{ $t("Promo Code") }}</label>
                    <p>{{ merchant.promo_code }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="recommendation">{{ $t("Recommendation") }}</label>
                    <p>{{ merchant.recommendation }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ merchant.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ merchant.updated_at_label }}</p>
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
                merchant : this.merchant_data,
            }
        },
        props: {
            merchant_data: [Array, Object]
        },
        mounted() {
            console.log('merchant detail page loaded');
        },
        methods: {
           
        }
    }
</script>