"use strict";
window.Vue = require('vue');

export var mixin = {
    methods: {
        loop_api_errors(error_json) {
            this.server_errors = '';
            var error_string = '';
            $.each(error_json, (key, value) => {
                error_string += value[0] + '<br>';
            });
            this.server_errors = error_string;
            return this.server_errors;
        },

        show_response_message(message, title = '') {
            Vue.notify({
                type: 'success',
                group: 'notification_bar',
                title: title,
                text: message,
            });

        },

        show_error_response_message(message, title = '') {
            Vue.notify({
                type: 'error',
                group: 'notification_bar',
                title: title,
                text: message,
            });

        }
    }
}