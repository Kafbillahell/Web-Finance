<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabunganTransaksiController extends Controller
{
    //
    public function index()
    {
        return view('history-tabungan/index');
    }
}
