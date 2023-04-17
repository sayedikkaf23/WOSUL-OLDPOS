<template>
   <div class="row">
     <div class="col-lg-8 tab-menu">
                                <ul class="nav nav-tabs category-tag-wrapper">
                                  <li><a class="category-tag" v-for="main_category in main_categories" data-toggle="tab" href="javascript:;" v-on:click="loadSubcategories(main_category.id)">{{ main_category.label }}</a></li>
                                </ul>

                                <ul class="nav nav-tabs category-tag-wrapper sub-categories">
                                  <li v-if="subcategories.length" v-for="subcategory in subcategories">
                                    <a class="category-tag" data-toggle="tab" href="javascript:;" v-on:click="loadProducts(subcategory.id)">{{ subcategory.label }}</a>
                                  </li>
                                </ul>

                                <div class="tab-content">
                                  <div id="menu2" class="tab-pane fade in active show">
                                    <div class="row" v-if="products.length">
                                        <div class="col-lg-4 mt-15" style="padding-left: 11px;" align="center" v-for="product in products">
                                            <div class="card product-card" v-on:click="addProductToBag(product)">
                                                <div class="card-body p-0">
                                                    <img src="images/product-sample-photo.png" height="180" style="height:150px;" class="mt-1">
                                                    <div class="row" style="padding:15px;">
                                                      <div class="col-6">
                                                        <div class="product-title" align="left">{{ product.name }}</div>
                                                      </div>
                                                      <div class="col-6">
                                                        <div class="product-price" align="right"><b>{{ product.purchase_amount_excluding_tax }}</b></div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  </div>
                                  <div id="menu3" class="tab-pane fade">
                                    <h3>Menu 3</h3>
                                    <p>Some content in menu 3.</p>
                                  </div>
                                </div>
                            </div>
      <div class="col-lg-4 card">
          <!-- <div style="margin: 10px 0px;">
              <a href="#" class="point-btn">Default</a>
              <a href="#" class="point-btn">DEF</a>
          </div> -->
          <!-- <div>
              <a href="#" class="count-text">1</a>
              <a href="#" class="count-text">2</a>
              <a href="#" class="count-text">3</a>
              <a href="#" class="count-text-active">4</a>
              <a href="#" class="count-text">5</a>
              <a href="#" class="count-add-minus">+</a>
              <a href="#" class="count-add-minus">-</a>
          </div> -->
          <div class="row" style="margin-top: 25px;">
              <div class="col-lg-6 mb-2"><a href="#" class="point-btn-grey">{{ store_name }}</a></div>
              <div class="col-lg-6 mb-2"><a href="#" class="point-btn-grey">{{ counter_name }}</a></div>
          </div>
          <div class="row">
            <div class="" v-if="product_bag.length">
                                <div class="card-body">
                                  <div class="row product-row" v-for="item in product_bag" :id="'product_item_'+item.index">
                                    <div class="col-sm-4">
                                      <div class="select-product-img">
                                        <img :src="item.product_image">
                                      </div>
                                    </div>
                                    <div class="col-sm-8">
                                      <h6>{{ item.product_name }}</h6>
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="select-product-piece-label">
                                            <label>{{ item.product_unit }}</label>
                                          </div>
                                        </div>
                                        <div class="col-6">
                                          <div class="select-product-piece-count">
                                            <div class="row align-items-center">
                                              <div class="col-3">
                                                <a href="#" class="count-add-minus" v-on:click="updateQty(item.index,-1)">-</a>
                                              </div>
                                              <div class="col-4">
<!--                                                <label :id="'product_qty_label_'+item.index">1</label>-->
                                                <label>{{ item.product_qty }}</label>
                                              </div>
                                              <div class="col-3">
                                                <a href="#" class="count-add-minus" v-on:click="updateQty(item.index,1)">+</a>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-6 mt-3">
                                          <div class="select-product-piece-label">
                                            <label>{{ item.product_price }}</label>
                                          </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                          <div class="select-product-btn">
                                            <button class="btn btn-primary btn-block remove-btn" v-on:click="removeProductFromBag(item.index)" >Remove</button>
                                          </div>
                                        </div>
                                        <div class="col-md-12">  
                                            <small class="text-muted">
                                              <span class="piece">{{ item.product_qty }}</span>
                                              <span class="multiple">*</span>
                                              <span class="price">{{ item.product_price }}</span>
                                              <span class="equal">=</span>
                                              <span class="total-price">{{ item.product_amount }}</span>
                                            </small>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
          </div>
          <div class="tab-menu payment-type-tabs" style="margin-top: 25px; width: 100%">
            <ul class="nav nav-tabs">
              <li><a data-toggle="tab" href="#menu11" class="pay-icon custom-width pl-4 pr-4 active"><img src="theme/assets/images/pay-3.png" height="15"></a></li>
              <li><a data-toggle="tab" href="#menu22" class="pay-icon custom-width pl-4 pr-4"><img src="theme/assets/images/pay-2-alt.png" height="15"></a></li>
              <li><a data-toggle="tab" href="#menu33" class="pay-icon custom-width pl-4 pr-4"><img src="theme/assets/images/pay-5.png" height="15"></a></li>
              <li><a data-toggle="tab" href="#menu44" class="pay-icon custom-width pl-4 pr-4"><img src="theme/assets/images/pay-6.png" height="15"></a></li>
            </ul>
            <div class="tab-content">
              <div id="menu11" class="tab-pane fade in active show">
                <div class="card-box">
                  <div style="width: 48%; float: left;">
                      <label>Cash</label>
                      <input type="" name="" class="card-inbox" value="0.00">
                  </div>
                  <div style="width: 48%; float: left; margin-left: 8px;">
                      <label>Change</label>
                      <input type="" name="" class="card-inbox" value="0.00">
                  </div>
                </div>
                <div class="card" style="border:1px solid rgba(0,0,0,0);box-shadow:none;">
                  <div class="card-body">
                    <div class="row" >
                      <form class="purchases-form">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="">Coupen Code</label>
                              <input type="text" class="form-control" id=""placeholder="" value="Default">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="">Discount Type</label>
                              <input type="text" class="form-control" placeholder="" value="Rate">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="">Discount</label>
                              <input type="text" class="form-control" placeholder="" value="0.00">
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label for="">Value Date</label>
                              <input type="date" class="form-control" placeholder="" value="2020-11-11">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 select-total-label mt-3">
                            <h6><b>Grand Total</b></h6>
                          </div>
                          <div class="col-md-6 select-total-price mt-3 text-right">
                            <h3><b>{{ total_amount }}</b> <small> SR</small></h3>
                          </div>
                        </div>
                        <button class="btn payment-btn">Send To Kitchen</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div id="menu22" class="tab-pane fade ">
                <div class="card-box">
                  <div style="width: 100%; float: left;">
                      <label style="width: 100%">Client</label>
                      <input type="" name="" class="card-inbox2 custom-width" placeholder="" value="Visa">
                      <!-- <a href="#" class="btn-plus">+</a> -->
                  </div>
                  <div style="width: 48%; float: left;">
                      <label>Credit</label>
                      <input type="" name="" class="card-inbox" value="0.00">
                  </div>
                  <div style="width: 48%; float: left; margin-left: 8px;">
                      <label>Cash</label>
                      <input type="" name="" class="card-inbox" value="0.00">
                  </div>
                </div>
                <div class="card" style="border:1px solid rgba(0,0,0,0);box-shadow:none;">
                  <div class="card-body">
                    <div class="row" style="min-height: 70px;">
                      <form class="purchases-form">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="">Coupen Code</label>
                              <input type="text" class="form-control" id=""placeholder="" value="Default">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="">Discount Type</label>
                              <input type="text" class="form-control" placeholder="" value="Rate">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="">Discount</label>
                              <input type="text" class="form-control" placeholder="" value="0.00">
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label for="">Value Date</label>
                              <input type="date" class="form-control" placeholder="" value="2020-11-11">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 select-total-label mt-3">
                            <h6><b>Grand Total</b></h6>
                          </div>
                          <div class="col-md-6 select-total-price mt-3 text-right">
                            <h3><b>{{ total_amount }}</b> <small> SR</small></h3>
                          </div>
                        </div>
                        <button class="btn payment-btn">Send To Kitchen</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div id="menu33" class="tab-pane fade">
                <div class="card" style="border:1px solid rgba(0,0,0,0);box-shadow:none;" >
                  <div class="card-body">
                    <!-- <div class="row"> -->
                      <form class="purchases-form">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <input type="text" class="form-control" id=""placeholder="Transaction ID" value="">
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6 select-total-label mt-3">
                            <h6><b>Grand Total</b></h6>
                          </div>
                          <div class="col-md-6 select-total-price mt-3 text-right">
                            <h3><b>{{ total_amount }}</b> <small> {{ this.session_currency_name }}</small></h3>
                          </div>
                        </div>
                        <button class="btn payment-btn">Send To Kitchen</button>

                      </form>
                    <!-- </div> -->
                  </div>
                </div>
              </div>
              <div id="menu44" class="tab-pane fade">
                <h3>Menu 4</h3>
                <p>Some content in menu 4.</p>
              </div>
            </div>
          </div>
          <div id="pay03" class="tab-pane fade">
            <p>pay03</p>
          </div>
          <div id="pay04" class="tab-pane fade">
            <p>pay04</p>
          </div>
        </div>

         <modalcomponent v-show="show_customer_modal" v-on:close="show_customer_modal = false" ref='customer'>
            <template v-slot:modal-header>
                Provide Customer Details
            </template>
            <template v-slot:modal-body>
                <div class="form-group">
                    <label for="customer_number">{{ $t("Contact Number") }}</label>
                    <cool-select type="text" name="customer_number" v-model="customer_number" v-validate="'min:10'" class="" placeholder="Provide Contact Number"  autocomplete="off" :items="customer_list" item-text="phone" itemValue='phone' :resetSearchOnBlur='false' ref="customer_number" disable-filtering-by-search @search="load_customers($event, 'phone')" @select="set_filter_customer_number($event)" :search-text.sync="filter_customer_number">
                        <template #item="{ item }">
                            <div class='d-flex justify-content-start'>
                            <div>
                                {{ item.name }} - {{ item.phone }}, {{ item.email }}
                            </div>
                            </div>
                        </template>
                    </cool-select>
                   <span v-bind:class="{ 'error' : errors.has('customer_number') }">{{ errors.first('customer_number') }}</span>
                </div>
                <div class="form-group">
                    <label for="customer_email">{{ $t("Email") }}</label>
                    <cool-select type="text" name="customer_email" v-model="customer_email" v-validate="'email'" class="" placeholder="Provide Email"  autocomplete="off" :items="customer_list" item-text="email" itemValue='email' :resetSearchOnBlur='false' ref="customer_email" disable-filtering-by-search @search="load_customers($event, 'email')"  @select="set_filter_customer_email($event)" :search-text.sync="filter_customer_email" >
                        <template #item="{ item }">
                            <div class='d-flex justify-content-start'>
                            <div>
                                {{ item.name }} - {{ item.email }}, {{ item.phone }}
                            </div>
                            </div>
                        </template>
                    </cool-select>
                    <span v-bind:class="{ 'error' : errors.has('customer_email') }">{{ errors.first('customer_email') }}</span>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button type="button" class="btn btn-light" v-shortkey="keyboard_shortcuts_formatted.SKIP_CUSTOMER" @shortkey="select_customer('skip')" @click="select_customer('skip')">Skip</button>
                <button type="button" class="btn btn-primary" v-shortkey="keyboard_shortcuts_formatted.PROCEED_CUSTOMER" @shortkey="select_customer('proceed')" @click="select_customer('proceed')" v-bind:disabled="processing == true"> <i class='fa fa-circle-notch fa-spin'  v-if="processing == true"></i> Proceed</button>
            </template>
        </modalcomponent>

      </div>



</template>

<script>

'use strict';

import moment from "moment";

export default {

  data(){
    return {
        topnav_heading_image : "images/purchase-logo.jpg",
        subcategories : [],
        products : [],
        product_bag : [],
        product_bag_items : 0,
        total_amount : 0,
        discount : 0,
        discount_amount : 0,
        po_number : null,
        po_reference_number : null,
        order_date : null,
        show_modal : false,
        order_date_error : false,
        show_customer_modal  : false,
        order_data  : [Array,Object],

    }
  },

  props:[
      'main_categories',
      'suppliers',
      'stores',
      'store_name',
      'counter_name',
      'session_currency_name',
  ],

  mounted() {

    $('#topnav_heading_image').attr('src',this.topnav_heading_image);

    if(this.order_data !== null){
        this.update_prices();
        this.update_customer();
        if(typeof this.$refs.waiter != 'undefined'){
            this.$refs.waiter.setSearchData(this.waiter_name);
        }
    }else{
        this.show_customer_modal = (this.enable_customer_detail_popup != null)?this.enable_customer_detail_popup:true;
        if(this.show_customer_modal == false){
            this.select_customer('skip');
        }
    }

  },

  methods:{
    loadSubcategories(catetgory_id){

        axios.get('/purchase/subcategories',{
            params : {
                catetgory_id : catetgory_id
            }
        })
        .then((response)=>{
            // console.log(response.data);
            this.subcategories = response.data;
        })

    },

    loadProducts(sub_category_id){
        axios.get('/purchase/products', {
            params : {
                sub_category_id : sub_category_id
            }
        })
        .then((response)=>{
            // console.log(response.data);
            this.products = response.data;
        })
    },

    addProductToBag(product){

      this.product_bag_items = this.product_bag.length;

      let item = {
          "index" : this.product_bag_items++,
          "product_image" : "images/product-sample-photo.png",
          "product_name" : product.name,
          "product_slack" : product.slack,
          "product_code" : product.product_code,
          "product_price" : product.purchase_amount_excluding_tax,
          "product_unit" : 1,
          "product_qty" : 1,
          "product_amount" : product.purchase_amount_excluding_tax,
      };

      // skip to add if product is already added into bag
      if(this.product_bag.find(item => item.product_code === product.product_code)){
        return false;
      }

      this.product_bag.push(item);
      this.calculateTotal();

    },

    removeProductFromBag(index){


      this.product_bag = $.grep(this.product_bag, function(e){
           return e.index != index;
      });
      $('#product_item_'+index).remove();
      this.calculateTotal();

    },

    updateQty(index,qty){

      let this_item = this.findByValue(this.product_bag,index);
      let new_qty = this_item.product_qty + qty;

      if(new_qty > 0){
        this_item.product_qty = new_qty;
        this_item.product_amount = new_qty * this_item.product_price;
      }

      this.calculateTotal();

    },

    findByValue(object,index) {
      let obj = object.find(item => item.index === index);
      return obj;
    },

    calculateTotal(){

      let total = 0;
      $.each(this.product_bag,function(index,value){
          total += parseFloat(value.product_amount);
      });

      if(this.discount > 0 ){
        this.discount_amount = (total * this.discount) / 100;
      }
      this.total_amount = total - this.discount_amount;

    },

    convert_date_format(date){
       return (date != '')?moment(date).format("YYYY-MM-DD"):'';
    },

    showModal(option){
      this.show_modal = option;
    },

    submitForm(){

      if(this.order_date == null){
        this.order_date_error = true;
        this.showModal(false);
        return false;
      }else{
        this.order_date_error = false;
      }
      
      let formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("supplier", $('#supplier').val());
      formData.append("order_date", this.convert_date_format(this.order_date));
      formData.append("order_due_date", this.convert_date_format(this.order_date));
      formData.append("currency", 'INR');
      formData.append("shipping_charge", 0);
      formData.append("packing_charge", 0);
      formData.append("tax_option", 'DEFAULT_TAX');
      formData.append("update_stock", 1);
      formData.append("po_number", new Date().valueOf());
      formData.append("po_reference_number", new Date().valueOf());
      // formData.append("terms", 'asdasd');
      formData.append("products", JSON.stringify(this.product_bag));

      axios.post('/api/add_purchase',formData).then((response) => {
        console.log(response);
        this.show_modal = false;
        if(response.data.status){

          alert(response.data.msg);
          // setTimeout(function(){
          //   location.href = '/purchase_orders'
          // },1000);
        }

      }).catch((error) => {
        console.log('Error:'+error);
      })

    }

  }
}


</script>
