<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index()
    {
        $pemasukan = Pemasukan::with(['user', 'dompet', 'kategori'])->latest()->get();
        return view('pemasukan.index', compact('pemasukan'));
    }

    public function create()
    {
        return view('pemasukan.create');
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

        Pemasukan::create($request->all());

        return redirect()->route('pemasukan.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Pemasukan $pemasukan)
    {
        return view('pemasukan.edit', compact('pemasukan'));
    }

    public function update(Request $request, Pemasukan $pemasukan)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'dompet_id' => 'nullable|exists:dompets,id',
            'kategori_id' => 'nullable|exists:kategori,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $pemasukan->update($request->all());

        return redirect()->route('pemasukan.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Pemasukan $pemasukan)
    {
        $pemasukan->delete();

        return redirect()->route('pemasukan.index')->with('success', 'Berhasil dihapus');
    }
}
