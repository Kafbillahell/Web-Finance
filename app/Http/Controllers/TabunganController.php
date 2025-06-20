<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Tabungan;
use App\Models\TransaksiTabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TabunganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;

        $query = Tabungan::with('user')
            ->where('user_id', $user->id);

        if ($search && strlen($search) >= 1) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tabungans = $query->latest()
            ->paginate(3);

        $dompets = Dompet::where('user_id', $user->id)->get();

        if ($request->ajax()) {
            return response()->json([
                'tabungans' => $tabungans->items(),
                'pagination' => (string) $tabungans->links('vendor.pagination.bootstrap-4'),
                'dompets' => $dompets->toArray(),
            ]);
        }

        return view('tabungan.index', [
            'tabungans' => $tabungans,
            'dompets' => $dompets
        ]);
    }

    public function history(Request $request)
    {
        $query = TransaksiTabungan::with(['tabungan', 'dompet'])
                    ->whereHas('tabungan', function ($q) {
                        $q->where('user_id', Auth::id());
                    });

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
        $validatedData = $request->validate([
            'nama'      => 'required|string|max:255',
            'saldo'     => 'required|numeric|min:0',
            'target'    => 'nullable|numeric|min:0',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        DB::beginTransaction();

        try {
            $dompet = Dompet::where('id', $validatedData['dompet_id'])
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

            if ($dompet->saldo < $validatedData['saldo']) {
                DB::rollBack();
                return back()->withErrors(['saldo' => 'Saldo di dompet tidak cukup untuk memulai tabungan ini.'])->withInput();
            }

            $dompet->saldo -= $validatedData['saldo'];
            $dompet->save();

            $tabungan = Tabungan::create([
                'user_id' => Auth::id(),
                'nama'    => $validatedData['nama'],
                'saldo'   => $validatedData['saldo'],
                'target'  => $validatedData['target'],
            ]);

            TransaksiTabungan::create([
                'tabungan_id' => $tabungan->id,
                'dompet_id'   => $validatedData['dompet_id'],
                'nominal'     => $validatedData['saldo'],
                'tipe'        => TransaksiTabungan::TYPE_DEPOSIT,
                'keterangan'  => 'Pembuatan tabungan baru: ' . $validatedData['nama'],
            ]);

            DB::commit();

            return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil ditambahkan dan saldo dompet telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan tabungan. ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Tabungan $tabungan)
    {
        if ($tabungan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        $dompets = Dompet::where('user_id', Auth::id())->get();
        return view('tabungan.form', compact('tabungan', 'users', 'dompets'));
    }

    public function update(Request $request, Tabungan $tabungan)
    {
        if ($tabungan->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit tabungan ini.');
        }

        $validatedData = $request->validate([
            'nama'      => 'required|string|max:255',
            'saldo'     => 'required|numeric|min:0',
            'target'    => 'nullable|numeric|min:0',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        DB::beginTransaction();

        try {
            $oldSaldo = $tabungan->saldo;
            $oldDompetId = $tabungan->dompet_id;

            $currentDompet = Dompet::where('id', $oldDompetId)
                                   ->where('user_id', Auth::id())
                                   ->firstOrFail();

            $newDompet = Dompet::where('id', $validatedData['dompet_id'])
                               ->where('user_id', Auth::id())
                               ->firstOrFail();

            if ($oldDompetId != $validatedData['dompet_id']) {
                $currentDompet->saldo += $oldSaldo;
                $currentDompet->save();

                if ($newDompet->saldo < $validatedData['saldo']) {
                    DB::rollBack();
                    return back()->withErrors(['saldo' => 'Saldo di dompet baru tidak cukup untuk jumlah tabungan ini.'])->withInput();
                }
                $newDompet->saldo -= $validatedData['saldo'];
                $newDompet->save();

                if ($oldSaldo > 0) {
                    TransaksiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'dompet_id'   => $oldDompetId,
                        'nominal'     => $oldSaldo,
                        'tipe'        => TransaksiTabungan::TYPE_RETURN,
                        'keterangan'  => 'Pengembalian saldo dari tabungan ke dompet lama karena perubahan dompet.',
                    ]);
                }
                TransaksiTabungan::create([
                    'tabungan_id' => $tabungan->id,
                    'dompet_id'   => $validatedData['dompet_id'],
                    'nominal'     => $validatedData['saldo'],
                    'tipe'        => TransaksiTabungan::TYPE_DEPOSIT,
                    'keterangan'  => 'Penambahan saldo ke tabungan dari dompet baru karena perubahan dompet.',
                ]);

            } else {
                $saldoDifference = $validatedData['saldo'] - $oldSaldo;

                if ($saldoDifference > 0) {
                    if ($currentDompet->saldo < $saldoDifference) {
                        DB::rollBack();
                        return back()->withErrors(['saldo' => 'Saldo di dompet tidak cukup untuk menambah tabungan.'])->withInput();
                    }
                    $currentDompet->saldo -= $saldoDifference;
                    $currentDompet->save();
                    TransaksiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'dompet_id'   => $validatedData['dompet_id'],
                        'nominal'     => $saldoDifference,
                        'tipe'        => TransaksiTabungan::TYPE_DEPOSIT,
                        'keterangan'  => 'Penyesuaian saldo tabungan: penambahan.',
                    ]);
                } elseif ($saldoDifference < 0) {
                    $currentDompet->saldo += abs($saldoDifference);
                    $currentDompet->save();
                    TransaksiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'dompet_id'   => $validatedData['dompet_id'],
                        'nominal'     => abs($saldoDifference),
                        'tipe'        => TransaksiTabungan::TYPE_WITHDRAWAL,
                        'keterangan'  => 'Penyesuaian saldo tabungan: penarikan.',
                    ]);
                }
            }

            $tabungan->update([
                'user_id' => Auth::id(),
                'nama'    => $validatedData['nama'],
                'saldo'   => $validatedData['saldo'],
                'target'  => $validatedData['target'],
            ]);

            DB::commit();

            return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil diperbarui dan saldo dompet telah disesuaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui tabungan. ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Tabungan $tabungan)
    {
        if ($tabungan->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus tabungan ini.');
        }

        DB::beginTransaction();
        try {
            $lastDeposit = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                                             ->where('tipe', TransaksiTabungan::TYPE_DEPOSIT)
                                             ->latest()
                                             ->first();

            if ($tabungan->saldo > 0 && $lastDeposit && $lastDeposit->dompet_id) {
                $dompet = Dompet::where('id', $lastDeposit->dompet_id)
                                 ->where('user_id', Auth::id())
                                 ->first();
                if ($dompet) {
                    $dompet->saldo += $tabungan->saldo;
                    $dompet->save();

                    TransaksiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'dompet_id'   => $dompet->id,
                        'nominal'     => $tabungan->saldo,
                        'tipe'        => TransaksiTabungan::TYPE_RETURN,
                        'keterangan'  => 'Pengembalian saldo dari penutupan tabungan: ' . $tabungan->nama,
                    ]);
                }
            }

            TransaksiTabungan::where('tabungan_id', $tabungan->id)->delete();

            $tabungan->delete();

            DB::commit();

            return redirect()->route('tabungan.index')->with('success', 'Tabungan berhasil dihapus dan saldo telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus tabungan. ' . $e->getMessage());
        }
    }

    public function addSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'dompet_id' => 'required|exists:dompets,id',
        ]);

        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $dompet = Dompet::where('id', $request->dompet_id)->where('user_id', Auth::id())->firstOrFail();

            if ($dompet->saldo < $request->amount) {
                DB::rollBack();
                return response()->json(['error' => true, 'message' => 'Saldo dompet tidak cukup.'], 400);
            }

            $tabungan->saldo += $request->amount;
            $tabungan->save();

            $dompet->saldo -= $request->amount;
            $dompet->save();

            TransaksiTabungan::create([
                'tabungan_id' => $tabungan->id,
                'dompet_id' => $request->dompet_id,
                'nominal'     => $request->amount,
                'tipe'        => TransaksiTabungan::TYPE_DEPOSIT,
                'keterangan'  => 'Tambah saldo',
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Saldo berhasil ditambahkan!',
                'saldo' => $tabungan->saldo,
                'target' => $tabungan->target,
                'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => 'Gagal menambahkan saldo. ' . $e->getMessage()], 500);
        }
    }

    public function withdrawSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'dompet_id' => 'required|exists:dompets,id'
        ]);

        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $dompet = Dompet::where('id', $request->dompet_id)->where('user_id', Auth::id())->firstOrFail();

            if ($request->amount > $tabungan->saldo) {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Jumlah penarikan melebihi saldo tabungan tersedia.'
                ], 400);
            }

            $tabungan->saldo -= $request->amount;
            $tabungan->save();

            $dompet->saldo += $request->amount;
            $dompet->save();

            TransaksiTabungan::create([
                'tabungan_id' => $tabungan->id,
                'dompet_id' => $request->dompet_id,
                'nominal'     => $request->amount,
                'tipe'        => TransaksiTabungan::TYPE_WITHDRAWAL,
                'keterangan'  => 'Tarik saldo'
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Saldo berhasil ditarik!',
                'saldo' => $tabungan->saldo,
                'target' => $tabungan->target,
                'saldo_formatted' => number_format($tabungan->saldo, 2, ',', '.')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => 'Gagal menarik saldo. ' . $e->getMessage()], 500);
        }
    }
}