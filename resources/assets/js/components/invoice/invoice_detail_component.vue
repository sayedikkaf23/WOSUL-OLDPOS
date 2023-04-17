<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> {{ $t("Invoice") }} #{{ invoice_basic.invoice_number }} </span>
                        </div>
                    </div>
                </div> 
                <div class="">
                    <span v-bind:class="invoice_basic.status.color">{{ $t(invoice_basic.status.label) }}</span>
                </div>
            </div>

            <div class="d-flex flex-wrap mb-4">

                <p v-html="server_errors" v-bind:class="[error_class]"></p>

                <div class="ml-auto">

                    <a type="submit" class="btn btn-warning mr-1" v-on:click="return_invoice()" 
                        v-if="invoice_basic.status.value == 3 || invoice_basic.status.value == 7" v-bind:disabled="order_processing == true" >
                        <i class="fa fa-circle-notch fa-spin" v-if="order_processing == true" ></i>
                        {{ $t("Return Invoice") }}
                    </a>

                    <!-- <a class="btn btn-outline-danger mr-1 text-danger" @click="print" target="_blank" >{{ $t("Print") }}</a> -->

                    <!-- <a class="btn btn-outline-success mr-1" :href="pdf_path" target="_blank" download >{{ $t("Download") }}</a> -->

              <!--       <button type="submit" class="btn btn-danger mr-1" v-show="!block_delete_invoice.includes(invoice_basic.status.constant)" v-if="delete_invoice_access == true" v-on:click="delete_invoice()" v-bind:disabled="invoice_delete_processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="invoice_delete_processing == true"></i> {{ $t("Delete Invoice") }}</button>   -->

                    <button type="submit" class="btn btn-outline-primary mr-1" v-on:click="show_payment_modal = true" v-show="!block_make_payment.includes(invoice_basic.status.constant)" v-if="make_payment_access == true"> {{ $t("Make Payment") }}</button>

                    <!-- <div class="dropdown d-inline" v-if="invoice_statuses != '' && !block_status_invoice.includes(invoice_basic.status.constant)">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="invoice_action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $t("Change Status") }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice_action">
                            <button class="dropdown-item" type="button" v-for="(invoice_status, key, index) in invoice_statuses" v-bind:value="invoice_status.value_constant" v-bind:key="index" v-on:click="change_invoice_status(invoice_status.value_constant)">{{ $t("Mark as") }} {{ invoice_status.label }}</button>
                        </div>
                    </div> -->

                       <div class="dropdown d-inline" v-if="invoice_statuses != '' && !show_payment_history_button.includes(invoice_basic.status.constant)">
                        <button class="btn btn-primary" type="button"   v-on:click="show_payment_history()" >
                            {{ $t("Payment History") }}
                        </button>
                    
                    </div> 
                </div>

            </div>
            <hr>
<!-- 

            <object :data="pdf_path" type="application/pdf" width="1450" height="800">
                Alt : <a :href="pdf_path">Unable to PDF</a>
            </object>
 -->
        </div>

        <modalcomponent v-if="show_payment_modal" v-on:close="show_payment_modal = false" :modal_width="'modal-container-md'">
            <template v-slot:modal-header>
                {{ $t("Make Payment") }}
            </template>
            <template v-slot:modal-body>
                <addtransactionwidgetcomponent :contact_number="contact_number" :transaction_type_data="transaction_type_data" @show_express_payment_link_btn="show_express_payment_link_btn" :express_payment_slack="express_payment_slack" :accounts="accounts" :payment_methods="payment_methods" :invoice_slack="slack" :bill_to_prop="'INVOICE'" :currency_codes="currency_codes" :mada_visa_master_img="mada_visa_master_img"></addtransactionwidgetcomponent>
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="cancel_transaction">{{ $t("Cancel") }}</button>

                <button type="button" class="btn btn-primary" @click="submit_transaction" v-show="show_payment_submit" v-bind:disabled="make_payment_processing == true"> <i class='fa fa-circle-notch fa-spin' v-if="make_payment_processing == true"></i> 
                    <span v-if="show_express_payment_link_btn_txt">{{ $t("Generate Payment Link & Send in SMS") }}</span>
                    <span v-else>{{ $t("Continue") }}</span>
                </button>
            </template>
        </modalcomponent>

             
              <modalcomponent v-if="show_payment_history_modal" v-on:close="show_payment_history_modal = false">
            <template v-slot:modal-header>
                     {{ $t("Payment History") }}
            </template>
            <template v-slot:modal-body>
                  <div class="row">
       <div class="col-md-12">
           <div class="form-row mb-2">
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Total Amount") }} </label
              >
              <div class="text-subtitle">{{ payment_total_amount }}</div>
            </div>
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Paid Amount") }} </label
              >
              <div class="text-subtitle">{{ payment_paid_amount }}</div>
            </div>
            <div class="form-group col-4 text-right">
              <label for="transaction_date"
                >{{ $t("Pending Amount") }} </label
              >
              <div class="text-subtitle">{{ payment_pending_amount }}</div>
            </div>
          </div>

                   <div class="form-row mb-2" v-if="partial_payment_details != ''">
             
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
                            <tr
                            v-for="partial_payment_detail in partial_payment_details"
                            :key="partial_payment_detail.id"
                            >
                            <td>{{ format_date_without_time(partial_payment_detail.transaction_date) }}</td>
                            <td>{{ partial_payment_detail.amount }}</td>
                            <td>{{ partial_payment_detail.payment_method }}</td>
                            <td>{{ partial_payment_detail.notes }}</td>
                            </tr>
                        </tbody>
                    </table>
                 </div>

        </div>
        </div>
            </template>
            <template v-slot:modal-footer>
      
           
            </template>
        </modalcomponent>


        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t("Confirm") }}
            </template>
            <template v-slot:modal-body>
                {{ $t("Are you sure you want to proceed?") }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t("Cancel") }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t("Continue") }}</button>
            </template>
        </modalcomponent>

    </div>
</template>  

<script src="print.js"></script>

<script>
    'use strict';
    import {event_bus} from '../../event_bus.js';
    import pdf from 'vue-pdf'
    import print from 'print-js'
    import moment from "moment";
    import DatePicker from "vue2-datepicker";
    import "vue2-datepicker/index.css";

    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                show_modal      : false,
                show_payment_modal : false,
                make_payment_processing: false,
                invoice_delete_processing: false,
                show_payment_submit: true,
                
                
                change_invoice_link  : '/api/update_invoice_status/'+this.invoice_data.slack,
                delete_invoice_api_link : '/api/delete_invoice/'+this.invoice_data.slack,
                invoice_return_api_link : '/invoice_return_details/'+this.invoice_data.slack,

                slack           : this.invoice_data.slack,
                invoice_basic   : this.invoice_data,
                products        : this.invoice_data.products,
                transactions    : this.invoice_data.transactions,

                tax_component_count  : (this.invoice_data.tax_option_data != null)?this.invoice_data.tax_option_data.component_count:0,
                tax_component_array  : (this.invoice_data.tax_option_data != null)?this.invoice_data.tax_option_data.component_array:[],
                table_colspan   : parseInt(8)+((this.invoice_data.tax_option_data != null)?parseInt(this.invoice_data.tax_option_data.component_count):1),
                contact_number  : (this.invoice_data != null)? this.invoice_data.customer.phone : '',

                block_make_payment : ['CANCELLED', 'PAID', 'VOID', 'WRITEOFF'],
                block_delete_invoice : ['PAID'],
                block_status_invoice : ['PAID'],
                show_payment_history_button : ['CANCELLED', 'VOID', 'WRITEOFF','NEW','RETURN','PARTIAL_PAY'],
                payment_total_amount: "-",
                payment_paid_amount: "-",
                payment_pending_amount: "-",
                payment_date: "-",
                payment_method: "",
                partial_payment_details: "",
                amount: "",
                notes: "",
                  calculate_invoice_payment_api_link:
        "/api/get_invoice_pending_payment_data/" + this.invoice_data.slack,
        show_payment_history_modal : false,
        order_processing: false,
        show_express_payment_link_btn_txt : false
            }
        },
        props: {
            invoice_data: [Array, Object],
            invoice_statuses: [Array, Object],

            transaction_type_data: String,
            mada_visa_master_img: String,
            accounts: [Array, Object],
            payment_methods: [Array, Object],
            currency_codes: [Array, Object],
            return_invoice_exist: Boolean,

            delete_invoice_access: Boolean,
            make_payment_access: Boolean,
            pdf_path : String,
            express_payment_slack: String,
            expresspay_transaction_permission: Boolean,
        },
        mounted() {
            console.log('Invoice detail page loaded');
             console.log(this.partial_payment_details,'invoice_basic');
            // console.log(this.pdf_path);
            event_bus.$on('cancel_transaction', this.hide_transaction_modal);
            event_bus.$on('start_processing', this.set_make_payment_processing_start);
            event_bus.$on('stop_processing', this.set_make_payment_processing_stop);
            event_bus.$on('invoice_paid', this.hide_payment_submit_button);
        },
        methods: {

  format_date(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY hh:mm:ss");
      }
    },

       format_date_without_time(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY");
      }
    },

            show_payment_history() {

            console.log('d');

            this.show_payment_history_modal = true;
            
      var formData = new FormData();

       formData.append("access_token", window.settings.access_token);

      axios.post(this.calculate_invoice_payment_api_link, formData).then((response) => {
          if (response.data.status_code == 200) {
           console.log(response,'d1');
            this.payment_total_amount = response.data.data.total_amount;
            this.payment_paid_amount = response.data.data.paid_amount;
            this.payment_pending_amount = response.data.data.pending_amount;
            this.payment_date = response.data.data.transaction_paid_date;
            this.partial_payment_details =
              response.data.data.invoice_transaction_details;

            if (
              !isNaN(this.payment_pending_amount) &&
              this.payment_pending_amount == 0
            ) {
              event_bus.$emit("invoice_paid");
            }
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = "error";
            this.show_modal = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

            change_invoice_status(invoice_status){
                this.processing = true;
                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                formData.append("status", invoice_status);

                axios.post(this.change_invoice_link, formData).then((response) => {
                    
                    this.show_modal = false;
                    this.processing = false;

                    if(response.data.status_code == 200) {
                        location.reload();
                    }else{
                        try{
                            var error_json = JSON.parse(response.data.msg);
                            this.loop_api_errors(error_json);
                        }catch(err){
                            this.server_errors = response.data.msg;
                        }
                        this.error_class = 'error';
                        this.show_modal = false;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            submit_transaction(){
                event_bus.$emit('submit_transaction');
            },

            cancel_transaction(){
                event_bus.$emit('cancel_transaction');
            },

            hide_transaction_modal(){
                this.$off("submit_transaction");
                this.$off("cancel_transaction");
                this.show_payment_modal = false;
            },

            set_make_payment_processing_start(){
                this.make_payment_processing = true;
            },

            set_make_payment_processing_stop(){
                this.make_payment_processing = false;
            },

            hide_payment_submit_button(){
                this.show_payment_submit = false;
            },

            delete_invoice(){

                this.$off("submit");
                this.$off("close");
                this.show_modal = true;

                this.$on("submit",function () {       
                    this.processing = true;
                    this.invoice_delete_processing = true;

                    var formData = new FormData();
                    formData.append("access_token", window.settings.access_token);

                    axios.post(this.delete_invoice_api_link, formData).then((response) => {

                        if(response.data.status_code == 200) {
                            if(typeof response.data.link != 'undefined' && response.data.link != ""){
                                window.location.href = response.data.link;
                            }else{
                                location.reload();
                            }
                        }else{
                            this.show_modal = false;
                            this.processing = false;
                            try{
                                var error_json = JSON.parse(response.data.msg);
                                this.loop_api_errors(error_json);
                            }catch(err){
                                this.server_errors = response.data.msg;
                            }
                            this.error_class = 'error';
                        }
                        this.invoice_delete_processing = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                });

                this.$on("close",function () {
                    this.show_modal = false;
                });
            },

                return_invoice() {

       window.location.href = this.invoice_return_api_link;

    },

            print(){
                printJS(this.pdf_path);
            },
            show_express_payment_link_btn(value){
                this.show_express_payment_link_btn_txt = value;
            }

          
        }
    }
</script>
