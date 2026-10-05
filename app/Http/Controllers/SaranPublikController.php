<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaranPublikController extends Controller
{
    public function index()
    {
        return view('saran-publik.index');
    }
}
