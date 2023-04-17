class Subscriptions {
    load_listing_table(commonDatatablesData, to_date, from_date, order_status) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/get_news_subscriptions',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token,
                    to_date: to_date,
                    from_date: from_date,
                    status: order_status
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'newsletter_subscription.email' },
                { name: 'master_status.label' },
                { name: 'newsletter_subscription.created_on' },
            ],
            order: [
                [1, "desc"]
            ],
            columnDefs: [
                { "orderable": false, "targets": [2] }
            ],
            destroy: true,
        });
    }
}