class Payment_report {

    constructor() {
        var self = this;
        this.data = {};
        this.data.access_token = window.settings.access_token;
    }

    load_listing_table(commonDatatablesData) {
        "use strict";
        self.listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/get_payment_data',
                type: 'POST',
                data: this.data
            },
            scrollX: false,
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'label' },
                { name: 'amount' },
                { name: 'count' },
                { name: 'return_amount' },
                { name: 'return_count' },
                { name: 'net_amount' },
            ],
            order: [[0, "asc"]]
        });
    }
}

document.addEventListener("range", function (e) {
    var range = e.detail;

    var payment_object = new Payment_report();
    payment_object.data.from_date = range.start_date;
    payment_object.data.to_date = range.end_date;

    self.listing_table.settings()[0].ajax.data = payment_object.data;
    self.listing_table.ajax.reload();
});

$(function(){
    var start_date = vm.$refs.payment.startDateFormatted;
    var end_date = vm.$refs.payment.endDateFormatted;
    
    var payment_object = new Payment_report();
    payment_object.data.from_date = start_date;
    payment_object.data.to_date = end_date;

    self.listing_table.settings()[0].ajax.data = payment_object.data;
    self.listing_table.ajax.reload();
});