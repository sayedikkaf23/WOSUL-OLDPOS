class Tables {
    load_listing_table(commonDatatablesData) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/tables',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,

            columns: [
                { name: 'restaurant_tables.table_number' },
                { name: 'restaurant_tables.no_of_occupants' },
                { name: 'master_status.label' },
                { name: 'restaurant_tables.created_at' },
                { name: 'restaurant_tables.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[3, "desc"]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}