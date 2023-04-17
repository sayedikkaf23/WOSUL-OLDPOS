<?php

namespace App\Billing;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Illuminate\Http\Request;

class HyperPayBilling implements BillingInterface
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Get the billing data.
     *
     * @return array
     */
    public function getBillingData(): array
    {
        return [
        'billing.state' => $this->request->billingState,
        'billing.city' => $this->request->billingCity,
        'billing.country' => $this->request->billingCountry,
        'billing.street1' => $this->request->billingStreet,
        'billing.postcode' => $this->request->billingPostCode,
        /*'supportedNetworks' => ['.mada','.visa','.masterCard'],
        'supportedCountries'=> [".SA"],
        'merchantCapabilities' => "supports3DS",*/
        'testMode'=>env('TESTMODE')
        ];
    }
}