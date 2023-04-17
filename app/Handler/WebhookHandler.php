<?php 
namespace App\Handler;

use \Spatie\WebhookClient\ProcessWebhookJob;

class WebhookHandler extends ProcessWebhookJob{

    public function handle(){
        logger('Hi Webhook');
        logger($this->webhookCall);
    }

}
