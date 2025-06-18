<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Transaksi;
use App\Models\Tabungan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dompet = Dompet::where('user_id', $user->id)->get();
        $totalSaldo = $dompet->sum('saldo') ?? 0;
        $totalPemasukan = Transaksi::where('user_id', $user->id)->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Transaksi::where('user_id', $user->id)->where('tipe', 'pengeluaran')->sum('nominal');
        $totalTabungan = Tabungan::where('user_id', $user->id)->sum('saldo');

        $recentTransactions = Transaksi::with(['dompet', 'kategori'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'dompet',
            'totalSaldo',
            'totalPemasukan',
            'totalPengeluaran',
            'totalTabungan',
            'recentTransactions'
        ));
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
        $tipeTransaksi = $tipe === 'withdraw' ? 'pengeluaran' : 'pemasukan';

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

        // Cari atau buat kategori otomatis
        $kategori = Kategori::firstOrCreate(
            ['nama' => ucfirst($tipe)],
            ['tipe' => $tipeTransaksi]
        );

        Transaksi::create([
            'user_id' => $user->id,
            'dompet_id' => $dompet->id,
            'kategori_id' => $kategori->id,
            'nominal' => $amount,
            'keterangan' => ucfirst($tipe) . ' saldo melalui dashboard',
            'tipe' => $tipeTransaksi,
        ]);

        return redirect()->route('dashboard')->with('success', ucfirst($tipe) . ' saldo berhasil.');
    }
}
