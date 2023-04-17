class Paypal_module{
    init(paypal, order_data){

        var order_amount = order_data.order_amount;
        var order_slack = order_data.order_slack;
        var new_order_link = order_data.new_order_link;
        var order_print_link = order_data.order_print_link;
        
        paypal.Buttons({
            createOrder: function(data, actions) {
                // This function sets up the details of the transaction, including the amount and line item details.
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: order_amount
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
              // This function captures the funds from the transaction.
              return actions.order.capture().then(function(details) {
                
                document.querySelector("#paypal-button-container").innerHTML = "<p>Please wait..</p>";
                
                if(details.status == 'COMPLETED'){
                    // Call your server to save the transaction
                    return fetch('/api/get_paypal_order_data', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            access_token: window.settings.access_token,
                            order_slack: order_slack,
                            order_id: data.orderID
                        })
                    }).then(function(data) {
                        document.querySelector("#paypal-button-container").innerHTML = "<p>Payment was successfull</p><br><a href='"+order_print_link+"' target='_blank'>Print Order</a>";
                        window.open(order_print_link, '_blank');
                        setTimeout(function() {
                            window.location.href = new_order_link;
                        }, 1800);
                    });
                }else{
                    document.querySelector("#paypal-button-container").innerHTML = "<p>Payment Failed!, Please try again</p>";
                }
              });
            }
        }).render('#paypal-button-container');
    }
}