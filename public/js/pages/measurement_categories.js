class MeasurementCategories{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/measurement_categories',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'measurement_categories.label' },
                { name: 'master_status.label' },
                { name: 'measurement_categories.created_at' },
                { name: 'measurement_categories.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [5] }
            ]
        });
    }
}