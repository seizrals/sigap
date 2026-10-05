<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EwsController extends Controller
{
    public function index()
    {
        return view('ews.index');
    }

    public function monitorKabupaten()
    {
        return view('ews.monitor-kabupaten');
    }
}
