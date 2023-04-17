class Inventory_count_item {

    constructor() {
        var self = this;
        this.data = {};
        this.data.access_token = window.settings.access_token;
    }

    load_listing_table(commonDatatablesData) {
        "use strict";
        self.listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/inventory_count/get_inventory_count_item_data',
                type: 'POST',
                data: this.data
            },
            scrollX: false,
            language: {
                'lengthMenu': "_MENU_",
                'searchPlaceholder': 'Search',
                'search': '',
                'paginate': {
                'previous': '<i class="fas fa-angle-left"></i> <span class="sr-only">Previous</span>',
                'next': '<i class="fas fa-angle-right"></i> <span class="sr-only">Next</span>'
                }
            },
            columns: [
                { name: 'item_name' },
                { name: 'original_quantity' },
                { name: 'entered_quantity' },
                { name: 'variance_quantity' },
                { name: 'variance_quantity_percentage' },
            ],
            order: [[0, "asc"]]
        });
    }
}

document.addEventListener("load-count-item-table", function (e) {

    var inventory_count_item_object = new Inventory_count_item();

    self.listing_table.settings()[0].ajax.data = inventory_count_item_object.data;
    self.listing_table.ajax.reload();
});

$(function(){
    
    var inventory_count_item_object = new Inventory_count_item();

    self.listing_table.settings()[0].ajax.data = inventory_count_item_object.data;
    self.listing_table.ajax.reload();
});