<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;

// Models
use App\Models\Expresspay;

// use GuzzleHttp\Exception;
trait ExpresspayTrait
{

    public function define_headers()
    {
        $this->params['headers'] = [
            'Accept' => 'application/json',
            'Accept-Language' => 'ar',
        ];
    }
   
    /* To add a new product From Wosul Using ZID API */
    public function expresspay_authenticate($form_params)
    {

        $this->define_headers();

        $api_url = env('EXPRESSPAY_ENDPOINT') . '/api/v1/session';

        $client = new \GuzzleHttp\Client();

        try {

            $response = $client->post($api_url, [
                'headers' => $this->params['headers'],
                'json' => $form_params,
            ]);

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {

                $data = json_decode($response->getBody()->getContents(), true);

                return [
                    'status' => true,
                    'message' => 'Successful Authentication',
                    'data' => $data,
                ];
            }
        } catch (RequestException $e) {

            return [
                'status' => false,
                'message' => $e->getResponse()->getReasonPhrase() . " from Expresspay",
            ];
        }
    }

}
