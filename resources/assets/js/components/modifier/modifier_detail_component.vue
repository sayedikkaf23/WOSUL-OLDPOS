<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> <span class='text-muted'>{{ $t("Modifier") }}</span> {{ modifier.label }} </span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <span v-bind:class="modifier.status.color">{{ modifier.status.label }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
              
                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Label") }}</label>
                    <p>{{ modifier.label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (modifier.created_by == null)?'-':modifier.created_by['fullname']+' ('+modifier.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (modifier.updated_by == null)?'-':modifier.updated_by['fullname']+' ('+modifier.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ modifier.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ modifier.updated_at_label }}</p>
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
                modifier : this.modifier_data,
            }
        },
        props: {
            modifier_data: [Array, Object]
        },
        mounted() {
            console.log('Modifier detail page loaded');
        },
        methods: {
           
        }
    }
</script>