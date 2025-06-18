<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Tabungan;
use App\Models\TransaksiTabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Tabungan::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tabungans = $query->latest()->paginate(10);
        return view('tabungan.index', [
            'tabungans' => $tabungans,
            'dompets' => Dompet::all()
        ]);
    }

    public function history(Request $request)
    {
        $query = TransaksiTabungan::with(['tabungan', 'dompet']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('tabungan', function ($t) use ($search) {
                        $t->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $transaksis = $query->latest()->paginate(10);
        return view('history-tabungan.index', compact('transaksis'));
    }

    public function create()
    {
        $users = User::all();
        return view('tabungan.form', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'nama'    => 'required|string|max:255',
            'saldo'   => 'required|numeric|min:0',
            'target'  => 'nullable|numeric|min:0',
        ]);

        Tabungan::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'saldo' => $request->saldo,
            'target' => $request->target
        ]);

        return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil ditambahkan.');
    }

    public function edit(Tabungan $tabungan)
    {
        $users = User::all();
        return view('tabungan.form', compact('tabungan', 'users'));
    }

    public function update(Request $request, Tabungan $tabungan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama'    => 'required|string|max:255',
            'saldo'   => 'required|numeric|min:0',
            'target'  => 'nullable|numeric|min:0',
        ]);

        $tabungan->update($request->all());

        return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil diperbarui.');
    }

    public function destroy(Tabungan $tabungan)
    {
        $tabungan->delete();
        return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil dihapus.');
    }

    public function addSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        $tabungan = Tabungan::findOrFail($id);

        $tabungan->saldo += $request->amount;
        $tabungan->save();

        // Catat ke tabungan_transaksi
        TransaksiTabungan::create([

            'tabungan_id' => $tabungan->id,
            'dompet_id' => $request->dompet_id,
            'nominal'     => $request->amount,
            'tipe' => TransaksiTabungan::TYPE_DEPOSIT,
            'keterangan'  => 'Tambah saldo',
        ]);

        $dompet = Dompet::findOrFail($request->dompet_id);
        $dompet->saldo -= $request->amount;
        $dompet->save();

        if($dompet->saldo > $request->amount){
            return back()->with('error', 'Saldo dompet anda tidak cukup.');
        }

        return response()->json([
            'saldo' => $tabungan->saldo,
            'target' => $tabungan->target,
            'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
        ], 200);
    }

    public function withdrawSaldo(Request $request, $id)
    {

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'dompet_id' => 'required|exists:dompets,id'
        ]);

        $tabungan = Tabungan::findOrFail($id);

        if ($request->amount > $tabungan->saldo) {
            return response()->json([
                'error' => true,
                'message' => 'Jumlah penarikan melebihi saldo tersedia'
            ], 400);
        }

        $tabungan->saldo -= $request->amount;
        $tabungan->save();

        // Catat ke tabungan_transaksi
        TransaksiTabungan::create([

            'tabungan_id' => $tabungan->id,
            'dompet_id' => $request->dompet_id,
            'nominal'     => $request->amount,
            'tipe' => TransaksiTabungan::TYPE_WITHDRAWAL,
            'keterangan'  => 'Tarik saldo'
        ]);

        $dompet = Dompet::findOrFail($request->dompet_id);
        $dompet->saldo += $request->amount;
        $dompet->save();


        return response()->json([
            'saldo' => $tabungan->saldo,
            'target' => $tabungan->target,
            'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
        ], 200);
    }
}
