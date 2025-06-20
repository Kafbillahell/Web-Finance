<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dompet = Dompet::where('user_id', $user->id)->get();
        $totalSaldo = $dompet->sum('saldo') ?? 0;
        $totalPemasukan = Transaksi::where('user_id', $user->id)
            ->whereHas('kategori', function ($query) {
                $query->where('tipe', 'pemasukan');
            })
            ->sum('nominal');
        $totalPengeluaran = Transaksi::where('user_id', $user->id)
            ->whereHas('kategori', function ($query) {
                $query->where('tipe', 'pengeluaran');
            })
            ->sum('nominal');
        $totalTabungan = Tabungan::where('user_id', $user->id)->sum('saldo');

        $monthlyTotals = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = Transaksi::where('user_id', $user->id)
                ->whereHas('kategori', function ($query) {
                    $query->where('tipe', 'pemasukan');
                })
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $expense = Transaksi::where('user_id', $user->id)
                ->whereHas('kategori', function ($query) {
                    $query->where('tipe', 'pengeluaran');
                })
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $monthlyTotals[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 10)),
                'income' => $income,
                'expense' => $expense
            ];
        }

        $kategoriTransaksi = \App\Models\Kategori::all();

        foreach ($kategoriTransaksi as $kategori) {
            $total = $kategori->transaksi()
                ->where('user_id', $user->id)
                ->sum('nominal');

            $kategori->total_nominal = $total;
        }

        $kategoriTransaksi = $kategoriTransaksi
            ->filter(function ($item) {
                return $item->total_nominal > 0;
            })
            ->sortBy('total_nominal')
            ->values();

        $recentTransactions = Transaksi::with(['dompet', 'kategori'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->each(function ($transaction) {
                if (!$transaction->kategori) {
                    $defaultCategory = Kategori::firstOrCreate([
                        'nama' => 'Uncategorized',
                        'tipe' => $transaction->tipe, 
                        'id_user' => Auth::id(),
                    ]);
                    $transaction->kategori()->associate($defaultCategory);
                }
            });

        return view('dashboard', compact('user', 'dompet', 'totalSaldo', 'totalPemasukan', 'totalPengeluaran', 'totalTabungan', 'recentTransactions', 'kategoriTransaksi', 'monthlyTotals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'dompet_id' => 'required|exists:dompets,id',
            'tipe' => 'required|in:deposit,withdraw',
        ]);

        $user = Auth::user();

        $dompet = Dompet::where('id', $request->dompet_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $amount = $request->amount;
        $tipe = $request->tipe;
        $tipeTransaksiKategori = $tipe === 'withdraw' ? 'pengeluaran' : 'pemasukan';

        $saldoSekarang = $dompet->saldo ?? 0;

        if ($tipe === 'withdraw') {
            if ($saldoSekarang < $amount) {
                return redirect()->route('dashboard')->with('error', 'Saldo tidak mencukupi untuk withdraw.');
            }
            $saldoBaru = $saldoSekarang - $amount;
        } else {
            $saldoBaru = $saldoSekarang + $amount;
        }

        $dompet->update(['saldo' => $saldoBaru]);

        $kategori = Kategori::firstOrCreate(
            ['nama' => ucfirst($tipe), 'id_user' => $user->id],
            ['tipe' => $tipeTransaksiKategori]
        );

        Transaksi::create([
            'user_id' => $user->id,
            'dompet_id' => $dompet->id,
            'kategori_id' => $kategori->id,
            'nominal' => $amount,
            'keterangan' => ucfirst($tipe) . ' saldo melalui dashboard',
        ]);

        return redirect()->route('dashboard')->with('success', ucfirst($tipe) . ' saldo berhasil.');
    }
}