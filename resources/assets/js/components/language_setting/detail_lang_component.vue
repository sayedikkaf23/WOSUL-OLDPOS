<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Name") }} - </span> {{ language_setting.lang_name }} </span>
                        </div>
                    </div>
                </div>
              
                <div class="">
                    <span v-bind:class="language_setting.status.color">{{ $t(language_setting.status.label) }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
                            
                 <div class="form-group col-md-3">
                    <label for="culture">{{ $t("Culture") }}</label>
                    <p>{{ language_setting.lang_culture }}</p>
                </div>

                <div class="form-group col-md-3">
                    <label for="culture">{{ $t("Code") }}</label>
                    <p>{{ language_setting.lang_code }}</p>
                </div>
              
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (language_setting.created_by == null)?'-':language_setting.created_by['fullname']+' ('+language_setting.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (language_setting.updated_by == null)?'-':language_setting.updated_by['fullname']+' ('+language_setting.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ language_setting.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ language_setting.updated_at_label }}</p>
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
                language_setting : this.language_setting_data
            }
        },
        props: {
            language_setting_data: [Array, Object]
        },
        mounted() {
            console.log('Language detail page loaded');
        },
        methods: {
           
        }
    }
</script>