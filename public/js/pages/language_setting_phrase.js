class Language_Setting_Phrase {
    load_listing_table(commonDatatablesData) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '{{url("api/language/phrase/".$slack)}}',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'language_setting_phrase.lang_phrase' },
                { name: 'language_setting_phrase.lang_value' },
                { name: 'master_status.label' },
                { name: 'language_setting_phrase.created_at' },
                { name: 'language_setting_phrase.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[2, "desc"]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    }
}