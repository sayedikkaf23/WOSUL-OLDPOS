<template>
    <div class="row">
        <div class="col-md-12">
            
            <form @submit.prevent="submit_form" class="row mb-3" id="barcodeForm">
                <div class="col-md-6 mb-4">
                    <div class="d-flex flex-wrap">
                        <div class="mr-auto">
                            <span class="text-title">{{ $t("Product Label") }}</span>
                        </div>
                        
                    </div>
                        
                    <p v-html="server_errors" v-bind:class="[error_class]"></p>

                    <div class="form-row mb-2">
                        <!-- <div class="form-group col-md-3">
                            <label for="supplier">{{ $t("Choose Supplier") }}</label>
                            <cool-select type="text" name="supplier" :placeholder="supplier_placeholder"  autocomplete="off" v-model="supplier" :items="supplier_list" item-text="label" itemValue='slack' @search='load_suppliers'></cool-select>
                        </div> -->
                        <!-- <div class="form-group col-md-10">
                            <label for="barcode">{{ $t("Search and Choose Products") }}</label>
                            <cool-select type="text" v-model="search_product"  autocomplete="off" inputForTextClass="form-control form-control-custom" :items="product_list" item-text="label" itemValue='label' :resetSearchOnBlur="false" disable-filtering-by-search @search='load_products' @select='add_product_to_list' :placeholder="typing_placeholder" ></cool-select>
                        </div> -->
                    </div>
                    <div class="form-row mb-2">
                        <div class="form-group col-md-8 mb-1">
                            <label for="name">{{ $t("Name & Description") }}</label>
                        </div>
                        <div class="form-group col-md-2 mb-1">
                            <label for="quantity">{{ $t("Quantity") }}</label>  
                        </div>
                        <div class="form-group col-md-2 mb-1">
                            <label for="quantity">{{ $t("Barcode No.") }}</label>  
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-8">
                            <input type="text" name="product_name" v-model="product_name_selected" readonly v-validate="'required|max:250'"  :data-vv-as="product_name" class="form-control form-control-custom" autocomplete="off">
                            <span v-bind:class="{ 'error' : errors.has('product_name') }">{{ errors.first('product_name') }}</span> 
                        </div>
                        <div class="form-group col-md-2">
                            <input type="number" name="quantity" v-model="product_quantity" v-validate="'required|min_value:0.1'" data-vv-as="Quantity" class="form-control form-control-custom"  autocomplete="off" step="1" min="1">
                            <span v-bind:class="{ 'error' : errors.has('quantity') }">{{ errors.first('quantity') }}</span> 
                        </div>
                        <div class="form-group col-md-2">
                            <input type="number" name="barcode_no" v-model="barcode_no" readonly data-vv-as="barcode_no" class="form-control form-control-custom"  autocomplete="off">
                            <span v-bind:class="{ 'error' : errors.has('barcode_no') }">{{ errors.first('barcode_no') }}</span> 
                        </div>
                    </div>

                    <!-- <div class="form-row mb-2" v-for="(product, index) in products" :key="index">
                        <div class="form-group col-md-8">
                            <input type="text" v-bind:name="'product.name_'+index" v-model="product_name_selected" v-validate="'required|max:250'"  :data-vv-as="product_name" class="form-control form-control-custom" autocomplete="off">
                            <span v-bind:class="{ 'error' : errors.has('product.name_'+index) }">{{ errors.first('product.name_'+index) }}</span> 
                        </div>
                        <div class="form-group col-md-2">
                            <input type="number" v-bind:name="'product.quantity_'+index" v-model="product.quantity" v-validate="'required|numeric|min_value:1'" data-vv-as="Quantity" class="form-control form-control-custom"  autocomplete="off" step="1" min="1">
                            <span v-bind:class="{ 'error' : errors.has('product.quantity_'+index) }">{{ errors.first('product.quantity_'+index) }}</span> 
                        </div>
                        <div class="form-group col-md-1" v-if="products.length>1">
                            <button type="button" class="btn btn-outline-danger" @click="remove_product(index)"><i class="fas fa-times"></i></button>
                        </div>
                    </div> -->
                    <div id="barcode_print_data">
                        <!-- <div class="form-row m-1" v-if="barcode != '' " v-html="barcode"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_barcode_value == 1" v-html="barcode_no_print"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_item_price_with_vat == 1" v-html="sale_price_with_tax"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_item_name == 1" v-html="product_name_selected"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_expiry_date == 1" v-html="barcode_expiry_date_print"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_manufacturing_date == 1" v-html="barcode_manuf_data_print"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_store_name == 1" v-html="barcode_store_name_print"></div>
                        <div class="form-row m-1" v-if="barcode != '' && show_notes == 1" v-html="barcode_notes_print"></div> -->
                        
                        <div style="width: 300px;
                            height: auto;
                            background: white;
                            margin: 0 auto;
                            border: 1px solid #f2f2f2;
                            border-radius: 10px;
                            padding: 10px 30px;
                            box-sizing: border-box;">

                            <div style="width: 100%; overflow: hidden; margin-top: 5px;" v-if="barcode != '' " v-html="barcode"></div>
                            <div style="text-align: center;" v-if="barcode != '' && show_barcode_value == 1">
                                <p style="text-transform: uppercase;font-size:12px;margin-bottom: 0px;" v-html="barcode_no_print"></p>
                            </div>
                            <div style="text-align: right;" v-if="barcode != '' && show_item_price_with_vat == 1">
                                <h5 style="margin-bottom:0px;margin-top:0px" class="p-label-price"><b v-html="sale_price_with_tax"></b> <b>SAR</b></h5>
                            </div>
                            <div v-if="barcode != '' && show_item_name == 1">
                                <h5 style="margin-top:0px;margin-bottom:4px" class="p-label-name"><b v-html="product_name_selected"></b></h5>
                            </div>
                            <div style="margin-bottom: 4px;" v-if="(barcode != '') && (show_expiry_date == 1 || show_manufacturing_date == 1)">
                                <div style="display: flex; justify-content: space-between;">
                                    <h4 v-if="show_manufacturing_date == 1 && barcode_manuf_data_print != ''" style="margin: 0px;" v-html="barcode_manuf_data_print"></h4>
                                    <h4 v-if="show_expiry_date == 1 && barcode_expiry_date_print != ''" style="margin: 0px;" v-html="barcode_expiry_date_print"></h4>
                                </div>
                            </div>
                            <div style="text-align: center; margin-bottom: 5px;" v-if="barcode != '' && show_store_name == 1">
                                <p style="margin-bottom: 0px;" v-html="barcode_store_name_print"></p>
                            </div>
                            <div style="text-align: center;" v-if="barcode != '' && show_notes == 1">
                                <p style="margin-bottom: 5px;"><small v-html="barcode_notes_print"></small></p>
                            </div>
                        </div>

                    </div>
                    <div class="form-row" v-if="barcode != '' " >
                        <div class="form-group col-md-5"></div>
                        <div class="form-group col-md-7 mt-4">
                            <button type="button" class="btn btn-primary" @click="printBarcode"> 
                                {{ $t("Print Barcode") }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    
                    <div class="form-row " >
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_barcode_value" v-model="show_barcode_value" />
                            <label class="" for="show_barcode_value">{{ $t("Show barcode value")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_item_name" v-model="show_item_name" />
                            <label class="" for="show_item_name">{{ $t("Show item name")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_item_price_with_vat" v-model="show_item_price_with_vat" />
                            <label class="" for="show_item_price_with_vat">{{ $t("Show item price with VAT")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_store_name" v-model="show_store_name" />
                            <label class="" for="show_store_name">{{ $t("Show store name")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_manufacturing_date" v-model="show_manufacturing_date" />
                            <label class="" for="show_manufacturing_date">{{ $t("Show manufacturing date")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            
                            <label class="" for="manufacturing_date">{{ $t("Manufacturer date")}}</label>
                            <input v-if="barcode != '' " type="date" name="manufacturing_date"  
                                v-model="product_barcode_details.manufacturing_date_raw" class="form-control form-control-custom" v-validate="'date_format:yyyy-MM-dd'" />
                            <input v-else  type="date" name="manufacturing_date"  v-model="product_manufacturing_date" 
                                class="form-control form-control-custom" v-validate="'date_format:yyyy-MM-dd'" />
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_expiry_date"  v-model="show_expiry_date" />
                            <label class="" for="show_expiry_date">{{ $t("Show expire date")}}</label>
                        </div>
                        <div class="form-group col-md-9">
                            <label class="" for="expiry_date">{{ $t("Expiry date")}}</label>
                            <input v-if="barcode != '' " type="date" name="expiry_date"  
                                v-model="product_barcode_details.expiry_date_raw" class="form-control form-control-custom" v-validate="'date_format:yyyy-MM-dd'" />
                            <input v-else  type="date" name="expiry_date"  v-model="product_expiry_date" 
                                class="form-control form-control-custom" v-validate="'date_format:yyyy-MM-dd'" />
                        </div>
                        <div class="form-group col-md-9">
                            <input type="checkbox" class="checkbox_styled mr-2" name="show_notes" v-model="show_notes" />
                            <label class="" for="show_notes">{{ $t("Show note")}}</label>
                            <textarea name="notes" class="form-control form-control-custom" v-model="product_barcode_details.notes" :placeholder="Note" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-row mb-2" >
                        <button type="submit" class="btn btn-primary" v-bind:disabled="processing == true"> 
                            <i class='fa fa-circle-notch fa-spin' v-if="processing == true"></i>{{ $t("Generate Barcode") }}
                        </button>
                    </div>
                    
                </div>

            </form>
                
        </div>

        <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
            <template v-slot:modal-header>
                {{ $t('Confirm') }}
            </template>
            <template v-slot:modal-body>
                {{ $t('Are you sure you want to proceed?') }}
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" @click="$emit('close')">{{ $t('Cancel') }}</button>
                <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> {{ $t('Continue') }}</button>
            </template>
        </modalcomponent>
        
    </div>
</template>

<script>
    'use strict';

    import { CoolSelect } from "vue-cool-select";
    
    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,
                modal           : false,
                show_modal      : false,
                api_link        : '/api/generate_barcodes',
                supplier       : '',
                product_list   : [],
                search_product : '',
                supplier_list  : [],
                
                supplier_placeholder: this.$t("Please choose supplier"),
                typing_placeholder: this.$t("Start Typing.."),

                product_slack: this.product_data.slack,
                product_name_selected: this.product_data.name,
                barcode_number: this.product_data.barcode,
                product_manufacturing_date: this.product_data.product_manufacturer_date != null ? this.product_data.product_manufacturer_date_raw : "",
                product_expiry_date: this.product_data.product_expiry_date != null ? this.product_data.product_expiry_date_raw : "",
                barcode_no: this.product_data.barcode != null ? this.product_data.barcode : "",
                product_quantity: 1,
                sale_price_with_tax: this.product_data.sale_amount_including_tax,
                products: [],
                products_template : {
                    slack: '',
                    name : '',
                    quantity : '',
                },
                product_name:this.$t("Name"),
                barcode:'',
                Note: 'Write some notes',
                barcode_no_print:'',
                show_item_name:'',
                show_barcode_value:'',
                show_item_price_with_vat:'',
                show_store_name:'',
                show_manufacturing_date:'',
                show_expiry_date:'',
                show_notes:'',
                barcode_product_name_print:'',
                barcode_manuf_data_print:'',
                barcode_expiry_date_print:'',
                barcode_store_name_print:'',
                barcode_notes_print:'',
                barcode_details:{},
            }
        },
        props: {
            product_data: [Array, Object],
            product_barcode_details: [Array, Object],
            barcode_data: String,
        },
        mounted() {
            console.log('Generate barcode page loaded');
            this.barcode = this.barcode_data;
            this.barcodeDetails();
        },
        created(){
            this.products.push(this.product_data);
            console.log(this.product_data);
            console.log(this.product_barcode_details);
        },
        methods: {
            barcodeDetails() {
                // console.log(barcode_details);
                // if(Object.keys(this.product_barcode_details).length < 1){
                //     this.product_barcode_details = barcode_details;
                // }
                if(Object.keys(this.product_barcode_details).length > 0){
                    console.log(this.product_barcode_details.quantity);
                    this.product_quantity = this.product_barcode_details.quantity;
                    this.barcode_no = this.product_barcode_details.barcode_no;
                    this.barcode_no_print = this.product_barcode_details.barcode_no;
                    this.show_barcode_value = this.product_barcode_details.show_barcode_value;
                    this.show_item_price_with_vat = this.product_barcode_details.show_item_price_with_vat;
                    this.show_item_name = this.product_barcode_details.show_item_name;
                    this.show_manufacturing_date = this.product_barcode_details.show_manufacturing_date;
                    this.show_expiry_date = this.product_barcode_details.show_expiry_date;
                    if(this.product_barcode_details.manufacturing_date != ''){
                        this.barcode_manuf_data_print = 'E: '+this.product_barcode_details.manufacturing_date;
                    }else{
                        this.barcode_manuf_data_print = '';
                    }
                    if(this.product_barcode_details.expiry_date != ''){
                        this.barcode_expiry_date_print = 'E: '+this.product_barcode_details.expiry_date;
                    }else{
                        this.barcode_expiry_date_print = '';
                    }
                    this.barcode_store_name_print = this.product_barcode_details.store_name;
                    this.show_store_name = this.product_barcode_details.show_store_name;
                    this.show_notes = this.product_barcode_details.show_notes;
                    this.barcode_notes_print = this.product_barcode_details.notes;
                    console.log('Barcode details generated');
                }
            },
            submit_form(){

                this.$off("submit");
                this.$off("close");

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.show_modal = true;
                        this.$on("submit",function () {
                            var barcodeForm = document.getElementById("barcodeForm")
                            this.processing = true;
                            var formData = new FormData(barcodeForm);
                            formData.append("access_token", window.settings.access_token);
                            //formData.append("products", JSON.stringify(this.products));
                            formData.append("product_slack", this.product_slack == null ? "" : this.product_slack );
                                    
                            axios.post(this.api_link, formData).then((response) => {
                                this.server_errors = '';
                                this.show_modal = false;
                                this.processing = false;

                                if(response.data.status_code == 200) {
                                    if(typeof response.data.barcode != 'undefined' && response.data.barcode != ""){
                                        this.barcode = response.data.barcode;
                                        // console.log('res='+response.data.barcode_data.barcode_no);
                                        this.product_quantity = response.data.barcode_data.quantity;
                                        this.barcode_no = response.data.barcode_data.barcode_no;
                                        this.barcode_no_print = response.data.barcode_data.barcode_no;
                                        this.show_barcode_value = response.data.barcode_data.show_barcode_value;
                                        this.show_item_price_with_vat = response.data.barcode_data.show_item_price_with_vat;
                                        this.show_item_name = response.data.barcode_data.show_item_name;
                                        this.show_manufacturing_date = response.data.barcode_data.show_manufacturing_date;
                                        this.show_expiry_date = response.data.barcode_data.show_expiry_date;
                                        if(response.data.barcode_data.manufacturing_date != ''){
                                            this.barcode_manuf_data_print = 'E: '+response.data.barcode_data.manufacturing_date;
                                        }else{
                                            this.barcode_manuf_data_print = '';
                                        }
                                        if(response.data.barcode_data.expiry_date != ''){
                                            this.barcode_expiry_date_print = 'E: '+response.data.barcode_data.expiry_date;
                                        }else{
                                            this.barcode_expiry_date_print = '';
                                        }
                                        this.barcode_store_name_print = response.data.barcode_data.store_name;
                                        this.show_store_name = response.data.barcode_data.show_store_name;
                                        this.show_notes = response.data.barcode_data.show_notes;
                                        this.barcode_notes_print = response.data.barcode_data.notes;
                                        //window.open(response.data.link, '_blank');
                                    }else{
                                        location.reload();
                                    }
                                }else{
                                    try{
                                        var error_json = JSON.stringify(response.data.msg);
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

                        });
                        this.$on("close",function () {
                            this.show_modal = false;
                        });
                    }
                });
            },

            printBarcode() {

                var divContents = document.getElementById("barcode_print_data").innerHTML;
                // var a = window.open('', '', 'height=500, width=500');
                // a.document.write('<html>');
                // a.document.write('<body > <h1>Div contents are <br>');
                // a.document.write(divContents);
                // a.document.write('</body></html>');
                // a.document.close();
                // a.print();

                let ifram = document.createElement("iframe");
                ifram.style = "display:none";
                document.body.appendChild(ifram);
                let pri = ifram.contentWindow;
                pri.document.open();
                pri.document.write('<html><head>');  
                pri.document.write('</head><body >'); 
                pri.document.write(divContents);
                pri.document.write('</body></html>'); 
                pri.document.close();
                pri.focus();
                pri.print();

            },
            remove_product(index) {
                this.products.splice(index, 1);
            },

            load_suppliers (keywords) {
                if(typeof keywords != 'undefined'){
                    if (keywords.length > 0) {

                        var formData = new FormData();
                        formData.append("access_token", window.settings.access_token);
                        formData.append("keywords", keywords);

                        axios.post('/api/load_suppliers', formData).then((response) => {
                            if(response.data.status_code == 200) {
                                this.supplier_list = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    }
                }
            },

            load_products (keywords) {
                if(typeof keywords != 'undefined'){
                    if (keywords.length > 0) {

                        var formData = new FormData();
                        formData.append("access_token", window.settings.access_token);
                        formData.append("keywords", keywords);
                        formData.append("supplier", this.supplier);

                        axios.post('/api/load_product_for_po', formData).then((response) => {
                            if(response.data.status_code == 200) {
                                this.product_list = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    }
                }
            },

            add_product_to_list(item) {
                if( item.product_slack != '' ){
                    var current_product = {
                        slack : item.product_slack,
                        name : item.label,
                        quantity : 1,
                    };
                }

                var item_found = false;
                for(var i = 0; i < this.products.length; i++){   
                    if(this.products[i].slack == item.product_slack){
                        this.products[i].quantity++;
                        item_found = true;
                    }
                }

                if(this.products[0].name == '' && this.products[0].quantity == ''){
                    this.$set(this.products, 0, current_product);
                }else{
                    if(item_found == false){
                        this.products.push(current_product);
                    }
                }
                this.product_list = [];
            }
        }
    }
</script>

<style type="text/css" media="print">
    .checkbox_styled{
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }
    @page 
    {
        size: auto;   /* auto is the main current active printer page size */
        margin: 0mm;  /* this small affects the margin in the printer IMP settings */
    }
    @media print 
    {
        p
        {
            font-size:12px!important;
        }
        .p-label-price
        {
            margin-top:0px!important;
            margin-bottom:0px!important;
        }
        .p-label-name
        {
            margin-top:0px!important;
            margin-bottom:4px!important;
        }
    }
    /* .barcode-wrapper 
    {
        width: 300px;
        height: auto;
        background: white;
        margin: 0 auto;
        border: 1px solid #f2f2f2;
        border-radius: 10px;
        padding: 10px 20px;
        box-sizing: border-box;
    }
    .barcode-img 
    {
        width: 100%;
        overflow: hidden;
    }
    .text-uppercase
    {
        text-transform: uppercase;
    }
    .b-img 
    {
        width: 100%;
    }
    .text-center 
    {
        text-align: center;
    }
    .text-right 
    {
        text-align: right;
    }
    .d-flex
    {
        display: flex;
    }
    .justify-content-between 
    {
        justify-content: space-between;
    }
    .date-block h3 
    {
        margin: 0px;
    } */
</style>