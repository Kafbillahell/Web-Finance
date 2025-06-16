<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use Illuminate\Http\Request;

class DompetController extends Controller
{
    public function index()
    {
        $dompets = Dompet::with('user')->latest()->get();
        return view('dompets.index', compact('dompets'));
    }

    public function create()
    {
        return view('dompets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        Dompet::create($request->all());

        return redirect()->route('dompets.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Dompet $dompet)
    {
        return view('dompets.edit', compact('dompet'));
    }

    public function update(Request $request, Dompet $dompet)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'saldo' => 'nullable|numeric',
        ]);

        $dompet->update($request->all());

        return redirect()->route('dompets.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Dompet $dompet)
    {
        $dompet->delete();

        return redirect()->route('dompets.index')->with('success', 'Berhasil dihapus');
    }
}
