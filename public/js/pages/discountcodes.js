class Discountcodes {
  load_listing_table() {
    "use strict";
    var listing_table = $("#listing-table").DataTable({
      ajax: {
        url: "/api/discount_codes",
        type: "POST",
        data: {
          access_token: window.settings.access_token,
        },
      },
      oLanguage: commonDatatablesData,
      columns: [
        { name: "discount_codes.label" },
        {
          name: "discount_codes.discounttype",
          render: function(data, type, row) {
            let discount_type = data.split("_")[0];
            let discount_code = data.split("_")[1];
            if (discount_type == "inventory" || discount_type == "cashier") {
              return `automatic(${discount_type})`;
            } else {
              return `code(${discount_code})`;
            }
          },
        },
        {
          name: "discount_codes.discount_percentage",
          render: function(data, type, row) {
            return Number(data).toFixed(2);
          },
        },
        {
          name: "discount_codes.discount_value",
          render: function(data, type, row) {
            return Number(data).toFixed(2);
          },
        },
        { name: "master_status.label" },
        { name: "discount_codes.created_at" },
        { name: "discount_codes.updated_at" },
        { name: "user_created.fullname" },
      ],
      order: [[5, "desc"]],
      columnDefs: [{ orderable: false, targets: [8] }],
    });
  }
}
