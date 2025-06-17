<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('tabungan.index', compact('tabungans'));
    }

    public function create()
    {
        $users = User::all();
        return view('tabungan.form', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama'    => 'required|string|max:255',
            'saldo'   => 'required|numeric|min:0',
            'target'  => 'nullable|numeric|min:0',
        ]);

        Tabungan::create($request->all());

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
        ]);

        $tabungan = Tabungan::findOrFail($id);

        // if (auth()->id() !== $tabungan->user_id) {
        //     return response()->json([
        //         'error' => true,
        //         'message' => 'Unauthorized'
        //     ], 200);
        // }

        $tabungan->saldo += $request->amount;
        $tabungan->save();

        return response()->json([
            'saldo' => $tabungan->saldo,
            'target' => $tabungan->target,
            'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
        ], 200);
    }
}
