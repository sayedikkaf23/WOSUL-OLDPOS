<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceivedForHyperPay extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->data['trackable_data'] = (array)json_decode($this->data['trackable_data']);
        unset($this->data['trackable_data']['amount']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@wosul.sa')
        ->subject('Your Order has been Received')
        ->view('mail.order_received_for_hyperpay')
        ->with(['data' => $this->data]);
    }
}