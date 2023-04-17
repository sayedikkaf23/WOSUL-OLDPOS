<template>
   <div>
    <div class="form-check form-switch">
        <input class="form-check-input mt-2" v-model="status" type="checkbox" role="switch" id="flexSwitchCheckDefault">
        <label class="form-check-label" for="flexSwitchCheckDefault">{{ $t("Enable Multiple Prices") }}</label>
    </div>
   </div>
</template>

<script>
    'use strict';
    
    export default {
        data(){
            return{
                status : false
            }
        },
        mounted() {
            // console.log('price component loaded');
            this.getPriceStatus();
        },
        watch: {
            status : function(value){
                this.changePriceStatus(value);
            }
        },
        methods : {
            getPriceStatus(){

                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                
                axios.post('/api/price/status', formData).then((response) => {

                    this.status = (response.data.is_price_enabled) ? true : false;

                })
                .catch((error) => {
                    console.log(error);
                });
            },
            changePriceStatus(value){

                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                formData.append("status", (value == true) ? 1 : 0);
                
                axios.post('/api/price/status/update', formData).then((response) => {

                    console.log(response.data);
                    return false;
                    // this.status = (response.data.is_price_enabled) ? true : false;

                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
