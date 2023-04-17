class TaxNames{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/tax_names',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'tax_names.percentage' },
                { name: 'tax_names.percentage' },
                // { name: 'master_status.label' },
                { name: 'tax_names.is_default' },
                { name: 'tax_names.created_at' },
                { name: 'tax_names.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 3, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}
