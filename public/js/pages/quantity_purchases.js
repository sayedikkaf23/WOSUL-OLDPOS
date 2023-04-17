class QuantityPurchase{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/quantity_purchases',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'quantity_purchases.po_number' },
                { name: 'quantity_purchases.po_reference',render: function(data, type, row) {
                    if(data=="null")
                    {
                        return "-";
                    }
                    else
                    {
                        return data;
                    }
                }
                 },
                { name: 'quantity_purchases.supplier_name' },
                { name: 'quantity_purchases.order_date' },
                { name: 'quantity_purchases.order_due_date' },
                { name: 'quantity_purchases.total_order_amount' },
                { name: 'master_status.label' },
                { name: 'quantity_purchases.created_at' },
                { name: 'quantity_purchases.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 7, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [10] }
            ]
        });
    }
}