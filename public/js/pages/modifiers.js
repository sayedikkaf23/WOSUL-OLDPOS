class Modifiers{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/modifiers',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'modifiers.label' },
                { name: 'master_status.label' },
                { name: 'modifiers.created_at' },
                { name: 'modifiers.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [5] }
            ]
        });
    }
}