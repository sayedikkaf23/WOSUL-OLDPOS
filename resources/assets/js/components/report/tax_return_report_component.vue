<template>
    <div class="row">
        <div class="col-md-12">
                
            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                <div class="d-flex">
                        <div>
                            <span class="text-title">{{ $t("Tax Return Report") }}</span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary " v-bind:disabled="processing == true" @click="submit_search_form('pdf')"> 
                        <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Download PDF") }}</button>
                    <button type="submit" class="btn btn-primary " v-bind:disabled="processing == true" @click="download_excel_report('report_details_table')"> 
                        <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Download Excel") }}</button>
                </div>
            </div>

            <div  class="mb-2">

                <p v-html="form_validation_errors" v-bind:class="[error_class]"></p>

                <div class="form-row mb-1">
                    <div class="form-group col-md-3">
                        <label for="from_created_date">{{ $t("Date Period Type") }}</label>
                        <select name="date_period_type" v-model="date_period_type" class="form-control form-control-custom custom-select">
                            <option v-for="(type, index) in date_period_type_arr" :value="index+1" v-bind:key="index">{{ type }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="from_created_date">{{ $t("Select Any Month/Quarter") }}</label>
                        
                        <select v-if="date_period_type == 1 " name="period_monthly" class="form-control form-control-custom custom-select"
                            v-on:change="getMonthName" v-model="period_monthly" >
                            <option value="0" disabled selected>{{ $t('Select Any Month') }}</option>
                            <option v-for="(month, index) in months" :value="index+1" v-bind:key="index">{{ month }}</option>
                        </select>

                        <select v-else-if="date_period_type == 2 " name="period_quarterly" class="form-control form-control-custom custom-select" 
                            v-on:change="getPeriodName" v-model="period_quarterly" >
                            <option value="0" disabled selected>{{ $t('Select Any Quarterly') }}</option>
                            <option v-for="(quarter, index) in period_quarterly_arr" :value="quarter.id" v-bind:key="index">{{ quarter.name }}</option>
                        </select>
                        <select v-else name="" class="form-control form-control-custom custom-select">
                            <option value="0" disabled selected>{{ $t('Select Any Month/Quarter') }}</option>
                        </select>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="status">{{ $t("Select Year") }}</label>
                        <!-- <select name="status" v-model="user_wise_sales_report_form.status" class="form-control form-control-custom custom-select">
                            <option value="">{{ $t('Choose Status..') }}</option>
                            <option v-for="(status, index) in user_statuses" v-bind:value="status.value" v-bind:key="index">
                                {{ $t(status.label) }}
                            </option>
                        </select>  -->
                        <select name="year" v-model="year" class="form-control form-control-custom custom-select">
                            <option value="0" disabled selected>{{ $t('Select Year') }}</option>
                            <option v-for="(year, index) in years" :value="year" v-bind:key="index">{{ year }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <button @click="submit_search_form('search')" type="button" class="btn btn-primary" v-bind:disabled="processing == true">
                            <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                            {{ $t("Search") }}
                        </button>
                    </div>
                </div>
            </div>

            <hr class='mb-4'>

            <div class="table-responsive">
                <table class="table table-striped nowrap text-nowrap w-100" v-if="tax_details != ''"  border='2px' id="report_details_table">
                    <thead>
                        <tr>
                            <th scope="col" colspan="4" style="text-align: center;">
                                {{$t("Tax Retrun Report")}} 
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="4" v-if="this.filtered_date_period_type == 1" style="text-align: center;">
                                {{filtered_month_name}} - {{filtered_year}}
                            </th>
                            <th scope="col" colspan="4" v-else-if="this.filtered_date_period_type == 2" style="text-align: center;">
                                {{filtered_period_name}} - {{filtered_year}}
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="4" style="text-align: center;">
                                {{ $t("From: ") }} {{from_date}} {{ $t("To: ") }} {{to_date}}
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="4" style="text-align: center;">
                                {{ $t("Tax Number: ") }} {{store_details.vat_number}}
                            </th>
                        </tr>

                        <tr>
                            <th scope="col"></th>
                            <th class="text-right" scope="col">{{ $t("Bill Amount") }}</th>
                            <th class="text-right" scope="col">{{ $t("Return Bill Amount") }}</th>
                            <th class="text-right" scope="col">{{ $t("Tax Amount") }}</th>
                        </tr>
                        <tr>
                            <th style="font-size: 14px;" scope="col">{{ $t("Tax") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("Sales (Orders & Invoices)") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("Sales Return (Orders & Invoices)") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("VAT Due (Sale Tax - Sale Return Tax)") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(detail, key, index) in tax_details"  v-bind:key="index">
                            <td class="text-left">{{ detail.label }}</td>
                            <td class="text-right">{{ sale_details[detail.id]['sale_orders'][0]['total_sale_amount'].toFixed(2) }}</td>
                            <td class="text-right">{{sale_details[detail.id]['sale_returns'][0]['total_sale_ret_amount'].toFixed(2) }}</td>
                            <td class="text-right">{{ sale_details[detail.id]['tax_due'].toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left;font-size: 14px;">{{ $t("Total") }}</th>
                            <th style="text-align: right; font-size: 14px;">{{$t("SAR")}} {{ sale_details['sale_order_total'].toFixed(2)}}</th>
                            <th style="text-align: right; font-size: 14px;">{{$t("SAR")}} {{ sale_details['sale_return_total'].toFixed(2)}}</th>
                            <th style="text-align: right; font-size: 14px;">{{$t("SAR")}} {{ sale_details['sale_order_total_tax_due'].toFixed(2)}}</th>
                        </tr>
                        <tr><th scope="col" colspan="4"></th></tr>
                        <tr><th scope="col" colspan="4"></th></tr>
                        <tr>
                            <th style="font-size: 14px;" scope="col">{{ $t("Tax") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("Purchase") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("Purchase Return") }}</th>
                            <th style="text-align: right;font-size: 14px;" scope="col">{{ $t("VAT Paid (Purchase Tax - Purchase Return Tax)") }}</th>
                        </tr>
                        <tr v-for="(detail, key, index) in tax_details"  v-bind:key="index">
                            <td class="text-left">{{ detail.label }}</td>
                            <td class="text-right">{{ purchase_details[detail.id]['purchase_orders'][0]['total_purchase_amount'] }}</td>
                            <td class="text-right">{{ purchase_details[detail.id]['purchase_returns'][0]['total_purc_ret_amount'] }}</td>
                            <td class="text-right">{{ purchase_details[detail.id]['tax_paid'] }}</td>
                        </tr>
                        <tr>
                            <th style="font-size: 14px;">{{ $t("Total") }}</th>
                            <th style="text-align: right;font-size: 14px;">{{$t("SAR")}} {{purchase_details['purchase_order_total'].toFixed(2)}}</th>
                            <th style="text-align: right;font-size: 14px;">{{$t("SAR")}} {{purchase_details['purchase_return_total'].toFixed(2)}}</th>
                            <th style="text-align: right;font-size: 14px;">{{$t("SAR")}} {{purchase_details['purchase_order_total_tax_paid'].toFixed(2)}}</th>
                        </tr>
                        <tr><th scope="col" colspan="4"></th></tr>
                        <tr>
                            <th colspan="3" style="text-align: center;font-size: 14px;">
                                {{$t("Total VAT Due of current Period")}}
                            </th>
                            <th style="font-size: 14px;">
                                {{$t("SAR")}} {{total_vat_due}}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: center;font-size: 14px;">
                                {{$t("VAT Return carried from previous period")}}
                            </th>
                            <th>
                                <input type="text"  name="prev_vat_credit" v-model="prev_vat_credit">
                                <label class="d-none" for="prev_vat_credit" >{{prev_vat_credit}}</label>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: center;font-size: 14px;">
                                {{$t("Corrections for previous period(-5000 to 5000)")}}
                            </th>
                            <th>
                                <input type="text"  name="prev_vat_correction" v-model="prev_vat_correction">
                                <label class="d-none" for="prev_vat_correction" >{{prev_vat_correction}}</label>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: center;font-size: 14px;">
                                {{$t("Net VAT Due")}}
                            </th>
                            <th style="font-size: 16px;">
                                {{$t("SAR")}} {{net_vat_due}}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>  

<script>
    'use strict';
    
    import DatePicker from 'vue2-datepicker';
    import moment from "moment";
import { event_bus } from '../../event_bus';

    export default {
        data(){
            return{
                store_details:[],
                tax_details:[],
                purchase_details:[],
                sale_details:[],
                total_vat_due:0,
                prev_vat_credit:0,
                prev_vat_correction:0,
                net_vat_due:0,
                date_period_type_arr:['Monthly', 'Quarterly'],
                period_quarterly_arr:[{'id':'01','name':'Quarter 1'}, {'id':'04','name':'Quarter 2'}, {'id':'07','name':'Quarter 3'},  {'id':'10','name':'Quarter 4'}],
                date_period_type:0,
                year:'',
                years: '',
                period_monthly:0,
                period_quarterly:0,
                months: moment.months(),
                server_errors: "",
                error_class: "",
                processing: false,
                modal: false,
                show_modal: false,
                error_class: '',
                form_validation_errors:'',
                from_date:'',
                to_date:'',
                selected_month_name:'',
                selected_period_name:'',
                filtered_month_name:'',
                filtered_period_name:'',
                filtered_year:'',
                filtered_date_period_type:'',
            }
        },
        components: {
            DatePicker
        },
        props: {
            //user_statuses: Array,
            // tax_details: Array,
            // purchase_details: [Array, Object],
            selected_store : [Array, Object],
            min_created_year : String,
        },
        mounted() {
            console.log('Report page loaded');
            
            let years_list = [];
            for (var i = this.min_created_year; i <= new Date().getFullYear(); i++) {
                years_list.push(i);
            }
            this.years = years_list;
        },
        watch:{
            date_period_type: function(val) {
                if (val) {
                    this.date_period_type = val;
                }
            },
            prev_vat_credit: function(val) {
                if (val) {
                    this.net_vat_due = (parseFloat(this.total_vat_due) + parseFloat(val)).toFixed(2);
                }
            },
        },
        methods: {
            // formatDate (d) {
            //     // you could also provide your own month names array
            //     const months = this.$refs.datePicker.translation.months
            //     return `${d.getDate().toString().padStart(2, 0)} ${months[d.getMonth()]} ${d.getFullYear()}`
            // },
            // convert_date_format(date){
            //     return (date != '' && date != null)?moment(date).format("YYYY-MM-DD HH:mm:ss"):'';
            // },
            getMonthName(e){
                if(e.target.options.selectedIndex > -1) {
                    this.selected_month_name = e.target.options[e.target.options.selectedIndex].text;
                }
            },
            getPeriodName(e){
                if(e.target.options.selectedIndex > -1) {
                    this.selected_period_name = e.target.options[e.target.options.selectedIndex].text;
                }
            },
            submit_search_form(type) {

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.processing = true;
                        var formData = new FormData();
                        formData.append("access_token", window.settings.access_token);
                        formData.append("date_period_type", this.date_period_type);
                        formData.append("period_monthly", this.period_monthly);
                        formData.append("period_quarterly", this.period_quarterly);
                        formData.append("year", this.year);
                        formData.append("selected_month_name", this.selected_month_name);
                        formData.append("selected_period_name", this.selected_period_name);
                        formData.append("year", this.year);
                        formData.append("report_type", type);
                        formData.append("prev_vat_credit", this.prev_vat_credit);
                        formData.append("prev_vat_correction", this.prev_vat_correction);
                        formData.append("net_vat_due", this.net_vat_due);
                        this.error_class = ''; this.form_validation_errors = '';
                        axios.post('/api/tax_return_report', formData)
                            .then((response) => {
                                if (response.data.status_code == 200) {
                                    if(type == 'search'){
                                        this.tax_details = response.data.data.tax_details;
                                        this.purchase_details = response.data.data.purchase_details;
                                        this.sale_details = response.data.data.sale_details;
                                        this.total_vat_due = response.data.data.total_vat_due;
                                        this.store_details = response.data.data.store_details;
                                        this.from_date = response.data.data.from_date;
                                        this.to_date = response.data.data.to_date;
                                        this.filtered_month_name = response.data.data.selected_month_name;
                                        this.filtered_period_name = response.data.data.selected_period_name;
                                        this.filtered_year = response.data.data.selected_year;
                                        this.filtered_date_period_type = response.data.data.selected_date_period_type;
                                        this.processing = false;
                                        this.net_vat_due = parseFloat(this.total_vat_due).toFixed(2);
                                    }else if(type == 'pdf'){
                                        if(typeof response.data.link != 'undefined' && response.data.link != ""){
                                            const link = document.createElement('a');
                                            link.target = "_blank";
                                            link.href = response.data.link;
                                            document.body.appendChild(link);
                                            link.click();
                                        }
                                        //else{
                                        //     location.reload();
                                        // }
                                        this.processing = false;
                                    }                                    
                                } else {
                                    this.processing = false;
                                    try{
                                        var error_json = JSON.parse(response.data.msg);
                                        this.form_validation_errors = this.loop_api_errors(error_json);
                                    }catch(err){
                                        this.form_validation_errors = response.data.msg;
                                    }
                                    this.error_class = "error";
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                       
                    }
                });
            },
            download_excel_report(tableID){
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = dd + '_' + mm + '_' + yyyy;
                var filename = 'tax_return_report_'+today;
                // var dataType = 'application/vnd.ms-excel';
                var dataType = "text/xlsx;charset=utf-8";
                var tableSelect = document.getElementById(tableID);
                var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
                // Specify file name
                filename = filename?filename+'.xlsx':'excel_data.xlsx';
                // Create download link element
                var downloadLink = document.createElement("a");
                document.body.appendChild(downloadLink);
                if(navigator.msSaveOrOpenBlob){
                    var blob = new Blob(['\ufeff', tableHTML], {
                        type: dataType
                    });
                    navigator.msSaveOrOpenBlob( blob, filename);
                }else{
                    // Create a link to the file
                    downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                    // Setting the file name
                    downloadLink.download = filename;
                    //triggering the function
                    downloadLink.click();
                }
            },

        }
    }
</script>