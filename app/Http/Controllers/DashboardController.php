<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dompet = Dompet::where('user_id', $user->id)->get();
        $totalSaldo = $dompet->sum('saldo');

        return view('dashboard', compact('user', 'dompet', 'totalSaldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'dompet_id' => 'required|exists:dompets,id',
            'tipe' => 'required|in:deposit,withdraw',
        ]);

        $dompet = Dompet::where('id', $request->dompet_id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        if ($request->tipe === 'withdraw') {
            if ($dompet->saldo < $request->amount) {
                return redirect()->route('dashboard')->with('error', 'Saldo tidak mencukupi.');
            }
            $dompet->saldo -= $request->amount;
        } else {
            $dompet->saldo += $request->amount;
        }

        $dompet->save();

        return redirect()->route('dashboard')->with('success', ucfirst($request->tipe) . ' berhasil.');
    }
}
