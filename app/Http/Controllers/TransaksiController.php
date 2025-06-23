<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Dompet;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class TransaksiController extends Controller
{
    public function index()
    {
        // Get all transactions for the current year
        $transaksi = Transaksi::with(['user', 'dompet', 'kategori'])
            ->where('user_id', Auth::id())
            ->whereYear('created_at', now()->year)
            ->get();

        // Calculate monthly totals
        $monthlyTotals = [];
        $currentYear = now()->year;
        
        // Get all transactions for the current year
        $allTransactions = Transaksi::where('user_id', Auth::id())
            ->whereYear('created_at', $currentYear)
            ->get();

        for ($month = 1; $month <= 12; $month++) {
            // Filter transactions by month
            $monthTransactions = $allTransactions->filter(function($trx) use ($month) {
                return $trx->created_at->month === $month;
            });

            $income = $monthTransactions->where('tipe', 'pemasukan')
                ->sum('nominal');
            
            $expense = $monthTransactions->where('tipe', 'pengeluaran')
                ->sum('nominal');

            $monthlyTotals[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 10)),
                'income' => $income,
                'expense' => $expense
            ];
        }

        // Calculate totals for display

        $total_income = $transaksi->filter(function ($item) {
            return $item->kategori && $item->kategori->tipe === 'pemasukan';
        })->sum('nominal');

        $total_expense = $transaksi->filter(function ($item) {
            return $item->kategori && $item->kategori->tipe === 'pengeluaran';
        })->sum('nominal');

        $formatted_income = number_format($total_income, 2, ',', '.');
        $formatted_expense = number_format($total_expense, 2, ',', '.');
        return view('transaksi.index', compact('transaksi', 'formatted_income', 'formatted_expense'));
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
        ]);

        $kategori = Kategori::findOrFail($request->kategori_id);
        $tipe = $kategori->tipe;

        
        $dompet = Dompet::findOrFail($request->dompet_id);
        
        if ($tipe === 'pengeluaran') {
            if ($dompet->saldo < $request->nominal) {
                return back()->with('error', 'Saldo tidak mencukupi untuk transaksi ini.');
            }
            $dompet->saldo -= $request->nominal;
        } else {
            $dompet->saldo += $request->nominal;
        }

        Transaksi::create([
            'user_id' => Auth::id(),
            'dompet_id' => $request->dompet_id,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        $dompet->save();
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
        ]);

        $transaksi->update([
            'dompet_id' => $request->dompet_id,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function exportTransaksi($type)
    {
        return Excel::download(new TransaksiExport($type), 'transaksi-'.$type.'.xlsx');
    }
}
