<template>
  <div class="row">
    <div class="col-md-8 p-0 border-right">
      <div class="col-lg-12 tab-menu pos-tab-menu">
        <ul class="nav nav-tabs category-tag-wrapper horizontal-slide">
          <li class="span">
            <a class="category-tag active" data-toggle="tab" href="javascript:void(0)" v-on:click="loadSubcategories(0)">
              {{ $t('All') }}
            </a>
          </li>
          <li v-for="main_category in main_categories" class="span" :key="main_category.id">
            <a class="category-tag" data-toggle="tab" href="javascript:void(0)" v-on:click="loadSubcategories(main_category.id)">
              {{ main_category.label }}
            </a>
          </li>
        </ul>

        <ul class="nav nav-tabs category-tag-wrapper sub-categories horizontal-slide">
          <li class="span">
            <a class="category-tag active" data-toggle="tab" href="javascript:void(0)" v-on:click="setSubcategory(parent_category_id, 'PARENT')">
              {{ $t('All') }}
            </a>
          </li>
          <li v-show="subcategories.length" v-for="subcategory in subcategories" class="span" :key="subcategory.slack">
            <a class="category-tag" data-toggle="tab" href="javascript:void(0)" v-on:click="setSubcategory(subcategory.slack, 'CHILD')">
              {{ subcategory.label }}
            </a>
          </li>
        </ul>
      </div>
      <div class="row col-lg-12 pos-search-wrapper ml-0">
        <div class="d-flex flex-column p-0 border-bottom product-info-form container-fluid">
          <form>
            <div class="form-row ml-0 mb-0">
              <div class="input-group mb-3 col-md-8 p-0">
                <input
                  type="search"
                  name="search"
                  v-model="search"
                  class="form-control form-control-lg pos-search-bar"
                  :placeholder="search_placeholder"
                  autocomplete="off"
                  style="border: 1px solid #186ca5 !important;border-top-right-radius: 0 !important;border-bottom-right-radius: 0 !important;"
                  @input="clearSearch(search)"
                  @keyup="searchByEnterKey"
                />
                <div class="input-group-append">
                  <button
                    type="button"
                    class="btn btn-md text-white"
                    style="background-color:#186ca5;"
                    @click="searchProductsByKeyword(search, 'KEYWORD')"
                  >
                    {{ $t('Search') }}
                  </button>
                </div>
              </div>
              <div class="form-group mb-3 col-md-4 p-0">
                <input
                  type="search"
                  name="search_barcode"
                  v-model="search_barcode"
                  class="form-control form-control-lg pos-search-bar"
                  :placeholder="search_barcode_placeholder"
                  autocomplete="off"
                  v-on:input="searchProductsByKeyword(search_barcode, 'BARCODE'), clearSearch(search_barcode)"
                  style="border: 1px solid #66AF94 !important;margin-left: 2px;"
                />
              </div>
            </div>
            
          </form>
        </div>
      </div>

      
      <div class=" col-md-12" v-if="combos.length">
        <div class="text-muted">{{ combos.length }} {{$t('combo(s) found') }}</div>
      </div>

      <div class="d-flex flex-wrap mb-5 p-3">
        <div class="col-md-4 p-2" v-for="(combo,index) in combos" :key="index">
            <div class="card" style="cursor:pointer;" @click="select_combo(combo)">
              <div class="card-body box">
                  <h5 class="py-3">{{ combo.combo.name }}</h5>
                  <span class="badge badge-light mr-2 border  mr-2" style="border-radius: 10px !important;" v-for="(combo_size,combo_size_index) in combo.combo.sizes" :key="combo_size_index">{{ combo_size.size_name }}</span>
                  <div class="ribbon text-center" v-show="combo.combo.is_discount_enabled">
                    <span style="font-size:25px;">
                      <span v-show="combo.combo.discount_type == 'AMOUNT'" style="font-size:10px;">SAR</span><span>{{ combo.combo.discount_value }}</span><span v-show="combo.combo.discount_type == 'PERCENTAGE'" >%</span>
                      <br> <span style="font-size:15px;">Off</span>
                    </span>
                  </div>
                </div>
            </div>
        </div>
      </div>

      <div class=" col-md-12">
        <div class="text-muted">{{ products_counter.total }} {{ $t('product(s) found') }}</div>
      </div>

      <div class="d-flex flex-wrap mb-5 p-3 product-list " id="product_container" ref="product_list_container" @scroll="loadMoreProducts">
        <div class="col-md-12 p-0">
          <div class="row" v-show="show_error_message">
            <div class="col-md-12 alert alert-danger">{{ error_message }}</div>
          </div>
          <div class="row pos-product-block" style="margin-left:3px;">
            <div
              class="d-flex align-items-start flex-column p-1 mb-1 col-md-4 bg-light hvr-float-shadow product"
              v-for="(product_list_item, index) in product_list"
              v-bind:value="product_list_item.product_slack"
              v-bind:key="index"
              v-on:click="product_list_item.product_modifiers.length > 0 ? selectModifier(product_list_item) : add_to_cart(product_list_item)"
            >
              <div
                class="col-12 p-3 bg-white product-grid"
                v-shortkey="{
                  left: [keyboard_shortcuts_formatted.ARROW_LEFT],
                  right: [keyboard_shortcuts_formatted.ARROW_RIGHT],
                  choose: [keyboard_shortcuts_formatted.CHOOSE_PRODUCT]
                }"
                @shortkey="product_navigate($event)"
                :class="{focus: index === product_focus}"
              >
                <div class="d-flex flex-row-reverse" ref="products">
                  <!-- <div class="col-12 p-3 bg-white product-grid" :style="{ 'box-shadow' : '2px 2px 1px '+product_list_item.product_border_color, 'border' : '1px solid '+product_list_item.product_border_color }" v-shortkey="{left: [keyboard_shortcuts_formatted.ARROW_LEFT], right: [keyboard_shortcuts_formatted.ARROW_RIGHT], choose: [keyboard_shortcuts_formatted.CHOOSE_PRODUCT] }" @shortkey="product_navigate($event)" :class="{ focus: index === product_focus }"> -->
                  <img
                    :src="[
                      product_list_item.product_thumb_image
                        ? '/storage/' + merchant_id + '/product/' + product_list_item.product_thumb_image
                        : '/images/default-product-image.png'
                    ]"
                    alt="No Product Image Found"
                    class=" product-image position-absolute d-none d-lg-block"
                    :style="{
                      border: '1px solid ' + product_list_item.product_border_color
                    }"
                    @error="imageUrl = 'images/default-product-image.png'"
                  />
                </div>
                <div class="product-code">
                  <span class="small text-secondary text-break">{{ $t('Product Code') }} : {{ product_list_item.product_code }}</span>
                </div>
                <div class="text-bold text-break overflow-hidden product-title" style="max-width:50%;">
                  <span v-if="locale == 'en'">{{ product_list_item.name }}</span>
                  <span v-if="locale == 'ar'">
                    <span v-if="product_list_item.name_ar!=null">{{ product_list_item.name_ar }}</span>
                    <span v-else>{{ product_list_item.name }}</span>
                  </span>
                  <div class="badge badge-pill badge-default badge-sm border border-1" v-if="product_list_item.price_tag != ''">{{ product_list_item.price_tag }}</div>
                  <br />
                  <span v-if="product_list_item.show_description_in == 2 || product_list_item.show_description_in == 3 || product_list_item.show_description_in == 6">
                    <span v-if="locale == 'en'" style="white-space: nowrap; overflow: hidden; display:block; text-overflow: ellipsis;">
                      {{ product_list_item.description }}
                     </span>
                    <span v-if="locale == 'ar'" style="white-space: nowrap; overflow: scroll;">
                      {{ product_list_item.description_ar }}
                     </span>
                  </span>
                </div>
                <div
                  class="text-bold text-break overflow-hidden"
                  v-show="
                    parseFloat(product_list_item.quantity) <= parseFloat(product_list_item.alert_quantity) &&
                      product_list_item.quantity != 0 &&
                      product_list_item.quantity != -1 &&
                      product_list_item.quantity != 0 &&
                      product_list_item.quantity != 'Unlimited'
                  "
                >
                  <span class="text-caption badge badge-pill badge-warning">
                    {{ $t('Only') }} {{ product_list_item.quantity }} {{ $t('Stock(s)') }} {{ $t('left') }}
                  </span>
                </div>
                <div
                  class="text-bold text-break overflow-hidden"
                  v-show="product_list_item.quantity <= -1 || product_list_item.quantity == 'Unlimited'"
                >
                  <span class="text-caption badge badge-pill badge-warning">{{ $t('Unlimited Stock') }}</span>
                </div>
                <div class="text-bold text-break overflow-hidden" v-show="product_list_item.quantity == 0">
                  <span class="text-caption badge badge-pill badge-danger">{{ $t('Out Of Stock') }}</span>
                </div>
                <div class="text-bold text-break overflow-hidden" v-show="product_list_item.ingredient_low_stock == 1">
                  <span class="text-caption badge badge-pill badge-warning">{{ $t('Low on Ingredient stock') }}</span>
                </div>
                <div class="text-bold text-break overflow-hidden"></div>
                <div class="mt-auto ml-auto pt-3 text-break product-price">
                  <!-- {{
                    product_list_item.sale_amount_excluding_tax | formatDecimal
                  }} -->
                  {{ calculate_item_total_price(product_list_item) | roundDecimal }}
                  <!-- {{ product_list_item.sale_amount_excluding_tax  | roundDecimal }} -->
                  <span class="product-price-currency">{{ store_currency }}</span>
                  <span
                    v-if="product_list_item.discount_code != null && product_list_item.discount_code.discount_percentage > 0"
                    class="custom-font text-success text-caption"
                    id="discountalbel"
                  >
                    Discount {{ product_list_item.discount_code.discount_percentage }}%
                  </span>

                  <span
                    class="custom-font text-success text-caption"
                    id="discountalbel"
                    v-if="product_list_item.discount_code != null && product_list_item.discount_code.discount_value > 0"
                  >
                    Discount
                    {{ product_list_item.discount_code.discount_value }}
                    {{ store_currency }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 text-center" v-show="product_loader">
          <img src="/theme/assets/images/list-loading.gif" alt="" style="width:100px;" />
        </div>
      </div>
    </div>

    <div class="col-md-4" v-if="order_receipt_pdf != null">
      <div class="text-center">
        <button @click="return_order(this_order_slack)" class="btn btn-warning btn-sm m-1">
          {{ $t('Return Order') }}
        </button>
        <!-- <button @click="cancel_order(this_order_slack)" class="btn btn-danger btn-sm m-1">{{ $t('Cancel') }} {{ $t('Order') }}</button> -->
        <button @click="print_order(this_order_slack)" class="btn btn-success btn-sm m-1">
          {{ $t('Print Receipt') }}
        </button>
        <button type="button" onClick="window.location.reload();" class="btn btn-primary btn-sm m-1">
          {{ $t('New Order') }}
        </button>
      </div>
      <pdf v-for="i in pdf_page_count" :key="i" :page="i" ref="pdf" :src="order_receipt_pdf"></pdf>
    </div>

    <div class="col-md-4 p-0 pt-2" v-if="order_receipt_pdf == null">
      <div class="custom-width-card sidebar-right">
        <div class="cart_form" style="padding-bottom: 0px;">
          <div class="row">
            <div class="col-lg-6 mx-auto my-1">
              <a href="#" class="bg-gradient btn-block p-2 text-white fw-bold rounded" style="font-size:14px;">
                <div class="row">
                  <div class="col-8 font-weight-light">{{ $t('Order Number') }}:</div>
                  <div class="col-4">{{ order_number }}</div>
                </div>
              </a>
            </div>
            <div class="col-lg-6 mx-auto my-1">
              <a href="#" class="bg-gradient btn-block p-2 text-white fw-bold rounded" style="font-size:14px;">
                <div class="row">
                  <div class="col-6 font-weight-light">{{ $t('Value Date') }}:</div>
                  <div class="col-6">{{ value_date }}</div>
                </div>
              </a>
            </div>
            <div class="col-lg-6 mx-auto my-1">
              <a href="#" class="bg-gradient btn-block p-2 text-white fw-bold rounded" style="font-size:14px;">
                <div class="row">
                  <div class="col-6 font-weight-light">{{ $t('Store') }}:</div>
                  <div class="col-6">{{ store_name }}</div>
                </div>
              </a>
            </div>
            <div class="col-lg-6 mx-auto my-1">
              <a href="#" class="bg-gradient btn-block p-2 text-white fw-bold rounded" style="font-size:14px;">
                <div class="row">
                  <div class="col-6 font-weight-light">{{ $t('Counter') }}:</div>
                  <div class="col-6">{{ counter_name }}</div>
                </div>
              </a>
            </div>
          </div>

          <div class="p-0 cart-list">
            <ul class="nav nav-tabs" style="padding-bottom:15px;padding-top:10px;">
              <li class="ml-auto text-center">
                <div
                  role="button"
                  style="cursor:pointer;"
                  data-toggle="tab"
                  href="#menu11"
                  title="Cash"
                  v-on:click="
                    payment_option = 1;
                    update_prices();
                    setActiveClass('cash');
                  "
                  class="p-2  border border-1 rounded"
                  :class="[active_class == 'cash' ? 'bg-primary ' : '']"
                >
                  <img src="/theme/assets/images/cash-icon.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Cash') }}</span>
                </div>
              </li>
              <li class="ml-auto text-center">
                <div role="button" class="p-2  border  border-1 rounded" style="cursor:pointer;" data-toggle="tab" href="#menu22"
                  title="Credit" v-on:click="payment_option = 2; update_prices(); setActiveClass('credit'); "
                  :class="[active_class == 'credit' ? 'bg-primary' : '']" >
                  <img src="/theme/assets/images/credit-icon.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Credit') }}</span>
                </div>
              </li>
              <li class="ml-auto text-center">
                <div
                  role="button"
                  class="p-2  border  border-1 rounded"
                  :class="[active_class == 'search_and_print' ? 'bg-primary' : '']"
                  style="cursor:pointer;"
                  data-toggle="tab"
                  href="#menu33"
                  title="Search & Print"
                  v-on:click="
                    payment_option = 3;
                    return_product();
                    setActiveClass('search_and_print');
                  "
                >
                  <img src="/theme/assets/images/print-pos.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Search & Print') }}</span>
                </div>
              </li>
              <li class="ml-auto text-center">
                <div
                  role="button"
                  class="p-2  border  border-1 rounded"
                  :class="[active_class == 'scan_qr' ? 'bg-primary' : '']"
                  style="cursor:pointer;"
                  data-toggle="tab"
                  href="#menu44"
                  title="Scan QR"
                  v-on:click="
                    payment_option = 4;
                    update_prices();
                    setActiveClass('scan_qr');
                  "
                >
                  <img src="/theme/assets/images/qr.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Scan QR') }}</span>
                </div>
              </li>
              <li class="ml-auto text-center">
                <div
                  role="button"
                  class="p-2  border  border-1 rounded"
                  :class="[active_class == 'close_counter' ? 'bg-primary' : '']"
                  style="cursor:pointer;"
                  data-toggle="tab"
                  title="Close Counter"
                  v-on:click="
                    close_register();
                    setActiveClass('close_counter');
                  "
                >
                  <img src="/theme/assets/images/cash-counter.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Counter') }}</span>
                </div>
              </li>
              <li class="ml-auto text-center">
                <div role="button" class="p-2  border  border-1 rounded" style="cursor:pointer;"
                  data-toggle="tab" title="Hold Orders" v-on:click="get_hold_list();" >
                  <img src="/theme/assets/images/hold_order.png" style="width:50px;" />
                  <br />
                  <span class="text-dark">{{ $t('Hold / Postpaid Orders') }}</span>
                </div>
              </li>
            </ul>
            
            <div
              v-if="combo_cart.length"
            >
              <div v-for="(combo_cart_item, key, combo_cart_index) in combo_cart" v-bind:key="combo_cart_index" class="row">

                <div class="col-3">
                  <img
                  :src="'/images/default-product-image.png'"
                  alt="No Combo Image Found"
                  class="product-image"
                />
                </div>  
                <div class="col-8 py-2">
                  <h5 style="font-size:18px;color:#186ca5;text-transform: uppercase;" class="pb-1">
                    {{ combo_cart_item.combo_name }}   
                  </h5>
                  <div class="pt-2">
                    <span v-for="(item,item_index) in combo_cart_item.products" :key="item_index" class="badge text-white mr-1 my-1" style="font-size: 13px !important;font-weight: 100 !important;border-radius: 3px !important;background: #366cab;padding:8px;">
                      {{ item.product_quantity }} <small> <span v-if="item.product_measurement != null">{{ item.product_measurement.label }}</span> <span v-else> {{ $t("Unit") }} </span>  </small>  {{ item.product.name }} 
                    </span>
                  </div>
                  <div>
                    <span style="color:#186ca5;">Price (Incl. Tax): <span style="font-size:18px;"> {{ combo_cart_item.combo_price_including_tax.toFixed(2) }} </span> </span>
                  </div>
                </div>
                <div class="col-1">
                  <button
                    type="button"
                    @click="remove_combo_from_cart(combo_cart_item.combo_cart_id)"
                    class="close cart-item-remove bg-light mr-2 ml-auto"
                    aria-label="Close"
                    style="height:25px;"
                  >
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
            </div>
            </div>

            <div v-for="(cart_item, key, index) in cart" v-bind:key="index">
            <div
              class="d-flex flex-column pl-3 pt-3 pb-3 border-bottom"
              v-if="!cart_item.is_combo_product"
              v-bind:value="cart_item.product_slack"
            >
              <div  class="d-flex justify-content-between mb-2">
                <img
                  :src="[cart_item.image ? '/storage/' + merchant_id + '/product/' + cart_item.image : '/images/default-product-image.png']"
                  alt="No Product Image Found"
                  :style="[cart_item.image ? {border: '1px solid' + cart_item.product_border_color} : {}]"
                  class="product-image"
                  @error="imageUrl = 'images/default-product-image.png'"
                />
                <span class="text-bold text-break cart-item-title pl-3" style="font-size:17px;">
                  <span v-if="locale == 'en'">{{ cart_item.name }}</span>
                  <span v-if="locale == 'ar'">
                    <span v-if="cart_item.name_ar!=null">{{ cart_item.name_ar }}</span>
                    <span v-else>{{ cart_item.name }}</span>
                  </span>
                  <i aria-hidden="true" class="fa fa-gift cart-item-title" style="font-size: 21px;display: contents;"  v-if="cart_item.gift_flag == true"></i>
                  <br />
                  <span style="font-size:13px;color:gray;">{{ $t('Product Code') }} : {{ cart_item.product_code }}</span>
                  <br />
                  <span>
                    <div  class="cart-custom-price" v-if="cart_item.bonat_discount != true && cart_item.price_type == 'open' && cart_item.gift_flag == false">
                      <div class="text" >
                        <input type="number" autocomplete="off" min="0"
                          :value="cart_item.tax_include_price | roundDecimal"
                          v-on:input="validate_price(cart_item.cart_id, $event)" 
                          class="form-control form-control-custom cart-product-quantity open_price_input w-100" :id="cart_item.cart_id"
                          />
                         
                      </div>
                     
                    </div>
                    <span style="font-size:12px;">{{ $t('Item Price (+ Modif.)') }} : {{ store_currency }}</span>
                    <span  v-if="cart_item.gift_flag == true" style="font-size:18px; text-decoration: line-through;">
                      <!-- {{
                      cart_item.price | formatDecimal
                    }} -->
                      <!-- {{ cart_item.price | roundDecimal }} -->
                      {{ calc_selcted_item_gift_total_price(cart_item, 'with_modif') | roundDecimal }}
                    </span>
                    <span  v-else style="font-size:18px;">
                      {{ calc_selcted_item_total_price(cart_item, 'with_modif') | roundDecimal }}
                    </span>
                    <br />
                    <span class="text-success" style="font-size:12px;" v-if="cart_item.discount_percentage > 0">
                      {{ $t('Discount') }} {{ cart_item.discount_percentage }}%
                      <br />
                    </span>
                    <span class="text-success" style="font-size:12px;" v-if="cart_item.discount_value > 0">
                      {{ $t('Discount Amount') }}
                      {{ cart_item.discount_value | roundDecimal }} {{ store_currency }}
                      <br />
                    </span>
                    <span style="font-size:12px;">
                      {{ $t('Item Price (Excl. Tax)') }} :
                      {{ store_currency }}
                    </span>
                    <span v-if="cart_item.gift_flag == true" style="font-size:15px; text-decoration: line-through;">
                      {{ cart_item.total_price_actual | roundDecimal }}
                      <!-- {{ cart_item.item_total_amount | roundDecimal }} -->
                    </span>
                    <span style="font-size:15px;" v-else>
                      {{ cart_item.total_price | roundDecimal }}
                      <!-- {{ cart_item.item_total_amount | roundDecimal }} -->
                    </span>
                    <span class="text-danger" style="font-size:12px;" v-if="cart_item.total_price <= 0 && cart_item.gift_flag == false">
                      {{ $t('Please set/input price greater than zero or enable the gift flag if you prefer to gift this item') }}
                    </span>
                  </span>
                </span>

                {{ $t('Qty') }}:
                <input type="number" v-if="cart_item.bonat_discount != true" v-model="cart_item.quantity"
                  v-on:input="validate_quantity(cart_item.cart_id, $event);" 
                  class="form-control form-control-custom cart-product-quantity mr-2 ml-3"
                  autocomplete="off" min="0"
                />
                <span class="mr-2 ml-3" v-if="cart_item.bonat_discount == true">1</span>
                <br />
                <button
                  type="button"
                  v-on:click="remove_from_cart(cart_item.cart_id)"
                  class="close cart-item-remove bg-light mr-2 ml-auto"
                  aria-label="Close"
                  style="height:25px;"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="row text-right" v-if="cart_item.bonat_discount == true">
                <div class="col-md-6"></div>
                <div class="col-md-6 text-right">
                  <img src="/images/discount_icon.png" title="bonat-discount-product" alt="disount-product" />
                  Bonat Free Product
                </div>
              </div>
              
              <div class="row" >
                <div class="col-md-3">{{ $t('Add Note') }}</div>
                <div class="col-md-3 p-0">
                  <label class="switch">
                    <input type="checkbox" v-model="cart_item.note_flag" :checked="cart_item.note_flag" />
                    <span class="slider"></span>
                  </label>
                </div>
                <div class="col-md-3" v-show="can_gift_access">{{ $t('Add as a Gift') }}</div>
                <div class="col-md-3 p-0" v-show="can_gift_access">
                  <label class="switch"> 
                    <input type="checkbox" v-model="cart_item.gift_flag" :checked="cart_item.gift_flag"   @change="gift_from_cart(cart_item.cart_id, $event)"/>
                    <span class="slider"></span>
                  </label>
                </div>
               
                
                <div class="col-md-12" v-show="cart_item.note_flag">
                  <textarea v-model="cart_item.note" cols="70" rows="2" autocomplete="off" class="form-control"></textarea>
                </div>
              </div>

              <!--        <div class="row">
                <div class="col-md-8"></div>
                  <div class="col-md-2">
                    <label class="switch">
                      <input type="checkbox" v-model="cart_item.note_flag" :checked="cart_item.note_flag">
                      <span class="slider"></span>
                    </label>
                  </div>
                  <div class="col-md-2">Add Note</div>
                  <div class="col-md-12" v-show="cart_item.note_flag">
                    <textarea name="" v-model="cart_item.note" cols="70" rows="2" class="form-control" autocomplete="off"></textarea>
                  </div>
              </div> -->
              <div class="">
                <div class="chip m-1" v-show="cart_item.modifiers.length" v-for="(modifier, index) in cart_item.modifiers" :key="index">
                  {{ modifier.label }}
                  <!-- <span class="closebtn" @click="remove_modifier(cart_item.product_slack,modifier.id)">&times;</span> -->
                </div>
              </div>
              </div>
            </div>
          </div>

          <div class="tab-menu payment-type-tabs" style="width: 100%">
            <div class="tab-content">
              <div id="menu11" class="tab-pane fade in active show">
                <div class="card-box bg-gradient" style="margin-top:0px;margin-bottom:0px;">
                  <div style="width: 49%; float: left;">
                    <label>{{ $t('Cash') }}</label>
                    <input
                      type="number"
                      name=""
                      class="card-inbox "
                      v-on:input="update_cash_amount()"
                      id="cash_amount"
                      min="0"
                      oninput="validity.valid||(value='');"
                    />
                  </div>
                  <div style="width: 49%; float: left; margin-left: 5px;">
                    <label>{{ $t('Change') }}</label>
                    <input
                      type="number"
                      name=""
                      class="card-inbox "
                      id="change_amount"
                      :value="this.change_amount"
                      min="0"
                      oninput="validity.valid||(value='');"
                    />
                  </div>
                </div>
                
              </div>
              <div id="menu22" class="tab-pane fade ">
                <div class="card-box bg-gradient">
                  <div style="width: 48%; float: left;">
                    <label>{{ $t('Credit') }}</label>
                    <input
                      type="number"
                      class="card-inbox "
                      id="credit_amount"
                      v-on:input="update_cash_amount()"
                      min="0"
                      oninput="validity.valid||(value='');"
                    />
                  </div>
                  <div style="width: 48%; float: left; margin-left: 8px;">
                    <label>{{ $t('Cash') }}</label>
                    <input
                      type="number"
                      class="card-inbox "
                      id="cash_amount2"
                      :value="this.cash_amount2"
                      min="0"
                      oninput="validity.valid||(value='');"
                    />
                  </div>
                  <div style="width: 100%; float: left;" v-if="show_payment_method">
                    <label style="width: 100%">{{ $t('Payment Method') }}</label>
                    <select
                      id="card_name"
                      name="card_name"
                      v-model="card_name"
                      v-validate="'required'"
                      class="form-control form-control-custom custom-select"
                      v-if="payment_methods.length"
                      key="restaurant_table"
                    >
                      <option value="">
                        {{ $t('Choose Payment Method..') }}
                      </option>
                      <option
                        v-for="(payment_method_item, index) in payment_methods"
                        v-bind:value="payment_method_item.slack"
                        v-bind:key="index"
                        :data-label="payment_method_item.label"
                        :data-payment-id="payment_method_item.id"
                      >
                        {{ payment_method_item.label }}
                      </option>
                    </select>
                  </div>
                </div>
                
                <div class="text-center">
                  <vue-qrcode v-if="stcpay_qr_code != ''" v-bind:value="stcpay_qr_code" style="width:150px;" />
                </div>
              </div>

              <div class="card" style="border:1px solid rgba(0,0,0,0);box-shadow:none;" v-if="payment_option == 1 || payment_option == 2">
                <div class="card-body p-0">
                  <div class="row">
                    <form class="purchases-form">
                      <div class="row">
                        <div class="col pl-3 pr-2">
                          <div class="form-group">
                            <label for="">{{ $t('Discount Code') }}</label>
                            <select
                              name="store_discount_code"
                              v-model="store_discount_code"
                              id="store_discount_code"
                              class="form-control"
                              @change="show_bonat_modal"
                            >
                              <option value="0">{{ $t('Choose Discount Code..') }}</option>
                              <option
                                v-for="(store_discount_code, index) in store_discount_codes"
                                v-bind:value="
                                  store_discount_code.discount_type == 'percentage'
                                    ? store_discount_code.discount_percentage
                                    : store_discount_code.discount_value
                                "
                                v-bind:key="index"
                                :data-label="store_discount_code.discount_code"
                                :data-discountid="store_discount_code.id"
                                :data-discount-percentage="store_discount_code.discount_percentage"
                              >
                                {{ store_discount_code.discount_code }}
                              </option>
                              <!-- <option
                                v-for="(store_discount_code_cashier, index) in store_discount_codes_cashier"
                                v-bind:value="
                                  store_discount_code_cashier.discount_type == 'percentage'
                                    ? store_discount_code_cashier.discount_percentage
                                    : store_discount_code_cashier.discount_value
                                "
                                v-bind:key="index"
                                :data-label="store_discount_code_cashier.label"
                                :data-discountid="store_discount_code_cashier.id"
                                :data-discount-percentage="store_discount_code_cashier.discount_percentage"
                              >
                                {{ store_discount_code_cashier.label }}
                              </option> -->
                            </select>
                          </div>
                        </div>
                        <div class="col pl-3 pr-2">
                          <div class="form-group">
                            <label for="">{{ $t('Discount Type') }}</label>
                            <select name="discount_type" id="discount_type" v-model="discount_type" v-on:input="update_prices()" class="form-control">
                              <option value="1">{{ $t('Amount') }}</option>
                              <option value="2">{{ $t('Percentage') }}(%)</option>
                            </select>
                            <!--                              <input type="text" class="form-control" placeholder="" value="Rate">-->
                          </div>
                        </div>
                        <!-- watch -->
                        <div class="col pl-2">
                          <div class="form-group">
                            <label for="">{{ $t('Discount') }}</label>
                            <input
                              type="number"
                              class="form-control"
                              placeholder=""
                              id="discount_rate"
                              step=".01"
                              oninput="validity.valid||(value='');"
                              v-model="discount_rate"
                            />
                          </div>
                        </div>
                        <!-- 
                        <div class="col-12">
                          <div class="form-group">
                            <label for="">{{ $t("Value Date") }}</label>
                            <input
                              type="date"
                              class="form-control"
                              placeholder=""
                              v-model="value_date"
                            />
                          </div>
                        </div>
                        -->
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div id="menu33" class="tab-pane fade">
                <div class="card" style="border:1px solid rgba(0,0,0,0);box-shadow:none;">
                  <div class="card-body p-0">
                    <!-- <div class="row"> -->
                    <form class="purchases-form p-0">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="transaction_id">{{ $t('Enter Date') }}</label>
                            <input type="date" class="form-control" placeholder="" id="transaction_date" v-model="transaction_date" />
                          </div>
                          <div class="form-group">
                            <label for="transaction_id">{{ $t('Enter Reference Number') }}</label>
                            <input type="text" class="form-control" id="transaction_id" placeholder="" v-model="transaction_id" />
                          </div>
                          <div class="row mt-3">
                            <div class="col-12">
                              <button type="button" class="btn btn-primary-updated btn-block btn-md" @click="get_return_order('SAVE')">
                                <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                                {{ $t('Search') }}
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                    <!-- </div> -->
                  </div>
                </div>
              </div>
              <div id="menu44" class="tab-pane fade">
                <div class="text-center" v-show="qrValue">
                  <vue-qrcode v-bind:value="qrValue" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" v-show="show_action_btn">
          <div class="col-6" style="font-size:10px;">
            <strong class="">{{ $t('Sub Total (Exclude Tax)') }}</strong>
          </div>
          <div class="col-6 text-right" style="font-size:15px;">
            <strong class="">{{ total_amount | roundDecimal }} {{ this.session_currency_code }}</strong>
          </div>
        </div>
        <div class="row" v-show="show_action_btn">
          <div class="col-6" style="font-size:10px;">
            <strong class="">{{ $t('Discount') }}</strong>
          </div>
          <div class="col-6 text-right" style="font-size:15px;">
            <strong class="">{{ total_discount_amount | roundDecimal }} {{ this.session_currency_code }}</strong>
          </div>
        </div>
        <div class="row" v-show="show_action_btn">
          <div class="col-6" style="font-size:10px;">
            <strong class="">{{ $t('Sub Total (After Disc.)') }}</strong>
          </div>
          <div class="col-6 text-right" style="font-size:15px;">
            <strong class="">{{ discounted_sub_total | roundDecimal }} {{ this.session_currency_code }}</strong>
          </div>
        </div>
        <div class="row" v-show="show_action_btn" v-for="tax_component in store_level_tax_component_objects" :key="tax_component.tax_type">
          <div class="col-6" style="font-size:10px;" v-if=" tolower(tax_component.tax_type) != 'no tax'">
            <strong class="">{{ $t(tax_component.tax_type) }} {{ $t(tax_component.tax_percentage + ' %') }}</strong>
          </div>
          <div class="col-6 text-right" style="font-size:15px;" v-if=" tolower(tax_component.tax_type) != 'no tax'">
            <strong class="">{{ tax_component.tax_amount | roundDecimal }} {{ session_currency_code }}</strong>
          </div>
        </div>
        <div class="row" v-show="show_action_btn">
          <div class="col-6 cart-item-title" style="font-size:15px;">
            <strong class="">{{ $t('Grand Total') }}</strong>
          </div>
          <div class="col-6 text-right cart-item-title" style="font-size:25px;">
            <!-- <strong
              class="
            "
              v-html="
                total != total_amount && String(total).split('.')[1] == '99'
                  ? Math.round(Number(total)) +
                    ' ' +
                    this.session_currency_code
                  : this.$options.filters.roundDecimal(total) +
                    ' ' +
                    this.session_currency_code
              "
              
            ></strong> -->
            <strong class="" v-html="this.$options.filters.roundDecimal(total) + ' ' + this.session_currency_code"></strong>
          </div>
        </div>
        <div class="row mt-3" v-show="show_action_btn">
          <div v-if="enable_partial_paid == true" class="pr-1 pl-1" :class="(send_to_kitchen_access && order_restaurant_mode) ? 'col-3 ' : 'col-4'" >
            <button type="submit" class="btn btn-primary-updated btn-block btn-md" :class="[processing ? 'disabled' : '']"
              :disabled="processing" @click="create_order_with_print('PARTIAL_PAYMENT')" >
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Post Paid') }}
            </button>
          </div>
          <div class="pr-1 pl-1" :class="(send_to_kitchen_access && order_restaurant_mode) ? 'col-3 ' : 'col-4'" >
            <button type="submit" class="btn btn-primary-updated btn-block btn-md" :class="[processing ? 'disabled' : '']"
              :disabled="processing" @click="create_order_with_print('SAVE')" >
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Save & Print') }}
            </button>
          </div>
          <div class="pr-1 pl-1" :class="order_restaurant_mode ? 'col-3 ' : 'col-4'" v-show="send_to_kitchen_access" v-if="order_restaurant_mode == 1">
            <button type="submit" class="btn btn-warning btn-block btn-md " :class="[processing ? 'disabled' : '']"
              v-shortkey="keyboard_shortcuts_formatted.SEND_TO_KITCHEN"  v-bind:disabled="processing == true"
              @shortkey="create_order('IN_KITCHEN')"
              @click.stop.prevent="create_order('IN_KITCHEN')" >
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Send to Kitchen') }}
            </button>
          </div>
          <div class="pr-1 pl-1" :class="order_restaurant_mode ? 'col-3 ' : 'col-4'" v-show="can_hold_access">
            <button type="submit" class="btn btn-warning btn-block btn-md " :class="[processing ? 'disabled' : '']"
              v-shortkey="keyboard_shortcuts_formatted.SEND_TO_KITCHEN"  v-bind:disabled="processing == true"
              @click.stop.prevent="create_order_with_print('HOLD')" >
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t('Hold Order') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!--       <div class="row">-->
    <!--         <div class="col-lg-8"></div>-->
    <!--         <div class="col-lg-4">-->
    <!--            -->
    <!--         </div>-->

    <!--        </div>-->

    <modalcomponent v-if="verify_coupon_modal" v-on:close="verify_coupon_modal = false" :hide_modal_footer="true" :show_footer="false">
      <template v-slot:modal-header>
        {{ $t('Verify Bonat Coupon') }}
      </template>
      <template v-slot:modal-body>
        <div class="row">
          <div class="col-md-12">
            <form @submit.prevent="submit_form" class="mb-3">
              <div class="d-flex flex-wrap mb-4"></div>

              <p v-html="server_errors" v-bind:class="[error_class]"></p>

              <div class="form-row mb-2">
                <div v-bind:class="['col-md-12', 'form-group']">
                  <input
                    type="text"
                    name="coupon"
                    v-model="coupon"
                    v-validate="'required|max:250'"
                    class="form-control form-control-custom"
                    autocomplete="off"
                    :placeholder="enter_coupon"
                  />
                  <span v-bind:class="{error: errors.has('coupon')}">{{ errors.first('coupon') }}</span>
                </div>
              </div>

              <div class="form-row mb-2"></div>
              <div class="flex-wrap mb-4">
                <div class=" pull-right text-right">
                  <button type="submit" class="btn btn-primary " v-bind:disabled="bonat_verify_processing == true">
                    <i class="fa fa-circle-notch fa-spin" v-if="bonat_verify_processing == true"></i>
                    {{ $t('Verify') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t('Close') }}
        </button>
      </template>
    </modalcomponent>
    
    <modalcomponent v-if="show_hold_return_modal" v-on:close="show_hold_return_modal = false">
      <template v-slot:modal-header>
          {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
          {{ $t("Are you sure you want to proceed?") }}
      </template>
      <template v-slot:modal-footer>
          <button type="button" class="btn btn-light" @click="$emit('close')">
              {{ $t("Cancel") }}
          </button>
          <button type="button" class="btn btn-primary" @click="cancel_order" v-bind:disabled="processing == true">
              <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
              {{ $t("Continue") }}
          </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_customer_modal" v-on:close="show_customer_modal = false" ref="customer" id="customer_modal">
      <template v-slot:modal-header>{{ $t('Provide') }} {{ $t('Customer') }} {{ $t('Details') }}</template>
      <template v-slot:modal-body>
        <div class="row mb-2">
          <div class="col-md-6" @click="new_customer = true">
            <button class="btn btn-success btn-md btn-block">{{ $t('New') }} {{ $t('Customer') }}</button>
          </div>
          <div class="col-md-6" @click="new_customer = false">
            <button class="btn btn-warning btn-md btn-block">{{ $t('Existing') }} {{ $t('Customer') }}</button>
          </div>
        </div>

        <div class="form-group" v-show="new_customer == false">
          <label for="customer_slack">{{ $t('Select') }} {{ $t('Customer') }}</label>
          <!--<vue-select  v-model="customer_slack" class="form-control">
                        <option v-for="(customer,index) in customers":key="index" :value="customer.slack">{{ customer.phone }} | {{ customer.email }} </option>
                    </vue-select">-->
          <Select2 v-model="customer_slack" :options="customerOptions" />
        </div>
        <div class="form-group" v-show="new_customer == true">
          <label for="customer_name">{{ $t('Name') }}</label>
          <cool-select
            type="text"
            name="customer_name"
            v-model="customer_name"
            v-validate="'max:100'"
            class=""
            placeholder="Provide Name"
            autocomplete="off"
            :items="customer_list_name"
            item-text="name"
            itemValue="name"
            :resetSearchOnBlur="false"
            ref="customer_name"
            disable-filtering-by-search
            @search="load_customers($event, 'name')"
            @select="set_filter_customer_name($event)"
            :search-text.sync="filter_customer_name"
          >
            <template #item="{ item }">
              <div class="d-flex justify-content-start">
                <div>{{ item.name }} - {{ item.phone }}, {{ item.email }}</div>
              </div>
            </template>
          </cool-select>
          <span v-bind:class="{error: errors.has('customer_name')}">{{ errors.first('customer_name') }}</span>
        </div>
        <div class="form-group" v-show="new_customer == true">
          <label for="customer_number">{{ $t('Contact Number') }}*</label>
          <cool-select
            type="text"
            name="customer_number"
            v-model="customer_number"
            v-validate="'required|min:10'"
            class=""
            placeholder="Provide Contact Number"
            autocomplete="off"
            :items="customer_list_number"
            item-text="phone"
            itemValue="phone"
            :resetSearchOnBlur="false"
            ref="customer_number"
            disable-filtering-by-search
            @search="load_customers($event, 'phone')"
            @select="set_filter_customer_number($event)"
            :search-text.sync="filter_customer_number"
          >
            <template #item="{ item }">
              <div class="d-flex justify-content-start">
                <div>{{ item.name }} - {{ item.phone }}, {{ item.email }}</div>
              </div>
            </template>
          </cool-select>
          <span v-bind:class="{error: errors.has('customer_number')}">{{ errors.first('customer_number') }}</span>
        </div>
        <div class="form-group" v-show="new_customer == true">
          <label for="customer_email">{{ $t('Email') }}</label>
          <cool-select
            type="text"
            name="customer_email"
            v-model="customer_email"
            v-validate="'email'"
            class=""
            placeholder="Provide Email"
            autocomplete="off"
            :items="customer_list_email"
            item-text="email"
            itemValue="email"
            :resetSearchOnBlur="false"
            ref="customer_email"
            disable-filtering-by-search
            @search="load_customers($event, 'email')"
            @select="set_filter_customer_email($event)"
            :search-text.sync="filter_customer_email"
          >
            <template #item="{ item }">
              <div class="d-flex justify-content-start">
                <div>{{ item.name }} - {{ item.email }}, {{ item.phone }}</div>
              </div>
            </template>
          </cool-select>
          <span v-bind:class="{error: errors.has('customer_email')}">{{ errors.first('customer_email') }}</span>
        </div>
      </template>
      <template v-slot:modal-footer>
        <button v-if="partial_paid == false" type="button" class="btn btn-light" v-shortkey="keyboard_shortcuts_formatted.SKIP_CUSTOMER"
          @shortkey="select_customer('skip')" 
          @click="select_customer('skip')"
        >
          {{ $t('Skip') }}
        </button>
        <button
          type="button"
          class="btn btn-primary"
          v-shortkey="keyboard_shortcuts_formatted.PROCEED_CUSTOMER"
          @shortkey="select_customer('proceed')"
          @click="select_customer('proceed')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Proceed') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_partial_payment_modal" v-on:close="show_partial_payment_modal = false" ref="customer" id="partial_paid_modal">
      <template v-slot:modal-header>{{ $t('Partial') }} {{ $t('Payment') }} {{ $t('Details') }}</template>
      <template v-slot:modal-body>

        <div class="form-group" v-show="new_customer == true">
          <label for="customer_number">{{ $t('Partial Pay Amount') }}*</label>
          <input type="number" class="form-control form-control-custom" name="paid_amount" v-model="paid_amount" v-validate="'required'" 
            placeholder="Enter Partial Pay Amount" autocomplete="off" item-text="Partial Pay Amount" ref="paid_amount">
          <span v-bind:class="{error: errors.has('paid_amount')}">{{ errors.first('paid_amount') }}</span>
        </div>
        
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" 
          v-shortkey="keyboard_shortcuts_formatted.CANCEL" @shortkey="$emit('close')" @click="show_partial_payment_modal = false" >
          {{ $t('Cancel') }}
        </button>
        <button type="button" class="btn btn-primary" v-bind:disabled="processing == true"
          v-shortkey="keyboard_shortcuts_formatted.CONTINUE" @shortkey="$emit('submit')" @click="submit_form_data()">
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_creation_modal" v-on:close="show_creation_modal = false" 
      :modal_width="order_restaurant_mode == 1 ? 'modal-container-xl' : ''" ref="restaurant">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
        <p v-show="server_errors" v-html="server_errors" v-bind:class="[error_class]"></p>
        <form data-vv-scope="confirmation_form">
          <div v-if="order_status == 'CLOSE'">
            <div class="form-group">
              <label for="payment_method d-block">{{ $t('Payment Method') }}</label>
              <div class="d-flex flex-wrap">
                <div class="row flex-fill">
                  <div class="col-md-6" v-for="(payment_method_item, index) in payment_methods" v-bind:key="index">
                    <input
                      type="radio"
                      class="check d-none"
                      name="payment_method"
                      v-model="payment_method"
                      v-bind:value="payment_method_item.slack"
                      v-bind:id="'payment_method_check' + index"
                      v-validate="order_status == 'CLOSE' ? 'required' : ''"
                      key="payment_method"
                    />
                    <label
                      class="check-buttons w-100 text-truncate"
                      v-bind:for="'payment_method_check' + index"
                      v-shortkey="{
                        scroll: keyboard_shortcuts_formatted.SCROLL_PAYMENT_METHODS,
                        choose: keyboard_shortcuts_formatted.CHOOSE_PAYMENT_METHOD
                      }"
                      @shortkey="order_confirm_navigate($event, 'payment_method', payment_method_item)"
                      :class="{confirm_focus: index === payment_method_focus}"
                    >
                      {{ payment_method_item.label }}
                    </label>
                  </div>
                </div>
              </div>
              <span
                v-bind:class="{
                  error: errors.has('confirmation_form.payment_method')
                }"
              >
                {{ errors.first('confirmation_form.payment_method') }}
              </span>
            </div>
            <div class="form-group">
              <label for="business_account">{{ $t('Business Account') }}</label>
              <div class="d-flex flex-wrap">
                <div class="row flex-fill">
                  <div
                    class="col-md-6"
                    v-for="(business_account_item, index) in business_accounts"
                    v-bind:value="business_account_item.slack"
                    v-bind:key="index"
                  >
                    <input
                      type="radio"
                      class="check d-none"
                      name="business_account"
                      v-model="business_account"
                      v-bind:value="business_account_item.slack"
                      v-bind:id="'business_account_check' + index"
                      v-validate="order_status == 'CLOSE' ? 'required' : ''"
                      key="business_account"
                    />
                    <label
                      class="check-buttons w-100 text-truncate"
                      v-bind:for="'business_account_check' + index"
                      :class="{
                        confirm_focus: index === business_account_focus
                      }"
                      v-shortkey="{
                        scroll: keyboard_shortcuts_formatted.SCROLL_BUSINESS_ACCOUNTS,
                        choose: keyboard_shortcuts_formatted.CHOOSE_BUSINESS_ACCOUNT
                      }"
                      @shortkey="order_confirm_navigate($event, 'business_account', business_account_item)"
                    >
                      {{ business_account_item.label }} ({{ business_account_item.account_type_label }})
                    </label>
                  </div>
                </div>
              </div>
              <small id="business_account_help" class="form-text text-muted">
                Transaction will be saved under this account
              </small>
              <span
                v-bind:class="{
                  error: errors.has('confirmation_form.business_account')
                }"
              >
                {{ errors.first('confirmation_form.business_account') }}
              </span>
            </div>
          </div>

          <div v-if="order_restaurant_mode == 1">
            <div class="form-group">
              <label for="restaurant_order_type">{{ $t('Order Type') }}</label>
              <div class="d-flex flex-wrap">
                <div class="row flex-fill">
                  <div
                    class="col-md-4"
                    v-for="(restaurant_order_type_item, index) in restaurant_order_types"
                    v-bind:value="restaurant_order_type.order_type_constant"
                    v-bind:key="index"
                  >
                    <input
                      type="radio"
                      class="check d-none"
                      name="restaurant_order_type"
                      v-model="restaurant_order_type"
                      v-bind:value="restaurant_order_type_item.order_type_constant"
                      v-bind:id="'restaurant_order_type_check' + index"
                      v-validate="'required'"
                      v-on:click="set_table_based_on_order_type()"
                      key="restaurant_order_type"
                    />
                    <label
                      class="check-buttons w-100 text-truncate"
                      v-bind:for="'restaurant_order_type_check' + index"
                      :class="{
                        confirm_focus: index === restaurant_order_type_focus
                      }"
                      v-shortkey="{
                        scroll: keyboard_shortcuts_formatted.SCROLL_ORDER_TYPES,
                        choose: keyboard_shortcuts_formatted.CHOOSE_ORDER_TYPE
                      }"
                      @shortkey="order_confirm_navigate($event, 'order_type', restaurant_order_type_item)"
                    >
                      {{ restaurant_order_type_item.label }}
                    </label>
                  </div>
                </div>
              </div>
              <span
                v-bind:class="{
                  error: errors.has('confirmation_form.restaurant_order_type')
                }"
              >
                {{ errors.first('confirmation_form.restaurant_order_type') }}
              </span>
            </div>

            <div class="form-group col-md-6 p-0">
              <label for="waiter">{{ $t('Waiter') }}</label>
              <cool-select
                type="text"
                name="waiter"
                v-model="waiter"
                v-validate=""
                class=""
                placeholder="Choose Waiter"
                autocomplete="off"
                :items="waiter_list"
                item-text="fullname"
                itemValue="slack"
                :resetSearchOnBlur="false"
                disable-filtering-by-search
                @search="load_waiters($event)"
                ref="waiter"
                key="waiter"
              >
                <template #item="{ item }">
                  <div class="d-flex justify-content-start">
                    <div>{{ item.fullname }} - {{ item.user_code }}</div>
                  </div>
                </template>
              </cool-select>
              <span v-bind:class="{error: errors.has('waiter')}">{{ errors.first('waiter') }}</span>
            </div>

            <div class="form-group" v-if="restaurant_order_type == 'DINEIN'" v-bind:class="vacant_tables.length >= 48 ? 'col-md-6 p-0' : ''">
              <label for="restaurant_table">{{ $t('Table') }}</label>

              <div class="d-flex flex-wrap" v-if="vacant_tables.length <= 48">
                <div class="row flex-fill pl-3 pr-3">
                  <div
                    class="col-md-2 p-1 pb-0 mb-0"
                    v-for="(vacant_table, index) in vacant_tables"
                    v-bind:value="vacant_table.slack"
                    v-bind:key="index"
                  >
                    <input
                      type="radio"
                      class="check d-none"
                      name="restaurant_table"
                      v-model="restaurant_table"
                      v-bind:value="vacant_table.slack"
                      v-bind:id="'vacant_table_check' + index"
                      v-validate="'required'"
                      key="restaurant_table"
                    />
                    <label
                      class="check-buttons w-100 text-truncate"
                      v-bind:for="'vacant_table_check' + index"
                      v-shortkey="{
                        scroll: keyboard_shortcuts_formatted.SCROLL_RESTAURANT_TABLES,
                        choose: keyboard_shortcuts_formatted.CHOOSE_RESTAURANT_TABLE
                      }"
                      @shortkey="order_confirm_navigate($event, 'restaurant_table', vacant_table)"
                      :class="{
                        confirm_focus: index === restaurant_table_focus
                      }"
                    >
                      {{ vacant_table.table_number }}
                      <span class="float-right" title="No of Occupants">
                        <i class="fas fa-users text-muted"></i>
                        {{ vacant_table.no_of_occupants }}
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              <select
                name="restaurant_table"
                v-model="restaurant_table"
                v-validate="'required'"
                class="form-control form-control-custom custom-select"
                v-if="vacant_tables.length > 48"
                key="restaurant_table"
              >
                <option value="">{{ $t('Choose Table') }}</option>
                <option v-for="(vacant_table, index) in vacant_tables" v-bind:value="vacant_table.slack" v-bind:key="index">
                  {{ vacant_table.table_number }} ({{ $t('Capacity') }}: {{ vacant_table.no_of_occupants }})
                </option>
              </select>

              <span
                v-bind:class="{
                  error: errors.has('confirmation_form.restaurant_table')
                }"
              >
                {{ errors.first('confirmation_form.restaurant_table') }}
              </span>
            </div>
          </div>
        </form>
        {{ $t('Are you sure you want to proceed?') }}
      </template>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-light"
          v-shortkey="keyboard_shortcuts_formatted.CANCEL"
          @shortkey="$emit('close')"
          @click="$emit('close')"
        >
          {{ $t('Cancel') }}
        </button>
        <button
          type="button"
          class="btn btn-primary"
          v-shortkey="keyboard_shortcuts_formatted.CONTINUE"
          @shortkey="$emit('submit')"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_creation_modal2" v-on:close="show_creation_modal2 = false"
      :modal_width="order_restaurant_mode == 1 ? 'modal-container-xl' : ''" ref="restaurant">
      <template v-slot:modal-header>
        {{ $t('Confirm') }}
      </template>
      <template v-slot:modal-body>
        Are you sure you want to proceed?
      </template>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-light"
          v-shortkey="keyboard_shortcuts_formatted.CANCEL"
          @shortkey="$emit('close')"
          @click="$emit('close')"
        >
          {{ $t('Cancel') }}
        </button>
        <button
          type="button"
          class="btn btn-primary btn-generate-pos"
          v-shortkey="keyboard_shortcuts_formatted.CONTINUE"
          @shortkey="$emit('submit')"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Continue') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_modifier_modal" v-on:close="show_modifier_modal = false"
      :modal_width="order_restaurant_mode == 1 ? 'modal-container-xl' : ''" ref="restaurant">
      <template v-slot:modal-header>
        {{ $t('Select Modifiers') }}
      </template>
      <template v-slot:modal-body>
        <div class="row" v-show="product_modifiers.length" v-for="(product_modifier, index) in product_modifiers" :key="index">
          <div class="col-md-3 text-right text-muted text-uppercase" style="height: 35px;font-size: 15px;padding-top: 7px;">
            <strong>{{ product_modifier.label }} :</strong>
          </div>
          <div class="col-md-9" v-show="product_modifier.modifier_options">
            <div class="d-flex">
              <select
                name="product_modifier_option[]"
                v-model="product_modifier_option[index]"
                class="form-control"
                :multiple="product_modifier.is_multiple == 1"
              >
                <option value="" selected="">--{{ $t('Choose Modifier Option') }}--</option>
                <option
                  v-for="(product_modifier_option, index) in product_modifier.modifier_options"
                  :data-id="index"
                  :value="product_modifier_option.id"
                  :key="index"
                >
                  {{ product_modifier_option.label }} | {{ product_modifier_option.price }} {{ store_currency }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="row" v-if="already_added_product">
          <div class="col-md-2"></div>
          <div class="col-md-10">
            <label for="add_as_new_product">
              <input type="checkbox" id="add_as_new_product" v-model="add_as_new_product" />
              {{ $t('Add as New Product') }}
            </label>
          </div>
        </div>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" v-shortkey="keyboard_shortcuts_formatted.CANCEL" @shortkey="$emit('close')" @click="skipModifier">
          {{ $t('Skip') }}
        </button>
        <button type="button" class="btn btn-primary btn-generate-pos" @click="applyModifier(product_modifier_option, add_as_new_product)">
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Apply') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-show="show_combo_modifier_modal" v-on:close="show_combo_modifier_modal = false"
      :modal_width="order_restaurant_mode == 1 ? 'modal-container-xl' : ''" ref="restaurant">
      <template v-slot:modal-header>
        {{ $t('Select Modifiers') }}
      </template>
      <template v-slot:modal-body>
        <div class="row" v-show="product_modifiers.length" v-for="(product_modifier, index) in combo_product_modifiers" :key="index">
          <div class="col-md-3 text-right text-muted text-uppercase" style="height: 35px;font-size: 15px;padding-top: 7px;">
            Combo: <strong>{{ product_modifier.label }} :</strong>
          </div>
          <div class="col-md-9" v-show="product_modifier.modifier_options">
            <div class="d-flex">
              <select
                name="product_modifier_option[]"
                v-model="product_modifier_option[index]"
                class="form-control"
                :multiple="product_modifier.is_multiple == 1"
              >
                <option value="" selected="">--Choose Modifier Option--</option>
                <option
                  v-for="(product_modifier_option, index) in product_modifier.modifier_options"
                  :data-id="index"
                  :value="product_modifier_option.id"
                  :key="index"
                >
                  {{ product_modifier_option.label }} | {{ product_modifier_option.price }} {{ store_currency }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="row" v-if="already_added_product">
          <div class="col-md-2"></div>
          <div class="col-md-10">
            <label for="add_as_new_product">
              <input type="checkbox" id="add_as_new_product" v-model="add_as_new_product" />
              Add as New Product
            </label>
          </div>
        </div>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" v-shortkey="keyboard_shortcuts_formatted.CANCEL" @shortkey="$emit('close')" @click="skipModifier">
          {{ $t('Skip') }}
        </button>
        <button type="button" class="btn btn-primary btn-generate-pos" @click="applyModifierForCombo(product_modifier_option, add_as_new_product)">
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t('Apply') }}
        </button>
      </template>
    </modalcomponent>

    <modalcomponent v-if="show_close_register_modal" v-on:close="show_close_register_modal = false">
      <template v-slot:modal-header>
        {{ $t('Close Register') }}
      </template>
      <template v-slot:modal-body>
        <p v-html="close_register_server_errors" v-bind:class="[error_class]"></p>
        <form data-vv-scope="close_register_form">
          <div>
            <!-- <div class="form-group">
              <label for="closing_amount">{{ $t("Closing Amount") }}</label>
              <input
                type="number"
                name="closing_amount"
                v-model="closing_amount"
                v-validate="'decimal|min_value:0'"
                class="form-control form-control-custom"
                placeholder="Please provide total cash"
                autocomplete="off"
              />
              <span
                v-bind:class="{
                  error: errors.has('close_register_form.closing_amount'),
                }"
                >{{ errors.first("close_register_form.closing_amount") }}</span
              >
            </div> -->
            <!-- <div class="form-group">
              <label for="register_cash_amount">{{ $t("Total Cash Amount") }}</label>
              <input
                type="number"
                name="register_cash_amount"
                v-model="register_cash_amount"
                v-validate="'decimal|min_value:0'"
                class="form-control form-control-custom"
                placeholder="Please provide total cash amount"
                autocomplete="off"
              />
              <span
                v-bind:class="{
                  error: errors.has('close_register_form.register_cash_amount'),
                }"
                >{{ errors.first("close_register_form.register_cash_amount") }}</span
              >
            </div>
            <div class="form-group">
              <label for="register_credit_amount">{{ $t("Total Credit Amount") }}</label>
              <input
                type="number"
                name="register_credit_amount"
                v-model="register_credit_amount"
                v-validate="'decimal|min_value:0'"
                class="form-control form-control-custom"
                placeholder="Please provide total cash amount"
                autocomplete="off"
              />
              <span
                v-bind:class="{
                  error: errors.has('close_register_form.register_credit_amount'),
                }"
                >{{ errors.first("close_register_form.register_credit_amount") }}</span
              >
            </div> -->
            <div class="form-group">
              <label for="credit_card_slips">{{ $t('Credit') }}</label>
              <input
                type="number"
                name="credit_card_slips"
                v-model="credit_card_slips"
                v-validate="'decimal|min_value:0'"
                class="form-control form-control-custom"
                placeholder="Please provide total credit"
                autocomplete="off"
              />
              <span
                v-bind:class="{
                  error: errors.has('close_register_form.credit_card_slips')
                }"
              >
                {{ errors.first('close_register_form.credit_card_slips') }}
              </span>
            </div>
            <div class="form-group">
              <label for="cheques">{{ $t('Cash') }}</label>
              <input
                type="number"
                name="cheques"
                v-model="cheques"
                v-validate="'decimal|min_value:0'"
                class="form-control form-control-custom"
                placeholder="Please provide total cash"
                autocomplete="off"
              />
              <span
                v-bind:class="{
                  error: errors.has('close_register_form.cheques')
                }"
              >
                {{ errors.first('close_register_form.cheques') }}
              </span>
            </div>
          </div>
        </form>
        {{ $t('Are you sure you want to close register?') }}
      </template>
      <template v-slot:modal-footer>
        <div v-if="register_amount_processing == true">
          <span class="" v-if="register_amount_processing == true">
            <i class="fa fa-circle-notch fa-spin" v-if="register_amount_processing == true"></i>
            Amount loading, please wait..
          </span>
        </div>
        <div v-else>
          <button type="button" class="btn btn-light" @click="$emit('close')">
            {{ $t('Cancel') }}
          </button>
          <button type="button" class="btn btn-primary" @click="$emit('submit')" v-bind:disabled="processing == true">
            <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
            {{ $t('Continue') }}
          </button>
        </div>
      </template>
    </modalcomponent>

    <modalcomponent v-if="show_combo_modal" v-on:close="show_combo_modal = false" :hide_modal_footer="true" :show_footer="false" :modal_width="'w-100 mx-5'" :z_index="'1002'">
      <template v-slot:modal-header>
          <h5>
            {{ $t(selected_combo.combo.name) }}

          </h5>
      </template>
      <template v-slot:modal-body >

        <div class="table-responsive">
        <table class="table table-striped table-condensed table-bordered">
          <tbody>
            <tr>
              <td width="10%" class="text-center text-muted"> Size/Group </td>
              <td class="text-center text-muted" :width=" 90 / selected_combo.combo_groups.length + '%'" style="font-size: 1.3rem;" v-for="(combo_group,combo_group_index) in selected_combo.combo_groups" :key="combo_group_index">{{ combo_group }}</td>
              
            </tr>
            <tr v-for="(combo_size,combo_size_index) in selected_combo.combo.sizes" :key="combo_size_index">
              <td class="text-center text-muted" style="vertical-align: middle;font-size: 1.3rem;"> {{ combo_size.size_name.toUpperCase() }} </td>
              <td class="" v-for="(combo_group,combo_group_index) in selected_combo.combo_groups" :key="combo_group_index">
                <!-- <div class="form-row"> -->
                  <!-- <div class="col-12 p-2" > -->
                    <div class="card  m-1" v-for="(combo_product,combo_product_index) in selected_combo.combo_products[combo_size_index][combo_group_index]" :key="combo_product_index" :class="[ is_active_combo_item(combo_product.slack) ? 'border-1 border-primary shadow-lg text-primary' : '' ]" style="cursor:pointer;" @click="select_combo_product(selected_combo.combo.id,combo_product,combo_size_index,combo_group_index)">
                          <div class="card-body">
                            <h5>
                              {{ combo_product.product.name }}
                            </h5>
                            <p class="text-muted"> 
                              {{ combo_product.quantity }} 
                              <span v-if="combo_product.measurement != null">{{ combo_product.measurement.label }}</span> <span v-else>{{ $t("Unit") }} </span>   
                            </p>
                            <p class="text-muted"> 
                              <span v-if="combo_product.price != combo_product.price_after_discount">
                                <del>SAR {{ combo_product.price }}</del> <span style="color:#007bff !important;font-size:15px;">SAR {{ combo_product.price_after_discount }}</span> 
                              </span> 
                              <span v-else>{{ combo_product.price_after_discount }}</span> 
                                
                            </p>
                        </div>
                    <!-- </div> -->
                  <!-- </div> -->
                </div>
              </td>
            </tr>
            <tr v-if="total_combo_price_including_tax > 0">
              <td :colspan="selected_combo.combo_groups.length + 1" class="text-right">
                Total (Incl. Tax) : <span class="p-3 text-primary" style="font-size:16px;"> <small> SAR</small>  {{ total_combo_price_including_tax.toFixed(2) }} </span>
              </td>
            </tr>
            <tr>
              <td :colspan="selected_combo.combo_groups.length + 1" class="text-right">
                <button @click="add_combo_to_cart" class="btn btn-primary btn-md">{{ $t('Add Combo Into Cart') }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t('Close') }}
        </button>
      </template>
    </modalcomponent>

    <quickpanelcomponent v-show="show_running_order_quickpanel"
      v-on:close-quick-panel="show_running_order_quickpanel = false" :panel_class="'col-md-6 col-xl-3'" >
      <template v-slot:quick-panel-header>{{ $t('Running Orders') }} ({{ running_order_total_records }})</template>
      <template v-slot:quick-panel-body>
        <span class="" v-if="running_order_list_processing == true">
          <i class="fa fa-circle-notch fa-spin" v-if="running_order_list_processing == true"></i>
          Loading..
        </span>

        <div v-show="running_order_list_processing == false">
          <input
            type="text"
            v-model="search_running_list"
            class="form-control form-control-custom mb-3"
            placeholder="Search by order, customer, table.."
            autocomplete="off"
          />

          <div v-if="running_order_list_filtered.length > 0">
            <div
              class="list-item mb-3"
              v-for="(running_order_list_item, index) in running_order_list_filtered"
              v-bind:value="running_order_list_item.slack"
              v-bind:key="index"
              v-on:click="go_to_order(running_order_list_item.edit_link)"
            >
              <div class="d-flex justify-content-between mb-2">
                <div class="mr-auto">
                  <span class="timer-circle bg-light">
                    <span class="timer-dot mr-1"></span>
                    {{ running_order_list_item.duration }} Minute
                  </span>
                  <span class="ml-2">
                    <label for="order">Order</label>
                    #{{ running_order_list_item.order_number }}
                  </span>
                </div>
                <div class="ml-auto">
                  <span v-if="running_order_list_item.kitchen_status != null" v-bind:class="running_order_list_item.kitchen_status.color">
                    {{ running_order_list_item.kitchen_status.label }}
                  </span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4 mb-0">
                  <label for="type">{{ $t('Type') }}</label>
                  <p class="mb-0">{{ running_order_list_item.order_type }}</p>
                </div>
                <div class="form-group col-md-4 mb-0">
                  <label for="table">{{ $t('Table') }}</label>
                  <p class="mb-0">
                    {{ running_order_list_item.table != '' ? running_order_list_item.table : '-' }}
                  </p>
                </div>
                <div class="form-group col-md-4 mb-0">
                  <label for="table">{{ $t('Waiter') }}</label>
                  <p class="mb-0">
                    {{
                      running_order_list_item.waiter_data != null
                        ? running_order_list_item.waiter_data.fullname + ' (' + running_order_list_item.waiter_data.user_code + ')'
                        : '-'
                    }}
                  </p>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-center pl-4 pr-4">
              <span
                class="text-centered btn-label mt-2"
                v-show="running_order_has_more_items"
                v-on:click="get_running_orders_list(running_order_next_page)"
              >
                <span class="" v-if="running_order_list_processing == true">
                  <i class="fa fa-circle-notch fa-spin"></i>
                </span>
                {{ $t('Load More') }}
              </span>
            </div>
          </div>
          <div v-else class="text-muted">There are 0 running orders</div>
        </div>
      </template>
    </quickpanelcomponent>

    <quickpanelcomponent v-show="show_hold_list_modal" v-on:close-quick-panel="show_hold_list_modal = false" :panel_class="'col-md-7 col-xl-4'">
      <template v-slot:quick-panel-header>{{ $t('Hold/Post Paid orders') }} ({{ hold_order_list.length }})</template>
      <template v-slot:quick-panel-body>
        <div class="mt-2 mb-4">
          <label for="">Order Status:</label>
          <select v-model="orders_listing_status" v-on:change="get_hold_list();"
            class="form-control form-control-custom custom-select">
            <option value="2">{{ $t('Hold Orders') }}</option>
            <option value="7">{{ $t('Post Paid Orders') }}</option>
          </select>
        </div>
        <span class="" v-if="hold_list_processing == true">
          <i class="fa fa-circle-notch fa-spin" v-if="hold_list_processing == true"></i>
          Loading..
        </span>

        <div v-show="hold_list_processing == false">
          <input type="text" v-model="search_hold_list" class="form-control form-control-custom mb-3"
            placeholder="Search by order, customer .." autocomplete="off" />

          <div v-if="hold_list_filtered.length > 0">
            <div class="list-item mb-3" v-for="(hold_order_list_item, key, index) in hold_list_filtered"
              v-bind:key="index" >
              <div class="form-row mb-3">
                <div class="form-group col-md-4 mb-0" v-on:click="go_to_order(hold_order_list_item.edit_link)">
                  <label for="type">{{ $t('Order') }}</label>
                  <p class="mb-0">#{{ hold_order_list_item.order_number }}</p>
                </div>
                <div class="form-group col-md-5 mb-0" v-on:click="go_to_order(hold_order_list_item.edit_link)">
                  <label for="time">{{ $t('Time') }}</label>
                  <p class="mb-0">
                    {{ hold_order_list_item.created_at_label }}
                  </p>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <button type="button" v-on:click="show_hold_order_confirm(hold_order_list_item.slack)" v-show="orders_listing_status == 2"
                    class="btn btn-warning" :class="[processing ? 'disabled' : '']" :disabled="processing"> 
                    <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                    {{ $t('Return') }} </button>
                  <a :href="hold_order_list_item.detail_link" target="_blank" class="mr-3">
                    <button type="button" v-show="orders_listing_status == 7"
                    class="btn btn-warning" :class="[processing ? 'disabled' : '']" :disabled="processing"> 
                    <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                    {{ $t('View/Return') }} </button>
                  </a>
                </div>
              </div>
              <div class="form-row mb-3" v-on:click="go_to_order(hold_order_list_item.edit_link)">
                <div class="form-group col-md-4 mb-0">
                  <label for="type">{{ $t('Phone') }}</label>
                  <p class="mb-0">
                    {{ hold_order_list_item.customer_phone != '' ? hold_order_list_item.customer_phone : '-' }}
                  </p>
                </div>
                <div class="form-group col-md-5 mb-0">
                  <label for="table">{{ $t('Email') }}</label>
                  <p class="mb-0 text-truncate">
                    {{ hold_order_list_item.customer_email != '' ? hold_order_list_item.customer_email : '-' }}
                  </p>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <button type="button" v-show="orders_listing_status == 7"
                    v-on:click="showPartialPaymentPopup(hold_order_list_item.slack, hold_order_list_item.transactions, 
                                hold_order_list_item.total_order_amount,hold_order_list_item.currency_code)" 
                    class="btn btn-warning " :class="[processing ? 'disabled' : '']" :disabled="processing"> 
                    <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                    {{ $t('Payment') }} </button>
                </div>
              </div>
              <div class="form-row mb-3" v-show="orders_listing_status == 7">
                <div class="form-group col-md-4 mb-0">
                  <label for="table">{{ $t('Total Amount') }}</label>
                  <p class="mb-0 bold h5">
                    {{ hold_order_list_item.total_order_amount }} {{hold_order_list_item.currency_code}}
                  </p>
                </div>
                <div class="form-group col-md-5 mb-0">
                  <label for="table">{{ $t('Paid Amount') }}</label>
                  <p class="mb-0 bold h5">
                   {{ calculateTransactionAmount(hold_order_list_item.transactions) }} {{hold_order_list_item.currency_code}}
                  </p>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="table">{{ $t('Pending Amount') }}</label>
                  <p class="mb-0 bold h5">
                   {{ calcTransactionPendingAmount(hold_order_list_item.transactions, hold_order_list_item.total_order_amount) }} {{hold_order_list_item.currency_code}}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-muted">{{ $t('There are 0 orders on hold.') }}</div>
        </div>
      </template>
    </quickpanelcomponent>

    <modalcomponent v-if="show_payment_details_modal" v-on:close="show_payment_details_modal = false" :modal_width="'modal-container-md'">
      <template v-slot:modal-header>
          {{ $t("Make Payment") }}
      </template>
      <template v-slot:modal-body>
        <div class="row">
          <div class="col-md-12 mb-3">
            <p v-if="partial_pay_server_errors.length > 0" class="alert alert-danger" v-html="partial_pay_server_errors" v-bind:class="[error_class]"></p>

            <div v-if="partial_pay_pending_amount > 0">
              <div class="form-row mb-2">
                <div class="form-group col-4 text-right">
                  <label for="transaction_date">{{ $t("Total Amount") }} ({{ partial_pay_currency_code }})</label>
                  <div class="text-subtitle">{{ partial_pay_order_total_amount }}</div>
                </div>
                <div class="form-group col-4 text-right">
                  <label for="transaction_date">{{ $t("Paid Amount") }} ({{ partial_pay_currency_code }})</label>
                  <div class="text-subtitle">{{ partial_pay_total_paid_amount }}</div>
                </div>
                <div class="form-group col-4 text-right">
                  <label for="transaction_date">{{ $t("Pending Amount") }} ({{ partial_pay_currency_code }})</label>
                  <div class="text-subtitle">{{ partial_pay_pending_amount }}</div>
                </div>
              </div>

              <div class="form-row mb-2" v-if="partial_payment_details != ''">
                <h5> {{ $t("Payment History") }}</h5>
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
                    <tr v-for="partial_payment_detail in partial_payment_details" :key="partial_payment_detail.id">
                      <td>{{ partial_payment_detail.transaction_date }}</td>
                      <td>{{ partial_payment_detail.amount }}</td>
                      <td>{{ partial_payment_detail.payment_method }}</td>
                      <td>{{ partial_payment_detail.notes }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="form-row mb-2">
                <div class="form-group col-md-6">
                  <label for="amount">{{ $t("Amount") }} ({{ partial_pay_currency_code }})</label>
                  <input type="number" name="amount" v-model="partial_pay_paid_amount" v-validate="`required|decimal|max_value:${partial_pay_pending_amount}`" 
                    class="form-control form-control-custom" autocomplete="off" step="0.01" min="0" />
                  <span v-bind:class="{ error: errors.has('amount') }">{{ errors.first("amount") }}</span>
                </div>
              </div>

              <div class="form-row mb-2">
                <div class="form-group col-md-12">
                  <label for="notes">{{ $t("Notes") }}</label>
                  <textarea name="notes" v-model="partial_pay_notes" v-validate="'max:65535'"
                    class="form-control form-control-custom" rows="3" ></textarea>
                  <span v-bind:class="{ error: errors.has('notes') }">{{errors.first("notes")}}</span>
                </div>
              </div>
            </div>
            <div v-if="partial_pay_pending_amount == 0">
              <p>{{ $t("You have already made the payment(s)") }}.</p>
            </div>
          </div>
        </div>
      </template>
      <template v-slot:modal-footer>
          <button type="button" class="btn btn-light" @click="show_payment_details_modal = false">{{ $t("Cancel") }}</button>
          <button type="button" class="btn btn-primary" @click="submit_partial_payment()" v-bind:disabled="processing == true"> 
            <i class='fa fa-circle-notch fa-spin' v-if="processing == true"></i> 
              <span>{{ $t("Continue") }}</span>
          </button>
      </template>
    </modalcomponent>
    <quickpanelcomponent v-show="show_keyboard_shortcuts_quickpanel"
      v-on:close-quick-panel="show_keyboard_shortcuts_quickpanel = false" :panel_class="'col-md-6 col-xl-3'">
      <template v-slot:quick-panel-header>
        {{ $t('Keyboard Shortcuts') }}
      </template>
      <template v-slot:quick-panel-body>
        <div
          class="d-flex border-bottom"
          v-for="(keyboard_shortcut, key, index) in keyboard_shortcuts"
          v-bind:value="keyboard_shortcut.keyboard_constant"
          v-bind:key="index"
        >
          <div class="p-2 col-4">
            {{ keyboard_shortcut.keyboard_shortcut_label }}
          </div>
          <div class="p-2 col-8">{{ keyboard_shortcut.description }}</div>
        </div>
      </template>
    </quickpanelcomponent>
  </div>
</template>

<script>
'use strict';

import moment from 'moment';
import {CoolSelect} from 'vue-cool-select';
import 'vue-cool-select/dist/themes/bootstrap.css';
import pdf from 'vue-pdf';
import Select2 from 'v-select2-component';
import Printjs from 'print-js';
import number_format from 'locutus/php/strings/number_format';
import VueQrcode from 'vue-qrcode';
import VueBarcodeScanner from 'vue-barcode-scanner';
import _ from 'lodash';

Vue.use(VueBarcodeScanner);

export default {
  components: {
    CoolSelect,
    Select2,
    VueQrcode,
    pdf: pdf
  },
  data() {
    return {
      //change_cash: false,
      //change_credit: false,
      discount_not_applicable_products_list: [],
      server_errors: '',
      partial_pay_server_errors: '',
      error_class: '',
      interval: null,
      cashier_discount_amount: 0,
      cashier_discount_percentage: 0,
      cashier_discount_amount_ids: [],
      cashier_discount_percentage_ids: [],
      processing: false,
      product_processing: false,
      verify_coupon_modal: false,
      register_amount_processing: false,
      close_register_server_errors: '',
      customerOptions: [],
      show_creation_modal: false,
      show_creation_modal2: false,
      return_hold_order_slack: null,
      show_hold_return_modal: false,
      show_customer_modal: false,
      show_hold_list_modal: false,
      show_close_register_modal: false,
      show_running_order_quickpanel: false,
      show_keyboard_shortcuts_quickpanel: false,
      show_action_btn: true,
      store_discount_code: 0,
      store_discount_code_credit: 0,
      new_order_route: this.new_order_link,
      api_link: this.order_data == null ? '/api/add_order' : '/api/update_order/' + this.order_data.slack,
      get_order_receipt_link: '/api/get_order_receipt',
      cancel_order_link: '/api/return_order',
      close_register_api_link: '/api/close_register',
      default_label: 'Walkin Customer',
      current_date_time: moment().format('MMM Do YYYY, h:mm:ss a'),

      customer_label: '-',
      barcode: '',
      product_title: '',
      category: '',
      product_code: '',
      search: '',
      search_barcode: '',

      order_slack: this.order_data == null ? '' : this.order_data.slack,
      order_number: this.order_data == null ? this.next_order_number : this.order_data.order['order_number'],

      sub_total: this.order_data == null ? 0.0 : this.order_data.order['sale_amount_subtotal_excluding_tax'],

      store_level_tax_components: this.store_tax_components,
      tobacco_tax_label: 'Tobacco Tax',
      item_level_total_tobacco_tax_details: [],
      item_level_total_tax_details: [],
      store_level_tax_component_objects: [],
      store_level_total_tax_percentage: this.store_tax_percentage,
      store_level_total_tax_amount: this.order_data == null ? 0.0 : this.order_data.order['store_level_total_tax_amount'],
      product_level_total_tax_amount: this.order_data == null ? 0.0 : this.order_data.order['product_level_total_tax_amount'],
      tax_total: this.order_data == null ? 0.0 : this.order_data.order['tax_total'],

      store_level_total_discount_percentage: this.store_discount_percentage,
      store_level_total_discount_amount: this.order_data == null ? 0.0 : this.order_data.order['store_level_total_discount_amount'],
      product_level_total_discount_amount: this.order_data == null ? 0.0 : this.order_data.order['product_level_total_discount_amount'],
      discount_total: this.order_data == null ? 0.0 : this.order_data.order['discount_amount'],

      additional_discount_percentage: this.order_data == null ? 0.0 : this.order_data.order['additional_discount_percentage'],
      additional_discount_amount: this.order_data == null ? 0.0 : this.order_data.order['additional_discount_amount'],

      total_after_discount: this.order_data == null ? 0.0 : this.order_data.order['total'],
      discounted_sub_total: 0,
      total: this.order_data == null ? 0.0 : this.order_data.order['total'],

      product_list: this.products_data != null ? this.products_data : null,
      customer_name: this.order_data == null ? '' : this.order_data.order['customer_name'],
      customer_number: this.order_data == null ? '' : this.order_data.order['customer_number'],
      customer_email: this.order_data == null ? '' : this.order_data.order['customer_email'],
      order_status: '',
      payment_method: this.order_data == null ? '' : this.order_data.order['payment_method'],
      business_account: this.default_business_account == null ? '' : this.default_business_account.slack,
      waiter: this.order_data == null ? '' : this.order_data.order['waiter'],
      waiter_name: this.order_data == null ? '' : this.order_data.order['waiter_name'],
      cart: this.order_data == null ? {} : this.order_data.cart.length == 0 ? {} : this.order_data.cart,
      cart_deleted_items: [],
      item_count: 0,
      quantity_count: 0,
      discount_code_list: this.store_discount_codes.length > 0 ? this.store_discount_codes : [],
      customer_list_name: [],
      customer_list_number: [],
      customer_list_email: [],
      filter_customer_name: '',
      filter_customer_number: '',
      filter_customer_email: '',

      hold_order_list: [],
      search_hold_list: '',
      hold_list_processing: false,

      running_order_list: [],
      search_running_list: '',
      running_order_list_processing: false,
      running_order_has_more_items: false,
      running_order_total_records: '',
      running_order_next_page: 1,

      //restaurant
      //order_restaurant_mode : (this.order_data == null)?this.store_restaurant_mode:this.order_data.order['restaurant_mode'],
      order_restaurant_mode: this.store_restaurant_mode,
      billing_type: this.order_data != null ? this.order_data.order['billing_type'] : this.store_billing_type ? this.store_billing_type : 'FINE_DINE',

      show_kitchen_modal: false,
      restaurant_order_type: this.order_data == null ? '' : this.order_data.order['order_type'],
      restaurant_table: this.order_data == null ? '' : this.order_data.order['table'],

      restaurant_mode_statuses: ['IN_KITCHEN', 'HOLD', 'PAYMENT_PENDING', 'PAYMENT_FAILED'],

      // closing_amount: 0,
      register_cash_amount: 0,
      register_credit_amount: 0,
      credit_card_slips: 0,
      cheques: 0,

      waiter_list: [],
      billing_type_data: this.billing_type,

      product_focus: null,

      payment_method_focus: null,
      payment_method_focus_mode_rev: false,

      business_account_focus: null,
      business_account_focus_mode_rev: false,

      restaurant_order_type_focus: null,
      restaurant_order_type_focus_mode_rev: false,

      restaurant_table_focus: null,
      restaurant_table_focus_mode_rev: false,
      subcategories: [],
      payment_option: 1,
      store_discount_code_id: this.order_data == null ? null : this.order_data.order['discount_code_id'],
      discount_type: this.order_data == null ? 1 : this.order_data.order['discount_type'],
      discount_rate: 0,
      discount_type_credit: null,
      discount_rate_credit: 0,
      cash_amount: 0,
      change_amount: 0,
      cash_amount2: 0,
      card_name: null,
      credit_amount: 0,
      transaction_id: null,
      transaction_date: new Date().toISOString().substr(0, 10),
      value_date:
        this.order_data == null
          ? this.order_value_date
          : this.order_data.order.value_date != null
          ? this.order_data.order.value_date
          : '',
      order_receipt_pdf: null,
      new_customer: true,
      customer_slack: '',
      this_order_slack: null,
      show_error_message: false,
      show_payment_method: false,
      error_message: '',
      customers: this.customer_data == null ? '' : this.customer_data,
      total_discount_amount: 0,
      search_placeholder: this.$t('Search Product Name or Product Code'),
      search_barcode_placeholder: this.$t('Search by Barcode'),
      show_modifier_modal: false,
      show_combo_modifier_modal: false,
      product_modifiers: [Array, Object],
      combo_product_modifiers: [Array, Object],
      product_modifier: [Array, Object],
      product_modifier_option: [Array, Object],
      selected_product_id: '',
      selected_product_slack: '',
      product_list_item: [Array, Object],
      total_amount: 0,
      specific_prod_total_price_after_discount: 0,
      already_added_product: false,
      add_as_new_product: '',
      qrValue: this.restaurant_url,
      product_list_on_load: [Array, Object],
      load_products_flag: true,
      product_loader: false,
      subcategory_slack: null,
      category_type: null,
      pdf_page_count: 1,
      stcpay_qr_code: '',
      coupon: '',
      processing: false,
      bonat_verify_processing: false,
      modal: false,
      show_modal: false,
      verify_link: '/api/verify_bonat_coupon',
      enter_coupon: this.$t('Please enter bonat coupon'),
      coupon_product_list: [Object, Array],
      active_class: 'cash',
      combos : [],
      show_combo_modal : false,
      selected_combo : [],
      selected_combo_products : null,
      selected_combo_product : null,
      temp_combo_cart : [],
      combo_cart : [],
      total_combo_price_including_tax : 0,
      partial_paid : false,
      enable_partial_paid : true,
      paid_amount: 0,
      show_partial_payment_modal : false,
      orders_listing_status : 2, // by default list Hold Orders
      partial_payment_order_slack: null,
      show_payment_details_modal: false,
      partial_pay_order_total_amount: 0,
      partial_pay_total_paid_amount: 0,
      partial_pay_pending_amount: 0,
      partial_payment_details: '',
      partial_pay_currency_code: '',
      partial_pay_notes: '',
      partial_pay_paid_amount: 0,
    };
  },
  props: {
    main_categories: [Object, Array],
    store_name: String,
    store_discount_codes_cashier: Array,
    counter_name: String,
    counter_slack: String,
    store_tax_components: [Array, Object],
    store_tax_percentage: String,
    store_discount_percentage: String,
    store_discount_codes: [Array, Object],
    payment_methods: Array,
    categories: Array,
    order_data: [Array, Object],
    store_currency: String,
    default_business_account: [Array, Object],
    business_accounts: [Array, Object],
    store_restaurant_mode: Boolean,
    restaurant_order_types: [Array, Object],
    vacant_tables: [Array, Object],
    new_order_link: String,
    billing_types: [Array, Object],
    store_billing_type: String,
    store_waiter_role_slack: String,
    keyboard_shortcuts: [Array, Object],
    keyboard_shortcuts_formatted: [Array, Object],
    enable_customer_detail_popup: Boolean,
    customer_data: [Array, Object],
    send_to_kitchen_access: false,
    can_gift_access: false,
    can_hold_access: false,
    base_url: String,
    session_currency_code: String,
    modifier_data: [Array, Object],
    restaurant_url: null,
    merchant_id: {
      type: [String, Number],
      value: 0
    },
    products_data: [Object, Array],
    products_counter: [Object, Array],
    next_order_number: Number,
    store_tax_code_id: Number,
    store_tax_label: String,
    order_value_date: String,
    locale: String,
  },
  filters: {
    truncate: function(value, limit) {
      if (!value) return '';
      if (value.length > limit) {
        value = value.substring(0, limit - 3) + '...';
      }
      return value;
    },
    formatNumber: function(value) {
      return value.toFixed(2);
    }
  },
  watch: {
    // show_combo_modifier_modal(val){
    //   // this.combo_item_reset_active_class();
    // },
    store_discount_code: function(val) {
      console.log('this.store_discount_code vallll watch ==',val);
      console.log('this.store_discount_code watch ==',this.store_discount_code);
      this.discount_rate = this.store_discount_code;
      if(this.store_discount_code == 0 && this.order_data != null && this.store_discount_codes_cashier.length > 0){
         if(this.store_discount_codes_cashier[0].discount_type == 'percentage'){
            this.discount_rate = parseFloat(this.store_discount_codes_cashier[0].discount_percentage);
            this.discount_type = 2;
         }else{
            this.discount_rate = parseFloat(this.store_discount_codes_cashier[0].discount_value);
            this.discount_type = 1;
         }
      }
      this.store_discount_code_id = $('#store_discount_code :selected').attr('data-discountid');
      if(this.store_discount_code_id == undefined && this.store_discount_codes_cashier.length > 0){
        this.store_discount_code_id = this.store_discount_codes_cashier[0].id;
      }
      console.log('this.store_discount_code_id watch ==',this.store_discount_code_id);
      if (Object.keys(this.cart).length > 0) {
        this.update_prices();
      }

    },
    store_discount_code_credit: function() {
      this.discount_rate_credit = this.store_discount_code_credit;
      this.update_prices();
    },
    discount_rate: function(val) {
      if (Object.keys(this.cart).length > 0) {
        this.update_prices();
      }
    }
  },
  computed: {
    hold_list_filtered() {
      if (this.search_hold_list) {
        return this.hold_order_list.filter(hold_order_list_item => {
          return this.search_hold_list
            .toLowerCase()
            .split(' ')
            .every(
              v =>
                hold_order_list_item.order_number.toLowerCase().includes(v) ||
                hold_order_list_item.customer_phone.toLowerCase().includes(v) ||
                hold_order_list_item.customer_email.toLowerCase().includes(v)
            );
        });
      } else {
        return this.hold_order_list;
      }
    },
    running_order_list_filtered() {
      if (this.search_running_list) {
        return this.running_order_list.filter(running_order_list_item => {
          return this.search_running_list
            .toLowerCase()
            .split(' ')
            .every(
              v =>
                running_order_list_item.order_number.toLowerCase().includes(v) ||
                running_order_list_item.customer_phone.toLowerCase().includes(v) ||
                running_order_list_item.customer_email.toLowerCase().includes(v) ||
                running_order_list_item.order_type.toLowerCase().includes(v) ||
                running_order_list_item.table.toLowerCase().includes(v) ||
                (running_order_list_item.waiter_data != null ? running_order_list_item.waiter_data.fullname.toLowerCase().includes(v) : '') ||
                (running_order_list_item.waiter_data != null ? running_order_list_item.waiter_data.user_code.toLowerCase().includes(v) : '')
            );
        });
      } else {
        return this.running_order_list;
      }
    }
  },
  mounted() {
    localStorage.removeItem('bonat_coupon_set');
    console.log('order_data mounted == ',this.order_data);
    console.log('store_discount_codes_cashier mounted == ',this.store_discount_codes_cashier[0]);
    // console.log('products_data== ',this.products_data[0]['tax_code']['tax_components'][0]['tax_name_id']);
    //this.run_clock();
    // this.tick_update_duration_for_products();
    if (this.order_data !== null) {
      this.update_customer();
      if (typeof this.$refs.waiter != 'undefined') {
        this.$refs.waiter.setSearchData(this.waiter_name);
      }
      console.log('mounted' );
      console.log('Cart on load =',this.cart );
      
      //var store_discount_code_id = this.order_data.order['discount_code_id'];
      
      var app_obj = this;
      
      if(this.additional_discount_percentage > 0 || this.additional_discount_amount > 0){
        if(this.discount_type == 2){
          this.discount_rate = this.additional_discount_percentage;
        }else{
          this.discount_rate = this.additional_discount_amount;
        }
        console.log('this.store_discount_code_id ===', this.store_discount_code_id);
        if(this.store_discount_code_id != 0){
          var disc_code_select_flag = 0;
          $('#store_discount_code option').each(function(){
              console.log( app_obj.store_discount_code_id, ' == ', $(this).attr('data-discountid'));
              if ( app_obj.store_discount_code_id == $(this).attr('data-discountid')) {
                  $(this).attr('selected', 'selected');
                  // $(this)[0].selected = true;
                  disc_code_select_flag = 1;
              }
          });  
         // this.store_discount_code = this.discount_rate; 
          if(disc_code_select_flag == 0){
            $("#store_discount_code option[value='0']").attr('selected', 'selected');
            // $("#store_discount_code option[value='0']")[0].selected = true;
          }
        }

      }
      $('#discount_rate').val(this.discount_rate);
      this.update_prices();

    } else {
      // this.show_customer_modal = (this.enable_customer_detail_popup != null)?this.enable_customer_detail_popup:true;
      // if(this.show_customer_modal == false){
      //     this.select_customer('skip');
      // }
      let store_level_tax_component_objects = [];
      var this_scope = this;  
      $.each(this.item_level_total_tax_details, function(tax_component_key, tax_component) {
        let tax_obj = {
          tax_type: this.store_tax_label,
          tax_percentage: 0,
          tax_amount: 0.0
        };
        store_level_tax_component_objects.push(tax_obj);
      });
      this.store_level_tax_component_objects = store_level_tax_component_objects;
    }
    // this.loadSubcategories(0);
      
    
  },
  created() {
    console.log('order_data created == ',this.order_data);
    this.loadSubcategories(0);
    this.error_message = this.$t(this.error_message);
    if (this.order_data !== null) {
      var order_curr_status = this.order_data.order.current_status.value_constant;
      //if order is already closed, load new order page
      if (this.order_data != null && (order_curr_status == 'CLOSED' || order_curr_status == 'RETURN')) {
        // console.log('Order is already closed!');
        window.location.href = this.new_order_route;
      }
    }
    // this.product_info_form(0);
    this.customers.forEach((customer, index) => {
      this.customerOptions.push({
        id: customer.slack,
        text: customer.phone + '  | ' + customer.email
      });
    });

    // this.product_info_form(0);
    this.$barcodeScanner.init(this.onBarcodeScanned);    
  },
  destroyed() {
    this.$barcodeScanner.destroy();
    // if (this.interval != null) {
    //   clearInterval(this.interval);
    // }
  },
  filters: {
    roundDecimal: function(value) {
      // return number_format(value, 2, ".", "");
      return Number(parseFloat(value).toFixed(2)).toLocaleString();
    },
    formatDecimal: function(value) {
      var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
      return Number(value.toString().match(re)[0]).toLocaleString();
      // return parseFloat(value).toFixed(2);
    }
  },
  methods: {

    select_combo(combo){
        // this.remove_combo_from_cart(combo.combo.id);
        this.selected_combo = combo;
        this.show_combo_modal = true;
        this.total_combo_price_including_tax = 0;
        // this.combo_item_reset_active_class();
    },
    select_combo_product(combo_id,combo_product,size_index,group_index){
      
      let product = combo_product.product;
      product.tax_percentage = (product.tax_code != null) ? product.tax_code.total_tax_percentage : 0;
      product.tax_code_label = (product.tax_code != null) ? product.tax_code.label : '';
      product.sale_amount_including_tax = parseFloat(combo_product.price_after_discount);
      product.sale_amount_excluding_tax = parseFloat(this.calculateSalePrice(product.sale_amount_including_tax,product.tax_percentage));
      
      product.is_combo_product = true;
      product.combo_id = combo_id;

      // search and remove existing group item in case of new item is added
      this.temp_combo_cart = this.temp_combo_cart.filter(function( obj ) {
        return obj.index !== group_index;
      });
      
      this.selected_combo_product = product;
      
      let temp_combo_cart_item = {
        slack : combo_product.slack,
        index : group_index,
        combo_id : combo_id,
        product : this.selected_combo_product,
        product_quantity : combo_product.quantity,
        product_measurement : combo_product.measurement,
      };
      
      this.temp_combo_cart.push(temp_combo_cart_item);
      
      this.total_combo_price_including_tax = 0;

      if(product.product_modifiers.length > 0){
        this.selectModifierForCombo(product);
      }

      this.update_combo_price();

    },
    add_combo_to_cart(){
      
      var combo_id = ''; 
      var combo_name = ''; 
      var combo_name = ''; 
      var combo_cart_id = this.uniqueCartId();

      this.temp_combo_cart.forEach( (value,index) => {
        
        let combo = this.combos.find( combo => combo.combo.id == value.combo_id );
        combo_id = combo.combo.id;
        combo_name = combo.combo.name;
        
        this.add_as_new_product = true;
        value.product.combo_quantity = value.product_quantity;
        value.product.product_measurement = value.product_measurement;
        value.product.combo_cart_id = combo_cart_id;
        this.add_to_cart(value.product);

      });
      
      let combo_cart_item = {
        combo_cart_id : combo_cart_id,
        combo_id : combo_id,
        combo_name : combo_name,
        combo_price_including_tax : this.total_combo_price_including_tax,
        products : this.temp_combo_cart,
      };

      this.combo_cart.push(combo_cart_item);

      this.show_combo_modal = false;
      this.temp_combo_cart = [];
      this.selected_combo_product = null;

    },
    is_active_combo_item(combo_item_slack){
      
      var flag = false;
      this.temp_combo_cart.forEach( (cart_item) => {
          if(cart_item.slack == combo_item_slack){
            flag = true;
          }
      });

      return flag;

    },
    remove_combo_from_cart(combo_cart_id){
      var combo_cart_id = combo_cart_id;
      this.combo_cart.forEach( combo => {
        combo.products.forEach( product => {
          // console.log(product,'combo product');
          if( this.cart[product.product.cart_id] !== undefined && this.cart[product.product.cart_id].combo_cart_id == combo_cart_id){
            Vue.delete(this.cart,product.product.cart_id);
          }
        });
      });
      this.combo_cart = this.combo_cart.filter( item => item.combo_cart_id != combo_cart_id );
      this.update_prices();
    },
    update_combo_price(){
      
      this.total_combo_price_including_tax = 0;

      this.temp_combo_cart.forEach( (value) => {
          if(value.product.modifier_price != undefined){
            this.total_combo_price_including_tax += parseFloat(value.product.sale_amount_including_tax) + parseFloat(value.product.modifier_price);
          }else{
            this.total_combo_price_including_tax += parseFloat(value.product.sale_amount_including_tax);
          }
      });
    },
    tolower(str){
      return str.toLowerCase();
    },
    submit_form() {
      
      this.$off('submit');
      this.$off('close');

      this.$validator.validateAll().then(result => {
        if (result) {
          if (this.reload_on_submit) {
            this.show_modal = true;

            this.$on('submit', function() {
              // Submit form data
              this.form_data();
            });

            this.$on('close', function() {
              this.show_modal = false;
            });
          } else {
            // request coming from other component
            this.form_data();
          }
        }
      });
    },

    form_data() {
      this.bonat_verify_processing = true;
      var formData = new FormData();
    
      formData.append('access_token', window.settings.access_token);
      formData.append('coupon', this.coupon == null ? '' : this.coupon);
      formData.append('counter_slack', this.counter_slack == null ? '' : this.counter_slack);

      var bonat_coupon_used = localStorage.getItem('bonat_coupon_set');
      if (bonat_coupon_used == 'true') {
        var msg = 'Bonat coupon already used!';
        this.server_errors = msg;
        this.error_class = 'error';
      }

      axios
        .post(this.verify_link, formData)
        .then(response => {
          if (response.data.status_code == 200) {
            this.bonat_verify_processing = false;
            this.show_response_message(response.data.msg, 'SUCCESS');

            this.coupon_product_list = response.data.data.products_data;

            if (this.coupon_product_list) {
              this.coupon_product_list.forEach(item => {
                this.add_to_bonat_cart(item);
              });
            }

            this.verify_coupon_modal = false;

            this.store_discount_code = 0;
          } else {
            this.bonat_verify_processing = false;
            this.processing = false;
            this.show_modal = false;
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
            }
            this.error_class = 'error';
          }
        })
        .catch(error => {
          console.log(error);
        });
    },

    isNumber: function(evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    handleDecimal(e) {
      let stringValue = this.discount_rate;
      let regex = /^\d*(\.\d{1,2})?$/;
      if (!stringValue.match(regex)) {
        this.discount_rate = this.discount_rate;
      }

      this.discount_rate = this.discount_rate;
    },
    myChangeEvent(val) {
      //  console.log(val);
    },
    mySelectEvent({id, text}) {
      //  console.log({ id, text });
    },
    loadSubcategories(catetgory_id) {
      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('catetgory_id', catetgory_id);
      this.parent_category_id = catetgory_id;
      axios
        .post('/api/load_subcategories', formData)
        .then(response => {
          this.subcategories = response.data;
        })
        .catch(error => {
          console.log(error);
        });

      // alert(this.parent_category_id);
      this.setSubcategory(this.parent_category_id, 'PARENT');

      // this.product_all_info_form(this.parent_category_id);
    },

    // product_all_info_form(parent_category_id) {
    //   this.product_processing = true;
    //   var formData = new FormData();

    //   formData.append("access_token", window.settings.access_token);
    //   formData.append("parent_category_id", parent_category_id);
    //   // formData.append("product_title", this.product_title);
    //   // formData.append("product_code", this.product_code);
    //   //  formData.append("barcode", this.barcode);
    //   formData.append("product_category", 0);

    //   axios
    //     .post("/api/get_product", formData)
    //     .then((response) => {
    //       this.product_processing = false;
    //       this.product_list = response.data.data;
    //       this.product_list_on_load = this.product_list;

    //       // console.log('product list');
    //       // console.log(this.product_list);

    //       // alert(0);

    //       // if (this.product_list.length == 1 && response.data.type == 2) {
    //       //   if(this.product_list[0].product_modifiers.length > 0){
    //       //     this.selectModifier(this.product_list[0]);
    //       //   }else{
    //       //     this.add_to_cart(this.product_list[0]);
    //       //   }
    //       // }

    //       // console.log("product_list");
    //       // console.log(this.product_list);

    //       // return false;
    //       // if (this.barcode != "" && this.product_list.length == 1) {
    //       //   this.add_to_cart(this.product_list[0]);
    //       //   //this.barcode = "";
    //       // }
    //     })
    //     .catch((error) => {
    //       console.log(error);
    //     });
    // },

    load_products_by_subcategory(subcategory_slack) {
      this.product_processing = true;
      var formData = new FormData();

      formData.append('access_token', window.settings.access_token);
      formData.append('subcategory_slack', subcategory_slack);
      formData.append('price_id', this.price_id);
      this.products_counter.offset = 0;
      formData.append('products_counter', JSON.stringify(this.products_counter));

      axios
        .post('/api/load_pos_products_by_subcategory', formData)
        .then(response => {
          
          // this.product_processing = false;
          this.product_list = response.data.data.products_data;
          // this.product_list_on_load = this.product_list;

          // if (this.product_list.length == 1 && response.data.type == 2) {
          //   if(this.product_list[0].product_modifiers.length > 0){
          //     this.selectModifier(this.product_list[0]);
          //   }else{
          //     this.add_to_cart(this.product_list[0]);
          //   }
          // }
        })
        .catch(error => {
          console.log(error);
        });
    },

    // product_info_form(category) {

    //   this.product_processing = true;
    //   var formData = new FormData();

    //   formData.append("access_token", window.settings.access_token);
    //   formData.append("product_title", this.product_title);
    //   formData.append("product_code", this.product_code);
    //   formData.append("barcode", this.barcode);
    //   formData.append("product_category", category);
    //   formData.append("search", this.search);

    //   axios
    //     .post("/api/get_product", formData)
    //     .then((response) => {
    //       this.product_processing = false;
    //       this.product_list = response.data.data;
    //       this.product_list_on_load = this.product_list;

    //       if (this.product_list.length == 1 && response.data.type == 2) {
    //         if(this.product_list[0].product_modifiers.length > 0){
    //           this.selectModifier(this.product_list[0]);
    //         }else{
    //           this.add_to_cart(this.product_list[0]);
    //         }
    //       }

    //     })
    //     .catch((error) => {
    //       console.log(error);
    //     });
    // },

    add_to_bonat_cart(product) {
      let already_scanned = false;
      let already_scanned_cart_id = '';

      var bonat_coupon_used = localStorage.getItem('bonat_coupon_set');
      if (bonat_coupon_used == 'true') {
        return false;
      } else {
        localStorage.setItem('bonat_coupon_set', 'true');
      }


      if (Object.keys(this.cart).length > 0) {
        for (const item in this.cart) {
          if (this.cart[item].product_slack == product.slack) {
            already_scanned = true;
            already_scanned_cart_id = item;
          }
        }
      }

      product.cart_id = this.uniqueCartId();

      if ((this.cart[product.slack] != undefined && this.cart[product.slack].is_low_on_ingredient == 1) || product.ingredient_low_stock == 1) {
        this.show_error_message = true;
        this.error_message = product.name + ' ' + this.$t('is low in ingredient, please add some stock first');
        return false;
      } else {
        if (product.quantity == '0.00') {
          this.show_error_message = true;
          this.error_message = product.name + ' ' + this.$t('is out of stock now!');
          return false;
        }
        if (this.cart[product.slack] != 'undefined' && quantity > product.quantity && product.quantity != '-1.00') {
          this.show_error_message = true;
          this.error_message = product.name + ' ' + this.$t('is out of stock now!');
          return false;
        }
      }

      this.show_error_message = false;

      var tax_percentage = product.tax_percentage != null ? parseFloat(product.tax_percentage) : 0;
      var discount_percentage = product.discount_code != null ? parseFloat(product.discount_code.discount_percentage) : 0;
      var discount_value = product.discount_code != null ? parseFloat(product.discount_code.discount_value) : 0;
      var discount_type = product.discount_code != null ? product.discount_code.discount_type : '';
      var quantity = this.cart[product.cart_id] != null ? parseFloat(this.cart[product.cart_id].quantity) + 1 : 1;
      var total_price = parseFloat(quantity) * parseFloat(product.sale_amount_excluding_tax);
      var modifiers = [];
      var modifier_amount = 0;
      if (product.modifiers != undefined) {
        modifiers = product.modifiers;
        modifier_amount = modifiers.reduce((acc, item) => acc + parseFloat(item.price), 0);
      }

      var bonat_discount = false;
      if (product.bonat_discount != undefined) {
        bonat_discount = product.bonat_discount;
      }

      var bonat_price = 0;
      if (product.bonat_price != undefined) {
        bonat_price = product.bonat_price;
      }

      var bonat_coupon = '';

      if (product.bonat_coupon != undefined) {
        bonat_coupon = product.bonat_coupon;
      }

      let price = parseFloat(product.sale_amount_excluding_tax) + parseFloat(modifier_amount);

      var discount_percentage =
        product.discount_code != null && product.discount_code.discount_percentage != null
          ? parseFloat(product.discount_code.discount_percentage)
          : 0;
      var discount_value =
        product.discount_code != null && product.discount_code.discount_value != null ? parseFloat(product.discount_code.discount_value) : 0;
      var discount_type = product.discount_code != null ? product.discount_code.discount_type : '';
      var product_data = {
        product_slack: product.slack,
        cart_id: product.cart_id,
        product_code: product.product_code,
        name: product.name,
        price: price,
        modifier_amount: parseFloat(modifier_amount),
        is_taxable: product.is_taxable,
        quantity: quantity,
        tax_percentage: tax_percentage != null ? tax_percentage : 0.0,
        discount_percentage: discount_percentage,
        discount_type: discount_type,
        discount_value: discount_value,
        total_price: parseFloat(total_price),
        image: product.product_thumb_image,
        product_border_color: product.product_border_color,
        is_low_on_ingredient: '',
        modifiers: modifiers,
        note: '',
        note_flag: false,
        gift_flag: false,
        already_scanned: already_scanned,
        bonat_discount: bonat_discount,
        bonat_price: bonat_price,
        bonat_coupon: bonat_coupon
      };

      this.$set(this.cart, product.cart_id, product_data);
      this.update_prices();
    },

    checkForDiscountNotApplicableProducts()
    {
      for(let cart_id in this.cart)
      {
        if((typeof($('#store_discount_code').find('option:selected').data("discountid"))!=="undefined")||(typeof($('#store_discount_code_credit').find('option:selected').data("discountid"))!=="undefined"))
        {
          if(typeof($('#store_discount_code').find('option:selected').data("discountid"))!=="undefined")
          {
            if(this.retrieveCodeDiscount(this.cart[cart_id].product_id,$('#store_discount_code').find('option:selected').data("discountid")).length==0)
            {
                this.discount_not_applicable_products_list.push(this.cart[cart_id]);
            }
          }
          if(typeof($('#store_discount_code_credit').find('option:selected').data("discountid"))!=="undefined")
          {
            if(this.retrieveCodeDiscount(this.cart[cart_id].product_id,$('#store_discount_code_credit').find('option:selected').data("discountid")).length==0)
            {
              this.discount_not_applicable_products_list.push(this.cart[cart_id]);
            }
          }
        }
      }
      // console.log("Discount Not applicable Products List : "+this.discount_not_applicable_products_list);
    },
    add_to_cart(product) {
      // console.log('product =', product);

      //console.log('product_tax =',product['tax_code']['tax_components'][0]['tax_name_id']);
      let already_scanned = false;
      let already_scanned_cart_id = '';

      if (Object.keys(this.cart).length > 0) {
        for (const item in this.cart) {
          if (this.cart[item].product_slack == product.slack) {
            already_scanned = true;
            already_scanned_cart_id = item;
          }
        }
      }
      
      if(product.is_combo_product != undefined && product.is_combo_product ){
      
        product.cart_id = this.uniqueCartId();
      
      }else{

        var item_exist_in_combo = false;
        this.combo_cart.forEach( (cart_item) => {
          cart_item.products.forEach( (item) => {
            if(item.product.slack == product.slack){
              item_exist_in_combo = true;
            }
          });
        });

        if (already_scanned) {

          if ( item_exist_in_combo || (this.add_as_new_product && product.modifiers != undefined) ) {
            product.cart_id = this.uniqueCartId();
          } else {
            // console.log('in');
            product.cart_id = already_scanned_cart_id;
            this.add_as_new_product = false;
            var quantity = this.cart[product.cart_id].quantity + 1; // needed to check
            this.check_cart_ingredient_stock(product.product_slack, product.id, quantity);
          }
        } else {
          product.cart_id = this.uniqueCartId();
        }

      }

      // if(typeof this.cart[product.cart_id] == 'undefined' || this.add_as_new_product){
      //   product.cart_id = this.uniqueCartId();
      // }else{
      //   this.add_as_new_product = false;
      //   var quantity = this.cart[product.cart_id].quantity + 1; // needed to check
      //   this.check_cart_ingredient_stock(product.product_slack, product.id, quantity);
      // }

      if ((this.cart[product.slack] != undefined && this.cart[product.slack].is_low_on_ingredient == 1) || product.ingredient_low_stock == 1) {
        this.show_error_message = true;
        this.error_message = product.name + ' ' + this.$t('is low in ingredient, please add some stock first');
        return false;
      } else {
        if (product.quantity == '0.00') {
          this.show_error_message = true;
          this.error_message = product.name + ' ' + this.$t('is out of stock now!');
          return false;
        }
        if (this.cart[product.slack] != 'undefined' && quantity > product.quantity && product.quantity != '-1.00') {
          this.show_error_message = true;
          this.error_message = product.name + ' ' + this.$t('is out of stock now!');
          return false;
        }
      }
      this.show_error_message = false;
      var app_obj = this;
      var tax_code_details = [];
      var tobacco_tax_code_details = [];
      if (product.is_tobacco_tax > 0) {
        if (app_obj.item_level_total_tobacco_tax_details == undefined) {
          app_obj.item_level_total_tobacco_tax_details = [];
        }
        app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax] = product.is_tobacco_tax;
        if (app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax]['item_tax_percentage'] == undefined) {
          app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax] = [];
        }
        app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax]['item_tax_percentage'] = product.tobacco_tax_percentage;
        app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax]['item_tax_label'] = app_obj.tobacco_tax_label;
        app_obj.item_level_total_tobacco_tax_details[product.is_tobacco_tax]['item_tax_total'] = 0;
        tobacco_tax_code_details.push({
          tax_type: app_obj.tobacco_tax_label,
          tax_name_id: 0,
          tax_percentage: product.tobacco_tax_percentage,
          tax_amount: 0
        });
      }

      if (product.tax_code_id > 0) {
        var tax_percentage = parseFloat(product.tax_percentage);

        if (product['tax_code']['tax_components'].length > 0) {
          $.each(product['tax_code']['tax_components'], function(tax_key, tax_detail) {
            tax_code_details.push({
              tax_type: tax_detail.tax_type,
              tax_name_id: tax_detail.tax_name_id,
              tax_percentage: tax_detail.tax_percentage,
              tax_amount: 0
            });

            var tax_name_id = tax_detail.tax_name_id;
            if (app_obj.item_level_total_tax_details == undefined) {
              app_obj.item_level_total_tax_details = [];
            }

            app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = tax_detail.tax_name_id;
            if (app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] == undefined) {
              app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = [];
            }

            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] = tax_detail.tax_percentage;
            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_label'] = tax_detail.tax_type;
            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_total'] = 0;
          });
        }
      } else {
        this.item_level_total_tax_details[this.store_tax_code_id] = this.store_tax_code_id;
        if (this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_percentage'] == undefined) {
          this.item_level_total_tax_details[this.store_tax_code_id] = [];
        }
        var tax_percentage = parseFloat(this.store_tax_percentage);
        this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_percentage'] = this.store_tax_percentage;
        this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_label'] = this.store_tax_label;
        this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_total'] = 0;
      }
      // var tax_percentage = product.tax_code != null ? parseFloat(product.tax_code.total_tax_percentage) : 0;
      var discount_percentage =
        product.discount_code != null && product.discount_code.discount_percentage != null
          ? parseFloat(product.discount_code.discount_percentage)
          : 0;
      var discount_value =
        product.discount_code != null && product.discount_code.discount_value != null ? parseFloat(product.discount_code.discount_value) : 0;
      var discount_type = product.discount_code != null ? product.discount_code.discount_type : '';
      
      // console.log(product,'product cart');

      if(product.is_combo_product){
        var quantity = 1;
      }else{
        var quantity = this.cart[product.cart_id] != null ? parseFloat(this.cart[product.cart_id].quantity) + 1 : 1;
      }
      // console.log('qty');

      var total_price = parseFloat(quantity) * parseFloat(product.sale_amount_excluding_tax);
      var modifiers = [];
      var modifier_amount = 0;
      if (product.modifiers != undefined) {
        modifiers = product.modifiers;
        // console.log('modifiers ====', modifiers);
        modifier_amount = modifiers.reduce((acc, item) => acc + parseFloat(item.price), 0);
      }

      var bonat_discount = false;
      if (product.bonat_discount != undefined) {
        bonat_discount = product.bonat_discount;
      }

      var bonat_price = 0;
      if (product.bonat_price != undefined) {
        bonat_price = product.bonat_price;
      }

      var bonat_coupon = '';

      if (product.bonat_coupon != undefined) {
        bonat_coupon = product.bonat_coupon;
      }

      /*if (discountcodelist_inventory.length > 0) {
        for (let i = 0; i < discountcodelist_inventory.length; i++) {
          if (discountcodelist_inventory[i].discount_type == "amount") {
            product.sale_amount_excluding_tax =
              parseFloat(product.sale_amount_excluding_tax) -
              discountcodelist_inventory[i].discount_value;
          } else {
            product.sale_amount_excluding_tax =
              parseFloat(product.sale_amount_excluding_tax) -
              product.sale_amount_excluding_tax *
                discountcodelist_inventory[i].discount_percentage *
                0.01;
          }
        }
      }*/

      let price = parseFloat(product.sale_amount_excluding_tax);
      if(isNaN(price)){
        price = 0;
      }
      var modifier_price = 0;
      var price_with_modifier = price;
      if (product.modifier_price > 0) {
        modifier_price = this.calculate_reverse_percent_amount(product.modifier_price, tax_percentage);
        if (product.is_tobacco_tax > 0) {
          modifier_price = this.calculate_reverse_percent_amount(modifier_price, product.tobacco_tax_percentage);
        }
        price_with_modifier = price + parseFloat(modifier_price);
      }
      // console.log('item price with modifier ==', price_with_modifier);

      let tax_include_price = parseFloat(product.sale_amount_including_tax);

      var product_data = {
        product_id: product.id,
        product_category: product.category_id,
        product_sub_category: product.subcategory_id,
        product_slack: product.slack,
        cart_id: product.cart_id,
        product_code: product.product_code,
        name: product.name,
        name_ar: product.name_ar,
        price: price,
        price_actual: price,
        price_with_modifier: price_with_modifier,
        price_with_modifier_actual: price_with_modifier,
        price_type: product.price_type,
        tax_include_price: tax_include_price,
        modifier_amount: parseFloat(modifier_amount),
        modifier_price: parseFloat(modifier_price),
        is_taxable: product.is_taxable,
        quantity: quantity,
        is_tobacco_tax: product.is_tobacco_tax,
        tobacco_tax_percentage: product.tobacco_tax_percentage,
        tax_code_details: tax_code_details,
        tobacco_tax_code_details: tobacco_tax_code_details,
        tax_code_id: product.tax_code_id,
        tax_code_label: product.tax_code_label,
        tax_percentage: tax_percentage != null ? parseFloat(tax_percentage) : 0.0,
        discount_percentage: discount_percentage != null ? discount_percentage : 0.0,
        discount_code_id: product.discount_code_id,
        discount_type: discount_type,
        discount_value: discount_value,
        additional_discount_percentage: 0,
        additional_discount_type: 0,
        additional_discount_value: 0,
        additional_discount_code: null,
        total_price: parseFloat(total_price),
        image: product.product_thumb_image,
        product_border_color: product.product_border_color,
        is_low_on_ingredient: '',
        modifiers: modifiers,
        note: '',
        note_flag: false,
        gift_flag: false,
        already_scanned: already_scanned,
        bonat_discount: bonat_discount,
        bonat_price: bonat_price,
        bonat_coupon: bonat_coupon,
        is_combo_product: (product.is_combo_product != 'undefined') ? product.is_combo_product : false,
        combo_id: (product.combo_id != 'undefined') ? product.combo_id : '',
        combo_cart_id: (product.combo_cart_id != 'undefined') ? product.combo_cart_id : '',
        product_measurement: (product.product_measurement != 'undefined') ? product.product_measurement : [],
        combo_quantity: (product.combo_quantity != 'undefined') ? product.combo_quantity : 1,
      };

      // this.checkForDiscountNotApplicableProducts(); 
      //var prod_disc_status = this.checkIfDiscountApplicable();
      if(isNaN(tax_include_price)){
        var updated_price = this.calc_selcted_item_total_price(product_data,'without_modif');
        product_data.tax_include_price = parseFloat(updated_price).toFixed(2);
      }
      product_data = this.checkAdditionalDiscount(product_data);

      this.$set(this.cart, product.cart_id, product_data);
      
      // console.log('product_data', product_data);
      this.update_prices();
      if (this.discount_rate != 0) {
        this.update_prices();
        // alert('heree');
      }
    },
    checkAdditionalDiscount(product_data){
      var discountcodeid = parseInt($('#store_discount_code').find('option:selected').data("discountid"));
      var prod_extra_disc_code = '';
      if(!isNaN(discountcodeid)){
        prod_extra_disc_code = this.retrieveCodeDiscount(product_data,discountcodeid);
      }
      var prod_extra_disc_auto = this.retrieveCashierDiscount(product_data);

      if(prod_extra_disc_code.length > 0 ){
        product_data.additional_discount_percentage = prod_extra_disc_code[0].discount_percentage;
        product_data.additional_discount_value = prod_extra_disc_code[0].discount_value;
        product_data.additional_discount_code = prod_extra_disc_code[0].discount_code;
        product_data.additional_discount_type = prod_extra_disc_code[0].discount_type;
      }
      else if(prod_extra_disc_auto.length > 0){
        product_data.additional_discount_percentage = prod_extra_disc_auto[0].discount_percentage;
        product_data.additional_discount_value = prod_extra_disc_auto[0].discount_value;
        product_data.additional_discount_code = prod_extra_disc_auto[0].discount_code;
        product_data.additional_discount_type = prod_extra_disc_auto[0].discount_type;
      }else {
        let discount_type_temp = $('#discount_type').val();
        if(this.discount_rate > 0){
          product_data.additional_discount_code = 'MANUAL';
        }else{
          product_data.additional_discount_code = '';
        }
        if (discount_type_temp == 2) {
          product_data.additional_discount_percentage = this.discount_rate;
          product_data.additional_discount_value = 0;
          product_data.additional_discount_type = 'percentage';
        }else{
          product_data.additional_discount_percentage = 0;
          product_data.additional_discount_value = this.discount_rate;
          product_data.additional_discount_type = 'amount';
        }
      }
      return product_data;
    },
    remove_from_cart(cart_id) {
      const product_data = this.cart[cart_id];
      // if(product_data.product_order_id != undefined && this.order_data != null){
      //   this.cart_deleted_items.push(product_data.product_order_id);
      //   console.log('this.cart_deleted_items on delete', this.cart_deleted_items);
      // }
      if (product_data.bonat_discount == true) {
        localStorage.removeItem('bonat_coupon_set');
      }
      //this.checkForDiscountNotApplicableProducts();
      Vue.delete(this.cart, cart_id);
      this.checkIfDiscountApplicable();

      if (Object.keys(this.cart).length > 0) {
        this.update_prices();
      } else {
        this.clearCart();
      }
    },
    checkIfDiscountApplicable() {
      let discount_list = [];
      this.store_discount_codes = this.discount_code_list;
      let productids = '',
        productcategories = '';
      for (let product in this.cart) {
        productids += this.cart[product].product_id + ',';
        productcategories += this.cart[product].product_category + ',';
      }
      productids = productids.replace(/,$/g, '');
      productcategories = productcategories.replace(/,$/g, '');
      //console.clear();
      for (let i = 0; i < this.store_discount_codes.length; i++) {
        /*console.log(
          "Discount applicable : " +
            (this.store_discount_codes[i].discount_applicable_products != null
              ? productids
                  .split(",")
                  .filter(
                    (el) =>
                      this.store_discount_codes[i].discount_applicable_products
                        .split(",")
                        .indexOf(el) == -1
                  )
              : "")
        );*/
        if (
          this.store_discount_codes[i].discount_applied_on == 'all_products' ||
          (this.store_discount_codes[i].discount_applicable_products != null &&
            productids.split(',').filter(el => this.store_discount_codes[i].discount_applicable_products.indexOf(el) == -1).length == 0) ||
          (this.store_discount_codes[i].discount_applicable_categories != null &&
            productcategories.split(',').filter(el => this.store_discount_codes[i].discount_applicable_categories.indexOf(el) == -1) == 0) ||
          (this.store_discount_codes[i].discount_not_applicable_products != null &&
            productids.split(',').filter(el => this.store_discount_codes[i].discount_not_applicable_products.indexOf(el) != -1) == 0)
        ) {
          discount_list = true;
         // discount_list.push(this.store_discount_codes[i]);
        }else{
          discount_list = false;
        }
      }
      return discount_list;
      //this.store_discount_codes = discount_list;
    },
    validate_quantity: _.debounce(function(cart_slack, event) {
      var entered_quantity = event.target.value;
      if (entered_quantity > 0 || entered_quantity == '') {
        this.update_prices();
        if (this.discount_type == 1) {
          this.update_prices();
        }
      } else {
        delete this.cart[cart_slack];
        this.update_prices();
      }
    }, 1000),
    validate_price: _.debounce(function(cart_slack, event) {
      var open_price_tax_include = parseFloat(event.target.value);
       
      if (isNaN(open_price_tax_include)) {  
        open_price_tax_include = 0;
      }
      if(open_price_tax_include == 0){
        $(event.target).addClass('border-danger');
      }else{
        $(event.target).removeClass('border-danger');
      }

      this.cart[cart_slack].tax_include_price = open_price_tax_include;
      let open_price_without_tax = this.setProductOpenPrice(this.cart[cart_slack]);
      this.cart[cart_slack].price = parseFloat(open_price_without_tax);
      this.cart[cart_slack].price_actual = parseFloat(open_price_without_tax);
      this.cart[cart_slack].price_with_modifier = parseFloat(open_price_without_tax) + this.cart[cart_slack].modifier_price;
      this.cart[cart_slack].price_with_modifier_actual = parseFloat(open_price_without_tax) + this.cart[cart_slack].modifier_price;
      this.update_prices();
      if (this.discount_type == 1) {
        this.update_prices();
      }
      
    }, 1000),
    gift_from_cart(cart_slack, event) {
    
      if(event.target.checked == true){

        this.cart[cart_slack].tax_include_price_actual = this.cart[cart_slack].tax_include_price;
        this.cart[cart_slack].tax_include_price = 0;
        let open_price_without_tax = this.setProductOpenPrice(this.cart[cart_slack]);
      
        this.cart[cart_slack].price = parseFloat(open_price_without_tax);
        this.cart[cart_slack].modifier_price_actual = this.cart[cart_slack].modifier_price;
        this.cart[cart_slack].modifier_price = 0;
        this.cart[cart_slack].price_with_modifier = parseFloat(open_price_without_tax) + this.cart[cart_slack].modifier_price;
      }else{
        this.cart[cart_slack].tax_include_price = this.cart[cart_slack].tax_include_price_actual;
        let open_price_without_tax = this.setProductOpenPrice(this.cart[cart_slack]);
        if (isNaN(open_price_without_tax)) { 
          open_price_without_tax = 0;
        }
        this.cart[cart_slack].price = parseFloat(open_price_without_tax);
        this.cart[cart_slack].modifier_price = this.cart[cart_slack].modifier_price_actual;
        this.cart[cart_slack].price_with_modifier = parseFloat(open_price_without_tax) + this.cart[cart_slack].modifier_price;
        if (isNaN(this.cart[cart_slack].price_with_modifier)) { 
          this.cart[cart_slack].price_with_modifier = 0;
        }
      }
      if(this.discount_type == 1){
        this.update_prices();
      }
      this.update_prices();
      
    },
    setProductOpenPrice(cart_item){
      var open_price_without_tax = 0;
      if(cart_item.is_tobacco_tax > 0 ){
        open_price_without_tax = this.calculateTobaccoSalePrice(cart_item.tax_include_price,cart_item.tobacco_tax_percentage,cart_item.tax_percentage);
      }else{
        open_price_without_tax = this.calculateSalePrice(cart_item.tax_include_price,cart_item.tax_percentage);
      }
      return open_price_without_tax;
    },
    calculateSalePrice(sale_price_including_tax,tax_percentage) {
      var sale_price_including_tax = sale_price_including_tax;
      var amount = this.calculate_reverse_percent_amount(sale_price_including_tax, tax_percentage)
      var sale_price_excluding_tax = number_format(amount, 4, ".", "");
      return sale_price_excluding_tax;
    },
    calculateTobaccoSalePrice(sale_price_including_tax,tobacco_tax_percentage,tax_percentage) {
      var sale_price_including_tax = sale_price_including_tax;
      var amount = this.calculate_reverse_percent_amount(sale_price_including_tax, tobacco_tax_percentage)
      var sale_price_excluding_tax = number_format(amount, 4, ".", "");
      
      var amount = this.calculate_reverse_percent_amount(sale_price_excluding_tax, tax_percentage)
      var sale_price_excluding_tobacco_tax = number_format(amount, 4, ".", "");
      return sale_price_excluding_tobacco_tax;
    },
    calculate_tax(item_total, tax_percentage) {
      var tax_amount = (parseFloat(tax_percentage) / 100) * parseFloat(item_total);

      return tax_amount;
    },

    calculate_store_level_tax(item_total, store_tax_percentage) {
      var store_level_tax_percentage = store_tax_percentage != null ? store_tax_percentage : 0;
      var store_level_tax_amount = (parseFloat(store_level_tax_percentage) / 100) * parseFloat(item_total);
      return store_level_tax_amount;
    },

    calculate_discount(item_total, discount_percentage, discount_value = 0) {
      // if (this.payment_option == 1) {
        this.discount_type = $('#discount_type').val();

        if (this.discount_type == 2) {
          // percent
          // this.discount_rate =
          //   (this.discount_rate / 100) * parseFloat(this.sub_total);
        }
        $('#discount_rate_credit').val(0);
      // } 
      // else if (this.payment_option == 2) {
      //   this.discount_type_credit = $('#discount_type_credit').val();
      //   this.discount_rate_credit = parseFloat($('#discount_rate_credit').val());

      //   $('#discount_rate').val(0);
      //   this.cash_amount2 = 0.0;
      // }

      if (this.discount_rate != 'undefined' && this.discount_rate > 0) {
        // var discount_amount = number_format(this.discount_rate, 2, ".", "");

        // goto
        // var discount_amount = this.$options.filters.formatDecimal(
        //   this.discount_rate
        // );
        var discount_amount = this.discount_rate;
      } else {
        // var discount_amount = this.$options.filters.formatDecimal(
        //   this.discount_rate
        // );
        var discount_amount = this.discount_rate;
      }
      if (discount_value == 0) {
        var discount_amount = (parseFloat(discount_percentage) / 100) * parseFloat(item_total);
      } else {
        var discount_amount = discount_value;
      }
      return discount_amount;
    },

    calculate_store_level_discount(item_total, store_discount_percentage) {
      var store_level_discount_percentage = store_discount_percentage.discount_percentage != null ? store_discount_percentage.discount_percentage : 0;
      var store_level_discount_amount = (parseFloat(store_level_discount_percentage) / 100) * parseFloat(item_total);

      return store_level_discount_amount;
    },

    show_bonat_modal() {
     // this.change_cash=true;
      var store_discount_name = $('#store_discount_code option:selected').text().trim();

      if (store_discount_name == 'BONAT') {
        this.coupon = '';
        this.server_errors = '';
        this.verify_coupon_modal = true;
      }
      this.applyCodeDiscount();
    },
    applyCodeDiscountToCredit(){
     for (var cart_id in this.cart) {
            if(typeof($('#store_discount_code_credit').find('option:selected').data("discountid"))!=="undefined")
            {
              if(this.checkCodeDiscountType(parseInt($('#store_discount_code_credit').find('option:selected').data("discountid")))!="all_products")
              {
                this.cart[cart_id].apply_to_all = false;
                this.cart[cart_id].code_discounts = this.retrieveCodeDiscount(this.cart[cart_id],parseInt($('#store_discount_code_credit').find('option:selected').data("discountid")));
              }
              else
              {
                this.cart[cart_id].apply_to_all = true;
                this.cart[cart_id].code_discounts = [];
              }
            }
            else
            {
              this.cart[cart_id].apply_to_all = true;
              this.cart[cart_id].code_discounts = [];
            }
         }
      this.update_prices();
    },
    applyCodeDiscount(){
          for (var cart_id in this.cart) {
            if(typeof($('#store_discount_code').find('option:selected').data("discountid"))!=="undefined")
            {
              if(this.checkCodeDiscountType(parseInt($('#store_discount_code').find('option:selected').data("discountid")))!="all_products")
              {
                this.cart[cart_id].apply_to_all = false;
                this.cart[cart_id].code_discounts = this.retrieveCodeDiscount(this.cart[cart_id],parseInt($('#store_discount_code').find('option:selected').data("discountid")));
              }
              else
              {
               this.cart[cart_id].apply_to_all = true;
               this.cart[cart_id].code_discounts = [];
              }
            }
            else
            {
              this.cart[cart_id].apply_to_all = true;
              this.cart[cart_id].code_discounts = [];
            }
         }
         this.update_prices(); 
    },
    calculate_reverse_percent_amount(item_total, tax_percentage) {
      var amount = (parseFloat(item_total) / (100 + parseFloat(tax_percentage))) * 100;
      return amount;
    },
    retrieveCodeDiscount(productdata,discountcodeid){
      let discount_codes = [];
       if(this.store_discount_codes.length>0)
       {
          for(let i = 0;i<this.store_discount_codes.length;i++)
          {
            if(this.store_discount_codes[i].id==discountcodeid)
            {
              if(this.store_discount_codes[i].discount_applied_on=="all_products")
              {
                  discount_codes.push(this.store_discount_codes[i]);
              }
              else if(this.store_discount_codes[i].discount_applied_on=="specific_products")
              {
                if(this.store_discount_codes[i].discount_applicable_products.split(",").filter((el)=>parseInt(el)==parseInt(productdata.product_id)).length>0)
                {
                    discount_codes.push(this.store_discount_codes[i]);
                }
              }
              else if(this.store_discount_codes[i].discount_applied_on=="specific_product_categories")
              {
                let product_category = productdata.product_sub_category;
                if(parseInt(product_category)==0)
                {
                  product_category = productdata.product_category;
                }
                if(this.store_discount_codes[i].discount_applicable_categories.split(",").filter((el)=>parseInt(el)==parseInt(product_category)).length>0)
                {
                    discount_codes.push(this.store_discount_codes[i]);
                }
              }
              else if(this.store_discount_codes[i].discount_applied_on=="all_products_except_specific")
              {
                if(this.store_discount_codes[i].discount_applicable_products.split(",").filter((el)=>parseInt(el)==parseInt(productdata.product_id)).length==0)
                {
                    discount_codes.push(this.store_discount_codes[i]);
                }
              }
            }
          }
       }
       return discount_codes;
    },
    retrieveCashierDiscount(productdata){
      let cashier_discounts = [];
       if(this.store_discount_codes_cashier.length>0)
       {
          for(let i = 0;i<this.store_discount_codes_cashier.length;i++)
          {
              if(this.store_discount_codes_cashier[i].discount_applied_on=="all_products")
              {
                  cashier_discounts.push(this.store_discount_codes_cashier[i]);
              }
              else if(this.store_discount_codes_cashier[i].discount_applied_on=="specific_products")
              {
                if(this.store_discount_codes_cashier[i].discount_applicable_products.split(",").filter((el)=>parseInt(el)==parseInt(productdata.product_id)).length>0)
                {
                    cashier_discounts.push(this.store_discount_codes_cashier[i]);
                }
              }
              else if(this.store_discount_codes_cashier[i].discount_applied_on=="specific_product_categories")
              {
                let product_category = productdata.product_sub_category;
                if(parseInt(product_category)==0)
                {
                  product_category = productdata.product_category;
                }
                if(this.store_discount_codes_cashier[i].discount_applicable_categories.split(",").filter((el)=>parseInt(el)==parseInt(product_category)).length>0)
                {
                    cashier_discounts.push(this.store_discount_codes_cashier[i]);
                }
              }
              else if(this.store_discount_codes_cashier[i].discount_applied_on=="all_products_except_specific")
              {
                if(this.store_discount_codes_cashier[i].discount_applicable_products.split(",").filter((el)=>parseInt(el)==parseInt(productdata.product_id)).length==0)
                {
                    cashier_discounts.push(this.store_discount_codes_cashier[i]);
                }
              }
          }
       }
       return cashier_discounts;
    },
    checkCodeDiscountType(codeid)
    {
      let discounttype = "";
      this.store_discount_codes.forEach((el)=>{
        if(parseInt(el.id) == parseInt(codeid))
        {
          discounttype = el.discount_applied_on;
        }
      });
      return discounttype;
    },

    update_prices() {
      // watch
      this.tax_total = 0.0;
      this.discount_total = 0.0;
      this.total = 0.0;
      this.quantity_count = 0;
      if (this.payment_option == 3 || this.payment_option == 4) {
        this.show_action_btn = false;
      } else {
        this.show_action_btn = true;
      }
      
      var discount_type = $('#discount_type').val();
      var discount_rate = this.discount_rate;
      var discount_type_credit = $('#discount_type_credit').val();
      var discount_rate_credit = $('#discount_rate_credit').val();
      var store_discount_code_credit = $('#store_discount_code_credit').val();
      var store_discount_code = $('#store_discount_code').val();
      let discount_code_details = [];
      let selected_discount_code = $('#store_discount_code option:selected').attr('data-label');
      if(selected_discount_code == undefined){
        selected_discount_code = '';
      }
      let selected_discount_code_credit = '';
      // document.querySelector('#store_discount_code_credit').options[document.querySelector('#store_discount_code_credit').selectedIndex].text;
      $('select[name="discount_type"]').find('option').removeAttr('selected');

      let cashier_discount_amount = 0;
      let cashier_discount_percentage = 0;

      let productids = '',
        productcategories = '';
      for (let product in this.cart) {
        productids += this.cart[product].product_id + ',';
        productcategories += this.cart[product].product_category + ',';
        productcategories += this.cart[product].product_sub_category + ',';
      }
      productids = productids.replace(/,$/g, '');
      productcategories = productcategories.replace(/,$/g, '');

      if(this.order_data !== null){
        if(this.discount_type == 1){
          cashier_discount_amount = this.discount_rate;
          this.cashier_discount_amount_ids.push(this.store_discount_code_id);
          this.cashier_discount_amount_ids = [...new Set(this.cashier_discount_amount_ids)];
        }else{
          cashier_discount_percentage = this.discount_rate;
          this.cashier_discount_percentage_ids.push(this.store_discount_code_id);
          this.cashier_discount_percentage_ids = [...new Set(this.cashier_discount_percentage_ids)];
        }
      }else{
        for (let index = 0; index < this.store_discount_codes_cashier.length; index++) {
          if (this.store_discount_codes_cashier[index].discount_type.toLowerCase() == 'amount') {
            if (
              this.store_discount_codes_cashier[index].discount_applied_on == 'all_products' ||
              (this.store_discount_codes_cashier[index].discount_applicable_products != null &&
                productids.split(',').filter(el => this.store_discount_codes_cashier[index].discount_applicable_products.indexOf(el) != -1).length == productids.split(',').length) ||
              (this.store_discount_codes_cashier[index].discount_applicable_categories != null &&
                productcategories.split(',').filter(el => this.store_discount_codes_cashier[index].discount_applicable_categories.indexOf(el) != -1).length == productids.split(',').length) ||
              (this.store_discount_codes_cashier[index].discount_not_applicable_products != null &&
                productids.split(',').filter(el => this.store_discount_codes_cashier[index].discount_not_applicable_products.indexOf(el) != -1).length == 0)
            ) {
              cashier_discount_amount += parseFloat(this.store_discount_codes_cashier[index].discount_value);
              this.cashier_discount_amount_ids.push(this.store_discount_codes_cashier[index].id);
              this.cashier_discount_amount_ids = [...new Set(this.cashier_discount_amount_ids)];
            }
          } else {
            if (
              this.store_discount_codes_cashier[index].discount_applied_on == 'all_products' ||
              (this.store_discount_codes_cashier[index].discount_applicable_products != null &&
                productids.split(',').filter(el => this.store_discount_codes_cashier[index].discount_applicable_products.indexOf(el) != -1).length ==
                  productids.split(',').length) ||
              (this.store_discount_codes_cashier[index].discount_applicable_categories != null &&
                productcategories.split(',').filter(el => this.store_discount_codes_cashier[index].discount_applicable_categories.indexOf(el) != -1).length ==
                  productids.split(',').length) ||
              (this.store_discount_codes_cashier[index].discount_not_applicable_products != null &&
                productids.split(',').filter(el => this.store_discount_codes_cashier[index].discount_not_applicable_products.indexOf(el) != -1).length == 0)
            ) {
              cashier_discount_percentage += parseFloat(this.store_discount_codes_cashier[index].discount_percentage);
              this.cashier_discount_percentage_ids.push(this.store_discount_codes_cashier[index].id);
              this.cashier_discount_percentage_ids = [...new Set(this.cashier_discount_percentage_ids)];
            }
          }
        }

      }
      console.log('this.cashier_discount_amount_ids =', this.cashier_discount_amount_ids);
      console.log('this.cashier_discount_percentage_ids =', this.cashier_discount_percentage_ids);
      if (cashier_discount_amount > 0 && this.cashier_discount_amount_ids.length > 0) {
        this.discount_rate = cashier_discount_amount;
        this.discount_rate_credit = cashier_discount_amount;
        discount_type = 1;
        discount_type_credit = 1;

        $('#discount_type').val('1');
        $('#discount_type_credit').val('1');

        this.cashier_discount_amount = cashier_discount_amount;
      } else if (cashier_discount_percentage > 0 && this.cashier_discount_percentage_ids.length > 0) {
        this.discount_rate = cashier_discount_percentage;
        this.discount_rate_credit = cashier_discount_percentage;
        discount_type = 2;
        discount_type_credit = 2;

        $('#discount_type').val('2');
        $('#discount_type_credit').val('2');

        this.cashier_discount_percentage = cashier_discount_percentage;
      } else {
        // this.discount_rate = 0;
        // this.discount_rate_credit = 0;
        // this.cashier_discount_amount = 0;
      }
      if (store_discount_code != '0') {
        discount_code_details = [];
        for (let i = 0; i < this.store_discount_codes.length; i++) {
          if (
            this.store_discount_codes[i].discount_value == store_discount_code ||
            (this.store_discount_codes[i].discount_percentage == store_discount_code &&
              this.store_discount_codes[i].discount_code.toUpperCase() == selected_discount_code)
          ) {
            discount_code_details.push(this.store_discount_codes[i]);
          }
        }
        this.discount_rate = store_discount_code;
        $('#discount_rate').attr('readonly', true);
        $('#discount_type').attr('disabled', true);
        if (typeof discount_code_details[0] !== 'undefined' && discount_code_details[discount_code_details.length - 1].discount_type == 'amount') {
          //$('select[name="discount_type"]').find('option[value="1"]').attr('selected', true);
          $('#discount_type').val(1);
          discount_type = 1;
          this.discount_rate = String(parseFloat(this.discount_rate));
        } else {
          $('#discount_type').val(2);
          //$('select[name="discount_type"]').find('option[value="2"]').attr('selected', true);
          discount_type = 2;
          this.discount_rate = String(parseFloat(this.discount_rate));
        }
        var discount_rate = this.discount_rate;
      } else {
        this.discount_rate = String(parseFloat(this.discount_rate));
        $("#discount_rate").removeAttr("readonly");
        $("#discount_rate").removeAttr("disabled");
        $("#discount_type").removeAttr("disabled");
        /*if(this.change_cash==true)
        {
          this.discount_rate = 0;
          discount_rate = 0;
          $("#discount_rate").val("0");
        } */
        /*if (cashier_discount_percentage > 0 || cashier_discount_amount > 0) {
          $("#store_discount_code").attr("disabled", "disabled");
          $("#discount_rate").attr("disabled", "disabled");
          $("#discount_type").attr("disabled", "disabled");
        } else {
          $("#discount_rate").removeAttr("readonly");
          $("#discount_type").removeAttr("disabled");

          $("#store_discount_code").removeAttr("disabled");
          $("#discount_rate").removeAttr("disabled");
          $("#discount_type").removeAttr("disabled");
        }*/
        //$('select[name="discount_type"]').find('option[value="1"]').attr("selected",true);
      }
      $('select[name="discount_type_credit"]').find('option').removeAttr('selected');
      if (parseInt(store_discount_code_credit) != 0) {
        discount_code_details = [];
        for (let i = 0; i < this.store_discount_codes.length; i++) {
          if (
            this.store_discount_codes[i].discount_value == store_discount_code ||
            (this.store_discount_codes[i].discount_percentage == store_discount_code &&
              this.store_discount_codes[i].discount_code.toUpperCase() == selected_discount_code_credit)
          ) {
            discount_code_details.push(this.store_discount_codes[i]);
          }
        }
        this.discount_rate_credit = store_discount_code_credit;
        $('#discount_rate_credit').attr('readonly', true);
        $('#discount_type_credit').attr('disabled', true);

        if (typeof discount_code_details[0] !== 'undefined' && discount_code_details[discount_code_details.length - 1].discount_type == 'amount') {
          $('select[name="discount_type_credit"]').find('option[value="1"]').attr('selected', true);
          discount_type_credit = 1;
          this.discount_rate_credit = String(parseFloat(this.discount_rate_credit));
        } else {
          $('select[name="discount_type_credit"]').find('option[value="2"]').attr('selected', true);
          discount_type_credit = 2;
          this.discount_rate_credit = String(parseFloat(this.discount_rate_credit));
        }
        var discount_rate_credit = this.discount_rate_credit;
        $('#discount_rate_credit').val(this.discount_rate_credit);
      } else {
        this.discount_rate_credit = String(parseFloat(this.discount_rate_credit));
        $("#discount_rate_credit").removeAttr("readonly");
        $("#discount_rate_credit").removeAttr("disabled");
        $("#discount_type_credit").removeAttr("disabled");
       /* if(this.change_credit==true)
        {
          this.discount_rate_credit = 0;
          discount_rate_credit = 0;
          $("#discount_rate_credit").val("0");
        } */
        /*if (cashier_discount_percentage > 0 || cashier_discount_amount > 0) {
         // $("#store_discount_code_credit").attr("disabled", "disabled");
          $("#discount_rate_credit").attr("disabled", "disabled");
          $("#discount_type_credit").attr("disabled", "disabled");
        } else {
          $("#discount_rate_credit").removeAttr("readonly");
          $("#discount_type_credit").removeAttr("disabled");

          $("#store_discount_code").removeAttr("disabled");
          $("#discount_rate").removeAttr("disabled");
          $("#discount_type").removeAttr("disabled");
        }*/
        //$('select[name="discount_type_credit"]').find('option[value="1"]').attr("selected",true);
      }
      var total_discount = 0.0;
      var total_tax = 0.0;
      var total_after_discount = 0.0;
      var total_price = 0.0;
      var item_total_amount = 0.0;
      var total_price_excluding_tax = 0.0;
      var total_additional_discount_amount = 0.0;
      this.all_total_additional_discount_amount = 0.0;
      this.total_discount_amount = 0.0;
      var total_discount_amount = 0.0;
      var total_product_amount_after_discount = 0.0;
      var product_total_after_discount = 0.0;
      var last_additional_discount_amount = 0.0;
      var total_product_discount = 0.0;
      this.discounted_sub_total = 0.0;
      var total_amount = 0.0;
      var product_discount = 0;
      this.sub_total = 0.0;
      let total_cashier_discount = 0;

      if (discount_rate == '') {
        discount_rate = parseFloat(0.0);
      }

      if (discount_type == 2 && discount_rate != 0) {
        if (parseInt(this.discount_rate) > 100) this.discount_rate = 0;
        this.handleDecimal();
      }

      if (discount_rate_credit == '') {
        discount_rate_credit = parseFloat(0.0);
      }

      if (discount_type_credit == 2 && discount_rate_credit != 0) {
        if (parseInt(discount_rate_credit) > 100) {
          $('#discount_rate_credit').val(0);
          this.discount_rate_credit = 0;
          discount_rate_credit = 0;
        }
      }
      this.item_level_total_tax_details = [];
      this.item_level_total_tobacco_tax_details = [];
      var app_obj = this;

      for (var cart_id in this.cart) {
        const product_data = this.cart[cart_id];

        if (product_data.is_tobacco_tax > 0) {
          if (app_obj.item_level_total_tobacco_tax_details == undefined) {
            app_obj.item_level_total_tobacco_tax_details = [];
          }
          app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax] = product_data.is_tobacco_tax;
          if (app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax]['item_tax_percentage'] == undefined) {
            app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax] = [];
          }
          app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax]['item_tax_percentage'] = product_data.tobacco_tax_percentage;
          app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax]['item_tax_label'] = app_obj.tobacco_tax_label;
          app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax]['item_tax_total'] = 0;
        }

        if (product_data.tax_code_id > 0) {
          $.each(product_data.tax_code_details, function(tax_key, tax_detail) {

            var tax_name_id = tax_detail.tax_name_id;
            if (app_obj.item_level_total_tax_details == undefined) {
              app_obj.item_level_total_tax_details = [];
            }

            app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = tax_detail.tax_name_id;
            if (app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] == undefined) {
              app_obj.item_level_total_tax_details[tax_detail.tax_name_id] = [];
            }

            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_percentage'] = tax_detail.tax_percentage;
            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_label'] = tax_detail.tax_type;
            app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_total'] = 0;
          });
        } else {
          this.item_level_total_tax_details[this.store_tax_code_id] = this.store_tax_code_id;
          if (this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_total'] == undefined) {
            this.item_level_total_tax_details[this.store_tax_code_id] = [];
          }
          this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_total'] = 0;
          this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_percentage'] = this.store_tax_percentage;
          this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_label'] = this.store_tax_label;
        }
      }

      var specific_prod_total_price_after_disc = 0;
      var manual_discount_rate = this.discount_rate;
      if(isNaN(manual_discount_rate)){ manual_discount_rate = 0; }
      for (var cart_id in this.cart) {
        var additional_discount_amount = 0;
        var additional_discount_percentage_calc = 0;
        //this.cart[cart_id].cashier_discounts = this.retrieveCashierDiscount(this.cart[cart_id]);
        var cart_length = Object.keys(this.cart).length;
        const product_data = this.cart[cart_id];
        product_data = this.checkAdditionalDiscount(product_data);
        console.log('hereeeeeeeeee product_data =',product_data );
        if (product_data.quantity != '') {
          var quantity = parseFloat(product_data.quantity);
          if (!isNaN(quantity)) {
            // product_data.price = number_format(product_data.price, 2, ".", "");
            total_price = parseFloat(quantity) * parseFloat(product_data.price_with_modifier);
            var product_discount = this.calculate_discount(total_price, product_data.discount_percentage, product_data.discount_value);

            product_total_after_discount = parseFloat(total_price) - parseFloat(product_discount);
            // product_total_after_discount = number_format(product_total_after_discount,2,".","");
            var prod_addit_discount_percentage = parseFloat(product_data.additional_discount_percentage);
            if(isNaN(prod_addit_discount_percentage)){ prod_addit_discount_percentage = 0;}
            var prod_addit_discount_type = product_data.additional_discount_type;
            var prod_addit_discount_value = parseFloat(product_data.additional_discount_value);
            if(isNaN(prod_addit_discount_value)){ prod_addit_discount_value = 0; }
            var discount_percent_calc = 0;

            if (discount_type == 1 && total_price > 0 && (prod_addit_discount_value > 0 || manual_discount_rate > 0)) {
              specific_prod_total_price_after_disc += parseFloat(product_total_after_discount);
              //additional_discount_amount = parseFloat( discount_rate / cart_length );
              //debugger;
              if(isNaN(this.specific_prod_total_price_after_discount)){ this.specific_prod_total_price_after_discount = 0; }
              if(this.specific_prod_total_price_after_discount > 0){
                if(prod_addit_discount_value > 0){
                  discount_percent_calc = (parseFloat(prod_addit_discount_value) / this.specific_prod_total_price_after_discount) * 100;
                }else if(manual_discount_rate > 0){
                  discount_percent_calc = (parseFloat(manual_discount_rate) / this.specific_prod_total_price_after_discount) * 100;
                }
                // let discount_percent_calc = (parseFloat(prod_addit_discount_value) / this.total_amount) * 100;
                if (isNaN(discount_percent_calc)) { discount_percent_calc = 0; }
              }
              else if(manual_discount_rate > 0 && this.total_amount > 0){
                  discount_percent_calc = (parseFloat(manual_discount_rate) / this.total_amount) * 100;
              }
              //additional_discount_amount = this.discount_rate;
              additional_discount_percentage_calc = discount_percent_calc;
              additional_discount_amount = this.calculate_discount(product_total_after_discount, discount_percent_calc);
            } else if(discount_type == 2 && total_price > 0 && (prod_addit_discount_percentage > 0 || manual_discount_rate > 0)){
              if(prod_addit_discount_percentage > 0){
                additional_discount_amount = this.calculate_discount(product_total_after_discount, prod_addit_discount_percentage);
              }else if(manual_discount_rate > 0){
                additional_discount_amount = this.calculate_discount(product_total_after_discount, manual_discount_rate);
              }
              additional_discount_percentage_calc = prod_addit_discount_percentage;
            }
            // if (this.payment_option == 2) {
            //   if (discount_type_credit == '1' && prod_addit_discount_percentage > 0) {
            //     specific_prod_total_price_after_disc += parseFloat(product_total_after_discount);
            //     // let discount_percent_calc = (parseFloat(discount_rate_credit) / this.total_amount) * 100;
            //     let discount_percent_calc = (parseFloat(discount_rate_credit) / this.specific_prod_total_price_after_discount) * 100;
            //     if (isNaN(discount_percent_calc)) {
            //       discount_percent_calc = 0;
            //     }
            //     //additional_discount_amount = parseFloat(discount_rate_credit / cart_length);
            //     additional_discount_amount = this.calculate_discount(product_total_after_discount, discount_percent_calc);
            //   } else if(discount_type_credit != '1' && prod_addit_discount_percentage > 0){
            //     additional_discount_amount = this.calculate_discount(product_total_after_discount, discount_rate_credit);
            //   }
            // }
            last_additional_discount_amount += parseFloat(additional_discount_amount) 
            total_after_discount = parseFloat(product_total_after_discount) - parseFloat(additional_discount_amount);

            total_discount = parseFloat(additional_discount_amount) + parseFloat(product_discount);

            console.log('total_after_discount -----------------====', total_after_discount);
            // total_price = total_price - parseFloat(product_discount);
            // // total_price = number_format(total_price, 2, ".", "");
            // if (discount_type == 1) {
            //   // total_additional_discount_amount = parseFloat( discount_rate / cart_length );
            //   let total_discount_percent_calc = (parseFloat(discount_rate) / this.total_amount) * 100;
            //   if (isNaN(total_discount_percent_calc)) {
            //     total_discount_percent_calc = 0;
            //   }
            //   total_additional_discount_amount = this.calculate_discount(total_price, total_discount_percent_calc);
            // } else {
            //   total_additional_discount_amount = this.calculate_discount(total_price, discount_rate);
            // }
            // if (this.payment_option == 2) {
            //   if (discount_type_credit == 1) {
            //     total_additional_discount_amount = parseFloat(discount_rate_credit / cart_length);
            //   } else {
            //     total_additional_discount_amount = this.calculate_discount(total_price, discount_rate_credit);
            //   }
            // }

            var curr_total_tobacco_tax = 0;
            var tax_code_details = [];
            var tobacco_tax_code_details = [];
            if (product_data.is_tobacco_tax > 0) {
              curr_total_tobacco_tax = app_obj.calculate_tax(total_after_discount, product_data.tobacco_tax_percentage);
              app_obj.item_level_total_tobacco_tax_details[product_data.is_tobacco_tax]['item_tax_total'] += curr_total_tobacco_tax;
              tobacco_tax_code_details.push({
                tax_type: app_obj.tobacco_tax_label,
                tax_name_id: 0,
                tax_percentage: product_data.tobacco_tax_percentage,
                tax_amount: curr_total_tobacco_tax.toFixed(4)
              });
              // total_tax += curr_total_tobacco_tax;
            }
            item_total_amount = (parseFloat(total_after_discount) + parseFloat(curr_total_tobacco_tax)).toFixed(4);

            if (product_data.tax_percentage != null) {
              total_tax = this.calculate_tax(item_total_amount, product_data.tax_percentage);
            } else {
              total_tax = 0.0;
            }
            total_tax = total_tax + curr_total_tobacco_tax;
            var tax_splitup_totals = 0;
            if (product_data.tax_code_id > 0) {
              $.each(product_data.tax_code_details, function(tax_key, tax_detail) {
                let curr_total_tax = app_obj.calculate_tax(item_total_amount, tax_detail.tax_percentage);
                app_obj.item_level_total_tax_details[tax_detail.tax_name_id]['item_tax_total'] += curr_total_tax;
                tax_splitup_totals = parseFloat(tax_splitup_totals) + parseFloat(curr_total_tax);
                tax_code_details.push({
                  tax_type: tax_detail.tax_type,
                  tax_name_id: tax_detail.tax_name_id,
                  tax_percentage: tax_detail.tax_percentage,
                  tax_amount: curr_total_tax.toFixed(4)
                });
              });
            } else {
              this.item_level_total_tax_details[this.store_tax_code_id]['item_tax_total'] += total_tax;
            }
            product_data.tax_code_details = tax_code_details;
            product_data.tobacco_tax_code_details = tobacco_tax_code_details;

            item_total_amount = (parseFloat(item_total_amount) + tax_splitup_totals).toFixed(4);
            // console.log('final item_total_amount ==', item_total_amount);
            if(this.cart[cart_id].gift_flag == false){
              this.$set(this.cart[cart_id], 'total_price_actual', product_total_after_discount.toFixed(4));
            }
            this.$set(this.cart[cart_id], 'total_price', product_total_after_discount.toFixed(4));
            this.$set(this.cart[cart_id], 'item_total_amount', item_total_amount);
            this.$set(this.cart[cart_id], 'total_tax', total_tax.toFixed(4));
            this.$set(this.cart[cart_id], 'total_discount', total_discount.toFixed(4));
            this.$set(this.cart[cart_id], 'tax_components', JSON.stringify(tax_code_details));
            this.$set(this.cart[cart_id], 'tobacco_tax_components', JSON.stringify(tobacco_tax_code_details));
            this.$set(this.cart[cart_id], 'additional_discount_percentage_calc', additional_discount_percentage_calc.toFixed(4));
            // this.$set(this.cart[cart_id], "discount_percentage", discount_rate);

            this.tax_total = this.tax_total + parseFloat(total_tax);
            this.discount_total = this.discount_total + parseFloat(total_discount);
            this.sub_total = this.sub_total + parseFloat(product_total_after_discount);
            this.quantity_count = this.quantity_count + quantity;
            if (discount_type == 1){
              this.total_discount_amount = manual_discount_rate; //parseFloat(additional_discount_amount);
            }else{
              this.total_discount_amount = this.total_discount_amount + parseFloat(additional_discount_amount);
            }
            total_product_discount = parseFloat(total_product_discount) + parseFloat(product_discount);
          }
        }
      }

      // this.sub_total = number_format(this.sub_total, 2, ".", "");

      // total_product_discount = number_format(
      //   total_product_discount,
      //   2,
      //   ".",
      //   ""
      // );

      // if (discount_type == 1) {
      //   last_additional_discount_amount = parseFloat(discount_rate);
      // } else {
      //   last_additional_discount_amount = this.calculate_discount(this.sub_total, discount_rate);
      // }

      // if (this.payment_option == 2) {
      //   if (discount_type_credit == 1) {
      //     last_additional_discount_amount = parseFloat(discount_rate_credit);
      //   } else {
      //     last_additional_discount_amount = this.calculate_discount(this.sub_total, discount_rate_credit);
      //   }
      // }
      // last_additional_discount_amount = number_format(
      //   last_additional_discount_amount,
      //   2,
      //   ".",
      //   ""
      // );
      total_amount = this.sub_total;
      this.total_amount = total_amount;

      this.specific_prod_total_price_after_discount = parseFloat(specific_prod_total_price_after_disc);
      
      // if (discount_type == 1 && discount_rate > 0) {

      //     if(isNaN(this.specific_prod_total_price_after_discount)){ this.specific_prod_total_price_after_discount = 0; }
      //     if(this.specific_prod_total_price_after_discount > 0){
      //       discount_percent_calc = (parseFloat(discount_rate) / this.specific_prod_total_price_after_discount) * 100;
      //       if (isNaN(discount_percent_calc)) { discount_percent_calc = 0; }
      //     }
      //     additional_discount_percentage_calc = discount_percent_calc;
      //     additional_discount_amount = this.calculate_discount(this.specific_prod_total_price_after_discount, discount_percent_calc);
      //     this.total_discount_amount = additional_discount_amount;
      // }
      // this.total_discount_amount = number_format(
      //   last_additional_discount_amount,
      //   2,
      //   ".",
      //   ""
      // );
      // if (Object.keys(this.cart).length > 0) {
      //   this.total_discount_amount = last_additional_discount_amount;
      // } else {
      //   this.total_discount_amount = 0;
      // }
      if(parseFloat(this.sub_total) > 0){
        this.discounted_sub_total = parseFloat(this.sub_total) - parseFloat(this.total_discount_amount);
      }else{
        this.discounted_sub_total = 0;
        this.total_discount_amount = 0;
      }
      // this.discounted_sub_total = number_format(
      //   this.discounted_sub_total,
      //   2,
      //   ".",
      //   ""
      // );
      let store_level_tax_component_objects = [];
      var this_scope = this;
      $.each(this.item_level_total_tobacco_tax_details, function(tax_key, tax_detail) {
        if (tax_detail != undefined) {
          let tax_obj = {
            tax_type: tax_detail.item_tax_label,
            tax_percentage: tax_detail.item_tax_percentage,
            tax_amount: tax_detail.item_tax_total.toFixed(2)
          };
          store_level_tax_component_objects.push(tax_obj);
        }
      });

      $.each(this.item_level_total_tax_details, function(tax_key, tax_detail) {
        if (tax_detail != undefined) {
          let tax_obj = {
            tax_type: tax_detail.item_tax_label,
            tax_percentage: tax_detail.item_tax_percentage,
            tax_amount: tax_detail.item_tax_total.toFixed(2)
          };
          store_level_tax_component_objects.push(tax_obj);
        }
      });
      // $.each(this.store_level_tax_components, function(
      //   tax_component_key,
      //   tax_component
      // ) {
      //   let tax_component_amount = this_scope.calculate_tax(
      //     this_scope.discounted_sub_total,
      //     tax_component.tax_percentage
      //   );
      //   let tax_obj = {
      //     tax_type: tax_component.tax_type,
      //     tax_percentage: tax_component.tax_percentage,
      //     tax_amount: tax_component_amount,
      //   };
      //   store_level_tax_component_objects.push(tax_obj);
      // });
      this.store_level_tax_component_objects = store_level_tax_component_objects;

      // total_tax = this.calculate_tax(
      //   this.discounted_sub_total,
      //   this.store_tax_percentage
      // );

      // this.tax_total = number_format(total_tax, 2, ".", "");
      //this.tax_total = total_tax;
      this.sub_total = parseFloat(this.discounted_sub_total) + parseFloat(this.tax_total);

      // this.total = number_format(this.sub_total, 2, ".", "");
      this.total = this.sub_total;

      if (this.payment_option == 1) {
        let change_amount = parseFloat($('#change_amount').val());
        if (change_amount == 0) {
          // let cash_total = this.$options.filters.formatDecimal(this.total);
          // let cash_total =
          //  String(this.total.toFixed(2)).split(".")[1] == "99"
          //    ? Math.round(this.total)
          //    : this.total.toFixed(2);
          let cash_total = this.total.toFixed(2);
          $('#cash_amount').val(cash_total);
        }

        if (change_amount > this.total) {
          this.change_amount = 0;
        }
      } else if (this.payment_option == 2) {
        let cash_amount2 = parseFloat($('#cash_amount2').val());
        // let temp_credit_total = this.$options.filters.formatDecimal(this.total);
        let temp_credit_total = this.total.toFixed(2);
        $('#credit_amount').val(temp_credit_total);
        if (cash_amount2 == 0) {
          if (cash_amount2 > this.total) {
            $('#credit_amount').val(0);
          }
          this.show_payment_method = true;
        }
        let cash_amount2_temp = Math.abs(cash_amount2);
        let total_temp = Math.abs(this.total);
        if (cash_amount2_temp > total_temp) {
          this.cash_amount2 = 0;
        }
      }

      this.item_count = Object.keys(this.cart).length;
    },
    checkOpenPriceValid(){
      $('.open_price_input').removeClass('border-danger');
      var open_price_error = 0;
      var app_obj = this;
      for (var cart_id in this.cart) {
        const product_data = this.cart[cart_id];
        if (product_data.total_price <= 0 && product_data.gift_flag == false) {
          console.log('product_data.total_price =', product_data.total_price);
            open_price_error = 1;
            app_obj.processing = false;
            app_obj.show_creation_modal = false;
            app_obj.show_error_message = true;
            app_obj.error_message = "Please set/input price greater than zero or enable the gift flag if you prefer to gift this item : "+product_data.name;
            window.scrollTo(0, 0);
            $('#'+product_data.cart_id).addClass('border-danger');
            $('#'+product_data.cart_id).focus();
            return false;
        }
      }
      // $('.open_price_input').each(function(index, element){
      //   if($(element).val() <= 0){
      //       open_price_error = 1;
      //       app_obj.processing = false;
      //       app_obj.show_creation_modal = false;
      //       app_obj.show_error_message = true;
      //       app_obj.error_message = "Please input amount grater than zero or enable gift flag if you prefer to gift this item(s).";
      //       window.scrollTo(0, 0);
      //       $(element).addClass('border-danger');
      //       $(element).focus();
      //       return;
      //     }
      // });
      if(open_price_error == 1){
        return false;
      }
    },
    create_order(order_status) {
      this.$on('submit');
      this.$off('close');
      if (Object.keys(this.cart).length <= 0) {
        return;
      }
      var checkOpenPriceValidation = this.checkOpenPriceValid();
      if(checkOpenPriceValidation == false){
        return;
      }
      // $('.btn-generate-pos').click();

      this.order_status = order_status;
      this.show_creation_modal = true;

      this.$on('submit', function() {
        this.$validator.validateAll('confirmation_form').then(isValid => {
          if (isValid) {
              this.show_creation_modal = false;
              this.create_order_with_print(order_status);
          }
        });

      });

      this.$on('close', function() {
        this.show_creation_modal = false;
      });
    },

    create_order_with_print(order_status) {
      if (Object.keys(this.cart).length <= 0) {
        this.processing = false;
        this.show_error_message = true;
        this.error_message = this.$t('Please select atleast one item in cart.');
        window.scrollTo(0, 0);
        return false;
      }
      
      var checkOpenPriceValidation = this.checkOpenPriceValid();
      if(checkOpenPriceValidation == false){
        return;
      }

      if (this.payment_option == 2) {
        var card_details = $('#card_name').val();
        if (card_details == null || card_details == '') {
          this.processing = false;
          this.show_error_message = true;
          this.error_message = this.$t('Invalid Payment method selected.');
          window.scrollTo(0, 0);
          return false;
        }
      }
      if(order_status == 'PARTIAL_PAYMENT'){
        this.partial_paid = true;
      }
      this.show_error_message = false;
      this.order_status = order_status;
      this.show_customer_modal = this.enable_customer_detail_popup != null ? this.enable_customer_detail_popup : true;

      if (this.show_customer_modal == false) {
        this.select_customer('skip');
      } else {
        // Display Customer Modal
        // console.log("show customer modal");
      }
    },

    submit_partial_payment() {
      this.processing = true;
      if (this.partial_pay_paid_amount <= 0) {
        this.processing = false;
        this.partial_pay_server_errors = 'Please enter amount greater than zero!';
        return false;
      }
      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('order_slack', this.partial_payment_order_slack);
      formData.append('paid_amount', this.partial_pay_paid_amount);
      formData.append('notes', this.partial_pay_notes);
      axios
        .post('/api/order/partial_pay', formData)
        .then(response => {
          console.log('data= ', response.data.status_code);
          if (response.data.status_code == 200) {
            this.show_payment_details_modal = false;
            this.show_hold_list_modal = false;
            this.partial_pay_paid_amount = 0;
            this.partial_payment_order_slack = '';
            this.partial_pay_server_errors = '';
            this.partial_pay_notes = '';
            this.show_response_message(response.data.msg, 'SUCCESS');
            alert(response.data.msg);
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.partial_pay_server_errors = response.data.msg;
              this.show_response_message(response.data.msg, 'ERROR');
              return false;
            }
            this.error_class = 'error';
          }
          this.processing = false;
        })
        .catch(error => {
          console.log(error);
        });
    },

    submit_form_data() {
      this.processing = true;
      // this.$off("submit");
      // this.$off("close");
      if (Object.keys(this.cart).length <= 0) {
        this.processing = false;
        this.show_creation_modal = false;
        this.show_error_message = true;
        this.error_message = 'Please select atleast one item in cart.';
        window.scrollTo(0, 0);
        return false;
      }

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('order_status', this.order_status);
      if ( typeof $('#store_discount_code').find(':selected').data('discountid') !== 'undefined') {
        formData.append('discount_code_id',$('#store_discount_code').find(':selected').data('discountid'));
        formData.append('discount_code_name',
          document.querySelector('#store_discount_code').options[document.querySelector('#store_discount_code').selectedIndex].text);
      }
      if (this.cashier_discount_amount > 0) {
        formData.append('cashier_discount_amount_ids', JSON.stringify(this.cashier_discount_amount_ids));
      } else {
        formData.append('cashier_discount_percentage_ids', JSON.stringify(this.cashier_discount_percentage_ids));
      }
      if (document.querySelector('#store_discount_code').value != '0') {
        formData.append('selected_discount_code',$('#store_discount_code').find(':selected').data('label'));
      } 
      // else {
      //   formData.append('selected_discount_code',
      //     document.querySelector('#store_discount_code_credit').options[document.querySelector('#store_discount_code_credit').selectedIndex].text
      //   );
      // }
      if(this.order_data !== null && this.store_discount_code_id > 0){
        formData.append('discount_code_id',this.store_discount_code_id);
      }
      if (this.new_customer) {
        // New Customer
        formData.append('customer_name', this.customer_name);
        formData.append('customer_number', this.customer_number);
        formData.append('customer_email', this.customer_email);
      } else {
        // Existing Customer
        formData.append('customer_slack', this.customer_slack);
      }
      formData.append('payment_method', this.payment_method);
      formData.append('business_account', this.business_account);
      formData.append('restaurant_mode', this.order_restaurant_mode == true ? 1 : 0);
      formData.append('restaurant_order_type', this.restaurant_order_type);
      formData.append('restaurant_table', this.restaurant_table);
      formData.append('waiter', this.waiter != null ? this.waiter : '');
      formData.append('billing_type', this.billing_type != null ? this.billing_type : '');

      formData.append('cart', JSON.stringify(this.cart));
      console.log('cart = ',JSON.stringify(this.cart));
      // custom updation code
      formData.append('payment_option', this.payment_option);

      // counter  code
      formData.append('counter_name', this.counter_name);
      formData.append('counter_slack', this.counter_slack);
      if (isNaN(this.discount_rate)) {
        this.discount_rate = 0.0;
      }
      formData.append('sale_amount_subtotal_excluding_tax', this.total_amount.toFixed(2));
      formData.append('total_after_discount', this.discounted_sub_total.toFixed(2));
      formData.append('total_tax_amount', this.tax_total.toFixed(2));
      formData.append('total_order_amount', this.total.toFixed(2));
      formData.append('store_level_tax_components', JSON.stringify(this.store_level_tax_component_objects));
      //this.discount_rate += this.total_discount_amount;
      formData.append('discount_type', this.discount_type);
      formData.append('paid_amount', this.paid_amount);
      if (this.discount_type == 1) {
        // discount in amount
        if (isNaN(this.discount_rate)) {
          this.discount_rate = 0.0;
        }
        formData.append('additional_discount_amount', this.discount_rate);
      } else if (this.discount_type == 2) {
        // discount in percentage
        formData.append('additional_discount_percentage', this.discount_rate);
      }
      if (this.payment_option == 1) {
        formData.append('change_amount', parseFloat($('#change_amount').val()));

        var cash_amount = parseFloat($('#cash_amount').val());

        if (cash_amount < 0 || this.total < 0) {
          this.processing = false;
          this.show_creation_modal = false;
          this.show_error_message = true;
          this.error_message = this.$t('Grand Total cannot be Negative.');
          window.scrollTo(0, 0);
          const El = document.getElementById('product_container');
          El.scrollTo(0, 0);
          return false;
        }
        formData.append('cash_amount', cash_amount);
      } 
      else if (this.payment_option == 2) {
        formData.append('card_name', $('#card_name option:selected').data('label'));
        formData.append('payment_method', this.card_name);
        formData.append('payment_method_id', $('#card_name option:selected').data('payment-id'));
        formData.append('payment_method_slack', this.card_name);
        var cash_amount = parseFloat($('#cash_amount2').val());
        var credit_amount = parseFloat($('#credit_amount').val());
        if (isNaN(credit_amount) || credit_amount == '') {
          credit_amount = 0;
        }

        if (credit_amount < 0 || this.total < 0) {
          this.processing = false;
          this.show_creation_modal = false;
          this.show_error_message = true;
          this.error_message = this.$t('Grand Total cannot be Negative.');
          window.scrollTo(0, 0);
          const El = document.getElementById('product_container');
          El.scrollTo(0, 0);
          return false;
        }

        formData.append('cash_amount', cash_amount);
        formData.append('credit_amount', parseFloat(credit_amount));
        // if (isNaN(this.discount_rate_credit)) {
        //   this.discount_rate_credit = 0.0;
        // }
        //this.discount_rate_credit += this.total_discount_amount;
        // if (this.discount_type_credit == 1) {
        //   // discount in amount
        //   if (isNaN(this.discount_rate_credit)) {
        //     this.discount_rate_credit = 0.0;
        //   }

        //   formData.append('additional_discount_amount', this.discount_rate_credit);
        // } else if (this.discount_type_credit == 2) {
        //   // discount in percentage
        //   formData.append('additional_discount_percentage', this.discount_rate_credit);
        // }
        formData.append('total_discount_amount', this.discount_rate_credit);
      } else if (this.payment_option == 3) {
        formData.append('transaction_id', this.transaction_id);
      }
      
      formData.append('has_combo', (this.combo_cart.length > 0) ? 1 : 0);
      
      /*console.clear();
      console.log(JSON.stringify(this.cart));
      return false;*/
      // total discount amount

      //   formData.append("value_date", this.value_date);

      // for (var value of formData.values()) {

      // }
      // return false;
      axios
        .post(this.api_link, formData)
        .then(response => {
          // console.log('data=' + response.data.status_code);
          if (response.data.status_code == 200) {
            this.show_partial_payment_modal = false;
            this.show_response_message(response.data.msg, 'SUCCESS');
            if (typeof response.data.link != 'undefined' && response.data.link != '') {
              this.this_order_slack = response.data.data;
              this.processing = false;

              // var loadingTask = pdf.createLoadingTask(response.data.link);

              // // console.log(loadingTask);
              // loadingTask.promise.then(pdf => {
              //   this.pdf_page_count = pdf.numPages;
              //   this.order_receipt_pdf = response.data.link;
              // });

              this.print_order(this.this_order_slack);
              this.show_customer_modal = false;
              this.clearCart();
            } else {
              var app_obj = this;
              setTimeout(function() {
                window.location.href = app_obj.new_order_route;
              }, 1000);
            }
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
              this.show_response_message(response.data.msg, 'ERROR');
              this.processing = false;
              this.show_creation_modal = false;
              this.show_error_message = true;
              this.error_message = response.data.msg;
              window.scrollTo(0, 0);
              return false;
            }
            this.error_class = 'error';
          }
        })
        .catch(error => {
          console.log(error);
        });
    },

    load_customers(keywords, type) {
      if (typeof keywords != 'undefined') {
        if (keywords.length > 0) {
          var formData = new FormData();
          formData.append('access_token', window.settings.access_token);
          formData.append('keywords', keywords);
          formData.append('type', type);
          var appobj = this;
          axios
            .post('/api/filter_single_customer', formData)
            .then(response => {
              if (response.data.status_code == 200) {
                if(type == 'name'){
                  this.customer_list_name = response.data.data;
                }else if (type == 'phone') {
                  this.customer_list_number = response.data.data;
                }
                else if (type == 'email') {
                  this.customer_list_email = response.data.data;
                }
                // console.log('response.data.data',response.data.data);
              }
            })
            .catch(error => {
              console.log(error);
            });
        }
        if (type == 'name') {
          this.customer_name = keywords;
        }else if (type == 'phone') {
          this.customer_number = keywords;
        } else if (type == 'email') {
          this.customer_email = keywords;
        }       
      }
    },

    select_customer(action) {
      switch (action) {
        case 'skip':
          this.customer_name = '';
          this.customer_number = '';
          this.customer_email = '';
          this.customer_label = this.default_label;
          this.set_customer_autocomplete_empty();
          this.show_customer_modal = false;
          this.submit_form_data();
          break;
        case 'proceed':
          // this.$validator.validateAll();
          this.$validator.validateAll().then(result => {
            if (!this.errors.any()) {
              if (this.customer_name == '' && this.filter_customer_name == '' && this.customer_number == '' && this.customer_email == '' && this.filter_customer_number == '' && this.filter_customer_email == '') {
                this.customer_label = this.default_label;
                this.set_customer_autocomplete_empty();
              } else {
                if ((this.customer_name != '' && this.customer_name != null) || this.filter_customer_name != '') {
                  var c_name = this.customer_name != '' && this.customer_name != null ? this.customer_name : this.filter_customer_name;
                  this.customer_name = c_name;
                  this.$refs.customer_name.setSearchData(this.customer_name);
                  this.customer_label = this.customer_name;
                } 
                if ((this.customer_number != '' && this.customer_number != null) || this.filter_customer_number != '') {
                  var c_number = this.customer_number != '' && this.customer_number != null ? this.customer_number : this.filter_customer_number;
                  this.customer_number = c_number;
                  this.$refs.customer_number.setSearchData(this.customer_number);
                  this.customer_label = this.customer_number;
                } 
                if ((this.customer_email != '' && this.customer_email != null) || this.filter_customer_email != '') {
                  var c_email = this.customer_email != '' && this.customer_email != null ? this.customer_email : this.filter_customer_email;
                  this.customer_email = c_email;
                  this.$refs.customer_email.setSearchData(this.customer_email);
                  this.customer_label = this.customer_email;
                }
              }
              this.show_customer_modal = false;
              if(this.partial_paid == true){
                this.show_partial_payment_modal = true
              }else{
                this.submit_form_data();
              }
            }
          });
          break;
      }
      // this.$refs.barcode.focus();
    },

    return_product() {
      this.cart = {};
      // this.order ={};
      //  this.product_list = {};
      this.show_action_btn = false;
      this.discount_rate = 0.0;
      this.total = 0.0;
    },

    get_return_order() {
      this.show_error_message = false;
      this.processing = true;

      var returnformData = new FormData();
      returnformData.append('access_token', window.settings.access_token);
      returnformData.append('transaction_date', this.transaction_date);
      returnformData.append('transaction_id', this.transaction_id);

      axios
        .post(this.get_order_receipt_link, returnformData)
        .then(response => {
          if (response.data.status_code == 200) {
            if (typeof response.data.link != 'undefined' && response.data.link != '') {
              this.this_order_slack = response.data.data;
              // this.order_receipt_pdf = response.data.link;

              var loadingTask = pdf.createLoadingTask(response.data.link);

              // console.log(loadingTask);
              loadingTask.promise.then(pdf => {
                this.pdf_page_count = pdf.numPages;
                this.order_receipt_pdf = response.data.link;
              });

              this.processing = false;
            } else {
              this.processing = false;
              this.show_error_message = true;
              this.error_message = response.data.msg;
              return false;
            }
          } else {
            try {
              var error_json = JSON.parse(response.data.msg);
              this.loop_api_errors(error_json);
            } catch (err) {
              this.server_errors = response.data.msg;
              this.show_action_btn = false;
            }
            this.error_class = 'error';
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    calcTransactionPendingAmount(transaction_data, total_order_amount){
      var total_paid_amount = 0;
      transaction_data.forEach((transaction, index) => {
        total_paid_amount += parseFloat(transaction.amount);
      }); 
      return parseFloat(total_order_amount) - total_paid_amount;
    },
    calculateTransactionAmount(transaction_data){
      var total_paid_amount = 0;
      transaction_data.forEach((transaction, index) => {
        total_paid_amount += parseFloat(transaction.amount);
      }); 
      return total_paid_amount;
    },
    get_hold_list() {
      this.show_hold_list_modal = true;
      this.hold_list_processing = true;
      this.$off('close');

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('order_status', this.orders_listing_status);

      axios
        .post('/api/get_hold_list', formData)
        .then(response => {
          if (response.data.status_code == 200) {
            this.hold_list_processing = false;
            this.hold_order_list = response.data.data;
          }
        })
        .catch(error => {
          console.log(error);
        });

      this.$on('close', function() {
        this.show_hold_list_modal = false;
      });
    },

    update_customer() {
      if (this.customer_name == '' && this.customer_number == '' && this.customer_email == '') {
        this.customer_label = this.default_label;
      } else {
        if (this.customer_name != '') {
          this.customer_label = this.customer_name;
          this.$refs.customer_name.setSearchData(this.customer_name);
        }
        if (this.customer_number != '') {
          this.customer_label = this.customer_number;
          this.$refs.customer_number.setSearchData(this.customer_number);
        }
        if (this.customer_email != '') {
          this.customer_label = this.customer_email;
          this.$refs.customer_email.setSearchData(this.customer_email);
        }
      }
    },

    set_filter_customer_name(e) {
      if (this.customer_name != '') {
        this.filter_customer_name = this.customer_name;
      }
      if (typeof e.phone != 'undefined' || e.phone != '') {
        this.filter_customer_number = e.phone;
        this.customer_number = e.phone;
      }
      if (typeof e.email != 'undefined' || e.email != '') {
        this.filter_customer_email = e.email;
        this.customer_email = e.email; 
      }
    },

    set_filter_customer_number(e) {
      if (this.customer_number != '') {
        this.filter_customer_number = this.customer_number;
      }
      if (typeof e.name != 'undefined' || e.name != '') {
        this.filter_customer_name = e.name;
        this.customer_name = e.name;
      }
      if (typeof e.email != 'undefined' || e.email != '') {
        this.filter_customer_email = e.email;
        this.customer_email = e.email;
      }
    },

    set_filter_customer_email(e) {
      if (this.customer_email != '') {
        this.filter_customer_email = this.customer_email;
      }
      if (typeof e.name != 'undefined' || e.name != '') {
        this.filter_customer_name = e.name;
      }
      if (typeof e.phone != 'undefined' || e.phone != '') {
        this.filter_customer_number = e.phone;
      }
    },

    set_customer_autocomplete_empty() {
      this.$refs.customer_name.setSearchData('');
      this.$refs.customer_number.setSearchData('');
      this.$refs.customer_email.setSearchData('');
    },

    run_clock() {
      setInterval(() => {
        this.current_date_time = moment().format('DD/MM/YYYY, h:mm:ss a');
      }, 1000);
    },

    set_table_based_on_order_type() {
      if (this.restaurant_order_type == 'TAKEWAY' || this.restaurant_order_type == 'DELIVERY') {
        this.restaurant_table = '';
      }
    },

    close_register() {
      this.$off('submit');
      this.$off('close');

      this.get_register_total_amount();
      this.show_close_register_modal = true;

      /*console.clear();
      console.log(JSON.stringify(this.cart));
      return false;*/

      this.$on('submit', function() {
        this.$validator.validateAll('close_register_form').then(isValid => {
          if (isValid) {
            this.processing = true;
            var formData = new FormData();

            formData.append('access_token', window.settings.access_token);
            // formData.append("closing_amount", this.closing_amount);
            formData.append('credit_card_slips', this.credit_card_slips);
            formData.append('cheques', this.cheques);

            axios
              .post(this.close_register_api_link, formData)
              .then(response => {
                if (response.data.status_code == 200) {
                  alert(response.data.msg);
                  this.show_response_message(response.data.msg, 'SUCCESS');
                  if (typeof response.data.link != 'undefined' && response.data.link != '') {
                    if (response.data.new_tab == true) {
                      window.open(response.data.link, '_blank');
                    } else {
                      window.location.href = response.data.link;
                    }

                    setTimeout(function() {
                      location.reload();
                    }, 1000);
                  } else {
                    setTimeout(function() {
                      location.reload();
                    }, 1000);
                  }
                } else {
                  this.processing = false;
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.loop_api_errors(error_json);
                  } catch (err) {
                    this.close_register_server_errors = response.data.msg;
                  }
                  this.error_class = 'error';
                }
              })
              .catch(error => {
                console.log(error);
              });
          }
        });
      });

      this.$on('close', function() {
        this.show_close_register_modal = false;
      });
    },

    get_register_total_amount() {
      this.register_amount_processing = true;

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);

      axios
        .post('/api/get_register_order_amount', formData)
        .then(response => {
          if (response.data.status_code == 200) {
            // this.closing_amount = response.data.data.closing_amount;
            this.cheques = response.data.data.cash_amount;
            this.credit_card_slips = response.data.data.credit_amount;
            // this.closing_amount = response.data.data.total_amount;
            // this.register_cash_amount = response.data.data.total_cash_amount;
            // this.register_credit_amount = response.data.data.total_credit_amount;
            this.register_amount_processing = false;
          }
        })
        .catch(error => {
          this.register_amount_processing = false;
          console.log(error);
        });
    },

    get_running_orders_list(page) {
      this.show_running_order_quickpanel = true;

      if (typeof page === 'undefined') {
        page = 1;
      }

      if (this.show_running_order_quickpanel == true) {
        this.running_order_list_processing = true;
        var formData = new FormData();
        formData.append('access_token', window.settings.access_token);

        axios
          .post('/api/get_running_order_list?page=' + page, formData)
          .then(response => {
            if (response.data.status_code == 200) {
              this.running_order_list_processing = false;
              var running_order_list = response.data.data.data;

              if (page == 1) {
                this.running_order_list = [];
              }
              running_order_list.forEach(item => {
                this.running_order_list.push(item);
              });

              this.running_order_has_more_items = response.data.data.links.has_more_items;
              this.running_order_total_records = response.data.data.links.total_records;
              this.running_order_next_page = response.data.data.links.has_more_items == true ? response.data.data.links.current_page + 1 : 1;
            }
          })
          .catch(error => {
            console.log(error);
          });
      }
    },

    calculate_duration(created_date) {
      var moment = require('moment-timezone');
      var tz = moment.tz.guess();

      var today = moment();
      var date_obj = new Date(created_date);
      var moment_obj = moment.unix(date_obj).tz(tz);

      var duration = moment.duration(today.diff(moment_obj));
      var minutes = Math.abs(Math.round(duration.as('minutes')));
      minutes = isNaN(minutes) ? '-' : minutes;
      return minutes;
    },

    update_duration_for_products() {
      for (var i = 0; i < this.running_order_list.length; i++) {
        var duration = this.calculate_duration(this.running_order_list[i].create_at_utc);
        this.$set(this.running_order_list[i], 'duration', duration);
      }
    },

    tick_update_duration_for_products() {
      setInterval(() => {
        this.update_duration_for_products();
      }, 1000);
    },

    go_to_order(order_link) {
      if(this.orders_listing_status == 2){
        window.location.href = order_link;
      }
    },

    load_waiters(keywords) {
      if (typeof keywords != 'undefined') {
        if (keywords.length > 0) {
          var formData = new FormData();
          formData.append('access_token', window.settings.access_token);
          formData.append('keywords', keywords);
          formData.append('role', this.store_waiter_role_slack != null ? this.store_waiter_role_slack : '');
          formData.append('waiter', true);

          axios
            .post('/api/load_users', formData)
            .then(response => {
              if (response.data.status_code == 200) {
                this.waiter_list = response.data.data;
              }
            })
            .catch(error => {
              console.log(error);
            });
        }
      }
    },

    choose_payment_method(data) {
      this.payment_method = data.slack;
    },

    choose_business_account(data) {
      this.business_account = data.slack;
    },

    choose_restaurant_order_type(data) {
      this.restaurant_order_type = data.order_type_constant;
    },

    choose_restaurant_table(data) {
      this.restaurant_table = data.slack;
    },

    product_navigate(event) {
      var x = document.activeElement.tagName;
      switch (event.srcKey) {
        case 'left':
          if (this.product_focus === null) {
            this.product_focus = 0;
          } else if (this.product_focus > 0) {
            this.product_focus--;
          }
          break;
        case 'right':
          if (this.product_focus === null) {
            this.product_focus = 0;
          } else if (this.product_focus < this.product_list.length - 1) {
            this.product_focus++;
          }
          break;
        case 'choose':
          this.add_to_cart(this.product_list[this.product_focus]);
          break;
      }
    },

    order_confirm_navigate(event, type, data) {
      switch (type) {
        case 'payment_method':
          switch (event.srcKey) {
            case 'scroll':
              if (this.payment_method_focus === null) {
                this.payment_method_focus = 0;
              } else if (this.payment_method_focus < this.payment_methods.length - 1) {
                if (this.payment_method_focus_mode_rev == false) {
                  this.payment_method_focus++;
                } else {
                  this.payment_method_focus--;
                  if (this.payment_method_focus < 0) {
                    this.payment_method_focus = Math.abs(this.payment_method_focus);
                    this.payment_method_focus_mode_rev = false;
                  }
                }
              } else if (this.payment_method_focus == this.payment_methods.length - 1 && this.payment_method_focus != 0) {
                this.payment_method_focus--;
                this.payment_method_focus_mode_rev = true;
              }
              break;
            case 'choose':
              this.choose_payment_method(this.payment_methods[this.payment_method_focus]);
              break;
          }
          break;
        case 'business_account':
          switch (event.srcKey) {
            case 'scroll':
              if (this.business_account_focus === null) {
                this.business_account_focus = 0;
              } else if (this.business_account_focus < this.business_accounts.length - 1) {
                if (this.business_account_focus_mode_rev == false) {
                  this.business_account_focus++;
                } else {
                  this.business_account_focus--;
                  if (this.business_account_focus < 0) {
                    this.business_account_focus = Math.abs(this.business_account_focus);
                    this.business_account_focus_mode_rev = false;
                  }
                }
              } else if (this.business_account_focus == this.business_accounts.length - 1 && this.business_account_focus != 0) {
                this.business_account_focus--;
                this.business_account_focus_mode_rev = true;
              }
              break;
            case 'choose':
              this.choose_business_account(this.business_accounts[this.business_account_focus]);
              break;
          }
          break;
        case 'order_type':
          switch (event.srcKey) {
            case 'scroll':
              if (this.restaurant_order_type_focus === null) {
                this.restaurant_order_type_focus = 0;
              } else if (this.restaurant_order_type_focus < this.restaurant_order_types.length - 1) {
                if (this.restaurant_order_type_focus_mode_rev == false) {
                  this.restaurant_order_type_focus++;
                } else {
                  this.restaurant_order_type_focus--;
                  if (this.restaurant_order_type_focus < 0) {
                    this.restaurant_order_type_focus = Math.abs(this.restaurant_order_type_focus);
                    this.restaurant_order_type_focus_mode_rev = false;
                  }
                }
              } else if (this.restaurant_order_type_focus == this.restaurant_order_types.length - 1 && this.restaurant_order_type_focus != 0) {
                this.restaurant_order_type_focus--;
                this.restaurant_order_type_focus_mode_rev = true;
              }
              break;
            case 'choose':
              this.choose_restaurant_order_type(this.restaurant_order_types[this.restaurant_order_type_focus]);
              break;
          }
          break;
        case 'restaurant_table':
          switch (event.srcKey) {
            case 'scroll':
              if (this.restaurant_table_focus === null) {
                this.restaurant_table_focus = 0;
              } else if (this.restaurant_table_focus < this.vacant_tables.length - 1) {
                if (this.restaurant_table_focus_mode_rev == false) {
                  this.restaurant_table_focus++;
                } else {
                  this.restaurant_table_focus--;
                  if (this.restaurant_table_focus < 0) {
                    this.restaurant_table_focus = Math.abs(this.restaurant_table_focus);
                    this.restaurant_table_focus_mode_rev = false;
                  }
                }
              } else if (this.restaurant_table_focus == this.vacant_tables.length - 1 && this.restaurant_table_focus != 0) {
                this.restaurant_table_focus--;
                this.restaurant_table_focus_mode_rev = true;
              }
              break;
            case 'choose':
              this.choose_restaurant_table(this.vacant_tables[this.restaurant_table_focus]);
              break;
          }
          break;
      }
    },

    get_keyboard_shortcuts() {
      this.show_keyboard_shortcuts_quickpanel = true;
    },

    return_order(this_order_slack) {
      window.location.href = '/order/' + this_order_slack;
    },

    show_hold_order_confirm(hold_order_slack) {
      this.return_hold_order_slack = hold_order_slack;
      this.show_hold_return_modal = true;
      this.$on("close", function () {
          this.show_hold_return_modal = false;
      });
    },

    showPartialPaymentPopup(order_slack, transactions, total_order_amount,currency_code) {
      this.partial_payment_order_slack = order_slack;
      this.show_payment_details_modal = true;
      this.$on("close", function () {
          this.show_payment_details_modal = false;
      });
      var total_paid_amount = 0;
      transactions.forEach((transaction, index) => {
        total_paid_amount += parseFloat(transaction.amount);
      }); 
      this.partial_pay_total_paid_amount = total_paid_amount;
      this.partial_pay_pending_amount = parseFloat(total_order_amount) - total_paid_amount;
      this.partial_pay_order_total_amount = total_order_amount;
      this.partial_pay_currency_code = currency_code;
      this.partial_payment_details = transactions;
    },

    cancel_order() {
      this.processing = true;
      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('order_slack', this.return_hold_order_slack);
      axios
        .post(this.cancel_order_link, formData)
        .then(response => {
          this.processing = false;
          if (response.data.status_code == 200) {
            alert(response.data.msg);
            this.show_response_message(response.data.msg, 'SUCCESS');
            this.order_receipt_pdf = null;
            this.show_hold_return_modal = false;
            this.show_hold_list_modal = false;
            if(this.return_hold_order_slack == this.order_slack){
              window.location.href = this.new_order_route;
            }
          }
        })
        .catch(error => {
          this.register_amount_processing = false;
          console.log(error);
        });
    },

    print_order(this_order_slack) {
      // axios.get('/print_pos_receipt/' + this_order_slack)
      // .then((response)=>{
      //     console.log(response.data);
      //     //this.subcategories = response.data;
      // });

      Printjs({
        printable: '/print_pos_receipt/' + this_order_slack,
        type: 'pdf',
        showModal: true,
        onPrintDialogClose: function() {
          window.location.reload();
        }
      });
    },

    update_cash_amount() {
      if (this.payment_option == 1) {
        let cash_amount = parseFloat($('#cash_amount').val());
        // let temp_change_total = this.$options.filters.formatDecimal(this.total);
        let temp_change_total = this.total;
        this.change_amount = parseFloat(cash_amount - temp_change_total).toFixed(2);
      } else if (this.payment_option == 2) {
        let credit_amount = parseFloat($('#credit_amount').val());
        if (isNaN(credit_amount) || credit_amount == '') {
          credit_amount = 0.0;
          $('#credit_amount').val(0);
        }

        // this.total = this.$options.filters.formatDecimal(this.total);
        this.total = this.total;
        this.cash_amount2 = parseFloat(this.total - credit_amount).toFixed(2);
        if (credit_amount > this.total) {
          // let temp_credit_total = this.$options.filters.formatDecimal(
          //   this.total
          // );
          let temp_credit_total = this.total.toFixed(2);
          $('#credit_amount').val(temp_credit_total);
          this.cash_amount2 = 0.0;
        }

        if (credit_amount != '' || !isNaN(credit_amount)) {
          this.show_payment_method = true;
        } else {
          this.show_payment_method = false;
        }
      }
    },

    check_cart_ingredient_stock(product_slack, product_id, quantity) {
      let formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('product_id', product_id);
      formData.append('quantity', quantity);

      axios
        .post('/api/check_cart_ingredient_stock', formData)
        .then(response => {
          if (response.data == 1 && Object.keys(this.cart).length > 0) {
            this.cart[product_slack].is_low_on_ingredient = 1;
          } else {
            this.cart[product_slack].is_low_on_ingredient = 0;
          }
          // console.log(this.cart);
          // return response;
        })
        .catch(error => {
          console.log(error);
        });
    },
    selectModifier(product_list_item) {
      let formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('product_slack', product_list_item.slack);

      axios
        .post('/api/get_product_modifiers', formData)
        .then(response => {
          this.product_modifiers = response.data.data.modifiers;
          this.selected_product_id = response.data.data.product_id;
          this.selected_product_slack = response.data.data.product_slack;
          if (Object.keys(this.cart).length > 0) {
            for (var cart_id in this.cart) {
              if (this.cart[cart_id].product_slack == this.selected_product_slack) {
                this.already_added_product = true;
              }
            }
          }

          this.show_modifier_modal = true;
          this.product_list_item = product_list_item;
        })
        .catch(error => {
          console.log(error);
        });

      this.product_modifiers = '';
    },
    selectModifierForCombo(product_list_item) {
      let formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('product_slack', product_list_item.slack);
      
      axios
        .post('/api/get_product_modifiers', formData)
        .then(response => {
          this.combo_product_modifiers = response.data.data.modifiers;
          this.show_combo_modifier_modal = true;

        })
        .catch(error => {
          console.log(error);
        });

    },
    applyModifier(product_modifier_option, add_as_new_product) {
      product_modifier_option = product_modifier_option.flat();

      var modifiers = [];

      var total_product_modifier_price = 0;

      for (var i = 0; i < this.product_modifiers.length; i++) {
        for (var j = 0; j < this.product_modifiers[i].modifier_options.length; j++) {
          if (product_modifier_option.includes(this.product_modifiers[i].modifier_options[j].id)) {
            var modifier_price = this.product_modifiers[i].modifier_options[j].price;

            modifiers.push({
              id: this.product_modifiers[i].modifier_options[j].id,
              price: modifier_price,
              label:
                this.product_modifiers[i].label.toUpperCase() +
                ':' +
                this.product_modifiers[i].modifier_options[j].label.toUpperCase() +
                ' | ' +
                modifier_price +
                ' ' +
                this.store_currency
            });

            // console.log(modifier_price,'modifier price');
            total_product_modifier_price += parseFloat(modifier_price);
          }
        }
      }

      this.product_list_item.modifiers = modifiers;
      this.product_list_item.modifier_price = parseFloat(total_product_modifier_price);
      this.product_list_item.modifier_tax = (parseFloat(total_product_modifier_price) * this.store_tax_percentage) / 100;
      this.show_modifier_modal = false;
      this.add_to_cart(this.product_list_item);
    },
    applyModifierForCombo(product_modifier_option, add_as_new_product) {
      product_modifier_option = product_modifier_option.flat();

      var modifiers = [];

      var total_product_modifier_price = 0;
      
      for (var i = 0; i < this.combo_product_modifiers.length; i++) {
        for (var j = 0; j < this.combo_product_modifiers[i].modifier_options.length; j++) {
          if (product_modifier_option.includes(this.combo_product_modifiers[i].modifier_options[j].id)) {
            var modifier_price = this.combo_product_modifiers[i].modifier_options[j].price;

            modifiers.push({
              id: this.combo_product_modifiers[i].modifier_options[j].id,
              price: modifier_price,
              label:
                this.combo_product_modifiers[i].label.toUpperCase() +
                ':' +
                this.combo_product_modifiers[i].modifier_options[j].label.toUpperCase() +
                ' | ' +
                modifier_price +
                ' ' +
                this.store_currency
            });

            total_product_modifier_price += parseFloat(modifier_price);
          }
        }
      }

      this.selected_combo_product.modifiers = modifiers;
      this.selected_combo_product.modifier_tax = (parseFloat(total_product_modifier_price) * this.store_tax_percentage) / 100;
      this.selected_combo_product.modifier_price = total_product_modifier_price;

      this.update_combo_price();
      this.show_combo_modifier_modal = false;
      
    },
    skipModifier() {
      this.show_modifier_modal = false;
      this.add_to_cart(this.product_list_item);
    },
    remove_modifier(product_slack, modifier_option_id) {
      const index = this.cart[product_slack].modifiers.map(e => e.id).indexOf(modifier_option_id);
      Vue.delete(this.cart[product_slack].modifiers, index);

      this.update_prices();
    },
    uniqueCartId() {
      var length = 16;
      var result = [];
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
      }
      return result.join('');
    },
    clearSearch(search_term) {
      if (search_term == '') {
        this.setSubcategory(this.subcategory_slack, this.category_type);
      }
    },
    searchByEnterKey(e) {
      if (e.keyCode === 13 && this.search != '') {
        this.searchProductsByKeyword(this.search, 'KEYWORD');
      }
    },
    searchProductsByKeyword(search_term, search_type) {
      // alert(search_term);
      // alert(search_type);

      if (search_term != '') {
        var formData = new FormData();
        formData.append('access_token', window.settings.access_token);
        this.products_counter.offset = 0;
        formData.append('products_counter', JSON.stringify(this.products_counter));
        formData.append('subcategory_slack', this.subcategory_slack);
        formData.append('category_type', this.category_type);
        formData.append('search_term', search_term);
        formData.append('search_type', search_type);

        axios
          .post('/api/load_pos_products_by_keyword', formData)
          .then(response => {
            this.product_list = response.data.data.products_data;
            this.products_counter.offset = response.data.data.products_counter.offset;
            this.products_counter.total = response.data.data.products_counter.total;

            if (search_type == 'BARCODE' && this.product_list) {
              if (this.product_list[0].product_modifiers.length) {
                this.selectModifier(this.product_list[0]);
              } else {
                this.add_to_cart(this.product_list[0]);
              }

              this.search_barcode = '';
              this.clearSearch('');
            }
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    setSubcategory(subcategory_slack, category_type) {
      this.subcategory_slack = subcategory_slack;
      this.category_type = category_type;

      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      this.products_counter.offset = 0;
      formData.append('products_counter', JSON.stringify(this.products_counter));
      formData.append('subcategory_slack', subcategory_slack);
      formData.append('category_type', category_type);

      axios
        .post('/api/load_pos_products_by_subcategory', formData)
        .then(response => {
          
          this.product_list = response.data.data.products_data;
          this.products_counter.offset = response.data.data.products_counter.offset;
          this.products_counter.total = response.data.data.products_counter.total;

          this.combos = response.data.data.combos;

        })
        .catch(error => {
          console.log(error);
        });
    },
    loadMoreProducts() {
      
      const top = this.$refs.product_list_container.scrollTop;
      const height = this.$refs.product_list_container.clientHeight;
      const total_height = this.$refs.product_list_container.scrollHeight;

      const percentage = ((top + height) * 100) / total_height;

      
      const total_products = this.products_counter.total;
      const total_loaded_products = this.products_counter.offset;

      if (percentage > 90 && total_loaded_products <= total_products) {
        if (this.load_products_flag) {
          this.load_products_flag = false;
          this.product_loader = true;

          var formData = new FormData();
          formData.append('access_token', window.settings.access_token);
          formData.append('products_counter', JSON.stringify(this.products_counter));

          axios
            .post('/api/load_pos_products', formData)
            .then(response => {
              if(response.data.data.products_data.length){
                this.product_list = this.product_list.concat(response.data.data.products_data);
                this.products_counter.offset = response.data.data.products_counter.offset;
                this.products_counter.total = response.data.data.products_counter.total;
                this.load_products_flag = true;
                this.product_loader = false;
              } else {
                this.load_products_flag = true;
                this.product_loader = false;
              }
            })
            .catch(error => {
              console.log(error);
            });
        }
      }
    },

    onBarcodeScanned(barcode) {
      this.searchProductsByKeyword(barcode, 'BARCODE');
    },
    clearCart() {
      this.cart = {};
      this.cash_amount = 0;
      this.change_amount = 0;
      this.discount_rate = 0;
      this.cash_amount2 = 0;
      this.card_name = '';
      this.transaction_id = '';
      this.store_discount_code = 0;
      $('#credit_amount, #cash_amount, #discount_rate_credit').val(0);
      $('#discount_type, #discount_type_credit').val(1);
      if (store_discount_code != 0 || $('#store_discount_code_credit').val() != 0) {
        $('#discount_type, #discount_rate, #discount_type_credit, #discount_rate_credit').removeAttr('disabled');
      }
      this.total_amount = 0;
      this.total = 0;
      this.total_discount_amount = 0;
      this.discounted_sub_total = 0;
      this.tax_total = 0;
      this.item_level_total_tax_details = [];
      this.item_level_total_tobacco_tax_details = [];
      this.store_level_tax_component_objects = [];
      this.specific_prod_total_price_after_discount = 0;
    },

    checkPaymentMethod(payment_method_slack) {
      var formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('payment_method_slack', payment_method_slack);

      axios
        .post('/api/load_payment_options', formData)
        .then(response => {
          if (response.data.status_code == 200) {
            if (response.data.data['qr_code_data'] !== undefined) {
              this.stcpay_qr_code = JSON.stringify(response.data.data.qr_code_data);
            } else {
              this.stcpay_qr_code = '';
            }
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    setActiveClass(element) {
      this.active_class = element;
    },
    calculate_item_total_price(product_list_item){
      var price_without_tax = parseFloat(product_list_item.sale_amount_excluding_tax);
      if(isNaN(price_without_tax)){
        price_without_tax = 0;
      }
      var tax_percentage = product_list_item.tax_percentage;
      var is_tobacco_tax = product_list_item.is_tobacco_tax;
      var tobacco_tax_percentage = product_list_item.tobacco_tax_percentage;
      var total_price_include_tax = 0;
      if(is_tobacco_tax > 0 && tobacco_tax_percentage > 0 ){
        var tobacco_tax_amount = this.calculate_tax(price_without_tax, tobacco_tax_percentage);
        tobacco_tax_amount = parseFloat(tobacco_tax_amount);
        if(isNaN(tobacco_tax_amount)){ tobacco_tax_amount = 0;}
        var price_with_tobacco_tax  = price_without_tax + tobacco_tax_amount;
        price_with_tobacco_tax = parseFloat(price_with_tobacco_tax);
        if(isNaN(price_with_tobacco_tax)){ price_with_tobacco_tax = 0;}

        var tax_amount = this.calculate_tax(price_with_tobacco_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_with_tobacco_tax + tax_amount;
      }else if(tax_percentage > 0){
        var tax_amount = this.calculate_tax(price_without_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_without_tax + tax_amount;
      }else{
        total_price_include_tax = price_without_tax;
      }
      // if(total_price_include_tax == 0){
      //   total_price_include_tax = '--';
      // }
      return total_price_include_tax;
    },

    calc_selcted_item_total_price(product_list_item, type = 'with_modif'){
      // console.log('product_list_item ====',product_list_item);
      // return;
      if(type == 'with_modif'){
        var price_without_tax = parseFloat(product_list_item.price_with_modifier);
      }else{
        var price_without_tax = parseFloat(product_list_item.price);
      }
      if(isNaN(price_without_tax)){
        price_without_tax = 0;
      }
      var tax_percentage = product_list_item.tax_percentage;
      var is_tobacco_tax = product_list_item.is_tobacco_tax;
      var tobacco_tax_percentage = product_list_item.tobacco_tax_percentage;
      var total_price_include_tax = 0;
      if(is_tobacco_tax > 0 && tobacco_tax_percentage > 0 ){
        var tobacco_tax_amount = this.calculate_tax(price_without_tax, tobacco_tax_percentage);
        tobacco_tax_amount = parseFloat(tobacco_tax_amount);
        if(isNaN(tobacco_tax_amount)){ tobacco_tax_amount = 0;}
        var price_with_tobacco_tax  = price_without_tax + tobacco_tax_amount;
        price_with_tobacco_tax = parseFloat(price_with_tobacco_tax);
        if(isNaN(price_with_tobacco_tax)){ price_with_tobacco_tax = 0;}

        var tax_amount = this.calculate_tax(price_with_tobacco_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_with_tobacco_tax + tax_amount;
      }else if(tax_percentage > 0){
        var tax_amount = this.calculate_tax(price_without_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_without_tax + tax_amount;
      }else{
        total_price_include_tax = price_without_tax;
      }
      return total_price_include_tax;
    },

    calc_selcted_item_gift_total_price(product_list_item, type = 'with_modif'){
      //console.log('product_list_item ====',product_list_item);
      // return;
      if(type == 'with_modif'){
        if(product_list_item.gift_flag == true){
          var price_without_tax = parseFloat(product_list_item.price_with_modifier_actual);
        }
      }else{
        var price_without_tax = parseFloat(product_list_item.price_actual);
      }
      if(isNaN(price_without_tax)){
        price_without_tax = 0;
      }
      var tax_percentage = product_list_item.tax_percentage;
      var is_tobacco_tax = product_list_item.is_tobacco_tax;
      var tobacco_tax_percentage = product_list_item.tobacco_tax_percentage;
      var total_price_include_tax = 0;
      if(is_tobacco_tax > 0 && tobacco_tax_percentage > 0 ){
        var tobacco_tax_amount = this.calculate_tax(price_without_tax, tobacco_tax_percentage);
        tobacco_tax_amount = parseFloat(tobacco_tax_amount);
        if(isNaN(tobacco_tax_amount)){ tobacco_tax_amount = 0;}
        var price_with_tobacco_tax  = price_without_tax + tobacco_tax_amount;
        price_with_tobacco_tax = parseFloat(price_with_tobacco_tax);
        if(isNaN(price_with_tobacco_tax)){ price_with_tobacco_tax = 0;}

        var tax_amount = this.calculate_tax(price_with_tobacco_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_with_tobacco_tax + tax_amount;
      }else if(tax_percentage > 0){
        var tax_amount = this.calculate_tax(price_without_tax, tax_percentage);
        tax_amount = parseFloat(tax_amount);
        if(isNaN(tax_amount)){ tax_amount = 0;}
        total_price_include_tax  = price_without_tax + tax_amount;
      }else{
        total_price_include_tax = price_without_tax;
      }
      return total_price_include_tax;
    },
    formatQuantityDecimal(cart_slack,event){
      let keyCode = (event.keyCode ? event.keyCode : event.which);
      if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.cart[cart_slack].quantity.indexOf('.') != -1)) { 
        event.preventDefault();
      }
      if(this.cart[cart_slack].quantity!=null && this.cart[cart_slack].quantity.indexOf(".")>-1 && (this.cart[cart_slack].quantity.split('.')[1].length > 1)){
        event.preventDefault();
      }
    }

    // searchProduct(){
    //   if(this.search != ''){
    //     var products = this.product_list_on_load.filter(product => {
    //       product.barcode = (product.barcode != null) ? product.barcode.toLowerCase() : null;
    //       product.name = product.name.toLowerCase();
    //       this.search = this.search.toLowerCase();
    //       if(product.barcode != null && product.barcode == this.search){
    //         if( product.product_modifiers.length  ){
    //           this.selectModifier(product);
    //         }else{
    //           this.add_to_cart(product);
    //         }
    //         return product;
    //       }else if(product.name.includes(this.search)){
    //         return product;
    //       }
    //     });
    //     this.product_list = products;
    //     // console.log('in');
    //   }else{
    //     this.product_list = this.product_list_on_load;
    //     // console.log('out');
    //   }
    //   // console.log(this.product_list);
    // }
    
  }
};
</script>
<style scoped>
  .cart-custom-price {
      background: #f5f4f4;
      padding: 5px;
      border-radius: 6px;
  }
  .cart-custom-price .cart-product-quantity {
    height: 32px;
    font-size: 18px;
}
.box {
  position: relative;
  /* background-color: #ddd; */
  overflow: hidden;
}

.ribbon {
  position: absolute;
  display: inline-block;
  
  top: 0.3em;
  right: 0.8em;
  
  max-width: 5em;
  
  color: #fff;
  
  z-index: 1;
}

.ribbon::after {
  position: absolute;
    top: -1.5em;
    right: -8em;
    content: "";
    height: 8em;
    width: 17em;
    transform: rotatez(45deg);
    background: linear-gradient(240.13deg, #3E63CA -3.35%, #BE0683 103.16%);
    z-index: -1;
}
.custom-width-card .nav-tabs > li {
    max-width: 16.6%;
    font-size: 11px;
    line-height: 120%;
}
.custom-width-card .nav-tabs > li .border {
    min-height: 92px;
}

</style>