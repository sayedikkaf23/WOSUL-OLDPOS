<template>
  <div class="navbar-header" style="height:55px;">
    <header class="header">
      <div class="header-left">
        <div class="dropdown d-flex flex-column mr-2 ml-2 mt-2">
          <a href="javascript:void(0);" class="" @click="toggleMainMenu()">
            <div class="d-flex flex-column">
              <img style="width:55px;" src="/images/menu/menu.png" alt="Menu" class="align-self-center" />
              <span class="pl-3" style="margin-top:-8px;">{{ $t('Menu') }}</span>
            </div>
          </a>

          <div class="dropdown-menu dropdown-menu-right" style="lang=='en'?'left: 0;right: auto;':''" v-bind:class="show_main_menu ? 'd-block' : ''">
            <ul v-for="(menu, menu_index) in menus" :key="menu_index" class="nav nav-pills dashboard-menu" style="flex-wrap: nowrap !important;">
              <li v-for="(rs, index) in menu" :key="index" @click="[rs.label != 'HRM' && rs.route == null ? openSubMenu(rs.menu_key) : '']">
                <!-- For HR & Finance Menu -->
                <!-- For rest of the menus of Wosul -->
                <a v-if="rs.label != 'HRM'" :href="rs.route">
                  <img :src="'/images/menu/' + rs.image" alt="" style="width:50px;" />
                  <br />
                  <span>{{ $t(rs.label) }}</span>
                </a>

              </li>
            </ul>
          </div>

          <div
            class="dropdown-menu dropdown-menu-right"
            style="min-width:23rem !important;lang=='en'?'left: 0;right: auto;':'' "
            v-bind:class="show_sub_menu ? 'd-block' : ''"
          >
            <ul class="nav nav-pills dashboard-menu ">
              <li></li>
              <li></li>
              <li @click="toggleMainMenu()">
                <i class="fa fa-arrow-left"></i>
              </li>
            </ul>

            <ul v-for="(item, index) in sub_menus" class="nav nav-pills dashboard-menu" style="flex-wrap: nowrap !important;">
              <li v-for="(submenu, index) in item" :key="index" v-if="submenu.route && submenu.label != 'Product Label'">
                <a :href="submenu.route" style="text-decoration:none;padding:auto;">
                  <img :src="'/images/menu/' + submenu.image" alt="" style="width:50px;" />
                  <br />
                  <span>{{ $t(submenu.label) }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="logo">
          <a href="#">
            <!-- <img src="images/logo.png" alt="" /> -->
          </a>
        </div>
        <div class="select-store">
          <div class="select-style-1">
            <Select2 v-model="store" :options="all_stores" :value="id" :title="text" @change="switchStore(store)" style="width:200px !important" />
          </div>
        </div>
        <div class="multi-language">
          <LanguageSwitcher :languages="languages" :selected_language="selected_language" on_footer="false"></LanguageSwitcher>
        </div>
        <button class="custom-btn" v-if="price_list.length" @click="show_switch_price">{{ $t('Switch Price')}}</button>
      </div>
      <div class="header-right">
        <div class="header-btnset">

          <a :href="invoice_menu_route" class="custom-btn custom-btn-primary ml-3">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" class="pl-1">
              <rect x="5.73535" width="1.52941" height="13" rx="0.764706" fill="currentColor" />
              <rect x="5.73535" width="1.52941" height="13" rx="0.764706" fill="currentColor" />
              <rect x="5.73535" width="1.52941" height="13" rx="0.764706" fill="currentColor" />
              <rect x="5.73535" width="1.52941" height="13" rx="0.764706" fill="currentColor" />
              <rect x="13" y="5.73535" width="1.52941" height="13" rx="0.764706" transform="rotate(90 13 5.73535)" fill="currentColor" />
              <rect x="13" y="5.73535" width="1.52941" height="13" rx="0.764706" transform="rotate(90 13 5.73535)" fill="currentColor" />
              <rect x="13" y="5.73535" width="1.52941" height="13" rx="0.764706" transform="rotate(90 13 5.73535)" fill="currentColor" />
              <rect x="13" y="5.73535" width="1.52941" height="13" rx="0.764706" transform="rotate(90 13 5.73535)" fill="currentColor" />
            </svg>
            <span>{{ $t('Create Invoice') }}</span>
          </a>
          <a v-if="show_add_pos_button && merchant_id != 0" :href="pos_menu_route" class="custom-btn ">
            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg" class="pl-1">
              <path
                d="M4.75 1.58325L2.375 4.74992V15.8333C2.375 16.2532 2.54181 16.6559 2.83875 16.9528C3.13568 17.2498 3.53841 17.4166 3.95833 17.4166H15.0417C15.4616 17.4166 15.8643 17.2498 16.1613 16.9528C16.4582 16.6559 16.625 16.2532 16.625 15.8333V4.74992L14.25 1.58325H4.75Z"
                stroke="currentColor"
                stroke-width="1.58333"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path d="M2.375 4.75H16.625" stroke="currentColor" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round" />
              <path
                d="M12.6663 7.91675C12.6663 8.7566 12.3327 9.56205 11.7388 10.1559C11.145 10.7498 10.3395 11.0834 9.49967 11.0834C8.65982 11.0834 7.85437 10.7498 7.2605 10.1559C6.66664 9.56205 6.33301 8.7566 6.33301 7.91675"
                stroke="currentColor"
                stroke-width="1.58333"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
            {{ $t('Go to cashier') }}
          </a>
        </div>

        <div class="header-user">
          <div class="avtar-inner pl-4">
            <div class="avtar-img">
              <img class="img img-thumbnail" :src="logo" alt="" />
            </div>
          </div>
          <h6 class="pr-3">{{ fullname }}</h6>
          <div
            class="user-dropdown"
            :style="{
              'margin-right': selected_language == 'Arabic - ar' ? '-150px' : ''
            }"
          >
            <ul>
              <li>
                <a :href="'/edit_store/' + store_slack">
                  {{ $t('My Store') }}
                </a>
              </li>
              <li>
                <a :href="logout_route">{{ $t('Logout') }}</a>
              </li>
            </ul>
          </div>
        </div>
        
      </div>
      <modalcomponent
        v-show="show_tax_creation_modal"
        v-on:close="show_tax_creation_modal = true"
        modal_width="modal-container-sm"
        :show_footer="false"
      >
        <template v-slot:modal-header>
          {{ $t('System Update') }}
        </template>
        <template v-slot:modal-body>
          <i class="fa fa-circle-notch fa-spin"></i> &nbsp;&nbsp;
          <span class="h5 text-danger">
            {{ $t('please wait while new tax setting is updating...') }}
          </span>
        </template>
      </modalcomponent>
      <modalcomponent
        v-show="price_switch_modal"
        v-on:close="price_switch_modal = false"
        modal_width="modal-container-md"
        :show_footer="false"
      >
        <template v-slot:modal-header>
          {{ $t('Switch Price') }}
        </template>
        <template v-slot:modal-body>
          <div class="row" v-if="price_list.length">
            <div v-for="(price,index) in price_list" :key="index" class="col-4 text-center">
              <div class="card mb-3" style="cursor:pointer;" @click="switch_price(price.id)">
                  <div class="card-body" :class="[ selected_price_id == price.id ? 'border border-1 border-primary' : '' ]" >
                    <h5>{{ price.name }}</h5>
                    <p class="text-muted">{{ price.product_count }} {{ $t('Products Affected') }} </p> 
                  </div>
                </div>
            </div>
          </div>  
        
        </template>
      </modalcomponent>
    </header>
  </div>
</template>

<script type="application/javascript">
'use strict';

import Select2 from 'v-select2-component';
import LanguageSwitcher from '../commons/language_switcher_component';

export default {
  components: {
    Select2,
    LanguageSwitcher
  },
  data() {
    return {
      show_main_menu: false,
      show_sub_menu: false,
      sub_menus: [Object],
      header_image: '',
      pos_menu: false,
      pos_menu_route: '',
      invoice_menu: false,
      invoice_menu_route: '',
      logo: this.store_logo == null || !this.checkIfImageExists(this.store_logo) ? '/images/logo-icon-64x64.png' : this.store_logo,
      topnav_heading_image: null,
      show_add_invoice_button: this.invoice_menu_status.active,
      show_add_pos_button: this.pos_menu_status.active,
      store: this.store_id,
      show_tax_creation_modal : !this.is_store_tax_exists,
      price_switch_modal : false,
      selected_store_tax_id : null,
      selected_price_id : (this.price_list_data != null) ? (this.price_list_data.selected_price_id != null ) ? this.price_list_data.selected_price_id : 0 : 0,
      price_list : (this.price_list_data != null) ? this.price_list_data.prices : [],
    };
  },
  props: {
    menus: Array,
    pos_menu_status: Object,
    invoice_menu_status: Object,
    logout_route: '',
    store_id: Number,
    store_slack: String,
    store_name: String,
    user_slack: String,
    subdomain_name: String,
    store_logo: String,
    lang: String,
    fullname: String,
    merchant_id: Number,
    all_stores: [Object, Array],
    is_master: Number,
    languages: [Array, Object],
    selected_language: String,
    is_store_tax_exists: Array,
    default_store_taxes: Array,
    price_list_data : [Object,Array],
  },
  mounted() {
    console.log('Header Component Loaded');
    // console.log(this.invoice_menu_status.active);
    // console.log(this.logo);
    this.pos_menu = this.pos_menu_status.active;
    this.invoice_menu = this.invoice_menu_status.active;
    this.pos_menu_route = this.pos_menu ? this.pos_menu_status.route : '';
    this.invoice_menu_route = this.invoice_menu ? this.invoice_menu_status.route : '';
    document.addEventListener('click', this.close);

    // console.log(location.href);
    let page_name = location.href.substring(location.href.lastIndexOf('/') + 1);
    if (page_name == 'dashboard') {
      this.topnav_heading_image = 'images/dashboard-logo.jpg';
    }

    if(!this.is_store_tax_exists){
      this.updateTaxSettingForAllStores();
    }
  },
  methods: {
    checkIfImageExists(image_url) {
      var http = new XMLHttpRequest();
      http.open('HEAD', image_url, false);
      http.send();
      return http.status != 404;
    },
    toggleMainMenu() {
      this.show_main_menu = !this.show_main_menu;
      this.show_sub_menu = false;
    },
    openSubMenu(menu_key) {
      let formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('menu_key', menu_key);

      this.show_main_menu = false;
      axios.post('/api/get_sub_menus', formData).then(response => {
        if (response.is_sub_menu == 0) {
          window.location.href = response.route;
        } else {
          this.sub_menus = _.chunk(_.toArray(response.data.sub_menu), 3);
          this.show_sub_menu = true;
        }
      });
    },
    close(e) {
      if (!this.$el.contains(e.target)) {
        this.show_main_menu = false;
      }
    },
    switchStore(store) {
      let formData = new FormData();
      formData.append('access_token', window.settings.access_token);
      formData.append('store', store);

      this.show_main_menu = false;
      axios.post('/api/switch_store', formData).then(response => {
        location.reload();
      });
    },
    updateTaxSettingForAllStores(){

      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("selected_store_tax_id", this.selected_store_tax_id);

      axios
        .post("/api/update_tax_setting_for_all_stores", formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.show_response_message(response.data.msg, 'SUCCESS');
            this.is_store_tax_exists = true;
            location.reload();
          }
        })
        .catch((error) => {
          console.log(error);
        });

    },
    show_switch_price(){
      this.price_switch_modal = true;

    },
    switch_price(price_id){
      
    var formData = new FormData();
    
    formData.append('access_token', window.settings.access_token);
    formData.append('price_id', price_id);

    axios
      .post('api/update_price_id', formData)
      .then(response => {
        if (response.data.status_code == 200) {
          window.location.reload();
        } else {
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
    }
  },
  beforeDestroy() {
    document.removeEventListener('click', this.close);
  },
  
  
};
</script>

<style scoped>
.select2-container {
  width: 100% !important;
}
</style>
