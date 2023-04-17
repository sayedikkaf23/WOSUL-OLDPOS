class Inventory_count {

    constructor() {
        var self = this;
        this.data = {};
        this.data.access_token = window.settings.access_token;
    }

    load_listing_table(commonDatatablesData) {
        "use strict";
        self.listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/inventory_count/get_inventory_count_data',
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
                { name: 'id' },
                { name: 'store_name' },
                { name: 'user_name' },
                { name: 'business_date' },
                { name: 'status_html' },
                { name: 'updated_at' },
            ],
            order: [[0, "asc"]],
            // drawCallback: function(settings) {
            //     alert( 'DataTables has redrawn the table' );
            // }
        });
    }
}

document.addEventListener("filters", function (e) {
    var range = e.detail;
    console.log(range);
    var inventory_count_object = new Inventory_count();
    inventory_count_object.data.reference_no = range.reference_no;
    inventory_count_object.data.branch = range.branch;
    inventory_count_object.data.user_name = range.user_name;
    inventory_count_object.data.status = range.status;
    inventory_count_object.data.from_date = range.start_date;
    inventory_count_object.data.to_date = range.end_date;

    self.listing_table.settings()[0].ajax.data = inventory_count_object.data;
    self.listing_table.ajax.reload();
});

$(function(){
    
    var inventory_count_object = new Inventory_count();

    self.listing_table.settings()[0].ajax.data = inventory_count_object.data;
    self.listing_table.ajax.reload();

    $('#listing-table tbody').on('click', 'tr', function () {
        var access_token = window.settings.access_token;

        var data = self.listing_table.row(this).data();
        var reference_no =  data[0];

        $.ajax({
            type: "POST",
            url: view_route,
            data: {
                access_token: access_token,
                reference_no: reference_no
            },
            success: function (response) {
                if(response.status_code == 200)
                    window.location.href = '/inventory-count/view';
            }
        });
    });   
});