class ModifierOptions{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/modifier_options',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'modifier_options.label' },
                { name: 'master_status.label' },
                { name: 'modifier_options.price' },
                { name: 'modifier_options.created_at' },
                { name: 'modifier_options.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}