<template>
    <transition name="modal">
        <div :style="{ 'z-index': z_index_attr }">
            <div class="modal-wrapper modal" >
                <div class="modal-dialog-centered">
                    <div class="modal-container"  v-bind:class="[ modal_w ]">

                        <div class="modal-header">
                            <span class="modal-title" v-bind:class="[ modal_title_class ]">
                                <slot name="modal-header">
                                    Confirm
                                </slot>
                            </span>
                            
                            <button type="button" @click="$emit('close')" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <slot name="modal-body">
                            Are you sure you want to proceed?
                            </slot>
                        </div>

                        <div class="modal-footer" v-show="show_footer">
                            <slot name="modal-footer" v-show="hide_modal_footer">
                                <button type="button" class="btn btn-light" @click="$emit('close')">Cancel</button>
                            </slot>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-mask modal"></div>
        </div>
    </transition>
</template>

<script>
    'use strict';
    
    export default {
        data(){
            return{
                modal_w   : this.modal_width,
                hide_modal_footer : false,
                z_index_attr : (this.z_index != null) ? this.z_index + '!important' : 9999
            }
        },
        props: {
            modal_width: String,
            modal_title_class: String,
            show_footer: {
                type : Boolean,
                default : true
            },
            z_index : String
        },
        mounted() {
            // console.log(this.show_footer);
        }

    }
</script>
