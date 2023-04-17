class ComboGroups{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/combo_groups',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'combo_groups.parent_name' },
                { name: 'combo_groups.name' },
                { name: 'combo_groups.created_at' },
                { name: 'combo_groups.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 4, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [5] }
            ]
        });
    }
}
