<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HyperPayPaymentController extends Controller
{
    public function checkout()
    {
        return view('checkout');
    }
    public function finalize(Request $request)
    {
        return view('payment.finalize', [
            'id' => $request->get('id'),
            'resourcePath' => $request->get('resourcePath'),
        ]);
    }

    public function checkout_new()
    {
        return view('checkout');
    }
}