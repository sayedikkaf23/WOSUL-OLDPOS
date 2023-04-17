<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class ReleaseController extends Controller
{

    public function index()
    {

        $data['title'] = 'Wosul Releases';
        $data['releases'] = DB::table('releases')->orderBy('id','DESC')->get();

        return view('release', compact('data'));
    }

}
