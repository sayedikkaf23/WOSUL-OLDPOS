class InventoryReport {
    load_listing_table(commonDatatablesData, to_date, from_date,store_id,product_type) {
      "use strict";
      var listing_table = $("#listing-table").DataTable({
        "ordering": false,
        ajax: {
          url: "/api/inventory_report",
          type: "POST",
          data: {
            access_token: window.settings.access_token,
            to_date: to_date,
            from_date: from_date,
            store_id: store_id,
            product_type: product_type,
          },
        },
        oLanguage: commonDatatablesData,
        columns: [
          { name: "products.name" },
          { name: "products.branch" },
          { name: "products.opening_quantity" },
          { name: "products.purchase_quantity" },
          { name: "products.transfer_from_quantity" },
          { name: "products.transfer_to_quantity" },
          { name: "products.sold_quantity" },
          { name: "products.returned_quantity" },
          { name: "products.damaged_quantity" },
          { name: "products.adjustment_quantity" },
          { name: "products.stock_return_quantity" },
          { name: "products.available_quantity" },
        ],
        order: [[0, "asc"]],
        columnDefs: [
          { orderable: false, targets: [0] },
          {
            targets: [0],
            className: "text-right",
          },
        ],
        destroy: true,
     });
    }
  }