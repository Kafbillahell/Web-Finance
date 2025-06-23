<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiTabungan;
use App\Models\Dompet;

class TransaksiTabunganController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiTabungan::with('dompet')->latest()->get();
        return view('history-tabungan.index', compact('transaksis'));
    }

    public function destroy(TransaksiTabungan $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.tabungan')->with('success', 'Transaksi berhasil dihapus');
    }
}
