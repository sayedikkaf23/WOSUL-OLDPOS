class Orders{
    load_listing_table(customer_slack,list_type,commonDatatablesData){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/customer_order_invoice',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token,
                    list_type : list_type,
                    customer_slack : customer_slack,
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'orders.order_id','visible' : false },
                { name: 'orders.order_id' },
                { name: 'orders.id' },
                { name: 'orders.value_date' },
                { name: 'orders.total_order_amount' },
                { name: 'orders.status' },
                { name: 'orders.created_at' },
                { name: 'orders.updated_at' },
                { name: 'orders.id' }
            ],
            order: [[ 0, "desc" ]],
            /*columnDefs: [
                { "orderable": false, "targets": [5] }
            ]*/
        });
    }
}