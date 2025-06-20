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
        $user = Auth::user();
        $search = $request->search;

        $query = Tabungan::with('user')
            ->where('user_id', $user->id);

        // Apply search filter if search term is provided and has at least 2 characters
        if ($search && strlen($search) >= 1) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tabungans = $query->latest()
            ->paginate(3); // Adjust pagination as needed

        $dompets = Dompet::where('user_id', $user->id)->get();

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'tabungans' => $tabungans->items(), // Get the actual items for JSON
                'pagination' => (string) $tabungans->links('vendor.pagination.bootstrap-4'), // Render pagination links as string
                'dompets' => $dompets->toArray(), // Pass dompets as an array for the modal
            ]);
        }

        return view('tabungan.index', [
            'tabungans' => $tabungans,
            'dompets' => $dompets
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
        $dompets = Dompet::where('user_id', Auth::id())->get();
        return view('tabungan.form', compact('users', 'dompets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'saldo'   => 'required|numeric|min:0',
            'target'  => 'nullable|numeric|min:0',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        // Create tabungan
        $tabungan = Tabungan::create([
            'user_id' => Auth::user()->id,
            'nama' => $request->nama,
            'saldo' => $request->saldo,
            'target' => $request->target
        ]);

        // Create transaksi tabungan
        TransaksiTabungan::create([
            'tabungan_id' => $tabungan->id,
            'dompet_id' => $request->dompet_id,
            'nominal' => $request->saldo,
            'tipe' => TransaksiTabungan::TYPE_DEPOSIT,
            'keterangan' => 'Pembuatan tabungan baru: ' . $request->nama,
        ]);

        return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil ditambahkan dan transaksi berhasil dicatat.');
    }

    public function edit(Tabungan $tabungan)
    {
        $users = User::all();
        $dompets = Dompet::where('user_id', Auth::id())->get();
        return view('tabungan.form', compact('tabungan', 'users', 'dompets'));
    }

    public function update(Request $request, Tabungan $tabungan)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'saldo'   => 'required|numeric|min:0',
            'target'  => 'nullable|numeric|min:0',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        // Calculate the difference in saldo
        $saldoDifference = $request->saldo - $tabungan->saldo;

        // Update tabungan
        $tabungan->update([
            'user_id' => Auth::id(),
            'nama'    => $request->nama,
            'saldo'   => $request->saldo,
            'target'  => $request->target,
        ]);

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
        $dompet = Dompet::where('id', $request->dompet_id)->where('user_id', Auth::id())->first();

        if (!$dompet) {
            return response()->json(['error' => true, 'message' => 'Dompet tidak ditemukan atau bukan milik Anda.'], 404);
        }

        if ($dompet->saldo < $request->amount) {
            return response()->json(['error' => true, 'message' => 'Saldo dompet tidak cukup.'], 400);
        }

        $tabungan->saldo += $request->amount;
        $tabungan->save();

        $dompet->saldo -= $request->amount;
        $dompet->save();

        // Catat ke transaksi_tabungan
        TransaksiTabungan::create([
            'tabungan_id' => $tabungan->id,
            'dompet_id' => $request->dompet_id,
            'nominal'     => $request->amount,
            'tipe' => TransaksiTabungan::TYPE_DEPOSIT,
            'keterangan'  => 'Tambah saldo',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saldo berhasil ditambahkan!',
            'saldo' => $tabungan->saldo,
            'target' => $tabungan->target,
            'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
        ]);
    }

    public function withdrawSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'dompet_id' => 'required|exists:dompets,id'
        ]);

        $tabungan = Tabungan::findOrFail($id);
        $dompet = Dompet::where('id', $request->dompet_id)->where('user_id', Auth::id())->first();

        if (!$dompet) {
            return response()->json(['error' => true, 'message' => 'Dompet tidak ditemukan atau bukan milik Anda.'], 404);
        }

        if ($request->amount > $tabungan->saldo) {
            return response()->json([
                'error' => true,
                'message' => 'Jumlah penarikan melebihi saldo tabungan tersedia.'
            ], 400);
        }

        $tabungan->saldo -= $request->amount;
        $tabungan->save();

        $dompet->saldo += $request->amount;
        $dompet->save();

        // Catat ke transaksi_tabungan
        TransaksiTabungan::create([
            'tabungan_id' => $tabungan->id,
            'dompet_id' => $request->dompet_id,
            'nominal'     => $request->amount,
            'tipe' => TransaksiTabungan::TYPE_WITHDRAWAL,
            'keterangan'  => 'Tarik saldo'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saldo berhasil ditarik!',
            'saldo' => $tabungan->saldo,
            'target' => $tabungan->target,
            'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
        ]);
    }
}