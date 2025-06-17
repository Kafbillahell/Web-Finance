<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\User;

class DompetController extends Controller
{
    public function index(Request $request)
    {
        $dompets = Dompet::latest()->get();
        
        if (!$dompets) {
            $dompets = collect([]);
        }

        // Get recent transactions
        $recentTransactions = Transaksi::with('dompet', 'kategori')
            ->latest()
            ->take(5)
            ->get();

        $total_dompet = $dompets->count();
        $total_saldo = $dompets->sum('saldo');
        $jenis_dompet = $dompets->pluck('nama')->unique()->count();
        $dompet_terpilih = $dompets->take(4);

        return view('dompet.index', compact('dompets', 'total_dompet', 'total_saldo', 'jenis_dompet', 'dompet_terpilih', 'recentTransactions'));
    }
    public function create()
    {
        $users = User::all();
        return view('dompet.form', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        Dompet::create($request->all());

        return redirect()->route('dompet.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Dompet $dompet)
    {
        $users = User::all();
        return view('dompet.form', compact('dompet', 'users'));
    }

    public function update(Request $request, Dompet $dompet)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        $dompet->update($request->all());

        return redirect()->route('dompet.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Dompet $dompet)
    {
        $dompet->delete();

        return redirect()->route('dompet.index')->with('success', 'Berhasil dihapus');
    }
}
