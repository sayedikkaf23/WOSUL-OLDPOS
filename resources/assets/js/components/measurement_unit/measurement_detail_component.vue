<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="">
                    <span v-bind:class="measurement.status.color">{{ $t(measurement.status.label) }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Basic Information") }}</span>
            </div>
            <div class="form-row mb-2">
               
                <div class="form-group col-md-3">
                    <label for="label">{{ $t("Label") }}</label>
                    <p>{{ measurement.label }}</p>
                </div>
           <!--      <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Category") }}</label>
                    <p>{{ measurement.measurement_category_name }}</p>
                </div> -->
                <div class="form-group col-md-3">
                    <label for="created_by">{{ $t("Created By") }}</label>
                    <p>{{ (measurement.created_by == null)?'-':measurement.created_by['fullname']+' ('+measurement.created_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_by">{{ $t("Updated By") }}</label>
                    <p>{{ (measurement.updated_by == null)?'-':measurement.updated_by['fullname']+' ('+measurement.updated_by['user_code']+')' }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="created_on">{{ $t("Created On") }}</label>
                    <p>{{ measurement.created_at_label }}</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="updated_on">{{ $t("Updated On") }}</label>
                    <p>{{ measurement.updated_at_label }}</p>
                </div>
            </div>

            <div class="mb-2">
                <span class="text-subhead">{{ $t("Conversions") }}</span>
            </div>
            
            <div class="row mb-2 col-md-3">
                <table class="table table-striped table-condensed ">
                    <tr v-if="measurement_conversions.length" v-for="(conversion,index) in measurement_conversions">
                        <td width="45%" class="text-center">{{ conversion.from_measurement_value }} {{ conversion.from_measurement_label }}</td>
                        <td width="10%" class="text-center">=</td>
                        <td width="45%" class="text-center">{{ conversion.to_measurement_value }} {{ conversion.to_measurement_label }}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</template>  

<script>
    'use strict';
    
    export default {
        data(){
            return{
                measurement : this.measurement_data,
                measurement_conversions : this.measurement_conversion_data,
            }
        },
        props: {
            measurement_data: [Array, Object],
            measurement_conversion_data: [Array, Object],
        },
        mounted() {
            console.log('Measurement detail page loaded');
            console.log(this.measurement_conversions);
        },
        methods: {
           
        }
    }
</script>