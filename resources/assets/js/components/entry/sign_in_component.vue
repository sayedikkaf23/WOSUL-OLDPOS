<template>


 <div class="login-page">
                    <div class="login-form-wrap">
                      <div v-if="store_logo_path != null" class="m-logo text-center">
                            <a href="#"><img class="img img-responsive w-75" :src="store_logo_path"></a>
                        </div>
                        <h2 class="text-center">Sign In</h2>
                        <div class="form-box">
                            <p v-html="server_errors" v-bind:class="[error_class]"></p>
                            {{ message }}
                            <form @submit.prevent="submit_form">
                                <div class="form-group">
                                   <label  for="email">{{ $t("Email") }}</label>
                                    <div class="form-group-icon">
                                        <input type="email"
                                        name="email"
                                        v-model="email"
                                        v-validate="'required|email'"
                                        class="form-control"
                                        placeholder="enter your email"
                                        autocomplete="off"
                                        @change="check_auto_fill()"
                                        @focusout="list_stores(email)" 
                                        />
                                    </div>
                                    <span v-bind:class="{ error: errors.has('email') }">{{
                        errors.first("email")
                      }}</span>
                                </div>
                                <div class="form-group">
                                    <label  for="password">{{ $t("Password") }}</label>
                                    <div class="form-group-icon">
                                        <input type="password"
                        name="password"
                        v-model="password"
                        v-validate="'required'"
                        class="form-control"
                        placeholder="enter your password" />
                                    </div>
                                    <span v-bind:class="{ error: errors.has('password') }">{{
                        errors.first("password")
                      }}</span>
                                </div>
                                <div class="form-group" v-show="show_stores">
                      <label
                        >Select Office</label
                      >
                      <select
                        class="form-control"
                        name="store_id"
                        v-model="store_id"
                        placeholder="Select Office"
                      >
                        <option
                          v-for="(store,store_index) in user_stores"
                          :value="store.id" :key="store_index"
                          >{{ store.name }}</option>
                      </select>

                    </div>

                    <div class="form-group" v-show="show_stores">
                      <label
                        >Select Language</label
                      >
                     
                      <select
                        class="form-control"
                        name="language_id"
                        v-model="language_id"
                      >
                       <!-- <option
                          v-for="(language,list_language) in list_language"
                          :value="language.id" :key="list_language"
                          >{{ language.language_name }}</option> -->
                          
                          <option value="" selected>Select Language</option>
                          <option :value="1" :selected="language_id == 1" >English</option>
                          <option :value="3" :selected="language_id == 3">Arabic</option>
                      
                      </select>

                    </div>
                    
                    <div class="col-auto p-0">
                          <a href="forgot_password" class="text-link-dark forgot-password-link"><u>Forgot Password?</u></a>
                    </div>
                  
                               
                                <div class="form-submit-wrap">
                                  <button
                      type="submit"
                      class="custom-btn custom-btn-primary w-100" 
                      v-bind:disabled="processing == true"
                    >
                      
                      <span>Sign in</span>  
                      
                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
               
                    <div class="login-gallery">
                        <div class="gallery-1 splide" >
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/1.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/2.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/3.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/4.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/5.png') + ')'  } " ></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="gallery-2 splide" >
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/6.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/7.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/8.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/9.png') + ')'  } " ></div>
                                    </li>
                                    <li class="splide__slide">
                                        <div class="login-gallery-box" :style="{ 'background-image' : 'url(' + require('/public/images/login-gallery/10.png') + ')'  } " ></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                      
                    </div>
                    <div class="logo">
                        <a :href="logo_url"><img :src="'/images/logo.svg'" /></a>
                    </div>
                    <modalcomponent
      v-if="show_device_approve_modal"
      v-on:close="show_device_approve_modal = false"
    >
      <template v-slot:modal-header>
        {{ $t("Already Logged In") }}
      </template>
      <template v-slot:modal-body>
        <span v-if="device_approved == false">{{ $t("You are already logged in another device, Do you want to logout from that device and login here?") }}</span>
        <span v-else>{{ $t("You are already logged in another device") }}</span>
      </template>
      <template v-slot:modal-footer>
        <div v-if="device_approved == false">
          <button type="button" 
          class="custom-btn custom-btn-primary" 
          @click="device_approval_action(1)"
          v-bind:disabled="processing == true">
          <i
            class="fa fa-circle-notch fa-spin"
            v-if="processing == true"
          ></i>
            {{ $t("Yes") }}
          </button>
          <button
            type="button"
            class="custom-btn custom-btn-danger"
            @click="device_approval_action(0)"
            v-bind:disabled="processing == true">
            <i
              class="fa fa-circle-notch fa-spin"
              v-if="processing == true"
            ></i>
            {{ $t("No") }}
          </button>
        </div>
        <div v-else>
          <button type="button" class="btn btn-light" @click="$emit('close')">
            {{ $t("Ok") }}
          </button>
        </div>
      </template>
    </modalcomponent>
                </div>
</template>

<script>
"use strict";

export default {
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,
      show_device_approve_modal: false,
      email: "",
      password: "",
      message: this.prop_message,
      show_stores: false,
      user_stores: this.stores,
      device_action: 0,
      device_approved: false,
      store_id: 0,
      language_id: 3,
     
      logo_url : 'https://wosul.sa'
    };
  },
  props: {
    prop_message: String,
    is_demo: Boolean,
    preview_mode: Boolean,
    company_logo: String,
    stores: [Object, Array],
    store_logo_path : String,
  },
  mounted() {
    this.$on("close", function() {
      this.show_device_approve_modal = false;
    });
  },
  watch: {
    // store_id: function(value){
    //   this.
    // }
  },
  
  methods: {
  
    submit_form() {      
      if(this.email !== null && this.store_id === 0){
        this.list_stores(this.email);
      }
      else{
        var formData = new FormData();

        formData.append(
          "store_id",
          this.store_id == null ? "" : this.store_id
        );

        formData.append(
          "language_id",
          this.language_id == null ? "" : this.language_id
        );

        this.$validator.validateAll().then((result) => {
          if (result) {
            this.processing = true;

            formData.append("email", this.email == null ? "" : this.email);
            formData.append(
              "password",
              this.password == null ? "" : this.password
            );
            formData.append('login_from','WEB'); // WEB, ANDROID OR IOS
            axios
              .post("/api/user/authenticate", formData)
              .then((response) => {
                console.log(response);
                this.processing = false;
                if (response.data.status_code === 200) {
                  if (typeof response.data.data.device_status !== 'undefined') {
                    response.data.data.device_status == 0 ? this.device_approved = false : this.device_approved = true;
                    this.show_device_approve_modal = true;
                  }
                  else
                    window.location.href = response.data.link;
                } else {
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.loop_api_errors(error_json);
                  } catch (err) {
                    this.server_errors = response.data.msg;
                  }
                  this.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        });
      }
    },
    // changeStore(event) {
    //   this.store_id = event.target.value;
    // },
    list_stores(email) {
      this.show_stores = true;
      var formData = new FormData();
      formData.append("email", email);

      axios.post("api/store/list_stores", formData).then((response) => {
        if (response.data.status_code === 200) {
          this.user_stores = response.data.data;
          this.user_stores.forEach((store,i) => {
            if(i==0){
              this.store_id = store.id;
            }
          });
        }
      });

      axios.post("api/user/language", formData).then((response) => {
      
          this.language_id = response.data;
          // this.user_stores = response.data.data;
          // this.user_stores.forEach((store,i) => {
          //   if(i==0){
          //     this.store_id = store.id;
          //   }
          // });
      });

    
    },

   

    check_auto_fill(){
      if(this.show_device_approve_modal)
        this.show_device_approve_modal = false;
    },
    device_approval_action(action){
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.processing = true;

          var formData = new FormData();
          formData.append("email", this.email == null ? "" : this.email);
          formData.append(
            "password",
            this.password == null ? "" : this.password
          );
          formData.append(
            "store_id",
            this.store_id == null ? "" : this.store_id
          );
          formData.append("approve", action);

          axios
            .post("/api/user/authenticate", formData)
            .then((response) => {
              this.processing = false;
              if (response.data.status_code === 200) {
                if (typeof response.data.data.device_status !== 'undefined') {
                  response.data.data.device_status == 0 ? this.device_approved = false : this.device_approved = true;
                  action == 0 ? this.show_device_approve_modal = false : this.show_device_approve_modal = true;
                }
                else
                  window.location.href = response.data.link;
              } else {
                try {
                  var error_json = JSON.parse(response.data.msg);
                  this.loop_api_errors(error_json);
                } catch (err) {
                  this.server_errors = response.data.msg;
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
  },
};
</script>
