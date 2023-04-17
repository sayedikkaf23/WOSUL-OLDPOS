class Measurements{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/measurements',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'measurements.label' },
                // { name: 'measurements.measurement_category_id' },
                { name: 'master_status.label' },
                { name: 'measurements.created_at' },
                { name: 'measurements.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 1, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}