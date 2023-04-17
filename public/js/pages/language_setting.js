class Language_Setting{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/languages',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
             oLanguage: commonDatatablesData,
            columns: [
                { name: 'language_setting.lang_name' },
                { name: 'language_setting.lang_culture' },
                { name: 'language_setting.lang_code' },
                { name: 'master_status.label' },
                { name: 'language_setting.created_at' },
                { name: 'language_setting.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 2, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [7] }
            ]
        });
    }
}