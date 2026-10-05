<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputDataController extends Controller
{
    public function pangan()
    {
        return view('input.pangan');
    }

    public function lpg()
    {
        return view('input.lpg');
    }

    public function bbm()
    {
        return view('input.bbm');
    }

    public function iph()
    {
        return view('input.iph');
    }
}
