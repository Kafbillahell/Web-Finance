<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dompet = Dompet::where('user_id', $user->id)->get();
        $totalSaldo = $dompet->sum('saldo');
        $totalPemasukan = Transaksi::where('user_id', $user->id)->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Transaksi::where('user_id', $user->id)->where('tipe', 'pengeluaran')->sum('nominal');
        $totalTabungan = Tabungan::where('user_id', $user->id)->sum('saldo');

        $kategoriTransaksi = \App\Models\Kategori::all();

        // Loop dan hitung total nominal per kategori untuk user login
        foreach ($kategoriTransaksi as $kategori) {
            $total = $kategori->transaksi()
                ->where('user_id', $user->id)
                ->sum('nominal');

            // Tambahkan properti manual untuk digunakan di view
            $kategori->total_nominal = $total;
        }

        // Filter hanya kategori yang ada transaksinya dan urutkan dari kecil ke besar
        $kategoriTransaksi = $kategoriTransaksi
            ->filter(function ($item) {
                return $item->total_nominal > 0;
            })
            ->sortBy('total_nominal') // 🔁 URUTKAN dari nominal terkecil
            ->values();

        // Get recent transactions
        $recentTransactions = Transaksi::with(['dompet', 'kategori'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'dompet', 'totalSaldo', 'totalPemasukan', 'totalPengeluaran', 'totalTabungan', 'recentTransactions', 'kategoriTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'dompet_id' => 'required|exists:dompets,id',
            'tipe' => 'required|in:deposit,withdraw',
        ]);

        Transaksi::create([
            'user_id' => Auth::id(),
            'dompet_id' => $request->dompet_id,
            'kategori_id' => null, // Bisa null kalau tidak ada kategori
            'nominal' => $request->amount,
            'keterangan' => ucfirst($request->tipe) . ' saldo melalui dashboard',
            'tipe' => $request->tipe === 'deposit' ? 'pemasukan' : 'pengeluaran',
        ]);

        $dompet = Dompet::where('id', $request->dompet_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->tipe === 'withdraw') {
            if ($dompet->saldo < $request->amount) {
                return redirect()->route('dashboard')->with('error', 'Saldo tidak mencukupi.');
            }
            $dompet->saldo -= $request->amount;
        } else {
            $dompet->saldo += $request->amount;
        }

        $dompet->save();

        return redirect()->route('dashboard')->with('success', ucfirst($request->tipe) . ' berhasil.');
    }
}
