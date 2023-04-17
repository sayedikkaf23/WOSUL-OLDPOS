class InvoiceReturn {
    load_listing_table(commonDatatablesData, to_date, from_date, invoice_status) {
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url: '/api/invoice_return',
                type: 'POST',
                data: {
                    access_token: window.settings.access_token,
                    to_date: to_date,
                    from_date: from_date,
                    status: invoice_status
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'invoices_return.return_invoice_number' },
                { name: 'invoices_return.bill_to' },
                { name: 'invoices_return.bill_to_name' },
                { name: 'invoices_return.bill_to_email' },
                { name: 'invoices_return.bill_to_contact' },
                { name: 'invoices_return.store.name' },
                { name: 'invoices_return.total_discount_amount' },
                { name: 'invoices_return.total_tax_amount' },
                { name: 'invoices_return.total_order_amount' },
                { name: 'master_status.label' },
                { name: 'invoices_return.created_at' },
                { name: 'invoices_return.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [
                [1, "desc"]
            ],
            columnDefs: [
                { "orderable": false, "targets": [13] },
                {
                    "targets": [3],
                    "className": 'text-right'
                }
            ],
            destroy: true,
        });
    }
}