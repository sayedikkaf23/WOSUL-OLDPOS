class Combos{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/combos',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'combos.name' },
                { name: 'combos.category' },
                { name: 'combos.available_sizes' },
                { name: 'combos.products' },
                { name: 'combos.discount' },
                { name: "master_status.label" },
                { name: 'combos.created_at' },
                { name: 'combos.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 7, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [9] }
            ]
        });
    }
}
