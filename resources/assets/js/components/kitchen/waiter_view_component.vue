<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title">{{ $t("Waiter View") }} <span class="text-muted" v-show="kot_list_filtered.length > 0">{{ kot_list_filtered.length }} {{ $t("Orders") }}</span> <span v-if="processing == true"><i class='fa fa-circle-notch fa-spin'></i> Loading..</span></span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="d-flex">

                        <div class="custom-control custom-switch ml-3 mr-3 mt-1">
                            <input type="checkbox" class="custom-control-input" id="auto_load_switch" v-model="auto_refresh">
                            <label class="custom-control-label" for="auto_load_switch">{{ $t("Auto Refresh Every 1 Min") }}</label>
                        </div>

                        <button class="btn btn-light" v-on:click="load_kot_list">{{ $t("Refresh") }}</button>
                    </div>
                </div>
            </div>
            
            <p v-if="server_errors" v-html="server_errors" v-bind:class="[error_class]"></p>
            
            <div class="d-flex flex-row mb-3">

                <div class="col-md-12">

                    <div class="d-flex justify-content-center mb-3" v-if="list_populated == true">
                        <input type="text" name="filter_order_no" v-model="filter_order_no" class="form-control form-control-lg col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12" placeholder="Search by Order No / Table"  autocomplete="off">
                    </div>

                    <div class="row flex-nowrap kitchen-wrapper" v-bind:class="{ 'bg-light': list_populated }">

                        <div class="d-flex flex-column mb-4 mt-4 ml-4 mr-4 p-0 bg-white col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-8 kitchen-card" v-for="(kot_list_item, kot_list_key, index) in kot_list_filtered" v-bind:key="index">
                            <div class="p-0 border-bottom">
                                <div class="d-flex flex-wrap p-3">
                                    <span class="mr-auto text-subtitle text-black-50">
                                        Order # {{ kot_list_item.order_number }}
                                    </span>
                                    <span><span class="timer-dot mr-2"></span> {{ kot_list_item.duration }} Minute</span>
                                </div>
                            </div>
                            <div class="p-0 border-bottom">
                                <div class="d-flex justify-content-between p-3">
                                    <div class=""><i v-show="kot_list_item.order_type_data != null" v-bind:class="kot_list_item.order_type_data.icon"></i> {{ kot_list_item.order_type }}</div>
                                    
                                    <span v-if="kot_list_item.kitchen_status != null">
                                        <span v-bind:class="kot_list_item.kitchen_status.color">{{ kot_list_item.kitchen_status.label }}</span>
                                    </span>
                                    
                                </div>
                            </div>
                            <div class="p-0 border-bottom" v-show="kot_list_item.table != ''">
                                <div class="d-flex justify-content-center p-3">
                                    <div class="">{{ kot_list_item.table }}</div>
                                </div>
                            </div>
                            <div class="p-0">
                                <div class="d-flex flex-wrap pl-3 pt-3 pr-3">
                                    <span class="mr-auto text-subtitle text-black-50">Items</span>
                                    <span class="text-subtitle text-black-50">Qty</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 p-3 border-bottom" v-for="(item_list_value, key, item_index) in kot_list_item.products" v-bind:key="item_index">
                                    <i class="kitchen-item-checker text-success fas fa-check-circle" v-show="item_list_value.is_ready_to_serve == 1"></i>
                                    <span class="text-break kitchen-item-title">{{ item_list_value.name }}</span>
                                    <span class="text-break">{{ item_list_value.quantity }}</span>
                                </div>
                            </div>

                        </div>

                        <div v-if="kot_list.length == 0 && processing == false">
                            <span>{{ $t('Zero orders in queue!') }}</span>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</template>  

<script>
    'use strict';
    
    import moment from "moment";

    export default {
        data(){
            return{
                server_errors   : '',
                error_class     : '',
                processing      : false,

                kot_list        : [],

                total_orders     : 0,
                list_populated : false,

                filter_order_no  : '',

                auto_refresh     : true
            }
        },
        props: {
            
        },
        computed: {
            kot_list_filtered(){
                if(this.filter_order_no){
                    return this.kot_list.filter((kot_list_item)=>{
                        return this.filter_order_no.toLowerCase().split(' ').every(v => kot_list_item.order_number.toLowerCase().includes(v) || kot_list_item.customer_phone.toLowerCase().includes(v) || kot_list_item.customer_email.toLowerCase().includes(v) || kot_list_item.table.toLowerCase().includes(v))
                    })
                }else{
                    return this.kot_list;
                }
            }
        },
        mounted() {
            console.log('Waiter order page loaded');
            this.tick_update_duration_for_products();
            this.tick_update_kot_list();
        },
        created(){
            this.load_kot_list();
        },
        methods: {
            load_kot_list(){
                this.processing = true;

                var formData = new FormData();
                formData.append("access_token", window.settings.access_token);

                axios.post('/api/get_waiter_order_list', formData).then((response) => {
                    if(response.data.status_code == 200) {
                        this.kot_list = response.data.data;
                        this.update_duration_for_products();
                        this.processing = false;
                        this.list_populated = (this.kot_list.length > 0)?true:false;
                        this.total_orders = this.kot_list.length;
                    }else{
                        this.processing = false;
                        try{
                            var error_json = JSON.parse(response.data.msg);
                            this.loop_api_errors(error_json);
                        }catch(err){
                            this.server_errors = response.data.msg;
                        }
                        this.error_class = 'error';
                    };
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            calculate_duration(created_date){
                var moment = require('moment-timezone');
                var tz = moment.tz.guess();

                var today = moment();
                var date_obj = new Date(created_date);
                var moment_obj = moment.unix(date_obj).tz(tz);

                var duration = moment.duration(today.diff(moment_obj));
                var minutes = Math.abs(Math.round(duration.as('minutes')));
                minutes = (isNaN(minutes))?'-':minutes;
                return minutes;
            },

            update_duration_for_products(){
                for(var i = 0; i < this.kot_list.length; i++){   
                    var duration = this.calculate_duration(this.kot_list[i].create_at_utc);
                    this.$set(this.kot_list[i], 'duration', duration);
                }
            },

            tick_update_duration_for_products(){
                setInterval(() => {
                    this.update_duration_for_products();
                }, 1000);
            },

            tick_update_kot_list(){
                setInterval(() => {
                    if(this.auto_refresh == true){
                        this.load_kot_list();
                    }
                }, 60000);
            },
    
        }
    }
</script>