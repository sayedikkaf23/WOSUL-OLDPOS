class Merchants {
    load_listing_table(commonDatatablesData) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/merchants',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,

            columns: [
                { name: 'merchants.name' },
                { name: 'merchants.company_name' },
                { name: 'merchants.company_url' },
                { name: 'merchants.company_phone' },
                { name: 'merchants.company_email' },
                { name: 'master_status.label' },
                { name: 'merchants.no_of_branches' },
                { name: 'merchants.expire_date' },
                { name: 'merchants.created_at' },
                { name: 'merchants.updated_at' },
                { name: 'merchants.action' },
            ],
            order: [[1, "desc"]],
            columnDefs: [
                { "orderable": false, "targets": [8] }
            ]
        });
    }
}