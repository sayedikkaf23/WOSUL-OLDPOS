class QuantityAdjustment {
  constructor(status) {
    var self = this;
    this.data = {};
    this.data.access_token = window.settings.access_token;
    this.data.status = status;
  }
  load_listing_table(commonDatatablesData) {
    ("use strict");
    self.listing_table = $("#listing-table").DataTable({
      ajax: {
        url: "/api/quantity_adjustments",
        type: "POST",
        data: this.data,
      },
      oLanguage: commonDatatablesData,
      columns: [
        { name: "quantity_adjustments.reference" },
        { name: "quantity_adjustments.branch" },
        { name: "quantity_adjustments.action" },
        { name: "quantity_adjustments.reason" },
        { name: "quantity_adjustments.status" },
        { name: "quantity_adjustments.submitted_at" },
        { name: "quantity_adjustments.created_by" },
        { name: "quantity_adjustments.action" },
      ],
      order: [[0, "desc"]],
    });

    document.addEventListener("quantity_adjustment_type_filter", function(e) {
      var product_type_filter = e.detail;

      var product_list = new QuantityAdjustment(product_type_filter);

      self.listing_table.settings()[0].ajax.data = product_list.data;
      self.listing_table.ajax.reload();
    });
  }
}
