<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Brand") }}</span> {{ brand.label }} </span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <span v-bind:class="brand.status.color">{{ brand.status.label }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Label") }}</label>
                    <p>{{ brand.label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (brand.created_by == null)?'-':brand.created_by['fullname']+' ('+brand.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (brand.updated_by == null)?'-':brand.updated_by['fullname']+' ('+brand.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ brand.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ brand.updated_at_label }}</p>
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
                brand : this.brand_data,
            }
        },
        props: {
            brand_data: [Array, Object]
        },
        mounted() {
            console.log('Brand detail page loaded');
        },
        methods: {
           
        }
    }
</script>
