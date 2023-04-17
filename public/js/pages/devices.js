class Devices{
  load_listing_table(commonDatatablesData){
      "use strict";
      var listing_table = $('#listing-table').DataTable({
          ajax: {
              url  : '/api/devices',
              type : 'POST',
              data : {
                  access_token : window.settings.access_token
              }
          },
          oLanguage: commonDatatablesData,
          columns: [
              {
                  name: "devices.image_path",
                  render: function(data, type, row) {
                    if (data != null) {
                      return '<img src="' + data + '" width="50" height="50" alt=""/>';
                    } else {
                      return "-";
                    }
                  },
                },
              { name: 'devices.title' },
              { name: 'devices.title_ar' },
              { name: 'devices.description' },
              { name: 'devices.description_ar' },
              { name: 'devices.price' },
              { name: 'master_status.label' },
              { name: 'devices.created_at' },
              { name: 'devices.updated_at' },
          ],
          order: [[ 1, "desc" ]],
          columnDefs: [
              { "orderable": false, "targets": [9] }
          ]
      });
  }
}