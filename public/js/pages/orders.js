class Orders {
  load_listing_table(commonDatatablesData, to_date, from_date, order_status) {
    "use strict";
    var listing_table = $("#listing-table").DataTable({
      ajax: {
        url: "/api/orders",
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
        { name: "orders.id" },
        { name: "orders.reference_number" },
        { name: "orders.order_number" },
        { name: "orders.customer_phone" },
        { name: "orders.customer_email" },
        {
          name: "orders.total_order_amount",
          className : "text-right",
          render: function(data, type, row) {
            if (row[5].toLowerCase().includes("return")) {
              return "0.00";
            } else {
              return String(Number(data).toFixed(2))
                .split(".")[1]
                .trim() == "99"
                ? Math.round(Number(data))
                : data;
            }
          },
        },
        { name: "orders.has_combo", className: "text-center" },
        {
          name: "master_status.label",
          render: function(data, type, row) {
            if (data.toLowerCase().includes("return")) {
              return data.replace("Return", "Return/Damaged");
            } else {
              return data;
            }
          },
        },
        { name: "orders.value_date" },

        { name: "orders.updated_at" },
        { name: "user_created.fullname" },
        { name: "" },
      ],
      // order: [[0, "asc"]],
      aaSorting: [[0, 'desc']],
      columnDefs: [
        { orderable: false, targets: [11] },
        {
          targets: [6],
          className: "text-right",
        },
      ],

      footerCallback: function(row, data, start, end, display) {
        var api = this.api(),
          data;

        var intVal = function(i) {
          return typeof i === "string"
            ? i.replace(/[\$,]/g, "") * 1
            : typeof i === "number"
            ? i
            : 0;
        };
        // Total over all pages
        var total = api.column(5).data().reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(5).footer()).append(
          "<tr><th><strong> Total</strong></th><td><strong> " +total.toFixed(2) + "</strong></td></tr>");
      },

      destroy: true,
      drawCallback: function(settings) {
        $("#listing-table").append(
          "<tr><td><strong>" +
            settings.json.total +
            "</strong></td><td></td><td></td><td></td><td></td><td class='text-right'>" +
            settings.json.total_amount +
            "</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"
        );
        $("#listing-table").append(
          "<tr><td><strong>" +
            settings.json.grand_total +
            "</strong></td><td></td><td></td><td></td><td></td><td class='text-right'>" +
            settings.json.grand_total_amount +
            "</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"
        );
      },
    });
  }
}
