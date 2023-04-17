<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Traits\ZidApiTrait;

class ZidMarketplace extends Controller
{
    use ZidApiTrait;

    public function index()
    {
        $data['title'] = 'Marketplace';

        $data['status']['zid'] = true;
        $data['status']['bonat'] = false;

        return view('marketplace.index', compact('data'));
    }

    public function zid()
    {
        $data['zid_store'] = [];

        $merchant_db = strtolower(session('logged_merchant_company_url') . "_wosul");
        $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);
        $zid_store =  mysqli_query($connect, 'SELECT * FROM zid_store WHERE store_id = "1" LIMIT 1');
        if (mysqli_num_rows($zid_store) > 0) {
            $data['zid_store'] = mysqli_fetch_assoc($zid_store);
        }
        mysqli_close($connect);

        return view('marketplace.zid', compact('data'));
    }

    //This is the function that loads the listing page
    public function oauthRedirect()
    {

        $queries = http_build_query([
            'client_id' => env('ZID_CLIENT_ID'),
            'redirect_uri' =>  env('APP_URL') . '/oauth/callback',
            'response_type' => 'code'
        ]);

        return redirect('https://oauth.zid.sa' . '/oauth/authorize?' . $queries);
    }

    public function oauthCallback()
    {

        $client = new Client();
        $params['form_params'] = array(
            'grant_type' => 'authorization_code',
            'client_id' => env('ZID_CLIENT_ID'),
            'client_secret' => env('ZID_CLIENT_SECRET'),
            'redirect_uri' => env('APP_URL') . '/oauth/callback',
            'code' => \request()->query('code')
        );

        $response = $client->post('https://oauth.zid.sa/oauth/token', $params);

        if ($response->getStatusCode() == 200) {

            $response_body = json_decode($response->getBody(), true);

            // fetching zid store id 
            $zid_profile = $this->sync_zid_get_profile($response_body['access_token'], $response_body['authorization']);

            $merchant_db = strtolower(session('logged_merchant_company_url') . "_wosul");
            $connect = mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), $merchant_db);
            $query = 'INSERT INTO zid_store(store_id,zid_store_id,authorization,access_token,expires_in) VALUES(
                "1",
                "' . $zid_profile['user']['store']['id'] . '",
                "' . $response_body['authorization'] . '",
                "' . $response_body['access_token'] . '",
                "' . $response_body['expires_in'] . '"
            )';
            mysqli_query($connect, $query);
            mysqli_close($connect);

            return redirect()->to('en/marketplace/zid')->withSuccess('Store Configured Successfully');
        } else {
            return redirect()->back()->withError('Something went wrong with configuration');
        }
    }
}
