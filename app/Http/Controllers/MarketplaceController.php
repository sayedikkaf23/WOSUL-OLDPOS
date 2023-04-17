<?php

namespace App\Http\Controllers;

use App\Models\WebsiteMarketplace;
use GuzzleHttp\Psr7\Request;
use mysqli;

class MarketplaceController extends Controller
{
    public function index()
    {
        $data['title'] = 'Marketplace';

        $data['marketplaces'] = WebsiteMarketplace::active()->get();

        return view('marketplace', compact('data'));
    }

    public function detail($lang, $slack)
    {

        $data['title'] = 'Marketplace Detail';
        $data['marketplace']  = WebsiteMarketplace::with('specifications')->where('slack', $slack)->first();
        return view('marketplace-detail', compact('data'));
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
}
