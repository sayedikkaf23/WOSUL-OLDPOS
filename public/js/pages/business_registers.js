class BusinessRegisters{
    load_listing_table(){
        "use strict";
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '/api/business_registers',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            // drawCallback: function (settings) { 
            //     // Here the response
            //     var response = settings.json;
            //     console.log(response);
            // },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'user.fullname' },
                { name: 'business_registers.opening_date' },
                { name: 'business_registers.opening_amount' },
                { name: 'business_registers.cash' },
                { name: 'business_registers.expected_cash_amount' },
                { name: 'business_registers.variance_cash' },
                { name: 'business_registers.credit' },
                { name: 'business_registers.expected_credit_amount' },
                { name: 'business_registers.variance_credit' },
                { name: 'business_registers.manual_drawer_opens' },
                { name: 'business_registers.closing_date' },
                { name: 'business_registers.created_at' },
                { name: 'business_registers.updated_at' },
                { name: 'user_created.fullname' }
            ],
            order: [[ 10, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [16] }
            ]
        });
    }
}