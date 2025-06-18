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
        $transaksi = Transaksi::with(['user', 'dompet', 'kategori'])
            ->where('user_id', Auth::id())
            ->whereYear('created_at', now()->year)
            ->get();

        // Calculate monthly totals
        $monthlyTotals = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = $transaksi->where('tipe', 'pemasukan')
                ->whereMonth('created_at', $month)
                ->sum('nominal');
            
            $expense = $transaksi->where('tipe', 'pengeluaran')
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $monthlyTotals[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 10)),
                'income' => $income,
                'expense' => $expense
            ];
        }

        $total_income = $transaksi->where('tipe', 'pemasukan')->sum('nominal');
        $total_expense = $transaksi->where('tipe', 'pengeluaran')->sum('nominal');

        $formatted_income = number_format($total_income, 2, ',', '.');
        $formatted_expense = number_format($total_expense, 2, ',', '.');

        return view('transaksi.index', compact('transaksi', 'formatted_income', 'formatted_expense', 'monthlyTotals'));
    }

    public function create()
    {
        $dompets = Auth::user()->dompets;
        $kategoris = Kategori::where('id_user', Auth::id())->get();
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
            'user_id' => Auth::id(),
            'dompet_id' => $request->dompet_id,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
        ]);

        $dompet = Dompet::findOrFail($request->dompet_id);
        if($request->tipe == 'pengeluaran') {
            $dompet->saldo -= $request->nominal;
        } else {
            $dompet->saldo += $request->nominal;
        }

        $dompet->save();

        if($request->tipe == 'pengeluaran' && $dompet->saldo < $request->nominal) {
            return back()->with('error', 'Saldo tidak mencukupi untuk transaksi ini.');
        }
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
