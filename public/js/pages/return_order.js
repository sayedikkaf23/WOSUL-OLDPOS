class ReturnOrders {
  load_listing_table(commonDatatablesData, to_date, from_date, order_status) {
    "use strict";
    var listing_table = $("#listing-table").DataTable({
      ajax: {
        url: "/api/return_orders",
        type: "POST",
        data: {
          access_token: window.settings.access_token,
          to_date: to_date,
          from_date: from_date,
          status: order_status,
        },
      },
      oLanguage: commonDatatablesData,
      columns: [
        { name: "order_return.order_number" },
        { name: "order_return.customer_phone" },
        { name: "order_return.customer_email" },
        { name: "order_return.total_order_amount" },
        { name: "master_status.label" },
        {
          name: "master_status.return_type",
          render: function(data, type, row) {
            if (data.toLowerCase() == "return") {
              return `<span class="label green-label">Return</span>`;
            } else if (data.toLowerCase() == "damage") {
              return `<span class="label yellow-label">Damage</span>`;
            }
          },
        },
        { name: "order_return.created_at" },
        { name: "order_return.updated_at" },
        { name: "user_created.fullname" },
      ],
      order: [[7, "desc"]],
      columnDefs: [
        { orderable: false, targets: [9] },
        {
          targets: [3],
          className: "text-right",
        },
      ],
      destroy: true,
    });
  }
}
