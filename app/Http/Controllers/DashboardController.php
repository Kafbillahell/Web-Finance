<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dompets = Dompet::where('user_id', auth()->id())->get();
        return view('dashboard', compact('dompets'));
    }

    public function form()
    {
        return view('form');
    }
}
