class DamageOrders {
  load_listing_table(commonDatatablesData, to_date, from_date) {
    "use strict";
    var listing_table = $("#listing-table").DataTable({
      ajax: {
        url: "/api/damage_orders",
        type: "POST",
        data: {
          access_token: window.settings.access_token,
          to_date: to_date,
          from_date: from_date,
        },
      },
      oLanguage: commonDatatablesData,
      columns: [
        { name: "order_return_product.id" },
        { name: "order_return_product.name" },
        { name: "order_return_product.branch" },
        { name: "order_return_product.branch_reference" },
        { name: "order_return_product.order_type" },
        { name: "order_return_product.added_by" },
        { name: "order_return_product.order_reference" },
        { name: "order_return_product.time" },
        { name: "order_return_product.quantity" },
        {
          name: "order_return_product.total_amount",
          render: function(data, type, row) {
            return Number(data).toFixed(2);
          },
        },
        { name: "order_return_product.reason" },
      ],
      order: [[0, "asc"]],
      columnDefs: [
        { orderable: false, targets: [10] },
        {
          targets: [3],
          className: "text-right",
        },
      ],
      footerCallback: function(row, data, start, end, display) {
        var api = this.api();

        var intVal = function(i) {
          return typeof i === "string"
            ? i.replace(/[\$,]/g, "") * 1
            : typeof i === "number"
            ? i
            : 0;
        };
        let total = api
          .column(9)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over this page
        let total_quantity = api
          .column(8, { page: "current" })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);
      },
      destroy: true,
      drawCallback: function(settings) {
        let total = 0.0;
        let total_quantity = 0;
        if (settings.json.data.length > 0) {
          $("#footerTotal").show();
          for (let product in settings.json.data) {
            total_quantity += Number.parseInt(settings.json.data[product][8]);
            total += Number.parseFloat(settings.json.data[product][9]);
          }
          $("#totalQuantity").html(total_quantity);
          $("#totalAmount").html(total.toFixed(2));
        } else {
          $("#footerTotal").hide();
        }
      },
    });
  }
}
