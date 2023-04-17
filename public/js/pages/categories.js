class Categories{
    load_listing_table(category_type,commonDatatablesData){
        "use strict";

        // console.log(category_type);
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/categories',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token,
                    category_type : category_type
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'category.label' },
                { name: 'category.parent' },
                { name: 'category.category_code' },
                { name: 'master_status.label' },
                { name: 'category.created_at' },
                { name: 'category.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 5, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [7] }
            ]
        });
    }
}