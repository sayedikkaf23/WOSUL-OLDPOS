<template>

    <input type="color" v-model="color_code" @change="updateInvoiceColor()">

</template>  

<script>
    'use strict';

    export default{

        data(){
            return {
                color_code : (this.invoice_color_code != null) ? this.invoice_color_code : '',
            }
        },
        props: {
            invoice_color_code : String,
            invoice_id : Number,
        },

        mounted(){
            // console.log(this.invoice_id);
        },

        methods: {
            updateInvoiceColor(){

                var formData = new FormData();
                formData.append("access_token", window.settings.access_token);
                formData.append('invoice_id',this.invoice_id);
                formData.append('color_code',this.color_code);
                
                axios.post('/api/update_invoice_color_code', formData).then( (response) => {
                    console.log(response);
                    location.reload();
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
