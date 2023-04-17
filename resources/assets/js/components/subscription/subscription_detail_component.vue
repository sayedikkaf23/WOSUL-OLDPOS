<template>
    <div class="row">
        <div class="col-md-12">

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Subscription Detail") }}</span>
            </div>
            <div class="form-row mb-2">
               
                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Title") }}</label>
                    <p>{{ title }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Title (Arabic)") }}</label>
                    <p>{{ title_ar }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Short Description") }}</label>
                    <p>{{ short_description }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Short Description (Arabic) ") }}</label>
                    <p>{{ short_description_ar }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Plan Tenure ") }}</label>
                    <p>{{ (plan_tenure == 1) ? 'Monthly' : 'Yearly' }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Currency") }}</label>
                    <p>{{ currency }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Amount") }}</label>
                    <p>{{ amount }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Discount") }}</label>
                    <p>{{ discount }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Discount Description") }}</label>
                    <p>{{ discount_description }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Discount Description (Arabic) ") }}</label>
                    <p>{{ discount_description_ar }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("URL ") }}</label>
                    <p>{{ url }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("URL (Arabic) ") }}</label>
                    <p>{{ url_ar }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Color Code") }}</label>
                    <p>{{ color_code }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Is Live") }}</label>
                    <p>
                        <span v-if="is_live == 1">Yes</span>
                        <span v-if="is_live == 0">No</span>
                    </p>
                </div>

                <div class="form-group col-md-3">
                    <label for="label">{{ $t("status") }}</label>
                    <p>{{ status }}</p>
                </div>

            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Subscription Features") }}</span>
            </div>
            
            <ul v-show="subscription_feature_data.length" v-for="(subscription_feature,index) in subscription_feature_data" >
                <li>{{ subscription_feature.title }}</li>
            </ul>         

        </div>
    </div>
</template>  

<script>
    'use strict';
    
    export default {
        data(){
            return{
                
                // variables
                title : (this.subscription_data == null) ? '' : this.subscription_data.title,
                title_ar : (this.subscription_data == null) ? '' : this.subscription_data.title_ar,
                short_description : (this.subscription_data == null) ? '' : this.subscription_data.short_description,
                short_description_ar : (this.subscription_data == null) ? '' : this.subscription_data.short_description_ar,
                plan_tenure : (this.subscription_data == null) ? '' : this.subscription_data.plan_tenure,
                currency : (this.subscription_data == null) ? '' : this.subscription_data.currency,
                amount : (this.subscription_data == null) ? '' : this.subscription_data.amount,
                discount : (this.subscription_data == null) ? '' : this.subscription_data.discount,
                discount_description : (this.subscription_data == null) ? '' : this.subscription_data.discount_description,
                discount_description_ar : (this.subscription_data == null) ? '' : this.subscription_data.discount_description_ar,
                url : (this.subscription_data == null) ? '' : this.subscription_data.url,
                url_ar : (this.subscription_data == null) ? '' : this.subscription_data.url_ar,
                color_code : (this.subscription_data == null) ? '' : this.subscription_data.color_code,
                is_live : (this.subscription_data == null) ? '' : this.subscription_data.is_live,
                status : (this.subscription_data == null) ? '' : this.subscription_data.status.label,
                updated_at : (this.subscription_data == null) ? '' : this.subscription_data.updated_at,
                created_at : (this.subscription_data == null) ? '' : this.subscription_data.created_at
            }
        },
        props: {
            subscription_data: [Array, Object],
            subscription_feature_data: [Array, Object],
        },
        mounted() {
            console.log('Subscription detail page loaded');
            // console.log(this.subscription.title);
            // console.log(this.subscription_feature_data);
        },
        methods: {
           
        }
    }
</script>