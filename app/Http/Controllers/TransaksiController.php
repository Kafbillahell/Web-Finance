<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Dompet;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        // Get all transactions
        $transaksi = Transaksi::with(['user', 'dompet', 'kategori'])
            ->latest()
            ->get();

        // Calculate totals
        $total_income = $transaksi->where('tipe', 'pemasukan')->sum('nominal');
        $total_expense = $transaksi->where('tipe', 'pengeluaran')->sum('nominal');

        // Format totals with number_format
        $formatted_income = number_format($total_income, 2, ',', '.');
        $formatted_expense = number_format($total_expense, 2, ',', '.');

        return view('transaksi.index', compact('transaksi', 'formatted_income', 'formatted_expense'));
    }

    public function create()
    {
        $dompets = Dompet::all();
        $kategoris = Kategori::all();
        return view('transaksi.form', compact('dompets', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dompet_id' => 'required|exists:dompets,id',
            'kategori_id' => 'required|exists:kategori,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        Transaksi::create([
            'user_id' => 1,
            'dompet_id' => $request->dompet_id,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $dompets = Dompet::all();
        $kategoris = Kategori::all();
        return view('transaksi.form', compact('transaksi', 'dompets', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'dompet_id' => 'required|exists:dompets,id',
            'kategori_id' => 'required|exists:kategori,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        $transaksi->update([
            'dompet_id' => $request->dompet_id,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
