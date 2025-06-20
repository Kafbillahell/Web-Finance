<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DompetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dompets = Dompet::where('user_id', $user->id)
            ->latest()
            ->get();

        $recentTransactions = Transaksi::with(['dompet', 'kategori'])
            ->whereHas('dompet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // For transactions without kategori, set a default type
        $recentTransactions->each(function ($transaction) {
            if (!$transaction->kategori) {
                $transaction->setAttribute('kategori', (object)['tipe' => $transaction->tipe]);
            }
        });

        return view('dompet.index', [
            'dompets' => $dompets,
            'total_dompet' => $dompets->count(),
            'total_saldo' => $dompets->sum('saldo'),
            'jenis_dompet' => $dompets->pluck('nama')->unique()->count(),
            'dompet_terpilih' => $dompets->take(4),
            'recentTransactions' => $recentTransactions,
        ]);
    }

    public function create()
    {
        return view('dompet.form', [
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        // Create dompet
        $dompet = Dompet::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'saldo' => $request->saldo
        ]);

        // Create transaction if saldo is provided
        if ($request->saldo > 0) {
            // Get or create a default category for initial balance
            $defaultCategory = Kategori::firstOrCreate([
                'nama' => 'Initial Balance',
                'tipe' => 'pemasukan',
                'id_user' => Auth::id(),
            ]);

            // Create transaction with proper tipe and kategori
            $transaction = Transaksi::create([
                'user_id' => Auth::id(),
                'dompet_id' => $dompet->id,
                'nominal' => $request->saldo,
                'tipe' => 'pemasukan', // Use the database enum value directly
                'kategori_id' => $defaultCategory->id,
                'keterangan' => 'Pembuatan dompet baru: ' . $request->nama,
            ]);

            // Force reload the transaction with relationships
            $transaction->load('kategori');
        }

        return redirect()->route('dompet.index')->with('success', 'Dompet Berhasil ditambahkan');
    }

    public function edit(Dompet $dompet)
    {
        return view('dompet.form', [
            'dompet' => $dompet,
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, Dompet $dompet)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        // Calculate saldo difference
        $saldoDifference = $request->saldo - $dompet->saldo;

        // Update dompet
        $dompet->update($request->only(['user_id', 'nama', 'saldo']));

        // Create transaction if saldo has changed
        if ($saldoDifference != 0) {
            // Get or create a default category for balance changes
            $defaultCategory = Kategori::firstOrCreate([
                'nama' => 'Balance Change',
                'tipe' => $saldoDifference > 0 ? 'pemasukan' : 'pengeluaran',
                'id_user' => Auth::id(),
            ]);

            // Create transaction with proper tipe and kategori
            $transaction = Transaksi::create([
                'user_id' => Auth::id(),
                'dompet_id' => $dompet->id,
                'nominal' => abs($saldoDifference),
                'tipe' => $saldoDifference > 0 ? 'pemasukan' : 'pengeluaran', // Use the database enum value directly
                'kategori_id' => $defaultCategory->id,
                'keterangan' => 'Perubahan saldo dompet: ' . $request->nama,
            ]);

            // Force reload the transaction with relationships
            $transaction->load('kategori');
        }

        return redirect()->route('dompet.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Dompet $dompet)
    {
        $dompet->delete();

        return redirect()->route('dompet.index')->with('success', 'Berhasil dihapus');
    }

    public function deposit(Request $request, Dompet $dompet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        // Pastikan amount positif
        $amount = abs($request->amount);

        $dompet->saldo += $amount;
        $dompet->save();

        Transaksi::create([
            'dompet_id' => $dompet->id,
            'kategori_id' => null,
            'tipe' => 'pemasukan',
            'nominal' => $amount,
            'keterangan' => 'Deposit Saldo',
            'tanggal' => now(),
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil.');
    }

    public function withdraw(Request $request, Dompet $dompet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        // Pastikan ambil ulang data saldo terbaru
        $dompet->refresh();

        // Pastikan amount positif
        $amount = abs($request->amount);

        if ($dompet->saldo < $amount) {
            return redirect()->back()->with('error', 'Saldo tidak cukup.');
        }

        // Kurangi saldo
        $dompet->saldo -= $amount;
        $dompet->save();

        Transaksi::create([
            'dompet_id' => $dompet->id,
            'kategori_id' => null,
            'tipe' => 'pengeluaran',
            'nominal' => $amount, // nominal selalu positif
            'keterangan' => 'Withdraw Saldo',
            'tanggal' => now(),
        ]);

        return redirect()->back()->with('success', 'Withdraw berhasil.');
    }
}
