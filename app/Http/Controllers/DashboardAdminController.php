<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Kategori;
use App\Models\Tabungan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    //
    public function index()
    {
        // Statistik utama
        $userCount = User::count();
        $dompetCount = Dompet::count();
        $transaksiCount = Transaksi::count();
        $tabunganCount = Tabungan::count();

        // Data grafik bulanan
        $monthlyTotals = Transaksi::selectRaw("DATE_FORMAT(transaksi.created_at, '%Y-%m') as bulan")
            ->selectRaw("SUM(CASE WHEN kategori.tipe = 'pemasukan' THEN transaksi.nominal ELSE 0 END) as pemasukan")
            ->selectRaw("SUM(CASE WHEN kategori.tipe = 'pengeluaran' THEN transaksi.nominal ELSE 0 END) as pengeluaran")
            ->join('kategori', 'transaksi.kategori_id', '=', 'kategori.id')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Kategori pengeluaran untuk pie chart
        $adminKategoriChart = Kategori::where('tipe', 'pengeluaran')
            ->withSum('transaksi as total_nominal', 'nominal')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'total_nominal' => $item->total_nominal ?? 0
                ];
            });

        // Transaksi terbaru dari semua user
        $recentAllTransactions = Transaksi::with(['user', 'dompet', 'kategori'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'dompetCount',
            'transaksiCount',
            'tabunganCount',
            'monthlyTotals',
            'adminKategoriChart',
            'recentAllTransactions'
        ));
    }
}
