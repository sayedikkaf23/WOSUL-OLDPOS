class Prices{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/prices',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'prices.name' },
                { name: 'prices.name_ar' },
                { name: 'master_status.label' },
                { name: 'prices.created_at' },
                { name: 'prices.updated_at' }
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [5] }
            ]
        });
    }
}