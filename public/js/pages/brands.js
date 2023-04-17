class Brands{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/brands',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'brands.label' },
                { name: 'master_status.label' },
                { name: 'brands.created_at' },
                { name: 'brands.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 2, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [5] }
            ]
        });
    }
}
