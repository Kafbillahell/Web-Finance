<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with(['user', 'dompet', 'kategori'])->latest()->get();
        return view('pengeluaran.index', compact('pengeluaran'));
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'dompet_id' => 'nullable|exists:dompets,id',
            'kategori_id' => 'nullable|exists:kategori,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'dompet_id' => 'nullable|exists:dompets,id',
            'kategori_id' => 'nullable|exists:kategori,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $pengeluaran->update($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Berhasil dihapus');
    }
}
