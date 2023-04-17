var lang = window.settings.language;

class BillingCounter {
    load_listing_table(commonDatatablesData) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/store_counters',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,

            columns: [
                { name: 'billing_counters.billing_counter_code' },
                { name: 'billing_counters.counter_name' },
                { name: 'master_status.label' },
                { name: 'billing_counters.created_at' },
                { name: 'billing_counters.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [
                [3, "desc"]
            ],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}