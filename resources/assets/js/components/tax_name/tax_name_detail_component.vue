<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Tax Name") }}</span> {{ tax_name.label }} ({{ tax_name.tax_name }}) </span>
                        </div>
                    </div>
                </div>
                <!-- <div class="">
                    <span v-bind:class="tax_name.status.color">{{ $t(tax_name.status.label) }}</span>
                </div> -->
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="tax_name">{{ $t("Tax Name") }}</label>
                    <p>{{ tax_name.tax_name  }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="tax_percentage">{{ $t("Tax Percentage") }}</label>
                    <p>{{ tax_name.percentage }} %</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="tax_percentage">{{ $t("Visibility") }}</label>
                    <p v-if="tax_name.is_visible == 1"  >{{ $t("Visible")  }} </p>
                    <p v-else class="text-danger" >{{ $t("Not Visible")  }} </p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (tax_name.created_by == null)?'-':tax_name.created_by['fullname']+' ('+tax_name.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (tax_name.updated_by == null)?'-':tax_name.updated_by['fullname']+' ('+tax_name.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ tax_name.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ tax_name.updated_at_label }}</p>
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
                tax_name : this.tax_name_data,
            }
        },
        props: {
            tax_name_data: [Array, Object]
        },
        mounted() {
            console.log('Tax Name detail page loaded');
        },
        methods: {
           
        }
    }
</script>