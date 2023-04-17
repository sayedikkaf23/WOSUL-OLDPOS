class Subscriptions{
    load_listing_table(commonDatatablesData){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/subscriptions',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'subscriptions.title' },
                { name: 'subscriptions.short_description' },
                { name: 'subscriptions.plan_tenure' },
                { name: 'subscriptions.currency' },
                { name: 'subscriptions.amount' },
                { name: 'subscriptions.discount' },
                { name: 'subscriptions.discount_description' },
                { name: 'subscriptions.is_live' },
                { name: 'master_status.label' },
                { name: 'subscriptions.created_at' },
                { name: 'subscriptions.updated_at' },
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [11] }
            ]
        });
    }
}