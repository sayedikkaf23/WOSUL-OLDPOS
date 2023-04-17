<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsitePartner;

class PartnerController extends Controller
{
    public function index()
    {
        $data['website_partners'] = WebsitePartner::active()->get();
        return view('partner', compact('data'));
    }
}
