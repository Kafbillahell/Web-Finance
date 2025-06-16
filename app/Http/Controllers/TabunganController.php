<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    public function index()
    {
        $tabungans = Tabungan::with('user')->latest()->paginate(10);
        return view('tabungan.index', compact('tabungans'));
    }

    public function create()
    {
        $users = User::all();
        return view('tabungan.create', compact('users'));
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
        return view('tabungan.edit', compact('tabungan', 'users'));
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
}
